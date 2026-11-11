<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_settingacc extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function select_kelompok()
	{
		$this->db->select('*');
		$this->db->from('tbl_acc_kelompok');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_kelompok($id)
	{
		$sql = " SELECT * FROM tbl_acc_kelompok WHERE id_kelompok='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKelompok($data)
	{
		$sql = "INSERT INTO tbl_acc_kelompok VALUES
		('','" . $this->db->escape_str((string) $data['kelompok']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKelompok($data)
	{
		$sql = "UPDATE tbl_acc_kelompok SET kelompok='" . $this->db->escape_str((string) $data['kelompok']) . "'
        WHERE id_kelompok='" . $this->db->escape_str((string) $data['id_kelompok']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKelompok($id)
	{
		$sql = "DELETE FROM tbl_acc_kelompok WHERE id_kelompok='".$this->db->escape_str((string) $id)."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kelompok //

	public function select_type()
	{
		$this->db->select('*');
		$this->db->from('tbl_acc_type');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_type($id)
	{
		$sql = " SELECT * FROM tbl_acc_type WHERE id_type='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertType($data)
	{
		$sql = "INSERT INTO tbl_acc_type VALUES
		('','" . $this->db->escape_str((string) $data['type']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateType($data)
	{
		$sql = "UPDATE tbl_acc_type SET type='" . $this->db->escape_str((string) $data['type']) . "'
        WHERE id_type='" . $this->db->escape_str((string) $data['id_type']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteType($id)
	{
		$sql = "DELETE FROM tbl_acc_type WHERE id_type='".$this->db->escape_str((string) $id)."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	//** end Type **//


	public function select_jenis()
	{
		$this->db->select('*');
		$this->db->from('tbl_acc_jenis');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_jenis($id)
	{
		$sql = " SELECT * FROM tbl_acc_jenis WHERE id_jenis='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertJenis($data)
	{
		$sql = "INSERT INTO tbl_acc_jenis VALUES
		('','" . $this->db->escape_str((string) $data['jenis_beban']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateJenis($data)
	{
		$sql = "UPDATE tbl_acc_jenis SET jenis_beban='" . $this->db->escape_str((string) $data['jenis_beban']) . "'
        WHERE id_jenis='" . $this->db->escape_str((string) $data['id_jenis']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteJenis($id)
	{
		$sql = "DELETE FROM tbl_acc_jenis WHERE id_jenis='".$this->db->escape_str((string) $id)."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Jenis //
	

	public function select_supplier()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_supplier');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_supplier($id)
	{
		$sql = " SELECT * FROM tbl_wh_supplier WHERE id_supplier='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function updateSupplier($data)
	{
		$sql = "UPDATE tbl_wh_supplier SET kode_akun='" . $this->db->escape_str((string) $data['kode_akun']) . "',nama_akun='" . $this->db->escape_str((string) $data['nama_akun']) . "',lawan_kode_akun='" . $this->db->escape_str((string) $data['lawan_kode_akun']) . "',
		lawan_nama_akun='" . $this->db->escape_str((string) $data['lawan_nama_akun']) . "',status_akun='" . $this->db->escape_str((string) $data['status_akun']) . "'
        WHERE kode_sup='" . $this->db->escape_str((string) $data['kode_supplier']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	//** end Supplier **//
	public function select_ref()
	{
		$this->db->select('*');
		$this->db->from('tbl_acc_ref');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_ref($id)
	{
		$sql = " SELECT * FROM tbl_acc_ref WHERE id_ref='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertRef($data)
	{
		$sql = "INSERT INTO tbl_acc_ref VALUES
		('','" . $this->db->escape_str((string) $data['no_ref']) . "','" . $this->db->escape_str((string) $data['keterangan']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateRef($data)
	{
		$sql = "UPDATE tbl_acc_ref SET no_ref='" . $this->db->escape_str((string) $data['no_ref']) . "',keterangan='" . $this->db->escape_str((string) $data['keterangan']) . "'
        WHERE id_ref='" . $this->db->escape_str((string) $data['id_ref']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteRef($id)
	{
		$sql = "DELETE FROM tbl_acc_ref WHERE id_ref='".$this->db->escape_str((string) $id)."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Referensi //
	

}
