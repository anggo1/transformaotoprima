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
		$data['jml_barang'] 	= $this->Mod_dashboard->total_barang();
		//$data['jml_selesai'] 	= $this->Mod_dashboard->total_selesai();

        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $index = 0;
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        if ($logged_in != TRUE || empty($logged_in)) {
            $idlevel = $this->session->userdata['id_level'];
            redirect('login');
        }else{
        	$this->template->load('layoutbackend','dashboard/dashboard_data',$data);
        }
        
    }

}
/* End of file Controllername.php */
 