<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tampilan & Bahasa: setiap pengguna dapat mengatur tema (terang/gelap/otomatis),
 * warna aksen, gaya sidebar, kepadatan, ukuran huruf, font, bahasa (ID/EN) dan
 * menu favorit. Super Admin / Admin System dapat menyimpan pilihan sebagai default
 * untuk semua pengguna.
 */
class Tampilan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->config->load('tampilan', true, true);
    }

    private function id_user() { return (int) $this->session->userdata('id_user'); }

    private function admin()
    {
        return in_array((int) $this->session->userdata('id_level'), (array) $this->config->item('level_admin_tampilan', 'tampilan'), true);
    }

    private function terapkan(array $p)
    {
        $this->session->set_userdata('pref', $p);
        $this->session->unset_userdata(array('menu_cache', 'menu_cache_level', 'menu_cache_time'));
        // cookie bahasa (dipakai halaman login)
        $this->input->set_cookie(array('name' => 'top_lang', 'value' => $p['bahasa'], 'expire' => 31536000, 'httponly' => true));
    }

    public function index()
    {
        $pref = $this->session->userdata('pref');
        // nama & ikon favorit
        $fav = array();
        $menu = $this->session->userdata('menu_cache');
        if (is_array($menu)) foreach ($menu as $m) foreach ($m['subs'] as $s) {
            if (in_array($s['link'], (array) $pref['favorit'], true)) $fav[$s['link']] = array(menu_t($s['nama_submenu']), $s['icon']);
        }
        $data = array(
            'page' => t('tampilan_bahasa'), 'judul' => t('tampilan_bahasa'),
            'pref' => $pref, 'aksen' => Preferensi::$AKSEN, 'admin' => $this->admin(),
            'default_sistem' => $this->preferensi->default_sistem(), 'fav' => $fav,
            'tabel_ada' => $this->preferensi->tabel_ada(),
        );
        $this->template->load('layoutbackend', 'tampilan/index', $data);
    }

    public function simpan()
    {
        $pref = $this->session->userdata('pref');
        $in = (array) $this->input->post();
        foreach (array_keys(Preferensi::$DEFAULT) as $k) {
            if ($k === 'favorit') continue;
            if (isset($in[$k])) $pref[$k] = $in[$k];
        }
        if (isset($in['favorit_urut']) && is_array($in['favorit_urut'])) $pref['favorit'] = $in['favorit_urut'];
        $p = $this->preferensi->simpan($this->id_user(), $pref);
        if ($p === false) $this->json(array('status' => false, 'pesan' => 'Tabel preferensi belum ada dan tidak dapat dibuat. Jalankan file migrasi database.'));
        if ( ! empty($in['jadikan_default']) && $this->admin()) {
            $d = $p; $d['favorit'] = array();
            $this->preferensi->simpan(0, $d);
        }
        $this->terapkan($p);
        $this->json(array('status' => true, 'pesan' => t('tersimpan'), 'pref' => $p));
    }

    public function reset()
    {
        $fav = (array) $this->session->userdata('pref')['favorit'];
        $this->preferensi->hapus($this->id_user());
        $p = $this->preferensi->get(0);
        $p['favorit'] = $fav;
        if ($fav) $this->preferensi->simpan($this->id_user(), array('favorit' => $fav) + $p);
        $this->terapkan($p);
        $this->json(array('status' => true, 'pesan' => t('tersimpan'), 'pref' => $p));
    }

    /** sematkan / lepas menu favorit; balikan HTML blok favorit untuk sidebar */
    public function favorit()
    {
        $link = trim((string) $this->input->post('link'));
        $pref = $this->session->userdata('pref');
        $f = (array) $pref['favorit'];
        $i = array_search($link, $f, true);
        if ($i !== false) { array_splice($f, $i, 1); $on = false; }
        else { if (count($f) >= 12) $this->json(array('status' => false, 'pesan' => 'Maksimal 12 favorit.')); $f[] = $link; $on = true; }
        $pref['favorit'] = $f;
        $p = $this->preferensi->simpan($this->id_user(), $pref);
        if ($p === false) { $p = $this->preferensi->bersihkan($pref); }
        $this->session->set_userdata('pref', $p);
        // susun ulang blok favorit
        $semua = array();
        $menu = $this->session->userdata('menu_cache');
        if (is_array($menu)) foreach ($menu as $m) foreach ($m['subs'] as $s) $semua[strtolower(trim($s['link'], '/'))] = $s;
        $fav = array();
        foreach ($p['favorit'] as $l) { $k = strtolower(trim($l, '/')); if (isset($semua[$k])) $fav[] = array($semua[$k]['link'], menu_t($semua[$k]['nama_submenu']), trim((string) $semua[$k]['icon']) !== '' ? $semua[$k]['icon'] : 'far fa-circle', false); }
        $html = $this->load->view('templates/_favorit', array('fav' => $fav), true);
        $this->json(array('status' => true, 'on' => $on, 'html' => $html));
    }

    /** ganti bahasa cepat dari navbar */
    public function bahasa($kode = 'id')
    {
        $pref = $this->session->userdata('pref');
        $pref['bahasa'] = $kode === 'en' ? 'en' : 'id';
        $p = $this->preferensi->simpan($this->id_user(), $pref);
        $this->terapkan($p ?: $this->preferensi->bersihkan($pref));
        $ref = $this->input->server('HTTP_REFERER');
        redirect($ref && strpos($ref, base_url()) === 0 ? $ref : 'dashboard');
    }
}
