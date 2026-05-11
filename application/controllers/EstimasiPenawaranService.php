<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EstimasiPenawaranService extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('service/Mod_estimasi_penawaran_service','service/Mod_operation_time', 'service/Mod_work_order', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Estimasi Penawaran";
		$data['judul'] 		= "Estimasi";
		$this->load->helper('url');
		//$data['dataCustomer'] = $this->Mod_estimasi_penawaran_service->select_customer();
		//$this->template->load('layoutbackend', 'service/estimasi_work_order', $data);
        echo show_my_modal('service/modals/modal_keterangan_estimasi', 'tambah-keterangan', $data);
		$this->template->load('layoutbackend', 'service/estimasi_penawaran_service', $data);
	}
	public function processEstimasi() {
        $idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
		$data['dataSa'] = $this->Mod_estimasi_penawaran_service->select_sa($id);
		$data['dataCus'] = $this->Mod_estimasi_penawaran_service->select_customer($kode_cus);
        
		$this->load->view('service/estimasi_work_order', $data);

		//echo show_my_modal('service/modals/modal_tambah_work_order', 'process-work-order', $data, ' modal-xl');
	}

	public function ajax_estimasi()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_estimasi_penawaran_service->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_estimasi_penawaran_service->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_estimasi_penawaran_service->get_datatables_estimasi();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->wo_no;
                $row[] = $p->sa_name;
                $row[] = $p->customer_name;
                $row[] = $p->customer_complain;
                $row[] = $p->vin;
                $row[] = $p->no_pol;
                $row[] = $p->type;
                $row[] = $p->storing;
                $row[] = tglIndoPendek($p->date_open_wo);
                $row[] = $p->clockin;
                $row[] = $p->pembuat;
                    $edit='                    
                    <button class="btn btn-xs btn-dark process-estimasi" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-chalkboard"></i> Process
                  </button>';
                  
                  $print='                    
                    <button class="btn btn-xs btn-info cetak-po" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-print"></i> Print
                  </button>                  
                    <button class="btn btn-xs btn-primary process-estimasi" title="Edit" data-id="'.$p->wo_no.'|'.$p->customer.'"><i class="fa fa-check"></i> Edit
                  </button>';
                $akses_system= ($p->estimasi == 'N') ? $edit : $print;
                $row[] = $akses_system;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_estimasi_penawaran_service->count_all_estimasi(),
            "recordsFiltered" => $this->Mod_estimasi_penawaran_service->count_filtered_estimasi(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
public function showPart()
    {
		$sup = $_GET['sup'];
        $data['dataDetail'] = $this->Mod_estimasi_penawaran_service->select_part($sup);
        $this->load->view('service/data_part_with_supplier', $data);
    }
	public function ajax_list()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_estimasi_penawaran_service->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pel) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $pel->no_part;
                $row[] = $pel->nama_part;
                $row[] = $pel->satuan;
                $row[] = $pel->stok;
                $row[] = number_format($pel->harga_baru);
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_estimasi_penawaran_service->count_all(),
			"recordsFiltered" => $this->Mod_estimasi_penawaran_service->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function list_operation()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_operation_time->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_operation_time->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_operation_time->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->code;
                $row[] = $p->duration;
                $row[] = $p->description;
                $row[] = $p->price;
                
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_operation_time->count_all(),
            "recordsFiltered" => $this->Mod_operation_time->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    //end cari operation
	public function cariKode($id)
	{
		$data = $this->Mod_estimasi_penawaran_service->get_part($id);
		echo json_encode($data);
	}
	public function prosesDetailPo()
	{
		$date = date("y-m");
		$ci_kons = get_instance();
		$query = "SELECT max(id_estimasi_penawaran) AS maxKode FROM tbl_wh_estimasi_penawaran WHERE id_estimasi_penawaran LIKE '%$date%'";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 5, 4);
		$noUrut++;
		$tahun = substr($date, 0, 2);
		$bulan = substr($date, 3, 2);
		$kode_po  = $tahun.'-'.$bulan.sprintf("%03s", $noUrut);
		$kode_ref = 'SV/TOP/'.$bulan.'/'.$tahun.'/'.sprintf("%03s", $noUrut);
		
		$data 	= $this->input->post();
		$result = $this->Mod_estimasi_penawaran_service->insertDetailPo($kode_po, $kode_ref, $data);
		
		if ($result > 0) {
				$out['msg'] = show_ok_msg('Data  ditambahkan!', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_del_msg('Filed !', '20px');
			}
		echo json_encode($out);
	}
	public function updateDiskon()
	{
        $id = $_POST['id'];
        $diskon = $_POST['diskon'];
        $hrg_part = $_POST['hrg_part'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran_service->update_detailDiskon($id,$diskon,$hrg_part);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateDetailPo()
	{
        $id = $_POST['id'];
        $jml_part = $_POST['jml_part'];
        $hrg_part = $_POST['hrg_part'];
		$total=$hrg_part*$jml_part;
		$data['dataPo'] = $this->Mod_estimasi_penawaran_service->update_detailPo($id,$jml_part,$hrg_part,$total);
		//$this->load->view('body_repair/detail_estimasi', $data);
	}
	public function updateRemark()
	{
        $id = $_POST['id'];
        $remark = $_POST['remark'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran_service->update_remark($id,$remark);
	}
	public function tambahKeterangan()
	{
        $id = $_POST['id'];
        $remark = $_POST['keterangan'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran_service->insertRemark($id,$remark);
	}
	public function tambahNote()
	{
        $id = $_POST['id'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran_service->insertNote($id);
	}
	public function prosesPo()
	{
		
		$sekarang= date("Y-m");
		$date = date("y-m");
		$ci_kons = get_instance();
		$query = "SELECT max(id_estimasi_penawaran) AS maxKode FROM tbl_af_estimasi_penawaran WHERE id_estimasi_penawaran LIKE '%$date%'";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 5, 4);
		$noUrut++;
		$tahun = substr($date, 0, 2);
		$bulan = substr($date, 3, 2);
		$kode_po  = $tahun.'-'.$bulan.sprintf("%03s", $noUrut);
		$kode_ref = 'SP/TOP/'.$bulan.'/'.$tahun.'/'.sprintf("%03s", $noUrut);

		$this->form_validation->set_rules('tgl_estimasi_penawaran', 'Tanggal Order', 'trim|required');
		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->input->post();
			$date2 = $data['tgl_estimasi_penawaran'];
			
			$bea=$data['bea_kirim'];
			$bea_kirim =str_replace(",","", $bea);

			$tgl2 = explode('-', $date2);
			$tgl_po_fix = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";

			$data = array(
				'id_estimasi_penawaran'  	=> $kode_po,
				'wo_no'  	=> $data['wo_no'],
				'kode_estimasi_penawaran'   => $kode_ref,
				'tgl_estimasi_penawaran'  	=> $tgl_po_fix,
				'id_customer'	=> $data['kode_cus'],
				'no_reg'	=> $data['no_reg'],
				'no_vin' => $data['no_vin'],
				'sales_design' => $data['sales_design'],
				'date_received' => $data['date_received'],
				'millage' => $data['millage'],
				'engine_no' => $data['engine_no'],
				'acc_no' => $data['acc_no'],
				'received_by' => $data['received_by'],
				'routing_no' => $data['routing_no'],
				'last_km' => $data['last_km'],
				'date_of_regis' => $data['date_of_regis'],
				'ppn' => $data['ppn'],
				'bea_kirim' => $bea_kirim,
				'user'   	=> $data['user'],
				'status_po'	=> 'N'
			);
			
			$updateWo= $this->Mod_estimasi_penawaran_service->updateWo($data['wo_no']);
				$data['dataPo'] = $this->db->insert('tbl_af_estimasi_penawaran', $data);
				$data 	= $this->input->post();
				
			if ($result > 0) {
				$out['dataRef'] = $kode_ref;
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
		$id 				= $_POST['wo_no'];
		$data['dataDetail'] = $this->Mod_estimasi_penawaran_service->select_detail($id);
		$this->load->view('service/detail_estimasi_penawaran', $data);
	}
	public function tampilKeterangan()
	{
		$id 				= $_POST['wo_no'];
		$data['dataKet'] = $this->Mod_estimasi_penawaran_service->select_keterangan($id);
		$this->load->view('service/data_keterangan_estimasi', $data);
	}
	public function view()
    {
            $id = $this->input->post('id');
            $table = $this->input->post('table');
            $data['table'] = $table;
            $data['data_field'] = $this->db->field_data($table);
            $data['dataDetail'] = $this->Mod_estimasi_penawaran_service->view($id)->result_array();
            $this->load->view('service/detail_estimasi_penawaran', $data);
        
    }
	public function tampilDetailCache()
	{
		$id 				= $_GET['wo_no'];
		$data['dataDetail'] = $this->Mod_estimasi_penawaran_service->select_detail($id);
		$this->load->view('service/detail_estimasi_penawaran_cache', $data);
	}
	public function deleteDetail()
	{
		$idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $spk = $kat[1];
        $id = $kat[0];
		$result = $this->Mod_estimasi_penawaran_service->deleteDetail_po($id, $spk);
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
	
	public function deleteKeterangan()
	{
		$id = $_POST['id'];
		$result = $this->Mod_estimasi_penawaran_service->deleteKeterangan_po($id);
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
		$idS = trim($_POST['id']);
        $kat = explode('|', $idS);
        $kode_cus = $kat[1];
        $id = $kat[0];

		$data['dataCus'] = $this->Mod_estimasi_penawaran_service->select_customer($kode_cus);
		$data['dataPo'] = $this->Mod_estimasi_penawaran_service->select_by_id($id);
		$data['detailPo'] = $this->Mod_estimasi_penawaran_service->select_detail($id);
		$data['detailKet'] = $this->Mod_estimasi_penawaran_service->select_keterangan($id);

		echo show_my_print('service/modals/modal_cetak_estimasi_penawaran_service', 'cetak-po', $data, ' modal-xl');
	}
	public function cetak_int()
	{
		$id 				= $_POST['id'];
		$data['dataPo'] = $this->Mod_estimasi_penawaran_service->select_by_id($id);
		$data['detailPo'] = $this->Mod_estimasi_penawaran_service->select_detail($id);

		echo show_my_print('service/modals/modal_cetak_estimasi_penawaran_internal', 'cetak-po-int', $data, ' modal-xl');
	}
}