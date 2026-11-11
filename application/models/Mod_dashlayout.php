<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Penyimpanan & penentuan layout dashboard (USER > DEPT > LEVEL > preset bawaan). */
class Mod_dashlayout extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->config->load('dashboard', true, true);
    }

    public function siap() { return $this->db->table_exists('tbl_dashboard_layout'); }

    public function preset($kode = null)
    {
        $p = (array) $this->config->item('preset', 'dashboard');
        return $kode === null ? $p : (isset($p[$kode]) ? $p[$kode] : null);
    }

    public function get($jenis, $target)
    {
        if ( ! $this->siap()) return null;
        $r = $this->db->get_where('tbl_dashboard_layout', array('jenis' => $jenis, 'target' => (string) $target))->row();
        if ($r) { $d = json_decode((string) $r->data, true); $r->widget = isset($d['widget']) ? (array) $d['widget'] : array(); }
        return $r;
    }

    public function simpan($jenis, $target, array $widget, $bolehUbah = 'N')
    {
        $this->load->library('dashwidget');
        $bersih = array();
        foreach ($widget as $w) {
            $k = is_array($w) ? (string) $w[0] : (string) $w; $l = is_array($w) && isset($w[1]) ? (int) $w[1] : 0;
            if ( ! isset(Dashwidget::$W[$k])) continue;
            if ( ! in_array($l, array(3, 4, 6, 8, 12), true)) $l = Dashwidget::$W[$k][3];
            $bersih[] = array($k, $l);
        }
        $row = array('data' => json_encode(array('widget' => $bersih)), 'boleh_ubah' => $bolehUbah === 'Y' ? 'Y' : 'N',
            'updated_by' => (string) $this->session->userdata('full_name'), 'updated_at' => date('Y-m-d H:i:s'));
        if ($this->get($jenis, $target)) $this->db->where(array('jenis' => $jenis, 'target' => (string) $target))->update('tbl_dashboard_layout', $row);
        else $this->db->insert('tbl_dashboard_layout', $row + array('jenis' => $jenis, 'target' => (string) $target));
        return true;
    }

    public function hapus($jenis, $target) { if ($this->siap()) $this->db->delete('tbl_dashboard_layout', array('jenis' => $jenis, 'target' => (string) $target)); }

    public function departemen_user($idUser)
    {
        if ( ! $this->db->table_exists('tbl_user_departement')) return null;
        $r = $this->db->get_where('tbl_user_departement', array('id_user' => (int) $idUser))->row();
        return $r && (int) $r->id_departement ? (int) $r->id_departement : null;
    }

    public function nama_departemen($id)
    {
        if ( ! $id || ! $this->db->table_exists('tbl_hrd_departement')) return '';
        return (string) $this->db->get_where('tbl_hrd_departement', array('id_departement' => $id))->row('departement');
    }

    /** layout efektif untuk user aktif → [widget, sumber, boleh_ubah] */
    public function untuk($idUser, $idLevel)
    {
        $dept = $this->departemen_user($idUser);
        $sumberIzin = 'N';
        foreach (array(array('LEVEL', $idLevel), array('DEPT', $dept)) as $x) if ($x[1] !== null) { $r = $this->get($x[0], $x[1]); if ($r && $r->boleh_ubah === 'Y') $sumberIzin = 'Y'; }
        if ($r = $this->get('USER', $idUser)) return array($r->widget, 'Pribadi' . ($r->updated_by ? ' (diatur ' . $r->updated_by . ')' : ''), $sumberIzin);
        if ($dept && ($r = $this->get('DEPT', $dept))) return array($r->widget, 'Departemen ' . $this->nama_departemen($dept), $sumberIzin);
        if ($r = $this->get('LEVEL', $idLevel)) return array($r->widget, 'Level ' . $this->nama_level($idLevel), $sumberIzin);
        // preset bawaan: departemen dahulu, lalu level
        $kode = null;
        if ($dept) { $n = strtolower($this->nama_departemen($dept)); foreach ((array) $this->config->item('preset_departemen', 'dashboard') as $kata => $p) if (strpos($n, $kata) !== false) { $kode = $p; break; } }
        if ( ! $kode) { $pl = (array) $this->config->item('preset_level', 'dashboard'); $kode = isset($pl[$idLevel]) ? $pl[$idLevel] : 'UMUM'; }
        $p = $this->preset($kode);
        return array($p['widget'], 'Bawaan: ' . $p['nama'], $sumberIzin);
    }

    public function nama_level($id) { return (string) $this->db->get_where('tbl_userlevel', array('id_level' => (int) $id))->row('nama_level'); }

    public function ringkas_semua()
    {
        $o = array();
        if ($this->siap()) foreach ($this->db->get('tbl_dashboard_layout')->result() as $r) $o[$r->jenis . ':' . $r->target] = $r;
        return $o;
    }

    public function pengumuman()
    {
        $r = $this->get('PENGUMUMAN', '0');
        $d = $r ? json_decode($r->data, true) : array();
        return array('judul' => $d['judul'] ?? '', 'isi' => $d['isi'] ?? '', 'warna' => $d['warna'] ?? 'info', 'sampai' => $d['sampai'] ?? '');
    }

    public function simpan_pengumuman(array $p)
    {
        $d = array('judul' => mb_substr(trim((string) ($p['judul'] ?? '')), 0, 120), 'isi' => mb_substr(trim((string) ($p['isi'] ?? '')), 0, 2000),
            'warna' => in_array($p['warna'] ?? '', array('info', 'success', 'warning', 'danger'), true) ? $p['warna'] : 'info', 'sampai' => tgl_sql($p['sampai'] ?? '') ?: '');
        $row = array('data' => json_encode($d), 'updated_by' => (string) $this->session->userdata('full_name'), 'updated_at' => date('Y-m-d H:i:s'));
        if ($this->get('PENGUMUMAN', '0')) $this->db->where(array('jenis' => 'PENGUMUMAN', 'target' => '0'))->update('tbl_dashboard_layout', $row);
        else $this->db->insert('tbl_dashboard_layout', $row + array('jenis' => 'PENGUMUMAN', 'target' => '0', 'boleh_ubah' => 'N'));
    }

    public function simpan_departemen_user(array $map)
    {
        foreach ($map as $idUser => $x) {
            $idUser = (int) $idUser; $dep = (int) ($x['dept'] ?? 0); $nip = mb_substr(trim((string) ($x['nip'] ?? '')), 0, 30);
            if ($this->db->where('id_user', $idUser)->count_all_results('tbl_user_departement')) $this->db->where('id_user', $idUser)->update('tbl_user_departement', array('id_departement' => $dep, 'nip' => $nip));
            else $this->db->insert('tbl_user_departement', array('id_user' => $idUser, 'id_departement' => $dep, 'nip' => $nip));
        }
    }

    public function user_departemen()
    {
        $map = array();
        if ($this->db->table_exists('tbl_user_departement')) foreach ($this->db->get('tbl_user_departement')->result() as $r) $map[$r->id_user] = $r;
        return $map;
    }
}
