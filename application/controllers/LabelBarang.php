<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cetak label QR barang & halaman Scan.
 * QR dibuat di browser (SVG, assets/js/top-qr.js) — tidak lagi menulis file PNG ke assets/img_qr,
 * sehingga tidak gagal karena no. part berisi "/" atau spasi, folder tidak bisa ditulis, atau gambar belum termuat saat print.
 */
class LabelBarang extends MY_Controller
{
    /** kode => [nama, lebar mm, tinggi mm, kolom per halaman (0 = printer label/roll), baris per halaman, ukuran kertas] */
    public static $TEMPLATE = array(
        'T5030'  => array('Printer label 50 × 30 mm (thermal, 1 label per baris)', 50, 30, 0, 0, '50mm 30mm'),
        'T4020'  => array('Printer label 40 × 20 mm (thermal, kecil)', 40, 20, 0, 0, '40mm 20mm'),
        'T7040'  => array('Printer label 70 × 40 mm (rak / bin)', 70, 40, 0, 0, '70mm 40mm'),
        'A4_3x8' => array('Kertas A4 stiker 3 × 8 (24 label, 70 × 37 mm)', 70, 37.1, 3, 8, 'A4'),
        'A4_4x10'=> array('Kertas A4 stiker 4 × 10 (40 label, 52,5 × 29,7 mm)', 52.5, 29.7, 4, 10, 'A4'),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_lapgudang', 'warehouse/Mod_whutil'));
        $this->wajib('view');
    }

    private function lok()
    {
        $l = (string) $this->input->get_post('lok');
        if (isset(Mod_lapgudang::$LOKASI[$l])) return $l;
        $s = Mod_lapgudang::lok((string) $this->session->userdata('lokasi'));
        return $s ?: 'cbt';
    }

    /** pilih barang yang akan dicetak */
    public function index()
    {
        $lok = $this->lok(); $sumber = (string) $this->input->get('sumber'); $q = trim((string) $this->input->get('q'));
        $rows = array(); $judul = '';
        $B = $this->Mod_lapgudang->barang();
        if ($sumber === 'masuk' && ($id = trim((string) $this->input->get('dok'))) !== '') {
            $h = $this->db->get_where('tbl_wh_part_masuk', array('id_masuk' => $id))->row();
            $judul = 'Part Masuk ' . $id . ($h ? ' · ' . tgl_singkat($h->tgl_masuk) . ($h->nama_supplier ? ' · ' . $h->nama_supplier : '') : '');
            if ($h) { $l2 = Mod_lapgudang::lok($h->lokasi); if ($l2) $lok = $l2; }
            foreach ($this->db->select('no_part, SUM(jumlah) AS qty', false)->where('id_masuk', $id)->group_by('no_part')->get('tbl_wh_detail_part_masuk')->result() as $r) {
                $k = trim($r->no_part); if (isset($B[$k])) $rows[] = array($B[$k], max(1, (int) round((float) $r->qty)));
            }
        } elseif ($sumber === 'so' && ($id = (int) $this->input->get('dok'))) {
            $h = $this->db->get_where('tbl_wh_so', array('id' => $id))->row();
            if ($h) { $lok = $h->lokasi; $judul = 'Stock Opname ' . $h->no_so; }
            foreach ($this->db->select('no_part')->get_where('tbl_wh_so_detail', array('id_so' => $id))->result() as $r) { $k = trim($r->no_part); if (isset($B[$k])) $rows[] = array($B[$k], 1); }
        } elseif ($sumber === 'filter') {
            $kat = (string) $this->input->get('kategori'); $rak = trim((string) $this->input->get('rak')); $bersaldo = (bool) $this->input->get('bersaldo');
            foreach ($B as $k => $b) {
                if ($kat !== '' && (string) $b->kategori !== $kat) continue;
                if ($rak !== '' && stripos(trim((string) $b->{'lok_' . $lok}), $rak) !== 0) continue;
                if ($bersaldo && $b->{'stok_' . $lok} <= 0) continue;
                if ($q !== '' && stripos($k . ' ' . $b->nama_part, $q) === false) continue;
                $rows[] = array($b, 1);
                if (count($rows) >= 2000) break;
            }
            $judul = 'Filter barang';
        }
        usort($rows, function ($a, $b) use ($lok) { return strcmp($a[0]->{'lok_' . $lok} . $a[0]->no_part, $b[0]->{'lok_' . $lok} . $b[0]->no_part); });
        $masuk = $this->db->select('id_masuk, tgl_masuk, nama_supplier, no_po, lokasi')->order_by('tgl_masuk', 'DESC')->order_by('id_masuk', 'DESC')->limit(40)->get('tbl_wh_part_masuk')->result();
        $so = $this->db->table_exists('tbl_wh_so') ? $this->db->where('status <>', 'BATAL')->order_by('id', 'DESC')->limit(20)->get('tbl_wh_so')->result() : array();
        $this->template->load('layoutbackend', 'warehouse/label/index', array('page' => 'Cetak Label QR Barang', 'judul' => 'Gudang', 'lok' => $lok, 'rows' => $rows,
            'sumber' => $sumber, 'judul_daftar' => $judul, 'kategori' => $this->Mod_lapgudang->kategori_list(), 'masuk' => $masuk, 'so' => $so,
            'tpl_default' => $this->Mod_whutil->setting('label_template', 'T5030')));
    }

    /** halaman cetak; ?id[]=id_part atau POST part[no_part]=jumlah */
    public function cetak()
    {
        $this->wajib('print');
        $lok = $this->lok(); $tpl = (string) $this->input->get_post('tpl');
        if ( ! isset(self::$TEMPLATE[$tpl])) $tpl = $this->Mod_whutil->setting('label_template', 'T5030');
        $opsi = (array) $this->input->get_post('opsi');
        if ( ! $opsi) $opsi = array('nama', 'rak', 'gudang');
        $B = $this->Mod_lapgudang->barang(); $label = array();
        $ids = (array) $this->input->get('id');
        if ($ids) {
            foreach ($this->db->select('no_part')->where_in('id_part', array_map('intval', $ids))->get('tbl_wh_barang')->result() as $r) { $k = trim($r->no_part); if (isset($B[$k])) $label[] = array($B[$k], 1); }
        }
        foreach ((array) $this->input->post('part') as $k => $n) {
            $n = max(0, min(500, (int) $n)); $k = trim((string) $k);
            if ($n > 0 && isset($B[$k])) $label[] = array($B[$k], $n);
        }
        $pl = array();
        if (in_array('harga', $opsi, true)) foreach ($this->db->select('no_part, price_list_' . $lok . ' AS p', false)->get('tbl_wh_barang')->result() as $r) $pl[trim($r->no_part)] = (float) $r->p;
        if ($this->input->post('simpan_tpl')) $this->Mod_whutil->simpan_setting(array('label_template' => $tpl));
        $this->load->view('warehouse/label/cetak', array('label' => $label, 'tpl' => $tpl, 'T' => self::$TEMPLATE[$tpl], 'lok' => $lok, 'opsi' => $opsi, 'pl' => $pl,
            'mulai' => max(1, (int) $this->input->post('mulai')), 'apl' => $this->db->get('aplikasi', 1)->row()));
    }

    /** scan QR / barcode → info barang */
    public function scan()
    {
        $this->template->load('layoutbackend', 'warehouse/label/scan', array('page' => 'Scan Barang', 'judul' => 'Gudang', 'lok' => $this->lok()));
    }

    public function info()
    {
        $kode = trim((string) $this->input->get('kode'));
        if (strpos($kode, 'TOP|') === 0) { $x = explode('|', $kode); $kode = isset($x[1]) ? $x[1] : ''; }
        $B = $this->Mod_lapgudang->barang();
        if ( ! isset($B[$kode])) {
            $c = $this->Mod_lapgudang->cari_part($kode, 8);
            $this->json(array('ok' => false, 'pesan' => 'No. part "' . $kode . '" tidak ditemukan.', 'saran' => array_map(function ($x) { return array('no_part' => trim($x->no_part), 'nama' => $x->nama_part); }, $c)));
        }
        $b = $B[$kode]; $lok = array();
        foreach (Mod_lapgudang::$LOKASI as $l => $nm) $lok[] = array('kode' => $l, 'nama' => $nm, 'stok' => $b->{'stok_' . $l}, 'rak' => trim((string) $b->{'lok_' . $l}), 'harga' => Mod_lapgudang::harga($b, $l));
        $gerak = array_slice(array_reverse($this->Mod_lapgudang->gerakan(date('Y-m-d', strtotime('-365 day')), null, $kode, '')), 0, 8);
        $this->json(array('ok' => true, 'no_part' => $kode, 'nama' => $b->nama_part, 'satuan' => $b->satuan, 'kategori' => $b->nama_kategori, 'min' => $b->stok_min, 'lokasi' => $lok,
            'gerak' => array_map(function ($g) { return array('tgl' => tgl_singkat($g->tgl), 'jenis' => Mod_lapgudang::$JENIS[$g->jenis], 'kode' => $g->jenis, 'masuk' => $g->masuk, 'keluar' => $g->keluar, 'lok' => $g->lok, 'ket' => $g->ket); }, $gerak),
            'url_kartu' => site_url('LaporanGudang/kartu_stok?part=' . rawurlencode($kode) . '&dari=' . date('Y-01-01')), 'url_harga' => site_url('LaporanGudang/riwayat_harga?part=' . rawurlencode($kode)),
            'url_label' => site_url('LabelBarang/cetak') . '?' . http_build_query(array('id' => array((int) $this->db->select('id_part')->where('TRIM(no_part) = ' . $this->db->escape($kode), null, false)->get('tbl_wh_barang')->row('id_part'))))));
    }
}
