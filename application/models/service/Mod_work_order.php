<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_work_order extends CI_Model
{
    var $table = 'tbl_after_sales';
    var $column_search = array('wo_no','sa_name','customer','customer_complain','vin','no_pol','type','storing','date_open_wo','clockin','date_close_wo','clockout','status','work_order','free_service','pembuat');
    var $column_order = array('null','wo_no','sa_name','customer','customer_complain','vin','no_pol','type','storing','date_open_wo','clockin','date_close_wo','clockout','status','work_order','free_service','pembuat');
    var $order = array('id' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('id,wo_no,sa_name,customer,customer_complain,vin,no_pol,type,storing,date_open_wo,clockin,date_close_wo,clockout,status,work_order,free_service,pembuat');
        $this->db->from('tbl_after_sales');
        $this->db->where('status !=', 'F');
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
       $this->db->select('id,wo_no,sa_name,customer,customer_complain,vin,no_pol,type,storing,date_open_wo,clockin,date_close_wo,clockout,status,pembuat');
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
    function select_operation_detail($wo_no)
    {
        $this->db->select('*');
        $this->db->from('tbl_after_sales_detail_wo');
        $this->db->where('wo_no',$wo_no);
        

        $data = $this->db->get();

        return $data->result();
    }
    function select_labor_detail($idL)
    {
        $this->db->select('*');
        $this->db->from('tbl_af_detail_estimasi_penawaran');
        $this->db->where('spk',$idL);

        $data = $this->db->get();

        return $data->result();
    }
    function select_labor_mechanic($idX)
    {
        $this->db->select('*');
        $this->db->from('tbl_after_sales_labor');
        $this->db->where('spk',$idX);

        $data = $this->db->get();

        return $data->result();
    }
    function select_work_order($wo_no)
    {
        $this->db->select('*');
        $this->db->from('tbl_after_sales_work_order');
        $this->db->where('wo_no',$wo_no);

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
    function insertOperation($wo_no, $operation, $hours, $type_of_work, $price)
    {
        $kd='SPK';
			$tgl_keluar = date("y-m-d");
			$date = date("ym");
			$ci_kons = get_instance();
			$query = "SELECT max(spk) AS maxKode FROM tbl_af_detail_estimasi_penawaran WHERE spk LIKE '%$date%'";
			$hasil = $ci_kons->db->query($query)->row_array();
			$noOrder = $hasil['maxKode'];
			$noUrut = (int)substr($noOrder, 8, 4);
			$noUrut++;
			$tahun = substr($date, 0, 2);
			$bulan = substr($date, 2, 2);

			$id_keluar  = $tahun.$bulan.sprintf("%04s", $noUrut);
			$kode_keluar  = $kd.$tahun.$bulan.sprintf("%04s", $noUrut);

		$harga_baru =str_replace(",","", $price);
        $grand_total = $price * $hours;
        
        $sql = "INSERT INTO tbl_af_detail_estimasi_penawaran SET
            wo_no     ='" . $wo_no . "',
            no_part     ='" . $operation . "',
            nama_part   ='" . $type_of_work . "',
            harga       ='" . $harga_baru. "',
            harga_net   ='" . $harga_baru. "',
            total_harga   ='" . $grand_total. "',
            jumlah      ='" . $hours . "',
            validasi_jenis = 'S',
            spk = '" . $kode_keluar . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    
    function insertLabor($wo_no, $spk, $nik, $nama)
    {
        $sql = "INSERT INTO tbl_after_sales_labor SET
        id_labor  ='',
        wo_no       ='".$wo_no."',
        spk         ='".$spk."',
        nik         ='".$nik."',
        nama        ='".$nama."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function inputWorkOrder($data)
    {
        
        $sql = "INSERT INTO tbl_after_sales_work_order SET
        wo_no     ='".$data['wo_no']."',
        wo_date   ='".date('Y-m-d')."',
        pembuat   ='".$data['pembuat']."'";

		$this->db->query($sql);

        $sql2 = "UPDATE tbl_after_sales SET
        work_order     ='".$data['wo_no']."',
        status     ='P' WHERE wo_no='".$data['wo_no']."'";

		$this->db->query($sql2);

		return $this->db->affected_rows();
    }
    function deleteOperation($id)
    {
        $sql = "DELETE FROM tbl_af_detail_estimasi_penawaran WHERE id_detail='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function deleteMechanic($id)
    {
        $sql = "DELETE FROM tbl_after_sales_labor WHERE id_labor='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function start_work($id_detail,$no_work_order)
    {
	    $tgl_mulai  = date('Y-m-d H:i:s');
	    $jam_mulai  = date("H:i:s");
        $sql2 = "UPDATE tbl_after_sales_detail_wo SET
        start_date     = NOW(),
        status     ='R' WHERE id_detail='".$id_detail."'";

		$this->db->query($sql2);

		return $this->db->affected_rows();
    }
    //total_pause     ='".$menit.",".$detik."',
    function pause_work($id_detail,$no_work_order,$total)
    {
        $waktu_input='';
            $ci_kons = get_instance();
			$query = "SELECT total_pause FROM tbl_after_sales_detail_wo WHERE id_detail = '$id_detail'";
			$hasil = $ci_kons->db->query($query)->row_array();
		    $pause = $hasil['total_pause'];
            empty ($pause) ? $waktu_input=$total : $waktu_input=$total+$pause;

        $sql2 = "UPDATE tbl_after_sales_detail_wo SET
        total_pause     ='".$waktu_input."',
        status     ='P' WHERE id_detail='".$id_detail."'";

		$this->db->query($sql2);

		return $this->db->affected_rows();
    }

    function end_work($id_detail,$no_work_order,$total)
    {
	    $tgl_jam_sekarang  = date("Y-m-d H:i:s");
        $waktu_input='';
            $ci_kons = get_instance();
			$query = "SELECT total_pause FROM tbl_after_sales_detail_wo WHERE id_detail = '$id_detail'";
			$hasil = $ci_kons->db->query($query)->row_array();
		    $pause = $hasil['total_pause'];
            empty ($pause) ? $waktu_input=$total : $waktu_input=$total+$pause;
        $sql2 = "UPDATE tbl_after_sales_detail_wo SET
        end_date    ='".$tgl_jam_sekarang."',
        total_time    ='".$waktu_input."',
        status     ='F' WHERE id_detail='".$id_detail."'";

		$this->db->query($sql2);

		return $this->db->affected_rows();
    }
    function finish_work($wo_no)
    {
         $tgl_jam_sekarang  = date("Y-m-d H:i:s");
        $waktu_input='';
            $ci_kons = get_instance();
			$query = "SELECT total_pause,total_time FROM tbl_after_sales_detail_wo WHERE wo_no = '$wo_no' AND status = 'R' OR status = 'P'";
			$hasil = $ci_kons->db->query($query)->row_array();
		    $pause = $hasil['total_pause'];
		    $total = $hasil['total_time'];
            empty ($pause) ? $waktu_input=$total : $waktu_input=$total+$pause;
        $sql2 = "UPDATE tbl_after_sales_detail_wo SET
        end_date    ='".$tgl_jam_sekarang."',
        total_time    ='".$waktu_input."',
        status     ='F' WHERE wo_no='".$wo_no."'";

		$this->db->query($sql2);

        
	    $tgl_sekarang  = date("Y-m-d");
	    $jam_sekarang  = date("H:i:s");
        $sql = "UPDATE tbl_after_sales SET        
        date_close_wo    ='".$tgl_sekarang."',
        clockout    ='".$jam_sekarang."',
        status     ='F' WHERE wo_no='".$wo_no."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}

/* End of file Mod_service_appointment.php */