<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Terjemahan antarmuka (ID / EN).
 *   t('cari_menu')            -> teks sesuai bahasa pengguna
 *   t('kunci', 'Teks bawaan') -> bila kunci belum ada di file bahasa
 *   menu_t('Part Masuk PO')   -> nama menu/submenu dalam bahasa pengguna
 * File bahasa: application/language/{indonesian,english}/top_lang.php
 */
if ( ! function_exists('bahasa_aktif')) {
    function bahasa_aktif()
    {
        $CI =& get_instance();
        $p = isset($CI->session) ? $CI->session->userdata('pref') : null;
        return is_array($p) && isset($p['bahasa']) ? $p['bahasa'] : 'id';
    }
}

if ( ! function_exists('bahasa_muat')) {
    function bahasa_muat()
    {
        $CI =& get_instance();
        if ( ! isset($CI->lang->is_loaded['top_lang.php'])) {
            $CI->lang->load('top', bahasa_aktif() === 'en' ? 'english' : 'indonesian');
        }
    }
}

if ( ! function_exists('t')) {
    function t($kunci, $bawaan = null)
    {
        bahasa_muat();
        $CI =& get_instance();
        $v = $CI->lang->line($kunci, false);
        if ($v === false || $v === null || is_array($v)) return $bawaan !== null ? $bawaan : $kunci;
        return $v;
    }
}

if ( ! function_exists('menu_t')) {
    function menu_t($nama)
    {
        static $peta = null;
        if ($peta === null) {
            bahasa_muat();
            $CI =& get_instance();
            $m = $CI->lang->line('_menu', false);
            $peta = array();
            if (is_array($m)) foreach ($m as $k => $v) $peta[strtolower(trim($k))] = $v;
        }
        $k = strtolower(trim((string) $nama));
        return isset($peta[$k]) ? $peta[$k] : $nama;
    }
}
