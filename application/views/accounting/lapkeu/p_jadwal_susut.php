<?php defined('BASEPATH') OR exit('No direct script access allowed'); $T = $d['total']; ?>
<table class="table table-sm" style="font-size:12px">
    <thead><tr><th>Kode</th><th>Nama Aset</th><th>Metode</th><th class="num">Harga Perolehan</th><th class="num">Akum. Awal</th>
        <?php for ($b = 1; $b <= 12; $b++) { ?><th class="num"><?php echo bulan_singkat($b); ?></th><?php } ?>
        <th class="num">Susut Tahun Ini</th><th class="num">Akum. Akhir</th><th class="num">Nilai Buku</th></tr></thead>
    <tbody>
    <?php $grup = null; foreach ($d['rows'] as $r) { if ($r->kelompok !== $grup) { $grup = $r->kelompok; ?>
        <tr class="row-group"><td colspan="20"><?php echo html_escape($grup ?: 'Tanpa kelompok'); ?></td></tr>
    <?php } ?>
        <tr><td><?php echo html_escape($r->kode); ?></td><td><?php echo html_escape($r->nama); ?><?php if ($r->status !== 'AKTIF' && $mode === 'screen') { ?> <span class="acc-status acc-status--<?php echo $r->status; ?>"><?php echo $r->status; ?></span><?php } ?></td>
            <td><?php echo str_replace('_', ' ', $r->metode); ?> <?php echo $r->umur ? '(' . $r->umur . ' bln)' : ''; ?></td>
            <td class="num"><?php echo acc_val($r->perolehan, $mode); ?></td><td class="num"><?php echo acc_val($r->awal, $mode); ?></td>
            <?php foreach ($r->bulan as $x) { ?><td class="num"<?php echo $x->j === 'P' && $mode !== 'excel' ? ' style="color:#8a94a6;font-style:italic" title="Proyeksi (belum diposting)"' : ''; ?>><?php echo acc_val($x->v, $mode); ?></td><?php } ?>
            <td class="num"><?php echo acc_val($r->susut, $mode); ?></td><td class="num"><?php echo acc_val($r->akhir, $mode); ?></td><td class="num"><?php echo acc_val($r->nb, $mode); ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="20" class="text-center text-muted">Belum ada aset tetap.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="3">TOTAL</td><td class="num"><?php echo acc_val($T->perolehan, $mode); ?></td><td class="num"><?php echo acc_val($T->awal, $mode); ?></td>
        <?php foreach ($d['bulan'] as $v) { ?><td class="num"><?php echo acc_val($v, $mode); ?></td><?php } ?>
        <td class="num"><?php echo acc_val($T->susut, $mode); ?></td><td class="num"><?php echo acc_val($T->akhir, $mode); ?></td><td class="num"><?php echo acc_val($T->nb, $mode); ?></td></tr></tfoot>
</table>
<?php if ($d['proyeksi'] && $mode !== 'excel') { ?><p class="small text-muted mt-2 mb-0"><em>Angka miring abu-abu</em> = proyeksi (belum diposting). Posting lewat menu Aset Tetap → Proses Penyusutan.</p><?php } ?>
