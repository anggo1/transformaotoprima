<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base controller untuk semua halaman yang membutuhkan login.
 * - Kompatibel PHP 7.4 s/d 8.4
 * - Menyediakan helper hak akses (view/add/edit/delete/print) berbasis tbl_akses_submenu
 */
class MY_Controller extends CI_Controller
{
    /** @var object|null baris hak akses submenu untuk halaman aktif */
    protected $akses = null;

    /** @var int level output buffer milik controller (lihat Template::load) */
    public $top_ob_level = 0;

    public function __construct()
    {
        parent::__construct();

        // MySQL 8 (server Ubuntu) memakai sql_mode ketat secara default. Kode lama memakai
        // GROUP BY gaya MySQL 5 (error 1055) dan tanggal '0000-00-00' (error 1525), jadi
        // koneksi aplikasi memakai mode yang kompatibel. Setelan server tidak diubah.
        if (isset($this->db) && $this->db->conn_id) {
            $this->db->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
        }

        // Tampung output yang di-echo controller sebelum layout (mis. show_my_modal)
        // agar dicetak di dalam <body>, bukan sebelum <!DOCTYPE> (mencegah quirks mode)
        ob_start();
        $this->top_ob_level = ob_get_level();

        if ($this->session->userdata('username') == '') {
            if ($this->input->is_ajax_request()) {
                // AJAX: kirim 401 agar layout dapat mengarahkan ke halaman login
                $this->output->set_status_header(401);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('status' => FALSE, 'error' => TRUE, 'session_expired' => TRUE,
                    'pesan' => 'Sesi Anda telah berakhir, silakan login kembali.'));
                exit;
            }
            redirect('login/logout');
        }

        // preferensi tampilan & bahasa (dimuat sekali per sesi)
        $this->load->library('preferensi');
        $this->load->helper('bahasa');
        $pref = $this->session->userdata('pref');
        if ( ! is_array($pref)) {
            $pref = $this->preferensi->get((int) $this->session->userdata('id_user'));
            $this->session->set_userdata('pref', $pref);
        }
        $this->lang->load('top', $pref['bahasa'] === 'en' ? 'english' : 'indonesian');
    }

    /**
     * Ambil hak akses submenu untuk link (default: segment pertama URL).
     */
    protected function hak_akses($link = null)
    {
        $default = ($link === null);
        if ($default && $this->akses !== null) {
            return $this->akses;
        }
        $link    = $link ?: (string) $this->uri->segment(1);
        $idlevel = (int) $this->session->userdata('id_level');

        $row = $this->db->select('a.*')
            ->from('tbl_akses_submenu a')
            ->join('tbl_submenu b', 'a.id_submenu = b.id_submenu')
            ->where('a.id_level', $idlevel)
            ->where('b.link', $link)
            ->limit(1)->get()->row();

        if ( ! $row) {
            // Super Admin (level 1) selalu punya akses penuh
            $full = ($idlevel === 1) ? 'Y' : 'N';
            $row = (object) array('view_level' => $full, 'add_level' => $full, 'edit_level' => $full,
                'delete_level' => $full, 'print_level' => $full, 'upload_level' => $full);
        }
        if ($default) {
            $this->akses = $row;
        }
        return $row;
    }

    /** true bila user punya hak tertentu: view|add|edit|delete|print|upload */
    protected function boleh($hak = 'view')
    {
        $a = $this->hak_akses();
        $k = $hak . '_level';
        return isset($a->$k) && $a->$k === 'Y';
    }

    /** hentikan request bila tidak punya hak */
    protected function wajib($hak = 'view')
    {
        if ($this->boleh($hak)) {
            return;
        }
        if ($this->input->is_ajax_request() || $this->input->method() === 'post') {
            $this->json(array('status' => FALSE, 'error' => TRUE,
                'pesan' => 'Anda tidak memiliki hak akses (' . $hak . ') untuk menu ini.'), 403);
            exit;
        }
        show_error('Anda tidak memiliki hak akses ke halaman ini.', 403, 'Akses Ditolak');
    }

    /** kirim respon JSON dan selesai */
    protected function json($data, $status = 200)
    {
        $this->output->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data))
            ->_display();
        exit;
    }
}
