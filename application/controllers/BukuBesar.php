<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Buku Besar lama digantikan oleh Laporan Keuangan → Buku Besar
 * (saldo awal, saldo berjalan, filter akun, cetak & Excel).
 * Controller ini hanya mengalihkan bookmark/link lama.
 */
class BukuBesar extends MY_Controller
{
    public function index()
    {
        redirect('LaporanKeuangan/buku_besar');
    }

    public function _remap($method)
    {
        $this->index();
    }
}
