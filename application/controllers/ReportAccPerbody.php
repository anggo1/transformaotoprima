<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportAccPerbody extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_reportacc', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Report Per SPK";
		$data['judul'] 		= "Report SPK";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'accounting/report/part_perbody', $data);
    }

    public function listBody() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";

		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        //$no_body 				= $_GET['no_body'];
        $no_spk 				= $_GET['no_spk'];
        $data['dataPart'] = $this->Mod_reportacc->cari_spk_part($no_spk);
		$data['dataBody'] = $this->Mod_reportacc->cari_body($no_spk,$ttmp1,$ttmp2);
        $this->load->view('accounting/report/data_body', $data);
	}
    public function listCetak() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        //$no_body 				= $_GET['no_body'];
        $no_spk 				= $_GET['no_spk'];
        $data['dataPart'] = $this->Mod_reportacc->cari_spk_part($no_spk);
		$data['dataBody'] = $this->Mod_reportacc->cari_body($no_spk,$ttmp1,$ttmp2);
		echo show_my_print('accounting/report/modals/modal_cetak_report_body', 'cetak-data', $data, ' modal-xl');
	}
}