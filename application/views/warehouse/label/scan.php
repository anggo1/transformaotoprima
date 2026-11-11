<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2"><i class="fas fa-qrcode mr-1"></i> Scan label QR / barcode</h6>
                <form id="sc-form" autocomplete="off" class="d-flex" style="gap:.4rem">
                    <input id="sc-kode" class="form-control" placeholder="Scan atau ketik no. part lalu Enter" autofocus style="font-size:15px;font-weight:600">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                </form>
                <div class="d-flex align-items-center mt-2" style="gap:.5rem">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="sc-kamera"><i class="fas fa-camera"></i> Gunakan kamera</button>
                    <small class="text-muted" id="sc-pesan">Scanner USB/Bluetooth bekerja seperti keyboard: cukup arahkan ke label.</small>
                </div>
                <video id="sc-video" playsinline muted hidden style="width:100%;max-height:300px;border-radius:10px;margin-top:.6rem;background:#000"></video>
            </div>
        </div>
        <div class="card"><div class="card-body small">
            <b>Gunakan untuk:</b>
            <ul class="pl-3 mb-0 mt-1">
                <li>Cek cepat stok & rak barang di semua gudang dari HP.</li>
                <li>Verifikasi label yang tertempel (no. part & rak cocok).</li>
                <li>Hitung fisik: buka dokumen di <a href="<?php echo site_url('OpnameStok'); ?>">Stock Opname</a> lalu scan di sana — setiap scan menambah jumlah.</li>
                <li>Label rusak/hilang: klik <i>Cetak label</i> pada hasil scan, atau cetak massal di <a href="<?php echo site_url('LabelBarang'); ?>">Cetak Label QR</a>.</li>
            </ul>
            <div class="text-muted mt-2">Scan kamera memakai fitur BarcodeDetector (Chrome / Edge terbaru, Android). Di browser lain gunakan scanner atau ketik no. part.</div>
        </div></div>
    </div>
    <div class="col-lg-7"><div id="sc-hasil"><div class="card"><div class="card-body text-center text-muted py-5"><i class="fas fa-barcode fa-3x mb-2 d-block"></i>Hasil scan tampil di sini.</div></div></div></div>
</div>
<script>
(function () {
    var w = window, kode = document.getElementById('sc-kode'), out = document.getElementById('sc-hasil'), pesan = document.getElementById('sc-pesan');
    function esc(s) { return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
    function nf(n) { return (+n || 0).toLocaleString('en-US', { maximumFractionDigits: 2 }); }
    function cari(k) {
        if (!k) return;
        fetch('<?php echo site_url('LabelBarang/info'); ?>?kode=' + encodeURIComponent(k), { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); }).then(function (j) {
                if (!j.ok) {
                    out.innerHTML = '<div class="card"><div class="card-body"><div class="text-danger mb-2"><i class="fas fa-times-circle"></i> ' + esc(j.pesan) + '</div>' +
                        (j.saran && j.saran.length ? '<div class="small text-muted mb-1">Mungkin maksud Anda:</div>' + j.saran.map(function (s) { return '<a href="#" class="d-block sc-saran" data-k="' + esc(s.no_part) + '">' + esc(s.no_part) + ' — ' + esc(s.nama) + '</a>'; }).join('') : '') + '</div></div>';
                    return;
                }
                var tot = 0, lok = j.lokasi.map(function (l) { tot += +l.stok; return '<tr><td>' + esc(l.nama) + '</td><td>' + (l.rak ? '<span class="badge badge-light border">' + esc(l.rak) + '</span>' : '-') + '</td><td class="num"><b class="' + (l.stok <= 0 ? 'text-danger' : '') + '">' + nf(l.stok) + '</b></td><td class="num">' + nf(l.harga) + '</td></tr>'; }).join('');
                var g = j.gerak.map(function (x) { return '<tr><td>' + esc(x.tgl) + '</td><td><span class="wh-jenis wh-jenis--' + x.kode + '">' + esc(x.jenis) + '</span></td><td>' + esc((x.lok || '').toUpperCase()) + '</td><td class="num text-success">' + (x.masuk ? '+' + nf(x.masuk) : '') + '</td><td class="num text-danger">' + (x.keluar ? '−' + nf(x.keluar) : '') + '</td><td class="small text-muted">' + esc(x.ket) + '</td></tr>'; }).join('');
                out.innerHTML = '<div class="card"><div class="card-body"><div class="d-flex justify-content-between flex-wrap" style="gap:.5rem"><div><div class="small text-muted">' + esc(j.kategori) + ' · ' + esc(j.satuan || '-') + '</div><h4 class="mb-0">' + esc(j.no_part) + '</h4><div>' + esc(j.nama) + '</div></div>' +
                    '<div class="text-right"><div class="small text-muted">Total stok</div><div style="font-size:26px;font-weight:800">' + nf(tot) + '</div>' + (j.min ? '<div class="small text-muted">min ' + esc(j.min) + '</div>' : '') + '</div></div>' +
                    '<table class="table table-sm mt-3 mb-2"><thead><tr><th>Gudang</th><th>Rak</th><th class="num">Stok</th><th class="num">Harga</th></tr></thead><tbody>' + lok + '</tbody></table>' +
                    '<div class="d-flex flex-wrap" style="gap:.4rem"><a class="btn btn-sm btn-outline-primary" href="' + j.url_kartu + '"><i class="fas fa-id-card-alt"></i> Kartu stok</a><a class="btn btn-sm btn-outline-secondary" href="' + j.url_harga + '"><i class="fas fa-history"></i> Riwayat harga</a><a class="btn btn-sm btn-outline-secondary" target="_blank" href="' + j.url_label + '"><i class="fas fa-tag"></i> Cetak label</a></div></div></div>' +
                    '<div class="card"><div class="card-header"><h3 class="card-title">Transaksi terakhir (12 bulan)</h3></div><div class="card-body p-0"><table class="table table-sm mb-0"><tbody>' + (g || '<tr><td class="text-center text-muted py-3">Tidak ada transaksi.</td></tr>') + '</tbody></table></div></div>';
            }).catch(function () { out.innerHTML = '<div class="alert alert-danger">Gagal memuat data.</div>'; })
            .then(function () { kode.value = ''; kode.focus(); });
    }
    document.getElementById('sc-form').addEventListener('submit', function (e) { e.preventDefault(); cari(kode.value.trim()); });
    out.addEventListener('click', function (e) { var a = e.target.closest('.sc-saran'); if (a) { e.preventDefault(); cari(a.getAttribute('data-k')); } });
    var btn = document.getElementById('sc-kamera'), video = document.getElementById('sc-video'), stream = null, last = '', jeda = 0;
    btn.addEventListener('click', function () {
        if (stream) { stream.getTracks().forEach(function (t) { t.stop(); }); stream = null; video.hidden = true; btn.innerHTML = '<i class="fas fa-camera"></i> Gunakan kamera'; return; }
        if (!('BarcodeDetector' in w) || !navigator.mediaDevices) { pesan.innerHTML = '<span class="text-warning">Browser ini belum mendukung scan kamera.</span>'; return; }
        var det = new w.BarcodeDetector({ formats: ['qr_code', 'code_128', 'ean_13', 'code_39'] });
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } }).then(function (s) {
            stream = s; video.srcObject = s; video.hidden = false; video.play(); btn.innerHTML = '<i class="fas fa-stop"></i> Hentikan kamera';
            (function loop() { if (!stream) return; det.detect(video).then(function (c) { if (c.length && (c[0].rawValue !== last || Date.now() - jeda > 3000)) { last = c[0].rawValue; jeda = Date.now(); cari(last); } }).catch(function () {}).then(function () { setTimeout(loop, 400); }); })();
        }).catch(function () { pesan.innerHTML = '<span class="text-danger">Izin kamera ditolak.</span>'; });
    });
    var q = new URLSearchParams(location.search).get('kode'); if (q) cari(q);
})();
</script>
