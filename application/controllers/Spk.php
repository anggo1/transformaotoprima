<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spk extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('marketing_model/Mod_spk'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Surat Pesanan Kendaraan";
		$data['judul'] 		= "SPK";
		$this->load->helper('url');
        echo show_my_modal('marketing/modals/modal_keterangan', 'tambah-keterangan', $data);
		$this->template->load('layoutbackend', 'marketing/spk', $data);
	}
public function showPart()
    {
		$sup = $_GET['sup'];
        $data['dataDetail'] = $this->Mod_spk->select_part($sup);
        $this->load->view('marketing/data_part_with_supplier', $data);
    }
	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_spk->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pel) {
			$no++;
			$row = array();
			$row[] = "<button class='btn btn-sm btn-outline-success' onClick=s=selectPart('$pel->id_part')>$no</button>";
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
			"recordsTotal" => $this->Mod_spk->count_all(),
			"recordsFiltered" => $this->Mod_spk->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cariKode($id)
	{
		$data = $this->Mod_spk->get_part($id);
		echo json_encode($data);
	}
	public function prosesDetailPo()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_spk->insertDetailPo($data);
		
		echo json_encode($data);
	}
	public function updateDiskon()
	{
        $id = $_POST['id'];
        $diskon = $_POST['diskon'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_spk->update_detailDiskon($id,$diskon,$hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateDetailPo()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_spk->update_detailPo($id,$jml_part,$hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateKeterangan()
	{
        $id = $_POST['id'];
        $remark = $_POST['remark'];
		$data['dataPo'] = $this->Mod_spk->update_remark($id,$remark);
	}
	public function tambahKeterangan()
	{
        $id = $_POST['id'];
        $no_spk = $_POST['no_spk'];
        $keterangan = $_POST['keterangan'];
		$data['dataPo'] = $this->Mod_spk->insertKEterangan($id,$no_spk,$keterangan);
	}
	public function tambahNote()
	{
        $id = $_POST['id'];
		$data['dataPo'] = $this->Mod_spk->insertNote($id);
	}
	public function prosesSpk()
	{
		
		$sekarang= date("Y-m");
		$this->form_validation->set_rules('nama_pemesan', 'Nama Pemesan', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			
			$hrg_ofr=$data['hrg_off_the_road'];
			$ofr =str_replace(",","", $hrg_ofr);
			$bbn=$data['biaya_bbn'];
			$h_bbn =str_replace(",","", $bbn);
			$hrg_otr=$data['hrg_on_the_road'];
			$otr =str_replace(",","", $hrg_otr);
			
			$t_1=$data['hrg_tambahan_1'];
			$tambah1 =str_replace(",","", $t_1);
			
			$t_2=$data['hrg_tambahan_2'];
			$tambah2 =str_replace(",","", $t_2);
			
			$t_3=$data['hrg_tambahan_3'];
			$tambah3 =str_replace(",","", $t_3);
			
			$t_4=$data['hrg_tambahan_4'];
			$tambah4 =str_replace(",","", $t_4);
			
			$hjp=$data['hrg_jual_perunit'];
			$perunit =str_replace(",","", $hjp);
			
			$thp=$data['total_harga_jual'];
			$total_harga =str_replace(",","", $thp);

			$no_spk1 = $data['no_ref'];
			$ciri = $data['kode'];
			if(empty($ciri)){
				$no_spk = $no_spk1;
			}else{
				$no_spk= $no_spk1.'-'.$ciri;
			}

			$data = array(
				'no_urut'  	=> $data['no_urut'],
				'no_spk'   => $no_spk,
				'tgl_spk'  	=> date("Y-m-d"),
				'nama_pemesan'	=> $data['nama_pemesan'],
				'alamat_pemesan'	=> $data['alamat_pemesan'],
				'telp_pemesan' => $data['telp_pemesan'],
				'faktur_pajak' => $data['faktur_pajak'],
				'npwp_pemesan' => $data['npwp_pemesan'],
				'nama_npwp_pemesan' => $data['nama_npwp_pemesan'],
				'alamat_npwp' => $data['alamat_npwp'],
				'contact_person' => $data['contact_person'],
				'telp_contact_person' => $data['telp_contact_person'],
				'nama_bpkb' => $data['nama_bpkb'],
				'no_ktp' => $data['no_ktp'],
				'alamat_faktur' => $data['alamat_faktur'],
				'plat_kendaraan' => $data['plat_kendaraan'],
				'type_body' =>$data['type_body'],
				'jml_unit' => $data['jml_unit'],
				'kategori' => $data['kategori'],
				'type_kendaraan' => $data['type_kendaraan'],
				'warna_tahun' => $data['warna_tahun'],
				'hrg_off_the_road' => $ofr,
				'biaya_bbn' => $h_bbn,
				'hrg_on_the_road' => $otr,
				'tambahan_1' => $data['tambahan_1'],
				'hrg_tambahan_1' => $tambah1,
				'tambahan_2' => $data['tambahan_2'],
				'hrg_tambahan_2' => $tambah2,
				'tambahan_3' => $data['tambahan_3'],
				'hrg_tambahan_3' => $tambah3,
				'tambahan_4' => $data['tambahan_4'],
				'hrg_tambahan_4' => $tambah4,
				'hrg_jual_perunit' => $perunit,
				'total_hrg_jual' => $total_harga,
				'user'   	=> $data['user']
			);
				$data['dataPo'] = $this->db->insert('tbl_mk_spk', $data);
				$data 	= $this->input->post();
				
			if ($result > 0) {
				$out['dataRef'] = $data['no_urut'];
				$out['dataPo'] = $data['no_urut'];
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
		$data['dataDetail'] = $this->Mod_spk->select_detail($id);
		$this->load->view('marketing/detail_estimasi_penawaran', $data);
	}
	public function tampilKeterangan()
	{
		$id 				= $_POST['no_urut'];
		$data['dataKet'] = $this->Mod_spk->select_keterangan($id);
		$this->load->view('marketing/data_keterangan_spk', $data);
	}
	public function view()
    {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $data['table'] = $table;
            $data['data_field'] = $this->db->field_data($table);
            $data['dataDetail'] = $this->Mod_userlevel->view($id)->result_array();
            $this->load->view('marketing/detail_estimasi_penawaran', $data);
        
    }
	public function deleteKeterangan()
	{
		$id = $_POST['id'];
		$result = $this->Mod_spk->deleteDetail_spk($id);
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
		$data['dataSpk'] = $this->Mod_spk->select_by_id($id);
		$data['detailSpk'] = $this->Mod_spk->select_keterangan($id);

		echo show_my_print('marketing/modals/modal_cetak_spk', 'cetak-po', $data, ' modal-xl');
	}
	public function cetak_int()
	{
		$id 				= $_POST['id'];
		$data['dataPo'] = $this->Mod_spk->select_by_id($id);
		$data['detailPo'] = $this->Mod_spk->select_detail($id);

		echo show_my_print('marketing/modals/modal_cetak_spk_internal', 'cetak-po-int', $data, ' modal-xl');
	}
}