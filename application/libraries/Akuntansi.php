<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mesin akuntansi TOP ERP (konsep mengikuti Accurate):
 * - daftar tipe akun standar & saldo normal
 * - posting jurnal otomatis (seimbang, cek akun, cek periode tutup buku)
 * - penomoran dokumen
 * - perhitungan saldo akun untuk laporan
 *
 * Semua transaksi modul (Kas & Bank, Piutang, Hutang, Aset Tetap, Tutup Buku)
 * ditulis ke tabel tbl_acc_jurnal_umum (status 'Y') sehingga Buku Besar lama tetap terbaca.
 */
class Akuntansi
{
    /** kode => [label, grup, saldo normal, urutan] */
    public static $KATEGORI = array(
        'KAS_BANK'        => array('Kas & Bank', 'ASET', 'D', 10),
        'PIUTANG'         => array('Piutang Usaha', 'ASET', 'D', 20),
        'PERSEDIAAN'      => array('Persediaan', 'ASET', 'D', 30),
        'ASET_LANCAR'     => array('Aset Lancar Lainnya', 'ASET', 'D', 40),
        'ASET_TETAP'      => array('Aset Tetap', 'ASET', 'D', 50),
        'AKUM_SUSUT'      => array('Akumulasi Penyusutan', 'ASET', 'K', 60),
        'ASET_LAIN'       => array('Aset Lainnya', 'ASET', 'D', 70),
        'HUTANG'          => array('Hutang Usaha', 'LIABILITAS', 'K', 110),
        'LIAB_PENDEK'     => array('Liabilitas Jangka Pendek', 'LIABILITAS', 'K', 120),
        'LIAB_PANJANG'    => array('Liabilitas Jangka Panjang', 'LIABILITAS', 'K', 130),
        'EKUITAS'         => array('Ekuitas', 'EKUITAS', 'K', 210),
        'PENDAPATAN'      => array('Pendapatan', 'PENDAPATAN', 'K', 310),
        'HPP'             => array('Beban Pokok Penjualan', 'BEBAN', 'D', 410),
        'BEBAN'           => array('Beban Operasional', 'BEBAN', 'D', 420),
        'PENDAPATAN_LAIN' => array('Pendapatan Lain-lain', 'PENDAPATAN', 'K', 510),
        'BEBAN_LAIN'      => array('Beban Lain-lain', 'BEBAN', 'D', 520),
        'PAJAK_PENGHASILAN' => array('Beban Pajak Penghasilan', 'BEBAN', 'D', 530),
    );

    /**
     * Perlakuan fiskal akun (untuk Rekonsiliasi Fiskal / SPT Tahunan PPh Badan)
     * kode => [label, efek terhadap laba fiskal]
     */
    public static $FISKAL = array(
        ''          => array('Normal (diakui fiskal)', 0),
        'POSITIF'   => array('Tidak boleh dibebankan → koreksi positif (Ps. 9 UU PPh)', 1),
        'FINAL'     => array('Penghasilan PPh Final / bukan objek → koreksi negatif', -1),
    );

    /**
     * Jenis setoran pajak => [label, kunci setting akun yang di-debit saat setor]
     */
    public static $JENIS_PAJAK = array(
        'PPN'       => array('PPN (Kurang Bayar Masa)', 'akun_hutang_ppn'),
        'PPH21'     => array('PPh Pasal 21 (karyawan)', 'akun_hutang_pph21'),
        'PPH23'     => array('PPh Pasal 23 (dipotong dari pemasok jasa)', 'akun_hutang_pph23'),
        'PPH42'     => array('PPh Pasal 4 ayat (2) Final (sewa, konstruksi)', 'akun_hutang_pph42'),
        'PPH25'     => array('PPh Pasal 25 (angsuran bulanan badan)', 'akun_pph25_dimuka'),
        'PPH29'     => array('PPh Pasal 29 (kurang bayar tahunan)', 'akun_hutang_pph_badan'),
        'PPHFINAL'  => array('PPh Final UMKM 0,5% (PP 55/2022 jo. PP 20/2026)', 'akun_beban_pph_final'),
    );

    /** label sumber jurnal */
    public static $SUMBER = array(
        'JU'  => 'Jurnal Umum',
        'KM'  => 'Kas/Bank Masuk',
        'KK'  => 'Kas/Bank Keluar',
        'TB'  => 'Transfer Bank',
        'FJ'  => 'Faktur Penjualan (Piutang)',
        'PP'  => 'Penerimaan Piutang',
        'FB'  => 'Faktur Pembelian (Hutang)',
        'PH'  => 'Pembayaran Hutang',
        'AT'  => 'Perolehan Aset Tetap',
        'PNY' => 'Penyusutan Aset',
        'LA'  => 'Pelepasan Aset',
        'TTP' => 'Jurnal Penutup',
        'PJK' => 'Pajak (setor, tutup masa PPN, PPh Badan)',
        'SO'  => 'Penyesuaian Persediaan (Stock Opname)',
    );

    /** @var CI_Controller */
    protected $CI;
    protected $setting = null;
    protected $akunCache = null;
    public $error = '';
    /** true = izinkan akun yang hanya ada di riwayat jurnal (dipakai jurnal penutup) */
    public $izinkan_akun_lama = false;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    /* ======================================================================
     * Pengaturan
     * ==================================================================== */
    public function setting($kunci = null, $default = '')
    {
        if ($this->setting === null) {
            $this->setting = array();
            if ($this->CI->db->table_exists('tbl_acc_setting')) {
                foreach ($this->CI->db->get('tbl_acc_setting')->result() as $r) {
                    $this->setting[$r->kunci] = $r->nilai;
                }
            }
        }
        if ($kunci === null) return $this->setting;
        return isset($this->setting[$kunci]) && $this->setting[$kunci] !== '' ? $this->setting[$kunci] : $default;
    }

    public function simpan_setting(array $data)
    {
        foreach ($data as $k => $v) {
            $k = preg_replace('/[^a-z0-9_]/', '', strtolower($k));
            if ($k === '') continue;
            $ada = $this->CI->db->where('kunci', $k)->count_all_results('tbl_acc_setting');
            if ($ada) $this->CI->db->where('kunci', $k)->update('tbl_acc_setting', array('nilai' => (string) $v));
            else $this->CI->db->insert('tbl_acc_setting', array('kunci' => $k, 'nilai' => (string) $v));
        }
        $this->setting = null;
        return true;
    }

    /* ======================================================================
     * Akun
     * ==================================================================== */
    public static function label_kategori($k)
    {
        return isset(self::$KATEGORI[$k]) ? self::$KATEGORI[$k][0] : ($k ?: '-');
    }

    public static function grup_kategori($k)
    {
        return isset(self::$KATEGORI[$k]) ? self::$KATEGORI[$k][1] : 'BEBAN';
    }

    public static function saldo_normal_kategori($k)
    {
        return isset(self::$KATEGORI[$k]) ? self::$KATEGORI[$k][2] : 'D';
    }

    /** semua akun aktif, di-index kode_akun */
    public function akun($kode = null)
    {
        if ($this->akunCache === null) {
            $this->akunCache = array();
            $this->CI->db->order_by('kode_akun', 'ASC');
            foreach ($this->CI->db->get('tbl_acc_akun')->result() as $a) {
                if ( ! isset($a->kategori) || $a->kategori === '') {
                    $a->kategori = $this->tebak_kategori($a->kode_akun);
                }
                if ( ! isset($a->saldo_normal) || $a->saldo_normal === '') {
                    $a->saldo_normal = self::saldo_normal_kategori($a->kategori);
                }
                if ( ! isset($a->is_active)) $a->is_active = 'Y';
                if ( ! isset($this->akunCache[$a->kode_akun])) {
                    $this->akunCache[$a->kode_akun] = $a;
                }
            }
        }
        if ($kode === null) return $this->akunCache;
        return isset($this->akunCache[$kode]) ? $this->akunCache[$kode] : null;
    }

    /** akun per kategori (array kode) */
    public function akun_kategori($kategori)
    {
        $kategori = (array) $kategori;
        $out = array();
        foreach ($this->akun() as $a) {
            if (in_array($a->kategori, $kategori, true) && $a->is_active !== 'N') $out[$a->kode_akun] = $a;
        }
        return $out;
    }

    /** dipakai bila migrasi belum dijalankan */
    public function tebak_kategori($kode)
    {
        $k = (string) $kode;
        $map = array('111' => 'KAS_BANK', '112' => 'KAS_BANK', '114' => 'PIUTANG', '115' => 'PERSEDIAAN', '116' => 'PERSEDIAAN',
            '129' => 'AKUM_SUSUT', '211' => 'HUTANG', '532' => 'PENDAPATAN_LAIN', '531' => 'BEBAN_LAIN', '54' => 'PAJAK_PENGHASILAN', '51' => 'HPP', '52' => 'BEBAN',
            '11' => 'ASET_LANCAR', '12' => 'ASET_TETAP', '1' => 'ASET_LAIN', '21' => 'LIAB_PENDEK', '2' => 'LIAB_PANJANG',
            '3' => 'EKUITAS', '4' => 'PENDAPATAN', '5' => 'BEBAN', '6' => 'BEBAN', '7' => 'PENDAPATAN_LAIN', '8' => 'BEBAN_LAIN');
        foreach ($map as $pref => $kat) {
            if (strpos($k, (string) $pref) === 0) return $kat;
        }
        return 'BEBAN';
    }

    /* ======================================================================
     * Periode / tutup buku
     * ==================================================================== */
    public function periode_tertutup($tgl)
    {
        if ( ! $tgl || ! $this->CI->db->table_exists('tbl_acc_periode')) return false;
        $p = substr($tgl, 0, 7);
        $r = $this->CI->db->get_where('tbl_acc_periode', array('periode' => $p))->row();
        return $r && $r->status === 'TUTUP';
    }

    /* ======================================================================
     * Penomoran: PREFIX-YYMM-0001
     * ==================================================================== */
    public function nomor_baru($tabel, $kolom, $prefix, $tgl = null)
    {
        $tgl = $tgl ?: date('Y-m-d');
        $pre = $prefix . '-' . substr($tgl, 2, 2) . substr($tgl, 5, 2) . '-';
        $row = $this->CI->db->select($kolom)->like($kolom, $pre, 'after')
            ->order_by($kolom, 'DESC')->limit(1)->get($tabel)->row();
        $urut = 1;
        if ($row) {
            $urut = (int) substr($row->$kolom, strlen($pre)) + 1;
        }
        return $pre . str_pad((string) $urut, 4, '0', STR_PAD_LEFT);
    }

    /* ======================================================================
     * Posting jurnal
     * $lines = [ ['kode_akun'=>..,'debit'=>..,'kredit'=>..,'keterangan'=>..], ... ]
     * return no_ref (string) atau false (lihat $this->error)
     * ==================================================================== */
    public function posting($sumber, $tgl, $keterangan, array $lines, $id_sumber = null, $no_bukti = '', $no_ref = null)
    {
        $this->error = '';
        $tgl = tgl_sql($tgl);
        if ( ! $tgl) { $this->error = 'Tanggal tidak valid.'; return false; }
        if ($this->periode_tertutup($tgl)) {
            $this->error = 'Periode ' . substr($tgl, 0, 7) . ' sudah ditutup (tutup buku). Buka periode terlebih dahulu.';
            return false;
        }

        // gabungkan & validasi baris
        $bersih = array(); $td = 0; $tk = 0;
        foreach ($lines as $l) {
            $d = round(to_number(isset($l['debit']) ? $l['debit'] : 0), 2);
            $k = round(to_number(isset($l['kredit']) ? $l['kredit'] : 0), 2);
            if ($d == 0 && $k == 0) continue;
            if ($d < 0) { $k += -$d; $d = 0; }
            if ($k < 0) { $d += -$k; $k = 0; }
            $akun = $this->akun(isset($l['kode_akun']) ? $l['kode_akun'] : '');
            if ( ! $akun && $this->izinkan_akun_lama && ! empty($l['kode_akun'])) {
                $lama = $this->CI->db->select('kode_akun, nama_akun, kelompok, type_akun, jenis_beban')
                    ->order_by('id_jurnal', 'DESC')->get_where('tbl_acc_jurnal_umum', array('kode_akun' => $l['kode_akun']), 1)->row();
                if ($lama) $akun = $lama;
            }
            if ( ! $akun) { $this->error = 'Akun ' . (isset($l['kode_akun']) ? $l['kode_akun'] : '?') . ' tidak ditemukan.'; return false; }
            $bersih[] = array('akun' => $akun, 'debit' => $d, 'kredit' => $k,
                'keterangan' => isset($l['keterangan']) && $l['keterangan'] !== '' ? $l['keterangan'] : $keterangan);
            $td += $d; $tk += $k;
        }
        if (count($bersih) < 2) { $this->error = 'Jurnal minimal terdiri dari 2 baris.'; return false; }
        if (abs(round($td - $tk, 2)) > 0.004) {
            $this->error = 'Jurnal tidak seimbang (Debit ' . nf($td, 2) . ' / Kredit ' . nf($tk, 2) . ').';
            return false;
        }

        $no_ref = $no_ref ?: $this->nomor_baru('tbl_acc_jurnal_umum', 'no_ref', $sumber, $tgl);
        $user = (string) $this->CI->session->userdata('full_name');
        $now  = date('Y-m-d H:i:s');
        $punyaSumber = $this->CI->db->field_exists('sumber', 'tbl_acc_jurnal_umum');

        $i = 0;
        foreach ($bersih as $b) {
            $i++;
            $row = array(
                'kode_awal'   => $no_ref,
                'no_ref'      => $no_ref,
                'no_jurnal'   => $no_ref . '-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'tgl_jurnal'  => $tgl,
                'no_bukti'    => (string) $no_bukti,
                'kode_akun'   => $b['akun']->kode_akun,
                'nama_akun'   => $b['akun']->nama_akun,
                'keterangan'  => mb_substr((string) $b['keterangan'], 0, 255),
                'debit'       => $b['debit'],
                'kredit'      => $b['kredit'],
                'user'        => $user,
                'tgl_insert'  => $now,
                'status'      => 'Y',
                'kelompok'    => (string) $b['akun']->kelompok,
                'type_akun'   => (string) $b['akun']->type_akun,
                'jenis_beban' => (string) $b['akun']->jenis_beban,
            );
            if ($punyaSumber) {
                $row['sumber'] = $sumber;
                $row['id_sumber'] = $id_sumber;
            }
            $this->CI->db->insert('tbl_acc_jurnal_umum', $row);
        }
        return $no_ref;
    }

    /** hapus jurnal berdasarkan no_ref (dengan cek periode) */
    public function hapus_jurnal($no_ref)
    {
        $this->error = '';
        if ($no_ref === '' || $no_ref === null) return true;
        $r = $this->CI->db->select('tgl_jurnal')->get_where('tbl_acc_jurnal_umum', array('no_ref' => $no_ref), 1)->row();
        if ($r && $this->periode_tertutup($r->tgl_jurnal)) {
            $this->error = 'Periode ' . substr($r->tgl_jurnal, 0, 7) . ' sudah ditutup. Transaksi tidak dapat diubah/dihapus.';
            return false;
        }
        $this->CI->db->where('no_ref', $no_ref)->delete('tbl_acc_jurnal_umum');
        return true;
    }

    /* ======================================================================
     * Saldo akun
     * ==================================================================== */

    /**
     * Tahun saldo awal yang berlaku untuk tanggal tertentu.
     * Saldo awal periode Y = saldo per 1 Januari Y.
     */
    public function tahun_saldo_awal($tgl)
    {
        $th = (int) substr($tgl, 0, 4);
        $r = $this->CI->db->select_max('periode', 'p')->where('periode <=', $th)->get('tbl_acc_saldo_awal')->row();
        return ($r && $r->p) ? (int) $r->p : null;
    }

    /**
     * Mutasi per akun dalam rentang (inklusif). return [kode => ['d'=>..,'k'=>..]]
     * $sampai inklusif, $dari boleh null (= sejak awal saldo awal)
     */
    public function mutasi($dari, $sampai, $filterAkun = null, $kecualiSumber = null)
    {
        $db = $this->CI->db;
        $db->select('kode_akun, SUM(debit) AS d, SUM(kredit) AS k', false)
            ->from('tbl_acc_jurnal_umum')->where('status', 'Y');
        if ($kecualiSumber && $db->field_exists('sumber', 'tbl_acc_jurnal_umum')) $db->where_not_in('sumber', (array) $kecualiSumber);
        if ($dari) $db->where('tgl_jurnal >=', $dari);
        if ($sampai) $db->where('tgl_jurnal <=', $sampai);
        if ($filterAkun) $db->where_in('kode_akun', (array) $filterAkun);
        $db->group_by('kode_akun');
        $out = array();
        foreach ($db->get()->result() as $r) {
            $out[$r->kode_akun] = array('d' => (float) $r->d, 'k' => (float) $r->k);
        }
        return $out;
    }

    /**
     * Saldo per akun pada akhir hari $tgl (inklusif), termasuk saldo awal.
     * return [kode => ['d'=>total debit, 'k'=>total kredit]]
     */
    public function saldo_per($tgl, $filterAkun = null)
    {
        $tahun = $this->tahun_saldo_awal($tgl);
        $out = array();
        if ($tahun) {
            $db = $this->CI->db;
            $db->select('kode_akun, SUM(debit) AS d, SUM(kredit) AS k', false)
                ->from('tbl_acc_saldo_awal')->where('periode', $tahun);
            if ($filterAkun) $db->where_in('kode_akun', (array) $filterAkun);
            $db->group_by('kode_akun');
            foreach ($db->get()->result() as $r) {
                $out[$r->kode_akun] = array('d' => (float) $r->d, 'k' => (float) $r->k);
            }
        }
        $dari = $tahun ? $tahun . '-01-01' : null;
        foreach ($this->mutasi($dari, $tgl, $filterAkun) as $kode => $m) {
            if ( ! isset($out[$kode])) $out[$kode] = array('d' => 0, 'k' => 0);
            $out[$kode]['d'] += $m['d'];
            $out[$kode]['k'] += $m['k'];
        }
        return $out;
    }

    /**
     * Saldo pembukaan pada awal hari $tgl (belum termasuk transaksi tanggal $tgl).
     */
    public function saldo_awal_tanggal($tgl, $filterAkun = null)
    {
        $tahun = $this->tahun_saldo_awal($tgl);
        $out = array();
        if ($tahun) {
            $db = $this->CI->db;
            $db->select('kode_akun, SUM(debit) AS d, SUM(kredit) AS k', false)
                ->from('tbl_acc_saldo_awal')->where('periode', $tahun);
            if ($filterAkun) $db->where_in('kode_akun', (array) $filterAkun);
            $db->group_by('kode_akun');
            foreach ($db->get()->result() as $r) {
                $out[$r->kode_akun] = array('d' => (float) $r->d, 'k' => (float) $r->k);
            }
        }
        $dari = $tahun ? $tahun . '-01-01' : null;
        $sampai = self::kemarin($tgl);
        if ($dari === null || $sampai >= $dari) {
            foreach ($this->mutasi($dari, $sampai, $filterAkun) as $kode => $m) {
                if ( ! isset($out[$kode])) $out[$kode] = array('d' => 0, 'k' => 0);
                $out[$kode]['d'] += $m['d'];
                $out[$kode]['k'] += $m['k'];
            }
        }
        return $out;
    }

    /** saldo bertanda sesuai saldo normal akun */
    public function nilai_normal($kode, $d, $k)
    {
        $a = $this->akun($kode);
        $sn = $a ? $a->saldo_normal : 'D';
        return $sn === 'K' ? ($k - $d) : ($d - $k);
    }

    /** tanggal sehari sebelum */
    public static function kemarin($tgl)
    {
        return date('Y-m-d', strtotime($tgl . ' -1 day'));
    }
}
