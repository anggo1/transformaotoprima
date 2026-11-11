<?php defined('BASEPATH') OR exit('No direct script access allowed'); $T = $d['total']; ?>
<table class="table table-sm">
    <thead><tr><th>No. Part</th><th>Nama Barang</th><th>Sat</th><th class="num">Saldo Awal</th><th class="num">Masuk</th><th class="num">Keluar</th><th class="num">Saldo Akhir</th><th class="num">Harga Net</th><th class="num">Nilai Akhir</th></tr></thead>
    <tbody>
    <?php $kat = null; foreach ($d['rows'] as $r) { if ($r->kategori !== $kat) { $kat = $r->kategori; ?>
        <tr class="row-group"><td colspan="9"><?php echo html_escape($kat); ?></td></tr>
    <?php } ?>
        <tr><td><?php echo $mode === 'screen' ? '<a href="' . site_url('LaporanGudang/kartu_stok?part=' . rawurlencode($r->no_part) . '&lok=' . $f['lok'] . '&dari=' . $f['dari'] . '&sampai=' . $f['sampai']) . '">' . html_escape($r->no_part) . '</a>' : html_escape($r->no_part); ?></td>
            <td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->satuan); ?></td>
            <td class="num"><?php echo nf_qty($r->awal, $mode); ?></td><td class="num"><?php echo nf_qty($r->masuk, $mode); ?></td><td class="num"><?php echo nf_qty($r->keluar, $mode); ?></td>
            <td class="num"><b><?php echo nf_qty($r->akhir, $mode); ?></b></td><td class="num"><?php echo acc_val($r->harga, $mode); ?></td><td class="num"><?php echo acc_val($r->nilai, $mode); ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="9" class="text-center text-muted">Tidak ada mutasi pada periode ini.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="3">TOTAL (<?php echo nf($T->n); ?> barang)</td><td class="num"><?php echo nf_qty($T->awal, $mode); ?></td><td class="num"><?php echo nf_qty($T->masuk, $mode); ?></td><td class="num"><?php echo nf_qty($T->keluar, $mode); ?></td><td class="num"><?php echo nf_qty($T->akhir, $mode); ?></td><td></td><td class="num"><?php echo acc_val($T->nilai, $mode); ?></td></tr></tfoot>
</table>
<?php if ($mode !== 'excel' && $T->tanpa_master) { ?><p class="small text-warning mb-0"><i class="fas fa-exclamation-triangle"></i> <?php echo $T->tanpa_master; ?> nomor part di transaksi tidak ada di master barang (tidak ditampilkan). Lihat Pemeriksaan Data Gudang.</p><?php } ?>
