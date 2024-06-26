<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;    


class Aplikasi extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_aplikasi');
        
	}

	public function index()
	{
		$data['page'] 		= "Setting Nama Perusahan";
		$data['judul'] 		= "Perusahaan";
        echo show_my_modal('admin/modals/modal_tambah_pool', 'tambah-pool', $data);
		$this->template->load('layoutbackend', 'admin/aplikasi',$data);
	}

	    public function ajax_list()
    {
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_aplikasi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $apl) {
            $no++;
            $row = array();
            $row[] = $apl->nama_owner;
            $row[] = $apl->alamat;
            $row[] = $apl->tlp;
            $row[] = $apl->title;
            $row[] = $apl->nama_aplikasi;
            $row[] = $apl->copy_right;
            $row[] = $apl->versi;
            $row[] = $apl->tahun;
            $row[] = $apl->status;
            $row[] = $apl->logo;  
            $row[] = $apl->id;
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_aplikasi->count_all(),
                        "recordsFiltered" => $this->Mod_aplikasi->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    public function showPl()
    {
        $data['dataPl'] = $this->Mod_aplikasi->select_pool();
        $this->load->view('admin/pool_data', $data);
    }
 /*Pool*/
 public function prosesTpool()
 {
     $this->form_validation->set_rules('nama_kota', 'Nama Pool', 'trim|required');

     $data     = $this->input->post();
     if ($this->form_validation->run() == TRUE) {
         $result = $this->Mod_aplikasi->insertPool($data);

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
 public function updatePool()
 {
     $id                 = trim($_POST['id']);
     $data['dataPool'] = $this->Mod_aplikasi->select_id_pool($id);

     echo show_my_modal('admin/modals/modal_tambah_pool', 'update-pool', $data);
 }

 public function prosesUPool()
 {

     $this->form_validation->set_rules('nama_kota', 'Nama Pool', 'trim|required');

     $data     = $this->input->post();
     if ($this->form_validation->run() == TRUE) {
         $result = $this->Mod_aplikasi->updatePool($data);

         if ($result > 0) {
             $out['status'] = '';
             $out['msg'] = show_ok_msg('Success', '20px');
         } else {
             $out['status'] = '';
             $out['msg'] = show_succ_msg('Filed!', '20px');
         }
     } else {
         $out['status'] = 'form';
         $out['msg'] = show_err_msg(validation_errors());
     }

     echo json_encode($out);
 }
 public function deletePool()
 {
     $id = $_POST['id'];
     $result = $this->Mod_aplikasi->deletePool($id);

     if ($result > 0) {
         $out['status'] = '';
         $out['msg'] = show_del_msg('Deleted', '20px');
     } else {
         $out['status'] = '';
         $out['msg'] = show_err_msg('Filed !', '20px');
     }
     echo json_encode($out);
 }

 /*endPool*/
    public function edit_aplikasi($id)
    {
            
            $data = $this->Mod_aplikasi->getAplikasi($id);
            echo json_encode($data);
        
    }

        public function update()
    {
        if(!empty($_FILES['imagefile']['name'])) {
        $this->_validate();
        $id = $this->input->post('id');
        
        $nama = slug($this->input->post('logo'));
        $config['upload_path']   = './assets/foto/logo/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png'; //mencegah upload backdor
        $config['max_size']      = '1000';
        $config['max_width']     = '2000';
        $config['max_height']    = '1024';
        $config['file_name']     = $nama; 
        
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('imagefile')){
            $gambar = $this->upload->data();
            $save  = array(
                'nama_owner' => $this->input->post('nama_owner'),
                'title' => $this->input->post('title'),
                'nama_aplikasi'  => $this->input->post('nama_aplikasi'),
                'copy_right'  => $this->input->post('copy_right'),
                'tahun' => $this->input->post('tahun'),
                'versi' => $this->input->post('versi'),
                'npwp' => $this->input->post('npwp'),
                'status' => $this->input->post('status'),
                'logo' => $gambar['file_name']
            );
            
            $g = $this->Mod_aplikasi->getImage($id)->row_array();

            if ($g != null) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/logo/'.$g['logo']);
            }
           
            $this->Mod_aplikasi->updateAplikasi($id, $save);
            echo json_encode(array("status" => TRUE));
            }else{//Apabila tidak ada gambar yang di upload
                $save  = array(
                'nama_owner' => $this->input->post('nama_owner'),
                'title' => $this->input->post('title'),
                'nama_aplikasi'  => $this->input->post('nama_aplikasi'),
                'copy_right'  => $this->input->post('copy_right'),
                'tahun' => $this->input->post('tahun'),
                'versi' => $this->input->post('versi'),
                'npwp' => $this->input->post('npwp'),
                'status' => $this->input->post('status')
            );
                $this->Mod_aplikasi->updateAplikasi($id, $save);
                echo json_encode(array("status" => TRUE));
            }
        }else{
            $this->_validate();
            $id = $this->input->post('id');
            $save  = array(
                'nama_owner' => $this->input->post('nama_owner'),
                'alamat'    => $this->input->post('alamat'),
                'kota'    => $this->input->post('kota'),
                'kode_pos'    => $this->input->post('kode_pos'),
                'tlp'       => $this->input->post('tlp'),
                'title' => $this->input->post('title'),
                'nama_aplikasi'  => $this->input->post('nama_aplikasi'),
                'copy_right'  => $this->input->post('copy_right'),
                'tahun' => $this->input->post('tahun'),
                'versi' => $this->input->post('versi'),
                'npwp' => $this->input->post('npwp'),
                'status' => $this->input->post('status')
            );
            $this->Mod_aplikasi->updateAplikasi($id, $save);
            echo json_encode(array("status" => TRUE));
        }
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('nama_owner') == '')
        {
            $data['inputerror'][] = 'nama_owner';
            $data['error_string'][] = 'Nama PT Tidak Boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('nama_aplikasi') == '')
        {
            $data['inputerror'][] = 'nama_aplikasi';
            $data['error_string'][] = 'Nama Aplikasi Tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('alamat') == '')
        {
            $data['inputerror'][] = 'alamat';
            $data['error_string'][] = 'Alamat Tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('tlp') == '')
        {
            $data['inputerror'][] = 'tlp';
            $data['error_string'][] = 'No Telpon Tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('title') == '')
        {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Title Tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('copy_right') == '')
        {
            $data['inputerror'][] = 'copy_right';
            $data['error_string'][] = 'Copy Right tidak boleh kosong';
            $data['status'] = FALSE;
        }

        if($this->input->post('tahun') == '')
        {
            $data['inputerror'][] = 'tahun';
            $data['error_string'][] = 'Tahun tidak boleh kosong';
            $data['status'] = FALSE;
        }


        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }


     public function download()
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
            $style_col = [
              'font' => ['bold' => true], // Set font nya jadi bold
              'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              ],
              'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
              ]
            ];
            // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
            $style_row = [
              'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              ],
              'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
              ]
            ];
            $sheet->setCellValue('A1', "DATA Aplikasi"); // Set kolom A1 dengan tulisan "DATA Aplikasi"
            $sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
            $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
            // Buat header tabel nya pada baris ke 3
            $sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
            $sheet->setCellValue('B3', "NAMA"); // Set kolom B3 dengan tulisan "NIS"
            $sheet->setCellValue('C3', "ALAMAT"); // Set kolom C3 dengan tulisan "NAMA"
            $sheet->setCellValue('D3', "STATUS"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
            $sheet->setCellValue('E3', "LOKASI"); // Set kolom E3 dengan tulisan "ALAMAT"
            // Apply style header yang telah kita buat tadi ke masing-masing kolom header
            $sheet->getStyle('A3')->applyFromArray($style_col);
            $sheet->getStyle('B3')->applyFromArray($style_col);
            $sheet->getStyle('C3')->applyFromArray($style_col);
            $sheet->getStyle('D3')->applyFromArray($style_col);
            $sheet->getStyle('E3')->applyFromArray($style_col);
            // Panggil function view yang ada di AplikasiModel untuk menampilkan semua data Aplikasinya
            $Aplikasi = $this->Mod_aplikasi->select_aplikasi();
            $no = 1; // Untuk penomoran tabel, di awal set dengan 1
            $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
            foreach($Aplikasi as $data){ // Lakukan looping pada variabel Aplikasi
              $sheet->setCellValue('A'.$numrow, $no);
              $sheet->setCellValue('B'.$numrow, $data->nama_aplikasi);
              $sheet->setCellValue('C'.$numrow, $data->alamat);
              $sheet->setCellValue('D'.$numrow, $data->status);
              $sheet->setCellValue('E'.$numrow, $data->lokasi);
              
              // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
              $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
              $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
              $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
              $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
              $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
              
              $no++; // Tambah 1 setiap kali looping
              $numrow++; // Tambah 1 setiap kali looping
            }
            // Set width kolom
            $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
            $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
            $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
            $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
            $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
            
            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            // Set orientasi kertas jadi LANDSCAPE
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            // Set judul file excel nya
            $sheet->setTitle("Laporan Data Aplikasi");
            // Proses file excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Data Aplikasi.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
        public function export(){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
            $style_col = [
              'font' => ['bold' => true], // Set font nya jadi bold
              'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              ],
              'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
              ]
            ];
            // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
            $style_row = [
              'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
              ],
              'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
              ]
            ];
            $sheet->setCellValue('A1', "DATA Aplikasi"); // Set kolom A1 dengan tulisan "DATA Aplikasi"
            $sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
            $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
            // Buat header tabel nya pada baris ke 3
            $sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
            $sheet->setCellValue('B3', "NIS"); // Set kolom B3 dengan tulisan "NIS"
            $sheet->setCellValue('C3', "NAMA"); // Set kolom C3 dengan tulisan "NAMA"
            $sheet->setCellValue('D3', "JENIS KELAMIN"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
            $sheet->setCellValue('E3', "ALAMAT"); // Set kolom E3 dengan tulisan "ALAMAT"
            // Apply style header yang telah kita buat tadi ke masing-masing kolom header
            $sheet->getStyle('A3')->applyFromArray($style_col);
            $sheet->getStyle('B3')->applyFromArray($style_col);
            $sheet->getStyle('C3')->applyFromArray($style_col);
            $sheet->getStyle('D3')->applyFromArray($style_col);
            $sheet->getStyle('E3')->applyFromArray($style_col);
            // Panggil function view yang ada di AplikasiModel untuk menampilkan semua data Aplikasinya
            $Aplikasi = $this->Mod_aplikasi->select_aplikasi();
            $no = 1; // Untuk penomoran tabel, di awal set dengan 1
            $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
            foreach($Aplikasi as $data){ // Lakukan looping pada variabel Aplikasi
              $sheet->setCellValue('A'.$numrow, $no);
              $sheet->setCellValue('B'.$numrow, $data->nama_aplikasi);
              $sheet->setCellValue('C'.$numrow, $data->alamat);
              $sheet->setCellValue('D'.$numrow, $data->status);
              $sheet->setCellValue('E'.$numrow, $data->lokasi);
              
              // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
              $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
              $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
              $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
              $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
              $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
              
              $no++; // Tambah 1 setiap kali looping
              $numrow++; // Tambah 1 setiap kali looping
            }
            // Set width kolom
            $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
            $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
            $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
            $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
            $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
            
            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            // Set orientasi kertas jadi LANDSCAPE
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            // Set judul file excel nya
            $sheet->setTitle("Laporan Data Aplikasi");
            // Proses file excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Data Aplikasi.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
          }
}