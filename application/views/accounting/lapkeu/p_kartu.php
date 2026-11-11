<?php defined('BASEPATH') OR exit('No direct script access allowed');
$AR = $d['jenis'] === 'AR'; $T = $d['total'];
$tgl = function ($t) use ($mode) { return $t ? ($mode === 'excel' ? $t : tgl_singkat($t)) : ''; };
?>
<table class="table table-sm">
    <thead><tr><th>Tanggal</th><th>No. Bukti</th><th>Keterangan</th><th>Jatuh Tempo</th>
        <th class="num"><?php echo $AR ? 'Penambahan (Faktur)' : 'Penambahan (Tagihan)'; ?></th><th class="num"><?php echo $AR ? 'Pengurangan (Diterima)' : 'Pengurangan (Dibayar)'; ?></th><th class="num">Saldo</th></tr></thead>
    <tbody>
    <?php foreach ($d['kontak'] as $c) { ?>
        <tr class="row-group"><td colspan="7"><?php echo html_escape($c->kontak); ?></td></tr>
        <tr class="row-sub"><td><?php echo $tgl($f['dari']); ?></td><td></td><td><em>Saldo awal</em></td><td></td><td></td><td></td><td class="num"><?php echo acc_val($c->awal, $mode); ?></td></tr>
        <?php foreach ($c->baris as $b) { ?>
        <tr class="row-sub"><td><?php echo $tgl($b->tanggal); ?></td><td><?php echo html_escape($b->no); ?></td><td><?php echo html_escape($b->ket); ?></td><td><?php echo $tgl($b->jt); ?></td>
            <td class="num"><?php echo acc_val($b->tambah, $mode); ?></td><td class="num"><?php echo acc_val($b->kurang, $mode); ?></td><td class="num"><?php echo acc_val($b->saldo, $mode); ?></td></tr>
        <?php } ?>
        <tr class="row-total"><td colspan="4">Total <?php echo html_escape($c->kontak); ?></td><td class="num"><?php echo acc_val($c->tambah, $mode); ?></td><td class="num"><?php echo acc_val($c->kurang, $mode); ?></td><td class="num"><?php echo acc_val($c->akhir, $mode); ?></td></tr>
    <?php } if ( ! $d['kontak']) { ?>
        <tr><td colspan="7" class="text-center text-muted">Tidak ada transaksi <?php echo $AR ? 'piutang' : 'hutang'; ?> pada periode ini.</td></tr>
    <?php } ?>
    </tbody>
    <tfoot>
        <tr class="row-grand"><td colspan="4">TOTAL SEMUA <?php echo $AR ? 'PELANGGAN' : 'PEMASOK'; ?> (saldo awal <?php echo $mode === 'excel' ? round($T->awal, 2) : nf_acc($T->awal); ?>)</td>
            <td class="num"><?php echo acc_val($T->tambah, $mode); ?></td><td class="num"><?php echo acc_val($T->kurang, $mode); ?></td><td class="num"><?php echo acc_val($T->akhir, $mode); ?></td></tr>
    </tfoot>
</table>
<?php if ($d['kontrol']) { $kon = 0; foreach ($d['kontrol'] as $x) $kon += $x->saldo; $sel = round($kon - $T->akhir, 2); ?>
<h6 class="mt-4 mb-2" style="font-weight:700">Rekonsiliasi dengan akun kontrol per <?php echo $tgl($f['sampai']); ?></h6>
<table class="table table-sm" style="max-width:720px">
    <tbody>
        <?php foreach ($d['kontrol'] as $x) { ?>
        <tr><td>Saldo akun <?php echo html_escape($x->kode . ' ' . $x->nama); ?> (Buku Besar)</td><td class="num"><?php echo acc_val($x->saldo, $mode); ?></td></tr>
        <?php } ?>
        <tr><td>Total sisa faktur (buku pembantu di atas)</td><td class="num"><?php echo acc_val($T->akhir, $mode); ?></td></tr>
        <tr class="row-total"><td>Selisih</td><td class="num"><?php echo acc_val($sel, $mode); ?></td></tr>
    </tbody>
</table>
<?php if ($mode === 'screen') { ?>
<p class="small text-muted mb-0"><?php echo abs($sel) < 1
    ? '<i class="fas fa-check-circle text-success"></i> Buku pembantu cocok dengan akun kontrol.'
    : '<i class="fas fa-info-circle text-warning"></i> Ada selisih. Umumnya karena saldo awal ' . ($AR ? 'piutang' : 'hutang') . ' atau koreksi dicatat lewat Saldo Awal / Jurnal Umum langsung ke akun kontrol, bukan lewat faktur. Telusuri di Buku Besar akun tersebut.'; ?></p>
<?php } } ?>
