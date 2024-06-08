<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_buskeluar extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function select_pk()
	{
		
		//$sql = "SELECT COUNT(tbl_br_pk_aktif.id_lapor) AS `jml_pk`,`tbl_br_bay`.`id_bay` AS `no_bay`,`tbl_br_laporan_bus`.* FROM `tbl_br_laporan_bus`
		//LEFT JOIN `tbl_br_pk_aktif` ON `tbl_br_pk_aktif`.`id_lapor`=`tbl_br_laporan_bus`.`id_lapor`
		//LEFT JOIN `tbl_br_bay` ON `tbl_br_laporan_bus`.`no_body`=`tbl_br_bay`.`keterangan`
		//GROUP BY `tbl_br_pk_aktif`.`id_lapor`";
		$sql = "SELECT COUNT(tbl_br_pk_aktif.id_lapor) AS `jml_pk`,`tbl_br_laporan_bus`.* FROM `tbl_br_laporan_bus`
		LEFT JOIN `tbl_br_pk_aktif` ON `tbl_br_pk_aktif`.`id_lapor`=`tbl_br_laporan_bus`.`id_lapor`
		WHERE tbl_br_laporan_bus.status ='S'
		GROUP BY `tbl_br_pk_aktif`.`id_lapor`";

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
	public function pausepk($data)
	{
        $date = date("Y-m-d");
        $jam = date("H:i:s");
		$sqldetail = "UPDATE tbl_br_pk_aktif SET status='P',ket_pause='" . $data['ket_pause'] . "',jam_pause='" .$jam. "',tgl_pause='" .$date. "'
        WHERE id_lapor='" . $data['id_lapor'] . "'";
		$this->db->query($sqldetail);

		$sql = "UPDATE tbl_br_laporan_bus SET status='P',ket_status='" . $data['ket_pause'] . "'
        WHERE id_lapor='" . $data['id_lapor'] . "'";

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
	function pkSelesai($id)
	{
		$date = date("Y-m-d");
        $jam = date("H:i:s");
		$sql = "UPDATE tbl_br_laporan_bus SET status='K' WHERE id_lapor='" . $id. "'";

		$this->db->query($sql);


		return $this->db->affected_rows();
	}
	function masukBay($id_lapor,$id_bay,$no_body)
	{	$ci_bay1 = get_instance();
		$bay1 = "SELECT id_bay AS bay FROM tbl_br_bay WHERE keterangan = '".$no_body."'";
		$dbay1 = $ci_bay1->db->query($bay1)->row_array();
		if($dbay1>0){
		$by1 = $dbay1['bay'];
		$sqlbayedit = "UPDATE tbl_br_bay SET keterangan='' WHERE id_bay='".$by1."'";$this->db->query($sqlbayedit);
		$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$no_body."' WHERE id_bay='".$id_bay."'";$this->db->query($sqlbay);
		$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$id_bay."' WHERE id_lapor='".$id_lapor."'";$this->db->query($sql);
		return $this->db->affected_rows();
		}else{
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$no_body."' WHERE id_bay='".$id_bay."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$id_bay."' WHERE id_lapor='".$id_lapor."'";$this->db->query($sql);
			return $this->db->affected_rows();
		}
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
