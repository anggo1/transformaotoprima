<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_part_request extends CI_Model
{
    var $table = 'tbl_after_sales';
    var $column_search = array('wo_no','sa_name','customer_name','customer_complain','vin','no_pol','type','storing','date_open_wo','clockin','date_close_wo','clockout','status','pre_order','part_request','free_service','pembuat');
    var $column_order = array('null','wo_no','sa_name','customer_name','customer_complain','vin','no_pol','type','storing','date_open_wo','clockin','date_close_wo','clockout','status','pre_order','pembuat');
    var $order = array('id' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('id,wo_no,sa_name,customer,customer_name,customer_complain,vin,no_pol,type,storing,date_open_wo,clockin,date_close_wo,clockout,status,pre_order,part_request,free_service,pembuat');
        $this->db->from('tbl_after_sales');
        $this->db->where('estimasi','Y');
        $this->db->or_where('free_service','Y');
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
    function select_sa($id)
    {
       $this->db->select('id,wo_no,sa_name,customer,customer_name,customer_complain,mileage,engine_no,vin,no_pol,type,storing,date_open_wo,clockin,date_close_wo,clockout,status,last_service_date,dead_line,pembuat');
        $this->db->from('tbl_after_sales');
         $this->db->where('wo_no',$id);

        $data = $this->db->get();

        return $data->result();
    }
    function select_customer($kode_cus)
    {
        $this->db->select('*');
        $this->db->from('tbl_customer');
        $this->db->where('kode_cus',$kode_cus);

         $data = $this->db->get();

        return $data->result();
    }
    function select_part_request_a($wo_no)
    {
        $this->db->select('*');
        $this->db->from('tbl_after_sales_part_request');
         $this->db->where('wo_no',$wo_no);

         $data = $this->db->get();

        return $data->result();
    }
    function select_part_request($wo_no)
    {
        $this->db->select('*');
        $this->db->from('tbl_af_detail_estimasi_penawaran');
        $this->db->where('wo_no',$wo_no);
        $this->db->where('validasi_jenis','P');
        $this->db->where('status_ok','Y');

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
    function insertPart($wo_no, $no_part, $nama_part, $harga, $jumlah, $total, $keterangan)
    {
        $sql = "INSERT INTO tbl_af_detail_estimasi_penawaran SET
        id_detail   ='',
        wo_no       ='".$wo_no."',
        no_part     ='".$no_part."',
        nama_part   ='".$nama_part."',
        harga       ='".$harga."',
        jumlah      ='".$jumlah."',
        total_harga       ='".$total."',
        remark  ='".$keterangan."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function updatePart($data)
    {
        $kd='PRQ';
			$tgl_keluar = date("y-m-d");
			$date = date("ym");
			$ci_kons = get_instance();
			$query = "SELECT max(kode_pr) AS maxKode FROM tbl_after_sales_part_request WHERE kode_pr LIKE '%$date%'";
			$hasil = $ci_kons->db->query($query)->row_array();
			$noOrder = $hasil['maxKode'];
			$noUrut = (int)substr($noOrder, 8, 4);
			$noUrut++;
			$tahun = substr($date, 0, 2);
			$bulan = substr($date, 2, 2);

			$id_keluar  = $tahun.$bulan.sprintf("%04s", $noUrut);
			$kode_keluar  = $kd.$tahun.$bulan.sprintf("%04s", $noUrut);

        $sql = "INSERT INTO tbl_after_sales_part_request SET
        wo_no       ='".$data['wo_no']."',
        kode_pr     ='".$kode_keluar."',
        nik   ='".$data['nik']."',
        nama       ='".$data['nama']."',
        pembuat  ='".$data['pembuat']."'";

		$this->db->query($sql);

        $sql2 = "UPDATE tbl_after_sales SET
        part_request     ='Y'
        WHERE wo_no='".$data['wo_no']."'";

		$this->db->query($sql2);

		return $this->db->affected_rows();
    }
    function deleteRequest($id)
    {
        $sql = "DELETE FROM tbl_af_detail_estimasi_penawaran WHERE id_detail='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}

/* End of file Mod_service_appointment.php */