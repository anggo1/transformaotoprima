<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PartKeluarWo extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('warehouse/Mod_part_keluar_wo'));
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
        $data['dataPo'] = $this->Mod_part_keluar_wo->get_po();
        $data['dataSup'] = $this->Mod_part_keluar_wo->get_sup();
        $data['dataKota'] = $this->Mod_part_keluar_wo->get_kota();
		$this->template->load('layoutbackend', 'warehouse/part_keluar_wo',$data);
	}

	public function cariKode($id)
	{
	$data = $this->Mod_part_keluar_wo->get_part($id);
	echo json_encode($data);
	}
	public function showPar1t()
    {
		$wo_no = $_GET['wo_no'];
		//if(!empty($po)){
        $data['dataPo'] = $this->Mod_part_keluar_wo->select_part($wo_no);
        $this->load->view('warehouse/detail_wo_part', $data);
    }
	public function showNopo()
    {
		$tgl_po = $_GET['tgl_po'];
        $data = $this->Mod_part_keluar_wo->select_nopo($tgl_po);
        //$this->load->view('warehouse/data_po_partmasuk', $data);
		echo json_encode($data);
    }
	public function showWo()
    {
		//$tgl_po = $_GET['tgl_po'];
        $data['dataPo']= $this->Mod_part_keluar_wo->select_wo();
        //$this->load->view('warehouse/data_po_partmasuk', $data);
        $this->load->view('warehouse/data_part_keluar_wo', $data);
    }
	public function cari_barang()
    {
		$wo_no = $_GET['wo_no'];
        $data['dataPo']= $this->Mod_part_keluar_wo->cari_barang($wo_no);
        //$this->load->view('warehouse/data_po_partmasuk', $data);
        $this->load->view('warehouse/detail_wo_part', $data);
    }
	public function prosesPartkeluar()
	{
        $idlokasi = $this->session->userdata['lokasi'];
        $idlevel = $this->session->userdata['id_level'];
		$tgl_keluar = date("y-m-d");
		$date = date("ym");
		$ci_kons = get_instance();
		$query = "SELECT max(kode_keluar) AS maxKode FROM tbl_wh_part_keluar_service WHERE kode_keluar LIKE '%$date%'";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 5, 4);
		$noUrut++;
		$tahun = substr($date, 0, 2);
		$bulan = substr($date, 2, 2);

		$kd='';
		if($idlokasi=='Cibitung'){
			$kd='CBT-';
		}
		if($idlokasi=='Jakarta'){
			$kd='JKT-';
		}
		if($idlokasi=='Surabaya'){
			$kd='SBY-';
		}
		$kode_awal  = $tahun.$bulan.sprintf("%04s", $noUrut);
		$kode_keluar  = $kd.$kode_awal;


		$this->form_validation->set_rules('tgl_keluar', 'Tanggal PO', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			
			$data = array(
				'kode_keluar'  	=> $kode_keluar,
				'wo_no'  		=> $data['wo_no'],
				'tgl_keluar'  	=> $tgl_keluar,
				'no_sj'  		=> $data['no_sj'],
				'nik'			=> $data['nik'],
				'petugas'		=> $data['petugas'],
				'pengguna'		=> $data['pengguna'],
				'lokasi'      	=> $idlokasi
			);
				$data['dataPo'] = $this->db->insert('tbl_wh_part_keluar_service', $data);

				$data 	= $this->input->post();
				$wo_no = $this->input->post('wo_no');
				$no_part = $this->input->post('no_part');
				$nama_part = $this->input->post('nama_part');
				$jumlah = $this->input->post('jumlah');
				$harga_penawaran = $this->input->post('harga_penawaran');
				$harga = $this->input->post('harga');
				$jml_keluar = $this->input->post('jml_keluar');
				$sisa = $this->input->post('sisa');
				$stok = $this->input->post('stok');

				$this->Mod_part_keluar_wo->insert_part($wo_no,$kode_keluar, $no_part,$nama_part,$jumlah,$harga_penawaran,$harga,$jml_keluar,$sisa,$stok);
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
		$id 				= $_GET['id_po'];
		$data['dataDetail'] = $this->Mod_part_keluar_wo->select_detail($id);
		$this->load->view('warehouse/detail_part_masuk', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_part_keluar_wo->deleteDetail($id);
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
        $jumlah = $_POST['jumlah'];
        $jml_keluar = $_POST['jml_keluar'];
		$data['dataPo'] = $this->Mod_part_keluar_wo->update_part($id,$jumlah,$jml_keluar);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function deletepartDetail()
	{
		$id = $_POST['id'];
		$sisa = $_POST['sisa'];
		$result = $this->Mod_part_keluar_wo->deletepartDetail($id,$sisa);
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
		$data['dataMasuk'] = $this->Mod_part_keluar_wo->select_by_id($id);
		$data['detailMasuk'] = $this->Mod_part_keluar_wo->select_detail_cetak($id);

		echo show_my_print('warehouse/modals/modal_cetak_part_masuk', 'cetak-masuk', $data, ' modal-xl');
	}
}
