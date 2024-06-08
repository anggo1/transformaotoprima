<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BusMasuk extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
        $this->load->model(array('body_repair_model/Mod_busmasuk'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Bus Perbaikan";
		$data['judul'] 		= "Bus Masuk";
		$this->load->helper('url');
        $data['dataBody'] = $this->Mod_busmasuk->get_body();
        $data['dataLap'] = $this->Mod_busmasuk->select_laporan();
        $data['dataKat'] = $this->Mod_busmasuk->select_kategori();
        $data['dataPk'] = $this->Mod_busmasuk->select_pk();
        $this->template->load('layoutbackend','body_repair/bus_masuk',$data);
	}

    public function ajax_list()
    {
		$link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_busmasuk->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $bd) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $bd->no_body;
            $row[] = $bd->no_pol;
            $row[] = $bd->nip_sp.'&nbsp'.$bd->nama_sp;
            $row[] = $bd->kode;
            $row[] = $bd->kategori;
            $row[] = tglIndoSedang($bd->tgl_masuk);
            $row[] = $bd->jam_masuk;
            $row[] = $bd->keterangan;
            if($bd->estimasi=='N'){
                $row[] = 
                '<button class="btn btn-xs bg-gradient-success estimasi" title="Proses Estimasi" no_body="'.$bd->no_body.'" id_lapor="'.$bd->id_lapor.'"><i class="fa fa-chalkboard-teacher"></i> Estimator
                </button></a>
                <button class="btn btn-xs bg-gradient-danger delete-laporan" title="Delete" data-toggle="modal" data-target="#hapusLaporan" data-id="'.$bd->id_bast.'"><i class="fa fa-times"></i>  Batal
                </button>';
            } if($bd->estimasi=='Y'){
                $row[] = 
                '</a><button class="btn btn-xs bg-gradient-primary proses-pk" title="Proses PK" data-id="'.$bd->id_lapor.'"><i class="fa fa-chalkboard"></i> Proses PK
                </button>
                <button class="btn btn-xs bg-gradient-warning cetak-estimasi" title="Cetak Estimasi" data-id="'.$bd->id_lapor.'"><i class="fa fa-print"></i> Cetak Estimasi
                </button>
                <button class="btn btn-xs bg-gradient-gray-dark delete-proses" title="Delete" data-toggle="modal" data-target="#hapusProses" data-id="'.$bd->id_lapor.'"><i class="fa fa-trash"></i>  Hapus
                </button>';
            }
                
            
            $data[] = $row;
        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_busmasuk->count_all(),
                        "recordsFiltered" => $this->Mod_busmasuk->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    public function cariKode($id)
	{
		$data = $this->Mod_busmasuk->get_part($id);
		echo json_encode($data);
	}
    public function showBast()
    {
        $data['dataBody'] = $this->Mod_busmasuk->get_body();
        $this->load->view('body_repair/data_bast_bus_masuk', $data);
    }
    public function prosesLaporan()
    {
        $this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'trim|required');
        $this->form_validation->set_rules('no_body', 'No Body', 'trim|required');

        $data=$this->input->post();
        if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_busmasuk->insertLaporan($data);

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
    public function deleteLaporan()
    {
        $id = $_POST['id'];
        $result = $this->Mod_busmasuk->deleteLapor($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    //Estimator//
    public function prosesEstimasi()
    {
        $this->form_validation->set_rules('tgl_estimasi', 'Tanggal Estimasi', 'trim|required');

        $data=$this->input->post();
        if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_busmasuk->insertEstimasi($data);

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
    public function updateEstimasi()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataEstimasi'] = $this->Mod_busmasuk->update_estimasi($id,$hrg_part,$jml_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
    public function tampilEstimasi()
	{
        $id = $_GET['id_lapor'];
		$data['dataEstimasi'] = $this->Mod_busmasuk->select_estimasi($id);
		$this->load->view('body_repair/detail_estimasi', $data);
	}
    public function tampilPk()
	{
        $id = $_GET['id'];
		$data['dataPk'] = $this->Mod_busmasuk->select_proses_pk($id);
		$this->load->view('body_repair/list_pk', $data);
	}
    public function tampilPkMulai()
	{
        $id = $_GET['id_lapor'];
		$data['dataMulai'] = $this->Mod_busmasuk->select_pk_mulai($id);
		$this->load->view('body_repair/list_pk_mulai', $data);
	}
    public function cariPKproses()
	{
        $id = $_POST['id'];
        $kode = $_POST['kode'];
		$data['dataPk'] = $this->Mod_busmasuk->cari_pk($id,$kode);
		echo show_my_modal('body_repair/modals/modal_proses_pk', 'proses-pk', $data, ' modal-md');
	}
    public function deleteEstimasi()
    {
        $id = $_POST['id'];
        $result = $this->Mod_busmasuk->deleteEstimasi($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function deleteProses()
    {
        $id = $_POST['id'];
        $result = $this->Mod_busmasuk->deleteProses($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function Estimasi()
	{
		$data['page'] 		= "Estimasi Perbaikan Bus";
		$data['judul'] 		= "Estimator";
		$this->load->helper('url');        
        $id = $_POST['id_lapor'];
        $data['dataPk'] = $this->Mod_busmasuk->select_pk();
        $this->template->load('layoutbackend','body_repair/estimator',$data);
	}
    public function cetakEstimasi()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_busmasuk->cetak_masuk($id);
		$data['detailPk'] = $this->Mod_busmasuk->cetak_estimasi($id);
		$data['hargaEstimasi'] = $this->Mod_busmasuk->harga_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_estimasi', 'cetak-estimasi', $data, ' modal-xl');
	}
    public function cetakEstimasi2()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_busmasuk->cetak_masuk($id);
		$data['detailPk'] = $this->Mod_busmasuk->cetak_estimasi($id);
		$data['hargaEstimasi'] = $this->Mod_busmasuk->harga_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_estimasi', 'cetak-estimasi2', $data, ' modal-xl');
	}
    public function cetakPk()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_busmasuk->cetak_pk($id);
		$data['detailPk'] = $this->Mod_busmasuk->cetak_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_pk', 'cetak-pk', $data, ' modal-xl');
	}
    public function inputPk()
    {
        $this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'trim|required');

        $data=$this->input->post();
        $id_lapor 				= $_POST['id_lapor'];
        if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_busmasuk->insertPk($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['datakode']=$id_lapor;
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

}