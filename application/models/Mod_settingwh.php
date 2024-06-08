<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_settingwh extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function select_satuan()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_satuan');
		//$this->db->join('tbl_pendidikan as b','a.pendidikan=b.id_pendidikan');
		//$this->db->join('tbl_supplier as c','a.supplier=c.id_supplier');
		//$this->db->join('tbl_departement as d','a.departement=d.id_departement');
		//$this->db->where('a.nip=',$id);

		$data = $this->db->get();

		return $data->result();
	}
	public function select_id_satuan($id)
	{
		$sql = " SELECT * FROM tbl_wh_satuan WHERE id_satuan='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertSatuan($data)
	{
		$sql = "INSERT INTO tbl_wh_satuan VALUES
		('','" . $data['kode_satuan'] . "','" . $data['satuan'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateSatuan($data)
	{
		$sql = "UPDATE tbl_wh_satuan SET kode_satuan='" . $data['kode_satuan'] . "',satuan='" . $data['satuan'] . "'
        WHERE id_satuan='" . $data['id_satuan'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteSat($id)
	{
		$sql = "DELETE FROM tbl_wh_satuan WHERE id_satuan='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	//end Satuan//

	public function select_kategori()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_kategori');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_kategori($id)
	{
		$sql = " SELECT * FROM tbl_wh_kategori WHERE id_kategori='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKategori($data)
	{
		$sql = "INSERT INTO tbl_wh_kategori VALUES
		('','" . $data['kode_kategori'] . "','" . $data['kategori'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKategori($data)
	{
		$sql = "UPDATE tbl_wh_kategori SET kode_kategori='" . $data['kode_kategori'] . "',kategori='" . $data['kategori'] . "'
        WHERE id_kategori='" . $data['id_kategori'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKat($id)
	{
		$sql = "DELETE FROM tbl_wh_kategori WHERE id_kategori='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kategori //
	public function select_type()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_type_mesin');
		$data = $this->db->get();

		return $data->result();
	}
	public function select_id_type($id)
	{
		$sql = " SELECT * FROM tbl_wh_type_mesin WHERE id_type='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertType($data)
	{
		$sql = "INSERT INTO tbl_wh_type_mesin VALUES
		('','" . $data['kode_type'] . "','" . $data['type'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateType($data)
	{
		$sql = "UPDATE tbl_wh_type_mesin SET kode_type='" . $data['kode_type'] . "', type_mesin='" . $data['type'] . "' WHERE id_type='" . $data['id_type'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteTy($id)
	{
		$sql = "DELETE FROM tbl_wh_type_mesin WHERE id_type='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	//end Type//

	public function select_supplier()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_supplier');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_supplier($id)
	{
		$sql = " SELECT * FROM tbl_wh_supplier WHERE id_supplier='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertSupplier($data)
	{
	$sql = "INSERT INTO tbl_wh_supplier SET kode_sup='" . $data['kode_supplier'] . "',nama_sup='" . $data['nama_supplier'] . "',alamat='" . $data['alamat'] . "',
	no_tlp='" . $data['no_tlp'] . "',tlp_person='" . $data['tlp_person'] . "'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateSupplier($data)
	{
		$sql = "UPDATE tbl_wh_supplier SET kode_sup='" . $data['kode_supplier'] . "',nama_sup='" . $data['nama_supplier'] . "',alamat='" . $data['alamat'] . "',
		no_tlp='" . $data['no_tlp'] . "',tlp_person='" . $data['tlp_person'] . "'
        WHERE kode_sup='" . $data['kode_supplier'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteSup($id)
	{
		$sql = "DELETE FROM tbl_wh_supplier WHERE id_supplier='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	//** end Supplier **//
	public function select_kelompok()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_kelompok');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_kelompok($id)
	{
		$sql = " SELECT * FROM tbl_wh_kelompok WHERE id_kelompok='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKelompok($data)
	{
		$sql = "INSERT INTO tbl_wh_kelompok VALUES
		('','" . $data['kode_kelompok'] . "','" . $data['kelompok'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKelompok($data)
	{
		$sql = "UPDATE tbl_wh_kelompok SET kode_kelompok='" . $data['kode_kelompok'] . "', kelompok='" . $data['kelompok'] . "'
        WHERE id_kelompok='" . $data['id_kelompok'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKel($id)
	{
		$sql = "DELETE FROM tbl_wh_kelompok WHERE id_kelompok='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kelompok //
}
