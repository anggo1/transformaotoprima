<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo html_escape($info[0]); ?></title>
    <?php if ($mode === 'print') { ?>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #111; margin: 18px; }
        .head { text-align: center; margin-bottom: 14px; }
        .head .co { font-weight: 700; font-size: 13px; }
        .head h3 { margin: 2px 0; font-size: 16px; }
        .head .sub { color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 4px 6px; border-bottom: 1px solid #e3e3e3; vertical-align: top; }
        thead th { background: #f1f3f6; border-bottom: 1.5px solid #888; text-align: left; font-size: 10px; text-transform: uppercase; }
        .num, .acc-num { text-align: right; white-space: nowrap; }
        .row-group td { font-weight: 700; background: #f6f7f9; }
        .row-sub td:first-child { padding-left: 18px; }
        .row-sub2 td:first-child { padding-left: 30px; }
        .row-total td { font-weight: 700; border-top: 1.5px solid #888; }
        .row-grand td { font-weight: 800; border-top: 2px solid #111; background: #eef6f7; }
        .acc-neg { color: #b00; }
        .ttd { margin-top: 40px; display: flex; justify-content: space-between; }
        .ttd div { width: 30%; text-align: center; }
        .ttd .garis { margin-top: 60px; border-top: 1px solid #333; padding-top: 4px; }
        .foot { margin-top: 12px; color: #777; font-size: 10px; }
        .grafik-cetak { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; page-break-inside: avoid; }
        .grafik-cetak .top-chart { border: 1px solid #e3e3e3; border-radius: 6px; padding: 8px; page-break-inside: avoid; }
        .top-chart__judul { font-weight: 700; font-size: 11px; margin-bottom: 4px; }
        @media print { body { margin: 0; } * { -webkit-print-color-adjust: exact; print-color-adjust: exact; } @page { margin: 12mm; } }
    </style>
    <?php } ?>
</head>
<body>
    <div class="head">
        <div class="co"><?php echo html_escape($perusahaan); ?></div>
        <h3><?php echo html_escape($info[0]); ?></h3>
        <div class="sub"><?php echo html_escape($subjudul); ?></div>
    </div>
    <?php if ( ! empty($grafik)) { if ($mode === 'excel') { foreach ($grafik as $g) { ?>
    <table><thead><tr><th colspan="<?php echo 1 + count($g['seri']); ?>"><?php echo html_escape('Grafik: ' . $g['judul']); ?></th></tr>
        <tr><th>Label</th><?php foreach ($g['seri'] as $s) { ?><th><?php echo html_escape($s['nama']); ?></th><?php } ?></tr></thead><tbody>
        <?php foreach ($g['labels'] as $i => $l) { ?><tr><td><?php echo html_escape($l); ?></td><?php foreach ($g['seri'] as $s) { ?><td><?php echo isset($s['data'][$i]) ? $s['data'][$i] : ''; ?></td><?php } ?></tr><?php } ?>
    </tbody></table><br>
    <?php } } else { ?>
    <div class="grafik-cetak"><?php foreach ($grafik as $g) { ?><div class="top-chart" data-cetak data-chart="<?php echo html_escape(json_encode($g)); ?>"></div><?php } ?></div>
    <?php } } ?>
    <?php $this->load->view(strpos($partial, '/') !== false ? $partial : 'accounting/lapkeu/p_' . $partial, array('d' => $d, 'f' => $f, 'mode' => $mode, 'kunci' => $kunci)); ?>
    <?php if ($mode === 'print') { ?>
    <div class="ttd">
        <div>Dibuat oleh,<div class="garis"><?php echo html_escape((string) $this->session->userdata('full_name')); ?></div></div>
        <div>Diperiksa oleh,<div class="garis">&nbsp;</div></div>
        <div>Disetujui oleh,<div class="garis">&nbsp;</div></div>
    </div>
    <div class="foot">Dicetak <?php echo date('d-m-Y H:i'); ?></div>
    <?php if ( ! empty($grafik)) { ?>
    <script>window.TOP = { baseUrl: '<?php echo base_url(); ?>' };</script>
    <script src="<?php echo base_url('assets/plugins/chart.js/Chart.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/top-chart.js?v=3.0.0'); ?>"></script>
    <script>window.onload = function () { TOPChart.render().then(function () { setTimeout(function () { window.print(); }, 300); }); };</script>
    <?php } else { ?>
    <script>window.onload = function () { window.print(); };</script>
    <?php } ?>
    <?php } ?>
</body>
</html>
