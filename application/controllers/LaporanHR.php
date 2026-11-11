<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pusat Laporan HRD: komposisi pegawai, masa kerja, ulang tahun, rekap cuti, rekap absensi, kelengkapan data.
 * Semua laporan dapat dicetak (?cetak=1) & diekspor Excel (?export=xlsx), lengkap dengan grafik.
 * Hanya membaca data pegawai / cuti / mesin absen — alur input HRD tidak berubah.
 */
class LaporanHR extends MY_Controller
{
    public static $LAPORAN = array(
        'komposisi'   => array('Komposisi Pegawai', 'fa-users', 'Pegawai per departemen, jabatan, pendidikan, jenis kelamin, usia & status kerja', 'Pegawai'),
        'masa_kerja'  => array('Masa Kerja & Penghargaan', 'fa-business-time', 'Lama bekerja setiap pegawai, sebaran masa kerja, ulang tahun kerja 5/10/15/20 tahun', 'Pegawai'),
        'ulang_tahun' => array('Ulang Tahun Pegawai', 'fa-birthday-cake', 'Daftar ulang tahun per bulan', 'Pegawai'),
        'cuti'        => array('Rekap Cuti Tahunan', 'fa-plane-departure', 'Jumlah hari cuti per pegawai, per jenis & per bulan', 'Kehadiran'),
        'absensi'     => array('Rekap Absensi Bulanan', 'fa-fingerprint', 'Hari hadir, keterlambatan, jam kerja per pegawai dari mesin absen', 'Kehadiran'),
        'kelengkapan' => array('Kelengkapan Data Pegawai', 'fa-user-check', 'Data wajib yang belum diisi (KTP, NPWP, BPJS, tanggal lahir/masuk, dst.)', 'Kontrol'),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('hrd/Mod_laphr');
        $this->config->load('panduan', true, true);
        $this->wajib('view');
    }

    public function index()
    {
        $p = (array) $this->config->item('laporan_hr', 'panduan');
        $this->template->load('layoutbackend', 'hrd/laporan/hub', array('page' => 'Dashboard & Laporan HRD', 'judul' => 'HRD', 'laporan' => self::$LAPORAN, 'penjelasan' => $p,
            'kom' => $this->Mod_laphr->komposisi(), 'mk' => $this->Mod_laphr->masa_kerja(), 'kl' => $this->Mod_laphr->kelengkapan()));
    }

    private function render($kunci, $d, $f, $sub)
    {
        $info = self::$LAPORAN[$kunci]; $apl = $this->db->get('aplikasi', 1)->row(); $p = (array) $this->config->item('laporan_hr', 'panduan');
        $vars = array('kunci' => $kunci, 'info' => $info, 'partial' => 'hrd/laporan/p_' . $kunci, 'd' => $d, 'f' => $f, 'subjudul' => $sub, 'apl' => $apl,
            'perusahaan' => $apl ? $apl->nama_owner : '', 'page' => $info[0], 'judul' => 'Laporan HRD', 'akun' => array(), 'penjelasan' => isset($p[$kunci]) ? $p[$kunci] : null,
            'ctrl' => 'LaporanHR', 'grafik' => $this->grafik($kunci, $d));
        $exp = $this->input->get('export');
        if ($exp === 'xlsx' || $exp === 'xls') {
            $this->wajib('print');
            $html = $this->load->view('accounting/lapkeu/cetak', $vars + array('mode' => 'excel'), TRUE);
            while (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . preg_replace('/[^A-Za-z0-9]+/', '_', $info[0]) . '_' . date('Ymd_His') . '.xls"');
            echo "\xEF\xBB\xBF" . $html; exit;
        }
        if ($this->input->get('cetak')) { $this->wajib('print'); $this->load->view('accounting/lapkeu/cetak', $vars + array('mode' => 'print')); return; }
        $this->template->load('layoutbackend', 'accounting/lapkeu/shell', $vars);
    }

    private function tahun() { $t = (int) ($this->input->get('tahun') ?: date('Y')); return max(2000, min(2100, $t)); }
    private function bulan() { $b = (int) ($this->input->get('bulan') ?: date('n')); return max(1, min(12, $b)); }

    public function komposisi() { $this->render('komposisi', $this->Mod_laphr->komposisi(), array('jenis' => 'none'), 'Pegawai aktif per ' . tgl_id(date('Y-m-d'))); }
    public function masa_kerja() { $this->render('masa_kerja', $this->Mod_laphr->masa_kerja(), array('jenis' => 'none'), 'Per ' . tgl_id(date('Y-m-d'))); }
    public function ulang_tahun() { $b = $this->bulan(); $this->render('ulang_tahun', $this->Mod_laphr->ulang_tahun($b), array('jenis' => 'tahun_masa', 'tahun' => $this->tahun(), 'bulan' => $b), 'Bulan ' . bulan_panjang($b)); }
    public function cuti() { $t = $this->tahun(); $this->render('cuti', $this->Mod_laphr->cuti($t), array('jenis' => 'tahun', 'tahun' => $t), 'Tahun ' . $t); }
    public function absensi()
    {
        $t = $this->tahun(); $b = $this->bulan(); $j = preg_match('/^\d{2}:\d{2}$/', (string) $this->input->get('jam')) ? $this->input->get('jam') : '08:00';
        $this->render('absensi', $this->Mod_laphr->absensi($t, $b, $j), array('jenis' => 'tahun_masa', 'tahun' => $t, 'bulan' => $b), bulan_panjang($b) . ' ' . $t . ' · jam masuk ' . $j);
    }
    public function kelengkapan() { $this->render('kelengkapan', $this->Mod_laphr->kelengkapan(), array('jenis' => 'none'), 'Per ' . tgl_id(date('Y-m-d'))); }

    private static function g($judul, $tipe, $labels, $seri, $format = 'qty', $extra = array())
    {
        $s = array(); foreach ($seri as $n => $d) $s[] = is_array($d) && isset($d['data']) ? $d + array('nama' => $n) : array('nama' => $n, 'data' => array_values($d));
        return array('judul' => $judul, 'tipe' => $tipe, 'labels' => array_values($labels), 'seri' => $s, 'format' => $format) + $extra;
    }

    private function grafik($k, $d)
    {
        $G = array();
        try {
            switch ($k) {
                case 'komposisi':
                    if ($d['dept']) $G[] = self::g('Pegawai per departemen', 'doughnut', array_keys($d['dept']), array('Pegawai' => $d['dept']));
                    if ($d['jab']) $G[] = self::g('Pegawai per jabatan', 'horizontalBar', array_keys($d['jab']), array('Pegawai' => $d['jab']));
                    if ($d['pend']) $G[] = self::g('Pendidikan', 'bar', array_keys($d['pend']), array('Pegawai' => $d['pend']));
                    if ($d['umur']) $G[] = self::g('Kelompok usia', 'bar', array_keys($d['umur']), array('Pegawai' => $d['umur']));
                    if ($d['jk']) $G[] = self::g('Jenis kelamin', 'pie', array_keys($d['jk']), array('Pegawai' => $d['jk']));
                    break;
                case 'masa_kerja': $G[] = self::g('Sebaran masa kerja', 'bar', array_keys($d['kelompok']), array('Pegawai' => $d['kelompok'])); break;
                case 'ulang_tahun': $l = array(); for ($i = 1; $i <= 12; $i++) $l[] = bulan_singkat($i); $G[] = self::g('Ulang tahun per bulan', 'bar', $l, array('Pegawai' => $d['per_bulan'])); break;
                case 'cuti':
                    $l = array(); for ($i = 1; $i <= 12; $i++) $l[] = bulan_singkat($i);
                    $G[] = self::g('Hari cuti per bulan', 'bar', $l, array('Hari cuti' => $d['bulan']));
                    if ($d['jenis']) $G[] = self::g('Jenis cuti', 'doughnut', array_keys($d['jenis']), array('Hari' => $d['jenis']));
                    break;
                case 'absensi':
                    if ($d['harian']) $G[] = self::g('Pegawai hadir per hari', 'bar', array_map(function ($t) { return date('d', strtotime($t)); }, array_keys($d['harian'])), array('Hadir' => $d['harian']));
                    $tl = array(); foreach ($d['rows'] as $r) if ($r->telat) $tl[$r->nama] = $r->telat; arsort($tl);
                    if ($tl) $G[] = self::g('Keterlambatan terbanyak (hari)', 'horizontalBar', array_keys(array_slice($tl, 0, 10, true)), array('Hari terlambat' => array_slice($tl, 0, 10, true)));
                    break;
                case 'kelengkapan': $x = $d['per_kolom']; arsort($x); $G[] = self::g('Data yang belum diisi (jumlah pegawai)', 'horizontalBar', array_keys($x), array('Pegawai' => $x), 'qty', array('tinggi' => 320)); break;
            }
        } catch (\Throwable $e) { log_message('error', 'grafik HR ' . $k . ': ' . $e->getMessage()); }
        return $G;
    }
}
