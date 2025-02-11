<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EstimasiPenawaran extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Mod_estimasi_penawaran'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Estimasi Penawaran";
		$data['judul'] 		= "Estimasi";
		$this->load->helper('url');
		$data['dataCustomer'] = $this->Mod_estimasi_penawaran->select_customer();
        echo show_my_modal('warehouse/modals/modal_keterangan_estimasi', 'tambah-keterangan', $data);
		$this->template->load('layoutbackend', 'warehouse/estimasi_penawaran', $data);
	}
public function showPart()
    {
		$sup = $_GET['sup'];
        $data['dataDetail'] = $this->Mod_estimasi_penawaran->select_part($sup);
        $this->load->view('warehouse/data_part_with_supplier', $data);
    }
	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_estimasi_penawaran->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pel) {
			$no++;
			$row = array();
			$row[] = "$no";
			$row[] = $pel->no_part;
                $row[] = $pel->nama_part;
                $row[] = $pel->satuan;
                $row[] = $pel->stok;
                $row[] = number_format($pel->harga_baru);
                $row[] = $pel->diskon;
                $row[] = number_format($pel->harga_net);
                $row[] = number_format($pel->harga_rata);
                $row[] = $pel->ppn;
                $row[] = number_format($pel->harga_valid);
                $row[] = $pel->ket_harga;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_estimasi_penawaran->count_all(),
			"recordsFiltered" => $this->Mod_estimasi_penawaran->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cariKode($id)
	{
		$data = $this->Mod_estimasi_penawaran->get_part($id);
		echo json_encode($data);
	}
	public function prosesDetailPo()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_estimasi_penawaran->insertDetailPo($data);
		
		echo json_encode($data);
	}
	public function updateDiskon()
	{
        $id = $_POST['id'];
        $diskon = $_POST['diskon'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran->update_detailDiskon($id,$diskon,$hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateDetailPo()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran->update_detailPo($id,$jml_part,$hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateRemark()
	{
        $id = $_POST['id'];
        $remark = $_POST['remark'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran->update_remark($id,$remark);
	}
	public function tambahKeterangan()
	{
        $id = $_POST['id'];
        $remark = $_POST['keterangan'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran->insertRemark($id,$remark);
	}
	public function tambahNote()
	{
        $id = $_POST['id'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran->insertNote($id);
	}
	public function prosesPo()
	{
		
		$sekarang= date("Y-m");
		$this->form_validation->set_rules('tgl_estimasi_penawaran', 'Tanggal Order', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			$kode_po = $data['id_estimasi_penawaran'];
			$date2 = $data['tgl_estimasi_penawaran'];
			
			$bea=$data['bea_kirim'];
			$bea_kirim =str_replace(",","", $bea);

			$tgl2 = explode('-', $date2);
			$tgl_po_fix = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";

			$data = array(
				'id_estimasi_penawaran'  	=> $kode_po,
				'kode_estimasi_penawaran'   => $data['no_ref'],
				'tgl_estimasi_penawaran'  	=> $tgl_po_fix,
				'id_customer'	=> $data['id_customer'],
				'no_reg'	=> $data['no_reg'],
				'no_vin' => $data['no_vin'],
				'sales_design' => $data['sales_design'],
				'date_received' => $data['date_received'],
				'millage' => $data['millage'],
				'engine_no' => $data['engine_no'],
				'acc_no' => $data['acc_no'],
				'received_by' => $data['received_by'],
				'routing_no' => $data['routing_no'],
				'last_km' => $data['last_km'],
				'date_of_regis' => $data['date_of_regis'],
				'ppn' => $data['ppn'],
				'bea_kirim' => $bea_kirim,
				'user'   	=> $data['user'],
				'status_po'	=> 'N'
			);
				$data['dataPo'] = $this->db->insert('tbl_wh_estimasi_penawaran', $data);
				$data 	= $this->input->post();
				
			if ($result > 0) {
				$out['dataRef'] = $data['no_ref'];
				$out['dataPo'] = $kode_po;
				$out['status'] = '';
				$out['msg'] = show_ok_msg('Data  ditambahkan!', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_del_msg('Filed !', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}
		echo json_encode($out);
	}
	public function tampilDetail()
	{
		$id 				= $_POST['id_estimasi_penawaran'];
		$data['dataDetail'] = $this->Mod_estimasi_penawaran->select_detail($id);
		$this->load->view('warehouse/detail_estimasi_penawaran', $data);
	}
	public function tampilKeterangan()
	{
		$id 				= $_POST['id_estimasi_penawaran'];
		$data['dataKet'] = $this->Mod_estimasi_penawaran->select_keterangan($id);
		$this->load->view('warehouse/data_keterangan_estimasi', $data);
	}
	public function view()
    {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $data['table'] = $table;
            $data['data_field'] = $this->db->field_data($table);
            $data['dataDetail'] = $this->Mod_userlevel->view($id)->result_array();
            $this->load->view('warehouse/detail_estimasi_penawaran', $data);
        
    }
	public function tampilDetailCache()
	{
		$id 				= $_GET['id_estimasi_penawaran'];
		$data['dataDetail'] = $this->Mod_estimasi_penawaran->select_detail($id);
		$this->load->view('warehouse/detail_estimasi_penawaran_cache', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_estimasi_penawaran->deleteDetail_po($id);
		if ($result > 0) {
			//$out['datakode']=$kodeBaru;
			$out['status'] = '';
			$out['msg'] = show_del_msg('Deleted', '10px');
		} else {
			$out['status'] = '';
			$out['msg'] = show_err_msg('Filed !', '10px');
		}
		echo json_encode($out);
	}
	
	public function deleteKeterangan()
	{
		$id = $_POST['id'];
		$result = $this->Mod_estimasi_penawaran->deleteKeterangan_po($id);
		if ($result > 0) {
			//$out['datakode']=$kodeBaru;
			$out['status'] = '';
			$out['msg'] = show_del_msg('Deleted', '10px');
		} else {
			$out['status'] = '';
			$out['msg'] = show_err_msg('Filed !', '10px');
		}
		echo json_encode($out);
	}
	public function cetak()
	{
		$id 				= $_POST['id'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran->select_by_id($id);
		$data['detailPo'] = $this->Mod_estimasi_penawaran->select_detail($id);
		$data['detailKet'] = $this->Mod_estimasi_penawaran->select_keterangan($id);

		echo show_my_print('warehouse/modals/modal_cetak_estimasi_penawaran', 'cetak-po', $data, ' modal-xl');
	}
	public function cetak_int()
	{
		$id 				= $_POST['id'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran->select_by_id($id);
		$data['detailPo'] = $this->Mod_estimasi_penawaran->select_detail($id);

		echo show_my_print('warehouse/modals/modal_cetak_estimasi_penawaran_internal', 'cetak-po-int', $data, ' modal-xl');
	}
}