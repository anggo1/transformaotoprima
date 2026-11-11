<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_bukubesar extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_nama($link)
    {
        $this->db->select('id_submenu');
        $this->db->from('tbl_submenu');
        $this->db->where('link', $link);
        $query = $this->db->get();
        return $query->result();
    }
    function getAll()
    {
        $this->db->select('tbl_acc_jurnal_umum');
        //$this->db->join('tbl_menu b','a.id_menu=b.id_menu');
        return $this->db->get('tbl_acc_jurnal_umum');
    }
    function select_by_id_jurnal($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_acc_jurnal_umum');
        $this->db->where('tbl_acc_jurnal_umum.no_jurnal=',$id);
        $data = $this->db->get();
        return $data->result();
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
    public function select_akun()
    {
        $sql = " SELECT * FROM tbl_acc_akun";

        $data = $this->db->query($sql);

        return $data->result();
    }
    function select_buku($kode_akun,$tgl_awal,$tgl_akhir)
    {
        $tgl1   = explode('-', $tgl_awal);
        $awal = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
        $awal1 = $tgl1[2];
        $tgl2   = explode('-', $tgl_akhir);
        $akhir = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
        $akhir2 = $tgl2[2];
        $sql = "SELECT a.*,
        (SELECT SUM(c.debit) FROM tbl_acc_saldo_awal AS c WHERE c.kode_akun = '".$this->db->escape_str((string) $kode_akun)."' AND c.periode BETWEEN '".$this->db->escape_str((string) $awal1)."' AND '".$this->db->escape_str((string) $akhir2)."')AS data_debit,
        (SELECT SUM(c.kredit) FROM tbl_acc_saldo_awal AS c WHERE c.kode_akun = '".$this->db->escape_str((string) $kode_akun)."' AND c.periode BETWEEN '".$this->db->escape_str((string) $awal1)."' AND '".$this->db->escape_str((string) $akhir2)."') AS data_kredit
        FROM tbl_acc_jurnal_umum AS a 
        WHERE a.status ='Y' AND a.tgl_jurnal BETWEEN '".$this->db->escape_str((string) $awal)."' AND '".$this->db->escape_str((string) $akhir)."' AND a.kode_akun = '".$this->db->escape_str((string) $kode_akun)."' ";

		$data = $this->db->query($sql);

		return $data->result();
    }
    function select_buku_tanggal($tgl_awal,$tgl_akhir)
    {
        $tgl1   = explode('-', $tgl_awal);
        $awal = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
        $awal1 = $tgl1[2];
        $tgl2   = explode('-', $tgl_akhir);
        $akhir = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
        $akhir2 = $tgl2[2];
        $sql = "SELECT * FROM tbl_acc_jurnal_umum
        WHERE tgl_jurnal BETWEEN '".$this->db->escape_str((string) $awal)."' AND '".$this->db->escape_str((string) $akhir)."' AND status ='Y' ";

		$data = $this->db->query($sql);

		return $data->result();
    }
    function select_saldo_global($kode_akun,$tgl_awal,$tgl_akhir)
    {
        $tgl1   = explode('-', $tgl_awal);
        $awal = $tgl1[2];
        $tgl2   = explode('-', $tgl_akhir);
        $akhir = $tgl2[2];
        $this->db->select('SUM(debit) AS data_debit, SUM(kredit) AS data_kredit,periode,kode_akun,nama_akun');
        $this->db->from('tbl_acc_saldo_awal');
        $this->db->where('kode_akun',$kode_akun);
        $this->db->where('periode BETWEEN "'.date($awal).'"AND"'.date($akhir).'"');
        $this->db->group_by('kode_akun');

        $data = $this->db->get();

        return $data->result();
    }
    function cetak_sparepart()
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.kode_sup,f.nama_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c','c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d','d.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e','e.id_kelompok=a.kelompok','left');
        $this->db->join('tbl_wh_supplier as f','f.id_supplier=a.supplier','left');

        $data = $this->db->get();

        return $data->result();
    }
    function insertSaldo($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $sekarang= date("Y-m-d h:i:s");
		$beaDebit=$data['debit'];
		$esDebit =str_replace(",","", $beaDebit);
		$beakredit=$data['kredit'];
		$esKredit =str_replace(",","", $beakredit);
        
        $kode = $data['kode_akun'];
        $ci_kons = get_instance();
        $query = "SELECT MAX(CAST(SUBSTRING(id_saldo, 4, length(id_saldo) - 1) AS UNSIGNED)) AS maxKode FROM tbl_acc_saldo_awal WHERE id_saldo LIKE '%$kode%'";
        $hasil = $ci_kons->db->query($query)->row_array();
        $noOrder = $hasil['maxKode'];
        //$noUrut = (int)substr($noOrder, 4, 3);
        $noOrder++;
        $kode_saldo  = $kode.sprintf("%05s", $noOrder);

        $sql = "INSERT INTO tbl_acc_saldo_awal SET
        id_saldo    ='".$this->db->escape_str((string) $kode_saldo)."',
        periode     ='".$this->db->escape_str((string) $data['thn_saldo_input'])."',
        kode_akun   ='".$this->db->escape_str((string) $data['kode_akun'])."',
        nama_akun   ='".$this->db->escape_str((string) $data['nama_akun'])."',
        debit       ='".$this->db->escape_str((string) $esDebit)."',
        kredit      ='".$this->db->escape_str((string) $esKredit)."',
        keterangan  ='".$this->db->escape_str((string) $data['keterangan'])."',
        tgl_insert  ='".$this->db->escape_str((string) $sekarang)."',
        user        ='".$this->db->escape_str((string) $data['user'])."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function updateJurnal($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $sekarang= date("Y-m-d h:i:s");
		$beaDebit=$data['debit'];
		$esDebit =str_replace(",","", $beaDebit);
		$beakredit=$data['kredit'];
		$esKredit =str_replace(",","", $beakredit);
        $sql = "UPDATE tbl_acc_jurnal_umum SET
        no_bukti        ='".$this->db->escape_str((string) $data['no_bukti'])."',
        kode_akun       ='".$this->db->escape_str((string) $data['kode_akun'])."',
        nama_akun       ='".$this->db->escape_str((string) $data['nama_akun'])."',
        keterangan      ='".$this->db->escape_str((string) $data['keterangan'])."',
        debit           ='".$this->db->escape_str((string) $esDebit)."',
        kredit          ='".$this->db->escape_str((string) $esKredit)."',
        user            ='".$this->db->escape_str((string) $data['user'])."',
        tgl_insert      ='".$this->db->escape_str((string) $sekarang)."'
        WHERE no_jurnal='".$this->db->escape_str((string) $data['no_jurnal'])."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }

    function deleteSaldo($id)
    {
        $sql = "DELETE FROM tbl_acc_saldo_awal WHERE id_saldo='".$this->db->escape_str((string) $id)."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}

/* End of file Mod_pegawai.php */