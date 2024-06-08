<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportAccPerpk extends MY_Controller
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
		$data['page'] 		= "Report Barang per Divisi";
		$data['judul'] 		= "Report Barang";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $data['dataPk'] = $this->Mod_reportacc->select_pk();
        $this->template->load('layoutbackend', 'accounting/report/part_jenis_pk', $data);
    }

    public function listPk() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        
        $jns_pk 				= $_GET['jns_pk'];
		$data['dataBody'] = $this->Mod_reportacc->cari_per_pk($jns_pk,$ttmp1,$ttmp2); 
        $this->load->view('accounting/report/data_jenis_pk', $data);
	}
    
}