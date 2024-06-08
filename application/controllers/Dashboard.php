<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('fungsi');
        $this->load->library('user_agent');
        $this->load->helper('myfunction_helper');
		$this->load->model(array('Mod_dashboard'));
        $this->load->model(array('Mod_userlevel'));
        // backButtonHandle();
    }

    function index()
    {
		$data['page'] 		= "Halaman Depan";
		$data['judul'] 		= "Dashboard";
    	$logged_in = $this->session->userdata('logged_in');
        
		$data['jml_bus'] 	= $this->Mod_dashboard->total_bus();
		$data['jml_antri'] 	= $this->Mod_dashboard->total_antri();
		$data['jml_spk'] 	= $this->Mod_dashboard->total_spk();
		$data['jml_pk'] 	= $this->Mod_dashboard->total_pk();
		$data['jml_barang'] 	= $this->Mod_dashboard->total_barang();
		$data['jml_selesai'] 	= $this->Mod_dashboard->total_selesai();
		$data['grafikA'] 	= $this->Mod_dashboard->grafikAgen();
		$data['grafikB'] 	= $this->Mod_dashboard->grafikBarang();

        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $index = 0;
		foreach ($data['grafikA'] as $value) {
		    $color = '#' .$rand[rand(0,15)] .$rand[rand(0,15)] .$rand[rand(0,15)] .$rand[rand(0,15)] .$rand[rand(0,15)] .$rand[rand(0,15)];

			$data_asal[$index]= $value->asalnye;
			$jumlah_asal[$index]= $value->jml;

			//$data_posisi[$index]['label'] = $value->asalnye;
			$data_posisi[$index]= $value->jml;
			$data_warna[$index]= $color;
			//$data_posisi[$index]['highlight'] = $color;
			
			$index++;
            $data['data_asal'] = json_encode($data_asal);
            $data['jumlah_asal'] = json_encode($data_posisi);
            $data['warna_asal'] = json_encode($data_warna);    
		}
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $index = 0;
		foreach ($data['grafikB'] as $value) {
		    $color = '#' .$rand[rand(0,15)] .$rand[rand(0,15)] .$rand[rand(0,15)] .$rand[rand(0,15)] .$rand[rand(0,15)] .$rand[rand(0,15)];

			$data_barang[$index]= $value->barang;
			$jumlah_barang[$index]= $value->jml;

			//$data_posisi[$index]['label'] = $value->asalnye;
			$data_posisi[$index]= $value->jml;
			$data_warna[$index]= $color;
			//$data_posisi[$index]['highlight'] = $color;
			
			$index++;
            $data['data_barang'] = json_encode($data_barang);
            $data['jumlah_barang'] = json_encode($jumlah_barang);
            $data['warna_barang'] = json_encode($data_warna);    
		}

        if ($logged_in != TRUE || empty($logged_in)) {
            $idlevel = $this->session->userdata['id_level'];
            redirect('login');
        }else{
        	$this->template->load('layoutbackend','dashboard/dashboard_data',$data);
        }
        
    }

}
/* End of file Controllername.php */
 