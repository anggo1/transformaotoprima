<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_reportwhpo extends CI_Model
{
    var $table = 'tbl_wh_barang';
    var $column_search = array('no_part', 'nama_part');
    var $column_order = array('no_part', 'nama_part');
    var $order = array('id_barang' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->from('tbl_wh_barang');
        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {

        $this->db->from('tbl_wh_barang as a');
        //$this->db->join('tbl_menu as b','a.id_menu=b.id_menu');
        return $this->db->count_all_results();
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
            $this->db->select('d.satuan AS nama_satuan', FALSE);
            $this->db->from('tbl_wh_detail_part_keluar AS a');
            $this->db->join('tbl_wh_part_keluar AS b','a.id_keluar=b.id_keluar','left');
            $this->db->join('tbl_wh_barang AS c','c.no_part = a.no_part','left');
            $this->db->join('tbl_wh_satuan AS d','d.id_satuan=c.satuan','left');
            $this->db->where('b.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.no_part', $no_part);
    
            
            $query_result = $this->db->get();
            return $data = $query_result->result();
        }
        /** End Perbarang */
           //** Report Perbarang */
           function cari_part_masuk($no_part,$ttmp1,$ttmp2)
           {
               $this->db->select('a.*', FALSE);
               $this->db->select('b.id_masuk as idnye,b.status,b.no_po,b.id_masuk,b.keterangan', FALSE);
               $this->db->from('tbl_wh_detail_part_masuk AS a');
               $this->db->join('tbl_wh_part_masuk AS b','a.id_masuk=b.kode_masuk','left');
               $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
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
        $this->db->select('a.tgl_masuk,a.kode_masuk,a.id_masuk,a.status_po,b.status_part,a.status,d.nama_sup AS nama_supplier,
        a.no_po,a.no_sj_sup,a.no_inv_sup,b.jumlah,sum(b.jumlah * c.harga_baru) AS total', FALSE);
        $this->db->from('tbl_wh_part_masuk as a');
        $this->db->join('tbl_wh_detail_part_masuk as b','a.kode_masuk=b.id_masuk','left');
        $this->db->join('tbl_wh_barang as c','c.no_part=b.no_part','left');
        $this->db->join('tbl_wh_supplier as d','d.kode_sup=a.kode_sup','left');
        $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
        $this->db->where('a.status_po',$status_po);
        $this->db->group_by('a.id_masuk');
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function cari_masuk_status($ttmp1 =null,$ttmp2=null,$status_po,$status)
    {
        $this->db->select('a.tgl_masuk,a.kode_masuk,a.id_masuk,a.status_po,b.status_part,a.status,d.nama_sup AS nama_supplier,
        a.no_po,a.no_sj_sup,a.no_inv_sup,b.jumlah,sum(b.jumlah * c.harga_baru) AS total', FALSE);
        $this->db->from('tbl_wh_part_masuk as a');
        $this->db->join('tbl_wh_detail_part_masuk as b','a.kode_masuk=b.id_masuk','left');
        $this->db->join('tbl_wh_barang as c','c.no_part=b.no_part','left');
        $this->db->join('tbl_wh_supplier as d','d.kode_sup=a.kode_sup','left');
        $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
        $this->db->where('a.status_po',$status_po);
        $this->db->where('a.status',$status);
        $this->db->group_by('a.id_masuk');
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function cari_masuk_npo($ttmp1 =null,$ttmp2=null,$status_po)
    {
        $this->db->select('a.tgl_masuk,a.kode_masuk,a.id_masuk,a.status_po,b.status_part,a.status,d.nama_sup AS nama_supplier,
        a.no_po,a.no_sj_sup,a.no_inv_sup,b.jumlah,sum(b.jumlah * c.hrg_awal) AS total', FALSE);
        $this->db->from('tbl_wh_part_masuk as a');
        $this->db->join('tbl_wh_detail_part_masuk as b','a.kode_masuk=b.id_masuk','left');
        $this->db->join('tbl_wh_barang as c','c.no_part=b.no_part','left');
        $this->db->join('tbl_wh_supplier as d','d.kode_sup=a.kode_sup','left');
        $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
        $this->db->where('a.status',$status_po);
        $this->db->where('a.status_po','N');
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
    function cari_detail_masuk($ttmp1 =null,$ttmp2=null,$status_po,$status)
    {
            $query = 'SET @dense_rank = 0;';
            $this->db->query($query);
            $query = 'SET @id_masuk = NULL;';
            $this->db->query($query);
            $this->db->select('@dense_rank:=CASE WHEN @id_masuk = a.kode_masuk THEN @dense_rank ELSE @dense_rank + 1 END AS row_urut, 
            @id_masuk:=b.id_masuk AS id_masuk, id, ROW_NUMBER() OVER(PARTITION BY b.id_masuk ORDER BY b.id_masuk) as row_no,
            a.id_masuk, a.tgl_masuk, a.id_masuk, a.status, a.keterangan,a.no_po,a.no_sj_sup,a.kode_sup,d.nama_sup,a.no_inv_sup,
            b.no_part,b.nama_part, b.jumlah, b.satuan, c.hrg_awal,b.jumlah*c.hrg_awal AS total', FALSE);
            $this->db->from('tbl_wh_part_masuk AS a');
            $this->db->join('tbl_wh_detail_part_masuk AS b','b.id_masuk=a.kode_masuk','left');
            $this->db->join('tbl_wh_barang AS c','c.no_part=b.no_part','left');
            $this->db->join('tbl_wh_supplier AS d','d.kode_sup=a.kode_sup','left');
            $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.status_po',$status_po);
            $this->db->where('a.status',$status);
            $this->db->order_by('b.id');
            $query_result = $this->db->get();
            return $data = $query_result->result();
    }
    function cari_detail_masuk_po($ttmp1 =null,$ttmp2=null,$status_po)
    {
        $this->db->select('a.tgl_masuk,a.kode_masuk,a.id_masuk,a.status_po,b.status_part,a.status,d.nama_sup AS nama_supplier,
        a.no_po,a.no_sj_sup,a.no_inv_sup,b.jumlah,sum(b.jumlah * c.hrg_awal) AS total', FALSE);
        $this->db->from('tbl_wh_part_masuk as a');
        $this->db->join('tbl_wh_detail_part_masuk as b','a.kode_masuk=b.id_masuk','left');
        $this->db->join('tbl_wh_barang as c','c.no_part=b.no_part','left');
        $this->db->join('tbl_wh_supplier as d','d.kode_sup=a.kode_sup','left');
        $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
        $this->db->where('a.status',$status_po);
        $this->db->where('a.status_po','Y');
        $query_result = $this->db->get();
        return $data = $query_result->result();
    }
    function cari_detail_masuk_npo($ttmp1 =null,$ttmp2=null,$status_po)
    {
        $this->db->select('a.tgl_masuk,a.kode_masuk,a.id_masuk,a.status_po,b.status_part,a.status,d.nama_sup AS nama_supplier,
        a.no_po,a.no_sj_sup,a.no_inv_sup,b.jumlah,sum(b.jumlah * c.hrg_awal) AS total', FALSE);
        $this->db->from('tbl_wh_part_masuk as a');
        $this->db->join('tbl_wh_detail_part_masuk as b','a.kode_masuk=b.id_masuk','left');
        $this->db->join('tbl_wh_barang as c','c.no_part=b.no_part','left');
        $this->db->join('tbl_wh_supplier as d','d.kode_sup=a.kode_sup','left');
        $this->db->where('a.tgl_masuk BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
        $this->db->where('a.status',$status_po);
        $this->db->where('a.status_po','N');
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
            $sql = "SELECT a.tgl_keluar,a.id_keluar,a.no_pk,a.no_body,a.status,c.ket_pk,a.keterangan,a.no_pk
            FROM tbl_wh_part_keluar AS a
            LEFT JOIN tbl_br_pk_aktif AS c ON c.id_pk = a.no_pk
            WHERE tujuan ='SPK' AND tgl_keluar BETWEEN '".$ttmp1."' AND '".$ttmp2."' ORDER BY a.id_keluar ASC";
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
            $query = 'SET @dense_rank = 0;';
            $this->db->query($query);
            $query = 'SET @id_keluar = NULL;';
            $this->db->query($query);
            $this->db->select('@dense_rank:=CASE WHEN @id_keluar = a.id_keluar
            THEN @dense_rank ELSE @dense_rank + 1 END AS row_urut, @id_keluar:=a.id_keluar AS id_keluar,id,
            ROW_NUMBER() OVER(PARTITION BY b.id_keluar ORDER BY b.id_keluar) as row_no,
                    a.kode_keluar,a.tgl_keluar,a.id_keluar,a.no_pk,a.no_body,a.status,a.keterangan,a.no_pk,a.ket_pk,a.tujuan,b.hrg_part,b.no_part,b.nama_part,b.jumlah,b.satuan', FALSE);
            $this->db->from('tbl_wh_part_keluar AS a');
            $this->db->join('tbl_wh_detail_part_keluar AS b','b.id_keluar=a.id_keluar','left');
            $this->db->where('a.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.no_spk','NON');
            $this->db->order_by('b.id');
            $query_result = $this->db->get();
            return $data = $query_result->result();
        }
        if($status_po=='Y'){
            $query = 'SET @dense_rank = 0;';
            $this->db->query($query);
            $query = 'SET @id_keluar = NULL;';
            $this->db->query($query);
            $this->db->select('@dense_rank:=CASE WHEN @id_keluar = a.id_keluar
            THEN @dense_rank ELSE @dense_rank + 1 END AS row_urut, @id_keluar:=a.id_keluar AS id_keluar,id,
            ROW_NUMBER() OVER(PARTITION BY b.id_keluar ORDER BY b.id_keluar) as row_no,
                    a.kode_keluar,a.tgl_keluar,a.id_keluar,a.no_pk,a.no_body,a.status,a.keterangan,a.no_pk,a.ket_pk,b.hrg_part,b.no_part,b.nama_part,b.jumlah,b.satuan', FALSE);
            $this->db->from('tbl_wh_part_keluar AS a');
            $this->db->join('tbl_wh_detail_part_keluar AS b','b.id_keluar=a.id_keluar','left');
            $this->db->where('a.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
            $this->db->where('a.tujuan','SPK');
            $this->db->order_by('b.id');
            $query_result = $this->db->get();
            return $data = $query_result->result();
            }
            if($status_po=='D'){
                $query = 'SET @dense_rank = 0;';
                $this->db->query($query);
                $query = 'SET @id_keluar = NULL;';
                $this->db->query($query);
                $this->db->select('@dense_rank:=CASE WHEN @id_keluar = a.id_keluar
                THEN @dense_rank ELSE @dense_rank + 1 END AS row_urut, @id_keluar:=a.id_keluar AS id_keluar,id,
                ROW_NUMBER() OVER(PARTITION BY b.id_keluar ORDER BY b.id_keluar) as row_no,
                        a.kode_keluar,a.tgl_keluar,a.id_keluar,a.no_pk,a.no_body,a.status,a.keterangan,a.no_pk,a.ket_pk,a.nama_divisi,b.hrg_part,b.no_part,b.nama_part,b.jumlah,b.satuan', FALSE);
                $this->db->from('tbl_wh_part_keluar AS a');
                $this->db->join('tbl_wh_detail_part_keluar AS b','b.id_keluar=a.id_keluar','left');
                $this->db->where('a.tgl_keluar BETWEEN "'.date($ttmp1).'"AND"'.date($ttmp2).'"');
                $this->db->where('a.divisi <>');
                $this->db->order_by('b.id');
                $query_result = $this->db->get();
                return $data = $query_result->result();
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