<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bast extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('body_repair_model/Mod_bast'));
        $this->load->helper('tgl_indo_helper');
	}

	public function index()
	{
		$data['page'] 		= "Berita Acara Serah Terima Kendaraan";
		$data['judul'] 		= "BAST";
		$this->load->helper('url');
		$this->template->load('layoutbackend', 'body_repair/bast',$data);
	}
	public function prosesBast()
    {
        $this->form_validation->set_rules('tgl_bast', 'Tanggal BAST', 'trim|required');

        $data=$this->input->post();
        if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_bast->insertBast($data);

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
	public function tampilDetail()
	{
		$data['dataDetail'] = $this->Mod_bast->select_detail();
		$this->load->view('body_repair/detail_bast', $data);
	}
	public function cetak()
	{
		$id 				= $_POST['id'];
		$data['dataBast'] = $this->Mod_bast->select_by_id($id);

		echo show_my_print('body_repair/modals/modal_cetak_bast', 'cetak-bast', $data, ' modal-xl');
	}
	public function deleteBast()
 	{       $id = $_POST['id'];
        $result = $this->Mod_bast->deleteBast($id);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function cari_body()
		{
        ini_set('memory_limit','512M');
        set_time_limit(3600);
        $list = $this->Mod_bast->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $bd) {
            $no++;
            
            $row = array();
            $nopolnye= preg_replace('/\s+/', '', $bd->no_pol);
            $bodynye= preg_replace('/\s+/', '', $bd->no_body);
			$row[] = '<button type="button" class="btn btn-sm btn-outline-success" onClick=selectBody("'.$bodynye.'","'.$nopolnye.'")><i class="fa fa-check"></i></button>';
            $row[] = $bd->no_body;
            $row[] = $bd->no_pol;
            $row[] = $bd->type;
            $row[] = $bd->merk;           
            $data[] = $row;
        }
    
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mod_bast->count_all(),
                        "recordsFiltered" => $this->Mod_bast->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

}
