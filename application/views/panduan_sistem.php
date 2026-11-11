<?php defined('BASEPATH') OR exit('No direct script access allowed');
$a = function ($u, $t) { return '<a href="' . site_url($u) . '">' . $t . '</a>'; };
$ok = 0; foreach ($cek as $c) if ($c[1] === true) $ok++;
?>
<div class="pd-wrap">
    <nav class="pd-toc no-print">
        <div class="text-muted small font-weight-bold text-uppercase mb-2 px-2">Daftar isi</div>
        <a href="#peta">1. Peta integrasi modul</a>
        <a href="#dept">2. Dashboard & laporan per departemen</a>
        <a href="#kesiapan">3. Kesiapan data</a>
        <a href="#gudang">4. Konsep gudang baru (v5)</a>
        <a href="#rutin">5. Kalender kerja bulanan</a>
        <a href="#lanjut">6. Rekomendasi pengembangan</a>
        <hr class="my-2"><a href="javascript:window.print()"><i class="fas fa-print mr-1"></i> Cetak panduan</a>
    </nav>
    <div>
    <div class="card pd-sec" id="peta"><div class="card-body">
        <h2>1. Peta integrasi modul</h2>
        <p>Setiap modul mengisi data untuk modul lain. Garis besar alur data:</p>
        <div class="pd-flow"><div><strong>Marketing</strong>Stok chasis, SPK</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>After Sales</strong>Appointment → WO → Part Request</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Gudang</strong>Part Keluar WO, stok −</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Akuntansi</strong>Piutang, pendapatan jasa & part</div></div>
        <div class="pd-flow"><div><strong>Gudang</strong>Stok minimum → Part Order</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Part Masuk</strong>stok +, harga beli</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Harga rata-rata</strong>nilai persediaan</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Akuntansi</strong>Hutang usaha, persediaan</div></div>
        <div class="pd-flow"><div><strong>Stock Opname</strong>selisih fisik</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Penyesuaian stok</strong>kartu stok (ADJ)</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Jurnal SO</strong>persediaan vs selisih</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Laba rugi & Neraca</strong></div></div>
        <div class="pd-flow"><div><strong>HRD</strong>pegawai, departemen</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>User & Dashboard</strong>user → departemen → dashboard</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div><div><strong>Mesin absen & cuti</strong>rekap kehadiran</div></div>
    </div></div>

    <div class="card pd-sec" id="dept"><div class="card-body">
        <h2>2. Dashboard & laporan rekomendasi per departemen</h2>
        <p>Dashboard setiap user ditentukan di <?php echo $a('PengaturanDashboard', 'Pengaturan Dashboard'); ?> (prioritas: user &gt; departemen &gt; level &gt; preset bawaan). Laporan yang disarankan:</p>
        <table class="table table-sm">
            <thead><tr><th style="width:150px">Departemen</th><th>Harian</th><th>Mingguan / bulanan</th><th>Tahunan</th></tr></thead>
            <tbody>
            <tr><td><b>Direksi</b></td><td>Dashboard Direksi</td><td><?php echo $a('LaporanKeuangan/laba_rugi', 'Laba rugi'); ?>, <?php echo $a('LaporanKeuangan/arus_kas', 'Arus kas'); ?>, <?php echo $a('LaporanKeuangan/rasio', 'Rasio keuangan'); ?>, <?php echo $a('LaporanGudang/kenaikan_harga', 'Kenaikan harga beli'); ?></td><td><?php echo $a('LaporanKeuangan/paket', 'Laporan keuangan lengkap'); ?>, <?php echo $a('LaporanKeuangan/anggaran', 'Anggaran vs realisasi'); ?></td></tr>
            <tr><td><b>Akuntansi</b></td><td><?php echo $a('KasBank', 'Kas & bank'); ?>, jatuh tempo</td><td><?php echo $a('LaporanKeuangan/umur_piutang', 'Umur piutang/hutang'); ?>, <?php echo $a('LaporanKeuangan/pajak_tahunan', 'PPN & PPh'); ?>, <?php echo $a('LaporanGudang/harga_rata', 'Nilai persediaan @rata-rata'); ?>, <?php echo $a('LaporanGudang/selisih_opname', 'Selisih opname'); ?></td><td><?php echo $a('LaporanKeuangan/rekon_fiskal', 'Rekonsiliasi fiskal'); ?>, tutup buku</td></tr>
            <tr><td><b>Gudang</b></td><td><?php echo $a('LaporanGudang/stok_minimum', 'Stok minimum'); ?>, <?php echo $a('LabelBarang/scan', 'Scan barang'); ?></td><td><?php echo $a('LaporanGudang/outstanding', 'PO outstanding'); ?>, <?php echo $a('LaporanGudang/mutasi', 'Mutasi'); ?>, cycle count di <?php echo $a('OpnameStok', 'Stock Opname'); ?></td><td><?php echo $a('LaporanGudang/abc', 'Analisis ABC'); ?>, <?php echo $a('LaporanGudang/slow_moving', 'Slow moving'); ?>, opname penuh</td></tr>
            <tr><td><b>After Sales</b></td><td>WO terbuka, Part Request</td><td><?php echo $a('WoService', 'Laporan WO'); ?>, <?php echo $a('ReportService', 'Services report'); ?></td><td>Tren WO & pendapatan jasa</td></tr>
            <tr><td><b>Marketing</b></td><td>Stok chasis</td><td>Chasis per sales, <?php echo $a('ListSpk', 'SPK'); ?></td><td>Tren chasis masuk/keluar</td></tr>
            <tr><td><b>HRD</b></td><td>Ulang tahun, cuti</td><td><?php echo $a('LaporanHR/absensi', 'Rekap absensi'); ?>, <?php echo $a('LaporanHR/cuti', 'Rekap cuti'); ?></td><td><?php echo $a('LaporanHR/komposisi', 'Komposisi'); ?>, <?php echo $a('LaporanHR/masa_kerja', 'Masa kerja'); ?>, <?php echo $a('LaporanHR/kelengkapan', 'Kelengkapan data'); ?></td></tr>
            </tbody>
        </table>
        <p class="small text-muted mb-0">Semua laporan baru memiliki tombol Cetak & Excel, dan setiap grafik dapat diunduh sebagai PNG, data CSV, atau dicetak terpisah.</p>
    </div></div>

    <div class="card pd-sec" id="kesiapan"><div class="card-body">
        <h2>3. Kesiapan data <small class="text-muted"><?php echo $ok . ' / ' . count($cek); ?> siap</small></h2>
        <table class="table table-sm mb-0"><tbody>
        <?php foreach ($cek as $c) { ?><tr><td style="width:28px"><i class="fas <?php echo $c[1] === true ? 'fa-check-circle text-success' : ($c[1] === null ? 'fa-exclamation-circle text-warning' : 'fa-times-circle text-danger'); ?>"></i></td><td><?php echo $c[0]; ?></td><td class="text-muted"><?php echo $c[2]; ?></td><td class="text-right"><a href="<?php echo site_url($c[3]); ?>">Buka ›</a></td></tr><?php } ?>
        </tbody></table>
    </div></div>

    <div class="card pd-sec" id="gudang"><div class="card-body">
        <h2>4. Konsep gudang baru (v5)</h2>
        <h3>a. Stock opname terkendali</h3>
        <ul><li>Snapshot stok saat mulai → transaksi tetap berjalan, penyesuaian hanya sebesar selisih.</li><li>Hitung buta & toleransi selisih → hitung ulang otomatis ditandai.</li><li>Input via scan QR/kamera, lembar Excel, atau ketik; disetujui atasan; berita acara & jurnal persediaan otomatis.</li><li>Rekomendasi: <b>cycle count</b> bulanan per rak / kelas A, opname penuh setahun sekali sebelum tutup buku.</li></ul>
        <h3>b. Impor barang dari Excel</h3>
        <ul><li>Template & ekspor seluruh master → edit massal → pratinjau BARU/UPDATE/ERROR sebelum disimpan.</li><li>Mode "hanya harga" untuk perubahan price list tahunan dari principal; semua perubahan masuk log harga.</li></ul>
        <h3>c. Harga rata-rata bergerak</h3>
        <ul><li>Harga net master = harga terbaru; harga rata-rata = biaya sebenarnya stok yang dimiliki (PSAK 14 mengizinkan rata-rata tertimbang).</li><li>Saat harga naik tiap tahun, menilai stok lama dengan harga baru membuat nilai persediaan & HPP melonjak. Gunakan metode <b>RATA</b> di <?php echo $a('LaporanGudang/harga_rata', 'Harga rata-rata'); ?>.</li><li><?php echo $a('LaporanGudang/kenaikan_harga', 'Kenaikan harga beli tahunan'); ?> memberi indeks harga & dampak biaya untuk anggaran dan penyesuaian tarif jual.</li></ul>
        <h3>d. Label QR</h3>
        <ul><li>QR dibuat di browser (tanpa file gambar) — tidak gagal lagi untuk no. part berisi "/" atau spasi.</li><li>Template printer thermal 50×30 / 40×20 / 70×40 mm dan stiker A4 3×8 / 4×10; cetak per dokumen Part Masuk.</li></ul>
    </div></div>

    <div class="card pd-sec" id="rutin"><div class="card-body">
        <h2>5. Kalender kerja bulanan</h2>
        <table class="table table-sm mb-0"><thead><tr><th>Waktu</th><th>Gudang</th><th>Akuntansi</th><th>HRD</th></tr></thead><tbody>
            <tr><td>Setiap hari</td><td>Input masuk/keluar, cek stok minimum</td><td>Kas & bank, faktur</td><td>Tarik data mesin absen</td></tr>
            <tr><td>Setiap minggu</td><td>PO outstanding, cycle count 1 rak</td><td>Jatuh tempo piutang/hutang</td><td>Rekap cuti</td></tr>
            <tr><td>Akhir bulan</td><td>Pemeriksaan data gudang, hitung ulang harga rata-rata</td><td>Penyusutan, tutup masa PPN, laba rugi</td><td>Rekap absensi & keterlambatan</td></tr>
            <tr><td>Akhir tahun</td><td>Opname penuh, analisis ABC & slow moving</td><td>Rekon fiskal, jurnal penutup</td><td>Kelengkapan data, penghargaan masa kerja</td></tr>
        </tbody></table>
    </div></div>

    <div class="card pd-sec" id="lanjut"><div class="card-body">
        <h2>6. Rekomendasi pengembangan berikutnya</h2>
        <ol class="mb-0">
            <li><b>Payroll</b> terhubung ke Akuntansi (beban gaji, hutang PPh 21 TER, BPJS) dan ke data absensi & cuti.</li>
            <li><b>Jurnal otomatis gudang</b>: Part Masuk → Persediaan/Hutang, Part Keluar WO → HPP, memakai harga rata-rata.</li>
            <li><b>Faktur service</b> langsung dari Work Order ke Piutang Usaha (jasa + part).</li>
            <li><b>Transfer antar gudang</b> sebagai dokumen resmi (keluar gudang A, masuk gudang B) dengan label QR.</li>
            <li><b>Kontrak kerja & pengingat</b> habis kontrak PKWT, saldo hak cuti per pegawai.</li>
        </ol>
    </div></div>
    </div>
</div>
