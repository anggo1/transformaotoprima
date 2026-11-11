<?php defined('BASEPATH') OR exit('No direct script access allowed');
$contoh = array(
    'KAS_BANK' => 'Kas Besar, Kas Kecil, Bank BCA, Bank Mandiri', 'PIUTANG' => 'Piutang Usaha, Piutang Karyawan',
    'PERSEDIAAN' => 'Persediaan Spare Part, Material Bodi, Cat', 'ASET_LANCAR' => 'Uang Muka Pembelian, Sewa Dibayar Dimuka, PPN Masukan, PPh 22/23/25 Dibayar Dimuka',
    'ASET_TETAP' => 'Tanah, Bangunan, Mesin, Kendaraan, Peralatan', 'AKUM_SUSUT' => 'Akumulasi Penyusutan Bangunan / Mesin / Kendaraan',
    'ASET_LAIN' => 'Deposit Jaminan, Aset Tak Berwujud', 'HUTANG' => 'Hutang Usaha / Hutang Supplier',
    'LIAB_PENDEK' => 'Hutang Gaji, PPN Keluaran, Hutang PPN, Hutang PPh 21/23/4(2)/29, Uang Muka Pelanggan', 'LIAB_PANJANG' => 'Hutang Bank Jangka Panjang, Hutang Leasing',
    'EKUITAS' => 'Modal Disetor, Laba Ditahan, Laba Tahun Berjalan, Prive/Dividen', 'PENDAPATAN' => 'Pendapatan Jasa Karoseri, Penjualan Spare Part, Jasa Service',
    'HPP' => 'Pemakaian Material, Upah Borongan, Biaya Subkon', 'BEBAN' => 'Gaji Kantor, Listrik, Sewa, Penyusutan, Transport',
    'PENDAPATAN_LAIN' => 'Bunga/Jasa Giro, Laba Pelepasan Aset, Selisih Kurs', 'BEBAN_LAIN' => 'Administrasi Bank, Rugi Pelepasan Aset, Denda',
    'PAJAK_PENGHASILAN' => 'Beban PPh Badan, Beban PPh Final UMKM',
);
$grupLap = function ($g) { return in_array($g, array('ASET', 'LIABILITAS', 'EKUITAS'), true) ? 'Neraca' : 'Laba Rugi'; };
$ok = 0; foreach ($cek as $c) if ($c[1] === true) $ok++;
$URL = array('account' => 'Account', 'saldo' => 'Saldo', 'jurnal' => 'jurnal', 'kasbank' => 'KasBank', 'piutang' => 'Piutang', 'hutang' => 'Hutang',
    'asettetap' => 'AsetTetap', 'anggaran' => 'Anggaran', 'pajak' => 'Pajak', 'periodeakuntansi' => 'PeriodeAkuntansi', 'settingacc' => 'SettingAcc', 'laporankeuangan' => 'LaporanKeuangan');
$link = function ($u, $t) { return '<a href="' . site_url($u) . '">' . $t . '</a>'; };
?>
<div class="pd-wrap">
    <nav class="pd-toc no-print">
        <div class="text-muted small font-weight-bold text-uppercase mb-2 px-2">Daftar isi</div>
        <a href="#alur">1. Alur sistem</a>
        <a href="#persiapan">2. Persiapan awal</a>
        <a href="#rutin">3. Rutinitas harian–tahunan</a>
        <a href="#menu">4. Fungsi setiap menu</a>
        <a href="#tipe">5. Tipe akun &amp; saldo normal</a>
        <a href="#jurnal">6. Contoh jurnal otomatis</a>
        <a href="#pajak">7. Referensi perpajakan</a>
        <a href="#laporan">8. Daftar laporan</a>
        <a href="#sumber">9. Kode sumber jurnal</a>
        <a href="#faq">10. Tanya jawab</a>
        <hr class="my-2">
        <a href="javascript:window.print()"><i class="fas fa-print mr-1"></i> Cetak panduan</a>
    </nav>

    <div>
    <!-- 1 ------------------------------------------------------------------>
    <div class="card pd-sec" id="alur"><div class="card-body">
        <h2>1. Alur sistem akuntansi</h2>
        <p>Modul Accounting bekerja seperti program akuntansi Accurate: setiap transaksi dicatat di <b>menu transaksi</b>, program membuat <b>jurnal otomatis</b>, lalu semua <b>laporan</b> dihitung langsung dari jurnal tersebut. Anda tidak perlu memindahkan angka secara manual.</p>
        <div class="pd-flow">
            <div><strong>1. Data awal</strong>Daftar Akun, Saldo Awal, Preferensi, Pengaturan Pajak</div>
            <div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>2. Transaksi</strong>Piutang (faktur jual), Hutang (faktur beli), Kas &amp; Bank, Aset Tetap, Jurnal Umum</div>
            <div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>3. Jurnal otomatis</strong>Buku besar per akun + buku pembantu (pelanggan, pemasok, aset, pajak)</div>
            <div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>4. Proses periodik</strong>Penyusutan, tutup masa PPN, setor pajak, kunci periode, PPh Badan, jurnal penutup</div>
            <div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
            <div><strong>5. Laporan</strong>Neraca, Laba Rugi, Arus Kas, Ekuitas, CaLK, laporan pajak, rasio</div>
        </div>
        <p class="mb-0">Prinsip penting: <b>satu transaksi dicatat sekali di menu yang tepat</b>. Contoh: pembayaran faktur pemasok dicatat di Hutang Usaha → Pelunasan (bukan di Kas &amp; Bank) agar status faktur, umur hutang dan buku besar ikut berubah bersamaan.</p>
    </div></div>

    <!-- 2 ------------------------------------------------------------------>
    <div class="card pd-sec" id="persiapan"><div class="card-body">
        <h2>2. Persiapan awal <small class="text-muted">(<?php echo $ok; ?> dari <?php echo count($cek); ?> selesai — dicek langsung dari data Anda)</small></h2>
        <ul class="pd-check">
        <?php foreach ($cek as $c) { ?>
            <li class="mb-2"><i class="fas <?php echo $c[1] === true ? 'fa-check-circle' : ($c[1] === null ? 'fa-info-circle text-info' : 'fa-times-circle'); ?> mr-1"></i>
                <b><?php echo html_escape($c[0]); ?></b> — <span class="text-muted"><?php echo html_escape($c[2]); ?></span>
                <?php if ($c[1] !== true) { ?> · <?php echo $link($c[3], 'perbaiki'); ?><?php } ?></li>
        <?php } ?>
        </ul>
        <h3>Urutan pengisian bila mulai dari nol</h3>
        <ol>
            <li><?php echo $link('Account', 'Daftar Akun'); ?>: lengkapi akun dan <b>Tipe Akun</b> (lihat bagian 5). Tandai perlakuan fiskal untuk akun yang perlu koreksi pajak.</li>
            <li><?php echo $link('PeriodeAkuntansi', 'Tutup Buku &amp; Preferensi'); ?>: isi akun default (piutang, hutang, PPN, laba ditahan, penyusutan).</li>
            <li><?php echo $link('Pajak', 'Perpajakan'); ?> → Pengaturan: NPWP, status PKP, rezim PPh (umum / final UMKM), akun-akun pajak.</li>
            <li><?php echo $link('Saldo', 'Saldo Awal'); ?>: saldo semua akun neraca per 1 Januari. Faktur yang belum lunas sebaiknya diinput sebagai faktur (tanggal asli) agar umur piutang/hutang akurat; dalam hal itu jangan masukkan lagi saldo akun piutang/hutangnya di saldo awal.</li>
            <li><?php echo $link('AsetTetap', 'Aset Tetap'); ?>: daftarkan aset yang sudah dimiliki tanpa jurnal perolehan (nilainya sudah ada di saldo awal), lalu isi akumulasi penyusutan sampai tanggal mulai.</li>
            <li>Buka <?php echo $link('LaporanKeuangan/cek_data', 'Pemeriksaan Kesehatan Data'); ?> — semua harus hijau sebelum mulai bertransaksi.</li>
        </ol>
    </div></div>

    <!-- 3 ------------------------------------------------------------------>
    <div class="card pd-sec" id="rutin"><div class="card-body">
        <h2>3. Rutinitas kerja</h2>
        <div class="row">
            <div class="col-lg-4">
                <h3><i class="fas fa-sun text-warning mr-1"></i> Harian</h3>
                <ul>
                    <li>Faktur penjualan (Piutang Usaha) begitu pekerjaan/barang diserahkan; isi NSFP bila ber-PPN.</li>
                    <li>Faktur pemasok (Hutang Usaha) saat tagihan diterima.</li>
                    <li>Penerimaan dari pelanggan &amp; pembayaran ke pemasok lewat <b>Pelunasan</b>, termasuk potongan PPh.</li>
                    <li>Kas masuk/keluar lain (biaya operasional, setoran tunai) di Kas &amp; Bank.</li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h3><i class="fas fa-calendar-alt text-info mr-1"></i> Akhir bulan (urut)</h3>
                <ol>
                    <li>Pastikan semua faktur &amp; transaksi kas bulan itu sudah masuk.</li>
                    <li>Rekonsiliasi bank: <?php echo $link('LaporanKeuangan/mutasi_kas', 'Mutasi Kas &amp; Bank'); ?> vs rekening koran; catat biaya admin &amp; bunga bank.</li>
                    <li>Jurnal penyesuaian: gaji &amp; PPh 21, biaya yang masih harus dibayar, beban dibayar dimuka yang terpakai.</li>
                    <li>Aset Tetap → <b>Proses Penyusutan</b>.</li>
                    <li>Perpajakan → <b>Tutup Masa PPN</b>.</li>
                    <li><?php echo $link('LaporanKeuangan/cek_data', 'Pemeriksaan Kesehatan Data'); ?>.</li>
                    <li>Cetak Laba Rugi, Neraca, Umur Piutang/Hutang.</li>
                    <li><b>Kunci periode</b> di Tutup Buku.</li>
                </ol>
                <p class="small text-muted mb-0">Bulan berikutnya: tgl 15 setor PPh 21/23/4(2)/25 (catat NTPN di Setoran Pajak); tgl 20 lapor SPT Masa PPh; paling lambat akhir bulan setor &amp; lapor SPT Masa PPN.</p>
            </div>
            <div class="col-lg-4">
                <h3><i class="fas fa-flag-checkered text-success mr-1"></i> Akhir tahun</h3>
                <ol>
                    <li>Jalankan rutinitas akhir bulan untuk Desember.</li>
                    <li>Stok opname; sesuaikan nilai persediaan dengan jurnal umum.</li>
                    <li>Tinjau umur piutang; pertimbangkan penghapusan piutang tak tertagih.</li>
                    <li>Perpajakan → isi koreksi fiskal → periksa <?php echo $link('LaporanKeuangan/rekon_fiskal', 'Rekonsiliasi Fiskal'); ?> → <b>Buat Jurnal PPh Badan</b>.</li>
                    <li>Cetak <?php echo $link('LaporanKeuangan/paket', 'Laporan Keuangan Lengkap + CaLK'); ?> (1 Jan – 31 Des).</li>
                    <li>Tutup Buku → <b>Jurnal Penutup</b>, lalu kunci Desember.</li>
                    <li>Setor PPh 29 &amp; lapor SPT Tahunan PPh Badan paling lambat akhir April; mulai angsuran PPh 25 tahun baru.</li>
                </ol>
            </div>
        </div>
    </div></div>

    <!-- 4 ------------------------------------------------------------------>
    <div class="card pd-sec" id="menu"><div class="card-body">
        <h2>4. Fungsi setiap menu</h2>
        <?php foreach ($halaman as $k => $h) { ?>
        <h3><?php echo $link(isset($URL[$k]) ? $URL[$k] : $k, html_escape($h['judul'])); ?></h3>
        <p class="mb-1"><?php echo $h['ringkas']; ?></p>
        <ol class="mb-1"><?php foreach ($h['langkah'] as $x) { ?><li><?php echo $x; ?></li><?php } ?></ol>
        <?php if ($h['catatan'] !== '') { ?><p class="small text-muted"><i class="fas fa-info-circle mr-1"></i><?php echo $h['catatan']; ?></p><?php } ?>
        <?php } ?>
    </div></div>

    <!-- 5 ------------------------------------------------------------------>
    <div class="card pd-sec" id="tipe"><div class="card-body">
        <h2>5. Tipe akun, posisi di laporan &amp; saldo normal</h2>
        <p>Tipe akun menentukan di mana akun tampil dan bagaimana saldonya dihitung. <b>Saldo normal Debit</b> berarti saldo bertambah bila didebit (aset, beban); <b>Kredit</b> berarti bertambah bila dikredit (liabilitas, ekuitas, pendapatan).</p>
        <div class="table-responsive"><table class="table table-sm pd-table">
            <thead><tr><th>Tipe akun</th><th>Laporan</th><th>Kelompok</th><th>Saldo normal</th><th>Contoh akun</th></tr></thead>
            <tbody>
            <?php foreach (Akuntansi::$KATEGORI as $k => $v) { ?>
                <tr><td><b><?php echo html_escape($v[0]); ?></b></td><td><?php echo $grupLap($v[1]); ?></td><td><?php echo ucfirst(strtolower($v[1])); ?></td><td><?php echo $v[2] === 'D' ? 'Debit' : 'Kredit'; ?></td><td><?php echo html_escape(isset($contoh[$k]) ? $contoh[$k] : ''); ?></td></tr>
            <?php } ?>
            </tbody>
        </table></div>
        <h3>Perlakuan fiskal akun</h3>
        <ul>
            <?php foreach (Akuntansi::$FISKAL as $k => $v) { ?><li><b><?php echo $k === '' ? 'Normal' : $k; ?></b> — <?php echo html_escape($v[0]); ?></li><?php } ?>
        </ul>
        <p class="small text-muted mb-0">Contoh koreksi positif (Ps. 9 UU PPh): sumbangan, sanksi/denda pajak, PPh yang dibebankan, biaya entertainment tanpa daftar nominatif, biaya pribadi pemegang saham. Contoh penghasilan final: bunga deposito/jasa giro, sewa tanah &amp; bangunan.</p>
    </div></div>

    <!-- 6 ------------------------------------------------------------------>
    <div class="card pd-sec" id="jurnal"><div class="card-body">
        <h2>6. Contoh jurnal yang dibuat otomatis</h2>
        <p>Contoh angka: jasa karoseri Rp100.000.000, PKP dengan PPN efektif 11% (12% × DPP nilai lain 11/12).</p>
        <div class="row">
            <div class="col-lg-6">
                <h3>a. Faktur penjualan (Piutang Usaha)</h3>
<div class="pd-jurnal">Dr Piutang Usaha              111.000.000
    Cr Pendapatan Jasa              100.000.000
    Cr PPN Keluaran                  11.000.000</div>
                <h3>b. Pelunasan, pelanggan memotong PPh 23 (2% × DPP)</h3>
<div class="pd-jurnal">Dr Bank                       109.000.000
Dr PPh 23 Dibayar Dimuka        2.000.000
    Cr Piutang Usaha                111.000.000</div>
                <h3>c. Faktur pembelian material (Hutang Usaha)</h3>
<div class="pd-jurnal">Dr Persediaan / Beban Material  50.000.000
Dr PPN Masukan                   5.500.000
    Cr Hutang Usaha                  55.500.000</div>
                <h3>d. Bayar jasa las Rp10 jt, kita potong PPh 23</h3>
<div class="pd-jurnal">Dr Hutang Usaha                10.000.000
    Cr Bank                           9.800.000
    Cr Hutang PPh 23                    200.000</div>
                <h3>e. Penyusutan bulanan</h3>
<div class="pd-jurnal">Dr Beban Penyusutan          x
    Cr Akumulasi Penyusutan       x</div>
            </div>
            <div class="col-lg-6">
                <h3>f. Tutup masa PPN (Keluaran &gt; Masukan)</h3>
<div class="pd-jurnal">Dr PPN Keluaran               11.000.000
    Cr PPN Masukan                    5.500.000
    Cr Hutang PPN                     5.500.000</div>
                <h3>g. Setor pajak (dengan NTPN)</h3>
<div class="pd-jurnal">Dr Hutang PPN / Hutang PPh xx   5.500.000
    Cr Bank                           5.500.000</div>
                <h3>h. Angsuran PPh 25</h3>
<div class="pd-jurnal">Dr PPh 25 Dibayar Dimuka      x
    Cr Bank                       x</div>
                <h3>i. PPh Badan akhir tahun</h3>
<div class="pd-jurnal">Dr Beban PPh Badan            (PPh terutang)
    Cr PPh 22/23 Dibayar Dimuka   (kredit pajak)
    Cr PPh 25 Dibayar Dimuka      (angsuran)
    Cr Hutang PPh Badan (Ps.29)   (sisa kurang bayar)</div>
                <h3>j. Jurnal penutup 31 Desember</h3>
<div class="pd-jurnal">Dr seluruh akun Pendapatan
    Cr seluruh akun HPP &amp; Beban
    Cr/Dr Laba Ditahan            (laba/rugi bersih)</div>
            </div>
        </div>
    </div></div>

    <!-- 7 ------------------------------------------------------------------>
    <div class="card pd-sec" id="pajak"><div class="card-body">
        <h2>7. Referensi perpajakan</h2>
        <p>Profil saat ini: <b><?php echo $profil->pkp ? 'PKP' : 'Non-PKP'; ?></b>, rezim PPh <b><?php echo $profil->rezim === 'FINAL_UMKM' ? 'Final UMKM' : 'umum (Pasal 17 + 31E)'; ?></b>. Ringkasan ketentuan umum di bawah ini untuk membantu operasional; aturan pajak dapat berubah, jadi selalu cek <a href="https://www.pajak.go.id" target="_blank" rel="noopener">pajak.go.id</a> atau konsultan pajak Anda.</p>
        <div class="table-responsive"><table class="table table-sm pd-table">
            <thead><tr><th>Pajak</th><th>Kapan / objek</th><th>Tarif umum</th><th>Akun di program</th><th>Setor</th><th>Lapor</th></tr></thead>
            <tbody>
            <tr><td><b>PPN</b></td><td>Penyerahan barang/jasa kena pajak oleh PKP</td><td>12% × DPP. Untuk barang/jasa non-mewah DPP = 11/12 × harga jual → efektif 11% (PMK 131/2024)</td><td>PPN Keluaran, PPN Masukan → Hutang PPN (tutup masa)</td><td>Akhir bulan berikutnya, sebelum SPT disampaikan</td><td>SPT Masa PPN, akhir bulan berikutnya</td></tr>
            <tr><td><b>PPh 21</b></td><td>Gaji, upah, honor karyawan</td><td>Tarif Efektif Rata-rata (TER) bulanan; masa Desember dihitung ulang dengan tarif Ps. 17 (PP 58/2023)</td><td>Hutang PPh 21</td><td>Tgl 15 bulan berikutnya</td><td>SPT Masa PPh 21/26, tgl 20</td></tr>
            <tr><td><b>PPh 23</b></td><td>Jasa (teknik, perbaikan, manajemen, dll.) &amp; sewa harta selain tanah/bangunan; dividen, bunga, royalti</td><td>2% × bruto (tanpa PPN) untuk jasa/sewa; 15% untuk dividen/bunga/royalti; lebih tinggi 100% bila penerima tanpa NPWP</td><td>Dipotong pelanggan: PPh 23 Dibayar Dimuka. Kita potong: Hutang PPh 23</td><td>Tgl 15 bulan berikutnya</td><td>SPT Masa PPh Unifikasi, tgl 20</td></tr>
            <tr><td><b>PPh 4 ayat (2)</b></td><td>Sewa tanah/bangunan, jasa konstruksi, bunga deposito, dll. (final)</td><td>Sewa tanah/bangunan 10%; jasa konstruksi 1,75%–6% sesuai kualifikasi (PP 9/2022)</td><td>Hutang PPh 4(2)</td><td>Tgl 15 bulan berikutnya</td><td>SPT Masa PPh Unifikasi, tgl 20</td></tr>
            <tr><td><b>PPh 22</b></td><td>Dipungut pihak lain saat impor/pembelian tertentu</td><td>Sesuai jenis transaksi</td><td>PPh 22 Dibayar Dimuka (kredit pajak)</td><td>—</td><td>—</td></tr>
            <tr><td><b>PPh 25</b></td><td>Angsuran bulanan PPh Badan tahun berjalan</td><td>(PPh terutang tahun lalu − kredit PPh 22/23) ÷ 12</td><td>PPh 25 Dibayar Dimuka</td><td>Tgl 15 bulan berikutnya</td><td>Dianggap dilaporkan bila setoran ber-NTPN</td></tr>
            <tr><td><b>PPh Badan</b></td><td>Laba fiskal setahun (Rekonsiliasi Fiskal)</td><td>22% (Ps. 17). Fasilitas Ps. 31E: bruto ≤ Rp50 M → diskon 50% atas PKP bagian Rp4,8 M ÷ bruto</td><td>Beban PPh Badan, Hutang PPh Badan</td><td>PPh 29 sebelum SPT dilaporkan</td><td>SPT Tahunan 1771, akhir bulan ke-4</td></tr>
            <tr><td><b>PPh Final UMKM</b></td><td>Alternatif bagi WP dengan peredaran bruto ≤ Rp4,8 M/tahun, dalam jangka waktu terbatas</td><td>0,5% × peredaran bruto bulanan (PP 55/2022 jo. PP 20/2026). Untuk PT, jangka waktunya 3 tahun pajak sejak terdaftar dan DJP menyatakan tidak diperpanjang</td><td>Beban PPh Final UMKM</td><td>Tgl 15 bulan berikutnya</td><td>Dianggap dilaporkan bila setoran ber-NTPN</td></tr>
            </tbody>
        </table></div>
        <h3>Istilah penting</h3>
        <ul>
            <li><b>PKP</b> — Pengusaha Kena Pajak, wajib memungut PPN (wajib bila omzet &gt; Rp4,8 M/tahun).</li>
            <li><b>DPP</b> — Dasar Pengenaan Pajak (harga jual setelah diskon, sebelum PPN).</li>
            <li><b>NSFP</b> — Nomor Seri Faktur Pajak dari Coretax; wajib untuk setiap faktur ber-PPN.</li>
            <li><b>NTPN</b> — Nomor Transaksi Penerimaan Negara; bukti sah setoran pajak.</li>
            <li><b>Kredit pajak</b> — pajak yang sudah dibayar/dipotong di muka (PPh 22, 23, 25) dan mengurangi PPh Badan terutang.</li>
            <li><b>Kurang/lebih bayar</b> — selisih pajak terutang dengan kredit pajak. Lebih bayar PPN dikompensasikan ke masa berikutnya.</li>
            <li><b>Peredaran bruto</b> — seluruh penghasilan usaha sebelum dikurangi biaya, tidak termasuk penghasilan yang sudah dikenai PPh final.</li>
        </ul>
        <p class="small text-muted mb-0">Dasar hukum utama: UU PPh &amp; UU PPN sebagaimana diubah UU 7/2021 (HPP); PMK 131/2024 (tarif PPN 12% &amp; DPP nilai lain); PMK 81/2024 (Coretax, batas setor &amp; lapor); PP 58/2023 &amp; PMK 168/2023 (TER PPh 21); PP 55/2022 jo. PP 20/2026 (PPh final UMKM); PP 9/2022 (jasa konstruksi).</p>
    </div></div>

    <!-- 8 ------------------------------------------------------------------>
    <div class="card pd-sec" id="laporan"><div class="card-body">
        <h2>8. Daftar laporan &amp; kegunaannya</h2>
        <?php $g = null; foreach ($laporan as $k => $l) { if ($l[3] !== $g) { $g = $l[3]; ?><h3><?php echo html_escape($g); ?></h3><?php } ?>
        <p class="mb-2"><?php echo $link('LaporanKeuangan/' . $k, '<b>' . html_escape($l[0]) . '</b>'); ?> — <?php echo isset($penjelasan[$k]) ? $penjelasan[$k]['tujuan'] : html_escape($l[2]); ?></p>
        <?php } ?>
        <p class="small text-muted mb-0">Setiap laporan memiliki kotak <b>"Tentang laporan ini"</b> berisi cara membaca, sumber data dan tips. Semua laporan dapat dicetak (Simpan sebagai PDF dari jendela cetak) dan diekspor ke Excel.</p>
    </div></div>

    <!-- 9 ------------------------------------------------------------------>
    <div class="card pd-sec" id="sumber"><div class="card-body">
        <h2>9. Kode sumber jurnal</h2>
        <p>Kolom <b>Sumber</b> di Daftar Jurnal &amp; Buku Besar menunjukkan modul asal jurnal. Jurnal selain JU tidak diedit dari Jurnal Umum — ubah atau batalkan dari menu asalnya.</p>
        <table class="table table-sm pd-table" style="max-width:640px"><tbody>
        <?php foreach (Akuntansi::$SUMBER as $k => $v) { ?><tr><td style="width:80px"><code><?php echo $k; ?></code></td><td><?php echo html_escape($v); ?></td></tr><?php } ?>
        </tbody></table>
    </div></div>

    <!-- 10 ----------------------------------------------------------------->
    <div class="card pd-sec" id="faq"><div class="card-body">
        <h2>10. Tanya jawab &amp; pemecahan masalah</h2>
        <h3>Neraca tidak seimbang / ada baris "Selisih".</h3>
        <p>Jalankan <?php echo $link('LaporanKeuangan/cek_data', 'Pemeriksaan Kesehatan Data'); ?>. Penyebab paling umum: Saldo Awal tidak seimbang, atau ada jurnal lama yang debit ≠ kredit.</p>
        <h3>Sebuah akun tidak muncul di Laba Rugi / Neraca.</h3>
        <p>Periksa Tipe Akun-nya di Daftar Akun. Akun tanpa tipe ditebak dari kode awalnya dan bisa salah tempat.</p>
        <h3>PPN di Rincian PPN berbeda dengan total faktur.</h3>
        <p>Rekap PPN mengikuti jurnal akun PPN. Selisih berarti ada PPN yang dicatat lewat Kas &amp; Bank atau Jurnal Umum (mis. pembelian tunai dengan faktur pajak). Itu sah, asalkan ada faktur pajaknya.</p>
        <h3>Salah input faktur.</h3>
        <p>Bila belum ada pelunasan: batalkan faktur lalu buat ulang. Bila sudah ada pelunasan: hapus pelunasannya dulu. Periode yang terkunci harus dibuka dulu di Tutup Buku.</p>
        <h3>Bagaimana mencatat gaji dan PPh 21?</h3>
        <p>Jurnal Umum atau Kas &amp; Bank Keluar: Dr Beban Gaji (bruto) / Cr Bank (gaji bersih) / Cr Hutang PPh 21 (potongan). Saat setor, catat di Perpajakan → Setoran (jenis PPh 21).</p>
        <h3>Kapan memakai Jurnal Umum dan kapan Kas &amp; Bank?</h3>
        <p>Bila transaksi melibatkan uang masuk/keluar rekening, pakai Kas &amp; Bank (atau Pelunasan bila terkait faktur). Jurnal Umum hanya untuk transaksi tanpa kas: penyesuaian, koreksi, reklasifikasi.</p>
        <h3>Laba di Laba Rugi besar tetapi kas menipis.</h3>
        <p>Lihat <?php echo $link('LaporanKeuangan/arus_kas', 'Arus Kas'); ?> dan <?php echo $link('LaporanKeuangan/rasio', 'Analisis Rasio'); ?> (umur piutang &amp; persediaan). Biasanya piutang belum tertagih atau persediaan menumpuk.</p>
        <h3>Ingin mencetak semua laporan tahunan sekaligus.</h3>
        <p class="mb-0">Gunakan <?php echo $link('LaporanKeuangan/paket', 'Laporan Keuangan Lengkap + CaLK'); ?> dengan periode 1 Januari – 31 Desember, lalu Cetak → Simpan sebagai PDF.</p>
    </div></div>
    </div>
</div>
