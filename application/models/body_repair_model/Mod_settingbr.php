<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_settingbr extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function select_laporan()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_ket_lapor');
		//$this->db->join('tbl_pendidikan as b','a.pendidikan=b.id_pendidikan');
		//$this->db->join('tbl_supplier as c','a.supplier=c.id_supplier');
		//$this->db->join('tbl_departement as d','a.departement=d.id_departement');
		//$this->db->where('a.nip=',$id);

		$data = $this->db->get();

		return $data->result();
	}
	public function select_id_lapor($id)
	{
		$sql = " SELECT * FROM tbl_br_ket_lapor WHERE id='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertLapor($data)
	{
		$sql = "INSERT INTO tbl_br_ket_lapor VALUES
		('','" . $data['kode'] . "','" . $data['keterangan'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateLapor($data)
	{
		$sql = "UPDATE tbl_br_ket_lapor SET kode='" . $data['kode'] . "',keterangan='" . $data['keterangan'] . "'
        WHERE id='" . $data['id'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteLap($id)
	{
		$sql = "DELETE FROM tbl_br_ket_lapor WHERE id='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	//end Satuan//

	public function select_kategori()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_kategori');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_kategori($id)
	{
		$sql = " SELECT * FROM tbl_br_kategori WHERE id_kategori='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKategori($data)
	{
		$sql = "INSERT INTO tbl_br_kategori VALUES
		('','" . $data['kode'] . "','" . $data['kategori'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKategori($data)
	{
		$sql = "UPDATE tbl_br_kategori SET kode='" . $data['kode'] . "',kategori='" . $data['kategori'] . "'
        WHERE id_kategori='" . $data['id_kategori'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKat($id)
	{
		$sql = "DELETE FROM tbl_br_kategori WHERE id_kategori='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kategori //

	public function select_pk()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_kat_pk');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_pk($id)
	{
		$sql = " SELECT * FROM tbl_br_kat_pk WHERE id='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertPk($data)
	{
		$sql = "INSERT INTO tbl_br_kat_pk VALUES
		('','" . $data['kode'] . "','" . $data['keterangan'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updatePk($data)
	{
		$sql = "UPDATE tbl_br_kat_pk SET kode='" . $data['kode'] . "',keterangan='" . $data['keterangan'] . "'
        WHERE id='" . $data['id'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deletePk($id)
	{
		$sql = "DELETE FROM tbl_br_kat_pk WHERE id='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	//** end Keterangan PK **//
	public function select_kelas()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_kelas');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_kelas($id)
	{
		$sql = " SELECT * FROM tbl_br_kelas WHERE id_kelas='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKelas($data)
	{
		$sql = "INSERT INTO tbl_br_kelas VALUES
		('','" . $data['kelas'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKelas($data)
	{
		$sql = "UPDATE tbl_br_kelas SET kelas='" . $data['kelas'] . "'
        WHERE id_kelas='" . $data['id_kelas'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKel($id)
	{
		$sql = "DELETE FROM tbl_br_kelas WHERE id_kelas='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kelas //

	public function select_pool()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_pool');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_pool($id)
	{
		$sql = " SELECT * FROM tbl_br_pool WHERE kode_pool='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertPool($data)
	{
		$ci_kons = get_instance();
		$query = "SELECT max(kode_pool) AS maxKode FROM tbl_br_pool";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 2, 3);
		$noUrut++;
		$huruf = "P-";
		$kode_po  = $huruf . sprintf("%03s", $noUrut);


		$sql = "INSERT INTO tbl_br_pool VALUES
		('$kode_po','" . $data['nama_pool'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updatePool($data)
	{
		$sql = "UPDATE tbl_br_pool SET nama_pool='" . $data['nama_pool'] . "'
        WHERE kode_pool='" . $data['kode_pool'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deletePool($id)
	{
		$sql = "DELETE FROM tbl_br_pool WHERE kode_pool='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	

}
