<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportWhPerbody extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_reportwhpo', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Report Per Body";
		$data['judul'] 		= "Report Body";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'warehouse/report_wh/part_perbody', $data);
    }

    public function listBody() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        
        $no_body 				= $_GET['no_body'];
		$data['dataBody'] = $this->Mod_reportwhpo->cari_body($no_body,$ttmp1,$ttmp2); 
        $this->load->view('warehouse/report_wh/data_body', $data);
	}
    
}