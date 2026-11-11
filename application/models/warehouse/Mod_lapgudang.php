<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Laporan & analisis gudang. Membaca tabel transaksi yang SUDAH ADA (tidak mengubah alur):
 *  - masuk  : tbl_wh_part_masuk + tbl_wh_detail_part_masuk (Part Masuk PO / Non-PO / retur)
 *  - keluar : tbl_wh_part_keluar + tbl_wh_detail_part_keluar (Part Keluar umum & PK body repair)
 *             tbl_wh_part_keluar_po + tbl_wh_detail_part_keluar_po (Part Keluar PO customer)
 *             tbl_wh_part_keluar_service + tbl_wh_detail_part_keluar_service (Part Keluar WO service)
 *  - stok   : tbl_wh_barang.stok_cbt / stok_jkt / stok_sby (stok sistem saat ini)
 *
 * Kartu stok dihitung MUNDUR dari stok sistem saat ini: saldo awal = stok sekarang − mutasi
 * tercatat sejak tanggal awal. Dengan begitu saldo akhir selalu sama dengan stok sistem;
 * koreksi stok manual tanpa dokumen tercermin di saldo awal.
 */
class Mod_lapgudang extends CI_Model
{
    public static $LOKASI = array('cbt' => 'Cibitung', 'jkt' => 'Jakarta', 'sby' => 'Surabaya');
    public static $JENIS = array('MPO' => 'Masuk PO', 'MNP' => 'Masuk Non-PO', 'RTR' => 'Retur masuk', 'KU' => 'Keluar umum / PK',
        'KPO' => 'Keluar PO customer', 'KWO' => 'Keluar WO service', 'ADJ' => 'Penyesuaian (opname / impor)');

    private $barangCache = null;

    /** normalisasi teks lokasi ('CBT|Cibitung', 'Surabaya', 'SBY', ...) */
    public static function lok($s)
    {
        $s = strtolower((string) $s);
        if ($s === '') return '';
        if (strpos($s, 'cbt') !== false || strpos($s, 'cib') !== false) return 'cbt';
        if (strpos($s, 'sby') !== false || strpos($s, 'sur') !== false) return 'sby';
        if (strpos($s, 'jkt') !== false || strpos($s, 'jak') !== false) return 'jkt';
        return '';
    }

    private static function angka($v)
    {
        if ($v === null || $v === '') return 0.0;
        if (is_numeric($v)) return (float) $v;
        $v = str_replace(array(' ', ','), '', (string) $v);
        return is_numeric($v) ? (float) $v : 0.0;
    }

    /* ==================================================================
     * MASTER BARANG
     * ================================================================ */
    public function barang()
    {
        if ($this->barangCache !== null) return $this->barangCache;
        $q = $this->db->select('b.no_part, b.nama_part, b.satuan, b.kategori, b.kelompok, b.stok_min, b.stok_cbt, b.stok_jkt, b.stok_sby, b.hrg_net_cbt, b.hrg_net_jkt, b.hrg_net_sby, b.lok_cbt, b.lok_jkt, b.lok_sby, b.kode_sup, k.kategori AS nama_kategori', false)
            ->from('tbl_wh_barang b')->join('tbl_wh_kategori k', 'k.kode_kategori = b.kategori', 'left')->get()->result();
        $out = array();
        foreach ($q as $r) {
            foreach (array('stok_cbt', 'stok_jkt', 'stok_sby', 'stok_min', 'hrg_net_cbt', 'hrg_net_jkt', 'hrg_net_sby') as $f) $r->$f = self::angka($r->$f);
            $r->nama_kategori = $r->nama_kategori ?: ($r->kategori ?: 'Tanpa kategori');
            $out[trim($r->no_part)] = $r;
        }
        // metode penilaian RATA (rata-rata bergerak) — lihat Mod_hargarata
        if ($this->db->table_exists('tbl_wh_setting') && $this->db->table_exists('tbl_wh_harga_rata')) {
            $m = $this->db->get_where('tbl_wh_setting', array('kunci' => 'metode_nilai'))->row();
            if ($m && $m->nilai === 'RATA') {
                foreach ($this->db->select('no_part, lokasi, harga_rata')->get('tbl_wh_harga_rata')->result() as $h) {
                    $k = trim($h->no_part);
                    if (isset($out[$k]) && in_array($h->lokasi, array('cbt', 'jkt', 'sby'), true)) $out[$k]->{'rata_' . $h->lokasi} = (float) $h->harga_rata;
                }
            }
        }
        return $this->barangCache = $out;
    }

    public static function stok($b, $lok)
    {
        if ($lok !== '') return $b->{'stok_' . $lok};
        return $b->stok_cbt + $b->stok_jkt + $b->stok_sby;
    }

    public static function harga($b, $lok)
    {
        if ($lok !== '' && ! empty($b->{'rata_' . $lok})) return $b->{'rata_' . $lok};
        if ($lok !== '' && $b->{'hrg_net_' . $lok} > 0) return $b->{'hrg_net_' . $lok};
        foreach (array('cbt', 'jkt', 'sby') as $l) if ($b->{'hrg_net_' . $l} > 0) return $b->{'hrg_net_' . $l};
        return 0.0;
    }

    public function kategori_list()
    {
        return $this->db->order_by('kategori')->get('tbl_wh_kategori')->result();
    }

    public function cari_part($q, $limit = 20)
    {
        $q = trim((string) $q);
        $this->db->select('no_part, nama_part')->from('tbl_wh_barang');
        if ($q !== '') $this->db->group_start()->like('no_part', $q)->or_like('nama_part', $q)->group_end();
        return $this->db->order_by('no_part')->limit($limit)->get()->result();
    }

    /* ==================================================================
     * MUTASI (semua sumber)
     * ================================================================ */
    /**
     * @return object[] {tgl, no_part, masuk, keluar, harga, lok, jenis, ref, ket}
     */
    public function gerakan($dari = null, $sampai = null, $noPart = null, $lok = '')
    {
        $out = array();
        $filter = function ($kolTgl, $kolPart) use ($dari, $sampai, $noPart) {
            if ($dari) $this->db->where($kolTgl . ' >=', $dari);
            if ($sampai) $this->db->where($kolTgl . ' <=', $sampai);
            if ($noPart !== null && $noPart !== '') $this->db->where($kolPart, $noPart);
        };
        $tambah = function ($tgl, $part, $masuk, $keluar, $harga, $lokRaw, $jenis, $ref, $ket, $sup = '') use (&$out, $lok) {
            $l = self::lok($lokRaw);
            if ($lok !== '' && $l !== $lok) return;
            if ($masuk == 0 && $keluar == 0) return;
            $out[] = (object) array('tgl' => substr((string) $tgl, 0, 10), 'no_part' => trim((string) $part), 'masuk' => $masuk, 'keluar' => $keluar,
                'harga' => $harga, 'lok' => $l, 'jenis' => $jenis, 'ref' => (string) $ref, 'ket' => trim((string) $ket), 'sup' => trim((string) $sup));
        };

        // masuk
        $this->db->select('d.tgl_masuk, d.no_part, d.jumlah, d.hrg_part, d.lokasi, d.part_return, d.id_masuk, h.lokasi AS h_lokasi, h.status_po, h.no_po, h.no_sj_sup, h.nama_supplier, h.part_return AS h_return', false)
            ->from('tbl_wh_detail_part_masuk d')->join('tbl_wh_part_masuk h', 'h.id_masuk = d.id_masuk', 'left');
        $filter('d.tgl_masuk', 'd.no_part');
        foreach ($this->db->get()->result() as $r) {
            $ret = $r->part_return === 'Y' || $r->h_return === 'Y';
            $jenis = $ret ? 'RTR' : (($r->status_po === 'Y' || trim((string) $r->no_po) !== '') ? 'MPO' : 'MNP');
            $tambah($r->tgl_masuk, $r->no_part, self::angka($r->jumlah), 0, self::angka($r->hrg_part), $r->lokasi ?: $r->h_lokasi, $jenis,
                $r->id_masuk, implode(' · ', array_filter(array(trim((string) $r->nama_supplier), $r->no_sj_sup ? 'SJ ' . $r->no_sj_sup : '', $r->no_po ? 'PO ' . $r->no_po : ''))), $r->nama_supplier);
        }
        // keluar umum / PK
        $this->db->select('d.tgl_keluar, d.no_part, d.jumlah, d.hrg_part, d.lokasi, d.id_keluar, d.nama_divisi, h.lokasi AS h_lokasi, h.tujuan, h.no_pk, h.no_body, h.nama_divisi AS h_divisi', false)
            ->from('tbl_wh_detail_part_keluar d')->join('tbl_wh_part_keluar h', 'h.id_keluar = d.id_keluar', 'left');
        $filter('d.tgl_keluar', 'd.no_part');
        foreach ($this->db->get()->result() as $r) {
            $ket = trim(($r->tujuan ?: '') . ($r->no_pk ? ' · PK ' . $r->no_pk : '') . ($r->no_body ? ' · Body ' . $r->no_body : '') . (($r->nama_divisi ?: $r->h_divisi) ? ' · ' . ($r->nama_divisi ?: $r->h_divisi) : ''), ' ·');
            $tambah($r->tgl_keluar, $r->no_part, 0, self::angka($r->jumlah), self::angka($r->hrg_part), $r->lokasi ?: $r->h_lokasi, 'KU', $r->id_keluar, $ket);
        }
        // keluar PO customer
        if ($this->db->table_exists('tbl_wh_detail_part_keluar_po')) {
            $this->db->select('d.tgl_keluar, d.no_part, d.jumlah, d.harga, d.lokasi, d.kode_keluar, h.nama_cus, h.kode_po, h.no_sj', false)
                ->from('tbl_wh_detail_part_keluar_po d')->join('tbl_wh_part_keluar_po h', 'h.kode_keluar = d.kode_keluar', 'left');
            $filter('d.tgl_keluar', 'd.no_part');
            foreach ($this->db->get()->result() as $r) {
                $tambah($r->tgl_keluar, $r->no_part, 0, self::angka($r->jumlah), self::angka($r->harga), $r->lokasi, 'KPO', $r->kode_keluar,
                    trim(($r->nama_cus ?: '') . ($r->kode_po ? ' · ' . $r->kode_po : '') . ($r->no_sj ? ' · SJ ' . $r->no_sj : ''), ' ·'));
            }
        }
        // keluar WO service
        if ($this->db->table_exists('tbl_wh_detail_part_keluar_service')) {
            $this->db->select('d.tgl_keluar, d.no_part, d.jumlah, d.jml_keluar, d.harga, d.lokasi, d.kode_keluar, d.wo_no', false)
                ->from('tbl_wh_detail_part_keluar_service d');
            $filter('d.tgl_keluar', 'd.no_part');
            foreach ($this->db->get()->result() as $r) {
                $q = self::angka($r->jml_keluar); if ($q <= 0) $q = self::angka($r->jumlah);
                $tambah($r->tgl_keluar, $r->no_part, 0, $q, self::angka($r->harga), $r->lokasi, 'KWO', $r->kode_keluar, 'WO ' . $r->wo_no);
            }
        }
        // penyesuaian stok (stock opname baru, stok awal impor Excel)
        if ($this->db->table_exists('tbl_wh_penyesuaian')) {
            $this->db->select('tgl, no_part, qty, harga, lokasi, sumber, no_dok, keterangan')->from('tbl_wh_penyesuaian');
            $filter('tgl', 'no_part');
            foreach ($this->db->get()->result() as $r) {
                $q = self::angka($r->qty);
                $tambah($r->tgl, $r->no_part, $q > 0 ? $q : 0, $q < 0 ? -$q : 0, self::angka($r->harga), $r->lokasi, 'ADJ', $r->no_dok, $r->keterangan);
            }
        }
        usort($out, function ($a, $b) { return strcmp($a->tgl . $a->ref, $b->tgl . $b->ref); });
        return $out;
    }

    /* ==================================================================
     * KARTU STOK
     * ================================================================ */
    public function kartu_stok($noPart, $lok, $dari, $sampai)
    {
        $B = $this->barang();
        $noPart = trim((string) $noPart);
        if ($noPart === '' || ! isset($B[$noPart])) return null;
        $b = $B[$noPart];
        $sejak = $this->gerakan($dari, null, $noPart, $lok);
        $netSejak = 0; $netSetelah = 0; $rows = array();
        foreach ($sejak as $g) {
            $netSejak += $g->masuk - $g->keluar;
            if ($g->tgl > $sampai) $netSetelah += $g->masuk - $g->keluar; else $rows[] = $g;
        }
        $stokKini = self::stok($b, $lok);
        $awal = $stokKini - $netSejak;
        $s = $awal; $tm = 0; $tk = 0;
        foreach ($rows as $g) { $s += $g->masuk - $g->keluar; $g->saldo = $s; $tm += $g->masuk; $tk += $g->keluar; }
        $harga = self::harga($b, $lok);
        return (object) array('barang' => $b, 'awal' => $awal, 'rows' => $rows, 'masuk' => $tm, 'keluar' => $tk, 'akhir' => $s,
            'stok_kini' => $stokKini, 'setelah' => $netSetelah, 'harga' => $harga, 'nilai_akhir' => $s * $harga,
            'rak' => $lok !== '' ? $b->{'lok_' . $lok} : trim($b->lok_cbt . ' ' . $b->lok_jkt . ' ' . $b->lok_sby));
    }

    /* ==================================================================
     * MUTASI STOK (ringkasan per barang)
     * ================================================================ */
    public function mutasi($dari, $sampai, $lok, $kategori = '', $hanyaBergerak = true)
    {
        $B = $this->barang();
        $acc = array();
        foreach ($this->gerakan($dari, null, null, $lok) as $g) {
            $k = $g->no_part;
            if ( ! isset($acc[$k])) $acc[$k] = array('net' => 0.0, 'm' => 0.0, 'k' => 0.0, 'nk' => 0.0);
            $acc[$k]['net'] += $g->masuk - $g->keluar;
            if ($g->tgl <= $sampai) { $acc[$k]['m'] += $g->masuk; $acc[$k]['k'] += $g->keluar; }
        }
        $rows = array(); $T = (object) array('awal' => 0, 'masuk' => 0, 'keluar' => 0, 'akhir' => 0, 'nilai' => 0, 'n' => 0, 'tanpa_master' => 0);
        $keys = $hanyaBergerak ? array_keys($acc) : array_keys($B);
        foreach ($keys as $k) {
            if ( ! isset($B[$k])) { $T->tanpa_master++; continue; }
            $b = $B[$k];
            if ($kategori !== '' && (string) $b->kategori !== $kategori) continue;
            $a = isset($acc[$k]) ? $acc[$k] : array('net' => 0, 'm' => 0, 'k' => 0);
            if ($hanyaBergerak && $a['m'] == 0 && $a['k'] == 0) continue;
            $kini = self::stok($b, $lok);
            $awal = $kini - $a['net'];
            $akhir = $awal + $a['m'] - $a['k'];
            if ( ! $hanyaBergerak && $awal == 0 && $akhir == 0) continue;
            $h = self::harga($b, $lok);
            $rows[] = (object) array('no_part' => $k, 'nama' => $b->nama_part, 'satuan' => $b->satuan, 'kategori' => $b->nama_kategori,
                'awal' => $awal, 'masuk' => $a['m'], 'keluar' => $a['k'], 'akhir' => $akhir, 'harga' => $h, 'nilai' => $akhir * $h);
            $T->awal += $awal; $T->masuk += $a['m']; $T->keluar += $a['k']; $T->akhir += $akhir; $T->nilai += $akhir * $h; $T->n++;
        }
        usort($rows, function ($a, $b) { return strcmp($a->kategori . $a->no_part, $b->kategori . $b->no_part); });
        return array('rows' => $rows, 'total' => $T);
    }

    /* ==================================================================
     * STOK MINIMUM & SARAN PEMESANAN
     * ================================================================ */
    public function dipesan()
    {
        // baris Part Order ke pemasok yang belum diterima penuh
        $o = array();
        if ( ! $this->db->table_exists('tbl_wh_detail_part_order')) return $o;
        foreach ($this->db->select('no_part, sisa, jumlah, jml_masuk')->get('tbl_wh_detail_part_order')->result() as $r) {
            $s = self::angka($r->sisa);
            if ($s <= 0) $s = self::angka($r->jumlah) - self::angka($r->jml_masuk);
            if ($s <= 0) continue;
            $k = trim($r->no_part);
            $o[$k] = (isset($o[$k]) ? $o[$k] : 0) + $s;
        }
        return $o;
    }

    public function stok_minimum($lok, $kategori = '', $semua = false)
    {
        $B = $this->barang(); $P = $this->dipesan();
        $rows = array(); $T = (object) array('n' => 0, 'kosong' => 0, 'nilai_saran' => 0);
        foreach ($B as $k => $b) {
            if ($kategori !== '' && (string) $b->kategori !== $kategori) continue;
            if ($b->stok_min <= 0) continue;
            $s = self::stok($b, $lok);
            if ( ! $semua && $s > $b->stok_min) continue;
            $p = isset($P[$k]) ? $P[$k] : 0;
            $saran = max(0, 2 * $b->stok_min - $s - $p);
            $h = self::harga($b, $lok);
            $rows[] = (object) array('no_part' => $k, 'nama' => $b->nama_part, 'satuan' => $b->satuan, 'kategori' => $b->nama_kategori,
                'min' => $b->stok_min, 'stok' => $s, 'dipesan' => $p, 'kurang' => max(0, $b->stok_min - $s), 'saran' => $saran, 'harga' => $h, 'nilai' => $saran * $h,
                'status' => $s <= 0 ? 'HABIS' : ($s <= $b->stok_min ? 'DI BAWAH MIN' : 'AMAN'));
            $T->n++; if ($s <= 0) $T->kosong++; $T->nilai_saran += $saran * $h;
        }
        usort($rows, function ($a, $b) { return ($a->stok - $a->min) <=> ($b->stok - $b->min); });
        return array('rows' => $rows, 'total' => $T, 'tanpa_min' => count(array_filter($B, function ($b) { return $b->stok_min <= 0; })));
    }

    /* ==================================================================
     * SLOW MOVING / DEAD STOCK
     * ================================================================ */
    public function slow_moving($lok, $hari, $tgl)
    {
        $B = $this->barang();
        $last = array(); $lastIn = array();
        foreach ($this->gerakan(null, $tgl, null, $lok) as $g) {
            if ($g->keluar > 0 && $g->jenis !== 'ADJ') $last[$g->no_part] = $g->tgl;
            if ($g->masuk > 0) $lastIn[$g->no_part] = $g->tgl;
        }
        $batas = date('Y-m-d', strtotime($tgl . ' -' . (int) $hari . ' day'));
        $rows = array(); $T = (object) array('n' => 0, 'nilai' => 0, 'mati' => 0, 'nilai_mati' => 0);
        foreach ($B as $k => $b) {
            $s = self::stok($b, $lok);
            if ($s <= 0) continue;
            $l = isset($last[$k]) ? $last[$k] : null;
            if ($l !== null && $l > $batas) continue;
            $h = self::harga($b, $lok);
            $umur = $l ? (int) floor((strtotime($tgl) - strtotime($l)) / 86400) : null;
            $rows[] = (object) array('no_part' => $k, 'nama' => $b->nama_part, 'satuan' => $b->satuan, 'kategori' => $b->nama_kategori,
                'stok' => $s, 'harga' => $h, 'nilai' => $s * $h, 'terakhir_keluar' => $l, 'terakhir_masuk' => isset($lastIn[$k]) ? $lastIn[$k] : null,
                'hari' => $umur, 'kelas' => $l === null ? 'TIDAK PERNAH KELUAR' : ($umur > 2 * $hari ? 'SANGAT LAMBAT' : 'LAMBAT'));
            $T->n++; $T->nilai += $s * $h;
            if ($l === null) { $T->mati++; $T->nilai_mati += $s * $h; }
        }
        usort($rows, function ($a, $b) { return $b->nilai <=> $a->nilai; });
        return array('rows' => $rows, 'total' => $T, 'batas' => $batas);
    }

    /* ==================================================================
     * ANALISIS ABC (berdasarkan nilai pemakaian)
     * ================================================================ */
    public function abc($dari, $sampai, $lok)
    {
        $B = $this->barang();
        $u = array();
        foreach ($this->gerakan($dari, $sampai, null, $lok) as $g) {
            if ($g->keluar <= 0 || $g->jenis === 'ADJ') continue;
            $k = $g->no_part;
            $h = $g->harga > 0 ? $g->harga : (isset($B[$k]) ? self::harga($B[$k], $lok) : 0);
            if ( ! isset($u[$k])) $u[$k] = array('qty' => 0, 'nilai' => 0, 'n' => 0);
            $u[$k]['qty'] += $g->keluar; $u[$k]['nilai'] += $g->keluar * $h; $u[$k]['n']++;
        }
        uasort($u, function ($a, $b) { return $b['nilai'] <=> $a['nilai']; });
        $total = 0; foreach ($u as $x) $total += $x['nilai'];
        $rows = array(); $kum = 0; $kelas = array('A' => array(0, 0), 'B' => array(0, 0), 'C' => array(0, 0));
        foreach ($u as $k => $x) {
            $sebelum = $total > 0 ? $kum / $total * 100 : 100;
            $kum += $x['nilai'];
            $pk = $total > 0 ? $kum / $total * 100 : 100;
            $c = $sebelum < 80 ? 'A' : ($sebelum < 95 ? 'B' : 'C');
            $kelas[$c][0]++; $kelas[$c][1] += $x['nilai'];
            $b = isset($B[$k]) ? $B[$k] : null;
            $rows[] = (object) array('no_part' => $k, 'nama' => $b ? $b->nama_part : '(tidak ada di master)', 'kategori' => $b ? $b->nama_kategori : '-',
                'qty' => $x['qty'], 'transaksi' => $x['n'], 'nilai' => $x['nilai'], 'persen' => $total > 0 ? $x['nilai'] / $total * 100 : 0, 'kumulatif' => $pk, 'kelas' => $c,
                'stok' => $b ? self::stok($b, $lok) : 0);
        }
        return array('rows' => $rows, 'total' => $total, 'kelas' => $kelas);
    }

    /* ==================================================================
     * PEMAKAIAN / PENGELUARAN
     * ================================================================ */
    public function pemakaian($dari, $sampai, $lok)
    {
        $B = $this->barang();
        $perJenis = array(); $perKat = array(); $perTujuan = array(); $perPart = array(); $T = (object) array('qty' => 0, 'nilai' => 0, 'baris' => 0);
        $tambah = function (array &$arr, $key, $q, $v) {
            if ( ! isset($arr[$key])) $arr[$key] = array('qty' => 0, 'nilai' => 0, 'n' => 0);
            $arr[$key]['qty'] += $q; $arr[$key]['nilai'] += $v; $arr[$key]['n']++;
        };
        foreach ($this->gerakan($dari, $sampai, null, $lok) as $g) {
            if ($g->keluar <= 0 || $g->jenis === 'ADJ') continue;
            $b = isset($B[$g->no_part]) ? $B[$g->no_part] : null;
            $h = $g->harga > 0 ? $g->harga : ($b ? self::harga($b, $lok) : 0);
            $v = $g->keluar * $h;
            $j = self::$JENIS[$g->jenis];
            $kat = $b ? $b->nama_kategori : 'Tanpa master';
            $tuj = $g->ket !== '' ? preg_replace('/\s*·.*$/', '', $g->ket) : '-';
            $tambah($perJenis, $j, $g->keluar, $v);
            $tambah($perKat, $kat, $g->keluar, $v);
            $tambah($perTujuan, $tuj ?: '-', $g->keluar, $v);
            if ( ! isset($perPart[$g->no_part])) $perPart[$g->no_part] = array('nama' => $b ? $b->nama_part : '', 'qty' => 0, 'nilai' => 0, 'satuan' => $b ? $b->satuan : '');
            $perPart[$g->no_part]['qty'] += $g->keluar; $perPart[$g->no_part]['nilai'] += $v;
            $T->qty += $g->keluar; $T->nilai += $v; $T->baris++;
        }
        $urut = function (&$a) { uasort($a, function ($x, $y) { return $y['nilai'] <=> $x['nilai']; }); };
        $urut($perJenis); $urut($perKat); $urut($perTujuan); $urut($perPart);
        return array('jenis' => $perJenis, 'kategori' => $perKat, 'tujuan' => array_slice($perTujuan, 0, 25, true), 'part' => array_slice($perPart, 0, 30, true), 'total' => $T);
    }

    /* ==================================================================
     * PENERIMAAN PER SUPPLIER
     * ================================================================ */
    public function penerimaan($dari, $sampai, $lok)
    {
        $S = array(); $T = (object) array('dok' => 0, 'baris' => 0, 'qty' => 0, 'nilai' => 0);
        $dok = array();
        foreach ($this->gerakan($dari, $sampai, null, $lok) as $g) {
            if ($g->masuk <= 0 || $g->jenis === 'ADJ') continue;
            $sup = $g->sup !== '' ? $g->sup : '(tanpa nama supplier)';
            if ( ! isset($S[$sup])) $S[$sup] = array('dok' => array(), 'baris' => 0, 'qty' => 0, 'nilai' => 0, 'MPO' => 0, 'MNP' => 0, 'RTR' => 0, 'terakhir' => '');
            $v = $g->masuk * $g->harga;
            $S[$sup]['dok'][$g->ref] = true; $S[$sup]['baris']++; $S[$sup]['qty'] += $g->masuk; $S[$sup]['nilai'] += $v; $S[$sup][$g->jenis] += $v;
            if ($g->tgl > $S[$sup]['terakhir']) $S[$sup]['terakhir'] = $g->tgl;
            $dok[$g->ref] = true; $T->baris++; $T->qty += $g->masuk; $T->nilai += $v;
        }
        $T->dok = count($dok);
        foreach ($S as $k => $x) $S[$k]['dok'] = count($x['dok']);
        uasort($S, function ($a, $b) { return $b['nilai'] <=> $a['nilai']; });
        return array('rows' => $S, 'total' => $T);
    }

    /* ==================================================================
     * PO OUTSTANDING (ke pemasok & dari customer)
     * ================================================================ */
    public function outstanding($tgl)
    {
        $B = $this->barang();
        $sup = array(); foreach ($this->db->select('kode_sup, nama_sup')->get('tbl_wh_supplier')->result() as $r) $sup[$r->kode_sup] = $r->nama_sup;
        $cus = array(); foreach ($this->db->select('kode_cus, nama_cus')->get('tbl_wh_customer')->result() as $r) $cus[$r->kode_cus] = $r->nama_cus;
        $hasil = array('AP' => array(), 'AR' => array());
        $t0 = strtotime($tgl);
        $pasang = array(
            'AP' => array('tbl_wh_part_order', 'kode_part_order', 'tgl_part_order', 'supplier', 'tbl_wh_detail_part_order', 'id_part_order', $sup),
            'AR' => array('tbl_wh_po_masuk', 'kode_po', 'tgl_po', 'customer', 'tbl_wh_detail_po_masuk', 'id_po_masuk', $cus),
        );
        foreach ($pasang as $j => $c) {
            if ( ! $this->db->table_exists($c[0]) || ! $this->db->table_exists($c[4])) continue;
            $H = array();
            foreach ($this->db->get($c[0])->result() as $h) { $kode = $h->{$c[1]}; $H[strtok($kode, '/')] = $h; }
            foreach ($this->db->get($c[4])->result() as $d) {
                $sisa = self::angka($d->sisa); $jml = self::angka($d->jumlah); $masuk = self::angka($d->jml_masuk);
                if ($sisa <= 0) $sisa = $jml - $masuk;
                if ($sisa <= 0 || $jml <= 0) continue;
                $key = trim((string) $d->{$c[5]});
                $h = isset($H[$key]) ? $H[$key] : null;
                $tglH = $h ? $h->{$c[2]} : '';
                if ($tglH && $tglH > $tgl) continue;
                $hari = ($tglH && $tglH !== '0000-00-00') ? (int) floor(($t0 - strtotime($tglH)) / 86400) : null;
                $kontak = $h ? $h->{$c[3]} : '';
                $hasil[$j][] = (object) array('no' => $h ? $h->{$c[1]} : $key, 'tgl' => $tglH, 'hari' => $hari,
                    'kontak' => isset($c[6][$kontak]) ? $c[6][$kontak] : $kontak, 'no_part' => trim($d->no_part),
                    'nama' => $d->nama_part ?: (isset($B[trim($d->no_part)]) ? $B[trim($d->no_part)]->nama_part : ''),
                    'jumlah' => $jml, 'diterima' => $masuk, 'sisa' => $sisa, 'harga' => self::angka($d->harga), 'nilai' => $sisa * self::angka($d->harga),
                    'bo' => isset($d->bo) ? $d->bo : '');
            }
            usort($hasil[$j], function ($a, $b) { return ($b->hari ?? -1) <=> ($a->hari ?? -1); });
        }
        return $hasil;
    }

    /* ==================================================================
     * SELISIH STOCK OPNAME
     * ================================================================ */
    public function opname_list()
    {
        $lama = $this->db->select('id_opname, MIN(tgl_opname) AS tgl, GROUP_CONCAT(kelompok) AS kelompok, MIN(status) AS status', false)
            ->group_by('id_opname')->order_by('tgl', 'DESC')->limit(60)->get('tbl_wh_opname')->result();
        // dokumen Stock Opname baru (menu OpnameStok) — kode "SO-…"
        if ($this->db->table_exists('tbl_wh_so')) {
            $baru = array();
            foreach ($this->db->where_in('status', array('REVIEW', 'DISETUJUI'))->order_by('tgl', 'DESC')->limit(60)->get('tbl_wh_so')->result() as $r)
                $baru[] = (object) array('id_opname' => $r->no_so, 'tgl' => $r->tgl, 'kelompok' => self::$LOKASI[$r->lokasi] . ' · ' . strtolower($r->status), 'status' => $r->status);
            $lama = array_merge($baru, $lama);
            usort($lama, function ($a, $b) { return strcmp($b->tgl, $a->tgl); });
        }
        return $lama;
    }

    public function selisih_opname($idOpname, $lok)
    {
        $B = $this->barang();
        $h = $this->db->get_where('tbl_wh_opname', array('id_opname' => $idOpname))->result();
        $rows = array(); $T = (object) array('item' => 0, 'selisih_item' => 0, 'lebih' => 0, 'kurang' => 0, 'nilai_lebih' => 0, 'nilai_kurang' => 0);
        if (strpos((string) $idOpname, 'SO-') === 0 && $this->db->table_exists('tbl_wh_so')) {
            $so = $this->db->get_where('tbl_wh_so', array('no_so' => $idOpname))->row();
            if ($so) {
                foreach ($this->db->get_where('tbl_wh_so_detail', array('id_so' => $so->id))->result() as $d) {
                    $T->item++;
                    if ($d->fisik === null) continue;
                    $sel = (float) $d->fisik - (float) $d->stok_snapshot;
                    if (abs($sel) < 0.0001) continue;
                    $hg = (float) $d->harga;
                    $rows[] = (object) array('no_part' => $d->no_part, 'nama' => $d->nama_part, 'kelompok' => $d->kategori, 'rak' => $d->rak, 'satuan' => $d->satuan,
                        'lama' => (float) $d->stok_snapshot, 'fisik' => (float) $d->fisik, 'selisih' => $sel, 'harga' => $hg, 'nilai' => $sel * $hg);
                    $T->selisih_item++;
                    if ($sel > 0) { $T->lebih++; $T->nilai_lebih += $sel * $hg; } else { $T->kurang++; $T->nilai_kurang += $sel * $hg; }
                }
            }
            usort($rows, function ($a, $b) { return abs($b->nilai) <=> abs($a->nilai); });
            return array('header' => $so ? array($so) : array(), 'rows' => $rows, 'total' => $T);
        }
        foreach ($this->db->get_where('tbl_wh_detail_opname', array('id_opname' => $idOpname))->result() as $d) {
            $T->item++;
            $lama = self::angka($d->stok_lama); $fisik = self::angka($d->stok_fisik);
            $sel = $fisik - $lama;
            if (abs($sel) < 0.0001) continue;
            $b = isset($B[trim($d->no_part)]) ? $B[trim($d->no_part)] : null;
            $hg = $b ? self::harga($b, $lok) : 0;
            $rows[] = (object) array('no_part' => $d->no_part, 'nama' => $d->nama_part, 'kelompok' => $d->kelompok, 'rak' => trim((string) $d->lokasi),
                'satuan' => $d->satuan, 'lama' => $lama, 'fisik' => $fisik, 'selisih' => $sel, 'harga' => $hg, 'nilai' => $sel * $hg);
            $T->selisih_item++;
            if ($sel > 0) { $T->lebih++; $T->nilai_lebih += $sel * $hg; } else { $T->kurang++; $T->nilai_kurang += $sel * $hg; }
        }
        usort($rows, function ($a, $b) { return abs($b->nilai) <=> abs($a->nilai); });
        return array('header' => $h, 'rows' => $rows, 'total' => $T);
    }

    /* ==================================================================
     * PEMERIKSAAN DATA GUDANG
     * ================================================================ */
    public function cek_data()
    {
        $B = $this->barang();
        $C = array();
        $add = function ($judul, $status, $pesan, $detail = array(), $link = '', $saran = '') use (&$C) {
            $C[] = (object) array('judul' => $judul, 'status' => $status, 'pesan' => $pesan, 'detail' => $detail, 'link' => $link, 'saran' => $saran);
        };
        foreach (self::$LOKASI as $l => $nm) {
            $det = array(); foreach ($B as $k => $b) if ($b->{'stok_' . $l} < 0) $det[] = $k . ' ' . $b->nama_part . ': ' . nf($b->{'stok_' . $l}, 2);
            $add('Stok ' . $nm . ' tidak minus', $det ? 'bad' : 'ok', $det ? count($det) . ' barang bersaldo minus.' : 'Tidak ada stok minus.', $det, 'Sparepart',
                'Stok minus berarti pengeluaran dicatat sebelum penerimaan, atau penerimaan belum diinput. Lengkapi Part Masuk lalu cek ulang.');
        }
        $dup = $this->db->select('no_part, COUNT(*) AS n', false)->group_by('no_part')->having('COUNT(*) >', 1)->get('tbl_wh_barang')->result();
        $add('No. part unik', $dup ? 'bad' : 'ok', $dup ? count($dup) . ' nomor part terdaftar ganda.' : 'Semua nomor part unik.', array_map(function ($r) { return $r->no_part . ' (' . $r->n . '×)'; }, $dup), 'Sparepart',
            'Nomor part ganda membuat stok terpecah. Gabungkan stoknya ke satu data lalu hapus duplikatnya.');
        $det = array(); foreach ($B as $k => $b) { $s = $b->stok_cbt + $b->stok_jkt + $b->stok_sby; if ($s > 0 && self::harga($b, '') <= 0) $det[] = $k . ' ' . $b->nama_part; }
        $add('Barang bersaldo memiliki harga', $det ? 'warn' : 'ok', $det ? count($det) . ' barang punya stok tetapi harga net 0.' : 'Semua barang bersaldo memiliki harga.', $det, 'UpdateHpart',
            'Tanpa harga, nilai persediaan & laporan nilai pemakaian menjadi kurang. Isi lewat Update Harga.');
        $nMin = 0; foreach ($B as $b) if ($b->stok_min <= 0) $nMin++;
        $add('Stok minimum diisi', $nMin > count($B) * .5 ? 'info' : 'ok', nf($nMin) . ' dari ' . nf(count($B)) . ' barang belum memiliki stok minimum.', array(), 'Sparepart',
            'Isi stok minimum untuk barang fast-moving agar laporan Stok Minimum & Reorder bisa memberi peringatan.');
        $det = array(); foreach ($B as $k => $b) if (trim((string) $b->kategori) === '' || trim((string) $b->satuan) === '') $det[] = $k . ' ' . $b->nama_part;
        $add('Kategori & satuan barang terisi', $det ? 'warn' : 'ok', $det ? count($det) . ' barang tanpa kategori/satuan.' : 'Lengkap.', $det, 'Sparepart', 'Kategori dipakai Report Per Divisi; satuan dipakai di seluruh dokumen.');
        $tanpa = array(); foreach ($this->gerakan(date('Y-m-d', strtotime('-365 day')), null) as $g) if ( ! isset($B[$g->no_part])) $tanpa[$g->no_part] = true;
        $add('Transaksi 12 bulan terakhir memakai part terdaftar', $tanpa ? 'warn' : 'ok', $tanpa ? count($tanpa) . ' nomor part di transaksi tidak ada di master.' : 'Semua part transaksi terdaftar.', array_keys($tanpa), 'Sparepart',
            'Biasanya karena nomor part diubah/dihapus setelah transaksi. Daftarkan kembali agar kartu stok lengkap.');
        $tl = array(); foreach ($this->gerakan(date('Y-m-d', strtotime('-365 day')), null) as $g) if ($g->lok === '') $tl[$g->ref] = true;
        $add('Lokasi gudang tercatat pada transaksi', $tl ? 'warn' : 'ok', $tl ? count($tl) . ' dokumen tanpa lokasi gudang yang dikenali.' : 'Semua transaksi berlokasi.', array_slice(array_keys($tl), 0, 30), '',
            'Transaksi tanpa lokasi tidak bisa dihitung ke stok per gudang (hanya ke total).');
        $po = $this->outstanding(date('Y-m-d'));
        $lama = array(); foreach ($po['AP'] as $r) if ($r->hari !== null && $r->hari > 60) $lama[$r->no] = $r->kontak . ' — ' . $r->hari . ' hari';
        $add('Part Order ke pemasok tidak menggantung > 60 hari', $lama ? 'warn' : 'ok', $lama ? count($lama) . ' dokumen masih ada sisa barang > 60 hari.' : 'Tidak ada pesanan lama yang menggantung.',
            array_map(function ($k, $v) { return $k . ': ' . $v; }, array_keys($lama), $lama), 'LaporanGudang/outstanding', 'Konfirmasi ke pemasok; bila batal, tutup sisa pesanan agar tidak dihitung "sedang dipesan".');
        $skor = array('ok' => 0, 'info' => 0, 'warn' => 0, 'bad' => 0);
        foreach ($C as $c) $skor[$c->status]++;
        return array('cek' => $C, 'skor' => $skor);
    }

    /* ==================================================================
     * DASHBOARD GUDANG
     * ================================================================ */
    public function dashboard()
    {
        $B = $this->barang();
        $lok = array(); foreach (self::$LOKASI as $l => $nm) $lok[$l] = (object) array('nama' => $nm, 'item' => 0, 'qty' => 0, 'nilai' => 0, 'min' => 0, 'habis' => 0);
        foreach ($B as $b) foreach (self::$LOKASI as $l => $nm) {
            $s = $b->{'stok_' . $l};
            if ($s > 0) { $lok[$l]->item++; $lok[$l]->qty += $s; $lok[$l]->nilai += $s * self::harga($b, $l); }
            if ($b->stok_min > 0 && $s <= $b->stok_min) { $lok[$l]->min++; if ($s <= 0) $lok[$l]->habis++; }
        }
        $awal = date('Y-m-01'); $kini = date('Y-m-d');
        $m = array('masuk' => 0, 'keluar' => 0, 'nm' => 0, 'nk' => 0, 'dok_m' => array(), 'dok_k' => array());
        $harian = array();
        foreach ($this->gerakan(date('Y-m-d', strtotime('-29 day')), $kini) as $g) {
            $d = $g->tgl;
            if ( ! isset($harian[$d])) $harian[$d] = array(0, 0);
            $harian[$d][0] += $g->masuk; $harian[$d][1] += $g->keluar;
            if ($g->tgl >= $awal) {
                if ($g->masuk > 0) { $m['masuk'] += $g->masuk; $m['nm'] += $g->masuk * $g->harga; $m['dok_m'][$g->ref] = 1; }
                if ($g->keluar > 0) { $m['keluar'] += $g->keluar; $m['nk'] += $g->keluar * $g->harga; $m['dok_k'][$g->ref] = 1; }
            }
        }
        $po = $this->outstanding($kini);
        $nilaiPo = 0; foreach ($po['AP'] as $r) $nilaiPo += $r->nilai;
        $pk = $this->pemakaian(date('Y-m-d', strtotime('-89 day')), $kini, '');
        return array('lokasi' => $lok, 'bulan' => $m, 'harian' => $harian, 'po_ap' => count($po['AP']), 'po_ap_nilai' => $nilaiPo, 'po_ar' => count($po['AR']),
            'top' => array_slice($pk['part'], 0, 8, true), 'jenis' => $pk['jenis'], 'item' => count($B));
    }
}
