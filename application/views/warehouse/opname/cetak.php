<?php defined('BASEPATH') OR exit('No direct script access allowed');
$L = Mod_lapgudang::$LOKASI; $R = $h->ringkas;
$buta = $h->buta === 'Y' && $h->status === 'HITUNG';
$judul = $jenis === 'hasil' ? 'Berita Acara Stock Opname' : 'Lembar Hitung Stock Opname';
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title><?php echo $judul . ' ' . html_escape($h->no_so); ?></title>
<style>
@page { size: A4; margin: 12mm 10mm; }
body { font-family: "Segoe UI", Arial, sans-serif; font-size: 11px; color: #111; margin: 0; }
.kop { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #111; padding-bottom: 6px; margin-bottom: 8px; }
.kop h2 { margin: 0; font-size: 16px; } .kop .meta { text-align: right; font-size: 10.5px; line-height: 1.5; }
table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #999; padding: 3px 5px; vertical-align: middle; }
th { background: #eef3f4; font-size: 10px; text-transform: uppercase; letter-spacing: .02em; } .num { text-align: right; white-space: nowrap; }
tr { page-break-inside: avoid; } thead { display: table-header-group; }
.qr { width: 15mm; height: 15mm; } .kotak { height: 15mm; min-width: 20mm; }
.ring { display: flex; gap: 8px; margin: 6px 0 10px; } .ring div { flex: 1; border: 1px solid #ccc; border-radius: 6px; padding: 6px 8px; } .ring b { display: block; font-size: 14px; }
.ttd { display: flex; justify-content: space-between; margin-top: 22px; gap: 20px; page-break-inside: avoid; } .ttd div { flex: 1; text-align: center; } .ttd span { display: block; margin-top: 52px; border-top: 1px solid #111; padding-top: 2px; }
.plus { color: #12805c; } .minus { color: #c0392b; } .ket { font-size: 10px; color: #555; margin-top: 6px; }
@media screen { body { max-width: 210mm; margin: 12px auto; padding: 0 10px; } .tombol { position: fixed; right: 16px; bottom: 16px; } }
@media print { .tombol { display: none; } }
</style></head>
<body>
<div class="tombol"><button onclick="window.print()">Cetak</button></div>
<div class="kop">
    <div><h2><?php echo $judul; ?></h2><div><?php echo html_escape($apl ? $apl->nama_owner : ''); ?> — Gudang <?php echo $L[$h->lokasi]; ?></div></div>
    <div class="meta">No. <b><?php echo html_escape($h->no_so); ?></b><br>Tanggal <?php echo tgl_id($h->tgl); ?><br><?php echo html_escape(Mod_opname::$CAKUPAN[$h->cakupan]); ?><?php echo $h->filter !== '' && $h->cakupan !== 'PILIH' ? ' : ' . html_escape($h->filter) : ''; ?><br>Snapshot <?php echo $h->mulai ? date('d/m/Y H:i', strtotime($h->mulai)) : '-'; ?></div>
</div>

<?php if ($jenis === 'lembar') { ?>
<table>
    <thead><tr><th style="width:18mm">QR</th><th>Rak</th><th>No. part</th><th>Nama barang</th><th>Sat.</th><?php if ( ! $buta) { ?><th class="num">Sistem</th><?php } ?><th>Hitung 1</th><th>Hitung 2</th><th>Paraf</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $d) { ?>
        <tr><td><div class="qr" data-qr="<?php echo html_escape($d->no_part); ?>"></div></td><td><?php echo html_escape($d->rak); ?></td><td><b><?php echo html_escape($d->no_part); ?></b></td><td><?php echo html_escape($d->nama_part); ?></td><td><?php echo html_escape($d->satuan); ?></td>
            <?php if ( ! $buta) { ?><td class="num"><?php echo nf_qty($d->stok_snapshot); ?></td><?php } ?>
            <td class="kotak"><?php echo $d->hitung1 === null ? '' : nf_qty($d->hitung1); ?></td><td class="kotak"><?php echo $d->hitung2 === null ? '' : nf_qty($d->hitung2); ?></td><td style="min-width:14mm"></td></tr>
    <?php } ?>
    </tbody>
</table>
<div class="ket">Scan QR pada kolom pertama dengan menu <b>Stock Opname → Scan</b> di aplikasi, atau tulis jumlah fisik lalu input/impor. <?php echo $buta ? 'Mode hitung buta: stok sistem tidak dicetak.' : ''; ?></div>
<div class="ttd"><div>Penghitung<span>( ............................ )</span></div><div>Pemeriksa<span>( ............................ )</span></div><div>Kepala Gudang<span>( ............................ )</span></div></div>
<?php } else { ?>
<div class="ring">
    <div>Barang dalam cakupan<b><?php echo nf($R->item); ?></b></div>
    <div>Dihitung<b><?php echo nf($R->dihitung); ?></b></div>
    <div>Cocok<b><?php echo nf($R->cocok); ?></b></div>
    <div>Selisih<b><?php echo nf($R->selisih); ?></b></div>
    <div>Selisih lebih<b class="plus"><?php echo nf_acc($R->lebih); ?></b></div>
    <div>Selisih kurang<b class="minus"><?php echo nf_acc($R->kurang); ?></b></div>
</div>
<p>Pada tanggal <?php echo tgl_id($h->tgl); ?> telah dilakukan penghitungan fisik persediaan di Gudang <?php echo $L[$h->lokasi]; ?> dengan hasil sebagai berikut (hanya barang yang berselisih):</p>
<table>
    <thead><tr><th>No.</th><th>No. part</th><th>Nama barang</th><th>Rak</th><th class="num">Sistem</th><th class="num">Hitung 1</th><th class="num">Hitung 2</th><th class="num">Fisik</th><th class="num">Selisih</th><th class="num">Harga</th><th class="num">Nilai selisih</th></tr></thead>
    <tbody>
    <?php $i = 0; $tot = 0; foreach ($rows as $d) { $s = $d->fisik - $d->stok_snapshot; $tot += $s * $d->harga; ?>
        <tr><td><?php echo ++$i; ?></td><td><?php echo html_escape($d->no_part); ?></td><td><?php echo html_escape($d->nama_part); ?></td><td><?php echo html_escape($d->rak); ?></td>
            <td class="num"><?php echo nf_qty($d->stok_snapshot); ?></td><td class="num"><?php echo $d->hitung1 === null ? '-' : nf_qty($d->hitung1); ?></td><td class="num"><?php echo $d->hitung2 === null ? '-' : nf_qty($d->hitung2); ?></td>
            <td class="num"><b><?php echo nf_qty($d->fisik); ?></b></td><td class="num <?php echo $s > 0 ? 'plus' : 'minus'; ?>"><?php echo ($s > 0 ? '+' : '') . nf_qty($s); ?></td><td class="num"><?php echo nf_acc($d->harga); ?></td><td class="num <?php echo $s > 0 ? 'plus' : 'minus'; ?>"><?php echo nf_acc($s * $d->harga); ?></td></tr>
    <?php } if ( ! $rows) { ?><tr><td colspan="11" style="text-align:center;padding:10px">Tidak ada selisih — seluruh barang yang dihitung sesuai dengan stok sistem.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr><th colspan="10" class="num">Total nilai selisih (neto)</th><th class="num"><?php echo nf_acc($tot); ?></th></tr></tfoot>
</table>
<div class="ket">Akurasi persediaan: <?php echo $R->dihitung ? round($R->cocok / $R->dihitung * 100, 1) : 0; ?>% barang cocok. <?php echo $R->item > $R->dihitung ? nf($R->item - $R->dihitung) . ' barang belum dihitung.' : ''; ?>
    Status: <?php echo Mod_opname::$STATUS[$h->status]; ?><?php echo $h->disetujui_oleh ? ' oleh ' . html_escape($h->disetujui_oleh) . ' (' . date('d/m/Y H:i', strtotime($h->tgl_posting)) . ')' : ''; ?><?php echo $h->no_ref_jurnal ? ', jurnal ' . html_escape($h->no_ref_jurnal) : ''; ?>.</div>
<div class="ttd"><div>Dibuat oleh<span><?php echo html_escape($h->dibuat_oleh); ?></span></div><div>Diperiksa (Akuntansi)<span>( ............................ )</span></div><div>Disetujui<span><?php echo $h->disetujui_oleh ? html_escape($h->disetujui_oleh) : '( ............................ )'; ?></span></div></div>
<?php } ?>
<script src="<?php echo base_url('assets/js/top-qr.js?v=3.0.0'); ?>"></script>
<script>window.addEventListener('load', function () { if (window.TOPQR) TOPQR.render(); setTimeout(function () { window.print(); }, 300); });</script>
</body></html>
