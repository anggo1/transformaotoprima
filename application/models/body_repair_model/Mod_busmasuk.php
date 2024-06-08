<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_busmasuk extends CI_Model
{
	var $table = 'tbl_br_laporan_bus';
	var $column_search = array('a.no_body','a.no_pol','a.nama_sp','a.nip_sp','c.kode','b.kategori','a.tgl_masuk','a.jam_masuk','a.keterangan'); 
	var $column_order = array('null','a.no_body','a.no_pol','a.nip_sp','c.kode','b.kategori','a.tgl_masuk','a.jam_masuk','a.keterangan');
	var $order = array('a.tgl_masuk' => 'asc'); 
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

		private function _get_datatables_query()
	{
		
		$this->db->select('a.*,b.kategori,c.kode');
        $this->db->from('tbl_br_laporan_bus as a');
        $this->db->join('tbl_br_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_br_ket_lapor as c','c.id=a.ket_lapor', 'left');
		$this->db->where('a.status', 'N');
		//$this->db->order_by('a.tgl_masuk', 'asc');
		$i = 0;

	foreach ($this->column_search as $item) // loop column 
	{
	if($_POST['search']['value']) // if datatable send POST for search
	{

	if($i===0) // first loop
	{
	$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
	$this->db->like($item, $_POST['search']['value']);
	}
	else
	{
		$this->db->or_like($item, $_POST['search']['value']);
		$this->db->or_like($item, $_POST['search']['value']);
	} 

		if(count($this->column_search) - 1 == $i) //last loop
		$this->db->group_end(); //close bracket
	}
	$i++;
	}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all()
	{
		$this->db->from('tbl_br_laporan_bus');
		return $this->db->count_all_results();
	}
	function get_part($id)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.nama_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.id_supplier=a.supplier', 'left');
        $this->db->where('a.id_barang', $id);
        return $this->db->get('tbl_wh_barang')->row();
    }
	function get_body()
    {
        $this->db->select('*');
        $this->db->from('tbl_br_bast');
        $this->db->where('status', 'N');
		$data = $this->db->get();

		return $data->result();
    }
	public function select_laporan()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_ket_lapor');
		//$this->db->join('tbl_pendidikan as b','a.pendidikan=b.id_pendidikan');
		//$this->db->join('tbl_supplier as c','a.supplier=c.id_supplier');
		//$this->db->join('tbl_departement as d','a.departement=d.id_departement');
		//$this->db->where('a.nip=',$id);

		$data = $this->db->get();

		return $data->result();
	}
	public function select_kategori()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_kategori');
		$data = $this->db->get();
		return $data->result();
	}

	// End Kategori //
	function select_estimasi($id)
		{
			$sql = "SELECT a.*,b.hrg_awal AS harga FROM tbl_br_detail_estimasi AS a
			LEFT JOIN tbl_wh_barang AS b ON b.no_part=a.no_part 
			WHERE id_lapor ='{$id}'";
	
			$data = $this->db->query($sql);
			return $data->result();
			//return $data->row();
		}
		function select_proses_pk($id)
		{
			$sql = "SELECT * FROM tbl_br_detail_estimasi WHERE id_lapor ='{$id}' GROUP BY jns_pk ";
	
			$data = $this->db->query($sql);
			return $data->result();
			//return $data->row();
		}
		function cari_pk($id,$kode)
		{
			$sql = "SELECT * FROM tbl_br_detail_estimasi as a
			LEFT JOIN tbl_br_kat_pk as b on b.kode=a.jns_pk  WHERE id_lapor ='{$id}' AND jns_pk ='{$kode}' GROUP BY jns_pk";
			$data = $this->db->query($sql);
			return $data->result();
			//return $data->row();
		}
	public function select_pk()
	{
		$sql = "SELECT COUNT(b.id_list) as jml_part, a.kode as kode, a.keterangan as keterangan FROM tbl_br_kat_pk as a
			LEFT JOIN tbl_br_list_estimasi as b on b.id_proses=a.id  GROUP BY a.id";
			$data = $this->db->query($sql);
		return $data->result();
	}
	public function insertLaporan($data)
    {
		
        date_default_timezone_set('Asia/Jakarta');
		$date= date("Y-m-d");
		$jam= date("H:i");
		$datekode= date("Ymd");

		$data_kategori = $data['kategori'];
		$id_kat = explode('|',$data_kategori);
		$id_kategori = $id_kat[0];
		$kode_kategori = $id_kat[1];

		$char ="";
		if($data['status_body']=="PPU"){$char='SP'.$kode_kategori;}else{$char='SM'.$kode_kategori;};
        $ci = get_instance();
        $qdata = "SELECT max(id_lapor) as maxKode FROM tbl_br_laporan_bus WHERE id_lapor LIKE '%$char%'";
        $dataQ = $ci->db->query($qdata)->row_array();
        $noOrder = $dataQ['maxKode'];
        $noUrut = (int) substr($noOrder, 8, 3);
        $noUrut++;
        $tahun=substr($datekode, 2, 2);
        $bln=substr($datekode, 4, 2);
        $kodeBaru  = $char.$tahun.$bln. sprintf("%03s", $noUrut);

        $date2 = $data['tgl_masuk'];
		$tgl2 = explode('-',$date2);
		$tgl_masuk = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$jam_masuk =$data['jam_masuk'];
		if(empty($jam_masuk)){
			$jam_masuk=$jam;
		}


        $sql = "INSERT INTO tbl_br_laporan_bus SET
            id_lapor	='".$kodeBaru."',
            tgl_masuk   ='".$tgl_masuk."',
            id_bast		='".$data['id_bast']."',
            jam_masuk	='".$jam_masuk."',
            no_body		='".$data['no_body']."',
            no_pol      ='".$data['no_pol']."',
            nip_sp      ='".$data['nip_sp']."',
            nama_sp     ='".$data['nama_sp']."',
            ket_lapor   ='".$data['ket_lapor']."',
            kategori    ='".$id_kategori."',
            keterangan  ='".$data['keterangan']."',
            status_body ='".$data['status_body']."',
            user        ='".$data['user']."',
            status      ='N'";

            $this->db->query($sql);
			
			$sql_update = "UPDATE tbl_br_bast SET status ='Y' WHERE id_bast ='".$data['id_bast']."'"; $this->db->query($sql_update);

            return $this->db->affected_rows();
    }
	public function insertEstimasi($data)
    {
        $date2 = $data['tgl_estimasi'];
		$tgl2 = explode('-',$date2);
		$tgl_estimasi = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$idUpdate = $data['id_lapor'];
		$no_body=$data['body_pk'];
		$id_lapor=$data['id_lapor'];
		$jns_pk=$data['jns_pk'];
		$pk = explode('|',$jns_pk);
		$pknya = $pk[0];
		$beaEstimasi=$data['biaya'];
		$hrg_naik=$data['hrg_naik'];
		$biaya =str_replace(".","", $beaEstimasi);

		$sql_update = "UPDATE tbl_br_laporan_bus SET estimasi ='Y' WHERE id_lapor ='{$idUpdate}'"; $this->db->query($sql_update);
		$query=$this->db->query("SELECT a.*, b.hrg_awal FROM tbl_br_list_estimasi AS a 
		LEFT JOIN tbl_wh_barang AS b ON b.no_part=a.no_part WHERE proses = '{$pknya}'")->result();

		$data = array();
		foreach($query as $key=>$value){ 
			$data[]  = array(
			'id_lapor'=>$id_lapor,
			'tgl_estimasi'=>$tgl_estimasi,
			'no_body'=>$no_body,
			'biaya'=>$biaya,
            'jns_pk'=>$pknya,
            'no_part'=>$value->no_part,
            'nama_part'=>$value->nama_part,
            'jml_part'=>$value->qty,
            'hrg_part'=>$value->hrg_awal,
            'total'=>$value->qty*$value->hrg_awal,
            'hrg_naik'=>$hrg_naik,
        );
    }
        $this->db->insert_batch('tbl_br_detail_estimasi', $data,'no_part');
            return $this->db->affected_rows();
    }
	function update_estimasi($id,$hrg_part,$jml_part)
		{			
		$jml =str_replace(" ","", $jml_part);
		$total=$hrg_part*$jml;
			$sql_update = "UPDATE tbl_br_detail_estimasi SET jml_part ='$jml', total = '$total' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
			
		return $this->db->affected_rows();
			//return $data->row();
		}
	function deleteLapor($id)
    {
		$sql_update = "UPDATE tbl_br_bast SET status ='N' WHERE id_bast ='{$id}'"; $this->db->query($sql_update);

        $sql = "DELETE FROM tbl_br_laporan_bus WHERE id_bast='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
	function deleteEstimasi($id)
    {
		$sql_update = "UPDATE tbl_br_laporan_bus SET estimasi ='N' WHERE id_lapor ='{$id}'"; $this->db->query($sql_update);
        $sql = "DELETE FROM tbl_br_detail_estimasi WHERE id_detail='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
	function deleteProses($id)
    {
        $sql1 = "DELETE FROM tbl_br_laporan_bus WHERE id_lapor='{$id}'";
		$this->db->query($sql1);
		$sql2 = "DELETE FROM tbl_br_pk_aktif WHERE id_lapor='{$id}'";
		$this->db->query($sql2);
		$sql3 = "DELETE FROM tbl_br_detail_estimasi WHERE id_lapor='{$id}'";
		$this->db->query($sql3);

		return $this->db->affected_rows();
    }
	function cetak_masuk($id)
    {
        $this->db->select('a.*,b.*,c.kategori,d.keterangan as ket_lapor');
        $this->db->from('tbl_br_laporan_bus as a');
        $this->db->join('tbl_br_detail_estimasi as b', 'b.id_lapor=a.id_lapor', 'left');
        $this->db->join('tbl_br_kategori as c', 'c.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_br_ket_lapor as d', 'd.id=a.ket_lapor', 'left');
        $this->db->where('a.id_lapor', $id);
        return $this->db->get('tbl_br_laporan_bus')->result();
		//return $data->result();
    }
	function cetak_estimasi($id)
    {
		$sql = "SELECT a.*,c.satuan AS satuan 
		FROM tbl_br_detail_estimasi as a
		LEFT JOIN tbl_wh_barang AS b ON b.no_part=a.no_part 
		LEFT JOIN tbl_wh_satuan AS c ON c.id_satuan=c.satuan 
		
		WHERE a.id_lapor = '{$id}' ORDER BY a.id_detail";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function harga_estimasi($id)
    {
		$sql = "SELECT  id_lapor,biaya,jns_pk,hrg_naik,SUM(total)AS totalnye
		FROM tbl_br_detail_estimasi WHERE id_lapor = '{$id}' GROUP BY jns_pk";
		$data = $this->db->query($sql);

		return $data->result();
    }
	function cetak_pk($id)
    {
		$sql = "SELECT * FROM tbl_br_pk_aktif AS a LEFT JOIN tbl_br_laporan_bus AS b ON b.id_lapor=a.id_lapor WHERE a.id_lapor ='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
    }

	public function insertPk($data)
    {
		$sql_update = "UPDATE tbl_br_detail_estimasi SET status ='Y' WHERE id_lapor ='".$data['id_lapor']."' AND jns_pk='".$data['jns_pk']."'";
		$this->db->query($sql_update);
		
		$ci_pk = get_instance();
        $pkdata = "SELECT COUNT(id_lapor) as jml_pk FROM tbl_br_detail_estimasi WHERE id_lapor = '".$data['id_lapor']."' AND status='N'";
        $datapk = $ci_pk->db->query($pkdata)->row_array();
		$jmlnya = $datapk['jml_pk'];
		if($jmlnya =='0'){
		$sql_pk = "UPDATE tbl_br_laporan_bus SET status ='Y' WHERE id_lapor ='".$data['id_lapor']."'";
		$this->db->query($sql_pk);
		}

        $date= date("Ymd");
        $ci = get_instance();
        $qdata = "SELECT max(id_pk) as maxKode FROM tbl_br_pk_aktif WHERE id_pk LIKE '%$date%'";
        $dataQ = $ci->db->query($qdata)->row_array();
        $noOrder = $dataQ['maxKode'];
        $noUrut = (int) substr($noOrder, 10, 3);
        $noUrut++;
        $char = "PK";
        $tahun=substr($date, 0, 4);
        $bulan=substr($date, 4, 2);
        $tgl=substr($date, 6, 2);
        $kodeBaru  = $char.$tahun.$bulan.$tgl. sprintf("%03s", $noUrut);

        $date2 = $data['tgl_mulai'];
		$tgl2 = explode('-',$date2);
		$tgl_mulai = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$number_borong=$data['biaya_borong'];
		$number =str_replace(".","", $number_borong);
        $sql = "INSERT INTO tbl_br_pk_aktif SET
            id_pk	='".$kodeBaru."',
            id_lapor	='".$data['id_lapor']."',
            tgl_mulai  ='".$tgl_mulai."',
            jam_mulai	='".$data['jam_mulai']."',
            no_body		='".$data['no_body']."',
            jns_pk      ='".$data['jns_pk']."',
            ket_pk      ='".$data['ket_pk']."',
            status     ='Y',
            pt_pemborong   ='".$data['pt_pemborong']."',
            pj_borong    ='".$data['pj_borong']."',
            biaya_borong    ='$number'
			";

            $this->db->query($sql);
    
            return $this->db->affected_rows();
    }
	function select_pk_mulai($id)
		{
			$sql = "SELECT * FROM tbl_br_pk_aktif WHERE id_lapor ='{$id}'";
	
			$data = $this->db->query($sql);
			return $data->result();
			//return $data->row();
		}
}