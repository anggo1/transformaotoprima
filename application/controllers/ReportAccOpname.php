<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportAccOpname extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_reportopname', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Report Stok Opname";
		$data['judul'] 		= "List Stok Opname";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'accounting/report/opname', $data);
    }

    public function listOpname() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataOpname'] = $this->Mod_reportopname->cari_opname($ttmp1,$ttmp2); 
        $this->load->view('accounting/report/data_opname', $data);
	}
    

    public function Detail()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_partpk->cetak_pk($id);

        echo show_my_modal('warehouse/modals/modal_part_pk', 'part-pk', $data, ' modal-md');
    }
    public function showDetail()
    {
        $id                 = $_GET['id_keluar']   ;
        $data['dataDetail'] = $this->Mod_partpk->select_detail($id);
        $this->load->view('warehouse/detail_part_pk', $data);
    }
    public function generate()
    {
        $id = $_POST['id'];
        $result = $this->Mod_reportopname->generateOpname($id);

        if ($result > 0) {
            //$out['datakode']=$kodeBaru;
            $out['status'] = '';
            $out['msg'] = show_del_msg('Berhasil', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Gagal !', '20px');
        }
        echo json_encode($out);
    }
    public function detailOpname()
	{
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataOpname'] = $this->Mod_reportopname->cari_opname_detail($ttmp1,$ttmp2); 
        $this->load->view('accounting/report/data_detail_opname', $data);
	}
    public function cetak_detailOpname()
	{
		
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataOpname'] = $this->Mod_reportopname->cari_opname_detail($ttmp1,$ttmp2); 

		echo show_my_print('accounting/report/modals/modal_cetak_opname_detail', 'cetak-masuk-detail', $data, ' modal-xl');
	}
    public function cetakListOpname()
	{
		
        
        $id = $_POST['id'];
		$data['dataOpname'] = $this->Mod_reportopname->cari_opname_list_detail($id); 

		echo show_my_print('accounting/modals/modal_cetak_opname_list_view', 'cetak-list-detail', $data, ' modal-xl');
	}
	public function deleteOpname()
    {
        $id = $_POST['id'];
        $result = $this->Mod_reportopname->deleteOpname($id);

        if ($result > 0) {
            //$out['datakode']=$kodeBaru;
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
}