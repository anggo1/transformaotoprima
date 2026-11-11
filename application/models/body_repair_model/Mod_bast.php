<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_bast extends CI_Model
{
    var $table = 'tbl_wh_body';
	var $column_search = array('no_body','type','thn_rangka','thn_pembuatan','karoseri','warna','kelas','strip','keterangan'); 
	var $column_order = array('no_body','type','thn_rangka','thn_pembuatan','karoseri','warna','kelas','strip','keterangan');
	var $order = array('no_body' => 'desc'); 
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

		private function _get_datatables_query()
	{
		
		$this->db->from('tbl_wh_body');
		$i = 0;

	foreach ($this->column_search as $item) // loop column 
	{
	if($_POST['search']['value']) // if datatable send POST for search
	{

	if($i===0) // first loop
	{
	$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
	$this->db->like($item, $_POST['search']['value']);
	}
	else
	{
		$this->db->or_like($item, $_POST['search']['value']);
		$this->db->or_like($item, $_POST['search']['value']);
	} 

		if(count($this->column_search) - 1 == $i) //last loop
		$this->db->group_end(); //close bracket
	}
	$i++;
	}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
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
		$this->db->from('tbl_wh_body');
		return $this->db->count_all_results();
	}
    function get_part($id)
    {
        $this->db->select('a.*,b.kategori,c.satuan,d.type_mesin,e.kelompok,f.nama_sup');
        $this->db->from('tbl_wh_barang as a');
        $this->db->join('tbl_wh_kategori as b', 'b.id_kategori=a.kategori', 'left');
        $this->db->join('tbl_wh_satuan as c', 'c.id_satuan=a.satuan', 'left');
        $this->db->join('tbl_wh_type_mesin as d', 'd.id_type=a.type', 'left');
        $this->db->join('tbl_wh_kelompok as e', 'e.id_kelompok=a.kelompok', 'left');
        $this->db->join('tbl_wh_supplier as f', 'f.id_supplier=a.supplier', 'left');
        $this->db->where('a.id_barang', $id);
        return $this->db->get('tbl_wh_barang')->row();
    }
    public function select_supplier()
    {
        $sql = " SELECT * FROM tbl_wh_supplier";

        $data = $this->db->query($sql);

        return $data->result();
    }
    public function deleteBast($id)
    {
        $sql = "DELETE FROM tbl_br_bast WHERE id_bast='". $this->db->escape_str((string) $id). "'";

        $this->db->query($sql);

        return $this->db->affected_rows();
    }
    public function insertBast($data)
    {
        $date= date("Ymd");
        $ci = get_instance();
        $qdata = "SELECT max(id_bast) as maxKode FROM tbl_br_bast WHERE id_bast LIKE '%$date%'";
        $dataQ = $ci->db->query($qdata)->row_array();
        $noOrder = $dataQ['maxKode'];
        $noUrut = (int) substr($noOrder, 10, 3);
        $noUrut++;
        $char = "SA";
        $tahun=substr($date, 0, 4);
        $bulan=substr($date, 4, 2);
        $tgl=substr($date, 6, 2);
        $kodeBaru  = $char.$tahun.$bulan.$tgl. sprintf("%03s", $noUrut);

        $date2 = $data['tgl_bast'];
		$tgl2 = explode('-',$date2);
		$tgl_bast = $tgl2[2]."-".$tgl2[1]."-".$tgl2[0]."";
        
        $ket1=""; if (!empty($data['ket1'])){ $ket1=$data['ket1']; }else { $ket1='0'; }
        $ket2=""; if (!empty($data['ket2'])){ $ket2=$data['ket2']; }else { $ket2='0'; }
        $ket3=""; if (!empty($data['ket3'])){ $ket3=$data['ket3']; }else { $ket3='0'; }
        $ket4=""; if (!empty($data['ket4'])){ $ket4=$data['ket4']; }else { $ket4='0'; }
        $ket5=""; if (!empty($data['ket5'])){ $ket5=$data['ket5']; }else { $ket5='0'; }
        $ket6=""; if (!empty($data['ket6'])){ $ket6=$data['ket6']; }else { $ket6='0'; }
        $ket7=""; if (!empty($data['ket7'])){ $ket7=$data['ket7']; }else { $ket7='0'; }
        $ket8=""; if (!empty($data['ket8'])){ $ket8=$data['ket8']; }else { $ket8='0'; }
        $ket9=""; if (!empty($data['ket9'])){ $ket9=$data['ket9']; }else { $ket9='0'; }
        $ket10=""; if (!empty($data['ket10'])){ $ket10=$data['ket10']; }else { $ket10='0'; }
        $ket11=""; if (!empty($data['ket11'])){ $ket11=$data['ket11']; }else { $ket11='0'; }
        $ket12=""; if (!empty($data['ket12'])){ $ket12=$data['ket12']; }else { $ket12='0'; }
        $ket13=""; if (!empty($data['ket13'])){ $ket13=$data['ket13']; }else { $ket13='0'; }
        $ket14=""; if (!empty($data['ket14'])){ $ket14=$data['ket14']; }else { $ket14='0'; }
        $ket15=""; if (!empty($data['ket15'])){ $ket15=$data['ket15']; }else { $ket15='0'; }
        $ket16=""; if (!empty($data['ket16'])){ $ket16=$data['ket16']; }else { $ket16='0'; }
        $ket17=""; if (!empty($data['ket17'])){ $ket17=$data['ket17']; }else { $ket17='0'; }
        $ket18=""; if (!empty($data['ket18'])){ $ket18=$data['ket18']; }else { $ket18='0'; }
        $ket19=""; if (!empty($data['ket19'])){ $ket19=$data['ket19']; }else { $ket19='0'; }
        $ket20=""; if (!empty($data['ket20'])){ $ket20=$data['ket20']; }else { $ket20='0'; }
        $ket21=""; if (!empty($data['ket21'])){ $ket21=$data['ket21']; }else { $ket21='0'; }
        $ket22=""; if (!empty($data['ket22'])){ $ket22=$data['ket22']; }else { $ket22='0'; }
        $ket23=""; if (!empty($data['ket23'])){ $ket23=$data['ket23']; }else { $ket23='0'; }
        $ket24=""; if (!empty($data['ket24'])){ $ket24=$data['ket24']; }else { $ket24='0'; }
        $ket25=""; if (!empty($data['ket25'])){ $ket25=$data['ket25']; }else { $ket25='0'; }
        $ket26=""; if (!empty($data['ket26'])){ $ket26=$data['ket26']; }else { $ket26='0'; }
        $ket27=""; if (!empty($data['ket27'])){ $ket27=$data['ket27']; }else { $ket27='0'; }
        $ket28=""; if (!empty($data['ket28'])){ $ket28=$data['ket28']; }else { $ket28='0'; }
        $ket29=""; if (!empty($data['ket29'])){ $ket29=$data['ket29']; }else { $ket29='0'; }
        $ket30=""; if (!empty($data['ket30'])){ $ket30=$data['ket30']; }else { $ket30='0'; }
        $ket31=""; if (!empty($data['ket31'])){ $ket31=$data['ket31']; }else { $ket31='0'; }
        $ket32=""; if (!empty($data['ket32'])){ $ket32=$data['ket32']; }else { $ket32='0'; }
        $ket33=""; if (!empty($data['ket33'])){ $ket33=$data['ket33']; }else { $ket33='0'; }
        $ket34=""; if (!empty($data['ket34'])){ $ket34=$data['ket34']; }else { $ket34='0'; }
        $ket35=""; if (!empty($data['ket35'])){ $ket35=$data['ket35']; }else { $ket35='0'; }
        $ket36=""; if (!empty($data['ket36'])){ $ket36=$data['ket36']; }else { $ket36='0'; }
        $ket37=""; if (!empty($data['ket37'])){ $ket37=$data['ket37']; }else { $ket37='0'; }
        $ket38=""; if (!empty($data['ket38'])){ $ket38=$data['ket38']; }else { $ket38='0'; }
        $ket39=""; if (!empty($data['ket39'])){ $ket39=$data['ket39']; }else { $ket39='0'; }
        $ket40=""; if (!empty($data['ket40'])){ $ket40=$data['ket40']; }else { $ket40='0'; }
        $ket41=""; if (!empty($data['ket41'])){ $ket41=$data['ket41']; }else { $ket41='0'; }
        $ket42=""; if (!empty($data['ket42'])){ $ket42=$data['ket42']; }else { $ket42='0'; }
        $ket43=""; if (!empty($data['ket43'])){ $ket43=$data['ket43']; }else { $ket43='0'; }
        $ket44=""; if (!empty($data['ket44'])){ $ket44=$data['ket44']; }else { $ket44='0'; }
        $ket45=""; if (!empty($data['ket45'])){ $ket45=$data['ket45']; }else { $ket45='0'; }
        $ket46=""; if (!empty($data['ket46'])){ $ket46=$data['ket46']; }else { $ket46='0'; }
        $ket47=""; if (!empty($data['ket47'])){ $ket47=$data['ket47']; }else { $ket47='0'; }
        $ket48=""; if (!empty($data['ket48'])){ $ket48=$data['ket48']; }else { $ket48='0'; }
        $ket49=""; if (!empty($data['ket48'])){ $ket49=$data['ket49']; }else { $ket49='0'; }
        $ket50=""; if (!empty($data['ket50'])){ $ket50=$data['ket50']; }else { $ket50='0'; }
        $ket51=""; if (!empty($data['ket51'])){ $ket51=$data['ket51']; }else { $ket51='0'; }
        $ket52=""; if (!empty($data['ket52'])){ $ket52=$data['ket52']; }else { $ket52='0'; }
        $ket53=""; if (!empty($data['ket53'])){ $ket53=$data['ket53']; }else { $ket53='0'; }
        $ket54=""; if (!empty($data['ket54'])){ $ket54=$data['ket54']; }else { $ket54='0'; }
        $ket55=""; if (!empty($data['ket55'])){ $ket55=$data['ket55']; }else { $ket55='0'; }
        $ket56=""; if (!empty($data['ket56'])){ $ket56=$data['ket56']; }else { $ket56='0'; }
        $ket57=""; if (!empty($data['ket57'])){ $ket57=$data['ket57']; }else { $ket57='0'; }
        $ket58=""; if (!empty($data['ket58'])){ $ket58=$data['ket58']; }else { $ket58='0'; }
        $ket59=""; if (!empty($data['ket59'])){ $ket59=$data['ket59']; }else { $ket59='0'; }
        $ket60=""; if (!empty($data['ket60'])){ $ket60=$data['ket60']; }else { $ket60='0'; }
        $ket61=""; if (!empty($data['ket61'])){ $ket61=$data['ket61']; }else { $ket61='0'; }
        $ket62=""; if (!empty($data['ket62'])){ $ket62=$data['ket62']; }else { $ket62='0'; }
        $ket63=""; if (!empty($data['ket63'])){ $ket63=$data['ket63']; }else { $ket63='0'; }
        $ket64=""; if (!empty($data['ket64'])){ $ket64=$data['ket64']; }else { $ket64='0'; }
        $ket65=""; if (!empty($data['ket65'])){ $ket65=$data['ket65']; }else { $ket65='0'; }
        $ket66=""; if (!empty($data['ket66'])){ $ket66=$data['ket66']; }else { $ket66='0'; }
        $ket67=""; if (!empty($data['ket67'])){ $ket67=$data['ket67']; }else { $ket67='0'; }
        $ket68=""; if (!empty($data['ket68'])){ $ket68=$data['ket68']; }else { $ket68='0'; }
        $ket69=""; if (!empty($data['ket69'])){ $ket69=$data['ket69']; }else { $ket69='0'; }
        $ket70=""; if (!empty($data['ket70'])){ $ket70=$data['ket70']; }else { $ket70='0'; }
        $ket71=""; if (!empty($data['ket71'])){ $ket71=$data['ket71']; }else { $ket71='0'; }
        $ket72=""; if (!empty($data['ket72'])){ $ket72=$data['ket72']; }else { $ket72='0'; }
        $ket73=""; if (!empty($data['ket73'])){ $ket73=$data['ket73']; }else { $ket73='0'; }
        $ket74=""; if (!empty($data['ket74'])){ $ket74=$data['ket74']; }else { $ket74='0'; }
        $ket75=""; if (!empty($data['ket75'])){ $ket75=$data['ket75']; }else { $ket75='0'; }
        $ket76=""; if (!empty($data['ket76'])){ $ket76=$data['ket76']; }else { $ket76='0'; }
        $ket77=""; if (!empty($data['ket77'])){ $ket77=$data['ket77']; }else { $ket77='0'; }
        $ket78=""; if (!empty($data['ket78'])){ $ket78=$data['ket78']; }else { $ket78='0'; }
        $ket79=""; if (!empty($data['ket79'])){ $ket79=$data['ket79']; }else { $ket79='0'; }
        $ket80=""; if (!empty($data['ket80'])){ $ket80=$data['ket80']; }else { $ket80='0'; }
        $ket81=""; if (!empty($data['ket81'])){ $ket81=$data['ket81']; }else { $ket81='0'; }
        $ket82=""; if (!empty($data['ket82'])){ $ket82=$data['ket82']; }else { $ket82='0'; }
        $ket83=""; if (!empty($data['ket83'])){ $ket83=$data['ket83']; }else { $ket83='0'; }
        $ket84=""; if (!empty($data['ket84'])){ $ket84=$data['ket84']; }else { $ket84='0'; }
        $ket85=""; if (!empty($data['ket85'])){ $ket85=$data['ket85']; }else { $ket85='0'; }
        $ket86=""; if (!empty($data['ket86'])){ $ket86=$data['ket86']; }else { $ket86='0'; }
        $ket87=""; if (!empty($data['ket87'])){ $ket87=$data['ket87']; }else { $ket87='0'; }
        $ket88=""; if (!empty($data['ket88'])){ $ket88=$data['ket88']; }else { $ket88='0'; }
        $ket89=""; if (!empty($data['ket89'])){ $ket89=$data['ket89']; }else { $ket89='0'; }
        
        $sql = "INSERT INTO tbl_br_bast SET
            id_bast         ='".$this->db->escape_str((string) $kodeBaru)."',
            tgl_bast        ='".$this->db->escape_str((string) $tgl_bast)."',
            no_sj           ='".$this->db->escape_str((string) $data['no_sj'])."',
            no_body         ='".$this->db->escape_str((string) $data['no_body'])."',
            no_pol          ='".$this->db->escape_str((string) $data['no_pol'])."',
            nip_sp          ='".$this->db->escape_str((string) $data['nip_sp'])."',
            nama_sp         ='".$this->db->escape_str((string) $data['nama_sp'])."',
            keterangan      ='".$this->db->escape_str((string) $data['keterangan'])."',
            status_bus      ='".$this->db->escape_str((string) $data['status_bus'])."',
            user            ='".$this->db->escape_str((string) $data['user'])."',
            kaca_depan      ='".$this->db->escape_str((string) $ket1)."',
            kaca_belakang   ='".$this->db->escape_str((string) $ket2)."',
            kc_kanan        ='".$this->db->escape_str((string) $ket3)."',
            kc_kiri         ='".$this->db->escape_str((string) $ket4)."',
            sp_kanan        ='".$this->db->escape_str((string) $ket5)."',
            sp_kiri         ='".$this->db->escape_str((string) $ket6)."',
            sp_dalam        ='".$this->db->escape_str((string) $ket7)."',
            body_depan      ='".$this->db->escape_str((string) $ket8)."',
            bemper_depan    ='".$this->db->escape_str((string) $ket9)."',
            body_kiri       ='".$this->db->escape_str((string) $ket10)."',
            body_kanan      ='".$this->db->escape_str((string) $ket11)."',
            body_belakang   ='".$this->db->escape_str((string) $ket12)."',
            bemper_belakang ='".$this->db->escape_str((string) $ket13)."',
            pintu_dp_lh     ='".$this->db->escape_str((string) $ket14)."',
            pintu_dp_rh     ='".$this->db->escape_str((string) $ket15)."',
            pintu_bl_lh     ='".$this->db->escape_str((string) $ket16)."',
            lp_dp_lh        ='".$this->db->escape_str((string) $ket17)."',
            lp_dp_rh        ='".$this->db->escape_str((string) $ket18)."',
            lp_stop_bl_lh   ='".$this->db->escape_str((string) $ket19)."',
            lp_stop_bl_rh   ='".$this->db->escape_str((string) $ket20)."',
            lp_sbl_lh       ='".$this->db->escape_str((string) $ket21)."',
            lp_sbl_rh       ='".$this->db->escape_str((string) $ket22)."',
            lp_sdp_lh       ='".$this->db->escape_str((string) $ket23)."',
            lp_sdp_rh       ='".$this->db->escape_str((string) $ket24)."',
            lp_s_samping    ='".$this->db->escape_str((string) $ket25)."',
            lp_plat_no      ='".$this->db->escape_str((string) $ket26)."',
            kursi_pp        ='".$this->db->escape_str((string) $ket27)."',
            kursi_png       ='".$this->db->escape_str((string) $ket28)."',
            sabuk_p         ='".$this->db->escape_str((string) $ket29)."',
            footrest        ='".$this->db->escape_str((string) $ket30)."',
            sarung_jok      ='".$this->db->escape_str((string) $ket31)."',
            gorden          ='".$this->db->escape_str((string) $ket32)."',
            tmp_sampah      ='".$this->db->escape_str((string) $ket33)."',
            smoking         ='".$this->db->escape_str((string) $ket34)."',
            toilet_kaca     ='".$this->db->escape_str((string) $ket35)."',
            plafon          ='".$this->db->escape_str((string) $ket36)."',
            palu_kaca       ='".$this->db->escape_str((string) $ket37)."',
            bagasi_atas     ='".$this->db->escape_str((string) $ket38)."',
            lp_dalam        ='".$this->db->escape_str((string) $ket39)."',
            p3k             ='".$this->db->escape_str((string) $ket40)."',
            segitiga        ='".$this->db->escape_str((string) $ket41)."',
            pewangi_ruang   ='".$this->db->escape_str((string) $ket42)."',
            pewangi_toilet  ='".$this->db->escape_str((string) $ket43)."',
            bangku_tabahan  ='".$this->db->escape_str((string) $ket44)."',
            pipa_pegang     ='".$this->db->escape_str((string) $ket45)."',
            tutup_radiator  ='".$this->db->escape_str((string) $ket46)."',
            unit_ac         ='".$this->db->escape_str((string) $ket47)."',
            kursi_kernet    ='".$this->db->escape_str((string) $ket48)."',
            spedometer      ='".$this->db->escape_str((string) $ket49)."',
            tutup_seat      ='".$this->db->escape_str((string) $ket50)."',
            gundu_persneling='".$this->db->escape_str((string) $ket51)."',
            tabung_air_wiper='".$this->db->escape_str((string) $ket52)."',
            accu            ='".$this->db->escape_str((string) $ket53)."',
            tutup_solar     ='".$this->db->escape_str((string) $ket54)."',
            wheel_dop       ='".$this->db->escape_str((string) $ket55)."',
            wiper           ='".$this->db->escape_str((string) $ket56)."',
            ban_stip        ='".$this->db->escape_str((string) $ket57)."',
            engkol_ban      ='".$this->db->escape_str((string) $ket58)."',
            klakson         ='".$this->db->escape_str((string) $ket59)."',
            knalpot         ='".$this->db->escape_str((string) $ket60)."',
            kompresor       ='".$this->db->escape_str((string) $ket61)."',
            altenator       ='".$this->db->escape_str((string) $ket62)."',
            alternator_ac   ='".$this->db->escape_str((string) $ket63)."',
            control_panel   ='".$this->db->escape_str((string) $ket64)."',
            kap_gembok_kunci='".$this->db->escape_str((string) $ket65)."',
            stik_oli        ='".$this->db->escape_str((string) $ket66)."',
            tutup_oli       ='".$this->db->escape_str((string) $ket67)."',
            dinamo_wiper    ='".$this->db->escape_str((string) $ket68)."',
            dongkrak_stang  ='".$this->db->escape_str((string) $ket69)."',
            kc_roda_stang   ='".$this->db->escape_str((string) $ket70)."',
            dashboard       ='".$this->db->escape_str((string) $ket71)."',
            sikring_kaca    ='".$this->db->escape_str((string) $ket72)."',
            radio_tape      ='".$this->db->escape_str((string) $ket73)."',
            video           ='".$this->db->escape_str((string) $ket74)."',
            kaset           ='".$this->db->escape_str((string) $ket75)."',
            tv              ='".$this->db->escape_str((string) $ket76)."',
            remote          ='".$this->db->escape_str((string) $ket77)."',
            inverter        ='".$this->db->escape_str((string) $ket78)."',
            equalizer       ='".$this->db->escape_str((string) $ket79)."',
            mic             ='".$this->db->escape_str((string) $ket80)."',
            speaker         ='".$this->db->escape_str((string) $ket81)."',
            power           ='".$this->db->escape_str((string) $ket82)."',
            subwofer        ='".$this->db->escape_str((string) $ket83)."',
            surat           ='".$this->db->escape_str((string) $ket84)."',
            stnk            ='".$this->db->escape_str((string) $ket85)."',
            kps             ='".$this->db->escape_str((string) $ket86)."',
            keur            ='".$this->db->escape_str((string) $ket87)."',
            bintang_mercy   ='".$this->db->escape_str((string) $ket88)."',
            plat_no         ='".$this->db->escape_str((string) $ket89)."'";

            $this->db->query($sql);
    
            return $this->db->affected_rows();
    }
    public function select_by_id($id)
    {
        $sql = "SELECT * FROM tbl_br_bast WHERE id_bast ='".$this->db->escape_str((string) $id)."'";

        $data = $this->db->query($sql);
        return $data->result();
    }
    public function select_detail()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date("Y-m-d");
        $sql = "SELECT * FROM tbl_br_bast WHERE status ='N'";

        $data = $this->db->query($sql);
        return $data->result();
        
    }
}
