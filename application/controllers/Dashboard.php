<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('fungsi', 'user_agent'));
        $this->load->model(array('Mod_dashboard', 'Mod_userlevel'));
    }

    /** dashboard per level / departemen / user (widget dimuat bertahap) */
    public function index()
    {
        $this->load->model('Mod_dashlayout');
        if ( ! $this->Mod_dashlayout->siap()) return $this->klasik();
        $this->load->library('dashwidget');
        $idUser = (int) $this->session->userdata('id_user'); $idLevel = (int) $this->session->userdata('id_level');
        list($widget, $sumber, $izin) = $this->Mod_dashlayout->untuk($idUser, $idLevel);
        $admin = $this->admin_dashboard();
        $pr = (string) $this->input->get('pratinjau');
        if ($admin && preg_match('/^(LEVEL|DEPT|USER|PRESET):([A-Za-z0-9_]+)$/', $pr, $m)) {
            if ($m[1] === 'PRESET') { $p = $this->Mod_dashlayout->preset($m[2]); if ($p) { $widget = $p['widget']; $sumber = 'Pratinjau preset ' . $p['nama']; } }
            elseif ($r = $this->Mod_dashlayout->get($m[1], $m[2])) { $widget = $r->widget; $sumber = 'Pratinjau ' . strtolower($m[1]) . ' ' . $m[2]; }
        }
        $tampil = array(); foreach ($widget as $w) if ($this->dashwidget->tersedia($w[0])) $tampil[] = $w;
        $this->template->load('layoutbackend', 'dashboard/layout', array('page' => 'Dashboard', 'judul' => 'Ringkasan', 'widget' => $tampil,
            'sumber' => $sumber, 'boleh_atur' => $izin === 'Y' || $admin, 'admin' => $admin));
    }

    /** satu widget (HTML) — dipanggil AJAX dari dashboard */
    public function widget($kode = '')
    {
        $this->load->library('dashwidget');
        $d = $this->dashwidget->data($kode);
        $this->output->set_content_type('text/html', 'utf-8');
        $this->load->view('dashboard/_widget', array('kode' => $kode, 'd' => $d, 'info' => isset(Dashwidget::$W[$kode]) ? Dashwidget::$W[$kode] : null));
    }

    /** atur dashboard pribadi (bila diizinkan level/departemen, atau admin) */
    public function atur()
    {
        $this->load->model('Mod_dashlayout'); $this->load->library('dashwidget');
        $idUser = (int) $this->session->userdata('id_user');
        list($widget, $sumber, $izin) = $this->Mod_dashlayout->untuk($idUser, (int) $this->session->userdata('id_level'));
        if ($izin !== 'Y' && ! $this->admin_dashboard()) show_error('Pengaturan dashboard pribadi belum diizinkan untuk level Anda. Hubungi administrator.', 403, 'Akses Ditolak');
        if ($this->input->method() === 'post') {
            if ($this->input->post('reset')) $this->Mod_dashlayout->hapus('USER', $idUser);
            else $this->Mod_dashlayout->simpan('USER', $idUser, (array) json_decode((string) $this->input->post('layout'), true));
            redirect('dashboard');
        }
        $this->template->load('layoutbackend', 'dashboard/atur', array('page' => 'Atur Dashboard Saya', 'judul' => 'Dashboard', 'widget' => $widget, 'sumber' => $sumber,
            'aksi' => site_url('Dashboard/atur'), 'preset' => $this->Mod_dashlayout->preset(), 'hanya_tersedia' => true, 'target_label' => 'Dashboard pribadi', 'boleh_ubah' => null));
    }

    /** tampilan dashboard lama (ringkasan gudang & keuangan) */
    public function klasik()
    {
        $idlevel = (int) $this->session->userdata('id_level');
        $data['page']  = 'Dashboard';
        $data['judul'] = 'Ringkasan';

        $data['tampil_gudang'] = in_array($idlevel, array(1, 7, 12), true) || $this->boleh_link('Sparepart');
        $data['tampil_keuangan'] = $idlevel === 1 || $this->boleh_link('LaporanKeuangan') || $this->boleh_link('KasBank');

        $data['jml_bus']    = $this->Mod_dashboard->total_bus();
        $data['jml_barang'] = $this->Mod_dashboard->total_barang();

        if ($data['tampil_gudang']) {
            $lok = $this->Mod_dashboard->kode_lokasi((string) $this->session->userdata('lokasi'));
            $data['lokasi_stok'] = $lok;
            $data['gudang'] = $this->Mod_dashboard->ringkasan_gudang($lok);
            $data['keluar_divisi'] = $this->Mod_dashboard->keluar_per_divisi(date('Y-m-01', strtotime('first day of last month')), date('Y-m-d'));
            $data['tren_keluar'] = $this->Mod_dashboard->tren_keluar(6);
        }
        if ($data['tampil_keuangan'] && $this->db->table_exists('tbl_acc_setting')) {
            $data['keuangan'] = $this->Mod_dashboard->ringkasan_keuangan();
        }
        $this->template->load('layoutbackend', 'dashboard/dashboard_data', $data);
    }

    private function admin_dashboard()
    {
        if (in_array((int) $this->session->userdata('id_level'), array(1, 12), true)) return true;
        $a = $this->hak_akses('PengaturanDashboard');
        return isset($a->view_level) && $a->view_level === 'Y';
    }

    /** cek hak lihat submenu berdasarkan link */
    private function boleh_link($link)
    {
        $a = $this->hak_akses($link);
        return isset($a->view_level) && $a->view_level === 'Y';
    }
}
/* End of file Dashboard.php */
