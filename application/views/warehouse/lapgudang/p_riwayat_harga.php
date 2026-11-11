<?php defined('BASEPATH') OR exit('No direct script access allowed');
if ( ! $d) { echo '<p class="text-muted">Pilih barang pada filter di atas untuk melihat riwayat harganya.</p>'; return; }
$b = $d->barang; $L = Mod_lapgudang::$LOKASI;
$pc = function ($v) use ($mode) { if ($v === null) return '-'; return $mode === 'excel' ? round($v, 2) : '<span class="' . ($v > 0.5 ? 'text-danger' : ($v < -0.5 ? 'text-success' : 'text-muted')) . '">' . ($v > 0 ? '+' : '') . nf($v, 1) . '%</span>'; }; ?>
<?php if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <?php foreach ($L as $k => $nm) { $r = isset($d->rata[$k]) ? $d->rata[$k] : null; ?>
    <div class="acc-kpi__item"><div class="acc-kpi__label"><?php echo $nm; ?></div><div class="acc-kpi__value" style="font-size:18px"><?php echo nf_acc($b->{'hrg_net_' . $k}); ?></div>
        <div class="acc-kpi__sub">net master · rata-rata <?php echo $r ? nf_acc($r->harga_rata) : '-'; ?> · stok <?php echo nf_qty($b->{'stok_' . $k}); ?></div></div>
    <?php } ?>
</div>
<?php } ?>
<h6>Ringkasan per tahun</h6>
<table class="table table-sm">
    <thead><tr><th>Tahun</th><th class="num">x beli</th><th class="num">Qty</th><th class="num">Rata-rata tertimbang</th><th class="num">vs tahun lalu</th><th class="num">Minimum</th><th class="num">Maksimum</th><th class="num">Terakhir</th><th>Pemasok</th></tr></thead>
    <tbody>
    <?php foreach ($d->tahun as $y => $x) { ?><tr><td><b><?php echo $y; ?></b></td><td class="num"><?php echo nf($x->n); ?></td><td class="num"><?php echo nf_qty($x->qty, $mode); ?></td><td class="num"><b><?php echo acc_val($x->rata, $mode); ?></b></td><td class="num"><?php echo $pc($x->pct); ?></td><td class="num"><?php echo acc_val($x->min, $mode); ?></td><td class="num"><?php echo acc_val($x->max, $mode); ?></td><td class="num"><?php echo acc_val($x->akhir, $mode); ?></td><td class="small"><?php echo html_escape($x->sup); ?></td></tr><?php } ?>
    <?php if ( ! $d->tahun) { ?><tr><td colspan="9" class="text-center text-muted">Belum ada penerimaan dengan harga untuk barang ini.</td></tr><?php } ?>
    </tbody>
</table>
<h6>Pembelian (terbaru di atas)</h6>
<table class="table table-sm">
    <thead><tr><th>Tanggal</th><th>Jenis</th><th>Gudang</th><th>Keterangan</th><th class="num">Qty</th><th class="num">Harga</th><th class="num">Nilai</th></tr></thead>
    <tbody>
    <?php foreach (array_slice($d->beli, 0, 100) as $g) { ?><tr><td><?php echo $mode === 'excel' ? $g->tgl : tgl_singkat($g->tgl); ?></td><td><?php echo $mode === 'excel' ? $g->jenis : '<span class="wh-jenis wh-jenis--' . $g->jenis . '">' . Mod_lapgudang::$JENIS[$g->jenis] . '</span>'; ?></td><td><?php echo $g->lok ? $L[$g->lok] : '-'; ?></td><td class="small"><?php echo html_escape($g->ket); ?></td><td class="num"><?php echo nf_qty($g->masuk, $mode); ?></td><td class="num"><?php echo acc_val($g->harga, $mode); ?></td><td class="num"><?php echo acc_val($g->masuk * $g->harga, $mode); ?></td></tr><?php } ?>
    <?php if ( ! $d->beli) { ?><tr><td colspan="7" class="text-center text-muted">-</td></tr><?php } ?>
    </tbody>
</table>
<h6>Log perubahan harga master</h6>
<table class="table table-sm">
    <thead><tr><th>Tanggal</th><th>Gudang</th><th class="num">Price list lama</th><th class="num">Price list baru</th><th class="num">Net lama</th><th class="num">Net baru</th><th class="num">Perubahan net</th><th>Oleh</th></tr></thead>
    <tbody>
    <?php foreach ($d->log as $g) { ?><tr><td><?php echo $mode === 'excel' ? $g->tgl_update : tgl_singkat($g->tgl_update); ?></td><td><?php echo html_escape(trim($g->lokasi)); ?></td><td class="num"><?php echo acc_val($g->hrg_price_list_lama, $mode); ?></td><td class="num"><?php echo acc_val($g->hrg_price_list, $mode); ?></td><td class="num"><?php echo acc_val($g->hrg_net_lama, $mode); ?></td><td class="num"><?php echo acc_val($g->hrg_net, $mode); ?></td><td class="num"><?php echo $pc($g->hrg_net_lama > 0 ? ($g->hrg_net - $g->hrg_net_lama) / $g->hrg_net_lama * 100 : null); ?></td><td><?php echo html_escape($g->user); ?></td></tr><?php } ?>
    <?php if ( ! $d->log) { ?><tr><td colspan="8" class="text-center text-muted">Belum ada perubahan harga tercatat.</td></tr><?php } ?>
    </tbody>
</table>
