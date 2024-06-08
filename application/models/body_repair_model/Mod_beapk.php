<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_beapk extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function select_pk_empty_id()
	{
		$sql = "SELECT COUNT(b.id_lapor) AS jml_pk,a.*
		FROM tbl_br_laporan_bus AS a
		LEFT JOIN tbl_br_pk_aktif AS b ON b.id_lapor=a.id_lapor
		WHERE a.status !='K'
		GROUP BY a.id_lapor";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function select_pk($id)
	{
		$sql = "SELECT COUNT(b.id_lapor) AS jml_pk,a.*
		FROM tbl_br_laporan_bus AS a
		LEFT JOIN tbl_br_pk_aktif AS b ON b.id_lapor=a.id_lapor
		WHERE a.id_lapor ='{$id}'
		GROUP BY a.id_lapor";

		$data = $this->db->query($sql);

		return $data->result();
	}	
	public function insertLapor($data)
	{
		$sql = "INSERT INTO tbl_br_ket_lapor VALUES
		('','" . $data['kode'] . "','" . $data['keterangan'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function insertBea($data)
	{
		$biaya=$data['biaya'];
		$esBea =str_replace(",","", $biaya);
		$sql1 = "UPDATE tbl_br_upah_borongan SET biaya_borong='" . $esBea . "'
        WHERE id_pk='" . $data['id_pk'] . "'";

		$this->db->query($sql1);
		$sql = "UPDATE tbl_br_pk_aktif SET biaya_borong='" . $esBea . "'
        WHERE id_pk='" . $data['id_pk'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function startPk($id)
	{
		$date = date("Y-m-d");
        $jam = date("H:i:s");
		
		$sqldetail = "UPDATE tbl_br_pk_aktif SET status='Y',jam_start='" .$jam. "',tgl_start='" .$date. "'
        WHERE id_lapor='" . $id. "'";
		$this->db->query($sqldetail);

		$sql = "UPDATE tbl_br_laporan_bus SET status='Y' WHERE id_lapor='" . $id. "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function pkSelesai($id,$no_body)
	{
		$date = date("Y-m-d");
        $jam = date("H:i:s");
		$sqlbay = "UPDATE tbl_br_bay SET keterangan='' WHERE keterangan='" . $no_body . "'";
		$this->db->query($sqlbay);

		$sqltutup = "UPDATE tbl_br_pk_aktif SET status='S',jam_selesai='" .$jam. "',tgl_selesai='" .$date. "'
        WHERE id_lapor='" . $id . "'";
		$this->db->query($sqltutup);

		$sql = "UPDATE tbl_br_laporan_bus SET status='S',tgl_selesai='" .$date. "' WHERE id_lapor='" . $id. "'";

		$this->db->query($sql);


		return $this->db->affected_rows();
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
		LEFT JOIN tbl_br_kat_pk as b ON b.kode=a.jns_pk WHERE a.id_lapor = '{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function cetak_pk($id)
    {
		$sql = "SELECT * FROM tbl_br_laporan_bus AS a LEFT JOIN tbl_br_pk_aktif AS b ON a.id_lapor=b.id_lapor WHERE a.id_lapor ='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function select_bay()
    {
		$sql = "SELECT * FROM `tbl_br_bay`";

		$data = $this->db->query($sql);

		return $data->result();
    }
	public function select_jenis_pk()
	{
		$this->db->select('*');
		$this->db->from('tbl_br_kat_pk');
		$data = $this->db->get();
		return $data->result();
	}
	public function insertPkBaru($data)
    {
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

        $date2 = $data['tgl_estimasi'];
		$tgl2 = explode('-',$date2);
		$tgl_estimasi = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
		$beaEstimasi=$data['biaya'];
		$esBea =str_replace(".","", $beaEstimasi);
		$jam=date("H-m");

		$ketpk = "SELECT keterangan as ket_pk FROM tbl_br_kat_pk WHERE kode = '".$data['jns_pk']."'";
        $datap = $ci->db->query($ketpk)->row_array();
        $pknye = $datap['ket_pk'];
			$sqlpk = "INSERT INTO tbl_br_pk_aktif SET
            id_pk	='".$kodeBaru."',
            id_lapor	='".$data['id_lapor']."',
            tgl_mulai   ='".$tgl_estimasi."',
            jam_mulai	='".$jam."',
            no_body		='".$data['body_pk']."',
            jns_pk      ='".$data['jns_pk']."',
            ket_pk      ='".$pknye."',
            status     ='Y',
            pt_pemborong   ='".$data['pt_pemborong']."',
            pj_borong    ='".$data['pj_borong']."',
            biaya_borong    ='$esBea'";
            $this->db->query($sqlpk);
            return $this->db->affected_rows();
    }
}
