<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Pengaturan dashboard per level user, departemen, atau user tertentu + pengumuman + pemetaan user ke departemen. */
class PengaturanDashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_dashlayout');
        $this->load->library('dashwidget');
        if ( ! in_array((int) $this->session->userdata('id_level'), array(1, 12), true)) $this->wajib('view');
        if ( ! $this->Mod_dashlayout->siap()) show_error('Tabel tbl_dashboard_layout belum ada. Jalankan migrasi database v5.', 500, 'Migrasi diperlukan');
    }

    private function bisa_ubah() { return in_array((int) $this->session->userdata('id_level'), array(1, 12), true) || $this->boleh('edit'); }

    private function target_ok($jenis, $target)
    {
        if ($jenis === 'LEVEL') return (bool) $this->db->get_where('tbl_userlevel', array('id_level' => (int) $target))->row();
        if ($jenis === 'DEPT') return $this->db->table_exists('tbl_hrd_departement') && (bool) $this->db->get_where('tbl_hrd_departement', array('id_departement' => (int) $target))->row();
        if ($jenis === 'USER') return (bool) $this->db->get_where('tbl_user', array('id_user' => (int) $target))->row();
        return false;
    }

    public function index()
    {
        $jenis = strtoupper((string) $this->input->get('jenis')); $target = (string) $this->input->get('target');
        $level = $this->db->order_by('id_level')->get('tbl_userlevel')->result();
        $dept = $this->db->table_exists('tbl_hrd_departement') ? $this->db->order_by('departement')->get('tbl_hrd_departement')->result() : array();
        $user = $this->db->select('id_user, username, full_name, id_level, lokasi')->order_by('full_name')->get('tbl_user')->result();
        $data = array('page' => 'Pengaturan Dashboard', 'judul' => 'Sistem', 'level' => $level, 'dept' => $dept, 'user' => $user,
            'ada' => $this->Mod_dashlayout->ringkas_semua(), 'umum' => $this->Mod_dashlayout->pengumuman(), 'ud' => $this->Mod_dashlayout->user_departemen(),
            'preset' => $this->Mod_dashlayout->preset(), 'pl' => (array) $this->config->item('preset_level', 'dashboard'), 'jenis' => '', 'bisa' => $this->bisa_ubah());
        if (in_array($jenis, array('LEVEL', 'DEPT', 'USER'), true) && $this->target_ok($jenis, $target)) {
            $r = $this->Mod_dashlayout->get($jenis, $target);
            $nama = $jenis === 'LEVEL' ? 'Level ' . $this->Mod_dashlayout->nama_level($target) : ($jenis === 'DEPT' ? 'Departemen ' . $this->Mod_dashlayout->nama_departemen((int) $target)
                : 'User ' . $this->db->get_where('tbl_user', array('id_user' => (int) $target))->row('full_name'));
            if ($r) $w = $r->widget;
            else {
                if ($jenis === 'LEVEL') { $kode = isset($data['pl'][(int) $target]) ? $data['pl'][(int) $target] : 'UMUM'; }
                else $kode = 'UMUM';
                $w = $this->Mod_dashlayout->preset($kode)['widget'];
            }
            $data += array('jenis' => $jenis, 'target' => $target, 'widget' => $w, 'tersimpan' => (bool) $r, 'aksi' => site_url('PengaturanDashboard/simpan'),
                'target_label' => $nama, 'boleh_ubah' => $jenis === 'USER' ? null : ($r ? $r->boleh_ubah : 'N'), 'hidden' => array('jenis' => $jenis, 'target' => $target), 'hanya_tersedia' => false);
            $data['jenis'] = $jenis;
        }
        $this->template->load('layoutbackend', 'dashboard/pengaturan', $data);
    }

    public function simpan()
    {
        if ( ! $this->bisa_ubah()) $this->wajib('edit');
        $jenis = strtoupper((string) $this->input->post('jenis')); $target = (string) $this->input->post('target');
        if ( ! in_array($jenis, array('LEVEL', 'DEPT', 'USER'), true) || ! $this->target_ok($jenis, $target)) show_error('Target tidak valid.', 400);
        if ($this->input->post('reset')) {
            $this->Mod_dashlayout->hapus($jenis, $target);
            $this->session->set_flashdata('wh_msg', array('success', 'Pengaturan dihapus — kembali ke dashboard bawaan.'));
        } else {
            $this->Mod_dashlayout->simpan($jenis, $target, (array) json_decode((string) $this->input->post('layout'), true), $this->input->post('boleh_ubah') === 'Y' ? 'Y' : 'N');
            $this->session->set_flashdata('wh_msg', array('success', 'Dashboard disimpan. <a href="' . site_url('dashboard?pratinjau=' . $jenis . ':' . $target) . '" target="_blank">Pratinjau</a>'));
        }
        redirect('PengaturanDashboard?jenis=' . $jenis . '&target=' . rawurlencode($target));
    }

    public function pengumuman()
    {
        if ( ! $this->bisa_ubah()) $this->wajib('edit');
        $this->Mod_dashlayout->simpan_pengumuman($this->input->post());
        $this->session->set_flashdata('wh_msg', array('success', 'Pengumuman disimpan.'));
        redirect('PengaturanDashboard#pengumuman');
    }

    public function departemen()
    {
        if ( ! $this->bisa_ubah()) $this->wajib('edit');
        if ( ! $this->db->table_exists('tbl_user_departement')) show_error('Tabel tbl_user_departement belum ada. Jalankan migrasi v5.', 500);
        $this->Mod_dashlayout->simpan_departemen_user((array) $this->input->post('u'));
        $this->session->set_flashdata('wh_msg', array('success', 'Pemetaan user ke departemen disimpan.'));
        redirect('PengaturanDashboard#departemen');
    }
}
