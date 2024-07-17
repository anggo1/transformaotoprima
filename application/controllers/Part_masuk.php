<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_masuk extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('warehouse/Mod_part_masuk'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Barang Masuk";
		$data['judul'] 		= "Input Stok";
		$this->load->helper('url');
		//$data['dataKode'] = $this->Mod_cuti->select_kode_cuti();
        $data['dataPo'] = $this->Mod_part_masuk->get_po();
        $data['dataSup'] = $this->Mod_part_masuk->get_sup();
        $data['dataKota'] = $this->Mod_part_masuk->get_kota();
		$this->template->load('layoutbackend', 'warehouse/part_masuk',$data);
	}

	public function cariKode($id)
	{
	$data = $this->Mod_part_masuk->get_part($id);
	echo json_encode($data);
	}
	public function showPart()
    {
		$po = $_GET['id_po'];
		//if(!empty($po)){
        $data['dataPo'] = $this->Mod_part_masuk->select_part($po);
        $this->load->view('warehouse/detail_po_part', $data);
    }
	public function showNopo()
    {
		$tgl_po = $_GET['tgl_po'];
        $data = $this->Mod_part_masuk->select_nopo($tgl_po);
        //$this->load->view('warehouse/data_po_partmasuk', $data);
		echo json_encode($data);
    }
	public function showPo()
    {
		//$tgl_po = $_GET['tgl_po'];
        $data['dataPo']= $this->Mod_part_masuk->select_po();
        //$this->load->view('warehouse/data_po_partmasuk', $data);
        $this->load->view('warehouse/data_part_masuk_po', $data);
    }
	public function prosesPartmasuk()
	{
		$tgl_masuk = date("y-m-d");
		$date = date("ym");
		$ci_kons = get_instance();
		$query = "SELECT max(kode_masuk) AS maxKode FROM tbl_wh_part_masuk WHERE kode_masuk LIKE '%$date%'";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 5, 4);
		$noUrut++;
		$tahun = substr($date, 0, 2);
		$bulan = substr($date, 2, 2);

		$kd='';
		$status = $_POST['status'];
		if($status=='PPU'){
			$kd='PTB-';
		}
		if($status=='MPU'){
			$kd='MTB-';
		}
		//if($status=='AKTIF' && $status_po=='N'){
		//	$kd='PTN-';
		//}
		//if($status=='PASIF' && $status_po=='N'){
		//	$kd='MTN-';
		//}

		$kode_awal  = $tahun.$bulan.sprintf("%04s", $noUrut);
		$kode_masuk  = $kd.$kode_awal;


		$this->form_validation->set_rules('tgl_po', 'Tanggal PO', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			
			$lokasi = $this->input->post('lokasi');				
			$kl = explode('|',$lokasi);
			$kd_lok = $kl[0];
			$nm_lok = $kl[1];

			$data = array(
				'kode_masuk'  	=> $kode_awal,
				'id_masuk'  	=> $kode_masuk,
				'tgl_masuk'  	=> $tgl_masuk,
				'status'      	=> $data['lokasi'],
				'keterangan'  	=> $data['keterangan'],
				'status_po'		=> 'Y',
				'no_po'			=> $data['no_po'],
				'no_sj_sup'		=> $data['no_sj_sup'],
				'no_inv_sup'	=> $data['no_inv_sup'],
				'kode_sup'		=> $data['kode_sup'],
				'nama_supplier'		=> $data['supplier'],
				'lokasi'   		=> $nm_lok,
				'user'   		=> $data['user']
			);
				$data['dataPo'] = $this->db->insert('tbl_wh_part_masuk', $data);
				$data 	= $this->input->post();
				$no_part = $this->input->post('no_part');
				$harga = $this->input->post('harga');
				$nama_part = $this->input->post('nama_part');
				$qty_masuk = $this->input->post('qty_masuk');
				$satuan = $this->input->post('satuan');
				$stok = $this->input->post('stok');
				$stok_jkt = $this->input->post('stok_jkt');
				$stok_cbt = $this->input->post('stok_cbt');
				$stok_sby = $this->input->post('stok_sby');
				$lokasi = $this->input->post('lokasi');
				
				$kl = explode('|',$lokasi);
				$kd_lok = $kl[0];
				$nm_lok = $kl[1];

				$id_po=$data['id_po'];
				$this->Mod_part_masuk->insert_part($kode_awal,$kode_masuk, $data,$no_part,$harga,$nama_part,$qty_masuk,$satuan,$stok,$stok_jkt,$stok_cbt,$stok_sby,$id_po,$kd_lok,$nm_lok);
			if ($result > 0) {
				$out['dataPo'] = $kode_awal;
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
		$id 				= $_GET['id_po'];
		$data['dataDetail'] = $this->Mod_part_masuk->select_detail($id);
		$this->load->view('warehouse/detail_part_masuk', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_part_masuk->deleteDetail($id);
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
	public function updatePart()
	{
        $id = $_POST['id'];
        $qty_awal = $_POST['qty_awal'];
        $qty_masuk = $_POST['qty_masuk'];
		$data['dataEstimasi'] = $this->Mod_part_masuk->update_part($id,$qty_awal,$qty_masuk);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function deletepartDetail()
	{
		$id = $_POST['id'];
		$sisa = $_POST['sisa'];
		$result = $this->Mod_part_masuk->deletepartDetail($id,$sisa);
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
		$data['dataMasuk'] = $this->Mod_part_masuk->select_by_id($id);
		$data['detailMasuk'] = $this->Mod_part_masuk->select_detail_cetak($id);

		echo show_my_print('warehouse/modals/modal_cetak_part_masuk', 'cetak-masuk', $data, ' modal-xl');
	}
}
