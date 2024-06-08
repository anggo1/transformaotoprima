<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Account extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_account', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Data Akun";
		$data['judul'] 		= "List Accounting";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $data['dataKl'] = $this->Mod_account->select_kelompok();
        $data['dataType'] = $this->Mod_account->select_type();
        $data['dataJn'] = $this->Mod_account->select_jenis();
        echo show_my_modal('accounting/modals/modal_tambah_akun', 'tambah-akun', $data);
        $this->template->load('layoutbackend', 'accounting/akun', $data);
    }


    public function showAcc()
    {
        $data['dataPk'] = $this->Mod_account->select_acc();
        $this->load->view('accounting/data_akun', $data);
    }

    public function prosesTacc()
    {
        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_account->insertAkun($data);

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
    public function updateAkun()
    {
        $id                 = trim($_POST['id']);
        $data['dataKl'] = $this->Mod_account->select_kelompok();
        $data['dataType'] = $this->Mod_account->select_type();
        $data['dataJn'] = $this->Mod_account->select_jenis();
        $data['dataAkun'] = $this->Mod_account->select_id_akun($id);
        echo show_my_modal('accounting/modals/modal_tambah_akun', 'update-akun', $data);
    }

    public function prosesUakun()
    {

        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_account->updateAkun($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_succ_msg('Filed!', '20px');
            }
        } else {
            $out['status'] = 'form';
            $out['msg'] = show_err_msg(validation_errors());
        }

        echo json_encode($out);
    }
    public function deleteAkun()
    {
        $id = $_POST['id'];
        $result = $this->Mod_account->deleteAkun($id);

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