<?php defined('BASEPATH') OR exit('No direct script access allowed');
$link = function ($u, $t) { return '<a href="' . site_url($u) . '">' . $t . '</a>'; };
$URL = array('sparepart' => 'Sparepart', 'updatehpart' => 'UpdateHpart', 'purchaseorder' => 'PurchaseOrder', 'partorder' => 'PartOrder', 'estimasipenawaran' => 'EstimasiPenawaran',
    'pomasuk' => 'PoMasuk', 'part_masuk_po' => 'Part_masuk_po', 'part_masuk_npo' => 'Part_masuk_npo', 'part_keluar' => 'Part_keluar', 'partkeluarwo' => 'PartKeluarWo',
    'partkeluarpo' => 'PartKeluarPo', 'suratjalan' => 'SuratJalan', 'stokopname' => 'StokOpname', 'settingwh' => 'Settingwh', 'laporangudang' => 'LaporanGudang', 'reportwhnilaistok' => 'ReportWhNilaiStok');
$ok = 0; foreach ($cek as $c) if ($c[1] === true) $ok++;
?>
<div class="pd-wrap">
    <nav class="pd-toc no-print">
        <div class="text-muted small font-weight-bold text-uppercase mb-2 px-2">Daftar isi</div>
        <a href="#alur">1. Alur gudang</a>
        <a href="#data">2. Kesiapan data</a>
        <a href="#rutin">3. Rutinitas kerja</a>
        <a href="#menu">4. Fungsi setiap menu</a>
        <a href="#laporan">5. Laporan & kegunaannya</a>
        <a href="#ref">6. Referensi manajemen persediaan</a>
        <a href="#akuntansi">7. Hubungan dengan Akuntansi</a>
        <a href="#faq">8. Tanya jawab</a>
        <hr class="my-2">
        <a href="javascript:window.print()"><i class="fas fa-print mr-1"></i> Cetak panduan</a>
    </nav>
    <div>
    <div class="card pd-sec" id="alur"><div class="card-body">
        <h2>1. Alur gudang</h2>
        <p>Setiap barang bergerak lewat dokumen. Stok di <?php echo $link('Sparepart', 'Material &amp; Sparepart'); ?> otomatis bertambah saat <b>Part Masuk</b> disimpan dan berkurang saat <b>Part Keluar</b> disimpan. Tidak ada alur yang diubah — panduan ini merangkum alur yang sudah berjalan.</p>
        <h3>a. Pembelian / pengadaan</h3>
        <div class="pd-flow">
            <div><strong>Stok Minimum</strong>Laporan saran pesan</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Part Order / Purchase Order</strong>pesan ke pemasok</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Part Masuk PO</strong>terima barang, stok +</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Hutang Usaha</strong>Akuntansi catat tagihan pemasok</div>
        </div>
        <h3>b. Penjualan part ke pelanggan</h3>
        <div class="pd-flow">
            <div><strong>Estimasi Penawaran</strong>harga ke pelanggan</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Purchase Order Masuk</strong>PO pelanggan</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Part Keluar PO</strong>stok −</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Surat Jalan</strong>kirim barang</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Piutang Usaha</strong>Akuntansi terbitkan faktur</div>
        </div>
        <h3>c. Pemakaian internal & service</h3>
        <div class="pd-flow">
            <div><strong>Part Request / PK</strong>permintaan bengkel</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Part Keluar / Part Keluar WO</strong>stok −, bon ditandatangani</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Laporan Pemakaian</strong>biaya per divisi/WO</div>
        </div>
        <h3>d. Kontrol</h3>
        <div class="pd-flow">
            <div><strong>Stock Opname</strong>hitung fisik per kelompok rak</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Selisih Opname</strong>sistem vs fisik</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>Koreksi stok + jurnal</strong>disetujui atasan & Akuntansi</div>
        </div>
    </div></div>

    <div class="card pd-sec" id="data"><div class="card-body">
        <h2>2. Kesiapan data <small class="text-muted">(<?php echo $ok; ?> dari <?php echo count($cek); ?> baik — dihitung langsung dari database)</small></h2>
        <ul class="pd-check">
        <?php foreach ($cek as $c) { ?>
            <li class="mb-2"><i class="fas <?php echo $c[1] === true ? 'fa-check-circle' : ($c[1] === null ? 'fa-info-circle text-info' : 'fa-times-circle'); ?> mr-1"></i>
                <b><?php echo html_escape($c[0]); ?></b> — <span class="text-muted"><?php echo html_escape($c[2]); ?></span><?php if ($c[1] !== true) { ?> · <?php echo $link($c[3], 'perbaiki'); ?><?php } ?></li>
        <?php } ?>
        </ul>
    </div></div>

    <div class="card pd-sec" id="rutin"><div class="card-body">
        <h2>3. Rutinitas kerja</h2>
        <div class="row">
            <div class="col-lg-4"><h3><i class="fas fa-sun text-warning mr-1"></i> Harian</h3><ul>
                <li>Catat setiap penerimaan saat barang tiba (cek fisik dulu, isi jumlah yang benar-benar diterima).</li>
                <li>Catat setiap pengeluaran dengan bon bertanda tangan penerima.</li>
                <li>Buka <?php echo $link('LaporanGudang', 'Dashboard Gudang'); ?> dan <?php echo $link('LaporanGudang/stok_minimum', 'Stok Minimum'); ?>.</li>
                <li>Kembalikan barang ke rak sesuai kode rak.</li></ul></div>
            <div class="col-lg-4"><h3><i class="fas fa-calendar-week text-info mr-1"></i> Mingguan</h3><ul>
                <li>Tindak lanjuti <?php echo $link('LaporanGudang/outstanding', 'PO Outstanding'); ?> ke pemasok.</li>
                <li>Opname siklus untuk barang kelas A (<?php echo $link('LaporanGudang/abc', 'Analisis ABC'); ?>).</li>
                <li>Periksa <?php echo $link('LaporanGudang/cek_data', 'Pemeriksaan Data Gudang'); ?> (stok minus, harga kosong).</li></ul></div>
            <div class="col-lg-4"><h3><i class="fas fa-flag-checkered text-success mr-1"></i> Bulanan / tahunan</h3><ul>
                <li>Cetak <?php echo $link('LaporanGudang/mutasi', 'Mutasi Stok'); ?> & <?php echo $link('ReportWhNilaiStok', 'Nilai Persediaan'); ?> untuk Akuntansi.</li>
                <li>Tinjau <?php echo $link('LaporanGudang/slow_moving', 'Slow Moving'); ?> & usulkan tindak lanjut.</li>
                <li>Perbarui harga (Update Harga) bila ada perubahan dari pemasok.</li>
                <li>Akhir tahun: stock opname menyeluruh sebelum tutup buku.</li></ul></div>
        </div>
    </div></div>

    <div class="card pd-sec" id="menu"><div class="card-body">
        <h2>4. Fungsi setiap menu</h2>
        <?php foreach ($URL as $k => $u) { if ( ! isset($halaman[$k])) continue; $h = $halaman[$k]; ?>
        <h3><?php echo $link($u, html_escape($h['judul'])); ?></h3>
        <p class="mb-1"><?php echo $h['ringkas']; ?></p>
        <ol class="mb-1"><?php foreach ($h['langkah'] as $x) { ?><li><?php echo $x; ?></li><?php } ?></ol>
        <?php if ($h['catatan'] !== '') { ?><p class="small text-muted"><i class="fas fa-info-circle mr-1"></i><?php echo $h['catatan']; ?></p><?php } ?>
        <?php } ?>
    </div></div>

    <div class="card pd-sec" id="laporan"><div class="card-body">
        <h2>5. Laporan & kegunaannya</h2>
        <?php $g = null; foreach ($laporan as $k => $l) { if ($l[3] !== $g) { $g = $l[3]; ?><h3><?php echo html_escape($g); ?></h3><?php } ?>
        <p class="mb-2"><?php echo $link('LaporanGudang/' . $k, '<b>' . html_escape($l[0]) . '</b>'); ?> — <?php echo isset($penjelasan[$k]) ? $penjelasan[$k]['tujuan'] : html_escape($l[2]); ?></p>
        <?php } ?>
    </div></div>

    <div class="card pd-sec" id="ref"><div class="card-body">
        <h2>6. Referensi manajemen persediaan</h2>
        <div class="table-responsive"><table class="table table-sm pd-table">
            <thead><tr><th style="width:22%">Konsep</th><th>Penjelasan & rumus</th><th style="width:26%">Di program</th></tr></thead>
            <tbody>
            <tr><td><b>Stok minimum / Reorder point</b></td><td>ROP = rata-rata pemakaian per hari × lead time pemasok (hari) + stok pengaman. Contoh: pakai 3/hari, lead time 10 hari, pengaman 10 → ROP = 40.</td><td>Isi kolom stok minimum; pantau di Stok Minimum & Saran Pesan.</td></tr>
            <tr><td><b>Stok pengaman (safety stock)</b></td><td>Cadangan untuk lonjakan pemakaian / keterlambatan pemasok. Cara praktis: (pemakaian harian tertinggi × lead time terlama) − (pemakaian rata-rata × lead time rata-rata).</td><td>Termasuk di dalam stok minimum.</td></tr>
            <tr><td><b>Analisis ABC</b></td><td>A ≈ 80% nilai pemakaian (±20% item), B ≈ 15%, C ≈ 5%. Kontrol & opname paling ketat untuk kelas A.</td><td>Laporan Analisis ABC.</td></tr>
            <tr><td><b>FIFO / FEFO</b></td><td>First In First Out: barang yang masuk lebih dulu dikeluarkan lebih dulu. First Expired First Out untuk barang berkedaluwarsa (oli, cat, lem, sealant).</td><td>Atur penempatan rak; catat tanggal masuk di label.</td></tr>
            <tr><td><b>Opname siklus</b></td><td>Menghitung sebagian barang secara bergilir (mis. kelas A bulanan, B triwulanan, C semesteran) sehingga tidak perlu menutup gudang.</td><td>Stock Opname per kelompok rak + Selisih Opname.</td></tr>
            <tr><td><b>Perputaran persediaan</b></td><td>= nilai pemakaian setahun ÷ rata-rata nilai persediaan. Hari persediaan = 365 ÷ perputaran. Makin tinggi perputaran, makin sedikit modal tertahan.</td><td>Pemakaian (nilai) & Nilai Persediaan; juga di Analisis Rasio Keuangan (DIO).</td></tr>
            <tr><td><b>Akurasi stok</b></td><td>= item tanpa selisih ÷ item dihitung × 100%. Target umum ≥ 95%.</td><td>KPI di Selisih Stock Opname.</td></tr>
            <tr><td><b>Dead stock</b></td><td>Barang tanpa pergerakan dalam waktu lama (mis. > 180 hari). Menambah biaya simpan & risiko rusak.</td><td>Slow Moving & Dead Stock.</td></tr>
            <tr><td><b>5S gudang</b></td><td>Seiri (pilah), Seiton (tata), Seiso (bersihkan), Seiketsu (standarkan), Shitsuke (biasakan) — dasar gudang rapi & stok akurat.</td><td>Kode rak di master barang & kelompok rak.</td></tr>
            </tbody>
        </table></div>
    </div></div>

    <div class="card pd-sec" id="akuntansi"><div class="card-body">
        <h2>7. Hubungan dengan Akuntansi</h2>
        <ul>
            <li><b>Pembelian</b>: penerimaan barang (gudang) → tagihan pemasok dicatat di <?php echo $link('Hutang', 'Hutang Usaha'); ?>: Dr Persediaan, Dr PPN Masukan / Cr Hutang Usaha.</li>
            <li><b>Penjualan part</b>: Part Keluar PO + Surat Jalan (gudang) → faktur di <?php echo $link('Piutang', 'Piutang Usaha'); ?>: Dr Piutang / Cr Pendapatan, Cr PPN Keluaran.</li>
            <li><b>Pemakaian</b>: nilai Laporan Pemakaian per bulan → Jurnal Umum: Dr Harga Pokok / Beban Material / Cr Persediaan.</li>
            <li><b>Selisih opname</b>: Dr Beban Selisih Persediaan / Cr Persediaan (kurang) atau sebaliknya (lebih).</li>
            <li>Setiap akhir bulan cocokkan total <?php echo $link('ReportWhNilaiStok', 'Nilai Persediaan'); ?> dengan saldo akun Persediaan di <?php echo $link('LaporanKeuangan/neraca', 'Neraca'); ?>.</li>
        </ul>
    </div></div>

    <div class="card pd-sec" id="faq"><div class="card-body">
        <h2>8. Tanya jawab</h2>
        <h3>Stok di sistem minus.</h3><p>Pengeluaran tercatat sebelum penerimaannya diinput. Input Part Masuk yang tertinggal, lalu periksa lewat Kartu Stok.</p>
        <h3>Saldo awal di Kartu Stok tidak sesuai catatan lama.</h3><p>Kartu stok dihitung mundur dari stok sistem hari ini, sehingga koreksi stok manual (tanpa dokumen) muncul di saldo awal. Biasakan koreksi lewat Stock Opname.</p>
        <h3>Barang sudah dipesan tetapi masih muncul di Stok Minimum.</h3><p>Kolom "Sedang dipesan" sudah memperhitungkan sisa Part Order; saran pesan menjadi 0 bila pesanan sudah cukup. Bila masih muncul, cek apakah pesanan dibuat lewat Part Order.</p>
        <h3>Nilai persediaan 0 untuk beberapa barang.</h3><p>Harga net belum diisi. Lengkapi lewat Update Harga.</p>
        <h3>Ingin pesan ulang berdasarkan pemakaian nyata.</h3><p class="mb-0">Lihat Pemakaian (90 hari) untuk rata-rata pemakaian, hitung ROP (bagian 6), lalu perbarui stok minimum di master barang.</p>
    </div></div>
    </div>
</div>
