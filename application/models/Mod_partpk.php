<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_partpk extends CI_Model
{ 
    var $table = 'tbl_wh_barang';
    var $column_search = array('a.no_part', 'a.nama_part', 'a.stok', 'a.lokasi', 'c.kode_satuan', 'a.type', 'a.kategori', 'a.kelompok');
    var $column_order = array('a.no_part', 'a.nama_part', 'a.stok', 'a.lokasi', 'c.kode_satuan', 'a.type', 'a.kategori', 'a.kelompok');
    var $order = array('id_barang' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('a.*,b.kategori,c.kode_satuan,c.satuan,d.type_mesin,e.kelompok');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
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
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {

        $this->db->from('tbl_wh_barang as a');
        //$this->db->join('tbl_menu as b','a.id_menu=b.id_menu');
        return $this->db->count_all_results();
    }
    
    function get_part($id)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.nama_sup,f.kode_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.id_supplier=a.supplier', 'left');
        $this->db->where('a.no_part', $id);
        return $this->db->get('tbl_wh_barang')->row();
    }
	public function select_pk()
	{
		$this->db->select('a.*,b.*');
		$this->db->from('tbl_br_pk_aktif as a');
		$this->db->join('tbl_br_laporan_bus as b','b.id_lapor=a.id_lapor');
		//$this->db->join('tbl_supplier as c','a.supplier=c.id_supplier');
		//$this->db->join('tbl_departement as d','a.departement=d.id_departement');
		$this->db->where('a.status!=','S');

		$data = $this->db->get();

		return $data->result();
	}	
    public function select_pk_off($id)
	{
		$this->db->select('a.*,b.*');
		$this->db->from('tbl_br_pk_aktif as a');
		$this->db->join('tbl_br_laporan_bus as b','b.id_lapor=a.id_lapor');
		//$this->db->join('tbl_supplier as c','a.supplier=c.id_supplier');
		//$this->db->join('tbl_departement as d','a.departement=d.id_departement');
		$this->db->where('a.id_lapor=',$id);
		$this->db->where('a.status=','S');

		$data = $this->db->get();

		return $data->result();
	}
	function update_ket($id,$keterangan)
		{
			$sql_update = "UPDATE tbl_wh_detail_part_keluar SET ket_part = '$keterangan' WHERE id ='{$id}'";
			$this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
		function update_jumlah($id,$jumlah,$no_part,$status_part)
		{
            $ci = get_instance();
            //$kodePart   = $data['no_part'];
            $query = "SELECT stok,stok_a,stok_p FROM tbl_wh_barang WHERE no_part='{$no_part}'";
            $d_data = $ci->db->query($query)->row_array();
            $stok       = $d_data['stok'];
            $stok_a       = $d_data['stok_a'];
            $stok_p       = $d_data['stok_p'];
            $stok_ambil = $stok - $jumlah;
            $jstok = '';
            if ($status_part =='PPU'){
                $jstok = $stok_a - $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_ambil', stok_a ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);
    
            }
            if ($status_part =='MPU'){
                $jstok = $stok_p - $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_ambil', stok_p ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);
    
            }

			$sql_update = "UPDATE tbl_wh_detail_part_keluar SET jumlah = '$jumlah' WHERE id ='{$id}'";
			$this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
        public function deletepartDetail($id,$no_part,$jumlah,$status_part)
        {
            $ci = get_instance();
            $kodePart   = $data['no_part'];
            $query = "SELECT stok,stok_a,stok_p FROM tbl_wh_barang WHERE no_part='{$no_part}'";
            $d_data = $ci->db->query($query)->row_array();
            $stok       = $d_data['stok'];
            $stok_a       = $d_data['stok_a'];
            $stok_p       = $d_data['stok_p'];
            $stok_kembali = $stok + $jumlah;
            $jstok = '';
            
            if ($status_part =='PPU'){
                $jstok = $stok_a + $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_kembali', stok_a ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);
    
            }
            if ($status_part =='MPU'){
                $jstok = $stok_p + $jumlah;
                $sql_stok = "UPDATE tbl_wh_barang SET stok = '$stok_kembali', stok_p ='$jstok' WHERE no_part ='{$no_part}'";
                $this->db->query($sql_stok);
    
            }

            $sql = "DELETE FROM tbl_wh_detail_part_keluar WHERE id='" . $id . "'";
    
            $this->db->query($sql);
    
            return $this->db->affected_rows();
        }
        public function insertDetail_temp($data)
        {
            date_default_timezone_set('Asia/Jakarta');
            $date= date("Y-m-d");
            $sql = "INSERT INTO tbl_wh_detail_part_keluar SET
                id       ='',
                id_keluar           ='" . $data['kode_keluar'] . "',
                no_part         ='" . $data['no_part'] . "',
                nama_part       ='" . $data['nama_part'] . "',
                status_part     ='" . $data['status_part'] . "',
                tgl_keluar      ='$date',
                hrg_part        ='" . $data['hrg_awal'] . "',
                jumlah          ='0',
                satuan     ='" . $data['satuan'] . "',
                total_harga     ='" . $data['hrg_awal'] . "',
                ket_part        ='".$data['keterangan']."'";
            $this->db->query($sql);
    
            return $this->db->affected_rows();
        }
	public function insertDetail($kodeKeluar, $data)
    {
        date_default_timezone_set('Asia/Jakarta');
		$date= date("Y-m-d");
        $kodenya = "";
        if (empty($data['id_keluar'])) {
            $kodenya = $kodeKeluar;
        } else {
            $kodenya = $data['id_keluar'];
        }
        $total_harga = $data['hrg_awal'] * $data['jumlah'];

        $ci = get_instance();
        $kodePart   = $data['no_part'];
        $query = "SELECT stok,stok_a,stok_p FROM tbl_wh_barang WHERE no_part='{$kodePart}'";
        $d_data = $ci->db->query($query)->row_array();
        $stok       = $d_data['stok'];
        $stok_a       = $d_data['stok_a'];
        $stok_p       = $d_data['stok_p'];
        $stok_update = $stok - $data['jumlah'];
        $statusnye = "";
        if($data['status_part']=='PPU'){
            $statusnye = $stok_a-$data['jumlah'];
            $sql_update = "UPDATE tbl_wh_barang SET stok ='$stok_update', stok_a ='$statusnye ' WHERE no_part ='{$kodePart}'"; $this->db->query($sql_update);
        }
        if($data['status_part']=='MPU'){
            $statusnye = $stok_p-$data['jumlah'];
            $sql_update = "UPDATE tbl_wh_barang SET stok ='$stok_update', stok_p ='$statusnye ' WHERE no_part ='{$kodePart}'"; $this->db->query($sql_update);
        }
        


        $sql = "INSERT INTO tbl_wh_detail_part_keluar SET
            id       ='',
            id_keluar           ='" . $kodenya . "',
            no_part         ='" . $data['no_part'] . "',
            nama_part       ='" . $data['nama_part'] . "',
            status_part     ='" . $data['status_part'] . "',
            tgl_keluar      ='$date',
            hrg_part        ='" . $data['hrg_awal'] . "',
            jumlah          ='" . $data['jumlah'] . "',
            total_harga     ='$total_harga',
            ket_part        ='".$data['keterangan']."'";
        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    function cari_pk($id)
    {
		$this->db->select('a.*,b.*,c.kategori AS nama_kategori, d.type');
		$this->db->from('tbl_br_pk_aktif as a');
		$this->db->join('tbl_br_laporan_bus as b','b.id_lapor=a.id_lapor');
		$this->db->join('tbl_br_kategori as c','c.id_kategori=b.kategori');
		$this->db->join('tbl_wh_body as d','d.no_body=a.no_body');
		$this->db->where('a.id_pk ',$id);

		$data = $this->db->get();

		return $data->result();
    }
    function cari_part($no_part)
    {
		$sql = "SELECT * FROM tbl_wh_barang WHERE no_part ='{$no_part}'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	public function pausepk($data)
	{
        $date = date("Y-m-d");
        $jam = date("H:i:s");
		$sql = "UPDATE tbl_br_pk_aktif SET status='P',ket_pause='" . $data['ket_pause'] . "',jam_pause='" .$jam. "',tgl_pause='" .$date. "'
        WHERE id_pk='" . $data['id_pk'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function select_detail($id)
	{
		$sql = "SELECT a.*,b.std_pakai FROM tbl_wh_detail_part_keluar AS a
                LEFT JOIN tbl_wh_barang AS b ON b.no_part=a.no_part WHERE id_keluar = '{$id}' ORDER BY a.id DESC";

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
		LEFT JOIN tbl_br_kat_pk as b ON b.kode=a.jns_pk WHERE a.id_lapor = '{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function cetak_part_pk($id)
    {
		$sql = "SELECT * FROM tbl_br_pk_aktif WHERE id_pk ='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
    }
	function cetak_detail_part($id)
    {
		$sql = "SELECT * FROM tbl_wh_part_keluar AS a
        LEFT JOIN tbl_wh_detail_part_keluar AS b
        ON b.id_keluar=a.id_keluar
        WHERE a.no_pk ='{$id}' ";

		$data = $this->db->query($sql);

		return $data->result();
    }
    function cetak_detail_sum($id)
    {
		$sql = "SELECT SUM(b.total_harga) totalnya FROM tbl_wh_part_keluar AS a
        LEFT JOIN tbl_wh_detail_part_keluar AS b
        ON b.id_keluar=a.id_keluar
        WHERE a.no_pk ='{$id}' GROUP BY a.id_keluar; ";

		$data = $this->db->query($sql);

		return $data->result();
    }
    function cetak_pk_bon($id)
    {
		$sql = "SELECT * FROM tbl_wh_part_keluar AS a
        LEFT JOIN tbl_br_pk_aktif AS b
        ON b.id_pk=a.no_pk
        WHERE a.id_keluar ='{$id}'  ";

		$data = $this->db->query($sql);

		return $data->result();
    }
    function cetak_bon($id)
    {
		$sql = "SELECT * FROM tbl_wh_part_keluar AS a
        LEFT JOIN tbl_wh_detail_part_keluar AS b
        ON b.id_keluar=a.id_keluar
        WHERE a.id_keluar ='{$id}' ";

		$data = $this->db->query($sql);

		return $data->result();
    }

   // SELECT * FROM tbl_br_pk_aktif AS a
   //     LEFT JOIN tbl_wh_part_keluar AS b ON b.no_spk=a.id_lapor
   //     LEFT JOIN tbl_wh_detail_part_keluar AS c ON c.id_keluar=b.id_keluar
   //     WHERE a.id_lapor ='
	//** end Keterangan PK **//

}