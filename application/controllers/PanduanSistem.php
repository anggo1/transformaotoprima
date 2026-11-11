<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Panduan sistem: peta integrasi antar modul, dashboard & laporan rekomendasi per departemen, kesiapan data lintas modul. */
class PanduanSistem extends MY_Controller
{
    public function index()
    {
        $db = $this->db; $cek = array();
        $t = function ($x) use ($db) { return $db->table_exists($x); };
        $cek[] = array('Tabel v5 (opname, impor, harga rata, dashboard) sudah dimigrasi', $t('tbl_wh_so') && $t('tbl_dashboard_layout'), $t('tbl_dashboard_layout') ? 'siap' : 'jalankan migrasi v5', 'PanduanSistem');
        if ($t('tbl_wh_setting')) {
            $s = array(); foreach ($db->get('tbl_wh_setting')->result() as $r) $s[$r->kunci] = $r->nilai;
            $cek[] = array('Akun persediaan & selisih opname diisi (jurnal otomatis)', ! empty($s['akun_persediaan']) && ! empty($s['akun_selisih_persediaan']), ! empty($s['akun_persediaan']) ? 'terisi' : 'belum diisi', 'OpnameStok');
            $cek[] = array('Harga rata-rata pernah dihitung', ! empty($s['harga_rata_dihitung']), ! empty($s['harga_rata_dihitung']) ? date('d/m/Y', strtotime($s['harga_rata_dihitung'])) : 'belum', 'LaporanGudang/harga_rata');
        }
        $nUser = $db->count_all('tbl_user'); $nMap = $t('tbl_user_departement') ? $db->where('id_departement >', 0)->count_all_results('tbl_user_departement') : 0;
        $cek[] = array('User dipetakan ke departemen', $nUser && $nMap >= $nUser * 0.8 ? true : ($nMap ? null : false), nf($nMap) . ' dari ' . nf($nUser) . ' user', 'PengaturanDashboard#departemen');
        $nLay = $t('tbl_dashboard_layout') ? $db->where_in('jenis', array('LEVEL', 'DEPT'))->count_all_results('tbl_dashboard_layout') : 0;
        $cek[] = array('Dashboard per level/departemen sudah diatur', $nLay > 0 ? true : null, $nLay ? nf($nLay) . ' pengaturan' : 'memakai preset bawaan', 'PengaturanDashboard');
        if ($t('tbl_hrd_pegawai')) { $n = $db->count_all('tbl_hrd_pegawai'); $tl = $db->where("tgl_lahir <> '0000-00-00' AND tgl_lahir <> '' AND tgl_lahir IS NOT NULL", null, false)->count_all_results('tbl_hrd_pegawai');
            $cek[] = array('Data pegawai: tanggal lahir terisi', $n && $tl === $n, nf($tl) . ' dari ' . nf($n), 'LaporanHR/kelengkapan'); }
        $this->template->load('layoutbackend', 'panduan_sistem', array('page' => 'Panduan Sistem & Integrasi Modul', 'judul' => 'Panduan', 'cek' => $cek));
    }
}
