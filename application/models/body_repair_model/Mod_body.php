<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_body extends CI_Model
{
	var $table = 'tbl_wh_body';
	var $column_search = array('a.no_body','a.no_pol','a.type','a.merk','b.nama_pool','a.rute_aktif','a.karoseri','a.warna','a.kelas','a.strip'); 
	var $column_order = array('null','a.no_body','a.no_pol','a.type','a.merk','b.nama_pool','a.rute_aktif','a.karoseri','a.warna','a.kelas','a.strip');
	var $order = array('no_body' => 'desc'); 
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

		private function _get_datatables_query()
	{
		
		$this->db->from('tbl_wh_body AS a');
        $this->db->join('tbl_br_pool AS b','b.kode_pool=a.pool','left');
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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
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
		$this->db->from('tbl_wh_body');
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
        $this->db->where('tbl_akses_submenu.id_level=',$idlevel);
        $this->db->where('tbl_akses_submenu.id_submenu=',$id_sub);
        $data = $this->db->get();
        return $data->result();
    }
    public function select_pool()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_pool');
		$data = $this->db->get();
		return $data->result();
	}
	function select_by_id_body($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_wh_body');
        $this->db->where('tbl_wh_body.no_body=',$id);
        $data = $this->db->get();
        return $data->result();
    }
	function insertBody($data)
    {
        $sql = "INSERT INTO tbl_wh_body SET
        no_body		='".$data['no_body']."',
        type 		='".$data['type']."',
        no_pol 		='".$data['no_pol']."',
        merk 		='".$data['merk']."',
        thn_rangka  ='".$data['thn_rangka']."',
        thn_pembuatan='".$data['thn_pembuatan']."',
        karoseri    ='".$data['karoseri']."',
        warna		='".$data['warna']."',
        kelas	    ='".$data['kelas']."',
        strip       ='".$data['strip']."',
        keterangan  ='".$data['keterangan']."',
        kondisi  	='".$data['kondisi']."',
        status  	='".$data['status']."',
        pool 	 	='".$data['pool']."',
        no_rangka  	='".$data['no_rangka']."',
        no_mesin  	='".$data['no_mesin']."',
        rute_asli  	='".$data['rute_asli']."',
        rute_aktif 	='".$data['rute_aktif']."',
        kode_trayek	='".$data['kode_trayek']."',
        seat_daya 	='".$data['seat_daya']."',
        jns_pelayanan	='".$data['jns_pelayanan']."',
        imei_gps 	='".$data['imei_gps']."'
		";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function updateBody( $data)
    {
        $sql = "UPDATE tbl_wh_body SET
        type 		='".$data['type']."',
        no_pol 		='".$data['no_pol']."',
        merk 		='".$data['merk']."',
        thn_rangka  ='".$data['thn_rangka']."',
        thn_pembuatan='".$data['thn_pembuatan']."',
        karoseri    ='".$data['karoseri']."',
        warna		='".$data['warna']."',
        kelas	    ='".$data['kelas']."',
        strip       ='".$data['strip']."',
        keterangan  ='".$data['keterangan']."',
        kondisi  	='".$data['kondisi']."',
        status  	='".$data['status']."',
		pool 	 	='".$data['pool']."',
        no_rangka  	='".$data['no_rangka']."',
        no_mesin  	='".$data['no_mesin']."',
        rute_asli  	='".$data['rute_asli']."',
        rute_aktif 	='".$data['rute_aktif']."',
        kode_trayek	='".$data['kode_trayek']."',
        seat_daya 	='".$data['seat_daya']."',
        jns_pelayanan	='".$data['jns_pelayanan']."',
        imei_gps 	='".$data['imei_gps']."'
        WHERE no_body='".$data['no_body']."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }

        function get_bus($no_body)
    {   
        $this->db->where('no_body',$no_body);
        return $this->db->get('tbl_wh_body')->row();
    }

	function deleteBody($id)
    {
        $sql = "DELETE FROM tbl_wh_body WHERE no_body='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
	public function select_kodeBody($kode) {
        $query= $this->db->get_where('tbl_wh_body',array('no_body'=>$kode));
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