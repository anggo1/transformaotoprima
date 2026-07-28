<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_part_keluar_wo extends CI_Model
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
    function select_part()
    {
        $sql    = "SELECT a.*,b.stok_jkt,b.stok_cbt,b.stok_sby,c.*,d.satuan 
        FROM tbl_af_estimasi_penawaran AS a 
        LEFT JOIN tbl_wh_barang AS b ON a.no_part=b.no_part
        LEFT JOIN tbl_wh_po AS c ON a.id_po=c.id_po
        LEFT JOIN tbl_wh_satuan AS d ON d.id_satuan=b.satuan
        WHERE a.wo_no = '".$wo_na."'" ;
        $data = $this->db->query($sql);


        return $data->result();
    }
    function select_wo()
    {
        //$tgl   = explode('-', $tgl_po);
        //$tglnya = $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0] . "";
        $sql = "SELECT *
        FROM tbl_after_sales AS a
        LEFT JOIN tbl_af_estimasi_penawaran AS b ON b.wo_no=a.wo_no
        LEFT JOIN tbl_customer AS d ON d.kode_cus=b.id_customer
        LEFT JOIN tbl_after_sales_part_request AS e ON e.wo_no=a.wo_no
        WHERE a.part_request ='Y' OR a.part_request ='P' GROUP BY a.wo_no " ;
        $data = $this->db->query($sql);
        return $data->result();
    }
    function cari_barang($wo_no)
    {
        //$tgl   = explode('-', $tgl_po);
        //$tglnya = $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0] . "";
        $sql    = "SELECT a.wo_no,a.date_open_wo,c.id_detail,c.harga_net,c.harga,c.diskon, c.jumlah,c.jml_masuk,c.sisa, f.kode_pr, f.nik, f.nama AS petugas, d.nama_cus,e.*
        FROM tbl_after_sales AS a
        LEFT JOIN tbl_af_estimasi_penawaran AS b ON b.wo_no=a.wo_no
        LEFT JOIN tbl_af_detail_estimasi_penawaran AS c ON c.wo_no=a.wo_no
        LEFT JOIN tbl_customer AS d ON d.kode_cus=b.id_customer
        LEFT JOIN tbl_wh_barang AS e ON e.no_part=c.no_part
        LEFT JOIN tbl_after_sales_part_request AS f ON f.wo_no=a.wo_no
        WHERE a.part_request ='Y' AND c.validasi_jenis='P' AND a.wo_no='".$wo_no."' GROUP BY c.id_detail  " ;
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
 
    public function insert_part($wo_no,$kode_keluar, $no_part,$nama_part,$jumlah,$harga_penawaran,$harga,$jml_keluar,$sisa,$stok)
    {
        //$id = md5(DATE('ymdhms') . rand());
        $idlokasi = $this->session->userdata['lokasi'];
        $tgl_keluar =  date("y-m-d");
        $ci_data = get_instance();
        $query = "SELECT * FROM tbl_af_detail_estimasi_penawaran WHERE wo_no='".$wo_no."' AND status_part='P' ";
        $d_data = $ci_data->db->query($query)->row_array();
        if ($d_data >1){
        $sql_po1 = "UPDATE tbl_after_sales SET part_request='F' WHERE wo_no='".$wo_no."'";
        $this->db->query($sql_po1);
        }else{
            $sql_po1 = "UPDATE tbl_after_sales SET part_request='F' WHERE wo_no='".$wo_no."'";
            $this->db->query($sql_po1);
        }
        //$stok_awal       = $d_data['stok'];
       if($idlokasi=="Jakarta"){
        $data1 = array();
        foreach($no_part as $key =>$value){ 
            $total_jkt = $stok[$key] - $jml_keluar[$key];
            $data1[]  = array(
            'no_part'=>$no_part[$key],  
            'stok_jkt'=>$total_jkt
        );
                }}
                if($idlokasi=="Cibitung"){
                    $data1 = array();
                    foreach($no_part as $key){
                        $total_cbt = $stok[$key] - $jml_keluar[$key];
                        $data1[]  = array(
                        'no_part'=>$no_part[$key],  
                        'stok_cbt'=>$total_cbt
                    );
                }}
                if($idlokasi=="Surabaya"){
                    $data1 = array();
                    foreach($no_part as $key){
                        $total_sby= $stok[$key] - $jml_keluar[$key];
                        $data1[]  = array(
                        'no_part'=>$no_part[$key], 
                        'stok_sby'=>$total_sby
                    );
                }}
                    $this->db->update_batch('tbl_wh_barang', $data1,'no_part');


        $data = array();
    foreach($no_part as $key=>$value){ 
        $data[]  = array(
        'kode_keluar'=>$kode_keluar,
        'wo_no'=>$wo_no,
        'no_part'=>$no_part[$key],
        'nama_part'=>isset($nama_part[$key]) ? $nama_part[$key] : null,
        'jumlah'=>isset($jumlah[$key]) ? $jumlah[$key] : null,
        'harga_penawaran'=>isset($harga_penawaran[$key]) ? $harga_penawaran[$key] : null,
        'harga'=>isset($harga[$key]) ? $harga[$key] : null,
        'jml_keluar'=>isset($jml_keluar[$key]) ? $jml_keluar[$key] : null,
        'sisa'=>isset($sisa[$key]) ? $sisa[$key] : null,
        'lokasi'=>$idlokasi,
        'tgl_keluar'=>$tgl_keluar
    );
    }
        $this->db->insert_batch('tbl_wh_detail_part_keluar_service', $data);
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

    function update_part($id,$jumlah,$jml_keluar)
		{
		$jml =str_replace(" ","", $jml_keluar);
		$sisa =$jumlah - $jml_keluar;
        if($sisa == 0){
            $sql_update = "UPDATE tbl_af_detail_estimasi_penawaran SET sisa = '$sisa', status_part='P', jml_masuk ='$jml' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
        }else{
		    $sql_update = "UPDATE tbl_af_detail_estimasi_penawaran SET sisa = '$sisa', jml_masuk ='$jml' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
        }
		return $this->db->affected_rows();
			//return $data->row();
		}
}