<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseOrder extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Mod_purchaseorder'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Purchase Order";
		$data['judul'] 		= "PO";
		$this->load->helper('url');
		$data['dataSupplier'] = $this->Mod_purchaseorder->select_supplier();
		$this->template->load('layoutbackend', 'warehouse/purchase_order', $data);
	}
public function showPart()
    {
		$sup = $_GET['sup'];
        $data['dataDetail'] = $this->Mod_purchaseorder->select_part($sup);
        $this->load->view('warehouse/data_part_with_supplier', $data);
    }
	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_purchaseorder->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pel) {
			$no++;
			$row = array();
			$row[] = "<a onclick=selectPart('$pel->id_barang')>$no</a>";
			$row[] = $pel->no_part;
			$row[] = $pel->nama_part;
			$row[] = $pel->stok;
			$row[] = number_format($pel->hrg_awal);
			$row[] = $pel->kode_satuan;
			$row[] = $pel->id_barang;
			$row[] = $pel->stok_a;
			$row[] = $pel->stok_p;
			$row[] = $pel->hrg_awal;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_purchaseorder->count_all(),
			"recordsFiltered" => $this->Mod_purchaseorder->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cariKode($id)
	{
		$data = $this->Mod_purchaseorder->get_part($id);
		echo json_encode($data);
	}
	public function prosesDetailPo()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_purchaseorder->insertDetailPo($data);
		
		echo json_encode($data);
	}
	public function updateDetailPo()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_purchaseorder->update_detailPo($id,$jml_part,$hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function prosesPo()
	{
		$this->form_validation->set_rules('tgl_po', 'Tanggal Masuk', 'trim|required');
		$this->form_validation->set_rules('ppn', 'PPN', 'trim|required');
		$this->form_validation->set_rules('supplier', 'Data Supplier', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			$kode_po = $data['id_po'];
			$date2 = $data['tgl_po'];
			$tgl2 = explode('-', $date2);
			$tgl_po_fix = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
			$sekarang = date('Y/m/d');
			$nama_ref="/PO/CNK/";
			$s=$data['status'];
			$koderef=$s.$nama_ref.$kode_po;

			$data = array(
				'id_po'  	=> $kode_po,
				'kode_po'   => $koderef,
				'tgl_po'  	=> $tgl_po_fix,
				'top'      	=> $data['top'],
				'ppn'  		=> $data['ppn'],
				'supplier'	=> $data['supplier'],
				'keterangan' => $data['keterangan'],
				'user'   	=> $data['user'],
				'status'   	=> $data['status'],
				'status_PO'	=> 'N'
			);
				$data['dataPo'] = $this->db->insert('tbl_wh_po', $data);
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
		$id 				= $_POST['id_po'];
		$data['dataDetail'] = $this->Mod_purchaseorder->select_detail($id);
		$this->load->view('warehouse/detail_po', $data);
	}
	public function view()
    {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $data['table'] = $table;
            $data['data_field'] = $this->db->field_data($table);
            $data['dataDetail'] = $this->Mod_userlevel->view($id)->result_array();
            $this->load->view('warehouse/detail_po', $data);
        
    }
	public function tampilDetailCache()
	{
		$id 				= $_GET['id_po'];
		$data['dataDetail'] = $this->Mod_purchaseorder->select_detail($id);
		$this->load->view('warehouse/detail_po_cache', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_purchaseorder->deleteDetail_po($id);
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
		$data['dataPo'] = $this->Mod_purchaseorder->select_by_id($id);
		$data['detailPo'] = $this->Mod_purchaseorder->select_detail($id);

		echo show_my_print('warehouse/modals/modal_cetak_po', 'cetak-po', $data, ' modal-xl');
	}
}
