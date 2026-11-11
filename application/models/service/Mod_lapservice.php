<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Data laporan & dokumen cetak After Sales (hanya membaca). */
class Mod_lapservice extends CI_Model
{
    public static $STATUS = array('F' => 'Selesai', 'P' => 'Proses', 'N' => 'Appointment');
    public static $CIW = array('C' => 'Cash', 'I' => 'Internal', 'W' => 'Warranty', 'GW' => 'Goodwill');

    private function t($x) { return $this->db->table_exists($x); }
    public static function tgl_ok($t) { return $t && substr((string) $t, 0, 4) !== '0000' && strtotime($t) !== false; }

    /** daftar WO + ringkasan jam/jasa/part per WO */
    public function daftar(array $f)
    {
        $this->db->from('tbl_after_sales');
        if ( ! empty($f['dari'])) $this->db->where('date_open_wo >=', $f['dari']);
        if ( ! empty($f['sampai'])) $this->db->where('date_open_wo <=', $f['sampai']);
        if ( ! empty($f['status'])) $this->db->where('status', $f['status']);
        if ( ! empty($f['sa'])) $this->db->where('sa_name', $f['sa']);
        if ( ! empty($f['ciw'])) $this->db->where('status_service', $f['ciw']);
        $rows = $this->db->order_by('date_open_wo', 'DESC')->order_by('wo_no', 'DESC')->get()->result();
        if ( ! $rows) return array();
        $wo = array_map(function ($r) { return $r->wo_no; }, $rows);
        $jasa = array(); $jam = array(); $op = array();
        if ($this->t('tbl_after_sales_detail_wo')) foreach (array_chunk($wo, 500) as $c)
            foreach ($this->db->select('wo_no, SUM(total_harga) AS n, SUM(jumlah) AS j, COUNT(*) AS c', false)->where_in('wo_no', $c)->group_by('wo_no')->get('tbl_after_sales_detail_wo')->result() as $x) { $jasa[$x->wo_no] = (float) $x->n; $jam[$x->wo_no] = (float) $x->j; $op[$x->wo_no] = (int) $x->c; }
        $part = array();
        if ($this->t('tbl_af_detail_estimasi_penawaran')) foreach (array_chunk($wo, 500) as $c)
            foreach ($this->db->select('wo_no, SUM(total_harga) AS n', false)->where_in('wo_no', $c)->group_by('wo_no')->get('tbl_af_detail_estimasi_penawaran')->result() as $x) $part[$x->wo_no] = (float) $x->n;
        $mek = array();
        if ($this->t('tbl_after_sales_labor')) foreach (array_chunk($wo, 500) as $c)
            foreach ($this->db->select('wo_no, nama')->where_in('wo_no', $c)->get('tbl_after_sales_labor')->result() as $x) $mek[$x->wo_no][trim((string) $x->nama)] = true;
        $est = array(); $pre = array(); $wor = array();
        if ($this->t('tbl_af_estimasi_penawaran')) foreach (array_chunk($wo, 500) as $c) foreach ($this->db->select('wo_no')->where_in('wo_no', $c)->get('tbl_af_estimasi_penawaran')->result() as $x) $est[$x->wo_no] = true;
        if ($this->t('tbl_after_sales_pre_order')) foreach (array_chunk($wo, 500) as $c) foreach ($this->db->select('wo_no')->where_in('wo_no', $c)->get('tbl_after_sales_pre_order')->result() as $x) $pre[$x->wo_no] = true;
        if ($this->t('tbl_after_sales_work_order')) foreach (array_chunk($wo, 500) as $c) foreach ($this->db->select('wo_no')->where_in('wo_no', $c)->get('tbl_after_sales_work_order')->result() as $x) $wor[$x->wo_no] = true;
        foreach ($rows as $r) {
            $k = $r->wo_no;
            $r->jasa = $jasa[$k] ?? 0; $r->jam = $jam[$k] ?? 0; $r->n_op = $op[$k] ?? 0; $r->part = $part[$k] ?? 0;
            $r->mekanik = isset($mek[$k]) ? implode(', ', array_filter(array_keys($mek[$k]))) : '';
            $r->ada_est = isset($est[$k]); $r->ada_pre = isset($pre[$k]); $r->ada_wo = isset($wor[$k]) || isset($op[$k]);
            $r->hari = self::tgl_ok($r->date_open_wo) && self::tgl_ok($r->date_close_wo) ? max(0, (int) round((strtotime($r->date_close_wo) - strtotime($r->date_open_wo)) / 86400)) : null;
        }
        return $rows;
    }

    public function ringkasan(array $rows)
    {
        $o = (object) array('n' => count($rows), 'selesai' => 0, 'proses' => 0, 'baru' => 0, 'jasa' => 0, 'part' => 0, 'jam' => 0, 'hari' => 0, 'n_hari' => 0,
            'per_sa' => array(), 'per_ciw' => array(), 'per_tgl' => array(), 'per_cus' => array(), 'per_type' => array());
        foreach ($rows as $r) {
            if ($r->status === 'F') $o->selesai++; elseif ($r->status === 'P') $o->proses++; else $o->baru++;
            $o->jasa += $r->jasa; $o->part += $r->part; $o->jam += $r->jam;
            if ($r->hari !== null) { $o->hari += $r->hari; $o->n_hari++; }
            $sa = trim((string) $r->sa_name) ?: '(tanpa SA)'; $o->per_sa[$sa] = ($o->per_sa[$sa] ?? 0) + 1;
            $c = self::$CIW[$r->status_service] ?? ($r->status_service ?: 'Lainnya'); $o->per_ciw[$c] = ($o->per_ciw[$c] ?? 0) + 1;
            $t = substr((string) $r->date_open_wo, 0, 10); $o->per_tgl[$t] = ($o->per_tgl[$t] ?? 0) + 1;
            $cu = trim((string) $r->customer_name) ?: '-'; if ( ! isset($o->per_cus[$cu])) $o->per_cus[$cu] = array(0, 0); $o->per_cus[$cu][0]++; $o->per_cus[$cu][1] += $r->jasa + $r->part;
            $ty = trim((string) $r->type) ?: '-'; $o->per_type[$ty] = ($o->per_type[$ty] ?? 0) + 1;
        }
        arsort($o->per_sa); arsort($o->per_ciw); ksort($o->per_tgl); arsort($o->per_type);
        uasort($o->per_cus, function ($a, $b) { return $b[1] <=> $a[1] ?: $b[0] <=> $a[0]; });
        $o->rata_hari = $o->n_hari ? $o->hari / $o->n_hari : null;
        return $o;
    }

    public function sa_list() { return $this->db->distinct()->select('sa_name')->where("sa_name <> ''", null, false)->order_by('sa_name')->get('tbl_after_sales')->result(); }

    /* ================= dokumen ================= */
    public function wo($wo)
    {
        $r = $this->db->get_where('tbl_after_sales', array('wo_no' => $wo))->row();
        if ( ! $r) return null;
        $r->cus = $this->t('tbl_customer') ? $this->db->get_where('tbl_customer', array('kode_cus' => $r->customer))->row() : null;
        $r->op_wo = $this->t('tbl_after_sales_detail_wo') ? $this->db->order_by('id_detail')->get_where('tbl_after_sales_detail_wo', array('wo_no' => $wo))->result() : array();
        $r->op_pre = $this->t('tbl_after_sales_detail_pre') ? $this->db->order_by('id_detail')->get_where('tbl_after_sales_detail_pre', array('wo_no' => $wo))->result() : array();
        $r->pre = $this->t('tbl_after_sales_pre_order') ? $this->db->get_where('tbl_after_sales_pre_order', array('wo_no' => $wo))->row() : null;
        $r->wo_doc = $this->t('tbl_after_sales_work_order') ? $this->db->get_where('tbl_after_sales_work_order', array('wo_no' => $wo))->row() : null;
        $r->labor = $this->t('tbl_after_sales_labor') ? $this->db->order_by('id_labor')->get_where('tbl_after_sales_labor', array('wo_no' => $wo))->result() : array();
        $r->est = $this->t('tbl_af_estimasi_penawaran') ? $this->db->get_where('tbl_af_estimasi_penawaran', array('wo_no' => $wo))->row() : null;
        $r->est_det = $this->t('tbl_af_detail_estimasi_penawaran') ? $this->db->order_by('id_detail')->get_where('tbl_af_detail_estimasi_penawaran', array('wo_no' => $wo))->result() : array();
        $r->est_note = $this->t('tbl_af_detail_estimasi_penawaran_note') ? $this->db->order_by('id_detail_note')->get_where('tbl_af_detail_estimasi_penawaran_note', array('wo_no' => $wo))->result() : array();
        $r->part_keluar = $this->t('tbl_wh_detail_part_keluar_service') ? $this->db->get_where('tbl_wh_detail_part_keluar_service', array('wo_no' => $wo))->result() : array();
        return $r;
    }

    /** aplikasi (kop) sesuai lokasi user, fallback baris pertama */
    public function kop()
    {
        $l = (string) $this->session->userdata('lokasi');
        $a = $l !== '' ? $this->db->get_where('aplikasi', array('lokasi' => $l))->row() : null;
        return $a ?: $this->db->get('aplikasi', 1)->row();
    }
}
