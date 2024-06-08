<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UpdateHpart extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_update_hpart', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
        $this->load->library('ciqrcode');
    }

    public function index()
    {
		$data['page'] 		= "Update Harga Barang";
		$data['judul'] 		= "Harga Barang";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_update_hpart->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_update_hpart->select_by_level($idlevel, $id_sub);
        
		//echo show_my_modal('warehouse/modals/modal_tambah_part', 'tambah-sparepart', $data, ' modal-lg');
        $this->template->load('layoutbackend', 'warehouse/update_harga_part', $data);
    }

    public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_update_hpart->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_update_hpart->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_update_hpart->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->no_part;
                $row[] = $p->nama_part;
                $row[] = $p->stok;
                $row[] = number_format($p->hrg_awal);
                $row[] = number_format($p->hrg_1);
                $row[] = number_format($p->hrg_2);
                $row[] = $p->satuan;
                $row[] = $p->kelompok;
                $row[]='
                    <button class="btn btn-sm btn-outline-primary update-harga" title="Edit" data-id="'.$p->id_barang.'"><i class="fa fa-edit"> Update Harga</i>
                    </button>';
                
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_update_hpart->count_all(),
            "recordsFiltered" => $this->Mod_update_hpart->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function cetak_label() {
        
		$id 				= $_POST['id'];
        $data['dataPart'] = $this->Mod_update_hpart->cetak_label($id);
        
	echo json_encode($data);
        //$data['dataqr'] = 'testah';
        //$this->ciqrcode->generate($data);

        echo show_my_print('warehouse/modals/modal_cetak_label', 'cetak-label', $data, ' modal-lg');
	}

    public function updateHarga() {
		$id 				= trim($_POST['id']);
		$data['dataPart'] = $this->Mod_update_hpart->select_by_id_part($id);

		echo show_my_modal('warehouse/modals/modal_harga_part', 'update-harga', $data, ' modal-lg');
	}

	public function prosesUharga() {
		
		$this->form_validation->set_rules('hrg_awal', 'Harga', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_update_hpart->updateHarga($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_ok_msg('Data Berhasil diupdate', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Data Gagal diupdate', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

    public function download()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Submenu');
        $sheet->setCellValue('C1', 'Link');
        $sheet->setCellValue('D1', 'Icon');
        $sheet->setCellValue('E1', 'Menu');
        $sheet->setCellValue('F1', 'Is Active');

        $menu = $this->Mod_submenu->getAll()->result();
        $no = 1;
        $x = 2;
        foreach ($menu as $row) {
            $sheet->setCellValue('A' . $x, $no++);
            $sheet->setCellValue('B' . $x, $row->nama_submenu);
            $sheet->setCellValue('C' . $x, $row->link);
            $sheet->setCellValue('D' . $x, $row->icon);
            $sheet->setCellValue('E' . $x, $row->nama_menu);
            $sheet->setCellValue('F' . $x, $row->is_active);
            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-Submenu';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
