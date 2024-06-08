<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mesin_absen extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_mesinabsen','Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
    }

    public function index()
    {
		$data['page'] 		= "Mesin Absen";
		$data['judul'] 		= "Mesin Absen";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();        
		echo show_my_modal('hrd/modals/modal_tambah_mesin', 'tambah-mesin', $data);
        $this->template->load('layoutbackend','hrd/mesin_data',$data);
    }

    
    public function showDev() {
		$data['dataDev'] = $this->Mod_mesinabsen->select_mesin();
		$this->load->view('hrd/dev_data', $data);
	}
	public function showAbsen() {
		$data['dataDev'] = $this->Mod_mesinabsen->select_mesin();
		$this->load->view('hrd/absen_data', $data);
	}
	
    /*Pendidikan*/
	public function prosesTmesin() {
		$this->form_validation->set_rules('ip', 'IP Address Mesin', 'trim|required');
		$this->form_validation->set_rules('nama_mesin', 'Nama Mesin', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_mesinabsen->insertMesin($data);

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
	public function prosesUnama() {
		
       // $this->load->helper('parse');
		$IP = trim($_POST['ip']);
        $Key = trim($_POST['pass']);
        if($IP!=""){
        $connect = fsockopen($IP, "80", $errno, $errstr, 1);
		if($connect) {
			$id = trim($_POST['nip']);
			$nama = trim($_POST['nama']);
			$soap_request = "<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><PIN>" . $id . "</PIN><Name>" . $nama . "</Name></Arg></SetUserInfo>";
			$newLine = "\r\n";
			fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
			fputs($connect, "Content-Type: text/xml" . $newLine);
			fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
			fputs($connect, $soap_request . $newLine);
			$buffer = "";
				$out['status'] = '';
				$out['msg'] = show_ok_msg('Success', '20px');

			while($Response = fgets($connect, 1024)) {
				$buffer = $buffer . $Response;
			}
		} else {
			$out['status'] = '';
			$out['msg'] = show_err_msg('Filed !', '20px');
		}
		//include("parse.php");
		$buffer = Parse_Data($buffer, "<Information>", "</Information>");
	}

		echo json_encode($buffer);
	}
	public function downloadMesin() {
		$IP 				= trim($_POST['ip']);
		$key 				= trim($_POST['pass']);
		$data['dataMesin'] = $this->Mod_mesinabsen->get_data_absen($IP,$key);
		
		foreach($ins as $result) {
			echo $result['PIN'], '<br>';
		}
		$this->load->view('hrd/absen_data', $ins);

		//echo show_my_modal('hrd/modals/modal_tambah_mesin', 'update-mesin', $data);
	}
	public function updateMesin() {
		$id 				= trim($_POST['id']);
		$data['dataMesin'] = $this->Mod_mesinabsen->select_id_mesin($id);

		echo show_my_modal('hrd/modals/modal_tambah_mesin', 'update-mesin', $data);
	}

	public function prosesUmesin() {
		
		$this->form_validation->set_rules('ip', 'IP Address Mesin', 'trim|required');
		$this->form_validation->set_rules('nama_mesin', 'Nama Mesin', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_mesinabsen->updateMesin($data);

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
	public function deleteMesin() {
		$id = $_POST['id'];
		$result = $this->Mod_mesinabsen->deleteDev($id);
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
}