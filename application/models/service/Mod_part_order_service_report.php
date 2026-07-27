<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_part_order_service_report extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    function cari_service($ttmp1 = null, $ttmp2 = null)
    {
        $this->db->select('a.wo_no,a.kode_estimasi_penawaran,a.tgl_estimasi_penawaran,a.sales_design', FALSE);
        $this->db->select('b.no_part,b.nama_part,b.harga,b.harga_net,b.jumlah,b.total_harga,b.satuan', FALSE);
        $this->db->select('c.nama_cus', FALSE);
        $this->db->from('tbl_af_estimasi_penawaran AS a');
        $this->db->join('tbl_af_detail_estimasi_penawaran AS b', 'b.wo_no = a.wo_no', 'left');
        $this->db->join('tbl_customer AS c', 'c.kode_cus = a.id_customer', 'left');
        $this->db->where('a.tgl_estimasi_penawaran BETWEEN "' . date($ttmp1) . '"AND"' . date($ttmp2) . '"');
        $this->db->where('b.validasi_jenis=', 'P');
        $this->db->where('b.status_ok=', 'Y');


        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
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
        $this->db->where('tbl_akses_submenu.id_level=', $idlevel);
        $this->db->where('tbl_akses_submenu.id_submenu=', $id_sub);
        $data = $this->db->get();
        return $data->result();
    }
    //** End USer data */
}
