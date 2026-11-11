<?php defined('BASEPATH') OR exit('No direct script access allowed');
$lbl = array('OPERASI' => 'Arus Kas dari Aktivitas Operasi', 'INVESTASI' => 'Arus Kas dari Aktivitas Investasi', 'PENDANAAN' => 'Arus Kas dari Aktivitas Pendanaan');
?>
<table class="table table-sm">
    <thead><tr><th>Keterangan</th><th class="num">Kas Masuk</th><th class="num">Kas Keluar</th><th class="num">Bersih</th></tr></thead>
    <tbody>
        <?php foreach ($lbl as $k => $l) { ?>
        <tr class="row-group"><td colspan="4"><?php echo $l; ?></td></tr>
        <?php foreach ($d['aktivitas'][$k] as $r) { ?>
        <tr class="row-sub"><td><?php echo html_escape($r->kode . ' - ' . $r->nama); ?></td>
            <td class="num"><?php echo acc_val($r->masuk, $mode); ?></td><td class="num"><?php echo acc_val($r->keluar, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->masuk - $r->keluar, $mode); ?></td></tr>
        <?php } if ( ! $d['aktivitas'][$k]) { ?><tr class="row-sub"><td class="text-muted" colspan="4">—</td></tr><?php } ?>
        <tr class="row-total"><td>Kas bersih dari aktivitas <?php echo strtolower($k); ?></td><td></td><td></td><td class="num"><?php echo acc_val($d['total'][$k], $mode); ?></td></tr>
        <?php } ?>
        <tr class="row-total"><td>Kenaikan (penurunan) kas bersih</td><td></td><td></td><td class="num"><?php echo acc_val($d['net'], $mode); ?></td></tr>
        <tr><td>Saldo kas &amp; bank awal periode</td><td></td><td></td><td class="num"><?php echo acc_val($d['awal'], $mode); ?></td></tr>
        <tr class="row-grand"><td>SALDO KAS &amp; BANK AKHIR PERIODE</td><td></td><td></td><td class="num"><?php echo acc_val($d['akhir'], $mode); ?></td></tr>
    </tbody>
</table>
<?php if ($mode === 'screen' && ! $d['akun_kas']) { ?>
<div class="alert alert-info py-2">Belum ada akun bertipe <strong>Kas &amp; Bank</strong>. Atur tipe akun di menu Daftar Akun.</div>
<?php } ?>
