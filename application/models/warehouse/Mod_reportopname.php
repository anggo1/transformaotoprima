<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_reportopname extends CI_Model
{
    var $table = 'tbl_wh_barang';
    var $column_search = array('no_part', 'nama_part');
    var $column_order = array('no_part', 'nama_part');
    var $order = array('id_barang' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->from('tbl_wh_barang');
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
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        if ($_POST['length'] != -1)
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

        $this->db->from('tbl_wh_barang as a');
        //$this->db->join('tbl_menu as b','a.id_menu=b.id_menu');
        return $this->db->count_all_results();
    }
	
    function cari_opname($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('tbl_wh_opname.*', FALSE);
        $this->db->from('tbl_wh_opname');
		$this->db->where('tbl_wh_opname.tgl_opname BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    function cari_opname_detail($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('tbl_wh_opname.*', FALSE);
		$this->db->select('tbl_wh_detail_opname.*', FALSE);
		$this->db->select('tbl_wh_barang.hrg_awal', FALSE);
        $this->db->from('tbl_wh_opname');
        $this->db->join('tbl_wh_detail_opname','tbl_wh_detail_opname.id_opname=tbl_wh_opname.id_opname','left');
        $this->db->join('tbl_wh_barang','tbl_wh_barang.no_part=tbl_wh_detail_opname.no_part','left');
		$this->db->where('tbl_wh_opname.tgl_opname BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    
    function cari_opname_list_detail($id)
    {
		$this->db->select('a.*', FALSE);
		$this->db->select('b.*', FALSE);
		$this->db->select('c.hrg_awal', FALSE);
        $this->db->from('tbl_wh_opname AS a');
        $this->db->join('tbl_wh_detail_opname AS b','a.id_opname=b.id_opname','left');
        $this->db->join('tbl_wh_barang AS c','c.no_part=b.no_part','left');
		$this->db->where('a.id_opname', $id);

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
	function deleteOpname($id)
	{
		$sql1 = "DELETE FROM tbl_wh_opname WHERE id_opname='{$id}'";
		$this->db->query($sql1);
		$sql = "DELETE FROM tbl_wh_detail_opname WHERE id_opname='{$id}'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	//** end per PO**//
    //** Report Perbody */
    function cari_body($no_body,$ttmp1,$ttmp2)
    {
		$this->db->select('a.*', FALSE);
		$this->db->select('b.*', FALSE);
        $this->db->from('tbl_wh_part_keluar AS a');
        $this->db->join('tbl_wh_detail_part_keluar AS b','a.id_keluar=b.id_keluar','left');
		$this->db->where('a.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->where('a.no_body', $no_body);

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
   
    //** USer Data */
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
    function generateOpname($id)
    {
        $sql_po1 = "UPDATE tbl_wh_opname SET status='Y' WHERE id_opname='".$id."'";
        $this->db->query($sql_po1);
        
    $query=$this->db->query("SELECT no_part,stok_fisik
            FROM tbl_wh_detail_opname 
            WHERE id_opname = '{$id}'")->result();
    
            $data = array();
            foreach($query as $key=>$value){
                $total_stok=$value->stok_fisik;
                if(empty($total_stok)){
                    $total_stok='0';
                }
                $data[]  = array(
                'no_part'=>$value->no_part,
                'stok'=>$total_stok,
            );
        }
    $this->db->update_batch('tbl_wh_barang', $data,'no_part');
    return $this->db->affected_rows();
    }
    //** End USer data */
}