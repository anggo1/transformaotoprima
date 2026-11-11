<?php defined('BASEPATH') OR exit('No direct script access allowed');
if ( ! $d || ! $info) return;
if ($d['tipe'] === 'pengumuman') { ?>
<div class="card dw-card dw-pengumuman dw-pengumuman--<?php echo html_escape($d['warna']); ?>">
    <div class="card-body d-flex" style="gap:.9rem">
        <i class="fas fa-bullhorn dw-pengumuman__ikon"></i>
        <div><b><?php echo html_escape($d['judul']); ?></b><div class="mt-1"><?php echo $d['html']; ?></div><div class="small text-muted mt-1"><?php echo html_escape($d['oleh']); ?> · <?php echo $d['tgl'] ? date('d/m/Y H:i', strtotime($d['tgl'])) : ''; ?></div></div>
    </div>
</div>
<?php return; }
if ($d['tipe'] === 'kpi') { ?>
<div class="dw-card dw-card--kpi">
    <div class="acc-kpi mb-0">
    <?php foreach ($d['item'] as $x) { ?>
        <a class="acc-kpi__item dw-kpi" <?php echo $x[3] ? 'href="' . site_url($x[3]) . '"' : ''; ?>>
            <?php if ($x[4]) { ?><span class="dw-kpi__ico <?php echo $x[5]; ?>"><i class="fas <?php echo $x[4]; ?>"></i></span><?php } ?>
            <div class="acc-kpi__label"><?php echo html_escape($x[0]); ?></div>
            <div class="acc-kpi__value"><?php echo $x[1]; ?></div>
            <div class="acc-kpi__sub"><?php echo html_escape($x[2]); ?></div>
        </a>
    <?php } ?>
    </div>
</div>
<?php return; } ?>
<div class="card dw-card">
    <div class="card-header dw-card__head"><h3 class="card-title"><i class="fas <?php echo $info[2]; ?> mr-1"></i> <?php echo html_escape($info[0]); ?></h3>
        <?php if ( ! empty($d['link'])) { ?><a class="dw-card__link" href="<?php echo site_url($d['link'][0]); ?>"><?php echo html_escape($d['link'][1]); ?> <i class="fas fa-angle-right"></i></a><?php } ?></div>
    <?php if ($d['tipe'] === 'chart') { if (empty($d['chart']['judul'])) $d['chart']['judul'] = $info[0]; ?>
    <div class="card-body pt-2"><div class="top-chart" data-tanpa-judul data-chart='<?php echo html_escape(json_encode($d['chart'])); ?>'></div></div>
    <?php } elseif ($d['tipe'] === 'tabel') { ?>
    <div class="card-body p-0"><div class="table-responsive"><table class="table table-sm mb-0 dw-tabel">
        <thead><tr><?php foreach ($d['kolom'] as $k) { ?><th class="<?php echo isset($k[1]) ? $k[1] : ''; ?>"><?php echo html_escape($k[0]); ?></th><?php } ?></tr></thead>
        <tbody>
        <?php foreach ($d['baris'] as $b) { ?><tr><?php foreach ($b as $i => $c) { ?><td class="<?php echo isset($d['kolom'][$i][1]) ? $d['kolom'][$i][1] : ''; ?>"><?php echo $c; ?></td><?php } ?></tr><?php } ?>
        <?php if ( ! $d['baris']) { ?><tr><td colspan="<?php echo count($d['kolom']); ?>" class="text-center text-muted py-3"><?php echo html_escape($d['kosong']); ?></td></tr><?php } ?>
        </tbody></table></div></div>
    <?php } else { ?>
    <div class="card-body"><?php echo $d['html']; ?></div>
    <?php } ?>
    <?php if ( ! empty($d['catatan'])) { ?><div class="card-footer small text-muted py-2"><?php echo html_escape($d['catatan']); ?></div><?php } ?>
</div>
