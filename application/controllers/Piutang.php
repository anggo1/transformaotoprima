<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Piutang Usaha (Faktur Penjualan + Penerimaan Pembayaran).
 * Controller Hutang mewarisi class ini dengan $jenis = 'AP'.
 */
class Piutang extends MY_Controller
{
    protected $jenis = 'AR';
    protected $nama  = 'Piutang';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('accounting/Mod_acctrx');
        $this->load->library('akuntansi');
        $this->wajib('view');
    }

    protected function label()
    {
        return $this->jenis === 'AR'
            ? array('judul' => 'Piutang Usaha', 'faktur' => 'Faktur Penjualan', 'kontak' => 'Pelanggan', 'bayar' => 'Penerimaan Pembayaran', 'kat' => 'PIUTANG', 'akun_default' => 'akun_piutang', 'lawan_default' => 'akun_pendapatan_default')
            : array('judul' => 'Hutang Usaha', 'faktur' => 'Faktur Pembelian / Tagihan', 'kontak' => 'Pemasok', 'bayar' => 'Pembayaran Hutang', 'kat' => 'HUTANG', 'akun_default' => 'akun_hutang', 'lawan_default' => 'akun_beban_default');
    }

    public function index()
    {
        $dari = tgl_sql($this->input->get('dari')) ?: date('Y-01-01');
        $sampai = tgl_sql($this->input->get('sampai')) ?: date('Y-m-d');
        $status = (string) $this->input->get('status');
        $kontak = trim((string) $this->input->get('kontak'));
        $L = $this->label();
        $data = array(
            'page' => $L['judul'], 'judul' => $L['judul'], 'L' => $L, 'jenis' => $this->jenis, 'ctrl' => $this->nama,
            'rows' => $this->Mod_acctrx->faktur_list($this->jenis, $dari, $sampai, $status, $kontak),
            'ringkas' => $this->Mod_acctrx->ringkasan_faktur($this->jenis),
            'f' => compact('dari', 'sampai', 'status', 'kontak'),
            'boleh' => array('add' => $this->boleh('add'), 'delete' => $this->boleh('delete'), 'print' => $this->boleh('print')),
        );
        $this->template->load('layoutbackend', 'accounting/trx/faktur', $data);
    }

    public function form()
    {
        $this->wajib('add');
        $L = $this->label();
        $this->load->view('accounting/trx/faktur_form', array(
            'L' => $L, 'jenis' => $this->jenis, 'ctrl' => $this->nama, 'akun' => $this->akuntansi->akun(),
            'kontak' => $this->Mod_acctrx->kontak($this->jenis),
            'akunArAp' => $this->akuntansi->setting($L['akun_default']),
            'akunLawan' => $this->akuntansi->setting($L['lawan_default']),
            'ppn' => $this->akuntansi->setting('ppn_persen', '11'),
        ));
    }

    public function simpan()
    {
        $this->wajib('add');
        $p = $this->input->post();
        $g = function ($k) use ($p) { return isset($p[$k]) ? $p[$k] : ''; };
        $h = array('jenis' => $this->jenis, 'no_faktur' => $g('no_faktur'), 'tanggal' => $g('tanggal'), 'jatuh_tempo' => $g('jatuh_tempo'),
            'kontak' => $g('kontak'), 'no_referensi' => $g('no_referensi'), 'keterangan' => $g('keterangan'),
            'kode_akun_ar_ap' => $g('kode_akun_ar_ap'), 'diskon' => $g('diskon'), 'ppn_persen' => $g('pakai_ppn') ? $g('ppn_persen') : 0,
            'no_faktur_pajak' => $g('no_faktur_pajak'));
        $lines = isset($p['baris']) && is_array($p['baris']) ? array_values($p['baris']) : array();
        $id = $this->Mod_acctrx->faktur_simpan($h, $lines);
        if ( ! $id) $this->json(array('status' => false, 'pesan' => $this->Mod_acctrx->error));
        $out = array('status' => true, 'pesan' => 'Faktur tersimpan dan sudah dijurnal.');
        if ( ! empty($p['cetak'])) $out['cetak'] = site_url($this->nama . '/cetak/' . $id);
        $this->json($out);
    }

    public function detail($id = 0)
    {
        $f = $this->Mod_acctrx->faktur_get($id, $this->jenis);
        if ( ! $f) show_404();
        $this->load->view('accounting/trx/faktur_detail', array('f' => $f, 'L' => $this->label(), 'ctrl' => $this->nama,
            'akun' => $this->akuntansi->akun(), 'boleh' => array('add' => $this->boleh('add'), 'delete' => $this->boleh('delete'))));
    }

    public function form_bayar($id = 0)
    {
        $this->wajib('add');
        $f = $this->Mod_acctrx->faktur_get($id, $this->jenis);
        if ( ! $f) show_404();
        $this->load->view('accounting/trx/faktur_bayar', array('f' => $f, 'L' => $this->label(), 'ctrl' => $this->nama, 'akun' => $this->akuntansi->akun(),
            'dpp' => (float) $f->subtotal - (float) $f->diskon, 'pph23' => $this->akuntansi->setting('pph23_persen', '2')));
    }

    public function simpan_bayar()
    {
        $this->wajib('add');
        $p = $this->input->post();
        $id = $this->Mod_acctrx->pelunasan_simpan(isset($p['id_faktur']) ? $p['id_faktur'] : 0, $this->jenis, array(
            'tanggal' => isset($p['tanggal']) ? $p['tanggal'] : '', 'kode_akun_kas' => isset($p['kode_akun_kas']) ? $p['kode_akun_kas'] : '',
            'jumlah' => isset($p['jumlah']) ? $p['jumlah'] : 0, 'keterangan' => isset($p['keterangan']) ? $p['keterangan'] : '',
            'potongan' => isset($p['potongan']) ? $p['potongan'] : 0, 'jenis_pph' => isset($p['jenis_pph']) ? $p['jenis_pph'] : '',
            'no_bukti_potong' => isset($p['no_bukti_potong']) ? $p['no_bukti_potong'] : ''));
        $this->json($id ? array('status' => true, 'pesan' => 'Pembayaran tersimpan dan sudah dijurnal.') : array('status' => false, 'pesan' => $this->Mod_acctrx->error));
    }

    public function hapus_bayar()
    {
        $this->wajib('delete');
        $ok = $this->Mod_acctrx->pelunasan_hapus($this->input->post('id'), $this->jenis);
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Pembayaran dihapus.' : $this->Mod_acctrx->error));
    }

    public function batal()
    {
        $this->wajib('delete');
        $ok = $this->Mod_acctrx->faktur_batal($this->input->post('id'), $this->jenis);
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Faktur dibatalkan & jurnal dihapus.' : $this->Mod_acctrx->error));
    }

    public function cetak($id = 0)
    {
        $this->wajib('print');
        $f = $this->Mod_acctrx->faktur_get($id, $this->jenis);
        if ( ! $f) show_404();
        $this->load->view('accounting/trx/faktur_cetak', array('f' => $f, 'L' => $this->label(), 'akun' => $this->akuntansi->akun(),
            'apl' => $this->db->get('aplikasi', 1)->row()));
    }
}
