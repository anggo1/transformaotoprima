<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Panduan Gudang: alur, checklist data (dihitung dari database), rutinitas, referensi & tanya jawab. */
class PanduanGudang extends MY_Controller
{
    public function index()
    {
        $this->config->load('panduan', true, true);
        $this->load->model('warehouse/Mod_lapgudang');
        require_once APPPATH . 'controllers/LaporanGudang.php';
        $B = $this->Mod_lapgudang->barang();
        $n = count($B); $min = 0; $harga = 0; $kat = 0; $minus = 0;
        foreach ($B as $b) {
            if ($b->stok_min > 0) $min++;
            if (Mod_lapgudang::harga($b, '') > 0) $harga++;
            if (trim((string) $b->kategori) !== '') $kat++;
            if ($b->stok_cbt < 0 || $b->stok_jkt < 0 || $b->stok_sby < 0) $minus++;
        }
        $pct = function ($a) use ($n) { return $n ? round($a / $n * 100) : 0; };
        $sup = $this->db->count_all('tbl_wh_supplier');
        $supAkun = $this->db->where("kode_akun IS NOT NULL AND kode_akun <> ''", null, false)->count_all_results('tbl_wh_supplier');
        $cek = array(
            array('Barang terdaftar di master', $n > 0, nf($n) . ' barang', 'Sparepart'),
            array('Kategori/divisi barang terisi', $pct($kat) >= 95, $pct($kat) . '% barang', 'Sparepart'),
            array('Harga net terisi', $pct($harga) >= 90, $pct($harga) . '% barang', 'UpdateHpart'),
            array('Stok minimum diisi untuk barang rutin', $min > 0 ? ($pct($min) >= 30 ? true : null) : false, nf($min) . ' barang (' . $pct($min) . '%)', 'Sparepart'),
            array('Tidak ada stok minus', $minus === 0, $minus ? nf($minus) . ' barang minus' : 'aman', 'LaporanGudang/cek_data'),
            array('Supplier terhubung ke akun hutang', $sup > 0 && $supAkun === $sup, nf($supAkun) . ' dari ' . nf($sup) . ' supplier', 'Settingwh'),
        );
        $data = array('page' => 'Panduan Gudang', 'judul' => 'Panduan', 'cek' => $cek,
            'halaman' => (array) $this->config->item('halaman', 'panduan'), 'laporan' => LaporanGudang::$LAPORAN,
            'penjelasan' => (array) $this->config->item('laporan_gudang', 'panduan'));
        $this->template->load('layoutbackend', 'warehouse/panduan/index', $data);
    }
}
