<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_reportwhpenawaran extends CI_Model
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
		$this->db->select('tbl_wh_estimasi_penawaran.*', FALSE);
		$this->db->select('tbl_wh_customer.*', FALSE);
        $this->db->from('tbl_wh_estimasi_penawaran');
        $this->db->join('tbl_wh_customer','tbl_wh_customer.kode_cus=tbl_wh_estimasi_penawaran.id_customer','left');
		$this->db->where('tbl_wh_estimasi_penawaran.tgl_estimasi_penawaran BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    
    public function select_by_id($id)
    {
        $sql = "SELECT * FROM tbl_wh_estimasi_penawaran 
        LEFT JOIN tbl_wh_customer ON tbl_wh_customer.kode_cus=tbl_wh_estimasi_penawaran.id_customer
        WHERE id_estimasi_penawaran ='{$id}'";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    public function select_detail($id)
    {
        $ci = get_instance();
                $query = "SELECT sum(total_harga) as total,b.ppn FROM tbl_wh_detail_estimasi_penawaran as a 
                    LEFT JOIN tbl_wh_estimasi_penawaran as b ON b.id_estimasi_penawaran=a.id_estimasi_penawaran
                    WHERE a.id_estimasi_penawaran='{$id}'";
        $d_data = $ci->db->query($query)->row_array();
        $total       = $d_data['total'];
        //$ppn       = $d_data['ppn'];
        //$total_ppn = $total * $ppn / 100;
        $grand_total = $total;
        $sql_update = "UPDATE tbl_wh_estimasi_penawaran SET
        sub_total   ='$total',
        grand_total ='$grand_total'
        WHERE id_estimasi_penawaran ='{$id}'";

        $this->db->query($sql_update);

        $sql = "SELECT a.* 
        FROM tbl_wh_detail_estimasi_penawaran AS a
        WHERE a.id_estimasi_penawaran ='{$id}' ORDER BY a.id_detail ASC";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    public function select_ket($id)
    {
        $sql = "SELECT * FROM tbl_wh_detail_estimasi_penawaran_note 
        WHERE id_estimasi_penawaran ='{$id}'";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
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
		$sql1 = "DELETE FROM tbl_wh_detail_po WHERE id_po='{$id}'";
		$this->db->query($sql1);
		$sql = "DELETE FROM tbl_wh_po WHERE id_po='{$id}'";
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
    /** End Perbody */

     //** Report Barang Return */
     function cari_return($status,$ttmp1,$ttmp2)
     {
        if(empty($status)){
            $this->db->select('a.*', FALSE);
            $this->db->select('b.id_masuk AS id_masuknye', FALSE);
            $this->db->from('tbl_wh_detail_part_masuk AS a');
            $this->db->join('tbl_wh_part_masuk AS b','b.kode_masuk=a.id_masuk','left');
            $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.part_return =','Y');

        }else{
            $this->db->select('a.*', FALSE);
            $this->db->select('b.id_masuk AS id_masuknye', FALSE);
            $this->db->from('tbl_wh_detail_part_masuk AS a');
            $this->db->join('tbl_wh_part_masuk AS b','b.kode_masuk=a.id_masuk','left');
            $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.status_part', $status);
            $this->db->where('a.part_return =','Y');
        }
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
     /** End Barang Return */
        //** Report Perbarang */
        function cari_part($no_part,$ttmp1,$ttmp2)
        {
            $this->db->select('a.*', FALSE);
            $this->db->select('b.*', FALSE);
            $this->db->select('d.satuan AS nama_satuan', FALSE);
            $this->db->from('tbl_wh_detail_part_keluar AS a');
            $this->db->join('tbl_wh_part_keluar AS b','a.id_keluar=b.id_keluar','left');
            $this->db->join('tbl_wh_barang AS c','c.no_part = a.no_part','left');
            $this->db->join('tbl_wh_satuan AS d','d.id_satuan=c.satuan','left');
            $this->db->where('b.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.no_part', $no_part);
    
            
            $query_result = $this->db->get();
            return $data = $query_result->result();
        }
        /** End Perbarang */
           //** Report Perbarang */
           function cari_part_masuk($no_part,$ttmp1,$ttmp2)
           {
               $this->db->select('a.*', FALSE);
               $this->db->select('b.id_masuk as idnye,b.status,b.no_po,b.id_masuk,b.keterangan', FALSE);
               $this->db->from('tbl_wh_detail_part_masuk AS a');
               $this->db->join('tbl_wh_part_masuk AS b','a.id_masuk=b.kode_masuk','left');
               $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
               $this->db->where('a.no_part', $no_part);
       
               
               $query_result = $this->db->get();
               return $data = $query_result->result();
           }
        /** End Perbarang */
      //** Report PerKategori */
      function cari_kategori($kat,$ttmp1,$ttmp2)
      {
        
          
        $this->db->select('a.*,b.*,c.*,d.satuan AS nama_satuan', FALSE);
          $this->db->from('tbl_wh_part_keluar AS a');
          $this->db->join('tbl_wh_detail_part_keluar AS b','b.id_keluar=a.id_keluar','left');
          $this->db->join('tbl_wh_barang AS c','c.no_part=b.no_part','left');
          $this->db->join('tbl_wh_satuan AS d','d.id_satuan=c.satuan','left');
          $this->db->where('a.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
          $this->db->where('a.divisi', $kat);
  
          
          $query_result = $this->db->get();
          return $data = $query_result->result();
      }
      public function select_kategori()
    {
        $sql = " SELECT * FROM tbl_wh_kategori";

        $data = $this->db->query($sql);

        return $data->result();
    }
      /** End PerKategori */

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
    //** End USer data */
}