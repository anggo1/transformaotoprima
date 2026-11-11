<?php defined('BASEPATH') OR exit('No direct script access allowed');
$tot = array('b0' => 0, 'b1' => 0, 'b2' => 0, 'b3' => 0, 'b4' => 0, 'total' => 0);
$label = $d['jenis'] === 'AR' ? 'Pelanggan' : 'Pemasok';
?>
<table class="table table-sm">
    <thead><tr><th><?php echo $label; ?></th><?php if ($d['rinci']) { ?><th>No. Faktur</th><th>Tgl</th><th>Jatuh Tempo</th><th class="num">Hari</th><?php } ?>
        <th class="num">Belum Jatuh Tempo</th><th class="num">1-30 hari</th><th class="num">31-60 hari</th><th class="num">61-90 hari</th><th class="num">&gt; 90 hari</th><th class="num">Total</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $k) { foreach ($tot as $b => $v) $tot[$b] += $k->$b; ?>
        <?php if ($d['rinci']) { foreach ($k->faktur as $x) { ?>
        <tr class="row-sub"><td><?php echo html_escape($k->kontak); ?></td><td><?php echo html_escape($x->no_faktur); ?></td><td><?php echo tgl_singkat($x->tanggal); ?></td><td><?php echo tgl_singkat($x->jatuh_tempo); ?></td><td class="num"><?php echo max(0, $x->hari); ?></td>
            <?php foreach (array('b0', 'b1', 'b2', 'b3', 'b4') as $b) { ?><td class="num"><?php echo acc_val($x->bucket === $b ? $x->sisa : 0, $mode); ?></td><?php } ?>
            <td class="num"><?php echo acc_val($x->sisa, $mode); ?></td></tr>
        <?php } } ?>
        <tr class="<?php echo $d['rinci'] ? 'row-total' : ''; ?>"><td><?php echo html_escape($k->kontak); ?><?php echo $d['rinci'] ? ' (subtotal)' : ''; ?></td><?php if ($d['rinci']) { ?><td colspan="4"></td><?php } ?>
            <?php foreach (array('b0', 'b1', 'b2', 'b3', 'b4', 'total') as $b) { ?><td class="num"><?php echo acc_val($k->$b, $mode); ?></td><?php } ?></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="12" class="text-center text-muted">Tidak ada saldo <?php echo $d['jenis'] === 'AR' ? 'piutang' : 'hutang'; ?> terbuka.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td<?php echo $d['rinci'] ? ' colspan="5"' : ''; ?>>TOTAL</td><?php foreach ($tot as $v) { ?><td class="num"><?php echo acc_val($v, $mode); ?></td><?php } ?></tr></tfoot>
</table>
