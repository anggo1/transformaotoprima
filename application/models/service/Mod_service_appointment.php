<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_service_appointment extends CI_Model
{
    var $table = 'tbl_after_sales';
    var $column_search = array('wo_no','sa_name','customer','customer_complain','vin','no_pol','type','storing','date_open_wo','clockin','date_close_wo','clockout','status','pembuat');
    var $column_order = array('null','wo_no','sa_name','customer','customer_complain','vin','no_pol','type','storing','date_open_wo','clockin','date_close_wo','clockout','status','pembuat');
    var $order = array('id' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('id,wo_no,sa_name,customer,customer_name,customer_complain,vin,no_pol,type,storing,date_open_wo,clockin,date_close_wo,clockout,status,pembuat');
        $this->db->from('tbl_after_sales');
        $this->db->where('status','N');
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

        $this->db->from('tbl_after_sales');
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
    public function select_customer()
    {
        $sql = " SELECT * FROM tbl_customer";

        $data = $this->db->query($sql);

        return $data->result();
    }
    function insertAppointment($data)
    {
        
        
		$date_wo = $data['date_open_wo'];
		$tgl1 = explode('-', $date_wo);
		$date_open_wo= $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
		$date_last = $data['last_service_date'];
		$tgl2 = explode('-', $date_last);
		$date_last_service= $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
		$date_dead = $data['dead_line'];
		$tgl3 = explode('-', $date_dead);
		$date_dead_line= $tgl3[2] . "-" . $tgl3[1] . "-" . $tgl3[0] . "";
		$nama_customer = trim($_POST['customer']);
        $kat = explode('|', $nama_customer);
        $nama_cus = $kat[1];
        $kode_cus = $kat[0];
        $date = date("my");
		$ci_kons = get_instance();
		$query = "SELECT max(wo_no) AS maxKode FROM tbl_after_sales WHERE wo_no LIKE '%$date%'";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = substr($noOrder, 0, 5);
		$noUrut++;
		$tahun = substr($date, 2, 2);
		$bulan = substr($date, 0, 2);
		$kode_po  = sprintf("%05s", $noUrut).$bulan.$tahun; 
        
        $data_sa='';
        if(empty($data['sa_name'])){
            $data_sa = $data['pembuat'];
        }else{
            $data_sa = $data['sa_name'];
        }   

        $sql = "INSERT INTO tbl_after_sales SET
        id   ='',
        wo_no     ='".$kode_po."',
        sa_name   ='".$data_sa."',
        customer  ='".$kode_cus."',
        customer_name  ='".$nama_cus."',
        customer_complain  ='".$data['customer_complain']."',
        vin       ='".$data['vin']."',
        engine_no ='".$data['engine_no']."',
        last_service_date ='".$date_last_service."',
        dead_line ='".$date_dead_line."',
        mileage   ='".$data['mileage']."',
        no_pol    ='".$data['licence_plate']."',
        type      ='".$data['vehicle_type']."',
        storing   ='".$data['storing']."',
        date_open_wo  ='".$date_open_wo."',
        clockin   ='".$data['clockin']."',
        status    ='N',
        pembuat   ='".$data['pembuat']."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function updateAppointment($data)
    {
        $tgl1 = explode('-', $date_wo);
		$date_open_wo= $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
		$date_last = $data['last_service_date'];
		$tgl2 = explode('-', $date_last);
		$date_last_service= $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
		$date_dead = $data['dead_line'];
		$tgl3 = explode('-', $date_dead);
		$date_dead_line= $tgl3[2] . "-" . $tgl3[1] . "-" . $tgl3[0] . "";
        $sql = "UPDATE tbl_after_sales SET
        wo_no     ='".$data['wo_no']."',
        sa_name   ='".$data['sa_name']."',
        customer  ='".$data['customer']."',
        customer_complain  ='".$data['customer_complain']."',
        vin       ='".$data['vin']."',
        engine_no ='".$data['engine_no']."',
        last_service_date ='".$date_last_service."',
        dead_line ='".$date_dead_line."',
        mileage   ='".$data['mileage']."',
        no_pol    ='".$data['licence_plate']."',
        type      ='".$data['vehicle_type']."',
        storing   ='".$data['storing']."',
        date_open_wo  ='".$data['date_open_wo']."',
        clockin   ='".$data['clockin']."' WHERE id='".$data['id']."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
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

    function deleteAppointment($id)
    {
        $sql = "DELETE FROM tbl_after_sales WHERE id='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}

/* End of file Mod_service_appointment.php */