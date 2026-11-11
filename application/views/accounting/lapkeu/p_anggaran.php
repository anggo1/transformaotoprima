<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<table class="table table-sm">
    <thead><tr><th>Kode</th><th>Nama Akun</th><th>Tipe</th><th class="num">Anggaran</th><th class="num">Realisasi</th><th class="num">Selisih</th><th class="num">Pencapaian</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $r) { ?>
        <tr><td><?php echo html_escape($r->kode); ?></td><td><?php echo html_escape($r->nama); ?></td><td><?php echo Akuntansi::label_kategori($r->kategori); ?></td>
            <td class="num"><?php echo acc_val($r->anggaran, $mode); ?></td><td class="num"><?php echo acc_val($r->realisasi, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->selisih, $mode); ?></td>
            <td class="num"><?php echo $r->persen === null ? '-' : ($mode === 'excel' ? round($r->persen, 2) : nf($r->persen, 1) . '%'); ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="7" class="text-center text-muted">Belum ada anggaran / realisasi. Isi anggaran di menu Accounting &rsaquo; Anggaran.</td></tr><?php } ?>
    </tbody>
</table>
