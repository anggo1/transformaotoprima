<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_spk extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    } 
    function select_part($sup)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok');
        $this->db->from('tbl_mk_spk as a');
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
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.nama_sup,f.kode_cus');
        $this->db->from('tbl_mk_spk as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_customer as f', 'f.id_supplier=a.supplier', 'left');
        $this->db->where('a.id_part', $id);
        return $this->db->get('tbl_mk_spk')->row();
    }
    public function deleteDetail_spk($id)
    {
        $sql = "DELETE FROM tbl_mk_keterangan_spk WHERE id_ket_spk='" . $id . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    function update_remark($id,$remark)
		{
			$sql_update = "UPDATE tbl_mk_keterangan_spk SET remark ='$remark' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
    function insertKeterangan($id,$no_spk,$keterangan)
		{
			$sql_update = "INSERT tbl_mk_keterangan_spk SET no_urut = '$id', no_spk='$no_spk', keterangan ='$keterangan'";
            $this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
    public function insertDetail($kodePo, $koderef, $data)
    {
        $kodenya = "";
        $koderefnya = "";
        if (empty($data['id_estimasi_penawaran'])) {
            $kodenya = $kodePo;
            $koderefnya = $koderef;
        } else {
            $kodenya = $data['id_estimasi_penawaran'];
            $koderefnya = $data['kode_ref'];
        }
        $total_harga = $data['total_harga'];
        if (!empty($data['diskon'])) {
            $total_harga = $data['total_harga'] - $data['total_diskon'];
        }
        $datenow = date("Y-m-d");
        $sql = "INSERT INTO tbl_mk_keterangan_spk SET
            id_detail       ='',
            id_estimasi_penawaran   ='" . $kodenya . "',
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
        $sql = "SELECT * FROM tbl_mk_spk 
        WHERE no_urut ='{$id}'";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    public function select_keterangan($id)
    {
        $sql = "SELECT * FROM tbl_mk_keterangan_spk WHERE no_urut ='{$id}' ORDER BY id_ket_spk ASC";

        $data = $this->db->query($sql);
        return $data->result();
    }
    function updatePo($a, $b, $c, $d)
    {
        $sql = "UPDATE tbl_wh_estimasi_penawaran SET
        t_ppn       ='$a',
        sub_total   ='$b',
        grand_total ='$c'
        WHERE id_estimasi_penawaran ='" . $data['id_estimasi_penawaran'] . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
}