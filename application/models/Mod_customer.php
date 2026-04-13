<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_customer extends CI_Model
{
 	var $table = 'tbl_customer';
    var $column_search = array('kode_cus','kode_nama','nama_cus','alamat','no_tlp','tlp_person');
    var $column_order = array('null','kode_cus','kode_nama','nama_cus','alamat','no_tlp','tlp_person');
    var $order = array('id_cus' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('id_cus,kode_cus,kode_nama,nama_cus,detail,alamat,no_tlp,no_fax,tlp_person');
        $this->db->from('tbl_customer');
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

        $this->db->from('tbl_customer');
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
	public function select_customer()
	{
		$this->db->select('*');
		$this->db->from('tbl_customer');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_customer($id)
	{
		$sql = " SELECT * FROM tbl_customer WHERE id_cus='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertCustomer($data)
	{
	$sql = "INSERT INTO tbl_customer SET kode_cus='" . $data['kode_cus'] . "',
	kode_nama='" . $data['kode_nama'] . "',
	nama_cus='" . $data['nama_customer'] . "',
	detail='" . $data['detail'] . "',
	alamat='" . $data['alamat'] . "',
	kota='" . $data['kota'] . "',
	no_tlp='" . $data['no_tlp'] . "',
	no_fax='" . $data['no_fax'] . "',
	tlp_person='" . $data['tlp_person'] . "'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateCustomer($data)
	{
		$sql = "UPDATE tbl_customer SET kode_cus='" . $data['kode_cus'] . "',
	kode_nama='" . $data['kode_nama'] . "',
	nama_cus='" . $data['nama_customer'] . "',
	detail='" . $data['detail'] . "',
	alamat='" . $data['alamat'] . "',
	kota='" . $data['kota'] . "',
	no_tlp='" . $data['no_tlp'] . "',
	no_fax='" . $data['no_fax'] . "',
		tlp_person='" . $data['tlp_person'] . "'
        WHERE id_cus='" . $data['id_cus'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteCus($id)
	{
		$sql = "DELETE FROM tbl_customer WHERE id_cus='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	//** end Customer **//

	// End Kelompok //
}
