<?php defined('BASEPATH') OR exit('No direct script access allowed'); $T = $d['total']; $tg = function ($t) use ($mode) { return $t ? ($mode === 'excel' ? $t : tgl_singkat($t)) : '-'; }; ?>
<?php if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Barang lambat</div><div class="acc-kpi__value"><?php echo nf($T->n); ?></div><div class="acc-kpi__sub">nilai <?php echo nf_acc($T->nilai); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Tidak pernah keluar</div><div class="acc-kpi__value text-danger"><?php echo nf($T->mati); ?></div><div class="acc-kpi__sub">nilai <?php echo nf_acc($T->nilai_mati); ?></div></div>
</div>
<?php } ?>
<table class="table table-sm">
    <thead><tr><th>No. Part</th><th>Nama Barang</th><th>Kategori</th><th>Sat</th><th class="num">Stok</th><th class="num">Harga</th><th class="num">Nilai</th><th>Terakhir Masuk</th><th>Terakhir Keluar</th><th class="num">Hari</th><th>Kelas</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $r) { ?>
        <tr><td><?php echo $mode === 'screen' ? '<a href="' . site_url('LaporanGudang/kartu_stok?part=' . rawurlencode($r->no_part) . '&lok=' . $f['lok'] . '&dari=' . date('Y-01-01', strtotime('-1 year'))) . '">' . html_escape($r->no_part) . '</a>' : html_escape($r->no_part); ?></td>
            <td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->kategori); ?></td><td><?php echo html_escape($r->satuan); ?></td>
            <td class="num"><?php echo nf_qty($r->stok, $mode); ?></td><td class="num"><?php echo acc_val($r->harga, $mode); ?></td><td class="num"><b><?php echo acc_val($r->nilai, $mode); ?></b></td>
            <td><?php echo $tg($r->terakhir_masuk); ?></td><td><?php echo $tg($r->terakhir_keluar); ?></td><td class="num"><?php echo $r->hari === null ? '-' : nf($r->hari); ?></td>
            <td><?php echo $mode === 'excel' ? $r->kelas : '<span class="acc-status acc-status--' . ($r->kelas === 'LAMBAT' ? 'SEBAGIAN' : 'BATAL') . '">' . $r->kelas . '</span>'; ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="11" class="text-center text-muted">Tidak ada barang lambat.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="6">TOTAL</td><td class="num"><?php echo acc_val($T->nilai, $mode); ?></td><td colspan="4"></td></tr></tfoot>
</table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">"Tidak pernah keluar" = tidak ada catatan pengeluaran di sistem untuk gudang ini. Pertimbangkan retur ke pemasok, transfer ke gudang lain, promosi, atau penghapusan (write-off) bersama bagian Akuntansi.</p><?php } ?>
