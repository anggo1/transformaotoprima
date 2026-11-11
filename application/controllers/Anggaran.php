<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Anggaran (budget) laba rugi per akun per bulan */
class Anggaran extends MY_Controller
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
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));
        $akun = array();
        foreach ($this->akuntansi->akun() as $a) {
            if (in_array(Akuntansi::grup_kategori($a->kategori), array('PENDAPATAN', 'BEBAN'), true) && $a->is_active !== 'N') $akun[] = $a;
        }
        usort($akun, function ($x, $y) {
            $ux = Akuntansi::$KATEGORI[$x->kategori][3]; $uy = Akuntansi::$KATEGORI[$y->kategori][3];
            return $ux === $uy ? strcmp($x->kode_akun, $y->kode_akun) : $ux - $uy;
        });
        $this->template->load('layoutbackend', 'accounting/trx/anggaran', array('page' => 'Anggaran', 'judul' => 'Anggaran',
            'tahun' => $tahun, 'akun' => $akun, 'data' => $this->Mod_acctrx->anggaran_get($tahun), 'boleh_edit' => $this->boleh('edit') || $this->boleh('add')));
    }

    public function simpan()
    {
        if ( ! $this->boleh('edit')) $this->wajib('add');
        $tahun = (int) $this->input->post('tahun');
        $data = $this->input->post('a');
        if ($tahun < 2000 || ! is_array($data)) $this->json(array('status' => false, 'pesan' => 'Data tidak valid.'));
        $this->Mod_acctrx->anggaran_simpan($tahun, $data);
        $this->json(array('status' => true, 'pesan' => 'Anggaran tahun ' . $tahun . ' tersimpan.'));
    }
}
