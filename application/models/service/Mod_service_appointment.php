<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_service_appointment extends CI_Model
{
    var $table = 'tbl_after_sales';
    var $column_search = array('wo_no','sa_name','customer','customer_name','customer_complain','vin','engine_no','no_pol','type','pembuat');
    var $column_order = array(null, 'wo_no', 'date_open_wo', 'customer_name', 'no_pol', 'customer_complain', 'sa_name', 'free_service', 'dead_line', 'pre_order', null);
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('id,wo_no,sa_name,customer,customer_name,customer_complain,vin,engine_no,no_pol,type,storing,date_open_wo,clockin,date_close_wo,clockout,status,free_service,status_service,dead_line,dead_time,mileage,remarks,pre_order,pembuat');
        $this->db->from('tbl_after_sales');
        $this->db->where('status','N');
        $dari = tgl_sql((string) $this->input->post('dari')); $sampai = tgl_sql((string) $this->input->post('sampai'));
        if ($dari) $this->db->where('date_open_wo >=', $dari);
        if ($sampai) $this->db->where('date_open_wo <=', $sampai);
        $f = (string) $this->input->post('filter');
        if ($f === 'menunggu') $this->db->where("(pre_order IS NULL OR pre_order = '')", null, false);
        elseif ($f === 'storing') $this->db->where('storing', 'Y');
        elseif ($f === 'lewat') $this->db->where('dead_line <', date('Y-m-d'))->where("dead_line <> '0000-00-00' AND dead_line IS NOT NULL", null, false);
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

        if (isset($_POST['order']) && ! empty($this->column_order[$_POST['order']['0']['column']])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir'] === 'asc' ? 'asc' : 'desc');
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

        $this->db->from('tbl_after_sales')->where('status', 'N');
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
    /** tanggal dari form: dd-mm-yyyy atau yyyy-mm-dd */
    private static function tgl($v) { $t = tgl_sql((string) $v); return $t ?: null; }

    function get($id)
    {
        return $this->db->get_where('tbl_after_sales', array('id' => (int) $id))->row();
    }

    /** ringkasan untuk kartu di atas daftar */
    function ringkasan()
    {
        $hari = date('Y-m-d'); $bln = date('Y-m-01');
        $q = function () { return $this->db->where('status', 'N'); };
        $o = (object) array();
        $o->hari_ini = $q()->where('date_open_wo', $hari)->count_all_results('tbl_after_sales');
        $o->bulan_ini = $q()->where('date_open_wo >=', $bln)->count_all_results('tbl_after_sales');
        $o->menunggu = $q()->where("(pre_order IS NULL OR pre_order = '')", null, false)->count_all_results('tbl_after_sales');
        $o->storing = $q()->where('storing', 'Y')->count_all_results('tbl_after_sales');
        $o->lewat = $q()->where('dead_line <', $hari)->where("dead_line <> '0000-00-00' AND dead_line IS NOT NULL", null, false)->count_all_results('tbl_after_sales');
        return $o;
    }

    /** kendaraan yang pernah diservis untuk customer (untuk isi otomatis) */
    function kendaraan($kodeCus)
    {
        return $this->db->select('vin, engine_no, no_pol, type, MAX(mileage) AS mileage, MAX(date_open_wo) AS terakhir', false)
            ->where('customer', $kodeCus)->where("vin <> ''", null, false)->group_by(array('vin', 'engine_no', 'no_pol', 'type'))
            ->order_by('terakhir', 'DESC')->limit(20)->get('tbl_after_sales')->result();
    }

    function insertAppointment($data)
    {
        $date_open_wo = self::tgl($data['date_open_wo']) ?: date('Y-m-d');
        $nama_customer = trim((string) $data['customer']);
        $kat = explode('|', $nama_customer);
        $kode_cus = $kat[0];
        $nama_cus = isset($kat[1]) ? $kat[1] : '';
        // nomor WO: 5 digit urut + bulan + tahun (format lama tetap dipakai)
        $date = date('my');
        $hasil = $this->db->query("SELECT max(wo_no) AS maxKode FROM tbl_after_sales WHERE wo_no LIKE '%" . $this->db->escape_like_str($date) . "'")->row_array();
        $noUrut = (int) substr((string) $hasil['maxKode'], 0, 5) + 1;
        $kode_po = sprintf('%05s', $noUrut) . substr($date, 0, 2) . substr($date, 2, 2);

        $row = array(
            'wo_no' => $kode_po,
            'sa_name' => trim((string) ($data['sa_name'] ?? '')) !== '' ? $data['sa_name'] : $data['pembuat'],
            'customer' => $kode_cus, 'customer_name' => $nama_cus,
            'customer_complain' => (string) ($data['customer_complain'] ?? ''),
            'vin' => (string) ($data['vin'] ?? ''), 'engine_no' => (string) ($data['engine_no'] ?? ''),
            'last_service_date' => self::tgl($data['last_service_date'] ?? ''),
            'dead_line' => self::tgl($data['dead_line'] ?? ''), 'dead_time' => (string) ($data['dead_time'] ?? ''),
            'mileage' => (string) ($data['mileage'] ?? ''), 'no_pol' => strtoupper((string) ($data['licence_plate'] ?? '')),
            'type' => (string) ($data['vehicle_type'] ?? ''), 'storing' => ($data['storing'] ?? 'N') === 'Y' ? 'Y' : 'N',
            'date_open_wo' => $date_open_wo, 'clockin' => (string) ($data['clockin'] ?? ''), 'status' => 'N',
            'remarks' => (string) ($data['remark'] ?? ''),
            'free_service' => ($data['free_service'] ?? 'N') === 'Y' ? 'Y' : 'N',
            'status_service' => in_array($data['status_service'] ?? '', array('C', 'I', 'W', 'GW'), true) ? $data['status_service'] : 'C',
            'pembuat' => (string) $data['pembuat'],
        );
        $this->db->insert('tbl_after_sales', $row);
        return $this->db->affected_rows();
    }

    function updateAppointment($data)
    {
        $id = (int) $data['id'];
        $lama = $this->get($id);
        if ( ! $lama || $lama->status !== 'N') return 0;
        $row = array(
            'sa_name' => (string) ($data['sa_name'] ?? $lama->sa_name),
            'customer_complain' => (string) ($data['customer_complain'] ?? ''),
            'vin' => (string) ($data['vin'] ?? ''), 'engine_no' => (string) ($data['engine_no'] ?? ''),
            'last_service_date' => self::tgl($data['last_service_date'] ?? ''),
            'dead_line' => self::tgl($data['dead_line'] ?? ''), 'dead_time' => (string) ($data['dead_time'] ?? ''),
            'mileage' => (string) ($data['mileage'] ?? ''), 'no_pol' => strtoupper((string) ($data['licence_plate'] ?? '')),
            'type' => (string) ($data['vehicle_type'] ?? ''), 'storing' => ($data['storing'] ?? 'N') === 'Y' ? 'Y' : 'N',
            'date_open_wo' => self::tgl($data['date_open_wo'] ?? '') ?: $lama->date_open_wo, 'clockin' => (string) ($data['clockin'] ?? ''),
            'remarks' => (string) ($data['remark'] ?? ''),
            'free_service' => ($data['free_service'] ?? 'N') === 'Y' ? 'Y' : 'N',
            'status_service' => in_array($data['status_service'] ?? '', array('C', 'I', 'W', 'GW'), true) ? $data['status_service'] : $lama->status_service,
        );
        if ( ! empty($data['customer'])) { $k = explode('|', (string) $data['customer']); $row['customer'] = $k[0]; if (isset($k[1])) $row['customer_name'] = $k[1]; }
        $this->db->where('id', $id)->update('tbl_after_sales', $row);
        return $this->db->affected_rows() >= 0 ? 1 : 0;
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
        $sql = "DELETE FROM tbl_after_sales WHERE id='".$this->db->escape_str((string) $id)."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}

/* End of file Mod_service_appointment.php */