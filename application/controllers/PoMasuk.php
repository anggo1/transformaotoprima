<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PoMasuk extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Mod_po_masuk'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "PO Masuk";
		$data['judul'] 		= "P O M";
		$this->load->helper('url');
		$data['dataCustomer'] = $this->Mod_po_masuk->select_customer();
		$this->template->load('layoutbackend', 'warehouse/po_masuk', $data);
	}
public function showPart()
    {
		$sup = $_GET['sup'];
        $data['dataDetail'] = $this->Mod_po_masuk->select_part($sup);
        $this->load->view('warehouse/data_part_with_supplier', $data);
    }
	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_po_masuk->get_datatables();
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
			"recordsTotal" => $this->Mod_po_masuk->count_all(),
			"recordsFiltered" => $this->Mod_po_masuk->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cariKode($id)
	{
		$data = $this->Mod_po_masuk->get_part($id);
		echo json_encode($data);
	}
	public function prosesDetailPo()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_po_masuk->insertDetailPo($data);
		
		echo json_encode($data);
	}
	public function updateDetailPo()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_po_masuk->update_detailPo($id,$jml_part,$hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateRemark()
	{
        $id = $_POST['id'];
        $remark = $_POST['remark'];
		$data['dataPo'] = $this->Mod_po_masuk->update_remark($id,$remark);
	}
	public function prosesPo()
	{
		
		$sekarang= date("Y-m");
		$this->form_validation->set_rules('tgl_po_masuk', 'Tanggal Order', 'trim|required');
		$this->form_validation->set_rules('customer', 'Data Customer', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			$kode_po = $data['id_po_masuk'];
			$date2 = $data['tgl_po_masuk'];
			$tgl2 = explode('-', $date2);
			$tgl_po_fix = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
			$sekarang = date('Y/m/d');
			//$s=$data['status'];
			$thn = substr($sekarang, 0, 4);
			$bln = substr($sekarang, 5, 2);

			$nama_ref="/PO/$bln/$thn";
			$koderef=$kode_po.$nama_ref;

			$data = array(
				'id_po_masuk'  	=> $kode_po,
				'kode_po'   => $koderef,
				'tgl_po'  	=> $tgl_po_fix,
				'customer'	=> $data['customer'],
				'kode_pesan'	=> $data['no_order'],
				'keterangan' => $data['keterangan'],
				'user'   	=> $data['user'],
				'status_PO'	=> 'N'
			);
				$data['dataPo'] = $this->db->insert('tbl_wh_po_masuk', $data);
				$data 	= $this->input->post();
				
			if ($result > 0) {
				$out['dataRef'] = $koderef;
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
		$id 				= $_POST['id_po_masuk'];
		$data['dataDetail'] = $this->Mod_po_masuk->select_detail($id);
		$this->load->view('warehouse/detail_po_masuk', $data);
	}
	public function view()
    {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $data['table'] = $table;
            $data['data_field'] = $this->db->field_data($table);
            $data['dataDetail'] = $this->Mod_userlevel->view($id)->result_array();
            $this->load->view('warehouse/detail_po_masuk', $data);
        
    }
	public function tampilDetailCache()
	{
		$id 				= $_GET['id_po_masuk'];
		$data['dataDetail'] = $this->Mod_po_masuk->select_detail($id);
		$this->load->view('warehouse/detail_po_masuk_cache', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_po_masuk->deleteDetail_po($id);
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
		$data['dataPo'] = $this->Mod_po_masuk->select_by_id($id);
		$data['detailPo'] = $this->Mod_po_masuk->select_detail($id);

		echo show_my_print('warehouse/modals/modal_cetak_po_masuk', 'cetak-po', $data, ' modal-xl');
	}
}
