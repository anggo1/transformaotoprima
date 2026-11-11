<?php defined('BASEPATH') OR exit('No direct script access allowed');
$w = $T[1]; $h = $T[2]; $kol = $T[3]; $bar = $T[4]; $a4 = $kol > 0;
$qr = min($h - 4, $w * 0.45);                 // ukuran QR (mm)
$fs = $h <= 22 ? 6.5 : ($h <= 31 ? 7.5 : 9);    // ukuran huruf (pt)
$o = array_flip($opsi); $L = Mod_lapgudang::$LOKASI;
$semua = array(); foreach ($label as $x) for ($i = 0; $i < $x[1]; $i++) $semua[] = $x[0];
if ($a4) for ($i = 1; $i < $mulai; $i++) array_unshift($semua, null);
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Label QR (<?php echo count($semua); ?>)</title>
<style>
@page { size: <?php echo $T[5]; ?>; margin: <?php echo $a4 ? '10.7mm 4.5mm' : '0'; ?>; }
* { box-sizing: border-box; }
body { margin: 0; font-family: Arial, Helvetica, sans-serif; color: #000; }
.hal { <?php echo $a4 ? 'display:grid;grid-template-columns:repeat(' . $kol . ',' . $w . 'mm);grid-auto-rows:' . $h . 'mm;width:' . ($kol * $w) . 'mm;' : ''; ?> }
.lbl { width: <?php echo $w; ?>mm; height: <?php echo $h; ?>mm; padding: 1.5mm 2mm; display: flex; gap: 1.6mm; align-items: center; overflow: hidden; <?php echo $a4 ? '' : 'page-break-after: always;'; ?> }
.lbl:last-child { page-break-after: auto; }
.qr { flex: 0 0 <?php echo $qr; ?>mm; width: <?php echo $qr; ?>mm; height: <?php echo $qr; ?>mm; }
.qr svg { display: block; width: 100%; height: 100%; }
.tx { flex: 1; min-width: 0; font-size: <?php echo $fs; ?>pt; line-height: 1.15; }
.no { font-weight: 700; font-size: <?php echo $fs + 1.5; ?>pt; overflow-wrap: anywhere; }
.nm { max-height: 2.35em; overflow: hidden; margin-top: .4mm; }
.kc { font-size: <?php echo $fs - 1; ?>pt; margin-top: .6mm; }
.rak { display: inline-block; border: .25mm solid #000; padding: 0 1mm; font-weight: 700; border-radius: .6mm; }
.hg { font-weight: 700; }
@media screen {
  body { background: #e9edf2; padding: 70px 16px 16px; }
  .hal { background: #fff; margin: 0 auto 16px; box-shadow: 0 2px 10px rgba(0,0,0,.12); <?php echo $a4 ? 'padding:10.7mm 4.5mm;width:' . ($kol * $w + 9) . 'mm;min-height:297mm;' : 'width:max-content;display:flex;flex-direction:column;gap:6px;background:transparent;box-shadow:none;'; ?> }
  .lbl { background: #fff; outline: 1px dashed #b7c0cc; }
  .bar { position: fixed; top: 0; left: 0; right: 0; background: #0f172a; color: #fff; padding: 10px 16px; display: flex; gap: 12px; align-items: center; font: 13px Segoe UI, Arial, sans-serif; z-index: 9; }
  .bar button { background: #0e7c86; color: #fff; border: 0; padding: 7px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; }
  .bar span { opacity: .8; }
}
@media print { .bar { display: none; } .hal { break-after: page; } .hal:last-child { break-after: auto; } }
</style></head>
<body>
<div class="bar"><button onclick="window.print()">Cetak <?php echo count($semua); ?> label</button><span><?php echo html_escape($T[0]); ?> · Pastikan skala cetak 100% dan ukuran kertas di printer sesuai.</span></div>
<?php if ( ! $semua) { ?><p style="padding:20px">Tidak ada label untuk dicetak.</p><?php } ?>
<?php $per = $a4 ? $kol * $bar : count($semua);
foreach (array_chunk($semua, max(1, $per)) as $hal) { ?>
<div class="hal">
<?php foreach ($hal as $b) { if ($b === null) { echo '<div class="lbl"></div>'; continue; } $rak = trim((string) $b->{'lok_' . $lok}); ?>
    <div class="lbl">
        <div class="qr" data-qr="<?php echo html_escape(trim($b->no_part)); ?>" data-qr-margin="2" data-qr-ecc="M"></div>
        <div class="tx">
            <div class="no"><?php echo html_escape(trim($b->no_part)); ?></div>
            <?php if (isset($o['nama'])) { ?><div class="nm"><?php echo html_escape(trim($b->nama_part)); ?></div><?php } ?>
            <div class="kc">
                <?php if (isset($o['rak']) && $rak !== '') { ?><span class="rak"><?php echo html_escape($rak); ?></span> <?php } ?>
                <?php if (isset($o['gudang'])) echo strtoupper($lok) . ' '; ?>
                <?php if (isset($o['kategori'])) echo html_escape($b->nama_kategori) . ' '; ?>
                <?php $p = $pl[trim($b->no_part)] ?? 0; if (isset($o['harga']) && $p > 0) echo '<div class="hg">Rp ' . nf($p) . '</div>'; ?>
                <?php if (isset($o['tanggal'])) echo '<div>' . date('d/m/Y') . '</div>'; ?>
                <?php if (isset($o['perusahaan']) && $apl) echo '<div>' . html_escape($apl->nama_owner) . '</div>'; ?>
            </div>
        </div>
    </div>
<?php } ?>
</div>
<?php } ?>
<script src="<?php echo base_url('assets/js/top-qr.js?v=3.0.0'); ?>"></script>
<script>
window.addEventListener('load', function () {
    if (window.TOPQR) TOPQR.render();
    <?php if ($semua) { ?>setTimeout(function () { window.print(); }, 400);<?php } ?>
});
</script>
</body></html>
