<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BusKeluar extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('body_repair_model/Mod_buskeluar', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Bus Selesai";
		$data['judul'] 		= " Bus Keluar";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'body_repair/bus_keluar', $data);
    }


    public function showPk()
    {
        $data['dataPk'] = $this->Mod_buskeluar->select_pk();
        $this->load->view('body_repair/bus_keluar_list_data', $data);
    }
    public function cetakPk()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);
		$data['detailPk'] = $this->Mod_buspk->cetak_estimasi($id);

		echo show_my_print('body_repair/modals/modal_cetak_pk', 'cetak-pk', $data, ' modal-xl');
	}
    /*Keterangan Laporan*/
   
    public function tutupPk()
    {
        $id = $_POST['id'];
        $result = $this->Mod_buskeluar->pkSelesai($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_ok_msg('PK Telah Selesai', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function cetakBus()
	{
		$data['dataPk'] = $this->Mod_buskeluar->select_pk(); 
		echo show_my_print('body_repair/modals/modal_cetak_bus_keluar', 'cetak-pk', $data, ' modal-xl');
	}

    public function tampilPk()
	{
        $id = $_GET['id_lapor'];
		$data['dataPk'] = $this->Mod_buspk->cetak_pk($id);
		$this->load->view('body_repair/detail_tambah_pk', $data);
	}
}