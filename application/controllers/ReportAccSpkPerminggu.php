<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportAccSpkPerminggu extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_spkperminggu', 'Mod_menu'));
        $this->load->model(array('body_repair_model/Mod_buspk'));
        $this->load->model(array('body_repair_model/Mod_busmasuk'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
        $this->load->library('ciqrcode');
    }

    public function index()
    {
		$data['page'] 		= "Report Detail SPK Perminggu";
		$data['judul'] 		= "Report Mingguan";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'accounting/spk_pertanggal', $data);
    }

    public function listPk() {
        $date1 				= $_GET['date1'];
		$tanggal = explode(' / ',$date1);
		$tawal = $tanggal[0]."";
		$takhir = $tanggal[1]."";
        
		$tgl1 = explode('-',$tawal);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
		$tgl2 = explode('-',$takhir);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        
		$data['dataPk'] = $this->Mod_spkperminggu->cari_pk($ttmp1,$ttmp2); 
        $this->load->view('accounting/data_spk_pertanggal', $data);
	}
	public function CetaklistPk() {
        $date1 				= $_POST['date1'];
		$tanggal = explode(' / ',$date1);
		$tawal = $tanggal[0]."";
		$takhir = $tanggal[1]."";
        
		$tgl1 = explode('-',$tawal);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
		$tgl2 = explode('-',$takhir);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        
		$data['dataPk'] = $this->Mod_spkperminggu->cari_pk($ttmp1,$ttmp2); 
		echo show_my_print('accounting/report/modals/modal_cetak_list_pk', 'cetak-list-pk-data', $data, ' modal-xl');
	}
    public function listKeluarDetail() {
        $date1 				= $_GET['date1'];
		$tanggal = explode(' / ',$date1);
		$tawal = $tanggal[0]."";
		$takhir = $tanggal[1]."";
        
		$tgl1 = explode('-',$tawal);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
		$tgl2 = explode('-',$takhir);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['detailKeluar'] = $this->Mod_spkperminggu->cari_keluar_detail($ttmp1,$ttmp2);
            $this->load->view('accounting/report/data_part_keluar_po_detail', $data);
	}
	public function cetak_listKeluarDetail() {
        $date1 				= $_POST['date1'];
		$tanggal = explode(' / ',$date1);
		$tawal = $tanggal[0]."";
		$takhir = $tanggal[1]."";
        
		$tgl1 = explode('-',$tawal);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
		$tgl2 = explode('-',$takhir);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$data['detailKeluar'] = $this->Mod_spkperminggu->cari_keluar_detail($ttmp1,$ttmp2);
		echo show_my_print('accounting/report/modals/modal_detail_data_spk_pertanggal', 'cetak-detail-pk', $data, ' modal-xl');
	}
    public function cetakPk()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_spkperminggu->cetak_pk($id);
		$data['detailPk'] = $this->Mod_spkperminggu->cetak_estimasi($id);

		echo show_my_print('accounting/modals/modal_cetak_pk_report', 'cetak-pk', $data, ' modal-xl');
	}
    public function cetakEstimasi()
	{
		$id 				= $_POST['id'];
		$data['dataPk'] = $this->Mod_busmasuk->cetak_masuk($id);
		$data['detailPk'] = $this->Mod_busmasuk->cetak_estimasi($id);
		$data['hargaEstimasi'] = $this->Mod_busmasuk->harga_estimasi($id);

		echo show_my_print('accounting/modals/modal_cetak_estimasi', 'cetak-estimasi', $data, ' modal-xl');
	}
    public function Detail()
    {
        $id                 = trim($_POST['id']);
		$data['dataPk'] = $this->Mod_spkperminggu->cetak_pk($id);

        echo show_my_modal('accounting/modals/modal_part_pk', 'part-pk', $data, ' modal-md');
    }
   
}