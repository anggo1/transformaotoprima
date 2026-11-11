<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('warehouse/_flash');
$L = Mod_lapgudang::$LOKASI;
?>
<?php if ( ! $A) { ?>
<div class="wh-steps mb-3">
    <div class="wh-steps__item"><b>1</b><span><strong>Unduh template</strong>Atau unduh seluruh master barang untuk diedit massal</span></div>
    <div class="wh-steps__item"><b>2</b><span><strong>Isi / ubah di Excel</strong>Sel kosong = tidak diubah. Judul kolom tidak boleh dihapus</span></div>
    <div class="wh-steps__item"><b>3</b><span><strong>Unggah & pratinjau</strong>Sistem menandai BARU, UPDATE, SAMA, ERROR</span></div>
    <div class="wh-steps__item"><b>4</b><span><strong>Konfirmasi</strong>Disimpan dalam satu transaksi; perubahan harga masuk log harga</span></div>
</div>
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-file-download mr-1"></i> 1 · Template & ekspor</h3></div>
            <div class="card-body">
                <form class="form-inline mb-3" method="get" action="<?php echo site_url('ImportBarang/template'); ?>" style="gap:.4rem">
                    <label class="mr-1">Harga & rak gudang</label>
                    <select name="lok" class="form-control form-control-sm"><?php foreach ($L as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $lok === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select>
                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-file-excel"></i> Template kosong</button>
                    <button class="btn btn-sm btn-outline-secondary" name="semua" value="1"><i class="fas fa-database"></i> Seluruh master barang</button>
                </form>
                <table class="table table-sm small mb-0">
                    <thead><tr><th>Kolom</th><th>Keterangan</th></tr></thead>
                    <tbody>
                    <?php foreach (Mod_impor::$KOLOM as $k => $x) { ?><tr><td><code><?php echo $k; ?></code><?php echo $x[1] ? ' <span class="text-danger">*</span>' : ''; ?></td><td><?php echo $x[0]; ?></td></tr><?php } ?>
                    </tbody>
                </table>
                <p class="small text-muted mt-2 mb-0"><span class="text-danger">*</span> wajib untuk barang baru. Kolom harga, diskon, rak & stok awal berlaku untuk gudang yang dipilih saat unggah. Bila <code>hrg_net</code> kosong, dihitung <i>price list × (1 − diskon%)</i>. Judul kolom lain yang umum (mis. "Part Number", "Description", "Harga", "Qty") juga dikenali.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <?php if ($bisa) { ?>
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-file-upload mr-1"></i> 2 · Unggah file</h3></div>
            <form class="card-body" method="post" enctype="multipart/form-data" action="<?php echo site_url('ImportBarang/unggah'); ?>">
                <div class="form-row">
                    <div class="form-group col-md-4"><label>Gudang</label><select name="lok" class="form-control form-control-sm"><?php foreach ($L as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $lok === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
                    <div class="form-group col-md-8"><label>File (.xlsx / .csv, maks. 8 MB)</label><input type="file" name="file" accept=".xlsx,.csv,.txt" class="form-control-file" required></div>
                </div>
                <div class="form-group mb-2"><label>Mode impor</label>
                    <?php foreach (Mod_impor::$MODE as $k => $v) { ?>
                    <div class="custom-control custom-radio"><input type="radio" class="custom-control-input" id="m-<?php echo $k; ?>" name="mode" value="<?php echo $k; ?>" <?php echo $k === 'SEMUA' ? 'checked' : ''; ?>><label class="custom-control-label" for="m-<?php echo $k; ?>"><?php echo $v; ?></label></div>
                    <?php } ?>
                </div>
                <button class="btn btn-primary"><i class="fas fa-search mr-1"></i> Baca & pratinjau</button>
                <span class="small text-muted ml-2">Belum ada data yang disimpan sampai Anda menekan <b>Proses impor</b>.</span>
            </form>
        </div>
        <?php } ?>
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-1"></i> Riwayat impor</h3></div>
            <div class="card-body p-0"><table class="table table-sm mb-0">
                <thead><tr><th>Dokumen</th><th>Waktu</th><th>Gudang / mode</th><th class="num">Baru</th><th class="num">Diubah</th><th class="num">Harga</th><th>Oleh</th></tr></thead>
                <tbody>
                <?php foreach ($riwayat as $r) { ?><tr><td><b><?php echo html_escape($r->no_dok); ?></b><div class="small text-muted text-truncate" style="max-width:180px"><?php echo html_escape($r->file); ?></div></td><td><?php echo date('d/m/y H:i', strtotime($r->tgl)); ?></td><td><?php echo $L[$r->lokasi] ?? $r->lokasi; ?> · <?php echo html_escape($r->mode); ?></td><td class="num"><?php echo nf($r->baru); ?></td><td class="num"><?php echo nf($r->diubah); ?></td><td class="num"><?php echo nf($r->harga); ?></td><td><?php echo html_escape($r->user); ?></td></tr><?php } ?>
                <?php if ( ! $riwayat) { ?><tr><td colspan="7" class="text-center text-muted py-3">Belum ada impor.</td></tr><?php } ?>
                </tbody></table></div>
        </div>
        <div class="card"><div class="card-body small">
            <b>Rekomendasi:</b> untuk perubahan harga tahunan dari principal, unduh <i>Seluruh master barang</i>, perbarui kolom <code>price_list</code>/<code>diskon</code>, lalu impor dengan mode <b>Hanya perbarui harga</b>. Setiap perubahan tercatat di log harga dan terlihat di laporan <a href="<?php echo site_url('HargaRata'); ?>">Harga Rata-rata & Riwayat Harga</a>. Untuk koreksi stok barang lama gunakan <a href="<?php echo site_url('OpnameStok'); ?>">Stock Opname</a>, bukan impor.
        </div></div>
    </div>
</div>

<?php } else { $R = $A['ringkas']; $tampil = in_array($f, array('BARU', 'UPDATE', 'SAMA', 'ERROR'), true) ? $f : ''; ?>
<div class="card">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center" style="gap:1rem">
        <div>
            <div class="small text-muted">Pratinjau impor · <?php echo html_escape($A['file']); ?> · Gudang <?php echo $L[$A['lok']]; ?> · <?php echo Mod_impor::$MODE[$A['mode']]; ?></div>
            <div class="small">Kolom dikenali: <?php foreach ($A['kolom'] as $k) echo '<code>' . $k . '</code> '; ?><?php if ($A['abaikan']) { ?><br><span class="text-warning">Kolom diabaikan: <?php echo html_escape(implode(', ', $A['abaikan'])); ?></span><?php } ?></div>
        </div>
        <div class="d-flex" style="gap:.4rem">
            <a href="<?php echo site_url('ImportBarang'); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
            <?php if ($R['ERROR']) { ?><a href="<?php echo site_url('ImportBarang/error/' . $tok); ?>" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-excel"></i> Unduh baris error</a><?php } ?>
            <?php if ($bisa && ($R['BARU'] || $R['UPDATE'])) { ?>
            <form method="post" action="<?php echo site_url('ImportBarang/proses/' . $tok); ?>" onsubmit="return confirm('Proses <?php echo $R['BARU']; ?> barang baru dan <?php echo $R['UPDATE']; ?> perubahan?')"><button class="btn btn-primary btn-sm"><i class="fas fa-check mr-1"></i> Proses impor</button></form>
            <?php } ?>
        </div>
    </div>
</div>
<div class="acc-kpi">
    <?php foreach (array('BARU' => 'Barang baru akan ditambahkan', 'UPDATE' => 'Barang akan diperbarui', 'SAMA' => 'Tidak berubah / dilewati', 'ERROR' => 'Bermasalah (tidak diproses)') as $k => $v) { ?>
    <a class="acc-kpi__item" href="<?php echo site_url('ImportBarang/index/' . $tok . ($tampil === $k ? '' : '?f=' . $k)); ?>" style="text-decoration:none;<?php echo $tampil === $k ? 'box-shadow:inset 0 0 0 2px var(--top-accent)' : ''; ?>">
        <div class="acc-kpi__label"><span class="imp-status imp-status--<?php echo $k; ?>"><?php echo $k; ?></span></div><div class="acc-kpi__value"><?php echo nf($R[$k]); ?></div><div class="acc-kpi__sub"><?php echo $v; ?></div></a>
    <?php } ?>
</div>
<div class="card">
    <div class="table-responsive" style="max-height:65vh">
    <table class="table table-sm table-hover mb-0">
        <thead class="thead-sticky"><tr><th>Baris</th><th>Status</th><th>No. part</th><th>Nama barang</th><th>Rak</th><th class="num">Price list</th><th class="num">Disk %</th><th class="num">Harga net</th><th class="num">Stok awal</th><th>Perubahan / catatan</th></tr></thead>
        <tbody>
        <?php $n = 0; foreach ($A['rows'] as $r) { if ($tampil !== '' && $r['status'] !== $tampil) continue; if (++$n > 1500) break; $v = $r['v']; $U = $r['ubah']; ?>
            <tr>
                <td class="text-muted"><?php echo $r['baris']; ?></td>
                <td><span class="imp-status imp-status--<?php echo $r['status']; ?>"><?php echo $r['status']; ?></span></td>
                <td><b><?php echo html_escape((string) $v['no_part']); ?></b></td>
                <td class="<?php echo isset($U['nama_part']) ? 'imp-ubah' : ''; ?>"><?php echo html_escape((string) ($v['nama_part'] ?? '')); ?></td>
                <td class="<?php echo isset($U['lok_rak']) ? 'imp-ubah' : ''; ?>"><?php echo html_escape((string) ($v['lok_rak'] ?? '')); ?></td>
                <td class="num <?php echo isset($U['price_list']) ? 'imp-ubah' : ''; ?>"><?php echo $v['price_list'] === null ? '' : nf($v['price_list']); ?></td>
                <td class="num <?php echo isset($U['diskon']) ? 'imp-ubah' : ''; ?>"><?php echo $v['diskon'] === null ? '' : nf($v['diskon'], 1); ?></td>
                <td class="num <?php echo isset($U['hrg_net']) ? 'imp-ubah' : ''; ?>"><?php echo $v['hrg_net'] === null ? '' : nf($v['hrg_net']); ?></td>
                <td class="num"><?php echo $v['stok_awal'] === null ? '' : nf_qty($v['stok_awal']); ?></td>
                <td class="small">
                    <?php foreach ($r['err'] as $e) echo '<div class="text-danger"><i class="fas fa-times-circle"></i> ' . html_escape($e) . '</div>'; ?>
                    <?php foreach ($U as $k => $x) { $pct = in_array($k, array('price_list', 'hrg_net'), true) && (float) $x[0] > 0 ? round(((float) $x[1] - (float) $x[0]) / (float) $x[0] * 100, 1) : null; ?>
                        <div><b><?php echo $k; ?></b>: <span class="text-muted"><?php echo html_escape((string) $x[0]); ?></span> → <?php echo html_escape(is_float($x[1]) ? nf($x[1], 0) : (string) $x[1]); ?><?php echo $pct !== null ? ' <span class="' . ($pct > 0 ? 'text-danger' : 'text-success') . '">(' . ($pct > 0 ? '+' : '') . $pct . '%)</span>' : ''; ?></div>
                    <?php } ?>
                    <?php foreach ($r['info'] as $e) echo '<div class="text-muted"><i class="fas fa-info-circle"></i> ' . html_escape($e) . '</div>'; ?>
                </td>
            </tr>
        <?php } if ($n > 1500) { ?><tr><td colspan="10" class="text-center text-muted">… hanya 1.500 baris pertama yang ditampilkan; seluruh baris tetap diproses.</td></tr><?php } ?>
        </tbody>
    </table>
    </div>
</div>
<?php } ?>
