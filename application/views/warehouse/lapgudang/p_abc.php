<?php defined('BASEPATH') OR exit('No direct script access allowed'); $K = $d['kelas']; ?>
<?php if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <?php foreach (array('A' => 'Item penting: ±80% nilai pemakaian. Kontrol ketat, hitung fisik sering, jaga stok aman.', 'B' => 'Item menengah: ±15% nilai. Kontrol normal.', 'C' => 'Item kecil: ±5% nilai. Pesan dalam jumlah ekonomis, kontrol sederhana.') as $c => $ket) { ?>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Kelas <?php echo $c; ?></div><div class="acc-kpi__value"><?php echo nf($K[$c][0]); ?> <small class="text-muted" style="font-size:12px">item</small></div><div class="acc-kpi__sub"><?php echo nf_acc($K[$c][1]); ?> · <?php echo $ket; ?></div></div>
    <?php } ?>
</div>
<?php } ?>
<table class="table table-sm">
    <thead><tr><th>#</th><th>Kelas</th><th>No. Part</th><th>Nama Barang</th><th>Kategori</th><th class="num">Qty Keluar</th><th class="num">Transaksi</th><th class="num">Nilai Pemakaian</th><th class="num">%</th><th class="num">% Kumulatif</th><th class="num">Stok Kini</th></tr></thead>
    <tbody>
    <?php $i = 0; foreach ($d['rows'] as $r) { $i++; ?>
        <tr><td><?php echo $i; ?></td><td><?php echo $mode === 'excel' ? $r->kelas : '<span class="wh-abc wh-abc--' . $r->kelas . '">' . $r->kelas . '</span>'; ?></td>
            <td><?php echo html_escape($r->no_part); ?></td><td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->kategori); ?></td>
            <td class="num"><?php echo nf_qty($r->qty, $mode); ?></td><td class="num"><?php echo nf($r->transaksi); ?></td><td class="num"><?php echo acc_val($r->nilai, $mode); ?></td>
            <td class="num"><?php echo $mode === 'excel' ? round($r->persen, 2) : nf($r->persen, 1) . '%'; ?></td><td class="num"><?php echo $mode === 'excel' ? round($r->kumulatif, 2) : nf($r->kumulatif, 1) . '%'; ?></td><td class="num"><?php echo nf_qty($r->stok, $mode); ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="11" class="text-center text-muted">Belum ada pengeluaran barang pada periode ini.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="7">TOTAL NILAI PEMAKAIAN</td><td class="num"><?php echo acc_val($d['total'], $mode); ?></td><td colspan="3"></td></tr></tfoot>
</table>
