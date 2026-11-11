<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_tambahpk extends CI_Model
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
		GROUP BY `tbl_br_pk_aktif`.`id_lapor`";

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
		$sql = "UPDATE tbl_br_pk_aktif SET status='P',ket_pause='" . $this->db->escape_str((string) $data['ket_pause']) . "',jam_pause='" .$this->db->escape_str((string) $jam). "',tgl_pause='" .$this->db->escape_str((string) $date). "'
        WHERE id_pk='" . $this->db->escape_str((string) $data['id_pk']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function startPk($id)
	{
		$date = date("Y-m-d");
        $jam = date("H:i:s");
		$sql = "UPDATE tbl_br_pk_aktif SET status='Y',jam_start='" .$this->db->escape_str((string) $jam). "',tgl_start='" .$this->db->escape_str((string) $date). "'
        WHERE id_pk='" . $this->db->escape_str((string) $id) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function pkSelesai($id)
	{
		$date = date("Y-m-d");
        $jam = date("H:i:s");
		$sql = "UPDATE tbl_br_pk_aktif SET status='S',jam_selesai='" .$this->db->escape_str((string) $jam). "',tgl_selesai='" .$this->db->escape_str((string) $date). "'
        WHERE id_pk='" . $this->db->escape_str((string) $id) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function select_estimasi($id)
	{
		$sql = "SELECT * FROM tbl_br_detail_estimasi WHERE id_lapor ='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);
		return $data->result();
		//return $data->row();
	}
	public function insertEstimasi($data)
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

		$cicari = get_instance();
        $qcari = "SELECT COUNT(jns_pk) as ketemu FROM tbl_br_pk_aktif WHERE id_lapor = '".$this->db->escape_str((string) $data['id_lapor'])."' AND jns_pk = '".$this->db->escape_str((string) $data['jns_pk'])."'";
        $hasil_cari = $cicari->db->query($qcari)->row_array();
        $adaPk = $hasil_cari['ketemu'];
		if($adaPk > 0){
			$sql = "INSERT INTO tbl_br_detail_estimasi SET
            id_detail	='',
            id_lapor	='".$this->db->escape_str((string) $data['id_lapor'])."',
            tgl_estimasi='".$this->db->escape_str((string) $tgl_estimasi)."',
            no_body		='".$this->db->escape_str((string) $data['body_pk'])."',
            biaya		='".$this->db->escape_str((string) $esBea)."',
            jns_pk		='".$this->db->escape_str((string) $data['jns_pk'])."',
            no_part     ='".$this->db->escape_str((string) $data['no_part'])."',
            nama_part   ='".$this->db->escape_str((string) $data['nama_part'])."',
            ket_part    ='".$this->db->escape_str((string) $data['ket_part'])."',
            jml_part  	='".$this->db->escape_str((string) $data['jml_part'])."',
            hrg_part  	='".$this->db->escape_str((string) $data['hrg_awal'])."',
            user        ='".$this->db->escape_str((string) $data['user'])."',
			status        ='Y'";
            $this->db->query($sql);
            return $this->db->affected_rows();

		} else {
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

        $sql = "INSERT INTO tbl_br_detail_estimasi SET
            id_detail	='',
            id_lapor	='".$this->db->escape_str((string) $data['id_lapor'])."',
            tgl_estimasi='".$this->db->escape_str((string) $tgl_estimasi)."',
            no_body		='".$this->db->escape_str((string) $data['body_pk'])."',
            biaya		='".$this->db->escape_str((string) $esBea)."',
            jns_pk		='".$this->db->escape_str((string) $data['jns_pk'])."',
            no_part     ='".$this->db->escape_str((string) $data['no_part'])."',
            nama_part   ='".$this->db->escape_str((string) $data['nama_part'])."',
            ket_part    ='".$this->db->escape_str((string) $data['ket_part'])."',
            jml_part  	='".$this->db->escape_str((string) $data['jml_part'])."',
            hrg_part  	='".$this->db->escape_str((string) $data['hrg_awal'])."',
            user        ='".$this->db->escape_str((string) $data['user'])."',
			status        ='Y'";
            $this->db->query($sql);
            return $this->db->affected_rows();
    }
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
		$sql = "SELECT a.*,b.keterangan as ket_pk FROM tbl_br_detail_estimasi as a
		LEFT JOIN tbl_br_kat_pk as b ON b.kode=a.jns_pk WHERE a.id_lapor = '".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function cetak_pk($id)
    {
		$sql = "SELECT * FROM tbl_br_laporan_bus AS a 
		LEFT JOIN tbl_br_pk_aktif AS b ON a.id_lapor=b.id_lapor 
		WHERE a.id_lapor ='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function select_bay()
    {
		$sql = "SELECT * FROM `tbl_br_bay`";

		$data = $this->db->query($sql);

		return $data->result();
    }
	//** end Keterangan PK **//

}
