<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StokOpname extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('warehouse/Mod_stokopname'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Stok Opname";
		$data['judul'] 		= "stokopname";
		$this->load->helper('url');
		$data['dataSupplier'] = $this->Mod_stokopname->select_supplier();
		$data['dataKelompok'] = $this->Mod_stokopname->select_kelompok();
        $data['dataKota'] = $this->Mod_stokopname->get_kota();
		$this->template->load('layoutbackend', 'warehouse/stok_opname', $data);
	}
	public function showPart()
    {
		$sup = $_GET['sup'];
        $data['dataDetail'] = $this->Mod_stokopname->select_part($sup);
        $this->load->view('warehouse/data_part_with_supplier', $data);
    }
	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_stokopname->get_datatables();
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
			"recordsTotal" => $this->Mod_stokopname->count_all(),
			"recordsFiltered" => $this->Mod_stokopname->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cariKode($id)
	{
		$data = $this->Mod_stokopname->get_part($id);
		echo json_encode($data);
	}
	public function prosesDetailSO()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_stokopname->insertDetailSO($data);
		
		echo json_encode($data);
	}
	public function updateDetailSO()
	{
        $id = $_POST['id'];
        $stok_fisik = $_POST['stok_fisik'];
		$data['dataPo'] = $this->Mod_stokopname->update_detailSO($id,$stok_fisik);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function prosesOpname()
	{
		
        $this->form_validation->set_rules('tgl_opname', 'Tanggal Stok Opname', 'trim|required');

        $data=$this->input->post();		
		$kode_po = $data['id_opname'];

        if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_stokopname->insertOpname($data);

            if ($result > 0) {
				$out['dataOpname'] = $kode_po;
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
	public function tampilKelompok()
	{
		$id_kelompok 	= $_GET['id_kelompok'];
		$kl 			= explode('|', $id_kelompok);
		$id = $kl[0];
		$lokasi 	= $_GET['lokasi'];
		$lok 			= explode('|', $lokasi);
		$kode_lok = $lok[0];
		$data['dataDetail'] = $this->Mod_stokopname->select_part_group($id, $kode_lok);
		$this->load->view('warehouse/detail_opname_kelompok', $data);
	}
	public function tampilCabang()
	{
		$id_kelompok 	= $_GET['id_kelompok'];
		$kl 			= explode('|', $id_kelompok);
		$id = $kl[0];
		$lokasi 	= $_GET['lokasi'];
		$lok 			= explode('|', $lokasi);
		$kode_lok = $lok[0];
		$data['dataDetail'] = $this->Mod_stokopname->select_part_group_cabang($id, $kode_lok);
	}
	public function tampilKelompokUpdate()
	{
		$id = $_GET['id_opname'];
		$data['dataDetail'] = $this->Mod_stokopname->select_part_group_update($id);
		$this->load->view('warehouse/detail_opname_kelompok_update', $data);
	}
	public function tampilDetail()
	{
		$id 				= $_GET['id_po'];
		$data['dataDetail'] = $this->Mod_stokopname->select_detail($id);
		$this->load->view('warehouse/detail_stok_opname', $data);
	}
	public function tampilDetailCache()
	{
		$id 				= $_GET['id_po'];
		$data['dataDetail'] = $this->Mod_stokopname->select_detail($id);
		$this->load->view('warehouse/detail_so_cache', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_stokopname->deleteDetail_so($id);
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
		$data['dataPo'] = $this->Mod_stokopname->select_by_id($id);
		$data['detailPo'] = $this->Mod_stokopname->select_detail($id);

		echo show_my_print('warehouse/modals/modal_cetak_po', 'cetak-po', $data, ' modal-xl');
	}
}
