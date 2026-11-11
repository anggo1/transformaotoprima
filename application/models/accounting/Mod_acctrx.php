<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Transaksi modul akuntansi: Kas & Bank, Faktur Piutang/Hutang + pelunasan,
 * Aset Tetap + penyusutan, Anggaran, Periode.
 * Setiap transaksi otomatis membuat jurnal lewat library Akuntansi.
 */
class Mod_acctrx extends CI_Model
{
    public $error = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('akuntansi');
    }

    private function user() { return (string) $this->session->userdata('full_name'); }

    private function gagal($pesan)
    {
        $this->error = $pesan;
        if ($this->db->trans_status() !== null) $this->db->trans_rollback();
        return false;
    }

    /* ==================================================================
     * KAS & BANK
     * ================================================================ */
    public function kb_list($dari, $sampai, $jenis = '', $akun = '')
    {
        $this->db->from('tbl_acc_kas_bank')->where('tanggal >=', $dari)->where('tanggal <=', $sampai);
        if ($jenis !== '') $this->db->where('jenis', $jenis);
        if ($akun !== '') $this->db->group_start()->where('kode_akun_kas', $akun)->or_where('kode_akun_tujuan', $akun)->group_end();
        return $this->db->order_by('tanggal DESC, id DESC')->get()->result();
    }

    public function kb_get($id)
    {
        $h = $this->db->get_where('tbl_acc_kas_bank', array('id' => (int) $id))->row();
        if ($h) $h->detail = $this->db->order_by('id')->get_where('tbl_acc_kas_bank_detail', array('id_kas_bank' => $h->id))->result();
        return $h;
    }

    /**
     * $h = [jenis, tanggal, kode_akun_kas, kode_akun_tujuan, kontak, no_bukti, keterangan]
     * $lines = [[kode_akun, keterangan, jumlah], ...]  (tidak dipakai untuk TRANSFER)
     */
    public function kb_simpan(array $h, array $lines, $jumlahTransfer = 0)
    {
        $this->error = '';
        $jenis = in_array($h['jenis'], array('MASUK', 'KELUAR', 'TRANSFER'), true) ? $h['jenis'] : 'MASUK';
        $tgl = tgl_sql($h['tanggal']);
        $kas = $this->akuntansi->akun($h['kode_akun_kas']);
        if ( ! $tgl) return $this->gagal('Tanggal wajib diisi.');
        if ( ! $kas) return $this->gagal('Akun kas/bank wajib dipilih.');

        $bersih = array(); $total = 0;
        if ($jenis === 'TRANSFER') {
            $tujuan = $this->akuntansi->akun($h['kode_akun_tujuan']);
            if ( ! $tujuan) return $this->gagal('Akun tujuan transfer wajib dipilih.');
            if ($tujuan->kode_akun === $kas->kode_akun) return $this->gagal('Akun asal dan tujuan tidak boleh sama.');
            $total = round(to_number($jumlahTransfer), 2);
            if ($total <= 0) return $this->gagal('Jumlah transfer harus lebih dari 0.');
            $bersih[] = array('kode_akun' => $tujuan->kode_akun, 'keterangan' => $h['keterangan'], 'jumlah' => $total);
        } else {
            foreach ($lines as $l) {
                $j = round(to_number(isset($l['jumlah']) ? $l['jumlah'] : 0), 2);
                $k = isset($l['kode_akun']) ? trim($l['kode_akun']) : '';
                if ($k === '' && $j == 0) continue;
                if ( ! $this->akuntansi->akun($k)) return $this->gagal('Akun pada rincian belum dipilih / tidak valid.');
                if ($j == 0) return $this->gagal('Jumlah pada rincian tidak boleh 0.');
                if ($k === $kas->kode_akun) return $this->gagal('Akun rincian tidak boleh sama dengan akun kas/bank.');
                $bersih[] = array('kode_akun' => $k, 'keterangan' => isset($l['keterangan']) ? trim($l['keterangan']) : '', 'jumlah' => $j);
                $total += $j;
            }
            if ( ! $bersih) return $this->gagal('Isi minimal satu baris rincian.');
            if ($total <= 0) return $this->gagal('Total transaksi harus lebih dari 0.');
        }

        $prefix = array('MASUK' => 'KM', 'KELUAR' => 'KK', 'TRANSFER' => 'TB');
        $this->db->trans_begin();
        $no = $this->akuntansi->nomor_baru('tbl_acc_kas_bank', 'no_transaksi', 'B' . $prefix[$jenis], $tgl);
        $this->db->insert('tbl_acc_kas_bank', array(
            'no_transaksi' => $no, 'jenis' => $jenis, 'tanggal' => $tgl, 'kode_akun_kas' => $kas->kode_akun,
            'kode_akun_tujuan' => $jenis === 'TRANSFER' ? $bersih[0]['kode_akun'] : '',
            'kontak' => mb_substr(trim((string) $h['kontak']), 0, 100), 'no_bukti' => mb_substr(trim((string) $h['no_bukti']), 0, 50),
            'keterangan' => mb_substr(trim((string) $h['keterangan']), 0, 255), 'total' => $total, 'status' => 'Y',
            'user' => $this->user(), 'created_at' => date('Y-m-d H:i:s'),
        ));
        $id = $this->db->insert_id();

        $jl = array();
        foreach ($bersih as $b) {
            $this->db->insert('tbl_acc_kas_bank_detail', array('id_kas_bank' => $id) + $b);
            $ket = $b['keterangan'] !== '' ? $b['keterangan'] : $h['keterangan'];
            if ($jenis === 'MASUK') $jl[] = array('kode_akun' => $b['kode_akun'], 'kredit' => $b['jumlah'], 'keterangan' => $ket);
            else $jl[] = array('kode_akun' => $b['kode_akun'], 'debit' => $b['jumlah'], 'keterangan' => $ket);
        }
        $ketKas = trim(($h['kontak'] ? $h['kontak'] . ' - ' : '') . $h['keterangan']);
        if ($jenis === 'MASUK') $jl[] = array('kode_akun' => $kas->kode_akun, 'debit' => $total, 'keterangan' => $ketKas);
        else $jl[] = array('kode_akun' => $kas->kode_akun, 'kredit' => $total, 'keterangan' => $ketKas);

        $ref = $this->akuntansi->posting($prefix[$jenis], $tgl, $h['keterangan'] ?: ('Transaksi ' . $no), $jl, $id, $no);
        if ( ! $ref) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $id)->update('tbl_acc_kas_bank', array('no_ref_jurnal' => $ref));

        if ($this->db->trans_status() === FALSE) return $this->gagal('Gagal menyimpan transaksi.');
        $this->db->trans_commit();
        return $id;
    }

    public function kb_batal($id)
    {
        $h = $this->kb_get($id);
        if ( ! $h) return $this->gagal('Transaksi tidak ditemukan.');
        if ($h->status === 'BATAL') return $this->gagal('Transaksi sudah dibatalkan.');
        $this->db->trans_begin();
        if ( ! $this->akuntansi->hapus_jurnal($h->no_ref_jurnal)) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $h->id)->update('tbl_acc_kas_bank', array('status' => 'BATAL', 'no_ref_jurnal' => ''));
        $this->db->trans_commit();
        return true;
    }

    /** saldo terkini semua akun kas/bank */
    public function saldo_kas($tgl = null)
    {
        $tgl = $tgl ?: date('Y-m-d');
        $akun = $this->akuntansi->akun_kategori('KAS_BANK');
        if ( ! $akun) return array();
        $s = $this->akuntansi->saldo_per($tgl, array_keys($akun));
        $out = array();
        foreach ($akun as $k => $a) {
            $out[] = (object) array('kode' => $k, 'nama' => $a->nama_akun, 'saldo' => isset($s[$k]) ? $s[$k]['d'] - $s[$k]['k'] : 0);
        }
        return $out;
    }

    /* ==================================================================
     * FAKTUR PIUTANG (AR) / HUTANG (AP)
     * ================================================================ */
    public function faktur_list($jenis, $dari, $sampai, $status = '', $kontak = '')
    {
        $this->db->from('tbl_acc_faktur')->where('jenis', $jenis)->where('tanggal >=', $dari)->where('tanggal <=', $sampai);
        if ($status === 'TERBUKA') $this->db->where_in('status', array('BELUM', 'SEBAGIAN'));
        elseif ($status !== '') $this->db->where('status', $status);
        if ($kontak !== '') $this->db->like('kontak', $kontak);
        return $this->db->order_by('tanggal DESC, id DESC')->get()->result();
    }

    public function faktur_get($id, $jenis = null)
    {
        $w = array('id' => (int) $id);
        if ($jenis) $w['jenis'] = $jenis;
        $f = $this->db->get_where('tbl_acc_faktur', $w)->row();
        if ($f) {
            $f->detail = $this->db->order_by('id')->get_where('tbl_acc_faktur_detail', array('id_faktur' => $f->id))->result();
            $f->bayar = $this->db->order_by('tanggal, id')->get_where('tbl_acc_pelunasan', array('id_faktur' => $f->id))->result();
        }
        return $f;
    }

    public function ringkasan_faktur($jenis)
    {
        $r = $this->db->select('COUNT(*) AS n, SUM(total - terbayar) AS sisa', false)->from('tbl_acc_faktur')
            ->where('jenis', $jenis)->where_in('status', array('BELUM', 'SEBAGIAN'))->get()->row();
        $j = $this->db->select('COUNT(*) AS n, SUM(total - terbayar) AS sisa', false)->from('tbl_acc_faktur')
            ->where('jenis', $jenis)->where_in('status', array('BELUM', 'SEBAGIAN'))->where('jatuh_tempo <', date('Y-m-d'))->get()->row();
        return (object) array('n' => (int) $r->n, 'sisa' => (float) $r->sisa, 'n_lewat' => (int) $j->n, 'sisa_lewat' => (float) $j->sisa);
    }

    public function kontak($jenis)
    {
        $nama = array();
        if ($jenis === 'AP') {
            if ($this->db->table_exists('tbl_wh_supplier')) foreach ($this->db->select('nama_sup AS n')->order_by('nama_sup')->get('tbl_wh_supplier')->result() as $r) $nama[] = $r->n;
        } else {
            foreach (array('tbl_customer', 'tbl_wh_customer') as $t) {
                if ($this->db->table_exists($t)) foreach ($this->db->select('nama_cus AS n')->order_by('nama_cus')->get($t)->result() as $r) $nama[] = $r->n;
            }
        }
        foreach ($this->db->distinct()->select('kontak')->where('jenis', $jenis)->get('tbl_acc_faktur')->result() as $r) $nama[] = $r->kontak;
        $nama = array_values(array_unique(array_filter(array_map('trim', $nama))));
        natcasesort($nama);
        return array_values($nama);
    }

    /**
     * $h: jenis, tanggal, jatuh_tempo, kontak, no_referensi, keterangan, kode_akun_ar_ap, diskon, ppn_persen
     * $lines: [kode_akun, keterangan, qty, harga]
     */
    public function faktur_simpan(array $h, array $lines)
    {
        $this->error = '';
        $jenis = $h['jenis'] === 'AP' ? 'AP' : 'AR';
        $tgl = tgl_sql($h['tanggal']);
        $jt = tgl_sql($h['jatuh_tempo']) ?: $tgl;
        $kontak = trim((string) $h['kontak']);
        $akunArAp = $this->akuntansi->akun($h['kode_akun_ar_ap']);
        if ( ! $tgl) return $this->gagal('Tanggal faktur wajib diisi.');
        if ($jt < $tgl) return $this->gagal('Jatuh tempo tidak boleh sebelum tanggal faktur.');
        if ($kontak === '') return $this->gagal($jenis === 'AR' ? 'Nama pelanggan wajib diisi.' : 'Nama pemasok wajib diisi.');
        if ( ! $akunArAp) return $this->gagal('Akun ' . ($jenis === 'AR' ? 'piutang' : 'hutang') . ' wajib dipilih.');

        $bersih = array(); $subtotal = 0;
        foreach ($lines as $l) {
            $k = isset($l['kode_akun']) ? trim($l['kode_akun']) : '';
            $qty = to_number(isset($l['qty']) && $l['qty'] !== '' ? $l['qty'] : 1);
            $harga = to_number(isset($l['harga']) ? $l['harga'] : 0);
            if ($k === '' && $harga == 0) continue;
            if ( ! $this->akuntansi->akun($k)) return $this->gagal('Akun pada rincian belum dipilih / tidak valid.');
            $jml = round($qty * $harga, 2);
            if ($jml <= 0) return $this->gagal('Nilai rincian harus lebih dari 0.');
            $bersih[] = array('kode_akun' => $k, 'keterangan' => trim((string) (isset($l['keterangan']) ? $l['keterangan'] : '')), 'qty' => $qty, 'harga' => $harga, 'jumlah' => $jml);
            $subtotal += $jml;
        }
        if ( ! $bersih) return $this->gagal('Isi minimal satu baris rincian.');
        $diskon = round(max(0, to_number(isset($h['diskon']) ? $h['diskon'] : 0)), 2);
        if ($diskon >= $subtotal) return $this->gagal('Diskon tidak boleh melebihi subtotal.');
        $ppnPersen = max(0, to_number(isset($h['ppn_persen']) ? $h['ppn_persen'] : 0));
        $dpp = $subtotal - $diskon;
        $ppn = round($dpp * $ppnPersen / 100, 2);
        $total = round($dpp + $ppn, 2);
        $akunPpn = null;
        if ($ppn > 0) {
            $kodePpn = $this->akuntansi->setting($jenis === 'AR' ? 'akun_ppn_keluaran' : 'akun_ppn_masukan');
            $akunPpn = $this->akuntansi->akun($kodePpn);
            if ( ! $akunPpn) return $this->gagal('Akun PPN ' . ($jenis === 'AR' ? 'Keluaran' : 'Masukan') . ' belum diatur di menu Tutup Buku & Preferensi.');
        }

        $this->db->trans_begin();
        $no = trim((string) (isset($h['no_faktur']) ? $h['no_faktur'] : ''));
        if ($no === '') $no = $this->akuntansi->nomor_baru('tbl_acc_faktur', 'no_faktur', $jenis === 'AR' ? 'INV' : 'BIL', $tgl);
        if ($this->db->where(array('jenis' => $jenis, 'no_faktur' => $no))->count_all_results('tbl_acc_faktur')) {
            return $this->gagal('Nomor faktur ' . $no . ' sudah dipakai.');
        }
        $this->db->insert('tbl_acc_faktur', array(
            'jenis' => $jenis, 'no_faktur' => $no, 'no_referensi' => mb_substr(trim((string) $h['no_referensi']), 0, 50),
            'tanggal' => $tgl, 'jatuh_tempo' => $jt, 'kontak' => mb_substr($kontak, 0, 100), 'kode_akun_ar_ap' => $akunArAp->kode_akun,
            'keterangan' => mb_substr(trim((string) $h['keterangan']), 0, 255), 'subtotal' => $subtotal, 'diskon' => $diskon,
            'ppn_persen' => $ppnPersen, 'ppn' => $ppn, 'total' => $total, 'terbayar' => 0, 'status' => 'BELUM',
            'user' => $this->user(), 'created_at' => date('Y-m-d H:i:s'),
        ) + ($this->db->field_exists('no_faktur_pajak', 'tbl_acc_faktur')
            ? array('no_faktur_pajak' => $ppn > 0 ? mb_substr(trim((string) (isset($h['no_faktur_pajak']) ? $h['no_faktur_pajak'] : '')), 0, 30) : '')
            : array()));
        $id = $this->db->insert_id();

        $ket = ($jenis === 'AR' ? 'Penjualan ' : 'Pembelian ') . $kontak . ' ' . $no;
        $jl = array();
        // alokasi diskon proporsional ke baris
        $sisaDisk = $diskon; $n = count($bersih);
        foreach ($bersih as $i => $b) {
            $this->db->insert('tbl_acc_faktur_detail', array('id_faktur' => $id) + $b);
            $dk = $i === $n - 1 ? $sisaDisk : round($diskon * $b['jumlah'] / $subtotal, 2);
            $sisaDisk -= $dk;
            $nilai = $b['jumlah'] - $dk;
            $jl[] = $jenis === 'AR'
                ? array('kode_akun' => $b['kode_akun'], 'kredit' => $nilai, 'keterangan' => $b['keterangan'] ?: $ket)
                : array('kode_akun' => $b['kode_akun'], 'debit' => $nilai, 'keterangan' => $b['keterangan'] ?: $ket);
        }
        if ($ppn > 0) $jl[] = $jenis === 'AR' ? array('kode_akun' => $akunPpn->kode_akun, 'kredit' => $ppn, 'keterangan' => 'PPN ' . $no)
                                             : array('kode_akun' => $akunPpn->kode_akun, 'debit' => $ppn, 'keterangan' => 'PPN ' . $no);
        $jl[] = $jenis === 'AR' ? array('kode_akun' => $akunArAp->kode_akun, 'debit' => $total, 'keterangan' => $ket)
                                : array('kode_akun' => $akunArAp->kode_akun, 'kredit' => $total, 'keterangan' => $ket);

        $ref = $this->akuntansi->posting($jenis === 'AR' ? 'FJ' : 'FB', $tgl, $ket, $jl, $id, $no);
        if ( ! $ref) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $id)->update('tbl_acc_faktur', array('no_ref_jurnal' => $ref));
        if ($this->db->trans_status() === FALSE) return $this->gagal('Gagal menyimpan faktur.');
        $this->db->trans_commit();
        return $id;
    }

    public function faktur_batal($id, $jenis)
    {
        $f = $this->faktur_get($id, $jenis);
        if ( ! $f) return $this->gagal('Faktur tidak ditemukan.');
        if ($f->status === 'BATAL') return $this->gagal('Faktur sudah dibatalkan.');
        if ($f->bayar) return $this->gagal('Faktur sudah memiliki pembayaran. Batalkan pembayarannya terlebih dahulu.');
        $this->db->trans_begin();
        if ( ! $this->akuntansi->hapus_jurnal($f->no_ref_jurnal)) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $f->id)->update('tbl_acc_faktur', array('status' => 'BATAL', 'no_ref_jurnal' => ''));
        $this->db->trans_commit();
        return true;
    }

    private function faktur_refresh_status($id)
    {
        $f = $this->db->get_where('tbl_acc_faktur', array('id' => $id))->row();
        if ( ! $f || $f->status === 'BATAL') return;
        $kol = $this->db->field_exists('potongan', 'tbl_acc_pelunasan') ? 'SUM(jumlah + potongan) AS j' : 'SUM(jumlah) AS j';
        $r = $this->db->select($kol, false)->get_where('tbl_acc_pelunasan', array('id_faktur' => $id))->row();
        $bayar = round((float) $r->j, 2);
        $status = $bayar <= 0 ? 'BELUM' : ($bayar + 0.004 >= (float) $f->total ? 'LUNAS' : 'SEBAGIAN');
        $this->db->where('id', $id)->update('tbl_acc_faktur', array('terbayar' => $bayar, 'status' => $status));
    }

    public function pelunasan_simpan($idFaktur, $jenis, array $p)
    {
        $this->error = '';
        $f = $this->faktur_get($idFaktur, $jenis);
        if ( ! $f || $f->status === 'BATAL') return $this->gagal('Faktur tidak ditemukan / dibatalkan.');
        $tgl = tgl_sql($p['tanggal']);
        $kas = $this->akuntansi->akun($p['kode_akun_kas']);
        $jml = round(to_number($p['jumlah']), 2);
        $sisa = round((float) $f->total - (float) $f->terbayar, 2);
        // potongan PPh (bukti potong): AR = dipotong pelanggan (kredit pajak), AP = kita potong (hutang pajak)
        $punyaPot = $this->db->field_exists('potongan', 'tbl_acc_pelunasan');
        $pot = $punyaPot ? round(max(0, to_number(isset($p['potongan']) ? $p['potongan'] : 0)), 2) : 0;
        $jenisPph = isset($p['jenis_pph']) ? strtoupper(trim((string) $p['jenis_pph'])) : '';
        $akunPot = null;
        if ( ! $tgl) return $this->gagal('Tanggal wajib diisi.');
        if ($tgl < $f->tanggal) return $this->gagal('Tanggal pembayaran tidak boleh sebelum tanggal faktur.');
        if ( ! $kas) return $this->gagal('Akun kas/bank wajib dipilih.');
        if ($jml <= 0 && $pot <= 0) return $this->gagal('Jumlah pembayaran harus lebih dari 0.');
        if ($jml < 0) return $this->gagal('Jumlah pembayaran tidak boleh negatif.');
        if ($jml + $pot - $sisa > 0.004) return $this->gagal('Jumlah + potongan PPh melebihi sisa tagihan (' . nf($sisa, 2) . ').');
        if ($pot > 0) {
            $peta = $jenis === 'AR'
                ? array('PPH23' => 'akun_pph23_dimuka', 'PPH22' => 'akun_pph22_dimuka')
                : array('PPH23' => 'akun_hutang_pph23', 'PPH42' => 'akun_hutang_pph42', 'PPH21' => 'akun_hutang_pph21');
            if ( ! isset($peta[$jenisPph])) return $this->gagal('Pilih jenis PPh yang dipotong.');
            $akunPot = $this->akuntansi->akun($this->akuntansi->setting($peta[$jenisPph]));
            if ( ! $akunPot) return $this->gagal('Akun untuk ' . $jenisPph . ' belum diatur di menu Perpajakan > Pengaturan.');
        }

        $this->db->trans_begin();
        $no = $this->akuntansi->nomor_baru('tbl_acc_pelunasan', 'no_bukti', $jenis === 'AR' ? 'RCV' : 'PAY', $tgl);
        $this->db->insert('tbl_acc_pelunasan', array('no_bukti' => $no, 'id_faktur' => $f->id, 'tanggal' => $tgl,
            'kode_akun_kas' => $kas->kode_akun, 'jumlah' => $jml, 'keterangan' => mb_substr(trim((string) $p['keterangan']), 0, 255),
            'user' => $this->user(), 'created_at' => date('Y-m-d H:i:s'))
            + ($punyaPot ? array('potongan' => $pot, 'jenis_pph' => $pot > 0 ? $jenisPph : '',
                'no_bukti_potong' => $pot > 0 ? mb_substr(trim((string) (isset($p['no_bukti_potong']) ? $p['no_bukti_potong'] : '')), 0, 50) : '') : array()));
        $idp = $this->db->insert_id();
        $ket = ($jenis === 'AR' ? 'Penerimaan piutang ' : 'Pembayaran hutang ') . $f->kontak . ' ' . $f->no_faktur;
        $ketPot = $jenisPph . ' dipotong ' . $f->no_faktur . (empty($p['no_bukti_potong']) ? '' : ' BP ' . trim((string) $p['no_bukti_potong']));
        if ($jenis === 'AR') {
            $jl = array(array('kode_akun' => $kas->kode_akun, 'debit' => $jml));
            if ($pot > 0) $jl[] = array('kode_akun' => $akunPot->kode_akun, 'debit' => $pot, 'keterangan' => $ketPot);
            $jl[] = array('kode_akun' => $f->kode_akun_ar_ap, 'kredit' => $jml + $pot);
        } else {
            $jl = array(array('kode_akun' => $f->kode_akun_ar_ap, 'debit' => $jml + $pot), array('kode_akun' => $kas->kode_akun, 'kredit' => $jml));
            if ($pot > 0) $jl[] = array('kode_akun' => $akunPot->kode_akun, 'kredit' => $pot, 'keterangan' => $ketPot);
        }
        $ref = $this->akuntansi->posting($jenis === 'AR' ? 'PP' : 'PH', $tgl, $ket, $jl, $idp, $no);
        if ( ! $ref) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $idp)->update('tbl_acc_pelunasan', array('no_ref_jurnal' => $ref));
        $this->faktur_refresh_status($f->id);
        if ($this->db->trans_status() === FALSE) return $this->gagal('Gagal menyimpan pembayaran.');
        $this->db->trans_commit();
        return $idp;
    }

    public function pelunasan_hapus($idp, $jenis)
    {
        $p = $this->db->select('p.*')->from('tbl_acc_pelunasan p')->join('tbl_acc_faktur f', 'f.id = p.id_faktur')
            ->where('p.id', (int) $idp)->where('f.jenis', $jenis)->get()->row();
        if ( ! $p) return $this->gagal('Pembayaran tidak ditemukan.');
        $this->db->trans_begin();
        if ( ! $this->akuntansi->hapus_jurnal($p->no_ref_jurnal)) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $p->id)->delete('tbl_acc_pelunasan');
        $this->faktur_refresh_status($p->id_faktur);
        $this->db->trans_commit();
        return true;
    }

    /* ==================================================================
     * ASET TETAP
     * ================================================================ */
    public function aset_list($status = '')
    {
        $akm = array();
        foreach ($this->db->select('id_aset, SUM(jumlah) AS j, MAX(periode) AS p', false)->group_by('id_aset')->get('tbl_acc_aset_susut')->result() as $r) $akm[$r->id_aset] = $r;
        if ($status !== '') $this->db->where('status', $status);
        $rows = $this->db->order_by('kode_aset')->get('tbl_acc_aset')->result();
        foreach ($rows as $r) {
            $r->akumulasi = isset($akm[$r->id]) ? (float) $akm[$r->id]->j : 0;
            $r->susut_terakhir = isset($akm[$r->id]) ? $akm[$r->id]->p : '';
            $r->nilai_buku = (float) $r->harga_perolehan - $r->akumulasi;
        }
        return $rows;
    }

    public function aset_get($id)
    {
        $a = $this->db->get_where('tbl_acc_aset', array('id' => (int) $id))->row();
        if ($a) {
            $a->susut = $this->db->order_by('periode')->get_where('tbl_acc_aset_susut', array('id_aset' => $a->id))->result();
            $a->akumulasi = 0; foreach ($a->susut as $s) $a->akumulasi += (float) $s->jumlah;
        }
        return $a;
    }

    public function aset_simpan(array $d, $jurnalPerolehan = false)
    {
        $this->error = '';
        $tglP = tgl_sql($d['tgl_perolehan']);
        $tglS = tgl_sql($d['tgl_mulai_susut']) ?: $tglP;
        $harga = round(to_number($d['harga_perolehan']), 2);
        $residu = round(to_number($d['nilai_residu']), 2);
        $umur = (int) $d['umur_bulan'];
        $metode = in_array($d['metode'], array('GARIS_LURUS', 'SALDO_MENURUN', 'TIDAK'), true) ? $d['metode'] : 'GARIS_LURUS';
        foreach (array('akun_aset' => 'Akun aset', 'akun_akumulasi' => 'Akun akumulasi penyusutan', 'akun_beban' => 'Akun beban penyusutan') as $k => $lbl) {
            if ( ! $this->akuntansi->akun($d[$k])) return $this->gagal($lbl . ' wajib dipilih.');
        }
        if (trim((string) $d['nama_aset']) === '') return $this->gagal('Nama aset wajib diisi.');
        if ( ! $tglP) return $this->gagal('Tanggal perolehan wajib diisi.');
        if ($harga <= 0) return $this->gagal('Harga perolehan harus lebih dari 0.');
        if ($residu < 0 || $residu >= $harga) return $this->gagal('Nilai residu harus di antara 0 dan harga perolehan.');
        if ($metode !== 'TIDAK' && $umur < 1) return $this->gagal('Umur ekonomis (bulan) wajib diisi.');

        $row = array(
            'nama_aset' => mb_substr(trim($d['nama_aset']), 0, 150), 'kelompok_aset' => mb_substr(trim((string) $d['kelompok_aset']), 0, 50),
            'lokasi' => mb_substr(trim((string) $d['lokasi']), 0, 50), 'tgl_perolehan' => $tglP, 'tgl_mulai_susut' => $tglS,
            'harga_perolehan' => $harga, 'nilai_residu' => $residu, 'umur_bulan' => max(0, $umur), 'metode' => $metode,
            'akun_aset' => $d['akun_aset'], 'akun_akumulasi' => $d['akun_akumulasi'], 'akun_beban' => $d['akun_beban'],
            'akun_lawan_perolehan' => (string) $d['akun_lawan_perolehan'], 'keterangan' => mb_substr(trim((string) $d['keterangan']), 0, 255),
        );
        $id = (int) (isset($d['id']) ? $d['id'] : 0);
        $this->db->trans_begin();
        if ($id) {
            $lama = $this->aset_get($id);
            if ( ! $lama) return $this->gagal('Aset tidak ditemukan.');
            if ($lama->susut && ($lama->harga_perolehan != $harga || $lama->tgl_mulai_susut !== $tglS || $lama->metode !== $metode)) {
                return $this->gagal('Aset sudah disusutkan. Harga, metode & tanggal mulai susut tidak dapat diubah.');
            }
            $this->db->where('id', $id)->update('tbl_acc_aset', $row);
        } else {
            $kode = trim((string) $d['kode_aset']);
            if ($kode === '') $kode = $this->akuntansi->nomor_baru('tbl_acc_aset', 'kode_aset', 'AT', $tglP);
            if ($this->db->where('kode_aset', $kode)->count_all_results('tbl_acc_aset')) return $this->gagal('Kode aset sudah dipakai.');
            $row += array('kode_aset' => $kode, 'status' => 'AKTIF', 'user' => $this->user(), 'created_at' => date('Y-m-d H:i:s'));
            $this->db->insert('tbl_acc_aset', $row);
            $id = $this->db->insert_id();
            if ($jurnalPerolehan) {
                $lawan = $this->akuntansi->akun($d['akun_lawan_perolehan']);
                if ( ! $lawan) return $this->gagal('Pilih akun pembayaran (kas/bank/hutang) untuk jurnal perolehan.');
                $ref = $this->akuntansi->posting('AT', $tglP, 'Perolehan aset ' . $row['nama_aset'], array(
                    array('kode_akun' => $row['akun_aset'], 'debit' => $harga),
                    array('kode_akun' => $lawan->kode_akun, 'kredit' => $harga)), $id, $kode);
                if ( ! $ref) return $this->gagal($this->akuntansi->error);
                $this->db->where('id', $id)->update('tbl_acc_aset', array('no_ref_jurnal' => $ref));
            }
        }
        if ($this->db->trans_status() === FALSE) return $this->gagal('Gagal menyimpan aset.');
        $this->db->trans_commit();
        return $id;
    }

    /** nilai penyusutan satu bulan untuk aset pada periode tertentu */
    public function hitung_susut($a, $akumulasi)
    {
        $dasar = (float) $a->harga_perolehan - (float) $a->nilai_residu;
        $sisa = $dasar - $akumulasi;
        if ($a->metode === 'TIDAK' || $sisa <= 0.004 || (int) $a->umur_bulan < 1) return 0;
        if ($a->metode === 'SALDO_MENURUN') {
            // saldo menurun ganda (double declining) per bulan, minimal garis lurus sisa umur
            $tarifTahun = 2 / max(1, $a->umur_bulan / 12);
            $nilaiBuku = (float) $a->harga_perolehan - $akumulasi;
            $v = round($nilaiBuku * $tarifTahun / 12, 2);
        } else {
            $v = round($dasar / (int) $a->umur_bulan, 2);
        }
        return min($v, round($sisa, 2));
    }

    /**
     * Proses penyusutan s/d periode YYYY-MM untuk semua aset aktif.
     * Satu jurnal per bulan (gabungan seluruh aset). Return jumlah baris diproses.
     */
    public function proses_susut($sampaiPeriode)
    {
        $this->error = '';
        if ( ! preg_match('/^\d{4}-\d{2}$/', $sampaiPeriode)) return $this->gagal('Periode tidak valid.');
        $aset = $this->db->where('status', 'AKTIF')->where('metode !=', 'TIDAK')->get('tbl_acc_aset')->result();
        $rencana = array(); // periode => [ [aset, jumlah] ]
        foreach ($aset as $a) {
            $done = array(); $akm = 0;
            foreach ($this->db->get_where('tbl_acc_aset_susut', array('id_aset' => $a->id))->result() as $s) { $done[$s->periode] = true; $akm += (float) $s->jumlah; }
            $p = substr($a->tgl_mulai_susut, 0, 7); $i = 0;
            while ($p <= $sampaiPeriode && $i < 600) {
                if ( ! isset($done[$p])) {
                    $v = $this->hitung_susut($a, $akm);
                    if ($v <= 0) break;
                    $rencana[$p][] = array($a, $v); $akm += $v;
                }
                $p = date('Y-m', strtotime($p . '-01 +1 month')); $i++;
            }
        }
        if ( ! $rencana) return 0;
        ksort($rencana);
        $n = 0;
        $this->db->trans_begin();
        foreach ($rencana as $per => $items) {
            $tgl = date('Y-m-t', strtotime($per . '-01'));
            if ($this->akuntansi->periode_tertutup($tgl)) return $this->gagal('Periode ' . $per . ' sudah ditutup; penyusutan tidak dapat diposting.');
            $jl = array(); $agg = array();
            foreach ($items as $it) {
                list($a, $v) = $it;
                $jl[] = array('kode_akun' => $a->akun_beban, 'debit' => $v, 'keterangan' => 'Penyusutan ' . $a->kode_aset . ' ' . $a->nama_aset);
                $agg[$a->akun_akumulasi] = (isset($agg[$a->akun_akumulasi]) ? $agg[$a->akun_akumulasi] : 0) + $v;
            }
            foreach ($agg as $k => $v) $jl[] = array('kode_akun' => $k, 'kredit' => round($v, 2), 'keterangan' => 'Akumulasi penyusutan ' . $per);
            $ref = $this->akuntansi->posting('PNY', $tgl, 'Penyusutan aset tetap periode ' . $per, $jl, null, 'PNY-' . $per);
            if ( ! $ref) return $this->gagal($this->akuntansi->error);
            foreach ($items as $it) {
                $this->db->insert('tbl_acc_aset_susut', array('id_aset' => $it[0]->id, 'periode' => $per, 'jumlah' => $it[1],
                    'no_ref_jurnal' => $ref, 'created_at' => date('Y-m-d H:i:s')));
                $n++;
            }
        }
        if ($this->db->trans_status() === FALSE) return $this->gagal('Gagal memproses penyusutan.');
        $this->db->trans_commit();
        return $n;
    }

    /** batalkan penyusutan periode tertentu (seluruh aset) */
    public function batal_susut($periode)
    {
        $refs = $this->db->distinct()->select('no_ref_jurnal')->get_where('tbl_acc_aset_susut', array('periode' => $periode))->result();
        if ( ! $refs) return $this->gagal('Tidak ada penyusutan pada periode ' . $periode . '.');
        $lebihBaru = $this->db->where('periode >', $periode)->count_all_results('tbl_acc_aset_susut');
        if ($lebihBaru) return $this->gagal('Batalkan dulu penyusutan periode setelah ' . $periode . '.');
        $this->db->trans_begin();
        foreach ($refs as $r) if ( ! $this->akuntansi->hapus_jurnal($r->no_ref_jurnal)) return $this->gagal($this->akuntansi->error);
        $this->db->where('periode', $periode)->delete('tbl_acc_aset_susut');
        $this->db->trans_commit();
        return true;
    }

    public function aset_lepas($id, $tgl, $nilaiJual, $akunKas, $status)
    {
        $a = $this->aset_get($id);
        $tgl = tgl_sql($tgl);
        $nilaiJual = round(max(0, to_number($nilaiJual)), 2);
        if ( ! $a || $a->status !== 'AKTIF') return $this->gagal('Aset tidak ditemukan / sudah dilepas.');
        if ( ! $tgl) return $this->gagal('Tanggal pelepasan wajib diisi.');
        if ($status === 'DIJUAL' && $nilaiJual > 0 && ! $this->akuntansi->akun($akunKas)) return $this->gagal('Pilih akun penerimaan (kas/bank/piutang).');
        $buku = (float) $a->harga_perolehan - $a->akumulasi;
        $lr = round($nilaiJual - $buku, 2);
        $jl = array();
        if ($a->akumulasi > 0) $jl[] = array('kode_akun' => $a->akun_akumulasi, 'debit' => $a->akumulasi);
        if ($nilaiJual > 0) $jl[] = array('kode_akun' => $akunKas, 'debit' => $nilaiJual);
        $jl[] = array('kode_akun' => $a->akun_aset, 'kredit' => $a->harga_perolehan);
        if ($lr > 0) $jl[] = array('kode_akun' => $this->akuntansi->setting('akun_laba_pelepasan_aset', '532'), 'kredit' => $lr);
        if ($lr < 0) $jl[] = array('kode_akun' => $this->akuntansi->setting('akun_rugi_pelepasan_aset', '531'), 'debit' => -$lr);
        $this->db->trans_begin();
        $ref = $this->akuntansi->posting('LA', $tgl, ($status === 'DIJUAL' ? 'Penjualan' : 'Penghapusan') . ' aset ' . $a->kode_aset . ' ' . $a->nama_aset, $jl, $a->id, $a->kode_aset);
        if ( ! $ref) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $a->id)->update('tbl_acc_aset', array('status' => $status === 'DIJUAL' ? 'DIJUAL' : 'DIHAPUS',
            'tgl_lepas' => $tgl, 'nilai_lepas' => $nilaiJual, 'no_ref_lepas' => $ref));
        $this->db->trans_commit();
        return true;
    }

    public function aset_hapus($id)
    {
        $a = $this->aset_get($id);
        if ( ! $a) return $this->gagal('Aset tidak ditemukan.');
        if ($a->susut) return $this->gagal('Aset sudah disusutkan, tidak dapat dihapus. Gunakan fitur Pelepasan Aset.');
        if ($a->no_ref_lepas) return $this->gagal('Aset sudah dilepas.');
        $this->db->trans_begin();
        if ( ! $this->akuntansi->hapus_jurnal($a->no_ref_jurnal)) return $this->gagal($this->akuntansi->error);
        $this->db->where('id', $a->id)->delete('tbl_acc_aset');
        $this->db->trans_commit();
        return true;
    }

    /* ==================================================================
     * ANGGARAN
     * ================================================================ */
    public function anggaran_get($tahun)
    {
        $out = array();
        foreach ($this->db->get_where('tbl_acc_anggaran', array('tahun' => (int) $tahun))->result() as $r) $out[$r->kode_akun] = $r;
        return $out;
    }

    public function anggaran_simpan($tahun, array $data)
    {
        $tahun = (int) $tahun;
        $this->db->trans_begin();
        foreach ($data as $kode => $bulan) {
            if ( ! $this->akuntansi->akun($kode)) continue;
            $row = array(); $ada = false;
            for ($b = 1; $b <= 12; $b++) {
                $v = round(to_number(isset($bulan[$b]) ? $bulan[$b] : 0), 2);
                $row[sprintf('b%02d', $b)] = $v; if ($v != 0) $ada = true;
            }
            $exist = $this->db->get_where('tbl_acc_anggaran', array('tahun' => $tahun, 'kode_akun' => $kode))->row();
            if ($exist) {
                if ($ada) $this->db->where('id', $exist->id)->update('tbl_acc_anggaran', $row);
                else $this->db->where('id', $exist->id)->delete('tbl_acc_anggaran');
            } elseif ($ada) {
                $this->db->insert('tbl_acc_anggaran', array('tahun' => $tahun, 'kode_akun' => $kode) + $row);
            }
        }
        $this->db->trans_commit();
        return true;
    }

    /* ==================================================================
     * PERIODE / TUTUP BUKU
     * ================================================================ */
    public function periode_tahun($tahun)
    {
        $st = array();
        foreach ($this->db->like('periode', $tahun . '-', 'after')->get('tbl_acc_periode')->result() as $r) $st[$r->periode] = $r;
        $jml = array();
        foreach ($this->db->select('SUBSTR(tgl_jurnal,1,7) AS p, COUNT(DISTINCT no_ref) AS n, SUM(debit) AS d', false)
            ->where('status', 'Y')->where('tgl_jurnal >=', $tahun . '-01-01')->where('tgl_jurnal <=', $tahun . '-12-31')
            ->group_by('SUBSTR(tgl_jurnal,1,7)', false)->get('tbl_acc_jurnal_umum')->result() as $r) $jml[$r->p] = $r;
        $out = array();
        for ($b = 1; $b <= 12; $b++) {
            $p = sprintf('%04d-%02d', $tahun, $b);
            $out[] = (object) array('periode' => $p, 'status' => isset($st[$p]) ? $st[$p]->status : 'BUKA',
                'user' => isset($st[$p]) ? $st[$p]->user : '', 'updated_at' => isset($st[$p]) ? $st[$p]->updated_at : '',
                'jurnal' => isset($jml[$p]) ? (int) $jml[$p]->n : 0, 'nilai' => isset($jml[$p]) ? (float) $jml[$p]->d : 0);
        }
        return $out;
    }

    public function set_periode($periode, $status, $ket = '')
    {
        if ( ! preg_match('/^\d{4}-\d{2}$/', $periode)) return $this->gagal('Periode tidak valid.');
        $status = $status === 'TUTUP' ? 'TUTUP' : 'BUKA';
        $row = array('status' => $status, 'keterangan' => (string) $ket, 'user' => $this->user(), 'updated_at' => date('Y-m-d H:i:s'));
        if ($this->db->where('periode', $periode)->count_all_results('tbl_acc_periode')) $this->db->where('periode', $periode)->update('tbl_acc_periode', $row);
        else $this->db->insert('tbl_acc_periode', array('periode' => $periode) + $row);
        return true;
    }

    /** jurnal penutup tahunan: nolkan akun pendapatan & beban ke laba ditahan */
    public function jurnal_penutup($tahun)
    {
        $tgl = $tahun . '-12-31';
        $akunLD = $this->akuntansi->akun($this->akuntansi->setting('akun_laba_ditahan'));
        if ( ! $akunLD) return $this->gagal('Akun Laba Ditahan belum diatur di Preferensi.');
        $ada = $this->db->where('sumber', 'TTP')->where('tgl_jurnal', $tgl)->where('status', 'Y')->count_all_results('tbl_acc_jurnal_umum');
        if ($ada) return $this->gagal('Jurnal penutup tahun ' . $tahun . ' sudah dibuat.');
        $mut = $this->akuntansi->mutasi($tahun . '-01-01', $tgl);
        $jl = array(); $laba = 0;
        foreach ($mut as $kode => $m) {
            $a = $this->akuntansi->akun($kode);
            $g = Akuntansi::grup_kategori($a ? $a->kategori : $this->akuntansi->tebak_kategori($kode));
            if ($g !== 'PENDAPATAN' && $g !== 'BEBAN') continue;
            $saldo = round($m['d'] - $m['k'], 2); // debit positif
            if (abs($saldo) < 0.005) continue;
            $jl[] = $saldo > 0 ? array('kode_akun' => $kode, 'kredit' => $saldo) : array('kode_akun' => $kode, 'debit' => -$saldo);
            $laba -= $saldo;
        }
        if ( ! $jl) return $this->gagal('Tidak ada saldo pendapatan/beban pada tahun ' . $tahun . '.');
        $laba = round($laba, 2);
        $jl[] = $laba >= 0 ? array('kode_akun' => $akunLD->kode_akun, 'kredit' => $laba) : array('kode_akun' => $akunLD->kode_akun, 'debit' => -$laba);
        $this->db->trans_begin();
        $this->akuntansi->izinkan_akun_lama = true;
        $ref = $this->akuntansi->posting('TTP', $tgl, 'Jurnal penutup tahun ' . $tahun, $jl, null, 'TTP-' . $tahun);
        $this->akuntansi->izinkan_akun_lama = false;
        if ( ! $ref) return $this->gagal($this->akuntansi->error);
        $this->db->trans_commit();
        return $ref;
    }
}
