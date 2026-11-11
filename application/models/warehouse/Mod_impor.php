<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Impor master barang dari Excel/CSV.
 * Tahapan: unggah → baca & cocokkan kolom → PRATINJAU (BARU / UPDATE / SAMA / ERROR) → konfirmasi → proses.
 * Sel kosong pada barang yang sudah ada = tidak diubah. Perubahan harga dicatat ke tbl_wh_log_harga,
 * stok awal barang baru dicatat ke tbl_wh_penyesuaian (sumber IMP) agar kartu stok tetap terlacak.
 */
class Mod_impor extends CI_Model
{
    public $error = '';

    /** kolom template => [judul, wajib untuk barang baru, contoh, lebar] */
    public static $KOLOM = array(
        'no_part'     => array('No. part', true, 'QA400 310 00 55', 20),
        'nama_part'   => array('Nama barang', true, 'SHOCK ABSORBER', 34),
        'nama_part_e' => array('Nama (Inggris/alias)', false, '', 22),
        'satuan'      => array('Satuan', false, 'PC', 8),
        'kategori'    => array('Kategori (kode)', false, 'SPAREPART', 14),
        'kelompok'    => array('Kelompok', false, '', 12),
        'type'        => array('Tipe mesin', false, '', 12),
        'kode_sup'    => array('Kode supplier', false, '', 12),
        'stok_min'    => array('Stok minimum', false, 2, 10),
        'std_pakai'   => array('Std. pakai', false, '', 9),
        'lok_rak'     => array('Rak (lokasi simpan)', false, 'D01-CA13', 12),
        'price_list'  => array('Price list', false, 710200, 13),
        'diskon'      => array('Diskon %', false, 40, 9),
        'hrg_net'     => array('Harga net', false, 426120, 13),
        'stok_awal'   => array('Stok awal (barang baru)', false, 0, 12),
    );

    /** alias judul kolom yang dikenali (huruf kecil, tanpa spasi/tanda baca) */
    public static $ALIAS = array(
        'no_part' => array('nopart', 'partnumber', 'partno', 'kodepart', 'kodebarang', 'kode', 'nomorpart', 'sku'),
        'nama_part' => array('namapart', 'namabarang', 'nama', 'description', 'deskripsi', 'partname'),
        'nama_part_e' => array('namaparte', 'namainggris', 'alias', 'namaalias', 'englishname'),
        'satuan' => array('satuan', 'unit', 'uom', 'sat'),
        'kategori' => array('kategori', 'kategorikode', 'category', 'divisi'),
        'kelompok' => array('kelompok', 'group', 'grup'),
        'type' => array('type', 'tipe', 'tipemesin', 'model'),
        'kode_sup' => array('kodesup', 'kodesupplier', 'supplier', 'pemasok'),
        'stok_min' => array('stokmin', 'stokminimum', 'minstock', 'min'),
        'std_pakai' => array('stdpakai', 'standarpakai'),
        'lok_rak' => array('lokrak', 'rak', 'lokasirak', 'raklokasisimpan', 'bin', 'lokasisimpan'),
        'price_list' => array('pricelist', 'hargalist', 'hargajual', 'pl', 'harga'),
        'diskon' => array('diskon', 'disc', 'discount', 'diskonpersen'),
        'hrg_net' => array('hrgnet', 'harganet', 'net', 'netprice', 'hargabeli'),
        'stok_awal' => array('stokawal', 'stokawalbarangbaru', 'stok', 'qty', 'saldoawal', 'jumlah'),
    );

    public static $MODE = array('SEMUA' => 'Tambah barang baru & perbarui yang sudah ada', 'TAMBAH' => 'Hanya tambah barang baru (yang sudah ada dilewati)', 'HARGA' => 'Hanya perbarui harga barang yang sudah ada');

    private static function norm($s) { return preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) $s))); }
    private static function angka($v)
    {
        if (is_int($v) || is_float($v)) return (float) $v;
        $v = trim((string) $v);
        if ($v === '') return null;
        $v = str_replace(array('Rp', 'rp', ' ', '%'), '', $v);
        // 1.234.567,89 (format Indonesia) atau 1,234,567.89
        if (preg_match('/^-?\d{1,3}(\.\d{3})+(,\d+)?$/', $v)) $v = str_replace(array('.', ','), array('', '.'), $v);
        else $v = str_replace(',', '', $v);
        return is_numeric($v) ? (float) $v : false;
    }

    public function template_baris($lok, $semua = false)
    {
        $rows = array(array_keys(self::$KOLOM));
        if ( ! $semua) {
            $c = array(); foreach (self::$KOLOM as $k => $x) $c[] = $x[2]; $rows[] = $c;
            return $rows;
        }
        foreach ($this->db->order_by('no_part')->get('tbl_wh_barang')->result() as $b) {
            $rows[] = array(trim($b->no_part), trim($b->nama_part), $b->nama_part_e, $b->satuan, $b->kategori, $b->kelompok, $b->type, $b->kode_sup,
                $b->stok_min === '' ? '' : (float) $b->stok_min, $b->std_pakai, $b->{'lok_' . $lok}, (float) $b->{'price_list_' . $lok},
                $b->{'discount_' . $lok} === '' ? '' : (float) str_replace(',', '.', rtrim($b->{'discount_' . $lok}, ',')), (float) $b->{'hrg_net_' . $lok}, '');
        }
        return $rows;
    }

    /** baca baris mentah → analisis per baris */
    public function analisa(array $raw, $mode, $lok)
    {
        $this->error = '';
        if (count($raw) < 2) { $this->error = 'File tidak berisi data (minimal baris judul + 1 baris).'; return false; }
        // cari baris judul di 5 baris pertama
        $peta = array(); $baris_judul = 0;
        for ($i = 0; $i < min(5, count($raw)); $i++) {
            $p = array();
            foreach ((array) $raw[$i] as $ci => $h) {
                $n = self::norm($h);
                foreach (self::$ALIAS as $k => $al) if ($n === $k || $n === self::norm($k) || in_array($n, $al, true)) { if ( ! isset($p[$k])) $p[$k] = $ci; break; }
            }
            if (isset($p['no_part'])) { $peta = $p; $baris_judul = $i; break; }
        }
        if ( ! isset($peta['no_part'])) { $this->error = 'Kolom "no_part" tidak ditemukan pada baris judul. Gunakan template yang disediakan.'; return false; }

        $ada = array();
        foreach ($this->db->select('id_part, no_part, nama_part, nama_part_e, satuan, kategori, kelompok, type, kode_sup, stok_min, std_pakai, lok_' . $lok . ' AS lok_rak, price_list_' . $lok . ' AS price_list, discount_' . $lok . ' AS diskon, hrg_net_' . $lok . ' AS hrg_net', false)->get('tbl_wh_barang')->result() as $b) $ada[trim($b->no_part)] = $b;
        $kat = array(); foreach ($this->db->get('tbl_wh_kategori')->result() as $k) { $kat[strtolower(trim($k->kode_kategori))] = $k->kode_kategori; $kat[strtolower(trim($k->kategori))] = $k->kode_kategori; }

        $out = array(); $lihat = array(); $R = array('BARU' => 0, 'UPDATE' => 0, 'SAMA' => 0, 'ERROR' => 0);
        foreach (array_slice($raw, $baris_judul + 1) as $i => $r) {
            $v = array();
            foreach ($peta as $k => $ci) $v[$k] = isset($r[$ci]) ? (is_string($r[$ci]) ? trim($r[$ci]) : $r[$ci]) : '';
            if (implode('', array_map('strval', $v)) === '') continue;
            $no = (string) $v['no_part']; $err = array(); $info = array();
            if ($no === '') $err[] = 'No. part kosong';
            elseif (mb_strlen($no) > 50) $err[] = 'No. part > 50 karakter';
            if (isset($lihat[$no]) && $no !== '') $err[] = 'No. part ganda di file (baris ' . $lihat[$no] . ')';
            $lihat[$no] = $i + $baris_judul + 2;
            foreach (array('stok_min', 'price_list', 'diskon', 'hrg_net', 'stok_awal') as $k) {
                if ( ! isset($v[$k]) || $v[$k] === '') { $v[$k] = null; continue; }
                $n = self::angka($v[$k]);
                if ($n === false) { $err[] = self::$KOLOM[$k][0] . ' bukan angka'; $v[$k] = null; } else $v[$k] = $n;
            }
            if ($v['diskon'] !== null && ($v['diskon'] < 0 || $v['diskon'] > 100)) $err[] = 'Diskon harus 0–100';
            if ($v['price_list'] !== null && $v['price_list'] < 0) $err[] = 'Price list negatif';
            if ($v['hrg_net'] === null && $v['price_list'] !== null) {
                $v['hrg_net'] = round($v['price_list'] * (1 - (float) $v['diskon'] / 100)); $info[] = 'harga net dihitung dari price list − diskon';
            }
            if (isset($v['kategori']) && $v['kategori'] !== '') {
                $kk = strtolower($v['kategori']);
                if (isset($kat[$kk])) $v['kategori'] = $kat[$kk]; else $info[] = 'kategori "' . $v['kategori'] . '" belum ada di master kategori';
            }
            $lama = isset($ada[$no]) ? $ada[$no] : null;
            $ubah = array();
            if ($lama) {
                if ($mode === 'TAMBAH') { $st = 'SAMA'; $info[] = 'sudah terdaftar — dilewati (mode tambah saja)'; }
                else {
                    $kolom = $mode === 'HARGA' ? array('price_list', 'diskon', 'hrg_net') : array_diff(array_keys(self::$KOLOM), array('no_part', 'stok_awal'));
                    foreach ($kolom as $k) {
                        if ( ! array_key_exists($k, $v) || $v[$k] === null || $v[$k] === '') continue;
                        $a = $lama->$k; $b = $v[$k];
                        $beda = in_array($k, array('price_list', 'diskon', 'hrg_net', 'stok_min'), true) ? abs((float) str_replace(',', '.', rtrim((string) $a, ',')) - (float) $b) > 0.004 : trim((string) $a) !== trim((string) $b);
                        if ($beda) $ubah[$k] = array($a, $b);
                    }
                    $st = $ubah ? 'UPDATE' : 'SAMA';
                    if ($v['stok_awal'] !== null && $v['stok_awal'] != 0) $info[] = 'stok awal diabaikan untuk barang yang sudah ada (gunakan Stock Opname)';
                }
            } else {
                if ($mode === 'HARGA') $err[] = 'Barang belum terdaftar (mode hanya harga)';
                if ( ! isset($v['nama_part']) || $v['nama_part'] === '') $err[] = 'Nama barang wajib untuk barang baru';
                $st = 'BARU';
            }
            if ($err) $st = 'ERROR';
            $R[$st]++;
            $out[] = array('baris' => $i + $baris_judul + 2, 'status' => $st, 'v' => $v, 'ubah' => $ubah, 'err' => $err, 'info' => $info, 'id_part' => $lama ? $lama->id_part : null);
        }
        if ( ! $out) { $this->error = 'Tidak ada baris data yang terbaca.'; return false; }
        $tidak = array(); foreach ((array) $raw[$baris_judul] as $h) { $n = self::norm($h); if ($n === '') continue; $kenal = false; foreach ($peta as $k => $ci) if (self::norm($raw[$baris_judul][$ci]) === $n) $kenal = true; if ( ! $kenal) $tidak[] = $h; }
        return array('rows' => $out, 'ringkas' => $R, 'kolom' => array_keys($peta), 'abaikan' => $tidak, 'mode' => $mode, 'lok' => $lok);
    }

    private function dir()
    {
        $d = APPPATH . 'cache/';
        if ( ! is_dir($d)) @mkdir($d, 0775, true);
        return is_dir($d) && is_writable($d) ? $d : rtrim(sys_get_temp_dir(), '/\\') . '/';
    }

    public function simpan_cache($data)
    {
        $tok = bin2hex(random_bytes(8));
        file_put_contents($this->dir() . 'impor_' . $tok . '.json', json_encode($data));
        foreach (glob($this->dir() . 'impor_*.json') ?: array() as $f) if (filemtime($f) < time() - 86400) @unlink($f);
        return $tok;
    }

    public function ambil_cache($tok)
    {
        if ( ! preg_match('/^[a-f0-9]{16}$/', (string) $tok)) return null;
        $f = $this->dir() . 'impor_' . $tok . '.json';
        return is_file($f) ? json_decode(file_get_contents($f), true) : null;
    }

    public function hapus_cache($tok) { if (preg_match('/^[a-f0-9]{16}$/', (string) $tok)) @unlink($this->dir() . 'impor_' . $tok . '.json'); }

    /** jalankan impor dari hasil analisa */
    public function proses(array $A, $namaFile = '')
    {
        $this->load->model('warehouse/Mod_whutil');
        $lok = $A['lok']; $namaLok = Mod_lapgudang::$LOKASI[$lok]; $user = $this->Mod_whutil->user(); $hari = date('Y-m-d');
        $no_dok = 'IMP-' . date('ymd-His');
        $n = array('baru' => 0, 'update' => 0, 'harga' => 0, 'stok' => 0);
        $pot = function ($s, $len) { return mb_substr(trim((string) $s), 0, $len); };
        $this->db->trans_begin();
        foreach ($A['rows'] as $r) {
            $v = $r['v'];
            if ($r['status'] === 'BARU') {
                $row = array('no_part' => $pot($v['no_part'], 50), 'nama_part' => $pot($v['nama_part'], 100), 'nama_part_e' => $pot(isset($v['nama_part_e']) ? $v['nama_part_e'] : '', 100),
                    'satuan' => $pot(isset($v['satuan']) ? $v['satuan'] : '', 25), 'kelompok' => $pot(isset($v['kelompok']) ? $v['kelompok'] : '', 25), 'type' => $pot(isset($v['type']) ? $v['type'] : '', 25),
                    'stok_min' => $v['stok_min'] === null ? '' : (string) (0 + $v['stok_min']), 'harga_forcase' => 0, 'kode_sup' => $pot(isset($v['kode_sup']) ? $v['kode_sup'] : '', 50),
                    'kategori' => $pot(isset($v['kategori']) && $v['kategori'] !== '' ? $v['kategori'] : 'SPAREPART', 25), 'std_pakai' => $pot(isset($v['std_pakai']) ? $v['std_pakai'] : '', 5));
                foreach (array('jkt', 'cbt', 'sby') as $l) { $row['hrg_net_' . $l] = 0; $row['price_list_' . $l] = 0; $row['discount_' . $l] = ''; $row['stok_' . $l] = 0; $row['lok_' . $l] = ''; }
                $row['hrg_net_' . $lok] = round((float) $v['hrg_net']); $row['price_list_' . $lok] = round((float) $v['price_list']);
                $row['discount_' . $lok] = $v['diskon'] === null ? '' : substr((string) (0 + $v['diskon']), 0, 3); $row['lok_' . $lok] = $pot(isset($v['lok_rak']) ? $v['lok_rak'] : '', 25);
                $this->db->insert('tbl_wh_barang', $row);
                $n['baru']++;
                if ($v['stok_awal'] !== null && $v['stok_awal'] != 0) {
                    $this->Mod_whutil->ubah_stok($row['no_part'], $lok, $v['stok_awal'], $row['hrg_net_' . $lok], 'IMP', $no_dok, 'Stok awal impor Excel ' . $namaFile, $hari);
                    $n['stok']++;
                }
            } elseif ($r['status'] === 'UPDATE') {
                $u = array(); $U = $r['ubah'];
                $map = array('lok_rak' => 'lok_' . $lok, 'price_list' => 'price_list_' . $lok, 'diskon' => 'discount_' . $lok, 'hrg_net' => 'hrg_net_' . $lok);
                $len = array('nama_part' => 100, 'nama_part_e' => 100, 'satuan' => 25, 'kategori' => 25, 'kelompok' => 25, 'type' => 25, 'kode_sup' => 50, 'std_pakai' => 5, 'lok_rak' => 25);
                foreach ($U as $k => $x) {
                    $kol = isset($map[$k]) ? $map[$k] : $k; $b = $x[1];
                    if ($k === 'diskon') $b = substr((string) (0 + $b), 0, 3);
                    elseif (in_array($k, array('price_list', 'hrg_net'), true)) $b = round((float) $b);
                    elseif ($k === 'stok_min') $b = (string) (0 + $b);
                    elseif (isset($len[$k])) $b = $pot($b, $len[$k]);
                    $u[$kol] = $b;
                }
                if ( ! $u) continue;
                $this->db->where('id_part', $r['id_part'])->update('tbl_wh_barang', $u);
                $n['update']++;
                if (isset($U['price_list']) || isset($U['hrg_net']) || isset($U['diskon'])) {
                    $lamaB = $this->db->select('hrg_net_' . $lok . ' AS n, price_list_' . $lok . ' AS p')->get_where('tbl_wh_barang', array('id_part' => $r['id_part']))->row();
                    $this->db->insert('tbl_wh_log_harga', array('id_part' => (string) $r['id_part'], 'no_part' => $pot($v['no_part'], 25),
                        'hrg_net_lama' => isset($U['hrg_net']) ? round((float) $U['hrg_net'][0]) : $lamaB->n, 'hrg_net' => $lamaB->n,
                        'hrg_price_list_lama' => isset($U['price_list']) ? round((float) $U['price_list'][0]) : $lamaB->p, 'hrg_price_list' => $lamaB->p,
                        'diskon' => $v['diskon'] === null ? '' : substr((string) (0 + $v['diskon']), 0, 5), 'ppn' => '', 'lokasi' => $namaLok, 'tgl_update' => $hari, 'user' => mb_substr($user, 0, 25)));
                    $n['harga']++;
                }
            }
        }
        if ($this->db->table_exists('tbl_wh_impor')) {
            $this->db->insert('tbl_wh_impor', array('no_dok' => $no_dok, 'tgl' => date('Y-m-d H:i:s'), 'user' => $user, 'file' => mb_substr((string) $namaFile, 0, 150), 'mode' => $A['mode'], 'lokasi' => $lok,
                'baru' => $n['baru'], 'diubah' => $n['update'], 'harga' => $n['harga'], 'dilewati' => $A['ringkas']['SAMA'], 'error' => $A['ringkas']['ERROR']));
        }
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); $this->error = 'Impor gagal, tidak ada data yang tersimpan.'; return false; }
        $this->db->trans_commit();
        return $n + array('no_dok' => $no_dok);
    }

    public function riwayat()
    {
        if ( ! $this->db->table_exists('tbl_wh_impor')) return array();
        return $this->db->order_by('id', 'DESC')->limit(15)->get('tbl_wh_impor')->result();
    }
}
