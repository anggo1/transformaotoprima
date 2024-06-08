<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pegawai extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_pegawai','Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
        $this->load->helper('myfunction_helper');
        $this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "Daftar Pegawai";
		$data['judul'] 		= "Data Pegawai";
        $this->load->helper('url');
		$data['datapendidikan'] = $this->Mod_pegawai->select_pendidikan();
		$data['databagian'] = $this->Mod_pegawai->select_bagian();
		$data['dataposisi'] = $this->Mod_pegawai->select_posisi();
        $data['menu'] = $this->Mod_menu->getAll()->result();

        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_pegawai->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_pegawai->select_by_level($idlevel, $id_sub);

		echo show_my_modal('hrd/modals/modal_tambah_pegawai', 'tambah-pegawai', $data, ' modal-lg');
        $this->template->load('layoutbackend','hrd/pegawai_data', $data);

	}
    public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_pegawai->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_pegawai->select_by_level($idlevel, $id_sub);

		foreach ($viewLevel as $b) {
            $row1 = array();
            $row1[] = $b->id_submenu;

        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_pegawai->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $p) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $p->nip;
            $row[] = $p->nama_depan;
            $row[] = tglIndoPendek($p->tgl_lahir);
            $row[] = $p->pendidikan;
            $row[] = $p->jabatan;
            $row[] = $p->departement;
            if($b->edit_level=="Y" && $b->delete_level=="Y"){
                $row[]='
                <button class="btn btn-sm btn-outline-success update-body ion-compose ion-sm" title="Edit" data-id="'.$p->nip.'">
                </button>
                <button class="btn btn-sm btn-outline-danger delete-body ion-android-close ion-sm" title="Delete" data-toggle="modal" data-target="#hapusBody" data-id="'.$p->nip.'">
                </button>';
            }
            if($b->edit_level=="Y" && $b->delete_level=="N"){
                $row[]='
                <button class="btn btn-sm btn-outline-success update-body ion-compose ion-lg" title="Edit" data-id="'.$p->nip.'">
                </button>';
            }else{
                $row[]='';
            }
            $data[] = $row;
        }
    }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_pegawai->count_all(),
            "recordsFiltered" => $this->Mod_pegawai->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


//

    public function viewpegawai()
    {
     $id = $this->input->post('id');
     $data['data_table'] = $this->Mod_pegawai->view_pegawai($id);
    
     $this->load->view('hrd/view', $data);
     
 }

 public function editpegawai($id)
 {
     
    $data = $this->Mod_pegawai->get_pegawai($id);
    echo json_encode($data);
    
}

public function insert()
{
 $this->_validate();
 $save  = array(
    'nama_submenu'	=> $this->input->post('nama_submenu'),
    'link'  	=> $this->input->post('link'),
    'icon'   	=> $this->input->post('icon'),
    'id_menu'  	=> $this->input->post('id_menu'),
    'is_active' => $this->input->post('is_active'),
    'urutan' 	=> $this->input->post('urutan')
);
 $this->Mod_submenu->insertsubmenu("tbl_submenu", $save);
 $insert_id = $this->db->insert_id();
 /*$nama_submenu = $this->input->post('nama_submenu');
 $get_id= $this->Mod_submenu->get_by_nama($nama_submenu);*/
 $id_level = $this->session->userdata['id_level'];
 $levels = $this->Mod_userlevel->getAll()->result();
 foreach ($levels as $row) {
    $data = array(
        'id_submenu' => $insert_id,
        'id_level'   => $row->id_level,
    );
    $this->Mod_submenu->insert_akses_submenu("tbl_akses_submenu",$data);
}
echo json_encode(array("status" => TRUE));

}

public function update()
{
    
    //$this->_validate();
    $nip = $this->input->post('nip');
    $data  = array(
        
		'nama_depan'	=> $this->input->post('nama_depan'),
        'nama_belakang'	=> $this->input->post('nama_belakang'),
        'departement'	=> $this->input->post('departement'),
        'jabatan'		=> $this->input->post('jabatan'),
        'status_kerja'	=> $this->input->post('status_kerja'),
        'tgl_masuk'		=> $this->input->post('tgl_masuk'),
        'tempat_lahir'	=> $this->input->post('tempat_lahir'),
        'tgl_lahir'		=> $this->input->post('tgl_lahir'),
        'agama'			=> $this->input->post('agama'),
        'status_nikah'	=> $this->input->post('status_nikah'),
        'pendidikan'	=> $this->input->post('pendidikan'),
        'alamat'		=> $this->input->post('alamat'),
        'kodepos'		=> $this->input->post('kodepos'),
        'no_hp'			=> $this->input->post('no_hp'),
        'status_nikah'	=> $this->input->post('status_nikah'),
        'jamsostek'		=> $this->input->post('jamsostek'),
        'tinggi'		=> $this->input->post('tinggi'),
        'berat'			=> $this->input->post('berat'),
        'darah'			=> $this->input->post('darah'),
        's_kawin'		=> $this->input->post('s_kawin'),
        'no_ktp'		=> $this->input->post('no_ktp'),
        'npwp'			=> $this->input->post('npwp'),
        'catatan1'		=> $this->input->post('catatan1')
    );
    $this->Mod_pegawai->updatepegawai($nip, $data);
    echo json_encode(array("status" => TRUE));
    
}
public function delete()
{
    $nip = $this->input->post('nip');
    $this->Mod_pegawai->deletepegawai($nip, 'tbl_pegawai');
    $data['status'] = TRUE;
    echo json_encode($data);
    
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
    foreach($menu as $row)
    {
        $sheet->setCellValue('A'.$x, $no++);
        $sheet->setCellValue('B'.$x, $row->nama_submenu);
        $sheet->setCellValue('C'.$x, $row->link);
        $sheet->setCellValue('D'.$x, $row->icon);
        $sheet->setCellValue('E'.$x, $row->nama_menu);
        $sheet->setCellValue('F'.$x, $row->is_active);
        $x++;
    }
    $writer = new Xlsx($spreadsheet);
    $filename = 'laporan-Submenu';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
}


private function _validate()
{
    $data = array();
    $data['error_string'] = array();
    $data['inputerror'] = array();
    $data['status'] = TRUE;

    if($this->input->post('nama_submenu') == '')
    {
        $data['inputerror'][] = 'nama_submenu';
        $data['error_string'][] = 'Submenu is required';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($this->input->post('link') == '')
    {
        $data['inputerror'][] = 'link';
        $data['error_string'][] = 'Link is required';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($this->input->post('icon') == '')
    {
        $data['inputerror'][] = 'icon';
        $data['error_string'][] = 'Icon is required';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($this->input->post('is_active') == '')
    {
        $data['inputerror'][] = 'is_active';
        $data['error_string'][] = 'Please select Is Active';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($this->input->post('id_menu') == '')
    {
        $data['inputerror'][] = 'id_menu';
        $data['error_string'][] = 'Please select Menu';
        $data['minlength'] = '2';
        $data['status'] = FALSE;
    }

    if($data['status'] === FALSE)
    {
        echo json_encode($data);
        exit();
    }
}
}