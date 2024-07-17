<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_stokopname extends CI_Model
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

        $this->db->select('a.*,b.kategori,c.kode_satuan,c.satuan,d.type,e.kelompok');
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
    function select_part($sup)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type,e.kelompok');
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
        $this->db->select('a.*,b.kategori,c.satuan,d.type,e.kelompok,f.nama_sup,f.kode_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.kode_sup=a.kode_sup', 'left');
        $this->db->where('a.id_barang', $id);
        return $this->db->get('tbl_wh_barang')->row();
    }
    public function select_supplier()
    {
        $sql = " SELECT * FROM tbl_wh_supplier";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function get_kota()
    {
        $sql = " SELECT * FROM tbl_kota";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function select_kelompok()
    {
        $sql = " SELECT * FROM tbl_wh_kelompok";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function deleteDetail_so($id)
    {
        $sql = "DELETE FROM tbl_wh_detail_so WHERE id='" . $id . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    public function insertDetailSO($data)
    {
        $datenow = date("Y-m-d");
        $sql = "INSERT INTO tbl_wh_detail_so SET
            id       ='',
            no_transaksi           ='" . $data['id_po'] . "',
            no_part         ='" . $data['no_part'] . "',
            nama_part       ='" . $data['nama_part'] . "',
            jumlah     ='" . $data['stok'] . "'";
        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    function update_detailSO($id,$jml_part)
		{			
		$jml =str_replace(" ","", $jml_part);
			$sql_update = "UPDATE tbl_wh_detail_opname SET stok_fisik ='$jml' WHERE id ='{$id}'"; $this->db->query($sql_update);
			
		return $this->db->affected_rows();
			//return $data->row();
		}
    public function insertOpname($data)
        {
            $date2 = $data['tgl_opname'];
            $tgl2 = explode('-',$date2);
            $tgl_opname = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
            $idUpdate = $data['id_opname'];
            $data_kelompok=$data['kelompok'];
            $kl = explode('|',$data_kelompok);
            $id_kelompok = $kl[0];
            $nama_kelompok = $kl[1];
            $keterangan=$data['keterangan'];
            $pembuat=$data['user'];
            $kode='SO';
    
            $sql_update = "INSERT INTO tbl_wh_opname SET
            id_opname   ='$idUpdate',
            tgl_opname  ='$tgl_opname',
            id_kelompok ='$id_kelompok',
            kelompok    ='$nama_kelompok',
            keterangan  ='$keterangan',
            status      ='N',
            pembuat ='$pembuat' "; $this->db->query($sql_update);

            $query=$this->db->query("SELECT a.no_part,a.nama_part,a.lokasi,a.stok,b.satuan,c.type,d.kelompok
            FROM tbl_wh_barang AS a 
            LEFT JOIN  tbl_wh_satuan AS b ON b.id_satuan=a.satuan
            LEFT JOIN  tbl_wh_type_mesin AS c ON c.id_type=a.type
            LEFT JOIN  tbl_wh_kelompok AS d ON d.id_kelompok=a.kelompok
            WHERE a.kelompok = '{$id_kelompok}'")->result();
    
            $data = array();
            foreach($query as $key=>$value){ 
                $data[]  = array(
                'id_opname'=>$idUpdate,
                'kelompok'=>$value->kelompok,
                'type'=>$value->type,
                'lokasi'=>$value->lokasi,
                'no_part'=>$value->no_part,
                'nama_part'=>$value->nama_part,
                'satuan'=>$value->satuan,
                'stok_lama'=>$value->stok,
                'stok_fisik'=>'0',
            );
        }
            $this->db->insert_batch('tbl_wh_detail_opname', $data);
                return $this->db->affected_rows();
        }
    public function select_by_id($id)
    {
        $sql = "SELECT * FROM tbl_wh_po 
        LEFT JOIN tbl_wh_supplier ON tbl_wh_supplier.kode_sup=tbl_wh_po.supplier
        WHERE id_po ='{$id}'";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    public function select_part_group($id,$kode_lok)
    {
        if($kode_lok =='JKT'){
            $this->db->select('a.no_part,a.nama_part,a.nama_part_e,a.lok_jkt AS lokasi_part,a.stok_jkt AS stok_barang,b.kategori,c.satuan,d.type,e.kelompok,f.nama_sup,f.kode_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.kode_sup=a.kode_sup', 'left');
        $this->db->where('a.kelompok', $id);
        $data = $this->db->get();
        return $data->result();
        }
        if($kode_lok =='CBT'){
            $this->db->select('a.no_part,a.nama_part,a.nama_part_e,a.lok_cbt AS lokasi_part,a.stok_cbt AS stok_barang,b.kategori,c.satuan,d.type,e.kelompok,f.nama_sup,f.kode_sup');
            $this->db->from('tbl_wh_barang as a');
            $this->db->join('tbl_wh_kategori as b', 'b.kategori=a.kategori', 'left');
            $this->db->join('tbl_wh_satuan as c', 'c.satuan=a.satuan', 'left');
            $this->db->join('tbl_wh_type_mesin as d', 'd.type=a.type', 'left');
            $this->db->join('tbl_wh_kelompok as e', 'e.kelompok=a.kelompok', 'left');
            $this->db->join('tbl_wh_supplier as f', 'f.kode_sup=a.kode_sup', 'left');
            $this->db->where('a.kelompok', $id);
            $data = $this->db->get();
            return $data->result();
        }
        if($kode_lok =='SBY'){
        $this->db->select('a.no_part,a.nama_part,a.nama_part_e,a.lok_sby AS lokasi_part,a.stok_sby AS stok_barang,b.kategori,c.satuan,d.type,e.kelompok,f.nama_sup,f.kode_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.kode_sup=a.kode_sup', 'left');
        $this->db->where('a.kelompok', $id);
        $data = $this->db->get();
        return $data->result();
        }

    }
    public function select_part_group_cabang($id,$kode_lok)
    {
        if($kode_lok =='JKT'){
            $this->db->select('a.no_part,a.nama_part,a.nama_part_e,a.lok_jkt AS lokasi_part,a.stok_jkt AS stok_barang,b.kategori,c.satuan,d.type,e.kelompok,f.nama_sup,f.kode_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.kode_sup=a.kode_sup', 'left');
        $this->db->where('a.kelompok', $id);
        }
        if($kode_lok =='CBT'){
            $this->db->select('a.no_part,a.nama_part,a.nama_part_e,a.lok_cbt AS lokasi_part,a.stok_cbt AS stok_barang,b.kategori,c.satuan,d.type,e.kelompok,f.nama_sup,f.kode_sup');
            $this->db->from('tbl_wh_barang as a');
            $this->db->join('tbl_wh_kategori as b', 'b.kategori=a.kategori', 'left');
            $this->db->join('tbl_wh_satuan as c', 'c.satuan=a.satuan', 'left');
            $this->db->join('tbl_wh_type_mesin as d', 'd.type=a.type', 'left');
            $this->db->join('tbl_wh_kelompok as e', 'e.kelompok=a.kelompok', 'left');
            $this->db->join('tbl_wh_supplier as f', 'f.kode_sup=a.kode_sup', 'left');
        }
        if($kode_lok =='SBY'){
        $this->db->select('a.no_part,a.nama_part,a.nama_part_e,a.lok_sby AS lokasi_part,a.stok_sby AS stok_barang,b.kategori,c.satuan,d.type,e.kelompok,f.nama_sup,f.kode_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.kode_sup=a.kode_sup', 'left');
        $this->db->where('a.kelompok', $id);
        }
        $data = $this->db->get();
        return $data->result();
    }
    public function select_part_group_update($id)
    {

        $this->db->select('*');
        $this->db->from('tbl_wh_detail_opname');
        $this->db->where('id_opname', $id);
        $data = $this->db->get();
        return $data->result();
    }
    public function select_detail($id)
    {

        $sql = "SELECT a.*,b.nama_part,c.satuan 
        FROM tbl_wh_detail_so AS a
        LEFT JOIN tbl_wh_barang AS b ON b.no_part = a.no_part
        LEFT JOIN tbl_wh_satuan AS c ON c.id_satuan = b.satuan
        WHERE a.no_transaksi ='{$id}' ORDER BY a.id DESC";

        $data = $this->db->query($sql);
        return $data->result();
        //return $data->row();
    }
    function updatePo($a, $b, $c, $d)
    {
        $sql = "UPDATE tbl_wh_po SET
        t_ppn       ='$a',
        sub_total   ='$b',
        grand_total ='$c'
        WHERE id_po ='" . $data['id_po'] . "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
}