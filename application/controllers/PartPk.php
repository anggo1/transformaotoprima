<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PartPk extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_partpk', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Part Keluar Dengan PK";
		$data['judul'] 		= "Detail PK";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'warehouse/part_pk', $data);
    }
    public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_partpk->get_datatables();
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
			$row[] = $pel->std_pakai;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_partpk->count_all(),
			"recordsFiltered" => $this->Mod_partpk->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function prosesKeluarDetail()
	{
		$data 	= $this->input->post();
				$kode_keluar = $data['kode_keluar'];
				$data['dataKeluar'] = $this->Mod_partpk->insertDetail_temp($data);
				if ($data > 0) {
					$out['dataKeluar'] = $kode_keluar;
					$out['status'] = '';
				} else {
					$out['status'] = '';
					$out['msg'] = show_del_msg('Filed !', '20px');
				}
			echo json_encode($out);
	}
    public function prosesKeluar()
	{
		
		$this->form_validation->set_rules('id_pk', 'ID PK', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			$sekarang = date('Y-m-d');

			$data = array(
				'kode_keluar' 	=> $data['kode_awal'],
				'id_keluar' 	=> $data['kode_keluar'],
				'tgl_keluar' 	=> $sekarang,
				'tujuan' 		=> 'SPK',
				'status' 		=> $data['status_part'],
				'no_spk' 		=> $data['id_lapor'],
				'no_pk' 		=> $data['id_pk'],
				'ket_pk' 		=> $data['ket_pk'],
				'no_body' 		=> $data['no_body'],
				'user'   		=> $data['user']
			);
				$data['dataKeluar'] = $this->db->insert('tbl_wh_part_keluar', $data);
			if ($result > 0) {
				$out['dataKeluar'] = $data['kode_keluar'];
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
	public function updateKet()
	{
        $id = $_POST['id'];
        $keterangan = $_POST['keterangan'];
		$data['dataPo'] = $this->Mod_partpk->update_ket($id,$keterangan);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
    public function updateJumlah()
	{
        $id_spk = $_POST['id_spk'];
        $id = $_POST['id'];
        $jumlah = $_POST['jumlah'];
        $status_part = $_POST['status_part'];
        $no_part = $_POST['no_part'];
        $std_pakai = $_POST['std_pakai'];
		$ci = get_instance();
        $query = "SELECT b.no_part,SUM(b.jumlah) AS total
					FROM `tbl_wh_part_keluar` AS a
					LEFT JOIN `tbl_wh_detail_part_keluar` AS b ON b.id_keluar=a.id_keluar 
					WHERE a.no_spk='{$id_spk}' AND  b.no_part='{$no_part}'  GROUP BY b.no_part";
        $d_data = $ci->db->query($query)->row_array();
		$total_pakai ="";

		if($jumlah > $std_pakai){
			$out['msg'] = 'Error';
		}
		if(!empty($d_data)){			
        $total_pakai    = $d_data['total'];
		$validasi_total =$total_pakai+$jumlah;
			if($validasi_total > $std_pakai){
				$out['msg'] = 'Error';
		}
	}
		if(empty($d_data)&&($jumlah > $std_pakai)){
			$out['msg'] = 'Error';
		}else{
			$data['dataPo'] = $this->Mod_partpk->update_jumlah($id,$jumlah,$no_part,$status_part);
                $out['msg'] = 'ok';
		}
        echo json_encode($out);
	}
	public function updateJumlahOver()
	{
        $id = $_POST['id'];
        $jumlah = $_POST['jumlah'];
        $status_part = $_POST['status_part'];
        $no_part = $_POST['no_part'];

		$data['dataPo'] = $this->Mod_partpk->update_jumlah($id,$jumlah,$no_part,$status_part);

	}

	public function tampilDetail()
	{
		$id 				= $_GET['id_keluar'];
		$data['dataDetail'] = $this->Mod_part_keluar->select_detail($id);
		$this->load->view('warehouse/detail_part_keluar', $data);
	}
    public function showPk()
    {
        $data['dataPk'] = $this->Mod_partpk->select_pk();
        $this->load->view('warehouse/data_part_pk', $data);
    }
    public function cetakPk()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_partpk->cetak_pk($id);

		echo show_my_print('warehouse/modals/modal_cetak_pk_tunggal', 'cetak-pk', $data, ' modal-xl');
	}
	public function deletepartDetail()
	{
		$id = $_POST['id'];
		$no_part = $_POST['no_part'];
		$jumlah = $_POST['jumlah'];
		$status_part = $_POST['status_part'];
		$data['dataPart']  = $this->Mod_partpk->deletepartDetail($id,$no_part,$jumlah,$status_part);
	}
    public function cetakDetail()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_partpk->cetak_part_pk($id);
		$data['dataDetail'] = $this->Mod_partpk->cetak_detail_part($id);
		$data['dataSum'] = $this->Mod_partpk->cetak_detail_sum($id);

		echo show_my_print('warehouse/modals/modal_cetak_part_pk', 'cetak-detail', $data, ' modal-xl');
	}
    /*Keterangan Laporan*/
    public function prosesTlaporan()
    {
        $this->form_validation->set_rules('kode', 'Kode Laporan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_settingbr->insertLapor($data);

            if ($result > 0) {
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
    public function Part()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_partpk->cari_pk($id);

        echo show_my_modal('warehouse/modals/modal_part_pk', 'part-pk', $data, ' modal-xl');
    }
	public function cariPart($id)
	{
		$data = $this->Mod_partpk->get_part($id);
		echo json_encode($data);
	}

    public function partPk()
    {

        $this->form_validation->set_rules('id_pk', 'ID PK', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_partpk->pausepk($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_del_msg('Filed!', '20px');
            }
        } else {
            $out['status'] = 'form';
            $out['msg'] = show_err_msg(validation_errors());
        }

        echo json_encode($out);
    }
  

    public function Detail()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_partpk->cetak_pk($id);

        echo show_my_modal('warehouse/modals/modal_part_pk', 'part-pk', $data, ' modal-md');
    }
    public function showDetail()
    {
        $id                 = $_GET['id_keluar']   ;
        $data['dataDetail'] = $this->Mod_partpk->select_detail($id);
        $this->load->view('warehouse/detail_part_pk', $data);
    }
    public function detailPk()
    {

        $this->form_validation->set_rules('id_pk', 'ID PK', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_partpk->detailpk($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_del_msg('Filed!', '20px');
            }
        } else {
            $out['status'] = 'form';
            $out['msg'] = show_err_msg(validation_errors());
        }

        echo json_encode($out);
    }
    public function cetakBon()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_partpk->cetak_pk_bon($id);
		$data['dataDetail'] = $this->Mod_partpk->cetak_bon($id);

		echo show_my_print('warehouse/modals/modal_cetak_bon', 'cetak-bon', $data, ' modal-xl');
	}
}