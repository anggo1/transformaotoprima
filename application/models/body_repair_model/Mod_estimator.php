<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_estimator extends CI_Model
{
	var $table = 'tbl_br_laporan_bus';
	var $column_search = array('a.tgl_masuk','a.jam_masuk','a.nobody','a.no_pol','a.nip_sp','a.nama_sp','b.kategori','c.kode','a.keterangan'); 
	var $column_order = array('a.tgl_masuk','a.jam_masuk','a.nobody','a.no_pol','a.nip_sp','a.nama_sp','b.kategori','c.kode','a.keterangan');
	var $order = array('no_body' => 'desc'); 
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
	public function select_pk()
	{
		$sql = "SELECT COUNT(b.id_list) as jml_part, a.kode as kode, a.keterangan as keterangan FROM tbl_br_kat_pk as a
			LEFT JOIN tbl_br_list_estimasi as b on b.id_proses=a.id  GROUP BY a.id";
			$data = $this->db->query($sql);
		return $data->result();
	}
	function select_lapor($id)
	{
		$sql = "SELECT * FROM tbl_br_laporan_bus 
		WHERE id_lapor ='{$id}'";

		$data = $this->db->query($sql);
		return $data->result();
		//return $data->row();
	}
	function select_estimasi($id)
	{
		$sql = "SELECT a.*,b.hrg_awal AS harga FROM tbl_br_detail_estimasi AS a
		LEFT JOIN tbl_wh_barang AS b ON b.no_part=a.no_part 
		WHERE id_lapor ='{$id}'";

		$data = $this->db->query($sql);
		return $data->result();
		//return $data->row();
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
        $sql = "DELETE FROM tbl_br_laporan_bus WHERE id_lapor='{$id}'";

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

}