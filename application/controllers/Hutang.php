<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Piutang.php';

/** Hutang Usaha (Faktur Pembelian + Pembayaran Hutang) */
class Hutang extends Piutang
{
    protected $jenis = 'AP';
    protected $nama  = 'Hutang';
}
