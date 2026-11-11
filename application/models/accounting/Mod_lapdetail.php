<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Laporan detail & analisis:
 *  - Kartu (buku pembantu) piutang / hutang per kontak + rekonsiliasi ke akun kontrol
 *  - Daftar faktur penjualan / pembelian (opsional rincian baris)
 *  - Jadwal penyusutan aset tetap per bulan (realisasi + proyeksi)
 *  - Analisis rasio keuangan beserta tafsiran
 *  - Pemeriksaan kesehatan data akuntansi
 *  - Catatan atas Laporan Keuangan (CaLK) otomatis
 */
class Mod_lapdetail extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('akuntansi');
        $this->load->model('accounting/Mod_lapkeu');
    }

    private function ada_potongan()
    {
        return $this->db->field_exists('potongan', 'tbl_acc_pelunasan');
    }

    /* ==================================================================
     * BUKU PEMBANTU PIUTANG / HUTANG
     * ================================================================ */
    public function kontak_list($jenis)
    {
        if ( ! $this->db->table_exists('tbl_acc_faktur')) return array();
        $r = $this->db->distinct()->select('kontak')->where('jenis', $jenis)->order_by('kontak')->get('tbl_acc_faktur')->result();
        return array_map(function ($x) { return $x->kontak; }, $r);
    }

    public function kartu_kontak($jenis, $dari, $sampai, $kontak = '')
    {
        $out = array('kontak' => array(), 'total' => (object) array('awal' => 0, 'tambah' => 0, 'kurang' => 0, 'akhir' => 0), 'kontrol' => array());
        if ( ! $this->db->table_exists('tbl_acc_faktur')) return $out;

        $this->db->from('tbl_acc_faktur')->where('jenis', $jenis)->where('status !=', 'BATAL')->where('tanggal <=', $sampai);
        if ($kontak !== '') $this->db->where('kontak', $kontak);
        $faktur = $this->db->order_by('tanggal, id')->get()->result();

        $pot = $this->ada_potongan() ? ', p.potongan, p.jenis_pph, p.no_bukti_potong' : ', 0 AS potongan, \'\' AS jenis_pph, \'\' AS no_bukti_potong';
        $this->db->select('p.tanggal, p.no_bukti, p.jumlah, p.keterangan, f.kontak, f.no_faktur' . $pot, false)
            ->from('tbl_acc_pelunasan p')->join('tbl_acc_faktur f', 'f.id = p.id_faktur')
            ->where('f.jenis', $jenis)->where('f.status !=', 'BATAL')->where('p.tanggal <=', $sampai);
        if ($kontak !== '') $this->db->where('f.kontak', $kontak);
        $bayar = $this->db->order_by('p.tanggal, p.id')->get()->result();

        $K = array();
        $get = function ($k) use (&$K) {
            if ( ! isset($K[$k])) $K[$k] = (object) array('kontak' => $k, 'awal' => 0.0, 'tambah' => 0.0, 'kurang' => 0.0, 'akhir' => 0.0, 'baris' => array());
            return $K[$k];
        };
        foreach ($faktur as $f) {
            $c = $get($f->kontak);
            if ($f->tanggal < $dari) { $c->awal += (float) $f->total; continue; }
            $c->baris[] = (object) array('tanggal' => $f->tanggal, 'no' => $f->no_faktur, 'ket' => 'Faktur' . ($f->keterangan !== '' ? ' — ' . $f->keterangan : ''),
                'jt' => $f->jatuh_tempo, 'tambah' => (float) $f->total, 'kurang' => 0.0);
        }
        foreach ($bayar as $p) {
            $c = $get($p->kontak);
            $nilai = (float) $p->jumlah + (float) $p->potongan;
            if ($p->tanggal < $dari) { $c->awal -= $nilai; continue; }
            $c->baris[] = (object) array('tanggal' => $p->tanggal, 'no' => $p->no_bukti, 'ket' => 'Pelunasan ' . $p->no_faktur, 'jt' => '', 'tambah' => 0.0, 'kurang' => (float) $p->jumlah);
            if ((float) $p->potongan > 0) {
                $c->baris[] = (object) array('tanggal' => $p->tanggal, 'no' => $p->no_bukti_potong !== '' ? $p->no_bukti_potong : $p->no_bukti,
                    'ket' => 'Dipotong ' . str_replace('PPH', 'PPh ', $p->jenis_pph) . ' atas ' . $p->no_faktur, 'jt' => '', 'tambah' => 0.0, 'kurang' => (float) $p->potongan);
            }
        }
        ksort($K, SORT_NATURAL | SORT_FLAG_CASE);
        $T = $out['total'];
        foreach ($K as $k => $c) {
            usort($c->baris, function ($a, $b) { return strcmp($a->tanggal, $b->tanggal); });
            $s = round($c->awal, 2);
            foreach ($c->baris as $b) { $s += $b->tambah - $b->kurang; $b->saldo = round($s, 2); $c->tambah += $b->tambah; $c->kurang += $b->kurang; }
            $c->akhir = round($s, 2);
            if (abs($c->awal) < 0.005 && ! $c->baris) { unset($K[$k]); continue; }
            $T->awal += $c->awal; $T->tambah += $c->tambah; $T->kurang += $c->kurang; $T->akhir += $c->akhir;
        }
        $out['kontak'] = array_values($K);

        // rekonsiliasi ke akun kontrol (hanya bila tanpa filter kontak)
        if ($kontak === '') {
            $kode = array();
            foreach ($this->db->distinct()->select('kode_akun_ar_ap')->where('jenis', $jenis)->get('tbl_acc_faktur')->result() as $r) if ($r->kode_akun_ar_ap !== '') $kode[] = (string) $r->kode_akun_ar_ap;
            $def = (string) $this->akuntansi->setting($jenis === 'AR' ? 'akun_piutang' : 'akun_hutang');
            if ($def !== '' && ! in_array($def, $kode, true)) $kode[] = $def;
            if ($kode) {
                $saldo = $this->akuntansi->saldo_per($sampai, $kode);
                foreach ($kode as $k) {
                    $a = $this->akuntansi->akun($k);
                    if ( ! $a) continue;
                    $s = isset($saldo[$k]) ? $saldo[$k] : array('d' => 0, 'k' => 0);
                    $out['kontrol'][] = (object) array('kode' => $k, 'nama' => $a->nama_akun, 'saldo' => $jenis === 'AR' ? $s['d'] - $s['k'] : $s['k'] - $s['d']);
                }
            }
        }
        return $out;
    }

    /* ==================================================================
     * DAFTAR FAKTUR
     * ================================================================ */
    public function daftar_faktur($jenis, $dari, $sampai, $status = '', $rinci = false)
    {
        if ( ! $this->db->table_exists('tbl_acc_faktur')) return array();
        $this->db->from('tbl_acc_faktur')->where('jenis', $jenis)->where('tanggal >=', $dari)->where('tanggal <=', $sampai);
        if ($status !== '') $this->db->where('status', $status);
        $rows = $this->db->order_by('tanggal, no_faktur')->get()->result();
        $det = array();
        if ($rinci && $rows) {
            $ids = array_map(function ($r) { return (int) $r->id; }, $rows);
            foreach ($this->db->where_in('id_faktur', $ids)->order_by('id')->get('tbl_acc_faktur_detail')->result() as $x) {
                $a = $this->akuntansi->akun($x->kode_akun);
                $x->nama_akun = $a ? $a->nama_akun : '';
                $det[$x->id_faktur][] = $x;
            }
        }
        foreach ($rows as $r) {
            $r->dpp = (float) $r->subtotal - (float) $r->diskon;
            $r->sisa = $r->status === 'BATAL' ? 0 : round((float) $r->total - (float) $r->terbayar, 2);
            $r->telat = $r->sisa > 0.004 && $r->jatuh_tempo < date('Y-m-d') ? (int) floor((time() - strtotime($r->jatuh_tempo)) / 86400) : 0;
            $r->detail = isset($det[$r->id]) ? $det[$r->id] : array();
            if ( ! isset($r->no_faktur_pajak)) $r->no_faktur_pajak = '';
        }
        return $rows;
    }

    /* ==================================================================
     * JADWAL PENYUSUTAN
     * ================================================================ */
    public function jadwal_susut($tahun)
    {
        $out = array('rows' => array(), 'bulan' => array_fill(1, 12, 0.0), 'total' => (object) array('perolehan' => 0, 'awal' => 0, 'susut' => 0, 'akhir' => 0, 'nb' => 0), 'proyeksi' => false);
        if ( ! $this->db->table_exists('tbl_acc_aset')) return $out;
        $this->load->model('accounting/Mod_acctrx');
        $mulai = sprintf('%04d-01', $tahun); $akhir = sprintf('%04d-12', $tahun);
        $aset = $this->db->where('tgl_perolehan <=', $tahun . '-12-31')->order_by('kelompok_aset, kode_aset')->get('tbl_acc_aset')->result();
        $susut = array();
        foreach ($this->db->select('id_aset, periode, jumlah')->get('tbl_acc_aset_susut')->result() as $s) $susut[$s->id_aset][$s->periode] = (float) $s->jumlah;
        foreach ($aset as $a) {
            $hist = isset($susut[$a->id]) ? $susut[$a->id] : array();
            $akmAwal = 0.0;
            foreach ($hist as $p => $v) if ($p < $mulai) $akmAwal += $v;
            // aset yang sudah dilepas sebelum tahun ini tidak ditampilkan
            if ($a->status !== 'AKTIF' && $a->tgl_lepas && $a->tgl_lepas < $tahun . '-01-01') continue;
            $stopP = ($a->status !== 'AKTIF' && $a->tgl_lepas) ? substr($a->tgl_lepas, 0, 7) : '9999-99';
            $mulaiSusut = substr($a->tgl_mulai_susut ?: $a->tgl_perolehan, 0, 7);
            $akm = $akmAwal; $bulan = array(); $tot = 0.0;
            for ($b = 1; $b <= 12; $b++) {
                $p = sprintf('%04d-%02d', $tahun, $b);
                if (isset($hist[$p])) { $v = $hist[$p]; $jenis = 'R'; }
                elseif ($a->status === 'AKTIF' && $p >= $mulaiSusut && $p <= $stopP) { $v = $this->Mod_acctrx->hitung_susut($a, $akm); $jenis = $v > 0 ? 'P' : ''; }
                else { $v = 0.0; $jenis = ''; }
                if ($jenis === 'P') $out['proyeksi'] = true;
                $akm += $v; $tot += $v;
                $bulan[$b] = (object) array('v' => $v, 'j' => $jenis);
                $out['bulan'][$b] += $v;
            }
            $r = (object) array('kode' => $a->kode_aset, 'nama' => $a->nama_aset, 'kelompok' => $a->kelompok_aset, 'metode' => $a->metode,
                'umur' => (int) $a->umur_bulan, 'tgl' => $a->tgl_perolehan, 'status' => $a->status,
                'perolehan' => (float) $a->harga_perolehan, 'awal' => $akmAwal, 'bulan' => $bulan, 'susut' => $tot, 'akhir' => $akm,
                'nb' => (float) $a->harga_perolehan - $akm);
            $out['rows'][] = $r;
            $T = $out['total'];
            $T->perolehan += $r->perolehan; $T->awal += $r->awal; $T->susut += $r->susut; $T->akhir += $r->akhir; $T->nb += $r->nb;
        }
        return $out;
    }

    /* ==================================================================
     * RASIO KEUANGAN
     * ================================================================ */
    private function posisi($tgl)
    {
        $s = $this->akuntansi->saldo_per($tgl);
        $k = array_fill_keys(array_keys(Akuntansi::$KATEGORI), 0.0);
        foreach ($s as $kode => $v) {
            $a = $this->akuntansi->akun($kode);
            $kat = $a ? $a->kategori : $this->akuntansi->tebak_kategori($kode);
            if ( ! isset($k[$kat])) $k[$kat] = 0.0;
            $k[$kat] += Akuntansi::grup_kategori($kat) === 'ASET' || in_array(Akuntansi::grup_kategori($kat), array('BEBAN'), true) ? $v['d'] - $v['k'] : $v['k'] - $v['d'];
        }
        $nr = $this->Mod_lapkeu->neraca(array($tgl));
        $k['_ASET'] = $nr['grup']['ASET']->total[0];
        $k['_LIAB'] = $nr['grup']['LIABILITAS']->total[0];
        $k['_EKUITAS'] = $nr['grup']['EKUITAS']->total[0];
        $k['_AL'] = $k['KAS_BANK'] + $k['PIUTANG'] + $k['PERSEDIAAN'] + $k['ASET_LANCAR'];
        $k['_LL'] = $k['HUTANG'] + $k['LIAB_PENDEK'];
        return $k;
    }

    public function rasio($dari, $sampai)
    {
        $hari = max(1, (int) round((strtotime($sampai) - strtotime($dari)) / 86400) + 1);
        $tahunan = 365 / $hari;
        $A = $this->posisi($sampai);
        $P = $this->posisi(Akuntansi::kemarin($dari));
        $avg = function ($k) use ($A, $P) { return ($A[$k] + $P[$k]) / 2; };
        $lr = $this->Mod_lapkeu->laba_rugi(array(array('label' => 'x', 'dari' => $dari, 'sampai' => $sampai)));
        $S = $lr['seksi']; $R = $lr['ringkas'];
        $pend = $S['PENDAPATAN']->total[0]; $hpp = $S['HPP']->total[0];
        $lk = $R['laba_kotor'][0]; $lo = $R['laba_operasi'][0]; $lb = $R['laba_bersih'][0];
        $bagi = function ($a, $b) { return abs($b) < 0.005 ? null : $a / $b; };

        $G = array();
        $add = function ($grup, $nama, $rumus, $nilai, $fmt, $baik, $tafsir, $arti) use (&$G) {
            $G[$grup][] = (object) array('nama' => $nama, 'rumus' => $rumus, 'nilai' => $nilai, 'fmt' => $fmt, 'baik' => $baik, 'tafsir' => $tafsir, 'arti' => $arti);
        };
        // status: 'ok' | 'warn' | 'bad' | '' (tidak dapat dinilai)
        $st = function ($v, $ok, $warn, $lebihBesarLebihBaik = true) {
            if ($v === null) return '';
            if ($lebihBesarLebihBaik) return $v >= $ok ? 'ok' : ($v >= $warn ? 'warn' : 'bad');
            return $v <= $ok ? 'ok' : ($v <= $warn ? 'warn' : 'bad');
        };

        // --- Likuiditas
        $cr = $bagi($A['_AL'], $A['_LL']);
        $add('Likuiditas (kemampuan membayar kewajiban jangka pendek)', 'Rasio Lancar (Current Ratio)', 'Aset Lancar ÷ Liabilitas Jangka Pendek', $cr, 'x', $st($cr, 1.5, 1.0),
            $cr === null ? 'Tidak ada liabilitas jangka pendek.' : ($cr >= 1.5 ? 'Sehat: aset lancar cukup untuk menutup kewajiban jangka pendek.' : ($cr >= 1 ? 'Cukup, tetapi ruang geraknya tipis.' : 'Kurang: aset lancar lebih kecil dari kewajiban jangka pendek — risiko kesulitan membayar.')),
            'Setiap Rp1 hutang jangka pendek dijamin oleh Rp' . ($cr === null ? '-' : nf($cr, 2)) . ' aset lancar. Umumnya ideal ≥ 1,5.');
        $qr = $bagi($A['KAS_BANK'] + $A['PIUTANG'], $A['_LL']);
        $add('Likuiditas (kemampuan membayar kewajiban jangka pendek)', 'Rasio Cepat (Quick Ratio)', '(Kas & Bank + Piutang) ÷ Liabilitas Jangka Pendek', $qr, 'x', $st($qr, 1.0, 0.7),
            $qr === null ? '-' : ($qr >= 1 ? 'Baik: tanpa menjual persediaan pun kewajiban jangka pendek bisa dibayar.' : 'Bergantung pada penjualan persediaan untuk membayar hutang jangka pendek.'),
            'Sama seperti rasio lancar tetapi tanpa persediaan (aset yang paling lambat dicairkan). Ideal ≥ 1.');
        $kr = $bagi($A['KAS_BANK'], $A['_LL']);
        $add('Likuiditas (kemampuan membayar kewajiban jangka pendek)', 'Rasio Kas (Cash Ratio)', 'Kas & Bank ÷ Liabilitas Jangka Pendek', $kr, 'x', $st($kr, 0.5, 0.2),
            $kr === null ? '-' : ($kr < 0 ? 'Saldo kas & bank minus — periksa pencatatan (lihat Pemeriksaan Kesehatan Data).' : ($kr >= 0.5 ? 'Posisi kas kuat.' : ($kr >= 0.2 ? 'Kas cukup untuk kebutuhan rutin.' : 'Kas tipis — pantau jadwal penagihan & pembayaran.'))),
            'Porsi kewajiban jangka pendek yang bisa dibayar seketika dengan kas yang ada.');
        $mk = $A['_AL'] - $A['_LL'];
        $add('Likuiditas (kemampuan membayar kewajiban jangka pendek)', 'Modal Kerja Bersih', 'Aset Lancar − Liabilitas Jangka Pendek', $mk, 'rp', $mk >= 0 ? 'ok' : 'bad',
            $mk >= 0 ? 'Positif: ada dana lebih untuk operasional.' : 'Negatif: operasional dibiayai hutang jangka pendek.', 'Dana yang tersedia untuk menjalankan operasi sehari-hari.');

        // --- Solvabilitas
        $der = $bagi($A['_LIAB'], $A['_EKUITAS']);
        $add('Solvabilitas (struktur modal & risiko hutang)', 'Debt to Equity Ratio (DER)', 'Total Liabilitas ÷ Total Ekuitas', $A['_EKUITAS'] <= 0 ? null : $der, 'x', $A['_EKUITAS'] <= 0 ? 'bad' : $st($der, 1.0, 2.0, false),
            $A['_EKUITAS'] <= 0 ? 'Ekuitas nol/negatif (defisiensi modal) — perlu perhatian serius.' : ($der <= 1 ? 'Konservatif: modal sendiri lebih besar dari hutang.' : ($der <= 2 ? 'Wajar, hutang masih terkendali.' : 'Tinggi: perusahaan sangat bergantung pada hutang.')),
            'Berapa rupiah hutang untuk setiap Rp1 modal sendiri.');
        $dar = $bagi($A['_LIAB'], $A['_ASET']);
        $add('Solvabilitas (struktur modal & risiko hutang)', 'Debt to Asset Ratio (DAR)', 'Total Liabilitas ÷ Total Aset', $dar, '%', $st($dar, 0.5, 0.7, false),
            $dar === null ? '-' : ($dar <= 0.5 ? 'Sebagian besar aset dibiayai modal sendiri.' : 'Sebagian besar aset dibiayai hutang.'), 'Persentase aset yang dibiayai oleh hutang.');

        // --- Profitabilitas
        $gpm = $bagi($lk, $pend); $opm = $bagi($lo, $pend); $npm = $bagi($lb, $pend);
        $add('Profitabilitas (kemampuan menghasilkan laba)', 'Margin Laba Kotor', 'Laba Kotor ÷ Pendapatan Usaha', $gpm, '%', $gpm === null ? '' : ($gpm >= 0.25 ? 'ok' : ($gpm > 0 ? 'warn' : 'bad')),
            $gpm === null ? 'Belum ada pendapatan pada periode ini.' : 'Dari setiap Rp100 penjualan tersisa Rp' . nf($gpm * 100, 1) . ' setelah dikurangi HPP.', 'Mengukur efisiensi harga jual dibanding biaya pokok (material, upah langsung).');
        $add('Profitabilitas (kemampuan menghasilkan laba)', 'Margin Laba Operasi', 'Laba Operasi ÷ Pendapatan Usaha', $opm, '%', $opm === null ? '' : ($opm >= 0.1 ? 'ok' : ($opm > 0 ? 'warn' : 'bad')),
            $opm === null ? '-' : ($opm > 0 ? 'Operasi inti menghasilkan laba.' : 'Biaya operasional melebihi laba kotor.'), 'Laba dari kegiatan usaha utama setelah beban operasional.');
        $add('Profitabilitas (kemampuan menghasilkan laba)', 'Margin Laba Bersih', 'Laba Bersih ÷ Pendapatan Usaha', $npm, '%', $npm === null ? '' : ($npm >= 0.05 ? 'ok' : ($npm > 0 ? 'warn' : 'bad')),
            $npm === null ? '-' : 'Laba bersih Rp' . nf($npm * 100, 1) . ' untuk setiap Rp100 penjualan.', 'Laba akhir setelah seluruh beban, pendapatan/beban lain dan pajak.');
        $roa = $bagi($lb * $tahunan, $avg('_ASET'));
        $add('Profitabilitas (kemampuan menghasilkan laba)', 'Return on Assets (ROA, disetahunkan)', 'Laba Bersih (setahun) ÷ Rata-rata Total Aset', $roa, '%', $roa === null ? '' : ($roa >= 0.05 ? 'ok' : ($roa > 0 ? 'warn' : 'bad')),
            $roa === null ? '-' : 'Setiap Rp100 aset menghasilkan laba Rp' . nf($roa * 100, 1) . ' per tahun.', 'Seberapa produktif aset digunakan untuk menghasilkan laba.');
        // bila ekuitas awal <= 0, rata-rata menyesatkan -> pakai ekuitas akhir
        $basisE = $P['_EKUITAS'] > 0 ? $avg('_EKUITAS') : $A['_EKUITAS'];
        $roe = $basisE > 0 ? $bagi($lb * $tahunan, $basisE) : null;
        $add('Profitabilitas (kemampuan menghasilkan laba)', 'Return on Equity (ROE, disetahunkan)', 'Laba Bersih (setahun) ÷ Rata-rata Ekuitas', $roe, '%', $roe === null ? '' : ($roe >= 0.1 ? 'ok' : ($roe > 0 ? 'warn' : 'bad')),
            $roe === null ? 'Tidak dapat dihitung (ekuitas rata-rata ≤ 0).' : 'Imbal hasil bagi pemilik ' . nf($roe * 100, 1) . '% per tahun.', 'Tingkat pengembalian atas modal pemilik.');

        // --- Aktivitas
        $dso = $pend > 0 ? $avg('PIUTANG') / $pend * $hari : null;
        $add('Aktivitas (efisiensi pengelolaan aset)', 'Umur Rata-rata Piutang (DSO)', 'Rata-rata Piutang ÷ Pendapatan × jumlah hari', $dso, 'hari', $st($dso, 45, 75, false),
            $dso === null ? '-' : 'Rata-rata pelanggan membayar dalam ' . nf($dso, 0) . ' hari. Bandingkan dengan termin kredit yang Anda berikan.', 'Kecepatan penagihan piutang.');
        $dio = $hpp > 0 ? $avg('PERSEDIAAN') / $hpp * $hari : null;
        $add('Aktivitas (efisiensi pengelolaan aset)', 'Umur Rata-rata Persediaan (DIO)', 'Rata-rata Persediaan ÷ HPP × jumlah hari', $dio, 'hari', $st($dio, 60, 120, false),
            $dio === null ? 'Tidak ada HPP pada periode ini.' : 'Persediaan rata-rata tersimpan ' . nf($dio, 0) . ' hari sebelum terpakai/terjual.', 'Kecepatan perputaran persediaan (spare part, material).');
        $dpo = $hpp > 0 ? $avg('HUTANG') / $hpp * $hari : null;
        $add('Aktivitas (efisiensi pengelolaan aset)', 'Umur Rata-rata Hutang (DPO)', 'Rata-rata Hutang Usaha ÷ HPP × jumlah hari', $dpo, 'hari', '',
            $dpo === null ? '-' : 'Rata-rata pemasok dibayar dalam ' . nf($dpo, 0) . ' hari.', 'Lama perusahaan menahan pembayaran ke pemasok.');
        $ccc = ($dso !== null && $dio !== null && $dpo !== null) ? $dso + $dio - $dpo : null;
        $add('Aktivitas (efisiensi pengelolaan aset)', 'Siklus Konversi Kas', 'DSO + DIO − DPO', $ccc, 'hari', $st($ccc, 60, 120, false),
            $ccc === null ? '-' : 'Uang tertahan di operasi selama ' . nf($ccc, 0) . ' hari dari membeli bahan sampai menerima kas.', 'Semakin pendek semakin sedikit modal kerja yang dibutuhkan.');
        $ato = $bagi($pend * $tahunan, $avg('_ASET'));
        $add('Aktivitas (efisiensi pengelolaan aset)', 'Perputaran Total Aset (disetahunkan)', 'Pendapatan (setahun) ÷ Rata-rata Total Aset', $ato, 'x', '',
            $ato === null ? '-' : 'Setiap Rp1 aset menghasilkan penjualan Rp' . nf($ato, 2) . ' per tahun.', 'Efektivitas aset dalam menghasilkan penjualan.');

        return array('grup' => $G, 'hari' => $hari, 'posisi' => $A, 'posisi_awal' => $P, 'pend' => $pend, 'hpp' => $hpp, 'laba_kotor' => $lk, 'laba_operasi' => $lo, 'laba_bersih' => $lb);
    }

    /* ==================================================================
     * PEMERIKSAAN DATA
     * ================================================================ */
    public function cek_data($tgl)
    {
        $tahun = (int) substr($tgl, 0, 4);
        $C = array();
        $add = function ($judul, $status, $pesan, $detail = array(), $link = '', $saran = '') use (&$C) {
            $C[] = (object) array('judul' => $judul, 'status' => $status, 'pesan' => $pesan, 'detail' => $detail, 'link' => $link, 'saran' => $saran);
        };

        // 1. jurnal tidak seimbang
        $q = $this->db->select('no_ref, MIN(tgl_jurnal) AS tgl, SUM(debit) AS d, SUM(kredit) AS k', false)->from('tbl_acc_jurnal_umum')
            ->where('status', 'Y')->group_by('no_ref')->having('ABS(SUM(debit) - SUM(kredit)) >', 0.009, false)->limit(50)->get()->result();
        $det = array(); foreach ($q as $r) $det[] = $r->no_ref . ' (' . tgl_singkat($r->tgl) . '): debit ' . nf($r->d, 2) . ' / kredit ' . nf($r->k, 2);
        $add('Keseimbangan setiap jurnal', $q ? 'bad' : 'ok', $q ? count($q) . ' jurnal tidak seimbang (debit ≠ kredit).' : 'Semua jurnal seimbang.', $det, 'LaporanKeuangan/daftar_jurnal',
            'Buka jurnal terkait lalu perbaiki nominalnya; laporan tidak akan seimbang selama ini belum diperbaiki.');

        // 2. akun di jurnal yang tidak terdaftar
        $akun = $this->akuntansi->akun();
        $kodeJ = $this->db->distinct()->select('kode_akun')->where('status', 'Y')->get('tbl_acc_jurnal_umum')->result();
        $det = array(); foreach ($kodeJ as $r) if ( ! isset($akun[(string) $r->kode_akun])) $det[] = (string) $r->kode_akun;
        $add('Akun pada jurnal terdaftar di Daftar Akun', $det ? 'warn' : 'ok', $det ? count($det) . ' kode akun dipakai di jurnal tetapi tidak ada di Daftar Akun.' : 'Semua akun pada jurnal terdaftar.',
            $det ? array('Kode: ' . implode(', ', array_slice($det, 0, 40))) : array(), 'Account', 'Tambahkan akun tersebut di Daftar Akun dengan tipe yang benar agar masuk ke laporan yang tepat.');

        // 3. akun tanpa tipe
        $det = array(); foreach ($akun as $a) if ($a->kategori === '' || ! isset(Akuntansi::$KATEGORI[$a->kategori])) $det[] = $a->kode_akun . ' ' . $a->nama_akun;
        $add('Setiap akun memiliki Tipe Akun', $det ? 'warn' : 'ok', $det ? count($det) . ' akun belum memiliki tipe.' : 'Semua akun sudah bertipe.', array_slice($det, 0, 40), 'Account',
            'Tipe akun menentukan posisi akun di Neraca/Laba Rugi. Edit akun dan pilih tipenya.');

        // 4. saldo awal seimbang
        $th = $this->akuntansi->tahun_saldo_awal($tgl);
        if ($th) {
            $r = $this->db->select('SUM(debit) AS d, SUM(kredit) AS k', false)->where('periode', $th)->get('tbl_acc_saldo_awal')->row();
            $sel = round((float) $r->d - (float) $r->k, 2);
            $add('Saldo Awal ' . $th . ' seimbang', abs($sel) < 0.01 ? 'ok' : 'bad',
                abs($sel) < 0.01 ? 'Total debit = total kredit (' . nf($r->d, 2) . ').' : 'Debit ' . nf($r->d, 2) . ' vs kredit ' . nf($r->k, 2) . ' — selisih ' . nf($sel, 2) . '.',
                array(), 'Saldo', 'Saldo awal yang tidak seimbang membuat Neraca selisih. Lengkapi saldo awal (mis. modal/laba ditahan) atau gunakan akun penampung "Ekuitas Saldo Awal" lalu telusuri.');
        } else {
            $add('Saldo Awal', 'warn', 'Belum ada saldo awal.', array(), 'Saldo', 'Isi saldo awal per 1 Januari tahun mulai memakai program.');
        }

        // 5. neraca seimbang
        $nr = $this->Mod_lapkeu->neraca(array($tgl));
        $sel = $nr['selisih'][0];
        $add('Neraca seimbang per ' . tgl_singkat($tgl), abs($sel) < 0.01 ? 'ok' : 'bad', abs($sel) < 0.01 ? 'Aset = Liabilitas + Ekuitas.' : 'Selisih ' . nf($sel, 2) . '.', array(), 'LaporanKeuangan/neraca',
            'Biasanya disebabkan saldo awal atau jurnal yang tidak seimbang (lihat pemeriksaan 1 & 4).');

        // 6-7. buku pembantu vs akun kontrol
        foreach (array('AR' => array('Piutang', 'Piutang'), 'AP' => array('Hutang', 'Hutang')) as $j => $lbl) {
            $k = $this->kartu_kontak($j, $tahun . '-01-01', $tgl);
            $sub = $k['total']->akhir; $kon = 0; foreach ($k['kontrol'] as $x) $kon += $x->saldo;
            $selisih = round($kon - $sub, 2);
            $add('Rekonsiliasi buku pembantu ' . strtolower($lbl[0]) . ' vs akun kontrol', abs($selisih) < 1 ? 'ok' : 'warn',
                'Sisa faktur ' . nf($sub, 2) . ' • saldo akun ' . nf($kon, 2) . (abs($selisih) < 1 ? '' : ' • selisih ' . nf($selisih, 2)),
                array_map(function ($x) { return $x->kode . ' ' . $x->nama . ': ' . nf($x->saldo, 2); }, $k['kontrol']), 'LaporanKeuangan/kartu_' . strtolower($lbl[1]),
                'Selisih wajar bila saldo awal ' . strtolower($lbl[0]) . ' diinput lewat Saldo Awal/Jurnal Umum (bukan faktur). Untuk kontrol penuh, catat ' . strtolower($lbl[0]) . ' lewat menu ' . $lbl[0] . ' Usaha.');
        }

        // 8. masa PPN belum ditutup
        $this->load->model('accounting/Mod_pajak');
        if ($this->Mod_pajak->profil()->pkp) {
            $ppn = $this->Mod_pajak->ppn_tahun($tahun);
            $det = array(); $bulanIni = (int) date('n'); $thIni = (int) date('Y');
            foreach ($ppn['rows'] as $b => $r) {
                if ($tahun > $thIni || ($tahun == $thIni && $b >= $bulanIni)) continue;
                if (($r->ppn_k != 0 || $r->ppn_m != 0) && $r->tutup === '') $det[] = bulan_panjang($b) . ' ' . $tahun . ': Keluaran ' . nf($r->ppn_k, 0) . ', Masukan ' . nf($r->ppn_m, 0);
            }
            $add('Masa PPN sudah ditutup', $det ? 'warn' : 'ok', $det ? count($det) . ' masa PPN ada transaksi tetapi belum ditutup.' : 'Semua masa PPN yang lewat sudah ditutup.', $det, 'Pajak?tahun=' . $tahun . '#tp-ppn',
                'Tutup masa PPN di menu Perpajakan → tab PPN agar Hutang PPN / lebih bayar tercatat dan bisa disetor.');
        }

        // 9. penyusutan
        if ($this->db->table_exists('tbl_acc_aset')) {
            $bulanLalu = date('Y-m', strtotime(date('Y-m-01') . ' -1 month'));
            $det = array();
            foreach ($this->db->where('status', 'AKTIF')->where('metode !=', 'TIDAK')->get('tbl_acc_aset')->result() as $a) {
                if (substr($a->tgl_mulai_susut, 0, 7) > $bulanLalu) continue;
                $r = $this->db->select_max('periode', 'p')->where('id_aset', $a->id)->get('tbl_acc_aset_susut')->row();
                $akm = (float) $this->db->select_sum('jumlah', 'j')->where('id_aset', $a->id)->get('tbl_acc_aset_susut')->row()->j;
                if ((float) $a->harga_perolehan - (float) $a->nilai_residu - $akm <= 0.01) continue;
                if ( ! $r || ! $r->p || $r->p < $bulanLalu) $det[] = $a->kode_aset . ' ' . $a->nama_aset . ' (terakhir: ' . ($r && $r->p ? $r->p : 'belum pernah') . ')';
            }
            $add('Penyusutan aset sampai bulan lalu', $det ? 'warn' : 'ok', $det ? count($det) . ' aset belum disusutkan sampai ' . $bulanLalu . '.' : 'Penyusutan sudah up to date.', $det, 'AsetTetap',
                'Jalankan "Proses Penyusutan" di menu Aset Tetap sampai periode bulan lalu.');
        }

        // 10. kas negatif
        $det = array();
        $kas = array_map('strval', array_keys($this->akuntansi->akun_kategori('KAS_BANK')));
        if ($kas) {
            $s = $this->akuntansi->saldo_per($tgl, $kas);
            foreach ($kas as $k) { $v = isset($s[$k]) ? $s[$k]['d'] - $s[$k]['k'] : 0; if ($v < -0.005) $det[] = $k . ' ' . $this->akuntansi->akun($k)->nama_akun . ': ' . nf($v, 2); }
        }
        $add('Saldo kas & bank tidak minus', $det ? 'bad' : 'ok', $det ? count($det) . ' akun kas/bank bersaldo minus.' : 'Tidak ada kas/bank minus.', $det, 'LaporanKeuangan/mutasi_kas',
            'Kas tidak mungkin minus: kemungkinan ada penerimaan yang belum dicatat atau pengeluaran tercatat dua kali.');

        // 11. periode belum dikunci
        if ($this->db->table_exists('tbl_acc_periode')) {
            $kunci = array();
            foreach ($this->db->get('tbl_acc_periode')->result() as $p) if (isset($p->status) && $p->status === 'TUTUP') $kunci[$p->periode] = true;
            $det = array();
            $p = sprintf('%04d-01', $tahun); $batas = date('Y-m', strtotime(date('Y-m-01') . ' -2 month'));
            while ($p <= $batas && $p <= sprintf('%04d-12', $tahun)) { if ( ! isset($kunci[$p])) $det[] = $p; $p = date('Y-m', strtotime($p . '-01 +1 month')); }
            $add('Periode lama sudah dikunci', $det ? 'info' : 'ok', $det ? count($det) . ' periode yang sudah lewat belum dikunci.' : 'Periode lama sudah dikunci.', $det ? array(implode(', ', $det)) : array(), 'PeriodeAkuntansi',
                'Kunci periode setelah laporan bulanan final agar data tidak berubah tanpa sengaja.');
        }

        $skor = array('ok' => 0, 'info' => 0, 'warn' => 0, 'bad' => 0);
        foreach ($C as $c) $skor[$c->status]++;
        return array('cek' => $C, 'skor' => $skor);
    }

    /* ==================================================================
     * CATATAN ATAS LAPORAN KEUANGAN (otomatis)
     * ================================================================ */
    public function calk($dari, $sampai)
    {
        $this->load->model('accounting/Mod_pajak');
        $prof = $this->Mod_pajak->profil();
        $s1 = $this->akuntansi->saldo_per($sampai);
        $s0 = $this->akuntansi->saldo_per(Akuntansi::kemarin($dari));
        $mut = $this->akuntansi->mutasi($dari, $sampai, null, array('TTP'));
        $nilai = function ($s, $kode, $kat) {
            if ( ! isset($s[$kode])) return 0.0;
            $g = Akuntansi::grup_kategori($kat);
            return ($g === 'ASET' || $g === 'BEBAN') ? $s[$kode]['d'] - $s[$kode]['k'] : $s[$kode]['k'] - $s[$kode]['d'];
        };
        $akun = $this->akuntansi->akun();
        $catatan = array(); $no = 3;
        foreach (Akuntansi::$KATEGORI as $kat => $info) {
            $rows = array(); $t1 = 0; $t0 = 0;
            $isLR = in_array(Akuntansi::grup_kategori($kat), array('PENDAPATAN', 'BEBAN'), true);
            foreach ($akun as $kode => $a) {
                if ($a->kategori !== $kat) continue;
                if ($isLR) {
                    $v1 = isset($mut[$kode]) ? (Akuntansi::grup_kategori($kat) === 'BEBAN' ? $mut[$kode]['d'] - $mut[$kode]['k'] : $mut[$kode]['k'] - $mut[$kode]['d']) : 0; $v0 = null;
                } else { $v1 = $nilai($s1, $kode, $kat); $v0 = $nilai($s0, $kode, $kat); }
                if (abs($v1) < 0.005 && ($v0 === null || abs($v0) < 0.005)) continue;
                $rows[] = (object) array('kode' => $kode, 'nama' => $a->nama_akun, 'v1' => $v1, 'v0' => $v0);
                $t1 += $v1; $t0 += (float) $v0;
            }
            if ( ! $rows) continue;
            $catatan[] = (object) array('no' => $no++, 'kat' => $kat, 'judul' => $info[0], 'lr' => $isLR, 'rows' => $rows, 't1' => $t1, 't0' => $t0, 'tambahan' => $this->calk_tambahan($kat, $sampai));
        }
        $metode = array();
        if ($this->db->table_exists('tbl_acc_aset')) foreach ($this->db->distinct()->select('metode')->get('tbl_acc_aset')->result() as $m) $metode[] = $m->metode;
        return array('profil' => $prof, 'catatan' => $catatan, 'metode_susut' => $metode, 'dari' => $dari, 'sampai' => $sampai);
    }

    private function calk_tambahan($kat, $tgl)
    {
        if ($kat === 'PIUTANG' || $kat === 'HUTANG') {
            $u = $this->Mod_lapkeu->umur($kat === 'PIUTANG' ? 'AR' : 'AP', $tgl);
            if ( ! $u) return null;
            $t = array('b0' => 0, 'b1' => 0, 'b2' => 0, 'b3' => 0, 'b4' => 0);
            foreach ($u as $k) foreach ($t as $b => $v) $t[$b] += $k->$b;
            usort($u, function ($a, $b) { return $b->total <=> $a->total; });
            return array('jenis' => 'umur', 'umur' => $t, 'top' => array_slice($u, 0, 5));
        }
        if ($kat === 'ASET_TETAP' && $this->db->table_exists('tbl_acc_aset')) {
            $g = array();
            foreach ($this->Mod_lapkeu->daftar_aset($tgl) as $a) {
                if ($a->status !== 'AKTIF') continue;
                $k = $a->kelompok_aset ?: 'Lainnya';
                if ( ! isset($g[$k])) $g[$k] = (object) array('kelompok' => $k, 'n' => 0, 'perolehan' => 0, 'akumulasi' => 0, 'nb' => 0);
                $g[$k]->n++; $g[$k]->perolehan += $a->harga_perolehan; $g[$k]->akumulasi += $a->akumulasi; $g[$k]->nb += $a->nilai_buku;
            }
            return $g ? array('jenis' => 'aset', 'kelompok' => array_values($g)) : null;
        }
        return null;
    }
}
