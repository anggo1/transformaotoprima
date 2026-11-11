<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('warehouse/_flash');
$R = $h->ringkas; $L = Mod_lapgudang::$LOKASI;
$tahap = array('DRAFT', 'HITUNG', 'REVIEW', 'DISETUJUI');
$idx = array_search($h->status, $tahap, true);
$buta = $h->buta === 'Y' && $h->status === 'HITUNG';
$input = in_array($h->status, array('HITUNG', 'REVIEW'), true) && $bisa_ubah;
$url = site_url('OpnameStok/lihat/' . $h->id);
?>
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-start" style="gap:1rem">
            <div>
                <div class="small text-muted">Gudang <?php echo $L[$h->lokasi]; ?> · <?php echo tgl_id($h->tgl); ?> · <?php echo html_escape(Mod_opname::$CAKUPAN[$h->cakupan]); ?><?php echo $h->filter !== '' && $h->cakupan !== 'PILIH' ? ' (' . html_escape($h->filter) . ')' : ''; ?></div>
                <h4 class="mb-1"><?php echo html_escape($h->no_so); ?> <span class="badge badge-light border"><?php echo Mod_opname::$STATUS[$h->status]; ?></span></h4>
                <div class="small text-muted">Dibuat <?php echo html_escape($h->dibuat_oleh); ?><?php echo $h->mulai ? ' · snapshot ' . date('d/m/Y H:i', strtotime($h->mulai)) : ''; ?><?php echo $h->disetujui_oleh ? ' · disetujui ' . html_escape($h->disetujui_oleh) . ' ' . date('d/m/Y H:i', strtotime($h->tgl_posting)) : ''; ?><?php echo $h->no_ref_jurnal ? ' · jurnal <b>' . html_escape($h->no_ref_jurnal) . '</b>' : ''; ?></div>
                <?php if ($h->catatan !== '') { ?><div class="small mt-1"><i class="fas fa-sticky-note text-muted"></i> <?php echo html_escape($h->catatan); ?></div><?php } ?>
            </div>
            <div class="d-flex flex-wrap" style="gap:.4rem">
                <a class="btn btn-sm btn-outline-secondary" href="<?php echo site_url('OpnameStok'); ?>"><i class="fas fa-arrow-left"></i> Daftar</a>
                <?php if ($h->status !== 'DRAFT' && $h->status !== 'BATAL') { ?>
                <a class="btn btn-sm btn-outline-secondary" target="_blank" href="<?php echo site_url('OpnameStok/cetak/' . $h->id . '/lembar'); ?>"><i class="fas fa-print"></i> Lembar hitung (QR)</a>
                <a class="btn btn-sm btn-outline-secondary" href="<?php echo site_url('OpnameStok/ekspor/' . $h->id); ?>"><i class="fas fa-file-excel"></i> Lembar Excel</a>
                <?php } if (in_array($h->status, array('REVIEW', 'DISETUJUI'), true)) { ?>
                <a class="btn btn-sm btn-outline-primary" target="_blank" href="<?php echo site_url('OpnameStok/cetak/' . $h->id . '/hasil'); ?>"><i class="fas fa-file-signature"></i> Berita acara</a>
                <?php } ?>
            </div>
        </div>
        <div class="wh-progress mt-3">
            <?php foreach ($tahap as $i => $t) { ?><div class="wh-progress__step <?php echo $h->status === 'BATAL' ? '' : ($idx !== false && $i < $idx ? 'is-done' : ($i === $idx ? 'is-now' : '')); ?>"><b><?php echo $i + 1; ?></b><?php echo Mod_opname::$STATUS[$t]; ?></div><?php } ?>
        </div>
    </div>
</div>

<?php if ($h->status !== 'DRAFT') { ?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Progres hitung</div><div class="acc-kpi__value"><?php echo $R->persen; ?>%</div><div class="acc-kpi__sub"><?php echo nf($R->dihitung) . ' dari ' . nf($R->item); ?> barang</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Cocok</div><div class="acc-kpi__value text-success"><?php echo nf($R->cocok); ?></div><div class="acc-kpi__sub">akurasi <?php echo $R->dihitung ? round($R->cocok / $R->dihitung * 100, 1) : 0; ?>% dari yang dihitung</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Selisih</div><div class="acc-kpi__value text-warning"><?php echo nf($R->selisih); ?></div><div class="acc-kpi__sub"><?php echo $R->ulang && in_array($h->status, array('HITUNG', 'REVIEW'), true) ? '<b class="text-danger">' . nf($R->ulang) . ' wajib hitung ulang</b>' : ($h->status === 'DISETUJUI' ? 'sudah disesuaikan ke stok' : 'tidak ada yang wajib dihitung ulang'); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Nilai selisih (neto)</div><div class="acc-kpi__value <?php echo $R->lebih + $R->kurang < 0 ? 'text-danger' : ''; ?>"><?php echo $buta ? '<span class="text-muted">tersembunyi</span>' : nf_acc($R->lebih + $R->kurang); ?></div><div class="acc-kpi__sub"><?php echo $buta ? 'mode hitung buta' : 'lebih ' . nf_acc($R->lebih) . ' · kurang ' . nf_acc($R->kurang); ?></div></div>
</div>
<?php } ?>

<?php if ($h->status === 'DRAFT') { ?>
<div class="card"><div class="card-body text-center py-5">
    <i class="fas fa-lock fa-2x text-muted mb-2"></i>
    <h5>Siap memulai penghitungan?</h5>
    <p class="text-muted mb-3">Saat dimulai, daftar barang sesuai cakupan dibentuk dan <b>stok sistem saat ini dikunci</b> sebagai pembanding.<br>Transaksi masuk/keluar setelahnya tetap aman karena penyesuaian hanya sebesar selisih.</p>
    <?php if ($bisa_ubah) { ?><a href="<?php echo site_url('OpnameStok/mulai/' . $h->id); ?>" class="btn btn-primary"><i class="fas fa-play mr-1"></i> Mulai & kunci snapshot</a>
    <a href="<?php echo site_url('OpnameStok/status/' . $h->id . '/BATAL'); ?>" class="btn btn-outline-danger" onclick="return confirm('Batalkan dokumen ini?')">Batalkan</a><?php } ?>
</div></div>
<?php } else { ?>

<?php if ($input) { ?>
<div class="row">
    <div class="col-lg-7">
        <div class="card so-scan">
            <div class="card-body">
                <h6 class="mb-2"><i class="fas fa-qrcode mr-1"></i> Scan / ketik no. part <?php echo $h->status === 'REVIEW' ? '<span class="badge badge-warning">hitung ulang (ke-2)</span>' : ''; ?></h6>
                <form id="so-scan" class="form-row align-items-end" autocomplete="off">
                    <div class="col-sm-6 mb-2"><input id="so-kode" class="form-control" placeholder="Arahkan scanner ke label QR / ketik no. part lalu Enter" autofocus></div>
                    <div class="col-4 col-sm-2 mb-2"><input id="so-qty" class="form-control" type="number" step="any" min="0" value="1" title="Jumlah per scan"></div>
                    <div class="col-8 col-sm-4 mb-2"><select id="so-mode" class="form-control"><option value="tambah">Tambahkan (+)</option><option value="set">Ganti jumlah (=)</option></select></div>
                </form>
                <div class="d-flex flex-wrap align-items-center" style="gap:.5rem">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="so-kamera"><i class="fas fa-camera"></i> Scan dengan kamera</button>
                    <span id="so-hasil" class="small text-muted">Setiap scan menambah jumlah fisik barang tersebut. Pilih "Ganti jumlah" untuk mengetik total.</span>
                </div>
                <video id="so-video" playsinline muted hidden style="width:100%;max-height:240px;border-radius:10px;margin-top:.6rem;background:#000"></video>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card"><div class="card-body">
            <h6 class="mb-2"><i class="fas fa-file-import mr-1"></i> Impor hasil hitung dari Excel</h6>
            <form method="post" enctype="multipart/form-data" action="<?php echo site_url('OpnameStok/impor/' . $h->id); ?>" class="d-flex" style="gap:.4rem">
                <input type="file" name="file" accept=".xlsx,.csv" class="form-control-file form-control-sm" required>
                <button class="btn btn-sm btn-primary">Impor</button>
            </form>
            <small class="text-muted">Unduh <a href="<?php echo site_url('OpnameStok/ekspor/' . $h->id); ?>">lembar Excel</a>, isi kolom <code>fisik</code>, lalu unggah kembali. Baris kosong diabaikan.</small>
            <hr class="my-2">
            <div class="d-flex flex-wrap" style="gap:.4rem">
                <?php if ($h->status === 'HITUNG') { ?>
                <a class="btn btn-sm btn-warning" href="<?php echo site_url('OpnameStok/status/' . $h->id . '/REVIEW'); ?>" onclick="return confirm('Tutup penghitungan dan lanjut ke review selisih?')"><i class="fas fa-flag-checkered"></i> Selesai hitung → Review</a>
                <?php } else { ?>
                <a class="btn btn-sm btn-outline-secondary" href="<?php echo site_url('OpnameStok/status/' . $h->id . '/HITUNG'); ?>"><i class="fas fa-undo"></i> Buka lagi penghitungan</a>
                <?php } ?>
                <a class="btn btn-sm btn-outline-danger" href="<?php echo site_url('OpnameStok/status/' . $h->id . '/BATAL'); ?>" onclick="return confirm('Batalkan dokumen ini? Stok tidak akan berubah.')">Batalkan</a>
            </div>
        </div></div>
    </div>
</div>
<?php } ?>

<?php if ($h->status === 'REVIEW' && $admin) { ?>
<div class="card border-success">
    <form class="card-body d-flex flex-wrap align-items-center justify-content-between" style="gap:.8rem" method="post" action="<?php echo site_url('OpnameStok/setujui/' . $h->id); ?>" onsubmit="return confirm('Setujui opname dan sesuaikan stok sekarang? Tindakan ini tidak dapat dibatalkan.')">
        <div>
            <b>Persetujuan</b> — stok gudang <?php echo $L[$h->lokasi]; ?> akan disesuaikan untuk <?php echo nf($R->selisih); ?> barang (neto <?php echo nf_acc($R->lebih + $R->kurang); ?>).
            <?php if ($R->ulang) { ?><div class="text-danger small"><i class="fas fa-exclamation-triangle"></i> Masih ada <?php echo nf($R->ulang); ?> barang yang selisihnya di atas toleransi dan belum dihitung ulang.</div><?php } ?>
            <?php if ($R->item > $R->dihitung) { ?>
            <div class="custom-control custom-checkbox small mt-1"><input type="checkbox" class="custom-control-input" id="so-nol" name="nol" value="1"><label class="custom-control-label" for="so-nol"><?php echo nf($R->item - $R->dihitung); ?> barang belum dihitung — anggap fisiknya 0 (tidak ditemukan). Bila tidak dicentang, barang tersebut tidak diubah.</label></div>
            <?php } ?>
        </div>
        <button class="btn btn-success"><i class="fas fa-check-double mr-1"></i> Setujui & posting penyesuaian</button>
    </form>
</div>
<?php } ?>

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <div class="btn-group btn-group-sm">
            <a class="btn btn-<?php echo $f === '' ? 'primary' : 'outline-secondary'; ?>" href="<?php echo $url; ?>">Semua (<?php echo nf($R->item); ?>)</a>
            <a class="btn btn-<?php echo $f === 'belum' ? 'primary' : 'outline-secondary'; ?>" href="<?php echo $url; ?>?f=belum">Belum dihitung (<?php echo nf($R->item - $R->dihitung); ?>)</a>
            <?php if ( ! $buta) { ?><a class="btn btn-<?php echo $f === 'selisih' ? 'primary' : 'outline-secondary'; ?>" href="<?php echo $url; ?>?f=selisih">Selisih (<?php echo nf($R->selisih); ?>)</a><?php } ?>
        </div>
        <input type="search" class="form-control form-control-sm" style="max-width:240px" placeholder="Saring no. part / nama / rak…" id="so-cari">
    </div>
    <form method="post" action="<?php echo site_url('OpnameStok/simpan/' . $h->id); ?>">
        <input type="hidden" name="f" value="<?php echo html_escape($f); ?>">
        <div class="table-responsive" style="max-height:65vh">
        <table class="table table-sm table-hover mb-0 so-tabel" id="so-tabel">
            <thead class="thead-sticky"><tr><th>Rak</th><th>No. part</th><th>Nama barang</th><th>Sat.</th>
                <?php if ( ! $buta) { ?><th class="num">Stok sistem</th><?php } ?>
                <th class="num" style="width:110px">Hitung 1</th><th class="num" style="width:110px">Hitung 2</th>
                <?php if ( ! $buta) { ?><th class="num">Selisih</th><th class="num">Nilai selisih</th><?php } ?><th>Penghitung</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $d) {
                $sel = $d->fisik === null ? null : $d->fisik - $d->stok_snapshot;
                $ulang = $d->fisik !== null && $d->hitung2 === null && Mod_opname::perlu_ulang($d->stok_snapshot, $d->fisik, $h->toleransi);
                $cls = $d->fisik === null ? '' : (abs($sel) < 0.0005 ? 'so-ok' : ($ulang && ! $buta ? 'so-ulang' : 'so-beda')); ?>
                <tr class="<?php echo $cls; ?>" data-id="<?php echo $d->id; ?>" data-cari="<?php echo html_escape(strtolower($d->no_part . ' ' . $d->nama_part . ' ' . $d->rak)); ?>">
                    <td><?php echo html_escape($d->rak); ?></td>
                    <td><a href="<?php echo site_url('LaporanGudang/kartu_stok?part=' . rawurlencode($d->no_part) . '&lok=' . $h->lokasi); ?>" target="_blank"><?php echo html_escape($d->no_part); ?></a></td>
                    <td><?php echo html_escape($d->nama_part); ?><?php echo $d->catatan !== '' ? '<div class="small text-muted">' . html_escape($d->catatan) . '</div>' : ''; ?></td>
                    <td><?php echo html_escape($d->satuan); ?></td>
                    <?php if ( ! $buta) { ?><td class="num"><?php echo nf_qty($d->stok_snapshot); ?></td><?php } ?>
                    <td class="num"><?php if ($input && $h->status === 'HITUNG') { ?><input class="form-control form-control-sm text-right so-in" name="q[<?php echo $d->id; ?>]" inputmode="decimal" placeholder="<?php echo $d->hitung1 === null ? '' : nf_qty($d->hitung1); ?>"><?php } else echo $d->hitung1 === null ? '-' : nf_qty($d->hitung1); ?></td>
                    <td class="num"><?php if ($input && $h->status === 'REVIEW') { ?><input class="form-control form-control-sm text-right so-in" name="q[<?php echo $d->id; ?>]" inputmode="decimal" placeholder="<?php echo $d->hitung2 === null ? ($ulang ? 'wajib' : '') : nf_qty($d->hitung2); ?>"><?php } else echo $d->hitung2 === null ? '-' : nf_qty($d->hitung2); ?></td>
                    <?php if ( ! $buta) { ?><td class="num so-sel"><?php echo $sel === null ? '' : ($sel > 0 ? '+' : '') . nf_qty($sel); ?></td><td class="num"><?php echo $sel === null || abs($sel) < 0.0005 ? '' : nf_acc($sel * $d->harga); ?></td><?php } ?>
                    <td class="small text-muted so-oleh"><?php echo $d->fisik === null ? '' : html_escape($d->penghitung) . '<br>' . date('d/m H:i', strtotime($d->waktu_hitung)); ?></td>
                </tr>
            <?php } if ( ! $rows) { ?><tr><td colspan="10" class="text-center text-muted py-4">Tidak ada baris untuk filter ini.</td></tr><?php } ?>
            </tbody>
        </table>
        </div>
        <?php if ($input && $rows) { ?>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Isi hanya kolom yang sudah dihitung; kolom kosong tidak mengubah data. <?php echo $h->status === 'REVIEW' ? 'Hasil hitung ke-2 menggantikan hitung ke-1 sebagai jumlah fisik.' : ''; ?></small>
            <button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan hasil hitung</button>
        </div>
        <?php } ?>
    </form>
</div>
<?php } ?>

<script>
(function () {
    var cari = document.getElementById('so-cari');
    if (cari) cari.addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        document.querySelectorAll('#so-tabel tbody tr[data-cari]').forEach(function (tr) { tr.hidden = q && tr.getAttribute('data-cari').indexOf(q) < 0; });
    });
    var f = document.getElementById('so-scan'); if (!f) return;
    var kode = document.getElementById('so-kode'), hasil = document.getElementById('so-hasil'), sibuk = false;
    function kirim(k) {
        if (!k || sibuk) return; sibuk = true;
        var fd = new FormData(); fd.append('kode', k); fd.append('qty', document.getElementById('so-qty').value); fd.append('mode', document.getElementById('so-mode').value);
        fetch('<?php echo site_url('OpnameStok/scan/' . $h->id); ?>', { method: 'POST', body: fd, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); }).then(function (j) {
                if (!j.ok) { hasil.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> ' + j.pesan + '</span>'; if (w.toastr) toastr.error(j.pesan); return; }
                hasil.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> <b>' + j.no_part + '</b> ' + j.nama + ' — fisik sekarang <b>' + j.fisik + ' ' + j.satuan + '</b>' + (j.rak ? ' · rak ' + j.rak : '') + '</span>';
                var tr = document.querySelector('#so-tabel tr[data-id="' + j.id + '"]');
                if (tr) { var i = tr.querySelector('.so-in'); if (i) i.placeholder = j.fisik; tr.classList.add('so-baru'); tr.scrollIntoView({ block: 'nearest' }); setTimeout(function () { tr.classList.remove('so-baru'); }, 1500); }
            }).catch(function () { hasil.innerHTML = '<span class="text-danger">Gagal terhubung ke server.</span>'; })
            .then(function () { sibuk = false; kode.value = ''; kode.focus(); });
    }
    var w = window;
    f.addEventListener('submit', function (e) { e.preventDefault(); kirim(kode.value.trim()); });
    kode.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); kirim(kode.value.trim()); } });
    // kamera (BarcodeDetector: Chrome/Edge Android & desktop terbaru)
    var btn = document.getElementById('so-kamera'), video = document.getElementById('so-video'), stream = null, terakhir = '', jeda = 0;
    btn.addEventListener('click', function () {
        if (stream) { stream.getTracks().forEach(function (t) { t.stop(); }); stream = null; video.hidden = true; btn.innerHTML = '<i class="fas fa-camera"></i> Scan dengan kamera'; return; }
        if (!('BarcodeDetector' in w) || !navigator.mediaDevices) { hasil.innerHTML = '<span class="text-warning">Browser ini belum mendukung scan kamera. Gunakan scanner USB/Bluetooth (bekerja seperti keyboard) atau ketik no. part.</span>'; return; }
        var det = new w.BarcodeDetector({ formats: ['qr_code', 'code_128', 'ean_13', 'code_39'] });
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } }).then(function (s) {
            stream = s; video.srcObject = s; video.hidden = false; video.play(); btn.innerHTML = '<i class="fas fa-stop"></i> Hentikan kamera';
            (function loop() {
                if (!stream) return;
                det.detect(video).then(function (c) {
                    if (c.length && (c[0].rawValue !== terakhir || Date.now() - jeda > 2500)) { terakhir = c[0].rawValue; jeda = Date.now(); kirim(terakhir); }
                }).catch(function () {}).then(function () { setTimeout(loop, 350); });
            })();
        }).catch(function () { hasil.innerHTML = '<span class="text-danger">Izin kamera ditolak.</span>'; });
    });
})();
</script>
