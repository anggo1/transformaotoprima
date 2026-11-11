<?php defined('BASEPATH') OR exit('No direct script access allowed'); $gd = 0; $gk = 0; ?>
<table class="table table-sm">
    <thead><tr><th>Tanggal</th><th>No. Ref</th><th>Sumber</th><th>Akun</th><th>Keterangan</th><th class="num">Debit</th><th class="num">Kredit</th></tr></thead>
    <tbody>
    <?php foreach ($d as $j) { $gd += $j->debit; $gk += $j->kredit; $first = true; ?>
        <?php foreach ($j->rows as $l) { ?>
        <tr<?php echo $first ? ' style="border-top:1.5px solid #cfd6df"' : ''; ?>>
            <td><?php echo $first ? ($mode === 'excel' ? $j->tgl : tgl_singkat($j->tgl)) : ''; ?></td>
            <td><?php echo $first ? html_escape($j->no_ref) : ''; ?></td>
            <td><?php echo $first ? html_escape(isset(Akuntansi::$SUMBER[$j->sumber]) ? Akuntansi::$SUMBER[$j->sumber] : $j->sumber) : ''; ?></td>
            <td<?php echo (float) $l->kredit > 0 ? ' style="padding-left:1.5rem"' : ''; ?>><?php echo html_escape($l->kode_akun . ' - ' . $l->nama_akun); ?></td>
            <td><?php echo html_escape($l->keterangan); ?></td>
            <td class="num"><?php echo acc_val($l->debit, $mode); ?></td>
            <td class="num"><?php echo acc_val($l->kredit, $mode); ?></td>
        </tr>
        <?php $first = false; } ?>
        <?php if (abs($j->debit - $j->kredit) > 0.5) { ?><tr><td colspan="7" class="text-danger small">Jurnal <?php echo html_escape($j->no_ref); ?> tidak seimbang (selisih <?php echo nf($j->debit - $j->kredit, 2); ?>)</td></tr><?php } ?>
    <?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="5">TOTAL (<?php echo count($d); ?> jurnal)</td><td class="num"><?php echo acc_val($gd, $mode); ?></td><td class="num"><?php echo acc_val($gk, $mode); ?></td></tr></tfoot>
</table>
