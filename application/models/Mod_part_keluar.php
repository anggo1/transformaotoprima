<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_part_keluar extends CI_Model
{
    var $table = 'tbl_wh_barang';
    var $column_search = array('a.no_part','a.nama_part','a.stok','a.lokasi','a.satuan','a.type','a.kelompok');
    var $column_order = array('a.no_part','a.nama_part','a.stok','a.lokasi','a.satuan','a.type','a.kelompok');
    var $order = array('id_part' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('a.*,b.kategori,e.kelompok');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        //$this->db->join('tbl_wh_satuan as c','c.id_satuan=a.satuan', 'left');
        //$this->db->join('tbl_wh_type_mesin as d','d.id_type=a.type','left');
        $this->db->join('tbl_wh_kelompok as e','e.id_kelompok=a.kelompok','left');
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
    function get_part($id)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.nama_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.id_supplier=a.supplier', 'left');
        $this->db->where('a.id_barang', $id);
        return $this->db->get('tbl_wh_barang')->row();
    }
    public function select_supplier()
    {
        $sql = " SELECT * FROM tbl_wh_supplier";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function insert_part($data)
    {
        
        $date = $data['tgl_keluar'];
        $tgl1 = explode('-', $date);
        $tgl_keluar = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
        
		$lokasi 	= $data['lokasi'];
		$loknye = explode('|',$lokasi);
		$kd_lok = $loknye[0];
        
        $sql = "INSERT INTO tbl_wh_detail_part_keluar SET
        id_keluar   ='".$data['id_keluar']."',
        no_part     ='".$data['no_part']."',
        nama_part   ='".$data['nama_part']."',
        tgl_keluar  ='$tgl_keluar',
        Jumlah      ='0',
        hrg_part    ='".$data['harga_baru']."',
        satuan      ='".$data['satuan']."',
        status_po   ='N',
        lokasi      ='".$kd_lok."',
        stok_akhir  ='".$data['stok']."'";
        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    function update_harga($id,$hrg_part)
		{
		$harga =str_replace(" ","", $hrg_part);
	    $sql_update = "UPDATE tbl_wh_detail_part_keluar SET hrg_part ='$harga' WHERE id ='{$id}'"; $this->db->query($sql_update);
		return $this->db->affected_rows();
		}
    function update_jml($id,$hrg_part)
		{
		$jumlah =str_replace(" ","", $hrg_part);
	    $sql_update = "UPDATE tbl_wh_detail_part_keluar SET jumlah ='$jumlah' WHERE id ='{$id}'"; $this->db->query($sql_update);
		return $this->db->affected_rows();
		}
    public function insertGlobal($kode_keluar, $data,$no_part,$nama_part,$qty_keluar,$stok,$stok_jkt,$stok_cbt,$stok_sby,$kd_lok,$nm_lok)
        {
            $date = $data['tgl_keluar'];
            $tgl1 = explode('-', $date);
            $tgl_keluar = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
            $lokasi = $data['lokasi'];
            $id_keluar = $data['id_keluar'];

            if($nm_lok=="Jakarta"){
             $data1 = array();
             foreach($no_part as $key=>$value){ 
                 $total = $stok[$key] - $qty_keluar[$key];
                 $total_jkt= $stok_jkt[$key] - $qty_keluar[$key];
                 $data1[]  = array(
                 'no_part'=>$no_part[$key],  
                 'stok'=>$total,
                 'stok_jkt'=>$total_jkt
             );
                     }}
                     elseif($nm_lok=="Cibitung"){
                         $data1 = array();
                         foreach($no_part as $key=>$value){
                             $total = $stok[$key] - $qty_keluar[$key];
                             $total_cbt= $stok_cbt[$key] - $qty_keluar[$key];
                             $data1[]  = array(
                             'no_part'=>$no_part[$key], 
                             'stok'=>$total,
                             'stok_cbt'=>$total_cbt
                         );
                     }}
                     elseif($nm_lok=="Surabaya"){
                         $data1 = array();
                         foreach($no_part as $key=>$value){
                             $total = $stok[$key] - $qty_keluar[$key];
                             $total_sby= $stok_sby[$key] - $qty_keluar[$key];
                             $data1[]  = array(
                             'no_part'=>$no_part[$key],  
                             'stok'=>$total,
                             'stok_sby'=>$total_sby
                         );
                     }}
            $this->db->update_batch('tbl_wh_barang', $data1,'no_part');
    
            $sql_update = "UPDATE tbl_wh_detail_part_keluar SET lokasi ='".$nm_lok."', tgl_keluar='".$tgl_keluar."' WHERE id_keluar ='{$id_keluar}'"; $this->db->query($sql_update);
            return $this->db->affected_rows();
        }
    public function insertGlobal_divisi($kode_keluar, $data,$no_part,$nama_part,$qty_keluar,$stok,$stok_a,$stok_p,$id_div,$nama_div)
    {
        $date = $data['tgl_keluar'];
        $tgl1 = explode('-', $date);
        $tgl_keluar = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
        $status_barang = $data['status_part'];
        $id_keluar = $data['id_keluar'];
        if($status_barang=="PPU"){
        $data1 = array();
        foreach($no_part as $key=>$value){
            $total = $stok[$key] - $qty_keluar[$key];
            $total_a= $stok_a[$key] - $qty_keluar[$key];
            $data1[]  = array(
            'no_part'=>$no_part[$key],  // Ambil dan set data telepon sesuai index array dari $index
            'stok'=>$total,
            'stok_a'=>$total_a
        );
    }}
    if($status_barang=="MPU"){
        $data1 = array();
        foreach($no_part as $key=>$value){
            $total = $stok[$key] - $qty_keluar[$key];
            $total_p= $stok_p[$key] -
            
            $qty_keluar[$key];
            $data1[]  = array(
            'no_part'=>$no_part[$key],  // Ambil dan set data telepon sesuai index array dari $index
            'stok'=>$total,
            'stok_p'=>$total_p
        );
    }}
        $this->db->update_batch('tbl_wh_barang', $data1,'no_part');

        $sql_update = "UPDATE tbl_wh_detail_part_keluar SET status_part ='".$status_barang."',tgl_keluar='".$tgl_keluar."', divisi='".$id_div."',nama_divisi='".$nama_div."' WHERE id_keluar ='{$id_keluar}'"; 
        $this->db->query($sql_update);
		return $this->db->affected_rows();
    }
    public function deletepartDetail($id)
    {
        $sql = "DELETE FROM tbl_wh_detail_part_keluar WHERE id='" . $id . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    
    public function select_kategori()
    {
        $sql = " SELECT * FROM tbl_wh_kategori";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function get_kota()
    {
        $sql = " SELECT * FROM tbl_kota";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function select_detail($id)
    {

        $this->db->select('a.*', FALSE);
        $this->db->select('b.*', FALSE);
        $this->db->from('tbl_wh_detail_part_keluar as a');
        $this->db->join('tbl_wh_barang as b','b.no_part=a.no_part','left');
        //$this->db->join('tbl_wh_satuan as c','c.id_satuan=b.satuan', 'left');
        $this->db->where('a.id_keluar', $id);
        $this->db->order_by('a.id', 'asc');
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function select_by_id($id)
    {
        $this->db->select('*', FALSE);
        $this->db->from('tbl_wh_part_keluar');
        $this->db->where('id_keluar', $id);
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function select_detail_cetak($id)
    {
        $this->db->select('a.*,b.*,c.satuan as nama_satuan', FALSE);
        $this->db->from('tbl_wh_detail_part_keluar as a');
        $this->db->join('tbl_wh_barang as b','b.no_part=a.no_part','left');
        $this->db->join('tbl_wh_satuan as c','c.id_satuan=b.satuan', 'left');
        $this->db->where('a.id_keluar', $id);
        $this->db->order_by('a.id', DESC);

        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
}
