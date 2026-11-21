<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Cetak Part Order service per customer (A4 portrait, satu halaman baru per customer). */
$e = function ($s) { return html_escape(trim((string) $s)); };
$tg = function ($t) { return Mod_lapservice::tgl_ok($t) ? date('d/m/Y', strtotime($t)) : ''; };
$rp = function ($n) { return 'Rp ' . number_format((float) $n, 0, ',', '.'); };
$base = rtrim(base_url(), '/') . '/';
$periode = $tg($f['dari']) . ' s/d ' . $tg($f['sampai']);
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Part Order per Customer <?php echo $periode; ?></title>
<style>
@page { size: A4 portrait; margin: 10mm 11mm 12mm; }
* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; }
body { font-family: Arial, Helvetica, sans-serif; font-size: 10.5px; color: #000; line-height: 1.3; }
.hal { width: 188mm; margin: 0 auto; }
.kop { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 6px; }
.kop .logo { text-align: center; } .kop .logo img { height: 15mm; }
.kop .pt { text-align: right; font-size: 10px; } .kop .pt b { font-size: 12px; }
.kop .judul b { font-size: 15px; letter-spacing: .03em; display: block; } .kop .judul span { font-size: 10px; }
table { width: 100%; border-collapse: collapse; }
.g td, .g th { border: 1px solid #000; padding: 3px 5px; vertical-align: top; }
.g th { background: #e9eef3; font-weight: 700; text-align: left; font-size: 9.5px; }
.kv td { padding: 1.5px 3px; vertical-align: top; } .kv td:first-child { width: 22%; color: #333; }
.c { text-align: center; } .r { text-align: right; white-space: nowrap; } .b { font-weight: 700; }
.wo td { background: #f6f8fa; font-weight: 700; }
.sub td { font-weight: 700; } .total td { font-weight: 700; background: #e9eef3; }
.ket { font-size: 9px; }
.ttd { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-top: 12px; text-align: center; page-break-inside: avoid; }
.ttd span { display: block; margin-top: 42px; border-top: 1px solid #000; padding-top: 2px; }
.foot { margin-top: 8px; } .foot img { width: 100%; display: block; }
.pecah { page-break-before: always; }
tr { page-break-inside: avoid; }
@media screen { body { background: #e9edf2; padding: 62px 0 20px; } .hal { background: #fff; padding: 10mm 11mm; box-shadow: 0 2px 12px rgba(0,0,0,.15); width: 210mm; margin-bottom: 14px; }
  .bar { position: fixed; top: 0; left: 0; right: 0; background: #0f172a; color: #fff; padding: 10px 16px; display: flex; gap: 10px; align-items: center; font: 13px "Segoe UI", Arial, sans-serif; z-index: 9; }
  .bar button { background: #0e7c86; color: #fff; border: 0; padding: 7px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 13px; }
  .bar span { opacity: .8; margin-left: auto; } }
@media print { .bar { display: none; } }
</style></head>
<body>
<div class="bar"><button onclick="window.print()">Cetak</button><span><?php echo count($grup); ?> customer · <?php echo $periode; ?> · A4 · skala 100%</span></div>
<?php if ( ! $grup) { ?>
<div class="hal"><p style="font-size:13px;text-align:center;padding:30px 0">Tidak ada part order service pada filter ini.<br><small>Part order diambil dari item Estimasi Penawaran Service yang divalidasi sebagai <b>Part order</b> dan sudah disetujui.</small></p></div>
<?php } $n = 0; foreach ($grup as $g) { $c = $g->cus; $grand = 0; $item = 0; ?>
<div class="hal <?php echo $n++ ? 'pecah' : ''; ?>">
    <div class="kop">
        <div class="judul"><b>PART ORDER</b><span>Periode <?php echo $periode; ?></span></div>
        <div class="logo"><img src="<?php echo $base; ?>assets/dist/img/logo_mercedes.png" alt="" onerror="this.style.display='none'"></div>
        <div class="pt"><b><?php echo $e($apl ? $apl->nama_owner : ''); ?></b><br><?php echo $e($apl ? $apl->status : ''); ?><br><?php echo $e($apl ? trim($apl->alamat . ', ' . $apl->kota) : ''); ?><?php echo $apl && $apl->tlp ? '<br>Telp. ' . $e($apl->tlp) : ''; ?></div>
    </div>
    <table class="kv">
        <tr><td>Customer</td><td class="b">: <?php echo $e($c ? $c->nama_cus : $g->nama); ?> <span style="font-weight:400">(<?php echo $e($g->kode); ?>)</span></td></tr>
        <tr><td>Address</td><td>: <?php echo $e($c ? trim($c->alamat . ($c->kota ? ', ' . $c->kota : '')) : ''); ?></td></tr>
        <tr><td>Up / telp</td><td>: <?php echo $e($c ? trim($c->tlp_person . ($c->no_tlp ? ' · ' . $c->no_tlp : '')) : ''); ?></td></tr>
        <tr><td>Jumlah WO</td><td>: <?php echo count($g->wo); ?></td></tr>
    </table>
    <table class="g" style="margin-top:6px">
        <tr><th class="c" style="width:5%">No</th><th style="width:17%">Part number</th><th>Description</th><th class="c" style="width:7%">Unit</th><th class="c" style="width:6%">Qty</th><th class="r" style="width:13%">Price</th><th class="r" style="width:14%">Amount</th><th style="width:12%">Remarks</th></tr>
        <?php foreach ($g->wo as $r) { $sub = 0; ?>
        <tr class="wo"><td colspan="8">WO <?php echo $e($r->wo_no); ?> · <?php echo $tg($r->date_open_wo); ?> · <?php echo $e($r->no_pol); ?> <?php echo $e($r->type); ?> · VIN <?php echo $e($r->vin); ?> · SA <?php echo $e($r->sa_name); ?></td></tr>
        <?php $i = 0; foreach ($r->part_order as $d) { $h = (float) (($d->harga_net ?? 0) ?: ($d->harga ?? 0)); $amt = $h * (float) $d->jumlah; $sub += $amt; $item++; ?>
        <tr><td class="c"><?php echo ++$i; ?></td><td><?php echo $e($d->no_part); ?></td><td><?php echo $e($d->nama_part); ?></td><td class="c"><?php echo $e($d->satuan ?? ''); ?></td><td class="c"><?php echo $e($d->jumlah); ?></td><td class="r"><?php echo $rp($h); ?></td><td class="r"><?php echo $rp($amt); ?></td><td><?php echo $e($d->remark ?? ''); ?></td></tr>
        <?php } $grand += $sub; ?>
        <tr class="sub"><td colspan="6" class="r">Subtotal WO <?php echo $e($r->wo_no); ?></td><td class="r"><?php echo $rp($sub); ?></td><td></td></tr>
        <?php } ?>
        <tr class="total"><td colspan="6" class="r">TOTAL PART ORDER (<?php echo $item; ?> item)</td><td class="r"><?php echo $rp($grand); ?></td><td></td></tr>
    </table>
    <p class="ket">Harga sebelum pajak. Dicetak <?php echo date('d/m/Y H:i'); ?> oleh <?php echo $e($oleh); ?>.</p>
    <div class="ttd"><div>Service Advisor<span>( ........................ )</span></div><div>Partman / Gudang<span>( ........................ )</span></div><div>Customer<span><?php echo $e($c ? $c->nama_cus : $g->nama); ?></span></div></div>
    <?php if ($apl && trim((string) $apl->footer) !== '') { ?><div class="foot"><img src="<?php echo $base; ?>assets/foto/logo/<?php echo rawurlencode($apl->footer); ?>" alt="" onerror="this.parentNode.style.display='none'"></div><?php } ?>
</div>
<?php } ?>
<script>
(function () {
    var imgs = Array.prototype.slice.call(document.images);
    Promise.all(imgs.map(function (i) { return i.complete ? 0 : new Promise(function (ok) { i.addEventListener('load', ok); i.addEventListener('error', ok); }); })).then(function () { setTimeout(function () { window.print(); }, 250); });
})();
</script>
</body></html>
