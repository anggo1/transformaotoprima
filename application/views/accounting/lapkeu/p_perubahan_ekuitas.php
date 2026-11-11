<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<table class="table table-sm">
    <thead><tr><th>Keterangan</th><th class="num">Saldo Awal</th><th class="num">Penambahan (Pengurangan)</th><th class="num">Saldo Akhir</th></tr></thead>
    <tbody>
        <?php foreach ($d['rows'] as $r) { ?>
        <tr><td><?php echo html_escape($r->kode . ' - ' . $r->nama); ?></td><td class="num"><?php echo acc_val($r->awal, $mode); ?></td><td class="num"><?php echo acc_val($r->mutasi, $mode); ?></td><td class="num"><?php echo acc_val($r->akhir, $mode); ?></td></tr>
        <?php } ?>
        <tr><td>Laba ditahan (akumulasi laba/rugi sebelum periode)</td><td class="num"><?php echo acc_val($d['laba_ditahan_awal'], $mode); ?></td><td class="num">-</td><td class="num"><?php echo acc_val($d['laba_ditahan_awal'], $mode); ?></td></tr>
        <tr><td>Laba (rugi) bersih periode berjalan</td><td class="num">-</td><td class="num"><?php echo acc_val($d['laba_periode'], $mode); ?></td><td class="num"><?php echo acc_val($d['laba_periode'], $mode); ?></td></tr>
        <tr class="row-grand"><td>TOTAL EKUITAS</td><td class="num"><?php echo acc_val($d['awal'], $mode); ?></td><td class="num"><?php echo acc_val($d['mutasi_akun'] + $d['laba_periode'], $mode); ?></td><td class="num"><?php echo acc_val($d['akhir'], $mode); ?></td></tr>
    </tbody>
</table>
