<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Saldo extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_saldo', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
        $this->load->library('ciqrcode');
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Data Saldo";
		$data['judul'] 		= "Saldo";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $data['dataAkun'] = $this->Mod_saldo->select_akun();
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_saldo->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_saldo->select_by_level($idlevel, $id_sub);
        
		echo show_my_modal('accounting/modals/modal_tambah_saldo', 'tambah-saldo', $data, ' modal-lg');
        $this->template->load('layoutbackend', 'accounting/saldo', $data);
    }

    public function prosesTsaldo()
    {
        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_saldo->insertSaldo($data);

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
    public function showSaldo()
    {
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $data['dataAkun'] = $this->Mod_saldo->select_akun();
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_saldo->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_saldo->select_by_level($idlevel, $id_sub);
        
		$id 				= trim($_GET['periode']);
        $data['dataSaldo'] = $this->Mod_saldo->select_saldo($id);
        $data['saldoGlobal'] = $this->Mod_saldo->select_saldo_global($id);
        $this->load->view('accounting/data_saldo', $data);
    }
    public function updateSaldo() {
		$id 				= trim($_POST['id']);
        $data['apl'] = $this->db->get("aplikasi")->row();
        $data['dataAkun'] = $this->Mod_saldo->select_akun();
		$data['dataSaldo'] = $this->Mod_saldo->select_by_id_saldo($id);

		echo show_my_modal('accounting/modals/modal_tambah_saldo', 'update-saldo', $data, ' modal-xl');
	}

	public function prosesUsaldo() {
		
		$this->form_validation->set_rules('kode_akun', 'Kode Akun', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_saldo->updateSaldo($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_ok_msg('Data Berhasil diupdate', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Data Batal diupdate', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

    public function deleteSaldo()
    {
        $id = $_POST['id'];
        $result = $this->Mod_saldo->deleteSaldo($id);

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