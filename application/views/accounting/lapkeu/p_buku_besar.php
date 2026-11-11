<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ( ! $d) { ?><p class="text-center text-muted">Tidak ada transaksi pada periode ini.</p><?php } ?>
<table class="table table-sm">
    <thead><tr><th>Tanggal</th><th>No. Ref</th><th>No. Bukti</th><th>Keterangan</th><th class="num">Debit</th><th class="num">Kredit</th><th class="num">Saldo</th></tr></thead>
    <tbody>
    <?php foreach ($d as $a) { ?>
        <tr class="row-group"><td colspan="7"><?php echo html_escape($a->kode . ' - ' . $a->nama); ?> <small class="text-muted">(saldo normal <?php echo $a->saldo_normal === 'K' ? 'Kredit' : 'Debit'; ?>)</small></td></tr>
        <tr class="row-sub"><td><?php echo $mode === 'excel' ? $f['dari'] : tgl_singkat($f['dari']); ?></td><td></td><td></td><td><em>Saldo awal</em></td><td></td><td></td><td class="num"><?php echo acc_val($a->awal, $mode); ?></td></tr>
        <?php foreach ($a->rows as $l) { ?>
        <tr class="row-sub">
            <td><?php echo $mode === 'excel' ? $l->tgl_jurnal : tgl_singkat($l->tgl_jurnal); ?></td>
            <td><?php echo html_escape($l->no_ref); ?></td>
            <td><?php echo html_escape(trim((string) $l->no_bukti, "' ")); ?></td>
            <td><?php echo html_escape($l->keterangan); ?></td>
            <td class="num"><?php echo acc_val($l->debit, $mode); ?></td>
            <td class="num"><?php echo acc_val($l->kredit, $mode); ?></td>
            <td class="num"><?php echo acc_val($l->saldo, $mode); ?></td>
        </tr>
        <?php } ?>
        <tr class="row-total"><td colspan="4">Total <?php echo html_escape($a->kode); ?></td><td class="num"><?php echo acc_val($a->debit, $mode); ?></td><td class="num"><?php echo acc_val($a->kredit, $mode); ?></td><td class="num"><?php echo acc_val($a->akhir, $mode); ?></td></tr>
    <?php } ?>
    </tbody>
</table>
