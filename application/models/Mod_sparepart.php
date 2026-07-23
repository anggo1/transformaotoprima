<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_sparepart extends CI_Model
{
    var $table = 'tbl_wh_barang';
    var $column_search = array('a.no_part','a.nama_part','a.satuan','a.lokasi','a.stok','a.kelompok','a.type','a.kategori');
    var $column_order = array('null','a.no_part','a.nama_part','a.satuan','a.lokasi','a.stok','a.kelompok','a.type','a.kategori');
    var $order = array('id_part' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('a.*');
        $this->db->from('tbl_wh_barang as a');
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
        $this->db->select('tbl_wh_barang');
        //$this->db->join('tbl_menu b','a.id_menu=b.id_menu');
        return $this->db->get('tbl_wh_barang a');
    }
    function select_by_id_part($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_wh_barang');
        $this->db->where('tbl_wh_barang.id_part=',$id);
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
    public function select_satuan()
    {
        $sql = " SELECT * FROM tbl_wh_satuan";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function select_kategori()
    {
        $sql = " SELECT * FROM tbl_wh_kategori";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function select_type()
    {
        $sql = " SELECT * FROM tbl_wh_type_mesin";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function select_kelompok()
    {
        $sql = " SELECT * FROM tbl_wh_kelompok";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function select_supplier()
    {
        $sql = " SELECT * FROM tbl_wh_supplier";

        $data = $this->db->query($sql);

        return $data->result();
    }
    function view_sparepart($id)
    {
        $this->db->select('a.*');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->where('a.id_part',$id);

        $data = $this->db->get();

        return $data->result();
    }
    function cetak_sparepart()
    {
        $this->db->select('a.*');
        $this->db->from('tbl_wh_barang as a');

        $data = $this->db->get();

        return $data->result();
    }
    function insertSparepart($data)
    {
        
        $idlokasi = $this->session->userdata['lokasi'];

		$pricelist=$data['harga_pricelist'];
		$hrg_pricelist =str_replace(",","", $pricelist);
		$net=$data['harga_net'];
		$hrg_net =str_replace(",","", $net);
        
        if($idlokasi=='Cibitung'){
        $sql = "INSERT INTO tbl_wh_barang SET
        id_part   ='',
        no_part     ='".$data['no_part']."',
        nama_part   ='".$data['nama_part']."',
        nama_part_e ='".$data['nama_part_e']."',
        satuan      ='".$data['satuan']."',
        kelompok    ='".$data['kelompok']."',
        type        ='".$data['type']."',
        kategori    ='".$data['kategori']."',
        kode_sup    ='".$data['supplier']."',
        lok_sby     ='".$data['lokasi']."',
        hrg_net_sby ='".$hrg_net."',
        price_list_sby ='".$hrg_pricelist."',
        std_pakai   ='".$data['std_pakai']."'"; 
        }if($idlokasi=='Jakarta'){
        $sql = "INSERT INTO tbl_wh_barang SET
        id_part   ='',
        no_part     ='".$data['no_part']."',
        nama_part   ='".$data['nama_part']."',
        nama_part_e ='".$data['nama_part_e']."',
        satuan      ='".$data['satuan']."',
        kelompok    ='".$data['kelompok']."',
        type        ='".$data['type']."',
        kategori    ='".$data['kategori']."',
        kode_sup    ='".$data['supplier']."',
        lok_jkt     ='".$data['lokasi']."',
        hrg_net_jkt ='".$hrg_net."',
        price_list_jkt ='".$hrg_pricelist."',
        std_pakai   ='".$data['std_pakai']."'"; 
        }if($idlokasi=='Surabaya'){
        $sql = "INSERT INTO tbl_wh_barang SET
        id_part   ='',
        no_part     ='".$data['no_part']."',
        nama_part   ='".$data['nama_part']."',
        nama_part_e ='".$data['nama_part_e']."',
        satuan      ='".$data['satuan']."',
        kelompok    ='".$data['kelompok']."',
        type        ='".$data['type']."',
        kategori    ='".$data['kategori']."',
        kode_sup    ='".$data['supplier']."',
        lok_sby     ='".$data['lokasi']."',
        hrg_net_sby ='".$hrg_net."',
        price_list_sby ='".$hrg_pricelist."',
        std_pakai   ='".$data['std_pakai']."'"; 
        }
		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function updateSparepart($data)
    {
        
        $idlokasi = $this->session->userdata['lokasi'];

		$pricelist=$data['harga_pricelist'];
		$hrg_pricelist =str_replace(",","", $pricelist);
		$net=$data['harga_net'];
		$hrg_net =str_replace(",","", $net);

        if($idlokasi=='Cibitung'){
        $sql = "UPDATE tbl_wh_barang SET
        nama_part   ='".$data['nama_part']."',
        nama_part_e ='".$data['nama_part_e']."',
        satuan      ='".$data['satuan']."',
        kelompok    ='".$data['kelompok']."',
        type        ='".$data['type']."',
        kategori    ='".$data['kategori']."',
        kode_sup    ='".$data['supplier']."',
        lok_cbt     ='".$data['lokasi']."',
        hrg_net_cbt ='".$hrg_net."',
        price_list_cbt ='".$hrg_pricelist."',
        std_pakai   ='".$data['std_pakai']."'
        WHERE id_part='".$data['id_part']."'";
        }if($idlokasi=='Jakarta'){
        $sql = "UPDATE tbl_wh_barang SET
        nama_part   ='".$data['nama_part']."',
        nama_part_e ='".$data['nama_part_e']."',
        satuan      ='".$data['satuan']."',
        kelompok    ='".$data['kelompok']."',
        type        ='".$data['type']."',
        kategori    ='".$data['kategori']."',
        kode_sup    ='".$data['supplier']."',
        lok_jkt     ='".$data['lokasi']."',
        hrg_net_jkt ='".$hrg_net."',
        price_list_jkt ='".$hrg_pricelist."',
        std_pakai   ='".$data['std_pakai']."'
        WHERE id_part='".$data['id_part']."'";
        }if($idlokasi=='Surabaya'){
        $sql = "UPDATE tbl_wh_barang SET
        nama_part   ='".$data['nama_part']."',
        nama_part_e ='".$data['nama_part_e']."',
        satuan      ='".$data['satuan']."',
        kelompok    ='".$data['kelompok']."',
        type        ='".$data['type']."',
        kategori    ='".$data['kategori']."',
        kode_sup    ='".$data['supplier']."',
        lok_sby     ='".$data['lokasi']."',
        hrg_net_sby ='".$hrg_net."',
        price_list_sby ='".$hrg_pricelist."',
        std_pakai   ='".$data['std_pakai']."'
        WHERE id_part='".$data['id_part']."'";
        }
		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function updateStok($data)
    {
        
        $idlokasi = $this->session->userdata['lokasi'];
        $id = $data['id_part'];
        if($idlokasi=='Jakarta'){
        $sql = "UPDATE tbl_wh_barang SET
        stok_jkt  ='".$data['stok']."',
        lok_jkt  ='".$data['lokasi']."'
        WHERE id_part='".$data['id_part']."'";
        }if($idlokasi=='Cibitung'){
        $sql = "UPDATE tbl_wh_barang SET
        stok_cbt  ='".$data['stok']."',
        lok_cbt  ='".$data['lokasi']."'
        WHERE id_part='".$data['id_part']."'";
        }if($idlokasi=='Surabaya'){
        $sql = "UPDATE tbl_wh_barang SET
        stok_sby  ='".$data['stok']."',
        lok_sby  ='".$data['lokasi']."'
        WHERE id_part='".$data['id_part']."'";
        }
		$this->db->query($sql);

		return $this->db->affected_rows();
    }

    function get_sparepart($id)
    {
        $this->db->where('id_part', $id);
        return $this->db->get('tbl_wh_barang')->row();
    }

    function edit_submenu($id)
    {
        $this->db->where('nip', $id);
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

    function deletePart($id)
    {
        $sql = "DELETE FROM tbl_wh_barang WHERE id_part='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}

/* End of file Mod_pegawai.php */