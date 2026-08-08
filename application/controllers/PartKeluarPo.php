<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PartKeluarPo extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('warehouse/Mod_part_keluar_po'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Barang Keluar";
		$data['judul'] 		= "Out Stock";
		$this->load->helper('url');
		//$data['dataKode'] = $this->Mod_cuti->select_kode_cuti();
		$idlokasi = $this->session->userdata['lokasi'];
		$idlevel = $this->session->userdata['id_level'];
		$data['dataPo'] = $this->Mod_part_keluar_po->get_po();
		$data['dataSup'] = $this->Mod_part_keluar_po->get_sup();
		$data['dataKota'] = $this->Mod_part_keluar_po->get_kota();
		$this->template->load('layoutbackend', 'warehouse/part_keluar_po', $data);
	}

	public function cariKode($id)
	{
		$data = $this->Mod_part_keluar_po->get_part($id);
		echo json_encode($data);
	}
	public function showPar1t()
	{
		$wo_no = $_GET['wo_no'];
		//if(!empty($po)){
		$data['dataPo'] = $this->Mod_part_keluar_po->select_part($wo_no);
		$this->load->view('warehouse/detail_po_part', $data);
	}
	public function showNopo()
	{
		$tgl_po = $_GET['tgl_po'];
		$data = $this->Mod_part_keluar_po->select_nopo($tgl_po);
		//$this->load->view('warehouse/data_po_partmasuk', $data);
		echo json_encode($data);
	}
	public function showPo()
	{
		//$tgl_po = $_GET['tgl_po'];
		$data['dataPo'] = $this->Mod_part_keluar_po->select_po();
		//$this->load->view('warehouse/data_po_partmasuk', $data);
		$this->load->view('warehouse/data_part_keluar_po', $data);
	}
	public function cari_barang()
	{
		$id_po_masuk = $_GET['id_po_masuk'];
		$data['dataPo'] = $this->Mod_part_keluar_po->cari_barang($id_po_masuk);
		//$this->load->view('warehouse/data_po_partmasuk', $data);
		$this->load->view('warehouse/detail_part_keluar_po', $data);
	}

	public function prosesPartkeluar()
	{
		$idlokasi = $this->session->userdata['lokasi'];
		$idlevel = $this->session->userdata['id_level'];
		$tgl_keluar = date("y-m-d");
		$date = date("ym");
		$ci_kons = get_instance();
		$query = "SELECT max(kode_keluar) AS maxKode FROM tbl_wh_part_keluar_po WHERE kode_keluar LIKE '%$date%'";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 5, 4);
		$noUrut++;
		$tahun = substr($date, 0, 2);
		$bulan = substr($date, 2, 2);

		$kd = '';
		if ($idlokasi == 'Cibitung') {
			$kd = 'CBT-';
		}
		if ($idlokasi == 'Jakarta') {
			$kd = 'JKT-';
		}
		if ($idlokasi == 'Surabaya') {
			$kd = 'SBY-';
		}
		$kode_awal  = $tahun . $bulan . sprintf("%04s", $noUrut);
		$kode_keluar  = $kd . $kode_awal;


		$this->form_validation->set_rules('tgl_keluar', 'Tanggal PO', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			$id_po_masuk = $this->input->post('id_po_masuk');
			$data = array(
				'kode_keluar'  	=> $kode_keluar,
				'id_po_masuk'  	=> $id_po_masuk,
				'kode_po'  		=> $data['kode_po'],
				'tgl_po'  		=> $tgl_keluar,
				'kode_cus'  	=> $data['kode_cus'],
				'nama_cus'  	=> $data['nama_cus'],
				'no_sj'  		=> $data['no_sj'],
				'lokasi'      	=> $idlokasi,
				'keterangan'		=> $data['keterangan'],
				'pengguna'		=> $data['pengguna']
			);
			$data['dataPo'] = $this->db->insert('tbl_wh_part_keluar_po', $data);
			$this->db->update('tbl_wh_po_masuk', array('status_po' => 'Y'), array('id_po_masuk' => $id_po_masuk));

			$id_po_masuk = $this->input->post('id_po_masuk');
			$data 	= $this->input->post();
			$this->Mod_part_keluar_po->insert_part($kode_keluar, $id_po_masuk, $data);
			if ($result > 0) {
				$out['dataPo'] = $kode_keluar;
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
		$id_po_masuk 				= $_GET['id_po'];
		$data['dataPo'] = $this->Mod_part_keluar_po->cari_barang($id_po_masuk);
		$this->load->view('warehouse/detail_part_keluar_po', $data);
	}
	public function tampilDetailCache()
	{
		$id_po_masuk = $_GET['id_po_masuk'];
		$data['dataPo'] = $this->Mod_part_keluar_po->cari_barang($id_po_masuk);
		//$this->load->view('warehouse/data_po_partmasuk', $data);
		$this->load->view('warehouse/detail_part_keluar_po_cache', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_part_keluar_po->deleteDetail($id);
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
	public function updateJumlah()
	{
		$id = $_POST['id'];
		$jumlah = $_POST['jumlah'];
		$jml_keluar = $_POST['jml_keluar'];
		$hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_part_keluar_po->update_part($id, $jumlah, $jml_keluar, $hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function deletepartDetail()
	{
		$id = $_POST['id'];
		$sisa = $_POST['sisa'];
		$result = $this->Mod_part_keluar_wo->deletepartDetail($id, $sisa);
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
		$id_po_masuk 				= $_POST['id_po_masuk'];
		$data['dataKeluar'] = $this->Mod_part_keluar_po->select_by_id($id_po_masuk);
		$data['detailKeluar'] = $this->Mod_part_keluar_po->select_detail_cetak($id_po_masuk);

		echo show_my_print('warehouse/modals/modal_cetak_data_part_keluar_po', 'cetak-keluar', $data, ' modal-xl');
	}
	public function cetak_bon()
	{
		$id_po_masuk 				= $_POST['id_po_masuk'];
		$data['dataKeluar'] = $this->Mod_part_keluar_po->select_by_id($id_po_masuk);
		$data['detailKeluar'] = $this->Mod_part_keluar_po->select_detail_cetak($id_po_masuk);

		echo show_my_print('warehouse/modals/modal_cetak_faktur_po', 'cetak-bon-keluar', $data, ' modal-xl');
	}
}
