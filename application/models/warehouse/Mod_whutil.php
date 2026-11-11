<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Utilitas gudang bersama: pengaturan (tbl_wh_setting), perubahan stok dengan jejak
 * (tbl_wh_penyesuaian) dan konversi lokasi.
 */
class Mod_whutil extends CI_Model
{
    private $set = null;

    public function setting($kunci = null, $default = '')
    {
        if ($this->set === null) {
            $this->set = array();
            if ($this->db->table_exists('tbl_wh_setting')) foreach ($this->db->get('tbl_wh_setting')->result() as $r) $this->set[$r->kunci] = $r->nilai;
        }
        if ($kunci === null) return $this->set;
        return isset($this->set[$kunci]) && $this->set[$kunci] !== '' ? $this->set[$kunci] : $default;
    }

    public function simpan_setting(array $data)
    {
        foreach ($data as $k => $v) {
            $k = preg_replace('/[^a-z0-9_]/', '', strtolower($k));
            if ($k === '') continue;
            if ($this->db->where('kunci', $k)->count_all_results('tbl_wh_setting')) $this->db->where('kunci', $k)->update('tbl_wh_setting', array('nilai' => (string) $v));
            else $this->db->insert('tbl_wh_setting', array('kunci' => $k, 'nilai' => (string) $v));
        }
        $this->set = null;
    }

    public function user() { return (string) $this->session->userdata('full_name'); }

    /**
     * Ubah stok satu barang di satu lokasi sebesar $delta dan catat jejaknya.
     * Stok di tabel barang bertipe bilangan bulat; nilai dibulatkan.
     */
    public function ubah_stok($noPart, $lok, $delta, $harga, $sumber, $noDok, $ket, $tgl = null)
    {
        if ( ! in_array($lok, array('cbt', 'jkt', 'sby'), true) || abs($delta) < 0.0005) return false;
        $f = 'stok_' . $lok;
        $this->db->set($f, $f . ' + ' . (int) round($delta), false)->where('no_part', $noPart)->update('tbl_wh_barang');
        if ($this->db->table_exists('tbl_wh_penyesuaian')) {
            $this->db->insert('tbl_wh_penyesuaian', array('tgl' => $tgl ?: date('Y-m-d'), 'lokasi' => $lok, 'no_part' => $noPart, 'qty' => round($delta, 3),
                'harga' => round((float) $harga, 2), 'sumber' => $sumber, 'no_dok' => $noDok, 'keterangan' => mb_substr((string) $ket, 0, 255),
                'user' => $this->user(), 'created_at' => date('Y-m-d H:i:s')));
        }
        return true;
    }
}
