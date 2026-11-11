<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Harga rata-rata bergerak (moving average) & riwayat harga beli per tahun.
 *
 * Kenapa perlu: harga dari principal berubah tiap tahun, sementara stok lama dibeli dengan harga lama.
 * Menilai seluruh stok dengan harga net TERBARU membuat nilai persediaan & HPP melonjak/turun mendadak.
 * Rata-rata bergerak: setiap penerimaan (PO / non-PO) dengan harga > 0
 *     harga_rata_baru = (qty_ada × harga_rata_lama + qty_masuk × harga_masuk) / (qty_ada + qty_masuk)
 * Pengeluaran tidak mengubah harga rata-rata, hanya mengurangi qty.
 * Hasil disimpan di tbl_wh_harga_rata dan dipakai laporan bila Metode penilaian = RATA (Pengaturan Gudang).
 */
class Mod_hargarata extends CI_Model
{
    public static $BELI = array('MPO', 'MNP');

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_lapgudang', 'warehouse/Mod_whutil'));
    }

    /** hitung ulang seluruh harga rata-rata per gudang (dari riwayat transaksi) */
    public function hitung_ulang()
    {
        $B = $this->Mod_lapgudang->barang(); $hasil = array(); $now = date('Y-m-d H:i:s');
        foreach (Mod_lapgudang::$LOKASI as $l => $nm) {
            $per = array();
            foreach ($this->Mod_lapgudang->gerakan(null, null, null, $l) as $g) $per[$g->no_part][] = $g;
            foreach ($per as $k => $gs) {
                $stok = isset($B[$k]) ? $B[$k]->{'stok_' . $l} : 0;
                $net = 0; $first = 0;
                foreach ($gs as $g) { $net += $g->masuk - $g->keluar; if ( ! $first && $g->masuk > 0 && in_array($g->jenis, self::$BELI, true) && $g->harga > 0) $first = $g->harga; }
                $qty = max(0, $stok - $net);
                $avg = $first ?: (isset($B[$k]) ? $B[$k]->{'hrg_net_' . $l} : 0);
                $last = 0; $tglLast = null; $n = 0;
                foreach ($gs as $g) {
                    if ($g->masuk > 0) {
                        if (in_array($g->jenis, self::$BELI, true) && $g->harga > 0) {
                            $avg = $qty > 0 ? ($qty * $avg + $g->masuk * $g->harga) / ($qty + $g->masuk) : $g->harga;
                            $last = $g->harga; $tglLast = $g->tgl; $n++;
                        }
                        $qty += $g->masuk;
                    }
                    if ($g->keluar > 0) $qty = max(0, $qty - $g->keluar);
                }
                if ($avg <= 0 && ! $n) continue;
                $hasil[] = array('no_part' => mb_substr($k, 0, 50), 'lokasi' => $l, 'harga_rata' => round($avg, 2), 'qty' => round($qty, 3), 'harga_beli_terakhir' => round($last, 2),
                    'tgl_beli_terakhir' => $tglLast, 'jumlah_beli' => $n, 'updated_at' => $now);
            }
        }
        $this->db->trans_begin();
        $this->db->empty_table('tbl_wh_harga_rata');
        foreach (array_chunk($hasil, 300) as $c) $this->db->insert_batch('tbl_wh_harga_rata', $c);
        $this->db->trans_commit();
        $this->Mod_whutil->simpan_setting(array('harga_rata_dihitung' => $now));
        return count($hasil);
    }

    public function terakhir_dihitung() { return $this->Mod_whutil->setting('harga_rata_dihitung', ''); }

    /** daftar harga rata vs harga net master */
    public function daftar($lok, $kategori = '')
    {
        $B = $this->Mod_lapgudang->barang(); $rows = array();
        $T = (object) array('n' => 0, 'nilai_net' => 0, 'nilai_rata' => 0, 'naik' => 0, 'turun' => 0);
        foreach ($this->db->get_where('tbl_wh_harga_rata', array('lokasi' => $lok))->result() as $r) {
            $k = trim($r->no_part); if ( ! isset($B[$k])) continue; $b = $B[$k];
            if ($kategori !== '' && (string) $b->kategori !== $kategori) continue;
            $stok = $b->{'stok_' . $lok}; $net = $b->{'hrg_net_' . $lok};
            $sel = $r->harga_rata > 0 && $net > 0 ? ($net - $r->harga_rata) / $r->harga_rata * 100 : null;
            $rows[] = (object) array('no_part' => $k, 'nama' => $b->nama_part, 'kategori' => $b->nama_kategori, 'satuan' => $b->satuan, 'stok' => $stok, 'net' => $net,
                'rata' => (float) $r->harga_rata, 'terakhir' => (float) $r->harga_beli_terakhir, 'tgl_terakhir' => $r->tgl_beli_terakhir, 'n' => (int) $r->jumlah_beli,
                'selisih' => $sel, 'nilai_net' => $stok * $net, 'nilai_rata' => $stok * $r->harga_rata);
            $T->n++; $T->nilai_net += $stok * $net; $T->nilai_rata += $stok * $r->harga_rata;
            if ($sel !== null && $sel > 0.5) $T->naik++; elseif ($sel !== null && $sel < -0.5) $T->turun++;
        }
        usort($rows, function ($a, $b) { return abs($b->nilai_net - $b->nilai_rata) <=> abs($a->nilai_net - $a->nilai_rata); });
        return array('rows' => $rows, 'total' => $T, 'dihitung' => $this->terakhir_dihitung());
    }

    /** agregat pembelian per tahun → [tahun][no_part] = {qty, nilai, min, max, n, akhir} */
    public function pembelian_tahunan($lok = '', $part = null, $dariTahun = null)
    {
        $out = array();
        foreach ($this->Mod_lapgudang->gerakan($dariTahun ? $dariTahun . '-01-01' : null, null, $part, $lok) as $g) {
            if ($g->masuk <= 0 || $g->harga <= 0 || ! in_array($g->jenis, self::$BELI, true)) continue;
            $y = (int) substr($g->tgl, 0, 4); $k = $g->no_part;
            if ( ! isset($out[$y][$k])) $out[$y][$k] = array('qty' => 0, 'nilai' => 0, 'min' => INF, 'max' => 0, 'n' => 0, 'akhir' => 0, 'sup' => array());
            $x = &$out[$y][$k];
            $x['qty'] += $g->masuk; $x['nilai'] += $g->masuk * $g->harga; $x['min'] = min($x['min'], $g->harga); $x['max'] = max($x['max'], $g->harga); $x['n']++; $x['akhir'] = $g->harga;
            if ($g->sup !== '') $x['sup'][$g->sup] = true;
            unset($x);
        }
        ksort($out);
        return $out;
    }

    /** perbandingan harga beli rata-rata tertimbang tahun $t vs $t-1 */
    public function kenaikan($tahun, $lok = '')
    {
        $B = $this->Mod_lapgudang->barang();
        $P = $this->pembelian_tahunan($lok, null, $tahun - 4);
        $a = isset($P[$tahun - 1]) ? $P[$tahun - 1] : array(); $b = isset($P[$tahun]) ? $P[$tahun] : array();
        $rows = array(); $dasar = 0; $kini = 0;
        foreach ($b as $k => $x) {
            if ( ! isset($a[$k])) continue;
            $ra = $a[$k]['nilai'] / $a[$k]['qty']; $rb = $x['nilai'] / $x['qty'];
            $pct = $ra > 0 ? ($rb - $ra) / $ra * 100 : 0;
            $rows[] = (object) array('no_part' => $k, 'nama' => isset($B[$k]) ? $B[$k]->nama_part : '', 'kategori' => isset($B[$k]) ? $B[$k]->nama_kategori : '',
                'rata_a' => $ra, 'rata_b' => $rb, 'pct' => $pct, 'qty_b' => $x['qty'], 'dampak' => ($rb - $ra) * $x['qty'], 'min' => $x['min'], 'max' => $x['max']);
            $dasar += $ra * $x['qty']; $kini += $rb * $x['qty'];
        }
        usort($rows, function ($p, $q) { return abs($q->dampak) <=> abs($p->dampak); });
        // indeks harga tahunan (rantai, tertimbang qty tahun berjalan)
        $indeks = array(); $nilai = 100; $ys = array_keys($P);
        foreach ($ys as $i => $y) {
            if ($i === 0) { $indeks[$y] = 100; continue; }
            $pa = $P[$ys[$i - 1]]; $pb = $P[$y]; $d = 0; $c = 0;
            foreach ($pb as $k => $x) if (isset($pa[$k])) { $d += $pa[$k]['nilai'] / $pa[$k]['qty'] * $x['qty']; $c += $x['nilai']; }
            $nilai = $d > 0 ? $nilai * $c / $d : $nilai; $indeks[$y] = round($nilai, 1);
        }
        $kat = array();
        foreach ($rows as $r) { if ( ! isset($kat[$r->kategori])) $kat[$r->kategori] = array(0, 0); $kat[$r->kategori][0] += $r->rata_a * $r->qty_b; $kat[$r->kategori][1] += $r->rata_b * $r->qty_b; }
        $katPct = array(); foreach ($kat as $k => $v) $katPct[$k] = $v[0] > 0 ? ($v[1] - $v[0]) / $v[0] * 100 : 0;
        $naik = 0; $turun = 0; foreach ($rows as $r) { if ($r->pct > 0.5) $naik++; elseif ($r->pct < -0.5) $turun++; }
        return array('rows' => $rows, 'tahun' => $tahun, 'inflasi' => $dasar > 0 ? ($kini - $dasar) / $dasar * 100 : null, 'dampak' => $kini - $dasar,
            'indeks' => $indeks, 'kategori' => $katPct, 'naik' => $naik, 'turun' => $turun, 'baru' => count(array_diff_key($b, $a)), 'n_tahun' => count($b));
    }

    /** riwayat harga satu barang */
    public function riwayat($part, $lok = '')
    {
        $B = $this->Mod_lapgudang->barang(); $part = trim((string) $part);
        if ($part === '' || ! isset($B[$part])) return null;
        $tahun = array(); $sebelum = null;
        foreach ($this->pembelian_tahunan($lok, $part) as $y => $arr) {
            $x = $arr[$part]; $r = $x['nilai'] / $x['qty'];
            $tahun[$y] = (object) array('qty' => $x['qty'], 'rata' => $r, 'min' => $x['min'], 'max' => $x['max'], 'n' => $x['n'], 'akhir' => $x['akhir'],
                'sup' => implode(', ', array_keys($x['sup'])), 'pct' => $sebelum ? ($r - $sebelum) / $sebelum * 100 : null);
            $sebelum = $r;
        }
        $beli = array();
        foreach ($this->Mod_lapgudang->gerakan(null, null, $part, $lok) as $g) if ($g->masuk > 0 && in_array($g->jenis, self::$BELI, true)) $beli[] = $g;
        $log = $this->db->where('TRIM(no_part) = ' . $this->db->escape($part), null, false)->order_by('tgl_update', 'DESC')->limit(50)->get('tbl_wh_log_harga')->result();
        $rata = $this->db->get_where('tbl_wh_harga_rata', array('no_part' => $part))->result();
        $rm = array(); foreach ($rata as $r) $rm[$r->lokasi] = $r;
        return (object) array('barang' => $B[$part], 'tahun' => $tahun, 'beli' => array_reverse($beli), 'log' => $log, 'rata' => $rm);
    }
}
