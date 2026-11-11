<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Laporan Nilai Persediaan & Stok Minimum per lokasi gudang */
class ReportWhNilaiStok extends MY_Controller
{
    private $lok = array('cbt' => 'Cibitung', 'jkt' => 'Jakarta', 'sby' => 'Surabaya');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_dashboard');
        $this->wajib('view');
    }

    public function index()
    {
        $lokasi = (string) $this->input->get('lokasi');
        if ( ! isset($this->lok[$lokasi])) $lokasi = $this->Mod_dashboard->kode_lokasi((string) $this->session->userdata('lokasi'));
        $filter = (string) $this->input->get('filter');
        $kategori = (string) $this->input->get('kategori');

        $stok = 'stok_' . $lokasi; $hrg = 'hrg_net_' . $lokasi; $rak = 'lok_' . $lokasi;
        $this->db->select("no_part, nama_part, kategori, satuan, stok_min, $stok AS stok, $hrg AS harga, $rak AS rak, ($stok * $hrg) AS nilai", false)
            ->from('tbl_wh_barang');
        if ($kategori !== '') $this->db->where('kategori', $kategori);
        if ($filter === 'ada') $this->db->where("$stok >", 0);
        elseif ($filter === 'nol') $this->db->where("$stok <=", 0);
        elseif ($filter === 'min') $this->db->where("stok_min <> ''", null, false)->where("stok_min <> '0'", null, false)->where("$stok < (stok_min + 0)", null, false);
        $rows = $this->db->order_by('kategori, nama_part')->get()->result();

        $ringkas = array(); $tot = array('item' => 0, 'qty' => 0, 'nilai' => 0);
        foreach ($rows as $r) {
            $k = $r->kategori !== '' ? $r->kategori : 'LAINNYA';
            if ( ! isset($ringkas[$k])) $ringkas[$k] = array('item' => 0, 'qty' => 0, 'nilai' => 0);
            $ringkas[$k]['item']++; $ringkas[$k]['qty'] += (float) $r->stok; $ringkas[$k]['nilai'] += (float) $r->nilai;
            $tot['item']++; $tot['qty'] += (float) $r->stok; $tot['nilai'] += (float) $r->nilai;
        }
        uasort($ringkas, function ($a, $b) { return $b['nilai'] <=> $a['nilai']; });
        $kats = array();
        foreach ($this->db->distinct()->select('kategori')->where('kategori !=', '')->order_by('kategori')->get('tbl_wh_barang')->result() as $k) $kats[] = $k->kategori;

        $this->template->load('layoutbackend', 'warehouse/report_wh/nilai_stok', array(
            'page' => 'Nilai Persediaan & Stok Minimum', 'judul' => 'Laporan Gudang',
            'rows' => $rows, 'ringkas' => $ringkas, 'tot' => $tot, 'kats' => $kats, 'lokList' => $this->lok,
            'f' => compact('lokasi', 'filter', 'kategori')));
    }
}
