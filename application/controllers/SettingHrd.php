<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SettingHrd extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_settinghrd','Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
    }

    public function index()
    {
		$data['page'] 		= "Panel Setting HRD";
		$data['judul'] 		= "Panel Setting";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();        
		echo show_my_modal('hrd/modals/modal_tambah_pend', 'tambah-pendidikan', $data);
		echo show_my_modal('hrd/modals/modal_tambah_jab', 'tambah-jabatan', $data);
		echo show_my_modal('hrd/modals/modal_tambah_dept', 'tambah-departement', $data);
		echo show_my_modal('hrd/modals/modal_tambah_kdcuti', 'tambah-kdcuti', $data);
        $this->template->load('layoutbackend','hrd/setting_panel',$data);
    }

    
    public function showPend() {
		$data['dataPen'] = $this->Mod_settinghrd->select_pendidikan();
		$this->load->view('hrd/pend_data', $data);
	}
    public function showDep() {
		$data['dataDep'] = $this->Mod_settinghrd->select_departement();
		$this->load->view('hrd/dep_data', $data);
	}
    public function showJab() {
		$data['datajab'] = $this->Mod_settinghrd->select_jabatan();
		$this->load->view('hrd/jab_data', $data);
	}
	public function showCt() {
		$data['dataKdcuti'] = $this->Mod_settinghrd->select_kdcuti();
		$this->load->view('hrd/kdcuti_data', $data);
	}
    /*Pendidikan*/
	public function prosesTpendidikan() {
		$this->form_validation->set_rules('pendidikan', 'Nama Pendidikan', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_settinghrd->insertPendidikan($data);

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
	public function updatePendidikan() {
		$id 				= trim($_POST['id']);
		$data['dataPendidikan'] = $this->Mod_settinghrd->select_id_pendidikan($id);

		echo show_my_modal('hrd/modals/modal_tambah_pend', 'update-pendidikan', $data);
	}

	public function prosesUpendidikan() {
		
		$this->form_validation->set_rules('pendidikan', 'Nama Pendidikan', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_settinghrd->updatePendidikan($data);

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
	public function deletePendidikan() {
		$id = $_POST['id'];
		$result = $this->Mod_settinghrd->deletePend($id);
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

	/*end Pendidikan*/
    /*Jabatan*/
	public function prosesTjabatan() {
		$this->form_validation->set_rules('jabatan', 'Nama Jabatan', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_settinghrd->insertJabatan($data);

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
	public function updateJabatan() {
		$id 				= trim($_POST['id']);
		$data['dataJabatan'] = $this->Mod_settinghrd->select_id_jabatan($id);

		echo show_my_modal('hrd/modals/modal_tambah_jab', 'update-jabatan', $data);
	}

	public function prosesUjabatan() {
		
		$this->form_validation->set_rules('jabatan', 'Nama Jabatan', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_settinghrd->updateJabatan($data);

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
	public function deleteJab() {
		$id = $_POST['id'];
		$result = $this->M_masterdata->deleteJab($id);
		
		if ($result > 0) {
			echo show_succ_msg('Deleted', '20px');
		} else {
			echo show_err_msg('Failed!', '20px');
		}
	}
    public function deleteJabatan() {
		$id = $_POST['id'];
		$result = $this->Mod_settinghrd->deleteJab($id);
		if ($result > 0) {
            $out['status'] = '';
			$out['msg'] = show_del_msg('Deleted', '20px');
			} else {
			$out['status'] = '';
			$out['msg'] = show_err_msg('Filed !', '20px');
			}
		echo json_encode($out);
	}
	/*endJabatan*/
     /*Department*/
	public function prosesTdepartement() {
		$this->form_validation->set_rules('departement', 'Nama departement', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_settinghrd->insertDepartement($data);

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
	public function updateDepartement() {
		$id 				= trim($_POST['id']);
		$data['dataDepartement'] = $this->Mod_settinghrd->select_id_departement($id);

		echo show_my_modal('hrd/modals/modal_tambah_dept', 'update-departement', $data);
	}

	public function prosesUdepartement() {
		
		$this->form_validation->set_rules('departement', 'Nama Departement', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_settinghrd->updateDepartement($data);

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
	public function deleteDep() {
		$id = $_POST['id'];
		$result = $this->M_masterdata->deleteJab($id);
		
		if ($result > 0) {
			echo show_succ_msg('Deleted', '20px');
		} else {
			echo show_err_msg('Failed!', '20px');
		}
	}
    public function deleteDepartement() {
		$id = $_POST['id'];
		$result = $this->Mod_settinghrd->deleteDep($id);
		if ($result > 0) {
            $out['status'] = '';
			$out['msg'] = show_del_msg('Deleted', '20px');
			} else {
			$out['status'] = '';
			$out['msg'] = show_err_msg('Filed !', '20px');
			}
		echo json_encode($out);
	}
	/*endDepartement*/
	     /*KodeCuti*/
		 public function prosesTkdcuti() {
			$this->form_validation->set_rules('kode', 'Kode cuti', 'trim|required');
	
			$data 	= $this->input->post();
			if ($this->form_validation->run() == TRUE) {
				$result = $this->Mod_settinghrd->insertkdcuti($data);
	
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
		public function updateKdcuti() {
			$id 				= trim($_POST['id']);
			$data['dataKdcuti'] = $this->Mod_settinghrd->select_id_kdcuti($id);
	
			echo show_my_modal('hrd/modals/modal_tambah_kdcuti', 'update-kdcuti', $data);
		}
	
		public function prosesUkdcuti() {
			
			$this->form_validation->set_rules('kode', 'Kode Cuti', 'trim|required');
	
			$data 	= $this->input->post();
			if ($this->form_validation->run() == TRUE) {
				$result = $this->Mod_settinghrd->updateKodecuti($data);
	
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
		public function deleteKdcuti() {
			$id = $_POST['id'];
			$result = $this->Mod_settinghrd->deleteKdcuti($id);			
			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_del_msg('Deleted', '20px');
				} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Filed !', '20px');
				}
			echo json_encode($out);
		}
		
		/*endCuti*/
   

public function download()
{
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Nama Submenu');
    $sheet->setCellValue('C1', 'Link');
    $sheet->setCellValue('D1', 'Icon');
    $sheet->setCellValue('E1', 'Menu');
    $sheet->setCellValue('F1', 'Is Active');

    $menu = $this->Mod_submenu->getAll()->result();
    $no = 1;
    $x = 2;
    foreach($menu as $row)
    {
        $sheet->setCellValue('A'.$x, $no++);
        $sheet->setCellValue('B'.$x, $row->nama_submenu);
        $sheet->setCellValue('C'.$x, $row->link);
        $sheet->setCellValue('D'.$x, $row->icon);
        $sheet->setCellValue('E'.$x, $row->nama_menu);
        $sheet->setCellValue('F'.$x, $row->is_active);
        $x++;
    }
    $writer = new Xlsx($spreadsheet);
    $filename = 'laporan-Submenu';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
}


private function _validate()
{
    $data = array();
    $data['error_string'] = array();
    $data['inputerror'] = array();
    $data['status'] = TRUE;

    if($this->input->post('nama_submenu') == '')
    {
        $data['inputerror'][] = 'nama_submenu';
        $data['error_string'][] = 'Submenu is required';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($this->input->post('link') == '')
    {
        $data['inputerror'][] = 'link';
        $data['error_string'][] = 'Link is required';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($this->input->post('icon') == '')
    {
        $data['inputerror'][] = 'icon';
        $data['error_string'][] = 'Icon is required';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($this->input->post('is_active') == '')
    {
        $data['inputerror'][] = 'is_active';
        $data['error_string'][] = 'Please select Is Active';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($this->input->post('id_menu') == '')
    {
        $data['inputerror'][] = 'id_menu';
        $data['error_string'][] = 'Please select Menu';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($data['status'] === FALSE)
    {
        echo json_encode($data);
        exit();
    }
}
}