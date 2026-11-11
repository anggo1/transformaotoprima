<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kas & Bank: Penerimaan, Pengeluaran, Transfer (jurnal otomatis) */
class KasBank extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('accounting/Mod_acctrx');
        $this->load->library('akuntansi');
        $this->wajib('view');
    }

    public function index()
    {
        $dari = tgl_sql($this->input->get('dari')) ?: date('Y-m-01');
        $sampai = tgl_sql($this->input->get('sampai')) ?: date('Y-m-d');
        $jenis = (string) $this->input->get('jenis');
        $akun = (string) $this->input->get('akun');
        $data = array(
            'page' => 'Kas & Bank', 'judul' => 'Kas & Bank',
            'rows' => $this->Mod_acctrx->kb_list($dari, $sampai, $jenis, $akun),
            'saldo' => $this->Mod_acctrx->saldo_kas(),
            'akunKas' => $this->akuntansi->akun_kategori('KAS_BANK'),
            'f' => compact('dari', 'sampai', 'jenis', 'akun'),
            'boleh' => array('add' => $this->boleh('add'), 'delete' => $this->boleh('delete'), 'print' => $this->boleh('print')),
        );
        $this->template->load('layoutbackend', 'accounting/trx/kas_bank', $data);
    }

    public function form()
    {
        $this->wajib('add');
        $jenis = strtoupper((string) $this->input->get('jenis'));
        if ( ! in_array($jenis, array('MASUK', 'KELUAR', 'TRANSFER'), true)) $jenis = 'MASUK';
        $this->load->view('accounting/trx/kas_bank_form', array('jenis' => $jenis, 'akun' => $this->akuntansi->akun()));
    }

    public function simpan()
    {
        $this->wajib('add');
        $p = $this->input->post();
        $h = array(
            'jenis' => isset($p['jenis']) ? $p['jenis'] : '', 'tanggal' => isset($p['tanggal']) ? $p['tanggal'] : '',
            'kode_akun_kas' => isset($p['kode_akun_kas']) ? $p['kode_akun_kas'] : '',
            'kode_akun_tujuan' => isset($p['kode_akun_tujuan']) ? $p['kode_akun_tujuan'] : '',
            'kontak' => isset($p['kontak']) ? $p['kontak'] : '', 'no_bukti' => isset($p['no_bukti']) ? $p['no_bukti'] : '',
            'keterangan' => isset($p['keterangan']) ? $p['keterangan'] : '',
        );
        $lines = isset($p['baris']) && is_array($p['baris']) ? array_values($p['baris']) : array();
        $id = $this->Mod_acctrx->kb_simpan($h, $lines, isset($p['jumlah']) ? $p['jumlah'] : 0);
        if ( ! $id) $this->json(array('status' => false, 'pesan' => $this->Mod_acctrx->error));
        $out = array('status' => true, 'pesan' => 'Transaksi kas & bank tersimpan dan sudah dijurnal.');
        if ( ! empty($p['cetak'])) $out['cetak'] = site_url('KasBank/cetak/' . $id);
        $this->json($out);
    }

    public function detail($id = 0)
    {
        $h = $this->Mod_acctrx->kb_get($id);
        if ( ! $h) show_404();
        $jurnal = $h->no_ref_jurnal ? $this->db->order_by('id_jurnal')->get_where('tbl_acc_jurnal_umum', array('no_ref' => $h->no_ref_jurnal))->result() : array();
        $this->load->view('accounting/trx/kas_bank_detail', array('h' => $h, 'jurnal' => $jurnal, 'akun' => $this->akuntansi->akun()));
    }

    public function batal()
    {
        $this->wajib('delete');
        $ok = $this->Mod_acctrx->kb_batal($this->input->post('id'));
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Transaksi dibatalkan & jurnal dihapus.' : $this->Mod_acctrx->error));
    }

    public function cetak($id = 0)
    {
        $this->wajib('print');
        $h = $this->Mod_acctrx->kb_get($id);
        if ( ! $h) show_404();
        $this->load->view('accounting/trx/kas_bank_cetak', array('h' => $h, 'akun' => $this->akuntansi->akun(),
            'apl' => $this->db->get('aplikasi', 1)->row()));
    }
}
