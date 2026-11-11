<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_dashboard extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function total_bus() {
		// COUNT(*) di database (sebelumnya seluruh baris diambil hanya untuk dihitung)
		return $this->db->table_exists('tbl_wh_body') ? $this->db->count_all('tbl_wh_body') : 0;
	}
    function total_antri()
	{		
		$data = $this->db->get('tbl_br_laporan_bus');

		return $data->num_rows();
	}
    function total_spk()
	{		
		$sql = "SELECT COUNT(tbl_br_pk_aktif.id_lapor) AS `jml_pk`,`tbl_br_laporan_bus`.* FROM `tbl_br_laporan_bus`
		LEFT JOIN `tbl_br_pk_aktif` ON `tbl_br_pk_aktif`.`id_lapor`=`tbl_br_laporan_bus`.`id_lapor`
		WHERE tbl_br_laporan_bus.status !='N' AND tbl_br_laporan_bus.status !='S'AND tbl_br_laporan_bus.status !='K'
		GROUP BY `tbl_br_pk_aktif`.`id_lapor`";
        $data = $this->db->query($sql);
		return $data->num_rows();
	}
    function total_pk()
	{		
		$this->db->select('*');
		$this->db->from('tbl_br_pk_aktif');
		$this->db->where('status!=','S');

		$data = $this->db->get();
		return $data->num_rows();
	}
    function total_selesai()
	{		
		$sql = "SELECT COUNT(tbl_br_pk_aktif.id_lapor) AS `jml_pk`,`tbl_br_laporan_bus`.* FROM `tbl_br_laporan_bus`
		LEFT JOIN `tbl_br_pk_aktif` ON `tbl_br_pk_aktif`.`id_lapor`=`tbl_br_laporan_bus`.`id_lapor`
		WHERE tbl_br_laporan_bus.status ='S'
		GROUP BY `tbl_br_pk_aktif`.`id_lapor`";

		$data = $this->db->query($sql);
		return $data->num_rows();
	}
    public function total_barang() {
		return $this->db->count_all('tbl_wh_barang');
	}

	/* ------------------------------------------------------------------
	 * Ringkasan dashboard v2
	 * ---------------------------------------------------------------- */
	/** nama lokasi sesi -> akhiran kolom stok (cbt/jkt/sby) */
	public function kode_lokasi($lokasi)
	{
		$l = strtolower($lokasi);
		if (strpos($l, 'jak') !== false) return 'jkt';
		if (strpos($l, 'sura') !== false || strpos($l, 'sby') !== false) return 'sby';
		return 'cbt';
	}

	public function ringkasan_gudang($lok)
	{
		$lok = in_array($lok, array('cbt', 'jkt', 'sby'), true) ? $lok : 'cbt';
		$stok = "stok_$lok"; $hrg = "hrg_net_$lok";
		$r = $this->db->select("COUNT(*) AS item, SUM(CASE WHEN $stok > 0 THEN 1 ELSE 0 END) AS ada, SUM($stok * $hrg) AS nilai", false)
			->get('tbl_wh_barang')->row();
		$min = $this->db->select('COUNT(*) AS n', false)->from('tbl_wh_barang')
			->where("stok_min <> ''", null, false)->where("stok_min <> '0'", null, false)
			->where("$stok < (stok_min + 0)", null, false)->get()->row();
		$po = $this->db->table_exists('tbl_wh_po') ? $this->db->where('status_po', 'N')->count_all_results('tbl_wh_po') : 0;
		$bulan = date('Y-m-01');
		$kel = $this->db->table_exists('tbl_wh_detail_part_keluar')
			? $this->db->select('COUNT(DISTINCT id_keluar) AS dok, SUM(hrg_part * jumlah) AS nilai', false)
				->where('tgl_keluar >=', $bulan)->get('tbl_wh_detail_part_keluar')->row()
			: (object) array('dok' => 0, 'nilai' => 0);
		return (object) array('item' => (int) $r->item, 'ada' => (int) $r->ada, 'nilai' => (float) $r->nilai,
			'dibawah_min' => (int) $min->n, 'po_terbuka' => (int) $po, 'keluar_dok' => (int) $kel->dok, 'keluar_nilai' => (float) $kel->nilai);
	}

	/** nilai barang keluar per divisi: bulan lalu vs bulan ini */
	public function keluar_per_divisi($dari, $sampai)
	{
		if ( ! $this->db->table_exists('tbl_wh_detail_part_keluar')) return array();
		$awalIni = date('Y-m-01');
		$rows = $this->db->select("COALESCE(NULLIF(nama_divisi,''),'Lainnya') AS divisi, SUM(CASE WHEN tgl_keluar >= '$awalIni' THEN hrg_part * jumlah ELSE 0 END) AS ini, SUM(CASE WHEN tgl_keluar < '$awalIni' THEN hrg_part * jumlah ELSE 0 END) AS lalu", false)
			->where('tgl_keluar >=', $dari)->where('tgl_keluar <=', $sampai)
			->group_by("COALESCE(NULLIF(nama_divisi,''),'Lainnya')", false)->get('tbl_wh_detail_part_keluar')->result();
		usort($rows, function ($a, $b) { return ($b->ini + $b->lalu) <=> ($a->ini + $a->lalu); });
		return array_slice($rows, 0, 8);
	}

	/** tren nilai barang keluar n bulan terakhir */
	public function tren_keluar($n = 6)
	{
		$out = array();
		if ( ! $this->db->table_exists('tbl_wh_detail_part_keluar')) return $out;
		$awal = date('Y-m-01', strtotime('-' . ($n - 1) . ' month', strtotime(date('Y-m-01'))));
		$map = array();
		foreach ($this->db->select('SUBSTR(tgl_keluar,1,7) AS p, SUM(hrg_part * jumlah) AS v', false)
			->where('tgl_keluar >=', $awal)->group_by('SUBSTR(tgl_keluar,1,7)', false)->get('tbl_wh_detail_part_keluar')->result() as $r) $map[$r->p] = (float) $r->v;
		for ($i = $n - 1; $i >= 0; $i--) {
			$p = date('Y-m', strtotime('-' . $i . ' month', strtotime(date('Y-m-01'))));
			$out[$p] = isset($map[$p]) ? $map[$p] : 0;
		}
		return $out;
	}

	public function ringkasan_keuangan()
	{
		$this->load->library('akuntansi');
		$this->load->model('accounting/Mod_lapkeu');
		$this->load->model('accounting/Mod_acctrx');
		$kas = 0;
		foreach ($this->Mod_acctrx->saldo_kas() as $s) $kas += $s->saldo;
		$ar = $this->db->table_exists('tbl_acc_faktur') ? $this->Mod_acctrx->ringkasan_faktur('AR') : (object) array('sisa' => 0, 'sisa_lewat' => 0, 'n' => 0);
		$ap = $this->db->table_exists('tbl_acc_faktur') ? $this->Mod_acctrx->ringkasan_faktur('AP') : (object) array('sisa' => 0, 'sisa_lewat' => 0, 'n' => 0);
		$tren = array();
		for ($i = 5; $i >= 0; $i--) {
			$a = date('Y-m-01', strtotime('-' . $i . ' month', strtotime(date('Y-m-01'))));
			$b = date('Y-m-t', strtotime($a));
			$lr = $this->Mod_lapkeu->laba_rugi(array(array('label' => '', 'dari' => $a, 'sampai' => $b)));
			$pend = $lr['seksi']['PENDAPATAN']->total[0] + $lr['seksi']['PENDAPATAN_LAIN']->total[0];
			$beban = $lr['seksi']['HPP']->total[0] + $lr['seksi']['BEBAN']->total[0] + $lr['seksi']['BEBAN_LAIN']->total[0] + $lr['seksi']['PAJAK_PENGHASILAN']->total[0];
			$tren[substr($a, 0, 7)] = array('pendapatan' => $pend, 'beban' => $beban, 'laba' => $pend - $beban);
		}
		$ini = end($tren);
		return (object) array('kas' => $kas, 'piutang' => $ar, 'hutang' => $ap, 'laba_bulan_ini' => $ini['laba'], 'tren' => $tren);
	}



    public function select_pk()
	{
		
		//$sql = "SELECT COUNT(tbl_br_pk_aktif.id_lapor) AS `jml_pk`,`tbl_br_bay`.`id_bay` AS `no_bay`,`tbl_br_laporan_bus`.* FROM `tbl_br_laporan_bus`
		//LEFT JOIN `tbl_br_pk_aktif` ON `tbl_br_pk_aktif`.`id_lapor`=`tbl_br_laporan_bus`.`id_lapor`
		//LEFT JOIN `tbl_br_bay` ON `tbl_br_laporan_bus`.`no_body`=`tbl_br_bay`.`keterangan`
		//GROUP BY `tbl_br_pk_aktif`.`id_lapor`";

		$data = $this->db->query($sql);

		return $data->result();
	}

   function get_all_bus(){
		$hsl=$this->db->query("select * from tbl_wh_body");
		return $hsl;
	}

	public function grafikAgen(){
        $bln1 = date("m");
        $bln2 = date("m")-1;
        $thn = date("Y");
        $sql = "SELECT  ket_pk as asalnye,COUNT(tujuan)AS jml FROM tbl_wh_part_keluar WHERE MONTH (tgl_keluar)='".$this->db->escape_str((string) $bln2)."' AND YEAR (tgl_keluar)='".$this->db->escape_str((string) $thn)."' GROUP BY ket_pk ";
		$data = $this->db->query($sql);

		return $data->result();
    }
	public function grafikBarang(){
        $bln1 = date("m");
        $bln2 = date("m");
        $thn = date("Y");
        $sql = "SELECT  ket_pk as asalnye,COUNT(tujuan)AS jml, ket_pk as barang  FROM tbl_wh_part_keluar WHERE MONTH (tgl_keluar)='".$this->db->escape_str((string) $bln2)."' AND YEAR (tgl_keluar)='".$this->db->escape_str((string) $thn)."' GROUP BY ket_pk ";
		$data = $this->db->query($sql);

		return $data->result();
    }
    public function grafikBarang2(){
        $bln1 = date("m");
        $bln2 = date("m")-1;
        $thn = date("Y");
        $sql = "SELECT  COUNT(detail_pengiriman.id_satuan)AS jml, data_satuan.nama_satuan as barang FROM detail_pengiriman 
        LEFT JOIN data_satuan ON data_satuan.id_satuan=detail_pengiriman.id_satuan
        WHERE MONTH (tgl_buat)='".$this->db->escape_str((string) $bln2)."' AND YEAR (tgl_buat)='".$this->db->escape_str((string) $thn)."' GROUP BY detail_pengiriman.id_satuan";
		  
		$data = $this->db->query($sql);

		return $data->result();
    }


	function get_jabatan_by_id($jabatan){
		$hsl=$this->db->query("select * from tbl_jabatan where id_jabatan='".$this->db->escape_str((string) $jabatan)."'");
		return $hsl;
	}
	
	public function select_jabatan() {
		$sql = " SELECT COUNT(*) TotalCount, 
		tbl_jabatan.id_jabatan,tbl_jabatan.jabatan 
		FROM tbl_jabatan 
		INNER JOIN tbl_pegawai ON tbl_pegawai.jabatan = tbl_jabatan.id_jabatan 
		GROUP BY  tbl_jabatan.jabatan;";

		$data = $this->db->query($sql);

		return $data->result();
	}
    public function select_id_jabatan($id) {
		$sql = " SELECT * FROM tbl_jabatan WHERE id_jabatan='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
    public function insertJabatan($data) {
		$sql = "INSERT INTO tbl_jabatan VALUES
		('','".$this->db->escape_str((string) $data['jabatan'])."')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
    public function updateJabatan($data) {
		$sql = "UPDATE tbl_jabatan SET jabatan='" .$this->db->escape_str((string) $data['jabatan']) ."'
        WHERE id_jabatan='" .$this->db->escape_str((string) $data['id_jabatan']) ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
    public function select_pendidikan() {
		$sql = " SELECT COUNT(*) TotalCount, 
		tbl_pendidikan.id_pendidikan,tbl_pendidikan.pendidikan 
		FROM tbl_pendidikan 
		INNER JOIN tbl_pegawai ON tbl_pegawai.pendidikan = tbl_pendidikan.id_pendidikan 
		GROUP BY tbl_pendidikan.id_pendidikan, tbl_pendidikan.pendidikan;";

		$data = $this->db->query($sql);

		return $data->result();
	}
     public function select_id_departement($id) {
		$sql = " SELECT * FROM tbl_departement WHERE id_departement='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
     public function insertDepartement($data) {
		$sql = "INSERT INTO tbl_departement VALUES
		('','".$this->db->escape_str((string) $data['departement'])."')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
    public function updateDepartement($data) {
		$sql = "UPDATE tbl_departement SET departement='" .$this->db->escape_str((string) $data['departement']) ."'
        WHERE id_departement='" .$this->db->escape_str((string) $data['id_departement']) ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function select_departement() {
		$sql = " SELECT COUNT(*) TotalCount, 
		tbl_departement.id_departement,tbl_departement.departement 
		FROM tbl_departement 
		INNER JOIN tbl_pegawai ON tbl_pegawai.departement = tbl_departement.id_departement 
		GROUP BY tbl_departement.id_departement, tbl_departement.departement;";
		$data = $this->db->query($sql);

		return $data->result();
	}

    public function get_by_nama($link)
    {
		$this->db->select('id_submenu');
        $this->db->from('tbl_submenu');
        $this->db->where('link',$link);
        $query = $this->db->get();
        return $query->row();
    }
    function getAll()
    {
        $this->db->select('tbl_pegawai');
        //$this->db->join('tbl_menu b','a.id_menu=b.id_menu');
       return $this->db->get('tbl_pegawai a');
    }
	function select_by_level($idlevel,$get_id) {
		$this->db->select('*');
        $this->db->from('tbl_akses_submenu');
        //$this->db->join('tbl_akses_submenu','tbl_akses_submenu.id_submenu=tbl_akses_menu.id_menu','inner');
        $this->db->where('tbl_akses_submenu.id_level=1');
        $this->db->where('tbl_akses_submenu.id_submenu=42');

		$data = $this->db->get();

		return $data->result();
	}
    
    function view_pegawai($id)
    {	
    	$this->db->select('a.*,b.pendidikan,c.jabatan,d.departement');
        $this->db->from('tbl_pegawai as a');
        $this->db->join('tbl_pendidikan as b','a.pendidikan=b.id_pendidikan');
        $this->db->join('tbl_jabatan as c','a.jabatan=c.id_jabatan');
        $this->db->join('tbl_departement as d','a.departement=d.id_departement');
        $this->db->where('a.nip=',$id);

		$data = $this->db->get();

		return $data->result();
    }

    function get_pegawai($id)
    {   
        $this->db->where('nip',$id);
        return $this->db->get('tbl_pegawai')->row();
    }

    function edit_submenu($id)
    {	
    	$this->db->where('nip',$id);
    	return $this->db->get('tbl_pegawai');
    }

    function insertsubmenu($tabel, $data)
    {
        $insert = $this->db->insert($tabel, $data);
        return $insert;
    }

    function insert_akses_submenu($tbl_akses_submenu, $data)
    {
        $insert = $this->db->insert($tbl_akses_submenu, $data);
        return $insert;
    }

    function updatepegawai($nip, $data)
    {
        $this->db->where('nip', $nip);
        $this->db->update('tbl_pegawai', $data);
    }
    function getImage($nip)
    {
        $this->db->select('image');
        $this->db->from('tbl_pegawai');
        $this->db->where('nip', $nip);
        return $this->db->get();
    }
    function deletepegawai($id, $table)
    {
        $this->db->where('nip', $id);
        $this->db->delete($table);
    }

}

/* End of file Mod_pegawai.php */
