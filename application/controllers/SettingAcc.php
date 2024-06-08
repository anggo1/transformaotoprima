<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SettingAcc extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_settingacc', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
    }

    public function index()
    {
		$data['page'] 		= "Panel Setting Accounting";
		$data['judul'] 		= "Panel Setting";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        echo show_my_modal('accounting/modals/modal_tambah_kel', 'tambah-kelompok', $data);
        echo show_my_modal('accounting/modals/modal_tambah_type', 'tambah-type', $data);
        echo show_my_modal('accounting/modals/modal_tambah_jenis', 'tambah-jenis', $data);
        echo show_my_modal('accounting/modals/modal_tambah_ref', 'tambah-ref', $data);
        $this->template->load('layoutbackend', 'accounting/setting_panel', $data);
    }


    public function showKel()
    {
        $data['dataKelompok'] = $this->Mod_settingacc->select_kelompok();
        $this->load->view('accounting/kel_data', $data);
    }
    public function showType()
    {
        $data['dataType'] = $this->Mod_settingacc->select_type();
        $this->load->view('accounting/type_data', $data);
    }
    public function showJenis()
    {
        $data['dataJenis'] = $this->Mod_settingacc->select_jenis();
        $this->load->view('accounting/jenis_data', $data);
    }
    public function showRef()
    {
        $data['dataRef'] = $this->Mod_settingacc->select_ref();
        $this->load->view('accounting/ref_data', $data);
    }
    public function showSup()
    {
        $data['dataSup'] = $this->Mod_settingacc->select_supplier();
        $this->load->view('accounting/sup_data', $data);
    }
     /*Supplier*/
     public function updateSupplier()
     {
         $id                 = trim($_POST['id']);
         $data['dataSup'] = $this->Mod_settingacc->select_id_supplier($id);
 
         echo show_my_modal('accounting/modals/modal_tambah_sup', 'update-supplier', $data);
     }
 
     public function prosesUsupplier()
     {
 
 
         $data     = $this->input->post();
             $result = $this->Mod_settingacc->updateSupplier($data);
 
             if ($result > 0) {
                 $out['status'] = '';
                 $out['msg'] = show_ok_msg('Success', '20px');
             } else {
                 $out['status'] = '';
                 $out['msg'] = show_succ_msg('Filed!', '20px');
             }
 
         echo json_encode($out);
     }
     /*endSupplier*/
    /*Keterangan Kelompok*/
    public function prosesTkelompok()
    {
        $this->form_validation->set_rules('kelompok', 'Kelompok', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingacc->insertKelompok($data);

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
    public function updateKelompok()
    {
        $id                 = trim($_POST['id']);
        $data['dataKelompok'] = $this->Mod_settingacc->select_id_kelompok($id);

        echo show_my_modal('accounting/modals/modal_tambah_kel', 'update-kelompok', $data);
    }

    public function prosesUkelompok()
    {

        $this->form_validation->set_rules('kelompok', 'NAma Kelompok', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingacc->updateKelompok($data);

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
    public function deleteKelompok()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingacc->deleteKelompok($id);

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

    /*End Kelompok*/
    public function prosesTtype()
    {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingacc->insertType($data);

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
    public function updateType()
    {
        $id                 = trim($_POST['id']);
        $data['dataType'] = $this->Mod_settingacc->select_id_type($id);

        echo show_my_modal('accounting/modals/modal_tambah_type', 'update-type', $data);
    }

    public function prosesUtype()
    {

        $this->form_validation->set_rules('type', 'Type', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingacc->updateType($data);

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
    public function deleteType()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingacc->deleteType($id);

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

    /*End Type*/
    public function prosesTjenis()
    {
        $this->form_validation->set_rules('jenis_beban', 'Jenis Beban', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingacc->insertJenis($data);

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
    public function updateJenis()
    {
        $id                 = trim($_POST['id']);
        $data['dataJenis'] = $this->Mod_settingacc->select_id_jenis($id);

        echo show_my_modal('accounting/modals/modal_tambah_jenis', 'update-jenis', $data);
    }

    public function prosesUjenis()
    {

        $this->form_validation->set_rules('jenis_beban', 'Jenis Beban', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingacc->updateJenis($data);

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
    public function deleteJenis()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingacc->deleteJenis($id);

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

    /*End Jenis*/
    public function prosesTref()
    {
        $this->form_validation->set_rules('no_ref', 'No Referensi', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingacc->insertRef($data);

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
    public function updateRef()
    {
        $id                 = trim($_POST['id']);
        $data['dataRef'] = $this->Mod_settingacc->select_id_ref($id);

        echo show_my_modal('accounting/modals/modal_tambah_ref', 'update-ref', $data);
    }

    public function prosesUref()
    {

        $this->form_validation->set_rules('no_ref', 'Nomor Referensi', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingacc->updateRef($data);

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
    public function deleteRef()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingacc->deleteRef($id);

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

    /*End Referensi*/
    /*endKelas*/
}