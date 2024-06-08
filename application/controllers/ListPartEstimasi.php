<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ListPartEstimasi extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('body_repair_model/Mod_listestimasi', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
		$data['page'] 		= "List Barang Estimator";
		$data['judul'] 		= "List Part";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $data['dataPk'] = $this->Mod_listestimasi->select_pk();
        echo show_my_modal('body_repair/modals/modal_list_part_estimasi', 'tambah-list', $data, ' modal-md');
        $this->template->load('layoutbackend', 'body_repair/list_part_estimasi', $data);
    }
    public function ajax_estimasi()
	{
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$list = $this->Mod_listestimasi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pel) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $pel->proses;
			$row[] = $pel->no_part;
			$row[] = $pel->nama_part;
			$row[] = $pel->qty;
			$row[] = '
            <button class="btn btn-xs btn-outline-danger delete-part" title="Delete" data-toggle="modal" data-target="#hapusPart" data-id="'.$pel->id_list.'"><i class="fa fa-trash"></i> Hapus</button>';
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Mod_listestimasi->count_all(),
			"recordsFiltered" => $this->Mod_listestimasi->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}
    public function prosesList()
    {
        $this->form_validation->set_rules('proses', 'Proses Pekerjaan', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_listestimasi->insertList($data);

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
    public function deleteList()
    {
        $id = $_POST['id'];
        $result = $this->Mod_listestimasi->deletePart($id);

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