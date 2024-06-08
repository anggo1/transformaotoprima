<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_saldo extends CI_Model
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
    function select_saldo($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_acc_saldo_awal');
        $this->db->where('periode',$id);

        $data = $this->db->get();

        return $data->result();
    }
    function select_saldo_global($id)
    {
        $this->db->select('SUM(debit) AS data_debit, SUM(kredit) AS data_kredit,periode,kode_akun,nama_akun');
        $this->db->from('tbl_acc_saldo_awal');
        $this->db->where('periode',$id);
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
        id_saldo    ='".$kode_saldo."',
        periode     ='".$data['thn_saldo_input']."',
        kode_akun   ='".$data['kode_akun']."',
        nama_akun   ='".$data['nama_akun']."',
        debit       ='".$esDebit."',
        kredit      ='".$esKredit."',
        keterangan  ='".$data['keterangan']."',
        tgl_insert  ='".$sekarang."',
        user        ='".$data['user']."'";

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
        no_bukti        ='".$data['no_bukti']."',
        kode_akun       ='".$data['kode_akun']."',
        nama_akun       ='".$data['nama_akun']."',
        keterangan      ='".$data['keterangan']."',
        debit           ='".$esDebit."',
        kredit          ='".$esKredit."',
        user            ='".$data['user']."',
        tgl_insert      ='".$sekarang."'
        WHERE no_jurnal='".$data['no_jurnal']."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }

    function deleteSaldo($id)
    {
        $sql = "DELETE FROM tbl_acc_saldo_awal WHERE id_saldo='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}

/* End of file Mod_pegawai.php */