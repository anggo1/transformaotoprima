<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_spkperminggu extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	
  function cari_pk($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('a.*', TRUE);
		$this->db->select('b.id_pk,COUNT(b.jam_mulai) AS jml_pk', FALSE);
		$this->db->select('c.kategori AS nama_kategori', FALSE);
		$this->db->select('SUM(e.jumlah * e.hrg_part) AS total_harga', FALSE);
		$this->db->select('g.nama_pool', FALSE);
        $this->db->from('tbl_br_laporan_bus AS a');
        $this->db->join('tbl_br_pk_aktif AS b','b.id_lapor=a.id_lapor','left');
        $this->db->join('tbl_br_kategori AS c','c.id_kategori=a.kategori','left');
        $this->db->join('tbl_wh_part_keluar AS d','d.no_pk=b.id_pk','left');
        $this->db->join('tbl_wh_detail_part_keluar AS e','e.id_keluar=d.id_keluar','left');
        $this->db->join('tbl_wh_body AS f','f.no_body=a.no_body','left');
        $this->db->join('tbl_br_pool AS g','g.kode_pool=f.pool','left');
		$this->db->where('b.tgl_mulai BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->where('a.status !=', 'N');
		$this->db->group_by('d.no_spk');

		
    $query_result = $this->db->get();
		return $data = $query_result->result();


    }

    function cari_pk2($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('a.*', FALSE);
		$this->db->select('b.kategori AS nama_kategori', FALSE);
		$this->db->select('c.id_pk', FALSE);
		$this->db->select('COUNT(c.id_lapor) AS jml_pk', FALSE);
		//$this->db->select('COUNT(e.jumlah * e.hrg_part) AS total_harga', FALSE);
        $this->db->from('tbl_br_laporan_bus AS a');
        $this->db->join('tbl_br_kategori AS b','b.id_kategori=a.kategori','left');
        $this->db->join('tbl_br_pk_aktif AS c','c.id_lapor=a.id_lapor','left');
        $this->db->join('tbl_wh_part_keluar AS d','d.no_pk=c.id_pk','left');
        $this->db->join('tbl_wh_detail_part_keluar AS e','e.id_keluar=d.id_keluar','left');
		$this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->where('a.status !=', 'N');
		$this->db->group_by('c.id_pk');

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    
	//** end per PO**//
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
  function cari_keluar_detail($ttmp1,$ttmp2)
      {
            $query = 'SET @dense_rank = 0;';
            $this->db->query($query);
            $query = 'SET @id_keluar = NULL;';
            $this->db->query($query);
            $this->db->select('@dense_rank:=CASE WHEN @id_keluar = a.id_keluar
            THEN @dense_rank ELSE @dense_rank + 1 END AS row_urut, @id_keluar:=a.id_keluar AS id_keluar,id,
            ROW_NUMBER() OVER(PARTITION BY b.id_keluar ORDER BY b.id_keluar) as row_no,
                    a.kode_keluar,a.tgl_keluar,a.id_keluar,a.no_spk,a.no_pk,a.no_body,a.status,a.keterangan,a.no_pk,a.ket_pk,b.hrg_part,b.no_part,b.nama_part,b.jumlah,b.satuan', FALSE);
            $this->db->from('tbl_wh_part_keluar AS a');
            $this->db->join('tbl_wh_detail_part_keluar AS b','b.id_keluar=a.id_keluar','left');
            $this->db->join('tbl_br_pk_aktif AS c','c.id_pk=a.no_pk','left');
            $this->db->where('c.tgl_mulai BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.tujuan','SPK');
            //$this->db->order_by('b.id');
           // $this->db->group_by('c.no_body');
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