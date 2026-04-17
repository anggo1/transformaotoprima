<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_operation_time extends CI_Model
{
 	var $table = 'tbl_after_sales_operation_time';
    var $column_search = array('code','duration','im','description');
    var $column_order = array('null','code','duration','im','description');
    var $order = array('id_x' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('id_x,code,duration,im,description,price,customer_price');
        $this->db->from('tbl_after_sales_operation_time');
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

        $this->db->from('tbl_after_sales_operation_time');
        //$this->db->join('tbl_menu as b','a.id_menu=b.id_menu');
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
	public function select_operation_time()
	{
		$this->db->select('*');
		$this->db->from('tbl_after_sales_operation_time');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_operation_time($id)
	{
		$sql = " SELECT * FROM tbl_after_sales_operation_time WHERE id_x='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertOperationTime($data)
	{
	$sql = "INSERT INTO tbl_after_sales_operation_time SET code='" . $data['code'] . "',
	duration='" . $data['duration'] . "',
	im='" . $data['im'] . "',
	description='" . $data['description'] . "'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateOperationTime($data)
	{
		$sql = "UPDATE tbl_after_sales_operation_time SET code='" . $data['code'] . "',
	duration='" . $data['duration'] . "',
	im='" . $data['im'] . "',
	description='" . $data['description'] . "'
        WHERE id_x='" . $data['id_x'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteOpTime($id)
	{
		$sql = "DELETE FROM tbl_after_sales_operation_time WHERE id_x='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	//** end Operation Time **//

}
