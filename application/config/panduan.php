<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| PANDUAN & PENJELASAN
| -------------------------------------------------------------------------
| 'halaman'  : kotak "Panduan halaman ini" di atas setiap menu akuntansi.
|              kunci = segmen URL pertama (huruf kecil), mis. 'kasbank'.
| 'laporan'  : kotak "Tentang laporan ini" di setiap laporan keuangan.
|              kunci = nama method di controller LaporanKeuangan.
| Teks boleh berisi HTML sederhana (<b>, <em>, <code>). Silakan disunting
| sesuai kebijakan perusahaan.
*/

$config['halaman'] = array(

    'account' => array(
        'judul' => 'Daftar Akun (Bagan Akun / Chart of Accounts)',
        'ringkas' => 'Daftar semua akun buku besar. Setiap transaksi akhirnya dicatat ke akun-akun ini.',
        'langkah' => array(
            'Pastikan setiap akun memiliki <b>Tipe Akun</b> yang benar — tipe menentukan apakah akun masuk Neraca atau Laba Rugi dan di kelompok mana.',
            'Gunakan pola kode bertingkat: 1xx Aset, 2xx Liabilitas, 3xx Ekuitas, 4xx Pendapatan, 5xx Harga Pokok & Beban, 54x Beban Pajak Penghasilan.',
            'Isi <b>Perlakuan Fiskal</b> untuk akun yang perlu dikoreksi pajak (mis. sumbangan, jamuan tanpa daftar nominatif, sanksi pajak = <em>Koreksi Positif</em>; penghasilan bunga deposito / sewa yang sudah PPh final = <em>Final</em>).',
            'Akun yang sudah dipakai di jurnal tidak bisa dihapus — nonaktifkan saja.',
        ),
        'catatan' => 'Akun Kas/Bank wajib bertipe <b>Kas & Bank</b> agar muncul di menu Kas & Bank dan laporan Arus Kas.',
    ),

    'saldo' => array(
        'judul' => 'Saldo Awal',
        'ringkas' => 'Saldo setiap akun pada tanggal 1 Januari tahun mulai memakai program (atau saat pindah dari pembukuan lama).',
        'langkah' => array(
            'Ambil angka dari Neraca per 31 Desember tahun sebelumnya.',
            'Isi akun Aset di kolom <b>Debit</b>; akun Liabilitas & Ekuitas di kolom <b>Kredit</b>; akumulasi penyusutan di Kredit.',
            'Akun Pendapatan & Beban <b>tidak</b> diisi — laba tahun lalu sudah masuk Laba Ditahan.',
            'Total Debit harus sama dengan total Kredit. Cek di <b>Laporan → Pemeriksaan Kesehatan Data</b>.',
        ),
        'catatan' => 'Untuk tahun-tahun berikutnya saldo awal tidak perlu diinput lagi: program menghitung otomatis dari saldo awal + seluruh jurnal.',
    ),

    'jurnal' => array(
        'judul' => 'Jurnal Umum',
        'ringkas' => 'Untuk transaksi yang tidak punya menu khusus: penyesuaian akhir bulan, koreksi, reklasifikasi, accrual, pembagian dividen, dll.',
        'langkah' => array(
            'Tambahkan baris akun debit dan kredit. Tombol <b>Generate</b> hanya aktif bila total debit = total kredit.',
            'Gunakan menu khusus bila tersedia (Kas & Bank, Piutang, Hutang, Aset, Pajak) — jurnalnya dibuat otomatis dan buku pembantunya ikut ter-update.',
            'Jurnal pada periode yang sudah dikunci tidak dapat ditambah/diubah.',
        ),
        'catatan' => 'Contoh penyesuaian umum: beban gaji yang belum dibayar (Dr Beban Gaji / Cr Hutang Gaji), sewa dibayar dimuka yang sudah terpakai (Dr Beban Sewa / Cr Sewa Dibayar Dimuka).',
    ),

    'kasbank' => array(
        'judul' => 'Kas & Bank',
        'ringkas' => 'Mencatat penerimaan, pengeluaran dan transfer antar rekening. Jurnal dibuat otomatis.',
        'langkah' => array(
            '<b>Penerimaan</b>: pilih akun kas/bank tujuan, lalu akun lawan di kredit (mis. pendapatan lain, pinjaman, setoran modal).',
            '<b>Pengeluaran</b>: pilih akun kas/bank sumber, lalu akun beban/aset di debit. Bila ada PPN Masukan, tambahkan baris akun PPN Masukan.',
            '<b>Transfer</b>: pindah dana antar akun kas/bank (mis. setor tunai ke bank).',
            'Untuk pembayaran ke pemasok/penerimaan dari pelanggan atas faktur, gunakan menu <b>Hutang/Piutang Usaha → Pelunasan</b> agar status faktur ikut lunas.',
        ),
        'catatan' => 'Cocokkan saldo dengan rekening koran setiap bulan memakai laporan <b>Mutasi Kas & Bank</b>.',
    ),

    'piutang' => array(
        'judul' => 'Piutang Usaha (Faktur Penjualan)',
        'ringkas' => 'Faktur penjualan kepada pelanggan, pelunasannya, dan potongan PPh oleh pelanggan.',
        'langkah' => array(
            'Buat faktur: pelanggan, tanggal, jatuh tempo, baris pendapatan. Centang PPN bila Anda PKP dan isi <b>No. Seri Faktur Pajak (NSFP)</b> dari Coretax.',
            'Jurnal otomatis: Dr Piutang / Cr Pendapatan, Cr PPN Keluaran.',
            'Saat pelanggan membayar: <b>Pelunasan</b>. Bila pelanggan memotong PPh 23 (umumnya 2% dari DPP jasa), isi kolom Potongan PPh dan nomor bukti potongnya → tercatat sebagai PPh 23 Dibayar Dimuka (kredit pajak).',
            'Pantau tagihan lewat laporan <b>Umur Piutang</b> dan <b>Kartu Piutang</b>.',
        ),
        'catatan' => 'Faktur yang sudah ada pelunasan tidak bisa dibatalkan — hapus pelunasannya terlebih dulu.',
    ),

    'hutang' => array(
        'judul' => 'Hutang Usaha (Faktur Pembelian)',
        'ringkas' => 'Tagihan dari pemasok, pembayarannya, dan PPh yang Anda potong dari pemasok jasa.',
        'langkah' => array(
            'Catat faktur pemasok: baris beban/persediaan/aset, PPN Masukan bila ada faktur pajak (isi NSFP pemasok).',
            'Jurnal otomatis: Dr Beban/Persediaan, Dr PPN Masukan / Cr Hutang Usaha.',
            'Saat membayar: <b>Pelunasan</b>. Bila Anda memotong PPh 23/4(2) dari pemasok jasa, isi kolom Potongan → tercatat sebagai Hutang PPh yang wajib disetor tgl 15 bulan berikutnya.',
        ),
        'catatan' => 'PPN Masukan hanya dapat dikreditkan bila ada faktur pajak yang sah atas nama perusahaan.',
    ),

    'asettetap' => array(
        'judul' => 'Aset Tetap',
        'ringkas' => 'Daftar aset (kendaraan, mesin, peralatan, bangunan) dan penyusutannya.',
        'langkah' => array(
            'Tambah aset: harga perolehan, tanggal mulai susut, umur (bulan), metode, akun aset/akumulasi/beban.',
            'Pilih "jurnal perolehan" bila pembelian aset belum dicatat di Kas & Bank / Hutang.',
            'Setiap akhir bulan klik <b>Proses Penyusutan</b> sampai bulan tersebut → Dr Beban Penyusutan / Cr Akumulasi Penyusutan.',
            'Saat aset dijual/dihapus gunakan <b>Lepas Aset</b>; laba/rugi pelepasan dihitung otomatis.',
        ),
        'catatan' => 'Umur fiskal (UU PPh Ps. 11): Kelompok 1 = 4 th, Kelompok 2 = 8 th, Kelompok 3 = 16 th, Kelompok 4 = 20 th, bangunan permanen = 20 th. Bila umur komersial berbeda, selisih penyusutan menjadi koreksi fiskal.',
    ),

    'anggaran' => array(
        'judul' => 'Anggaran',
        'ringkas' => 'Target pendapatan & beban per akun per bulan untuk dibandingkan dengan realisasi.',
        'langkah' => array(
            'Pilih tahun, isi angka per bulan untuk akun pendapatan dan beban utama.',
            'Lihat hasilnya di <b>Laporan → Anggaran vs Realisasi</b>.',
        ),
        'catatan' => '',
    ),

    'pajak' => array(
        'judul' => 'Perpajakan',
        'ringkas' => 'Pusat kewajiban pajak: kalender, tutup masa PPN, setoran (NTPN), koreksi fiskal dan PPh Badan.',
        'langkah' => array(
            '<b>Pengaturan</b> (sekali di awal): NPWP, status PKP, rezim PPh, dan akun-akun pajak.',
            '<b>Setiap bulan</b>: setelah semua faktur masa tersebut masuk, buka tab <b>Masa PPN</b> → Tutup Masa. Hasilnya Hutang PPN (kurang bayar) atau lebih bayar yang dikompensasikan.',
            '<b>Setor</b>: catat setiap setoran (PPN, PPh 21, 23, 4(2), 25, 29, Final) beserta <b>NTPN</b> di tab Setoran Pajak — jurnal Dr Hutang Pajak / Cr Bank dibuat otomatis.',
            '<b>Akhir tahun</b>: isi koreksi fiskal, periksa Rekonsiliasi Fiskal, lalu <b>Buat Jurnal PPh Badan</b> sebelum jurnal penutup.',
        ),
        'catatan' => 'Batas waktu umum: setor PPh potong/pungut & PPh 25 tgl 15 bulan berikutnya, lapor SPT Masa PPh tgl 20, PPN setor & lapor akhir bulan berikutnya, SPT Tahunan Badan akhir bulan ke-4. Selalu cek perubahan aturan di pajak.go.id.',
    ),

    'periodeakuntansi' => array(
        'judul' => 'Tutup Buku & Preferensi',
        'ringkas' => 'Kunci periode, jurnal penutup tahunan, dan akun default yang dipakai otomatis oleh modul lain.',
        'langkah' => array(
            '<b>Preferensi</b>: atur akun default (piutang, hutang, PPN, laba ditahan, penyusutan). Isi sekali di awal.',
            '<b>Kunci periode</b> setelah laporan bulanan final — transaksi pada bulan terkunci ditolak.',
            '<b>Tutup buku tahunan</b>: setelah penyusutan Desember, tutup masa PPN Desember dan jurnal PPh Badan, buat <b>Jurnal Penutup</b> agar pendapatan & beban pindah ke Laba Ditahan.',
        ),
        'catatan' => 'Jurnal penutup bisa dibatalkan bila masih ada koreksi tahun tersebut.',
    ),

    'settingacc' => array(
        'judul' => 'Panel Setting Accounting',
        'ringkas' => 'Ringkasan akun bawaan & integrasi modul, akun hutang setiap supplier, serta master kelompok/type/jenis akun.',
        'langkah' => array(
            'Tab <b>Akun bawaan &amp; integrasi</b>: pastikan tidak ada tanda kuning/merah — semua akun bawaan menunjuk ke akun yang ada di Daftar Akun.',
            'Tab <b>Akun hutang supplier</b>: hubungkan setiap pemasok gudang dengan akun hutang & lawan akunnya.',
            'Kelompok/Type/Jenis hanya label tambahan; penempatan akun di laporan ditentukan oleh <b>Tipe Akun</b> di Daftar Akun.',
        ),
        'catatan' => '',
    ),

    'laporankeuangan' => array(
        'judul' => 'Pusat Laporan Keuangan',
        'ringkas' => 'Semua laporan dihitung langsung dari jurnal & saldo awal, bisa dicetak dan diekspor ke Excel.',
        'langkah' => array(
            'Klik nama laporan, atur periode, lalu <b>Tampilkan</b>.',
            'Klik nama akun pada laporan untuk membuka Buku Besarnya (drill-down).',
            'Mulai dari <b>Pemeriksaan Kesehatan Data</b> sebelum mencetak laporan akhir bulan/tahun.',
        ),
        'catatan' => '',
    ),
);

$config['laporan'] = array(

    'neraca' => array(
        'tujuan' => 'Posisi kekayaan (aset), kewajiban (liabilitas) dan modal (ekuitas) perusahaan pada satu tanggal.',
        'baca' => array(
            '<b>Aset</b> diurutkan dari yang paling likuid: Kas & Bank, Piutang, Persediaan, Aset Lancar Lain, lalu Aset Tetap dikurangi Akumulasi Penyusutan.',
            '<b>Liabilitas</b>: Hutang Usaha & liabilitas jangka pendek (jatuh tempo ≤ 1 tahun) lalu jangka panjang.',
            '<b>Ekuitas</b>: modal disetor, laba ditahan (laba tahun-tahun lalu), dan laba/rugi tahun berjalan.',
            'Total Aset harus = Total Liabilitas + Ekuitas. Bila ada baris "Selisih", jalankan Pemeriksaan Kesehatan Data.',
        ),
        'sumber' => 'Saldo Awal + seluruh jurnal (semua modul) sampai tanggal laporan.',
        'tips' => array('Isi "Bandingkan dengan" (mis. 31 Des tahun lalu) untuk Neraca komparatif.'),
    ),
    'laba_rugi' => array(
        'tujuan' => 'Kinerja usaha selama satu periode: pendapatan dikurangi biaya = laba atau rugi.',
        'baca' => array(
            '<b>Pendapatan Usaha − Harga Pokok = Laba Kotor</b> (seberapa untung dari pekerjaan/penjualan inti).',
            '<b>Laba Kotor − Beban Operasional = Laba Operasi.</b>',
            '+ Pendapatan lain − Beban lain = <b>Laba Sebelum Pajak</b>; − Beban Pajak Penghasilan = <b>Laba Bersih</b>.',
            'Centang <b>Kolom % pendapatan</b> untuk analisis vertikal (porsi tiap biaya terhadap penjualan).',
            'Mode <b>Per bulan</b> menampilkan tren; mode <b>Bandingkan</b> menampilkan selisih & % pertumbuhan.',
        ),
        'sumber' => 'Mutasi akun bertipe Pendapatan, Harga Pokok, Beban, Pendapatan/Beban Lain dan Beban Pajak Penghasilan. Jurnal penutup diabaikan.',
        'tips' => array('Klik nama akun untuk melihat transaksi pembentuknya.'),
    ),
    'arus_kas' => array(
        'tujuan' => 'Dari mana kas berasal dan ke mana kas digunakan selama periode.',
        'baca' => array(
            '<b>Operasi</b>: penerimaan dari pelanggan, pembayaran ke pemasok, gaji, pajak, beban rutin.',
            '<b>Investasi</b>: pembelian/penjualan aset tetap.',
            '<b>Pendanaan</b>: pinjaman, setoran modal, pembagian laba.',
            'Kas awal + kenaikan (penurunan) bersih = kas akhir, harus sama dengan saldo akun Kas & Bank di Neraca.',
        ),
        'sumber' => 'Jurnal yang menyentuh akun bertipe Kas & Bank; klasifikasi berdasarkan tipe akun lawannya (metode langsung).',
        'tips' => array('Laba besar tetapi arus kas operasi negatif → piutang menumpuk atau persediaan berlebih.'),
    ),
    'perubahan_ekuitas' => array(
        'tujuan' => 'Perubahan modal pemilik: saldo awal, tambahan modal, laba periode, pembagian laba, saldo akhir.',
        'baca' => array('Saldo akhir ekuitas harus sama dengan total Ekuitas di Neraca pada tanggal akhir periode.'),
        'sumber' => 'Akun bertipe Ekuitas + laba bersih periode.',
    ),
    'paket' => array(
        'tujuan' => 'Satu dokumen laporan keuangan lengkap untuk pemilik, bank, investor atau lampiran SPT Tahunan.',
        'baca' => array(
            'Berisi 5 komponen standar: Neraca (komparatif), Laba Rugi, Perubahan Ekuitas, Arus Kas, dan Catatan atas Laporan Keuangan (CaLK).',
            'CaLK disusun otomatis: informasi umum, kebijakan akuntansi, dan rincian setiap pos (per akun) beserta umur piutang/hutang dan rincian aset tetap.',
        ),
        'sumber' => 'Gabungan seluruh laporan utama.',
        'tips' => array('Lengkapi Nama WP & NPWP di Perpajakan → Pengaturan agar muncul di CaLK.', 'Pilih periode 1 Jan – 31 Des untuk laporan tahunan. Gunakan tombol Cetak lalu "Save as PDF".', 'Teks kebijakan akuntansi bersifat umum — sesuaikan dengan kondisi perusahaan bila perlu.'),
        'dasar' => 'Komponen laporan keuangan menurut SAK EP / SAK EMKM (IAI).',
    ),
    'neraca_saldo' => array(
        'tujuan' => 'Daftar semua akun dengan saldo awal, mutasi debit/kredit dan saldo akhir — alat kontrol sebelum menyusun laporan.',
        'baca' => array('Total debit harus = total kredit pada setiap kolom. Akun bersaldo tidak wajar (mis. kas kredit) perlu ditelusuri.'),
        'sumber' => 'Saldo Awal + jurnal.',
    ),
    'buku_besar' => array(
        'tujuan' => 'Rincian setiap transaksi per akun dengan saldo berjalan.',
        'baca' => array('Saldo awal → setiap baris jurnal (tanggal, no. bukti, sumber, keterangan, debit/kredit) → saldo akhir.'),
        'sumber' => 'Jurnal semua modul.',
        'tips' => array('Kosongkan pilihan akun untuk mencetak buku besar semua akun sekaligus.'),
    ),
    'daftar_jurnal' => array(
        'tujuan' => 'Semua jurnal dalam periode, dikelompokkan per nomor jurnal; bisa disaring per sumber (JU, Kas Bank, Faktur, Penyusutan, Pajak, dsb).',
        'baca' => array('Setiap jurnal harus seimbang. Kolom sumber menunjukkan modul asal transaksi.'),
        'sumber' => 'Tabel jurnal umum.',
    ),
    'mutasi_kas' => array(
        'tujuan' => 'Rekening koran internal untuk setiap akun kas/bank — dipakai rekonsiliasi bank.',
        'baca' => array('Bandingkan saldo akhir dan setiap mutasi dengan rekening koran bank. Selisih biasanya: biaya admin/bunga bank belum dicatat, setoran dalam perjalanan, atau cek belum dicairkan.'),
        'sumber' => 'Jurnal pada akun bertipe Kas & Bank.',
    ),
    'umur_piutang' => array(
        'tujuan' => 'Sisa piutang per pelanggan dikelompokkan menurut lama keterlambatan.',
        'baca' => array('Kolom "> 90 hari" adalah piutang berisiko tinggi — prioritaskan penagihan atau pertimbangkan cadangan kerugian.'),
        'sumber' => 'Faktur penjualan dikurangi pelunasan & potongan PPh.',
    ),
    'umur_hutang' => array(
        'tujuan' => 'Sisa hutang per pemasok menurut jatuh tempo — dasar penjadwalan pembayaran.',
        'baca' => array('Bayar dulu yang sudah lewat jatuh tempo untuk menjaga hubungan dengan pemasok.'),
        'sumber' => 'Faktur pembelian dikurangi pembayaran.',
    ),
    'kartu_piutang' => array(
        'tujuan' => 'Buku pembantu piutang: riwayat lengkap setiap pelanggan (saldo awal, faktur, pelunasan, potongan PPh, saldo berjalan).',
        'baca' => array(
            'Gunakan sebagai lampiran surat konfirmasi saldo atau penagihan.',
            'Bagian <b>Rekonsiliasi</b> membandingkan total buku pembantu dengan saldo akun Piutang di Buku Besar; idealnya selisih nol.',
        ),
        'sumber' => 'Faktur penjualan & pelunasan; saldo akun kontrol dari jurnal.',
    ),
    'kartu_hutang' => array(
        'tujuan' => 'Buku pembantu hutang: riwayat setiap pemasok (saldo awal, tagihan, pembayaran, PPh dipotong, saldo).',
        'baca' => array('Cocokkan dengan statement dari pemasok setiap akhir bulan.'),
        'sumber' => 'Faktur pembelian & pembayaran.',
    ),
    'faktur_penjualan' => array(
        'tujuan' => 'Daftar faktur penjualan lengkap dengan DPP, PPN, NSFP, pembayaran dan sisa.',
        'baca' => array('Tanda "NSFP kosong" berarti faktur ber-PPN belum diberi nomor faktur pajak — lengkapi sebelum lapor SPT Masa PPN.', 'Centang "Rincian baris faktur" untuk melihat akun & item per faktur.'),
        'sumber' => 'Menu Piutang Usaha.',
    ),
    'faktur_pembelian' => array(
        'tujuan' => 'Daftar faktur pembelian dari pemasok beserta PPN Masukan dan status pembayaran.',
        'baca' => array('Pastikan setiap PPN Masukan memiliki NSFP pemasok agar dapat dikreditkan.'),
        'sumber' => 'Menu Hutang Usaha.',
    ),
    'pajak_tahunan' => array(
        'tujuan' => 'Gambaran seluruh kewajiban pajak per bulan dalam satu tahun — memastikan tidak ada masa yang terlewat.',
        'baca' => array(
            '<b>Peredaran bruto</b> = pendapatan usaha + pendapatan lain yang bukan objek PPh final (dasar PPh final UMKM & fasilitas Ps. 31E).',
            '<b>PPN kurang bayar</b> wajib disetor; <b>lebih bayar</b> dikompensasikan ke masa berikutnya.',
            'PPh 21/23/4(2) = PPh yang Anda potong dan wajib disetor tgl 15 bulan berikutnya.',
            'Bandingkan kolom <b>Total Kewajiban</b> dengan <b>Disetor</b> setiap bulan.',
        ),
        'sumber' => 'Jurnal pada akun-akun pajak (diatur di Perpajakan → Pengaturan), faktur, dan daftar setoran.',
        'dasar' => 'UU PPN & UU PPh sebagaimana diubah UU HPP (UU 7/2021); PMK 81/2024 (tata cara & batas waktu di era Coretax).',
    ),
    'ppn_masa' => array(
        'tujuan' => 'Bahan pengisian SPT Masa PPN: rekap PPN Keluaran − PPN Masukan dan daftar faktur pajaknya.',
        'baca' => array(
            'Bagian rekap: A Keluaran − B Masukan − C kompensasi = D kurang/lebih bayar.',
            'Daftar Pajak Keluaran/Masukan: cocokkan nomor seri faktur pajak (NSFP), DPP dan PPN dengan data di Coretax.',
            'DPP Nilai Lain 11/12 berlaku untuk penyerahan non-mewah dengan tarif 12% sehingga PPN efektif 11%.',
        ),
        'sumber' => 'Faktur ber-PPN (Piutang & Hutang Usaha) dan jurnal akun PPN.',
        'tips' => array('Tutup masa PPN di menu Perpajakan setelah angka di sini cocok dengan Coretax.'),
        'dasar' => 'PMK 131/2024 (tarif 12% & DPP nilai lain 11/12).',
    ),
    'buku_pajak' => array(
        'tujuan' => 'Kontrol saldo setiap akun pajak: apakah semua hutang pajak sudah disetor dan berapa kredit pajak yang dimiliki.',
        'baca' => array('Hutang PPN/PPh bersaldo di akhir tahun = belum disetor. PPh 22/23/25 Dibayar Dimuka bersaldo = kredit pajak yang akan mengurangi PPh Badan.'),
        'sumber' => 'Jurnal pada akun pajak.',
    ),
    'bukti_potong' => array(
        'tujuan' => 'Daftar bukti potong PPh dari pelunasan faktur: yang dipotong pelanggan (kredit pajak) dan yang Anda potong dari pemasok (wajib setor).',
        'baca' => array('Baris tanpa nomor bukti potong perlu dilengkapi — kredit pajak tanpa bukti potong dapat ditolak saat pemeriksaan.'),
        'sumber' => 'Kolom Potongan PPh pada pelunasan Piutang & Hutang Usaha.',
        'dasar' => 'PPh Pasal 23 UU PPh: 2% atas jasa & sewa selain tanah/bangunan, 15% atas dividen/bunga/royalti (tarif lebih tinggi 100% bila tanpa NPWP/NIK).',
    ),
    'setoran_pajak' => array(
        'tujuan' => 'Arsip seluruh setoran pajak beserta NTPN sebagai bukti pembayaran ke kas negara.',
        'baca' => array('Setiap setoran harus memiliki NTPN (Nomor Transaksi Penerimaan Negara) dari bukti penerimaan negara.'),
        'sumber' => 'Menu Perpajakan → Setoran Pajak.',
    ),
    'rekon_fiskal' => array(
        'tujuan' => 'Menghitung PPh Badan: laba akuntansi disesuaikan dengan aturan pajak (koreksi fiskal) → penghasilan kena pajak → PPh terutang → kurang/lebih bayar.',
        'baca' => array(
            '<b>Koreksi positif</b>: biaya yang tidak boleh dikurangkan (sumbangan, natura tertentu, sanksi pajak, PPh, biaya tanpa bukti, penyusutan komersial > fiskal).',
            '<b>Koreksi negatif</b>: penghasilan yang sudah dikenai PPh final / bukan objek (bunga deposito, sewa bangunan), penyusutan fiskal > komersial.',
            '<b>Ps. 31E</b>: peredaran bruto ≤ Rp50 M mendapat pengurangan tarif 50% atas PKP bagian (Rp4,8 M ÷ bruto).',
            'Kredit pajak (PPh 22, 23, 25) mengurangi PPh terutang → sisa = PPh 29 yang disetor sebelum SPT Tahunan dilaporkan.',
        ),
        'sumber' => 'Laba Rugi, tanda fiskal di Daftar Akun, koreksi manual di Perpajakan, akun pajak dibayar dimuka.',
        'dasar' => 'UU PPh Pasal 6, 9, 17, 31E (sebagaimana diubah UU HPP); tarif badan 22%.',
        'tips' => array('Gunakan sebagai lampiran kerja Lampiran I SPT 1771. Konsultasikan koreksi yang tidak biasa dengan konsultan pajak.'),
    ),
    'aset' => array(
        'tujuan' => 'Daftar aset tetap dengan harga perolehan, akumulasi penyusutan dan nilai buku per tanggal.',
        'baca' => array('Total nilai buku harus sama dengan (akun Aset Tetap − Akumulasi Penyusutan) di Neraca bila semua aset dicatat lewat menu Aset Tetap.'),
        'sumber' => 'Menu Aset Tetap.',
    ),
    'jadwal_susut' => array(
        'tujuan' => 'Penyusutan setiap aset per bulan dalam satu tahun, termasuk proyeksi bulan yang belum diproses.',
        'baca' => array('Angka miring abu-abu = proyeksi (belum dijurnal). Dipakai juga untuk lampiran penyusutan SPT Tahunan dan perencanaan beban.'),
        'sumber' => 'Riwayat penyusutan + perhitungan metode masing-masing aset.',
    ),
    'anggaran' => array(
        'tujuan' => 'Membandingkan target (anggaran) dengan realisasi pendapatan & beban.',
        'baca' => array('Persentase realisasi > 100% pada beban = melebihi anggaran; pada pendapatan = melampaui target.'),
        'sumber' => 'Menu Anggaran & jurnal.',
    ),
    'rasio' => array(
        'tujuan' => 'Menilai kesehatan keuangan dengan rasio standar beserta tafsirannya.',
        'baca' => array(
            '<b>Likuiditas</b>: mampukah membayar hutang jangka pendek.',
            '<b>Solvabilitas</b>: seberapa besar perusahaan dibiayai hutang.',
            '<b>Profitabilitas</b>: seberapa efisien menghasilkan laba.',
            '<b>Aktivitas</b>: seberapa cepat piutang tertagih, persediaan berputar dan hutang dibayar.',
        ),
        'sumber' => 'Neraca awal & akhir periode serta Laba Rugi periode.',
        'tips' => array('Lihat tren: bandingkan rasio bulan ini dengan bulan/tahun sebelumnya.'),
    ),
    'cek_data' => array(
        'tujuan' => 'Daftar periksa otomatis sebelum menutup bulan/tahun — menemukan kesalahan yang membuat laporan tidak akurat.',
        'baca' => array('Hijau = beres, biru = informasi, kuning = perlu dicek, merah = harus diperbaiki. Klik "Buka" untuk langsung ke menu perbaikan.'),
        'sumber' => 'Jurnal, Daftar Akun, Saldo Awal, faktur, aset dan pajak.',
    ),
);

/* ==========================================================================
 * GUDANG — panduan per halaman (kunci = segmen URL pertama, huruf kecil)
 * ========================================================================== */
$PG = 'PanduanGudang';
$config['halaman'] += array(
    'sparepart' => array('panduan' => $PG, 'judul' => 'Material & Sparepart (Master Barang)',
        'ringkas' => 'Daftar semua barang beserta stok, rak, harga & stok minimum di tiap gudang (Cibitung, Jakarta, Surabaya).',
        'langkah' => array(
            'Tambah barang baru: no. part (unik), nama, satuan, kategori/divisi, kelompok rak, type mesin, supplier utama.',
            'Isi <b>stok minimum</b> untuk barang yang rutin dipakai — dipakai laporan <em>Stok Minimum & Saran Pesan</em>.',
            'Stok bertambah otomatis dari Part Masuk dan berkurang dari Part Keluar. Hindari mengubah stok manual; gunakan Stock Opname untuk koreksi.',
            'Klik no. part di laporan gudang untuk membuka <b>Kartu Stok</b> barang tersebut.'),
        'catatan' => 'Satu nomor part = satu baris data. Nomor part ganda membuat stok terpecah (dicek di Pemeriksaan Data Gudang).'),
    'updatehpart' => array('panduan' => $PG, 'judul' => 'Update Harga Part',
        'ringkas' => 'Memperbarui price list, diskon & harga net per gudang. Setiap perubahan tercatat di log harga.',
        'langkah' => array('Cari barang, isi price list dan diskon; harga net dihitung otomatis.', 'Harga net dipakai untuk nilai persediaan, nilai pemakaian dan saran pesan.'),
        'catatan' => 'Perbarui harga setiap ada perubahan dari pemasok agar nilai persediaan mendekati harga beli terakhir.'),
    'purchaseorder' => array('panduan' => $PG, 'judul' => 'Purchase Order',
        'ringkas' => 'Dokumen pemesanan barang ke pemasok (PO) lengkap dengan harga, diskon & PPN.',
        'langkah' => array('Pilih pemasok, tambahkan barang & jumlah, simpan lalu cetak PO.', 'Barang datang dicatat di <b>Part Masuk PO</b> dengan merujuk nomor PO ini.'),
        'catatan' => 'Pantau PO yang belum datang di Laporan Gudang → PO Outstanding.'),
    'partorder' => array('panduan' => $PG, 'judul' => 'Part Order',
        'ringkas' => 'Pemesanan part ke pemasok per jenis pesanan (VOR, Warranty, Regular Stock, Courier, Special Tools).',
        'langkah' => array('Pilih jenis pesanan (Kode PO) & pemasok, isi barang dan jumlah.', 'Kolom sisa berkurang otomatis saat barang diterima di Part Masuk PO.', 'Jumlah sisa dihitung sebagai "sedang dipesan" di laporan Stok Minimum.'),
        'catatan' => ''),
    'estimasipenawaran' => array('panduan' => $PG, 'judul' => 'Estimasi Penawaran',
        'ringkas' => 'Penawaran harga part kepada pelanggan, dapat dicetak versi pelanggan & internal.',
        'langkah' => array('Pilih pelanggan, tambahkan part dan harga/diskon.', 'Bila disetujui, pelanggan menerbitkan PO yang dicatat di Purchase Order Masuk.'),
        'catatan' => ''),
    'pomasuk' => array('panduan' => $PG, 'judul' => 'Purchase Order Masuk (PO Customer)',
        'ringkas' => 'PO dari pelanggan yang harus dipenuhi gudang.',
        'langkah' => array('Catat PO pelanggan sesuai dokumen asli.', 'Pengiriman dicatat di <b>Part Keluar PO</b> lalu dibuatkan <b>Surat Jalan</b>.'),
        'catatan' => 'Sisa PO customer yang belum dikirim tampil di Laporan Gudang → PO Outstanding (bagian B).'),
    'part_masuk_po' => array('panduan' => $PG, 'judul' => 'Part Masuk PO (Penerimaan dengan PO)',
        'ringkas' => 'Penerimaan barang dari pemasok berdasarkan Part Order / PO.',
        'langkah' => array('Pilih nomor PO, isi No. SJ & No. Invoice pemasok.', 'Periksa fisik barang: isi jumlah yang benar-benar diterima (boleh sebagian / back order).', 'Simpan — stok gudang bertambah dan sisa PO berkurang.'),
        'catatan' => 'Tagihan pemasok dicatat bagian Akuntansi di Hutang Usaha dengan nomor invoice yang sama.'),
    'part_masuk_npo' => array('panduan' => $PG, 'judul' => 'Part Masuk Non PO',
        'ringkas' => 'Penerimaan barang tanpa PO (pembelian tunai/mendadak) atau barang kembali (retur dari pemakai).',
        'langkah' => array('Isi supplier, No. SJ / invoice, lokasi gudang.', 'Centang <b>Barang Kembali</b> bila barang adalah pengembalian dari pemakaian.', 'Tambahkan barang, jumlah & harga — stok bertambah saat disimpan.'),
        'catatan' => 'Usahakan pembelian rutin tetap melalui Part Order agar bisa dikontrol.'),
    'part_keluar' => array('panduan' => $PG, 'judul' => 'Part Keluar (Pengeluaran Barang)',
        'ringkas' => 'Pengeluaran barang untuk pemakaian internal / divisi / PK body repair.',
        'langkah' => array('Isi tujuan, divisi/kategori, no. PK atau keterangan pemakaian.', 'Tambahkan barang & jumlah — stok berkurang saat disimpan.', 'Cetak bon pengeluaran untuk ditandatangani penerima.'),
        'catatan' => 'Pemakaian per tujuan/divisi dapat dianalisis di Laporan Gudang → Pemakaian.'),
    'partkeluarwo' => array('panduan' => $PG, 'judul' => 'Part Keluar WO (Work Order Service)',
        'ringkas' => 'Pengeluaran part untuk Work Order After Sales berdasarkan Part Request.',
        'langkah' => array('Pilih nomor WO; daftar part yang diminta muncul otomatis.', 'Isi jumlah keluar; sisa permintaan tersimpan untuk pengeluaran berikutnya.'),
        'catatan' => ''),
    'partkeluarpo' => array('panduan' => $PG, 'judul' => 'Part Keluar PO (Pengiriman ke Customer)',
        'ringkas' => 'Pengeluaran barang untuk memenuhi PO pelanggan.',
        'langkah' => array('Pilih PO customer, isi jumlah yang dikirim.', 'Lanjutkan dengan Surat Jalan; bagian Akuntansi membuat Faktur Penjualan di Piutang Usaha.'),
        'catatan' => ''),
    'suratjalan' => array('panduan' => $PG, 'judul' => 'Surat Jalan',
        'ringkas' => 'Dokumen pengantar barang ke pelanggan / antar cabang.',
        'langkah' => array('Isi nomor PO / SJ, tujuan, cabang stok.', 'Cetak rangkap untuk pengemudi, penerima & arsip.'),
        'catatan' => ''),
    'stokopname' => array('panduan' => $PG, 'judul' => 'Stock Opname',
        'ringkas' => 'Perhitungan fisik barang per kelompok rak untuk mencocokkan stok sistem.',
        'langkah' => array('Buat dokumen opname: tanggal, cabang, kelompok rak.', 'Cetak lembar hitung, hitung fisik tanpa melihat stok sistem (blind count).', 'Input stok fisik, lalu periksa <b>Selisih Stock Opname</b> di Laporan Gudang.', 'Setelah disetujui, koreksi stok & minta Akuntansi membuat jurnal penyesuaian persediaan.'),
        'catatan' => 'Barang kelas A (Analisis ABC) sebaiknya dihitung lebih sering, misalnya tiap bulan.'),
    'settingwh' => array('panduan' => $PG, 'judul' => 'Pengaturan Gudang (Master Data)',
        'ringkas' => 'Satuan, kategori/divisi, kelompok rak, type mesin, supplier, customer & jenis pesanan.',
        'langkah' => array('Pilih jenis master di sebelah kiri, lalu Tambah / ubah (ikon pensil) / hapus (ikon tempat sampah).', 'Isi akun hutang di data Supplier agar terhubung ke modul Akuntansi.'),
        'catatan' => 'Jangan menghapus master yang sudah dipakai transaksi; cukup ubah namanya.'),
    'laporangudang' => array('panduan' => $PG, 'judul' => 'Dashboard & Laporan Gudang',
        'ringkas' => 'Ringkasan stok & mutasi semua gudang serta pusat laporan gudang yang detail.',
        'langkah' => array('Lihat kartu ringkasan & tabel stok per gudang setiap pagi.', 'Buka <b>Stok Minimum</b> untuk daftar belanja, <b>PO Outstanding</b> untuk tindak lanjut pemasok.', 'Mulai dari <b>Pemeriksaan Data Gudang</b> sebelum opname / tutup bulan.'),
        'catatan' => ''),
    'reportwhnilaistok' => array('panduan' => $PG, 'judul' => 'Nilai Persediaan & Stok Minimum',
        'ringkas' => 'Nilai stok (stok × harga net) per gudang & kategori.',
        'langkah' => array('Pilih gudang & kategori, lalu Tampilkan. Ekspor ke Excel bila perlu.', 'Bandingkan total nilai dengan saldo akun Persediaan di Neraca.'),
        'catatan' => ''),
);
unset($PG);

/* ==========================================================================
 * GUDANG — penjelasan laporan (kunci = method controller LaporanGudang)
 * ========================================================================== */
$config['laporan_gudang'] = array(
    'kartu_stok' => array('tujuan' => 'Riwayat lengkap satu barang: setiap penerimaan dan pengeluaran dengan saldo berjalan.',
        'baca' => array('Saldo awal + masuk − keluar = saldo akhir; saldo akhir hari ini = stok sistem.', 'Kolom Jenis menunjukkan dokumen asal: Masuk PO, Masuk Non-PO, Retur, Keluar umum/PK, Keluar WO, Keluar PO customer.', 'Kosongkan Lokasi untuk melihat gabungan semua gudang.'),
        'sumber' => 'Part Masuk, Part Keluar (umum/PK, WO, PO) dan stok di master barang.',
        'tips' => array('Gunakan saat ada selisih opname: telusuri transaksi sejak opname terakhir.')),
    'mutasi' => array('tujuan' => 'Ringkasan pergerakan setiap barang dalam periode: saldo awal, masuk, keluar, saldo akhir & nilainya.',
        'baca' => array('Default hanya barang yang bergerak; centang "Tampilkan semua barang bersaldo" untuk daftar lengkap.', 'Klik no. part untuk membuka kartu stoknya.'),
        'sumber' => 'Seluruh transaksi gudang + stok sistem.',
        'tips' => array('Total nilai akhir dapat dicocokkan dengan akun Persediaan di Neraca.')),
    'stok_minimum' => array('tujuan' => 'Daftar barang yang perlu dipesan ulang beserta saran jumlahnya.',
        'baca' => array('<b>Sedang dipesan</b> = sisa Part Order yang belum diterima, sehingga tidak dipesan dua kali.', '<b>Saran pesan</b> = 2 × stok minimum − stok − sedang dipesan (mengisi kembali sampai dua kali stok minimum).'),
        'sumber' => 'Stok & stok minimum di master barang, sisa Part Order.',
        'dasar' => 'Reorder point (ROP) = rata-rata pemakaian harian × lead time pemasok + stok pengaman. Isi stok minimum mendekati ROP.'),
    'slow_moving' => array('tujuan' => 'Menemukan modal yang "tidur" di gudang: barang bersaldo yang lama atau tidak pernah dikeluarkan.',
        'baca' => array('LAMBAT: tidak keluar ≥ batas hari; SANGAT LAMBAT: ≥ 2× batas; TIDAK PERNAH KELUAR: tidak ada catatan pengeluaran.', 'Diurutkan dari nilai terbesar — prioritaskan tindak lanjut di baris teratas.'),
        'sumber' => 'Stok sistem & tanggal pengeluaran terakhir.',
        'tips' => array('Tindak lanjut: retur ke pemasok, pindah ke gudang lain yang membutuhkan, jual/promosi, atau penghapusan bersama Akuntansi.')),
    'abc' => array('tujuan' => 'Mengelompokkan barang menurut kontribusi nilai pemakaian agar kontrol fokus pada barang yang paling berpengaruh.',
        'baca' => array('<b>A</b>: sedikit item tetapi ±80% nilai pemakaian — kontrol ketat, opname sering.', '<b>B</b>: ±15% nilai berikutnya.', '<b>C</b>: banyak item dengan ±5% nilai — kontrol sederhana.'),
        'sumber' => 'Pengeluaran barang dalam periode × harga.',
        'dasar' => 'Prinsip Pareto (aturan 80/20) dalam manajemen persediaan.'),
    'pemakaian' => array('tujuan' => 'Ke mana barang keluar: per jenis pengeluaran, kategori/divisi, tujuan/pelanggan/WO, dan barang terbesar.',
        'baca' => array('Bar porsi menunjukkan bagian dari total nilai pengeluaran.'), 'sumber' => 'Part Keluar umum/PK, WO dan PO customer.'),
    'penerimaan' => array('tujuan' => 'Kinerja pembelian per pemasok: jumlah dokumen, qty dan nilai penerimaan.',
        'baca' => array('Nilai dipisah: dengan PO, tanpa PO, dan retur masuk.', 'Porsi Non-PO yang besar menandakan pembelian di luar rencana.'), 'sumber' => 'Part Masuk PO & Non-PO.'),
    'outstanding' => array('tujuan' => 'Pesanan yang belum tuntas: barang dari pemasok yang belum datang dan PO pelanggan yang belum dikirim.',
        'baca' => array('Umur dihitung dari tanggal dokumen; > 60 hari ditandai merah.'), 'sumber' => 'Part Order (sisa) & PO Masuk (sisa).'),
    'selisih_opname' => array('tujuan' => 'Perbedaan antara stok sistem dan hasil hitung fisik pada satu dokumen opname, beserta nilainya.',
        'baca' => array('Selisih positif = fisik lebih; negatif = fisik kurang (potensi hilang/rusak/salah catat).', 'Diurutkan dari nilai selisih terbesar.'),
        'sumber' => 'Stock Opname (stok lama vs stok fisik) & harga net.',
        'tips' => array('Akurasi stok = item tanpa selisih ÷ item dihitung. Target umum ≥ 95%.')),
    'harga_rata' => array('tujuan' => 'Membandingkan harga net master (harga terbaru) dengan harga rata-rata bergerak dari seluruh pembelian, serta dampaknya ke nilai stok.',
        'baca' => array('Rata-rata bergerak dihitung ulang dari riwayat penerimaan: setiap pembelian mencampur harga baru dengan harga stok yang masih ada.', 'Kolom "Net vs rata" positif = price list sudah naik dibanding biaya stok yang dimiliki.', 'Nilai @rata adalah nilai persediaan yang lebih mendekati biaya perolehan sebenarnya.'),
        'sumber' => 'Part Masuk (PO & non-PO) dengan harga, penyesuaian stok, master barang.',
        'tips' => array('Klik "Hitung ulang" setelah penerimaan besar atau sebelum tutup buku.', 'Pilih metode "Rata-rata bergerak" agar Nilai Persediaan, Mutasi, Opname dan laporan lain memakai harga rata-rata.', 'Selisih besar antara nilai @net dan @rata perlu dibicarakan dengan Akuntansi sebelum menyesuaikan saldo akun Persediaan.')),
    'kenaikan_harga' => array('tujuan' => 'Mengukur kenaikan harga beli tahunan: rata-rata tertimbang tahun ini vs tahun lalu untuk barang yang sama, dan berapa tambahan biaya yang ditimbulkan.',
        'baca' => array('Perubahan % dihitung dari harga beli rata-rata tertimbang qty per tahun.', 'Dampak biaya = (rata-rata tahun ini − rata-rata tahun lalu) × qty yang dibeli tahun ini.', 'Indeks harga memakai tahun pertama data = 100 dan dirantai per tahun (seperti indeks harga produsen).'),
        'sumber' => 'Part Masuk PO & non-PO dengan harga > 0 (retur & penyesuaian tidak dihitung).',
        'tips' => array('Pakai angka kenaikan untuk menyusun anggaran dan meninjau tarif jasa / harga jual.', 'Barang dengan dampak terbesar adalah prioritas negosiasi harga.')),
    'riwayat_harga' => array('tujuan' => 'Melihat perjalanan harga satu barang dari tahun ke tahun: harga beli minimum, maksimum, rata-rata, pemasok, dan perubahan price list di master.',
        'baca' => array('Ringkasan per tahun: rata-rata tertimbang & persentase perubahan dari tahun sebelumnya.', 'Daftar pembelian menampilkan setiap penerimaan beserta harganya.', 'Log perubahan harga berasal dari menu Update Harga Part dan Impor Barang.'),
        'sumber' => 'Part Masuk, tbl_wh_log_harga, tbl_wh_harga_rata.',
        'tips' => array('Gunakan saat menyusun penawaran ke pelanggan atau mengecek kewajaran harga pemasok.')),
    'cek_data' => array('tujuan' => 'Daftar periksa otomatis kualitas data gudang.',
        'baca' => array('Merah = harus diperbaiki, kuning = perlu dicek, biru = informasi.'), 'sumber' => 'Master barang, transaksi 12 bulan terakhir, Part Order.'),
);

/* ---------------- v5: halaman baru ---------------- */
$config['halaman'] += array(
    'serviceappointment' => array('panduan' => 'PanduanSistem', 'judul' => 'Service Appointment',
        'ringkas' => 'Pendaftaran kendaraan yang akan diservis: jadwal, pelanggan & kendaraan, keluhan, jenis layanan. Nomor WO dibuat otomatis.',
        'langkah' => array('Klik <b>Appointment baru</b>, pilih customer — kendaraan yang pernah diservis dapat dipilih untuk mengisi VIN, no. polisi & km otomatis.', 'Isi keluhan, target selesai, storing, free service & jenis pembayaran (Cash / Internal / Warranty / Goodwill).', 'Cetak <b>slip appointment</b> (ikon printer) untuk pelanggan.', 'Lanjutkan ke <b>Pre Order</b>; appointment yang sudah dibuat Pre Order tidak dapat dihapus.'),
        'catatan' => 'Klik kartu ringkasan (menunggu pre order, storing, lewat deadline) untuk menyaring daftar.'),
    'reportservice' => array('panduan' => 'PanduanSistem', 'judul' => 'Services Report',
        'ringkas' => 'Laporan work order per periode dengan jam terjual, nilai jasa & part, grafik, serta cetak semua dokumen service per WO.',
        'langkah' => array('Pilih periode, status, jenis & SA lalu <b>Tampilkan</b>.', '<b>Cetak laporan</b> (A4 landscape, dengan grafik) atau <b>Excel</b>.', 'Ikon printer pada setiap baris: Laporan service lengkap, Pre Order, Estimasi, Work Order, Job Time Card, Workshop Report — masing-masing terbuka di tab baru siap cetak A4.'),
        'catatan' => 'Nilai jasa dari operasi work order; nilai part dari estimasi penawaran service. Atur printer: ukuran A4, skala 100%, margin bawaan.'),
    'settinghrd' => array('panduan' => 'PanduanSistem', 'judul' => 'Panel Setting HRD',
        'ringkas' => 'Master data HRD: departemen, jabatan, pendidikan & kode cuti, plus ringkasan modul HRD terkait.',
        'langkah' => array('Pilih master di sebelah kiri, lalu Tambah / ubah (ikon pensil) / hapus.', 'Lengkapi departemen & jabatan sebelum mengisi data pegawai.', 'Buka tab <b>Ringkasan</b> untuk jumlah pegawai per departemen dan tautan ke Cuti, Mesin Absen & Laporan HRD.'),
        'catatan' => 'Jangan menghapus master yang sudah dipakai pegawai; cukup ubah namanya.'),
    'opnamestok' => array('panduan' => 'PanduanGudang', 'judul' => 'Stock Opname',
        'ringkas' => 'Penghitungan fisik terkendali: snapshot stok → hitung (input/scan/Excel) → review & hitung ulang → disetujui → stok & jurnal disesuaikan.',
        'langkah' => array('Buat dokumen: pilih gudang & cakupan (semua bersaldo, kategori, awalan rak untuk cycle count, atau kelas A).', 'Mulai = stok sistem dikunci. Cetak <b>Lembar hitung (QR)</b> atau unduh lembar Excel untuk petugas.', 'Input hasil hitung: ketik, scan QR label, atau impor Excel. Klik <b>Selesai hitung → Review</b>.', 'Hitung ulang barang yang selisihnya di atas toleransi (kolom Hitung 2).', 'Atasan menekan <b>Setujui & posting</b>; cetak Berita Acara untuk ditandatangani.'),
        'catatan' => 'Transaksi selama opname tetap boleh berjalan: penyesuaian hanya sebesar selisih terhadap snapshot. Rekomendasi: opname penuh setahun sekali + cycle count bulanan untuk barang kelas A (Analisis ABC).'),
    'importbarang' => array('panduan' => 'PanduanGudang', 'judul' => 'Impor Barang dari Excel',
        'ringkas' => 'Tambah barang baru & perbarui master/harga secara massal dari Excel, dengan pratinjau sebelum disimpan.',
        'langkah' => array('Unduh template (atau seluruh master barang untuk diedit).', 'Isi di Excel; sel kosong = tidak diubah.', 'Unggah, pilih gudang & mode, periksa pratinjau BARU / UPDATE / ERROR.', 'Klik <b>Proses impor</b>.'),
        'catatan' => 'Perubahan harga tercatat di log harga. Stok awal hanya untuk barang baru; koreksi stok barang lama lewat Stock Opname.'),
    'labelbarang' => array('panduan' => 'PanduanGudang', 'judul' => 'Label QR & Scan',
        'ringkas' => 'Cetak label QR barang (printer thermal atau stiker A4) dan scan untuk cek stok/rak.',
        'langkah' => array('Pilih barang dari dokumen Part Masuk, filter rak/kategori, atau dokumen opname.', 'Atur jumlah label, template & isi label.', 'Klik Pratinjau & cetak — atur skala 100% dan ukuran kertas sesuai template.'),
        'catatan' => 'QR berisi no. part sehingga dapat dibaca scanner apa pun dan menu Stock Opname.'),
    'laporanhr' => array('panduan' => 'PanduanSistem', 'judul' => 'Dashboard & Laporan HRD',
        'ringkas' => 'Komposisi pegawai, masa kerja, ulang tahun, rekap cuti & absensi, serta kelengkapan data pegawai.',
        'langkah' => array('Periksa <b>Kelengkapan Data</b> lalu lengkapi data pegawai di menu Employe.', 'Tarik data mesin absen, lalu buka <b>Rekap Absensi</b> per bulan.', 'Gunakan <b>Rekap Cuti</b> untuk memantau sisa hak cuti.'),
        'catatan' => 'Semua laporan dapat dicetak & diekspor ke Excel, grafiknya dapat diunduh sebagai gambar.'),
    'pengaturandashboard' => array('panduan' => 'PanduanSistem', 'judul' => 'Pengaturan Dashboard',
        'ringkas' => 'Menentukan widget dashboard per level user, per departemen, atau per user, serta pengumuman.',
        'langkah' => array('Klik <b>Atur</b> pada level/departemen, tambahkan widget atau muat preset, atur urutan & lebar.', 'Centang "Izinkan user mengatur dashboard pribadinya" bila perlu.', 'Petakan user ke departemen di bagian bawah.', 'Gunakan tombol mata untuk pratinjau.'),
        'catatan' => 'Prioritas: User > Departemen > Level > preset bawaan. Widget tetap mengikuti hak akses menu.'),
);

$config['laporan_hr'] = array(
    'komposisi' => array('tujuan' => 'Gambaran struktur SDM: berapa pegawai di setiap departemen, jabatan, pendidikan, gender, usia & status kerja.', 'baca' => array('Persentase dihitung dari pegawai aktif.', 'Matriks departemen × jabatan membantu melihat rentang kendali (span of control).'), 'sumber' => 'Data pegawai (menu Employe) & master HRD.', 'tips' => array('"Belum diisi" berarti data master pegawai belum lengkap.')),
    'masa_kerja' => array('tujuan' => 'Lama bekerja setiap pegawai dan siapa yang akan genap 5/10/15/20 tahun dalam 12 bulan ke depan.', 'baca' => array('Masa kerja dihitung dari tanggal masuk sampai hari ini.'), 'sumber' => 'Tanggal masuk di data pegawai.', 'tips' => array('Masa kerja menjadi dasar perhitungan pesangon, uang penghargaan masa kerja & hak cuti besar.')),
    'ulang_tahun' => array('tujuan' => 'Daftar pegawai yang berulang tahun pada bulan terpilih.', 'baca' => array('Grafik menunjukkan sebaran ulang tahun sepanjang tahun.'), 'sumber' => 'Tanggal lahir pegawai.', 'tips' => array()),
    'cuti' => array('tujuan' => 'Rekap hari cuti setiap pegawai per bulan & per jenis, serta perkiraan sisa hak cuti tahunan.', 'baca' => array('Setiap baris data cuti dihitung 1 hari.', 'Sisa = 12 − hari cuti (hak minimal UU Ketenagakerjaan).'), 'sumber' => 'Menu Cuti & kode cuti di Panel Setting HRD.', 'tips' => array('Bedakan kode cuti tahunan dan cuti khusus agar sisa hak cuti akurat.')),
    'absensi' => array('tujuan' => 'Rekap kehadiran bulanan dari mesin absen: hari hadir, % kehadiran, keterlambatan & rata-rata jam kerja.', 'baca' => array('Scan pertama di hari itu = jam masuk, scan terakhir = jam pulang.', 'Terlambat = jam masuk melewati jam masuk yang diatur (bawaan 08:00).', 'PIN yang belum terhubung ke pegawai ditandai.'), 'sumber' => 'tbl_hrd_data_absen (tarik data di menu Mesin Absen).', 'tips' => array('Isi NIPA pegawai dengan PIN mesin absen agar nama tampil.')),
    'kelengkapan' => array('tujuan' => 'Menemukan data pegawai yang belum diisi agar administrasi pajak, BPJS & kepegawaian lengkap.', 'baca' => array('Pegawai dengan kelengkapan terendah tampil paling atas.'), 'sumber' => 'Data pegawai.', 'tips' => array('NPWP/NIK terpadan dan BPJS wajib untuk PPh 21 & iuran jaminan sosial.')),
);
