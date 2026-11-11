<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_pembayaranpk extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function select_pk($id)
	{
		$sql = "SELECT a.biaya_borong - SUM(b.jumlah) AS sisanye,a.id_pk,a.no_body,a.ket_pk,a.pj_borong,a.biaya_borong
		FROM tbl_br_pk_aktif AS a 
		LEFT JOIN tbl_br_upah_borongan AS b ON b.id_pk=a.id_pk
		WHERE a.id_pk= '".$this->db->escape_str((string) $id)."'";
		$data = $this->db->query($sql);
		return $data->result();
	}
	public function select_pk2()
	{
		$sql = "SELECT a.id_pk,a.no_body,a.ket_pk,a.pj_borong,a.biaya_borong
		FROM tbl_br_pk_aktif AS a
		WHERE a.biaya_borong !=0 ";
		$data = $this->db->query($sql);
		return $data->result();
	}
	public function select_sisa($id)
	{
		$sql = "SELECT a.biaya_borong - SUM(b.jumlah) AS sisanye   FROM tbl_br_pk_aktif AS a 
		LEFT JOIN tbl_br_upah_borongan AS b ON b.id_pk=a.id_pk
		WHERE a.id_pk= '".$this->db->escape_str((string) $id)."'";
		$data = $this->db->query($sql);
		return $data->result();
	}
	public function insertBayar($data)
    {
        $datenow = date("Y-m-d");
		$ci = get_instance();
        $qdata = "SELECT * FROM tbl_br_upah_borongan WHERE id_pk = '".$this->db->escape_str((string) $data['id_pk'])."' ORDER BY id DESC";
        $dataQ = $ci->db->query($qdata)->row_array();
		$idpk = $dataQ['id_pk'];
        $sisanya = $dataQ['sisa'];
        if($dataQ > 0){
			$sql = "INSERT INTO tbl_br_upah_borongan SET
				id       	='',
				tgl_bayar   ='" .$this->db->escape_str((string) $datenow). "',
				id_pk       ='" . $this->db->escape_str((string) $data['id_pk']) . "',
				no_body     ='" . $this->db->escape_str((string) $data['no_body']) . "',
				jns_pk      ='" . $this->db->escape_str((string) $data['ket_pk']) . "',
				biaya_borong='" . $this->db->escape_str((string) $data['biaya']) . "',
				sisa		='" . $this->db->escape_str((string) $sisanya). "'";
			$this->db->query($sql);
			return $this->db->affected_rows();
		} else {
        $sql = "INSERT INTO tbl_br_upah_borongan SET
            id       	='',
            tgl_bayar   ='" .$this->db->escape_str((string) $datenow). "',
            id_pk       ='" . $this->db->escape_str((string) $data['id_pk']) . "',
            no_body     ='" . $this->db->escape_str((string) $data['no_body']) . "',
            jns_pk      ='" . $this->db->escape_str((string) $data['ket_pk']) . "',
            biaya_borong='" . $this->db->escape_str((string) $data['biaya']) . "',
            sisa		='" . $this->db->escape_str((string) $data['biaya']) . "'";
        $this->db->query($sql);
        return $this->db->affected_rows();
		}
    }
	public function detailpk($data)
	{
        $date = date("Y-m-d");
        $jam = date("H:i:s");
		$sql = "INSERT tbl_br_detail_pk SET id_lapor='".$this->db->escape_str((string) $data['id_lapor'])."',id_pk='".$this->db->escape_str((string) $data['id_pk'])."',
		jns_pk='".$this->db->escape_str((string) $data['jns_pk'])."',ket_pk='".$this->db->escape_str((string) $data['ket_pk'])."',ket_detail='".$this->db->escape_str((string) $data['ket_detail'])."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function update_tgl_pembayaran($id,$tgl_bayar)
		{
			$sql_update = "UPDATE tbl_br_upah_borongan SET tgl_bayar = '".$this->db->escape_str((string) $tgl_bayar)."' WHERE id ='".$this->db->escape_str((string) $id)."'";
			$this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
	
    function update_ket_pembayaran($id,$keterangan)
		{
			$sql_update = "UPDATE tbl_br_upah_borongan SET keterangan = '".$this->db->escape_str((string) $keterangan)."' WHERE id ='".$this->db->escape_str((string) $id)."'";
			$this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
		function update_pembayaran($id,$sisa,$jumlah)
		{			
		$jml =str_replace(",","", $jumlah);
		$total=$sisa - $jml;
			$sql_update = "UPDATE tbl_br_upah_borongan SET jumlah = '".$this->db->escape_str((string) $jml)."', sisa ='".$this->db->escape_str((string) $total)."' WHERE id ='".$this->db->escape_str((string) $id)."'";
			$this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
	function deleteDetail_bayar($id)
	{
		$sql = "DELETE FROM tbl_br_upah_borongan WHERE id='".$this->db->escape_str((string) $id)."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function select_detail($id)
	{
		$sql = "SELECT * FROM tbl_br_upah_borongan WHERE id_pk = '".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	//end Satuan//
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
		$sql = "SELECT a.*,b.keterangan as ket_pk FROM tbl_br_detail_estimasi as a
		LEFT JOIN tbl_br_kat_pk as b ON b.kode=a.jns_pk WHERE a.id_lapor = '".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function cetak_pk($id)
    {
		$sql = "SELECT * FROM tbl_br_pk_aktif AS a LEFT JOIN tbl_br_laporan_bus AS b ON b.id_lapor=a.id_lapor WHERE a.id_pk ='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function cetak_detail($id)
    {
		$sql = "SELECT * FROM tbl_br_detail_pk  WHERE id_pk ='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	//** end Keterangan PK **//

}
