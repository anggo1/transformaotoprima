<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportWhMasuk extends MY_Controller
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
		$data['page'] 		= "Report Barang Masuk";
		$data['judul'] 		= "Barang Masuk";
        $this->load->helper('url');
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_reportwhpo->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_reportwhpo->select_by_level($idlevel, $id_sub);

        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'warehouse/report_wh/part_masuk', $data);
    }

    public function listMasuk() {
        $this->load->helper('url');
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_reportwhpo->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_reportwhpo->select_by_level($idlevel, $id_sub);

        $status_po 				= $_GET['status_po'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataMasuk'] = $this->Mod_reportwhpo->cari_masuk($ttmp1,$ttmp2,$status_po); 
        if($status_po=='Y'){
        $this->load->view('warehouse/report_wh/data_part_masuk', $data);
        }else{
            $this->load->view('warehouse/report_wh/data_part_masuk_npo_global', $data);
        }
	}
    public function listMasukCetak() {
        $status_po 				= $_GET['status_po'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        $data['dataMasuk'] = $this->Mod_reportwhpo->cari_masuk($ttmp1,$ttmp2,$status_po);
        $data['tgl_awal'] = $date1;
        $data['tgl_akhir'] = $date2;
        $data['sub_status'] = "GLOBAL";
        if($status_po=='Y'){
            echo show_my_print('warehouse/modals/modal_cetak_data_part_masuk_po', 'cetak-masuk', $data, ' modal-xl');
        }else{
            echo show_my_print('warehouse/modals/modal_cetak_data_part_masuk_npo_global', 'cetak-masuk', $data, ' modal-xl');
        }
	}
    public function listMasukStatusCetak() {
        $status_po 				= $_GET['status_po'];
        $status 				= $_GET['status'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";

		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataMasuk'] = $this->Mod_reportwhpo->cari_masuk_status($ttmp1,$ttmp2,$status_po,$status); 
        $data['tgl_awal'] = $date1;
        $data['tgl_akhir'] = $date2;
        $data['sub_status'] = $status;
        if($status_po=='Y'){
            echo show_my_print('warehouse/modals/modal_cetak_data_part_masuk_po', 'cetak-masuk', $data, ' modal-xl');
        }else{
            echo show_my_print('warehouse/modals/modal_cetak_data_part_masuk_npo_global', 'cetak-masuk', $data, ' modal-xl');
        }
	}
    public function listMasukStatus() {
        $this->load->helper('url');
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_reportwhpo->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_reportwhpo->select_by_level($idlevel, $id_sub);

        $status_po 				= $_GET['status_po'];
        $status 				= $_GET['status'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataMasuk'] = $this->Mod_reportwhpo->cari_masuk_status($ttmp1,$ttmp2,$status_po,$status); 
        if($status_po=='Y'){
        $this->load->view('warehouse/report_wh/data_part_masuk_po', $data);
        }else{
            $this->load->view('warehouse/report_wh/data_part_masuk_npo', $data);
        }
	}
    public function listMasukPo() {
        $status_po 				= $_GET['status_po'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataMasuk'] = $this->Mod_reportwhpo->cari_masuk_po($ttmp1,$ttmp2,$status_po); 
        $this->load->view('warehouse/report_wh/data_part_masuk_po', $data);
	}
    
    public function listMasukNpo() {
        $status_po 				= $_GET['status_po'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataMasuk'] = $this->Mod_reportwhpo->cari_masuk_npo($ttmp1,$ttmp2,$status_po); 
        $this->load->view('warehouse/report_wh/data_part_masuk_npo', $data);
	}
    public function listDetailMasuk() {
        $status_po 				= $_GET['status_po'];
        $status 				= $_GET['status'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        $data['tgl_awal'] = $date1;
        $data['tgl_akhir'] = $date2;
        $data['status'] = $status;
        $data['status_po'] = $status_po;
		$data['dataMasuk'] = $this->Mod_reportwhpo->cari_detail_masuk($ttmp1,$ttmp2,$status_po,$status); 
        if($status_po=='Y'){
        $this->load->view('warehouse/report_wh/data_detail_part_masuk_po', $data);
        }else{
        $this->load->view('warehouse/report_wh/data_detail_part_masuk_npo', $data);
        }
	}
    public function CetakDetailMasuk() {
        $status_po 				= $_GET['status_po'];
        $status 				= $_GET['status'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        $data['tgl_awal'] = $date1;
        $data['tgl_akhir'] = $date2;
        $data['status'] = $status;
        $data['status_po'] = $status_po;
		$data['dataMasuk'] = $this->Mod_reportwhpo->cari_detail_masuk($ttmp1,$ttmp2,$status_po,$status); 
        if($status_po=='Y'){
            echo show_my_print('warehouse/modals/modal_cetak_part_masuk_detail_po', 'cetak-masuk-detail', $data, ' modal-xl');
        }else{
            echo show_my_print('warehouse/modals/modal_cetak_part_masuk_detail_npo', 'cetak-masuk-detail', $data, ' modal-xl');
        }
	}
    public function cetakBon()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_reportwhpo->cetak_pk_bon($id);
		$data['dataDetail'] = $this->Mod_reportwhpo->cetak_bon($id);

		echo show_my_print('warehouse/modals/modal_cetak_bon', 'cetak-bon', $data, ' modal-xl');
	}
    public function deleteData()
    {
        $id = $_POST['id'];
        $data_status = $_POST['status'];
        $result = $this->Mod_reportwhpo->deleteMasuk($id,$data_status);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
}