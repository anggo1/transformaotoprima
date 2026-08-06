<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_keluar extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('warehouse/Mod_part_keluar'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Barang Keluar Non PO";
		$data['judul'] 		= "Barang Keluar";
		$this->load->helper('url');
		$data['dataSupplier'] = $this->Mod_part_keluar->select_supplier();
        $data['dataKategori'] = $this->Mod_part_keluar->select_kategori();
        $data['dataKota'] = $this->Mod_part_keluar->get_kota();
		$this->template->load('layoutbackend', 'warehouse/part_keluar', $data);
	}

	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_part_keluar->get_datatables();
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $p) {
			$no++;
			$row = array();
			$row[] = $p->id_part;
			$row[] = $p->no_part;
			$row[] = $p->nama_part;
			
			$row[] = $p->satuan;
			 if ($idlokasi =='Cibitung'){
                    $row[] = $p->stok_cbt;
                    $row[] = $p->hrg_net_cbt;
                    //$row[] = $p->lok_cbt;
                    $row[] = number_format($p->hrg_net_cbt);
                }elseif ($idlokasi=='Jakarta'){
                        $row[] = $p->stok_jkt;
                        $row[] = $p->hrg_net_jkt;
                        //$row[] = $p->lok_jkt;
                    $row[] = number_format($p->hrg_net_jkt);
                }elseif ($idlokasi=='Surabaya'){
                            $row[] = $p->stok_sby;
                            $row[] = $p->hrg_net_sby;
                            //$row[] = $p->lok_sby;
                    		$row[] = number_format($p->hrg_net_sby);
							}
			$row[] = $p->id_part;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_part_keluar->count_all(),
			"recordsFiltered" => $this->Mod_part_keluar->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cariKode($id)
	{
		$data = $this->Mod_part_keluar->get_part($id);
		echo json_encode($data);
	}
	public function prosesDetailInput()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_part_keluar->insert_part($data);
		
		echo json_encode($data);
	}
	public function updateHarga()
	{
        $id = $_POST['id'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPart'] = $this->Mod_part_keluar->update_harga($id,$hrg_part);
	}
	public function updateJumlah()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
		$data['dataPart'] = $this->Mod_part_keluar->update_jml($id,$jml_part);
	}
	public function prosesKeluar()
	{
        $idlokasi = $this->session->userdata['lokasi'];
		$this->form_validation->set_rules('tgl_keluar', 'Tanggal Keluar', 'trim|required');
		$data 	= $this->input->post();
		$kode_keluar = $data['id_keluar'];
		//$no_keluar = $data['id_keluar'];
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			$date2 = $data['tgl_keluar'];
			$tgl2 = explode('-', $date2);
			$tgl_fix = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
			$sekarang = date('Y/m/d');
			
			$divisi = $data['divisi'];	
			$id_div = '';			
			$nama_div='';
			if(!empty($divisi)){
				$divisi = $data['divisi'];
				$div = explode('|', $divisi);
				$id_div = $div[0];
				$nama_div = $div[1];
			}
				$data = array(
				'kode_keluar' 	=> $data['kode_keluar'],
				'id_keluar' 	=> $data['id_keluar'],
				'tgl_keluar' 	=> $tgl_fix,
				'tujuan'		=> $data['tujuan'],
				'lokasi'		=> $idlokasi,
				'no_spk'		=> "NON",
				'keterangan'	=> $data['ket_surat'],
				'user'   		=> $data['user'],
				'no_po_cus'   	=> $data['no_po_cus'],
				'alamat'   		=> $data['alamat'],
				'divisi'   		=> $id_div,
				'nama_divisi'   => $nama_div
			);
		
				$data['dataKeluar'] = $this->db->insert('tbl_wh_part_keluar', $data);
				$data 		= $this->input->post();
				$no_part 	= $this->input->post('no_part');
				$nama_part 	= $this->input->post('nama_part');
				$qty_keluar = $this->input->post('qty_keluar');
				$this->Mod_part_keluar->insertGlobal($kode_keluar, $data,$no_part,$nama_part,$qty_keluar);
				
			if ($result > 0) {
				$out['dataKeluar'] = $kode_keluar;
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
		$id 				= $_GET['id_keluar'];
		$data['dataDetail'] = $this->Mod_part_keluar->select_detail($id);
		$this->load->view('warehouse/detail_part_keluar', $data);
	}
	public function tampilDetailCache()
	{
		$id 				= $_GET['id_keluar'];
		$data['dataDetail'] = $this->Mod_part_keluar->select_detail($id);
		$this->load->view('warehouse/detail_part_keluar_cache', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$stok = $_POST['stok'];
		$status = $_POST['status'];
		$kodePart = $_POST['kodePart'];
		$result = $this->Mod_part_keluar->deleteDetail_keluar($id,$stok,$status,$kodePart);
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
		$id = $_POST['id'];
		$data['dataPo']  = $this->Mod_part_keluar->deletepartDetail($id);
	}
	public function cetak()
	{
		$id 				= $_POST['id'];
		$data['dataPart'] = $this->Mod_part_keluar->select_by_id($id);
		$data['detailPart'] = $this->Mod_part_keluar->select_detail($id);

		echo show_my_print('warehouse/modals/modal_cetak_part', 'cetak-keluar', $data, ' modal-xl');
	}
	public function cetak_bon()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_part_keluar->select_by_id($id);
		$data['dataDetail'] = $this->Mod_part_keluar->select_detail($id);

		echo show_my_print('warehouse/modals/modal_cetak_bon_npk', 'cetak-bon-keluar', $data, ' modal-xl');
	}
}
