<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_reportwhpo_masuk extends CI_Model
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
	
    function cari_po($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('tbl_wh_po_masuk.*', FALSE);
		$this->db->select('tbl_wh_customer.*', FALSE);
        $this->db->from('tbl_wh_po_masuk');
        $this->db->join('tbl_wh_customer','tbl_wh_customer.kode_cus=tbl_wh_po_masuk.customer','left');
		$this->db->where('tbl_wh_po_masuk.tgl_po BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    function cetak_bon($id)
    {
		$sql = "SELECT * FROM tbl_wh_part_keluar AS a
        LEFT JOIN tbl_wh_detail_part_keluar AS b
        ON b.id_keluar=a.id_keluar
        WHERE a.id_keluar ='{$id}' ";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function deletePo($id)
	{
		$sql1 = "DELETE FROM tbl_wh_detail_po_masuk WHERE id_po_masuk='{$id}'";
		$this->db->query($sql1);
		$sql = "DELETE FROM tbl_wh_po_masuk WHERE id_po_masuk='{$id}'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	//** end per PO**//
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
    public function select_by_id($id)
    {
        $sql = "SELECT * FROM tbl_wh_po_masuk 
        LEFT JOIN tbl_wh_customer ON tbl_wh_customer.kode_cus=tbl_wh_po_masuk.customer
        WHERE id_po_masuk ='{$id}'";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    public function select_detail($id)
    {
        $ci = get_instance();
                $query = "SELECT sum(total_harga) as total,b.ppn FROM tbl_wh_detail_po_masuk as a 
                    LEFT JOIN tbl_wh_po_masuk as b ON b.id_po_masuk=a.id_po_masuk
                    WHERE a.id_po_masuk='{$id}'";
        $d_data = $ci->db->query($query)->row_array();
        $total       = $d_data['total'];
        //$ppn       = $d_data['ppn'];
        //$total_ppn = $total * $ppn / 100;
        $grand_total = $total;
        $sql_update = "UPDATE tbl_wh_po_masuk SET
        sub_total   ='$total',
        grand_total ='$grand_total'
        WHERE id_po_masuk ='{$id}'";

        $this->db->query($sql_update);

        $sql = "SELECT a.* 
        FROM tbl_wh_detail_po_masuk AS a
        WHERE a.id_po_masuk ='{$id}' ORDER BY a.id_detail ASC";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    //** End USer data */
}