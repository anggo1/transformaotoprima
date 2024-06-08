<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PembayaranPk extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('body_repair_model/Mod_pembayaranpk', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Pembayaran Upah Borongan";
		$data['judul'] 		= "Upah Pekerjaan";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'body_repair/pembayaran_pk', $data);
    }

    public function prosesBayar()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_pembayaranpk->insertBayar($data);
		
		//echo json_encode($data);
	}
    
	public function tampilDetail()
	{
		$id 				= $_GET['id_pk'];
		$data['dataDetail'] = $this->Mod_pembayaranpk->select_detail($id);
		$data['dataSisa'] = $this->Mod_pembayaranpk->select_sisa($id);
		$this->load->view('body_repair/pembayaran_detail', $data);
	}
    public function showPk()
    {
        $id 				= $_GET['no_pk'];
        $data['dataPk'] = $this->Mod_pembayaranpk->select_pk($id);
        $this->load->view('body_repair/pembayaran_pk_data', $data);
    }
    public function showPk2()
    {
        //$id 				= $_GET['no_pk'];
        $data['dataPk2'] = $this->Mod_pembayaranpk->select_pk2();
        $this->load->view('body_repair/pembayaran_pk_data2', $data);
    }
    public function showSisa()
    {
        $id                 = $_POST['id_pk'];
        $data= $this->Mod_pembayaranpk->select_sisa($id);
        echo json_encode($data);
    }
    public function cetakPk()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_pembayaranpk->cetak_pk($id);

		echo show_my_print('body_repair/modals/modal_cetak_pk_tunggal', 'cetak-pk', $data, ' modal-xl');
	}
    public function updateTglBayar()
	{
        $id = $_POST['id'];
        $tgl_bayar = $_POST['tgl_bayar'];
		$data['dataPo'] = $this->Mod_pembayaranpk->update_tgl_pembayaran($id,$tgl_bayar);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
    public function updateKetBayar()
	{
        $id = $_POST['id'];
        $keterangan = $_POST['keterangan'];
		$data['dataPo'] = $this->Mod_pembayaranpk->update_ket_pembayaran($id,$keterangan);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
    public function updateBayar()
	{
        $id = $_POST['id'];
        $sisa = $_POST['sisa'];
        $jumlah = $_POST['jumlah'];
		$data['dataPo'] = $this->Mod_pembayaranpk->update_pembayaran($id,$sisa,$jumlah);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_pembayaranpk->deleteDetail_bayar($id);
		if ($result > 0) {
			//$out['datakode']=$kodeBaru;
			$out['status'] = '';
			$out['msg'] = show_del_msg('Deleted', '10px');
		} else {
			$out['status'] = '';
			$out['msg'] = show_err_msg('Filed !', '10px');
		}
		echo json_encode($out);
	}
    public function cetakDetail()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_prosespk->cetak_pk($id);
		$data['dataDetail'] = $this->Mod_prosespk->cetak_detail($id);

		echo show_my_print('body_repair/modals/modal_cetak_detail_pk', 'cetak-detail', $data, ' modal-xl');
	}
    

    public function Detail()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_prosespk->cetak_pk($id);

        echo show_my_modal('body_repair/modals/modal_pk_detail', 'detail-pk', $data, ' modal-md');
    }
    public function showDetail()
    {
        $id                 = $_GET['id_pk']   ;
        $data['dataDetail'] = $this->Mod_prosespk->select_detail($id);
        $this->load->view('body_repair/detail_pk', $data);
    }
    public function detailPk()
    {

        $this->form_validation->set_rules('id_pk', 'ID PK', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_prosespk->detailpk($data);

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
}