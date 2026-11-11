<?php defined('BASEPATH') OR exit('No direct script access allowed');
$L = Mod_lapgudang::$LOKASI;
?>
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i> 1 · Pilih barang</h3></div>
            <div class="card-body">
                <ul class="nav nav-pills nav-sm mb-3" role="tablist" style="gap:.3rem">
                    <li class="nav-item"><a class="nav-link btn-sm <?php echo $sumber === 'masuk' || $sumber === '' ? 'active' : ''; ?>" data-toggle="pill" href="#src-masuk">Part Masuk</a></li>
                    <li class="nav-item"><a class="nav-link btn-sm <?php echo $sumber === 'filter' ? 'active' : ''; ?>" data-toggle="pill" href="#src-filter">Filter / rak</a></li>
                    <li class="nav-item"><a class="nav-link btn-sm <?php echo $sumber === 'so' ? 'active' : ''; ?>" data-toggle="pill" href="#src-so">Stock Opname</a></li>
                </ul>
                <div class="tab-content">
                    <form class="tab-pane fade <?php echo $sumber === 'masuk' || $sumber === '' ? 'show active' : ''; ?>" id="src-masuk" method="get">
                        <input type="hidden" name="sumber" value="masuk">
                        <label>Dokumen penerimaan (jumlah label = qty diterima)</label>
                        <select name="dok" class="form-control form-control-sm mb-2" size="8">
                            <?php foreach ($masuk as $m) { ?><option value="<?php echo html_escape($m->id_masuk); ?>"><?php echo html_escape($m->id_masuk . ' · ' . tgl_singkat($m->tgl_masuk) . ' · ' . ($m->nama_supplier ?: '-') . ($m->no_po ? ' · ' . $m->no_po : '') . ' · ' . $m->lokasi); ?></option><?php } ?>
                        </select>
                        <button class="btn btn-sm btn-primary btn-block">Tampilkan barang</button>
                        <small class="text-muted">Rekomendasi: tempel label segera saat barang diterima, sebelum disimpan ke rak.</small>
                    </form>
                    <form class="tab-pane fade <?php echo $sumber === 'filter' ? 'show active' : ''; ?>" id="src-filter" method="get">
                        <input type="hidden" name="sumber" value="filter">
                        <div class="form-group mb-2"><label>Gudang</label><select name="lok" class="form-control form-control-sm"><?php foreach ($L as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $lok === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
                        <div class="form-group mb-2"><label>Kategori</label><select name="kategori" class="form-control form-control-sm"><option value="">— Semua —</option><?php foreach ($kategori as $k) { ?><option value="<?php echo html_escape($k->kode_kategori); ?>" <?php echo $this->input->get('kategori') === $k->kode_kategori ? 'selected' : ''; ?>><?php echo html_escape($k->kategori); ?></option><?php } ?></select></div>
                        <div class="form-group mb-2"><label>Awalan rak</label><input name="rak" class="form-control form-control-sm" value="<?php echo html_escape((string) $this->input->get('rak')); ?>" placeholder="mis. D01"></div>
                        <div class="form-group mb-2"><label>Cari no. part / nama</label><input name="q" class="form-control form-control-sm" value="<?php echo html_escape((string) $this->input->get('q')); ?>"></div>
                        <div class="custom-control custom-checkbox mb-2"><input type="checkbox" class="custom-control-input" id="lb-saldo" name="bersaldo" value="1" <?php echo $this->input->get('bersaldo') || $sumber !== 'filter' ? 'checked' : ''; ?>><label class="custom-control-label" for="lb-saldo">Hanya barang bersaldo</label></div>
                        <button class="btn btn-sm btn-primary btn-block">Tampilkan barang</button>
                    </form>
                    <form class="tab-pane fade <?php echo $sumber === 'so' ? 'show active' : ''; ?>" id="src-so" method="get">
                        <input type="hidden" name="sumber" value="so">
                        <label>Dokumen stock opname (label untuk seluruh barang dalam cakupan)</label>
                        <select name="dok" class="form-control form-control-sm mb-2" size="6"><?php foreach ($so as $s) { ?><option value="<?php echo $s->id; ?>"><?php echo html_escape($s->no_so . ' · ' . tgl_singkat($s->tgl) . ' · ' . $L[$s->lokasi]); ?></option><?php } ?></select>
                        <button class="btn btn-sm btn-primary btn-block">Tampilkan barang</button>
                        <small class="text-muted">Cocok sebelum opname: barang yang labelnya hilang dapat dicetak ulang sekaligus.</small>
                    </form>
                </div>
            </div>
        </div>
        <div class="card"><div class="card-body small">
            <b><i class="fas fa-qrcode mr-1"></i> Tentang label QR</b>
            <ul class="pl-3 mb-0 mt-1">
                <li>Isi QR = <b>no. part</b> apa adanya, dapat dibaca aplikasi kamera apa pun, scanner USB/Bluetooth, dan menu <a href="<?php echo site_url('LabelBarang/scan'); ?>">Scan Barang</a> / <a href="<?php echo site_url('OpnameStok'); ?>">Stock Opname</a>.</li>
                <li>QR dibuat langsung di browser (tanpa file gambar), sehingga no. part yang berisi "/" atau spasi tetap tercetak.</li>
                <li>Printer thermal: atur ukuran kertas di driver printer sama dengan template (mis. 50 × 30 mm), margin 0, skala 100%.</li>
                <li>Kertas stiker A4: isi "Mulai dari label ke-" untuk memakai sisa lembar stiker.</li>
            </ul>
        </div></div>
    </div>

    <div class="col-xl-8">
        <form class="card" method="post" action="<?php echo site_url('LabelBarang/cetak'); ?>" target="_blank" id="lb-form">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between" style="gap:.5rem">
                <h3 class="card-title">2 · Atur & cetak <?php echo $judul_daftar ? '<small class="text-muted">— ' . html_escape($judul_daftar) . '</small>' : ''; ?></h3>
                <span class="small text-muted" id="lb-total"></span>
            </div>
            <div class="card-body pb-2">
                <input type="hidden" name="lok" value="<?php echo $lok; ?>">
                <div class="form-row">
                    <div class="form-group col-md-6"><label>Template</label><select name="tpl" class="form-control form-control-sm"><?php foreach (LabelBarang::$TEMPLATE as $k => $t) { ?><option value="<?php echo $k; ?>" <?php echo $tpl_default === $k ? 'selected' : ''; ?>><?php echo $t[0]; ?></option><?php } ?></select></div>
                    <div class="form-group col-md-3"><label>Mulai dari label ke-</label><input type="number" min="1" max="40" name="mulai" value="1" class="form-control form-control-sm"></div>
                    <div class="form-group col-md-3 d-flex align-items-end"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="lb-simpan" name="simpan_tpl" value="1"><label class="custom-control-label" for="lb-simpan">Jadikan template bawaan</label></div></div>
                </div>
                <div class="lbl-opsi mb-2">
                    <?php foreach (array('nama' => 'Nama barang', 'rak' => 'Kode rak', 'gudang' => 'Gudang', 'kategori' => 'Kategori', 'harga' => 'Harga jual (price list)', 'tanggal' => 'Tanggal cetak', 'perusahaan' => 'Nama perusahaan') as $k => $v) { ?>
                    <label><input type="checkbox" name="opsi[]" value="<?php echo $k; ?>" <?php echo in_array($k, array('nama', 'rak', 'gudang'), true) ? 'checked' : ''; ?>> <?php echo $v; ?></label>
                    <?php } ?>
                </div>
            </div>
            <div class="table-responsive" style="max-height:55vh">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-sticky"><tr><th style="width:34px"><input type="checkbox" id="lb-semua" checked></th><th>No. part</th><th>Nama barang</th><th>Rak</th><th class="num">Stok</th><th style="width:90px" class="num">Jumlah label</th></tr></thead>
                    <tbody>
                    <?php foreach ($rows as $r) { $b = $r[0]; ?>
                        <tr><td><input type="checkbox" class="lb-cek" checked></td><td><b><?php echo html_escape($b->no_part); ?></b></td><td><?php echo html_escape($b->nama_part); ?></td><td><?php echo html_escape(trim((string) $b->{'lok_' . $lok})); ?></td><td class="num"><?php echo nf_qty($b->{'stok_' . $lok}); ?></td>
                            <td><input type="number" min="0" max="500" class="form-control form-control-sm text-right lb-n" name="part[<?php echo html_escape($b->no_part); ?>]" value="<?php echo (int) $r[1]; ?>"></td></tr>
                    <?php } if ( ! $rows) { ?><tr><td colspan="6" class="text-center text-muted py-5"><i class="fas fa-tags fa-2x d-block mb-2"></i>Pilih sumber barang di panel kiri.</td></tr><?php } ?>
                    </tbody>
                </table>
            </div>
            <?php if ($rows) { ?>
            <div class="card-footer d-flex flex-wrap justify-content-between align-items-center" style="gap:.5rem">
                <div class="btn-group btn-group-sm"><button type="button" class="btn btn-outline-secondary" data-isi="1">Semua = 1</button><button type="button" class="btn btn-outline-secondary" data-isi="stok">= stok</button></div>
                <button class="btn btn-primary"><i class="fas fa-print mr-1"></i> Pratinjau & cetak</button>
            </div>
            <?php } ?>
        </form>
    </div>
</div>
<script>
(function () {
    var f = document.getElementById('lb-form'); if (!f) return;
    function hitung() {
        var n = 0, b = 0;
        f.querySelectorAll('tbody tr').forEach(function (tr) { var c = tr.querySelector('.lb-cek'), i = tr.querySelector('.lb-n'); if (!c || !i) return; i.disabled = !c.checked; if (c.checked) { n += +i.value || 0; b++; } });
        document.getElementById('lb-total').textContent = b + ' barang · ' + n + ' label';
    }
    f.addEventListener('input', hitung); f.addEventListener('change', hitung);
    var s = document.getElementById('lb-semua'); if (s) s.addEventListener('change', function () { f.querySelectorAll('.lb-cek').forEach(function (c) { c.checked = s.checked; }); hitung(); });
    f.querySelectorAll('[data-isi]').forEach(function (btn) { btn.addEventListener('click', function () {
        f.querySelectorAll('tbody tr').forEach(function (tr) { var i = tr.querySelector('.lb-n'); if (!i) return; i.value = btn.getAttribute('data-isi') === '1' ? 1 : Math.max(0, Math.round(parseFloat(tr.children[4].textContent.replace(/,/g, '')) || 0)); });
        hitung(); }); });
    hitung();
})();
</script>
