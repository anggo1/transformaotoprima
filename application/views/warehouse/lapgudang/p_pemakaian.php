<?php defined('BASEPATH') OR exit('No direct script access allowed');
$T = $d['total'];
$tabel = function ($judul, $label, $rows) use ($mode, $T) {
    echo '<h6 class="mt-3 mb-2" style="font-weight:700">' . $judul . '</h6><table class="table table-sm"><thead><tr><th>' . $label . '</th><th class="num">Baris</th><th class="num">Qty</th><th class="num">Nilai</th><th style="width:30%">Porsi</th></tr></thead><tbody>';
    foreach ($rows as $k => $x) {
        $p = $T->nilai > 0 ? $x['nilai'] / $T->nilai * 100 : 0;
        echo '<tr><td>' . html_escape($k) . '</td><td class="num">' . (isset($x['n']) ? nf($x['n']) : '') . '</td><td class="num">' . nf_qty($x['qty'], $mode) . '</td><td class="num">' . acc_val($x['nilai'], $mode) . '</td><td>'
            . ($mode === 'excel' ? round($p, 2) : '<div class="wh-bar"><span style="width:' . min(100, round($p, 1)) . '%"></span><em>' . nf($p, 1) . '%</em></div>') . '</td></tr>';
    }
    if ( ! $rows) echo '<tr><td colspan="5" class="text-center text-muted">Tidak ada data.</td></tr>';
    echo '</tbody></table>';
};
if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Total nilai pengeluaran</div><div class="acc-kpi__value"><?php echo nf_acc($T->nilai); ?></div><div class="acc-kpi__sub"><?php echo nf($T->baris); ?> baris transaksi</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Total qty</div><div class="acc-kpi__value"><?php echo nf_qty($T->qty, $mode); ?></div><div class="acc-kpi__sub">semua satuan</div></div>
</div>
<?php }
$tabel('Per jenis pengeluaran', 'Jenis', $d['jenis']);
$tabel('Per kategori / divisi barang', 'Kategori', $d['kategori']);
$tabel('Per tujuan / pelanggan / WO (25 terbesar)', 'Tujuan', $d['tujuan']);
?>
<h6 class="mt-3 mb-2" style="font-weight:700">30 barang dengan nilai pemakaian terbesar</h6>
<table class="table table-sm"><thead><tr><th>No. Part</th><th>Nama</th><th>Sat</th><th class="num">Qty</th><th class="num">Nilai</th></tr></thead><tbody>
<?php foreach ($d['part'] as $k => $x) { ?><tr><td><?php echo html_escape($k); ?></td><td><?php echo html_escape($x['nama']); ?></td><td><?php echo html_escape($x['satuan']); ?></td><td class="num"><?php echo nf_qty($x['qty'], $mode); ?></td><td class="num"><?php echo acc_val($x['nilai'], $mode); ?></td></tr><?php } ?>
</tbody></table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">Nilai memakai harga pada dokumen keluar; bila kosong memakai harga net barang di gudang tersebut.</p><?php } ?>
