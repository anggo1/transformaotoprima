<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chasis extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        $this->load->model(array('marketing_model/Mod_chasis', 'Mod_menu'));
	}

	public function index()
	{
		$data['page'] 		= "Daftar Chasis";
		$data['judul'] 		= "Chasis";
		$this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_chasis->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_chasis->select_by_level($idlevel, $id_sub);
        
		echo show_my_modal('marketing/modals/modal_tambah_chasis', 'tambah-chasis', $data, ' modal-lg');
        $this->template->load('layoutbackend','marketing/data_chasis', $data);
		
	}

	 public function ajax_list()
    {
		$link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_chasis->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_chasis->select_by_level($idlevel, $id_sub);

		 foreach ($viewLevel as $b) {
            $row1 = array();
            $row1[] = $b->id_submenu;
		
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_chasis->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $cs) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $cs->tgl_masuk;
            $row[] = $cs->retail;
            $row[] = $cs->type;
            $row[] = $cs->no_rangka;
            $row[] = $cs->no_mesin;
            $row[] = $cs->sales;
            $row[] = $cs->gesekan;
            $row[] = $cs->thn_produksi;
            $row[] = $cs->nama_customer;
            $row[] = $cs->pengiriman;
            if($b->edit_level=="Y" && $b->delete_level=="Y"){
                $row[]='
                <button class="btn btn-sm btn-outline-success update-chasis" title="Edit" data-id="'.$cs->id_chasis.'">
                <i class="fa fa-edit"></i></button><button class="btn btn-sm btn-outline-danger delete-chasis" title="Delete" data-toggle="modal" data-target="#hapusChasis" data-id="'.$cs->id_chasis.'"><i class="fa fa-trash"></i>
                </button>';
            }
            if($b->edit_level=="Y" && $b->delete_level=="N"){
                $row[]='
                <button class="btn btn-sm btn-outline-success update-chasis" title="Edit" data-id="'.$cs->id_chasis.'"><i class="fa fa-edit"></i>
                </button>';
            }else{
                $row[]='';
            }
            $data[] = $row;
        }
    }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_chasis->count_all(),
                        "recordsFiltered" => $this->Mod_chasis->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function prosesTchasis()
    {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_chasis->insertChasis($data);

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
    public function updateChasis() {
		$id 				= trim($_POST['id']);
		$data['dataChasis'] = $this->Mod_chasis->select_by_id_chasis($id);
        //echo json_encode($data);

		echo show_my_modal('marketing/modals/modal_tambah_chasis', 'update-chasis', $data, ' modal-lg');
	}

	public function prosesUchasis() {
		
		$this->form_validation->set_rules('type', 'Type', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_chasis->updateChasis($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_ok_msg('Data Berhasil diupdate', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Data Gagal diupdate', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

    public function deleteChasis()
    {
        $id = $_POST['id'];
        $result = $this->Mod_chasis->deleteChasis($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function cariKodeBody() {	
        $kode	= $_GET['a'];
            $cari	= $this->Mod_chasis->select_kodeBody($kode)->result();
            echo json_encode($cari);
           
        }

}