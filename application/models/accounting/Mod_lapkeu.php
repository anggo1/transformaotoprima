<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Perhitungan laporan keuangan (Neraca, Laba Rugi, Arus Kas, dll.)
 * Query sengaja ditulis dengan Query Builder + SQL standar agar ringan
 * dan kompatibel MySQL 5.7/8 & MariaDB (tanpa ONLY_FULL_GROUP_BY issue).
 */
class Mod_lapkeu extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('akuntansi');
    }

    /* ------------------------------------------------------------------ */
    /** akun terdaftar + akun yang hanya muncul di jurnal/saldo (agar total laporan tetap benar) */
    private function akun_urut(array $kodeTambahan = array())
    {
        $akun = $this->akuntansi->akun();
        foreach ($kodeTambahan as $k) {
            if ( ! isset($akun[$k])) {
                $akun[$k] = (object) array('kode_akun' => $k, 'nama_akun' => '(akun tidak terdaftar)',
                    'kategori' => $this->akuntansi->tebak_kategori($k), 'saldo_normal' => 'D', 'is_active' => 'Y');
            }
        }
        uasort($akun, function ($a, $b) {
            $ua = isset(Akuntansi::$KATEGORI[$a->kategori]) ? Akuntansi::$KATEGORI[$a->kategori][3] : 999;
            $ub = isset(Akuntansi::$KATEGORI[$b->kategori]) ? Akuntansi::$KATEGORI[$b->kategori][3] : 999;
            return $ua === $ub ? strcmp($a->kode_akun, $b->kode_akun) : $ua - $ub;
        });
        return $akun;
    }

    /* ==================================================================
     * NERACA SALDO (Trial Balance)
     * ================================================================ */
    public function neraca_saldo($dari, $sampai, $tampilNol = false)
    {
        $awal = $this->akuntansi->saldo_awal_tanggal($dari);
        $mut  = $this->akuntansi->mutasi($dari, $sampai);
        $rows = array();
        $tot = array('awal_d' => 0, 'awal_k' => 0, 'mut_d' => 0, 'mut_k' => 0, 'akhir_d' => 0, 'akhir_k' => 0);
        foreach ($this->akun_urut() as $kode => $a) {
            $ad = isset($awal[$kode]) ? $awal[$kode]['d'] - $awal[$kode]['k'] : 0;
            $md = isset($mut[$kode]) ? $mut[$kode]['d'] : 0;
            $mk = isset($mut[$kode]) ? $mut[$kode]['k'] : 0;
            $akhir = $ad + $md - $mk;
            if ( ! $tampilNol && abs($ad) < 0.005 && abs($md) < 0.005 && abs($mk) < 0.005) continue;
            $r = (object) array(
                'kode' => $kode, 'nama' => $a->nama_akun, 'kategori' => $a->kategori,
                'awal_d' => $ad > 0 ? $ad : 0, 'awal_k' => $ad < 0 ? -$ad : 0,
                'mut_d' => $md, 'mut_k' => $mk,
                'akhir_d' => $akhir > 0 ? $akhir : 0, 'akhir_k' => $akhir < 0 ? -$akhir : 0,
            );
            foreach ($tot as $k => $v) $tot[$k] += $r->$k;
            $rows[] = $r;
        }
        // akun di jurnal yang tidak ada di daftar akun
        foreach (array_diff_key($mut + $awal, $this->akuntansi->akun()) as $kode => $x) {
            $ad = isset($awal[$kode]) ? $awal[$kode]['d'] - $awal[$kode]['k'] : 0;
            $md = isset($mut[$kode]) ? $mut[$kode]['d'] : 0;
            $mk = isset($mut[$kode]) ? $mut[$kode]['k'] : 0;
            $akhir = $ad + $md - $mk;
            $r = (object) array('kode' => $kode, 'nama' => '(akun tidak terdaftar)', 'kategori' => '',
                'awal_d' => max($ad, 0), 'awal_k' => max(-$ad, 0), 'mut_d' => $md, 'mut_k' => $mk,
                'akhir_d' => max($akhir, 0), 'akhir_k' => max(-$akhir, 0));
            foreach ($tot as $k => $v) $tot[$k] += $r->$k;
            $rows[] = $r;
        }
        return array('rows' => $rows, 'total' => (object) $tot);
    }

    /* ==================================================================
     * LABA RUGI
     * ================================================================ */
    /** $tanpaPenutup: abaikan jurnal penutup (TTP) agar laporan laba rugi tetap menampilkan hasil usaha */
    private function nilai_lr($dari, $sampai, $tanpaPenutup = true)
    {
        $mut = $this->akuntansi->mutasi($dari, $sampai, null, $tanpaPenutup ? array('TTP') : null);
        $nilai = array();
        foreach ($mut as $kode => $m) {
            $a = $this->akuntansi->akun($kode);
            $kat = $a ? $a->kategori : $this->akuntansi->tebak_kategori($kode);
            if ( ! in_array(Akuntansi::grup_kategori($kat), array('PENDAPATAN', 'BEBAN'), true)) continue;
            // pendapatan bertanda K-D, beban bertanda D-K
            $nilai[$kode] = Akuntansi::grup_kategori($kat) === 'PENDAPATAN' ? $m['k'] - $m['d'] : $m['d'] - $m['k'];
        }
        return $nilai;
    }

    /**
     * $kolom = [ ['label'=>..,'dari'=>..,'sampai'=>..], ... ]
     */
    public function laba_rugi(array $kolom)
    {
        $data = array();
        foreach ($kolom as $i => $c) $data[$i] = $this->nilai_lr($c['dari'], $c['sampai']);

        $seksi = array(
            'PENDAPATAN' => 'Pendapatan', 'HPP' => 'Beban Pokok Penjualan', 'BEBAN' => 'Beban Operasional',
            'PENDAPATAN_LAIN' => 'Pendapatan Lain-lain', 'BEBAN_LAIN' => 'Beban Lain-lain',
            'PAJAK_PENGHASILAN' => 'Beban Pajak Penghasilan',
        );
        $out = array();
        $n = count($kolom);
        $semua = array();
        foreach ($data as $dd) $semua = array_merge($semua, array_keys($dd));
        $akunLR = $this->akun_urut(array_unique(array_map('strval', $semua)));
        foreach ($seksi as $kat => $label) {
            $rows = array(); $tot = array_fill(0, $n, 0.0);
            foreach ($akunLR as $kode => $a) {
                $kk = $a->kategori;
                if ($kk !== $kat) continue;
                $vals = array(); $ada = false;
                for ($i = 0; $i < $n; $i++) {
                    $v = isset($data[$i][$kode]) ? $data[$i][$kode] : 0;
                    $vals[] = $v; $tot[$i] += $v; if (abs($v) >= 0.005) $ada = true;
                }
                if ($ada) $rows[] = (object) array('kode' => $kode, 'nama' => $a->nama_akun, 'nilai' => $vals);
            }
            $out[$kat] = (object) array('label' => $label, 'rows' => $rows, 'total' => $tot);
        }
        $calc = function ($f) use ($n) { $r = array(); for ($i = 0; $i < $n; $i++) $r[] = $f($i); return $r; };
        $t = function ($k) use ($out) { return $out[$k]->total; };
        $ringkas = array(
            'laba_kotor'   => $calc(function ($i) use ($t) { return $t('PENDAPATAN')[$i] - $t('HPP')[$i]; }),
            'laba_operasi' => $calc(function ($i) use ($t) { return $t('PENDAPATAN')[$i] - $t('HPP')[$i] - $t('BEBAN')[$i]; }),
            'laba_sebelum_pajak' => $calc(function ($i) use ($t) { return $t('PENDAPATAN')[$i] - $t('HPP')[$i] - $t('BEBAN')[$i] + $t('PENDAPATAN_LAIN')[$i] - $t('BEBAN_LAIN')[$i]; }),
            'laba_bersih'  => $calc(function ($i) use ($t) { return $t('PENDAPATAN')[$i] - $t('HPP')[$i] - $t('BEBAN')[$i] + $t('PENDAPATAN_LAIN')[$i] - $t('BEBAN_LAIN')[$i] - $t('PAJAK_PENGHASILAN')[$i]; }),
        );
        return array('kolom' => $kolom, 'seksi' => $out, 'ringkas' => $ringkas);
    }

    /** laba bersih satu rentang (dipakai dashboard, neraca, ekuitas) */
    public function laba_bersih($dari, $sampai, $tanpaPenutup = true)
    {
        $total = 0;
        foreach ($this->nilai_lr($dari, $sampai, $tanpaPenutup) as $kode => $v) {
            $a = $this->akuntansi->akun($kode);
            $kat = $a ? $a->kategori : $this->akuntansi->tebak_kategori($kode);
            $total += Akuntansi::grup_kategori($kat) === 'PENDAPATAN' ? $v : -$v;
        }
        return $total;
    }

    /* ==================================================================
     * NERACA (Laporan Posisi Keuangan)
     * ================================================================ */
    public function neraca(array $tanggal)
    {
        $kolom = array();
        foreach ($tanggal as $tgl) {
            $saldo = $this->akuntansi->saldo_per($tgl);
            $fy = substr($tgl, 0, 4) . '-01-01';
            $lrKumulatif = 0; $nilai = array();
            foreach ($saldo as $kode => $s) {
                $a = $this->akuntansi->akun($kode);
                $kat = $a ? $a->kategori : $this->akuntansi->tebak_kategori($kode);
                $grup = Akuntansi::grup_kategori($kat);
                if ($grup === 'PENDAPATAN') { $lrKumulatif += $s['k'] - $s['d']; continue; }
                if ($grup === 'BEBAN') { $lrKumulatif -= $s['d'] - $s['k']; continue; }
                $nilai[$kode] = $grup === 'ASET' ? $s['d'] - $s['k'] : $s['k'] - $s['d'];
            }
            // termasuk jurnal penutup: setelah tutup buku tahunan laba pindah ke akun Laba Ditahan
            $berjalan = $this->laba_bersih($fy, $tgl, false);
            $kolom[] = array('tgl' => $tgl, 'nilai' => $nilai, 'laba_berjalan' => $berjalan, 'laba_ditahan_auto' => $lrKumulatif - $berjalan);
        }

        $n = count($kolom);
        $semua = array();
        foreach ($kolom as $kk) $semua = array_merge($semua, array_keys($kk['nilai']));
        $akunNR = $this->akun_urut(array_unique(array_map('strval', $semua)));
        $grupLabel = array('ASET' => 'ASET', 'LIABILITAS' => 'LIABILITAS', 'EKUITAS' => 'EKUITAS');
        $out = array();
        foreach ($grupLabel as $g => $gl) {
            $sub = array(); $totG = array_fill(0, $n, 0.0);
            foreach (Akuntansi::$KATEGORI as $kat => $info) {
                if ($info[1] !== $g) continue;
                $rows = array(); $totK = array_fill(0, $n, 0.0);
                foreach ($akunNR as $kode => $a) {
                    if ($a->kategori !== $kat) continue;
                    $vals = array(); $ada = false;
                    for ($i = 0; $i < $n; $i++) {
                        $v = isset($kolom[$i]['nilai'][$kode]) ? $kolom[$i]['nilai'][$kode] : 0;
                        // akumulasi penyusutan (saldo kredit) otomatis bernilai negatif = pengurang aset
                        $vals[] = $v; $totK[$i] += $v; if (abs($v) >= 0.005) $ada = true;
                    }
                    if ($ada) $rows[] = (object) array('kode' => $kode, 'nama' => $a->nama_akun, 'nilai' => $vals);
                }
                if ($g === 'EKUITAS') {
                    $ld = array(); $lb = array(); $adaD = false; $adaB = false;
                    for ($i = 0; $i < $n; $i++) {
                        $ld[] = $kolom[$i]['laba_ditahan_auto']; $lb[] = $kolom[$i]['laba_berjalan'];
                        $totK[$i] += $kolom[$i]['laba_ditahan_auto'] + $kolom[$i]['laba_berjalan'];
                        if (abs($ld[$i]) >= 0.005) $adaD = true;
                        if (abs($lb[$i]) >= 0.005) $adaB = true;
                    }
                    if ($adaD) $rows[] = (object) array('kode' => '', 'nama' => 'Laba Ditahan (akumulasi laba/rugi tahun lalu)', 'nilai' => $ld);
                    if ($adaB || ! $adaD) $rows[] = (object) array('kode' => '', 'nama' => 'Laba/Rugi Tahun Berjalan', 'nilai' => $lb);
                }
                if ($rows) {
                    $sub[] = (object) array('label' => $info[0], 'rows' => $rows, 'total' => $totK);
                    for ($i = 0; $i < $n; $i++) $totG[$i] += $totK[$i];
                }
            }
            $out[$g] = (object) array('label' => $gl, 'sub' => $sub, 'total' => $totG);
        }
        $selisih = array();
        for ($i = 0; $i < $n; $i++) {
            $selisih[] = round($out['ASET']->total[$i] - $out['LIABILITAS']->total[$i] - $out['EKUITAS']->total[$i], 2);
        }
        return array('tanggal' => $tanggal, 'grup' => $out, 'selisih' => $selisih);
    }

    /* ==================================================================
     * ARUS KAS (metode langsung)
     * ================================================================ */
    public function arus_kas($dari, $sampai)
    {
        $kas = array_map('strval', array_keys($this->akuntansi->akun_kategori('KAS_BANK')));
        $hasil = array('OPERASI' => array(), 'INVESTASI' => array(), 'PENDANAAN' => array());
        $awal = 0; $akhir = 0;
        if ($kas) {
            $sa = $this->akuntansi->saldo_awal_tanggal($dari, $kas);
            foreach ($sa as $s) $awal += $s['d'] - $s['k'];

            $refs = array();
            $q = $this->db->distinct()->select('no_ref')->from('tbl_acc_jurnal_umum')
                ->where('status', 'Y')->where('tgl_jurnal >=', $dari)->where('tgl_jurnal <=', $sampai)
                ->where_in('kode_akun', $kas)->get()->result();
            foreach ($q as $r) $refs[] = $r->no_ref;

            $kat = function ($kode) {
                $a = $this->akuntansi->akun($kode);
                return $a ? $a->kategori : $this->akuntansi->tebak_kategori($kode);
            };
            $aktivitas = function ($k) {
                if (in_array($k, array('ASET_TETAP', 'AKUM_SUSUT', 'ASET_LAIN'), true)) return 'INVESTASI';
                if (in_array($k, array('LIAB_PANJANG', 'EKUITAS'), true)) return 'PENDANAAN';
                return 'OPERASI';
            };
            $tambah = function ($akt, $kode, $nama, $v) use (&$hasil) {
                $key = (string) $kode;
                if ( ! isset($hasil[$akt][$key])) $hasil[$akt][$key] = (object) array('kode' => $key, 'nama' => $nama, 'masuk' => 0, 'keluar' => 0);
                if ($v >= 0) $hasil[$akt][$key]->masuk += $v; else $hasil[$akt][$key]->keluar += -$v;
            };
            foreach (array_chunk($refs, 500) as $chunk) {
                $lines = $this->db->select('no_ref, kode_akun, nama_akun, debit, kredit')->from('tbl_acc_jurnal_umum')
                    ->where('status', 'Y')->where_in('no_ref', $chunk)->get()->result();
                $perRef = array();
                foreach ($lines as $l) $perRef[$l->no_ref][] = $l;
                foreach ($perRef as $ref => $ls) {
                    $kasNet = 0; $lawan = array(); $utama = null; $prioUtama = 99;
                    $prio = array('ASET_TETAP' => 1, 'ASET_LAIN' => 2, 'LIAB_PANJANG' => 3, 'EKUITAS' => 4, 'AKUM_SUSUT' => 5);
                    foreach ($ls as $l) {
                        if (in_array((string) $l->kode_akun, $kas, true)) { $kasNet += (float) $l->debit - (float) $l->kredit; continue; }
                        $lawan[] = $l;
                        $k = $kat($l->kode_akun);
                        // transaksi investasi/pendanaan dikelompokkan utuh ke akun utamanya
                        if (isset($prio[$k]) && $prio[$k] < $prioUtama) { $utama = $l; $prioUtama = $prio[$k]; }
                    }
                    if (abs($kasNet) < 0.005) continue; // mis. transfer antar kas
                    if ($utama !== null) {
                        $a = $this->akuntansi->akun($utama->kode_akun);
                        $tambah($aktivitas($kat($utama->kode_akun)), $utama->kode_akun, $a ? $a->nama_akun : $utama->nama_akun, $kasNet);
                        continue;
                    }
                    foreach ($lawan as $l) {
                        $a = $this->akuntansi->akun($l->kode_akun);
                        $tambah('OPERASI', $l->kode_akun, $a ? $a->nama_akun : $l->nama_akun, (float) $l->kredit - (float) $l->debit);
                    }
                }
            }
        }
        $total = array(); $net = 0;
        foreach ($hasil as $akt => $rows) {
            ksort($hasil[$akt]);
            $t = 0; foreach ($rows as $r) $t += $r->masuk - $r->keluar;
            $total[$akt] = $t; $net += $t;
        }
        $akhir = $awal + $net;
        return array('aktivitas' => $hasil, 'total' => $total, 'net' => $net, 'awal' => $awal, 'akhir' => $akhir, 'akun_kas' => $kas);
    }

    /* ==================================================================
     * PERUBAHAN EKUITAS
     * ================================================================ */
    public function perubahan_ekuitas($dari, $sampai)
    {
        $ekuitas = array_map('strval', array_keys($this->akuntansi->akun_kategori('EKUITAS')));
        $awal = $this->akuntansi->saldo_awal_tanggal($dari);
        $mut  = $this->akuntansi->mutasi($dari, $sampai, $ekuitas ?: array('-'));
        $rows = array(); $tA = 0; $tM = 0;
        foreach ($ekuitas as $kode) {
            $a = $this->akuntansi->akun($kode);
            $va = isset($awal[$kode]) ? $awal[$kode]['k'] - $awal[$kode]['d'] : 0;
            $vm = isset($mut[$kode]) ? $mut[$kode]['k'] - $mut[$kode]['d'] : 0;
            if (abs($va) < .005 && abs($vm) < .005) continue;
            $rows[] = (object) array('kode' => $kode, 'nama' => $a->nama_akun, 'awal' => $va, 'mutasi' => $vm, 'akhir' => $va + $vm);
            $tA += $va; $tM += $vm;
        }
        // laba ditahan otomatis (P&L sebelum periode)
        $lrAwal = 0;
        foreach ($awal as $kode => $s) {
            $a = $this->akuntansi->akun($kode);
            $g = Akuntansi::grup_kategori($a ? $a->kategori : $this->akuntansi->tebak_kategori($kode));
            if ($g === 'PENDAPATAN') $lrAwal += $s['k'] - $s['d'];
            elseif ($g === 'BEBAN') $lrAwal -= $s['d'] - $s['k'];
        }
        $laba = $this->laba_bersih($dari, $sampai, false);
        return array('rows' => $rows, 'awal_akun' => $tA, 'mutasi_akun' => $tM, 'laba_ditahan_awal' => $lrAwal,
            'laba_periode' => $laba, 'awal' => $tA + $lrAwal, 'akhir' => $tA + $lrAwal + $tM + $laba);
    }

    /* ==================================================================
     * BUKU BESAR
     * ================================================================ */
    public function buku_besar($dari, $sampai, $kode = null)
    {
        $filter = $kode ? array($kode) : null;
        $awal = $this->akuntansi->saldo_awal_tanggal($dari, $filter);
        $this->db->select('id_jurnal, no_ref, no_bukti, tgl_jurnal, kode_akun, keterangan, debit, kredit')
            ->from('tbl_acc_jurnal_umum')->where('status', 'Y')
            ->where('tgl_jurnal >=', $dari)->where('tgl_jurnal <=', $sampai);
        if ($this->db->field_exists('sumber', 'tbl_acc_jurnal_umum')) $this->db->select('sumber');
        if ($kode) $this->db->where('kode_akun', $kode);
        $this->db->order_by('kode_akun ASC, tgl_jurnal ASC, id_jurnal ASC');
        $lines = $this->db->get()->result();

        $per = array();
        foreach ($lines as $l) $per[$l->kode_akun][] = $l;
        $kodes = array_unique(array_merge(array_keys($per), array_keys($awal)));
        sort($kodes, SORT_STRING);
        $out = array();
        foreach ($kodes as $k) {
            $a = $this->akuntansi->akun($k);
            $sn = $a ? $a->saldo_normal : 'D';
            $saldo = isset($awal[$k]) ? ($sn === 'K' ? $awal[$k]['k'] - $awal[$k]['d'] : $awal[$k]['d'] - $awal[$k]['k']) : 0;
            $buka = $saldo; $td = 0; $tk = 0; $rows = array();
            foreach (isset($per[$k]) ? $per[$k] : array() as $l) {
                $d = (float) $l->debit; $c = (float) $l->kredit;
                $saldo += $sn === 'K' ? $c - $d : $d - $c;
                $td += $d; $tk += $c;
                $l->saldo = $saldo; $rows[] = $l;
            }
            if ( ! $rows && abs($buka) < .005) continue;
            $out[] = (object) array('kode' => $k, 'nama' => $a ? $a->nama_akun : '(tidak terdaftar)', 'saldo_normal' => $sn,
                'awal' => $buka, 'rows' => $rows, 'debit' => $td, 'kredit' => $tk, 'akhir' => $saldo);
        }
        return $out;
    }

    /* ==================================================================
     * DAFTAR JURNAL
     * ================================================================ */
    public function daftar_jurnal($dari, $sampai, $sumber = '')
    {
        $this->db->select('*')->from('tbl_acc_jurnal_umum')->where('status', 'Y')
            ->where('tgl_jurnal >=', $dari)->where('tgl_jurnal <=', $sampai);
        if ($sumber !== '' && $this->db->field_exists('sumber', 'tbl_acc_jurnal_umum')) $this->db->where('sumber', $sumber);
        $this->db->order_by('tgl_jurnal ASC, no_ref ASC, id_jurnal ASC');
        $grup = array();
        foreach ($this->db->get()->result() as $l) {
            $k = $l->no_ref !== '' ? $l->no_ref : ('#' . $l->id_jurnal);
            if ( ! isset($grup[$k])) $grup[$k] = (object) array('no_ref' => $l->no_ref, 'tgl' => $l->tgl_jurnal,
                'sumber' => isset($l->sumber) ? $l->sumber : 'JU', 'rows' => array(), 'debit' => 0, 'kredit' => 0);
            $grup[$k]->rows[] = $l; $grup[$k]->debit += (float) $l->debit; $grup[$k]->kredit += (float) $l->kredit;
        }
        return array_values($grup);
    }

    /* ==================================================================
     * MUTASI KAS & BANK
     * ================================================================ */
    public function mutasi_kas($dari, $sampai, $kode = null)
    {
        $kas = array_map('strval', array_keys($this->akuntansi->akun_kategori('KAS_BANK')));
        if ($kode) $kas = array_values(array_intersect($kas, array((string) $kode)));
        if ( ! $kas) return array();
        $bb = $this->buku_besar($dari, $sampai, null);
        return array_values(array_filter($bb, function ($x) use ($kas) { return in_array((string) $x->kode, $kas, true); }));
    }

    /* ==================================================================
     * UMUR PIUTANG / HUTANG
     * ================================================================ */
    public function umur($jenis, $tgl)
    {
        if ( ! $this->db->table_exists('tbl_acc_faktur')) return array();
        $f = $this->db->from('tbl_acc_faktur')->where('jenis', $jenis)->where('status !=', 'BATAL')
            ->where('tanggal <=', $tgl)->order_by('kontak ASC, jatuh_tempo ASC')->get()->result();
        $bayar = array();
        $q = $this->db->select($this->db->field_exists('potongan', 'tbl_acc_pelunasan') ? 'p.id_faktur, SUM(p.jumlah + p.potongan) AS jml' : 'p.id_faktur, SUM(p.jumlah) AS jml', false)->from('tbl_acc_pelunasan p')
            ->join('tbl_acc_faktur f', 'f.id = p.id_faktur')->where('f.jenis', $jenis)
            ->where('p.tanggal <=', $tgl)->group_by('p.id_faktur')->get()->result();
        foreach ($q as $r) $bayar[$r->id_faktur] = (float) $r->jml;

        $kontak = array();
        $t0 = strtotime($tgl);
        foreach ($f as $x) {
            $sisa = round((float) $x->total - (isset($bayar[$x->id]) ? $bayar[$x->id] : 0), 2);
            if ($sisa <= 0.004) continue;
            $hari = (int) floor(($t0 - strtotime($x->jatuh_tempo)) / 86400);
            $b = $hari <= 0 ? 'b0' : ($hari <= 30 ? 'b1' : ($hari <= 60 ? 'b2' : ($hari <= 90 ? 'b3' : 'b4')));
            $k = $x->kontak;
            if ( ! isset($kontak[$k])) $kontak[$k] = (object) array('kontak' => $k, 'faktur' => array(), 'b0' => 0, 'b1' => 0, 'b2' => 0, 'b3' => 0, 'b4' => 0, 'total' => 0);
            $x->sisa = $sisa; $x->hari = $hari; $x->bucket = $b;
            $kontak[$k]->faktur[] = $x; $kontak[$k]->$b += $sisa; $kontak[$k]->total += $sisa;
        }
        return array_values($kontak);
    }

    /* ==================================================================
     * ASET TETAP
     * ================================================================ */
    public function daftar_aset($tgl)
    {
        if ( ! $this->db->table_exists('tbl_acc_aset')) return array();
        $per = substr($tgl, 0, 7);
        $akm = array();
        foreach ($this->db->select('id_aset, SUM(jumlah) AS j', false)->where('periode <=', $per)->group_by('id_aset')->get('tbl_acc_aset_susut')->result() as $r) {
            $akm[$r->id_aset] = (float) $r->j;
        }
        $rows = $this->db->where('tgl_perolehan <=', $tgl)->order_by('kelompok_aset ASC, kode_aset ASC')->get('tbl_acc_aset')->result();
        foreach ($rows as $r) {
            $r->akumulasi = isset($akm[$r->id]) ? $akm[$r->id] : 0;
            $r->nilai_buku = (float) $r->harga_perolehan - $r->akumulasi;
        }
        return $rows;
    }

    /* ==================================================================
     * ANGGARAN vs REALISASI
     * ================================================================ */
    public function anggaran_realisasi($tahun, $bulanSampai)
    {
        $bulanSampai = max(1, min(12, (int) $bulanSampai));
        $dari = sprintf('%04d-01-01', $tahun);
        $sampai = date('Y-m-t', strtotime(sprintf('%04d-%02d-01', $tahun, $bulanSampai)));
        $real = $this->nilai_lr($dari, $sampai);
        $ang = array();
        if ($this->db->table_exists('tbl_acc_anggaran')) {
            foreach ($this->db->get_where('tbl_acc_anggaran', array('tahun' => $tahun))->result() as $r) {
                $t = 0; for ($b = 1; $b <= $bulanSampai; $b++) { $f = sprintf('b%02d', $b); $t += (float) $r->$f; }
                $ang[$r->kode_akun] = $t;
            }
        }
        $rows = array();
        foreach ($this->akun_urut(array_map('strval', array_keys($real))) as $kode => $a) {
            if ( ! in_array(Akuntansi::grup_kategori($a->kategori), array('PENDAPATAN', 'BEBAN'), true)) continue;
            $va = isset($ang[$kode]) ? $ang[$kode] : 0;
            $vr = isset($real[$kode]) ? $real[$kode] : 0;
            if (abs($va) < .005 && abs($vr) < .005) continue;
            $rows[] = (object) array('kode' => $kode, 'nama' => $a->nama_akun, 'kategori' => $a->kategori,
                'anggaran' => $va, 'realisasi' => $vr, 'selisih' => $vr - $va, 'persen' => $va != 0 ? $vr / $va * 100 : null);
        }
        return array('rows' => $rows, 'dari' => $dari, 'sampai' => $sampai);
    }
}
