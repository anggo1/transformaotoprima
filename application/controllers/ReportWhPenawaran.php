<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportWhPenawaran extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_reportwhpenawaran', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Report Penawaran";
		$data['judul'] 		= "Penawaran";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'warehouse/report_wh/penawaran', $data);
    }

    public function listPo() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['dataPo'] = $this->Mod_reportwhpenawaran->cari_po($ttmp1,$ttmp2); 
        $this->load->view('warehouse/report_wh/data_penawaran', $data);
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
    
	public function cetak()
	{
		$id 				= $_POST['id'];
		$data['dataPo'] = $this->Mod_reportwhpenawaran->select_by_id($id);
		$data['detailPo'] = $this->Mod_reportwhpenawaran->select_detail($id);
		$data['detailKet'] = $this->Mod_reportwhpenawaran->select_ket($id);

		echo show_my_print('warehouse/modals/modal_cetak_estimasi_penawaran', 'cetak-po', $data, ' modal-xl');
	}
    public function cetak_int()
	{
		$id 				= $_POST['id'];
		$data['dataPo'] = $this->Mod_reportwhpenawaran->select_by_id($id);
		$data['detailPo'] = $this->Mod_reportwhpenawaran->select_detail($id);
		$data['detailKet'] = $this->Mod_reportwhpenawaran->select_ket($id);

		echo show_my_print('warehouse/modals/modal_cetak_estimasi_penawaran_internal', 'cetak-po-int', $data, ' modal-xl');
	}
	public function deletePo()
    {
        $id = $_POST['id'];
        $result = $this->Mod_reportwhpenawaran->deletePo($id);

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