<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PreOrder extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('service/Mod_pre_order', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
		$this->load->model('Mod_aplikasi');
	}

	public function index()
	{
		$data['page'] 		= "Pre Order";
		$data['judul'] 		= "P O";
		$this->load->helper('url');
		//$data['dataCus'] = $this->Mod_pre_order->select_customer();
		//echo show_my_modal('service/modals/modal_tambah_pre_order', 'tambah-pre-order', $data, ' modal-lg');
		$this->template->load('layoutbackend', 'service/pre_order', $data);
	}
	public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_pre_order->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_pre_order->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_pre_order->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->wo_no;
                $row[] = $p->sa_name;
                $row[] = $p->customer_name;
                $row[] = $p->customer_complain;
                $row[] = $p->vin;
                $row[] = $p->no_pol;
                $row[] = $p->type;
                $row[] = $p->storing;
                $row[] = $p->free_service == 'Y' ? '<td style="background-color: #28a745; color: white;">Free Service</td>' : '<td style="background-color: #ffc107; color: white;">Non Free</td>';
                $row[] = tglIndoPendek($p->date_open_wo);
                $row[] = $p->clockin;
                $row[] = empty($p->pre_order) ? 'Not Processed' : 'On Process';
                $row[] = $p->pembuat;
                    $edit='                    
                    <button class="btn btn-sm btn-outline-success process-pre-order" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'">Process
                  </button>';
                  $print='                    
                    <button class="btn btn-sm btn-outline-info cetak-pre-order" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'">Print
                  </button>';
                $akses_system= empty ($p->pre_order) ? $edit : $print;
                $row[] = $akses_system;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_pre_order->count_all(),
            "recordsFiltered" => $this->Mod_pre_order->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

	public function tambahOperation()
    {
        $this->form_validation->set_rules('operation', 'Operation', 'trim|required');

        //$data     = $this->input->post();
        $wo_no = $this->input->post('wo_no');
        $operation = $this->input->post('operation');
        $no_pre_order = $this->input->post('no_pre_order');


        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_pre_order->insertOperation($wo_no, $operation,$no_pre_order);

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
    public function tampilOperationDetail()
	{
		$wo_no = $_POST['wo_no'];
		$data['dataDetail'] = $this->Mod_pre_order->select_operation_detail($wo_no);
		$this->load->view('service/detail_pre_operation', $data);
	}

    public function processPreOrder() {
        $idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
		$data['dataSa'] = $this->Mod_pre_order->select_sa($id);
		$data['dataCus'] = $this->Mod_pre_order->select_customer($kode_cus);

		echo show_my_modal('service/modals/modal_tambah_pre_order', 'process-pre-order', $data, ' modal-xl');
	}

	public function inputPreOrder() {
		
		$this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_pre_order->inputPreOrder($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_ok_msg('Data Berhasil ditambahkan', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Data Gagal ditambahkan', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

    public function deleteOperation()
    {
        $id = $_POST['id'];
        $result = $this->Mod_pre_order->deleteOperation($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function cetak_pre_order()
	{
        $idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
		$data['dataSa'] = $this->Mod_pre_order->select_sa($id);
		$data['detailKet'] = $this->Mod_pre_order->select_operation_detail($id);
		$data['dataCus'] = $this->Mod_pre_order->select_customer($kode_cus);
		$data['dataPre'] = $this->Mod_pre_order->select_pre_order($id);

		echo show_my_print('service/modals/modal_cetak_pre_order', 'cetak-pre-order', $data, ' modal-xl');
	}
}
