<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Aset Tetap: daftar aset, penyusutan otomatis bulanan, pelepasan aset */
class AsetTetap extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('accounting/Mod_acctrx');
        $this->load->library('akuntansi');
        $this->wajib('view');
    }

    public function index()
    {
        $status = (string) $this->input->get('status');
        $rows = $this->Mod_acctrx->aset_list($status);
        $periodeSusut = $this->db->select_max('periode', 'p')->get('tbl_acc_aset_susut')->row();
        $this->template->load('layoutbackend', 'accounting/trx/aset', array(
            'page' => 'Aset Tetap', 'judul' => 'Aset Tetap', 'rows' => $rows, 'status' => $status,
            'periodeTerakhir' => $periodeSusut ? $periodeSusut->p : '',
            'boleh' => array('add' => $this->boleh('add'), 'edit' => $this->boleh('edit'), 'delete' => $this->boleh('delete')),
        ));
    }

    public function form($id = 0)
    {
        $this->wajib($id ? 'edit' : 'add');
        $a = $id ? $this->Mod_acctrx->aset_get($id) : null;
        $this->load->view('accounting/trx/aset_form', array('a' => $a, 'akun' => $this->akuntansi->akun(),
            'def' => array('akum' => $this->akuntansi->setting('akun_akumulasi_default'), 'beban' => $this->akuntansi->setting('akun_beban_susut_default')),
            'kelompok' => $this->db->distinct()->select('kelompok_aset')->where('kelompok_aset !=', '')->get('tbl_acc_aset')->result()));
    }

    public function simpan()
    {
        $p = $this->input->post();
        $this->wajib( ! empty($p['id']) ? 'edit' : 'add');
        $keys = array('id', 'kode_aset', 'nama_aset', 'kelompok_aset', 'lokasi', 'tgl_perolehan', 'tgl_mulai_susut', 'harga_perolehan', 'nilai_residu',
            'umur_bulan', 'metode', 'akun_aset', 'akun_akumulasi', 'akun_beban', 'akun_lawan_perolehan', 'keterangan');
        $d = array(); foreach ($keys as $k) $d[$k] = isset($p[$k]) ? $p[$k] : '';
        if (isset($p['umur_tahun']) && $p['umur_tahun'] !== '' && empty($p['umur_bulan'])) $d['umur_bulan'] = (int) round(to_number($p['umur_tahun']) * 12);
        $id = $this->Mod_acctrx->aset_simpan($d, ! empty($p['jurnal_perolehan']));
        $this->json($id ? array('status' => true, 'pesan' => 'Data aset tersimpan.') : array('status' => false, 'pesan' => $this->Mod_acctrx->error));
    }

    public function detail($id = 0)
    {
        $a = $this->Mod_acctrx->aset_get($id);
        if ( ! $a) show_404();
        // proyeksi jadwal penyusutan
        $jadwal = array(); $akm = 0; $done = array();
        foreach ($a->susut as $s) { $done[$s->periode] = $s; }
        $p = substr($a->tgl_mulai_susut, 0, 7); $i = 0;
        while ($i < 600) {
            if (isset($done[$p])) { $v = (float) $done[$p]->jumlah; $st = 'Diposting'; }
            else { $v = $this->Mod_acctrx->hitung_susut($a, $akm); $st = 'Proyeksi'; }
            if ($v <= 0) break;
            $akm += $v;
            $jadwal[] = (object) array('periode' => $p, 'jumlah' => $v, 'akumulasi' => $akm, 'nilai_buku' => $a->harga_perolehan - $akm, 'status' => $st);
            $p = date('Y-m', strtotime($p . '-01 +1 month')); $i++;
        }
        $this->load->view('accounting/trx/aset_detail', array('a' => $a, 'jadwal' => $jadwal, 'akun' => $this->akuntansi->akun()));
    }

    public function proses_susut()
    {
        $this->wajib('add');
        $per = (string) $this->input->post('periode');
        $n = $this->Mod_acctrx->proses_susut($per);
        if ($n === false) $this->json(array('status' => false, 'pesan' => $this->Mod_acctrx->error));
        $this->json(array('status' => true, 'pesan' => $n ? $n . ' baris penyusutan diposting s/d ' . $per . '.' : 'Tidak ada penyusutan yang perlu diproses s/d ' . $per . '.'));
    }

    public function batal_susut()
    {
        $this->wajib('delete');
        $ok = $this->Mod_acctrx->batal_susut((string) $this->input->post('periode'));
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Penyusutan periode tersebut dibatalkan.' : $this->Mod_acctrx->error));
    }

    public function form_lepas($id = 0)
    {
        $this->wajib('edit');
        $a = $this->Mod_acctrx->aset_get($id);
        if ( ! $a) show_404();
        $this->load->view('accounting/trx/aset_lepas', array('a' => $a, 'akun' => $this->akuntansi->akun()));
    }

    public function lepas()
    {
        $this->wajib('edit');
        $p = $this->input->post();
        $ok = $this->Mod_acctrx->aset_lepas(isset($p['id']) ? $p['id'] : 0, isset($p['tanggal']) ? $p['tanggal'] : '',
            isset($p['nilai']) ? $p['nilai'] : 0, isset($p['akun_kas']) ? $p['akun_kas'] : '', isset($p['status']) ? $p['status'] : 'DIHAPUS');
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Pelepasan aset tersimpan & dijurnal.' : $this->Mod_acctrx->error));
    }

    public function hapus()
    {
        $this->wajib('delete');
        $ok = $this->Mod_acctrx->aset_hapus($this->input->post('id'));
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Aset dihapus.' : $this->Mod_acctrx->error));
    }
}
