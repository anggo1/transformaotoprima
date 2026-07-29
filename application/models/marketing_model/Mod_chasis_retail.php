<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_chasis_retail extends CI_Model
{
	var $table = 'tbl_mk_chasis_retail';
	var $column_search = array('tgl_masuk', 'retail', 'type', 'no_rangka', 'no_mesin', 'sales', 'gesekan', 'thn_produksi', 'nama_customer', 'pengiriman');
	var $column_order = array('null', 'tgl_masuk', 'retail', 'type', 'no_rangka', 'no_mesin', 'sales', 'gesekan', 'thn_produksi', 'nama_customer', 'pengiriman');
	var $order = array('id_chasis' => 'desc');
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->from('tbl_mk_chasis_retail');
		$i = 0;

		foreach ($this->column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all()
	{
		$this->db->from('tbl_mk_chasis');
		return $this->db->count_all_results();
	}

	public function get_by_nama($link)
	{
		$this->db->select('id_submenu');
		$this->db->from('tbl_submenu');
		$this->db->where('link', $link);
		$query = $this->db->get();
		return $query->result();
	}
	function select_by_level($idlevel, $id_sub)
	{
		$this->db->select('*');
		$this->db->from('tbl_akses_submenu');
		//$this->db->join('tbl_akses_submenu','tbl_akses_submenu.id_submenu=tbl_akses_menu.id_menu','inner');
		$this->db->where('tbl_akses_submenu.id_level=', $idlevel);
		$this->db->where('tbl_akses_submenu.id_submenu=', $id_sub);
		$data = $this->db->get();
		return $data->result();
	}
	public function select_chasis()
	{
		$this->db->select('*');
		$this->db->from('tbl_mk_chasis');
		$data = $this->db->get();
		return $data->result();
	}
	function select_by_id_chasis($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_mk_chasis_retail');
		$this->db->where('tbl_mk_chasis_retail.id_chasis=', $id);
		$data = $this->db->get();
		return $data->result();
	}
	function insertChasis($data)
	{
		$tgl_input = date('Y-m-d H:i:s');
		$tgl_masuk = $data['tgl_masuk'];
		$tgl1 = explode('-', $tgl_masuk);
		$tgl_masuknya = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
		$sql = "INSERT INTO tbl_mk_chasis_retail SET
        tgl_masuk		='" . $tgl_masuknya . "',
        retail 			='" . $data['retail'] . "',
        nama_pemesan 		='" . $data['nama_pemesan'] . "',
        alamat_pemesan 		='" . $data['alamat_pemesan'] . "',
        no_npwp 			='" . $data['no_npwp'] . "',
        alamat_npwp 		='" . $data['alamat_npwp'] . "',
        telp_pemesan 		='" . $data['telp_pemesan'] . "',
        contact_person 		='" . $data['contact_person'] . "',
        telp_contact_person ='" . $data['telp_contact_person'] . "',
        nama_bpkb 			='" . $data['nama_bpkb'] . "',
        no_ktp 			='" . $data['no_ktp'] . "',
        nama_npwp 		='" . $data['nama_npwp'] . "',
        type 			='" . $data['type'] . "',
        no_rangka 		='" . $data['no_rangka'] . "',
        no_mesin  		='" . $data['no_mesin'] . "',
        sales			='" . $data['sales'] . "',
        gesekan    		='" . $data['gesekan'] . "',
        thn_produksi	='" . $data['thn_produksi'] . "',
        nama_customer	='" . $data['nama_customer'] . "',
        pengiriman      ='" . $data['pengiriman'] . "',
        status_chasis  	='S',
        harga_retail      ='" . $data['harga_retail'] . "',
        tgl_input  		='" . $tgl_input . "',
        user  			='" . $data['user'] . "'
		";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	function updateChasis($data)
	{
		$sql = "UPDATE tbl_mk_chasis_retail SET
        retail 			='" . $data['retail'] . "',
        nama_pemesan 		='" . $data['nama_pemesan'] . "',
        alamat_pemesan 		='" . $data['alamat_pemesan'] . "',
        no_npwp 			='" . $data['no_npwp'] . "',
        alamat_npwp 		='" . $data['alamat_npwp'] . "',
        telp_pemesan 		='" . $data['telp_pemesan'] . "',
        contact_person 		='" . $data['contact_person'] . "',
        telp_contact_person ='" . $data['telp_contact_person'] . "',
        nama_bpkb 			='" . $data['nama_bpkb'] . "',
        no_ktp 			='" . $data['no_ktp'] . "',
        nama_npwp 		='" . $data['nama_npwp'] . "',
        type 			='" . $data['type'] . "',
        no_rangka 		='" . $data['no_rangka'] . "',
        no_mesin  		='" . $data['no_mesin'] . "',
        sales			='" . $data['sales'] . "',
        gesekan    		='" . $data['gesekan'] . "',
        thn_produksi	='" . $data['thn_produksi'] . "',
        nama_customer	='" . $data['nama_customer'] . "',
        pengiriman      ='" . $data['pengiriman'] . "',
        status_chasis  	='S',
        harga_retail      ='" . $data['harga_retail'] . "'
        WHERE id_chasis='" . $data['id_chasis'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	function get_bus($no_body)
	{
		$this->db->where('no_body', $no_body);
		return $this->db->get('tbl_mk_chasis')->row();
	}

	function deleteChasis($id)
	{
		$sql = "DELETE FROM tbl_mk_chasis WHERE id_chasis='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function select_kodeBody($kode)
	{
		$query = $this->db->get_where('tbl_mk_chasis', array('no_body' => $kode));
		return $query;
	}
	public function select_kelas()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_kelas');
		$data = $this->db->get();
		return $data->result();
	}
}
