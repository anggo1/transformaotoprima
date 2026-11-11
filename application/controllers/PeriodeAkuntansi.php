<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Tutup buku per bulan, jurnal penutup tahunan & preferensi akun default */
class PeriodeAkuntansi extends MY_Controller
{
    private $prefKunci = array(
        'nama_perusahaan_laporan' => array('Nama perusahaan di laporan', 'text'),
        'akun_laba_ditahan' => array('Akun Laba Ditahan', 'EKUITAS'),
        'akun_laba_berjalan' => array('Akun Laba Tahun Berjalan', 'EKUITAS'),
        'akun_piutang' => array('Akun Piutang default', 'PIUTANG'),
        'akun_hutang' => array('Akun Hutang default', 'HUTANG'),
        'akun_pendapatan_default' => array('Akun Pendapatan default (faktur penjualan)', 'PENDAPATAN'),
        'akun_beban_default' => array('Akun Beban default (faktur pembelian)', '*'),
        'akun_ppn_keluaran' => array('Akun PPN Keluaran', '*'),
        'akun_ppn_masukan' => array('Akun PPN Masukan', '*'),
        'ppn_persen' => array('Tarif PPN default (%)', 'number'),
        'akun_akumulasi_default' => array('Akun Akumulasi Penyusutan default', 'AKUM_SUSUT'),
        'akun_beban_susut_default' => array('Akun Beban Penyusutan default', 'BEBAN'),
        'akun_laba_pelepasan_aset' => array('Akun Laba Pelepasan Aset', 'PENDAPATAN_LAIN'),
        'akun_rugi_pelepasan_aset' => array('Akun Rugi Pelepasan Aset', 'BEBAN_LAIN'),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('accounting/Mod_acctrx');
        $this->load->library('akuntansi');
        $this->wajib('view');
    }

    public function index()
    {
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));
        $this->template->load('layoutbackend', 'accounting/trx/periode', array('page' => 'Tutup Buku & Preferensi', 'judul' => 'Periode Akuntansi',
            'tahun' => $tahun, 'periode' => $this->Mod_acctrx->periode_tahun($tahun), 'pref' => $this->prefKunci,
            'setting' => $this->akuntansi->setting(), 'akun' => $this->akuntansi->akun(),
            'penutup' => $this->db->where('sumber', 'TTP')->where('tgl_jurnal', $tahun . '-12-31')->where('status', 'Y')->count_all_results('tbl_acc_jurnal_umum') > 0,
            'boleh' => array('edit' => $this->boleh('edit'), 'add' => $this->boleh('add'), 'delete' => $this->boleh('delete'))));
    }

    public function set()
    {
        $this->wajib('edit');
        $per = (string) $this->input->post('periode');
        $st = (string) $this->input->post('status');
        $ok = $this->Mod_acctrx->set_periode($per, $st);
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Periode ' . $per . ($st === 'TUTUP' ? ' ditutup.' : ' dibuka kembali.') : $this->Mod_acctrx->error));
    }

    public function penutup()
    {
        $this->wajib('add');
        $tahun = (int) $this->input->post('tahun');
        $ref = $this->Mod_acctrx->jurnal_penutup($tahun);
        $this->json(array('status' => (bool) $ref, 'pesan' => $ref ? 'Jurnal penutup ' . $tahun . ' dibuat (' . $ref . ').' : $this->Mod_acctrx->error));
    }

    public function batal_penutup()
    {
        $this->wajib('delete');
        $tahun = (int) $this->input->post('tahun');
        $r = $this->db->select('no_ref')->where('sumber', 'TTP')->where('tgl_jurnal', $tahun . '-12-31')->get('tbl_acc_jurnal_umum', 1)->row();
        if ( ! $r) $this->json(array('status' => false, 'pesan' => 'Jurnal penutup tidak ditemukan.'));
        $ok = $this->akuntansi->hapus_jurnal($r->no_ref);
        $this->json(array('status' => $ok, 'pesan' => $ok ? 'Jurnal penutup dihapus.' : $this->akuntansi->error));
    }

    public function simpan_pref()
    {
        $this->wajib('edit');
        $data = array();
        foreach ($this->prefKunci as $k => $x) {
            $v = $this->input->post($k);
            if ($v === null) continue;
            $v = trim((string) $v);
            if ($x[1] !== 'text' && $x[1] !== 'number' && $v !== '' && ! $this->akuntansi->akun($v)) {
                $this->json(array('status' => false, 'pesan' => $x[0] . ': akun tidak ditemukan.'));
            }
            $data[$k] = $x[1] === 'number' ? (string) to_number($v) : $v;
        }
        $this->akuntansi->simpan_setting($data);
        $this->json(array('status' => true, 'pesan' => 'Preferensi tersimpan.'));
    }
}
