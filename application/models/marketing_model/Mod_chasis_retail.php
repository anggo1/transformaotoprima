<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_chasis_retail extends CI_Model
{
	var $table = 'tbl_mk_chasis_retail';
	var $column_search = array('tgl_masuk', 'retail', 'type', 'no_rangka', 'no_mesin', 'sales', 'gesekan', 'thn_produksi', 'nama_customer', 'pengiriman');
	var $column_order = array('null', 'tgl_masuk', 'retail', 'type', 'no_rangka', 'no_mesin', 'sales', 'gesekan', 'thn_produksi', 'nama_customer', 'pengiriman');
	var $order = array('id_chasis' => 'desc');
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$this->db->select('*', true);
		$this->db->from('tbl_mk_chasis_retail');
		$this->db->where('status !=', 'Y');
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
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all()
	{
		$this->db->from('tbl_mk_chasis');
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
	function select_by_level($idlevel, $id_sub)
	{
		$this->db->select('*');
		$this->db->from('tbl_akses_submenu');
		//$this->db->join('tbl_akses_submenu','tbl_akses_submenu.id_submenu=tbl_akses_menu.id_menu','inner');
		$this->db->where('tbl_akses_submenu.id_level=', $idlevel);
		$this->db->where('tbl_akses_submenu.id_submenu=', $id_sub);
		$data = $this->db->get();
		return $data->result();
	}
	public function select_chasis()
	{
		$this->db->select('*');
		$this->db->from('tbl_mk_chasis');
		$this->db->where('jumlah >0');
		$data = $this->db->get();
		return $data->result();
	}
	function select_by_id_chasis($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_mk_chasis_retail');
		$this->db->where('tbl_mk_chasis_retail.id_chasis=', $id);
		$data = $this->db->get();
		return $data->result();
	}
	function select_by_id_chasis_spk($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_mk_chasis_retail');
		$this->db->where('tbl_mk_chasis_retail.id_chasis=', $id);
		$data = $this->db->get();
		return $data->result();
	}
	function insertChasis($data)
	{
		$hrg	= $data['harga_retail'];
		$harga = str_replace(",", "", $hrg);
		$tgl_input = date('Y-m-d H:i:s');
		$tgl_masuk = $data['tgl_masuk'];
		$tgl1 = explode('-', $tgl_masuk);
		$tgl_masuknya = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
		$sql = "INSERT INTO tbl_mk_chasis_retail SET
        tgl_masuk		='" . $tgl_masuknya . "',
        chasis_id 		='" . $data['chasis_id'] . "',
        retail 			='" . $data['retail'] . "',
        kode_cus 		='" . $data['kode_cus'] . "',
        nama_pemesan 		='" . $data['nama_pemesan'] . "',
        alamat_pemesan 		='" . $data['alamat_pemesan'] . "',
        no_npwp 			='" . $data['no_npwp'] . "',
        alamat_npwp 		='" . $data['alamat_npwp'] . "',
        telp_pemesan 		='" . $data['telp_pemesan'] . "',
        contact_person 		='" . $data['contact_person'] . "',
        telp_contact_person ='" . $data['telp_contact_person'] . "',
        nama_bpkb 			='" . $data['nama_bpkb'] . "',
        alamat_faktur 			='" . $data['alamat_faktur'] . "',
        no_ktp 			='" . $data['no_ktp'] . "',
        nama_npwp 		='" . $data['nama_npwp'] . "',
        type_body 			='" . $data['type_body'] . "',
        no_rangka 		='" . $data['no_rangka'] . "',
        no_mesin  		='" . $data['no_mesin'] . "',
        sales			='" . $data['sales'] . "',
        gesekan    		='" . $data['gesekan'] . "',
        thn_produksi	='" . $data['thn_produksi'] . "',
        warna			='" . $data['warna'] . "',
        pengiriman      ='" . $data['pengiriman'] . "',
        status_chasis  	='S',
        harga_retail      ='" . $harga . "',
        jumlah      ='" . $data['jumlah'] . "',
        tgl_input  		='" . $tgl_input . "',
        user  			='" . $data['user'] . "',
        keterangan      ='" . $data['keterangan'] . "'
		";

		$this->db->query($sql);
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah - " . $data['jumlah'] . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);

		return $this->db->affected_rows();
	}

	function updateChasis($data)
	{
		$cari = "SELECT jumlah FROM tbl_mk_chasis_retail WHERE id_chasis = '" . $data['id_chasis'] . "'";
		$h = $this->db->query($cari)->row();
		$dataJumlah = $h->jumlah;
		$hsl = $data['jumlah'];
		if ($hsl > $dataJumlah) {
			$hasil = $dataJumlah - $hsl;
			$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah + " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
			$this->db->query($sql2);
		}
		if ($hsl < $dataJumlah) {
			$hasil = $hsl - $dataJumlah;
			$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah - " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
			$this->db->query($sql2);
		}

		$hrg	= $data['harga_retail'];
		$harga = str_replace(",", "", $hrg);
		$tgl_input = date('Y-m-d H:i:s');
		$tgl_masuk = $data['tgl_masuk'];
		$tgl1 = explode('-', $tgl_masuk);
		$tgl_masuknya = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
		$sql = "UPDATE tbl_mk_chasis_retail SET
        retail 			='" . $data['retail'] . "',
        kode_cus 		='" . $data['kode_cus'] . "',
        nama_pemesan 		='" . $data['nama_pemesan'] . "',
        alamat_pemesan 		='" . $data['alamat_pemesan'] . "',
        no_npwp 			='" . $data['no_npwp'] . "',
        alamat_npwp 		='" . $data['alamat_npwp'] . "',
        telp_pemesan 		='" . $data['telp_pemesan'] . "',
        contact_person 		='" . $data['contact_person'] . "',
        telp_contact_person ='" . $data['telp_contact_person'] . "',
        nama_bpkb 			='" . $data['nama_bpkb'] . "',
        alamat_faktur 			='" . $data['alamat_faktur'] . "',
        no_ktp 			='" . $data['no_ktp'] . "',
        nama_npwp 		='" . $data['nama_npwp'] . "',
        type_body 			='" . $data['type_body'] . "',
        no_rangka 		='" . $data['no_rangka'] . "',
        no_mesin  		='" . $data['no_mesin'] . "',
        sales			='" . $data['sales'] . "',
        gesekan    		='" . $data['gesekan'] . "',
        thn_produksi	='" . $data['thn_produksi'] . "',
        warna			='" . $data['warna'] . "',
        pengiriman      ='" . $data['pengiriman'] . "',
        harga_retail      ='" . $harga . "',
        jumlah      	='" . $data['jumlah'] . "',
        keterangan      ='" . $data['keterangan'] . "'
        WHERE id_chasis='" . $data['id_chasis'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function Proses_inputSpk($data)
	{
		$cari = "SELECT jumlah FROM tbl_mk_chasis_retail WHERE id_chasis = '" . $data['id_chasis'] . "'";
		$h = $this->db->query($cari)->row();
		$dataJumlah = $h->jumlah;
		$hsl = $data['jml_unit'];
		if ($hsl > $dataJumlah) {
			$hasil = $dataJumlah - $hsl ;
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah + " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);
		}
		if ($hsl < $dataJumlah) {
			$hasil = $hsl - $dataJumlah ;
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah - " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);
		}

        $sekarang = date("Y-m");
        

            $hrg_ofr = $data['hrg_on_the_road'];
            $ofr = str_replace(",", "", $hrg_ofr);
            $bbn = $data['biaya_bbn'];
            $h_bbn = str_replace(",", "", $bbn);
            $hrg_otr = $data['hrg_on_the_road'];
            $otr = str_replace(",", "", $hrg_otr);

            $t_1 = $data['hrg_tambahan_1'];
            $tambah1 = str_replace(",", "", $t_1);

            $t_2 = $data['hrg_tambahan_2'];
            $tambah2 = str_replace(",", "", $t_2);

            $t_3 = $data['hrg_tambahan_3'];
            $tambah3 = str_replace(",", "", $t_3);

            $t_4 = $data['hrg_tambahan_4'];
            $tambah4 = str_replace(",", "", $t_4);

            $hjp = $data['hrg_jual_perunit'];
            $perunit = str_replace(",", "", $hjp);

            $thp = $data['total_harga_jual'];
            $total_harga = str_replace(",", "", $thp);

            $no_spk1 = $data['no_ref'];
            $ciri = $data['kode'];
            if (empty($ciri)) {
                $no_spk = $no_spk1;
            } else {
                $no_spk = $no_spk1 . '-' . $ciri;
            }
		$sql = "INSERT INTO tbl_mk_spk SET
            no_urut       		='" . $data['no_urut'] . "',
            no_spk   			='" . $no_spk . "',
            tgl_spk         	='" . date("Y-m-d") . "',
            nama_pemesan        ='" . $data['nama_pemesan'] . "',
            id_chasis       	='" . $data['id_chasis'] . "',
            alamat_pemesan      ='" . $data['alamat_pemesan'] . "',
            telp_pemesan        ='" . $data['telp_pemesan'] . "',
            faktur_pajak        ='" . $data['faktur_pajak'] . "',
            npwp_pemesan    	='" . $data['npwp_pemesan'] . "',
			nama_npwp_pemesan   ='" . $data['nama_npwp_pemesan'] . "',
			alamat_npwp    		='" . $data['alamat_npwp'] . "',
			contact_person    	='" . $data['contact_person'] . "',
			telp_contact_person ='" . $data['telp_contact_person'] . "',
			nama_bpkb    		='" . $data['nama_bpkb'] . "',
			no_ktp    			='" . $data['no_ktp'] . "',
			alamat_faktur    	='" . $data['alamat_faktur'] . "',
			plat_kendaraan    	='" . $data['plat_kendaraan'] . "',
			type_body    		='" . $data['type_body'] . "',
			jumlah    			='" . $data['jml_unit'] . "',
			kategori    		='" . $data['kategori'] . "',
			type_kendaraan    	='" . $data['type_kendaraan'] . "',
			thn_produksi   		='" . $data['thn_produksi'] . "',
			warna	    		='" . $data['warna'] . "',
			harga_retail    	='" . $ofr . "',
			biaya_bbn    		='" . $h_bbn . "',
			hrg_on_the_road    	='" . $otr . "',
			tambahan_1    		='" . $data['tambahan_1'] . "',
			hrg_tambahan_1    	='" . $tambah1 . "',
			tambahan_2    		='" . $data['tambahan_2'] . "',
			hrg_tambahan_2    	='" . $tambah2 . "',
			tambahan_3    		='" . $data['tambahan_3'] . "',
			hrg_tambahan_3    	='" . $tambah3 . "',
			tambahan_4    		='" . $data['tambahan_4'] . "',
			hrg_tambahan_4    	='" . $tambah4 . "',
			hrg_jual_perunit    ='" . $perunit . "',
			total_hrg_jual    	='" . $total_harga . "',
			user    			='" . $data['user'] . "',
			status    ='P'";
		$this->db->query($sql);
		
            $result = $this->db->affected_rows();
            $this->db->where('id_chasis', $data['id_chasis'])->update('tbl_mk_chasis_retail', array('status' => 'P'));
            $data     = $this->input->post();

		return $this->db->affected_rows();
	}
	function updateSpk($data)
	{
		$cari = "SELECT jumlah FROM tbl_mk_chasis_retail WHERE id_chasis = '" . $data['id_chasis'] . "'";
		$h = $this->db->query($cari)->row();
		$dataJumlah = $h->jumlah;
		$hsl = $data['jml_unit'];
		if ($hsl > $dataJumlah) {
			$hasil = $dataJumlah - $hsl ;
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah + " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);
		}
		if ($hsl < $dataJumlah) {
			$hasil = $hsl - $dataJumlah ;
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah - " . $hasil . " WHERE chasis_id ='" . $data['chasis_id'] . "'";
		$this->db->query($sql2);
		}
		
		 $hrg_ofr = $data['hrg_on_the_road'];
            $ofr = str_replace(",", "", $hrg_ofr);
            $bbn = $data['biaya_bbn'];
            $h_bbn = str_replace(",", "", $bbn);
            $hrg_otr = $data['hrg_on_the_road'];
            $otr = str_replace(",", "", $hrg_otr);

            $t_1 = $data['hrg_tambahan_1'];
            $tambah1 = str_replace(",", "", $t_1);

            $t_2 = $data['hrg_tambahan_2'];
            $tambah2 = str_replace(",", "", $t_2);

            $t_3 = $data['hrg_tambahan_3'];
            $tambah3 = str_replace(",", "", $t_3);

            $t_4 = $data['hrg_tambahan_4'];
            $tambah4 = str_replace(",", "", $t_4);

            $hjp = $data['hrg_jual_perunit'];
            $perunit = str_replace(",", "", $hjp);

            $thp = $data['total_harga_jual'];
            $total_harga = str_replace(",", "", $thp);

		$sql = "UPDATE tbl_mk_spk SET
            nama_pemesan        ='" . $data['nama_pemesan'] . "',
            id_chasis       	='" . $data['id_chasis'] . "',
            alamat_pemesan      ='" . $data['alamat_pemesan'] . "',
            telp_pemesan        ='" . $data['telp_pemesan'] . "',
            faktur_pajak        ='" . $data['faktur_pajak'] . "',
            npwp_pemesan    	='" . $data['npwp_pemesan'] . "',
			nama_npwp_pemesan   ='" . $data['nama_npwp_pemesan'] . "',
			alamat_npwp    		='" . $data['alamat_npwp'] . "',
			contact_person    	='" . $data['contact_person'] . "',
			telp_contact_person ='" . $data['telp_contact_person'] . "',
			nama_bpkb    		='" . $data['nama_bpkb'] . "',
			no_ktp    			='" . $data['no_ktp'] . "',
			alamat_faktur    	='" . $data['alamat_faktur'] . "',
			plat_kendaraan    	='" . $data['plat_kendaraan'] . "',
			type_body    		='" . $data['type_body'] . "',
			jumlah    			='" . $data['jml_unit'] . "',
			kategori    		='" . $data['kategori'] . "',
			type_kendaraan    	='" . $data['type_kendaraan'] . "',
			thn_produksi   		='" . $data['thn_produksi'] . "',
			warna	    		='" . $data['warna'] . "',
			harga_retail    	='" . $ofr . "',
			biaya_bbn    		='" . $h_bbn . "',
			hrg_on_the_road    	='" . $otr . "',
			tambahan_1    		='" . $data['tambahan_1'] . "',
			hrg_tambahan_1    	='" . $tambah1 . "',
			tambahan_2    		='" . $data['tambahan_2'] . "',
			hrg_tambahan_2    	='" . $tambah2 . "',
			tambahan_3    		='" . $data['tambahan_3'] . "',
			hrg_tambahan_3    	='" . $tambah3 . "',
			tambahan_4    		='" . $data['tambahan_4'] . "',
			hrg_tambahan_4    	='" . $tambah4 . "',
			hrg_jual_perunit    ='" . $perunit . "',
			total_hrg_jual    	='" . $total_harga . "'
        WHERE id_spk='" . $data['id_spk'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

	function get_bus($no_body)
	{
		$this->db->where('no_body', $no_body);
		return $this->db->get('tbl_mk_chasis')->row();
	}

	function deleteChasis($id, $chasis_id, $jumlah)
	{
		$sql2 = "UPDATE tbl_mk_chasis SET jumlah = jumlah + " . $jumlah . " WHERE chasis_id='{$chasis_id}'";
		$this->db->query($sql2);
		$sql1 = "DELETE FROM tbl_mk_spk WHERE id_chasis='{$id}'";
		$this->db->query($sql1);
		$sql = "DELETE FROM tbl_mk_chasis_retail WHERE id_chasis='{$id}'";
		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function get_part($id)
	{
		$this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.nama_sup,f.kode_cus');
		$this->db->from('tbl_mk_spk as a');
		$this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
		$this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
		$this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
		$this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
		$this->db->join('tbl_wh_customer as f', 'f.id_supplier=a.supplier', 'left');
		$this->db->where('a.id_part', $id);
		return $this->db->get('tbl_mk_spk')->row();
	}
	public function deleteDetail_spk($id)
	{
		$sql = "DELETE FROM tbl_mk_keterangan_spk WHERE id_ket_spk='" . $id . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	function update_remark($id, $remark)
	{
		$sql_update = "UPDATE tbl_mk_keterangan_spk SET remark ='$remark' WHERE id_detail ='{$id}'";
		$this->db->query($sql_update);
		return $this->db->affected_rows();
		//return $data->row();
	}
	function insertKeterangan($id, $no_spk, $keterangan, $id_chasis)
	{
		$sql_update = "INSERT tbl_mk_keterangan_spk SET no_urut = '$id', no_spk='$no_spk', keterangan ='$keterangan', id_chasis='$id_chasis'";
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
		$sql = "INSERT INTO tbl_mk_keterangan_spk SET
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
		$sql = "SELECT * FROM tbl_mk_spk 
        WHERE no_urut ='{$id}'";

		$data = $this->db->query($sql);
		return $data->result();
		//return $data->row();
	}
	public function select_ulang_id($id)
	{
		$sql = "SELECT * FROM tbl_mk_spk 
        WHERE id_chasis ='{$id}'";

		$data = $this->db->query($sql);
		return $data->result();
		//return $data->row();
	}
	public function select_keterangan($id)
	{
		$sql = "SELECT * FROM tbl_mk_keterangan_spk WHERE no_urut ='{$id}' ORDER BY id_ket_spk ASC";

		$data = $this->db->query($sql);
		return $data->result();
	}
	public function select_ulang_keterangan($id)
	{
		$this->db->select('a.no_urut', true);
		$this->db->select('b.*', false);
		$this->db->from('tbl_mk_spk as a');
		$this->db->join('tbl_mk_keterangan_spk as b', 'b.no_urut=a.no_urut', 'left');
		$this->db->where('a.no_urut=', $id);
		$data = $this->db->get();
		return $data->result();
	}
	function updatePo($a, $b, $c, $d)
	{
		$sql = "UPDATE tbl_wh_estimasi_penawaran SET
        t_ppn       ='$a',
        sub_total   ='$b',
        grand_total ='$c'
        WHERE id_estimasi_penawaran ='" . $data['id_estimasi_penawaran'] . "'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	public function select_customer()
	{
		$this->db->select('*');
		$this->db->from('tbl_customer');
		$data = $this->db->get();
		return $data->result();
	}
	function select_by_id_spk($id)
	{
		$this->db->select('a.*', true);
		$this->db->select('b.chasis_id', false);
		$this->db->from('tbl_mk_spk as a');
		$this->db->join('tbl_mk_chasis_retail as b', 'b.id_chasis=a.id_chasis', 'left');
		$this->db->where('a.id_chasis=', $id);
		$data = $this->db->get();
		return $data->result();
	}
}
