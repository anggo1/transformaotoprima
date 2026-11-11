<?php defined('BASEPATH') OR exit('No direct script access allowed'); $t = array(0, 0, 0); ?>
<table class="table table-sm">
    <thead><tr><th>Kode</th><th>Nama Aset</th><th>Kelompok</th><th>Tgl Perolehan</th><th>Metode</th><th class="num">Umur (bln)</th><th class="num">Harga Perolehan</th><th class="num">Akumulasi Penyusutan</th><th class="num">Nilai Buku</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($d as $a) { $t[0] += $a->harga_perolehan; $t[1] += $a->akumulasi; $t[2] += $a->nilai_buku; ?>
        <tr><td><?php echo html_escape($a->kode_aset); ?></td><td><?php echo html_escape($a->nama_aset); ?></td><td><?php echo html_escape($a->kelompok_aset); ?></td>
            <td><?php echo $mode === 'excel' ? $a->tgl_perolehan : tgl_singkat($a->tgl_perolehan); ?></td><td><?php echo str_replace('_', ' ', $a->metode); ?></td><td class="num"><?php echo (int) $a->umur_bulan; ?></td>
            <td class="num"><?php echo acc_val($a->harga_perolehan, $mode); ?></td><td class="num"><?php echo acc_val($a->akumulasi, $mode); ?></td><td class="num"><?php echo acc_val($a->nilai_buku, $mode); ?></td>
            <td><?php echo $mode === 'excel' ? $a->status : '<span class="acc-status acc-status--' . $a->status . '">' . $a->status . '</span>'; ?></td></tr>
    <?php } if ( ! $d) { ?><tr><td colspan="10" class="text-center text-muted">Belum ada aset tetap.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="6">TOTAL</td><td class="num"><?php echo acc_val($t[0], $mode); ?></td><td class="num"><?php echo acc_val($t[1], $mode); ?></td><td class="num"><?php echo acc_val($t[2], $mode); ?></td><td></td></tr></tfoot>
</table>
