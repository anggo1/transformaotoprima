<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Customer extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_customer', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
    }

    public function index()
    {
		$data['page'] 		= "Data Customer";
		$data['judul'] 		= "Customer";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        echo show_my_modal('service/modals/modal_tambah_customer', 'tambah-customer', $data);
        $this->template->load('layoutbackend', 'service/customer', $data);
    }

    public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_customer->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_customer->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_customer->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->kode_cus;
                $row[] = $p->kode_nama;
                $row[] = $p->nama_cus;
                $row[] = $p->detail;
                $row[] = $p->alamat;
                $row[] = $p->no_tlp;
                $row[] = $p->tlp_person;
                if($pel1->edit_level=="Y"){
                    $edit='                    
                    <button class="btn btn-sm btn-outline-success update-customer" title="Edit" data-id="'.$p->id_cus.'"><i class="fa fa-edit"></i>
                    </button>';
                }                
                if($pel1->delete_level=="Y"){
                    $delete='
                    <button class="btn btn-sm btn-outline-danger delete-customer" title="Delete" data-toggle="modal" data-target="#hapusCustomer" data-id="'.$p->id_cus.'">
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
            "recordsTotal" => $this->Mod_customer->count_all(),
            "recordsFiltered" => $this->Mod_customer->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /*Customer*/
    public function prosesTcustomer()
    {
        $this->form_validation->set_rules('nama_customer', 'Nama Customer', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_customer->insertCustomer($data);

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
    public function updateCustomer()
    {
        $id                 = trim($_POST['id']);
        $data['dataCus'] = $this->Mod_customer->select_id_customer($id);
        echo show_my_modal('service/modals/modal_tambah_customer', 'update-customer', $data);
    }

    public function prosesUcustomer()
    {

        $this->form_validation->set_rules('nama_customer', 'Nama Customer', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_customer->updateCustomer($data);

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
    public function deleteCustomer()
    {
        $id = $_POST['id'];
        $result = $this->Mod_customer->deleteCus($id);

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
