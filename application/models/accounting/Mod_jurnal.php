<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_jurnal extends CI_Model
{

    var $table = 'tbl_acc_jurnal_umum';
    var $column_search = array('no_jurnal','tgl_jurnal','no_bukti','kode_akun','nama_akun','keterangan','debit','kredit');
    var $column_order = array('no_jurnal','tgl_jurnal','no_bukti','kode_akun','nama_akun','keterangan','debit','kredit');
    var $order = array('tgl_insert' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query($term = '')
    {

        $this->db->select('*');
        $this->db->from('tbl_acc_jurnal_umum');
        $this->db->where('status','Y');
        $this->db->where('tgl_jurnal BETWEEN "'.$starDate.'"AND"'.$endDate.'"');
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

        $this->db->from('tbl_acc_jurnal_umum');
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
        $this->db->select('tbl_acc_jurnal_umum');
        //$this->db->join('tbl_menu b','a.id_menu=b.id_menu');
        return $this->db->get('tbl_acc_jurnal_umum');
    }
    function select_jurnal($tgl_awal,$tgl_akhir,$no_ref)
    {
        
        $tgl1 = explode('-', $tgl_awal);
        $starDate = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
        
        $tgl1 = explode('-', $tgl_akhir);
        $endDate = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
        
        if(empty($no_ref)){
        $sql = "SELECT ROW_NUMBER() OVER(PARTITION BY no_ref ORDER BY id_jurnal) as row_no,
        tbl_acc_jurnal_umum.* FROM tbl_acc_jurnal_umum WHERE tgl_jurnal BETWEEN '$starDate' AND '$endDate' AND status='Y' ORDER BY id_jurnal ASC ";
        }
        if(!empty($no_ref)){
            $sql = "SELECT ROW_NUMBER() OVER(PARTITION BY no_ref ORDER BY id_jurnal) as row_no,
            tbl_acc_jurnal_umum.* FROM tbl_acc_jurnal_umum WHERE tgl_jurnal BETWEEN '$starDate' AND '$endDate' AND status='Y' AND no_ref='$no_ref' ORDER BY id_jurnal ASC ";
            }
        $data = $this->db->query($sql);
        return $data->result();
    }
    function select_by_id_jurnal($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_acc_jurnal_umum');
        $this->db->where('tbl_acc_jurnal_umum.no_jurnal=',$id);
        $data = $this->db->get();
        return $data->result();
    }
    function select_ref()
    {
        $sql = " SELECT * FROM tbl_acc_ref";

        $data = $this->db->query($sql);

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
    public function select_akun()
    {
        $sql = " SELECT * FROM tbl_acc_akun";

        $data = $this->db->query($sql);

        return $data->result();
    }
    function view_sparepart($id)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.kode_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c','c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d','d.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e','e.id_kelompok=a.kelompok','left');
        $this->db->join('tbl_wh_supplier as f','f.id_supplier=a.supplier','left');
        $this->db->where('a.id_barang',$id);

        $data = $this->db->get();

        return $data->result();
    }
    function cetak_sparepart()
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.kode_sup,f.nama_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c','c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d','d.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e','e.id_kelompok=a.kelompok','left');
        $this->db->join('tbl_wh_supplier as f','f.id_supplier=a.supplier','left');

        $data = $this->db->get();

        return $data->result();
    }
    function insertJurnal($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $sekarang= date("Y-m-d h:i:s");
        $date = $data['tgl_jurnal'];
        $tgl1 = explode('-', $date);
        $tgl_cuti = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
		$beaDebit=$data['debit'];
		$esDebit =str_replace(",","", $beaDebit);
		$beakredit=$data['kredit'];
		$esKredit =str_replace(",","", $beakredit);
        
        $kode = $data['kode_akun'];
        $ci_kons = get_instance();
        $query = "SELECT MAX(CAST(SUBSTRING(no_jurnal, 4, length(no_jurnal) - 1) AS UNSIGNED)) AS maxKode FROM tbl_acc_jurnal_umum WHERE no_jurnal LIKE '%$kode%'";
        $hasil = $ci_kons->db->query($query)->row_array();
        $noOrder = $hasil['maxKode'];
        //$noUrut = (int)substr($noOrder, 4, 3);
        $noOrder++;
        $tahun = substr($date, 0, 2);
        $bulan = substr($date, 2, 2);
        $no_jurnal  = $kode.sprintf("%05s", $noOrder);

        $sql = "INSERT INTO tbl_acc_jurnal_umum SET
        no_jurnal       ='".$no_jurnal."',
        tgl_jurnal      ='".$tgl_cuti."',
        no_ref          ='".$data['no_ref']."',
        kode_akun       ='".$data['kode_akun']."',
        nama_akun       ='".$data['nama_akun']."',
        kelompok        ='".$data['kelompok']."',
        type_akun       ='".$data['type_akun']."',
        jenis_beban     ='".$data['jenis_beban']."',
        keterangan      ='".$data['keterangan']."',
        debit           ='".$esDebit."',
        kredit          ='".$esKredit."',
        user            ='".$data['user']."',
        tgl_insert      ='".$sekarang."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    public function select_detail()
    {
        $sql = " SELECT * FROM tbl_acc_jurnal_umum WHERE status = 'N'";

        $data = $this->db->query($sql);

        return $data->result();
    }    
    public function select_detail_edit($no_ref)
    {
        $sql = " SELECT * FROM tbl_acc_jurnal_umum WHERE no_ref= '$no_ref' OR status = 'N' ";

        $data = $this->db->query($sql);

        return $data->result();
    }
	public function select_kodeRef($kode) {
        $query= $this->db->get_where('tbl_acc_jurnal_umum',array('no_ref'=>$kode));
        return $query;
	}
    function generate_jurnal($kode_awal,$no_ref)
    {
        $sql = "UPDATE tbl_acc_jurnal_umum SET
        kode_awal       ='".$kode_awal."',
        no_ref          ='".$no_ref."',
        status          ='Y'
        WHERE status    ='N'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    public function cetak_jurnal($no_ref)
    {
        $sql = "SELECT * FROM tbl_acc_jurnal_umum WHERE no_ref='$no_ref'";

        $data = $this->db->query($sql);

        return $data->result();
    }
    function updateJurnal($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $sekarang= date("Y-m-d h:i:s");
		$beaDebit=$data['debit'];
		$esDebit =str_replace(",","", $beaDebit);
		$beakredit=$data['kredit'];
		$esKredit =str_replace(",","", $beakredit);
        $sql = "UPDATE tbl_acc_jurnal_umum SET
        no_bukti        ='".$data['no_bukti']."',
        kode_akun       ='".$data['kode_akun']."',
        nama_akun       ='".$data['nama_akun']."',
        keterangan      ='".$data['keterangan']."',
        debit           ='".$esDebit."',
        kredit          ='".$esKredit."',
        user            ='".$data['user']."',
        tgl_insert      ='".$sekarang."'
        WHERE no_jurnal='".$data['no_jurnal']."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }

    function deleteJurnal($no_ref)
    {
        $sql = "DELETE FROM tbl_acc_jurnal_umum WHERE no_ref='{$no_ref}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
    function deleteJurnal_detail($id)
    {
        $sql = "DELETE FROM tbl_acc_jurnal_umum WHERE id_jurnal='{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
    }
}

/* End of file Mod_pegawai.php */