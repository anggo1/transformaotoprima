<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BukuBesar extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_bukubesar', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Buku Besar";
		$data['judul'] 		= "General Ledger";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $data['dataAkun'] = $this->Mod_bukubesar->select_akun();
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_bukubesar->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_bukubesar->select_by_level($idlevel, $id_sub);
        
		//echo show_my_modal('accounting/modals/modal_tambah_saldo', 'tambah-saldo', $data, ' modal-lg');
        $this->template->load('layoutbackend', 'accounting/report/buku_besar', $data);
    }

    public function showBuku()
    {
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $data['dataAkun'] = $this->Mod_bukubesar->select_akun();
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_bukubesar->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_bukubesar->select_by_level($idlevel, $id_sub);
        
		$kode_akun 				= trim($_GET['kode_akun']);
		$tgl_awal 				= trim($_GET['tgl_awal']);
		$tgl_akhir 				= trim($_GET['tgl_akhir']);
        if (!empty($kode_akun)){
        $data['dataBuku'] = $this->Mod_bukubesar->select_buku($kode_akun,$tgl_awal,$tgl_akhir);
        $this->load->view('accounting/report/data_buku_besar', $data);
        }else{
            $data['dataBuku'] = $this->Mod_bukubesar->select_buku_tanggal($tgl_awal,$tgl_akhir);
            $this->load->view('accounting/report/data_buku_besar_tanggal', $data);
        }
    }
    public function cetakBuku()
    {
		$tgl_awal 				= trim($_GET['date1']);
		$tgl_akhir 				= trim($_GET['date2']);
            $data['dataBuku'] = $this->Mod_bukubesar->select_buku_tanggal($tgl_awal,$tgl_akhir);
            echo show_my_print('accounting/report/modals/modal_cetak_buku_besar_tanggal', 'cetak-buku', $data, ' modal-xl');
    }
    public function cetakBukuAkun()
    {
		$kode_akun 				= trim($_GET['kode_akun']);
		$tgl_awal 				= trim($_GET['tgl_awal']);
		$tgl_akhir 				= trim($_GET['tgl_akhir']);
        $data['dataBuku'] = $this->Mod_bukubesar->select_buku($kode_akun,$tgl_awal,$tgl_akhir);
            echo show_my_print('accounting/report/modals/modal_cetak_buku_besar_akun', 'cetak-buku-akun', $data, ' modal-xl');
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