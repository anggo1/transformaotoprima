<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportUpahBorong extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_report_upah_borong', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Report Biaya Upah Borongan";
		$data['judul'] 		= "Report Acc";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'accounting/report_upah_borong', $data);
    }

    public function listBayar() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
        $no_body 				= $_GET['no_body'];
        $no_pk 				= $_GET['no_pk'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        if(empty($no_body)&&(empty($no_pk))){
            $data['dataPk'] = $this->Mod_report_upah_borong->cari_bayar($ttmp1, $ttmp2, $no_body,$no_pk);
            $this->load->view('accounting/report_upah_borong_data', $data);
        } else {
            $data['dataPk'] = $this->Mod_report_upah_borong->cari_bayar($ttmp1, $ttmp2, $no_body,$no_pk);
            $this->load->view('accounting/report_upah_borong_data_body', $data);
        }
	}
    public function listBayarDetail() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
        $no_body 				= $_GET['no_body'];
        $no_pk 				= $_GET['no_pk'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        if(empty($no_body)&&(empty($no_pk))){
            $data['dataPk'] = $this->Mod_report_upah_borong->cari_bayar_detail($ttmp1, $ttmp2, $no_body,$no_pk);
            $this->load->view('accounting/report_upah_borong_data', $data);
        } else {
            $data['dataPk'] = $this->Mod_report_upah_borong->cari_bayar_detail($ttmp1, $ttmp2, $no_body,$no_pk);
            $this->load->view('accounting/report_upah_borong_data_body_detail', $data);
        }
	}
    public function cetakBayar()
	{
		$date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
        $no_body 				= $_GET['no_body'];
        $no_pk				= $_GET['no_pk'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        if(empty($no_body)&&(empty($no_pk))){
            $data['dataPk'] = $this->Mod_report_upah_borong->cari_bayar($ttmp1, $ttmp2, $no_body,$no_pk);
            echo show_my_print('accounting/modals/modal_cetak_report_upah_borongan', 'cetak-pk', $data, ' modal-xl');
        } else {
            $data['dataPk'] = $this->Mod_report_upah_borong->cari_bayar($ttmp1, $ttmp2, $no_body,$no_pk);
            echo show_my_print('accounting/modals/modal_cetak_report_upah_borongan_body', 'cetak-pk', $data, ' modal-xl');
        }
	}

}