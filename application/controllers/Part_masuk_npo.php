<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_masuk_npo extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('warehouse/Mod_part_masuknpo'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Barang Masuk";
		$data['judul'] 		= "Input Stok";
		$this->load->helper('url');
        $data['dataSup'] = $this->Mod_part_masuknpo->get_sup();
        $data['dataKota'] = $this->Mod_part_masuknpo->get_kota();
		$this->template->load('layoutbackend', 'warehouse/part_masuk_non_po',$data);
	}
	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
        $idlokasi = $this->session->userdata['lokasi'];
        $idlevel = $this->session->userdata['id_level'];
		$list = $this->Mod_part_masuknpo->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $p) {
			$no++;
			$row = array();
			$row[] = "<a onclick=selectPart('$p->id_part')>$no</a>";
			$row[] = $p->no_part;
			$row[] = $p->nama_part;
			$row[] = $p->kode_satuan;
			$row[] = $p->id_part;
			 if (($idlevel !='12') && $idlokasi =='Cibitung'){
                    $row[] = $p->stok_cbt;
                    //$row[] = $p->lok_cbt;
                    $row[] = number_format($p->hrg_net_cbt);
                }elseif (($idlevel !='12') && $idlokasi=='Jakarta'){
                        $row[] = $p->stok_jkt;
                        //$row[] = $p->lok_jkt;
                    $row[] = number_format($p->hrg_net_jkt);
                }elseif (($idlevel !='1' or $idlevel !='12') && $idlokasi=='Surabaya'){
                            $row[] = $p->stok_sby;
                            //$row[] = $p->lok_sby;
                    		$row[] = number_format($p->hrg_net_sby);
							}
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_part_masuknpo->count_all(),
			"recordsFiltered" => $this->Mod_part_masuknpo->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cariKode($id)
	{
	$data = $this->Mod_part_masuk_npo->get_part($id);
	echo json_encode($data);
	}
	public function prosesDetailInput()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_part_masuknpo->insert_part($data);
		
		echo json_encode($data);
	}
	public function updateHarga()
	{
        $id = $_POST['id'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPart'] = $this->Mod_part_masuknpo->update_harga($id,$hrg_part);
	}
	public function updateJumlah()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
		$data['dataPart'] = $this->Mod_part_masuknpo->update_jml($id,$jml_part);
	}
	public function showPart()
    {
		$po = $_GET['no_po'];
		//if(!empty($po)){
        $data['dataPo'] = $this->Mod_part_masuk->select_part_nopo();
        $this->load->view('warehouse/detail_po_part', $data);
		//}else{
		//$data['dataPart'] = $this->Mod_part_masuk->select_part_nonpo();
        //$this->load->view('warehouse/data_po_partmasuk', $data);
		//}
    }
	public function prosesPartmasuk()
	{
		$tgl_masuk = date("y-m-d");


		$this->form_validation->set_rules('date1', 'Tanggal Masuk', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();

			$lokasi = $this->input->post('lokasi');				
			$kl = explode('|',$lokasi);
			$kd_lok = $kl[0];
			$nm_lok = $kl[1];

			$idnye=$data['kode_masuk'];
			$kode_masuk  = $data['id_masuk'];
			$ret=""; if (!empty($data['return'])){ 
				$ret=$data['return']; }else { $ret='N'; }
			$data = array(
				'kode_masuk'  	=> $data['kode_masuk'],
				'id_masuk'  	=> $data['id_masuk'],
				'tgl_masuk'  	=> $tgl_masuk,
				'status'      	=> $kd_lok ,
				'keterangan'  	=> $data['keterangan'],
				'status_po'		=> 'N',
				'no_sj_sup'		=> $data['no_sj_sup'],
				'no_inv_sup'	=> $data['no_inv_sup'],
				'kode_sup'		=> $data['supplier'],
				'lokasi'   		=> $nm_lok,
				'user'   		=> $data['user'],
				'part_return'	=> $ret
			);
				$data['dataPo'] = $this->db->insert('tbl_wh_part_masuk', $data);
				$data 		= $this->input->post();
				$no_part 	= $this->input->post('no_part');
				$nama_part 	= $this->input->post('nama_part');
				$qty_masuk 	= $this->input->post('qty_masuk');
				$stok 		= $this->input->post('stok');
				$stok_jkt = $this->input->post('stok_jkt');
				$stok_cbt = $this->input->post('stok_cbt');
				$stok_sby = $this->input->post('stok_sby');
				$lokasi = $this->input->post('lokasi');
				$kl = explode('|',$lokasi);
				$kd_lok = $kl[0];
				$nm_lok = $kl[1];
				//$kode_po=$data['id_po'];
				$this->Mod_part_masuknpo->insert_global($kode_masuk, $data,$no_part,$nama_part,$qty_masuk,$stok,$stok_jkt,$stok_cbt,$stok_sby,$kd_lok,$nm_lok);
			if ($result > 0) {
				$out['dataPo'] = $idnye;
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
		$id 				= $_GET['kode_masuk'];
		$data['dataDetail'] = $this->Mod_part_masuknpo->select_detail($id);
		$this->load->view('warehouse/detail_part_masuknpo', $data);
	}
	public function tampilDetailCache()
	{
		$id 				= $_GET['kode_masuk'];
		$data['dataDetail'] = $this->Mod_part_masuknpo->select_detail($id);
		$this->load->view('warehouse/detail_part_masuknpo_cache', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_part_masuk_npo->deleteDetail($id);
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
	public function deletepartDetail()
	{
		$id = $_POST['id_detail'];
		$data['dataPo']  = $this->Mod_part_masuknpo->deletepartDetail($id);
	}

	public function cetak()
	{
		$id 				= $_POST['id'];
		$data['dataMasuk'] = $this->Mod_part_masuknpo->select_by_id($id);
		$data['detailMasuk'] = $this->Mod_part_masuknpo->select_detail_cetak($id);

		echo show_my_print('warehouse/modals/modal_cetak_part_masuknpo', 'cetak-masuknpo', $data, ' modal-xl');
	}
}
