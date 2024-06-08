<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Jurnal extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_jurnal', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
        $this->load->library('ciqrcode');
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Jurnal Umum";
		$data['judul'] 		= "Jurnal";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $data['dataAkun'] = $this->Mod_jurnal->select_akun();
        $data['dataRef'] = $this->Mod_jurnal->select_ref();
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_jurnal->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_jurnal->select_by_level($idlevel, $id_sub);
        
        $this->template->load('layoutbackend', 'accounting/jurnal_umum', $data);
    }

    public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_jurnal->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_jurnal->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_jurnal->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->no_ref;
                $row[] = $p->no_jurnal;
                $row[] = $p->kode_akun;
                $row[] = $p->nama_akun;
                $row[] = tglIndoSedang($p->tgl_jurnal);
                $row[] = $p->keterangan;
                $row[] = number_format($p->debit);
                $row[] = number_format($p->kredit);
                $row[] = $pel1->delete_level;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_jurnal->count_all(),
            "recordsFiltered" => $this->Mod_jurnal->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function showJurnal()
    {
		$tgl_awal 				= trim($_POST['tgl_awal']);
		$tgl_akhir 				= trim($_POST['tgl_akhir']);
		$no_ref 				= trim($_POST['no_ref_cari']);
        $data['dataJurnal'] = $this->Mod_jurnal->select_jurnal($tgl_awal,$tgl_akhir,$no_ref);
        $this->load->view('accounting/data_jurnal_umum', $data);
    }
    public function cetakJurnalUmum()
    {
        
		$tgl_awal 				= trim($_POST['tgl_awal']);
		$tgl_akhir 				= trim($_POST['tgl_akhir']);
		$no_ref 				= trim($_POST['no_ref_cari']);
        $data['dataJurnal'] = $this->Mod_jurnal->select_jurnal($tgl_awal,$tgl_akhir,$no_ref);
            echo show_my_print('accounting/modals/modal_cetak_jurnal_umum', 'cetak-jurnal', $data, ' modal-xl');
        
    }
    public function tambahJurnal()
	{
        $data['dataAkun'] = $this->Mod_jurnal->select_akun();
        $data['dataRef'] = $this->Mod_jurnal->select_ref();
		echo show_my_modal('accounting/modals/modal_tambah_jurnal', 'tambah-jurnal', $data, ' modal-xl');
	}
    public function cetakJurnal()
	{
        $no_ref 				= $_POST['no_ref'];
		$data['dataJurnal'] = $this->Mod_jurnal->cetak_jurnal($no_ref);
		echo show_my_print('accounting/modals/modal_cetak_jurnal', 'cetak-jurnal-detail', $data, ' modal-xl');
	}

    public function prosesTjurnal()
    {
        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_jurnal->insertJurnal($data);

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
    public function showDetail()
    {
        $data['dataDetail'] = $this->Mod_jurnal->select_detail();
        $this->load->view('accounting/detail_jurnal', $data);
    }
    public function showDetailEdit()
    {
        $no_ref 				= $_GET['no_ref'];
        $data['dataDetail'] = $this->Mod_jurnal->select_detail_edit($no_ref);
        $this->load->view('accounting/detail_jurnal', $data);
    }
    public function cariRef() {	
        $kode	= $_GET['a'];
            $cari	= $this->Mod_jurnal->select_kodeRef($kode)->result();
            echo json_encode($cari);
           
        }
    public function Generate()
    {
        $kode_awal 				= $_GET['kode_awal'];
        $no_ref 				= $_GET['no_ref'];
            $result = $this->Mod_jurnal->generate_jurnal($kode_awal,$no_ref);

            if ($result > 0) {
                $this->uri->segment(1);
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Data Jurnal Telah di Tambahkan', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_err_msg('Filed !', '20px');
            }

        echo json_encode($out);
    }
    public function updateJurnal() {
		$id 				= trim($_POST['id']);
        $data['apl'] = $this->db->get("aplikasi")->row();
        $data['dataAkun'] = $this->Mod_jurnal->select_akun();
		$data['dataJurnal'] = $this->Mod_jurnal->select_by_id_jurnal($id);

		echo show_my_modal('accounting/modals/modal_tambah_jurnal', 'update-jurnal', $data, ' modal-xl');
	}

	public function prosesUjurnal() {
		
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_jurnal->updateJurnal($data);

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

    public function deleteJurnal()
    {
        $no_ref = $_POST['no_ref'];
        $result = $this->Mod_jurnal->deleteJurnal($no_ref);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function deleteJurnal_detail()
    {
        $id = $_POST['id'];
        $result = $this->Mod_jurnal->deleteJurnal_detail($id);

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