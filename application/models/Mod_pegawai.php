<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_pegawai extends CI_Model {

    var $table = 'tbl_hrd_pegawai';
    var $column_search = array('a.nip','a.nama_depan','a.nama_belakang','a.tgl_lahir','b.pendidikan','c.jabatan','d.departement');
    var $column_order = array('null','a.nip','a.nama_depan','a.tgl_lahir','b.pendidikan','c.jabatan','d.departement');
    var $order = array('nip' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term='')
    {
        
        $this->db->select('a.*,b.pendidikan,c.jabatan,d.departement');
        $this->db->from('tbl_hrd_pegawai as a');
        $this->db->join('tbl_hrd_pendidikan as b','a.pendidikan=b.id_pendidikan');
        $this->db->join('tbl_hrd_jabatan as c','a.jabatan=c.id_jabatan');
        $this->db->join('tbl_hrd_departement as d','a.departement=d.id_departement');
        $i = 0;
    
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
//
    function get_datatables()
    {
        $term = $_REQUEST['search']['value'];  
        $this->_get_datatables_query($term);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $term = $_REQUEST['search']['value'];  
        $this->_get_datatables_query($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        
        $this->db->from('tbl_hrd_pegawai as a');
        //$this->db->join('tbl_menu as b','a.id_menu=b.id_menu');
        return $this->db->count_all_results();
    }

    function getAll()
    {
        $this->db->select('tbl_hrd_pegawai');
        //$this->db->join('tbl_menu b','a.id_menu=b.id_menu');
       return $this->db->get('tbl_hrd_pegawai a');
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
        $this->db->where('tbl_akses_submenu.id_level=',$idlevel);
        $this->db->where('tbl_akses_submenu.id_submenu=',$id_sub);
        $data = $this->db->get();
        return $data->result();
    }
    public function select_pendidikan() {
		$sql = " SELECT * FROM tbl_hrd_pendidikan";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function select_bagian() {
		$sql = " SELECT * FROM tbl_hrd_departement";

		$data = $this->db->query($sql);

		return $data->result();
	}
    public function select_posisi() {
		$sql = " SELECT * FROM tbl_hrd_jabatan";

		$data = $this->db->query($sql);

		return $data->result();
	}
    function view_pegawai($id)
    {	
    	$this->db->select('a.*,b.pendidikan,c.jabatan,d.departement');
        $this->db->from('tbl_hrd_pegawai as a');
        $this->db->join('tbl_hrd_pendidikan as b','a.pendidikan=b.id_pendidikan');
        $this->db->join('tbl_hrd_jabatan as c','a.jabatan=c.id_jabatan');
        $this->db->join('tbl_hrd_departement as d','a.departement=d.id_departement');
        $this->db->where('a.nip=',$id);

		$data = $this->db->get();

		return $data->result();
    }

    function get_pegawai($id)
    {
        $this->db->where('nip',$id);
        return $this->db->get('tbl_hrd_pegawai')->row();
    }

    function edit_submenu($id)
    {
    $this->db->where('nip',$id);
    return $this->db->get('tbl_hrd_pegawai');
    }

    function insertsubmenu($tabel, $data)
    {
        $insert = $this->db->insert($tabel, $data);
        return $insert;
    }

    function insert_akses_submenu($tbl_akses_submenu, $data)
    {
        $insert = $this->db->insert($tbl_akses_submenu, $data);
        return $insert;
    }

    function updatepegawai($nip, $data)
    {
        $this->db->where('nip', $nip);
        $this->db->update('tbl_hrd_pegawai', $data);
    }
    function getImage($nip)
    {
        $this->db->select('image');
        $this->db->from('tbl_hrd_pegawai');
        $this->db->where('nip', $nip);
        return $this->db->get();
    }
    function deletepegawai($id, $table)
    {
        $this->db->where('nip', $id);
        $this->db->delete($table);
    }

}

/* End of file Mod_pegawai.php */