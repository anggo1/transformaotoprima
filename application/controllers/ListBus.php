<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListBus extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        $this->load->model(array('body_repair_model/Mod_body', 'Mod_menu'));
	}

	public function index()
	{
		$data['page'] 		= "Daftar Bus";
		$data['judul'] 		= "Data Bus";
		$this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_body->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_body->select_by_level($idlevel, $id_sub);
        
		echo show_my_modal('warehouse/modals/modal_tambah_body', 'tambah-body', $data, ' modal-lg');
        $this->template->load('layoutbackend','warehouse/data_body', $data);
		
	}

	 public function ajax_list()
    {
		$link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_body->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_body->select_by_level($idlevel, $id_sub);

		 foreach ($viewLevel as $b) {
            $row1 = array();
            $row1[] = $b->id_submenu;
		
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_body->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $bd) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $bd->no_body;
            $row[] = $bd->type;
            $row[] = $bd->thn_rangka;
            $row[] = $bd->thn_pembuatan;
            $row[] = $bd->karoseri;
            $row[] = $bd->warna;
            $row[] = $bd->kelas;
            $row[] = $bd->strip;
            $row[] = $bd->keterangan;
            if($b->edit_level=="Y" && $b->delete_level=="Y"){
                $row[]='
                <button class="btn btn-sm btn-outline-success update-body ion-compose ion-lg" title="Edit" data-id="'.$bd->no_body.'">
                </button>
                <button class="btn btn-sm btn-outline-danger delete-body ion-android-close ion-lg" title="Delete" data-toggle="modal" data-target="#hapusBody" data-id="'.$bd->no_body.'">
                </button>';
            }
            if($b->edit_level=="Y" && $b->delete_level=="N"){
                $row[]='
                <button class="btn btn-sm btn-outline-success update-body ion-compose ion-lg" title="Edit" data-id="'.$bd->no_body.'">
                </button>';
            }else{
                $row[]='';
            }
            $data[] = $row;
        }
    }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_body->count_all(),
                        "recordsFiltered" => $this->Mod_body->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function viewbody()
    {
        $id = $this->input->post('id_barang');
        $data['data_table'] = $this->Mod_body->view_body($id);

        $this->load->view('warehouse/view', $data);
    }

    public function prosesTbody()
    {
        $this->form_validation->set_rules('no_body', 'No Body', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_body->insertBody($data);

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
    public function updateBody() {
		$id 				= trim($_POST['id']);
		$data['dataBody'] = $this->Mod_body->select_by_id_body($id);

		echo show_my_modal('warehouse/modals/modal_tambah_body', 'update-body', $data, ' modal-lg');
	}

	public function prosesUbody() {
		
		$this->form_validation->set_rules('no_body', 'No Body', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_body->updateBody($data);

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

    public function deleteBody()
    {
        $id = $_POST['id'];
        $result = $this->Mod_body->deleteBody($id);

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
            $cari	= $this->Mod_body->select_kodeBody($kode)->result();
            echo json_encode($cari);
           
        }

}