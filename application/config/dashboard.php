<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Dashboard per level / departemen / user.
| Urutan penentuan dashboard seorang user:
|   1. layout USER (diatur admin, atau user sendiri bila level/departemennya diizinkan)
|   2. layout DEPARTEMEN (bila user dipetakan ke departemen di Pengaturan Dashboard)
|   3. layout LEVEL
|   4. preset bawaan untuk level (di bawah)
| Widget yang tidak boleh diakses user (tidak punya hak lihat menu sumbernya) otomatis disembunyikan.
| Format layout: array of [kode_widget, lebar] ; lebar = kolom grid 12 (3, 4, 6, 8, 12)
*/

$config['preset'] = array(
    'DIREKSI' => array('nama' => 'Direksi / Manajemen', 'ikon' => 'fa-crown', 'widget' => array(
        array('sys_pengumuman', 12), array('keu_kpi', 12), array('keu_tren', 8), array('keu_kas', 4),
        array('wh_kpi', 12), array('as_kpi', 6), array('mk_kpi', 6), array('keu_umur_ar', 6), array('as_tren', 6),
        array('wh_tren', 8), array('hr_kpi', 4), array('wh_kenaikan', 6), array('mk_sales', 6))),
    'AKUNTANSI' => array('nama' => 'Akuntansi & Keuangan', 'ikon' => 'fa-calculator', 'widget' => array(
        array('sys_pengumuman', 12), array('keu_kpi', 12), array('keu_tren', 8), array('keu_kas', 4), array('keu_umur_ar', 6), array('keu_umur_ap', 6),
        array('keu_jatuh_tempo', 6), array('keu_pajak', 6), array('wh_nilai', 6), array('wh_opname', 6), array('sys_pintasan', 12))),
    'GUDANG' => array('nama' => 'Gudang', 'ikon' => 'fa-warehouse', 'widget' => array(
        array('sys_pengumuman', 12), array('wh_kpi', 12), array('wh_tren', 8), array('wh_nilai', 4), array('wh_stok_min', 6), array('wh_top_pakai', 6),
        array('wh_opname', 4), array('wh_kenaikan', 4), array('wh_slow', 4), array('wh_po', 12), array('sys_pintasan', 12))),
    'AFTERSALES' => array('nama' => 'After Sales / Service', 'ikon' => 'fa-tools', 'widget' => array(
        array('sys_pengumuman', 12), array('as_kpi', 12), array('as_tren', 8), array('as_status', 4), array('as_wo_open', 12), array('wh_stok_min', 6), array('sys_pintasan', 6))),
    'MARKETING' => array('nama' => 'Marketing & Sales', 'ikon' => 'fa-handshake', 'widget' => array(
        array('sys_pengumuman', 12), array('mk_kpi', 12), array('mk_sales', 6), array('mk_tren', 6), array('mk_stok', 12), array('sys_pintasan', 12))),
    'HR' => array('nama' => 'HRD / Personalia', 'ikon' => 'fa-users', 'widget' => array(
        array('sys_pengumuman', 12), array('hr_kpi', 12), array('hr_dept', 6), array('hr_masa_kerja', 6), array('hr_ultah', 6), array('hr_cuti', 6), array('hr_absen', 12))),
    'UMUM' => array('nama' => 'Umum / Staf', 'ikon' => 'fa-user', 'widget' => array(
        array('sys_pengumuman', 12), array('sys_pintasan', 12), array('hr_ultah', 6), array('wh_stok_min', 6))),
);

/* preset bawaan per id_level (bila belum diatur di Pengaturan Dashboard) */
$config['preset_level'] = array(
    1 => 'DIREKSI', 12 => 'DIREKSI', 19 => 'DIREKSI',
    10 => 'AKUNTANSI',
    15 => 'GUDANG', 20 => 'GUDANG',
    16 => 'MARKETING',
    18 => 'AFTERSALES', 21 => 'AFTERSALES',
    17 => 'UMUM',
);

/* preset bawaan per nama departemen HRD (huruf kecil, cocok sebagian) */
$config['preset_departemen'] = array('keuangan' => 'AKUNTANSI', 'akunt' => 'AKUNTANSI', 'marketing' => 'MARKETING', 'teknik' => 'AFTERSALES', 'service' => 'AFTERSALES',
    'gudang' => 'GUDANG', 'warehouse' => 'GUDANG', 'hrd' => 'HR', 'personalia' => 'HR');
