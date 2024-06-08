<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimator extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        $this->load->model(array('body_repair_model/Mod_estimator'));
	}

	public function index()
	{
		$data['page'] 		= "Estimasi Perbaikan Bus";
		$data['judul'] 		= "Edit Estimator";
		//$this->load->helper('url');
        $id = $_GET['data-pk'];
		$data['dataLapor'] = $this->Mod_estimator->select_lapor($id);
        $data['dataPk'] = $this->Mod_estimator->select_pk();
        $this->template->load('layoutbackend','body_repair/edit_estimasi',$data);
	}


    //Estimator//
    public function prosesEstimasi()
    {
        $this->form_validation->set_rules('tgl_estimasi', 'Tanggal Estimasi', 'trim|required');

        $data=$this->input->post();
        if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_estimator->insertEstimasi($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_err_msg('Filed !', '20px');
            }
        } else {
            $out['status'] = 'form';
            $out['msg'] = show_err_msg(validation_errors());
        }

        echo json_encode($out);
    }
    public function updateEstimasi()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataEstimasi'] = $this->Mod_estimator->update_estimasi($id,$hrg_part,$jml_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
    public function tampilEstimasi()
	{
        $id = $_GET['id_lapor'];
		$data['dataEstimasi'] = $this->Mod_estimator->select_estimasi($id);
		$this->load->view('body_repair/detail_estimasi', $data);
	}
    public function deleteEstimasi()
    {
        $id = $_POST['id'];
        $result = $this->Mod_estimator->deleteEstimasi($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function cetakEstimasi()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_estimator->cetak_masuk($id);
		$data['detailPk'] = $this->Mod_estimator->cetak_estimasi($id);
		$data['hargaEstimasi'] = $this->Mod_estimator->harga_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_estimasi', 'cetak-estimasi', $data, ' modal-xl');
	}
    public function cetakEstimasi2()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_estimator->cetak_masuk($id);
		$data['detailPk'] = $this->Mod_estimator->cetak_estimasi($id);
		$data['hargaEstimasi'] = $this->Mod_estimator->harga_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_estimasi', 'cetak-estimasi2', $data, ' modal-xl');
	}
}