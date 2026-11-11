<?php defined('BASEPATH') OR exit('No direct script access allowed');
if ( ! $d) { ?>
<div class="text-center text-muted py-5"><i class="fas fa-search fa-2x mb-2 d-block"></i><?php echo $f['part'] === '' ? 'Pilih nomor part / nama barang di atas, lalu klik Tampilkan.' : 'No. part <b>' . html_escape($f['part']) . '</b> tidak ditemukan di master barang.'; ?></div>
<?php return; }
$b = $d->barang; $J = Mod_lapgudang::$JENIS;
$tgl = function ($t) use ($mode) { return $mode === 'excel' ? $t : tgl_singkat($t); };
?>
<table class="table table-sm mb-3" style="max-width:900px">
    <tbody>
        <tr><td class="text-muted" style="width:22%">Barang</td><td><b><?php echo html_escape($b->no_part); ?></b> — <?php echo html_escape($b->nama_part); ?></td>
            <td class="text-muted" style="width:18%">Satuan / Kategori</td><td><?php echo html_escape($b->satuan . ' / ' . $b->nama_kategori); ?></td></tr>
        <tr><td class="text-muted">Rak</td><td><?php echo html_escape($d->rak ?: '-'); ?></td><td class="text-muted">Stok minimum</td><td><?php echo nf_qty($b->stok_min, $mode); ?></td></tr>
        <tr><td class="text-muted">Stok sistem saat ini</td><td><b><?php echo nf_qty($d->stok_kini, $mode); ?></b><?php if ($d->setelah != 0 && $mode !== 'excel') { ?> <span class="small text-muted">(mutasi setelah periode: <?php echo nf_qty($d->setelah, $mode); ?>)</span><?php } ?></td>
            <td class="text-muted">Harga net</td><td><?php echo acc_val($d->harga, $mode); ?></td></tr>
    </tbody>
</table>
<table class="table table-sm">
    <thead><tr><th>Tanggal</th><th>Dokumen</th><th>Jenis</th><th>Keterangan</th><th>Gudang</th><th class="num">Masuk</th><th class="num">Keluar</th><th class="num">Saldo</th><th class="num">Harga</th></tr></thead>
    <tbody>
        <tr class="row-group"><td><?php echo $tgl($f['dari']); ?></td><td colspan="6">Saldo awal</td><td class="num"><?php echo nf_qty($d->awal, $mode); ?></td><td></td></tr>
        <?php foreach ($d->rows as $g) { ?>
        <tr><td><?php echo $tgl($g->tgl); ?></td><td><?php echo html_escape($g->ref); ?></td>
            <td><?php echo $mode === 'excel' ? $J[$g->jenis] : '<span class="wh-jenis wh-jenis--' . $g->jenis . '">' . $J[$g->jenis] . '</span>'; ?></td>
            <td><?php echo html_escape($g->ket); ?></td><td><?php echo $g->lok ? Mod_lapgudang::$LOKASI[$g->lok] : '-'; ?></td>
            <td class="num"><?php echo nf_qty($g->masuk, $mode); ?></td><td class="num"><?php echo nf_qty($g->keluar, $mode); ?></td>
            <td class="num"><b><?php echo nf_qty($g->saldo, $mode); ?></b></td><td class="num"><?php echo acc_val($g->harga, $mode); ?></td></tr>
        <?php } if ( ! $d->rows) { ?><tr><td colspan="9" class="text-center text-muted">Tidak ada mutasi pada periode ini.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="5">Saldo akhir (nilai <?php echo $mode === 'excel' ? round($d->nilai_akhir) : nf($d->nilai_akhir); ?>)</td>
        <td class="num"><?php echo nf_qty($d->masuk, $mode); ?></td><td class="num"><?php echo nf_qty($d->keluar, $mode); ?></td><td class="num"><?php echo nf_qty($d->akhir, $mode); ?></td><td></td></tr></tfoot>
</table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">Saldo dihitung mundur dari stok sistem saat ini, sehingga saldo akhir hari ini selalu sama dengan stok di Material &amp; Sparepart. Koreksi stok manual (tanpa dokumen) tercermin pada saldo awal.</p><?php } ?>
