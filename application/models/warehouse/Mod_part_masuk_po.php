<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_part_masuk_po extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_po()
    {
        $this->db->select('a.*,b.*');
        $this->db->from('tbl_wh_part_order as a');
        $this->db->join('tbl_wh_supplier as b', 'b.id_supplier=a.supplier', 'left');
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
    function select_part($id)
    {

        $idlokasi = $this->session->userdata['lokasi'];
        if ($idlokasi == "Jakarta") {
            $sql    = "SELECT a.*,b.stok_jkt,c.*,d.satuan 
        FROM tbl_wh_detail_part_order AS a 
        LEFT JOIN tbl_wh_barang AS b ON a.no_part=b.no_part
        LEFT JOIN tbl_wh_part_order AS c ON a.id_part_order=c.id_part_order
        LEFT JOIN tbl_wh_satuan AS d ON d.id_satuan=b.satuan
        WHERE a.id_part_order = '" . $id . "' AND a.status='N' ORDER BY a.id_detail ASC";
        }
        if ($idlokasi == "Cibitung") {

            $sql    = "SELECT a.*,b.stok_cbt,c.*,d.satuan 
        FROM tbl_wh_detail_part_order AS a 
        LEFT JOIN tbl_wh_barang AS b ON a.no_part=b.no_part
        LEFT JOIN tbl_wh_part_order AS c ON a.id_part_order=c.id_part_order
        LEFT JOIN tbl_wh_satuan AS d ON d.id_satuan=b.satuan
        WHERE a.id_part_order = '" . $id . "' AND a.status='N' ORDER BY a.id_detail ASC";
        }
        if ($idlokasi == "Surabaya") {

            $sql    = "SELECT a.*,b.stok_sby,c.*,d.satuan 
        FROM tbl_wh_detail_part_order AS a 
        LEFT JOIN tbl_wh_barang AS b ON a.no_part=b.no_part
        LEFT JOIN tbl_wh_part_order AS c ON a.id_part_order=c.id_part_order
        LEFT JOIN tbl_wh_satuan AS d ON d.id_satuan=b.satuan
        WHERE a.id_part_order = '" . $id . "' AND a.status='N' ORDER BY a.id_detail ASC";
        }
        $data = $this->db->query($sql);


        return $data->result();
    }
    function select_po()
    {
        $sql    = "SELECT a.*,b.kode_sup as kode_sup, b.nama_sup as supplier
        FROM tbl_wh_part_order AS a
        LEFT JOIN tbl_wh_supplier AS b ON b.kode_sup=a.supplier
        WHERE a.status_po !='Y' ";
        $data = $this->db->query($sql);


        return $data->result();
    }
    function select_part_nopo()
    {
        $sql    = "SELECT * FROM tbl_wh_barang";
        $data = $this->db->query($sql);


        return $data->result();
    }
    function select_nopo($tgl_po)
    {
        $tgl   = explode('-', $tgl_po);
        $tglnya = $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0] . "";
        //$this->db->where('tgl_po',$tglnya);
        //return $this->db->get('tbl_wh_po')->row();

        $sql    = "SELECT kode_po FROM tbl_wh_part_order WHERE tgl_po='" . $tglnya . "'";
        $data   = $this->db->query($sql);


        return $data->result();
    }
    function get_part($id)
    {
        $sql    = "SELECT * FROM tbl_wh_barang 
        WHERE no_part='" . $id . "'";
        $data = $this->db->query($sql);
        return $data->result();
    }
    public function select_detail($id)
    {

        $this->db->select('a.*,b.*', FALSE);
        $this->db->from('tbl_wh_detail_part_masuk as a');
        $this->db->join('tbl_wh_barang as b', 'b.no_part=a.no_part', 'left');
        $this->db->where('a.id_masuk', $id);
        $this->db->order_by('a.id_detail', ASC);
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }

    public function insert_part($kode_awal,$kode_masuk, $data)
    {
        //$id = md5(DATE('ymdhms') . rand());
        $tgl_masuk =  date("y-m-d");
        
        //$stok_awal       = $d_data['stok'];
		$idlokasi = $this->session->userdata['lokasi'];
        $no_part = $this->input->post('no_part');
        $stok=$this->input->post('stok');
        $qty_masuk = $this->input->post('qty_masuk');
        $satuan = $this->input->post('satuan');
        $harga = $this->input->post('harga');
        $qty_awal = $this->input->post('qty_request');
        $id_po = $this->input->post('id_po');
        $status_barang = $idlokasi;
        $nama_part = $this->input->post('nama_part');

        if ($idlokasi == "Jakarta") {
            $data1 = array();
            foreach ($no_part as $key => $value) {
                $total_jkt = $stok[$key] + $qty_masuk[$key];
                $data1[]  = array(
                    'no_part' => $no_part[$key],
                    'stok_jkt' => $total_jkt
                );
            }
        }
        if ($idlokasi == "Cibitung") {
            $data1 = array();
            foreach ($no_part as $key => $value) {
                $total_cbt = $stok[$key] + $qty_masuk[$key];
                $data1[]  = array(
                    'no_part' => $no_part[$key],
                    'stok_cbt' => $total_cbt
                );
            }
        }
        if ($idlokasi == "Surabaya") {
            $data1 = array();
            foreach ($no_part as $key => $value) {
                $total_sby = $stok[$key] + $qty_masuk[$key];
                $data1[]  = array(
                    'no_part' => $no_part[$key],
                    'stok_sby' => $total_sby
                );
            }
        }
        $this->db->update_batch('tbl_wh_barang', $data1, 'no_part');


        $data = array();
        if($qty_awal == $qty_masuk){
            $status = 'Y';
        }
        else{
            $status = 'P';
        }
        $sql_update = "UPDATE tbl_wh_part_order SET status_po='".$status."' WHERE id_part_order ='{$id_po}'";
        $this->db->query($sql_update);
        foreach ($no_part as $key => $value) { // Kita buat perulangan berdasarkan nis sampai data terakhir
            $data[]  = array(
                'id_masuk' => $kode_awal,
                'no_part' => $no_part[$key],  // Ambil dan set data nama sesuai index array dari $index
                'hrg_part' => $harga[$key],  // Ambil dan set data nama sesuai index array dari $index
                'status_part' => $status_barang,  // Ambil dan set data nama sesuai index array dari $index
                'nama_part' => $nama_part[$key],  // Ambil dan set data telepon sesuai index array dari $index
                'jumlah' => $qty_masuk[$key],  // Ambil dan set data alamat sesuai index array dari $index
                'satuan' => $satuan[$key],  // Ambil dan set data alamat sesuai index array dari $index
                'tgl_masuk' => $tgl_masuk
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
        $this->db->join('tbl_wh_detail_part_masuk as b', 'b.id_masuk=a.kode_masuk', 'left');
        $this->db->join('tbl_wh_supplier as c', 'c.kode_sup=a.kode_sup', 'left');
        $this->db->where('a.kode_masuk', $id);
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function select_by_id2($id)
    {
        $this->db->select('a.*,b.*,c.*', FALSE);
        $this->db->from('tbl_wh_part_masuk as a');
        $this->db->join('tbl_wh_detail_part_masuk as b', 'b.id_masuk=a.id_masuk', 'left');
        $this->db->join('tbl_wh_supplier as c', 'c.kode_sup=a.kode_sup', 'left');
        $this->db->where('a.id_masuk', $id);
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function select_detail_cetak($id)
    {
        $this->db->select('a.*,b.*,c.satuan AS nama_satuan', FALSE);
        $this->db->from('tbl_wh_detail_part_masuk as a');
        $this->db->join('tbl_wh_barang as b', 'b.no_part=a.no_part', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=b.satuan', 'left');
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
    public function deletepartDetail($id, $sisa)
    {
        $sql = "UPDATE tbl_wh_detail_part_order SET sisa='" . $sisa . "', status='P' WHERE id_detail='" . $id . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }

    function update_part($id, $qty_awal, $qty_masuk)
    {
        $jml = str_replace(" ", "", $qty_masuk);
        $sisa = $qty_awal - $qty_masuk;
        if ($sisa == 0) {
            $sql_update = "UPDATE tbl_wh_detail_part_order SET sisa = '$sisa', status='N', jml_masuk ='$jml' WHERE id_detail ='{$id}'";
            $this->db->query($sql_update);
        } else {
            $sql_update = "UPDATE tbl_wh_detail_part_order SET sisa = '$sisa', jml_masuk ='$jml' WHERE id_detail ='{$id}'";
            $this->db->query($sql_update);
        }
        return $this->db->affected_rows();
        //return $data->row();
    }
}
