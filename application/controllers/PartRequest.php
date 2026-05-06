<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PartRequest extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('service/Mod_part_request','Mod_sparepart', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
		$this->load->model('Mod_aplikasi');
	}

	public function index()
	{
		$data['page'] 		= "Part Request";
		$data['judul'] 		= "Request";
		$this->load->helper('url');
		//$data['dataCus'] = $this->Mod_part_request->select_customer();
		//echo show_my_modal('service/modals/modal_tambah_pre_order', 'tambah-pre-order', $data, ' modal-lg');
		$this->template->load('layoutbackend', 'service/part_request', $data);
	}
	public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_part_request->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_part_request->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_part_request->get_datatables();
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
                $row[] = $p->free_service == 'Y' ? '<td style="background-color: #28a745; color: white;">Free Service</td>' : '<td style="background-color: #ffc107; color: white;">Non Free</td>';
                $row[] = tglIndoPendek($p->date_open_wo);
                $row[] = $p->clockin;
                $row[] = empty($p->pre_order) ? 'Not Processed' : 'On Process';
                $row[] = $p->pembuat;
                    $process='                    
                    <button class="btn btn-xs btn-success process-part" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-cogs"></i> Process
                  </button>';
                  $edit='';
                    /* <button class="btn btn-xs btn-primary edit-part" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-edit"></i> Edit
                  </button>'; */
                  $print='                    
                    <button class="btn btn-xs btn-info cetak-part-request" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-print"></i> Print
                  </button>';
                $akses_system= ($p->part_request=='N') ? $process : $print.$edit;
                $row[] = $akses_system;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_part_request->count_all(),
            "recordsFiltered" => $this->Mod_part_request->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    //Cari Part
    public function list_parts()
    {
            $list = $this->Mod_sparepart->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->no_part;
                $row[] = $p->nama_part;                
                $row[] = $p->harga_baru;                
                $data[] = $row;
            }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_sparepart->count_all(),
            "recordsFiltered" => $this->Mod_sparepart->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    //end cari Part
    public function inputPartRequest()
    {
        $this->form_validation->set_rules('wo_no', 'No WO', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_part_request->updatePart($data);
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
    
	public function tambahPart()
    {
        $this->form_validation->set_rules('no_part', 'No Part', 'trim|required');
        $this->form_validation->set_rules('nama_part', 'Nama Part', 'trim|required');

        //$data     = $this->input->post();
        $wo_no = $this->input->post('wo_no');
        $no_part = $this->input->post('no_part');
        $nama_part = $this->input->post('nama_part');
        $harga = $this->input->post('harga');
        $jumlah = $this->input->post('jumlah');
        $total = $harga * $jumlah;
        $keterangan = $this->input->post('keterangan');


        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_part_request->insertPart($wo_no, $no_part, $nama_part, $harga, $jumlah, $total, $keterangan);

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
    public function tampilRequestDetail()
	{
		$wo_no = $_POST['wo_no'];
		$data['dataDetail'] = $this->Mod_part_request->select_part_request($wo_no);
		$this->load->view('service/detail_part_request', $data);
	}

    public function processPart_request() {
        $idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
		$data['dataSa'] = $this->Mod_part_request->select_sa($id);
		$data['dataCus'] = $this->Mod_part_request->select_customer($kode_cus);

		echo show_my_modal('service/modals/modal_tambah_part_request', 'process-part-request', $data, ' modal-xl');
	}

	
    public function deleteRequest()
    {
        $id = $_POST['id'];
        $result = $this->Mod_part_request->deleteRequest($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function cetak_part_request()
	{
        $idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
		$data['dataSa'] = $this->Mod_part_request->select_sa($id);
		$data['dataCus'] = $this->Mod_part_request->select_customer($kode_cus);
		$data['dataPartA'] = $this->Mod_part_request->select_part_request_a($id);
		$data['dataPart'] = $this->Mod_part_request->select_part_request($id);

		echo show_my_print('service/modals/modal_cetak_part', 'cetak-part-request', $data, ' modal-xl');
	}
}