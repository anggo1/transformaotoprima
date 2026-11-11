-- ============================================================================
--  TOP ERP v2 + v3 + v4 - MIGRASI DATABASE (untuk database yang SEDANG DIPAKAI)
--  v2 = modul akuntansi (Kas & Bank, Piutang/Hutang, Aset, Anggaran, Tutup Buku)
--  v3 = Perpajakan (PPN, PPh, Rekonsiliasi Fiskal), laporan detail & Panduan
--  v4 = Tampilan & Bahasa per pengguna, menu Gudang dirapikan, Laporan & Panduan Gudang
-- ----------------------------------------------------------------------------
--  Cara pakai : phpMyAdmin > pilih database db_transforma_oto_prima > tab SQL
--               > Import/tempel file ini > Go.
--  Aman       : - TIDAK menghapus data apa pun
--               - boleh dijalankan BERULANG (yang sudah ada dilewati otomatis),
--                 jadi bila sebelumnya sempat error di tengah jalan, cukup jalankan lagi
--  Kompatibel : MySQL 5.7 / 8.0, MariaDB 10.3+
--  Hasil akhir: sama persis dengan file db_transforma_oto_prima_v2_final.sql
--  Saran      : Export (backup) database dulu sebelum menjalankan.
-- ============================================================================

SET NAMES utf8mb4;
SET @OLD_SQL_MODE = @@SQL_MODE;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- ---------------------------------------------------------------------------
-- 1. DAFTAR AKUN : tipe akun (Accurate), induk, saldo normal, status
-- ---------------------------------------------------------------------------
ALTER TABLE `tbl_acc_akun` MODIFY `nama_akun` varchar(100) NOT NULL;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_akun' AND COLUMN_NAME = 'kategori') = 0,
  'ALTER TABLE `tbl_acc_akun` ADD COLUMN `kategori` varchar(20) NOT NULL DEFAULT '''' COMMENT ''tipe akun gaya Accurate'' AFTER `jenis_beban`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_akun' AND COLUMN_NAME = 'parent_kode') = 0,
  'ALTER TABLE `tbl_acc_akun` ADD COLUMN `parent_kode` varchar(25) NOT NULL DEFAULT '''' AFTER `kategori`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_akun' AND COLUMN_NAME = 'saldo_normal') = 0,
  'ALTER TABLE `tbl_acc_akun` ADD COLUMN `saldo_normal` enum(''D'',''K'') NOT NULL DEFAULT ''D'' AFTER `parent_kode`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_akun' AND COLUMN_NAME = 'is_active') = 0,
  'ALTER TABLE `tbl_acc_akun` ADD COLUMN `is_active` enum(''Y'',''N'') NOT NULL DEFAULT ''Y'' AFTER `saldo_normal`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_akun' AND COLUMN_NAME = 'keterangan') = 0,
  'ALTER TABLE `tbl_acc_akun` ADD COLUMN `keterangan` varchar(255) NOT NULL DEFAULT '''' AFTER `is_active`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;

-- isi tipe akun otomatis (hanya akun yang belum diisi)
UPDATE `tbl_acc_akun` SET `kategori` = CASE
    WHEN `nama_akun` LIKE 'AKUMULASI%' THEN 'AKUM_SUSUT'
    WHEN `nama_akun` LIKE 'PERSEDIAAN%' AND `kode_akun` LIKE '1%' THEN 'PERSEDIAAN'
    WHEN `nama_akun` LIKE 'PIUTANG%' AND `kode_akun` LIKE '1%' THEN 'PIUTANG'
    WHEN `nama_akun` LIKE 'HUTANG SUPPLIER%' THEN 'HUTANG'
    WHEN `nama_akun` LIKE 'PPN%' AND `kode_akun` LIKE '1%' THEN 'ASET_LANCAR'
    WHEN `nama_akun` LIKE 'PPN%' AND `kode_akun` LIKE '2%' THEN 'LIAB_PENDEK'
    WHEN `nama_akun` LIKE 'BIAYA PENYUSUTAN%' THEN 'BEBAN'
    WHEN `kode_akun` LIKE '111%' OR `kode_akun` LIKE '112%' THEN 'KAS_BANK'
    WHEN `kode_akun` LIKE '114%' THEN 'PIUTANG'
    WHEN `kode_akun` LIKE '115%' OR `kode_akun` LIKE '116%' THEN 'PERSEDIAAN'
    WHEN `kode_akun` LIKE '11%' THEN 'ASET_LANCAR'
    WHEN `kode_akun` LIKE '129%' THEN 'AKUM_SUSUT'
    WHEN `kode_akun` LIKE '12%' THEN 'ASET_TETAP'
    WHEN `kode_akun` LIKE '1%' THEN 'ASET_LAIN'
    WHEN `kode_akun` LIKE '211%' THEN 'HUTANG'
    WHEN `kode_akun` LIKE '21%' THEN 'LIAB_PENDEK'
    WHEN `kode_akun` LIKE '2%' THEN 'LIAB_PANJANG'
    WHEN `kode_akun` LIKE '3%' THEN 'EKUITAS'
    WHEN `kode_akun` LIKE '4%' THEN 'PENDAPATAN'
    WHEN `kode_akun` LIKE '51%' THEN 'HPP'
    WHEN `kode_akun` LIKE '52%' THEN 'BEBAN'
    WHEN `kode_akun` LIKE '532%' THEN 'PENDAPATAN_LAIN'
    WHEN `kode_akun` LIKE '53%' THEN 'BEBAN_LAIN'
    WHEN `kode_akun` LIKE '54%' THEN 'PAJAK_PENGHASILAN'
    WHEN `kode_akun` LIKE '5%' THEN 'BEBAN'
    WHEN `kode_akun` LIKE '6%' THEN 'BEBAN'
    WHEN `kode_akun` LIKE '7%' THEN 'PENDAPATAN_LAIN'
    WHEN `kode_akun` LIKE '8%' THEN 'BEBAN_LAIN'
    ELSE 'BEBAN' END
WHERE `kategori` = '';

-- akun pendukung (hanya ditambahkan bila belum ada)
INSERT INTO `tbl_acc_akun` (`kode_akun`, `nama_akun`, `kelompok`, `type_akun`, `jenis_beban`, `kategori`, `saldo_normal`, `is_active`)
SELECT * FROM (
    SELECT '1191' AS k, 'PPN MASUKAN' AS n, '' AS a, '' AS b, '' AS c, 'ASET_LANCAR' AS t, 'D' AS s, 'Y' AS y UNION ALL
    SELECT '1290', 'AKUMULASI PENYUSUTAN ASET TETAP', '', '', '', 'AKUM_SUSUT', 'K', 'Y' UNION ALL
    SELECT '2191', 'PPN KELUARAN', '', '', '', 'LIAB_PENDEK', 'K', 'Y'
) x WHERE NOT EXISTS (SELECT 1 FROM `tbl_acc_akun` a WHERE a.`kode_akun` = x.k);

UPDATE `tbl_acc_akun` SET `saldo_normal` =
    CASE WHEN `kategori` IN ('KAS_BANK','PIUTANG','PERSEDIAAN','ASET_LANCAR','ASET_TETAP','ASET_LAIN','HPP','BEBAN','BEBAN_LAIN','PAJAK_PENGHASILAN') THEN 'D' ELSE 'K' END;

SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_akun' AND INDEX_NAME = 'idx_akun_kode') = 0,
  'ALTER TABLE `tbl_acc_akun` ADD INDEX `idx_akun_kode` (`kode_akun`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_akun' AND INDEX_NAME = 'idx_akun_kategori') = 0,
  'ALTER TABLE `tbl_acc_akun` ADD INDEX `idx_akun_kategori` (`kategori`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;

-- ---------------------------------------------------------------------------
-- 2. JURNAL UMUM & SALDO AWAL : nilai desimal, sumber transaksi, index
-- ---------------------------------------------------------------------------
UPDATE `tbl_acc_jurnal_umum` SET `debit` = 0 WHERE `debit` IS NULL;
UPDATE `tbl_acc_jurnal_umum` SET `kredit` = 0 WHERE `kredit` IS NULL;
ALTER TABLE `tbl_acc_jurnal_umum`
  MODIFY `kode_awal` varchar(20) NOT NULL DEFAULT '',
  MODIFY `no_ref` varchar(20) NOT NULL DEFAULT '',
  MODIFY `no_jurnal` varchar(30) NOT NULL DEFAULT '',
  MODIFY `no_bukti` varchar(100) DEFAULT '',
  MODIFY `kode_akun` varchar(25) NOT NULL,
  MODIFY `debit` decimal(18,2) NOT NULL DEFAULT 0,
  MODIFY `kredit` decimal(18,2) NOT NULL DEFAULT 0,
  MODIFY `kelompok` varchar(10) NOT NULL DEFAULT '',
  MODIFY `type_akun` varchar(10) NOT NULL DEFAULT '',
  MODIFY `jenis_beban` varchar(10) NOT NULL DEFAULT '';
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_jurnal_umum' AND COLUMN_NAME = 'sumber') = 0,
  'ALTER TABLE `tbl_acc_jurnal_umum` ADD COLUMN `sumber` varchar(10) NOT NULL DEFAULT ''JU'' AFTER `jenis_beban`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_jurnal_umum' AND COLUMN_NAME = 'id_sumber') = 0,
  'ALTER TABLE `tbl_acc_jurnal_umum` ADD COLUMN `id_sumber` int NULL DEFAULT NULL AFTER `sumber`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_jurnal_umum' AND INDEX_NAME = 'idx_ju_tgl') = 0,
  'ALTER TABLE `tbl_acc_jurnal_umum` ADD INDEX `idx_ju_tgl` (`tgl_jurnal`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_jurnal_umum' AND INDEX_NAME = 'idx_ju_akun_tgl') = 0,
  'ALTER TABLE `tbl_acc_jurnal_umum` ADD INDEX `idx_ju_akun_tgl` (`kode_akun`, `tgl_jurnal`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_jurnal_umum' AND INDEX_NAME = 'idx_ju_ref') = 0,
  'ALTER TABLE `tbl_acc_jurnal_umum` ADD INDEX `idx_ju_ref` (`no_ref`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_jurnal_umum' AND INDEX_NAME = 'idx_ju_status') = 0,
  'ALTER TABLE `tbl_acc_jurnal_umum` ADD INDEX `idx_ju_status` (`status`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_jurnal_umum' AND INDEX_NAME = 'idx_ju_sumber') = 0,
  'ALTER TABLE `tbl_acc_jurnal_umum` ADD INDEX `idx_ju_sumber` (`sumber`, `id_sumber`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
UPDATE `tbl_acc_jurnal_umum` SET `no_bukti` = '' WHERE `no_bukti` = '''''';

UPDATE `tbl_acc_saldo_awal` SET `debit` = 0 WHERE `debit` IS NULL;
UPDATE `tbl_acc_saldo_awal` SET `kredit` = 0 WHERE `kredit` IS NULL;
ALTER TABLE `tbl_acc_saldo_awal`
  MODIFY `kode_akun` varchar(25) NOT NULL,
  MODIFY `debit` decimal(18,2) NOT NULL DEFAULT 0,
  MODIFY `kredit` decimal(18,2) NOT NULL DEFAULT 0;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_saldo_awal' AND INDEX_NAME = 'idx_sa_periode') = 0,
  'ALTER TABLE `tbl_acc_saldo_awal` ADD INDEX `idx_sa_periode` (`periode`, `kode_akun`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;

-- ---------------------------------------------------------------------------
-- 3. TABEL BARU (dibuat hanya bila belum ada)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_acc_setting` (
  `kunci` varchar(50) NOT NULL,
  `nilai` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`kunci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_periode` (
  `periode` char(7) NOT NULL COMMENT 'YYYY-MM',
  `status` enum('BUKA','TUTUP') NOT NULL DEFAULT 'TUTUP',
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(50) NOT NULL DEFAULT '',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_kas_bank` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(20) NOT NULL,
  `jenis` enum('MASUK','KELUAR','TRANSFER') NOT NULL,
  `tanggal` date NOT NULL,
  `kode_akun_kas` varchar(25) NOT NULL,
  `kode_akun_tujuan` varchar(25) NOT NULL DEFAULT '' COMMENT 'untuk transfer',
  `kontak` varchar(100) NOT NULL DEFAULT '',
  `no_bukti` varchar(50) NOT NULL DEFAULT '',
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `total` decimal(18,2) NOT NULL DEFAULT 0,
  `no_ref_jurnal` varchar(20) NOT NULL DEFAULT '',
  `status` enum('Y','BATAL') NOT NULL DEFAULT 'Y',
  `user` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_kb_no` (`no_transaksi`),
  KEY `idx_kb_tgl` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_kas_bank_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_kas_bank` int NOT NULL,
  `kode_akun` varchar(25) NOT NULL,
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `jumlah` decimal(18,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_kbd_header` (`id_kas_bank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_faktur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis` enum('AR','AP') NOT NULL COMMENT 'AR=piutang (penjualan), AP=hutang (pembelian)',
  `no_faktur` varchar(30) NOT NULL,
  `no_referensi` varchar(50) NOT NULL DEFAULT '' COMMENT 'no faktur pemasok / no PO / no SPK',
  `tanggal` date NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `kontak` varchar(100) NOT NULL,
  `kode_akun_ar_ap` varchar(25) NOT NULL,
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `subtotal` decimal(18,2) NOT NULL DEFAULT 0,
  `diskon` decimal(18,2) NOT NULL DEFAULT 0,
  `ppn_persen` decimal(5,2) NOT NULL DEFAULT 0,
  `ppn` decimal(18,2) NOT NULL DEFAULT 0,
  `total` decimal(18,2) NOT NULL DEFAULT 0,
  `terbayar` decimal(18,2) NOT NULL DEFAULT 0,
  `status` enum('BELUM','SEBAGIAN','LUNAS','BATAL') NOT NULL DEFAULT 'BELUM',
  `no_ref_jurnal` varchar(20) NOT NULL DEFAULT '',
  `user` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_faktur_no` (`jenis`, `no_faktur`),
  KEY `idx_faktur_kontak` (`jenis`, `kontak`),
  KEY `idx_faktur_tgl` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_faktur_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_faktur` int NOT NULL,
  `kode_akun` varchar(25) NOT NULL,
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `qty` decimal(12,2) NOT NULL DEFAULT 1,
  `harga` decimal(18,2) NOT NULL DEFAULT 0,
  `jumlah` decimal(18,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_fd_faktur` (`id_faktur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_pelunasan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_bukti` varchar(30) NOT NULL,
  `id_faktur` int NOT NULL,
  `tanggal` date NOT NULL,
  `kode_akun_kas` varchar(25) NOT NULL,
  `jumlah` decimal(18,2) NOT NULL DEFAULT 0,
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `no_ref_jurnal` varchar(20) NOT NULL DEFAULT '',
  `user` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pel_faktur` (`id_faktur`),
  KEY `idx_pel_tgl` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_aset` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_aset` varchar(30) NOT NULL,
  `nama_aset` varchar(150) NOT NULL,
  `kelompok_aset` varchar(50) NOT NULL DEFAULT '',
  `lokasi` varchar(50) NOT NULL DEFAULT '',
  `tgl_perolehan` date NOT NULL,
  `tgl_mulai_susut` date NOT NULL,
  `harga_perolehan` decimal(18,2) NOT NULL DEFAULT 0,
  `nilai_residu` decimal(18,2) NOT NULL DEFAULT 0,
  `umur_bulan` int NOT NULL DEFAULT 48,
  `metode` enum('GARIS_LURUS','SALDO_MENURUN','TIDAK') NOT NULL DEFAULT 'GARIS_LURUS',
  `akun_aset` varchar(25) NOT NULL,
  `akun_akumulasi` varchar(25) NOT NULL,
  `akun_beban` varchar(25) NOT NULL,
  `akun_lawan_perolehan` varchar(25) NOT NULL DEFAULT '' COMMENT 'kas/hutang saat perolehan dijurnal',
  `status` enum('AKTIF','DIJUAL','DIHAPUS') NOT NULL DEFAULT 'AKTIF',
  `tgl_lepas` date DEFAULT NULL,
  `nilai_lepas` decimal(18,2) NOT NULL DEFAULT 0,
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `no_ref_jurnal` varchar(20) NOT NULL DEFAULT '',
  `no_ref_lepas` varchar(20) NOT NULL DEFAULT '',
  `user` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_aset_kode` (`kode_aset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_aset_susut` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_aset` int NOT NULL,
  `periode` char(7) NOT NULL COMMENT 'YYYY-MM',
  `jumlah` decimal(18,2) NOT NULL DEFAULT 0,
  `no_ref_jurnal` varchar(20) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_susut` (`id_aset`, `periode`),
  KEY `idx_susut_periode` (`periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_anggaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tahun` smallint NOT NULL,
  `kode_akun` varchar(25) NOT NULL,
  `b01` decimal(18,2) NOT NULL DEFAULT 0, `b02` decimal(18,2) NOT NULL DEFAULT 0, `b03` decimal(18,2) NOT NULL DEFAULT 0,
  `b04` decimal(18,2) NOT NULL DEFAULT 0, `b05` decimal(18,2) NOT NULL DEFAULT 0, `b06` decimal(18,2) NOT NULL DEFAULT 0,
  `b07` decimal(18,2) NOT NULL DEFAULT 0, `b08` decimal(18,2) NOT NULL DEFAULT 0, `b09` decimal(18,2) NOT NULL DEFAULT 0,
  `b10` decimal(18,2) NOT NULL DEFAULT 0, `b11` decimal(18,2) NOT NULL DEFAULT 0, `b12` decimal(18,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_anggaran` (`tahun`, `kode_akun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `tbl_acc_setting` (`kunci`, `nilai`) VALUES
('akun_laba_ditahan', '321'),
('akun_laba_berjalan', '322'),
('akun_piutang', '114'),
('akun_hutang', '211'),
('akun_ppn_keluaran', '2191'),
('akun_ppn_masukan', '1191'),
('akun_akumulasi_default', '1290'),
('akun_beban_susut_default', '527'),
('akun_pendapatan_default', '411'),
('akun_beban_default', '525'),
('akun_laba_pelepasan_aset', '532'),
('akun_rugi_pelepasan_aset', '531'),
('ppn_persen', '11'),
('nama_perusahaan_laporan', '');

-- ---------------------------------------------------------------------------
-- 4. MENU & HAK AKSES
-- ---------------------------------------------------------------------------
-- menu yang tidak punya controller / link '#' (sebelumnya error 404)
UPDATE `tbl_submenu` SET `nama_submenu` = 'Neraca Saldo', `link` = 'LaporanKeuangan/neraca_saldo', `icon` = 'fa fa-balance-scale-left', `id_menu` = 65, `urutan` = 2 WHERE `id_submenu` = 65;
UPDATE `tbl_submenu` SET `nama_submenu` = 'Neraca', `link` = 'LaporanKeuangan/neraca', `icon` = 'fa fa-balance-scale', `id_menu` = 65, `urutan` = 3 WHERE `id_submenu` = 66;
UPDATE `tbl_submenu` SET `nama_submenu` = 'Pusat Laporan Keuangan', `link` = 'LaporanKeuangan', `icon` = 'fa fa-folder-open' WHERE `id_submenu` = 67;
UPDATE `tbl_submenu` SET `nama_submenu` = 'Laba Rugi', `link` = 'LaporanKeuangan/laba_rugi', `icon` = 'fa fa-chart-line', `urutan` = 4 WHERE `id_submenu` = 68;

-- urutan menu Accounting mengikuti alur Accurate
UPDATE `tbl_submenu` SET `urutan` = 1 WHERE `id_submenu` = 62;  -- Daftar Akun
UPDATE `tbl_submenu` SET `urutan` = 2 WHERE `id_submenu` = 93;  -- Saldo Awal
UPDATE `tbl_submenu` SET `urutan` = 3 WHERE `id_submenu` = 63;  -- Jurnal Umum
UPDATE `tbl_submenu` SET `urutan` = 9, `id_menu` = 65 WHERE `id_submenu` = 64;  -- Buku Besar -> Report Accounting
UPDATE `tbl_submenu` SET `urutan` = 20 WHERE `id_submenu` = 82; -- Panel Setting

INSERT INTO `tbl_submenu` (`nama_submenu`, `link`, `icon`, `id_menu`, `is_active`, `urutan`)
SELECT * FROM (
    SELECT 'Kas & Bank' AS n, 'KasBank' AS l, 'fa fa-wallet' AS i, 60 AS m, 'Y' AS a, 4 AS u UNION ALL
    SELECT 'Piutang Usaha', 'Piutang', 'fa fa-file-invoice-dollar', 60, 'Y', 5 UNION ALL
    SELECT 'Hutang Usaha', 'Hutang', 'fa fa-file-invoice', 60, 'Y', 6 UNION ALL
    SELECT 'Aset Tetap', 'AsetTetap', 'fa fa-building', 60, 'Y', 7 UNION ALL
    SELECT 'Anggaran', 'Anggaran', 'fa fa-bullseye', 60, 'Y', 8 UNION ALL
    SELECT 'Tutup Buku & Preferensi', 'PeriodeAkuntansi', 'fa fa-lock', 60, 'Y', 9 UNION ALL
    SELECT 'Arus Kas', 'LaporanKeuangan/arus_kas', 'fa fa-water', 65, 'Y', 5 UNION ALL
    SELECT 'Umur Piutang & Hutang', 'LaporanKeuangan/umur_piutang', 'fa fa-hourglass-half', 65, 'Y', 6 UNION ALL
    SELECT 'Nilai Persediaan & Stok Min', 'ReportWhNilaiStok', 'fa fa-boxes', 61, 'Y', 9
) x
WHERE NOT EXISTS (SELECT 1 FROM `tbl_submenu` s WHERE s.`link` = x.l);

-- hak akses: Super Admin (1) & Accounting (10) = penuh, level lain = tidak terlihat
INSERT INTO `tbl_akses_submenu` (`id_level`, `id_submenu`, `view_level`, `add_level`, `edit_level`, `delete_level`, `print_level`, `upload_level`)
SELECT l.`id_level`, s.`id_submenu`,
       IF(l.`id_level` IN (1, 10), 'Y', 'N'), IF(l.`id_level` IN (1, 10), 'Y', 'N'), IF(l.`id_level` IN (1, 10), 'Y', 'N'),
       IF(l.`id_level` IN (1, 10), 'Y', 'N'), IF(l.`id_level` IN (1, 10), 'Y', 'N'), 'N'
FROM `tbl_userlevel` l
JOIN `tbl_submenu` s ON s.`link` IN ('KasBank','Piutang','Hutang','AsetTetap','Anggaran','PeriodeAkuntansi','LaporanKeuangan/arus_kas','LaporanKeuangan/umur_piutang')
WHERE NOT EXISTS (SELECT 1 FROM `tbl_akses_submenu` a WHERE a.`id_level` = l.`id_level` AND a.`id_submenu` = s.`id_submenu`);

-- laporan nilai persediaan: ikuti hak akses menu "Report Perbarang" yang sudah ada
INSERT INTO `tbl_akses_submenu` (`id_level`, `id_submenu`, `view_level`, `add_level`, `edit_level`, `delete_level`, `print_level`, `upload_level`)
SELECT a.`id_level`, s.`id_submenu`, a.`view_level`, 'N', 'N', 'N', a.`print_level`, 'N'
FROM `tbl_akses_submenu` a
JOIN `tbl_submenu` ref ON ref.`id_submenu` = a.`id_submenu` AND ref.`link` = 'ReportWhPerbarang'
JOIN `tbl_submenu` s ON s.`link` = 'ReportWhNilaiStok'
WHERE NOT EXISTS (SELECT 1 FROM `tbl_akses_submenu` x WHERE x.`id_level` = a.`id_level` AND x.`id_submenu` = s.`id_submenu`);

-- level Accounting (10) juga dapat melihat menu Accounting & Report Accounting
UPDATE `tbl_akses_submenu` a JOIN `tbl_submenu` s ON s.`id_submenu` = a.`id_submenu`
SET a.`view_level` = 'Y', a.`add_level` = 'Y', a.`edit_level` = 'Y', a.`delete_level` = 'Y', a.`print_level` = 'Y'
WHERE a.`id_level` = 10 AND s.`id_menu` IN (60, 65);

INSERT INTO `tbl_akses_menu` (`id_level`, `id_menu`, `view_level`)
SELECT 10, m.`id_menu`, 'Y' FROM `tbl_menu` m
WHERE m.`id_menu` IN (60, 65) AND NOT EXISTS (SELECT 1 FROM `tbl_akses_menu` a WHERE a.`id_level` = 10 AND a.`id_menu` = m.`id_menu`);
UPDATE `tbl_akses_menu` SET `view_level` = 'Y' WHERE `id_level` = 10 AND `id_menu` IN (60, 65);

-- ---------------------------------------------------------------------------
-- 5. INDEX TAMBAHAN (menu & login lebih cepat)
-- ---------------------------------------------------------------------------
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_akses_submenu' AND INDEX_NAME = 'idx_aks_level_sub') = 0,
  'ALTER TABLE `tbl_akses_submenu` ADD INDEX `idx_aks_level_sub` (`id_level`, `id_submenu`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_akses_menu' AND INDEX_NAME = 'idx_akm_level_menu') = 0,
  'ALTER TABLE `tbl_akses_menu` ADD INDEX `idx_akm_level_menu` (`id_level`, `id_menu`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_submenu' AND INDEX_NAME = 'idx_sub_menu') = 0,
  'ALTER TABLE `tbl_submenu` ADD INDEX `idx_sub_menu` (`id_menu`, `urutan`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_submenu' AND INDEX_NAME = 'idx_sub_link') = 0,
  'ALTER TABLE `tbl_submenu` ADD INDEX `idx_sub_link` (`link`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_user' AND INDEX_NAME = 'idx_user_username') = 0,
  'ALTER TABLE `tbl_user` ADD INDEX `idx_user_username` (`username`)', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;

SET SQL_MODE = @OLD_SQL_MODE;

-- ---------------------------------------------------------------------------
-- 6. CEK HASIL (tampil di phpMyAdmin setelah Go)
-- ---------------------------------------------------------------------------
SELECT `kategori` AS tipe_akun, COUNT(*) AS jumlah_akun FROM `tbl_acc_akun` GROUP BY `kategori`;
SELECT `id_submenu`, `nama_submenu`, `link` FROM `tbl_submenu`
 WHERE `link` IN ('KasBank','Piutang','Hutang','AsetTetap','Anggaran','PeriodeAkuntansi','LaporanKeuangan','LaporanKeuangan/neraca','LaporanKeuangan/neraca_saldo','LaporanKeuangan/laba_rugi','LaporanKeuangan/arus_kas','LaporanKeuangan/umur_piutang','ReportWhNilaiStok');
-- selesai

-- ============================================================================
-- BAGIAN v3 : PERPAJAKAN, LAPORAN DETAIL & PANDUAN  (ditambahkan 11-11-2026)
-- Aman dijalankan berulang. Bila database Anda SUDAH pernah menjalankan bagian
-- v2 di atas, menjalankan seluruh file ini lagi hanya akan menambah bagian v3.
-- ============================================================================
SET @OLD_SQL_MODE3 = @@SQL_MODE;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- 7. Kolom baru ---------------------------------------------------------------
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_akun' AND COLUMN_NAME = 'fiskal') = 0,
  'ALTER TABLE `tbl_acc_akun` ADD COLUMN `fiskal` varchar(10) NOT NULL DEFAULT '''' COMMENT ''perlakuan fiskal: kosong / POSITIF / FINAL'' AFTER `keterangan`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_faktur' AND COLUMN_NAME = 'no_faktur_pajak') = 0,
  'ALTER TABLE `tbl_acc_faktur` ADD COLUMN `no_faktur_pajak` varchar(30) NOT NULL DEFAULT '''' COMMENT ''Nomor Seri Faktur Pajak (NSFP)'' AFTER `no_referensi`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_pelunasan' AND COLUMN_NAME = 'potongan') = 0,
  'ALTER TABLE `tbl_acc_pelunasan` ADD COLUMN `potongan` decimal(18,2) NOT NULL DEFAULT 0 COMMENT ''PPh dipotong (bukti potong)'' AFTER `jumlah`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_pelunasan' AND COLUMN_NAME = 'jenis_pph') = 0,
  'ALTER TABLE `tbl_acc_pelunasan` ADD COLUMN `jenis_pph` varchar(10) NOT NULL DEFAULT '''' AFTER `potongan`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;
SET @q = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_acc_pelunasan' AND COLUMN_NAME = 'no_bukti_potong') = 0,
  'ALTER TABLE `tbl_acc_pelunasan` ADD COLUMN `no_bukti_potong` varchar(50) NOT NULL DEFAULT '''' AFTER `jenis_pph`', 'DO 0');
PREPARE st FROM @q; EXECUTE st; DEALLOCATE PREPARE st;

-- 8. Tabel baru ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_acc_setor_pajak` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_bukti` varchar(30) NOT NULL DEFAULT '',
  `tanggal` date NOT NULL,
  `jenis` varchar(10) NOT NULL COMMENT 'PPN, PPH21, PPH23, PPH42, PPH25, PPH29, PPHFINAL',
  `masa` varchar(7) NOT NULL DEFAULT '' COMMENT 'YYYY-MM (masa) atau YYYY (tahunan)',
  `kode_akun_pajak` varchar(25) NOT NULL,
  `kode_akun_kas` varchar(25) NOT NULL,
  `jumlah` decimal(18,2) NOT NULL DEFAULT 0,
  `ntpn` varchar(30) NOT NULL DEFAULT '' COMMENT 'Nomor Transaksi Penerimaan Negara',
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `no_ref_jurnal` varchar(20) NOT NULL DEFAULT '',
  `user` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_setor_tgl` (`tanggal`),
  KEY `idx_setor_jenis` (`jenis`,`masa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_acc_koreksi_fiskal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tahun` smallint NOT NULL,
  `jenis` varchar(8) NOT NULL DEFAULT 'POSITIF' COMMENT 'POSITIF / NEGATIF',
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `kode_akun` varchar(25) NOT NULL DEFAULT '',
  `nilai` decimal(18,2) NOT NULL DEFAULT 0,
  `user` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_kf_tahun` (`tahun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 9. Akun pajak (hanya ditambahkan bila nomor akun belum dipakai) -------------
INSERT INTO `tbl_acc_akun` (`kode_akun`, `nama_akun`, `kelompok`, `type_akun`, `jenis_beban`, `kategori`, `saldo_normal`, `is_active`, `keterangan`)
SELECT * FROM (
    SELECT '1192' AS k, 'PPH 22 DIBAYAR DIMUKA' AS n, '' AS a, '' AS b, '' AS c, 'ASET_LANCAR' AS t, 'D' AS s, 'Y' AS y, 'Kredit pajak: PPh 22 dipungut saat impor/pembelian' AS ket UNION ALL
    SELECT '1193', 'PPH 23 DIBAYAR DIMUKA', '', '', '', 'ASET_LANCAR', 'D', 'Y', 'Kredit pajak: PPh 23 dipotong pelanggan' UNION ALL
    SELECT '1194', 'PPH 25 DIBAYAR DIMUKA', '', '', '', 'ASET_LANCAR', 'D', 'Y', 'Kredit pajak: angsuran PPh 25 yang sudah disetor' UNION ALL
    SELECT '2192', 'HUTANG PPN', '', '', '', 'LIAB_PENDEK', 'K', 'Y', 'PPN kurang bayar hasil tutup masa PPN' UNION ALL
    SELECT '2193', 'HUTANG PPH 21', '', '', '', 'LIAB_PENDEK', 'K', 'Y', 'PPh 21 dipotong dari gaji, wajib disetor tgl 15' UNION ALL
    SELECT '2194', 'HUTANG PPH 23', '', '', '', 'LIAB_PENDEK', 'K', 'Y', 'PPh 23 dipotong dari pemasok jasa' UNION ALL
    SELECT '2195', 'HUTANG PPH 4 AYAT 2', '', '', '', 'LIAB_PENDEK', 'K', 'Y', 'PPh final sewa / jasa konstruksi' UNION ALL
    SELECT '2196', 'HUTANG PPH BADAN PASAL 29', '', '', '', 'LIAB_PENDEK', 'K', 'Y', 'Kurang bayar PPh Badan tahunan' UNION ALL
    SELECT '541', 'BEBAN PPH BADAN', '', '', '', 'PAJAK_PENGHASILAN', 'D', 'Y', 'Pajak penghasilan badan tahun berjalan' UNION ALL
    SELECT '542', 'BEBAN PPH FINAL UMKM', '', '', '', 'PAJAK_PENGHASILAN', 'D', 'Y', 'PPh final 0,5% x peredaran bruto'
) x WHERE NOT EXISTS (SELECT 1 FROM `tbl_acc_akun` a WHERE a.`kode_akun` = x.k);

-- saldo normal ikut tipe akun (termasuk tipe baru Beban Pajak Penghasilan)
UPDATE `tbl_acc_akun` SET `saldo_normal` =
    CASE WHEN `kategori` IN ('KAS_BANK','PIUTANG','PERSEDIAAN','ASET_LANCAR','ASET_TETAP','ASET_LAIN','HPP','BEBAN','BEBAN_LAIN','PAJAK_PENGHASILAN') THEN 'D' ELSE 'K' END;

-- 10. Pengaturan pajak ----------------------------------------------------------
INSERT IGNORE INTO `tbl_acc_setting` (`kunci`, `nilai`) VALUES
('akun_pph22_dimuka', '1192'),
('akun_pph23_dimuka', '1193'),
('akun_pph25_dimuka', '1194'),
('akun_hutang_ppn', '2192'),
('akun_hutang_pph21', '2193'),
('akun_hutang_pph23', '2194'),
('akun_hutang_pph42', '2195'),
('akun_hutang_pph_badan', '2196'),
('akun_beban_pph_badan', '541'),
('akun_beban_pph_final', '542'),
('pajak_npwp', ''),
('pajak_nama_wp', ''),
('pajak_pkp', 'Y'),
('pajak_rezim', 'UMUM'),
('pajak_tarif_badan', '22'),
('pajak_tarif_final', '0.5'),
('pph23_persen', '2'),
('ppn_dpp_nilai_lain', 'Y');

-- 11. Menu ----------------------------------------------------------------------
-- Buku Besar lama diarahkan ke Buku Besar baru (rincian + saldo berjalan + cetak/Excel)
UPDATE `tbl_submenu` SET `link` = 'LaporanKeuangan/buku_besar', `urutan` = 9 WHERE `id_submenu` = 64 AND `link` = 'BukuBesar';
UPDATE `tbl_submenu` SET `urutan` = 10 WHERE `link` = 'PeriodeAkuntansi';
UPDATE `tbl_submenu` SET `urutan` = 14 WHERE `link` = 'ReportAccOpname';
-- Panel Setting diberi nama yang menjelaskan isinya
UPDATE `tbl_submenu` SET `nama_submenu` = 'Kelompok Akun & Akun Pemasok' WHERE `link` = 'SettingAcc' AND `nama_submenu` = 'Panel Setting';

INSERT INTO `tbl_submenu` (`nama_submenu`, `link`, `icon`, `id_menu`, `is_active`, `urutan`)
SELECT * FROM (
    SELECT 'Panduan Akuntansi & Pajak' AS n, 'PanduanAkuntansi' AS l, 'fa fa-question-circle' AS i, 60 AS m, 'Y' AS a, 0 AS u UNION ALL
    SELECT 'Perpajakan', 'Pajak', 'fa fa-receipt', 60, 'Y', 9 UNION ALL
    SELECT 'Laporan Pajak (PPN & PPh)', 'LaporanKeuangan/pajak_tahunan', 'fa fa-receipt', 65, 'Y', 7 UNION ALL
    SELECT 'Rekonsiliasi Fiskal', 'LaporanKeuangan/rekon_fiskal', 'fa fa-calculator', 65, 'Y', 8 UNION ALL
    SELECT 'Analisis Rasio Keuangan', 'LaporanKeuangan/rasio', 'fa fa-tachometer-alt', 65, 'Y', 10 UNION ALL
    SELECT 'Laporan Keuangan Lengkap', 'LaporanKeuangan/paket', 'fa fa-book-open', 65, 'Y', 11 UNION ALL
    SELECT 'Kartu Piutang & Hutang', 'LaporanKeuangan/kartu_piutang', 'fa fa-address-card', 65, 'Y', 12 UNION ALL
    SELECT 'Pemeriksaan Kesehatan Data', 'LaporanKeuangan/cek_data', 'fa fa-stethoscope', 65, 'Y', 13
) x
WHERE NOT EXISTS (SELECT 1 FROM `tbl_submenu` s WHERE s.`link` = x.l);

INSERT INTO `tbl_akses_submenu` (`id_level`, `id_submenu`, `view_level`, `add_level`, `edit_level`, `delete_level`, `print_level`, `upload_level`)
SELECT l.`id_level`, s.`id_submenu`,
       IF(l.`id_level` IN (1, 10), 'Y', 'N'), IF(l.`id_level` IN (1, 10), 'Y', 'N'), IF(l.`id_level` IN (1, 10), 'Y', 'N'),
       IF(l.`id_level` IN (1, 10), 'Y', 'N'), IF(l.`id_level` IN (1, 10), 'Y', 'N'), 'N'
FROM `tbl_userlevel` l
JOIN `tbl_submenu` s ON s.`link` IN ('PanduanAkuntansi','Pajak','LaporanKeuangan/pajak_tahunan','LaporanKeuangan/rekon_fiskal','LaporanKeuangan/rasio','LaporanKeuangan/paket','LaporanKeuangan/kartu_piutang','LaporanKeuangan/cek_data')
WHERE NOT EXISTS (SELECT 1 FROM `tbl_akses_submenu` a WHERE a.`id_level` = l.`id_level` AND a.`id_submenu` = s.`id_submenu`);

SET SQL_MODE = @OLD_SQL_MODE3;

-- 12. Cek hasil v3 ----------------------------------------------------------------
SELECT `kode_akun`, `nama_akun`, `kategori` FROM `tbl_acc_akun` WHERE `kode_akun` IN ('1191','1192','1193','1194','2191','2192','2193','2194','2195','2196','541','542') ORDER BY `kode_akun`;
SELECT `id_submenu`, `nama_submenu`, `link` FROM `tbl_submenu` WHERE `link` IN ('PanduanAkuntansi','Pajak','LaporanKeuangan/pajak_tahunan','LaporanKeuangan/rekon_fiskal','LaporanKeuangan/rasio','LaporanKeuangan/paket','LaporanKeuangan/kartu_piutang','LaporanKeuangan/cek_data','LaporanKeuangan/buku_besar');
-- selesai v3

-- ============================================================================
-- BAGIAN v4 : TAMPILAN & BAHASA, GUDANG (menu rapi, laporan detail, panduan)
-- Aman dijalankan berulang.
-- ============================================================================
SET @OLD_SQL_MODE4 = @@SQL_MODE;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- 13. Preferensi tampilan & bahasa per pengguna (id_user 0 = default sistem)
CREATE TABLE IF NOT EXISTS `tbl_user_preferensi` (
  `id_user` int NOT NULL,
  `data` text,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 14. Menu gudang: nama, urutan & ikon dirapikan (link / alur tidak berubah)
UPDATE `tbl_submenu` SET `nama_submenu` = 'Material & Sparepart', `urutan` = 1,  `icon` = 'fas fa-boxes'          WHERE `link` = 'Sparepart';
UPDATE `tbl_submenu` SET `nama_submenu` = 'Update Harga Part',    `urutan` = 2,  `icon` = 'fas fa-tags'           WHERE `link` = 'UpdateHpart';
UPDATE `tbl_submenu` SET `urutan` = 3,  `icon` = 'fas fa-file-invoice'    WHERE `link` = 'PurchaseOrder';
UPDATE `tbl_submenu` SET `urutan` = 4,  `icon` = 'fas fa-cart-plus'       WHERE `link` = 'PartOrder';
UPDATE `tbl_submenu` SET `urutan` = 5,  `icon` = 'fas fa-file-signature'  WHERE `link` = 'EstimasiPenawaran';
UPDATE `tbl_submenu` SET `urutan` = 6,  `icon` = 'fas fa-inbox'           WHERE `link` = 'PoMasuk';
UPDATE `tbl_submenu` SET `urutan` = 7,  `icon` = 'fas fa-dolly-flatbed'   WHERE `link` = 'Part_masuk_po';
UPDATE `tbl_submenu` SET `urutan` = 8,  `icon` = 'fas fa-truck-loading'   WHERE `link` = 'Part_masuk_npo';
UPDATE `tbl_submenu` SET `urutan` = 9,  `icon` = 'fas fa-dolly'           WHERE `link` = 'Part_keluar';
UPDATE `tbl_submenu` SET `urutan` = 10, `icon` = 'fas fa-tools'           WHERE `link` = 'PartKeluarWo';
UPDATE `tbl_submenu` SET `urutan` = 11, `icon` = 'fas fa-shipping-fast'   WHERE `link` = 'PartKeluarPo';
UPDATE `tbl_submenu` SET `urutan` = 12, `icon` = 'fas fa-file-alt'        WHERE `link` = 'SuratJalan';
UPDATE `tbl_submenu` SET `urutan` = 13, `icon` = 'fas fa-clipboard-check' WHERE `link` = 'StokOpname';
UPDATE `tbl_submenu` SET `nama_submenu` = 'Pengaturan Gudang', `urutan` = 20, `icon` = 'fas fa-cogs' WHERE `link` = 'Settingwh';
-- submenu kosong yang tidak dipakai (link '#', nonaktif)
DELETE FROM `tbl_akses_submenu` WHERE `id_submenu` IN (SELECT x.id FROM (SELECT `id_submenu` AS id FROM `tbl_submenu` WHERE `nama_submenu` = 'Transfer Stock Part' AND `link` = '#') x);
DELETE FROM `tbl_submenu` WHERE `nama_submenu` = 'Transfer Stock Part' AND `link` = '#';

-- laporan gudang lama: dipindah ke bawah, laporan baru di atas
UPDATE `tbl_submenu` SET `urutan` = 20 WHERE `link` = 'ReportWhPo';
UPDATE `tbl_submenu` SET `urutan` = 21 WHERE `link` = 'ReportWhPoMasuk';
UPDATE `tbl_submenu` SET `urutan` = 22 WHERE `link` = 'ReportWhPartorder';
UPDATE `tbl_submenu` SET `urutan` = 23 WHERE `link` = 'ReportWhPenawaran';
UPDATE `tbl_submenu` SET `urutan` = 24 WHERE `link` = 'ReportWhPerbarang';
UPDATE `tbl_submenu` SET `urutan` = 25 WHERE `link` = 'ReportWhPerkategori';
UPDATE `tbl_submenu` SET `urutan` = 26 WHERE `link` = 'ReportWhMasuk';
UPDATE `tbl_submenu` SET `urutan` = 27 WHERE `link` = 'ReportWhKeluar';
UPDATE `tbl_submenu` SET `urutan` = 28 WHERE `link` = 'ReportWhReturn';
UPDATE `tbl_submenu` SET `urutan` = 29 WHERE `link` = 'ReportOpname';
UPDATE `tbl_submenu` SET `urutan` = 30 WHERE `link` = 'ReportWhMasukPerbarang';
UPDATE `tbl_submenu` SET `urutan` = 31 WHERE `link` = 'ReportWhPartpk';
UPDATE `tbl_submenu` SET `nama_submenu` = 'Nilai Persediaan', `urutan` = 4 WHERE `link` = 'ReportWhNilaiStok';

INSERT INTO `tbl_submenu` (`nama_submenu`, `link`, `icon`, `id_menu`, `is_active`, `urutan`)
SELECT * FROM (
    SELECT 'Panduan Gudang' AS n, 'PanduanGudang' AS l, 'fas fa-question-circle' AS i, 57 AS m, 'Y' AS a, 0 AS u UNION ALL
    SELECT 'Dashboard & Laporan Gudang', 'LaporanGudang', 'fas fa-warehouse', 61, 'Y', 0 UNION ALL
    SELECT 'Kartu Stok', 'LaporanGudang/kartu_stok', 'fas fa-id-card-alt', 61, 'Y', 1 UNION ALL
    SELECT 'Mutasi Stok', 'LaporanGudang/mutasi', 'fas fa-exchange-alt', 61, 'Y', 2 UNION ALL
    SELECT 'Stok Minimum & Saran Pesan', 'LaporanGudang/stok_minimum', 'fas fa-exclamation-triangle', 61, 'Y', 3 UNION ALL
    SELECT 'Nilai Persediaan', 'ReportWhNilaiStok', 'fas fa-coins', 61, 'Y', 4 UNION ALL
    SELECT 'PO Outstanding', 'LaporanGudang/outstanding', 'fas fa-clipboard-list', 61, 'Y', 5 UNION ALL
    SELECT 'Pemeriksaan Data Gudang', 'LaporanGudang/cek_data', 'fas fa-stethoscope', 61, 'Y', 6
) x
WHERE NOT EXISTS (SELECT 1 FROM `tbl_submenu` s WHERE s.`link` = x.l);

-- hak akses: Panduan Gudang mengikuti hak menu Material & Sparepart; laporan mengikuti "Pengeluaran Barang"
INSERT INTO `tbl_akses_submenu` (`id_level`, `id_submenu`, `view_level`, `add_level`, `edit_level`, `delete_level`, `print_level`, `upload_level`)
SELECT r.`id_level`, s.`id_submenu`, r.`view_level`, 'N', 'N', 'N', r.`view_level`, 'N'
FROM `tbl_akses_submenu` r
JOIN `tbl_submenu` ref ON ref.`id_submenu` = r.`id_submenu` AND ref.`link` = 'Sparepart'
JOIN `tbl_submenu` s ON s.`link` = 'PanduanGudang'
WHERE NOT EXISTS (SELECT 1 FROM (SELECT `id_level`, `id_submenu` FROM `tbl_akses_submenu`) a WHERE a.`id_level` = r.`id_level` AND a.`id_submenu` = s.`id_submenu`);

INSERT INTO `tbl_akses_submenu` (`id_level`, `id_submenu`, `view_level`, `add_level`, `edit_level`, `delete_level`, `print_level`, `upload_level`)
SELECT r.`id_level`, s.`id_submenu`, r.`view_level`, 'N', 'N', 'N', IF(r.`print_level` = 'Y' OR r.`view_level` = 'Y', 'Y', 'N'), 'N'
FROM `tbl_akses_submenu` r
JOIN `tbl_submenu` ref ON ref.`id_submenu` = r.`id_submenu` AND ref.`link` = 'ReportWhKeluar'
JOIN `tbl_submenu` s ON s.`link` IN ('LaporanGudang', 'LaporanGudang/kartu_stok', 'LaporanGudang/mutasi', 'LaporanGudang/stok_minimum', 'ReportWhNilaiStok', 'LaporanGudang/outstanding', 'LaporanGudang/cek_data')
WHERE NOT EXISTS (SELECT 1 FROM (SELECT `id_level`, `id_submenu` FROM `tbl_akses_submenu`) a WHERE a.`id_level` = r.`id_level` AND a.`id_submenu` = s.`id_submenu`);

-- 15. Menu utama "Dashboard Warehouse" (sebelumnya kosong) -> Dashboard Gudang
UPDATE `tbl_menu` SET `nama_menu` = 'Dashboard Gudang', `link` = 'LaporanGudang', `icon` = 'fas fa-warehouse', `urutan` = 6 WHERE `id_menu` = 63 AND `link` = '#';
UPDATE `tbl_menu` SET `urutan` = 7 WHERE `id_menu` = 57 AND `urutan` = 6;
UPDATE `tbl_menu` SET `urutan` = 8 WHERE `id_menu` = 61 AND `urutan` = 7;
UPDATE `tbl_akses_menu` m JOIN (SELECT `id_level`, `view_level` FROM `tbl_akses_menu` WHERE `id_menu` = 61) r ON r.`id_level` = m.`id_level`
   SET m.`view_level` = r.`view_level` WHERE m.`id_menu` = 63;

SET SQL_MODE = @OLD_SQL_MODE4;

-- 16. Cek hasil v4
SELECT s.`id_submenu`, s.`id_menu`, s.`nama_submenu`, s.`link`, s.`urutan` FROM `tbl_submenu` s WHERE s.`id_menu` IN (57, 61) ORDER BY s.`id_menu`, s.`urutan`;
-- selesai v4


-- ============================================================================
-- BAGIAN v5 : DASHBOARD PER LEVEL/DEPARTEMEN, STOCK OPNAME BARU, IMPORT EXCEL,
--             HARGA RATA-RATA, LABEL QR, PANEL HR, PANDUAN SISTEM
-- Aman dijalankan berulang.
-- ============================================================================
SET @OLD_SQL_MODE5 = @@SQL_MODE;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- 17. Gudang: pengaturan, log penyesuaian stok, stock opname baru, harga rata-rata
CREATE TABLE IF NOT EXISTS `tbl_wh_setting` (
  `kunci` varchar(50) NOT NULL,
  `nilai` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`kunci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT IGNORE INTO `tbl_wh_setting` (`kunci`, `nilai`) VALUES
('metode_nilai', 'NET'), ('so_toleransi', '0'), ('so_buta', 'Y'), ('so_jurnal', 'N'),
('akun_persediaan', ''), ('akun_selisih_persediaan', ''), ('label_template', 'T5030');

CREATE TABLE IF NOT EXISTS `tbl_wh_penyesuaian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tgl` date NOT NULL,
  `lokasi` varchar(5) NOT NULL DEFAULT '',
  `no_part` varchar(50) NOT NULL,
  `qty` decimal(14,3) NOT NULL DEFAULT 0 COMMENT '+ menambah stok, - mengurangi stok',
  `harga` decimal(15,2) NOT NULL DEFAULT 0,
  `sumber` varchar(10) NOT NULL DEFAULT '' COMMENT 'SO = stock opname, IMP = import Excel',
  `no_dok` varchar(40) NOT NULL DEFAULT '',
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_peny_part` (`no_part`),
  KEY `idx_peny_tgl` (`tgl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_wh_so` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_so` varchar(30) NOT NULL,
  `tgl` date NOT NULL,
  `lokasi` varchar(5) NOT NULL,
  `cakupan` varchar(20) NOT NULL DEFAULT 'SEMUA',
  `filter` varchar(100) NOT NULL DEFAULT '',
  `buta` char(1) NOT NULL DEFAULT 'Y',
  `toleransi` decimal(6,2) NOT NULL DEFAULT 0,
  `status` varchar(12) NOT NULL DEFAULT 'DRAFT' COMMENT 'DRAFT, HITUNG, REVIEW, DISETUJUI, BATAL',
  `catatan` varchar(255) NOT NULL DEFAULT '',
  `mulai` datetime DEFAULT NULL,
  `selesai_hitung` datetime DEFAULT NULL,
  `disetujui_oleh` varchar(50) NOT NULL DEFAULT '',
  `tgl_posting` datetime DEFAULT NULL,
  `no_ref_jurnal` varchar(20) NOT NULL DEFAULT '',
  `dibuat_oleh` varchar(50) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_no_so` (`no_so`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_wh_so_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_so` int NOT NULL,
  `no_part` varchar(50) NOT NULL,
  `nama_part` varchar(100) NOT NULL DEFAULT '',
  `satuan` varchar(25) NOT NULL DEFAULT '',
  `kategori` varchar(50) NOT NULL DEFAULT '',
  `rak` varchar(25) NOT NULL DEFAULT '',
  `stok_snapshot` decimal(14,3) NOT NULL DEFAULT 0,
  `hitung1` decimal(14,3) DEFAULT NULL,
  `hitung2` decimal(14,3) DEFAULT NULL,
  `fisik` decimal(14,3) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL DEFAULT 0,
  `penghitung` varchar(50) NOT NULL DEFAULT '',
  `waktu_hitung` datetime DEFAULT NULL,
  `catatan` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_sod_so` (`id_so`),
  KEY `idx_sod_part` (`id_so`, `no_part`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_wh_harga_rata` (
  `no_part` varchar(50) NOT NULL,
  `lokasi` varchar(5) NOT NULL,
  `harga_rata` decimal(15,2) NOT NULL DEFAULT 0,
  `qty` decimal(14,3) NOT NULL DEFAULT 0,
  `harga_beli_terakhir` decimal(15,2) NOT NULL DEFAULT 0,
  `tgl_beli_terakhir` date DEFAULT NULL,
  `jumlah_beli` int NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`no_part`, `lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_wh_impor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_dok` varchar(30) NOT NULL,
  `tgl` datetime NOT NULL,
  `user` varchar(50) NOT NULL DEFAULT '',
  `file` varchar(150) NOT NULL DEFAULT '',
  `mode` varchar(10) NOT NULL DEFAULT '',
  `lokasi` varchar(5) NOT NULL DEFAULT '',
  `baru` int NOT NULL DEFAULT 0,
  `diubah` int NOT NULL DEFAULT 0,
  `harga` int NOT NULL DEFAULT 0,
  `dilewati` int NOT NULL DEFAULT 0,
  `error` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 18. Dashboard per level / departemen / user
CREATE TABLE IF NOT EXISTS `tbl_dashboard_layout` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis` varchar(12) NOT NULL COMMENT 'LEVEL, DEPT, USER, PENGUMUMAN',
  `target` varchar(30) NOT NULL COMMENT 'id_level / id_departement / id_user',
  `data` text,
  `boleh_ubah` char(1) NOT NULL DEFAULT 'N' COMMENT 'Y = user pada level/departemen ini boleh mengatur dashboard pribadinya',
  `updated_by` varchar(50) NOT NULL DEFAULT '',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_dash_target` (`jenis`, `target`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_user_departement` (
  `id_user` int NOT NULL,
  `id_departement` int NOT NULL DEFAULT 0,
  `nip` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 19. Menu baru v5 (link/alur lama tidak berubah; hak akses disalin dari menu sejenis)
INSERT INTO `tbl_submenu` (`nama_submenu`, `link`, `icon`, `id_menu`, `is_active`, `urutan`)
SELECT * FROM (
    SELECT 'Stock Opname (Baru)' AS n, 'OpnameStok' AS l, 'fas fa-clipboard-check' AS i, 57 AS m, 'Y' AS a, 9 AS u UNION ALL
    SELECT 'Impor Barang (Excel)', 'ImportBarang', 'fas fa-file-import', 57, 'Y', 2 UNION ALL
    SELECT 'Cetak Label QR', 'LabelBarang', 'fas fa-qrcode', 57, 'Y', 2 UNION ALL
    SELECT 'Scan Barang', 'LabelBarang/scan', 'fas fa-barcode', 57, 'Y', 2 UNION ALL
    SELECT 'Harga Rata-rata vs Net', 'LaporanGudang/harga_rata', 'fas fa-balance-scale', 61, 'Y', 7 UNION ALL
    SELECT 'Kenaikan Harga Beli', 'LaporanGudang/kenaikan_harga', 'fas fa-chart-line', 61, 'Y', 8 UNION ALL
    SELECT 'Dashboard & Laporan HRD', 'LaporanHR', 'fas fa-chart-pie', 68, 'Y', 0 UNION ALL
    SELECT 'Cuti Pegawai', 'Cuti', 'fas fa-plane-departure', 68, 'Y', 3 UNION ALL
    SELECT 'Mesin Absen', 'Mesin_absen', 'fas fa-fingerprint', 68, 'Y', 4 UNION ALL
    SELECT 'Pengaturan Dashboard', 'PengaturanDashboard', 'fas fa-th-large', 2, 'Y', 6 UNION ALL
    SELECT 'Panduan Sistem', 'PanduanSistem', 'fas fa-project-diagram', 2, 'Y', 7
) x
WHERE NOT EXISTS (SELECT 1 FROM `tbl_submenu` s WHERE s.`link` = x.l);

-- salin seluruh hak (lihat/tambah/ubah/hapus/cetak/unggah) dari menu referensi
INSERT INTO `tbl_akses_submenu` (`id_level`, `id_submenu`, `view_level`, `add_level`, `edit_level`, `delete_level`, `print_level`, `upload_level`)
SELECT r.`id_level`, s.`id_submenu`, r.`view_level`, r.`add_level`, r.`edit_level`, r.`delete_level`, r.`print_level`, r.`upload_level`
FROM `tbl_akses_submenu` r
JOIN `tbl_submenu` ref ON ref.`id_submenu` = r.`id_submenu`
JOIN `tbl_submenu` s ON (ref.`link` = 'StokOpname' AND s.`link` = 'OpnameStok')
                     OR (ref.`link` = 'Sparepart' AND s.`link` IN ('ImportBarang', 'LabelBarang', 'LabelBarang/scan'))
                     OR (ref.`link` = 'Pegawai' AND s.`link` IN ('Cuti', 'Mesin_absen'))
                     OR (ref.`link` = 'user' AND s.`link` IN ('PengaturanDashboard', 'PanduanSistem'))
WHERE NOT EXISTS (SELECT 1 FROM (SELECT `id_level`, `id_submenu` FROM `tbl_akses_submenu`) a WHERE a.`id_level` = r.`id_level` AND a.`id_submenu` = s.`id_submenu`);

-- laporan (lihat + cetak) dari menu laporan sejenis
INSERT INTO `tbl_akses_submenu` (`id_level`, `id_submenu`, `view_level`, `add_level`, `edit_level`, `delete_level`, `print_level`, `upload_level`)
SELECT r.`id_level`, s.`id_submenu`, r.`view_level`, 'N', 'N', 'N', IF(r.`print_level` = 'Y' OR r.`view_level` = 'Y', 'Y', 'N'), 'N'
FROM `tbl_akses_submenu` r
JOIN `tbl_submenu` ref ON ref.`id_submenu` = r.`id_submenu`
JOIN `tbl_submenu` s ON (ref.`link` = 'ReportWhKeluar' AND s.`link` IN ('LaporanGudang/harga_rata', 'LaporanGudang/kenaikan_harga'))
                     OR (ref.`link` = 'Pegawai' AND s.`link` = 'LaporanHR')
WHERE NOT EXISTS (SELECT 1 FROM (SELECT `id_level`, `id_submenu` FROM `tbl_akses_submenu`) a WHERE a.`id_level` = r.`id_level` AND a.`id_submenu` = s.`id_submenu`);

-- Hitung ulang harga rata-rata & atur metode penilaian dijalankan dari menu Harga Rata-rata (perlu hak ubah)

SET SQL_MODE = @OLD_SQL_MODE5;

-- 20. Cek hasil v5
SELECT s.`id_submenu`, s.`id_menu`, s.`nama_submenu`, s.`link` FROM `tbl_submenu` s
WHERE s.`link` IN ('OpnameStok', 'ImportBarang', 'LabelBarang', 'LabelBarang/scan', 'LaporanGudang/harga_rata', 'LaporanGudang/kenaikan_harga', 'LaporanHR', 'Cuti', 'Mesin_absen', 'PengaturanDashboard', 'PanduanSistem');
-- selesai v5
