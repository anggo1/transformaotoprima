<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stock Opname (konsep baru): snapshot → hitung (input/scan/Excel) → review & hitung ulang → setujui → stok disesuaikan.
 * Menu Stock Opname lama tetap ada; dokumen baru ini yang mengubah stok secara terkendali.
 */
class OpnameStok extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_opname', 'warehouse/Mod_whutil', 'warehouse/Mod_lapgudang'));
        $this->wajib('view');
    }

    private function pesan($tipe, $teks) { $this->session->set_flashdata('wh_msg', array($tipe, $teks)); }
    private function kembali($id = null) { redirect($id ? 'OpnameStok/lihat/' . (int) $id : 'OpnameStok'); }
    private function admin() { return in_array((int) $this->session->userdata('id_level'), array(1, 12), true) || $this->boleh('delete'); }

    public function index()
    {
        $st = (string) $this->input->get('status');
        $data = array('page' => 'Stock Opname', 'judul' => 'Gudang', 'rows' => $this->Mod_opname->daftar(isset(Mod_opname::$STATUS[$st]) ? $st : ''),
            'status' => $st, 'set' => $this->Mod_whutil->setting(), 'kategori' => $this->Mod_lapgudang->kategori_list(),
            'kelompok' => $this->db->distinct()->select('kelompok')->where("kelompok <> ''", null, false)->order_by('kelompok')->get('tbl_wh_barang')->result(),
            'lok_user' => Mod_lapgudang::lok((string) $this->session->userdata('lokasi')), 'admin' => $this->admin(),
            'akun' => $this->db->table_exists('tbl_acc_akun') ? $this->db->select('kode_akun, nama_akun')->order_by('kode_akun')->get('tbl_acc_akun')->result() : array(),
            'bisa_tambah' => $this->boleh('add'));
        $this->template->load('layoutbackend', 'warehouse/opname/index', $data);
    }

    public function buat()
    {
        $this->wajib('add');
        $id = $this->Mod_opname->buat($this->input->post());
        if ( ! $id) { $this->pesan('danger', $this->Mod_opname->error); return $this->kembali(); }
        if ($this->input->post('langsung')) {
            $n = $this->Mod_opname->mulai($id);
            if ($n === false) $this->pesan('warning', 'Dokumen dibuat, tetapi: ' . $this->Mod_opname->error);
            else $this->pesan('success', 'Dokumen opname dibuat. ' . nf($n) . ' barang siap dihitung — stok sistem sudah dikunci (snapshot).');
        } else $this->pesan('success', 'Dokumen opname dibuat sebagai Draft.');
        $this->kembali($id);
    }

    public function lihat($id = 0)
    {
        $h = $this->Mod_opname->get($id);
        if ( ! $h) show_404();
        $f = (string) $this->input->get('f');
        $data = array('page' => 'Stock Opname ' . $h->no_so, 'judul' => 'Stock Opname', 'h' => $h, 'f' => $f,
            'rows' => $h->status === 'DRAFT' ? array() : $this->Mod_opname->detail($h->id, in_array($f, array('belum', 'selisih'), true) ? $f : ''),
            'bisa_ubah' => $this->boleh('edit') || $this->boleh('add'), 'admin' => $this->admin());
        $this->template->load('layoutbackend', 'warehouse/opname/lihat', $data);
    }

    public function mulai($id = 0)
    {
        $this->wajib('add');
        $n = $this->Mod_opname->mulai($id);
        if ($n === false) $this->pesan('danger', $this->Mod_opname->error);
        else $this->pesan('success', nf($n) . ' barang siap dihitung. Stok sistem saat ini dikunci sebagai pembanding.');
        $this->kembali($id);
    }

    public function simpan($id = 0)
    {
        if ( ! $this->boleh('edit') && ! $this->boleh('add')) $this->wajib('edit');
        $n = $this->Mod_opname->simpan_hitung($id, (array) $this->input->post('q'));
        if ($n === false) $this->pesan('danger', $this->Mod_opname->error); else $this->pesan('success', nf($n) . ' baris hasil hitung disimpan.');
        redirect('OpnameStok/lihat/' . (int) $id . ($this->input->post('f') ? '?f=' . rawurlencode($this->input->post('f')) : ''));
    }

    /** AJAX scan: no_part, qty, mode(tambah|set) */
    public function scan($id = 0)
    {
        if ( ! $this->boleh('edit') && ! $this->boleh('add')) $this->wajib('edit');
        $kode = trim((string) $this->input->post('kode'));
        // label QR berisi "TOP|no_part|..." atau no_part saja
        if (strpos($kode, 'TOP|') === 0) { $x = explode('|', $kode); $kode = isset($x[1]) ? $x[1] : ''; }
        $qty = $this->input->post('qty'); $qty = $qty === null || $qty === '' ? 1 : (float) str_replace(',', '.', $qty);
        $d = $this->Mod_opname->scan($id, $kode, $qty, $this->input->post('mode') === 'set' ? 'set' : 'tambah');
        if ( ! $d) $this->json(array('ok' => false, 'pesan' => $this->Mod_opname->error));
        $h = $this->Mod_opname->get($id);
        $this->json(array('ok' => true, 'id' => (int) $d->id, 'no_part' => $d->no_part, 'nama' => $d->nama_part, 'rak' => $d->rak, 'fisik' => (float) $d->fisik,
            'snapshot' => $h->buta === 'Y' && $h->status === 'HITUNG' ? null : (float) $d->stok_snapshot, 'satuan' => $d->satuan));
    }

    public function status($id = 0, $ke = '')
    {
        $this->wajib('edit');
        $ok = $this->Mod_opname->ubah_status($id, strtoupper($ke));
        if ( ! $ok) $this->pesan('danger', $this->Mod_opname->error);
        else $this->pesan('success', array('REVIEW' => 'Penghitungan ditutup. Periksa selisih dan lakukan hitung ulang bila perlu.', 'HITUNG' => 'Dokumen dibuka kembali untuk penghitungan.', 'BATAL' => 'Dokumen dibatalkan — stok tidak berubah.')[strtoupper($ke)]);
        $this->kembali($id);
    }

    public function setujui($id = 0)
    {
        if ( ! $this->admin()) $this->wajib('delete');
        $r = $this->Mod_opname->setujui($id, (bool) $this->input->post('nol'));
        if ($r === false) $this->pesan('danger', $this->Mod_opname->error);
        else $this->pesan('success', 'Opname disetujui: ' . nf($r['baris']) . ' barang disesuaikan (lebih ' . nf_acc($r['lebih']) . ', kurang ' . nf_acc($r['kurang']) . ')' . ($r['jurnal'] ? ', jurnal ' . $r['jurnal'] . ' dibuat.' : '.'));
        $this->kembali($id);
    }

    /** lembar hitung xlsx (untuk dihitung di lapangan lalu diimpor kembali) */
    public function ekspor($id = 0)
    {
        $this->wajib('print');
        $h = $this->Mod_opname->get($id);
        if ( ! $h || $h->status === 'DRAFT') show_404();
        $buta = $h->buta === 'Y' && $h->status === 'HITUNG';
        $rows = array(array_merge(array('no_part', 'nama_part', 'rak', 'satuan'), $buta ? array() : array('stok_sistem'), array('fisik', 'catatan')));
        foreach ($this->Mod_opname->detail($h->id) as $d) {
            $rows[] = array_merge(array($d->no_part, $d->nama_part, $d->rak, $d->satuan), $buta ? array() : array((float) $d->stok_snapshot), array($d->fisik === null ? '' : (float) $d->fisik, ''));
        }
        $this->load->library('xlsx');
        $bin = $this->xlsx->tulis($rows, 'Lembar hitung', $buta ? array(20, 40, 12, 10, 12, 30) : array(20, 40, 12, 10, 12, 12, 30));
        if ($bin === false) show_error($this->xlsx->error);
        $this->output->set_content_type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->set_header('Content-Disposition: attachment; filename="Lembar_hitung_' . $h->no_so . '.xlsx"')->set_output($bin);
    }

    public function impor($id = 0)
    {
        if ( ! $this->boleh('edit') && ! $this->boleh('add')) $this->wajib('edit');
        if (empty($_FILES['file']['tmp_name'])) { $this->pesan('danger', 'Pilih file Excel/CSV hasil hitung.'); return $this->kembali($id); }
        $this->load->library('xlsx');
        $rows = $this->xlsx->baca($_FILES['file']['tmp_name'], $_FILES['file']['name']);
        if ($rows === false) { $this->pesan('danger', $this->xlsx->error); return $this->kembali($id); }
        $r = $this->Mod_opname->impor_hitung($id, $rows);
        if ($r === false) $this->pesan('danger', $this->Mod_opname->error);
        else $this->pesan($r['tidak'] ? 'warning' : 'success', nf($r['n']) . ' baris hasil hitung diimpor.' . ($r['tidak'] ? ' ' . count($r['tidak']) . ' no. part tidak ada di daftar opname: ' . html_escape(implode(', ', array_slice($r['tidak'], 0, 10))) . (count($r['tidak']) > 10 ? ', …' : '') : ''));
        $this->kembali($id);
    }

    /** cetak: lembar hitung (QR per baris) atau berita acara hasil */
    public function cetak($id = 0, $jenis = 'lembar')
    {
        $this->wajib('print');
        $h = $this->Mod_opname->get($id);
        if ( ! $h || $h->status === 'DRAFT') show_404();
        $apl = $this->db->get('aplikasi', 1)->row();
        $this->load->view('warehouse/opname/cetak', array('h' => $h, 'rows' => $this->Mod_opname->detail($h->id, $jenis === 'hasil' ? 'selisih' : ''),
            'semua' => $jenis === 'hasil' ? $this->Mod_opname->detail($h->id) : array(),
            'jenis' => $jenis === 'hasil' ? 'hasil' : 'lembar', 'apl' => $apl));
    }

    public function pengaturan()
    {
        if ( ! $this->admin()) $this->wajib('delete');
        $p = $this->input->post();
        $this->Mod_whutil->simpan_setting(array('so_toleransi' => max(0, min(100, (float) (isset($p['so_toleransi']) ? $p['so_toleransi'] : 0))),
            'so_buta' => ! empty($p['so_buta']) ? 'Y' : 'N', 'so_jurnal' => ! empty($p['so_jurnal']) ? 'Y' : 'N',
            'akun_persediaan' => trim((string) (isset($p['akun_persediaan']) ? $p['akun_persediaan'] : '')),
            'akun_selisih_persediaan' => trim((string) (isset($p['akun_selisih_persediaan']) ? $p['akun_selisih_persediaan'] : ''))));
        $this->pesan('success', 'Pengaturan stock opname disimpan.');
        $this->kembali();
    }
}
