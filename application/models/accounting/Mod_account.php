<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_account extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function select_acc()
	{
		$sql = "SELECT * FROM `tbl_acc_akun`
		LEFT JOIN `tbl_acc_jenis` ON `tbl_acc_jenis`.`id_jenis`=`tbl_acc_akun`.`jenis_beban`
		LEFT JOIN `tbl_acc_kelompok` ON `tbl_acc_kelompok`.`id_kelompok`=`tbl_acc_akun`.`kelompok`
		LEFT JOIN `tbl_acc_type` ON `tbl_acc_type`.`id_type`=`tbl_acc_akun`.`type_akun`";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertAkun($data)
	{
		$sql = "INSERT INTO tbl_acc_akun SET
		id         	='',
		kode_akun 	='".$data['kode_akun']."',
		nama_akun  	='".$data['nama_akun']."',
		kelompok    ='".$data['kelompok']."',
		type_akun  ='".$data['type']."',
		jenis_beban ='".$data['jns_beban']."'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
    function updateAkun($data)
    {
        $sql = "UPDATE tbl_acc_akun SET
		nama_akun  	='".$data['nama_akun']."',
		kelompok    ='".$data['kelompok']."',
		type_akun  ='".$data['type']."',
		jenis_beban ='".$data['jns_beban']."'
        WHERE kode_akun='".$data['kode_akun']."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
	public function select_id_akun($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_acc_akun');
		$this->db->where('id',$id);
		$data = $this->db->get();
		return $data->result();
	}
	public function select_kelompok()
	{
		$this->db->select('*');
		$this->db->from('tbl_acc_kelompok');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_type()
	{
		$this->db->select('*');
		$this->db->from('tbl_acc_type');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_jenis()
	{
		$this->db->select('*');
		$this->db->from('tbl_acc_jenis');
		$data = $this->db->get();
		return $data->result();
	}
	function deleteAkun($id)
    {
        $sql = "DELETE FROM tbl_acc_akun WHERE id='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}
