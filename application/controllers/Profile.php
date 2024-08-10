<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_profil');
        $this->load->model('Mod_user');

    }
	public function index()
    {
		$data['page'] 		= "Edit Data Pengguna";
		$data['judul'] 		= "Daftar Pengguna";
        $this->load->helper('url');
        $data['user'] = $this->Mod_user->getAll();
        $data['user_level'] = $this->Mod_user->userlevel();
        $data['user_lokasi'] = $this->Mod_user->userlokasi();
        $this->template->load('layoutbackend', 'admin/profile', $data);
    }

	public function update() {
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]');
		$this->form_validation->set_rules('FirstName', 'Nama', 'trim|required');

		$id = $this->userdata->User_id;
		$data = $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$config['upload_path'] = './assets/img/';
			$config['allowed_types'] = 'jpg|png';
			
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('foto')){
				$error = array('error' => $this->upload->display_errors());
			}
			else{
				$data_foto = $this->upload->data();
				$data['foto'] = $data_foto['file_name'];
			}

			$result = $this->M_admin->update($data, $id);
			if ($result > 0) {
				$this->updateProfil();
				$this->session->set_flashdata('msg', show_succ_msg('Data Profile Berhasil diubah'));
				redirect('Profile');
			} else {
				$this->session->set_flashdata('msg', show_err_msg('Data Profile Gagal diubah'));
				redirect('Profile');
			}
		} else {
			$this->session->set_flashdata('msg', show_err_msg(validation_errors()));
			redirect('Profile');
		}
	}
	


	public function update_pass() {

		$id_user = $this->input->post('id_user');
        $pass = $this->session->userdata['password'];
		$pass1 = $this->input->post('passBaru');
		$pass2 = $this->input->post('passKonf');

		
			if(hash_verified(anti_injection($this->input->post('passLama')), $pass)) {
				if ($pass1 != $pass2) {
					$out['status'] = 'gagal1';
					$out['msg'] = show_del_msg('Password Baru tidak sama', '10px');
				} else {
					$save  = array(
						'password'  => get_hash($this->input->post('passBaru'))
					);
					$this->Mod_user->updateUser($id_user, $save);
					$out['status'] = 'ok';
					$out['msg'] = show_ok_msg('Deleted', '10px');
            //echo json_encode(array("status" => TRUE));
				}
			} else {
				$out['status'] = 'gagal2';
				$out['msg'] = show_ok_msg('Password lama tidak sama', '10px');
			}
		
			echo json_encode($out);
			
	}

	public function update_data()
    {
        if(!empty($_FILES['imagefile']['name'])) {
        // $this->_validate();
        $id = $this->input->post('id_user');
        
        $nama = slug($this->input->post('username'));
        $config['upload_path']   = './assets/foto/user/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png'; //mencegah upload backdor
        $config['max_size']      = '2000';
        $config['max_width']     = '4000';
        $config['max_height']    = '2400';
        $config['file_name']     = $nama; 
        
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('imagefile')){
            $gambar = $this->upload->data();
            //Jika Password tidak kosong
                $save  = array(
                'username' => $this->input->post('username'),
                'full_name' => $this->input->post('full_name'),
                'image' => $gambar['file_name']
                );            
            
            $g = $this->Mod_user->getImage($id)->row_array();

            if ($g != null) {
                //hapus gambar yg ada diserver
                unlink('assets/foto/user/'.$g['image']);
            }
            
            $this->Mod_user->updateUser($id, $save);
            echo json_encode(array("status" => TRUE));
            }
        }else{
			$id = $this->input->post('id_user');
                $save  = array(
                'username' => $this->input->post('username'),
                'full_name' => $this->input->post('full_name')
                );
            
            
            $this->Mod_user->updateUser($id, $save);
            echo json_encode(array("status" => TRUE));
        }
    }
	public function edituser($id)
    {
            
            $data = $this->Mod_user->getUser($id);
            echo json_encode($data);
        
    }

}