<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_update_hpart extends CI_Model
{

    var $table = 'tbl_wh_barang';
    var $column_search = array('a.no_part','a.nama_part','a.satuan','a.harga_baru','a.diskon','a.harga_net','a.harga_rata','a.ppn','a.harga_valid','a.ket_harga');
    var $column_order = array('null','a.no_part','a.nama_part','a.satuan','a.harga_baru','a.diskon','a.harga_net','a.harga_rata','a.ppn','a.harga_valid','a.ket_harga');
    var $order = array('id_part' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        
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
        $this->db->select('a.*');
        $this->db->from('tbl_wh_barang as a');
        $this->db->where('a.id_part',$id);
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

    function updateHarga( $data)
    {
        $datenow= date("Y-m-d");
        $hargaB=$data['harga_baru'];
		$harga_baru =str_replace(",","", $hargaB);
        $hargaN=$data['harga_net'];
		$harga_net =str_replace(",","", $hargaN);
        $hargaR=$data['harga_rata'];
		$harga_rata =str_replace(",","", $hargaR);
        $hargaV=$data['harga_valid'];
		$harga_valid =str_replace(",","", $hargaV);

        $sql_log = "INSERT INTO tbl_wh_log_harga SET
        id = '',
        id_part   = '".$data['id_part']."',
        no_part     = '".$data['no_part']."',
        harga_baru  = '".$harga_baru."',
        diskon      = '".$data['diskon']."',
        harga_net   = '".$harga_net."',
        harga_rata  = '".$harga_rata."',
        harga_valid = '".$harga_valid."',
        ket_harga   = '".$data['ket_harga']."',
        ppn         = '".$data['ppn']."',
        tgl_update  = '$datenow',
        user        = '".$data['user']."'";
        $this ->db ->query($sql_log);

        $sql = "UPDATE tbl_wh_barang SET
        harga_baru  = '".$harga_baru."',
        diskon      = '".$data['diskon']."',
        harga_net   = '".$harga_net."',
        harga_rata  = '".$harga_rata."',
        harga_valid = '".$harga_valid."',
        ppn         = '".$data['ppn']."',
        ket_harga   = '".$data['ket_harga']."'
        WHERE id_part = '".$data['id_part']."'";
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