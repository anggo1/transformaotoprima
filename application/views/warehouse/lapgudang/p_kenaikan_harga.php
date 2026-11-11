<?php defined('BASEPATH') OR exit('No direct script access allowed'); $t = $d['tahun'];
$pc = function ($v) use ($mode) { if ($v === null) return '-'; $s = ($v > 0 ? '+' : '') . nf($v, 1) . '%'; return $mode === 'excel' ? round($v, 2) : '<span class="' . ($v > 0.5 ? 'text-danger' : ($v < -0.5 ? 'text-success' : 'text-muted')) . '">' . $s . '</span>'; }; ?>
<?php if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Kenaikan harga beli <?php echo $t; ?></div><div class="acc-kpi__value <?php echo $d['inflasi'] > 0 ? 'text-danger' : 'text-success'; ?>"><?php echo $d['inflasi'] === null ? '-' : $pc($d['inflasi']); ?></div><div class="acc-kpi__sub">tertimbang qty <?php echo $t; ?>, dibanding <?php echo $t - 1; ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Dampak biaya</div><div class="acc-kpi__value"><?php echo nf_acc($d['dampak']); ?></div><div class="acc-kpi__sub">tambahan biaya akibat perubahan harga</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Barang naik / turun</div><div class="acc-kpi__value"><span class="text-danger"><?php echo nf($d['naik']); ?></span> / <span class="text-success"><?php echo nf($d['turun']); ?></span></div><div class="acc-kpi__sub">dari <?php echo nf(count($d['rows'])); ?> barang dibeli di kedua tahun</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Barang baru dibeli <?php echo $t; ?></div><div class="acc-kpi__value"><?php echo nf($d['baru']); ?></div><div class="acc-kpi__sub">tidak dibeli di <?php echo $t - 1; ?></div></div>
</div>
<?php if ($mode === 'screen' && $d['inflasi'] !== null) { ?>
<div class="card acc-explain"><div class="acc-explain__body" style="display:block">
    <h6><i class="fas fa-lightbulb text-warning mr-1"></i> Rekomendasi</h6>
    <ul class="mb-0 small">
        <?php if ($d['inflasi'] > 3) { ?><li>Harga beli naik <?php echo nf($d['inflasi'], 1); ?>%. Tinjau <b>harga jual / tarif jasa</b> barang di daftar teratas agar margin tidak tergerus; perbarui price list via <a href="<?php echo site_url('ImportBarang'); ?>">Impor Barang</a> (mode hanya harga).</li><?php } ?>
        <li>Gunakan metode penilaian <b>rata-rata bergerak</b> agar kenaikan harga tahun ini tidak langsung menaikkan nilai seluruh stok lama (<a href="<?php echo site_url('LaporanGudang/harga_rata'); ?>">Harga rata-rata</a>).</li>
        <li>Untuk barang kelas A dengan kenaikan tinggi, negosiasikan kontrak harga tahunan atau beli lebih awal sebelum kenaikan price list principal (lihat <a href="<?php echo site_url('LaporanGudang/abc'); ?>">Analisis ABC</a>).</li>
        <li>Selisih minimum–maksimum yang lebar dalam satu tahun menandakan harga beli tidak konsisten antar pemasok — bandingkan di <a href="<?php echo site_url('LaporanGudang/penerimaan'); ?>">Penerimaan per Supplier</a>.</li>
        <li>Masukkan angka kenaikan ini ke <a href="<?php echo site_url('Anggaran'); ?>">anggaran</a> biaya suku cadang tahun berikutnya.</li>
    </ul>
</div></div>
<?php } } ?>
<table class="table table-sm">
    <thead><tr><th>No. Part</th><th>Nama Barang</th><th>Kategori</th><th class="num">Rata-rata <?php echo $t - 1; ?></th><th class="num">Rata-rata <?php echo $t; ?></th><th class="num">Perubahan</th><th class="num">Min <?php echo $t; ?></th><th class="num">Maks <?php echo $t; ?></th><th class="num">Qty <?php echo $t; ?></th><th class="num">Dampak biaya</th></tr></thead>
    <tbody>
    <?php $tot = 0; foreach ($d['rows'] as $r) { $tot += $r->dampak; ?>
        <tr><td><?php echo $mode === 'screen' ? '<a href="' . site_url('LaporanGudang/riwayat_harga?part=' . rawurlencode($r->no_part) . '&lok=' . $f['lok']) . '">' . html_escape($r->no_part) . '</a>' : html_escape($r->no_part); ?></td>
            <td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->kategori); ?></td>
            <td class="num"><?php echo acc_val($r->rata_a, $mode); ?></td><td class="num"><b><?php echo acc_val($r->rata_b, $mode); ?></b></td><td class="num"><?php echo $pc($r->pct); ?></td>
            <td class="num"><?php echo acc_val($r->min, $mode); ?></td><td class="num"><?php echo acc_val($r->max, $mode); ?></td><td class="num"><?php echo nf_qty($r->qty_b, $mode); ?></td><td class="num"><?php echo acc_val($r->dampak, $mode); ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="10" class="text-center text-muted">Tidak ada barang yang dibeli (dengan harga) di tahun <?php echo $t - 1; ?> dan <?php echo $t; ?>.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="9">TOTAL DAMPAK</td><td class="num"><?php echo acc_val($tot, $mode); ?></td></tr></tfoot>
</table>
<?php if ($mode !== 'excel' && $d['indeks']) { ?>
<h6 class="mt-3">Indeks harga beli per tahun</h6>
<table class="table table-sm" style="max-width:420px"><thead><tr><th>Tahun</th><th class="num">Indeks</th><th class="num">Perubahan</th></tr></thead><tbody>
<?php $s = null; foreach ($d['indeks'] as $y => $v) { ?><tr><td><?php echo $y; ?></td><td class="num"><?php echo nf($v, 1); ?></td><td class="num"><?php echo $s ? $pc(($v - $s) / $s * 100) : '-'; ?></td></tr><?php $s = $v; } ?>
</tbody></table>
<?php } ?>
