<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    /** batas percobaan login gagal sebelum dijeda */
    const MAX_GAGAL = 5;
    const JEDA_DETIK = 60;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Mod_login'));
    }

    public function index()
    {
        if ($this->session->userdata('logged_in') == TRUE) {
            redirect('dashboard');
        }
        $aplikasi['aplikasi'] = $this->Mod_login->Aplikasi()->row();
        $this->load->view('admin/login_data', $aplikasi);
    }

    public function login()
    {
        $this->_validate();

        // proteksi brute-force sederhana per sesi
        $gagal = (int) $this->session->userdata('login_gagal');
        $sejak = (int) $this->session->userdata('login_gagal_waktu');
        if ($gagal >= self::MAX_GAGAL && (time() - $sejak) < self::JEDA_DETIK) {
            $sisa = self::JEDA_DETIK - (time() - $sejak);
            $this->_out(array('error' => TRUE, 'pesan' => 'Terlalu banyak percobaan. Coba lagi dalam ' . $sisa . ' detik.'));
            return;
        }

        $username = trim((string) $this->input->post('username'));
        $password = (string) $this->input->post('password');
        $q  = $this->Mod_login->check_db($username);

        if ($q->num_rows() == 1) {
            $db  = $q->row();
            $apl = $this->Mod_login->Aplikasi()->row();

            if ( ! hash_verified($password, $db->password) && ! hash_verified(anti_injection($password), $db->password)) {
                $this->_gagal();
                $this->_out(array('error' => TRUE, 'pesan' => 'Username atau Password Salah!'));
                return;
            }
            if (isset($db->is_active) && $db->is_active === 'N') {
                $this->_out(array('error' => TRUE, 'pesan' => 'Akun Anda tidak aktif. Hubungi administrator.'));
                return;
            }

            // cegah session fixation
            $this->session->sess_regenerate(TRUE);

            $userdata = array(
                'id_user'     => $db->id_user,
                'username'    => ucfirst($db->username),
                'full_name'   => ucfirst($db->full_name),
                'id_level'    => $db->id_level,
                'aplikasi'    => $apl ? $apl->nama_aplikasi : '',
                'title'       => $apl ? $apl->title : '',
                'logo'        => $apl ? $apl->logo : '',
                'nama_owner'  => $apl ? $apl->nama_owner : '',
                'image'       => $db->image,
                'id_lokasi'   => $db->id_lokasi,
                'lokasi'      => $db->lokasi,
                'footer'      => $apl ? $apl->footer : '',
                'logged_in'   => TRUE,
                'lang'        => 'id',
                'login_gagal' => 0,
            );
            $this->session->set_userdata($userdata);
            $this->session->unset_userdata(array('menu_cache', 'menu_cache_level'));

            $this->_out(array('status' => TRUE));
        } else {
            $this->_gagal();
            $this->_out(array('error' => TRUE, 'pesan' => 'Username atau Password belum terdaftar!'));
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        if (ob_get_level() > 0) {
            ob_clean();
        }
        redirect('login');
    }

    private function _gagal()
    {
        $gagal = (int) $this->session->userdata('login_gagal') + 1;
        $this->session->set_userdata(array('login_gagal' => $gagal, 'login_gagal_waktu' => time()));
    }

    private function _out($data)
    {
        $this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($data));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if (trim((string) $this->input->post('username')) === '') {
            $data['inputerror'][] = 'username';
            $data['error_string'][] = 'Username wajib diisi';
            $data['status'] = FALSE;
        }

        if ((string) $this->input->post('password') === '') {
            $data['inputerror'][] = 'password';
            $data['error_string'][] = 'Password wajib diisi';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Login.php */
