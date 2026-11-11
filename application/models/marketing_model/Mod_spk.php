<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_spk extends CI_Model
{
    var $table = 'tbl_mk_spk';
	var $column_search = array('tgl_spk', 'nama_pemesan', 'type_body','type_kendaraan', 'no_rangka', 'no_mesin', 'sales', 'thn_produksi','warna');
	var $column_order = array('null', 'tgl_spk', 'nama_pemesan', 'type_body','type_kendaraan', 'no_rangka', 'no_mesin', 'sales', 'thn_produksi','warna');
	var $order = array('id_spk' => 'desc');
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->select('*', true);
		$this->db->from('tbl_mk_spk');
		$this->db->where('status !=', 'Y');
		$i = 0;

		foreach ($this->column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all()
	{
		$this->db->from('tbl_mk_chasis');
		return $this->db->count_all_results();
	}
    function select_part($sup)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok');
        $this->db->from('tbl_mk_spk as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->where('a.supplier', $sup);
        $data = $this->db->get();
        return $data->result();
    }
    function get_part($id)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.nama_sup,f.kode_cus');
        $this->db->from('tbl_mk_spk as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_customer as f', 'f.id_supplier=a.supplier', 'left');
        $this->db->where('a.id_part', $id);
        return $this->db->get('tbl_mk_spk')->row();
    }
    public function deleteDetail_spk($id)
    {
        $sql = "DELETE FROM tbl_mk_keterangan_spk WHERE id_ket_spk='" . $this->db->escape_str((string) $id) . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    function update_remark($id,$remark)
		{
			$sql_update = "UPDATE tbl_mk_keterangan_spk SET remark ='".$this->db->escape_str((string) $remark)."' WHERE id_detail ='".$this->db->escape_str((string) $id)."'"; $this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
    function insertKeterangan($id,$no_spk,$keterangan)
		{
			$sql_update = "INSERT tbl_mk_keterangan_spk SET no_urut = '".$this->db->escape_str((string) $id)."', no_spk='".$this->db->escape_str((string) $no_spk)."', keterangan ='".$this->db->escape_str((string) $keterangan)."'";
            $this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
    public function insertDetail($kodePo, $koderef, $data)
    {
        $kodenya = "";
        $koderefnya = "";
        if (empty($data['id_estimasi_penawaran'])) {
            $kodenya = $kodePo;
            $koderefnya = $koderef;
        } else {
            $kodenya = $data['id_estimasi_penawaran'];
            $koderefnya = $data['kode_ref'];
        }
        $total_harga = $data['total_harga'];
        if (!empty($data['diskon'])) {
            $total_harga = $data['total_harga'] - $data['total_diskon'];
        }
        $datenow = date("Y-m-d");
        $sql = "INSERT INTO tbl_mk_keterangan_spk SET
            id_detail       ='',
            id_estimasi_penawaran   ='" . $this->db->escape_str((string) $kodenya) . "',
            kode_po         ='" . $this->db->escape_str((string) $koderefnya) . "',
            no_part         ='" . $this->db->escape_str((string) $data['no_part']) . "',
            nama_part       ='" . $this->db->escape_str((string) $data['nama_part']) . "',
            harga_baru           ='" . $this->db->escape_str((string) $data['harga_baru']) . "',
            jumlah          ='" . $this->db->escape_str((string) $data['jumlah']) . "',
            diskon          ='" . $this->db->escape_str((string) $data['diskon']) . "',
            total_diskon    ='" . $this->db->escape_str((string) $data['total_diskon']) . "',
            total_harga     ='".$this->db->escape_str((string) $total_harga)."',
            stok_akhir     ='" . $this->db->escape_str((string) $data['stok_awal']) . "'";
        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    public function select_by_id($id)
    {
        $sql = "SELECT * FROM tbl_mk_spk 
        WHERE no_urut ='".$this->db->escape_str((string) $id)."'";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    public function select_keterangan($id)
    {
        $sql = "SELECT * FROM tbl_mk_keterangan_spk WHERE no_urut ='".$this->db->escape_str((string) $id)."' ORDER BY id_ket_spk ASC";

        $data = $this->db->query($sql);
        return $data->result();
    }
    function updatePo($a, $b, $c, $d)
    {
        $sql = "UPDATE tbl_wh_estimasi_penawaran SET
        t_ppn       ='".$this->db->escape_str((string) $a)."',
        sub_total   ='".$this->db->escape_str((string) $b)."',
        grand_total ='".$this->db->escape_str((string) $c)."'
        WHERE id_estimasi_penawaran ='" . $this->db->escape_str((string) $data['id_estimasi_penawaran']) . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    
    public function get_by_nama($link)
	{
		$this->db->select('id_submenu');
		$this->db->from('tbl_submenu');
		$this->db->where('link', $link);
		$query = $this->db->get();
		return $query->result();
	}
    function select_by_level($idlevel, $id_sub)
	{
		$this->db->select('*');
		$this->db->from('tbl_akses_submenu');
		//$this->db->join('tbl_akses_submenu','tbl_akses_submenu.id_submenu=tbl_akses_menu.id_menu','inner');
		$this->db->where('tbl_akses_submenu.id_level=', $idlevel);
		$this->db->where('tbl_akses_submenu.id_submenu=', $id_sub);
		$data = $this->db->get();
		return $data->result();
	}
    public function select_chasis()
	{
		$this->db->select('*');
		$this->db->from('tbl_mk_chasis');
		$this->db->where('jumlah >0');
		$data = $this->db->get();
		return $data->result();
	}
    public function select_customer()
	{
		$this->db->select('*');
		$this->db->from('tbl_customer');
		$data = $this->db->get();
		return $data->result();
	}
}