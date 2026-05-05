<?php
defined('BASEPATH') or exit('No direct script access allowed');
class WorkOrder extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('service/Mod_work_order', 'service/Mod_operation_time', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
		$this->load->model('Mod_aplikasi');
	}

	public function index()
	{
		$data['page'] 		= "Work Order";
		$data['judul'] 		= "W O";
		$this->load->helper('url');
		//$data['dataCus'] = $this->Mod_work_order->select_customer();
		//echo show_my_modal('service/modals/modal_tambah_work_order', 'tambah-work-order', $data, ' modal-lg');
		$this->template->load('layoutbackend', 'service/work_order', $data);
	}
	public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_work_order->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_work_order->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_work_order->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->wo_no;
                $row[] = $p->sa_name;
                $row[] = $p->customer;
                $row[] = $p->customer_complain;
                $row[] = $p->vin;
                $row[] = $p->no_pol;
                $row[] = $p->type;
                $row[] = $p->storing;
                $row[] = tglIndoPendek($p->date_open_wo);
                $row[] = $p->clockin;
                $row[] = empty($p->work_order) ? 'Not Processed' : 'Active';
                $row[] = $p->pembuat;
                    $edit='                    
                    <button class="btn btn-xs btn-dark process-work-order" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-chalkboard"></i> Process
                  </button>';
                  
                  $print='                    
                    <button class="btn btn-xs btn-info cetak-work-order" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-print"></i> Print
                  </button>
                  <button class="btn btn-xs btn-primary process-work-order" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-edit"></i> Edit
                  </button>
                  <button class="btn btn-xs btn-success process-start" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-play"></i> Detail
                  </button>
                  <button class="btn btn-xs btn-danger hapus-work-order" title="Edit" data-id="'.$p->wo_no.'"><i class="fa fa-times"></i>Finish
                  </button>';
                $akses_system= empty ($p->work_order) ? $edit : $print;
                $row[] = $akses_system;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_work_order->count_all(),
            "recordsFiltered" => $this->Mod_work_order->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    //Cari Operation
    public function list_operation()
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
                $row[] = $p->description;
                
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
    //end cari operation

    
	public function tambahXot()
    {
        $this->form_validation->set_rules('operation', 'Operation', 'trim|required');

        //$data     = $this->input->post();
        $wo_no = $this->input->post('wo_no');
        $operation = $this->input->post('operation');
        $hours = $this->input->post('hours');
        $type_of_work = $this->input->post('type_of_work');
        $no_work_order = $this->input->post('no_work_order');


        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_work_order->insertOperation($wo_no, $operation, $hours, $type_of_work, $no_work_order);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_err_msg('Failed !', '20px');
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
		$data['dataDetail'] = $this->Mod_work_order->select_operation_detail($wo_no);
		$this->load->view('service/detail_work_order', $data);
	}
    public function tambahLabor()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');

        //$data     = $this->input->post();
        $wo_no = $this->input->post('wo_no');
        $no_work_order = $this->input->post('no_work_order');
        $nik   = $this->input->post('nik');
        $nama = $this->input->post('nama');


        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_work_order->insertLabor($wo_no, $nik, $nama, $no_work_order);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_err_msg('Failed !', '20px');
            }
        } else {
            $out['status'] = 'form';
            $out['msg'] = show_err_msg(validation_errors());
        }

        echo json_encode($out);
    }
    //start insert data mechanic
    public function tampilLabor() {
		$idL = $_POST['id'];

        $data['apl'] = $this->db->get("aplikasi")->row();
		$data['dataMechanic'] = $this->Mod_work_order->select_labor_detail($idL);
        
		$this->load->view('service/add_mechanic', $data);

		//echo show_my_modal('service/modals/modal_tambah_work_order', 'process-work-order', $data, ' modal-xl');
	}
    
    public function tampilMechanic() {
		$idX = $_POST['no_work_order'];
		$data['dataM'] = $this->Mod_work_order->select_labor_mechanic($idX);
        
		$this->load->view('service/detail_mechanic', $data);

		//echo show_my_modal('service/modals/modal_tambah_work_order', 'process-work-order', $data, ' modal-xl');
	}

    //end insert data mechanic
    public function processWorkOrder() {
        $idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
		$data['dataSa'] = $this->Mod_work_order->select_sa($id);
		$data['dataCus'] = $this->Mod_work_order->select_customer($kode_cus);
        
		$this->load->view('service/modals/modal_tambah_work_order', $data);

		//echo show_my_modal('service/modals/modal_tambah_work_order', 'process-work-order', $data, ' modal-xl');
	}

	public function inputWorkOrder() {
		
		$this->form_validation->set_rules('wo_no', 'No Work Order', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_work_order->inputWorkOrder($data);

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
        $result = $this->Mod_work_order->deleteOperation($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Failed !', '20px');
        }
        echo json_encode($out);
    }
    public function deleteMechanic()
    {
        $id = $_POST['id'];
        $result = $this->Mod_work_order->deleteMechanic($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Failed !', '20px');
        }
        echo json_encode($out);
    }
    public function cetak_work_order()
	{
        $idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
		$data['dataSa'] = $this->Mod_work_order->select_sa($id);
		$data['detailKet'] = $this->Mod_work_order->select_operation_detail($id);
		$data['dataCus'] = $this->Mod_work_order->select_customer($kode_cus);
		$data['dataWork'] = $this->Mod_work_order->select_work_order($id);

		echo show_my_print('service/modals/modal_cetak_work_order', 'cetak-work-order', $data, ' modal-xl');
	}

    //start process start work order
    public function processStartWorkOrder() {
        $idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
        $data['dataSa'] = $this->Mod_work_order->select_sa($id);
        $data['dataCus'] = $this->Mod_work_order->select_customer($kode_cus);
        
        $this->load->view('service/modals/process_start_work', $data);

        //echo show_my_modal('service/modals/modal_tambah_work_order', 'process-work-order', $data, ' modal-xl');
    }
    public function tampilStartDetail()
	{
		$wo_no = $_POST['wo_no'];
		$data['dataDetail'] = $this->Mod_work_order->select_operation_detail($wo_no);
		$this->load->view('service/detail_start_work_order', $data);
	}
    public function StartWork()
	{
        $id_detail = $_POST['id_detail'];
        $no_work_order = $_POST['no_work_order'];
		$data['dataPo'] = $this->Mod_work_order->start_work($id_detail,$no_work_order);
	}
    public function PauseWork()
	{
        date_default_timezone_set('Asia/Jakarta');
        $id_detail = $_POST['id_detail'];
        $no_work_order = $_POST['no_work_order'];
	    $tgl_jam_sekarang  = date("Y-m-d H:i:s");
	    $tgl_jam_mulai  = $_POST['start_date'];

        $start = new DateTime($tgl_jam_mulai);
        $end = new DateTime($tgl_jam_sekarang);
        $interval = $start->diff($end);

        $jam = $interval->format('%h');
        $menit = $interval->format('%i');
        $total=$jam.'.'.$menit;


		$data['dataPo'] = $this->Mod_work_order->pause_work($id_detail,$no_work_order,$total);
	}
    public function EndWork()
	{
        $id_detail = $_POST['id_detail'];
        $no_work_order = $_POST['no_work_order'];
	    $tgl_jam_sekarang  = date("Y-m-d H:i:s");
	    $tgl_jam_mulai  = $_POST['start_date'];

        $start = new DateTime($tgl_jam_mulai);
        $end = new DateTime($tgl_jam_sekarang);
        $interval = $start->diff($end);

        $jam = $interval->format('%h');
        $menit = $interval->format('%i');
        $total=$jam.'.'.$menit;
        
		$data['dataPo'] = $this->Mod_work_order->end_work($id_detail,$no_work_order,$total);
	}
    public function finishWorkOrder()
	{
        $wo_no = $_POST['wo_no'];
        
        $result = $this->Mod_work_order->finish_work($wo_no);
         if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Finished', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Failed !', '20px');
        }
        echo json_encode($out);
	}
    //end process start work order
}   