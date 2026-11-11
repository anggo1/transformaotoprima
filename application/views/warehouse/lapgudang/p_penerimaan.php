<?php defined('BASEPATH') OR exit('No direct script access allowed'); $T = $d['total']; ?>
<table class="table table-sm">
    <thead><tr><th>Supplier</th><th class="num">Dokumen</th><th class="num">Baris</th><th class="num">Qty</th><th class="num">Nilai PO</th><th class="num">Nilai Non-PO</th><th class="num">Nilai Retur</th><th class="num">Total Nilai</th><th>Terakhir</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $k => $x) { ?>
        <tr><td><?php echo html_escape($k); ?></td><td class="num"><?php echo nf($x['dok']); ?></td><td class="num"><?php echo nf($x['baris']); ?></td><td class="num"><?php echo nf_qty($x['qty'], $mode); ?></td>
            <td class="num"><?php echo acc_val($x['MPO'], $mode); ?></td><td class="num"><?php echo acc_val($x['MNP'], $mode); ?></td><td class="num"><?php echo acc_val($x['RTR'], $mode); ?></td>
            <td class="num"><b><?php echo acc_val($x['nilai'], $mode); ?></b></td><td><?php echo $x['terakhir'] ? ($mode === 'excel' ? $x['terakhir'] : tgl_singkat($x['terakhir'])) : '-'; ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="9" class="text-center text-muted">Tidak ada penerimaan pada periode ini.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td>TOTAL</td><td class="num"><?php echo nf($T->dok); ?></td><td class="num"><?php echo nf($T->baris); ?></td><td class="num"><?php echo nf_qty($T->qty, $mode); ?></td><td colspan="3"></td><td class="num"><?php echo acc_val($T->nilai, $mode); ?></td><td></td></tr></tfoot>
</table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">Nilai = qty × harga pada dokumen penerimaan. Penerimaan tanpa harga bernilai 0 — lengkapi harga agar analisis pembelian akurat.</p><?php } ?>
