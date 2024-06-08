<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportWhKeluar extends MY_Controller
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
		$data['page'] 		= "Report Barang Keluar";
		$data['judul'] 		= "Keluar Barang";
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
        $this->template->load('layoutbackend', 'warehouse/report_wh/part_keluar', $data);
    }

    public function listKeluar() {
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

		$data['dataKeluar'] = $this->Mod_reportwhpo->cari_keluar($status_po,$ttmp1,$ttmp2);
        if($status_po=="D"){
            $this->load->view('warehouse/report_wh/data_part_keluar_divisi', $data);
        }
        if($status_po=="Y"){
            $this->load->view('warehouse/report_wh/data_part_keluar_po', $data);
            json_encode($data);
        }
        if($status_po=="N"){
            $this->load->view('warehouse/report_wh/data_part_keluar', $data);
        }
	}
    public function listKeluarDetail() {
        $status_po 				= $_GET['status_po'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";

		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['detailKeluar'] = $this->Mod_reportwhpo->cari_keluar_detail($status_po,$ttmp1,$ttmp2);
        if($status_po=="D"){
            $this->load->view('warehouse/report_wh/data_part_keluar_divisi_detail', $data);
        }
        if($status_po=="Y"){
            $this->load->view('warehouse/report_wh/data_part_keluar_po_detail', $data);
        }
        if($status_po=="N"){
            $this->load->view('warehouse/report_wh/data_part_keluar_detail', $data);
        }
	}
    public function listKeluarCetak() {
        $status_po 				= $_GET['status_po'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";

		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";

		$data['dataKeluar'] = $this->Mod_reportwhpo->cari_keluar($status_po,$ttmp1,$ttmp2);
        if($status_po=="D"){
            echo show_my_print('warehouse/modals/modal_cetak_data_part_keluar_divisi', 'cetak-keluar-pk', $data, ' modal-xl');
        }
        if($status_po=="Y"){
            echo show_my_print('warehouse/modals/modal_cetak_data_part_keluar_pk', 'cetak-keluar-pk', $data, ' modal-xl');
        }
        if($status_po=="N"){
            echo show_my_print('warehouse/modals/modal_cetak_data_part_keluar', 'cetak-keluar-pk', $data, ' modal-xl');
        }
	}
    public function listKeluarDetailCetak() {
        $status_po 				= $_GET['status_po'];
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";

		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['detailKeluar'] = $this->Mod_reportwhpo->cari_keluar_detail($status_po,$ttmp1,$ttmp2);
        if($status_po=="D"){
            echo show_my_print('warehouse/modals/modal_cetak_data_part_keluar_divisi_detail', 'cetak-keluar-pk-detail', $data, ' modal-xl');
        }
        if($status_po=="Y"){
            echo show_my_print('warehouse/modals/modal_cetak_data_part_keluar_pk_detail', 'cetak-keluar-pk-detail', $data, ' modal-xl');
        }
        if($status_po=="N"){
            echo show_my_print('warehouse/modals/modal_cetak_data_part_keluar_detail', 'cetak-keluar-pk-detail', $data, ' modal-xl');
        }
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
    public function detailPk()
    {

        $this->form_validation->set_rules('id_pk', 'ID PK', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_partpk->detailpk($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_del_msg('Filed!', '20px');
            }
        } else {
            $out['status'] = 'form';
            $out['msg'] = show_err_msg(validation_errors());
        }

        echo json_encode($out);
    }
    public function cetakBon()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_partpk->cetak_pk_bon($id);
		$data['dataDetail'] = $this->Mod_partpk->cetak_bon($id);

		echo show_my_print('warehouse/modals/modal_cetak_bon', 'cetak-bon', $data, ' modal-xl');
	}
    public function deleteData()
    {
        $id = $_POST['id'];
        $data_status = $_POST['status'];
        $result = $this->Mod_reportwhpo->deleteKeluar($id,$data_status);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function deleteDatanon()
    {
        $id = $_POST['id'];
        $data_status = $_POST['status'];
        $result = $this->Mod_reportwhpo->deleteKeluarnon_pk($id,$data_status);

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