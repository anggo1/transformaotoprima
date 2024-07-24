<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_partorder extends CI_Model
{
    var $table = 'tbl_wh_barang';
    var $column_search = array('a.no_part','a.nama_part','a.satuan','a.harga_baru','a.diskon','a.harga_net','a.harga_rata','a.ppn','a.harga_valid','a.ket_harga');
    var $column_order = array('null','a.no_part','a.nama_part','a.satuan','a.harga_baru','a.diskon','a.harga_net','a.harga_rata','a.ppn','a.harga_valid','a.ket_harga');
    var $order = array('id_part' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('a.*');
        $this->db->from('tbl_wh_barang as a');
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
    function select_part($sup)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->where('a.supplier', $sup);
        $data = $this->db->get();
        return $data->result();
    }
    function get_part($id)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.nama_sup,f.kode_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.id_supplier=a.supplier', 'left');
        $this->db->where('a.id_part', $id);
        return $this->db->get('tbl_wh_barang')->row();
    }
    public function select_supplier()
    {
        $sql = " SELECT * FROM tbl_wh_supplier";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function select_kode()
    {
        $sql = " SELECT * FROM tbl_wh_kode_po";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function select_kota()
    {
        $sql = " SELECT * FROM tbl_kota";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function deleteDetail_po($id)
    {
        $sql = "DELETE FROM tbl_wh_detail_part_order WHERE id_detail='" . $id . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    public function insertDetailPo($data)
    {
        $datenow = date("Y-m-d");
		$harga=$data['harga_baru'];
		$harga_baru =str_replace(",","", $harga);
        $sql = "INSERT INTO tbl_wh_detail_part_order SET
            id_detail       ='',
            id_part_order           ='" . $data['id_part_order'] . "',
            no_part         ='" . $data['no_part'] . "',
            nama_part       ='" . $data['nama_part'] . "',
            satuan       ='" . $data['satuan'] . "',
            harga           ='" . $harga_baru. "',
            stok_akhir     ='" . $data['stok'] . "'";
        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    function update_detailPo($id,$jml_part,$hrg_part)
		{			
		$jml =str_replace(" ","", $jml_part);
		$total=$hrg_part*$jml;
			$sql_update = "UPDATE tbl_wh_detail_part_order SET jumlah ='$jml_part', total_harga = '$total', sisa ='$jml_part' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
			
		return $this->db->affected_rows();
			//return $data->row();
		}
    function update_detailBo($id,$bo)
		{			
			$sql_update = "UPDATE tbl_wh_detail_part_order SET bo ='$bo' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
			
		return $this->db->affected_rows();
		}
    function update_remark($id,$remark)
		{			
			$sql_update = "UPDATE tbl_wh_detail_part_order SET remark ='$remark' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
			
		return $this->db->affected_rows();
			//return $data->row();
		}
    public function insertDetail($kodePo, $koderef, $data)
    {
        $kodenya = "";
        $koderefnya = "";
        if (empty($data['id_part_order'])) {
            $kodenya = $kodePo;
            $koderefnya = $koderef;
        } else {
            $kodenya = $data['id_part_order'];
            $koderefnya = $data['kode_ref'];
        }
        $total_harga = $data['total_harga'];
        if (!empty($data['diskon'])) {
            $total_harga = $data['total_harga'] - $data['total_diskon'];
        }
        $datenow = date("Y-m-d");
        $sql = "INSERT INTO tbl_wh_detail_part_order SET
            id_detail       ='',
            id_part_order   ='" . $kodenya . "',
            kode_po         ='" . $koderefnya . "',
            no_part         ='" . $data['no_part'] . "',
            nama_part       ='" . $data['nama_part'] . "',
            harga_baru           ='" . $data['harga_baru'] . "',
            jumlah          ='" . $data['jumlah'] . "',
            diskon          ='" . $data['diskon'] . "',
            total_diskon    ='" . $data['total_diskon'] . "',
            total_harga     ='$total_harga',
            stok_akhir     ='" . $data['stok_awal'] . "'";
        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    public function select_by_id($id)
    {
        $sql = "SELECT * FROM tbl_wh_part_order 
        LEFT JOIN tbl_wh_supplier ON tbl_wh_supplier.kode_sup=tbl_wh_part_order.supplier
        WHERE id_part_order ='{$id}'";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    public function select_detail($id)
    {
        $ci = get_instance();
                $query = "SELECT sum(total_harga) as total,b.ppn FROM tbl_wh_detail_part_order as a 
                    LEFT JOIN tbl_wh_part_order as b ON b.id_part_order=a.id_part_order
                    WHERE a.id_part_order='{$id}'";
        $d_data = $ci->db->query($query)->row_array();
        $total       = $d_data['total'];
        //$ppn       = $d_data['ppn'];
        //$total_ppn = $total * $ppn / 100;
        $grand_total = $total;
        $sql_update = "UPDATE tbl_wh_part_order SET
        sub_total   ='$total',
        grand_total ='$grand_total'
        WHERE id_part_order ='{$id}'";

        $this->db->query($sql_update);

        $sql = "SELECT a.* 
        FROM tbl_wh_detail_part_order AS a
        WHERE a.id_part_order ='{$id}' ORDER BY a.id_detail ASC";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    function updatePo($a, $b, $c, $d)
    {
        $sql = "UPDATE tbl_wh_part_order SET
        t_ppn       ='$a',
        sub_total   ='$b',
        grand_total ='$c'
        WHERE id_part_order ='" . $data['id_part_order'] . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
}
