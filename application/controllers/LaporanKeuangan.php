<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pusat Laporan Keuangan (konsep "Report Center" Accurate)
 * Semua laporan mendukung: tampil di layar, cetak (?cetak=1) & ekspor Excel (?export=xlsx)
 */
class LaporanKeuangan extends MY_Controller
{
    /** daftar laporan: method => [judul, ikon, deskripsi, grup] */
    public static $LAPORAN = array(
        // --- Laporan keuangan utama
        'neraca'            => array('Neraca (Posisi Keuangan)', 'fa-balance-scale', 'Aset, liabilitas & ekuitas per tanggal, bisa dibandingkan', 'Laporan Keuangan Utama'),
        'laba_rugi'         => array('Laba Rugi', 'fa-chart-line', 'Standar, % pendapatan, per bulan, atau dibandingkan periode sebelumnya', 'Laporan Keuangan Utama'),
        'arus_kas'          => array('Arus Kas', 'fa-water', 'Metode langsung: operasi, investasi, pendanaan', 'Laporan Keuangan Utama'),
        'perubahan_ekuitas' => array('Perubahan Ekuitas', 'fa-layer-group', 'Mutasi modal & laba ditahan', 'Laporan Keuangan Utama'),
        'paket'             => array('Laporan Keuangan Lengkap + CaLK', 'fa-book-open', 'Neraca komparatif, Laba Rugi, Ekuitas, Arus Kas & Catatan atas Laporan Keuangan dalam satu dokumen', 'Laporan Keuangan Utama'),
        // --- buku besar
        'neraca_saldo'      => array('Neraca Saldo', 'fa-th-list', 'Saldo awal, mutasi & saldo akhir seluruh akun', 'Buku Besar & Jurnal'),
        'buku_besar'        => array('Buku Besar', 'fa-book', 'Rincian transaksi per akun dengan saldo berjalan', 'Buku Besar & Jurnal'),
        'daftar_jurnal'     => array('Daftar Jurnal', 'fa-list-ol', 'Semua jurnal per periode & sumber transaksi', 'Buku Besar & Jurnal'),
        'mutasi_kas'        => array('Mutasi Kas & Bank', 'fa-wallet', 'Rekening koran internal per akun kas/bank', 'Buku Besar & Jurnal'),
        // --- piutang & hutang
        'umur_piutang'      => array('Umur Piutang', 'fa-hourglass-half', 'Aging piutang per pelanggan', 'Piutang & Hutang'),
        'umur_hutang'       => array('Umur Hutang', 'fa-hourglass-end', 'Aging hutang per pemasok', 'Piutang & Hutang'),
        'kartu_piutang'     => array('Kartu Piutang (Buku Pembantu)', 'fa-address-card', 'Saldo awal, faktur, pelunasan & saldo berjalan per pelanggan + rekonsiliasi akun', 'Piutang & Hutang'),
        'kartu_hutang'      => array('Kartu Hutang (Buku Pembantu)', 'fa-id-card', 'Saldo awal, faktur, pembayaran & saldo berjalan per pemasok + rekonsiliasi akun', 'Piutang & Hutang'),
        'faktur_penjualan'  => array('Rincian Faktur Penjualan', 'fa-file-invoice-dollar', 'DPP, PPN, total, terbayar & sisa per faktur, bisa dengan rincian baris', 'Piutang & Hutang'),
        'faktur_pembelian'  => array('Rincian Faktur Pembelian', 'fa-file-invoice', 'DPP, PPN, total, terbayar & sisa per faktur pemasok', 'Piutang & Hutang'),
        // --- perpajakan
        'pajak_tahunan'     => array('Rekap Pajak Tahunan', 'fa-receipt', 'Per masa: peredaran bruto, PPN, PPh 21/23/4(2), PPh final, total kewajiban & setoran', 'Perpajakan'),
        'ppn_masa'          => array('Rincian PPN per Masa', 'fa-file-alt', 'Daftar Pajak Keluaran & Masukan (DPP, DPP nilai lain, NSFP) — bahan SPT Masa PPN', 'Perpajakan'),
        'buku_pajak'        => array('Buku Pembantu Akun Pajak', 'fa-book-reader', 'Saldo awal, bertambah, berkurang & saldo akhir tiap akun pajak per bulan', 'Perpajakan'),
        'bukti_potong'      => array('Daftar Bukti Potong PPh', 'fa-stamp', 'PPh dipotong pelanggan (kredit pajak) & PPh yang kita potong dari pemasok', 'Perpajakan'),
        'setoran_pajak'     => array('Daftar Setoran Pajak (NTPN)', 'fa-money-check-alt', 'Seluruh setoran pajak beserta NTPN per jenis pajak', 'Perpajakan'),
        'rekon_fiskal'      => array('Rekonsiliasi Fiskal & PPh Badan', 'fa-calculator', 'Laba komersial → koreksi fiskal → PKP → PPh terutang, kredit pajak, PPh 29 & PPh 25', 'Perpajakan'),
        // --- aset & anggaran
        'aset'              => array('Daftar Aset Tetap', 'fa-building', 'Harga perolehan, akumulasi & nilai buku', 'Aset & Anggaran'),
        'jadwal_susut'      => array('Jadwal Penyusutan', 'fa-calendar-alt', 'Penyusutan per aset per bulan (realisasi & proyeksi) dalam 1 tahun', 'Aset & Anggaran'),
        'anggaran'          => array('Anggaran vs Realisasi', 'fa-bullseye', 'Perbandingan anggaran laba rugi dengan realisasi', 'Aset & Anggaran'),
        // --- analisis
        'rasio'             => array('Analisis Rasio Keuangan', 'fa-tachometer-alt', 'Likuiditas, solvabilitas, profitabilitas & aktivitas lengkap dengan tafsiran', 'Analisis & Kontrol'),
        'cek_data'          => array('Pemeriksaan Kesehatan Data', 'fa-stethoscope', 'Cek jurnal tidak seimbang, akun tanpa tipe, saldo awal, rekonsiliasi, PPN & penyusutan', 'Analisis & Kontrol'),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_lapkeu', 'accounting/Mod_lapdetail', 'accounting/Mod_pajak'));
        $this->load->library('akuntansi');
        $this->config->load('panduan', true, true);
        $this->load->helper('tgl_indo_helper');
        $this->wajib('view');
    }

    /* ---------------- hub ---------------- */
    public function index()
    {
        $data['page']  = 'Pusat Laporan Keuangan';
        $data['judul'] = 'Laporan';
        $data['laporan'] = self::$LAPORAN;
        $data['penjelasan'] = (array) $this->config->item('laporan', 'panduan');
        $this->template->load('layoutbackend', 'accounting/lapkeu/hub', $data);
    }

    /* ---------------- util filter ---------------- */
    private function f_tgl($key, $default)
    {
        $v = tgl_sql($this->input->get($key));
        return $v ?: $default;
    }

    private function filter_periode()
    {
        $dari = $this->f_tgl('dari', date('Y-m-01'));
        $sampai = $this->f_tgl('sampai', date('Y-m-d'));
        if ($sampai < $dari) { $t = $dari; $dari = $sampai; $sampai = $t; }
        return array($dari, $sampai);
    }

    private function render($kunci, $partial, $d, $f, $subjudul)
    {
        $info = self::$LAPORAN[$kunci];
        $apl  = $this->db->get('aplikasi', 1)->row();
        $vars = array(
            'kunci' => $kunci, 'info' => $info, 'partial' => $partial, 'd' => $d, 'f' => $f,
            'subjudul' => $subjudul, 'apl' => $apl,
            'perusahaan' => $this->akuntansi->setting('nama_perusahaan_laporan', $apl ? $apl->nama_owner : ''),
            'page' => $info[0], 'judul' => 'Laporan Keuangan',
            'akun' => $this->akuntansi->akun(),
            'penjelasan' => $this->penjelasan($kunci),
            'grafik' => $this->grafik($kunci, $d, $f),
        );
        $export = $this->input->get('export');
        if ($export === 'xlsx' || $export === 'xls') {
            $this->wajib('print');
            $html = $this->load->view('accounting/lapkeu/cetak', $vars + array('mode' => 'excel'), TRUE);
            $this->_excel($html, $info[0]);
            return;
        }
        if ($this->input->get('cetak')) {
            $this->wajib('print');
            $this->load->view('accounting/lapkeu/cetak', $vars + array('mode' => 'print'));
            return;
        }
        $this->template->load('layoutbackend', 'accounting/lapkeu/shell', $vars);
    }

    private function _excel($html, $judul)
    {
        $nama = preg_replace('/[^A-Za-z0-9]+/', '_', $judul) . '_' . date('Ymd_His');
        while (ob_get_level() > 0) ob_end_clean();
        if (class_exists('\PhpOffice\PhpSpreadsheet\Reader\Html')) {
            try {
                $tmp = tempnam(sys_get_temp_dir(), 'lap') . '.html';
                file_put_contents($tmp, $html);
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
                $sheet = $reader->load($tmp);
                @unlink($tmp);
                $ws = $sheet->getActiveSheet();
                $ws->setTitle(substr(preg_replace('/[^A-Za-z0-9 ]/', '', $judul), 0, 30));
                foreach (range('A', 'L') as $col) $ws->getColumnDimension($col)->setAutoSize(true);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $nama . '.xlsx"');
                header('Cache-Control: max-age=0');
                $w = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
                $w->save('php://output');
                exit;
            } catch (\Throwable $e) {
                log_message('error', 'Ekspor Excel gagal: ' . $e->getMessage());
            }
        }
        // fallback: HTML yang bisa dibuka Excel
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nama . '.xls"');
        echo "\xEF\xBB\xBF" . $html;
        exit;
    }

    private function penjelasan($kunci)
    {
        $p = (array) $this->config->item('laporan', 'panduan');
        return isset($p[$kunci]) ? $p[$kunci] : null;
    }

    private function f_tahun()
    {
        $t = (int) $this->input->get('tahun');
        return ($t >= 2000 && $t <= 2100) ? $t : (int) date('Y');
    }

    private function label_periode($dari, $sampai)
    {
        return 'Periode ' . tgl_id($dari) . ' s/d ' . tgl_id($sampai);
    }

    /* ---------------- laporan ---------------- */
    public function neraca_saldo()
    {
        list($dari, $sampai) = $this->filter_periode();
        $nol = (bool) $this->input->get('nol');
        $d = $this->Mod_lapkeu->neraca_saldo($dari, $sampai, $nol);
        $this->render('neraca_saldo', 'neraca_saldo', $d, array('dari' => $dari, 'sampai' => $sampai, 'nol' => $nol, 'jenis' => 'periode'), $this->label_periode($dari, $sampai));
    }

    public function laba_rugi()
    {
        list($dari, $sampai) = $this->filter_periode();
        $mode = $this->input->get('mode') ?: 'standar';
        $kolom = array();
        if ($mode === 'bulanan') {
            $t = strtotime(substr($dari, 0, 7) . '-01'); $i = 0;
            while ($t <= strtotime($sampai) && $i < 12) {
                $a = date('Y-m-01', $t); $b = date('Y-m-t', $t);
                $kolom[] = array('label' => bulan_singkat((int) date('n', $t)) . ' ' . date('Y', $t), 'dari' => max($a, $dari), 'sampai' => min($b, $sampai));
                $t = strtotime('+1 month', $t); $i++;
            }
            $kolom[] = array('label' => 'Total', 'dari' => $dari, 'sampai' => $sampai);
        } elseif ($mode === 'banding') {
            $hari = (int) round((strtotime($sampai) - strtotime($dari)) / 86400);
            $pb = date('Y-m-d', strtotime($dari . ' -1 day'));
            $pa = date('Y-m-d', strtotime($pb . ' -' . $hari . ' day'));
            if (substr($dari, 8) === '01' && $sampai === date('Y-m-t', strtotime($sampai))) {
                // periode bulanan penuh -> bandingkan dengan bulan sebanyak sama sebelumnya
                $bln = (int) round($hari / 30.4) + 1;
                $pa = date('Y-m-01', strtotime($dari . ' -' . $bln . ' month'));
                $pb = date('Y-m-t', strtotime($dari . ' -1 month'));
            }
            $kolom[] = array('label' => tgl_singkat($dari) . ' - ' . tgl_singkat($sampai), 'dari' => $dari, 'sampai' => $sampai);
            $kolom[] = array('label' => tgl_singkat($pa) . ' - ' . tgl_singkat($pb), 'dari' => $pa, 'sampai' => $pb);
        } else {
            $kolom[] = array('label' => 'Jumlah', 'dari' => $dari, 'sampai' => $sampai);
        }
        $d = $this->Mod_lapkeu->laba_rugi($kolom);
        $d['mode'] = $mode;
        $this->render('laba_rugi', 'laba_rugi', $d, array('dari' => $dari, 'sampai' => $sampai, 'mode' => $mode, 'persen' => (bool) $this->input->get('persen'), 'jenis' => 'periode'), $this->label_periode($dari, $sampai));
    }

    public function neraca()
    {
        $tgl = $this->f_tgl('tgl', date('Y-m-d'));
        $banding = tgl_sql($this->input->get('banding'));
        $tanggal = array($tgl);
        if ($banding) $tanggal[] = $banding;
        $d = $this->Mod_lapkeu->neraca($tanggal);
        $this->render('neraca', 'neraca', $d, array('tgl' => $tgl, 'banding' => $banding, 'jenis' => 'tanggal'), 'Per ' . tgl_id($tgl));
    }

    public function arus_kas()
    {
        list($dari, $sampai) = $this->filter_periode();
        $d = $this->Mod_lapkeu->arus_kas($dari, $sampai);
        $this->render('arus_kas', 'arus_kas', $d, array('dari' => $dari, 'sampai' => $sampai, 'jenis' => 'periode'), $this->label_periode($dari, $sampai));
    }

    public function perubahan_ekuitas()
    {
        list($dari, $sampai) = $this->filter_periode();
        $d = $this->Mod_lapkeu->perubahan_ekuitas($dari, $sampai);
        $this->render('perubahan_ekuitas', 'perubahan_ekuitas', $d, array('dari' => $dari, 'sampai' => $sampai, 'jenis' => 'periode'), $this->label_periode($dari, $sampai));
    }

    public function buku_besar()
    {
        list($dari, $sampai) = $this->filter_periode();
        $kode = trim((string) $this->input->get('akun'));
        $d = $this->Mod_lapkeu->buku_besar($dari, $sampai, $kode !== '' ? $kode : null);
        $this->render('buku_besar', 'buku_besar', $d, array('dari' => $dari, 'sampai' => $sampai, 'akun' => $kode, 'jenis' => 'periode_akun'), $this->label_periode($dari, $sampai));
    }

    public function daftar_jurnal()
    {
        list($dari, $sampai) = $this->filter_periode();
        $sumber = (string) $this->input->get('sumber');
        $d = $this->Mod_lapkeu->daftar_jurnal($dari, $sampai, $sumber);
        $this->render('daftar_jurnal', 'daftar_jurnal', $d, array('dari' => $dari, 'sampai' => $sampai, 'sumber' => $sumber, 'jenis' => 'periode_sumber'), $this->label_periode($dari, $sampai));
    }

    public function mutasi_kas()
    {
        list($dari, $sampai) = $this->filter_periode();
        $kode = trim((string) $this->input->get('akun'));
        $d = $this->Mod_lapkeu->mutasi_kas($dari, $sampai, $kode !== '' ? $kode : null);
        $this->render('mutasi_kas', 'buku_besar', $d, array('dari' => $dari, 'sampai' => $sampai, 'akun' => $kode, 'jenis' => 'periode_kas'), $this->label_periode($dari, $sampai));
    }

    public function umur_piutang() { $this->_umur('AR', 'umur_piutang'); }
    public function umur_hutang()  { $this->_umur('AP', 'umur_hutang'); }

    private function _umur($jenis, $kunci)
    {
        $tgl = $this->f_tgl('tgl', date('Y-m-d'));
        $d = array('jenis' => $jenis, 'rows' => $this->Mod_lapkeu->umur($jenis, $tgl), 'rinci' => (bool) $this->input->get('rinci'));
        $this->render($kunci, 'umur', $d, array('tgl' => $tgl, 'rinci' => $d['rinci'], 'jenis' => 'tanggal_rinci'), 'Per ' . tgl_id($tgl));
    }

    public function aset()
    {
        $tgl = $this->f_tgl('tgl', date('Y-m-d'));
        $d = $this->Mod_lapkeu->daftar_aset($tgl);
        $this->render('aset', 'aset', $d, array('tgl' => $tgl, 'jenis' => 'tanggal'), 'Per ' . tgl_id($tgl));
    }

    public function anggaran()
    {
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));
        $bulan = (int) ($this->input->get('bulan') ?: date('n'));
        $d = $this->Mod_lapkeu->anggaran_realisasi($tahun, $bulan);
        $this->render('anggaran', 'anggaran', $d, array('tahun' => $tahun, 'bulan' => $bulan, 'jenis' => 'tahun_bulan'),
            'Januari s/d ' . bulan_panjang($bulan) . ' ' . $tahun);
    }

    /* ---------------- laporan keuangan lengkap ---------------- */
    public function paket()
    {
        list($dari, $sampai) = $this->filter_periode();
        $banding = Akuntansi::kemarin($dari);
        $d = array(
            'neraca' => $this->Mod_lapkeu->neraca(array($sampai, $banding)),
            'lr' => $this->Mod_lapkeu->laba_rugi(array(array('label' => 'Jumlah', 'dari' => $dari, 'sampai' => $sampai))) + array('mode' => 'standar'),
            'pe' => $this->Mod_lapkeu->perubahan_ekuitas($dari, $sampai),
            'ak' => $this->Mod_lapkeu->arus_kas($dari, $sampai),
            'calk' => $this->Mod_lapdetail->calk($dari, $sampai),
        );
        $this->render('paket', 'paket', $d, array('dari' => $dari, 'sampai' => $sampai, 'persen' => true, 'jenis' => 'periode'), $this->label_periode($dari, $sampai));
    }

    /* ---------------- piutang & hutang ---------------- */
    public function kartu_piutang() { $this->_kartu('AR', 'kartu_piutang'); }
    public function kartu_hutang()  { $this->_kartu('AP', 'kartu_hutang'); }

    private function _kartu($jenis, $kunci)
    {
        list($dari, $sampai) = $this->filter_periode();
        $kontak = trim((string) $this->input->get('kontak'));
        $d = $this->Mod_lapdetail->kartu_kontak($jenis, $dari, $sampai, $kontak);
        $d['jenis'] = $jenis;
        $this->render($kunci, 'kartu', $d, array('dari' => $dari, 'sampai' => $sampai, 'kontak' => $kontak,
            'kontak_list' => $this->Mod_lapdetail->kontak_list($jenis), 'jenis' => 'periode_kontak'), $this->label_periode($dari, $sampai) . ($kontak !== '' ? ' — ' . $kontak : ''));
    }

    public function faktur_penjualan() { $this->_faktur('AR', 'faktur_penjualan'); }
    public function faktur_pembelian() { $this->_faktur('AP', 'faktur_pembelian'); }

    private function _faktur($jenis, $kunci)
    {
        list($dari, $sampai) = $this->filter_periode();
        $status = (string) $this->input->get('status');
        if ( ! in_array($status, array('', 'BELUM', 'SEBAGIAN', 'LUNAS', 'BATAL'), true)) $status = '';
        $rinci = (bool) $this->input->get('rinci');
        $d = array('jenis' => $jenis, 'rinci' => $rinci, 'rows' => $this->Mod_lapdetail->daftar_faktur($jenis, $dari, $sampai, $status, $rinci));
        $this->render($kunci, 'faktur', $d, array('dari' => $dari, 'sampai' => $sampai, 'status' => $status, 'rinci' => $rinci, 'jenis' => 'periode_faktur'), $this->label_periode($dari, $sampai));
    }

    /* ---------------- perpajakan ---------------- */
    public function pajak_tahunan()
    {
        $tahun = $this->f_tahun();
        $d = $this->Mod_pajak->ringkasan_tahunan($tahun);
        $d['ppn'] = $this->Mod_pajak->ppn_tahun($tahun);
        $this->render('pajak_tahunan', 'pajak_tahunan', $d, array('tahun' => $tahun, 'jenis' => 'tahun'), 'Tahun Pajak ' . $tahun);
    }

    public function ppn_masa()
    {
        $tahun = $this->f_tahun();
        $bulan = (int) ($this->input->get('bulan') ?: date('n'));
        $bulan = max(1, min(12, $bulan));
        $per = sprintf('%04d-%02d', $tahun, $bulan);
        $ppn = $this->Mod_pajak->ppn_tahun($tahun);
        $d = array('rincian' => $this->Mod_pajak->ppn_rincian($per), 'rekap' => $ppn['rows'][$bulan], 'profil' => $this->Mod_pajak->profil(), 'periode' => $per);
        $this->render('ppn_masa', 'ppn_masa', $d, array('tahun' => $tahun, 'bulan' => $bulan, 'jenis' => 'tahun_masa'), 'Masa Pajak ' . bulan_panjang($bulan) . ' ' . $tahun);
    }

    public function buku_pajak()
    {
        $tahun = $this->f_tahun();
        $rinci = (bool) $this->input->get('rinci');
        $d = array('rows' => $this->Mod_pajak->pph_buku($tahun), 'rinci' => $rinci, 'tahun' => $tahun);
        $this->render('buku_pajak', 'buku_pajak', $d, array('tahun' => $tahun, 'rinci' => $rinci, 'jenis' => 'tahun_rinci'), 'Tahun ' . $tahun);
    }

    public function bukti_potong()
    {
        $tahun = $this->f_tahun();
        $d = array('rows' => $this->Mod_pajak->bukti_potong($tahun));
        $this->render('bukti_potong', 'bukti_potong', $d, array('tahun' => $tahun, 'jenis' => 'tahun'), 'Tahun ' . $tahun);
    }

    public function setoran_pajak()
    {
        $tahun = $this->f_tahun();
        $rows = array_reverse($this->Mod_pajak->setor_list($tahun));
        $this->render('setoran_pajak', 'setoran', array('rows' => $rows), array('tahun' => $tahun, 'jenis' => 'tahun'), 'Tahun ' . $tahun);
    }

    public function rekon_fiskal()
    {
        $tahun = $this->f_tahun();
        $d = $this->Mod_pajak->rekon_fiskal($tahun);
        $this->render('rekon_fiskal', 'rekon_fiskal', $d, array('tahun' => $tahun, 'jenis' => 'tahun'), 'Tahun Pajak ' . $tahun);
    }

    /* ---------------- aset ---------------- */
    public function jadwal_susut()
    {
        $tahun = $this->f_tahun();
        $d = $this->Mod_lapdetail->jadwal_susut($tahun);
        $this->render('jadwal_susut', 'jadwal_susut', $d, array('tahun' => $tahun, 'jenis' => 'tahun'), 'Tahun ' . $tahun);
    }

    /* ---------------- analisis ---------------- */
    public function rasio()
    {
        $dari = $this->f_tgl('dari', date('Y-01-01'));
        $sampai = $this->f_tgl('sampai', date('Y-m-d'));
        if ($sampai < $dari) { $t = $dari; $dari = $sampai; $sampai = $t; }
        $d = $this->Mod_lapdetail->rasio($dari, $sampai);
        $this->render('rasio', 'rasio', $d, array('dari' => $dari, 'sampai' => $sampai, 'jenis' => 'periode'), $this->label_periode($dari, $sampai));
    }

    public function cek_data()
    {
        $tgl = $this->f_tgl('tgl', date('Y-m-d'));
        $d = $this->Mod_lapdetail->cek_data($tgl);
        $this->render('cek_data', 'cek_data', $d, array('tgl' => $tgl, 'jenis' => 'tanggal'), 'Per ' . tgl_id($tgl));
    }

    /* ================= GRAFIK (dapat diunduh PNG/CSV & ikut tercetak) ================= */
    private static function g($judul, $tipe, $labels, $seri, $format = 'rp', $extra = array())
    {
        $s2 = array();
        foreach ($seri as $nama => $data) $s2[] = is_array($data) && isset($data['data']) ? $data + array('nama' => $nama) : array('nama' => $nama, 'data' => array_map(function ($v) { return round((float) $v, 2); }, array_values($data)));
        return array('judul' => $judul, 'tipe' => $tipe, 'labels' => array_values($labels), 'seri' => $s2, 'format' => $format) + $extra;
    }

    private static function top($arr, $n = 10)
    {
        arsort($arr); $o = array_slice($arr, 0, $n, true);
        $sisa = array_sum(array_slice($arr, $n)); if (abs($sisa) > 0.5) $o['Lainnya'] = $sisa;
        return $o;
    }

    private function grafik($k, $d, $f)
    {
        $G = array();
        try {
            switch ($k) {
                case 'laba_rugi': case 'paket':
                    $lr = $k === 'paket' ? $d['lr'] : $d;
                    $S = $lr['seksi']; $R = $lr['ringkas']; $kol = $lr['kolom'];
                    $n = count($kol); $idx = range(0, $n - 1);
                    if (($lr['mode'] ?? '') === 'bulanan' && $n > 2) $idx = range(0, $n - 2);
                    if (count($idx) > 1) {
                        $lab = array(); $p = array(); $b = array(); $l = array();
                        foreach ($idx as $i) { $lab[] = $kol[$i]['label']; $p[] = $S['PENDAPATAN']->total[$i] + $S['PENDAPATAN_LAIN']->total[$i];
                            $b[] = $S['HPP']->total[$i] + $S['BEBAN']->total[$i] + $S['BEBAN_LAIN']->total[$i] + $S['PAJAK_PENGHASILAN']->total[$i]; $l[] = $R['laba_bersih'][$i]; }
                        $G[] = self::g('Pendapatan, beban & laba', 'bar', $lab, array('Pendapatan' => $p, 'Beban' => $b, 'Laba bersih' => array('data' => $l, 'tipe' => 'line')));
                    } else {
                        $G[] = self::g('Struktur laba rugi', 'bar', array('Pendapatan', 'HPP', 'Laba kotor', 'Beban operasi', 'Pend./beban lain', 'Pajak', 'Laba bersih'),
                            array('Nilai' => array($S['PENDAPATAN']->total[0], $S['HPP']->total[0], $R['laba_kotor'][0], $S['BEBAN']->total[0], $S['PENDAPATAN_LAIN']->total[0] - $S['BEBAN_LAIN']->total[0], $S['PAJAK_PENGHASILAN']->total[0], $R['laba_bersih'][0])));
                    }
                    $it = $n - 1; // kolom total (mode bulanan) / satu-satunya kolom
                    if (($lr['mode'] ?? '') === 'banding') $it = 0;
                    $bb = array(); foreach (array('HPP', 'BEBAN', 'BEBAN_LAIN') as $kat) foreach ($S[$kat]->rows as $r) if ($r->nilai[$it] > 0) $bb[$r->nama] = $r->nilai[$it];
                    if ($bb) { $t = self::top($bb, 8); $G[] = self::g('Komposisi biaya terbesar', 'doughnut', array_keys($t), array('Biaya' => $t)); }
                    if ($k === 'paket') { $nr = $d['neraca']; $a = array(); foreach ($nr['grup']['ASET']->sub as $sub) $a[$sub->label] = $sub->total[0];
                        $G[] = self::g('Komposisi aset', 'doughnut', array_keys($a), array('Aset' => $a)); }
                    break;
                case 'neraca':
                    $a = array(); foreach ($d['grup']['ASET']->sub as $sub) $a[$sub->label] = $sub->total[0];
                    $G[] = self::g('Komposisi aset', 'doughnut', array_keys($a), array('Aset' => $a));
                    $lab = array(); foreach ($d['tanggal'] as $t) $lab[] = tgl_singkat($t);
                    $G[] = self::g('Aset vs liabilitas & ekuitas', 'bar', $lab, array('Aset' => $d['grup']['ASET']->total, 'Liabilitas' => $d['grup']['LIABILITAS']->total, 'Ekuitas' => $d['grup']['EKUITAS']->total));
                    break;
                case 'arus_kas':
                    $G[] = self::g('Arus kas per aktivitas', 'bar', array('Kas awal', 'Operasi', 'Investasi', 'Pendanaan', 'Kas akhir'),
                        array('Nilai' => array($d['awal'], $d['total']['OPERASI'], $d['total']['INVESTASI'], $d['total']['PENDANAAN'], $d['akhir'])));
                    break;
                case 'umur_piutang': case 'umur_hutang':
                    $b = array('b0' => 0, 'b1' => 0, 'b2' => 0, 'b3' => 0, 'b4' => 0); $kt = array();
                    foreach ($d['rows'] as $r) { foreach ($b as $x => $v) $b[$x] += $r->$x; $kt[$r->kontak] = $r->total; }
                    $G[] = self::g('Umur saldo', 'bar', array('Belum jatuh tempo', '1-30 hari', '31-60 hari', '61-90 hari', '> 90 hari'), array('Saldo' => array_values($b)));
                    if ($kt) { $t = self::top($kt, 10); $G[] = self::g('Saldo terbesar per ' . ($k === 'umur_piutang' ? 'pelanggan' : 'pemasok'), 'horizontalBar', array_keys($t), array('Saldo' => $t)); }
                    break;
                case 'kartu_piutang': case 'kartu_hutang':
                    $kt = array(); foreach ($d['kontak'] as $c) if ($c->akhir != 0) $kt[$c->kontak] = $c->akhir;
                    if ($kt) { $t = self::top($kt, 10); $G[] = self::g('Saldo akhir per kontak', 'horizontalBar', array_keys($t), array('Saldo' => $t)); }
                    break;
                case 'faktur_penjualan': case 'faktur_pembelian':
                    $bl = array(); $st = array();
                    foreach ($d['rows'] as $r) { if ($r->status === 'BATAL') continue; $m = substr($r->tanggal, 0, 7); $bl[$m] = ($bl[$m] ?? 0) + $r->total; $st[$r->status] = ($st[$r->status] ?? 0) + $r->total; }
                    ksort($bl);
                    if ($bl) $G[] = self::g('Total faktur per bulan', 'bar', array_map(function ($m) { return bulan_singkat((int) substr($m, 5)) . ' ' . substr($m, 0, 4); }, array_keys($bl)), array('Total' => $bl));
                    if ($st) $G[] = self::g('Status pembayaran (nilai)', 'doughnut', array_keys($st), array('Nilai' => $st));
                    break;
                case 'pajak_tahunan':
                    $lab = array(); $k1 = array(); $m1 = array(); $kw = array(); $se = array();
                    foreach ($d['rows'] as $b => $r) { $lab[] = bulan_singkat($b); $k1[] = $r->ppn_k; $m1[] = $r->ppn_m; $kw[] = $r->kewajiban; $se[] = $r->setor; }
                    $G[] = self::g('PPN keluaran vs masukan', 'bar', $lab, array('PPN Keluaran' => $k1, 'PPN Masukan' => $m1));
                    $G[] = self::g('Kewajiban pajak vs setoran', 'line', $lab, array('Kewajiban' => $kw, 'Disetor' => $se));
                    break;
                case 'rekon_fiskal':
                    $G[] = self::g('Dari laba komersial ke PPh', 'bar', array('Laba komersial', 'Koreksi +', 'Koreksi −', 'Laba fiskal', 'PPh terutang', 'Kredit pajak', 'PPh 29'),
                        array('Nilai' => array($d['laba_komersial'], $d['total_positif'], -$d['total_negatif'], $d['laba_fiskal'], $d['terutang'], $d['total_kredit'], $d['pph29'])));
                    break;
                case 'aset':
                    $g = array(); foreach ($d as $a) if ($a->status === 'AKTIF') $g[$a->kelompok_aset ?: 'Lainnya'] = ($g[$a->kelompok_aset ?: 'Lainnya'] ?? 0) + $a->nilai_buku;
                    if ($g) $G[] = self::g('Nilai buku per kelompok', 'doughnut', array_keys($g), array('Nilai buku' => $g));
                    break;
                case 'jadwal_susut':
                    $lab = array(); for ($i = 1; $i <= 12; $i++) $lab[] = bulan_singkat($i);
                    $G[] = self::g('Beban penyusutan per bulan', 'bar', $lab, array('Penyusutan' => $d['bulan']));
                    break;
                case 'anggaran':
                    $rows = array_slice($d['rows'], 0, 12);
                    if ($rows) $G[] = self::g('Anggaran vs realisasi', 'horizontalBar', array_map(function ($r) { return $r->nama; }, $rows),
                        array('Anggaran' => array_map(function ($r) { return $r->anggaran; }, $rows), 'Realisasi' => array_map(function ($r) { return $r->realisasi; }, $rows)), 'rp', array('tinggi' => 360));
                    break;
            }
        } catch (\Throwable $e) { log_message('error', 'grafik ' . $k . ': ' . $e->getMessage()); }
        return $G;
    }
}
