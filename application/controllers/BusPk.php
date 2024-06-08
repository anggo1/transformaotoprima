<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BusPk extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('body_repair_model/Mod_buspk', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Bus Dengan PK";
		$data['judul'] 		= "Detail PK";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'body_repair/bus_pk', $data);
    }


    public function showPk()
    {
        $data['dataPk'] = $this->Mod_buspk->select_pk();
        $this->load->view('body_repair/bus_dengan_pk', $data);
    }
    public function cetakPk()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);
		$data['detailPk'] = $this->Mod_buspk->cetak_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_pk', 'cetak-pk', $data, ' modal-xl');
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
    public function Pause()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);

        echo show_my_modal('body_repair/modals/modal_pk_pause', 'pause-pk', $data);
    }

    public function pausePk()
    {

        $this->form_validation->set_rules('id_lapor', 'ID SPK', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_buspk->pausepk($data);

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
    public function startPk()
    {
        $id = $_POST['id'];
        $result = $this->Mod_buspk->startPk($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('PK Telah Dimulai', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function tutupPk()
    {
        $id = $_POST['id'];
        $no_body = $_POST['body'];
        $result = $this->Mod_buspk->pkSelesai($id,$no_body);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('PK Telah Selesai', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function inputBay()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);
        $data['dataBay'] = $this->Mod_buspk->select_bay();
        $data['antriCat'] = $this->Mod_buspk->select_antri_cat();
        $data['antriTriming'] = $this->Mod_buspk->select_antri_triming();
        $data['antriElektrik'] = $this->Mod_buspk->select_antri_elektrik();
        $data['antriQc'] = $this->Mod_buspk->select_antri_qc();
        $data['antriJok'] = $this->Mod_buspk->select_antri_jok();
        $data['antriPh'] = $this->Mod_buspk->select_antri_ph();
        echo show_my_modal('body_repair/modals/modal_masuk_bay', 'input-bay',$data,' modal-xl');
    }
    public function masukBay()
    {

        $id_lapor = $_POST['idLapor'];
        $id_bay = $_POST['idBay'];
        $no_body = $_POST['idBody'];
        $result = $this->Mod_buspk->masukBay($id_lapor,$id_bay,$no_body);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('Sukses', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function masukCat()
    {

        $id_lapor = $_POST['idLapor'];
        $id_bay = $_POST['idBay'];
        $no_body = $_POST['idBody'];
        $result = $this->Mod_buspk->masukCat($id_lapor,$id_bay,$no_body);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('Sukses', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function masukTriming()
    {

        $id_lapor = $_POST['idLapor'];
        $id_bay = $_POST['idBay'];
        $no_body = $_POST['idBody'];
        $result = $this->Mod_buspk->masukTriming($id_lapor,$id_bay,$no_body);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('Sukses', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function masukElektrik()
    {
        $id_lapor = $_POST['idLapor'];
        $id_bay = $_POST['idBay'];
        $no_body = $_POST['idBody'];
        $result = $this->Mod_buspk->masukElektrik($id_lapor,$id_bay,$no_body);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('Sukses', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function masukQc()
    {

        $id_lapor = $_POST['idLapor'];
        $id_bay = $_POST['idBay'];
        $no_body = $_POST['idBody'];
        $result = $this->Mod_buspk->masukQc($id_lapor,$id_bay,$no_body);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('Sukses', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function masukJok()
    {

        $id_lapor = $_POST['idLapor'];
        $id_bay = $_POST['idBay'];
        $no_body = $_POST['idBody'];
        $result = $this->Mod_buspk->masukJok($id_lapor,$id_bay,$no_body);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('Sukses', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function masukPh()
    {

        $id_lapor = $_POST['idLapor'];
        $id_bay = $_POST['idBay'];
        $no_body = $_POST['idBody'];
        $result = $this->Mod_buspk->masukPh($id_lapor,$id_bay,$no_body);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('Sukses', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function revPk()
    {
        
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);
		$data['detailPk'] = $this->Mod_buspk->cetak_estimasi($id);
        $data['jenisPk'] = $this->Mod_buspk->select_jenis_pk();

		echo show_my_print('body_repair/modals/modal_tambah_pk', 'tambah-pk', $data, ' modal-xl');

    }
    public function inputPk()
    {
        $this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'trim|required');

        $data=$this->input->post();
        $id_lapor 				= $_POST['id_lapor'];
        if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_busmasuk->insertPk($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['datakode']=$id_lapor;
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
    public function prosesEstimasi()//tambah PK
    {
        $this->form_validation->set_rules('tgl_estimasi', 'Tanggal Estimasi', 'trim|required');

        $data=$this->input->post();
        if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_buspk->insertPkBaru($data);

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
    public function tampilPk()
	{
        $id = $_GET['id_lapor'];
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);
		$this->load->view('body_repair/detail_tambah_pk', $data);
	}
}