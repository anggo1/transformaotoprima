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
        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ( ! empty($_POST['search']['value'])) // if datatable send POST for search
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
        $term = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
        $this->_get_datatables_query($term);
        if (isset($_POST['length']) && $_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $term = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
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
        
        // tanggal dd-mm-yyyy -> yyyy-mm-dd (aman dari SQL injection)
        $starDate = tgl_sql($tgl_awal) ?: date('Y-m-01');
        $endDate  = tgl_sql($tgl_akhir) ?: date('Y-m-d');

        $this->db->from('tbl_acc_jurnal_umum')
            ->where('tgl_jurnal >=', $starDate)->where('tgl_jurnal <=', $endDate)->where('status', 'Y');
        if ( ! empty($no_ref)) $this->db->where('no_ref', $no_ref);
        // urut per no_ref agar nomor baris (row_no) per jurnal benar (tanpa window function)
        $rows = $this->db->order_by('no_ref ASC, id_jurnal ASC')->get()->result();
        $n = array();
        foreach ($rows as $r) {
            $n[$r->no_ref] = isset($n[$r->no_ref]) ? $n[$r->no_ref] + 1 : 1;
            $r->row_no = $n[$r->no_ref];
        }
        usort($rows, function ($a, $b) { return (int) $a->id_jurnal - (int) $b->id_jurnal; });
        return $rows;
    }
    function select_by_id_jurnal($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_acc_jurnal_umum');
        $this->db->where('tbl_acc_jurnal_umum.no_jurnal', $id);
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
        $sekarang = date("Y-m-d H:i:s");
        $tgl      = tgl_sql(isset($data['tgl_jurnal']) ? $data['tgl_jurnal'] : '') ?: date('Y-m-d');
        $kode     = trim((string) $data['kode_akun']);

        // nomor jurnal: kode akun + 5 digit urut
        $row = $this->db->select('no_jurnal')->like('no_jurnal', $kode, 'after')
            ->order_by('LENGTH(no_jurnal) DESC, no_jurnal DESC', '', false)->limit(1)->get('tbl_acc_jurnal_umum')->row();
        $noOrder = 0;
        if ($row && ctype_digit(substr($row->no_jurnal, strlen($kode)))) $noOrder = (int) substr($row->no_jurnal, strlen($kode));
        $no_jurnal = $kode . sprintf("%05s", $noOrder + 1);

        $this->db->insert('tbl_acc_jurnal_umum', array(
            'kode_awal'   => '',
            'no_jurnal'   => $no_jurnal,
            'tgl_jurnal'  => $tgl,
            'no_ref'      => (string) (isset($data['no_ref']) ? $data['no_ref'] : ''),
            'no_bukti'    => (string) (isset($data['no_bukti']) ? $data['no_bukti'] : ''),
            'kode_akun'   => $kode,
            'nama_akun'   => (string) (isset($data['nama_akun']) ? $data['nama_akun'] : ''),
            'kelompok'    => (string) (isset($data['kelompok']) ? $data['kelompok'] : ''),
            'type_akun'   => (string) (isset($data['type_akun']) ? $data['type_akun'] : ''),
            'jenis_beban' => (string) (isset($data['jenis_beban']) ? $data['jenis_beban'] : ''),
            'keterangan'  => (string) (isset($data['keterangan']) ? $data['keterangan'] : ''),
            'debit'       => to_number(isset($data['debit']) ? $data['debit'] : 0),
            'kredit'      => to_number(isset($data['kredit']) ? $data['kredit'] : 0),
            'user'        => (string) $this->session->userdata('full_name'),
            'tgl_insert'  => $sekarang,
            'status'      => 'N',
        ));
        return $this->db->affected_rows();
    }
    /** draft jurnal (status N) milik user yang sedang login */
    public function select_detail()
    {
        return $this->db->where('status', 'N')->where('user', (string) $this->session->userdata('full_name'))
            ->order_by('id_jurnal', 'ASC')->get('tbl_acc_jurnal_umum')->result();
    }
    public function select_detail_edit($no_ref)
    {
        return $this->db->group_start()->where('no_ref', (string) $no_ref)->or_group_start()
            ->where('status', 'N')->where('user', (string) $this->session->userdata('full_name'))->group_end()->group_end()
            ->order_by('id_jurnal', 'ASC')->get('tbl_acc_jurnal_umum')->result();
    }
    /** total debit/kredit draft milik user */
    public function total_draft()
    {
        return $this->db->select('COUNT(*) AS n, SUM(debit) AS d, SUM(kredit) AS k, MIN(tgl_jurnal) AS t1, MAX(tgl_jurnal) AS t2', false)
            ->where('status', 'N')->where('user', (string) $this->session->userdata('full_name'))
            ->get('tbl_acc_jurnal_umum')->row();
    }
	public function select_kodeRef($kode) {
        $query= $this->db->get_where('tbl_acc_jurnal_umum',array('no_ref'=>$kode));
        return $query;
	}
    function generate_jurnal($kode_awal,$no_ref)
    {
        $this->db->where('status', 'N')->where('user', (string) $this->session->userdata('full_name'))
            ->update('tbl_acc_jurnal_umum', array('kode_awal' => (string) $kode_awal, 'no_ref' => (string) $no_ref, 'status' => 'Y'));
		return $this->db->affected_rows();
    }
    public function cetak_jurnal($no_ref)
    {
        return $this->db->order_by('id_jurnal', 'ASC')->get_where('tbl_acc_jurnal_umum', array('no_ref' => (string) $no_ref))->result();
    }
    function updateJurnal($data)
    {
        $this->db->where('no_jurnal', (string) $data['no_jurnal'])->update('tbl_acc_jurnal_umum', array(
            'no_bukti'   => (string) (isset($data['no_bukti']) ? $data['no_bukti'] : ''),
            'kode_akun'  => (string) $data['kode_akun'],
            'nama_akun'  => (string) (isset($data['nama_akun']) ? $data['nama_akun'] : ''),
            'keterangan' => (string) $data['keterangan'],
            'debit'      => to_number(isset($data['debit']) ? $data['debit'] : 0),
            'kredit'     => to_number(isset($data['kredit']) ? $data['kredit'] : 0),
            'user'       => (string) $this->session->userdata('full_name'),
            'tgl_insert' => date('Y-m-d H:i:s'),
        ));
		return $this->db->affected_rows();
    }

    /** tanggal jurnal untuk cek tutup buku */
    function tgl_by_ref($no_ref)
    {
        $r = $this->db->select('tgl_jurnal')->get_where('tbl_acc_jurnal_umum', array('no_ref' => (string) $no_ref), 1)->row();
        return $r ? $r->tgl_jurnal : null;
    }
    function tgl_by_field($field, $val)
    {
        $r = $this->db->select('tgl_jurnal')->get_where('tbl_acc_jurnal_umum', array($field => (string) $val), 1)->row();
        return $r ? $r->tgl_jurnal : null;
    }

    function deleteJurnal($no_ref)
    {
        $this->db->where('no_ref', (string) $no_ref)->delete('tbl_acc_jurnal_umum');
		return $this->db->affected_rows();
    }
    function deleteJurnal_detail($id)
    {
        $this->db->where('id_jurnal', (int) $id)->delete('tbl_acc_jurnal_umum');
		return $this->db->affected_rows();
    }
}

/* End of file Mod_pegawai.php */