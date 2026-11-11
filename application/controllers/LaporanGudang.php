<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pusat Laporan Gudang + Dashboard Gudang.
 * Semua laporan: tampil di layar, cetak (?cetak=1) & ekspor Excel (?export=xlsx).
 * Hanya MEMBACA data transaksi gudang yang sudah ada — alur input tidak berubah.
 */
class LaporanGudang extends MY_Controller
{
    /** method => [judul, ikon, deskripsi, grup] */
    public static $LAPORAN = array(
        'kartu_stok'    => array('Kartu Stok', 'fa-id-card-alt', 'Riwayat masuk/keluar satu barang dengan saldo berjalan per gudang', 'Persediaan'),
        'mutasi'        => array('Mutasi Stok', 'fa-exchange-alt', 'Saldo awal, masuk, keluar, saldo akhir & nilai per barang dalam periode', 'Persediaan'),
        'nilai'         => array('Nilai Persediaan', 'fa-coins', 'Nilai stok per lokasi & kategori (stok × harga net)', 'Persediaan'),
        'stok_minimum'  => array('Stok Minimum & Saran Pesan', 'fa-exclamation-triangle', 'Barang di bawah stok minimum, yang sedang dipesan & saran jumlah pesan', 'Persediaan'),
        'slow_moving'   => array('Slow Moving & Dead Stock', 'fa-hourglass-end', 'Barang bersaldo yang lama / tidak pernah keluar beserta nilainya', 'Analisis'),
        'abc'           => array('Analisis ABC', 'fa-sort-amount-down', 'Klasifikasi A/B/C berdasarkan nilai pemakaian (Pareto 80/15/5)', 'Analisis'),
        'pemakaian'     => array('Pemakaian per Tujuan/Divisi', 'fa-dolly', 'Pengeluaran barang per jenis, kategori, tujuan & barang terbanyak', 'Analisis'),
        'penerimaan'    => array('Penerimaan per Supplier', 'fa-truck-loading', 'Dokumen, baris, qty & nilai penerimaan per pemasok (PO / Non-PO / retur)', 'Pembelian'),
        'outstanding'   => array('PO Outstanding', 'fa-clipboard-list', 'Part Order ke pemasok yang belum datang & PO customer yang belum dikirim', 'Pembelian'),
        'selisih_opname'=> array('Selisih Stock Opname', 'fa-clipboard-check', 'Perbedaan stok sistem vs fisik per dokumen opname beserta nilainya', 'Kontrol'),
        'harga_rata'    => array('Harga Rata-rata vs Harga Net', 'fa-balance-scale', 'Harga rata-rata bergerak, harga beli terakhir & harga net master beserta dampaknya ke nilai stok', 'Harga'),
        'kenaikan_harga'=> array('Kenaikan Harga Beli Tahunan', 'fa-chart-line', 'Perbandingan harga beli rata-rata tertimbang tahun ini vs tahun lalu, indeks harga & dampak biaya', 'Harga'),
        'riwayat_harga' => array('Riwayat Harga per Barang', 'fa-history', 'Harga beli per tahun (min, maks, rata-rata), daftar pembelian & log perubahan harga', 'Harga'),
        'cek_data'      => array('Pemeriksaan Data Gudang', 'fa-stethoscope', 'Stok minus, part ganda, harga kosong, part tak terdaftar, PO menggantung', 'Kontrol'),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('warehouse/Mod_lapgudang');
        $this->config->load('panduan', true, true);
        $this->wajib('view');
    }

    /* ---------------- dashboard + daftar laporan ---------------- */
    public function index()
    {
        $p = (array) $this->config->item('laporan_gudang', 'panduan');
        $data = array('page' => 'Dashboard & Laporan Gudang', 'judul' => 'Gudang', 'laporan' => self::$LAPORAN, 'penjelasan' => $p,
            'dash' => $this->Mod_lapgudang->dashboard());
        $this->template->load('layoutbackend', 'warehouse/lapgudang/hub', $data);
    }

    public function cari_part()
    {
        $r = array();
        foreach ($this->Mod_lapgudang->cari_part($this->input->get('q')) as $x) $r[] = array('id' => $x->no_part, 'text' => $x->no_part . ' — ' . $x->nama_part);
        $this->json($r);
    }

    /* ---------------- util ---------------- */
    private function tgl($k, $d) { $v = tgl_sql($this->input->get($k)); return $v ?: $d; }
    private function periode($dariDefault = null)
    {
        $a = $this->tgl('dari', $dariDefault ?: date('Y-m-01')); $b = $this->tgl('sampai', date('Y-m-d'));
        if ($b < $a) { $t = $a; $a = $b; $b = $t; }
        return array($a, $b);
    }
    private function lok($wajib = false)
    {
        $l = (string) $this->input->get('lok');
        if ( ! isset(Mod_lapgudang::$LOKASI[$l])) {
            $l = '';
            if ($wajib) { $s = Mod_lapgudang::lok((string) $this->session->userdata('lokasi')); $l = $s ?: 'cbt'; }
        }
        return $l;
    }
    private function label_lok($l) { return $l === '' ? 'Semua gudang' : 'Gudang ' . Mod_lapgudang::$LOKASI[$l]; }
    private function lbl($a, $b) { return tgl_id($a) . ' s/d ' . tgl_id($b); }

    private function render($kunci, $d, $f, $sub)
    {
        $info = self::$LAPORAN[$kunci];
        $apl = $this->db->get('aplikasi', 1)->row();
        $p = (array) $this->config->item('laporan_gudang', 'panduan');
        $vars = array('kunci' => $kunci, 'info' => $info, 'partial' => 'warehouse/lapgudang/p_' . $kunci, 'd' => $d, 'f' => $f, 'subjudul' => $sub,
            'apl' => $apl, 'perusahaan' => $apl ? $apl->nama_owner : '', 'page' => $info[0], 'judul' => 'Laporan Gudang', 'akun' => array(),
            'penjelasan' => isset($p[$kunci]) ? $p[$kunci] : null, 'ctrl' => 'LaporanGudang', 'grafik' => $this->grafik($kunci, $d, $f));
        $exp = $this->input->get('export');
        if ($exp === 'xlsx' || $exp === 'xls') {
            $this->wajib('print');
            $html = $this->load->view('accounting/lapkeu/cetak', $vars + array('mode' => 'excel'), TRUE);
            $nama = preg_replace('/[^A-Za-z0-9]+/', '_', $info[0]) . '_' . date('Ymd_His');
            while (ob_get_level() > 0) ob_end_clean();
            if (class_exists('\PhpOffice\PhpSpreadsheet\Reader\Html')) {
                try {
                    $tmp = tempnam(sys_get_temp_dir(), 'lg') . '.html'; file_put_contents($tmp, $html);
                    $sheet = (new \PhpOffice\PhpSpreadsheet\Reader\Html())->load($tmp); @unlink($tmp);
                    $ws = $sheet->getActiveSheet(); $ws->setTitle(substr(preg_replace('/[^A-Za-z0-9 ]/', '', $info[0]), 0, 30));
                    foreach (range('A', 'P') as $c) $ws->getColumnDimension($c)->setAutoSize(true);
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment; filename="' . $nama . '.xlsx"');
                    (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet))->save('php://output'); exit;
                } catch (\Throwable $e) { log_message('error', 'Ekspor Excel gagal: ' . $e->getMessage()); }
            }
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $nama . '.xls"');
            echo "\xEF\xBB\xBF" . $html; exit;
        }
        if ($this->input->get('cetak')) { $this->wajib('print'); $this->load->view('accounting/lapkeu/cetak', $vars + array('mode' => 'print')); return; }
        $this->template->load('layoutbackend', 'accounting/lapkeu/shell', $vars);
    }

    /* ---------------- laporan ---------------- */
    public function kartu_stok()
    {
        list($a, $b) = $this->periode(date('Y-01-01'));
        $l = $this->lok(); $part = trim((string) $this->input->get('part'));
        $d = $this->Mod_lapgudang->kartu_stok($part, $l, $a, $b);
        $f = array('dari' => $a, 'sampai' => $b, 'lok' => $l, 'part' => $part, 'jenis' => 'periode');
        if ($d) $f['part_nama'] = $d->barang->nama_part;
        $this->render('kartu_stok', $d, $f, ($d ? $part . ' — ' . $d->barang->nama_part . ' · ' : '') . $this->label_lok($l) . ' · ' . $this->lbl($a, $b));
    }

    public function mutasi()
    {
        list($a, $b) = $this->periode();
        $l = $this->lok(); $k = (string) $this->input->get('kategori'); $semua = (bool) $this->input->get('semua');
        $d = $this->Mod_lapgudang->mutasi($a, $b, $l, $k, ! $semua);
        $this->render('mutasi', $d, array('dari' => $a, 'sampai' => $b, 'lok' => $l, 'kategori' => $k, 'kategori_list' => $this->Mod_lapgudang->kategori_list(), 'bergerak' => ! $semua, 'jenis' => 'periode'),
            $this->label_lok($l) . ' · ' . $this->lbl($a, $b));
    }

    public function nilai()
    {
        redirect('ReportWhNilaiStok' . ($this->input->get('lok') ? '?lokasi=' . rawurlencode($this->input->get('lok')) : ''));
    }

    public function stok_minimum()
    {
        $l = $this->lok(true); $k = (string) $this->input->get('kategori'); $semua = (bool) $this->input->get('semua');
        $d = $this->Mod_lapgudang->stok_minimum($l, $k, $semua);
        $this->render('stok_minimum', $d, array('lok' => $l, 'lok_wajib' => true, 'kategori' => $k, 'kategori_list' => $this->Mod_lapgudang->kategori_list(), 'bergerak' => ! $semua, 'jenis' => 'none'),
            $this->label_lok($l) . ' · per ' . tgl_id(date('Y-m-d')));
    }

    public function slow_moving()
    {
        $l = $this->lok(true); $h = (int) ($this->input->get('hari') ?: 90); $h = max(7, min(1095, $h));
        $tgl = date('Y-m-d');
        $d = $this->Mod_lapgudang->slow_moving($l, $h, $tgl);
        $this->render('slow_moving', $d, array('lok' => $l, 'lok_wajib' => true, 'hari' => $h, 'jenis' => 'none'), $this->label_lok($l) . ' · tidak keluar sejak ' . tgl_id($d['batas']));
    }

    public function abc()
    {
        list($a, $b) = $this->periode(date('Y-m-d', strtotime('-1 year +1 day')));
        $l = $this->lok();
        $this->render('abc', $this->Mod_lapgudang->abc($a, $b, $l), array('dari' => $a, 'sampai' => $b, 'lok' => $l, 'jenis' => 'periode'), $this->label_lok($l) . ' · ' . $this->lbl($a, $b));
    }

    public function pemakaian()
    {
        list($a, $b) = $this->periode();
        $l = $this->lok();
        $this->render('pemakaian', $this->Mod_lapgudang->pemakaian($a, $b, $l), array('dari' => $a, 'sampai' => $b, 'lok' => $l, 'jenis' => 'periode'), $this->label_lok($l) . ' · ' . $this->lbl($a, $b));
    }

    public function penerimaan()
    {
        list($a, $b) = $this->periode();
        $l = $this->lok();
        $this->render('penerimaan', $this->Mod_lapgudang->penerimaan($a, $b, $l), array('dari' => $a, 'sampai' => $b, 'lok' => $l, 'jenis' => 'periode'), $this->label_lok($l) . ' · ' . $this->lbl($a, $b));
    }

    public function outstanding()
    {
        $t = $this->tgl('tgl', date('Y-m-d'));
        $this->render('outstanding', $this->Mod_lapgudang->outstanding($t), array('tgl' => $t, 'jenis' => 'tanggal'), 'Per ' . tgl_id($t));
    }

    public function selisih_opname()
    {
        $list = $this->Mod_lapgudang->opname_list();
        $id = (string) $this->input->get('opname');
        if ($id === '' && $list) $id = (string) $list[0]->id_opname;
        $l = $this->lok(true);
        $d = $this->Mod_lapgudang->selisih_opname($id, $l);
        $this->render('selisih_opname', $d, array('opname' => $id, 'opname_list' => $list, 'lok' => $l, 'lok_wajib' => true, 'jenis' => 'none'), 'Dokumen ' . $id . ' · harga ' . $this->label_lok($l));
    }

    public function harga_rata()
    {
        $this->load->model('warehouse/Mod_hargarata');
        $l = $this->lok(true); $k = (string) $this->input->get('kategori');
        $d = $this->Mod_hargarata->daftar($l, $k);
        $d['metode'] = $this->metode();
        $this->render('harga_rata', $d, array('lok' => $l, 'lok_wajib' => true, 'kategori' => $k, 'kategori_list' => $this->Mod_lapgudang->kategori_list(), 'jenis' => 'none'),
            $this->label_lok($l) . ' · dihitung ' . ($d['dihitung'] ? date('d/m/Y H:i', strtotime($d['dihitung'])) : 'belum pernah'));
    }

    public function kenaikan_harga()
    {
        $this->load->model('warehouse/Mod_hargarata');
        $t = (int) ($this->input->get('tahun') ?: date('Y')); $t = max(2000, min(2100, $t)); $l = $this->lok();
        $this->render('kenaikan_harga', $this->Mod_hargarata->kenaikan($t, $l), array('tahun' => $t, 'lok' => $l, 'jenis' => 'tahun'), 'Tahun ' . $t . ' dibanding ' . ($t - 1) . ' · ' . $this->label_lok($l));
    }

    public function riwayat_harga()
    {
        $this->load->model('warehouse/Mod_hargarata');
        $l = $this->lok(); $part = trim((string) $this->input->get('part'));
        $d = $this->Mod_hargarata->riwayat($part, $l);
        $f = array('lok' => $l, 'part' => $part, 'jenis' => 'none');
        if ($d) $f['part_nama'] = $d->barang->nama_part;
        $this->render('riwayat_harga', $d, $f, ($d ? $part . ' — ' . $d->barang->nama_part . ' · ' : 'Pilih barang · ') . $this->label_lok($l));
    }

    private function metode() { $this->load->model('warehouse/Mod_whutil'); return $this->Mod_whutil->setting('metode_nilai', 'NET'); }
    private function admin_gudang() { return in_array((int) $this->session->userdata('id_level'), array(1, 12), true) || $this->boleh('edit'); }

    /** POST: hitung ulang harga rata-rata dari riwayat transaksi */
    public function hitung_harga_rata()
    {
        if ( ! $this->admin_gudang()) $this->wajib('edit');
        $this->load->model('warehouse/Mod_hargarata');
        @set_time_limit(300);
        $n = $this->Mod_hargarata->hitung_ulang();
        $this->session->set_flashdata('wh_msg', array('success', 'Harga rata-rata dihitung ulang untuk ' . nf($n) . ' barang-gudang.'));
        redirect('LaporanGudang/harga_rata' . ($this->input->post('lok') ? '?lok=' . rawurlencode($this->input->post('lok')) : ''));
    }

    /** POST: metode penilaian persediaan NET | RATA */
    public function metode_nilai()
    {
        if ( ! $this->admin_gudang()) $this->wajib('edit');
        $this->load->model('warehouse/Mod_whutil');
        $m = $this->input->post('metode') === 'RATA' ? 'RATA' : 'NET';
        $this->Mod_whutil->simpan_setting(array('metode_nilai' => $m));
        $this->session->set_flashdata('wh_msg', array('success', 'Metode penilaian laporan gudang: ' . ($m === 'RATA' ? 'harga rata-rata bergerak' : 'harga net master') . '.'));
        redirect('LaporanGudang/harga_rata');
    }

    public function cek_data()
    {
        $this->render('cek_data', $this->Mod_lapgudang->cek_data(), array('jenis' => 'none'), 'Per ' . tgl_id(date('Y-m-d')));
    }

    /* ================= GRAFIK ================= */
    private static function g($judul, $tipe, $labels, $seri, $format = 'rp', $extra = array())
    {
        $s2 = array();
        foreach ($seri as $nama => $data) $s2[] = is_array($data) && isset($data['data']) ? $data + array('nama' => $nama) : array('nama' => $nama, 'data' => array_map(function ($v) { return round((float) $v, 2); }, array_values($data)));
        return array('judul' => $judul, 'tipe' => $tipe, 'labels' => array_values($labels), 'seri' => $s2, 'format' => $format) + $extra;
    }

    private static function top($arr, $n = 10, $lain = true)
    {
        arsort($arr); $o = array_slice($arr, 0, $n, true);
        $sisa = array_sum(array_slice($arr, $n)); if ($lain && abs($sisa) > 0.5) $o['Lainnya'] = $sisa;
        return $o;
    }

    private function grafik($k, $d, $f)
    {
        $G = array();
        if ( ! $d) return $G;
        try {
            switch ($k) {
                case 'kartu_stok':
                    $lab = array(tgl_singkat($f['dari'])); $sal = array($d->awal); $m = array(0); $kel = array(0);
                    foreach ($d->rows as $r) { $lab[] = tgl_singkat($r->tgl); $sal[] = $r->saldo; $m[] = $r->masuk; $kel[] = $r->keluar; }
                    $G[] = self::g('Pergerakan stok', 'bar', $lab, array('Masuk' => $m, 'Keluar' => $kel, 'Saldo' => array('data' => $sal, 'tipe' => 'line')), 'qty');
                    break;
                case 'mutasi':
                    $kat = array(); $nil = array();
                    foreach ($d['rows'] as $r) { $kat[$r->kategori]['m'] = ($kat[$r->kategori]['m'] ?? 0) + $r->masuk * $r->harga; $kat[$r->kategori]['k'] = ($kat[$r->kategori]['k'] ?? 0) + $r->keluar * $r->harga; $nil[$r->no_part . ' ' . $r->nama] = $r->nilai; }
                    if ($kat) $G[] = self::g('Nilai masuk vs keluar per kategori', 'bar', array_keys($kat), array('Masuk' => array_column($kat, 'm'), 'Keluar' => array_column($kat, 'k')));
                    if ($nil) { $t = self::top($nil, 10, false); $G[] = self::g('10 barang dengan nilai akhir terbesar', 'horizontalBar', array_keys($t), array('Nilai' => $t)); }
                    break;
                case 'stok_minimum':
                    $st = array('HABIS' => 0, 'DI BAWAH MIN' => 0, 'AMAN' => 0); $nil = array();
                    foreach ($d['rows'] as $r) { $st[$r->status]++; if ($r->nilai > 0) $nil[$r->no_part] = $r->nilai; }
                    if ($d['rows']) $G[] = self::g('Status stok', 'doughnut', array_keys($st), array('Jumlah barang' => $st), 'qty');
                    if ($nil) { $t = self::top($nil, 10, false); $G[] = self::g('Nilai saran pesan terbesar', 'horizontalBar', array_keys($t), array('Nilai' => $t)); }
                    break;
                case 'slow_moving':
                    $kl = array(); $nil = array();
                    foreach ($d['rows'] as $r) { $kl[$r->kelas] = ($kl[$r->kelas] ?? 0) + $r->nilai; $nil[$r->no_part] = $r->nilai; }
                    if ($kl) $G[] = self::g('Nilai persediaan lambat per kelas', 'doughnut', array_keys($kl), array('Nilai' => $kl));
                    if ($nil) { $t = self::top($nil, 10, false); $G[] = self::g('10 barang lambat dengan nilai terbesar', 'horizontalBar', array_keys($t), array('Nilai' => $t)); }
                    break;
                case 'abc':
                    $rows = array_slice($d['rows'], 0, 30);
                    if ($rows) $G[] = self::g('Pareto nilai pemakaian (30 teratas)', 'pareto', array_map(function ($r) { return $r->no_part; }, $rows), array('Nilai pemakaian' => array_map(function ($r) { return $r->nilai; }, $rows)), 'rp', array('tinggi' => 320));
                    $K = $d['kelas']; $G[] = self::g('Nilai per kelas ABC', 'doughnut', array('A', 'B', 'C'), array('Nilai' => array($K['A'][1], $K['B'][1], $K['C'][1])));
                    break;
                case 'pemakaian':
                    if ($d['jenis']) $G[] = self::g('Pengeluaran per jenis', 'doughnut', array_keys($d['jenis']), array('Nilai' => array_column($d['jenis'], 'nilai')));
                    if ($d['kategori']) $G[] = self::g('Pengeluaran per kategori', 'bar', array_keys($d['kategori']), array('Nilai' => array_column($d['kategori'], 'nilai')));
                    $tp = array_slice($d['part'], 0, 10, true);
                    if ($tp) $G[] = self::g('10 barang paling banyak dipakai (nilai)', 'horizontalBar', array_keys($tp), array('Nilai' => array_column($tp, 'nilai')));
                    break;
                case 'penerimaan':
                    $rows = array_slice($d['rows'], 0, 10, true);
                    if ($rows) $G[] = self::g('Penerimaan per supplier', 'horizontalBar', array_keys($rows), array('PO' => array_column($rows, 'MPO'), 'Non-PO' => array_column($rows, 'MNP'), 'Retur' => array_column($rows, 'RTR')), 'rp', array('tumpuk' => true));
                    break;
                case 'outstanding':
                    $lab = array('0-30 hari', '31-60 hari', '61-90 hari', '> 90 hari'); $o = array();
                    foreach (array('AP' => 'Ke pemasok', 'AR' => 'Dari customer') as $j => $nm) {
                        $b = array(0, 0, 0, 0);
                        foreach ($d[$j] as $r) { $h = (int) $r->hari; $b[$h <= 30 ? 0 : ($h <= 60 ? 1 : ($h <= 90 ? 2 : 3))] += $r->nilai; }
                        $o[$nm] = $b;
                    }
                    $G[] = self::g('Nilai sisa pesanan menurut umur', 'bar', $lab, $o);
                    break;
                case 'selisih_opname':
                    $T = $d['total'];
                    if ($T->selisih_item) $G[] = self::g('Nilai selisih', 'bar', array('Fisik lebih', 'Fisik kurang', 'Bersih'), array('Nilai' => array($T->nilai_lebih, $T->nilai_kurang, $T->nilai_lebih + $T->nilai_kurang)));
                    $rows = array_slice($d['rows'], 0, 10);
                    if ($rows) $G[] = self::g('10 selisih terbesar (nilai)', 'horizontalBar', array_map(function ($r) { return $r->no_part; }, $rows), array('Nilai selisih' => array_map(function ($r) { return $r->nilai; }, $rows)));
                    break;
                case 'harga_rata':
                    $T = $d['total'];
                    if ($T->n) $G[] = self::g('Nilai stok: harga net vs rata-rata', 'bar', array('Harga net master', 'Harga rata-rata'), array('Nilai stok' => array($T->nilai_net, $T->nilai_rata)));
                    $sel = array(); foreach (array_slice($d['rows'], 0, 10) as $r) $sel[$r->no_part] = $r->nilai_net - $r->nilai_rata;
                    if ($sel) $G[] = self::g('10 selisih nilai terbesar (net − rata)', 'horizontalBar', array_keys($sel), array('Selisih' => $sel));
                    break;
                case 'kenaikan_harga':
                    if ($d['indeks']) $G[] = self::g('Indeks harga beli (tahun pertama = 100)', 'line', array_keys($d['indeks']), array('Indeks' => $d['indeks']), 'qty');
                    if ($d['kategori']) { arsort($d['kategori']); $G[] = self::g('Kenaikan per kategori (%)', 'bar', array_keys($d['kategori']), array('Kenaikan %' => $d['kategori']), 'persen'); }
                    $tp = array(); foreach (array_slice($d['rows'], 0, 10) as $r) $tp[$r->no_part] = $r->dampak;
                    if ($tp) $G[] = self::g('10 dampak biaya terbesar', 'horizontalBar', array_keys($tp), array('Dampak (Rp)' => $tp));
                    break;
                case 'riwayat_harga':
                    if ($d->tahun) $G[] = self::g('Harga beli per tahun', 'line', array_keys($d->tahun), array('Rata-rata' => array_map(function ($x) { return $x->rata; }, $d->tahun),
                        'Minimum' => array_map(function ($x) { return $x->min; }, $d->tahun), 'Maksimum' => array_map(function ($x) { return $x->max; }, $d->tahun)));
                    $bl = array_slice(array_reverse($d->beli), -30);
                    if ($bl) $G[] = self::g('Harga per penerimaan (30 terakhir)', 'line', array_map(function ($g) { return tgl_singkat($g->tgl); }, $bl), array('Harga' => array_map(function ($g) { return $g->harga; }, $bl)));
                    break;
            }
        } catch (\Throwable $e) { log_message('error', 'grafik gudang ' . $k . ': ' . $e->getMessage()); }
        return $G;
    }
}
