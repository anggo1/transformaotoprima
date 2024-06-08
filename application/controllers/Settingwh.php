<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Settingwh extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_settingwh', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
    }

    public function index()
    {
		$data['page'] 		= "Panel Setting Gudang";
		$data['judul'] 		= "Panel Setting";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        echo show_my_modal('warehouse/modals/modal_tambah_sat', 'tambah-satuan', $data);
        echo show_my_modal('warehouse/modals/modal_tambah_kat', 'tambah-kategori', $data);
        echo show_my_modal('warehouse/modals/modal_tambah_type', 'tambah-type', $data);
        echo show_my_modal('warehouse/modals/modal_tambah_sup', 'tambah-supplier', $data);
        echo show_my_modal('warehouse/modals/modal_tambah_kp', 'tambah-kelompok', $data);
        $this->template->load('layoutbackend', 'warehouse/setting_panel', $data);
    }


    public function showSat()
    {
        $data['dataSat'] = $this->Mod_settingwh->select_satuan();
        $this->load->view('warehouse/sat_data', $data);
    }
    public function showKat()
    {
        $data['dataKat'] = $this->Mod_settingwh->select_kategori();
        $this->load->view('warehouse/kat_data', $data);
    }
    public function showType()
    {
        $data['dataType'] = $this->Mod_settingwh->select_type();
        $this->load->view('warehouse/type_data', $data);
    }
    public function showSup()
    {
        $data['dataSup'] = $this->Mod_settingwh->select_supplier();
        $this->load->view('warehouse/sup_data', $data);
    }
    public function showKp()
    {
        $data['dataKel'] = $this->Mod_settingwh->select_kelompok();
        $this->load->view('warehouse/kp_data', $data);
    }
    /*Satuan*/
    public function prosesTsatuan()
    {
        $this->form_validation->set_rules('satuan', 'Nama Satuan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->insertSatuan($data);

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
    public function updateSatuan()
    {
        $id                 = trim($_POST['id']);
        $data['dataSatuan'] = $this->Mod_settingwh->select_id_satuan($id);

        echo show_my_modal('warehouse/modals/modal_tambah_sat', 'update-satuan', $data);
    }

    public function prosesUsatuan()
    {

        $this->form_validation->set_rules('satuan', 'Nama Satuan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->updateSatuan($data);

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
    public function deleteSatuan()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingwh->deleteSat($id);

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

    /*endSatuan*/
    /*Kategori*/
    public function prosesTkategori()
    {
        $this->form_validation->set_rules('kategori', 'Nama Satuan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->insertKategori($data);

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
    public function updateKategori()
    {
        $id                 = trim($_POST['id']);
        $data['dataKategori'] = $this->Mod_settingwh->select_id_kategori($id);

        echo show_my_modal('warehouse/modals/modal_tambah_kat', 'update-kategori', $data);
    }

    public function prosesUkategori()
    {

        $this->form_validation->set_rules('kategori', 'Nama kategori', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->updateKategori($data);

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
    public function deleteKategori()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingwh->deleteKat($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }

    /*endKategori*
     /*Type*/
    public function prosesTtype()
    {
        $this->form_validation->set_rules('type', 'Nama Type', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->insertType($data);

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
        $data['dataType'] = $this->Mod_settingwh->select_id_type($id);

        echo show_my_modal('warehouse/modals/modal_tambah_type', 'update-type', $data);
    }

    public function prosesUtype()
    {

        $this->form_validation->set_rules('type', 'Nama Type', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->updateType($data);

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
        $result = $this->Mod_settingwh->deleteTy($id);

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
    /*endType*/
    /*Supplier*/
    public function prosesTsupplier()
    {
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->insertSupplier($data);

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
    public function updateSupplier()
    {
        $id                 = trim($_POST['id']);
        $data['dataSup'] = $this->Mod_settingwh->select_id_supplier($id);

        echo show_my_modal('warehouse/modals/modal_tambah_sup', 'update-supplier', $data);
    }

    public function prosesUsupplier()
    {

        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->updateSupplier($data);

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
    public function deleteSupplier()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingwh->deleteSup($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    /*endSupplier*/
    /*Kelompok*/
    public function prosesTkelompok()
    {
        $this->form_validation->set_rules('kelompok', 'Nama Kelompok', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->insertKelompok($data);

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
        $data['dataKelompok'] = $this->Mod_settingwh->select_id_kelompok($id);

        echo show_my_modal('warehouse/modals/modal_tambah_kp', 'update-kelompok', $data);
    }

    public function prosesUkelompok()
    {

        $this->form_validation->set_rules('kelompok', 'Nama Kelompok', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingwh->updateKelompok($data);

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
        $result = $this->Mod_settingwh->deleteKel($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    /*endKelompok*/
}
