<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Panduan Akuntansi & Pajak: alur sistem, checklist persiapan (dihitung dari data),
 * rutinitas bulanan/tahunan, referensi tipe akun, contoh jurnal, referensi pajak,
 * daftar laporan dan tanya-jawab.
 */
class PanduanAkuntansi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('akuntansi');
        $this->load->model('accounting/Mod_pajak');
        $this->config->load('panduan', true, true);
    }

    public function index()
    {
        require_once APPPATH . 'controllers/LaporanKeuangan.php';
        $data = array(
            'page' => 'Panduan Akuntansi & Pajak',
            'judul' => 'Panduan',
            'halaman' => (array) $this->config->item('halaman', 'panduan'),
            'laporan' => LaporanKeuangan::$LAPORAN,
            'penjelasan' => (array) $this->config->item('laporan', 'panduan'),
            'cek' => $this->checklist(),
            'profil' => $this->Mod_pajak->profil(),
        );
        $this->template->load('layoutbackend', 'accounting/panduan/index', $data);
    }

    /** checklist persiapan: [judul, ok(bool|null), keterangan, link] */
    private function checklist()
    {
        $c = array();
        $akun = $this->akuntansi->akun();
        $tanpa = 0; foreach ($akun as $a) if ($a->kategori === '' || ! isset(Akuntansi::$KATEGORI[$a->kategori])) $tanpa++;
        $c[] = array('Semua akun memiliki Tipe Akun', $tanpa === 0, $tanpa ? $tanpa . ' akun belum bertipe' : count($akun) . ' akun siap', 'Account');

        $kas = count($this->akuntansi->akun_kategori('KAS_BANK'));
        $c[] = array('Akun Kas & Bank sudah ditandai', $kas > 0, $kas . ' akun kas/bank', 'Account');

        $wajib = array('akun_laba_ditahan' => 'Laba Ditahan', 'akun_piutang' => 'Piutang', 'akun_hutang' => 'Hutang', 'akun_ppn_keluaran' => 'PPN Keluaran', 'akun_ppn_masukan' => 'PPN Masukan', 'akun_akumulasi_default' => 'Akumulasi Penyusutan', 'akun_beban_susut_default' => 'Beban Penyusutan');
        $kosong = array(); foreach ($wajib as $k => $l) { $v = (string) $this->akuntansi->setting($k); if ($v === '' || ! $this->akuntansi->akun($v)) $kosong[] = $l; }
        $c[] = array('Akun default di Preferensi terisi', ! $kosong, $kosong ? 'Belum: ' . implode(', ', $kosong) : 'Lengkap', 'PeriodeAkuntansi');

        $th = $this->akuntansi->tahun_saldo_awal(date('Y-m-d'));
        if ($th) {
            $r = $this->db->select('SUM(debit) AS d, SUM(kredit) AS k', false)->where('periode', $th)->get('tbl_acc_saldo_awal')->row();
            $sel = round((float) $r->d - (float) $r->k, 2);
            $c[] = array('Saldo Awal ' . $th . ' terisi & seimbang', abs($sel) < 0.01, abs($sel) < 0.01 ? 'Debit = Kredit' : 'Selisih ' . nf($sel, 2), 'Saldo');
        } else {
            $c[] = array('Saldo Awal terisi', false, 'Belum ada saldo awal', 'Saldo');
        }

        $p = $this->Mod_pajak->profil();
        $c[] = array('Profil pajak (NPWP, PKP, rezim PPh)', $p->npwp !== '', $p->npwp !== '' ? 'NPWP ' . $p->npwp : 'NPWP belum diisi', 'Pajak');
        $kurang = array(); foreach ($this->Mod_pajak->akun_pajak_daftar() as $k => $l) if ( ! $this->Mod_pajak->akun($k)) $kurang[] = $l;
        $c[] = array('Akun-akun pajak diatur', ! $kurang, $kurang ? 'Belum: ' . implode(', ', $kurang) : 'Lengkap', 'Pajak');

        $n = $this->db->table_exists('tbl_acc_aset') ? $this->db->count_all('tbl_acc_aset') : 0;
        $c[] = array('Aset tetap didaftarkan', $n > 0 ? true : null, $n . ' aset terdaftar', 'AsetTetap');
        return $c;
    }
}
