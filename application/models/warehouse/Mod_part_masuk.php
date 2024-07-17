<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_part_masuk extends CI_Model
{
   
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_po()
    {
        $this->db->select('a.*,b.*');
        $this->db->from('tbl_wh_po as a');
        $this->db->join('tbl_wh_supplier as b','b.id_supplier=a.supplier','left');
        //$this->db->where('status', 'N');
		$data = $this->db->get();

		return $data->result();
    }
    function get_sup()
    {
        $this->db->select('*');
        $this->db->from('tbl_wh_supplier');
        //$this->db->where('status', 'N');
		$data = $this->db->get();

		return $data->result();
    }
    function get_kota()
    {
        $this->db->select('*');
        $this->db->from('tbl_kota');
        //$this->db->where('status', 'N');
		$data = $this->db->get();

		return $data->result();
    }
    function select_part($po)
    {
        $sql    = "SELECT a.*,b.harga_baru,b.stok,b.stok_jkt,b.stok_cbt,b.stok_sby,c.*,d.satuan 
        FROM tbl_wh_detail_po AS a 
        LEFT JOIN tbl_wh_barang AS b ON a.no_part=b.no_part
        LEFT JOIN tbl_wh_po AS c ON a.id_po=c.id_po
        LEFT JOIN tbl_wh_satuan AS d ON d.id_satuan=b.satuan
        WHERE a.id_po = '".$po."' AND a.status='N' ORDER BY a.id_detail ASC" ;
        $data = $this->db->query($sql);


        return $data->result();
    }
    function select_po()
    {
        //$tgl   = explode('-', $tgl_po);
        //$tglnya = $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0] . "";
        $sql    = "SELECT a.*,b.kode_sup as kode_sup, b.nama_sup as supplier
        FROM tbl_wh_po AS a
        LEFT JOIN tbl_wh_supplier AS b ON b.kode_sup=a.supplier
        WHERE a.status_po !='Y' " ;
        $data = $this->db->query($sql);


        return $data->result();
    }
    function select_part_nopo()
    {
        $sql    = "SELECT * FROM tbl_wh_barang" ;
        $data = $this->db->query($sql);


        return $data->result();
    }
    function select_nopo($tgl_po)
    {
        $tgl   = explode('-', $tgl_po);
        $tglnya = $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0] . "";
        //$this->db->where('tgl_po',$tglnya);
        //return $this->db->get('tbl_wh_po')->row();

        $sql    = "SELECT kode_po FROM tbl_wh_po WHERE tgl_po='".$tglnya."'" ;
        $data   = $this->db->query($sql);


        return $data->result();
    }
    function get_part($id)
    {
        $sql    = "SELECT * FROM tbl_wh_barang 
        WHERE no_part='".$id."'" ;
        $data = $this->db->query($sql);
        return $data->result();
    }
    public function select_detail($id)
    {

        $this->db->select('a.*,b.*', FALSE);
        $this->db->from('tbl_wh_detail_part_masuk as a');
        $this->db->join('tbl_wh_barang as b','b.no_part=a.no_part','left');
        $this->db->where('a.id_masuk', $id);
        $this->db->order_by('a.id_detail', ASC);
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
 
    public function insert_part($kode_awal,$kode_masuk,$data,$no_part,$harga,$nama_part,$qty_masuk,$satuan,$stok,$stok_jkt,$stok_cbt,$stok_sby,$id_po,$kd_lok,$nm_lok)
    {
        //$id = md5(DATE('ymdhms') . rand());
        $tgl_masuk =  date("y-m-d");
        $ci_data = get_instance();
        $query = "SELECT * FROM tbl_wh_detail_po WHERE id_po='".$id_po."' AND status='P' OR status='N' ";
        $d_data = $ci_data->db->query($query)->row_array();
        if ($d_data >1){
        $sql_po1 = "UPDATE tbl_wh_po SET status_po='P' WHERE id_po='".$id_po."'";
        $this->db->query($sql_po1);
        $sql_po2 = "UPDATE tbl_wh_detail_po SET status='Y' WHERE id_po='".$id_po."' AND sisa=0";
        $this->db->query($sql_po2);
        $sql_po3 = "UPDATE tbl_wh_detail_po SET status='N' WHERE id_po='".$id_po."' AND status='P'";
        $this->db->query($sql_po3);
        }else{
            $sql_po1 = "UPDATE tbl_wh_po SET status_po='Y' WHERE id_po='".$id_po."'";
            $this->db->query($sql_po1);
            $sql_po2 = "UPDATE tbl_wh_detail_po SET status='Y' WHERE id_po='".$id_po."'";
            $this->db->query($sql_po2);
        }
        //$stok_awal       = $d_data['stok'];
       $status_barang = $data['lokasi'];
       if($nm_lok=="Jakarta"){
        $data1 = array();
        foreach($no_part as $key=>$value){ 
            $total = $stok[$key] + $qty_masuk[$key];
            $total_jkt= $stok_jkt[$key] + $qty_masuk[$key];
            $data1[]  = array(
            'no_part'=>$no_part[$key],  // Ambil dan set data telepon sesuai index array dari $index
            'stok'=>$total,
            'stok_jkt'=>$total_jkt
        );
                }}
                if($nm_lok=="Cibitung"){
                    $data1 = array();
                    foreach($no_part as $key=>$value){
                        $total = $stok[$key] + $qty_masuk[$key];
                        $total_cbt= $stok_cbt[$key] + $qty_masuk[$key];
                        $data1[]  = array(
                        'no_part'=>$no_part[$key],  // Ambil dan set data telepon sesuai index array dari $index
                        'stok'=>$total,
                        'stok_cbt'=>$total_cbt
                    );
                }}
                if($nm_lok=="Surabaya"){
                    $data1 = array();
                    foreach($no_part as $key=>$value){
                        $total = $stok[$key] + $qty_masuk[$key];
                        $total_sby= $stok_sby[$key] + $qty_masuk[$key];
                        $data1[]  = array(
                        'no_part'=>$no_part[$key],  // Ambil dan set data telepon sesuai index array dari $index
                        'stok'=>$total,
                        'stok_sby'=>$total_sby
                    );
                }}
                    $this->db->update_batch('tbl_wh_barang', $data1,'no_part');


        $data = array();
    foreach($no_part as $key=>$value){ // Kita buat perulangan berdasarkan nis sampai data terakhir
        $data[]  = array(
        'id_masuk'=>$kode_awal,
        'no_part'=>$no_part[$key],  // Ambil dan set data nama sesuai index array dari $index
        'hrg_part'=>$harga[$key],  // Ambil dan set data nama sesuai index array dari $index
        'status_part'=>$status_barang,  // Ambil dan set data nama sesuai index array dari $index
        'nama_part'=>$nama_part[$key],  // Ambil dan set data telepon sesuai index array dari $index
        'jumlah'=>$qty_masuk[$key],  // Ambil dan set data alamat sesuai index array dari $index
        'satuan'=>$satuan[$key],  // Ambil dan set data alamat sesuai index array dari $index
        'tgl_masuk'=>$tgl_masuk
    );
    }
        $this->db->insert_batch('tbl_wh_detail_part_masuk', $data);
        return $this->db->affected_rows();
    }
    function select_by_id($id)
    {
        $this->db->select('a.kode_masuk,a.id_masuk,a.tgl_masuk,a.status,a.keterangan,
        a.status_po,a.no_po AS no_ponye,a.no_sj_sup,a.no_inv_sup,a.kode_sup,a.user,a.part_return,b.jumlah,c.*', FALSE);
        $this->db->from('tbl_wh_part_masuk as a');
        $this->db->join('tbl_wh_detail_part_masuk as b','b.id_masuk=a.kode_masuk','left');
        $this->db->join('tbl_wh_supplier as c','c.kode_sup=a.kode_sup','left');
        $this->db->where('a.kode_masuk', $id);
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function select_by_id2($id)
    {
    $this->db->select('a.*,b.*,c.*', FALSE);
    $this->db->from('tbl_wh_part_masuk as a');
    $this->db->join('tbl_wh_detail_part_masuk as b','b.id_masuk=a.id_masuk','left');
    $this->db->join('tbl_wh_supplier as c','c.kode_sup=a.kode_sup','left');
    $this->db->where('a.id_masuk', $id);
    $query_result = $this->db->get();
    return $data = $query_result->result();
    }
    function select_detail_cetak($id)
    {
        $this->db->select('a.*,b.*,c.satuan AS nama_satuan', FALSE);
        $this->db->from('tbl_wh_detail_part_masuk as a');
        $this->db->join('tbl_wh_barang as b','b.no_part=a.no_part','left');
        $this->db->join('tbl_wh_satuan as c','c.id_satuan=b.satuan','left');
        $this->db->where('a.id_masuk', $id);
        $this->db->order_by('a.id', 'ASC');

        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    public function deleteDetail($id)
    {
        $sql = "DELETE FROM tbl_wh_detail_part_masuk WHERE id='" . $id . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    public function deletepartDetail($id,$sisa)
    {
        $sql = "UPDATE tbl_wh_detail_po SET sisa='".$sisa."', status='P' WHERE id_detail='".$id."'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }

    function update_part($id,$qty_awal,$qty_masuk)
		{
		$jml =str_replace(" ","", $qty_masuk);
		$sisa =$qty_awal - $qty_masuk;
        if($sisa == 0){
            $sql_update = "UPDATE tbl_wh_detail_po SET sisa = '$sisa', status='N', jml_masuk ='$jml' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
        }else{
		    $sql_update = "UPDATE tbl_wh_detail_po SET sisa = '$sisa', jml_masuk ='$jml' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
        }
		return $this->db->affected_rows();
			//return $data->row();
		}
}