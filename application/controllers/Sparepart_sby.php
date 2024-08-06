<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Sparepart_sby extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_sparepart', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
        $this->load->library('ciqrcode');
    }

    public function index()
    {
		$data['page'] 		= "Data Barang";
		$data['judul'] 		= "Sparepart";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $data['dataSatuan'] = $this->Mod_sparepart->select_satuan();
        $data['dataType'] = $this->Mod_sparepart->select_type();
        $data['dataKategori'] = $this->Mod_sparepart->select_kategori();
        $data['dataKelompok'] = $this->Mod_sparepart->select_kelompok();
        $data['dataSupplier'] = $this->Mod_sparepart->select_supplier();

        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_sparepart->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_sparepart->select_by_level($idlevel, $id_sub);
        
		echo show_my_modal('warehouse/modals/modal_tambah_part', 'tambah-sparepart', $data, ' modal-lg');
        $this->template->load('layoutbackend', 'warehouse/sparepart_data_sby', $data);
    }
    public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_sparepart->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_sparepart->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_sparepart->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                
                //$stok1 = $p->stok_a;
                //$stok2 = $p->stok_a;
                //$min1  = $p->minstok_a;
                //$//min2  = $p->minstok_p;
                //$status_a ="";
                //$status_p ="";
                //$empty_stok ="";
                //if($p->stok <= 0){
                //    $empty_stok='<button class="tombol-danger Blink-warning empty-stok"><i class="fa fa-info-circle"></i>
                //    </button>';
                //}
                //if($min1 == $stok1){
                    $status_a='<button class="tombol-warning Blink-warning peringatan-a"><i class="fa fa-info-circle">A</i>
                    </button>';
                //}
                //if($min1 > $stok1){
                //    $status_a='<button class="tombol-danger Blink-danger bahaya-a" title="Cetak"><i class="fa fa-angry">A</i>
                //    </button>';
                //}

                //if($min2 == $stok2){
                //    $status_p='<button class="tombol-warning Blink-warning peringatan-p" title="Cetak"><i class="fa fa-info-circle">P</i>
                //    </button>';
                //}
                //if($min2 > $stok2){
                //   $status_p='<button class="tombol-danger Blink-danger bahaya-p" title="Cetak"><i class="fa fa-angry">P</i>
                //    </button>';
                //}
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->no_part;
                $row[] = $p->nama_part;
                $row[] = $p->satuan;
                if ($idlokasi=='Cibitung'){
                    $row[] = $p->stok_cbt;
                    $row[] = $p->lok_cbt;}
                    if ($idlokasi=='Jakarta'){
                        $row[] = $p->stok_jkt;
                        $row[] = $p->lok_jkt;}
                        if ($idlokasi=='Surabaya'){
                            $row[] = $p->stok_sby;
                            $row[] = $p->lok_sby;}                
                $row[] = $p->kode_sup;
                $row[] = $p->type;
                $row[] = $p->kategori;
                if($pel1->edit_level=="Y"){
                    $edit='                    
                    <button class="btn btn-sm btn-outline-success update-sparepart" title="Edit" data-id="'.$p->id_part.'"><i class="fa fa-edit"></i>
                    </button>';
                }                
                if($pel1->delete_level=="Y"){
                    $delete='
                    <button class="btn btn-sm btn-outline-danger delete-part" title="Delete" data-toggle="modal" data-target="#hapusPart" data-id="'.$p->id_part.'">
                    <i class="fa fa-trash"></i></button>';
                }
                if($pel1->upload_level=="Y"){
                    $upload='
                    <button class="btn btn-sm btn-outline-info update-stok" title="Edit" data-id="'.$p->id_part.'"><i class="fa fa-random"></i>
                    </button>
                    ';
                }
                if($pel1->delete_level=="N"){
                    $delete='';
                }
                if($pel1->edit_level=="N"){
                    $edit='';
                }
                if($pel1->upload_level=="N"){
                    $upload='';
                }
                $lainnya='<a href="#" onclick="testPrint('.$p->id_part.')">
                <button class="btn btn-sm btn-outline-primary" title="Cetak" data-cetak="'.$p->no_part.'" data-id="'.$p->id_part.'"><i class="fa fa-qrcode"></i>
                </button></a>';
                $akses_system=$lainnya.$edit.$delete.$upload;
                $row[] = $akses_system;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_sparepart->count_all(),
            "recordsFiltered" => $this->Mod_sparepart->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function ajax_list_sby()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_sparepart->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_sparepart->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_sparepart->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->no_part;
                $row[] = $p->nama_part;
                $row[] = $p->satuan;
                $row[] = $p->stok_sby;
                $row[] = $p->lok_sby;             
                $row[] = $p->kode_sup;
                $row[] = $p->type;
                $row[] = $p->kategori;
                if($pel1->edit_level=="Y"){
                    $edit='                    
                    <button class="btn btn-sm btn-outline-success update-sparepart" title="Edit" data-id="'.$p->id_part.'"><i class="fa fa-edit"></i>
                    </button>';
                }                
                if($pel1->delete_level=="Y"){
                    $delete='
                    <button class="btn btn-sm btn-outline-danger delete-part" title="Delete" data-toggle="modal" data-target="#hapusPart" data-id="'.$p->id_part.'">
                    <i class="fa fa-trash"></i></button>';
                }
                if($pel1->upload_level=="Y"){
                    $upload='
                    <button class="btn btn-sm btn-outline-info update-stok" title="Edit" data-id="'.$p->id_part.'"><i class="fa fa-random"></i>
                    </button>
                    ';
                }
                if($pel1->delete_level=="N"){
                    $delete='';
                }
                if($pel1->edit_level=="N"){
                    $edit='';
                }
                if($pel1->upload_level=="N"){
                    $upload='';
                }
                $lainnya='<a href="#" onclick="testPrint('.$p->id_part.')">
                <button class="btn btn-sm btn-outline-primary" title="Cetak" data-cetak="'.$p->no_part.'" data-id="'.$p->id_part.'"><i class="fa fa-qrcode"></i>
                </button></a>';
                $akses_system=$lainnya.$edit.$delete.$upload;
                $row[] = $akses_system;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_sparepart->count_all(),
            "recordsFiltered" => $this->Mod_sparepart->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function cetak_label() {
        
		$id 				= $_POST['id'];
        $data['dataPart'] = $this->Mod_sparepart->cetak_label($id);
        
	echo json_encode($data);
        //$data['dataqr'] = 'testah';
        //$this->ciqrcode->generate($data);

        echo show_my_print('warehouse/modals/modal_cetak_label', 'cetak-label', $data, ' modal-lg');
	}
    public function viewsparepart()
    {
        $id 				= trim($_POST['id']);
        $idc 				= trim($_POST['idc']);
		$data['dataPart'] = $this->Mod_sparepart->view_sparepart($id);

        $qr['data'] = $idc;
        $qr['level'] = 'H';
        $qr['size'] = 450;
        $qr['savename'] = './assets/img_qr/'.$idc.'.png';
        $qrnye=$this->ciqrcode->generate($qr);

		echo show_my_modal('warehouse/modals/modal_cetak_label', 'cetak-label', $data, ' modal-lg');
    }
    public function viewsparepart2()
    {
        $id 				= trim($_GET['id']);
        $ci_part = get_instance();
		$query = "SELECT max(no_part) AS no_part FROM tbl_wh_barang WHERE id_part='$id'";
		$hasil = $ci_part->db->query($query)->row_array();
		$nomornye = $hasil['no_part'];
		$data['dataPart'] = $this->Mod_sparepart->view_sparepart($id);

        $qr['data'] = $nomornye;
        $qr['level'] = 'H';
        $qr['size'] = 450;
        $qr['savename'] = './assets/img_qr/'.$nomornye.'.png';
        $qrnye=$this->ciqrcode->generate($qr);
        //$this->load('layoutbackend', 'warehouse/modals/modal_cetak_label', $data);

		echo show_my_modal('warehouse/modals/modal_cetak_label', 'cetak-label', $data, ' modal-lg');
    }
    

    public function cetakPart()
	{
		$data['dataPart'] = $this->Mod_sparepart->cetak_sparepart();
		echo show_my_print('warehouse/modals/modal_cetak_all_part', 'cetak-part', $data, ' modal-xl');
	}

    public function prosesTsparepart()
    {
        $this->form_validation->set_rules('no_part', 'Nomor Part', 'trim|required');
        $this->form_validation->set_rules('nama_part', 'Nama Barang', 'trim|required');

        $data     = $this->input->post();
		//$kategori = trim($_POST['kategori']);
        //$kat = explode('|', $kategori);
        //$kdKat = $kat[1];
        //$idKat = $kat[0];

		//$kelompok = trim($_POST['kelompok']);
        //$kel = explode('|', $kelompok);
        //$kdKel = $kel[1];
        //$idKel = $kel[0];

        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_sparepart->insertSparepart($data);

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
    public function updateSparepart() {
		$id 				= trim($_POST['id']);
        $data['apl'] = $this->db->get("aplikasi")->row();
        $data['dataSatuan'] = $this->Mod_sparepart->select_satuan();
        $data['dataType'] = $this->Mod_sparepart->select_type();
        $data['dataKategori'] = $this->Mod_sparepart->select_kategori();
        $data['dataKelompok'] = $this->Mod_sparepart->select_kelompok();
        $data['dataSupplier'] = $this->Mod_sparepart->select_supplier();
		$data['dataPart'] = $this->Mod_sparepart->select_by_id_part($id);

		echo show_my_modal('warehouse/modals/modal_tambah_part', 'update-sparepart', $data, ' modal-lg');
	}

	public function prosesUsparepart() {
		
		$this->form_validation->set_rules('no_part', 'no Part', 'trim|required');
		$this->form_validation->set_rules('nama_part', 'Nama Barang', 'trim|required');

		$data 	= $this->input->post();
        //$kategori = trim($_POST['kategori']);
        //$kat = explode('|', $kategori);
        //$kdKat = $kat[1];
        //$idKat = $kat[0];
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_sparepart->updateSparepart($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_ok_msg('Data Berhasil diupdate', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Data Batal diupdate', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}
    public function updateStok() {
		$id 				= trim($_POST['id']);
        $data['apl'] = $this->db->get("aplikasi")->row();
        $data['dataSatuan'] = $this->Mod_sparepart->select_satuan();
        $data['dataType'] = $this->Mod_sparepart->select_type();
        $data['dataKategori'] = $this->Mod_sparepart->select_kategori();
        $data['dataKelompok'] = $this->Mod_sparepart->select_kelompok();
        $data['dataSupplier'] = $this->Mod_sparepart->select_supplier();
		$data['dataPart'] = $this->Mod_sparepart->select_by_id_part($id);

		echo show_my_modal('warehouse/modals/modal_stok_manual', 'update-stok', $data, ' modal-md');
	}

	public function prosesUstok() 
    {
		
		$data 	= $this->input->post();
			$result = $this->Mod_sparepart->updateStok($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_ok_msg('Data Berhasil diupdate', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Data Gagal diupdate', '20px');
			}
		echo json_encode($out);
		
	}


    public function deleteSparepart()
    {
        $id = $_POST['id'];
        $result = $this->Mod_sparepart->deletePart($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
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


    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nama_submenu') == '') {
            $data['inputerror'][] = 'nama_submenu';
            $data['error_string'][] = 'Submenu is required';
            $data['minlength'] = '2';
            $data['status'] = FALSE;
        }

        if ($this->input->post('link') == '') {
            $data['inputerror'][] = 'link';
            $data['error_string'][] = 'Link is required';
            $data['minlength'] = '2';
            $data['status'] = FALSE;
        }

        if ($this->input->post('icon') == '') {
            $data['inputerror'][] = 'icon';
            $data['error_string'][] = 'Icon is required';
            $data['minlength'] = '2';
            $data['status'] = FALSE;
        }

        if ($this->input->post('is_active') == '') {
            $data['inputerror'][] = 'is_active';
            $data['error_string'][] = 'Please select Is Active';
            $data['minlength'] = '2';
            $data['status'] = FALSE;
        }

        if ($this->input->post('id_menu') == '') {
            $data['inputerror'][] = 'id_menu';
            $data['error_string'][] = 'Please select Menu';
            $data['minlength'] = '2';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}