<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;    
class PartOrder extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Mod_partorder'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
		$this->load->model('Mod_aplikasi');
	}

	public function index()
	{
		$data['page'] 		= "Part Order";
		$data['judul'] 		= "P O";
		$this->load->helper('url');
		$data['dataSupplier'] = $this->Mod_partorder->select_supplier();
		$data['dataKode'] = $this->Mod_partorder->select_kode();
		$data['dataKota'] = $this->Mod_partorder->select_kota();
		$this->template->load('layoutbackend', 'warehouse/part_order', $data);
	}
public function showPart()
    {
		$sup = $_GET['sup'];
        $data['dataDetail'] = $this->Mod_partorder->select_part($sup);
        $this->load->view('warehouse/data_part_with_supplier', $data);
    }
	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_partorder->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pel) {
			$no++;
			$row = array();
			$row[] = "$no";
			$row[] = $pel->no_part;
                $row[] = $pel->nama_part;
                $row[] = $pel->satuan;
                $row[] = $pel->stok;
                $row[] = number_format($pel->harga_baru);
                $row[] = $pel->diskon;
                $row[] = number_format($pel->harga_net);
                $row[] = number_format($pel->harga_rata);
                $row[] = $pel->ppn;
                $row[] = number_format($pel->harga_valid);
                $row[] = $pel->ket_harga;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_partorder->count_all(),
			"recordsFiltered" => $this->Mod_partorder->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cariKode($id)
	{
		$data = $this->Mod_partorder->get_part($id);
		echo json_encode($data);
	}
	public function prosesDetailPo()
	{
		
		$data 	= $this->input->post();
		$data['dataPo'] = $this->Mod_partorder->insertDetailPo($data);
		
		echo json_encode($data);
	}
	public function updateDetailPo()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_partorder->update_detailPo($id,$jml_part,$hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateBo()
	{
        $id = $_POST['id'];
        $bo = $_POST['bo'];
		$data['dataPo'] = $this->Mod_partorder->update_detailBo($id,$bo);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateRemark()
	{
        $id = $_POST['id'];
        $remark = $_POST['remark'];
		$data['dataPo'] = $this->Mod_partorder->update_remark($id,$remark);
	}
	public function prosesPo()
	{
		
		$sekarang= date("Y-m");
		$this->form_validation->set_rules('tgl_part_order', 'Tanggal Order', 'trim|required');
		$this->form_validation->set_rules('supplier', 'Data Supplier', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			$kode_po = $data['id_part_order'];
			$date2 = $data['tgl_part_order'];
			$tgl2 = explode('-', $date2);
			$tgl_po_fix = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
			
			$data_pesan = $data['kode'];
			$kode = explode('|', $data_pesan);
			$kode_angka = $kode[0];
			$kode_ket = $kode[1];
			$lokasi = $data['lokasi'];
			$kode_lk = explode('|', $lokasi);
			$lok1 = $kode_lk[0];

			$sekarang = date('Y/m/d');
			//$s=$data['status'];
			$thn = substr($sekarang, 0, 4);
			$bln = substr($sekarang, 5, 2);

			$nama_ref="/PO/$bln/$thn";
			$koderef=$kode_po.$nama_ref;

			$data = array(
				'id_part_order'  	=> $kode_po,
				'kode_part_order'   => $koderef,
				'tgl_part_order'  	=> $tgl_po_fix,
				'supplier'	=> $data['supplier'],
				'kode_pesan'	=> $kode_angka.$data['no_order'],
				'ket_pesan'	=> $kode_ket,
				'keterangan' => $data['keterangan'],
				'user'   	=> $data['user'],
				'status_PO'	=> 'N',
				'lokasi'   	=> $lok1
			);
				$data['dataPo'] = $this->db->insert('tbl_wh_part_order', $data);
				$data 	= $this->input->post();
				
			if ($result > 0) {
				$out['dataRef'] = $koderef;
				$out['dataPo'] = $kode_po;
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
	public function tampilDetail()
	{
		$id 				= $_POST['id_part_order'];
		$data['dataDetail'] = $this->Mod_partorder->select_detail($id);
		$this->load->view('warehouse/detail_part_order', $data);
	}
	public function view()
    {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $data['table'] = $table;
            $data['data_field'] = $this->db->field_data($table);
            $data['dataDetail'] = $this->Mod_userlevel->view($id)->result_array();
            $this->load->view('warehouse/detail_part_order', $data);
        
    }
	public function tampilDetailCache()
	{
		$id 				= $_GET['id_part_order'];
		$data['dataDetail'] = $this->Mod_partorder->select_detail($id);
		$this->load->view('warehouse/detail_part_order_cache', $data);
	}
	public function deleteDetail()
	{
		$id = $_POST['id'];
		$result = $this->Mod_partorder->deleteDetail_po($id);
		if ($result > 0) {
			//$out['datakode']=$kodeBaru;
			$out['status'] = '';
			$out['msg'] = show_del_msg('Deleted', '10px');
		} else {
			$out['status'] = '';
			$out['msg'] = show_err_msg('Filed !', '10px');
		}
		echo json_encode($out);
	}
	public function cetak()
	{
		$id 				= $_POST['id'];
		$data['dataPo'] = $this->Mod_partorder->select_by_id($id);
		$data['detailPo'] = $this->Mod_partorder->select_detail($id);

		echo show_my_print('warehouse/modals/modal_cetak_part_order', 'cetak-po', $data, ' modal-xl');
	}
	public function export(){
		$id = $this->input->get( 'id', TRUE );
    //$nip = $this->input->get( 'nip', TRUE );
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = [
		  'font' => ['bold' => true], // Set font nya jadi bold
		  'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Set text jadi di tengah secara vertical (middle)
			'wrapText'     => TRUE
		  ],
		  'borders' => [
			'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
			'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
			'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
			'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
		  ]
		];
		$garis_luar = [
			'borders' => [
			  'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
			  'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
			  'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
			  'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
			]
		  ];
		$style_2garis = [
			'font' => ['bold' => true], // Set font nya jadi bold
			'alignment' => [
			  'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
			  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
			  'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE], // Set border top dengan garis tipis
			  'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE],  // Set border right dengan garis tipis
			  'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE], // Set border bottom dengan garis tipis
			  'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE] // Set border left dengan garis tipis
			]
		  ];
		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = [
		  'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
		  'borders' => [
			'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
			'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
			'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
			'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
		  ]
		];

		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setName('Paid');
		$drawing->setDescription('Logo_mercy');
		$drawing->setPath('assets/dist/img/logo_mercedes.png'); // put your path and image here
		$drawing->setCoordinates('G3');
		$drawing->setOffsetX(110);
		$drawing->setWidth(500);
    	$drawing->setHeight(55);
		$drawing->setOffsetX(25);
		$drawing->setOffsetY(-5);
		//$drawing->setRotation(25);
		$drawing->getShadow()->setVisible(true);
		$drawing->getShadow()->setDirection(45);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		
		$sheet->setCellValue('C6', "CV PART ORDER FORM"); // Set kolom A1 dengan tulisan "DATA Aplikasi"
		$sheet->mergeCells('C6:K6'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('C6')->getFont()->setBold(true); // Set bold kolom A1
		
		$dataPo= $this->Mod_partorder->select_by_id($id);
		foreach($dataPo as $detail){ 
		$sheet->setCellValue('C8', "To");				
		$sheet->setCellValue('C9', $detail->nama_sup);
		$sheet->setCellValue('C10', $detail->detail);
		$sheet->setCellValue('C11', $detail->alamat);
		$sheet->setCellValue('C12', 'FAX : '.$detail->no_fax);

		$sheet->setCellValue('C14', "From");
		$sheet->setCellValue('C15', 'Dealer Name : PT. TRANSFORMA OTO PRIMA');
		$sheet->setCellValue('C16', 'Order No : '.$detail->kode_pesan.' '.$detail->ket_pesan);
		$sheet->setCellValue('C17', 'Order Date : '.$detail->tgl_part_order);
		$sheet->setCellValue('I8', " Page : __1__ of __1__");
		}
		$sheet->setCellValue('C19', "NO");
		$sheet->mergeCells('C19:C20');	
		$sheet->setCellValue('D19', "Part Number");
		$sheet->mergeCells('D19:G19');
		$sheet->setCellValue('D20', "SC");
		$sheet->setCellValue('F20', "ES1");
		$sheet->setCellValue('G20', "ES2");
		$sheet->setCellValue('H19', "Description");
		$sheet->mergeCells('H19:H20');
		$sheet->setCellValue('I19', "Qty");
		$sheet->mergeCells('I19:I20');
		$sheet->setCellValue('J19', "BO Y/N");
		$sheet->mergeCells('J19:J20');
		$sheet->setCellValue('K19', "Remarks");
		$sheet->mergeCells('K19:K20');
		
		
		$sheet->getStyle('B2:L40')->applyFromArray($garis_luar);
		$sheet->getStyle('C6:K6')->applyFromArray($style_2garis);
		$sheet->getStyle('C19:C20')->applyFromArray($style_col);
		$sheet->getStyle('D19:G19')->applyFromArray($style_col);
		$sheet->getStyle('D20')->applyFromArray($style_col);
		$sheet->getStyle('E20')->applyFromArray($style_col);
		$sheet->getStyle('F20')->applyFromArray($style_col);
		$sheet->getStyle('G20')->applyFromArray($style_col);
		$sheet->getStyle('H19:H20')->applyFromArray($style_col);
		$sheet->getStyle('I19:I20')->applyFromArray($style_col);
		$sheet->getStyle('J19:J20')->applyFromArray($style_col);
		$sheet->getStyle('K19:K20')->applyFromArray($style_col);
		// Panggil function view yang ada di AplikasiModel untuk menampilkan semua data Aplikasinya
		
		//$id 				= $_POST['id'];
		$detailPo= $this->Mod_partorder->select_detail($id);
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 1; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach($detailPo as $data){ // Lakukan looping pada variabel Aplikasi
			$sheet->setCellValue('C2'.$numrow, $no);
			$sheet->setCellValue('D2'.$numrow, '');
			$sheet->setCellValue('E2'.$numrow, $data->no_part);
			$sheet->setCellValue('F2'.$numrow, '');
			$sheet->setCellValue('G2'.$numrow, '');
			$sheet->setCellValue('H2'.$numrow, $data->nama_part);
			$sheet->setCellValue('I2'.$numrow, $data->jumlah);
			$sheet->setCellValue('J2'.$numrow, $data->bo);
			$sheet->setCellValue('K2'.$numrow, $data->remark);


			//$sheet->setCellValue('K2'.$numrow, $detail->keterangan);
			
		  	//$sheet->setCellValue('C2', 'FAX : '.$detail->keterangan);
			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$sheet->getStyle('C2'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('D2'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('E2'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('F2'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('G2'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('H2'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('I2'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('J2'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('K2'.$numrow)->applyFromArray($style_row);
		  
		  $no++; // Tambah 1 setiap kali looping
		  $numrow++; // Tambah 1 setiap kali looping
		}
		//$sheet->getStyle('C2'.$numrow)->applyFromArray($style_col);
		$sheet->setCellValue('C2'.$numrow, 'NOTE : '.$detail->keterangan);
		$sheet->setCellValue('E3'.$numrow, '   Signature');

		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setName('Paid');
		$drawing->setDescription('Logo_mercy');
		$drawing->setPath('assets/dist/img/signryan.png'); // put your path and image here
		$drawing->setCoordinates('E3'.$numrow, 'Signature');
		$drawing->setOffsetX(110);
		$drawing->setWidth(500);
    	$drawing->setHeight(90);
		$drawing->setOffsetX(5);
		$drawing->setOffsetY(30);
		//$drawing->setRotation(25);
		$drawing->getShadow()->setVisible(false);
		$drawing->getShadow()->setDirection(2);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		
		
		$tgl_sekarang= date("d-m-Y");
		// Set width kolom
		$sheet->getColumnDimension('A')->setWidth(15); // Set width kolom A
		$sheet->getColumnDimension('B')->setWidth(3); // Set width kolom A
		$sheet->getColumnDimension('C')->setWidth(5); // Set width kolom A
		$sheet->getColumnDimension('D')->setWidth(6); // Set width kolom B
		$sheet->getColumnDimension('E')->setWidth(25); // Set width kolom C
		$sheet->getColumnDimension('F')->setWidth(5); // Set width kolom D
		$sheet->getColumnDimension('G')->setWidth(5); // Set width kolom E
		$sheet->getColumnDimension('H')->setWidth(40); // Set width kolom E
		$sheet->getColumnDimension('I')->setWidth(6); // Set width kolom E
		$sheet->getColumnDimension('J')->setWidth(6); // Set width kolom E
		$sheet->getColumnDimension('K')->setWidth(25); // Set width kolom E
		$sheet->getColumnDimension('L')->setWidth(3); // Set width kolom E
		
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("PART ORDER".$tgl_sekarang);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PO'.$detail->kode_pesan.$detail->lokasi.'.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	  }
}
