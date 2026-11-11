<?php defined('BASEPATH') OR exit('No direct script access allowed');
$AR = $d['jenis'] === 'AR'; $rinci = $d['rinci'];
$t = array('dpp' => 0, 'ppn' => 0, 'total' => 0, 'terbayar' => 0, 'sisa' => 0);
$tgl = function ($x) use ($mode) { return $mode === 'excel' ? $x : tgl_singkat($x); };
?>
<table class="table table-sm">
    <thead><tr><th>Tanggal</th><th>No. Faktur</th><th><?php echo $AR ? 'Pelanggan' : 'Pemasok'; ?></th><th>No. Faktur Pajak</th><th>Jatuh Tempo</th>
        <th class="num">DPP</th><th class="num">PPN</th><th class="num">Total</th><th class="num">Terbayar</th><th class="num">Sisa</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $r) { if ($r->status !== 'BATAL') { $t['dpp'] += $r->dpp; $t['ppn'] += $r->ppn; $t['total'] += $r->total; $t['terbayar'] += $r->terbayar; $t['sisa'] += $r->sisa; } ?>
        <tr<?php echo $rinci ? ' class="row-group"' : ''; ?><?php echo $r->status === 'BATAL' ? ' style="opacity:.55"' : ''; ?>>
            <td><?php echo $tgl($r->tanggal); ?></td>
            <td><?php echo $mode === 'screen' ? '<a href="' . site_url(($AR ? 'Piutang' : 'Hutang') . '/detail/' . $r->id) . '">' . html_escape($r->no_faktur) . '</a>' : html_escape($r->no_faktur); ?></td>
            <td><?php echo html_escape($r->kontak); ?></td>
            <td><?php echo html_escape($r->no_faktur_pajak); ?><?php if ($mode === 'screen' && $r->ppn > 0 && $r->no_faktur_pajak === '') { ?><span class="badge badge-warning" title="Faktur ber-PPN tanpa NSFP">NSFP kosong</span><?php } ?></td>
            <td><?php echo $tgl($r->jatuh_tempo); ?><?php if ($mode === 'screen' && $r->telat > 0) { ?> <span class="badge badge-danger"><?php echo $r->telat; ?> hr</span><?php } ?></td>
            <td class="num"><?php echo acc_val($r->dpp, $mode); ?></td><td class="num"><?php echo acc_val($r->ppn, $mode); ?></td><td class="num"><?php echo acc_val($r->total, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->terbayar, $mode); ?></td><td class="num"><?php echo acc_val($r->sisa, $mode); ?></td>
            <td><?php echo $mode === 'excel' ? $r->status : '<span class="acc-status acc-status--' . $r->status . '">' . $r->status . '</span>'; ?></td>
        </tr>
        <?php if ($rinci) { foreach ($r->detail as $x) { ?>
        <tr class="row-sub2"><td colspan="3"><?php echo html_escape($x->kode_akun . ' ' . $x->nama_akun); ?></td><td colspan="2"><?php echo html_escape($x->keterangan); ?></td>
            <td class="num" colspan="2"><?php echo ($mode === 'excel' ? (float) $x->qty : nf($x->qty, 2)) . ' × ' . ($mode === 'excel' ? (float) $x->harga : nf($x->harga, 0)); ?></td><td class="num"><?php echo acc_val($x->jumlah, $mode); ?></td><td colspan="3"></td></tr>
        <?php } } ?>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="11" class="text-center text-muted">Tidak ada faktur pada periode ini.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="5">TOTAL (<?php echo count($d['rows']); ?> faktur, tanpa yang batal)</td>
        <?php foreach ($t as $v) { ?><td class="num"><?php echo acc_val($v, $mode); ?></td><?php } ?><td></td></tr></tfoot>
</table>
