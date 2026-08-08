<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_part_keluar_po extends CI_Model
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
    function select_part()
    {
        $sql    = "SELECT a.*,b.stok_jkt,b.stok_cbt,b.stok_sby,c.*,d.satuan 
        FROM tbl_af_estimasi_penawaran AS a 
        LEFT JOIN tbl_wh_barang AS b ON a.no_part=b.no_part
        LEFT JOIN tbl_wh_po AS c ON a.id_po=c.id_po
        LEFT JOIN tbl_wh_satuan AS d ON d.id_satuan=b.satuan
        WHERE a.wo_no = '" . $wo_na . "'";
        $data = $this->db->query($sql);


        return $data->result();
    }
    function select_po()
    {
        //$tgl   = explode('-', $tgl_po);
        //$tglnya = $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0] . "";
        $sql = "SELECT a.*,c.kode_cus,c.nama_cus
        FROM tbl_wh_po_masuk AS a
        LEFT JOIN tbl_wh_detail_po_masuk AS b ON b.id_po_masuk=a.id_po_masuk
        LEFT JOIN tbl_wh_customer AS c ON c.kode_cus=a.customer
        WHERE a.status_po ='N' OR a.status_po ='P' GROUP BY a.id_po_masuk";
        $data = $this->db->query($sql);
        return $data->result();
    }
    function cari_barang($no_po)
    {
        //$tgl   = explode('-', $tgl_po);
        //$tglnya = $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0] . "";
        $sql    = "SELECT a.*,b.nama_part,b.satuan,b.stok_jkt,b.stok_cbt,b.stok_sby
        FROM tbl_wh_detail_po_masuk AS a 
        LEFT JOIN tbl_wh_barang AS b ON b.no_part=a.no_part
        WHERE id_po_masuk ='" . $no_po . "' AND status !='Y'";
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

        $sql    = "SELECT kode_po FROM tbl_wh_po WHERE tgl_po='" . $tglnya . "'";
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
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }

    public function insert_part($kode_keluar, $data)
    {
        //$id = md5(DATE('ymdhms') . rand());
        $idlokasi = $this->session->userdata['lokasi'];

        $id_po_masuk = $this->input->post('id_po_masuk');
        $no_part = $this->input->post('no_part');
        $nama_part = $this->input->post('nama_part');
        $jumlah = $this->input->post('jumlah');
        $harga = $this->input->post('harga');
        $jml_keluar = $this->input->post('jml_keluar');
        $total_harga = $this->input->post('total_harga');


        $tgl_keluar =  date("y-m-d");
        //$stok_awal       = $d_data['stok'];
        $field = null;
        if ($idlokasi == "Jakarta") {
            $field = 'stok_jkt';
        } elseif ($idlokasi == "Cibitung") {
            $field = 'stok_cbt';
        } elseif ($idlokasi == "Surabaya") {
            $field = 'stok_sby';
        }

        $data1 = array();
        if ($field !== null) {
            foreach ($no_part as $key => $value) {
                $total = isset($stok[$key]) && isset($jml_keluar[$key]) ? $stok[$key] - $jml_keluar[$key] : 0;
                $data1[] = array(
                    'no_part' => $value,
                    $field => $total
                );
            }
            $this->db->update_batch('tbl_wh_barang', $data1, 'no_part');
        }

        $data = array();
        foreach ($no_part as $key => $value) {
            $data[]  = array(
                'kode_keluar' => $kode_keluar,
                'id_po_masuk' => $id_po_masuk,
                'no_part' => $no_part[$key],
                'nama_part' => isset($nama_part[$key]) ? $nama_part[$key] : null,
                'jumlah' => isset($jumlah[$key]) ? $jumlah[$key] : null,
                'harga' => isset($harga[$key]) ? $harga[$key] : null,
                'total_harga' => isset($total_harga[$key]) ? $total_harga[$key] : null,
                'lokasi' => $idlokasi,
                'tgl_keluar' => $tgl_keluar
            );
        }
        $this->db->insert_batch('tbl_wh_detail_part_keluar_po', $data);
        return $this->db->affected_rows();
    }
    function select_by_id($id)
    {
        $this->db->select('a.*,b.*,c.*,d.kode_pesan,d.tgl_po', FALSE);
        $this->db->from('tbl_wh_part_keluar_po as a');
        $this->db->join('tbl_wh_detail_part_keluar_po as b', 'b.kode_keluar=a.kode_keluar', 'left');
        $this->db->join('tbl_wh_customer as c', 'c.kode_cus=a.kode_cus', 'left');
        $this->db->join('tbl_wh_po_masuk as d', 'd.id_po_masuk=a.id_po_masuk', 'left');
        $this->db->where('a.id_po_masuk', $id);
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function select_detail_cetak($id)
    {
        $this->db->select('a.*,b.*,c.satuan AS nama_satuan', FALSE);
        $this->db->from('tbl_wh_detail_part_keluar_po as a');
        $this->db->join('tbl_wh_barang as b', 'b.no_part=a.no_part', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=b.satuan', 'left');
        $this->db->where('a.id_po_masuk', $id);
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
        $sql = "UPDATE tbl_wh_detail_po SET sisa='" . $sisa . "', status='P' WHERE id_detail='" . $id . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }

    function update_part($id, $jumlah, $jml_keluar, $hrg_part)
    {
        $jml = str_replace(" ", "", $jml_keluar);
        $sisa = $jumlah - $jml_keluar;
        $total_harga = $hrg_part * $jml;
        if ($sisa == 0) {
            $sql_update = "UPDATE tbl_wh_detail_po_masuk SET jumlah = '$jml', sisa = '$sisa', jml_masuk ='$jml', total_harga = '$total_harga' WHERE id_detail ='{$id}'";
            $this->db->query($sql_update);
        } else {
            $sql_update = "UPDATE tbl_wh_detail_po_masuk SET jumlah = '$jml', sisa = '$sisa', jml_masuk ='$jml', total_harga = '$total_harga' WHERE id_detail ='{$id}'";
            $this->db->query($sql_update);
        }
        return $this->db->affected_rows();
        //return $data->row();
    }
}
