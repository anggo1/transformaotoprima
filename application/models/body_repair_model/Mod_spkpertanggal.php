<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_spkpertanggal extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	
    function cari_pk($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('a.*', FALSE);
		$this->db->select('b.kategori AS nama_kategori', FALSE);
		$this->db->select('c.keterangan AS ket_lapor', FALSE);
		$this->db->select('d.id_pk', FALSE);
		$this->db->select('COUNT(d.id_lapor) AS jml_pk', FALSE);        
        $this->db->from('tbl_br_laporan_bus AS a');
        $this->db->join('tbl_br_kategori AS b','b.id_kategori=a.kategori','left');
        $this->db->join('tbl_br_ket_lapor AS c','c.id=a.ket_lapor','left');
        $this->db->join('tbl_br_pk_aktif AS d','a.id_lapor=d.id_lapor','left');
		$this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->where('a.status !=', 'N');
		$this->db->group_by('d.id_lapor');

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    function cari_detail_pk($ttmp1 =null,$ttmp2=null)
    {
      $query = 'SET @dense_rank = 0;';
      $this->db->query($query);
      $query = 'SET @id_lapor = NULL;';
      $this->db->query($query);
      $this->db->select('@dense_rank:=CASE WHEN @id_lapor = a.id_lapor THEN @dense_rank ELSE @dense_rank + 1 END AS row_urut,
      @id_lapor:=b.id_lapor AS id_lapor, a.id_lapor, ROW_NUMBER() OVER(PARTITION BY b.id_lapor ORDER BY b.id_lapor) as row_no,
      a.*,b.*,c.kategori,d.rute_aktif,d.type,d.kelas,e.nama_pool', FALSE);
      $this->db->from('tbl_br_laporan_bus AS a');
      $this->db->join('tbl_br_pk_aktif AS b','b.id_lapor=a.id_lapor','left');
      $this->db->join('tbl_br_kategori AS c','c.id_kategori=a.kategori','left');
      $this->db->join('tbl_wh_body AS d','d.no_body=a.no_body','left');
      $this->db->join('tbl_br_pool AS e','e.kode_pool=d.pool','left');
      $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
      $this->db->where('a.status !=', 'N');
      //$this->db->order_by('b.id_lapor');
      $query_result = $this->db->get();
      return $data = $query_result->result();

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

    
    function cetak_pk($id)
    {
      $query = 'SET @dense_rank = 0;';
      $this->db->query($query);
      $query = 'SET @id_lapor = NULL;';
      $this->db->query($query);
      $this->db->select('@dense_rank:=CASE WHEN @id_lapor = a.id_lapor THEN @dense_rank ELSE @dense_rank + 1 END AS row_urut,
      @id_lapor:=b.id_lapor AS id_lapor, a.id_lapor, ROW_NUMBER() OVER(PARTITION BY b.id_lapor ORDER BY b.id_lapor) as row_no,
      a.id_lapor,a.no_body,a.tgl_masuk, a.no_pol,a.keterangan,b.id_pk,b.pt_pemborong,b.pj_borong,b.jns_pk,b.ket_pk,c.kategori,d.rute_aktif,d.type,d.kelas,e.nama_pool', FALSE);
      $this->db->from('tbl_br_laporan_bus AS a');
      $this->db->join('tbl_br_pk_aktif AS b','b.id_lapor=a.id_lapor','left');
      $this->db->join('tbl_br_kategori AS c','c.id_kategori=a.kategori','left');
      $this->db->join('tbl_wh_body AS d','d.no_body=a.no_body','left');
      $this->db->join('tbl_br_pool AS e','e.kode_pool=d.pool','left');
      $this->db->where('a.id_lapor =',$id);
      $this->db->order_by('b.id_lapor');
      $query_result = $this->db->get();
      return $data = $query_result->result();

}
    function cetak_estimasi($id)
    {
      
      $query = 'SET @dense_rank = 0;';
      $this->db->query($query);
      $query = 'SET @id_pk = NULL;';
      $this->db->query($query);
      $this->db->select('@dense_rank:=CASE WHEN @id_pk = a.id_pk THEN @dense_rank ELSE @dense_rank + 1 END AS row_urut,
      @id_pk:=b.id_pk AS id_pk, a.id_pk, ROW_NUMBER() OVER(PARTITION BY b.id_pk ORDER BY b.id_pk) as row_no,
      a.id_lapor,a.pt_pemborong,a.pj_borong,a.id_pk,a.jns_pk,a.ket_pk,b.ket_detail AS keterangan', FALSE);
      $this->db->from('tbl_br_pk_aktif AS a');
      $this->db->join('tbl_br_detail_pk AS b','b.id_pk=a.id_pk','left');
      $this->db->join('tbl_br_kat_pk AS c','c.kode=b.jns_pk','left');
      $this->db->where('a.id_lapor =',$id);
      $this->db->order_by('row_urut','asc');
      $query_result = $this->db->get();
      return $data = $query_result->result();
      
}
    function cetak_estimasierror($id)
    {
      
		$sql = "SELECT * FROM tbl_br_laporan_bus AS a LEFT JOIN tbl_br_pk_aktif AS b ON a.id_lapor=b.id_lapor WHERE a.id_lapor ='{$id}'";
		$sql = "SELECT a.id_pk,a.pt_pemborong,a.pj_borong,b.jns_pk,b.ket_pk,b.ket_detail
    FROM tbl_br_pk_aktif as a
		JOIN tbl_br_detail_pk as b ON b.id_lapor=a.id_lapor 
		JOIN tbl_br_laporan_bus as c ON b.id_lapor=a.id_lapor 
    WHERE a.id_lapor = '{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
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