<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Perpajakan (PPN, PPh potong/pungut, PPh Badan & Rekonsiliasi Fiskal).
 *
 * Prinsip: semua angka pajak DIBACA DARI JURNAL pada akun pajak yang diatur di
 * menu Perpajakan > Pengaturan, sehingga PPN/PPh yang dicatat lewat Faktur,
 * Kas & Bank, maupun Jurnal Umum ikut terhitung.
 *
 * Jurnal yang dibuat modul ini memakai sumber 'PJK':
 *  - PPN-YYYY-MM  : tutup masa PPN (kompensasi Keluaran vs Masukan)
 *  - PPHB-YYYY    : beban PPh Badan akhir tahun
 *  - SPJ-YYMM-nnnn: setoran pajak (dengan NTPN)
 */
class Mod_pajak extends CI_Model
{
    public $error = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('akuntansi');
    }

    private function gagal($pesan)
    {
        $this->error = $pesan;
        if ($this->db->trans_status() !== null) $this->db->trans_rollback();
        return false;
    }

    private function user() { return (string) $this->session->userdata('full_name'); }

    /** kode akun dari setting (string kosong bila belum diatur / tidak ada) */
    public function akun($kunci)
    {
        $k = (string) $this->akuntansi->setting($kunci);
        return ($k !== '' && $this->akuntansi->akun($k)) ? $k : '';
    }

    public function profil()
    {
        $s = $this->akuntansi->setting();
        $g = function ($k, $d = '') use ($s) { return isset($s[$k]) && $s[$k] !== '' ? $s[$k] : $d; };
        return (object) array(
            'npwp' => $g('pajak_npwp'), 'nama_wp' => $g('pajak_nama_wp', $g('nama_perusahaan_laporan')),
            'pkp' => $g('pajak_pkp', 'Y') === 'Y', 'rezim' => $g('pajak_rezim', 'UMUM'),
            'tarif_badan' => (float) $g('pajak_tarif_badan', '22'), 'tarif_final' => (float) $g('pajak_tarif_final', '0.5'),
            'ppn_persen' => (float) $g('ppn_persen', '11'), 'dpp_nilai_lain' => $g('ppn_dpp_nilai_lain', 'Y') === 'Y',
            'pph23' => (float) $g('pph23_persen', '2'),
        );
    }

    /**
     * Mutasi per bulan per akun.  return [kode][bulan 1..12] = ['d'=>,'k'=>]
     * $kecualiBukti: no_bukti yang diabaikan (mis. jurnal tutup masa PPN)
     */
    private function mutasi_bulanan($tahun, array $kode, $kecualiPJK = false)
    {
        $kode = array_values(array_filter(array_map('strval', $kode)));
        $out = array();
        foreach ($kode as $k) for ($b = 1; $b <= 12; $b++) $out[$k][$b] = array('d' => 0.0, 'k' => 0.0);
        if ( ! $kode) return $out;
        $this->db->select('SUBSTR(tgl_jurnal,6,2) AS b, kode_akun, SUM(debit) AS d, SUM(kredit) AS k', false)
            ->from('tbl_acc_jurnal_umum')->where('status', 'Y')
            ->where('tgl_jurnal >=', $tahun . '-01-01')->where('tgl_jurnal <=', $tahun . '-12-31')
            ->where_in('kode_akun', $kode);
        if ($kecualiPJK) $this->db->where('sumber !=', 'PJK');
        $this->db->group_by(array('SUBSTR(tgl_jurnal,6,2)', 'kode_akun'), false);
        foreach ($this->db->get()->result() as $r) {
            $out[(string) $r->kode_akun][(int) $r->b] = array('d' => (float) $r->d, 'k' => (float) $r->k);
        }
        return $out;
    }

    /* ==================================================================
     * PPN
     * ================================================================ */
    /** rekap PPN per masa untuk 1 tahun */
    public function ppn_tahun($tahun)
    {
        $ak = $this->akun('akun_ppn_keluaran'); $am = $this->akun('akun_ppn_masukan');
        $nb = array();
        for ($b = 1; $b <= 12; $b++) $nb[] = sprintf('PPN-%04d-%02d', $tahun, $b);
        // jurnal PJK yang menyentuh akun PPN Keluaran/Masukan hanyalah jurnal tutup masa -> diabaikan
        $mut = $this->mutasi_bulanan($tahun, array($ak, $am), true);

        // DPP dari faktur
        $dpp = array('AR' => array_fill(1, 12, 0.0), 'AP' => array_fill(1, 12, 0.0));
        $nfk = array('AR' => array_fill(1, 12, 0), 'AP' => array_fill(1, 12, 0));
        if ($this->db->table_exists('tbl_acc_faktur')) {
            $q = $this->db->select('jenis, SUBSTR(tanggal,6,2) AS b, SUM(subtotal - diskon) AS dpp, COUNT(*) AS n', false)
                ->from('tbl_acc_faktur')->where('status !=', 'BATAL')->where('ppn >', 0)
                ->where('tanggal >=', $tahun . '-01-01')->where('tanggal <=', $tahun . '-12-31')
                ->group_by(array('jenis', 'SUBSTR(tanggal,6,2)'), false)->get()->result();
            foreach ($q as $r) { $dpp[$r->jenis][(int) $r->b] = (float) $r->dpp; $nfk[$r->jenis][(int) $r->b] = (int) $r->n; }
        }
        // tutup masa & setoran
        $tutup = array();
        foreach ($this->db->select('no_bukti, no_ref')->distinct()->where('sumber', 'PJK')->where_in('no_bukti', $nb)->get('tbl_acc_jurnal_umum')->result() as $r) $tutup[$r->no_bukti] = $r->no_ref;
        $setor = $this->setor_per_masa($tahun, 'PPN');

        // lebih bayar dari tahun sebelumnya = saldo debit PPN Masukan - saldo kredit PPN Keluaran per 31 Des tahun lalu (bila positif)
        $kompensasi = 0.0;
        if ($ak && $am) {
            $s = $this->akuntansi->saldo_per(($tahun - 1) . '-12-31', array($ak, $am));
            $m = isset($s[$am]) ? $s[$am]['d'] - $s[$am]['k'] : 0;
            $k = isset($s[$ak]) ? $s[$ak]['k'] - $s[$ak]['d'] : 0;
            $kompensasi = max(0, round($m - $k, 2));
        }
        $rows = array(); $tot = array('dpp_k' => 0, 'ppn_k' => 0, 'dpp_m' => 0, 'ppn_m' => 0, 'kb' => 0, 'setor' => 0);
        for ($b = 1; $b <= 12; $b++) {
            $pk = $ak ? $mut[$ak][$b]['k'] - $mut[$ak][$b]['d'] : 0;
            $pm = $am ? $mut[$am][$b]['d'] - $mut[$am][$b]['k'] : 0;
            $selisih = round($pk - $pm - $kompensasi, 2);
            $kb = max(0, $selisih); $lb = $selisih < 0 ? -$selisih : 0;
            $per = sprintf('%04d-%02d', $tahun, $b);
            $rows[$b] = (object) array(
                'bulan' => $b, 'periode' => $per,
                'dpp_k' => $dpp['AR'][$b], 'n_k' => $nfk['AR'][$b], 'ppn_k' => $pk,
                'dpp_m' => $dpp['AP'][$b], 'n_m' => $nfk['AP'][$b], 'ppn_m' => $pm,
                'kompensasi' => $kompensasi, 'kb' => $kb, 'lb' => $lb,
                'tutup' => isset($tutup['PPN-' . $per]) ? $tutup['PPN-' . $per] : '',
                'setor' => isset($setor[$per]) ? $setor[$per] : 0,
                'jt_setor' => date('Y-m-t', strtotime($per . '-01 +1 month')),
            );
            $kompensasi = $lb;
            $tot['dpp_k'] += $rows[$b]->dpp_k; $tot['ppn_k'] += $pk; $tot['dpp_m'] += $rows[$b]->dpp_m; $tot['ppn_m'] += $pm;
            $tot['kb'] += $kb; $tot['setor'] += $rows[$b]->setor;
        }
        return array('rows' => $rows, 'total' => (object) $tot, 'akun_k' => $ak, 'akun_m' => $am);
    }

    /** daftar faktur pajak keluaran & masukan dalam 1 masa */
    public function ppn_rincian($periode)
    {
        $out = array('AR' => array(), 'AP' => array());
        if ( ! preg_match('/^\d{4}-\d{2}$/', $periode) || ! $this->db->table_exists('tbl_acc_faktur')) return $out;
        $dari = $periode . '-01'; $sampai = date('Y-m-t', strtotime($dari));
        $faktor = $this->profil()->dpp_nilai_lain ? 11 / 12 : 1;
        $q = $this->db->from('tbl_acc_faktur')->where('status !=', 'BATAL')->where('ppn >', 0)
            ->where('tanggal >=', $dari)->where('tanggal <=', $sampai)->order_by('tanggal, no_faktur')->get()->result();
        foreach ($q as $f) {
            $f->dpp = (float) $f->subtotal - (float) $f->diskon;
            // DPP nilai lain 11/12 hanya relevan bila tarif efektif 11% (= 12% x 11/12, PMK 131/2024)
            $f->dpp_lain = abs((float) $f->ppn_persen - 11) < 0.001 ? round($f->dpp * $faktor, 2) : $f->dpp;
            $f->tarif = abs((float) $f->ppn_persen - 11) < 0.001 && $faktor != 1 ? 12 : (float) $f->ppn_persen;
            $out[$f->jenis][] = $f;
        }
        return $out;
    }

    /**
     * Tutup masa PPN: kompensasikan saldo PPN Keluaran dengan PPN Masukan per akhir bulan.
     * Keluaran > Masukan  -> sisa menjadi Hutang PPN (disetor)
     * Masukan > Keluaran  -> sisa tetap di PPN Masukan (lebih bayar, dikompensasikan ke masa berikutnya)
     */
    public function tutup_masa_ppn($periode)
    {
        $this->error = '';
        if ( ! preg_match('/^(\d{4})-(\d{2})$/', $periode, $m)) return $this->gagal('Masa pajak tidak valid.');
        $ak = $this->akun('akun_ppn_keluaran'); $am = $this->akun('akun_ppn_masukan'); $ah = $this->akun('akun_hutang_ppn');
        if ( ! $ak || ! $am || ! $ah) return $this->gagal('Akun PPN Keluaran, PPN Masukan dan Hutang PPN wajib diatur di Perpajakan > Pengaturan.');
        $nb = 'PPN-' . $periode;
        if ($this->db->where('sumber', 'PJK')->where('no_bukti', $nb)->count_all_results('tbl_acc_jurnal_umum')) return $this->gagal('Masa ' . $periode . ' sudah ditutup.');
        $akhir = date('Y-m-t', strtotime($periode . '-01'));
        // tidak boleh ada masa sesudahnya yang sudah ditutup (agar kompensasi berurutan)
        if ($this->db->where('sumber', 'PJK')->like('no_bukti', 'PPN-', 'after')->where('tgl_jurnal >', $akhir)->count_all_results('tbl_acc_jurnal_umum')) {
            return $this->gagal('Ada masa PPN sesudah ' . $periode . ' yang sudah ditutup. Batalkan masa tersebut dulu.');
        }
        $s = $this->akuntansi->saldo_per($akhir, array($ak, $am));
        $K = round(isset($s[$ak]) ? $s[$ak]['k'] - $s[$ak]['d'] : 0, 2);
        $M = round(isset($s[$am]) ? $s[$am]['d'] - $s[$am]['k'] : 0, 2);
        if ($K <= 0) return $this->gagal('Tidak ada saldo PPN Keluaran pada masa ' . $periode . '. Saldo PPN Masukan ' . nf($M, 2) . ' otomatis dikompensasikan ke masa berikutnya.');
        $pakaiM = min($K, max(0, $M));
        $jl = array(array('kode_akun' => $ak, 'debit' => $K, 'keterangan' => 'Tutup PPN Keluaran masa ' . $periode));
        if ($pakaiM > 0) $jl[] = array('kode_akun' => $am, 'kredit' => $pakaiM, 'keterangan' => 'Kompensasi PPN Masukan masa ' . $periode);
        if ($K - $pakaiM > 0.004) $jl[] = array('kode_akun' => $ah, 'kredit' => round($K - $pakaiM, 2), 'keterangan' => 'PPN kurang bayar masa ' . $periode);
        if (count($jl) < 2) return $this->gagal('Tidak ada yang perlu ditutup.');
        $this->db->trans_begin();
        $ref = $this->akuntansi->posting('PJK', $akhir, 'Tutup masa PPN ' . $periode, $jl, null, $nb);
        if ( ! $ref) return $this->gagal($this->akuntansi->error);
        $this->db->trans_commit();
        return (object) array('ref' => $ref, 'keluaran' => $K, 'masukan' => $pakaiM, 'kurang_bayar' => round($K - $pakaiM, 2), 'lebih_bayar' => round(max(0, $M - $K), 2));
    }

    public function batal_masa_ppn($periode)
    {
        $nb = 'PPN-' . $periode;
        $r = $this->db->select('no_ref, tgl_jurnal')->where('sumber', 'PJK')->where('no_bukti', $nb)->get('tbl_acc_jurnal_umum', 1)->row();
        if ( ! $r) return $this->gagal('Masa ' . $periode . ' belum ditutup.');
        if ($this->db->where('sumber', 'PJK')->like('no_bukti', 'PPN-', 'after')->where('tgl_jurnal >', $r->tgl_jurnal)->count_all_results('tbl_acc_jurnal_umum')) {
            return $this->gagal('Batalkan dulu masa PPN sesudahnya.');
        }
        if ( ! $this->akuntansi->hapus_jurnal($r->no_ref)) return $this->gagal($this->akuntansi->error);
        return true;
    }

    /* ==================================================================
     * SETORAN PAJAK
     * ================================================================ */
    private function setor_per_masa($tahun, $jenis)
    {
        $out = array();
        if ( ! $this->db->table_exists('tbl_acc_setor_pajak')) return $out;
        $q = $this->db->select('masa, SUM(jumlah) AS j', false)->where('jenis', $jenis)->like('masa', $tahun, 'after')
            ->group_by('masa')->get('tbl_acc_setor_pajak')->result();
        foreach ($q as $r) $out[$r->masa] = (float) $r->j;
        return $out;
    }

    public function setor_list($tahun, $jenis = '')
    {
        if ( ! $this->db->table_exists('tbl_acc_setor_pajak')) return array();
        $this->db->where('tanggal >=', $tahun . '-01-01')->where('tanggal <=', $tahun . '-12-31');
        if ($jenis !== '') $this->db->where('jenis', $jenis);
        return $this->db->order_by('tanggal DESC, id DESC')->get('tbl_acc_setor_pajak')->result();
    }

    /** saran nilai setor: saldo akun pajak per akhir masa */
    public function saran_setor($jenis, $masa)
    {
        if ( ! isset(Akuntansi::$JENIS_PAJAK[$jenis])) return 0;
        $kode = $this->akun(Akuntansi::$JENIS_PAJAK[$jenis][1]);
        if ( ! $kode) return 0;
        if ($jenis === 'PPHFINAL') {
            // 0,5% x peredaran bruto bulan tsb
            if ( ! preg_match('/^\d{4}-\d{2}$/', $masa)) return 0;
            return round($this->bruto(substr($masa, 0, 4), (int) substr($masa, 5, 2)) * $this->profil()->tarif_final / 100, 0);
        }
        if ($jenis === 'PPH25') return round($this->estimasi_pph25(), 0);
        $tgl = preg_match('/^\d{4}-\d{2}$/', $masa) ? date('Y-m-t', strtotime($masa . '-01')) : ($masa . '-12-31');
        $s = $this->akuntansi->saldo_per($tgl, array($kode));
        $v = isset($s[$kode]) ? $s[$kode]['k'] - $s[$kode]['d'] : 0;
        return max(0, round($v, 2));
    }

    public function setor_simpan(array $p)
    {
        $this->error = '';
        $jenis = isset($p['jenis']) ? (string) $p['jenis'] : '';
        if ( ! isset(Akuntansi::$JENIS_PAJAK[$jenis])) return $this->gagal('Jenis pajak tidak valid.');
        $tgl = tgl_sql(isset($p['tanggal']) ? $p['tanggal'] : '');
        $masa = trim((string) (isset($p['masa']) ? $p['masa'] : ''));
        $jml = round(to_number(isset($p['jumlah']) ? $p['jumlah'] : 0), 2);
        $kas = $this->akuntansi->akun(isset($p['kode_akun_kas']) ? $p['kode_akun_kas'] : '');
        $kodePajak = trim((string) (isset($p['kode_akun_pajak']) ? $p['kode_akun_pajak'] : '')) ?: $this->akun(Akuntansi::$JENIS_PAJAK[$jenis][1]);
        $akunPajak = $this->akuntansi->akun($kodePajak);
        if ( ! $tgl) return $this->gagal('Tanggal setor wajib diisi.');
        if ( ! preg_match('/^\d{4}(-\d{2})?$/', $masa)) return $this->gagal('Masa pajak wajib diisi (bulan/tahun).');
        if ($jml <= 0) return $this->gagal('Jumlah setor harus lebih dari 0.');
        if ( ! $kas || $kas->kategori !== 'KAS_BANK') return $this->gagal('Pilih akun Kas/Bank sumber pembayaran.');
        if ( ! $akunPajak) return $this->gagal('Akun pajak untuk ' . Akuntansi::$JENIS_PAJAK[$jenis][0] . ' belum diatur.');
        $this->db->trans_begin();
        $no = $this->akuntansi->nomor_baru('tbl_acc_setor_pajak', 'no_bukti', 'SPJ', $tgl);
        $ntpn = mb_substr(trim((string) (isset($p['ntpn']) ? $p['ntpn'] : '')), 0, 30);
        $ket = 'Setor ' . Akuntansi::$JENIS_PAJAK[$jenis][0] . ' masa ' . $masa . ($ntpn !== '' ? ' NTPN ' . $ntpn : '');
        $this->db->insert('tbl_acc_setor_pajak', array('no_bukti' => $no, 'tanggal' => $tgl, 'jenis' => $jenis, 'masa' => $masa,
            'kode_akun_pajak' => $akunPajak->kode_akun, 'kode_akun_kas' => $kas->kode_akun, 'jumlah' => $jml, 'ntpn' => $ntpn,
            'keterangan' => mb_substr(trim((string) (isset($p['keterangan']) ? $p['keterangan'] : '')), 0, 255),
            'user' => $this->user(), 'created_at' => date('Y-m-d H:i:s')));
        $id = $this->db->insert_id();
        $ref = $this->akuntansi->posting('PJK', $tgl, $ket, array(
            array('kode_akun' => $akunPajak->kode_akun, 'debit' => $jml),
            array('kode_akun' => $kas->kode_akun, 'kredit' => $jml),
        ), $id, $no);
        if ( ! $ref) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $id)->update('tbl_acc_setor_pajak', array('no_ref_jurnal' => $ref));
        if ($this->db->trans_status() === FALSE) return $this->gagal('Gagal menyimpan setoran.');
        $this->db->trans_commit();
        return $id;
    }

    public function setor_hapus($id)
    {
        $r = $this->db->get_where('tbl_acc_setor_pajak', array('id' => (int) $id))->row();
        if ( ! $r) return $this->gagal('Data setoran tidak ditemukan.');
        $this->db->trans_begin();
        if ( ! $this->akuntansi->hapus_jurnal($r->no_ref_jurnal)) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $r->id)->delete('tbl_acc_setor_pajak');
        $this->db->trans_commit();
        return true;
    }

    /* ==================================================================
     * BUKU PEMBANTU PAJAK & BUKTI POTONG
     * ================================================================ */
    public function akun_pajak_daftar()
    {
        return array(
            'akun_ppn_keluaran' => 'PPN Keluaran', 'akun_ppn_masukan' => 'PPN Masukan', 'akun_hutang_ppn' => 'Hutang PPN',
            'akun_hutang_pph21' => 'Hutang PPh 21', 'akun_hutang_pph23' => 'Hutang PPh 23', 'akun_hutang_pph42' => 'Hutang PPh 4(2)',
            'akun_hutang_pph_badan' => 'Hutang PPh Badan (29)', 'akun_pph22_dimuka' => 'PPh 22 Dibayar Dimuka',
            'akun_pph23_dimuka' => 'PPh 23 Dibayar Dimuka', 'akun_pph25_dimuka' => 'PPh 25 Dibayar Dimuka',
        );
    }

    public function pph_buku($tahun)
    {
        $daftar = array();
        foreach ($this->akun_pajak_daftar() as $k => $label) { $kode = $this->akun($k); if ($kode) $daftar[$kode] = $label; }
        $kodes = array_keys($daftar);
        $mut = $this->mutasi_bulanan($tahun, $kodes);
        $awal = $kodes ? $this->akuntansi->saldo_awal_tanggal($tahun . '-01-01', $kodes) : array();
        $rows = array();
        foreach ($daftar as $kode => $label) {
            $a = $this->akuntansi->akun($kode);
            $sn = $a->saldo_normal;
            $sa = isset($awal[$kode]) ? ($sn === 'K' ? $awal[$kode]['k'] - $awal[$kode]['d'] : $awal[$kode]['d'] - $awal[$kode]['k']) : 0;
            $bulan = array(); $tambah = 0; $kurang = 0; $saldo = $sa;
            for ($b = 1; $b <= 12; $b++) {
                $d = $mut[$kode][$b]['d']; $k = $mut[$kode][$b]['k'];
                $t = $sn === 'K' ? $k : $d; $c = $sn === 'K' ? $d : $k;
                $saldo += $t - $c; $tambah += $t; $kurang += $c;
                $bulan[$b] = (object) array('tambah' => $t, 'kurang' => $c, 'saldo' => $saldo);
            }
            $rows[] = (object) array('kode' => (string) $kode, 'nama' => $a->nama_akun, 'label' => $label, 'saldo_normal' => $sn,
                'awal' => $sa, 'tambah' => $tambah, 'kurang' => $kurang, 'akhir' => $saldo, 'bulan' => $bulan);
        }
        return $rows;
    }

    /** bukti potong dari pelunasan faktur */
    public function bukti_potong($tahun)
    {
        if ( ! $this->db->field_exists('potongan', 'tbl_acc_pelunasan')) return array();
        return $this->db->select('p.tanggal, p.no_bukti, p.potongan, p.jenis_pph, p.no_bukti_potong, p.jumlah, f.jenis, f.no_faktur, f.kontak, (f.subtotal - f.diskon) AS dpp', false)
            ->from('tbl_acc_pelunasan p')->join('tbl_acc_faktur f', 'f.id = p.id_faktur')
            ->where('p.potongan >', 0)->where('p.tanggal >=', $tahun . '-01-01')->where('p.tanggal <=', $tahun . '-12-31')
            ->order_by('f.jenis, p.tanggal')->get()->result();
    }

    /* ==================================================================
     * PEREDARAN BRUTO, REKONSILIASI FISKAL, PPh BADAN
     * ================================================================ */
    /** peredaran bruto (pendapatan usaha + lain-lain non-final) per tahun / per bulan */
    public function bruto($tahun, $bulan = null)
    {
        $dari = $bulan ? sprintf('%04d-%02d-01', $tahun, $bulan) : $tahun . '-01-01';
        $sampai = $bulan ? date('Y-m-t', strtotime($dari)) : $tahun . '-12-31';
        $mut = $this->akuntansi->mutasi($dari, $sampai, null, array('TTP'));
        $t = 0;
        foreach ($mut as $kode => $m) {
            $a = $this->akuntansi->akun($kode);
            if ( ! $a || ! in_array($a->kategori, array('PENDAPATAN', 'PENDAPATAN_LAIN'), true)) continue;
            if (isset($a->fiskal) && $a->fiskal === 'FINAL') continue;
            $t += $m['k'] - $m['d'];
        }
        return round($t, 2);
    }

    public function bruto_bulanan($tahun)
    {
        $out = array();
        for ($b = 1; $b <= 12; $b++) $out[$b] = $this->bruto($tahun, $b);
        return $out;
    }

    public function koreksi_list($tahun)
    {
        if ( ! $this->db->table_exists('tbl_acc_koreksi_fiskal')) return array();
        return $this->db->order_by('jenis DESC, id')->get_where('tbl_acc_koreksi_fiskal', array('tahun' => (int) $tahun))->result();
    }

    public function koreksi_simpan(array $p)
    {
        $tahun = (int) (isset($p['tahun']) ? $p['tahun'] : 0);
        $nilai = round(to_number(isset($p['nilai']) ? $p['nilai'] : 0), 2);
        $ket = trim((string) (isset($p['keterangan']) ? $p['keterangan'] : ''));
        $jenis = isset($p['jenis']) && $p['jenis'] === 'NEGATIF' ? 'NEGATIF' : 'POSITIF';
        if ($tahun < 2000) return $this->gagal('Tahun tidak valid.');
        if ($ket === '') return $this->gagal('Keterangan koreksi wajib diisi.');
        if ($nilai <= 0) return $this->gagal('Nilai koreksi harus lebih dari 0.');
        $this->db->insert('tbl_acc_koreksi_fiskal', array('tahun' => $tahun, 'jenis' => $jenis, 'keterangan' => mb_substr($ket, 0, 255),
            'kode_akun' => trim((string) (isset($p['kode_akun']) ? $p['kode_akun'] : '')), 'nilai' => $nilai,
            'user' => $this->user(), 'created_at' => date('Y-m-d H:i:s')));
        return $this->db->insert_id();
    }

    public function koreksi_hapus($id)
    {
        $this->db->where('id', (int) $id)->delete('tbl_acc_koreksi_fiskal');
        return true;
    }

    /** kredit pajak tahun berjalan (PPh 22, 23, 25) — mengabaikan jurnal PPh Badan akhir tahun */
    private function kredit_pajak($tahun)
    {
        $out = array();
        foreach (array('akun_pph22_dimuka' => 'PPh Pasal 22', 'akun_pph23_dimuka' => 'PPh Pasal 23 (dipotong pihak lain)', 'akun_pph25_dimuka' => 'PPh Pasal 25 (angsuran)') as $k => $label) {
            $kode = $this->akun($k);
            $v = 0;
            if ($kode) {
                $r = $this->db->select('SUM(debit) AS d', false)->from('tbl_acc_jurnal_umum')->where('status', 'Y')
                    ->where('kode_akun', $kode)->where('tgl_jurnal >=', $tahun . '-01-01')->where('tgl_jurnal <=', $tahun . '-12-31')
                    ->where("NOT (sumber = 'PJK' AND no_bukti = " . $this->db->escape('PPHB-' . $tahun) . ")", null, false)->get()->row();
                $v = (float) $r->d;
            }
            $out[$k] = (object) array('label' => $label, 'kode' => $kode, 'nilai' => round($v, 2));
        }
        return $out;
    }

    /** hitung PPh Badan Pasal 17 + fasilitas Pasal 31E */
    public function hitung_pph_badan($pkp, $bruto, $tarif = 22.0)
    {
        $pkp = max(0, floor($pkp / 1000) * 1000);  // dibulatkan ke bawah ribuan penuh
        $r = (object) array('pkp' => $pkp, 'fasilitas' => '', 'pkp_fas' => 0, 'pkp_non' => $pkp, 'pph' => 0.0, 'rincian' => array());
        if ($pkp <= 0) { $r->fasilitas = 'Rugi / nihil — tidak ada PPh terutang'; return $r; }
        $t = $tarif / 100;
        if ($bruto <= 4800000000) {
            $r->fasilitas = 'Pasal 31E: peredaran bruto ≤ Rp4,8 M → seluruh PKP mendapat pengurangan tarif 50%';
            $r->pkp_fas = $pkp; $r->pkp_non = 0;
        } elseif ($bruto <= 50000000000) {
            $r->pkp_fas = floor(4800000000 / $bruto * $pkp);
            $r->pkp_non = $pkp - $r->pkp_fas;
            $r->fasilitas = 'Pasal 31E: peredaran bruto Rp4,8 M – Rp50 M → PKP bagian (4,8 M ÷ bruto) mendapat pengurangan tarif 50%';
        } else {
            $r->fasilitas = 'Peredaran bruto > Rp50 M → tarif normal Pasal 17';
        }
        if ($r->pkp_fas > 0) $r->rincian[] = array('50% × ' . nf($tarif, 0) . '% × ' . nf($r->pkp_fas), round($r->pkp_fas * $t * 0.5, 0));
        if ($r->pkp_non > 0) $r->rincian[] = array(nf($tarif, 0) . '% × ' . nf($r->pkp_non), round($r->pkp_non * $t, 0));
        foreach ($r->rincian as $x) $r->pph += $x[1];
        return $r;
    }

    public function rekon_fiskal($tahun)
    {
        $CI =& get_instance();
        $CI->load->model('accounting/Mod_lapkeu');
        $prof = $this->profil();
        $dari = $tahun . '-01-01'; $sampai = $tahun . '-12-31';
        $lr = $CI->Mod_lapkeu->laba_rugi(array(array('label' => (string) $tahun, 'dari' => $dari, 'sampai' => $sampai)));
        $labaKomersial = $lr['ringkas']['laba_sebelum_pajak'][0];

        // koreksi otomatis dari tanda fiskal akun
        $pos = array(); $neg = array();
        foreach ($lr['seksi'] as $kat => $sek) {
            if ($kat === 'PAJAK_PENGHASILAN') continue;
            $isPend = Akuntansi::grup_kategori($kat) === 'PENDAPATAN';
            foreach ($sek->rows as $r) {
                $a = $this->akuntansi->akun($r->kode);
                $fk = $a && isset($a->fiskal) ? $a->fiskal : '';
                $v = $r->nilai[0];
                if (abs($v) < 0.005 || $fk === '') continue;
                $baris = (object) array('ket' => $r->kode . ' ' . $r->nama, 'nilai' => abs($v), 'sumber' => 'akun',
                    'dasar' => $fk === 'POSITIF' ? 'Biaya tidak boleh dikurangkan (Ps. 9 UU PPh)' : ($isPend ? 'Penghasilan sudah dikenai PPh Final / bukan objek' : 'Biaya untuk penghasilan final'));
                if ($fk === 'POSITIF') { if ($isPend) $neg[] = $baris; else $pos[] = $baris; }
                elseif ($fk === 'FINAL') { if ($isPend) $neg[] = $baris; else $pos[] = $baris; }
            }
        }
        foreach ($this->koreksi_list($tahun) as $k) {
            $b = (object) array('ket' => $k->keterangan, 'nilai' => (float) $k->nilai, 'sumber' => 'manual', 'dasar' => 'Input manual', 'id' => $k->id);
            if ($k->jenis === 'NEGATIF') $neg[] = $b; else $pos[] = $b;
        }
        $tPos = 0; foreach ($pos as $x) $tPos += $x->nilai;
        $tNeg = 0; foreach ($neg as $x) $tNeg += $x->nilai;
        $labaFiskal = round($labaKomersial + $tPos - $tNeg, 2);
        $bruto = $this->bruto($tahun);
        $kredit = $this->kredit_pajak($tahun);
        $tKredit = 0; foreach ($kredit as $k) $tKredit += $k->nilai;

        $final = $prof->rezim === 'FINAL_UMKM';
        $hit = $this->hitung_pph_badan($labaFiskal, $bruto, $prof->tarif_badan);
        $pphFinal = 0; $bulanan = array();
        if ($final) {
            foreach ($this->bruto_bulanan($tahun) as $b => $v) { $bulanan[$b] = (object) array('bruto' => $v, 'pph' => round(max(0, $v) * $prof->tarif_final / 100, 0)); $pphFinal += $bulanan[$b]->pph; }
            $setorFinal = array_sum($this->setor_per_masa($tahun, 'PPHFINAL'));
        }
        $terutang = $final ? 0 : $hit->pph;
        $pph29 = round($terutang - $tKredit, 0);
        $k22 = $kredit['akun_pph22_dimuka']->nilai; $k23 = $kredit['akun_pph23_dimuka']->nilai;
        $pph25 = $final ? 0 : max(0, round(($terutang - $k22 - $k23) / 12, 0));
        $jurnal = $this->db->select('no_ref')->where('sumber', 'PJK')->where('no_bukti', 'PPHB-' . $tahun)->get('tbl_acc_jurnal_umum', 1)->row();
        return array(
            'tahun' => $tahun, 'profil' => $prof, 'lr' => $lr, 'laba_komersial' => $labaKomersial,
            'positif' => $pos, 'negatif' => $neg, 'total_positif' => $tPos, 'total_negatif' => $tNeg,
            'laba_fiskal' => $labaFiskal, 'bruto' => $bruto, 'hitung' => $hit, 'final' => $final,
            'pph_final' => $pphFinal, 'pph_final_bulanan' => $bulanan, 'setor_final' => isset($setorFinal) ? $setorFinal : 0,
            'terutang' => $terutang, 'kredit' => $kredit, 'total_kredit' => $tKredit, 'pph29' => $pph29, 'pph25' => $pph25,
            'jurnal' => $jurnal ? $jurnal->no_ref : '',
            'beban_pajak_tercatat' => $lr['seksi']['PAJAK_PENGHASILAN']->total[0],
        );
    }

    /** estimasi angsuran PPh 25 tahun ini dari rekonsiliasi tahun lalu */
    public function estimasi_pph25()
    {
        $r = $this->rekon_fiskal((int) date('Y') - 1);
        return $r['pph25'];
    }

    /** jurnal beban PPh Badan 31 Desember */
    public function jurnal_pph_badan($tahun)
    {
        $this->error = '';
        $r = $this->rekon_fiskal($tahun);
        if ($r['final']) return $this->gagal('Rezim PPh Final UMKM: PPh dicatat lewat setoran bulanan (jenis PPh Final), tidak ada jurnal PPh Badan tahunan.');
        if ($r['jurnal']) return $this->gagal('Jurnal PPh Badan ' . $tahun . ' sudah dibuat (' . $r['jurnal'] . ').');
        if ($this->db->where('sumber', 'TTP')->where('tgl_jurnal', $tahun . '-12-31')->where('status', 'Y')->count_all_results('tbl_acc_jurnal_umum')) {
            return $this->gagal('Jurnal penutup ' . $tahun . ' sudah ada. Hapus jurnal penutup dulu di menu Tutup Buku, buat jurnal PPh Badan, lalu buat ulang jurnal penutup.');
        }
        $beban = $this->akun('akun_beban_pph_badan'); $hut = $this->akun('akun_hutang_pph_badan');
        if ( ! $beban || ! $hut) return $this->gagal('Akun Beban PPh Badan dan Hutang PPh Badan wajib diatur.');
        $jl = array();
        if ($r['terutang'] > 0) $jl[] = array('kode_akun' => $beban, 'debit' => $r['terutang'], 'keterangan' => 'Beban PPh Badan ' . $tahun);
        foreach ($r['kredit'] as $k) if ($k->kode && $k->nilai > 0) $jl[] = array('kode_akun' => $k->kode, 'kredit' => $k->nilai, 'keterangan' => 'Kredit pajak ' . $k->label . ' ' . $tahun);
        if ($r['pph29'] > 0) $jl[] = array('kode_akun' => $hut, 'kredit' => $r['pph29'], 'keterangan' => 'PPh 29 kurang bayar ' . $tahun);
        elseif ($r['pph29'] < 0) $jl[] = array('kode_akun' => $hut, 'debit' => -$r['pph29'], 'keterangan' => 'PPh lebih bayar ' . $tahun . ' (restitusi/kompensasi)');
        if (count($jl) < 2) return $this->gagal('Tidak ada PPh terutang maupun kredit pajak pada tahun ' . $tahun . '.');
        $this->db->trans_begin();
        $ref = $this->akuntansi->posting('PJK', $tahun . '-12-31', 'PPh Badan tahun ' . $tahun, $jl, null, 'PPHB-' . $tahun);
        if ( ! $ref) return $this->gagal($this->akuntansi->error);
        $this->db->trans_commit();
        return $ref;
    }

    public function batal_pph_badan($tahun)
    {
        $r = $this->db->select('no_ref')->where('sumber', 'PJK')->where('no_bukti', 'PPHB-' . $tahun)->get('tbl_acc_jurnal_umum', 1)->row();
        if ( ! $r) return $this->gagal('Jurnal PPh Badan ' . $tahun . ' tidak ditemukan.');
        if ($this->db->where('sumber', 'TTP')->where('tgl_jurnal', $tahun . '-12-31')->where('status', 'Y')->count_all_results('tbl_acc_jurnal_umum')) {
            return $this->gagal('Hapus jurnal penutup ' . $tahun . ' terlebih dahulu.');
        }
        if ( ! $this->akuntansi->hapus_jurnal($r->no_ref)) return $this->gagal($this->akuntansi->error);
        return true;
    }

    /* ==================================================================
     * RINGKASAN TAHUNAN & KALENDER
     * ================================================================ */
    public function ringkasan_tahunan($tahun)
    {
        $prof = $this->profil();
        $ppn = $this->ppn_tahun($tahun);
        $kode = array();
        foreach (array('akun_hutang_pph21' => 'pph21', 'akun_hutang_pph23' => 'pph23', 'akun_hutang_pph42' => 'pph42') as $k => $n) {
            $x = $this->akun($k); if ($x) $kode[$n] = $x;
        }
        $mut = $this->mutasi_bulanan($tahun, array_values($kode));
        $setor = array();
        foreach (array_keys(Akuntansi::$JENIS_PAJAK) as $j) $setor[$j] = $this->setor_per_masa($tahun, $j);
        $rows = array();
        for ($b = 1; $b <= 12; $b++) {
            $per = sprintf('%04d-%02d', $tahun, $b);
            $bruto = $this->bruto($tahun, $b);
            $r = (object) array('bulan' => $b, 'periode' => $per, 'bruto' => $bruto,
                'ppn_k' => $ppn['rows'][$b]->ppn_k, 'ppn_m' => $ppn['rows'][$b]->ppn_m, 'ppn_kb' => $ppn['rows'][$b]->kb, 'ppn_lb' => $ppn['rows'][$b]->lb,
                'pph_final' => $prof->rezim === 'FINAL_UMKM' ? round(max(0, $bruto) * $prof->tarif_final / 100, 0) : 0);
            $potong = 0;
            foreach ($kode as $n => $k) { $r->$n = $mut[$k][$b]['k']; $potong += $mut[$k][$b]['k']; }
            foreach (array('pph21', 'pph23', 'pph42') as $n) if ( ! isset($r->$n)) $r->$n = 0;
            $r->setor = 0; foreach ($setor as $j => $per2) $r->setor += isset($per2[$per]) ? $per2[$per] : 0;
            $r->kewajiban = $r->ppn_kb + $potong + $r->pph_final;
            $rows[$b] = $r;
        }
        return array('rows' => $rows, 'profil' => $prof, 'setor' => $setor);
    }

    /** jadwal kewajiban pajak bulan berjalan (berdasarkan ketentuan umum; cek perubahan aturan DJP) */
    public function kalender($tahun, $bulan)
    {
        $prof = $this->profil();
        $masaLalu = date('Y-m', strtotime(sprintf('%04d-%02d-01', $tahun, $bulan) . ' -1 month'));
        $nm = bulan_panjang((int) substr($masaLalu, 5, 2)) . ' ' . substr($masaLalu, 0, 4);
        $t15 = sprintf('%04d-%02d-15', $tahun, $bulan);
        $t20 = sprintf('%04d-%02d-20', $tahun, $bulan);
        $akhir = date('Y-m-t', strtotime(sprintf('%04d-%02d-01', $tahun, $bulan)));
        $out = array(
            array($t15, 'Setor PPh 21, PPh 23, PPh 4(2) masa ' . $nm, 'PPH'),
            array($t15, $prof->rezim === 'FINAL_UMKM' ? 'Setor PPh Final 0,5% masa ' . $nm : 'Setor angsuran PPh 25 masa ' . $nm, 'PPH'),
            array($t20, 'Lapor SPT Masa PPh 21/26 & SPT Masa PPh Unifikasi (23, 4(2)) masa ' . $nm, 'LAPOR'),
        );
        if ($prof->pkp) $out[] = array($akhir, 'Setor & lapor SPT Masa PPN masa ' . $nm . ' (sebelum SPT dilaporkan)', 'PPN');
        if ($bulan <= 4) $out[] = array(sprintf('%04d-04-30', $tahun), 'Batas lapor SPT Tahunan PPh Badan tahun ' . ($tahun - 1) . ' & setor PPh 29 sebelum SPT', 'TAHUNAN');
        usort($out, function ($a, $b) { return strcmp($a[0], $b[0]); });
        return $out;
    }
}
