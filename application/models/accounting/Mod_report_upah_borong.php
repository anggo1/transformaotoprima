<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_report_upah_borong extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	
    function cari_bayar($ttmp1 =null,$ttmp2=null,$no_body,$no_pk)
    {
        if(empty($no_body)&&(empty($no_pk))){       
		$this->db->select('a.*', FALSE); 
		$this->db->select('b.biaya_borong AS biaya_awal', FALSE);
        $this->db->from('tbl_br_upah_borongan AS a');
        $this->db->join('tbl_br_pk_aktif AS b','b.id_pk=a.id_pk','left');
		$this->db->where('tgl_bayar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->group_by('a.id');
        }
        if(!empty($no_body)&&(empty($no_pk))){ 
            $this->db->select('a.id_pk,a.no_body,a.biaya_borong,a.tgl_mulai,a.ket_pk', FALSE); 
            $this->db->select('b.jns_pk,b.jumlah,SUM(b.jumlah) AS dibayarkan', FALSE);
            $this->db->from('tbl_br_pk_aktif AS a');
            $this->db->join('tbl_br_upah_borongan AS b','b.id_pk=a.id_pk','left');
            $this->db->where('a.tgl_mulai BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.no_body',$no_body);
            $this->db->group_by('a.id_pk'); 
        }
        if(empty($no_body)&&(!empty($no_pk))){ 
            $this->db->select('a.id_pk,a.no_body,a.biaya_borong,a.tgl_mulai,a.ket_pk', FALSE); 
            $this->db->select('b.jns_pk,b.jumlah,SUM(b.jumlah) AS dibayarkan', FALSE);
            $this->db->from('tbl_br_pk_aktif AS a');
            $this->db->join('tbl_br_upah_borongan AS b','b.id_pk=a.id_pk','left');
            $this->db->where('a.tgl_mulai BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.id_lapor',$no_pk);
            $this->db->group_by('a.id_pk'); 
        }
        if(!empty($no_body)&&(!empty($no_pk))){ 
            $this->db->select('a.id_pk,a.no_body,a.biaya_borong,a.tgl_mulai,a.ket_pk', FALSE); 
            $this->db->select('b.jns_pk,b.jumlah,SUM(b.jumlah) AS dibayarkan', FALSE);
            $this->db->from('tbl_br_pk_aktif AS a');
            $this->db->join('tbl_br_upah_borongan AS b','b.id_pk=a.id_pk','left');
            $this->db->where('a.tgl_mulai BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.no_body',$no_body);
            $this->db->where('a.id_lapor',$no_pk);
            $this->db->group_by('a.id_pk'); 
        }
		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    function cari_bayar_detail($ttmp1 =null,$ttmp2=null,$no_body,$no_pk)
    {
        if(!empty($no_body)&&(empty($no_pk))){ 
		$sql = "SELECT a.tgl_bayar,a.jns_pk,a.jumlah,a.keterangan,b.id_pk,b.no_body,b.biaya_borong,b.tgl_mulai,b.ket_pk,
        (SELECT SUM(c.biaya_borong) FROM tbl_br_pk_aktif AS c WHERE c.no_body = '{$no_body}' ) AS beaBorong
        FROM tbl_br_upah_borongan AS a 
        JOIN tbl_br_pk_aktif AS b ON b.id_pk=a.id_pk 
        WHERE b.tgl_mulai BETWEEN '$ttmp1' AND '$ttmp2' AND b.no_body = '{$no_body}' ";
        }
        if(empty($no_body)&&(!empty($no_pk))){ 
        $sql = "SELECT a.tgl_bayar,a.jns_pk,a.jumlah,a.keterangan,b.id_pk,b.no_body,b.biaya_borong,b.tgl_mulai,b.ket_pk,
        (SELECT SUM(c.biaya_borong) FROM tbl_br_pk_aktif AS c WHERE c.no_body = '{$no_body}' ) AS beaBorong
        FROM tbl_br_upah_borongan AS a 
        JOIN tbl_br_pk_aktif AS b ON b.id_pk=a.id_pk 
        WHERE b.tgl_mulai BETWEEN '$ttmp1' AND '$ttmp2' AND b.id_lapor = '{$no_pk}' ";
        }
        if(!empty($no_body)&&(!empty($no_pk))){
        $sql = "SELECT a.tgl_bayar,a.jns_pk,a.jumlah,a.keterangan,b.id_pk,b.no_body,b.biaya_borong,b.tgl_mulai,b.ket_pk,
        (SELECT SUM(c.biaya_borong) FROM tbl_br_pk_aktif AS c WHERE c.no_body = '{$no_body}' ) AS beaBorong
        FROM tbl_br_upah_borongan AS a 
        JOIN tbl_br_pk_aktif AS b ON b.id_pk=a.id_pk 
        WHERE b.tgl_mulai BETWEEN '$ttmp1' AND '$ttmp2' AND b.no_body = '{$no_body}' AND b.id_lapor = '{$no_pk}' ";
        }

		$data = $this->db->query($sql);

		return $data->result();
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
    
	function cetak_pk($no_spk)
    {
		$this->db->select('a.*,b.*,c.kategori AS nama_kategori, d.type,d.rute_aktif,d.kelas,e.nama_pool AS pool');
		$this->db->from('tbl_br_pk_aktif as a');
		$this->db->join('tbl_br_laporan_bus as b','b.id_lapor=a.id_lapor');
		$this->db->join('tbl_br_kategori as c','c.id_kategori=b.kategori');
		$this->db->join('tbl_wh_body as d','d.no_body=a.no_body');
		$this->db->join('tbl_br_pool as e','e.kode_pool=d.pool');
		$this->db->where('a.id_pk ',$no_spk);

		$data = $this->db->get();

		return $data->result();
    }
    //** Report PerSPK */
    function cari_spk_part($no_spk)
    {
		$sql ="SELECT a.id_lapor,a.id_pk,a.jns_pk,a.ket_pk,a.no_body,a.biaya_borong,c.jumlah,c.hrg_part,SUM(c.jumlah*c.hrg_part) AS total_hrg_part,g.tgl_masuk,
        d.kategori AS nama_kategori,e.*,f.*
        FROM tbl_br_pk_aktif AS a 
        LEFT JOIN tbl_wh_part_keluar AS b ON b.no_pk=a.id_pk 
		LEFT JOIN tbl_br_laporan_bus AS g ON g.id_lapor=a.id_lapor
        LEFT JOIN tbl_wh_detail_part_keluar AS c ON b.id_keluar=c.id_keluar
		LEFT JOIN tbl_br_kategori AS d ON d.id_kategori=g.kategori
		LEFT JOIN tbl_wh_body AS e ON e.no_body=a.no_body
		LEFT JOIN tbl_br_pool  AS f ON f.kode_pool=e.pool
        WHERE a.id_lapor = '{$no_spk}' GROUP BY a.id_pk";

		$data = $this->db->query($sql);

		return $data->result();
    }
    function cari_spk_detail($no_spk)
    {
		$sql ="SELECT a.id_lapor,a.id_pk,a.jns_pk,a.ket_pk,a.no_body,a.biaya_borong,c.no_part,c.nama_part,c.jumlah,e.satuan,c.hrg_part,c.jumlah*c.hrg_part AS total_hrg_part
        FROM tbl_br_pk_aktif AS a 
        LEFT JOIN tbl_wh_part_keluar AS b ON b.no_pk=a.id_pk 
        LEFT JOIN tbl_wh_detail_part_keluar AS c ON b.id_keluar=c.id_keluar 
        LEFT JOIN tbl_wh_barang AS d ON d.no_part=c.no_part 
        LEFT JOIN tbl_wh_satuan AS e ON e.id_satuan=d.satuan 
        WHERE a.id_lapor = '{$no_spk}'";

		$data = $this->db->query($sql);

		return $data->result();
    }
    function cari_spk_detail_upah($no_spk)
    {
        $sql = "SELECT a.tgl_bayar,a.jns_pk,a.jumlah,a.keterangan,b.id_lapor,b.id_pk,b.no_body,b.biaya_borong,b.tgl_mulai,b.ket_pk,
        (SELECT SUM(c.biaya_borong) FROM tbl_br_pk_aktif AS c WHERE c.id_lapor = '{$no_spk}') AS beaBorong
        FROM tbl_br_upah_borongan AS a 
        JOIN tbl_br_pk_aktif AS b ON b.id_pk=a.id_pk 
        WHERE b.id_lapor = '{$no_spk}' ";
       
		$data = $this->db->query($sql);

		return $data->result();
    }
    /** End PerSPK */
        //** Report Perbarang */
        function cari_part($no_part,$ttmp1,$ttmp2)
        {
            $this->db->select('a.*', FALSE);
            $this->db->select('b.*', FALSE);
            $this->db->from('tbl_wh_detail_part_keluar AS a');
            $this->db->join('tbl_wh_part_keluar AS b','a.id_keluar=b.id_keluar','left');
            $this->db->where('b.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.no_part', $no_part);
    
            
            $query_result = $this->db->get();
            return $data = $query_result->result();
        }
        /** End Perbarang */
      //** Report PerKategori */
      function cari_kategori($kat,$ttmp1,$ttmp2)
      {
          $this->db->select('a.*', FALSE);
          $this->db->select('b.*', FALSE);
          $this->db->select('c.*', FALSE);
          $this->db->from('tbl_wh_barang AS a');
          $this->db->join('tbl_wh_detail_part_keluar AS b','a.no_part=b.no_part','left');
          $this->db->join('tbl_wh_part_keluar AS c','b.id_keluar=c.id_keluar','left');
          $this->db->where('c.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
          $this->db->where('a.kategori', $kat);
  
          
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
      //** Report Masuk */
      function cari_masuk($ttmp1 =null,$ttmp2=null)
      {
          $this->db->select('a.*,b.*,c.nama_sup', FALSE);
          $this->db->from('tbl_wh_part_masuk as a');
          $this->db->join('tbl_wh_detail_part_masuk as b','b.id_masuk=a.id_masuk','left');
          $this->db->join('tbl_wh_supplier as c','c.kode_sup=a.kode_sup','left');
          $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
  
          
          $query_result = $this->db->get();
          return $data = $query_result->result();
      }
      /** End Perbarang */
      //** Report Masuk */
      function cari_keluar($ttmp1,$ttmp2)
      {
          $this->db->select('a.*', FALSE);
          $this->db->select('b.*', FALSE);
          $this->db->from('tbl_wh_part_keluar AS a');
          $this->db->join('tbl_wh_detail_part_keluar AS b','a.id_keluar=b.id_keluar','left');
          $this->db->where('a.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
  
          
          $query_result = $this->db->get();
          return $data = $query_result->result();
      }
      /** End Perbarang */
      function cari_partpk($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('tbl_wh_part_keluar.*', FALSE);
		$this->db->select('tbl_wh_detail_part_keluar.*', FALSE);
        $this->db->from('tbl_wh_part_keluar');
        $this->db->join('tbl_wh_detail_part_keluar','tbl_wh_detail_part_keluar.id_keluar=tbl_wh_part_keluar.id_keluar','left');
		$this->db->where('tbl_wh_part_keluar.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->where('tbl_wh_part_keluar.tujuan','SPK');

        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    function cari_partnon($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('tbl_wh_part_keluar.*', FALSE);
		$this->db->select('tbl_wh_detail_part_keluar.*', FALSE);
        $this->db->from('tbl_wh_part_keluar');
        $this->db->join('tbl_wh_detail_part_keluar','tbl_wh_detail_part_keluar.id_keluar=tbl_wh_part_keluar.id_keluar','left');
		$this->db->where('tbl_wh_part_keluar.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->where('tbl_wh_part_keluar.tujuan != ','SPK');

        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
	function deletePartnon($id)
	{
		$sql1 = "DELETE FROM tbl_wh_part_keluar WHERE id_keluar='{$id}'";
		$this->db->query($sql1);
		$sql = "DELETE FROM tbl_wh_detail_part_keluar WHERE id_keluar='{$id}'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	//** end per NonPK **//
}
