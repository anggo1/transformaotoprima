<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stock Opname (konsep baru, berdampingan dengan menu Stock Opname lama).
 *
 * Siklus dokumen:
 *   DRAFT      -> dokumen dibuat (lokasi, cakupan, metode)
 *   HITUNG     -> daftar barang + STOK SNAPSHOT dikunci saat mulai; petugas menghitung (input / scan QR / impor Excel)
 *   REVIEW     -> penghitungan ditutup; selisih di atas toleransi dihitung ulang (hitung ke-2)
 *   DISETUJUI  -> disetujui atasan: stok disesuaikan sebesar (fisik − snapshot), tercatat di tbl_wh_penyesuaian,
 *                 dan (opsional) jurnal penyesuaian persediaan ke Akuntansi
 *   BATAL
 * Transaksi yang terjadi selama penghitungan tetap aman karena penyesuaian memakai SELISIH terhadap snapshot,
 * bukan menimpa stok.
 */
class Mod_opname extends CI_Model
{
    public $error = '';
    public static $STATUS = array('DRAFT' => 'Draft', 'HITUNG' => 'Penghitungan', 'REVIEW' => 'Review selisih', 'DISETUJUI' => 'Disetujui & diposting', 'BATAL' => 'Batal');
    public static $CAKUPAN = array('BERSALDO' => 'Semua barang bersaldo', 'SEMUA' => 'Semua barang terdaftar', 'KATEGORI' => 'Per kategori / divisi',
        'KELOMPOK' => 'Per kelompok rak', 'RAK' => 'Per awalan kode rak (mis. A12)', 'ABC_A' => 'Barang kelas A (Analisis ABC 12 bulan)', 'PILIH' => 'Daftar no. part tertentu');

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_whutil', 'warehouse/Mod_lapgudang'));
    }

    private function gagal($m) { $this->error = $m; if ($this->db->trans_status() !== null) @$this->db->trans_rollback(); return false; }

    public function daftar($status = '')
    {
        $this->db->from('tbl_wh_so')->order_by('id', 'DESC');
        if ($status !== '') $this->db->where('status', $status);
        $rows = $this->db->limit(200)->get()->result();
        foreach ($rows as $r) $r->ringkas = $this->ringkasan($r->id);
        return $rows;
    }

    public function get($id)
    {
        $h = $this->db->get_where('tbl_wh_so', array('id' => (int) $id))->row();
        if ($h) $h->ringkas = $this->ringkasan($h->id);
        return $h;
    }

    public function ringkasan($id)
    {
        $r = (object) array('item' => 0, 'dihitung' => 0, 'cocok' => 0, 'selisih' => 0, 'ulang' => 0, 'lebih' => 0.0, 'kurang' => 0.0, 'persen' => 0);
        $h = $this->db->get_where('tbl_wh_so', array('id' => (int) $id))->row();
        $tol = $h ? (float) $h->toleransi : 0;
        foreach ($this->db->select('stok_snapshot, hitung1, hitung2, fisik, harga')->get_where('tbl_wh_so_detail', array('id_so' => (int) $id))->result() as $d) {
            $r->item++;
            if ($d->fisik === null) continue;
            $r->dihitung++;
            $sel = (float) $d->fisik - (float) $d->stok_snapshot;
            if (abs($sel) < 0.0005) { $r->cocok++; continue; }
            $r->selisih++;
            if ($sel > 0) $r->lebih += $sel * $d->harga; else $r->kurang += $sel * $d->harga;
            if ($d->hitung2 === null && self::perlu_ulang($d->stok_snapshot, $d->fisik, $tol)) $r->ulang++;
        }
        $r->persen = $r->item ? round($r->dihitung / $r->item * 100) : 0;
        return $r;
    }

    /** selisih melebihi toleransi (% dari stok snapshot, minimal 1 unit) */
    public static function perlu_ulang($snap, $fisik, $tol)
    {
        $sel = abs((float) $fisik - (float) $snap);
        if ($sel < 0.0005) return false;
        $batas = max(0, (float) $tol) / 100 * abs((float) $snap);
        return $sel > $batas;
    }

    private function nomor($tgl)
    {
        $p = 'SO-' . date('ym', strtotime($tgl)) . '-';
        $r = $this->db->select_max('no_so', 'n')->like('no_so', $p, 'after')->get('tbl_wh_so')->row();
        $n = $r && $r->n ? (int) substr($r->n, -3) + 1 : 1;
        return $p . sprintf('%03d', $n);
    }

    public function buat(array $p)
    {
        $lok = isset($p['lokasi']) ? $p['lokasi'] : '';
        if ( ! isset(Mod_lapgudang::$LOKASI[$lok])) return $this->gagal('Pilih lokasi gudang.');
        $cak = isset($p['cakupan']) && isset(self::$CAKUPAN[$p['cakupan']]) ? $p['cakupan'] : 'BERSALDO';
        $filter = trim((string) (isset($p['filter']) ? $p['filter'] : ''));
        if (in_array($cak, array('KATEGORI', 'KELOMPOK', 'RAK', 'PILIH'), true) && $filter === '') return $this->gagal('Isi pilihan ' . strtolower(self::$CAKUPAN[$cak]) . '.');
        $tgl = tgl_sql(isset($p['tgl']) ? $p['tgl'] : '') ?: date('Y-m-d');
        $row = array('no_so' => $this->nomor($tgl), 'tgl' => $tgl, 'lokasi' => $lok, 'cakupan' => $cak, 'filter' => mb_substr($filter, 0, 100),
            'buta' => ! empty($p['buta']) ? 'Y' : 'N', 'toleransi' => max(0, min(100, (float) (isset($p['toleransi']) ? $p['toleransi'] : 0))),
            'status' => 'DRAFT', 'catatan' => mb_substr(trim((string) (isset($p['catatan']) ? $p['catatan'] : '')), 0, 255),
            'dibuat_oleh' => $this->Mod_whutil->user(), 'created_at' => date('Y-m-d H:i:s'));
        $this->db->insert('tbl_wh_so', $row);
        return $this->db->insert_id();
    }

    /** kunci daftar barang & stok snapshot, status -> HITUNG */
    public function mulai($id)
    {
        $h = $this->get($id);
        if ( ! $h || $h->status !== 'DRAFT') return $this->gagal('Dokumen tidak dalam status Draft.');
        $l = $h->lokasi; $B = $this->Mod_lapgudang->barang(); $pilih = null;
        if ($h->cakupan === 'PILIH') { $pilih = array(); foreach (preg_split('/[\r\n,;]+/', $h->filter) as $x) if (trim($x) !== '') $pilih[trim($x)] = true; }
        if ($h->cakupan === 'ABC_A') {
            $abc = $this->Mod_lapgudang->abc(date('Y-m-d', strtotime('-1 year')), date('Y-m-d'), $l); $pilih = array();
            foreach ($abc['rows'] as $r) if ($r->kelas === 'A') $pilih[$r->no_part] = true;
        }
        $rows = array();
        foreach ($B as $k => $b) {
            $stok = $b->{'stok_' . $l}; $rak = trim((string) $b->{'lok_' . $l});
            switch ($h->cakupan) {
                case 'BERSALDO': if (abs($stok) < 0.0005) continue 2; break;
                case 'KATEGORI': if ((string) $b->kategori !== $h->filter) continue 2; break;
                case 'KELOMPOK': if ((string) $b->kelompok !== $h->filter) continue 2; break;
                case 'RAK': if (stripos($rak, $h->filter) !== 0) continue 2; break;
                case 'PILIH': case 'ABC_A': if ( ! isset($pilih[$k])) continue 2; break;
            }
            $rows[] = array('id_so' => $h->id, 'no_part' => $k, 'nama_part' => mb_substr((string) $b->nama_part, 0, 100), 'satuan' => (string) $b->satuan,
                'kategori' => mb_substr((string) $b->nama_kategori, 0, 50), 'rak' => mb_substr($rak, 0, 25), 'stok_snapshot' => $stok,
                'harga' => Mod_lapgudang::harga($b, $l), 'catatan' => '');
        }
        if ( ! $rows) return $this->gagal('Tidak ada barang yang masuk cakupan ini.');
        usort($rows, function ($a, $b) { return strcmp($a['rak'] . $a['no_part'], $b['rak'] . $b['no_part']); });
        $this->db->trans_begin();
        $this->db->delete('tbl_wh_so_detail', array('id_so' => $h->id));
        foreach (array_chunk($rows, 300) as $c) $this->db->insert_batch('tbl_wh_so_detail', $c);
        $this->db->where('id', $h->id)->update('tbl_wh_so', array('status' => 'HITUNG', 'mulai' => date('Y-m-d H:i:s')));
        if ($this->db->trans_status() === false) return $this->gagal('Gagal menyiapkan daftar hitung.');
        $this->db->trans_commit();
        return count($rows);
    }

    public function detail($id, $filter = '')
    {
        $this->db->from('tbl_wh_so_detail')->where('id_so', (int) $id);
        if ($filter === 'belum') $this->db->where('fisik IS NULL', null, false);
        elseif ($filter === 'selisih') $this->db->where('fisik IS NOT NULL AND ABS(fisik - stok_snapshot) > 0.0005', null, false);
        return $this->db->order_by('rak, no_part')->get()->result();
    }

    /**
     * Simpan hasil hitung. $ronde 1 = hitung pertama, 2 = hitung ulang.
     * $isi = [id_detail => qty|''] ; qty kosong = tidak diubah
     */
    public function simpan_hitung($id, array $isi, $ronde = 1)
    {
        $h = $this->get($id);
        if ( ! $h || ! in_array($h->status, array('HITUNG', 'REVIEW'), true)) return $this->gagal('Dokumen tidak dalam tahap penghitungan.');
        if ($h->status === 'REVIEW') $ronde = 2;
        $kol = $ronde == 2 ? 'hitung2' : 'hitung1'; $n = 0;
        $this->db->trans_begin();
        foreach ($isi as $idd => $q) {
            $q = trim((string) $q);
            if ($q === '') continue;
            $q = (float) str_replace(',', '.', $q);
            if ($q < 0) continue;
            $this->db->where('id', (int) $idd)->where('id_so', $h->id)->update('tbl_wh_so_detail', array($kol => $q, 'fisik' => $q,
                'penghitung' => $this->Mod_whutil->user(), 'waktu_hitung' => date('Y-m-d H:i:s')));
            $n += $this->db->affected_rows() > 0 ? 1 : 0;
        }
        $this->db->trans_commit();
        return $n;
    }

    /** scan QR / barcode: tambah (atau set) jumlah untuk satu no. part */
    public function scan($id, $noPart, $qty, $mode = 'tambah')
    {
        $h = $this->get($id);
        if ( ! $h || ! in_array($h->status, array('HITUNG', 'REVIEW'), true)) return $this->gagal('Dokumen tidak dalam tahap penghitungan.');
        $d = $this->db->get_where('tbl_wh_so_detail', array('id_so' => $h->id, 'no_part' => trim($noPart)))->row();
        if ( ! $d) return $this->gagal('No. part ' . $noPart . ' tidak ada dalam daftar opname ini.');
        $kol = $h->status === 'REVIEW' ? 'hitung2' : 'hitung1';
        $lama = $d->$kol === null ? 0 : (float) $d->$kol;
        $baru = $mode === 'set' ? (float) $qty : $lama + (float) $qty;
        $this->db->where('id', $d->id)->update('tbl_wh_so_detail', array($kol => $baru, 'fisik' => $baru, 'penghitung' => $this->Mod_whutil->user(), 'waktu_hitung' => date('Y-m-d H:i:s')));
        $d->$kol = $baru; $d->fisik = $baru;
        return $d;
    }

    public function ubah_status($id, $ke)
    {
        $h = $this->get($id);
        if ( ! $h) return $this->gagal('Dokumen tidak ditemukan.');
        $boleh = array('HITUNG' => array('REVIEW', 'BATAL'), 'REVIEW' => array('HITUNG', 'BATAL'), 'DRAFT' => array('BATAL'));
        if ( ! isset($boleh[$h->status]) || ! in_array($ke, $boleh[$h->status], true)) return $this->gagal('Perubahan status tidak diizinkan.');
        $u = array('status' => $ke);
        if ($ke === 'REVIEW') $u['selesai_hitung'] = date('Y-m-d H:i:s');
        $this->db->where('id', $h->id)->update('tbl_wh_so', $u);
        return true;
    }

    /** setujui & posting penyesuaian stok (+ jurnal opsional) */
    public function setujui($id, $nolBelumDihitung = false)
    {
        $h = $this->get($id);
        if ( ! $h || $h->status !== 'REVIEW') return $this->gagal('Hanya dokumen berstatus Review yang dapat disetujui.');
        $rows = $this->detail($h->id);
        $lebih = 0; $kurang = 0; $n = 0;
        $this->db->trans_begin();
        foreach ($rows as $d) {
            $fisik = $d->fisik;
            if ($fisik === null) { if ( ! $nolBelumDihitung) continue; $fisik = 0; $this->db->where('id', $d->id)->update('tbl_wh_so_detail', array('fisik' => 0, 'catatan' => 'Tidak ditemukan saat opname (dianggap 0)')); }
            $sel = (float) $fisik - (float) $d->stok_snapshot;
            if (abs($sel) < 0.0005) continue;
            $this->Mod_whutil->ubah_stok($d->no_part, $h->lokasi, $sel, $d->harga, 'SO', $h->no_so, 'Penyesuaian stock opname ' . $h->no_so, $h->tgl);
            if ($sel > 0) $lebih += $sel * $d->harga; else $kurang += -$sel * $d->harga;
            $n++;
        }
        $ref = '';
        if ($this->Mod_whutil->setting('so_jurnal', 'N') === 'Y' && ($lebih > 0 || $kurang > 0)) {
            $this->load->library('akuntansi');
            $ap = $this->Mod_whutil->setting('akun_persediaan'); $as = $this->Mod_whutil->setting('akun_selisih_persediaan');
            if ($ap && $as && $this->akuntansi->akun($ap) && $this->akuntansi->akun($as)) {
                $jl = array(); $net = round($lebih - $kurang, 2);
                if ($net > 0) { $jl[] = array('kode_akun' => $ap, 'debit' => $net, 'keterangan' => 'Selisih lebih opname ' . $h->no_so); $jl[] = array('kode_akun' => $as, 'kredit' => $net, 'keterangan' => 'Selisih lebih opname ' . $h->no_so); }
                elseif ($net < 0) { $jl[] = array('kode_akun' => $as, 'debit' => -$net, 'keterangan' => 'Selisih kurang opname ' . $h->no_so); $jl[] = array('kode_akun' => $ap, 'kredit' => -$net, 'keterangan' => 'Selisih kurang opname ' . $h->no_so); }
                if ($jl) {
                    $ref = $this->akuntansi->posting('SO', $h->tgl, 'Penyesuaian persediaan stock opname ' . $h->no_so, $jl, $h->id, $h->no_so);
                    if ( ! $ref) return $this->gagal('Jurnal gagal: ' . $this->akuntansi->error);
                }
            }
        }
        $this->db->where('id', $h->id)->update('tbl_wh_so', array('status' => 'DISETUJUI', 'disetujui_oleh' => $this->Mod_whutil->user(), 'tgl_posting' => date('Y-m-d H:i:s'), 'no_ref_jurnal' => (string) $ref));
        if ($this->db->trans_status() === false) return $this->gagal('Posting gagal, tidak ada perubahan.');
        $this->db->trans_commit();
        return array('baris' => $n, 'lebih' => $lebih, 'kurang' => $kurang, 'jurnal' => $ref);
    }

    /** impor hasil hitung dari Excel: kolom no_part & fisik */
    public function impor_hitung($id, array $rows)
    {
        if (count($rows) < 2) return $this->gagal('File kosong.');
        $head = array_map(function ($x) { return strtolower(trim((string) $x)); }, $rows[0]);
        $ip = array_search('no_part', $head, true); $iq = false;
        foreach (array('fisik', 'hitung', 'jumlah_fisik', 'qty') as $c) if (($x = array_search($c, $head, true)) !== false) { $iq = $x; break; }
        if ($ip === false || $iq === false) return $this->gagal('Kolom "no_part" dan "fisik" wajib ada di baris judul.');
        $map = array();
        foreach ($this->db->select('id, no_part')->get_where('tbl_wh_so_detail', array('id_so' => (int) $id))->result() as $d) $map[trim($d->no_part)] = $d->id;
        $isi = array(); $tidak = array();
        foreach (array_slice($rows, 1) as $r) {
            $p = trim((string) (isset($r[$ip]) ? $r[$ip] : '')); $q = isset($r[$iq]) ? trim((string) $r[$iq]) : '';
            if ($p === '' || $q === '') continue;
            if (isset($map[$p])) $isi[$map[$p]] = $q; else $tidak[] = $p;
        }
        $n = $this->simpan_hitung($id, $isi);
        if ($n === false) return false;
        return array('n' => $n, 'tidak' => $tidak);
    }
}
