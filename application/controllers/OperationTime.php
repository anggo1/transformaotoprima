<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OperationTime extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('service/Mod_operation_time', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
    }

    public function index()
    {
		$data['page'] 		= "Data XENTRY Operation Time";
		$data['judul'] 		= "XTOT";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        echo show_my_modal('service/modals/modal_tambah_operation_time', 'tambah-operation-time', $data);
        $this->template->load('layoutbackend', 'service/operation_time', $data);
    }

    public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_operation_time->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_operation_time->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_operation_time->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->code;
                $row[] = $p->duration;
                $row[] = $p->im;
                $row[] = $p->description;
                if($pel1->edit_level=="Y"){
                    $edit='                    
                    <button class="btn btn-sm btn-outline-success update-operation-time" title="Edit" data-id="'.$p->id_x.'"><i class="fa fa-edit"></i>
                    </button>';
                }                
                if($pel1->delete_level=="Y"){
                    $delete='
                    <button class="btn btn-sm btn-outline-danger delete-operation-time" title="Delete" data-toggle="modal" data-target="#hapusOperationTime" data-id="'.$p->id_x.'">
                    <i class="fa fa-trash"></i></button>';
                }
                if($pel1->delete_level=="N"){
                    $delete='';
                }
                if($pel1->edit_level=="N"){
                    $edit='';
                }
                if($pel1->upload_level=="N"){
                    $upload='';
                }
                $akses_system=$edit.$delete;
                $row[] = $akses_system;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_operation_time->count_all(),
            "recordsFiltered" => $this->Mod_operation_time->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /*Operation Time*/
    public function prosesToperation_time()
    {
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_rules('duration', 'Duration', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_operation_time->insertOperationTime($data);

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
    public function updateOperationTime()
    {
        $id                 = trim($_POST['id']);
        $data['dataOpTime'] = $this->Mod_operation_time->select_id_operation_time($id);
        echo show_my_modal('service/modals/modal_tambah_operation_time', 'update-operation-time', $data);
    }

    public function prosesUoperation_time()
    {

        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_rules('duration', 'Duration', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_operation_time->updateOperationTime($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_err_msg('Filed!', '20px');
            }
        } else {
            $out['status'] = 'form';
            $out['msg'] = show_err_msg(validation_errors());
        }

        echo json_encode($out);
    }
    public function deleteOperationTime()
    {
        $id = $_POST['id'];
        $result = $this->Mod_operation_time->deleteOpTime($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    /*endCustomer*/
}
