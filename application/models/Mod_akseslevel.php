<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_akseslevel extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    function getuser($id_level)
    {
        $this->db->where('id_level',$id_level);
        $this->db->where('is_active','Y');
        $this->db->from('tbl_user');
        return $this->db->count_all_results();
    }
    function showLevel()
    {
		$this->db->from('tbl_userlevel');

		$data = $this->db->get();

		return $data->result();
        
	}	
    function aksesMenu($id)
    {

		$this->db->from('tbl_akses_menu a');
        $this->db->join('tbl_menu b','a.id_menu=b.id_menu');
        $this->db->where('id_level',$id);

		$data = $this->db->get();

		return $data->result();
        
	}	
    function bukaAkses($id)
		{			
			$sql_update = "UPDATE tbl_akses_menu SET view_level ='Y' WHERE id ='{$id}'"; $this->db->query($sql_update);
			
		return $this->db->affected_rows();
			//return $data->row();
		}
    function tutupAkses($id)
		{			
			$sql_update = "UPDATE tbl_akses_menu SET view_level ='N' WHERE id ='{$id}'"; $this->db->query($sql_update);
			
		return $this->db->affected_rows();
			//return $data->row();
		}

     function getAll()
    {   
        
        return $this->db->get('tbl_userlevel');
    }

    function insertlevel($tabel, $data)
    {
        $insert = $this->db->insert($tabel, $data);
        return $insert;
    }

    function view($id)
    {   
        $this->db->where('id_level',$id);
        return $this->db->get('tbl_userlevel');
    }

    function getUserlevel($id)
    {   
        $this->db->where("id_level", $id);
        return $this->db->get("tbl_userlevel")->row();
    }

    function update($id, $data)
    {
        $this->db->where('id_level', $id);
		$this->db->update('tbl_userlevel', $data);
    }
    

    function delete($id, $table)
    {
        $this->db->where('id_level', $id);
        $this->db->delete($table);
    }

    function getId($nama_level)
    {
        $this->db->from($this->table);
        $this->db->where('nama_level', $nama_level);
        $query = $this->db->get();
        return $query->row();
    }


    function getMenu()
    {
        $this->db->select('id_menu');
        return $this->db->get('tbl_menu');
    }

    function getSubmenu()
    {

        return $this->db->get('tbl_submenu');
    }

    function getIdsubmenu($id_menu)
    {
        $this->db->where('id_menu',$id_menu);
        return $this->db->get('tbl_submenu');
    }
    function insert_akses_menu($tbl_akses_menu, $data)
    {
        $insert = $this->db->insert($tbl_akses_menu, $data);
        return $insert;
    }

    function insert_akses_submenu($tbl_akses_submenu, $data)
    {
        $insert = $this->db->insert($tbl_akses_submenu, $data);
        return $insert;
    }

    function deleteakses($id, $tbl_akses_menu){
        $this->db->where('id_level', $id);
        $this->db->delete($tbl_akses_menu);
    }

    function deleteaksessubmenu($id, $tbl_akses_submenu){
        $this->db->where('id_level', $id);
        $this->db->delete($tbl_akses_submenu);
    }

    function view_akses_menu($id)
    {   
        $this->db->join('tbl_menu b','a.id_menu=b.id_menu');
        $this->db->where('id_level',$id);
        return $this->db->get('tbl_akses_menu a');
    }

    function akses_submenu($id)
    {   
        $this->db->select("a.*,b.id_menu,b.nama_submenu,c.nama_menu");
        $this->db->join('tbl_submenu b','a.id_submenu=b.id_submenu');
        $this->db->join('tbl_menu c','b.id_menu=c.id_menu');
        $this->db->where('a.id_level',$id);
        $this->db->group_by('a.id_submenu');
        return $this->db->get('tbl_akses_submenu a');
    }

    function update_aksesmenu($id, $data)
    {
       $this->db->where('id', $id);
       $this->db->update('tbl_akses_menu', $data);
    }
    function update_akses_submenu($id, $data)
    {
       $this->db->where('id', $id);
       $this->db->update('tbl_akses_submenu', $data);
    }
}