<?php defined('BASEPATH') OR exit('No direct script access allowed');
$ikon = array('ok' => array('fa-check-circle', '#16a34a', 'OK'), 'info' => array('fa-info-circle', '#2563eb', 'Info'), 'warn' => array('fa-exclamation-triangle', '#d97706', 'Perhatian'), 'bad' => array('fa-times-circle', '#dc2626', 'Masalah'));
$S = $d['skor'];
?>
<?php if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <?php foreach (array('ok', 'info', 'warn', 'bad') as $k) { ?>
    <div class="acc-kpi__item"><div class="acc-kpi__label"><i class="fas <?php echo $ikon[$k][0]; ?>" style="color:<?php echo $ikon[$k][1]; ?>"></i> <?php echo $ikon[$k][2]; ?></div><div class="acc-kpi__value"><?php echo $S[$k]; ?></div><div class="acc-kpi__sub">pemeriksaan</div></div>
    <?php } ?>
</div>
<?php } ?>
<table class="table table-sm">
    <thead><tr><th style="width:4%"></th><th style="width:28%">Pemeriksaan</th><th>Hasil</th><th style="width:30%">Saran perbaikan</th></tr></thead>
    <tbody>
    <?php foreach ($d['cek'] as $i => $c) { $k = $ikon[$c->status]; ?>
        <tr><td><?php echo $mode === 'excel' ? $k[2] : '<i class="fas ' . $k[0] . '" style="color:' . $k[1] . ';font-size:16px"></i>'; ?></td>
            <td><strong><?php echo ($i + 1) . '. ' . html_escape($c->judul); ?></strong>
                <?php if ($c->link && $mode === 'screen') { ?><div><a class="small" href="<?php echo site_url($c->link); ?>">Buka <i class="fas fa-arrow-right"></i></a></div><?php } ?></td>
            <td><?php echo html_escape($c->pesan); ?>
                <?php if ($c->detail) { ?><ul class="small mb-0 mt-1 pl-3"><?php foreach (array_slice($c->detail, 0, 15) as $x) { ?><li><?php echo html_escape($x); ?></li><?php } ?><?php if (count($c->detail) > 15) { ?><li>… dan <?php echo count($c->detail) - 15; ?> lainnya</li><?php } ?></ul><?php } ?></td>
            <td class="small text-muted"><?php echo $c->status === 'ok' ? '' : html_escape($c->saran); ?></td></tr>
    <?php } ?>
    </tbody>
</table>
