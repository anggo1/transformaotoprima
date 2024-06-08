<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Body extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
        $this->load->model(array('body_repair_model/Mod_body', 'Mod_menu'));
	}

	public function index()
	{
		$data['page'] 		= "Daftar Bus / Kendaraan";
		$data['judul'] 		= "Daftar Bus";
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
        $data['dataKl'] = $this->Mod_body->select_kelas();
        $data['dataPool'] = $this->Mod_body->select_pool();
        
		echo show_my_modal('body_repair/modals/modal_tambah_body', 'tambah-body', $data, ' modal-xl');
        $this->template->load('layoutbackend','body_repair/data_body', $data);
		
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
            if($bd->status == 'AKTIF'){
                $status_body='<button class="tombol-success Blink-warning btn-lg pull-right">A</button>';
            }else{
                $status_body='<button class="tombol-warning Blink-warning btn-lg pull-right">P</button>';
            }
            $row = array();
            $row[] = $no;
            $row[] = $bd->no_body.'&nbsp'.$status_body;
            $row[] = $bd->no_pol;
            $row[] = $bd->type;
            $row[] = $bd->merk;
            $row[] = $bd->nama_pool;
            $row[] = $bd->rute_aktif;
            $row[] = $bd->karoseri;
            $row[] = $bd->warna;
            $row[] = $bd->kelas;
            $row[] = $bd->strip;
            if($b->edit_level=="Y" && $b->delete_level=="Y"){
                $row[]='
                <button class="btn btn-sm btn-outline-success update-body" title="Edit" data-id="'.$bd->no_body.'">
                <i class="fa fa-edit"></i></button><button class="btn btn-sm btn-outline-danger delete-body" title="Delete" data-toggle="modal" data-target="#hapusBody" data-id="'.$bd->no_body.'"><i class="fa fa-trash"></i>
                </button>';
            }
            if($b->edit_level=="Y" && $b->delete_level=="N"){
                $row[]='
                <button class="btn btn-sm btn-outline-success update-body" title="Edit" data-id="'.$bd->no_body.'"><i class="fa fa-edit"></i>
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

        $this->load->view('body_repair/view', $data);
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
        $data['dataKl'] = $this->Mod_body->select_kelas();
        $data['dataPool'] = $this->Mod_body->select_pool();

		echo show_my_modal('body_repair/modals/modal_tambah_body', 'update-body', $data, ' modal-xl');
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