<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Preferensi tampilan & bahasa per pengguna.
 * Disimpan di tbl_user_preferensi (id_user, data JSON). Baris id_user = 0 adalah
 * default sistem (diatur Super Admin) yang dipakai semua pengguna yang belum mengatur sendiri.
 */
class Preferensi
{
    const TABEL = 'tbl_user_preferensi';

    public static $DEFAULT = array(
        'bahasa'    => 'id',        // id | en
        'tema'      => 'terang',    // terang | gelap | otomatis
        'aksen'     => 'petrol',
        'sidebar'   => 'gelap',     // gelap | terang | aksen
        'kepadatan' => 'nyaman',    // nyaman | ringkas
        'huruf'     => 'sedang',    // kecil | sedang | besar
        'font'      => 'jakarta',   // jakarta | inter | sistem
        'sidebar_mini' => 'N',      // Y = sidebar diciutkan saat membuka aplikasi
        'kelompok_menu' => 'Y',     // Y = tampilkan judul kelompok menu di sidebar
        'favorit'   => array(),     // daftar link submenu yang disematkan
    );

    /** warna aksen: kode => [label, utama, gelap, muda, rgb] */
    public static $AKSEN = array(
        'petrol'  => array('Petrol (bawaan)', '#0e7c86', '#0b6a73', '#e7f4f5', '14,124,134'),
        'biru'    => array('Biru Korporat', '#1d5fd1', '#174ea9', '#e8f0fd', '29,95,209'),
        'indigo'  => array('Indigo', '#4f46e5', '#4338ca', '#eef0ff', '79,70,229'),
        'ungu'    => array('Ungu Anggur', '#7c3aed', '#6d28d9', '#f3edff', '124,58,237'),
        'hijau'   => array('Hijau Zamrud', '#0f8a5f', '#0c714e', '#e6f5ef', '15,138,95'),
        'emas'    => array('Emas', '#b7791f', '#975a16', '#fbf3e4', '183,121,31'),
        'merah'   => array('Merah Marun', '#b42335', '#951c2b', '#fbe9eb', '180,35,53'),
        'grafit'  => array('Grafit', '#3f4a5a', '#2d3643', '#eef0f3', '63,74,90'),
    );

    public static $PILIHAN = array(
        'bahasa' => array('id', 'en'), 'tema' => array('terang', 'gelap', 'otomatis'),
        'sidebar' => array('gelap', 'terang', 'aksen'), 'kepadatan' => array('nyaman', 'ringkas'),
        'huruf' => array('kecil', 'sedang', 'besar'), 'font' => array('jakarta', 'inter', 'sistem'),
        'sidebar_mini' => array('Y', 'N'), 'kelompok_menu' => array('Y', 'N'),
    );

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function tabel_ada()
    {
        return $this->CI->db->table_exists(self::TABEL);
    }

    public function buat_tabel()
    {
        if ($this->tabel_ada()) return true;
        $sql = 'CREATE TABLE IF NOT EXISTS ' . self::TABEL . ' (id_user INT NOT NULL PRIMARY KEY, data TEXT, updated_at DATETIME NULL)';
        $this->CI->db->query($sql);
        $this->CI->db->data_cache = array();
        return $this->tabel_ada();
    }

    private function baca($idUser)
    {
        $r = $this->CI->db->get_where(self::TABEL, array('id_user' => (int) $idUser), 1)->row();
        if ( ! $r || $r->data === null || $r->data === '') return array();
        $d = json_decode($r->data, true);
        return is_array($d) ? $d : array();
    }

    /** preferensi efektif: bawaan <- default sistem <- milik user */
    public function get($idUser)
    {
        $p = self::$DEFAULT;
        if ($this->tabel_ada()) {
            $p = $this->bersihkan(array_merge($p, $this->baca(0)));
            if ((int) $idUser > 0) $p = $this->bersihkan(array_merge($p, $this->baca($idUser)));
        }
        return $p;
    }

    public function default_sistem()
    {
        return $this->bersihkan(array_merge(self::$DEFAULT, $this->tabel_ada() ? $this->baca(0) : array()));
    }

    public function bersihkan(array $p)
    {
        $o = self::$DEFAULT;
        foreach (self::$PILIHAN as $k => $ok) if (isset($p[$k]) && in_array((string) $p[$k], $ok, true)) $o[$k] = (string) $p[$k];
        if (isset($p['aksen']) && isset(self::$AKSEN[$p['aksen']])) $o['aksen'] = $p['aksen'];
        if (isset($p['favorit']) && is_array($p['favorit'])) {
            $f = array();
            foreach ($p['favorit'] as $l) { $l = trim((string) $l); if ($l !== '' && $l !== '#' && strlen($l) < 120 && ! in_array($l, $f, true)) $f[] = $l; }
            $o['favorit'] = array_slice($f, 0, 12);
        }
        return $o;
    }

    public function simpan($idUser, array $p)
    {
        if ( ! $this->buat_tabel()) return false;
        $p = $this->bersihkan($p);
        $row = array('data' => json_encode($p), 'updated_at' => date('Y-m-d H:i:s'));
        $ada = $this->CI->db->where('id_user', (int) $idUser)->count_all_results(self::TABEL);
        if ($ada) $this->CI->db->where('id_user', (int) $idUser)->update(self::TABEL, $row);
        else $this->CI->db->insert(self::TABEL, $row + array('id_user' => (int) $idUser));
        return $p;
    }

    public function hapus($idUser)
    {
        if ($this->tabel_ada()) $this->CI->db->where('id_user', (int) $idUser)->delete(self::TABEL);
    }

    /** atribut <html> untuk CSS */
    public static function atribut(array $p)
    {
        $a = array('data-tema' => $p['tema'], 'data-aksen' => $p['aksen'], 'data-sidebar' => $p['sidebar'],
            'data-kepadatan' => $p['kepadatan'], 'data-huruf' => $p['huruf'], 'data-font' => $p['font']);
        $s = '';
        foreach ($a as $k => $v) $s .= ' ' . $k . '="' . htmlspecialchars($v, ENT_QUOTES) . '"';
        return $s;
    }

    /** variabel CSS warna aksen */
    public static function css_aksen($kode)
    {
        $a = isset(self::$AKSEN[$kode]) ? self::$AKSEN[$kode] : self::$AKSEN['petrol'];
        return ':root{--top-accent:' . $a[1] . ';--top-accent-600:' . $a[2] . ';--top-accent-50:' . $a[3] . ';--top-accent-rgb:' . $a[4] . ';}';
    }
}
