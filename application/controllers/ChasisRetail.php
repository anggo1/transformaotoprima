<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Model $Mod_chasis_retail
 * @property CI_DB_query_builder $db
 */
class ChasisRetail extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        // ensure database is loaded for $this->db usage
        $this->load->database();
        $this->load->library('form_validation');
        // load models with explicit property names to avoid undefined property errors
        $this->load->model('marketing_model/Mod_chasis_retail', 'Mod_chasis_retail');
        $this->load->model('Mod_menu');
        $this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
        $data['page']         = "Daftar Chasis Retail";
        $data['judul']         = "Chasis Retail";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $data['dataCus'] = $this->Mod_chasis_retail->select_customer();

        $link = $this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_chasis_retail->get_by_nama($link);
        foreach ($get_id as $idnye) {
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub = $idnye->id_submenu;
        }
        $data['viewLevel']  = $this->Mod_chasis_retail->select_by_level($idlevel, $id_sub);

        echo show_my_modal('marketing/modals/modal_tambah_chasis_retail', 'tambah-chasis-retail', $data, ' modal-lg');
        $this->template->load('layoutbackend', 'marketing/data_chasis_retail', $data);
    }

    public function ajax_list()
    {
        $link = $this->uri->segment(1);
        $idlevel = $this->session->userdata['id_level'];
        $get_id = $this->Mod_chasis_retail->get_by_nama($link);
        foreach ($get_id as $idnye) {
            $row1 = array();
            $row1[] = $idnye->id_submenu;
            $id_sub = $idnye->id_submenu;
        }
        $viewLevel = $this->Mod_chasis_retail->select_by_level($idlevel, $id_sub);

        foreach ($viewLevel as $b) {
            $row1 = array();
            $row1[] = $b->id_submenu;

            ini_set('memory_limit', '512M');
            set_time_limit(3600);
            $list = $this->Mod_chasis_retail->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $cs) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = tglIndoSedang($cs->tgl_masuk);
                $row[] = $cs->retail;
                $row[] = $cs->nama_pemesan;
                $row[] = $cs->alamat_pemesan;
                $row[] = $cs->no_npwp;
                $row[] = $cs->nama_npwp;
                $row[] = $cs->alamat_npwp;
                $row[] = $cs->telp_pemesan;
                $row[] = $cs->contact_person;
                $row[] = $cs->nama_bpkb;
                $row[] = $cs->no_ktp;
                $row[] = $cs->alamat_faktur;
                $row[] = $cs->type;
                $row[] = $cs->no_rangka;
                $row[] = $cs->no_mesin;
                $row[] = $cs->sales;
                $row[] = $cs->gesekan;
                $row[] = $cs->thn_produksi;
                $row[] = $cs->pengiriman;
                $row[] = number_format($cs->harga_retail);
                if ($b->edit_level == "Y" && $b->delete_level == "Y") {
                    if ($cs->status == 'N') {
                        $row[] = '<button class="btn btn-sm btn-outline-info proses-spk" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-list"> SPK</i></button> &nbsp;'
                            . '<button class="btn btn-sm btn-outline-success update-chasis" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-edit"> Edit</i></button> &nbsp;'
                            . '<button class="btn btn-xs btn-outline-danger delete-chasis" title="Delete" data-toggle="modal" data-target="#hapusChasis" data-id="' . $cs->id_chasis . '|' . $cs->chasis_id . '|' . $cs->jumlah . '"><i class="fa fa-trash"></i> Delete</button>';
                    } elseif ($cs->status == 'P') {
                        $row[] = '<button class="btn btn-xs btn-outline-info print-spk" title="Edit" id="cetak-ulang" data-id="' . $cs->id_chasis . '"><i class="fa fa-print"></i> Print SPK</button> &nbsp;'
                            . '<button class="btn btn-xs btn-outline-success update-spk" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-edit"></i> Edit SPK</button> &nbsp;'
                            . '<button class="btn btn-xs btn-outline-primary close-chasis" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-close"></i> Closing</button> &nbsp;';
                    }
                }
                if ($b->edit_level == "Y" && $b->delete_level == "N") {
                    if ($cs->status == 'N') {
                        $row[] = '<button class="btn btn-xs btn-outline-info proses-spk" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-list"> SPK</i></button> &nbsp;'
                            . '<button class="btn btn-xs btn-outline-success update-chasis" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-edit"> Edit</i></button> &nbsp;';
                    } elseif ($cs->status == 'P') {
                        $row[] = '<button class="btn btn-xs btn-outline-info print-spk" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-print"></i> Print SPK</button> &nbsp;'
                            . '<button class="btn btn-xs btn-outline-success update-spk" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-edit"></i> Edit SPK</button> &nbsp;'
                            . '<button class="btn btn-xs btn-outline-success close-chasis" title="Edit" data-id="' . $cs->id_chasis . '"><i class="fa fa-close"></i> Closing</button> &nbsp;';
                    }
                } else {
                    $row[] = '';
                }
                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_chasis_retail->count_all(),
            "recordsFiltered" => $this->Mod_chasis_retail->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function prosesTchasis()
    {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_chasis_retail->insertChasis($data);

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
    public function updateChasis()
    {
        $id                 = trim($_POST['id']);
        $data['dataCus'] = $this->Mod_chasis_retail->select_customer();
        $data['dataChasis'] = $this->Mod_chasis_retail->select_by_id_chasis($id);
        //echo json_encode($data);

        echo show_my_modal('marketing/modals/modal_tambah_chasis_retail', 'update-chasis', $data, ' modal-lg');
    }

    public function prosesUchasis()
    {

        $this->form_validation->set_rules('type', 'Type', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_chasis_retail->updateChasis($data);

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

    public function deleteChasis()
    {
        $idS = $_POST['id'];
        $kat = explode('|', $idS);
        $id = $kat[0];
        $chasis_id = $kat[1];
        $jumlah = $kat[2];
        $result = $this->Mod_chasis_retail->deleteChasis($id, $chasis_id, $jumlah);

        if ($result > 0) {
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function dataChasis()
    {
        //$tgl_po = $_GET['tgl_po'];
        $data['dataChasis'] = $this->Mod_chasis_retail->select_chasis();
        //$this->load->view('warehouse/data_po_partmasuk', $data);
        $this->load->view('marketing/data_cari_chasis', $data);
    }

    public function updateKeterangan()
    {
        $id = $_POST['id'];
        $remark = $_POST['remark'];
        $data['dataPo'] = $this->Mod_chasis_retail->update_remark($id, $remark);
    }
    public function tambahKeterangan()
    {
        $id = $_POST['id'];
        $no_spk = $_POST['no_spk'];
        $keterangan = $_POST['keterangan'];
        $data['dataPo'] = $this->Mod_chasis_retail->insertKEterangan($id, $no_spk, $keterangan);
    }
    public function tambahNote()
    {
        $id = $_POST['id'];
        $data['dataPo'] = $this->Mod_chasis_retail->insertNote($id);
    }
    public function prosesSpk()
    {
        $cari = "SELECT jumlah FROM tbl_mk_chasis_retail WHERE id_chasis = '" . $data['id_chasis'] . "'";
		$h = $this->db->query($cari)->row();
		$dataJumlah = $h->jumlah;
		$hsl = $data['jml_unit'];
		if ($hsl > $dataJumlah) {
			$hasil = $dataJumlah - $hsl ;
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah + " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);
		}
		if ($hsl < $dataJumlah) {
			$hasil = $hsl - $dataJumlah ;
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah - " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);
		}

        $sekarang = date("Y-m");
        $this->form_validation->set_rules('nama_pemesan', 'Nama Pemesan', 'trim|required');
        $this->form_validation->set_rules('plat_kendaraan', 'Plat Kendaraan', 'trim|required');
        $this->form_validation->set_rules('type_body', 'Type Body', 'trim|required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'trim|required');
        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->input->post();

            $hrg_ofr = $data['hrg_on_the_road'];
            $ofr = str_replace(",", "", $hrg_ofr);
            $bbn = $data['biaya_bbn'];
            $h_bbn = str_replace(",", "", $bbn);
            $hrg_otr = $data['hrg_on_the_road'];
            $otr = str_replace(",", "", $hrg_otr);

            $t_1 = $data['hrg_tambahan_1'];
            $tambah1 = str_replace(",", "", $t_1);

            $t_2 = $data['hrg_tambahan_2'];
            $tambah2 = str_replace(",", "", $t_2);

            $t_3 = $data['hrg_tambahan_3'];
            $tambah3 = str_replace(",", "", $t_3);

            $t_4 = $data['hrg_tambahan_4'];
            $tambah4 = str_replace(",", "", $t_4);

            $hjp = $data['hrg_jual_perunit'];
            $perunit = str_replace(",", "", $hjp);

            $thp = $data['total_harga_jual'];
            $total_harga = str_replace(",", "", $thp);

            $no_spk1 = $data['no_ref'];
            $ciri = $data['kode'];
            if (empty($ciri)) {
                $no_spk = $no_spk1;
            } else {
                $no_spk = $no_spk1 . '-' . $ciri;
            }

            $data = array(
                'no_urut'      => $data['no_urut'],
                'no_spk'   => $no_spk,
                'tgl_spk'      => date("Y-m-d"),
                'nama_pemesan'    => $data['nama_pemesan'],
                'id_chasis'    => $data['id_chasis'],
                'alamat_pemesan'    => $data['alamat_pemesan'],
                'telp_pemesan' => $data['telp_pemesan'],
                'faktur_pajak' => $data['faktur_pajak'],
                'npwp_pemesan' => $data['npwp_pemesan'],
                'nama_npwp_pemesan' => $data['nama_npwp_pemesan'],
                'alamat_npwp' => $data['alamat_npwp'],
                'contact_person' => $data['contact_person'],
                'telp_contact_person' => $data['telp_contact_person'],
                'nama_bpkb' => $data['nama_bpkb'],
                'no_ktp' => $data['no_ktp'],
                'alamat_faktur' => $data['alamat_faktur'],
                'plat_kendaraan' => $data['plat_kendaraan'],
                'type_body' => $data['type_body'],
                'jumlah' => $data['jml_unit'],
                'kategori' => $data['kategori'],
                'type_kendaraan' => $data['type_kendaraan'],
                'warna_tahun' => $data['warna_tahun'],
                'harga_retail' => $ofr,
                'biaya_bbn' => $h_bbn,
                'hrg_on_the_road' => $otr,
                'tambahan_1' => $data['tambahan_1'],
                'hrg_tambahan_1' => $tambah1,
                'tambahan_2' => $data['tambahan_2'],
                'hrg_tambahan_2' => $tambah2,
                'tambahan_3' => $data['tambahan_3'],
                'hrg_tambahan_3' => $tambah3,
                'tambahan_4' => $data['tambahan_4'],
                'hrg_tambahan_4' => $tambah4,
                'hrg_jual_perunit' => $perunit,
                'total_hrg_jual' => $total_harga,
                'user'       => $data['user'],
                'status' => 'P'
            );
            $this->db->insert('tbl_mk_spk', $data);
            // determine if insert succeeded
            $result = $this->db->affected_rows();
            $this->db->where('id_chasis', $data['id_chasis'])->update('tbl_mk_chasis_retail', array('status' => 'P'));
            $data     = $this->input->post();
            //$sql2 = "UPDATE tbl_mk_chasis SET status = 'P' WHERE id_chasis='" . $data['id_chasis'] . "'";

            if ($result > 0) {
                $out['dataRef'] = $data['no_urut'];
                $out['dataPo'] = $data['no_urut'];
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
    public function Proses_updateSpk()
    {
        $cari = "SELECT jumlah FROM tbl_mk_chasis_retail WHERE id_chasis = '" . $data['id_chasis'] . "'";
		$h = $this->db->query($cari)->row();
		$dataJumlah = $h->jumlah;
		$hsl = $data['jml_unit'];
		if ($hsl > $dataJumlah) {
			$hasil = $dataJumlah - $hsl ;
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah + " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);
		}
		if ($hsl < $dataJumlah) {
			$hasil = $hsl - $dataJumlah ;
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah - " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);
		}

        $sekarang = date("Y-m");
        $this->form_validation->set_rules('nama_pemesan', 'Nama Pemesan', 'trim|required');
        $this->form_validation->set_rules('plat_kendaraan', 'Plat Kendaraan', 'trim|required');
        $this->form_validation->set_rules('type_body', 'Type Body', 'trim|required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'trim|required');
        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->input->post();

            $hrg_ofr = $data['harga_retail'];
            $ofr = str_replace(",", "", $hrg_ofr);
            $bbn = $data['biaya_bbn'];
            $h_bbn = str_replace(",", "", $bbn);
            $hrg_otr = $data['hrg_on_the_road'];
            $otr = str_replace(",", "", $hrg_otr);

            $t_1 = $data['hrg_tambahan_1'];
            $tambah1 = str_replace(",", "", $t_1);

            $t_2 = $data['hrg_tambahan_2'];
            $tambah2 = str_replace(",", "", $t_2);

            $t_3 = $data['hrg_tambahan_3'];
            $tambah3 = str_replace(",", "", $t_3);

            $t_4 = $data['hrg_tambahan_4'];
            $tambah4 = str_replace(",", "", $t_4);

            $hjp = $data['hrg_jual_perunit'];
            $perunit = str_replace(",", "", $hjp);

            $thp = $data['total_harga_jual'];
            $total_harga = str_replace(",", "", $thp);

            $data = array(
                'nama_pemesan'    => $data['nama_pemesan'],
                'id_chasis'    => $data['id_chasis'],
                'alamat_pemesan'    => $data['alamat_pemesan'],
                'telp_pemesan' => $data['telp_pemesan'],
                'faktur_pajak' => $data['faktur_pajak'],
                'npwp_pemesan' => $data['npwp_pemesan'],
                'nama_npwp_pemesan' => $data['nama_npwp_pemesan'],
                'alamat_npwp' => $data['alamat_npwp'],
                'contact_person' => $data['contact_person'],
                'telp_contact_person' => $data['telp_contact_person'],
                'nama_bpkb' => $data['nama_bpkb'],
                'no_ktp' => $data['no_ktp'],
                'alamat_faktur' => $data['alamat_faktur'],
                'plat_kendaraan' => $data['plat_kendaraan'],
                'type_body' => $data['type_body'],
                'jumlah' => $data['jumlah'],
                'kategori' => $data['kategori'],
                'type_kendaraan' => $data['type_kendaraan'],
                'warna_tahun' => $data['warna_tahun'],
                'harga_retail' => $ofr,
                'biaya_bbn' => $h_bbn,
                'hrg_on_the_road' => $otr,
                'tambahan_1' => $data['tambahan_1'],
                'hrg_tambahan_1' => $tambah1,
                'tambahan_2' => $data['tambahan_2'],
                'hrg_tambahan_2' => $tambah2,
                'tambahan_3' => $data['tambahan_3'],
                'hrg_tambahan_3' => $tambah3,
                'tambahan_4' => $data['tambahan_4'],
                'hrg_tambahan_4' => $tambah4,
                'hrg_jual_perunit' => $perunit,
                'total_hrg_jual' => $total_harga
            );
            $this->db->update('tbl_mk_spk', $data, array('id_spk' => $id_spk));
            // determine if update succeeded
            $result = $this->db->affected_rows();
            $data     = $this->input->post();
            //$sql2 = "UPDATE tbl_mk_chasis SET status = 'P' WHERE id_chasis='" . $data['id_chasis'] . "'";

            if ($result > 0) {
                $out['dataRef'] = $data['no_urut'];
                $out['dataPo'] = $data['no_urut'];
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Data telah dirubah!', '20px');
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
    public function processSPK()
    {
        $id = trim($_POST['id']);
        // $kat = explode('|', $idS);
        //$kode_cus = $kat[1];
        //$id = $kat[0];

        $data['apl'] = $this->db->get("aplikasi")->row();
        $data['dataRetail'] = $this->Mod_chasis_retail->select_by_id_chasis_spk($id);
        echo show_my_modal('marketing/modals/modal_keterangan', 'tambah-keterangan', $data);

        $this->load->view('marketing/new_spk', $data);

        //echo show_my_modal('service/modals/modal_tambah_work_order', 'process-work-order', $data, ' modal-xl');
    }
    public function updateSpk()
    {
        $id                 = trim($_POST['id']);
        $data['dataSpk'] = $this->Mod_chasis_retail->select_by_id_spk($id);
        echo show_my_modal('marketing/modals/modal_keterangan', 'tambah-keterangan', $data);

        $this->load->view('marketing/edit_spk', $data);
    }

    public function prosesUspk()
    {

        $this->form_validation->set_rules('type', 'Type', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_chasis_retail->updateSpk($data);

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

    public function tampilDetail()
    {
        $id                 = $_POST['id_estimasi_penawaran'];
        $data['dataDetail'] = $this->Mod_chasis_retail->select_detail($id);
        $this->load->view('marketing/detail_estimasi_penawaran', $data);
    }
    public function tampilKeterangan()
    {
        $id                 = $_POST['no_urut'];
        $data['dataKet'] = $this->Mod_chasis_retail->select_keterangan($id);
        $this->load->view('marketing/data_keterangan_spk', $data);
    }
    public function view()
    {
        $id = $this->input->post('id');
        $table = $this->input->post('table');
        $data['table'] = $table;
        $data['data_field'] = $this->db->field_data($table);
        $data['dataDetail'] = $this->Mod_userlevel->view($id)->result_array();
        $this->load->view('marketing/detail_estimasi_penawaran', $data);
    }
    public function deleteKeterangan()
    {
        $id = $_POST['id'];
        $result = $this->Mod_chasis_retail->deleteDetail_spk($id);
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
        $id                 = $_POST['id'];
        $data['dataSpk'] = $this->Mod_chasis_retail->select_by_id($id);
        $data['detailSpk'] = $this->Mod_chasis_retail->select_keterangan($id);

        echo show_my_print('marketing/modals/modal_cetak_spk', 'cetak-po', $data, ' modal-xl');
    }
    public function cetak_ulang()
    {
        $idS                 = $_POST['id'];
        $ci_kons = get_instance();
        $query = "SELECT no_urut FROM tbl_mk_spk WHERE id_chasis ='" . $idS . "'";
        $hasil = $ci_kons->db->query($query)->row_array();
        $id = $hasil['no_urut'];

        $data['dataSpk'] = $this->Mod_chasis_retail->select_by_id($id);
        $data['detailSpk'] = $this->Mod_chasis_retail->select_keterangan($id);

        echo show_my_print('marketing/modals/modal_cetak_spk', 'cetak-po', $data, ' modal-xl');
    }
    public function cetak_int()
    {
        $id                 = $_POST['id'];
        $data['dataPo'] = $this->Mod_chasis_retail->select_by_id($id);
        $data['detailPo'] = $this->Mod_chasis_retail->select_detail($id);

        echo show_my_print('marketing/modals/modal_cetak_spk_internal', 'cetak-po-int', $data, ' modal-xl');
    }
}
