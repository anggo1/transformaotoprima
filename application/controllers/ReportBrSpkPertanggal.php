<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportBrSpkPertanggal extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('body_repair_model/Mod_spkpertanggal', 'Mod_menu'));
        $this->load->model(array('body_repair_model/Mod_buspk'));
        $this->load->model(array('body_repair_model/Mod_busmasuk'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
        $this->load->library('ciqrcode');
    }

    public function index()
    {
		$data['page'] 		= "Report SPK Pertanggal";
		$data['judul'] 		= "Report Harian SPK";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'body_repair/report/spk_pertanggal', $data);
    }

    public function listPk() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataPk'] = $this->Mod_spkpertanggal->cari_pk($ttmp1,$ttmp2); 
        $this->load->view('body_repair/report/data_spk_pertanggal', $data);
	}
    public function detail_listPk() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataPk'] = $this->Mod_spkpertanggal->cari_detail_pk($ttmp1,$ttmp2); 
        $this->load->view('body_repair/report/data_spk_detail_pertanggal', $data);
	}
    public function cetakPk()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_spkpertanggal->cetak_pk($id);
		$data['detailPk'] = $this->Mod_spkpertanggal->cetak_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_pk_report', 'cetak-pk', $data, ' modal-xl');
	}
    public function cetakEstimasi()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_busmasuk->cetak_masuk($id);
		$data['detailPk'] = $this->Mod_busmasuk->cetak_estimasi($id);
		$data['hargaEstimasi'] = $this->Mod_busmasuk->harga_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_estimasi', 'cetak-estimasi', $data, ' modal-xl');
	}
    public function Detail()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_spkpertanggal->cetak_pk($id);

        echo show_my_modal('warehouse/modals/modal_part_pk', 'part-pk', $data, ' modal-md');
    }
   
}