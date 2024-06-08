<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportWhPerbarang extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_reportwhpo', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Report Per Barang";
		$data['judul'] 		= "List Barang";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'warehouse/report_wh/part_perbarang', $data);
    }

    public function listPart() {
        $date1 				= $_GET['date1'];
        $date2 				= $_GET['date2'];
		$tgl1 = explode('-',$date1);
		$ttmp1 = $tgl1[2]."-".$tgl1[1]."-".$tgl1[0]."";
        
		$tgl2 = explode('-',$date2);
		$ttmp2 = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        $no_part        = trim($_GET['no_part']);
		$data['dataPart'] = $this->Mod_reportwhpo->cari_part($no_part,$ttmp1,$ttmp2); 
        $this->load->view('warehouse/report_wh/data_perbarang', $data);
	}
    public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_reportwhpo->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pel) {
			$no++;
			$row = array();
			$row[] = '<i class="fa fa-check"></i>';
			$row[] = $pel->no_part;
			$row[] = $pel->nama_part;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_reportwhpo->count_all(),
			"recordsFiltered" => $this->Mod_reportwhpo->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
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