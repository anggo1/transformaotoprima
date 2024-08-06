<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_settingwh extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function select_voucher()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_voucher');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_voucher($id)
	{
		$sql = " SELECT * FROM tbl_wh_voucher WHERE id_voucher='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertVoucher($data,$date1,$date2)
	{
		$sql = "INSERT INTO tbl_wh_voucher VALUES
		('','" . $data['kode_voucher'] . "','" . $date1. "','" . $date2. "','" . $data['keterangan'] . "','N')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateVoucher($data)
	{
		$sql = "UPDATE tbl_wh_kategori SET tgl_awal='" . $date1 . "',tgl_akhir='" . $date2 . "',keterangan='" . $data['keterangan'] . "'
        WHERE id_voucher='" . $data['id_voucher'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteVoucher($id)
	{
		$sql = "DELETE FROM tbl_wh_voucher WHERE id_voucher='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Voucher //

	
	public function select_kode_po()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_kode_po');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_kode_po($id)
	{
		$sql = " SELECT * FROM tbl_wh_kode_po WHERE id_kode_po='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKode_po($data)
	{
		$sql = "INSERT INTO tbl_wh_kode_po VALUES
		('','" . $data['kode_po'] . "','" . $data['keterangan'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKode_po($data)
	{
		$sql = "UPDATE tbl_wh_kode_po SET keterangan='" . $data['keterangan'] . "'
        WHERE id_kode_po='" . $data['id_kode_po'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKode_po($id)
	{
		$sql = "DELETE FROM tbl_wh_kode_po WHERE id_kode_po='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kode Po //
	public function select_satuan()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_satuan');
		//$this->db->join('tbl_pendidikan as b','a.pendidikan=b.id_pendidikan');
		//$this->db->join('tbl_supplier as c','a.supplier=c.id_supplier');
		//$this->db->join('tbl_departement as d','a.departement=d.id_departement');
		//$this->db->where('a.nip=',$id);

		$data = $this->db->get();

		return $data->result();
	}
	public function select_id_satuan($id)
	{
		$sql = " SELECT * FROM tbl_wh_satuan WHERE id_satuan='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertSatuan($data)
	{
		$sql = "INSERT INTO tbl_wh_satuan VALUES
		('','" . $data['kode_satuan'] . "','" . $data['satuan'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateSatuan($data)
	{
		$sql = "UPDATE tbl_wh_satuan SET kode_satuan='" . $data['kode_satuan'] . "',satuan='" . $data['satuan'] . "'
        WHERE id_satuan='" . $data['id_satuan'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteSat($id)
	{
		$sql = "DELETE FROM tbl_wh_satuan WHERE id_satuan='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	//end Satuan//

	public function select_kategori()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_kategori');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_kategori($id)
	{
		$sql = " SELECT * FROM tbl_wh_kategori WHERE id_kategori='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKategori($data)
	{
		$sql = "INSERT INTO tbl_wh_kategori VALUES
		('','" . $data['kode_kategori'] . "','" . $data['kategori'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKategori($data)
	{
		$sql = "UPDATE tbl_wh_kategori SET kode_kategori='" . $data['kode_kategori'] . "',kategori='" . $data['kategori'] . "'
        WHERE id_kategori='" . $data['id_kategori'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKat($id)
	{
		$sql = "DELETE FROM tbl_wh_kategori WHERE id_kategori='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kategori //
	public function select_type()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_type_mesin');
		$data = $this->db->get();

		return $data->result();
	}
	public function select_id_type($id)
	{
		$sql = " SELECT * FROM tbl_wh_type_mesin WHERE id_type='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertType($data)
	{
		$sql = "INSERT INTO tbl_wh_type_mesin VALUES
		('','" . $data['kode_type'] . "','" . $data['type'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateType($data)
	{
		$sql = "UPDATE tbl_wh_type_mesin SET kode_type='" . $data['kode_type'] . "', type_mesin='" . $data['type'] . "' WHERE id_type='" . $data['id_type'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteTy($id)
	{
		$sql = "DELETE FROM tbl_wh_type_mesin WHERE id_type='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	//end Type//

	public function select_supplier()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_supplier');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_supplier($id)
	{
		$sql = " SELECT * FROM tbl_wh_supplier WHERE id_supplier='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertSupplier($data)
	{
	$sql = "INSERT INTO tbl_wh_supplier SET kode_sup='" . $data['kode_supplier'] . "',kode_nama='" . $data['kode_nama'] . "',nama_sup='" . $data['nama_supplier'] . "',detail='" . $data['detail'] . "',alamat='" . $data['alamat'] . "',
	no_tlp='" . $data['no_tlp'] . "',no_fax='" . $data['no_fax'] . "',tlp_person='" . $data['tlp_person'] . "'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateSupplier($data)
	{
		$sql = "UPDATE tbl_wh_supplier SET kode_nama='" . $data['kode_nama'] . "',nama_sup='" . $data['nama_supplier'] . "',detail='" . $data['detail'] . "',alamat='" . $data['alamat'] . "',
		no_tlp='" . $data['no_tlp'] . "',no_fax='" . $data['no_fax'] . "',tlp_person='" . $data['tlp_person'] . "' WHERE kode_sup='" . $data['kode_sup'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteSup($id)
	{
		$sql = "DELETE FROM tbl_wh_supplier WHERE id_supplier='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	//** end Supplier **//

	public function select_customer()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_customer');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_customer($id)
	{
		$sql = " SELECT * FROM tbl_wh_customer WHERE id_customer='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertCustomer($data)
	{
	$sql = "INSERT INTO tbl_wh_customer SET kode_cus='" . $data['kode_customer'] . "',nama_cus='" . $data['nama_customer'] . "',alamat='" . $data['alamat'] . "',kota='" . $data['kota'] . "',
	no_tlp='" . $data['no_tlp'] . "',tlp_person='" . $data['tlp_person'] . "'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateCustomer($data)
	{
		$sql = "UPDATE tbl_wh_customer SET kode_cus='" . $data['kode_customer'] . "',nama_cus='" . $data['nama_customer'] . "',alamat='" . $data['alamat'] . "',kota='" . $data['kota'] . "',
		no_tlp='" . $data['no_tlp'] . "',tlp_person='" . $data['tlp_person'] . "'
        WHERE kode_cus='" . $data['kode_customer'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteCus($id)
	{
		$sql = "DELETE FROM tbl_wh_customer WHERE id_customer='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	//** end Customer **//

	public function select_kelompok()
	{
		$this->db->select('*');
		$this->db->from('tbl_wh_kelompok');
		$data = $this->db->get();
		return $data->result();
	}
	public function select_id_kelompok($id)
	{
		$sql = " SELECT * FROM tbl_wh_kelompok WHERE id_kelompok='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKelompok($data)
	{
		$sql = "INSERT INTO tbl_wh_kelompok VALUES
		('','" . $data['kode_kelompok'] . "','" . $data['kelompok'] . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKelompok($data)
	{
		$sql = "UPDATE tbl_wh_kelompok SET kode_kelompok='" . $data['kode_kelompok'] . "', kelompok='" . $data['kelompok'] . "'
        WHERE id_kelompok='" . $data['id_kelompok'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKel($id)
	{
		$sql = "DELETE FROM tbl_wh_kelompok WHERE id_kelompok='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kelompok //
}
