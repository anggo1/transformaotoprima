<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_reportacc extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function select_pk()
	{
		$sql = "SELECT COUNT(b.id_list) as jml_part, a.kode as kode, a.keterangan as keterangan FROM tbl_br_kat_pk as a
			LEFT JOIN tbl_br_list_estimasi as b on b.id_proses=a.id  GROUP BY a.id";
			$data = $this->db->query($sql);
		return $data->result();
	}
    function cari_po($ttmp1 =null,$ttmp2=null)
    {
		$this->db->select('tbl_wh_po.*', FALSE);
		$this->db->select('tbl_wh_supplier.*', FALSE);
        $this->db->from('tbl_wh_po');
        $this->db->join('tbl_wh_supplier','tbl_wh_supplier.kode_sup=tbl_wh_po.supplier','left');
		$this->db->where('tbl_wh_po.tgl_po BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');

		
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
    //** Report Perpk */
    function cari_per_pk($jns_pk,$ttmp1,$ttmp2)
    {
		$this->db->select('a.jns_pk,a.ket_pk', FALSE);
		$this->db->select('b.*', FALSE);
		$this->db->select('c.*', FALSE);
		$this->db->select('e.kode_satuan,e.satuan AS nama_satuan', FALSE);
        $this->db->from('tbl_br_pk_aktif AS a');
        $this->db->join('tbl_wh_part_keluar AS b','b.no_pk=a.id_pk','left');
        $this->db->join('tbl_wh_detail_part_keluar AS c','c.id_keluar=b.id_keluar','left');
        $this->db->join('tbl_wh_barang AS d','d.no_part=c.no_part','left');
        $this->db->join('tbl_wh_satuan AS e','e.id_satuan=d.satuan','left');
		$this->db->where('b.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->where('a.jns_pk', $jns_pk);

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    /** End Perpk */
    //** Report Perbody */
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
    function cari_body($no_spk,$ttmp1,$ttmp2)
    {
            $this->db->select('a.*', FALSE);
		$this->db->select('b.*', FALSE);
		$this->db->select('d.kode_satuan,d.satuan AS nama_satuan', FALSE);
        $this->db->from('tbl_wh_part_keluar AS a');
        $this->db->join('tbl_wh_detail_part_keluar AS b','a.id_keluar=b.id_keluar','left');
        $this->db->join('tbl_wh_barang AS c','c.no_part=b.no_part','left');
        $this->db->join('tbl_wh_satuan AS d','d.id_satuan=c.satuan','left');
		$this->db->where('a.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
		$this->db->where('a.no_spk', $no_spk);

		
        $query_result = $this->db->get();
		return $data = $query_result->result();
    }
    /** End Perbody */

     //** Report Barang Return */
     function cari_return($status,$ttmp1,$ttmp2)
     {
        if(empty($status)){
            $this->db->select('a.*', FALSE);
            $this->db->select('b.id_masuk AS id_masuknye', FALSE);
            $this->db->from('tbl_wh_detail_part_masuk AS a');
            $this->db->join('tbl_wh_part_masuk AS b','b.kode_masuk=a.id_masuk','left');
            $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.part_return =','Y');

        }else{
            $this->db->select('a.*', FALSE);
            $this->db->select('b.id_masuk AS id_masuknye', FALSE);
            $this->db->from('tbl_wh_detail_part_masuk AS a');
            $this->db->join('tbl_wh_part_masuk AS b','b.kode_masuk=a.id_masuk','left');
            $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.status_part', $status);
            $this->db->where('a.part_return =','Y');
        }
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
     /** End Barang Return */
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
        
          
        $this->db->select('a.*,b.*,c.*,d.satuan AS nama_satuan', FALSE);
          $this->db->from('tbl_wh_part_keluar AS a');
          $this->db->join('tbl_wh_detail_part_keluar AS b','b.id_keluar=a.id_keluar','left');
          $this->db->join('tbl_wh_barang AS c','c.no_part=b.no_part','left');
          $this->db->join('tbl_wh_satuan AS d','d.id_satuan=c.satuan','left');
          $this->db->where('a.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
          $this->db->where('a.divisi', $kat);
  
          
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
      function cari_masuk($ttmp1 =null,$ttmp2=null,$status_po)
      {
          $this->db->select('a.tgl_masuk,a.kode_masuk,a.id_masuk,a.status_po,a.status AS status_part,b.nama_part,b.no_part,b.jumlah,c.nama_sup', FALSE);
          $this->db->from('tbl_wh_part_masuk as a');
          $this->db->join('tbl_wh_detail_part_masuk as b','a.kode_masuk=b.id_masuk','left');
          $this->db->join('tbl_wh_supplier as c','c.kode_sup=a.kode_sup','left');
          $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
          $this->db->where('a.status_po',$status_po);
          $this->db->group_by('a.id_masuk');
  
          
          $query_result = $this->db->get();
          return $data = $query_result->result();
      }
      function cari_masuk_detail($ttmp1 =null,$ttmp2=null,$status_po)
      {
          $this->db->select('a.tgl_masuk,a.kode_masuk,a.id_masuk,a.status_po,b.id_masuk,b.status_part,b.nama_part,b.no_part,b.jumlah,c.nama_sup', FALSE);
          $this->db->from('tbl_wh_part_masuk as a');
          $this->db->join('tbl_wh_detail_part_masuk as b','a.kode_masuk=b.id_masuk','left');
          $this->db->join('tbl_wh_supplier as c','c.kode_sup=a.kode_sup','left');
          $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
          $this->db->where('a.status_po',$status_po);
          $this->db->group_by('a.kode_masuk');
  
          
          $query_result = $this->db->get();
          return $data = $query_result->result();
      }
      function deleteMasuk($id,$data_status)
	{
        $result=$this->db->query("SELECT * FROM tbl_wh_detail_part_masuk WHERE id_masuk ='{$id}'")->result();
        $a = array();
        foreach($result as $a) {
            $no_part=$a->no_part;
            $jumlah=$a->jumlah;

        $ci = get_instance();
        $query_barang = "SELECT stok,stok_a,stok_p FROM tbl_wh_barang WHERE no_part='{$no_part}'";
            $d_data = $ci->db->query($query_barang)->row_array();
            $stok       = $d_data['stok'];
            $stok_a       = $d_data['stok_a'];
            $stok_p       = $d_data['stok_p'];
            $stok_ambil = $stok - $jumlah;
            $jstok = '';
            if ($data_status =='PPU'){
                $jstok = $stok_a - $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_ambil', stok_a ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);

            }
            if ($data_status =='MPU'){
                $jstok = $stok_p - $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_ambil', stok_p ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);
            }
        }

		$sql1 = "DELETE FROM tbl_wh_part_masuk WHERE kode_masuk='{$id}'";
		$this->db->query($sql1);
		$sql = "DELETE FROM tbl_wh_detail_part_masuk WHERE id_masuk='{$id}'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}

      /** End Perbarang */
      //** Report Keluar */
      function cari_keluar($status_po,$ttmp1,$ttmp2)
      {
        if($status_po=='N'){
        $sql = "SELECT * FROM tbl_wh_part_keluar WHERE no_spk='NON' AND tgl_keluar BETWEEN '".$ttmp1."' AND '".$ttmp2."'";
		$data = $this->db->query($sql);
		return $data->result();
        }
        if($status_po=='Y'){
            $sql = "SELECT * FROM tbl_wh_part_keluar WHERE tujuan ='SPK' AND tgl_keluar BETWEEN '".$ttmp1."' AND '".$ttmp2."'";
            $data = $this->db->query($sql);
		return $data->result();
            }
            if($status_po=='D'){
                $sql = "SELECT * FROM tbl_wh_part_keluar WHERE tgl_keluar BETWEEN '".$ttmp1."' AND '".$ttmp2."' AND divisi <>''";
                $data = $this->db->query($sql);
            return $data->result();
                }
    }
    function cari_keluar_detail($status_po,$ttmp1,$ttmp2)
      {
        if($status_po=='N'){
        $sql = "SELECT * FROM tbl_wh_part_keluar AS a
        LEFT JOIN tbl_wh_detail_part_keluar AS b ON b.id_keluar = a.id_keluar
        WHERE a.no_spk='NON' AND a.tgl_keluar BETWEEN '".$ttmp1."' AND '".$ttmp2."'";
		$data = $this->db->query($sql);
		return $data->result();
        }
        if($status_po=='Y'){
            $sql = "SELECT a.tgl_keluar,a.id_keluar,a.no_pk,a.no_body,a.status,c.ket_pk,b.no_part,b.nama_part,b.jumlah FROM tbl_wh_part_keluar AS a
            LEFT JOIN tbl_wh_detail_part_keluar AS b ON b.id_keluar = a.id_keluar
            LEFT JOIN tbl_br_pk_aktif AS c ON c.id_pk = a.no_pk
            WHERE a.tujuan ='SPK' AND a.tgl_keluar BETWEEN '".$ttmp1."' AND '".$ttmp2."'";
            $data = $this->db->query($sql);
		return $data->result();
            }
            if($status_po=='D'){
                $sql = "SELECT * FROM tbl_wh_part_keluar AS a
                LEFT JOIN tbl_wh_detail_part_keluar AS b ON b.id_keluar = a.id_keluar
                WHERE a.tgl_keluar BETWEEN '".$ttmp1."' AND '".$ttmp2."' AND a.divisi <>''";
                $data = $this->db->query($sql);
            return $data->result();
                }
    }
    function deleteKeluar($id,$data_status)
	{
        $result=$this->db->query("SELECT * FROM tbl_wh_detail_part_keluar WHERE id_keluar ='{$id}'")->result();
        $a = array();
        foreach($result as $a) {
            $no_part=$a->no_part;
            $jumlah=$a->jumlah;

        $ci = get_instance();
        $query_barang = "SELECT stok,stok_a,stok_p FROM tbl_wh_barang WHERE no_part='{$no_part}'";
            $d_data = $ci->db->query($query_barang)->row_array();
            $stok       = $d_data['stok'];
            $stok_a       = $d_data['stok_a'];
            $stok_p       = $d_data['stok_p'];
            $stok_ambil = $stok + $jumlah;
            $jstok = '';
            if ($data_status =='PPU'){
                $jstok = $stok_a + $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_ambil', stok_a ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);

            }
            if ($data_status =='MPU'){
                $jstok = $stok_p + $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_ambil', stok_p ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);
            }
        }

		$sql1 = "DELETE FROM tbl_wh_part_keluar WHERE id_keluar='{$id}'";
		$this->db->query($sql1);
		$sql = "DELETE FROM tbl_wh_detail_part_keluar WHERE id_keluar='{$id}'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
    function deleteKeluarnon_pk($id,$data_status)
	{
        $result=$this->db->query("SELECT * FROM tbl_wh_detail_part_keluar WHERE id_keluar ='{$id}'")->result();
        $a = array();
        foreach($result as $a) {
            $no_part=$a->no_part;
            $jumlah=$a->jumlah;

        $ci = get_instance();
        $query_barang = "SELECT stok,stok_a,stok_p FROM tbl_wh_barang WHERE no_part='{$no_part}'";
            $d_data = $ci->db->query($query_barang)->row_array();
            $stok       = $d_data['stok'];
            $stok_a       = $d_data['stok_a'];
            $stok_p       = $d_data['stok_p'];
            $stok_ambil = $stok + $jumlah;
            $jstok = '';
            if ($data_status =='PPU'){
                $jstok = $stok_a + $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_ambil', stok_a ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);

            }
            if ($data_status =='MPU'){
                $jstok = $stok_p + $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_ambil', stok_p ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);
            }
        }

		$sql1 = "DELETE FROM tbl_wh_part_keluar WHERE id_keluar='{$id}'";
		$this->db->query($sql1);
		$sql = "DELETE FROM tbl_wh_detail_part_keluar WHERE id_keluar='{$id}'";
		$this->db->query($sql);

		return $this->db->affected_rows();
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
		$sql = "SELECT * FROM tbl_wh_part_keluar AS a
        LEFT JOIN tbl_wh_detail_part_keluar AS b
        ON b.id_keluar=a.id_keluar
        WHERE a.id_keluar ='{$id}' ";

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
        $this->db->where('tbl_akses_submenu.id_level=',$idlevel);
        $this->db->where('tbl_akses_submenu.id_submenu=',$id_sub);
        $data = $this->db->get();
        return $data->result();
    }
    //** End USer data */
}