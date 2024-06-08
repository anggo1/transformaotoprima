<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportAccSpk extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_report_upah_borong', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Report Biaya SPK";
		$data['judul'] 		= "Report SPK";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'accounting/report/report_spk', $data);
    }

    public function listSPK() {
        $no_spk 				= $_GET['no_spk'];
        $data['dataPart'] = $this->Mod_report_upah_borong->cari_spk_part($no_spk);
        $this->load->view('accounting/report/report_spk_data', $data);
	}
    public function listSPKdetail() {
        $no_spk 				= $_GET['no_spk'];
        $data['dataPart'] = $this->Mod_report_upah_borong->cari_spk_detail($no_spk);
        $this->load->view('accounting/report/report_spk_data_detail', $data);
	}
    public function listSPKdetail_upah() {
        $no_spk 				= $_GET['no_spk'];
        $data['dataKop'] = $this->Mod_report_upah_borong->cari_spk_part($no_spk);
        $data['dataPart'] = $this->Mod_report_upah_borong->cari_spk_detail_upah($no_spk);
        $this->load->view('accounting/report/report_spk_data_detail_upah', $data);
	}
    public function cetak_listSPK()
	{
        $no_spk 				= $_GET['no_spk'];
        $data['dataKop'] = $this->Mod_report_upah_borong->cari_spk_part($no_spk);
        $data['dataPart'] = $this->Mod_report_upah_borong->cari_spk_part($no_spk);
        echo show_my_print('accounting/report/modals/modal_cetak_report_spk_data', 'cetak-detail', $data, ' modal-xl');
	}
    public function cetak_listSPKdetail()
	{
        $no_spk 				= $_GET['no_spk'];
        $data['dataKop'] = $this->Mod_report_upah_borong->cari_spk_part($no_spk);
        $data['dataPart'] = $this->Mod_report_upah_borong->cari_spk_detail($no_spk);
        echo show_my_print('accounting/report/modals/modal_cetak_report_spk_data_detail', 'cetak-detail', $data, ' modal-xl');
	}
    public function cetak_listSPKdetail_upah()
	{
        $no_spk 				= $_GET['no_spk'];
        $data['dataKop'] = $this->Mod_report_upah_borong->cari_spk_part($no_spk);
        $data['dataPart'] = $this->Mod_report_upah_borong->cari_spk_detail_upah($no_spk);
        echo show_my_print('accounting/report/modals/modal_cetak_report_spk_data_detail_upah', 'cetak-detail', $data, ' modal-xl');
	}

}