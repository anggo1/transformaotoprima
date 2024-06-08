<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Settingbr extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('body_repair_model/Mod_settingbr', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
    }

    public function index()
    {
		$data['page'] 		= "Panel Setting Body Repair";
		$data['judul'] 		= "Panel Setting";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        echo show_my_modal('body_repair/modals/modal_tambah_lap', 'tambah-laporan', $data);
        echo show_my_modal('body_repair/modals/modal_tambah_kat', 'tambah-kategori', $data);
        echo show_my_modal('body_repair/modals/modal_tambah_pk_panel', 'tambah-pk', $data);
        echo show_my_modal('body_repair/modals/modal_tambah_kelas', 'tambah-kelas', $data);
        echo show_my_modal('body_repair/modals/modal_tambah_pool', 'tambah-pool', $data);
        $this->template->load('layoutbackend', 'body_repair/setting_panel', $data);
    }


    public function showLap()
    {
        $data['dataLap'] = $this->Mod_settingbr->select_laporan();
        $this->load->view('body_repair/lap_data', $data);
    }
    public function showKat()
    {
        $data['dataKat'] = $this->Mod_settingbr->select_kategori();
        $this->load->view('body_repair/kat_data', $data);
    }
    public function showPk()
    {
        $data['dataPk'] = $this->Mod_settingbr->select_pk();
        $this->load->view('body_repair/pk_data', $data);
    }
    public function showKl()
    {
        $data['dataKl'] = $this->Mod_settingbr->select_kelas();
        $this->load->view('body_repair/kelas_data', $data);
    }
    public function showPl()
    {
        $data['dataPl'] = $this->Mod_settingbr->select_pool();
        $this->load->view('body_repair/pool_data', $data);
    }
    /*Keterangan Laporan*/
    public function prosesTlaporan()
    {
        $this->form_validation->set_rules('kode', 'Kode Laporan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingbr->insertLapor($data);

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
    public function updateLaporan()
    {
        $id                 = trim($_POST['id']);
        $data['dataLapor'] = $this->Mod_settingbr->select_id_lapor($id);

        echo show_my_modal('body_repair/modals/modal_tambah_lap', 'update-laporan', $data);
    }

    public function prosesUlaporan()
    {

        $this->form_validation->set_rules('kode', 'Kode Laporan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingbr->updateLapor($data);

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
    public function deleteLaporan()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingbr->deleteLap($id);

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

    /*endKeteranganLaporan*/
    /*Kategori*/
    public function prosesTkategori()
    {
        $this->form_validation->set_rules('kategori', 'Nama Satuan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingbr->insertKategori($data);

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
        $data['dataKategori'] = $this->Mod_settingbr->select_id_kategori($id);

        echo show_my_modal('body_repair/modals/modal_tambah_kat', 'update-kategori', $data);
    }

    public function prosesUkategori()
    {

        $this->form_validation->set_rules('kategori', 'Nama kategori', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingbr->updateKategori($data);

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
        $result = $this->Mod_settingbr->deleteKat($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }

    /*endKategori*/
        /*KodePK*/
        public function prosesTpk()
        {
            $this->form_validation->set_rules('kode', 'Nama Pekerjaan', 'trim|required');
    
            $data     = $this->input->post();
            if ($this->form_validation->run() == TRUE) {
                $result = $this->Mod_settingbr->insertPk($data);
    
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
        public function updatePk()
        {
            $id                 = trim($_POST['id']);
            $data['dataPk'] = $this->Mod_settingbr->select_id_pk($id);
    
            echo show_my_modal('body_repair/modals/modal_tambah_pk_panel', 'update-pk', $data);
        }
    
        public function prosesUpk()
        {
    
            $this->form_validation->set_rules('kode', 'Nama Pekerjaan', 'trim|required');
    
            $data     = $this->input->post();
            if ($this->form_validation->run() == TRUE) {
                $result = $this->Mod_settingbr->updatePk($data);
    
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
        public function deletePk()
        {
            $id = $_POST['id'];
            $result = $this->Mod_settingbr->deletePk($id);
    
            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_del_msg('Deleted', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_err_msg('Filed !', '20px');
            }
            echo json_encode($out);
        }
    
        /*endPk*/

         /*Kelas*/
    public function prosesTkelas()
    {
        $this->form_validation->set_rules('kelas', 'Nama Satuan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingbr->insertKelas($data);

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
    public function updateKelas()
    {
        $id                 = trim($_POST['id']);
        $data['dataKelas'] = $this->Mod_settingbr->select_id_kelas($id);

        echo show_my_modal('body_repair/modals/modal_tambah_kelas', 'update-kelas', $data);
    }

    public function prosesUkelas()
    {

        $this->form_validation->set_rules('kelas', 'Nama kelas', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingbr->updateKelas($data);

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
    public function deleteKelas()
    {
        $id = $_POST['id'];
        $result = $this->Mod_settingbr->deleteKel($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }

    /*endKelas*/
             /*Pool*/
             public function prosesTpool()
             {
                 $this->form_validation->set_rules('nama_pool', 'Nama Pool', 'trim|required');
         
                 $data     = $this->input->post();
                 if ($this->form_validation->run() == TRUE) {
                     $result = $this->Mod_settingbr->insertPool($data);
         
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
             public function updatePool()
             {
                 $id                 = trim($_POST['id']);
                 $data['dataPool'] = $this->Mod_settingbr->select_id_pool($id);
         
                 echo show_my_modal('body_repair/modals/modal_tambah_pool', 'update-pool', $data);
             }
         
             public function prosesUPool()
             {
         
                 $this->form_validation->set_rules('nama_pool', 'Nama Pool', 'trim|required');
         
                 $data     = $this->input->post();
                 if ($this->form_validation->run() == TRUE) {
                     $result = $this->Mod_settingbr->updatePool($data);
         
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
             public function deletePool()
             {
                 $id = $_POST['id'];
                 $result = $this->Mod_settingbr->deletePool($id);
         
                 if ($result > 0) {
                     $out['status'] = '';
                     $out['msg'] = show_del_msg('Deleted', '20px');
                 } else {
                     $out['status'] = '';
                     $out['msg'] = show_err_msg('Filed !', '20px');
                 }
                 echo json_encode($out);
             }
         
             /*endKelas*/
}