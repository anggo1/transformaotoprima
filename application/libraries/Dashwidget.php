<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Registry & sumber data widget dashboard.
 * Setiap widget: judul, grup, ikon, lebar bawaan, link submenu yang wajib bisa dilihat (null = semua user), deskripsi.
 * data($kode) mengembalikan struktur netral yang dirender oleh views/dashboard/_widget.php:
 *   tipe kpi   : item => [[label, nilai, sub, link, ikon, warna]]
 *   tipe chart : chart => spesifikasi TOPChart
 *   tipe tabel : kolom => [[judul, 'num'?]], baris => [[sel html...]], kosong => teks
 *   tipe html  : html
 *   (opsional) link => [url, label], catatan
 */
class Dashwidget
{
    /** kode => [judul, grup, ikon, lebar, link akses, deskripsi] */
    public static $W = array(
        // Keuangan
        'keu_kpi'        => array('Ringkasan keuangan', 'Keuangan', 'fa-wallet', 12, 'LaporanKeuangan', 'Kas & bank, piutang, hutang, laba bulan berjalan'),
        'keu_tren'       => array('Pendapatan, beban & laba 6 bulan', 'Keuangan', 'fa-chart-line', 8, 'LaporanKeuangan', 'Grafik laba rugi bulanan'),
        'keu_kas'        => array('Saldo kas & bank', 'Keuangan', 'fa-university', 4, 'KasBank', 'Saldo setiap akun kas/bank hari ini'),
        'keu_umur_ar'    => array('Umur piutang', 'Keuangan', 'fa-hourglass-half', 6, 'Piutang', 'Sisa piutang per kelompok umur jatuh tempo'),
        'keu_umur_ap'    => array('Umur hutang', 'Keuangan', 'fa-hourglass-half', 6, 'Hutang', 'Sisa hutang per kelompok umur jatuh tempo'),
        'keu_jatuh_tempo'=> array('Jatuh tempo 14 hari', 'Keuangan', 'fa-calendar-check', 6, 'LaporanKeuangan', 'Faktur piutang & hutang yang jatuh tempo dalam 14 hari'),
        'keu_pajak'      => array('PPN masa berjalan', 'Keuangan', 'fa-receipt', 6, 'Pajak', 'PPN keluaran, masukan & kurang/lebih bayar tahun ini'),
        // Gudang
        'wh_kpi'         => array('Ringkasan gudang', 'Gudang', 'fa-warehouse', 12, 'LaporanGudang', 'Nilai persediaan, masuk/keluar bulan ini, PO menggantung'),
        'wh_tren'        => array('Mutasi barang 30 hari', 'Gudang', 'fa-chart-bar', 8, 'LaporanGudang', 'Qty masuk & keluar harian'),
        'wh_nilai'       => array('Nilai stok per gudang', 'Gudang', 'fa-coins', 4, 'LaporanGudang', 'Komposisi nilai persediaan per lokasi'),
        'wh_stok_min'    => array('Stok di bawah minimum', 'Gudang', 'fa-exclamation-triangle', 6, 'LaporanGudang', 'Barang yang perlu dipesan ulang'),
        'wh_top_pakai'   => array('Barang paling banyak dipakai', 'Gudang', 'fa-fire', 6, 'LaporanGudang', '10 barang dengan nilai pemakaian terbesar 90 hari'),
        'wh_opname'      => array('Stock opname', 'Gudang', 'fa-clipboard-check', 4, 'OpnameStok', 'Dokumen opname berjalan & akurasi terakhir'),
        'wh_kenaikan'    => array('Kenaikan harga beli', 'Gudang', 'fa-chart-line', 4, 'LaporanGudang', 'Inflasi harga beli tahun ini vs tahun lalu'),
        'wh_slow'        => array('Slow moving', 'Gudang', 'fa-hourglass-end', 4, 'LaporanGudang', 'Nilai stok yang tidak keluar > 180 hari'),
        'wh_po'          => array('PO menunggu barang', 'Gudang', 'fa-truck-loading', 12, 'LaporanGudang', 'Part order ke pemasok yang belum datang'),
        // After sales
        'as_kpi'         => array('Ringkasan service', 'After Sales', 'fa-tools', 12, 'WorkOrder', 'WO terbuka, selesai bulan ini, pendapatan jasa & part'),
        'as_tren'        => array('Work order per bulan', 'After Sales', 'fa-chart-area', 8, 'WorkOrder', 'Jumlah WO dibuka & ditutup 6 bulan'),
        'as_status'      => array('Status WO', 'After Sales', 'fa-tasks', 4, 'WorkOrder', 'Komposisi status work order'),
        'as_wo_open'     => array('WO terbuka terlama', 'After Sales', 'fa-clock', 12, 'WorkOrder', 'Work order yang belum ditutup, diurutkan dari yang terlama'),
        // Marketing
        'mk_kpi'         => array('Ringkasan chasis', 'Marketing', 'fa-truck', 12, 'Chasis', 'Stok chasis per status & SPK bulan ini'),
        'mk_sales'       => array('Chasis per sales', 'Marketing', 'fa-user-tie', 6, 'Chasis', 'Jumlah chasis per sales tahun ini'),
        'mk_tren'        => array('Chasis masuk per bulan', 'Marketing', 'fa-chart-line', 6, 'Chasis', '12 bulan terakhir'),
        'mk_stok'        => array('Stok chasis tersedia', 'Marketing', 'fa-list', 12, 'Chasis', 'Daftar chasis berstatus stok'),
        // HR
        'hr_kpi'         => array('Ringkasan pegawai', 'HRD', 'fa-users', 12, 'Pegawai', 'Jumlah pegawai, departemen, masa kerja & cuti'),
        'hr_dept'        => array('Pegawai per departemen', 'HRD', 'fa-sitemap', 6, 'Pegawai', 'Komposisi pegawai'),
        'hr_masa_kerja'  => array('Masa kerja', 'HRD', 'fa-business-time', 6, 'Pegawai', 'Sebaran masa kerja pegawai'),
        'hr_ultah'       => array('Ulang tahun 30 hari', 'HRD', 'fa-birthday-cake', 6, null, 'Rekan kerja yang berulang tahun'),
        'hr_cuti'        => array('Cuti terbaru', 'HRD', 'fa-plane-departure', 6, 'Pegawai', 'Pengajuan cuti terbaru'),
        'hr_absen'       => array('Kehadiran 14 hari', 'HRD', 'fa-fingerprint', 12, 'Pegawai', 'Jumlah pegawai yang tercatat hadir per hari (mesin absen)'),
        // Sistem
        'sys_pengumuman' => array('Pengumuman', 'Umum', 'fa-bullhorn', 12, null, 'Pengumuman dari administrator'),
        'sys_pintasan'   => array('Pintasan', 'Umum', 'fa-bolt', 12, null, 'Menu favorit & pintasan sesuai hak akses'),
    );

    /** @var CI_Controller */
    protected $CI;
    protected $akses = null;

    public function __construct() { $this->CI =& get_instance(); }

    /** daftar link submenu yang boleh dilihat level user aktif */
    public function boleh($link)
    {
        if ($link === null) return true;
        $lv = (int) $this->CI->session->userdata('id_level');
        if ($lv === 1) return true;
        if ($this->akses === null) {
            $this->akses = array();
            foreach ($this->CI->db->select('b.link')->from('tbl_akses_submenu a')->join('tbl_submenu b', 'a.id_submenu = b.id_submenu')
                ->where('a.id_level', $lv)->where('a.view_level', 'Y')->get()->result() as $r) $this->akses[strtolower(strtok($r->link, '/'))] = true;
        }
        return isset($this->akses[strtolower($link)]);
    }

    public function tersedia($kode) { return isset(self::$W[$kode]) && $this->boleh(self::$W[$kode][4]); }

    private function t($tabel) { return $this->CI->db->table_exists($tabel); }
    private static function kpi($label, $nilai, $sub = '', $link = '', $ikon = '', $warna = '') { return array($label, $nilai, $sub, $link, $ikon, $warna); }
    private static function g($judul, $tipe, $labels, $seri, $format = 'rp', $extra = array())
    {
        $s = array(); foreach ($seri as $n => $d) $s[] = is_array($d) && isset($d['data']) ? $d + array('nama' => $n) : array('nama' => $n, 'data' => array_map(function ($v) { return round((float) $v, 2); }, array_values($d)));
        return array('judul' => $judul, 'tipe' => $tipe, 'labels' => array_values($labels), 'seri' => $s, 'format' => $format, 'tinggi' => 240) + $extra;
    }
    private static function e($s) { return html_escape((string) $s); }
    private static function a($url, $teks) { return '<a href="' . site_url($url) . '">' . html_escape((string) $teks) . '</a>'; }

    /** ambil data widget; null bila tidak tersedia */
    public function data($kode)
    {
        if ( ! $this->tersedia($kode)) return null;
        $m = 'w_' . $kode;
        try { return method_exists($this, $m) ? $this->$m() : null; }
        catch (\Throwable $e) { log_message('error', 'widget ' . $kode . ': ' . $e->getMessage()); return array('tipe' => 'html', 'html' => '<div class="text-muted small">Data belum dapat dimuat.</div>'); }
    }

    /* ======================= KEUANGAN ======================= */
    private function keu()
    {
        static $k = null;
        if ($k === null) { $this->CI->load->model('Mod_dashboard'); $k = $this->CI->Mod_dashboard->ringkasan_keuangan(); }
        return $k;
    }
    private function w_keu_kpi()
    {
        if ( ! $this->t('tbl_acc_setting')) return array('tipe' => 'html', 'html' => '<span class="text-muted">Modul akuntansi belum disiapkan.</span>');
        $k = $this->keu(); $t = $k->tren; $lalu = array_slice($t, -2, 1); $lalu = $lalu ? $lalu[0]['laba'] : 0;
        return array('tipe' => 'kpi', 'item' => array(
            self::kpi('Kas & bank', nf_acc($k->kas), 'saldo hari ini', 'LaporanKeuangan/mutasi_kas', 'fa-wallet'),
            self::kpi('Piutang usaha', nf_acc($k->piutang->sisa), nf($k->piutang->n) . ' faktur · lewat tempo ' . nf_acc($k->piutang->sisa_lewat), 'LaporanKeuangan/umur_piutang', 'fa-hand-holding-usd', $k->piutang->sisa_lewat > 0 ? 'warn' : ''),
            self::kpi('Hutang usaha', nf_acc($k->hutang->sisa), nf($k->hutang->n) . ' faktur · lewat tempo ' . nf_acc($k->hutang->sisa_lewat), 'LaporanKeuangan/umur_hutang', 'fa-file-invoice-dollar', $k->hutang->sisa_lewat > 0 ? 'red' : ''),
            self::kpi('Laba bulan ini', nf_acc($k->laba_bulan_ini), 'bulan lalu ' . nf_acc($lalu), 'LaporanKeuangan/laba_rugi', 'fa-chart-line', $k->laba_bulan_ini < 0 ? 'red' : ''),
        ));
    }
    private function w_keu_tren()
    {
        if ( ! $this->t('tbl_acc_setting')) return null;
        $t = $this->keu()->tren; $lab = array(); foreach (array_keys($t) as $p) $lab[] = bulan_singkat((int) substr($p, 5, 2)) . ' ' . substr($p, 2, 2);
        return array('tipe' => 'chart', 'chart' => self::g('', 'bar', $lab, array('Pendapatan' => array_column($t, 'pendapatan'), 'Beban' => array_column($t, 'beban'), 'Laba' => array('data' => array_map(function ($x) { return round($x['laba']); }, array_values($t)), 'tipe' => 'line')), 'rp', array('tinggi' => 260)),
            'link' => array('LaporanKeuangan/laba_rugi', 'Laba rugi'));
    }
    private function w_keu_kas()
    {
        $this->CI->load->library('akuntansi'); $this->CI->load->model('accounting/Mod_acctrx');
        $b = array(); $tot = 0;
        foreach ($this->CI->Mod_acctrx->saldo_kas() as $s) { $b[] = array(self::e($s->nama) . '<div class="small text-muted">' . self::e($s->kode) . '</div>', nf_acc($s->saldo)); $tot += $s->saldo; }
        if ($b) $b[] = array('<b>Total</b>', '<b>' . nf_acc($tot) . '</b>');
        return array('tipe' => 'tabel', 'kolom' => array(array('Akun'), array('Saldo', 'num')), 'baris' => $b, 'kosong' => 'Belum ada akun kas/bank.', 'link' => array('KasBank', 'Kas & Bank'));
    }
    private function umur($jenis)
    {
        if ( ! $this->t('tbl_acc_faktur')) return null;
        $lab = array('Belum jatuh tempo', '1-30 hari', '31-60 hari', '61-90 hari', '> 90 hari'); $v = array_fill(0, 5, 0.0); $hari = date('Y-m-d');
        foreach ($this->CI->db->select('jatuh_tempo, tanggal, total, terbayar')->where('jenis', $jenis)->where_not_in('status', array('LUNAS', 'BATAL'))->get('tbl_acc_faktur')->result() as $f) {
            $sisa = (float) $f->total - (float) $f->terbayar; if ($sisa <= 0.004) continue;
            $jt = $f->jatuh_tempo ?: $f->tanggal; $d = (int) floor((strtotime($hari) - strtotime($jt)) / 86400);
            $v[$d <= 0 ? 0 : ($d <= 30 ? 1 : ($d <= 60 ? 2 : ($d <= 90 ? 3 : 4)))] += $sisa;
        }
        return array('tipe' => 'chart', 'chart' => self::g('', 'bar', $lab, array('Sisa' => $v), 'rp', array('seri_warna' => true))
            , 'link' => array($jenis === 'AR' ? 'LaporanKeuangan/umur_piutang' : 'LaporanKeuangan/umur_hutang', 'Laporan umur'), 'catatan' => 'Total ' . nf_acc(array_sum($v)));
    }
    private function w_keu_umur_ar() { return $this->umur('AR'); }
    private function w_keu_umur_ap() { return $this->umur('AP'); }
    private function w_keu_jatuh_tempo()
    {
        if ( ! $this->t('tbl_acc_faktur')) return null;
        $b = array();
        foreach ($this->CI->db->where_not_in('status', array('LUNAS', 'BATAL'))->where('jatuh_tempo <=', date('Y-m-d', strtotime('+14 day')))->order_by('jatuh_tempo')->limit(10)->get('tbl_acc_faktur')->result() as $f) {
            $sisa = (float) $f->total - (float) $f->terbayar; if ($sisa <= 0.004) continue;
            $lewat = $f->jatuh_tempo < date('Y-m-d');
            $b[] = array('<span class="badge badge-' . ($f->jenis === 'AR' ? 'info' : 'warning') . '">' . ($f->jenis === 'AR' ? 'Piutang' : 'Hutang') . '</span>', self::a($f->jenis === 'AR' ? 'Piutang' : 'Hutang', $f->no_faktur) . '<div class="small text-muted">' . self::e($f->kontak) . '</div>',
                '<span class="' . ($lewat ? 'text-danger font-weight-bold' : '') . '">' . tgl_singkat($f->jatuh_tempo) . '</span>', nf_acc($sisa));
        }
        return array('tipe' => 'tabel', 'kolom' => array(array('Jenis'), array('Faktur'), array('Jatuh tempo'), array('Sisa', 'num')), 'baris' => $b, 'kosong' => 'Tidak ada faktur jatuh tempo dalam 14 hari.');
    }
    private function w_keu_pajak()
    {
        $this->CI->load->library('akuntansi'); $this->CI->load->model('accounting/Mod_pajak');
        $y = (int) date('Y'); $p = $this->CI->Mod_pajak->ppn_tahun($y); $lab = array(); $k = array(); $m = array();
        for ($b = 1; $b <= (int) date('n'); $b++) { $lab[] = bulan_singkat($b); $k[] = $p['rows'][$b]->ppn_k; $m[] = $p['rows'][$b]->ppn_m; }
        $ini = $p['rows'][(int) date('n')];
        return array('tipe' => 'chart', 'chart' => self::g('', 'bar', $lab, array('PPN keluaran' => $k, 'PPN masukan' => $m)), 'link' => array('Pajak', 'Perpajakan'),
            'catatan' => 'Masa ' . bulan_panjang((int) date('n')) . ': ' . ($ini->kb > 0 ? 'kurang bayar ' . nf_acc($ini->kb) . ', setor paling lambat ' . tgl_singkat($ini->jt_setor) : ($ini->lb > 0 ? 'lebih bayar ' . nf_acc($ini->lb) : 'nihil')));
    }

    /* ======================= GUDANG ======================= */
    private function gd()
    {
        static $d = null;
        if ($d === null) { $this->CI->load->model('warehouse/Mod_lapgudang'); $d = $this->CI->Mod_lapgudang->dashboard(); }
        return $d;
    }
    private function lok() { $this->CI->load->model('warehouse/Mod_lapgudang'); $l = Mod_lapgudang::lok((string) $this->CI->session->userdata('lokasi')); return $l ?: 'cbt'; }
    private function w_wh_kpi()
    {
        $D = $this->gd(); $nilai = 0; $min = 0; foreach ($D['lokasi'] as $x) { $nilai += $x->nilai; $min += $x->min; }
        return array('tipe' => 'kpi', 'item' => array(
            self::kpi('Nilai persediaan', nf_acc($nilai), nf($D['item']) . ' barang terdaftar', 'ReportWhNilaiStok', 'fa-boxes'),
            self::kpi('Masuk bulan ini', nf_qty($D['bulan']['masuk']), count($D['bulan']['dok_m']) . ' dokumen · ' . nf_acc($D['bulan']['nm']), 'LaporanGudang/penerimaan', 'fa-arrow-down'),
            self::kpi('Keluar bulan ini', nf_qty($D['bulan']['keluar']), count($D['bulan']['dok_k']) . ' dokumen · ' . nf_acc($D['bulan']['nk']), 'LaporanGudang/pemakaian', 'fa-arrow-up'),
            self::kpi('Di bawah stok minimum', nf($min), 'semua gudang', 'LaporanGudang/stok_minimum', 'fa-exclamation-triangle', $min ? 'warn' : ''),
            self::kpi('PO belum datang', nf($D['po_ap']) . ' baris', nf_acc($D['po_ap_nilai']), 'LaporanGudang/outstanding', 'fa-truck', $D['po_ap'] ? 'blue' : ''),
        ));
    }
    private function w_wh_tren()
    {
        $D = $this->gd(); $lab = array(); $m = array(); $k = array();
        for ($i = 29; $i >= 0; $i--) { $t = date('Y-m-d', strtotime('-' . $i . ' day')); $lab[] = date('d/m', strtotime($t)); $v = isset($D['harian'][$t]) ? $D['harian'][$t] : array(0, 0); $m[] = $v[0]; $k[] = $v[1]; }
        return array('tipe' => 'chart', 'chart' => self::g('', 'bar', $lab, array('Masuk' => $m, 'Keluar' => $k), 'qty', array('tinggi' => 260)), 'link' => array('LaporanGudang/mutasi', 'Mutasi stok'));
    }
    private function w_wh_nilai()
    {
        $D = $this->gd(); $l = array(); foreach ($D['lokasi'] as $x) $l[$x->nama] = $x->nilai;
        return array('tipe' => 'chart', 'chart' => self::g('', 'doughnut', array_keys($l), array('Nilai' => $l)), 'link' => array('ReportWhNilaiStok', 'Nilai persediaan'));
    }
    private function w_wh_stok_min()
    {
        $this->CI->load->model('warehouse/Mod_lapgudang'); $l = $this->lok(); $r = $this->CI->Mod_lapgudang->stok_minimum($l); $b = array();
        foreach (array_slice($r['rows'], 0, 8) as $x) $b[] = array(self::a('LaporanGudang/kartu_stok?part=' . rawurlencode($x->no_part) . '&lok=' . $l, $x->no_part) . '<div class="small text-muted text-truncate" style="max-width:220px">' . self::e($x->nama) . '</div>',
            '<span class="' . ($x->stok <= 0 ? 'text-danger font-weight-bold' : 'text-warning') . '">' . nf_qty($x->stok) . '</span>', nf_qty($x->min), nf_qty($x->saran));
        return array('tipe' => 'tabel', 'kolom' => array(array('Barang'), array('Stok', 'num'), array('Min', 'num'), array('Saran pesan', 'num')), 'baris' => $b,
            'kosong' => 'Semua barang di atas stok minimum.', 'link' => array('LaporanGudang/stok_minimum?lok=' . $l, nf($r['total']->n) . ' barang · ' . Mod_lapgudang::$LOKASI[$l]));
    }
    private function w_wh_top_pakai()
    {
        $D = $this->gd(); $t = array(); foreach (array_slice($D['top'], 0, 10, true) as $k => $x) $t[$k] = $x['nilai'];
        return array('tipe' => 'chart', 'chart' => self::g('', 'horizontalBar', array_keys($t), array('Nilai pemakaian' => $t), 'rp', array('tinggi' => 280)), 'link' => array('LaporanGudang/pemakaian', 'Pemakaian'));
    }
    private function w_wh_opname()
    {
        if ( ! $this->t('tbl_wh_so')) return array('tipe' => 'html', 'html' => '<span class="text-muted">Belum ada data.</span>');
        $this->CI->load->model('warehouse/Mod_opname'); $b = array();
        foreach ($this->CI->db->where_in('status', array('DRAFT', 'HITUNG', 'REVIEW', 'DISETUJUI'))->order_by('id', 'DESC')->limit(5)->get('tbl_wh_so')->result() as $h) {
            $R = $this->CI->Mod_opname->ringkasan($h->id);
            $b[] = array(self::a('OpnameStok/lihat/' . $h->id, $h->no_so) . '<div class="small text-muted">' . Mod_opname::$STATUS[$h->status] . '</div>', $R->persen . '%', $R->dihitung ? round($R->cocok / $R->dihitung * 100) . '%' : '-');
        }
        return array('tipe' => 'tabel', 'kolom' => array(array('Dokumen'), array('Dihitung', 'num'), array('Akurasi', 'num')), 'baris' => $b, 'kosong' => 'Belum ada stock opname.', 'link' => array('OpnameStok', 'Stock opname'));
    }
    private function w_wh_kenaikan()
    {
        $this->CI->load->model('warehouse/Mod_hargarata'); $k = $this->CI->Mod_hargarata->kenaikan((int) date('Y'));
        $html = '<div class="dw-big ' . ($k['inflasi'] > 0 ? 'text-danger' : 'text-success') . '">' . ($k['inflasi'] === null ? '-' : ($k['inflasi'] > 0 ? '+' : '') . nf($k['inflasi'], 1) . '%') . '</div>'
            . '<div class="small text-muted">harga beli rata-rata ' . date('Y') . ' vs ' . (date('Y') - 1) . ' (tertimbang qty)</div>'
            . '<div class="mt-2 small">Dampak biaya <b>' . nf_acc($k['dampak']) . '</b> · naik ' . nf($k['naik']) . ' / turun ' . nf($k['turun']) . ' barang</div>';
        return array('tipe' => 'html', 'html' => $html, 'link' => array('LaporanGudang/kenaikan_harga', 'Detail kenaikan harga'));
    }
    private function w_wh_slow()
    {
        $this->CI->load->model('warehouse/Mod_lapgudang'); $l = $this->lok(); $r = $this->CI->Mod_lapgudang->slow_moving($l, 180, date('Y-m-d')); $T = $r['total'];
        $html = '<div class="dw-big">' . nf_acc($T->nilai) . '</div><div class="small text-muted">' . nf($T->n) . ' barang tidak keluar &gt; 180 hari · ' . Mod_lapgudang::$LOKASI[$l] . '</div>'
            . '<div class="mt-2 small">Tidak pernah keluar: <b class="text-danger">' . nf($T->mati) . '</b> barang (' . nf_acc($T->nilai_mati) . ')</div>';
        return array('tipe' => 'html', 'html' => $html, 'link' => array('LaporanGudang/slow_moving?hari=180&lok=' . $l, 'Slow moving'));
    }
    private function w_wh_po()
    {
        $this->CI->load->model('warehouse/Mod_lapgudang'); $o = $this->CI->Mod_lapgudang->outstanding(date('Y-m-d')); $b = array();
        foreach (array_slice($o['AP'], 0, 8) as $r) $b[] = array(self::e($r->no), self::e($r->kontak), self::e($r->no_part) . '<div class="small text-muted text-truncate" style="max-width:200px">' . self::e($r->nama) . '</div>', nf_qty($r->sisa), '<span class="' . ($r->hari > 30 ? 'text-danger' : '') . '">' . ($r->hari === null ? '-' : nf($r->hari) . ' hr') . '</span>', nf_acc($r->nilai));
        return array('tipe' => 'tabel', 'kolom' => array(array('PO'), array('Pemasok'), array('No. part'), array('Sisa', 'num'), array('Umur', 'num'), array('Nilai', 'num')), 'baris' => $b, 'kosong' => 'Semua PO sudah diterima.', 'link' => array('LaporanGudang/outstanding', 'PO outstanding'));
    }

    /* ======================= AFTER SALES ======================= */
    private function w_as_kpi()
    {
        if ( ! $this->t('tbl_after_sales')) return null;
        $db = $this->CI->db; $a = date('Y-m-01');
        $buka = $db->where("(date_close_wo IS NULL OR date_close_wo = '0000-00-00')", null, false)->count_all_results('tbl_after_sales');
        $baru = $db->where('date_open_wo >=', $a)->count_all_results('tbl_after_sales');
        $tutup = $db->where('date_close_wo >=', $a)->count_all_results('tbl_after_sales');
        $r = $db->select('SUM(amount_labor) AS j, SUM(part_turn_over) AS p', false)->where('date_open_wo >=', $a)->get('tbl_after_sales')->row();
        return array('tipe' => 'kpi', 'item' => array(
            self::kpi('WO terbuka', nf($buka), 'belum ditutup', 'WorkOrder', 'fa-folder-open', $buka ? 'warn' : ''),
            self::kpi('WO baru bulan ini', nf($baru), 'dibuka sejak ' . tgl_singkat($a), 'WorkOrder', 'fa-plus-circle'),
            self::kpi('WO selesai bulan ini', nf($tutup), 'ditutup', 'WoService', 'fa-check-circle'),
            self::kpi('Pendapatan jasa', nf_acc($r ? $r->j : 0), 'part ' . nf_acc($r ? $r->p : 0), 'ReportService', 'fa-coins'),
        ));
    }
    private function w_as_tren()
    {
        if ( ! $this->t('tbl_after_sales')) return null;
        $lab = array(); $o = array(); $c = array();
        for ($i = 5; $i >= 0; $i--) {
            $a = date('Y-m-01', strtotime('-' . $i . ' month', strtotime(date('Y-m-01')))); $b = date('Y-m-t', strtotime($a)); $lab[] = bulan_singkat((int) substr($a, 5, 2)) . ' ' . substr($a, 2, 2);
            $o[] = $this->CI->db->where('date_open_wo >=', $a)->where('date_open_wo <=', $b)->count_all_results('tbl_after_sales');
            $c[] = $this->CI->db->where('date_close_wo >=', $a)->where('date_close_wo <=', $b)->count_all_results('tbl_after_sales');
        }
        return array('tipe' => 'chart', 'chart' => self::g('', 'bar', $lab, array('Dibuka' => $o, 'Ditutup' => array('data' => $c, 'tipe' => 'line')), 'qty', array('tinggi' => 260)), 'link' => array('WoService', 'Laporan WO'));
    }
    private function w_as_status()
    {
        if ( ! $this->t('tbl_after_sales')) return null;
        $nama = array('F' => 'Selesai (F)', 'N' => 'Baru (N)', 'P' => 'Proses (P)', 'C' => 'Closed (C)', '' => 'Tanpa status'); $v = array();
        foreach ($this->CI->db->select('status, COUNT(*) AS n', false)->group_by('status')->get('tbl_after_sales')->result() as $r) $v[isset($nama[$r->status]) ? $nama[$r->status] : $r->status] = (int) $r->n;
        return array('tipe' => 'chart', 'chart' => self::g('', 'doughnut', array_keys($v), array('WO' => $v), 'qty'));
    }
    private function w_as_wo_open()
    {
        if ( ! $this->t('tbl_after_sales')) return null; $b = array();
        foreach ($this->CI->db->where("(date_close_wo IS NULL OR date_close_wo = '0000-00-00')", null, false)->order_by('date_open_wo')->limit(10)->get('tbl_after_sales')->result() as $r) {
            $h = $r->date_open_wo ? (int) floor((time() - strtotime($r->date_open_wo)) / 86400) : 0;
            $b[] = array(self::e($r->wo_no), tgl_singkat($r->date_open_wo), self::e($r->customer_name), self::e($r->no_pol), self::e($r->sa_name), '<span class="' . ($h > 7 ? 'text-danger font-weight-bold' : '') . '">' . nf($h) . ' hr</span>');
        }
        return array('tipe' => 'tabel', 'kolom' => array(array('WO'), array('Dibuka'), array('Pelanggan'), array('No. pol'), array('SA'), array('Umur', 'num')), 'baris' => $b, 'kosong' => 'Tidak ada WO terbuka.', 'link' => array('WorkOrder', 'Work order'));
    }

    /* ======================= MARKETING ======================= */
    private function w_mk_kpi()
    {
        if ( ! $this->t('tbl_mk_chasis')) return null;
        $s = array('S' => 0, 'K' => 0, 'C' => 0); foreach ($this->CI->db->select('status_chasis, COUNT(*) AS n', false)->group_by('status_chasis')->get('tbl_mk_chasis')->result() as $r) $s[$r->status_chasis] = (int) $r->n;
        $spk = $this->t('tbl_mk_spk') ? $this->CI->db->where('tgl_spk >=', date('Y-m-01'))->count_all_results('tbl_mk_spk') : 0;
        $masuk = $this->CI->db->where('tgl_masuk >=', date('Y-01-01'))->count_all_results('tbl_mk_chasis');
        return array('tipe' => 'kpi', 'item' => array(
            self::kpi('Chasis stok (S)', nf($s['S']), 'siap dijual', 'Chasis', 'fa-truck'),
            self::kpi('Chasis keluar (K)', nf($s['K']), 'terkirim / terjual', 'Chasis', 'fa-truck-moving'),
            self::kpi('Chasis C', nf($s['C']), 'status C', 'Chasis', 'fa-clipboard'),
            self::kpi('SPK bulan ini', nf($spk), 'chasis masuk ' . date('Y') . ': ' . nf($masuk), 'ListSpk', 'fa-file-signature'),
        ));
    }
    private function w_mk_sales()
    {
        if ( ! $this->t('tbl_mk_chasis')) return null; $v = array();
        foreach ($this->CI->db->select('sales, COUNT(*) AS n', false)->where('tgl_masuk >=', date('Y-01-01'))->group_by('sales')->get('tbl_mk_chasis')->result() as $r) $v[trim($r->sales) !== '' ? trim($r->sales) : '(tanpa sales)'] = (int) $r->n;
        arsort($v);
        if ( ! $v) foreach ($this->CI->db->select('sales, COUNT(*) AS n', false)->group_by('sales')->get('tbl_mk_chasis')->result() as $r) $v[trim($r->sales) !== '' ? trim($r->sales) : '(tanpa sales)'] = (int) $r->n;
        return array('tipe' => 'chart', 'chart' => self::g('', 'horizontalBar', array_keys($v), array('Chasis' => $v), 'qty', array('tinggi' => 260)));
    }
    private function w_mk_tren()
    {
        if ( ! $this->t('tbl_mk_chasis')) return null; $lab = array(); $v = array();
        for ($i = 11; $i >= 0; $i--) { $a = date('Y-m-01', strtotime('-' . $i . ' month', strtotime(date('Y-m-01')))); $lab[] = bulan_singkat((int) substr($a, 5, 2)) . ' ' . substr($a, 2, 2);
            $v[] = $this->CI->db->where('tgl_masuk >=', $a)->where('tgl_masuk <=', date('Y-m-t', strtotime($a)))->count_all_results('tbl_mk_chasis'); }
        return array('tipe' => 'chart', 'chart' => self::g('', 'area', $lab, array('Chasis masuk' => $v), 'qty'));
    }
    private function w_mk_stok()
    {
        if ( ! $this->t('tbl_mk_chasis')) return null; $b = array();
        foreach ($this->CI->db->where('status_chasis', 'S')->order_by('tgl_masuk')->limit(10)->get('tbl_mk_chasis')->result() as $r) {
            $h = $r->tgl_masuk ? (int) floor((time() - strtotime($r->tgl_masuk)) / 86400) : 0;
            $b[] = array(self::e($r->no_rangka), self::e($r->type_body), self::e($r->thn_produksi), tgl_singkat($r->tgl_masuk), '<span class="' . ($h > 180 ? 'text-danger' : '') . '">' . nf($h) . ' hr</span>');
        }
        return array('tipe' => 'tabel', 'kolom' => array(array('No. rangka'), array('Tipe'), array('Tahun'), array('Masuk'), array('Umur stok', 'num')), 'baris' => $b, 'kosong' => 'Tidak ada chasis stok.', 'link' => array('Chasis', 'Stok chasis'));
    }

    /* ======================= HRD ======================= */
    private function pegawai()
    {
        static $p = null;
        if ($p === null) {
            $p = array();
            if ($this->t('tbl_hrd_pegawai')) foreach ($this->CI->db->get('tbl_hrd_pegawai')->result() as $r) {
                if (in_array(strtolower(trim((string) $r->status)), array('n', 'keluar', 'resign', 'nonaktif', '0'), true)) continue;
                $p[] = $r;
            }
        }
        return $p;
    }
    private static function tgl_ok($t) { return $t && $t !== '0000-00-00' && strtotime($t) !== false; }
    private function w_hr_kpi()
    {
        $P = $this->pegawai(); $baru = 0; $mk = 0; $nmk = 0; $ul = 0; $bln = date('m');
        foreach ($P as $r) {
            if (self::tgl_ok($r->tgl_masuk)) { $th = (time() - strtotime($r->tgl_masuk)) / 31557600; $mk += $th; $nmk++; if ($r->tgl_masuk >= date('Y-01-01')) $baru++; }
            if (self::tgl_ok($r->tgl_lahir) && substr($r->tgl_lahir, 5, 2) === $bln) $ul++;
        }
        $cuti = $this->t('tbl_hrd_cuti_pegawai') ? $this->CI->db->where('tgl_cuti >=', date('Y-m-01'))->count_all_results('tbl_hrd_cuti_pegawai') : 0;
        $dept = $this->t('tbl_hrd_departement') ? $this->CI->db->count_all('tbl_hrd_departement') : 0;
        return array('tipe' => 'kpi', 'item' => array(
            self::kpi('Pegawai aktif', nf(count($P)), nf($dept) . ' departemen', 'Pegawai', 'fa-users'),
            self::kpi('Masuk tahun ini', nf($baru), 'pegawai baru ' . date('Y'), 'Pegawai', 'fa-user-plus'),
            self::kpi('Rata-rata masa kerja', $nmk ? nf($mk / $nmk, 1) . ' th' : '-', 'dari ' . nf($nmk) . ' pegawai bertanggal masuk', 'LaporanHR/masa_kerja', 'fa-business-time'),
            self::kpi('Cuti bulan ini', nf($cuti), 'ulang tahun bulan ini: ' . nf($ul), 'LaporanHR/cuti', 'fa-plane-departure'),
        ));
    }
    private function w_hr_dept()
    {
        $nm = array(); if ($this->t('tbl_hrd_departement')) foreach ($this->CI->db->get('tbl_hrd_departement')->result() as $d) $nm[$d->id_departement] = $d->departement;
        $v = array(); foreach ($this->pegawai() as $r) { $k = isset($nm[$r->departement]) ? $nm[$r->departement] : 'Belum diisi'; $v[$k] = (isset($v[$k]) ? $v[$k] : 0) + 1; }
        arsort($v);
        return array('tipe' => 'chart', 'chart' => self::g('', 'doughnut', array_keys($v), array('Pegawai' => $v), 'qty'), 'link' => array('LaporanHR/komposisi', 'Komposisi pegawai'));
    }
    private function w_hr_masa_kerja()
    {
        $b = array('< 1 th' => 0, '1-3 th' => 0, '3-5 th' => 0, '5-10 th' => 0, '> 10 th' => 0, 'Belum diisi' => 0);
        foreach ($this->pegawai() as $r) {
            if ( ! self::tgl_ok($r->tgl_masuk)) { $b['Belum diisi']++; continue; }
            $t = (time() - strtotime($r->tgl_masuk)) / 31557600;
            $b[$t < 1 ? '< 1 th' : ($t < 3 ? '1-3 th' : ($t < 5 ? '3-5 th' : ($t < 10 ? '5-10 th' : '> 10 th')))]++;
        }
        return array('tipe' => 'chart', 'chart' => self::g('', 'bar', array_keys($b), array('Pegawai' => $b), 'qty'), 'link' => array('LaporanHR/masa_kerja', 'Masa kerja'));
    }
    private function w_hr_ultah()
    {
        $b = array(); $hari = strtotime(date('Y-m-d'));
        foreach ($this->pegawai() as $r) {
            if ( ! self::tgl_ok($r->tgl_lahir)) continue;
            $u = strtotime(date('Y') . substr($r->tgl_lahir, 4)); if ($u < $hari) $u = strtotime((date('Y') + 1) . substr($r->tgl_lahir, 4));
            $sel = (int) round(($u - $hari) / 86400); if ($sel > 30) continue;
            $b[$sel . '_' . $r->nip] = array(self::e(trim($r->nama_depan . ' ' . $r->nama_belakang)), tgl_singkat(date('Y-m-d', $u)), $sel === 0 ? '<b class="text-success">Hari ini 🎉</b>' : nf($sel) . ' hari lagi');
        }
        ksort($b, SORT_NATURAL);
        return array('tipe' => 'tabel', 'kolom' => array(array('Nama'), array('Tanggal'), array('', 'num')), 'baris' => array_slice(array_values($b), 0, 8), 'kosong' => 'Tidak ada ulang tahun dalam 30 hari.');
    }
    private function w_hr_cuti()
    {
        if ( ! $this->t('tbl_hrd_cuti_pegawai')) return null;
        $nm = array(); foreach ($this->pegawai() as $p) $nm[$p->nip] = trim($p->nama_depan . ' ' . $p->nama_belakang);
        $b = array();
        foreach ($this->CI->db->order_by('tgl_cuti', 'DESC')->limit(8)->get('tbl_hrd_cuti_pegawai')->result() as $c)
            $b[] = array(self::e(isset($nm[$c->nip]) ? $nm[$c->nip] : $c->nip), tgl_singkat($c->tgl_cuti), self::e($c->alasan), '<span class="badge badge-light border">' . self::e($c->status_cuti) . '</span>');
        return array('tipe' => 'tabel', 'kolom' => array(array('Pegawai'), array('Tanggal'), array('Alasan'), array('Status')), 'baris' => $b, 'kosong' => 'Belum ada data cuti.', 'link' => array('LaporanHR/cuti', 'Rekap cuti'));
    }
    private function w_hr_absen()
    {
        if ( ! $this->t('tbl_hrd_data_absen')) return null; $lab = array(); $v = array();
        $q = array(); foreach ($this->CI->db->select('SUBSTR(date_time,1,10) AS t, COUNT(DISTINCT pin) AS n', false)->where('date_time >=', date('Y-m-d', strtotime('-13 day')))->group_by('SUBSTR(date_time,1,10)', false)->get('tbl_hrd_data_absen')->result() as $r) $q[$r->t] = (int) $r->n;
        for ($i = 13; $i >= 0; $i--) { $t = date('Y-m-d', strtotime('-' . $i . ' day')); $lab[] = date('d/m', strtotime($t)); $v[] = isset($q[$t]) ? $q[$t] : 0; }
        return array('tipe' => 'chart', 'chart' => self::g('', 'bar', $lab, array('Pegawai hadir' => $v), 'qty'), 'link' => array('LaporanHR/absensi', 'Rekap absensi'),
            'catatan' => array_sum($v) ? '' : 'Belum ada data dari mesin absen pada 14 hari terakhir.');
    }

    /* ======================= UMUM ======================= */
    private function w_sys_pengumuman()
    {
        $r = $this->t('tbl_dashboard_layout') ? $this->CI->db->get_where('tbl_dashboard_layout', array('jenis' => 'PENGUMUMAN', 'target' => '0'))->row() : null;
        $d = $r ? json_decode($r->data, true) : null;
        if ( ! $d || trim((string) ($d['isi'] ?? '')) === '' || ( ! empty($d['sampai']) && $d['sampai'] < date('Y-m-d'))) return null;
        return array('tipe' => 'pengumuman', 'warna' => $d['warna'] ?? 'info', 'judul' => $d['judul'] ?? 'Pengumuman', 'html' => nl2br(html_escape($d['isi'])), 'oleh' => $r->updated_by, 'tgl' => $r->updated_at);
    }
    private function w_sys_pintasan()
    {
        $p = $this->CI->session->userdata('pref'); $fav = isset($p['favorit']) ? (array) $p['favorit'] : array();
        $lv = (int) $this->CI->session->userdata('id_level');
        $this->CI->db->select('s.link, s.nama_submenu, s.icon')->from('tbl_submenu s')->where('s.is_active', 'Y');
        if ($lv !== 1) $this->CI->db->join('tbl_akses_submenu a', 'a.id_submenu = s.id_submenu')->where('a.id_level', $lv)->where('a.view_level', 'Y');
        $semua = array(); foreach ($this->CI->db->get()->result() as $r) $semua[$r->link] = $r;
        $pil = array(); foreach ($fav as $l) if (isset($semua[$l])) $pil[$l] = $semua[$l];
        foreach (array('OpnameStok', 'LabelBarang/scan', 'LaporanGudang', 'LaporanKeuangan', 'KasBank', 'Part_keluar', 'WorkOrder', 'Chasis', 'Pegawai', 'LaporanHR', 'PanduanSistem') as $l) {
            if (count($pil) >= 12) break;
            if (isset($semua[$l]) && ! isset($pil[$l])) $pil[$l] = $semua[$l];
        }
        if ( ! $pil) return array('tipe' => 'html', 'html' => '<span class="text-muted">Sematkan menu favorit (ikon ☆ di sidebar) agar tampil di sini.</span>');
        $h = '<div class="dw-pintas">';
        foreach ($pil as $l => $r) $h .= '<a href="' . site_url($l) . '"><i class="' . self::e($r->icon ?: 'fas fa-circle') . '"></i><span>' . self::e(menu_t($r->nama_submenu)) . '</span></a>';
        return array('tipe' => 'html', 'html' => $h . '</div>');
    }
}
