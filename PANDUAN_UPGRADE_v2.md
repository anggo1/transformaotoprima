# TOP ERP v2 — Catatan Upgrade

## Langkah pemasangan (urut)
1. **Backup** database (phpMyAdmin → Export) dan folder `htdocs/top`.
2. Pilih SALAH SATU:
   - **Database yang sedang dipakai (disarankan):** jalankan `database/migrasi_v2_akuntansi.sql` di phpMyAdmin pada database `db_transforma_oto_prima`. Data tidak dihapus dan file boleh dijalankan ulang (bagian yang sudah ada dilewati).
   - **Instalasi baru / database kosong:** import `database/db_transforma_oto_prima_v2_final.sql` — berisi struktur + data lengkap dalam kondisi akhir (data per dump 11 Nov 2026).
3. Logout lalu login ulang (menu di-cache per sesi).
4. Buka **Accounting › Tutup Buku & Preferensi › Preferensi Akun**, periksa akun default (Laba Ditahan, Piutang, Hutang, PPN, Akumulasi Penyusutan).
5. Buka **Accounting › Daftar Akun**, periksa kolom *Tipe Akun* (sudah dipetakan otomatis dari nomor/nama akun; ubah bila kurang tepat).
6. (Opsional) Jalankan `tools/bersihkan_file_lama.bat` untuk memindahkan file lama/berisiko ke folder `_to_delete`.

Mode pengembangan (error tampil di layar): buka `.htaccess`, hapus `#` di baris `SetEnv CI_ENV development`.

## Kompatibilitas PHP
- Diuji pada PHP 8.3 dengan `error_reporting(E_ALL)`; kompatibel PHP 7.4 – 8.4.
- CodeIgniter 3.1.13 di-patch: `#[\AllowDynamicProperties]` (PHP 8.2+), konstanta `E_STRICT` & pengaturan sesi yang deprecated di PHP 8.4.
- `number_format()` di seluruh aplikasi diganti `nf()` (aman bila nilai kosong/teks — di PHP 8 sebelumnya Fatal Error).
- Fungsi `show_succ_msg()` yang dipanggil di beberapa controller tetapi tidak pernah ada (Fatal Error) sudah ditambahkan.
- Default environment `production`: notice/deprecated tidak lagi merusak respon AJAX; error tetap dicatat di `application/logs`.

## Keamanan
- Login: regenerasi ID sesi, cek user nonaktif, hash password tidak lagi disimpan di sesi, jeda setelah 5x gagal.
- Ganti password memakai ID user dari sesi (sebelumnya bisa mengganti password user lain lewat form).
- ±1.500 nilai yang disisipkan langsung ke query SQL di 70 model/controller kini di-escape (`escape_str`) — mencegah SQL injection tanpa mengubah alur.
- `.htaccess`: blokir akses web ke folder `application`, `system`, `.git`, file `.sql`/`.log`/`.bat` (sebelumnya dump database bisa diunduh siapa saja), kompresi & cache aset.
- AJAX dengan sesi habis → otomatis diarahkan ke halaman login.

## Tampilan & performa
- Tema baru (layout, sidebar, tabel, form, modal) + halaman login baru; struktur HTML halaman lama tidak diubah.
- Sidebar: pencarian menu (tekan `/`), status buka/tutup diingat, menu dibangun dengan 2 query (sebelumnya 2 query per menu) dan di-cache 10 menit.
- Script dibuang dari setiap halaman: jQuery UI, jqvmap+peta USA, knob, summernote, colorpicker, duallistbox, bootstrap-switch, inputmask, overlayScrollbars, demo.js. pdfmake (±2 MB) hanya dimuat saat tombol PDF diklik; Chart.js hanya di dashboard.
- Modal yang di-echo controller kini dicetak di dalam `<body>` (sebelumnya sebelum `<!DOCTYPE>` → browser masuk quirks mode).
- Dashboard baru: KPI keuangan & gudang + grafik yang benar-benar terisi data (grafik lama kosong / error JS).

## Modul Akuntansi (konsep Accurate)
| Menu | Fungsi |
|---|---|
| Daftar Akun | Tipe akun standar (Kas & Bank, Piutang, Persediaan, Aset Tetap, Akumulasi, Hutang, Liabilitas, Ekuitas, Pendapatan, HPP, Beban, dll.), sub-akun, saldo normal, status aktif, saldo hari ini; akun yang sudah dipakai tidak bisa dihapus |
| Jurnal Umum | Alur lama tetap; kini wajib seimbang, draft per user (tidak tercampur antar user), cek tutup buku |
| Kas & Bank | Penerimaan, Pengeluaran (multi baris), Transfer antar kas/bank; jurnal otomatis; cetak bukti kas + terbilang; pembatalan |
| Piutang Usaha | Faktur penjualan (multi baris, diskon, PPN), penerimaan pembayaran sebagian/lunas, status, jatuh tempo, cetak faktur |
| Hutang Usaha | Faktur pembelian/tagihan pemasok, pembayaran hutang |
| Aset Tetap | Daftar aset, jurnal perolehan, penyusutan otomatis (garis lurus / saldo menurun ganda) per bulan, jadwal penyusutan, pelepasan (jual/hapus) dengan laba/rugi pelepasan |
| Anggaran | Anggaran per akun per bulan |
| Tutup Buku & Preferensi | Kunci/buka periode bulanan, jurnal penutup tahunan, akun default |

**Pusat Laporan Keuangan** (cetak + ekspor Excel): Neraca (bisa dibandingkan dua tanggal), Laba Rugi (standar / per bulan / banding periode), Arus Kas (metode langsung), Perubahan Ekuitas, Neraca Saldo, Buku Besar (saldo berjalan), Daftar Jurnal, Mutasi Kas & Bank, Umur Piutang, Umur Hutang, Daftar Aset Tetap, Anggaran vs Realisasi.

Laporan Gudang baru: **Nilai Persediaan & Stok Minimum** per lokasi (Cibitung/Jakarta/Surabaya).

Semua transaksi modul baru ditulis ke `tbl_acc_jurnal_umum` (kolom `sumber` membedakan asal transaksi), sehingga Buku Besar lama tetap membaca data yang sama.

### Aturan saldo awal
Saldo awal periode *Y* (menu Saldo Awal) = saldo per 1 Januari *Y*. Laporan memakai saldo awal tahun terakhir ≤ tanggal laporan, ditambah jurnal sejak 1 Januari tahun tersebut. Jika Neraca menampilkan peringatan "tidak seimbang", periksa saldo awal (total debit harus sama dengan total kredit).

---

# Tambahan v3 — Perpajakan, laporan detail & panduan

## Cara memasang
1. Salin folder `application`, `assets`, `tools` ke `C:\xampp\htdocs\top` (timpa).
2. Database — pilih **salah satu**:
   - Database yang sedang dipakai (`db_transforma_oto_prima`): jalankan `database/migrasi_v2_akuntansi.sql` di phpMyAdmin (tab SQL / Import). Aman diulang; bila v2 sudah pernah dijalankan, hanya bagian v3 yang bertambah.
   - Database baru: import `database/db_transforma_oto_prima_v2_final.sql` lalu ubah `'database'` di `application/config/database.php` menjadi `db_transforma_oto_prima_v2`.
3. Logout lalu login lagi (menu disimpan di sesi 10 menit).
4. Buka **Accounting → Panduan Akuntansi & Pajak** dan selesaikan daftar "Persiapan awal" (NPWP, akun pajak, saldo awal seimbang).
5. Opsional: jalankan `tools/bersihkan_file_lama.bat` — kini juga memindahkan file Buku Besar lama.

## Menu baru / berubah
| Menu | Isi |
|---|---|
| Accounting → Panduan Akuntansi & Pajak | Alur sistem, checklist persiapan yang dicek otomatis dari data, rutinitas harian/bulanan/tahunan, fungsi setiap menu, tipe akun, contoh jurnal, referensi pajak, kode sumber jurnal, tanya jawab |
| Accounting → Perpajakan | Ringkasan & kalender kewajiban, tutup masa PPN, setoran pajak dengan NTPN, koreksi fiskal, PPh Badan (Ps. 17 + 31E) / PPh Final UMKM, pengaturan pajak |
| Piutang/Hutang Usaha | Nomor Seri Faktur Pajak; potongan PPh 23/4(2) saat pelunasan + nomor bukti potong |
| Daftar Akun | Tipe baru *Beban Pajak Penghasilan* dan kolom *Perlakuan Fiskal* (koreksi positif / penghasilan final) |
| Accounting → Kelompok Akun & Akun Pemasok | Nama baru "Panel Setting" (isi tidak berubah) |
| Buku Besar lama | Diarahkan otomatis ke Laporan Keuangan → Buku Besar |
| Setiap halaman akuntansi | Kotak **Panduan halaman ini** (bisa dilipat) — teksnya di `application/config/panduan.php` |
| Setiap laporan | Kotak **Tentang laporan ini**: tujuan, cara membaca, sumber data, dasar aturan |

## Laporan baru (Pusat Laporan Keuangan)
- **Laporan Keuangan Utama**: Laporan Keuangan Lengkap + CaLK (neraca komparatif, laba rugi, ekuitas, arus kas, catatan otomatis); Laba Rugi kini bisa dengan kolom % pendapatan.
- **Piutang & Hutang**: Kartu Piutang / Kartu Hutang (buku pembantu + rekonsiliasi ke akun kontrol), Rincian Faktur Penjualan / Pembelian (DPP, PPN, NSFP, sisa, keterlambatan, rincian baris).
- **Perpajakan**: Rekap Pajak Tahunan, Rincian PPN per Masa (bahan SPT Masa PPN, DPP nilai lain 11/12), Buku Pembantu Akun Pajak, Daftar Bukti Potong PPh, Daftar Setoran Pajak (NTPN), Rekonsiliasi Fiskal & PPh Badan.
- **Aset**: Jadwal Penyusutan per bulan (realisasi + proyeksi).
- **Analisis & Kontrol**: Analisis Rasio Keuangan (likuiditas, solvabilitas, profitabilitas, aktivitas + tafsiran), Pemeriksaan Kesehatan Data (11 pemeriksaan otomatis + saran perbaikan).

Referensi pajak yang dipakai: UU PPh/PPN jo. UU HPP, PMK 131/2024 (PPN 12%, DPP nilai lain), PMK 81/2024 (Coretax, batas setor/lapor), PP 58/2023 (TER PPh 21), PP 55/2022 jo. PP 20/2026 (PPh final UMKM), PP 9/2022 (jasa konstruksi). Aturan dapat berubah — cek pajak.go.id.

---

# Tambahan v4 — Gudang, Tampilan & Bahasa

## Cara memasang
Sama seperti sebelumnya: salin folder `application` dan `assets`, jalankan `database/migrasi_v2_akuntansi.sql` (aman diulang — hanya bagian yang belum ada yang ditambahkan), lalu logout & login lagi.

## Tampilan & Bahasa (setiap pengguna)
- Menu: klik nama pengguna (kanan atas) → **Tampilan & Bahasa**, atau ikon pengaturan di kartu pengguna (bawah sidebar).
- Bisa diatur: bahasa **Indonesia / English**, tema **terang / gelap / ikuti perangkat**, 8 warna aksen, gaya sidebar (gelap / terang / warna aksen), kepadatan, ukuran huruf, font, sidebar ringkas, judul kelompok menu.
- Navbar: tombol cepat ganti bahasa (ID/EN) dan mode gelap.
- **Menu favorit**: arahkan kursor ke submenu di sidebar lalu klik ☆ — menu tersemat di atas sidebar.
- Super Admin / Admin System dapat menyimpan pilihan sebagai **default untuk semua pengguna** (`application/config/tampilan.php`).
- Bahasa Inggris menerjemahkan menu, navigasi, label umum & tabel; isi panduan dan data transaksi tetap berbahasa Indonesia. Terjemahan dapat ditambah di `application/language/english/top_lang.php`.
- Sidebar baru: kelompok menu (Utama, Operasional, Gudang, Keuangan, Sistem), favorit, jumlah submenu, kartu pengguna, pencarian menu (tekan `/`).

## Gudang
- **Pengaturan Gudang** (dulu Panel Setting Gudang) disusun ulang menjadi tab: Satuan, Kategori/Divisi, Kelompok Rak, Type Mesin, Supplier, Customer, Jenis Pesanan — lengkap dengan penjelasan & jumlah data. Proses tambah/ubah/hapus tetap sama.
- Perbaikan tata letak: beberapa modal lama memiliki `</div>` berlebih sehingga isi halaman keluar ke bawah sidebar (ruang kosong besar). Kini dirapikan otomatis untuk semua modal.
- Ikon tombol ubah/hapus yang kosong diperbaiki; pustaka ionicons (±60 KB) tidak lagi dimuat.
- Menu gudang dirapikan (urutan alur, ikon, nama); submenu kosong "Transfer Stock Part" dihapus; tab Voucher yang tidak dipakai transaksi disembunyikan.
- **Dashboard Gudang** (menu utama) & **Pusat Laporan Gudang**: Kartu Stok, Mutasi Stok, Nilai Persediaan, Stok Minimum & Saran Pesan, Slow Moving & Dead Stock, Analisis ABC, Pemakaian per Tujuan/Divisi, Penerimaan per Supplier, PO Outstanding, Selisih Stock Opname, Pemeriksaan Data Gudang — semua bisa dicetak & diekspor Excel, dengan kotak penjelasan.
- **Panduan Gudang**: alur pembelian / penjualan / pemakaian / kontrol, cek kesiapan data, rutinitas, fungsi setiap menu, referensi manajemen persediaan (ROP, safety stock, ABC, FIFO/FEFO, opname siklus, perputaran, akurasi stok), hubungan dengan Akuntansi, tanya jawab.
- Setiap halaman gudang memiliki kotak **Panduan halaman ini**.

---

# Tambahan v5 — Dashboard per Level/Departemen, Gudang Lanjutan, HRD & Grafik

## Cara memasang
1. Salin folder `application` dan `assets` (timpa).
2. Jalankan `database/migrasi_v2_akuntansi.sql` (bagian v5 menambah tabel `tbl_wh_setting`, `tbl_wh_penyesuaian`, `tbl_wh_so`, `tbl_wh_so_detail`, `tbl_wh_harga_rata`, `tbl_wh_impor`, `tbl_dashboard_layout`, `tbl_user_departement` serta menu baru; aman diulang). Atau import ulang `db_transforma_oto_prima_v2_final.sql` untuk database baru.
3. Logout & login lagi. Hak akses menu baru disalin dari menu sejenis (Stock Opname → Stock Opname Baru, Material & Sparepart → Impor/Label/Scan, Employe → Cuti/Mesin Absen/Laporan HRD, User → Pengaturan Dashboard). Sesuaikan di Management User bila perlu.

## Dashboard per level / departemen / user
- 32 widget (Keuangan, Gudang, After Sales, Marketing, HRD, Pengumuman, Pintasan) dimuat bertahap agar halaman cepat.
- **Pengaturan Dashboard** (Management User): atur susunan & lebar widget per **level user**, per **departemen**, atau per **user**; muat preset (Direksi, Akuntansi, Gudang, After Sales, Marketing, HRD, Umum); pratinjau; izinkan user mengatur dashboard pribadinya; tulis pengumuman; petakan user ke departemen & NIP.
- Prioritas: user → departemen → level → preset bawaan. Widget dari menu yang tidak boleh diakses user otomatis disembunyikan.
- Tampilan lama tetap tersedia di tombol **Klasik**.

## Gudang
- **Stock Opname (Baru)**: Draft → Penghitungan (stok sistem dikunci/snapshot) → Review selisih (hitung ulang otomatis untuk selisih di atas toleransi) → Disetujui (stok disesuaikan sebesar selisih + jurnal persediaan opsional, sumber `SO`). Cakupan: semua bersaldo, kategori, kelompok, awalan rak (cycle count), kelas A, daftar part. Hitung buta, scan QR/kamera, lembar hitung ber-QR, lembar Excel → impor, berita acara. Menu Stock Opname lama tetap ada.
- **Impor Barang (Excel)**: template & ekspor seluruh master, pratinjau BARU/UPDATE/SAMA/ERROR, mode tambah saja / tambah & perbarui / hanya harga, harga net dihitung otomatis dari price list − diskon, perubahan harga masuk log harga, stok awal barang baru tercatat (kartu stok jenis ADJ), riwayat impor.
- **Harga rata-rata bergerak**: dihitung ulang dari riwayat penerimaan; metode penilaian NET atau RATA dipakai seluruh laporan gudang. Laporan baru: **Harga Rata-rata vs Harga Net**, **Kenaikan Harga Beli Tahunan** (indeks harga, dampak biaya, rekomendasi), **Riwayat Harga per Barang**.
- **Label QR baru**: QR dibuat di browser (tidak lagi menulis PNG ke `assets/img_qr`, sehingga no. part berisi "/" atau spasi tetap berfungsi). Template 50×30, 40×20, 70×40 mm, stiker A4 3×8 & 4×10; pilih dari dokumen Part Masuk, filter rak/kategori, atau dokumen opname. Tombol QR di Material & Sparepart diarahkan ke halaman ini. **Scan Barang**: kamera HP / scanner USB untuk cek stok, rak & transaksi.
- Kartu stok & laporan mutasi kini menyertakan penyesuaian (jenis **ADJ**) dari opname & impor.

## HRD
- **Dashboard & Laporan HRD**: Komposisi Pegawai, Masa Kerja & Penghargaan, Ulang Tahun, Rekap Cuti Tahunan, Rekap Absensi Bulanan (hadir, terlambat, jam kerja), Kelengkapan Data Pegawai.
- Menu **Cuti Pegawai** & **Mesin Absen** (sebelumnya tidak tampil di menu) ditambahkan. Perbaikan: helper `parse` bentrok dengan fungsi bahasa sehingga halaman Mesin Absen error — sudah diperbaiki.

## Grafik di semua laporan
- Laporan keuangan, gudang & HRD menampilkan grafik (batang, garis, area, donat, pareto). Setiap grafik: unduh **PNG**, unduh **data (CSV/Excel)**, **cetak**. Grafik ikut tercetak pada mode Cetak, dan datanya ikut di ekspor Excel.

## Panduan Sistem
- Peta integrasi antar modul, dashboard & laporan rekomendasi per departemen, kesiapan data lintas modul, kalender kerja bulanan, dan rekomendasi pengembangan berikutnya (payroll, jurnal otomatis gudang, faktur service, transfer antar gudang, kontrak kerja).

---

# Tambahan v6 — Pemasangan di Server (MySQL 8.0)

Database server (dump 12-11-2026, MySQL 8.0.46) sudah diperiksa dan diuji di MySQL 8.0.46 yang sama persis.

## Kondisi server sebelum upgrade
- 75 tabel, struktur lama (belum ada modul akuntansi v2–v5). Setelah migrasi menjadi 97 tabel.
- Data server sedikit lebih baru dari data lokal — **jangan** import `db_transforma_oto_prima_v2_final.sql` ke server (itu data lokal 11 Nov).

## Langkah di server
1. Upload folder `application` (termasuk `application/core/MY_Controller.php` yang baru) dan `assets`.
2. phpMyAdmin server → Export database `db_transforma_oto_prima` (backup).
3. Pilih SALAH SATU:
   - **Disarankan:** jalankan `database/migrasi_v2_akuntansi.sql` di database server. Data tetap utuh, aman diulang.
   - **Alternatif:** import `database/db_transforma_oto_prima_server_siap.zip` (data server 12-11-2026 09:14 + semua perubahan). File ini menimpa tabel; transaksi yang diinput setelah jam tersebut hilang.
4. Logout & login lagi.

## Yang disesuaikan untuk server
- **MySQL 8 `ONLY_FULL_GROUP_BY`**: query lama di Buku Besar, Saldo Awal, Akses Level/User Level, Laporan Gudang Masuk, dan Part Keluar PO error 1055 di MySQL 8. `MY_Controller` sekarang mematikan mode itu per koneksi aplikasi (tidak mengubah setelan server).
- **Tabel `tbl_wh_detail_so`** dibuat (bagian v6 migrasi): menu lama *Stok Opname* membutuhkannya, tapi tabel ini belum pernah ada.
- Konfigurasi `application/config/database.php` di server: sesuaikan `username`/`password` MySQL server (jangan pakai `root` tanpa password).
