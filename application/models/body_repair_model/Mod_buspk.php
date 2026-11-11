<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_buspk extends CI_Model
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
		WHERE tbl_br_laporan_bus.status !='N' AND tbl_br_laporan_bus.status !='S'AND tbl_br_laporan_bus.status !='K'
		GROUP BY `tbl_br_pk_aktif`.`id_lapor`";

		$data = $this->db->query($sql);

		return $data->result();
	}	
	public function insertLapor($data)
	{
		$sql = "INSERT INTO tbl_br_ket_lapor VALUES
		('','" . $this->db->escape_str((string) $data['kode']) . "','" . $this->db->escape_str((string) $data['keterangan']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function pausepk($data)
	{
        $date = date("Y-m-d");
        $jam = date("H:i:s");
		$sqldetail = "UPDATE tbl_br_pk_aktif SET status='P',ket_pause='" . $this->db->escape_str((string) $data['ket_pause']) . "',jam_pause='" .$this->db->escape_str((string) $jam). "',tgl_pause='" .$this->db->escape_str((string) $date). "'
        WHERE id_lapor='" . $this->db->escape_str((string) $data['id_lapor']) . "'";
		$this->db->query($sqldetail);

		$sql = "UPDATE tbl_br_laporan_bus SET status='P',ket_status='" . $this->db->escape_str((string) $data['ket_pause']) . "'
        WHERE id_lapor='" . $this->db->escape_str((string) $data['id_lapor']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function startPk($id)
	{
		$date = date("Y-m-d");
        $jam = date("H:i:s");
		
		$sqldetail = "UPDATE tbl_br_pk_aktif SET status='Y',jam_start='" .$this->db->escape_str((string) $jam). "',tgl_start='" .$this->db->escape_str((string) $date). "'
        WHERE id_lapor='" . $this->db->escape_str((string) $id). "'";
		$this->db->query($sqldetail);

		$sql = "UPDATE tbl_br_laporan_bus SET status='Y' WHERE id_lapor='" . $this->db->escape_str((string) $id). "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function pkSelesai($id,$no_body)
	{
		$date = date("Y-m-d");
        $jam = date("H:i:s");
		$sqlbay = "UPDATE tbl_br_bay SET keterangan='' WHERE keterangan='" . $this->db->escape_str((string) $no_body) . "'";$this->db->query($sqlbay);
		
		$sqlc = "DELETE FROM tbl_br_antri_cat WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlc);		
		$sqlq = "DELETE FROM tbl_br_antri_qc WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlq);		
		$sqlp = "DELETE FROM tbl_br_antri_ph WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlp);		
		$sqlj = "DELETE FROM tbl_br_antri_jok WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlj);		
		$sqle = "DELETE FROM tbl_br_antri_elektrik WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqle);		
		$sqlt = "DELETE FROM tbl_br_antri_triming WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlt);		

		$sqltutup = "UPDATE tbl_br_pk_aktif SET status='S',jam_selesai='" .$this->db->escape_str((string) $jam). "',tgl_selesai='" .$this->db->escape_str((string) $date). "'
        WHERE id_lapor='" . $this->db->escape_str((string) $id) . "'";

		$this->db->query($sqltutup);

		$sql = "UPDATE tbl_br_laporan_bus SET status='S',tgl_selesai='" .$this->db->escape_str((string) $date). "' WHERE id_lapor='" . $this->db->escape_str((string) $id). "'";

		$this->db->query($sql);


		return $this->db->affected_rows();
	}
	function masukBay($id_lapor,$id_bay,$no_body)
	{	
		$ci_bay1 = get_instance();
		$bay1 = "SELECT id_bay AS bay FROM tbl_br_bay WHERE keterangan = '".$this->db->escape_str((string) $no_body)."'";
		$dbay1 = $ci_bay1->db->query($bay1)->row_array();
		if($dbay1>0){
		$by1 = $dbay1['bay'];
		$sqla = "DELETE FROM tbl_br_antri_cat WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqla);
		$sqlb = "DELETE FROM tbl_br_antri_elektrik WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlb);
		$sqlc = "DELETE FROM tbl_br_antri_jok WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlc);
		$sqld = "DELETE FROM tbl_br_antri_ph WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqld);
		$sqle = "DELETE FROM tbl_br_antri_qc WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqle);
		$sqlf = "DELETE FROM tbl_br_antri_triming WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlf);
		$sqlbayedit = "UPDATE tbl_br_bay SET keterangan='' WHERE id_bay='".$this->db->escape_str((string) $by1)."'";$this->db->query($sqlbayedit);
		$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
		$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		return $this->db->affected_rows();
		}else{
			$sqla = "DELETE FROM tbl_br_antri_cat WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqla);
			$sqlb = "DELETE FROM tbl_br_antri_elektrik WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlb);
			$sqlc = "DELETE FROM tbl_br_antri_jok WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlc);
			$sqld = "DELETE FROM tbl_br_antri_ph WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqld);
			$sqle = "DELETE FROM tbl_br_antri_qc WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqle);
			$sqlf = "DELETE FROM tbl_br_antri_triming WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlf);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
			return $this->db->affected_rows();
		}
	}
	function masukCat($id_lapor,$id_bay,$no_body)
	{	
		$ci_bay1 = get_instance();
		$bay1 = "SELECT id_bay AS bay FROM tbl_br_bay WHERE keterangan = '".$this->db->escape_str((string) $no_body)."'";
		$dbay1 = $ci_bay1->db->query($bay1)->row_array();
		if($dbay1>0){
			$by1 = $dbay1['bay'];
			$sqlbayedit = "UPDATE tbl_br_bay SET keterangan='' WHERE id_bay='".$this->db->escape_str((string) $by1)."'";$this->db->query($sqlbayedit);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}else{
			$sqlb = "DELETE FROM tbl_br_antri_elektrik WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlb);
			$sqlc = "DELETE FROM tbl_br_antri_jok WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlc);
			$sqld = "DELETE FROM tbl_br_antri_ph WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqld);
			$sqle = "DELETE FROM tbl_br_antri_qc WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqle);
			$sqlf = "DELETE FROM tbl_br_antri_triming WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlf);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}
		$ci_bay = get_instance();
		$bay = "SELECT no_body FROM tbl_br_antri_cat WHERE no_body = '".$this->db->escape_str((string) $no_body)."'";
		$dbay = $ci_bay->db->query($bay)->row_array();
		if($dbay < 1){
		$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		$sqlpk = "INSERT INTO tbl_br_antri_cat SET no_body ='".$this->db->escape_str((string) $no_body)."'";
            $this->db->query($sqlpk);
            return $this->db->affected_rows();
		}
	}
	function masukTriming($id_lapor,$id_bay,$no_body)
	{	
		$ci_bay1 = get_instance();
		$bay1 = "SELECT id_bay AS bay FROM tbl_br_bay WHERE keterangan = '".$this->db->escape_str((string) $no_body)."'";
		$dbay1 = $ci_bay1->db->query($bay1)->row_array();
		if($dbay1>0){
			$by1 = $dbay1['bay'];
			$sqlbayedit = "UPDATE tbl_br_bay SET keterangan='' WHERE id_bay='".$this->db->escape_str((string) $by1)."'";$this->db->query($sqlbayedit);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}else{
			$sqla = "DELETE FROM tbl_br_antri_cat WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqla);
			$sqlb = "DELETE FROM tbl_br_antri_elektrik WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlb);
			$sqlc = "DELETE FROM tbl_br_antri_jok WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlc);
			$sqld = "DELETE FROM tbl_br_antri_ph WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqld);
			$sqle = "DELETE FROM tbl_br_antri_qc WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqle);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}
		$ci_bay = get_instance();
		$bay = "SELECT no_body FROM tbl_br_antri_triming WHERE no_body = '".$this->db->escape_str((string) $no_body)."'";
		$dbay = $ci_bay->db->query($bay)->row_array();
		if($dbay < 1){
		$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		$sqlpk = "INSERT INTO tbl_br_antri_triming SET no_body ='".$this->db->escape_str((string) $no_body)."'";
            $this->db->query($sqlpk);
            return $this->db->affected_rows();
		}
	}
	function masukElektrik($id_lapor,$id_bay,$no_body)
	{	
		$ci_bay1 = get_instance();
		$bay1 = "SELECT id_bay AS bay FROM tbl_br_bay WHERE keterangan = '".$this->db->escape_str((string) $no_body)."'";
		$dbay1 = $ci_bay1->db->query($bay1)->row_array();
		if($dbay1>0){
			$by1 = $dbay1['bay'];
			$sqlbayedit = "UPDATE tbl_br_bay SET keterangan='' WHERE id_bay='".$this->db->escape_str((string) $by1)."'";$this->db->query($sqlbayedit);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}else{
			$sqla = "DELETE FROM tbl_br_antri_cat WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqla);
			$sqlc = "DELETE FROM tbl_br_antri_jok WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlc);
			$sqld = "DELETE FROM tbl_br_antri_ph WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqld);
			$sqle = "DELETE FROM tbl_br_antri_qc WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqle);
			$sqlf = "DELETE FROM tbl_br_antri_triming WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlf);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}
		$ci_bay = get_instance();
		$bay = "SELECT no_body FROM tbl_br_antri_elektrik WHERE no_body = '".$this->db->escape_str((string) $no_body)."'";
		$dbay = $ci_bay->db->query($bay)->row_array();
		if($dbay < 1){
		$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		$sqlpk = "INSERT INTO tbl_br_antri_elektrik SET no_body ='".$this->db->escape_str((string) $no_body)."'"; $this->db->query($sqlpk);
            return $this->db->affected_rows();
		}
	}
	function masukJok($id_lapor,$id_bay,$no_body)
	{	
		$ci_bay1 = get_instance();
		$bay1 = "SELECT id_bay AS bay FROM tbl_br_bay WHERE keterangan = '".$this->db->escape_str((string) $no_body)."'";
		$dbay1 = $ci_bay1->db->query($bay1)->row_array();
		if($dbay1>0){
			$by1 = $dbay1['bay'];
			$sqlbayedit = "UPDATE tbl_br_bay SET keterangan='' WHERE id_bay='".$this->db->escape_str((string) $by1)."'";$this->db->query($sqlbayedit);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}else{
			$sqla = "DELETE FROM tbl_br_antri_cat WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqla);
			$sqlb = "DELETE FROM tbl_br_antri_elektrik WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlb);
			$sqld = "DELETE FROM tbl_br_antri_ph WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqld);
			$sqle = "DELETE FROM tbl_br_antri_qc WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqle);
			$sqlf = "DELETE FROM tbl_br_antri_triming WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlf);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}
		$ci_bay = get_instance();
		$bay = "SELECT no_body FROM tbl_br_antri_jok WHERE no_body = '".$this->db->escape_str((string) $no_body)."'";
		$dbay = $ci_bay->db->query($bay)->row_array();
		if($dbay < 1){
		$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		$sqlpk = "INSERT INTO tbl_br_antri_jok SET no_body ='".$this->db->escape_str((string) $no_body)."'";
            $this->db->query($sqlpk);
            return $this->db->affected_rows();
		}
	}
	function masukPh($id_lapor,$id_bay,$no_body)
	{	
		$ci_bay1 = get_instance();
		$bay1 = "SELECT id_bay AS bay FROM tbl_br_bay WHERE keterangan = '".$this->db->escape_str((string) $no_body)."'";
		$dbay1 = $ci_bay1->db->query($bay1)->row_array();
		if($dbay1>0){
			$by1 = $dbay1['bay'];
			$sqlbayedit = "UPDATE tbl_br_bay SET keterangan='' WHERE id_bay='".$this->db->escape_str((string) $by1)."'";$this->db->query($sqlbayedit);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}else{
			$sqla = "DELETE FROM tbl_br_antri_cat WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqla);
			$sqlb = "DELETE FROM tbl_br_antri_elektrik WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlb);
			$sqlc = "DELETE FROM tbl_br_antri_jok WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlc);
			$sqle = "DELETE FROM tbl_br_antri_qc WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqle);
			$sqlf = "DELETE FROM tbl_br_antri_triming WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlf);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}
		$ci_bay = get_instance();
		$bay = "SELECT no_body FROM tbl_br_antri_ph WHERE no_body = '".$this->db->escape_str((string) $no_body)."'";
		$dbay = $ci_bay->db->query($bay)->row_array();
		if($dbay < 1){
		$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		$sqlpk = "INSERT INTO tbl_br_antri_ph SET no_body ='".$this->db->escape_str((string) $no_body)."'";
            $this->db->query($sqlpk);
            return $this->db->affected_rows();
		}
	}
	function masukQc($id_lapor,$id_bay,$no_body)
	{	
		$ci_bay1 = get_instance();
		$bay1 = "SELECT id_bay AS bay FROM tbl_br_bay WHERE keterangan = '".$this->db->escape_str((string) $no_body)."'";
		$dbay1 = $ci_bay1->db->query($bay1)->row_array();
		if($dbay1>0){
			$by1 = $dbay1['bay'];
			$sqlbayedit = "UPDATE tbl_br_bay SET keterangan='' WHERE id_bay='".$this->db->escape_str((string) $by1)."'";$this->db->query($sqlbayedit);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}else{
			$sqla = "DELETE FROM tbl_br_antri_cat WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqla);
			$sqlb = "DELETE FROM tbl_br_antri_elektrik WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlb);
			$sqlc = "DELETE FROM tbl_br_antri_jok WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlc);
			$sqld = "DELETE FROM tbl_br_antri_ph WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqld);
			$sqlf = "DELETE FROM tbl_br_antri_triming WHERE no_body='".$this->db->escape_str((string) $no_body)."'";$this->db->query($sqlf);
			$sqlbay = "UPDATE tbl_br_bay SET keterangan='".$this->db->escape_str((string) $no_body)."' WHERE id_bay='".$this->db->escape_str((string) $id_bay)."'";$this->db->query($sqlbay);
			$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		}
		$ci_bay = get_instance();
		$bay = "SELECT no_body FROM tbl_br_antri_qc WHERE no_body = '".$this->db->escape_str((string) $no_body)."'";
		$dbay = $ci_bay->db->query($bay)->row_array();
		if($dbay < 1){
		$sql = "UPDATE tbl_br_laporan_bus SET no_bay='".$this->db->escape_str((string) $id_bay)."' WHERE id_lapor='".$this->db->escape_str((string) $id_lapor)."'";$this->db->query($sql);
		$sqlpk = "INSERT INTO tbl_br_antri_qc SET no_body ='".$this->db->escape_str((string) $no_body)."'";
            $this->db->query($sqlpk);
            return $this->db->affected_rows();
		}
	}
	public function select_antri_cat()
	{
		$sql = "SELECT * FROM `tbl_br_antri_cat` ";
        $data = $this->db->query($sql);
        return $data->result();
	}public function select_antri_triming()
	{
		$sql = "SELECT * FROM `tbl_br_antri_triming` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_elektrik()
	{
		$sql = "SELECT * FROM `tbl_br_antri_elektrik` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_qc()
	{
		$sql = "SELECT * FROM `tbl_br_antri_qc` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_jok()
	{
		$sql = "SELECT * FROM `tbl_br_antri_jok` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_ph()
	{
		$sql = "SELECT * FROM `tbl_br_antri_ph` ";
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
		$sql = "SELECT * FROM tbl_br_laporan_bus AS a LEFT JOIN tbl_br_pk_aktif AS b ON a.id_lapor=b.id_lapor WHERE a.id_lapor ='".$this->db->escape_str((string) $id)."'";

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

		$ketpk = "SELECT keterangan as ket_pk FROM tbl_br_kat_pk WHERE kode = '".$this->db->escape_str((string) $data['jns_pk'])."'";
        $datap = $ci->db->query($ketpk)->row_array();
        $pknye = $datap['ket_pk'];
			$sqlpk = "INSERT INTO tbl_br_pk_aktif SET
            id_pk	='".$this->db->escape_str((string) $kodeBaru)."',
            id_lapor	='".$this->db->escape_str((string) $data['id_lapor'])."',
            tgl_mulai   ='".$this->db->escape_str((string) $tgl_estimasi)."',
            jam_mulai	='".$this->db->escape_str((string) $jam)."',
            no_body		='".$this->db->escape_str((string) $data['body_pk'])."',
            jns_pk      ='".$this->db->escape_str((string) $data['jns_pk'])."',
            ket_pk      ='".$this->db->escape_str((string) $pknye)."',
            status     ='Y',
            pt_pemborong   ='".$this->db->escape_str((string) $data['pt_pemborong'])."',
            pj_borong    ='".$this->db->escape_str((string) $data['pj_borong'])."',
            biaya_borong    ='".$this->db->escape_str((string) $esBea)."'";
            $this->db->query($sqlpk);
            return $this->db->affected_rows();
    }
}