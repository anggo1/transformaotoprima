<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_profil extends CI_Model
{
	public function update($data, $id) {
		$this->db->where("User_id", $id);
		$this->db->update("user", $data);

		return $this->db->affected_rows();
	}
	public function data_baru($data,$Password) {
		//$this->db->insert("user", $UserName,$FirstName,$Password,$data_foto);
		$sql = "INSERT INTO user VALUES('',
		'".$data['UserName']."',
		'".$Password."',
		'".$data['FirstName']."',
		'','".$data['User_Type']."',
		'".$data['foto']."')";
		
		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	public function select($id = '') {
		if ($id != '') {
			$this->db->where('User_id', $id);
		}

		$data = $this->db->get('user');

		return $data->row();
	}
}
