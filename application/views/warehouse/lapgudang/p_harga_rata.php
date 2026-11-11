<?php defined('BASEPATH') OR exit('No direct script access allowed'); $T = $d['total']; $L = Mod_lapgudang::$LOKASI;
$pc = function ($v) use ($mode) { if ($v === null) return '-'; $t = ($v > 0 ? '+' : '') . nf($v, 1) . '%'; return $mode === 'excel' ? round($v, 2) : '<span class="' . ($v > 0.5 ? 'text-danger' : ($v < -0.5 ? 'text-success' : 'text-muted')) . '">' . $t . '</span>'; }; ?>
<?php if ($mode === 'screen') { $this->load->view('warehouse/_flash'); ?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Nilai stok @ harga net</div><div class="acc-kpi__value"><?php echo nf_acc($T->nilai_net); ?></div><div class="acc-kpi__sub"><?php echo nf($T->n); ?> barang</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Nilai stok @ rata-rata</div><div class="acc-kpi__value"><?php echo nf_acc($T->nilai_rata); ?></div><div class="acc-kpi__sub">selisih <?php echo nf_acc($T->nilai_net - $T->nilai_rata); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Harga net &gt; rata-rata</div><div class="acc-kpi__value text-danger"><?php echo nf($T->naik); ?></div><div class="acc-kpi__sub">harga sudah naik dari harga beli lama</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Metode penilaian aktif</div><div class="acc-kpi__value" style="font-size:18px"><?php echo $d['metode'] === 'RATA' ? 'Rata-rata bergerak' : 'Harga net master'; ?></div><div class="acc-kpi__sub">dipakai semua laporan gudang</div></div>
</div>
<div class="card no-print"><div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="gap:.8rem">
    <form method="post" action="<?php echo site_url('LaporanGudang/hitung_harga_rata'); ?>" class="d-flex align-items-center" style="gap:.5rem">
        <input type="hidden" name="lok" value="<?php echo html_escape($f['lok']); ?>">
        <button class="btn btn-sm btn-primary"><i class="fas fa-calculator mr-1"></i> Hitung ulang harga rata-rata</button>
        <span class="small text-muted">Terakhir: <?php echo $d['dihitung'] ? date('d/m/Y H:i', strtotime($d['dihitung'])) : 'belum pernah'; ?>. Jalankan setelah input penerimaan/periode tutup buku.</span>
    </form>
    <form method="post" action="<?php echo site_url('LaporanGudang/metode_nilai'); ?>" class="d-flex align-items-center" style="gap:.4rem">
        <label class="mb-0 small">Metode penilaian</label>
        <select name="metode" class="form-control form-control-sm" style="width:auto"><option value="NET" <?php echo $d['metode'] !== 'RATA' ? 'selected' : ''; ?>>Harga net master (terbaru)</option><option value="RATA" <?php echo $d['metode'] === 'RATA' ? 'selected' : ''; ?>>Rata-rata bergerak (disarankan)</option></select>
        <button class="btn btn-sm btn-outline-primary">Simpan</button>
    </form>
</div></div>
<?php } ?>
<table class="table table-sm">
    <thead><tr><th>No. Part</th><th>Nama Barang</th><th>Kategori</th><th class="num">Stok</th><th class="num">Harga net</th><th class="num">Rata-rata</th><th class="num">Net vs rata</th><th class="num">Beli terakhir</th><th>Tgl beli</th><th class="num">x beli</th><th class="num">Nilai @net</th><th class="num">Nilai @rata</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $r) { ?>
        <tr><td><?php echo $mode === 'screen' ? '<a href="' . site_url('LaporanGudang/riwayat_harga?part=' . rawurlencode($r->no_part) . '&lok=' . $f['lok']) . '">' . html_escape($r->no_part) . '</a>' : html_escape($r->no_part); ?></td>
            <td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->kategori); ?></td><td class="num"><?php echo nf_qty($r->stok, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->net, $mode); ?></td><td class="num"><b><?php echo acc_val($r->rata, $mode); ?></b></td><td class="num"><?php echo $pc($r->selisih); ?></td>
            <td class="num"><?php echo acc_val($r->terakhir, $mode); ?></td><td><?php echo $r->tgl_terakhir ? ($mode === 'excel' ? $r->tgl_terakhir : tgl_singkat($r->tgl_terakhir)) : '-'; ?></td><td class="num"><?php echo nf($r->n); ?></td>
            <td class="num"><?php echo acc_val($r->nilai_net, $mode); ?></td><td class="num"><?php echo acc_val($r->nilai_rata, $mode); ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="12" class="text-center text-muted">Belum ada data harga rata-rata. Klik <b>Hitung ulang harga rata-rata</b>.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-grand"><td colspan="10">TOTAL</td><td class="num"><?php echo acc_val($T->nilai_net, $mode); ?></td><td class="num"><?php echo acc_val($T->nilai_rata, $mode); ?></td></tr></tfoot>
</table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">Urutan: selisih nilai terbesar di atas. Bila harga net jauh di atas rata-rata, stok lama dibeli lebih murah — menilai dengan harga net membuat nilai persediaan & HPP lebih tinggi dari biaya sebenarnya (sesuai PSAK 14, persediaan diukur pada biaya; rata-rata tertimbang adalah metode yang diizinkan).</p><?php } ?>
