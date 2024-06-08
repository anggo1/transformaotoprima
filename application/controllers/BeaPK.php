<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BeaPk extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('body_repair_model/Mod_beapk', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Biaya Pekerjaan";
		$data['judul'] 		= "Bea PK";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'body_repair/bea_pk', $data);
    }


    public function showPk()
    {
        $id 				= $_GET['no_pk'];
        if(empty($id)){
            $data['dataPk'] = $this->Mod_beapk->select_pk_empty_id();
        }else{
        $data['dataPk'] = $this->Mod_beapk->select_pk($id);
        }
        $this->load->view('body_repair/bea_pk_data', $data);
    }
    public function tampilPk()
	{
        $id = $_GET['id_lapor'];
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);
		$this->load->view('body_repair/detail_tambah_pk', $data);
	}
    public function cetakPk()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_beapk->cetak_pk($id);
		$data['detailPk'] = $this->Mod_beapk->cetak_estimasi($id);

		echo show_my_print('body_repair/modals/modal_data_pk', 'cetak-pk', $data, ' modal-xl');
	}
    /*Update Bea PK*/
    public function update_bea_pk()
    {
        $this->form_validation->set_rules('no_body', 'No Body', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_beapk->insertBea($data);

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
    public function Pause()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);

        echo show_my_modal('body_repair/modals/modal_pk_pause', 'pause-pk', $data);
    }

}