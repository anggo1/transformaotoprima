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
		$sql = " SELECT * FROM tbl_wh_voucher WHERE id_voucher='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertVoucher($data,$date1,$date2)
	{
		$sql = "INSERT INTO tbl_wh_voucher VALUES
		('','" . $this->db->escape_str((string) $data['kode_voucher']) . "','" . $this->db->escape_str((string) $date1). "','" . $this->db->escape_str((string) $date2). "','" . $this->db->escape_str((string) $data['keterangan']) . "','N')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateVoucher($data)
	{
		$sql = "UPDATE tbl_wh_kategori SET tgl_awal='" . $this->db->escape_str((string) $date1) . "',tgl_akhir='" . $this->db->escape_str((string) $date2) . "',keterangan='" . $this->db->escape_str((string) $data['keterangan']) . "'
        WHERE id_voucher='" . $this->db->escape_str((string) $data['id_voucher']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteVoucher($id)
	{
		$sql = "DELETE FROM tbl_wh_voucher WHERE id_voucher='".$this->db->escape_str((string) $id)."'";

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
		$sql = " SELECT * FROM tbl_wh_kode_po WHERE id_kode_po='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKode_po($data)
	{
		$sql = "INSERT INTO tbl_wh_kode_po VALUES
		('','" . $this->db->escape_str((string) $data['kode_po']) . "','" . $this->db->escape_str((string) $data['keterangan']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKode_po($data)
	{
		$sql = "UPDATE tbl_wh_kode_po SET keterangan='" . $this->db->escape_str((string) $data['keterangan']) . "'
        WHERE id_kode_po='" . $this->db->escape_str((string) $data['id_kode_po']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKode_po($id)
	{
		$sql = "DELETE FROM tbl_wh_kode_po WHERE id_kode_po='".$this->db->escape_str((string) $id)."'";

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
		$sql = " SELECT * FROM tbl_wh_satuan WHERE id_satuan='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertSatuan($data)
	{
		$sql = "INSERT INTO tbl_wh_satuan VALUES
		('','" . $this->db->escape_str((string) $data['kode_satuan']) . "','" . $this->db->escape_str((string) $data['satuan']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateSatuan($data)
	{
		$sql = "UPDATE tbl_wh_satuan SET kode_satuan='" . $this->db->escape_str((string) $data['kode_satuan']) . "',satuan='" . $this->db->escape_str((string) $data['satuan']) . "'
        WHERE id_satuan='" . $this->db->escape_str((string) $data['id_satuan']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteSat($id)
	{
		$sql = "DELETE FROM tbl_wh_satuan WHERE id_satuan='".$this->db->escape_str((string) $id)."'";

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
		$sql = " SELECT * FROM tbl_wh_kategori WHERE id_kategori='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKategori($data)
	{
		$sql = "INSERT INTO tbl_wh_kategori VALUES
		('','" . $this->db->escape_str((string) $data['kode_kategori']) . "','" . $this->db->escape_str((string) $data['kategori']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKategori($data)
	{
		$sql = "UPDATE tbl_wh_kategori SET kode_kategori='" . $this->db->escape_str((string) $data['kode_kategori']) . "',kategori='" . $this->db->escape_str((string) $data['kategori']) . "'
        WHERE id_kategori='" . $this->db->escape_str((string) $data['id_kategori']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKat($id)
	{
		$sql = "DELETE FROM tbl_wh_kategori WHERE id_kategori='".$this->db->escape_str((string) $id)."'";

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
		$sql = " SELECT * FROM tbl_wh_type_mesin WHERE id_type='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertType($data)
	{
		$sql = "INSERT INTO tbl_wh_type_mesin VALUES
		('','" . $this->db->escape_str((string) $data['kode_type']) . "','" . $this->db->escape_str((string) $data['type']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateType($data)
	{
		$sql = "UPDATE tbl_wh_type_mesin SET kode_type='" . $this->db->escape_str((string) $data['kode_type']) . "', type_mesin='" . $this->db->escape_str((string) $data['type']) . "' WHERE id_type='" . $this->db->escape_str((string) $data['id_type']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteTy($id)
	{
		$sql = "DELETE FROM tbl_wh_type_mesin WHERE id_type='".$this->db->escape_str((string) $id)."'";

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
		$sql = " SELECT * FROM tbl_wh_supplier WHERE id_supplier='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertSupplier($data)
	{
	$sql = "INSERT INTO tbl_wh_supplier SET kode_sup='" . $this->db->escape_str((string) $data['kode_supplier']) . "',kode_nama='" . $this->db->escape_str((string) $data['kode_nama']) . "',nama_sup='" . $this->db->escape_str((string) $data['nama_supplier']) . "',detail='" . $this->db->escape_str((string) $data['detail']) . "',alamat='" . $this->db->escape_str((string) $data['alamat']) . "',
	no_tlp='" . $this->db->escape_str((string) $data['no_tlp']) . "',no_fax='" . $this->db->escape_str((string) $data['no_fax']) . "',tlp_person='" . $this->db->escape_str((string) $data['tlp_person']) . "'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateSupplier($data)
	{
		$sql = "UPDATE tbl_wh_supplier SET kode_nama='" . $this->db->escape_str((string) $data['kode_nama']) . "',nama_sup='" . $this->db->escape_str((string) $data['nama_supplier']) . "',detail='" . $this->db->escape_str((string) $data['detail']) . "',alamat='" . $this->db->escape_str((string) $data['alamat']) . "',
		no_tlp='" . $this->db->escape_str((string) $data['no_tlp']) . "',no_fax='" . $this->db->escape_str((string) $data['no_fax']) . "',tlp_person='" . $this->db->escape_str((string) $data['tlp_person']) . "' WHERE kode_sup='" . $this->db->escape_str((string) $data['kode_sup']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteSup($id)
	{
		$sql = "DELETE FROM tbl_wh_supplier WHERE id_supplier='".$this->db->escape_str((string) $id)."'";

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
		$sql = " SELECT * FROM tbl_wh_customer WHERE id_customer='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertCustomer($data)
	{
	$sql = "INSERT INTO tbl_wh_customer SET kode_cus='" . $this->db->escape_str((string) $data['kode_customer']) . "',nama_cus='" . $this->db->escape_str((string) $data['nama_customer']) . "',alamat='" . $this->db->escape_str((string) $data['alamat']) . "',kota='" . $this->db->escape_str((string) $data['kota']) . "',
	no_tlp='" . $this->db->escape_str((string) $data['no_tlp']) . "',tlp_person='" . $this->db->escape_str((string) $data['tlp_person']) . "'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateCustomer($data)
	{
		$sql = "UPDATE tbl_wh_customer SET kode_cus='" . $this->db->escape_str((string) $data['kode_customer']) . "',nama_cus='" . $this->db->escape_str((string) $data['nama_customer']) . "',alamat='" . $this->db->escape_str((string) $data['alamat']) . "',kota='" . $this->db->escape_str((string) $data['kota']) . "',
		no_tlp='" . $this->db->escape_str((string) $data['no_tlp']) . "',tlp_person='" . $this->db->escape_str((string) $data['tlp_person']) . "'
        WHERE kode_cus='" . $this->db->escape_str((string) $data['kode_customer']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteCus($id)
	{
		$sql = "DELETE FROM tbl_wh_customer WHERE id_customer='".$this->db->escape_str((string) $id)."'";

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
		$sql = " SELECT * FROM tbl_wh_kelompok WHERE id_kelompok='".$this->db->escape_str((string) $id)."'";

		$data = $this->db->query($sql);

		return $data->result();
	}
	public function insertKelompok($data)
	{
		$sql = "INSERT INTO tbl_wh_kelompok VALUES
		('','" . $this->db->escape_str((string) $data['kode_kelompok']) . "','" . $this->db->escape_str((string) $data['kelompok']) . "')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function updateKelompok($data)
	{
		$sql = "UPDATE tbl_wh_kelompok SET kode_kelompok='" . $this->db->escape_str((string) $data['kode_kelompok']) . "', kelompok='" . $this->db->escape_str((string) $data['kelompok']) . "'
        WHERE id_kelompok='" . $this->db->escape_str((string) $data['id_kelompok']) . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function deleteKel($id)
	{
		$sql = "DELETE FROM tbl_wh_kelompok WHERE id_kelompok='".$this->db->escape_str((string) $id)."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	// End Kelompok //
}
