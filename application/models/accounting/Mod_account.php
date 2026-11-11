<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_account extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/** kolom tambahan (hasil migrasi v2) tersedia? */
	private function v2()
	{
		static $ada = null;
		if ($ada === null) $ada = $this->db->field_exists('kategori', 'tbl_acc_akun');
		return $ada;
	}

	public function select_acc()
	{
		return $this->db->select('a.*, j.jenis_beban AS nama_jenis, k.kelompok AS nama_kelompok, t.type AS nama_type', false)
			->from('tbl_acc_akun a')
			->join('tbl_acc_jenis j', 'j.id_jenis = a.jenis_beban', 'left')
			->join('tbl_acc_kelompok k', 'k.id_kelompok = a.kelompok', 'left')
			->join('tbl_acc_type t', 't.id_type = a.type_akun', 'left')
			->order_by('a.kode_akun', 'ASC')
			->get()->result();
	}

	private function payload($data)
	{
		$row = array(
			'nama_akun'   => trim((string) (isset($data['nama_akun']) ? $data['nama_akun'] : '')),
			'kelompok'    => (string) (isset($data['kelompok']) ? $data['kelompok'] : ''),
			'type_akun'   => (string) (isset($data['type']) ? $data['type'] : ''),
			'jenis_beban' => (string) (isset($data['jns_beban']) ? $data['jns_beban'] : ''),
		);
		if ($this->v2()) {
			$this->load->library('akuntansi');
			$kat = isset($data['kategori']) && isset(Akuntansi::$KATEGORI[$data['kategori']]) ? $data['kategori'] : $this->akuntansi->tebak_kategori(isset($data['kode_akun']) ? $data['kode_akun'] : '');
			$row['kategori']     = $kat;
			$row['parent_kode']  = trim((string) (isset($data['parent_kode']) ? $data['parent_kode'] : ''));
			$row['saldo_normal'] = Akuntansi::saldo_normal_kategori($kat);
			$row['is_active']    = (isset($data['is_active']) && $data['is_active'] === 'N') ? 'N' : 'Y';
			$row['keterangan']   = trim((string) (isset($data['keterangan']) ? $data['keterangan'] : ''));
			if ($this->db->field_exists('fiskal', 'tbl_acc_akun')) {
				$row['fiskal'] = isset($data['fiskal']) && isset(Akuntansi::$FISKAL[$data['fiskal']]) ? $data['fiskal'] : '';
			}
		}
		return $row;
	}

	public function kode_ada($kode, $kecualiId = 0)
	{
		$this->db->where('kode_akun', $kode);
		if ($kecualiId) $this->db->where('id !=', (int) $kecualiId);
		return $this->db->count_all_results('tbl_acc_akun') > 0;
	}

	public function insertAkun($data)
	{
		$row = $this->payload($data);
		$row['kode_akun'] = trim((string) $data['kode_akun']);
		$this->db->insert('tbl_acc_akun', $row);
		return $this->db->affected_rows();
	}

	function updateAkun($data)
	{
		$row = $this->payload($data);
		$id = isset($data['id']) ? (int) $data['id'] : 0;
		if ($id) $this->db->where('id', $id);
		else $this->db->where('kode_akun', (string) $data['kode_akun']);
		$this->db->update('tbl_acc_akun', $row);
		// nama akun di jurnal ikut diperbarui agar laporan konsisten
		if ($this->db->affected_rows() >= 0 && $row['nama_akun'] !== '') {
			$this->db->where('kode_akun', (string) $data['kode_akun'])->update('tbl_acc_jurnal_umum', array('nama_akun' => $row['nama_akun']));
		}
		return 1;
	}

	public function select_id_akun($id)
	{
		return $this->db->get_where('tbl_acc_akun', array('id' => (int) $id))->result();
	}

	public function select_kelompok() { return $this->db->get('tbl_acc_kelompok')->result(); }
	public function select_type()     { return $this->db->get('tbl_acc_type')->result(); }
	public function select_jenis()    { return $this->db->get('tbl_acc_jenis')->result(); }

	/** jumlah pemakaian akun di transaksi */
	public function dipakai($kode)
	{
		$n = $this->db->where('kode_akun', $kode)->count_all_results('tbl_acc_jurnal_umum');
		$n += $this->db->where('kode_akun', $kode)->count_all_results('tbl_acc_saldo_awal');
		return $n;
	}

	function deleteAkun($id)
	{
		$this->db->where('id', (int) $id)->delete('tbl_acc_akun');
		return $this->db->affected_rows();
	}
}
