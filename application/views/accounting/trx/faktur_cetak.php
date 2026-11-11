<?php defined('BASEPATH') OR exit('No direct script access allowed');
$nm = function ($k) use ($akun) { return isset($akun[$k]) ? $akun[$k]->nama_akun : $k; };
$judul = $f->jenis === 'AR' ? 'FAKTUR PENJUALAN' : 'FAKTUR PEMBELIAN';
?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title><?php echo html_escape($f->no_faktur); ?></title>
<style>
body{font-family:'Segoe UI',Arial,sans-serif;font-size:12px;color:#111;margin:24px}
.top{display:flex;justify-content:space-between;border-bottom:2px solid #111;padding-bottom:8px}.co b{font-size:15px}.co div{color:#555}
h2{margin:0;font-size:18px;letter-spacing:.06em;text-align:right}
.meta{display:flex;justify-content:space-between;margin:14px 0}.meta .box{width:48%}.meta span{display:inline-block;width:110px;color:#555}
table{width:100%;border-collapse:collapse}th,td{border:1px solid #bbb;padding:6px}th{background:#f1f3f6;text-align:left}.num{text-align:right;white-space:nowrap}
.tot td{border:0;padding:3px 6px}.tot .g td{font-weight:800;border-top:2px solid #111}
.terbilang{margin-top:8px;font-style:italic}.ttd{display:flex;justify-content:space-between;margin-top:40px}.ttd div{width:30%;text-align:center}.ttd .g{margin-top:60px;border-top:1px solid #333;padding-top:3px}
.batal{color:#b00;font-weight:800;font-size:22px;text-align:center;border:3px solid #b00;margin:10px 0}
@media print{body{margin:0}@page{margin:12mm}}
</style></head><body>
<div class="top"><div class="co"><b><?php echo html_escape($apl ? $apl->nama_owner : ''); ?></b><div><?php echo html_escape($apl ? $apl->alamat : ''); ?></div><div><?php echo html_escape($apl ? $apl->kota . ' ' . $apl->kode_pos . ' · Telp ' . $apl->tlp : ''); ?></div></div>
<div><h2><?php echo $judul; ?></h2><div style="text-align:right">No. <?php echo html_escape($f->no_faktur); ?></div></div></div>
<?php if ($f->status === 'BATAL') { ?><div class="batal">DIBATALKAN</div><?php } ?>
<div class="meta">
    <div class="box"><div><span><?php echo $f->jenis === 'AR' ? 'Kepada' : 'Pemasok'; ?></span>: <b><?php echo html_escape($f->kontak); ?></b></div><div><span>No. referensi</span>: <?php echo html_escape($f->no_referensi); ?></div><div><span>Keterangan</span>: <?php echo html_escape($f->keterangan); ?></div></div>
    <div class="box"><div><span>Tanggal</span>: <?php echo tgl_id($f->tanggal); ?></div><div><span>Jatuh tempo</span>: <?php echo tgl_id($f->jatuh_tempo); ?></div><div><span>Status</span>: <?php echo $f->status; ?></div></div>
</div>
<table><thead><tr><th style="width:36px">No</th><th>Uraian</th><th class="num" style="width:70px">Qty</th><th class="num" style="width:130px">Harga</th><th class="num" style="width:140px">Jumlah</th></tr></thead><tbody>
<?php $i = 0; foreach ($f->detail as $d) { $i++; ?><tr><td><?php echo $i; ?></td><td><?php echo html_escape($d->keterangan ?: $nm($d->kode_akun)); ?></td><td class="num"><?php echo nf($d->qty, 2); ?></td><td class="num"><?php echo nf($d->harga, 2); ?></td><td class="num"><?php echo nf($d->jumlah, 2); ?></td></tr><?php } ?>
</tbody></table>
<table class="tot" style="width:45%;margin-left:55%;margin-top:6px">
    <tr><td>Subtotal</td><td class="num"><?php echo nf($f->subtotal, 2); ?></td></tr>
    <?php if ($f->diskon > 0) { ?><tr><td>Diskon</td><td class="num">(<?php echo nf($f->diskon, 2); ?>)</td></tr><?php } ?>
    <?php if ($f->ppn > 0) { ?><tr><td>PPN <?php echo nf($f->ppn_persen, 2); ?>%</td><td class="num"><?php echo nf($f->ppn, 2); ?></td></tr><?php } ?>
    <?php if ($f->ppn > 0 && ! empty($f->no_faktur_pajak)) { ?><tr><td>No. Faktur Pajak</td><td class="num"><?php echo html_escape($f->no_faktur_pajak); ?></td></tr><?php } ?>
    <tr class="g"><td>TOTAL</td><td class="num"><?php echo nf($f->total, 2); ?></td></tr>
    <?php if ($f->terbayar > 0) { ?><tr><td>Terbayar</td><td class="num"><?php echo nf($f->terbayar, 2); ?></td></tr><tr><td><b>Sisa</b></td><td class="num"><b><?php echo nf($f->total - $f->terbayar, 2); ?></b></td></tr><?php } ?>
</table>
<div class="terbilang">Terbilang: <?php echo ucwords(terbilang((int) round($f->total))); ?> Rupiah</div>
<div class="ttd"><div>Hormat kami<div class="g"><?php echo html_escape($apl ? $apl->nama_owner : ''); ?></div></div><div></div><div>Penerima<div class="g">&nbsp;</div></div></div>
<script>window.onload=function(){window.print();}</script>
</body></html>
