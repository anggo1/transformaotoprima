<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_display extends CI_Model {
    function Aplikasi()
    {
        return $this->db->get('aplikasi');
    }

    function Auth($username, $password)
    {

        //menggunakan active record . untuk menghindari sql injection
        $this->db->where("username", $username);
        $this->db->where("password", $password);
        $this->db->where("is_active", 'Y');
        return $this->db->get("tbl_user");    
    }

    function check_db($username)
    {
        return $this->db->get_where('tbl_user', array('username' => $username));
    }
    public function select_antri()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date("Y-m-d");
        $sql = "SELECT `a`.*, `b`.`kategori`, `c`.`kode` FROM `tbl_br_laporan_bus` as `a` 
        LEFT JOIN `tbl_br_kategori` as `b` ON `b`.`id_kategori`=`a`.`kategori` 
        LEFT JOIN `tbl_br_ket_lapor` as `c` ON `c`.`id`=`a`.`ket_lapor` 
        WHERE `a`.`status` = 'N' ORDER BY `no_body`";

        $data = $this->db->query($sql);
        return $data->result();
        
    }
    public function select_pk()
	{
        $sql = "SELECT COUNT(b.id_lapor) AS `jml_pk`,b.pj_borong,`a`.* FROM `tbl_br_laporan_bus` AS `a`
		LEFT JOIN `tbl_br_pk_aktif`AS `b` ON `b`.`id_lapor`=`a`.`id_lapor`
		WHERE a.status !='N' AND a.status !='S'AND a.status !='K'
		GROUP BY `b`.`id_lapor`";

		//$sql = "SELECT x.total,id_pk, id_lapor, tgl_mulai, pj_borong, no_body, jns_pk FROM tbl_br_pk_aktif, 
        //(select count(id_lapor) as total FROM tbl_br_pk_aktif WHERE `status` != 'S' AND `status` != 'K') as x 
        //WHERE `status` != 'S' AND `status` != 'K'GROUP BY id_lapor";

		$data = $this->db->query($sql);

		return $data->result();
	}
    public function select_total_spk()
	{
        $sql = "SELECT COUNT(a.id_lapor) AS `total` FROM `tbl_br_laporan_bus` AS `a`
		WHERE a.status !='N' AND a.status !='S'AND a.status !='K'";
		$data = $this->db->query($sql);
		return $data->result();
	}
    public function select_total_antri()
	{
        $sql = "SELECT COUNT(a.id_lapor) AS `tAntri` FROM `tbl_br_laporan_bus` as `a` 
        LEFT JOIN `tbl_br_kategori` as `b` ON `b`.`id_kategori`=`a`.`kategori` 
        LEFT JOIN `tbl_br_ket_lapor` as `c` ON `c`.`id`=`a`.`ket_lapor` 
        WHERE `a`.`status` = 'N' ORDER BY `no_body`";
		$data = $this->db->query($sql);
		return $data->result();
	}
    public function select_total_keluar()
	{
        $this->db->select('COUNT(a.id_lapor) AS `tKeluar`');
        $this->db->from('tbl_br_laporan_bus as a');
        $this->db->join('tbl_br_kategori as c', 'c.id_kategori=a.kategori', 'left');
		$this->db->where('a.status=','S');

		$data = $this->db->get();

		return $data->result();
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
		//$sql = "SELECT * FROM tbl_br_laporan_bus AS a LEFT JOIN tbl_br_pk_aktif AS b ON a.id_lapor=b.id_lapor WHERE a.id_lapor ='{$id}'";

		//$data = $this->db->query($sql);

		//return $data->result();
        $result=$this->db->query("SELECT * FROM tbl_br_laporan_bus WHERE id_lapor ='{$id}'")->result();
        $a = array();
        $result2=$this->db->query("SELECT * FROM tbl_br_pk_aktif WHERE id_lapor = '{$id}'")->result();        
        $b = array();
        foreach($result as $a) {
            $statusnye="";
            if($a->status=='P'){
                $statusnye="Pending";
            }
            if($a->status=='Y'){
                $statusnye="Aktif";
            }
            echo'<table width="100%" align="center" >

            <tr>
            <td><strong>No SPK</strong>
            </h4></td>
            <td ><strong>: '.$a->id_lapor.'</strong></td>
            <td ><strong>No Body</strong>
            </h4></td>
            <td ><strong>: '.$a->no_body.'</strong></td>
            </tr>
            <tr>
            <td><strong>Tgl Masuk</strong>
            </h4></td>
            <td ><strong>: '.$a->tgl_masuk.'</strong></td>
            <td ><strong>No Pol</strong>
            </h4></td>
            <td ><strong>: '.$a->no_pol.'</strong></td>
            </tr>
            <tr>
            <td><strong>Status</strong></td>
            <td><strong>: '.$statusnye.'</strong></td>
            <td><strong>Keterangan</strong></td>
            <td><strong>: '.$a->ket_status.'</strong></td>
            </tr>
            
    </table>';
			}
                echo'<table width="100%" align="center" class="table table-bordered table-striped" id="list-modal" >
    
                <tr>
                <th><strong>No</strong></th>
                <th><strong>ID PK</strong></th>
                <th><strong>KODE PK</strong></th>
                <th><strong>Keterangan PK</strong></th>
                <th><strong>Pemborong</strong></th>
                <th><strong>Status</strong></th>
                <th><strong>Keterangan</strong></th>
                </tr>';
                $no=1;
                foreach($result2 as $b) 
                {
                    $ket_status="";
            if($b->status=='P'){
                $ket_status="Pending";
            }
            if($b->status=='Y'){
                $ket_status="Aktif";
            }
            if($b->status=='S'){
                $ket_status="Selesai";
            }
            echo'
                <tr>
                <td>'.$no++.'</td>
                <td>'.$b->id_pk.'</td>
                <td>'.$b->jns_pk.'</td>
                <td>'.$b->ket_pk.'</td>
                <td>'.$b->pj_borong.'</td>
                <td>'.$ket_status.'</td>
                <td>'.$b->ket_pause.'</td>
                </tr>'; }'
        </table>';
                
    }
    public function select_pk_selesai()
	{
        $this->db->select('a.*,c.kategori');
        $this->db->from('tbl_br_laporan_bus as a');
        $this->db->join('tbl_br_kategori as c', 'c.id_kategori=a.kategori', 'left');
		$this->db->where('a.status=','S');

		$data = $this->db->get();

		return $data->result();
	}
    public function select_bay1()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '1' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay2()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '2' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay3()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '3' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay4()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '4' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay5()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '5' AND `a`.`status`!='S' AND `a`.`status`!='K' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay6()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '6' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay7()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '7' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay8()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '8' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay9()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '9' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay10()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '10' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay11()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '11' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay12()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '12' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay13()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '13' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay14()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '14' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay15()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '15' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
	public function select_bay16()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '16' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay17()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '17' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay18()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '18' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay19()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '19' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay20()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '20' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_bay21()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '21' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay22()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '22' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay23()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '23' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay24()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '24' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay25()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '25' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay26()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '26' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay27()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '27' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay28()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '28' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay29()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '29' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay30()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '30' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	} 
    public function select_bay31()
	{
		$sql = "SELECT `a`.*,`b`.`tgl_mulai`,COUNT(b.id_lapor) AS jml_pk,
        COUNT(IF(`b`.`status` = 'S', 1, NULL)) 'selesai',
        COUNT(IF(`b`.`status` = 'P', 1, NULL)) 'pause',
        COUNT(IF(`b`.`status` = 'Y', 1, NULL)) 'aktif'
        FROM `tbl_br_laporan_bus` as `a`
        INNER JOIN `tbl_br_pk_aktif` as `b` ON `a`.`id_lapor`=`b`.`id_lapor`
        WHERE `a`.`no_bay` = '31' AND `a`.`status`!='S' AND `a`.`status`!='K'";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_cat()
	{
		$sql = "SELECT * FROM `tbl_br_antri_cat` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_total_antri_cat()
	{
        $sql = "SELECT COUNT(no_body) AS `total_cat` FROM `tbl_br_antri_cat` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_triming()
	{
		$sql = "SELECT * FROM `tbl_br_antri_triming` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_total_antri_triming()
	{
        $sql = "SELECT COUNT(no_body) AS `total_triming` FROM `tbl_br_antri_triming` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_elektrik()
	{
		$sql = "SELECT * FROM `tbl_br_antri_elektrik` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_total_antri_elektrik()
	{
        $sql = "SELECT COUNT(no_body) AS `total_elektrik` FROM `tbl_br_antri_elektrik` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_qc()
	{
		$sql = "SELECT * FROM `tbl_br_antri_qc` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_total_antri_qc()
	{
        $sql = "SELECT COUNT(no_body) AS `total_qc` FROM `tbl_br_antri_qc` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_jok()
	{
		$sql = "SELECT * FROM `tbl_br_antri_jok` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_total_antri_jok()
	{
        $sql = "SELECT COUNT(no_body) AS `total_jok` FROM `tbl_br_antri_jok` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_antri_ph()
	{
		$sql = "SELECT * FROM `tbl_br_antri_ph` ";
        $data = $this->db->query($sql);
        return $data->result();
	}
    public function select_total_antri_ph()
	{
        $sql = "SELECT COUNT(no_body) AS `total_ph` FROM `tbl_br_antri_ph` ";
        $data = $this->db->query($sql);
        return $data->result();
	}


}

/* End of file Mod_login.php */
