<?php defined('BASEPATH') OR exit('No direct script access allowed');
$tab = function ($judul, $arr, $tot) use ($mode) { ?>
<h6 class="mt-3"><?php echo $judul; ?></h6>
<table class="table table-sm" style="max-width:560px"><thead><tr><th><?php echo $judul; ?></th><th class="num">Pegawai</th><th class="num">%</th></tr></thead><tbody>
<?php foreach ($arr as $k => $n) { ?><tr><td><?php echo html_escape($k); ?></td><td class="num"><?php echo nf($n); ?></td><td class="num"><?php echo $tot ? nf($n / $tot * 100, 1) : 0; ?></td></tr><?php } ?>
</tbody></table>
<?php }; ?>
<?php if ($mode !== 'excel') { ?><div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Pegawai aktif</div><div class="acc-kpi__value"><?php echo nf($d['total']); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Departemen</div><div class="acc-kpi__value"><?php echo nf(count($d['dept'])); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Jabatan</div><div class="acc-kpi__value"><?php echo nf(count($d['jab'])); ?></div></div>
</div><?php } ?>
<?php $tab('Departemen', $d['dept'], $d['total']); $tab('Jabatan', $d['jab'], $d['total']); $tab('Pendidikan', $d['pend'], $d['total']); $tab('Jenis kelamin', $d['jk'], $d['total']); $tab('Kelompok usia', $d['umur'], $d['total']); $tab('Status kerja', $d['status'], $d['total']); ?>
<h6 class="mt-3">Matriks departemen × jabatan</h6>
<?php $jab = array_keys($d['jab']); ?>
<table class="table table-sm"><thead><tr><th>Departemen</th><?php foreach ($jab as $j) { ?><th class="num"><?php echo html_escape($j); ?></th><?php } ?><th class="num">Total</th></tr></thead><tbody>
<?php foreach ($d['matriks'] as $dep => $x) { ?><tr><td><b><?php echo html_escape($dep); ?></b></td><?php foreach ($jab as $j) { ?><td class="num"><?php echo isset($x[$j]) ? nf($x[$j]) : ''; ?></td><?php } ?><td class="num"><b><?php echo nf(array_sum($x)); ?></b></td></tr><?php } ?>
</tbody></table>
