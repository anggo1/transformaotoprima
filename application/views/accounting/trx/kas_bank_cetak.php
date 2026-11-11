<?php defined('BASEPATH') OR exit('No direct script access allowed');
$judul = array('MASUK' => 'BUKTI KAS / BANK MASUK', 'KELUAR' => 'BUKTI KAS / BANK KELUAR', 'TRANSFER' => 'BUKTI TRANSFER KAS / BANK');
$nm = function ($k) use ($akun) { return isset($akun[$k]) ? $akun[$k]->nama_akun : $k; };
?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title><?php echo html_escape($h->no_transaksi); ?></title>
<style>
body{font-family:'Segoe UI',Arial,sans-serif;font-size:12px;color:#111;margin:24px}
.top{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #111;padding-bottom:8px}
.co b{font-size:15px}.co div{color:#555}
h2{margin:0;font-size:16px;letter-spacing:.05em;text-align:right}
.meta{display:grid;grid-template-columns:1fr 1fr;gap:4px 24px;margin:12px 0}
.meta div span{display:inline-block;width:110px;color:#555}
table{width:100%;border-collapse:collapse;margin-top:6px}th,td{border:1px solid #bbb;padding:6px}th{background:#f1f3f6;text-align:left}
.num{text-align:right;white-space:nowrap}.terbilang{margin-top:8px;padding:8px;border:1px dashed #999;font-style:italic}
.ttd{display:flex;justify-content:space-between;margin-top:36px}.ttd div{width:23%;text-align:center}.ttd .g{margin-top:56px;border-top:1px solid #333;padding-top:3px}
.batal{color:#b00;font-weight:800;font-size:22px;text-align:center;border:3px solid #b00;margin:10px 0;padding:4px}
@media print{body{margin:0}@page{margin:12mm}}
</style></head><body>
<div class="top"><div class="co"><b><?php echo html_escape($apl ? $apl->nama_owner : ''); ?></b><div><?php echo html_escape($apl ? $apl->alamat . ', ' . $apl->kota : ''); ?></div></div>
<div><h2><?php echo $judul[$h->jenis]; ?></h2><div style="text-align:right">No. <?php echo html_escape($h->no_transaksi); ?></div></div></div>
<?php if ($h->status !== 'Y') { ?><div class="batal">DIBATALKAN</div><?php } ?>
<div class="meta">
    <div><span>Tanggal</span>: <?php echo tgl_id($h->tanggal); ?></div>
    <div><span><?php echo $h->jenis === 'TRANSFER' ? 'Dari akun' : 'Akun kas/bank'; ?></span>: <?php echo html_escape($h->kode_akun_kas . ' - ' . $nm($h->kode_akun_kas)); ?></div>
    <div><span><?php echo $h->jenis === 'MASUK' ? 'Diterima dari' : ($h->jenis === 'KELUAR' ? 'Dibayar kepada' : 'Ke akun'); ?></span>: <?php echo html_escape($h->jenis === 'TRANSFER' ? $h->kode_akun_tujuan . ' - ' . $nm($h->kode_akun_tujuan) : $h->kontak); ?></div>
    <div><span>No. bukti/cek</span>: <?php echo html_escape($h->no_bukti); ?></div>
    <div style="grid-column:1/3"><span>Keterangan</span>: <?php echo html_escape($h->keterangan); ?></div>
</div>
<table><thead><tr><th style="width:40px">No</th><th>Akun</th><th>Keterangan</th><th class="num" style="width:150px">Jumlah</th></tr></thead><tbody>
<?php $i = 0; foreach ($h->detail as $d) { $i++; ?><tr><td><?php echo $i; ?></td><td><?php echo html_escape($d->kode_akun . ' - ' . $nm($d->kode_akun)); ?></td><td><?php echo html_escape($d->keterangan); ?></td><td class="num"><?php echo nf($d->jumlah, 2); ?></td></tr><?php } ?>
</tbody><tfoot><tr><th colspan="3" class="num">TOTAL</th><th class="num"><?php echo nf($h->total, 2); ?></th></tr></tfoot></table>
<div class="terbilang">Terbilang: <?php echo ucwords(terbilang((int) round($h->total))); ?> Rupiah</div>
<div class="ttd"><div>Dibuat<div class="g"><?php echo html_escape($h->user); ?></div></div><div>Diperiksa<div class="g">&nbsp;</div></div><div>Disetujui<div class="g">&nbsp;</div></div><div><?php echo $h->jenis === 'MASUK' ? 'Penyetor' : 'Penerima'; ?><div class="g">&nbsp;</div></div></div>
<script>window.onload=function(){window.print();}</script>
</body></html>
