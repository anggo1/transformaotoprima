<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ServiceAppointment extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('service/Mod_service_appointment', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
		$this->load->model('Mod_aplikasi');
	}

	public function index()
	{
		$data['page'] 		= "Service Appointment";
		$data['judul'] 		= "S A";
		$this->load->helper('url');
		//$data['dataCus'] = $this->Mod_service_appointment->select_customer();
		echo show_my_modal('service/modals/modal_tambah_appointment', 'tambah-appointment', $data, ' modal-lg');
		$this->template->load('layoutbackend', 'service/service_appointment', $data);
	}
	public function ajax_list()
    {
        $link=$this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $idlokasi = $this->session->userdata['lokasi'];
        $get_id = $this->Mod_service_appointment->get_by_nama($link);
        foreach ($get_id as $idnye){
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub=$idnye->id_submenu;
        }
        $viewLevel = $this->Mod_service_appointment->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $pel1) {
            $row1 = array();
            $row1[] = $pel1->id_submenu;
            $data1[] = $row1;

            $list = $this->Mod_service_appointment->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $p) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $p->wo_no;
                $row[] = $p->sa_name;
                $row[] = $p->claim_no;
                $row[] = $p->vin;
                $row[] = $p->no_pol;
                $row[] = $p->type;
                $row[] = $p->storing;
                $row[] = $p->date_open_wo;
                $row[] = $p->clockin;
                $row[] = $p->date_close_wo;
                $row[] = $p->clockout;
                $row[] = $p->status;
                $row[] = $p->pembuat;
                if($pel1->edit_level=="Y"){
                    $edit='                    
                    <button class="btn btn-sm btn-outline-success update-sparepart" title="Edit" data-id="'.$p->id.'"><i class="fa fa-edit"></i>
                    </button>';
                }                
                if($pel1->delete_level=="Y"){
                    $delete='
                    <button class="btn btn-sm btn-outline-danger delete-part" title="Delete" data-toggle="modal" data-target="#hapusPart" data-id="'.$p->id.'">
                    <i class="fa fa-trash"></i></button>';
                }
                if($pel1->upload_level=="Y"){
                    $upload='
                    <button class="btn btn-sm btn-outline-info update-stok" title="Edit" data-id="'.$p->id.'"><i class="fa fa-random"></i>
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
                $akses_system=$edit.$delete.$upload;
                $row[] = $akses_system;
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_service_appointment->count_all(),
            "recordsFiltered" => $this->Mod_service_appointment->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

	public function prosesTapointment()
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
            $result = $this->Mod_service_appointment->insertSparepart($data);

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
        $data['dataSatuan'] = $this->Mod_service_appointment->select_satuan();
        $data['dataType'] = $this->Mod_service_appointment->select_type();
        $data['dataKategori'] = $this->Mod_service_appointment->select_kategori();
        $data['dataKelompok'] = $this->Mod_service_appointment->select_kelompok();
        $data['dataSupplier'] = $this->Mod_service_appointment->select_supplier();
		$data['dataPart'] = $this->Mod_service_appointment->select_by_id_part($id);

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
			$result = $this->Mod_service_appointment->updateSparepart($data);

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
        $data['dataSatuan'] = $this->Mod_service_appointment->select_satuan();
        $data['dataType'] = $this->Mod_service_appointment->select_type();
        $data['dataKategori'] = $this->Mod_service_appointment->select_kategori();
        $data['dataKelompok'] = $this->Mod_service_appointment->select_kelompok();
        $data['dataSupplier'] = $this->Mod_service_appointment->select_supplier();
		$data['dataPart'] = $this->Mod_service_appointment->select_by_id_part($id);

		echo show_my_modal('warehouse/modals/modal_stok_manual', 'update-stok', $data, ' modal-md');
	}

	public function prosesUstok() 
    {
		
		$data 	= $this->input->post();
			$result = $this->Mod_service_appointment->updateStok($data);

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
        $result = $this->Mod_service_appointment->deletePart($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
}
