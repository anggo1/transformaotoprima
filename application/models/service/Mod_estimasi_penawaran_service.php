<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_estimasi_penawaran_service extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    var $table = 'tbl_wh_barang';
    var $column_search = array('a.no_part','a.nama_part','a.satuan','a.harga_baru','a.diskon','a.harga_net','a.harga_rata','a.ppn','a.harga_valid','a.ket_harga');
    var $column_order = array('null','a.no_part','a.nama_part','a.satuan','a.harga_baru','a.diskon','a.harga_net','a.harga_rata','a.ppn','a.harga_valid','a.ket_harga');
    var $order = array('id_part' => 'desc'); // default order 

    private function _get_datatables_query($term = '')
    {

        $this->db->select('id_part,no_part,nama_part,satuan,harga_baru,stok');
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
    //tabele work order
    var $table_estimasi = 'tbl_after_sales';
    var $column_search_estimasi = array('wo_no','sa_name','customer','customer_name','customer_complain','vin','no_pol','type','storing','date_open_wo','clockin','date_close_wo','clockout','status','work_order','pembuat');
    var $column_order_estimasi = array('null','wo_no','sa_name','customer','customer_name','customer_complain','vin','no_pol','type','storing','date_open_wo','clockin','date_close_wo','clockout','status','work_order','pembuat');
    var $order_estimasi = array('id' => 'asc'); // default order 

    private function _get_datatables_query_estimasi($term = '')
    {
    $this->db->select('id,wo_no,sa_name,customer,customer_name,customer_complain,vin,no_pol,type,storing,date_open_wo,clockin,date_close_wo,clockout,status,work_order,estimasi,pembuat');
        $this->db->from('tbl_after_sales');
        //$this->db->where('estimasi !=', 'Y');
        $this->db->where('status !=', 'F');
        $i = 0;

        foreach ($this->column_search_estimasi as $item) // loop column 
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

                if (count($this->column_search_estimasi) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order_estimasi[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order_estimasi)) {
            $order = $this->order_estimasi;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables_estimasi()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query_estimasi($term);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_estimasi()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query_estimasi($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_estimasi()
    {

        $this->db->from('tbl_after_sales');
        //$this->db->join('tbl_menu as b','a.id_menu=b.id_menu');
        return $this->db->count_all_results();
    }
    //end tabel work order
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
        $this->db->where('tbl_akses_submenu.id_level=',$idlevel);
        $this->db->where('tbl_akses_submenu.id_submenu=',$id_sub);
        $data = $this->db->get();
        return $data->result();
    }
    
    function select_part($sup)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok');
        $this->db->from('tbl_wh_barang as a');
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
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_customer as f', 'f.id_supplier=a.supplier', 'left');
        $this->db->where('a.id_part', $id);
        return $this->db->get('tbl_wh_barang')->row();
    }
    public function select_supplier()
    {
        $sql = " SELECT * FROM tbl_wh_supplier";

        $data = $this->db->query($sql);

        return $data->result();
    }
    function select_sa($id)
    {
       $this->db->select('*');
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
    
    public function deleteDetail_po($id, $spk)
    {
        $sql = "DELETE FROM tbl_af_detail_estimasi_penawaran WHERE id_detail='" . $id . "' or spk='" . $spk . "'";

        $this->db->query($sql);
        
        $sql2 = "DELETE FROM tbl_after_sales_detail_wo WHERE id_detail='{$id}' OR spk='{$spk}'";

		$this->db->query($sql2);

        return $this->db->affected_rows();
    }
    public function deleteKeterangan_po($id)
    {
        $sql = "DELETE FROM tbl_af_detail_estimasi_penawaran_note WHERE id_detail_note='" . $id . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    public function insertNote($id)
    {        
        $sql_detil = "INSERT INTO tbl_af_detail_estimasi_penawaran_note (wo_no,remark) 
        VALUES ('".$id."','Masa berlaku 7 hari'), 
        ('".$id."','Stock sewaktu-waktu dapat berubah sesuai kebijakan'),
        ('".$id."','Harga dapat berubah sewaktu-waktu'),
        ('".$id."','Standar LOCO : Panjaitan, Cibitung, Osowilangun'),
        ('".$id."','Term of Payment Cash Before Delivery'),
        ('".$id."','Harga Sudah Termasuk Diskon'),
        ('".$id."','Barga Belum termasuk ongkos kirim'),
        ('".$id."','Harga Belum termasuk jasa'),
        ('".$id."','Semua sparepart yang kami tawarkan original Merced...'),
        ('".$id."','Jika ada sparepart yang Tidak Ready maka Indent'),
        ('".$id."','Pembayaran harap ditransfer atau Bilyet Giro atas ...')";
        $this->db->query($sql_detil);
        return $this->db->affected_rows();
    }
    public function insertDetailPo($kode_po, $kode_ref, $data)
    {
        
        $datenow = date("Y-m-d");
		$harga=$data['harga_baru'];
		$harga_baru =str_replace(",","", $harga);
        $grand_total = $harga_baru * $data['jumlah'];

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

        $jenis = $data['jenis'];
        if($jenis == 'S'){
           $spk=$kode_keluar;

        $sql2 = "INSERT INTO tbl_after_sales_detail_wo SET
        id_detail   ='',
        wo_no       ='" . $data['wo_no'] . "',
        spk       ='".$kode_keluar."',
        operation   ='" . $data['no_part'] . "',
        hours       ='" . $data['jumlah'] . "',
        type_of_work  ='" . $data['nama_part'] . "',
        jumlah      ='" . $data['jumlah'] . "',
        harga       ='" . $harga_baru. "',
        total_harga   ='" . $grand_total. "'";

		$this->db->query($sql2);
           }else{
             $spk='';}
              
        $sql = "INSERT INTO tbl_af_detail_estimasi_penawaran SET
            wo_no     ='" . $data['wo_no'] . "',
            id_estimasi_penawaran   ='" . $kode_po . "',
            kode_estimasi_penawaran   ='" . $kode_ref . "',
            no_part     ='" . $data['no_part'] . "',
            nama_part   ='" . $data['nama_part'] . "',
            satuan      ='" . $data['satuan'] . "',
            harga       ='" . $harga_baru. "',
            harga_net   ='" . $harga_baru. "',
            total_harga   ='" . $grand_total. "',
            jumlah      ='" . $data['jumlah'] . "',
            stok_akhir  ='" . $data['stok'] . "',
            validasi_jenis = '" . $data['jenis'] . "',
            spk = '" . $spk . "'";

        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    function update_detailDiskon($id,$diskon,$hrg_part)
		{			
		$jml_diskon =str_replace(" ","", $diskon);
		$total=$hrg_part * $jml_diskon / 100;
        $harga_asli = $hrg_part;
        $totalnya = $harga_asli - $total;
			$sql_update = "UPDATE tbl_af_detail_estimasi_penawaran SET diskon ='$jml_diskon', harga_net = '$totalnya' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);

		return $this->db->affected_rows();
			//return $data->row();
		}
    function update_detailPo($id,$jml_part,$hrg_part,$total)
		{			
		//$jml =str_replace(" ","", $jml_part);
		//$total=$hrg_part*$jml_part;
			$sql_update = "UPDATE tbl_af_detail_estimasi_penawaran SET jumlah ='$jml_part', total_harga = '$total', sisa ='$jml_part' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);

		return $this->db->affected_rows();
			//return $data->row();
		}
    function update_remark($id,$remark)
		{
			$sql_update = "UPDATE tbl_af_detail_estimasi_penawaran SET remark ='$remark' WHERE id_detail ='{$id}'"; $this->db->query($sql_update);
		return $this->db->affected_rows();
			//return $data->row();
		}
    function insertRemark($id,$remark)
		{
			$sql_update = "INSERT tbl_af_detail_estimasi_penawaran_note SET wo_no = '$id', remark ='$remark'";
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
        $sql = "INSERT INTO tbl_af_detail_estimasi_penawaran SET
            id_detail       ='',
            id_estimasi_penawaran   ='" . $kodenya . "',
            kode_po         ='" . $koderefnya . "',
            no_part         ='" . $data['no_part'] . "',
            nama_part       ='" . $data['nama_part'] . "',
            harga_baru           ='" . $data['harga_baru'] . "',
            jumlah          ='" . $data['jumlah'] . "',
            diskon          ='" . $data['diskon'] . "',
            total_diskon    ='" . $data['total_diskon'] . "',
            total_harga     ='$total_harga',
            stok_akhir     ='" . $data['stok_awal'] . "'";
        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    public function select_by_id($id)
    {
        $sql = "SELECT * FROM tbl_af_estimasi_penawaran 
        LEFT JOIN tbl_wh_customer ON tbl_wh_customer.kode_cus=tbl_af_estimasi_penawaran.id_customer
        WHERE wo_no ='{$id}'";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    
    public function select_detail($id)
    {
        $ci = get_instance();
                $query = "SELECT sum(total_harga) as total,b.ppn FROM tbl_af_detail_estimasi_penawaran as a 
                    LEFT JOIN tbl_af_estimasi_penawaran as b ON b.wo_no=a.wo_no
                    WHERE a.wo_no='{$id}'";
        $d_data = $ci->db->query($query)->row_array();
        $total       = $d_data['total'];
        $ppn       = $d_data['ppn'];
        if(empty($ppn)){
            $ppn = 0;
        }
        $total_ppn = $total * $ppn / 100;
        $grand_total = $total;
        $sql_update = "UPDATE tbl_af_estimasi_penawaran SET
        t_ppn       ='$total_ppn',
        sub_total   ='$total',
        grand_total ='$grand_total'
        WHERE wo_no ='{$id}'";

        $this->db->query($sql_update);

        $sql = "SELECT a.* 
        FROM tbl_af_detail_estimasi_penawaran AS a
        WHERE a.wo_no ='{$id}' ORDER BY a.id_detail ASC";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    public function select_keterangan($id)
    {
        $sql = "SELECT * FROM tbl_af_detail_estimasi_penawaran_note WHERE wo_no ='{$id}' ORDER BY id_detail_note ASC";

        $data = $this->db->query($sql);
        return $data->result();
    }
    function updatePo($a, $b, $c, $d)
    {
        $sql = "UPDATE tbl_af_estimasi_penawaran SET
        t_ppn       ='$a',
        sub_total   ='$b',
        grand_total ='$c'
        WHERE wo_no ='" . $data['wo_no'] . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    function updateWo($wo_no)
    {
        $sql = "UPDATE tbl_after_sales SET
        estimasi       ='Y'
         WHERE wo_no ='" . $wo_no . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
}