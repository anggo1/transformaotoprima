<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* English — interface text and menu names. Add or adjust translations freely. */
$lang['cari_menu'] = 'Search menu...';
$lang['profil_saya'] = 'My Profile';
$lang['keluar'] = 'Sign out';
$lang['tampilan_bahasa'] = 'Appearance & Language';
$lang['akun'] = 'Account';
$lang['favorit'] = 'Favorites';
$lang['favorit_kosong'] = 'Click ☆ next to a menu to pin it here.';
$lang['sematkan'] = 'Pin to Favorites';
$lang['lepas'] = 'Unpin';
$lang['layar_penuh'] = 'Full screen';
$lang['ganti_bahasa'] = 'Language';
$lang['mode_gelap'] = 'Dark mode';
$lang['panduan_halaman'] = 'Guide for this page';
$lang['langkah_kerja'] = 'Steps';
$lang['catatan'] = 'Notes';
$lang['buka_panduan'] = 'Open the full guide';
$lang['tentang_laporan'] = 'About this report';
$lang['cara_membaca'] = 'How to read it';
$lang['sumber_data'] = 'Data source';
$lang['tips'] = 'Tips';
$lang['dasar'] = 'Basis / reference';
$lang['tampilkan'] = 'Show';
$lang['cetak'] = 'Print';
$lang['dari'] = 'From';
$lang['sampai'] = 'To';
$lang['cepat'] = 'Quick';
$lang['pilih'] = '— choose —';
$lang['bulan_ini'] = 'This month';
$lang['bulan_lalu'] = 'Last month';
$lang['kuartal_ini'] = 'This quarter';
$lang['tahun_ini'] = 'This year';
$lang['tahun_lalu'] = 'Last year';
$lang['semua_laporan'] = 'All reports';
$lang['per_tanggal'] = 'As of';
$lang['tahun'] = 'Year';
$lang['lokasi'] = 'Location';
$lang['semua'] = '— All —';
$lang['simpan'] = 'Save';
$lang['batal'] = 'Cancel';
$lang['tersimpan'] = 'Settings saved.';
$lang['sec_utama'] = 'Main';
$lang['sec_operasional'] = 'Operations';
$lang['sec_gudang'] = 'Warehouse';
$lang['sec_keuangan'] = 'Finance';
$lang['sec_sistem'] = 'System';
$lang['sec_lain'] = 'Others';

/* nama menu & submenu (kunci = nama di database, tidak peka huruf besar/kecil) */
$lang['_menu'] = array(
    // menu utama
    'Dashboard Utama' => 'Main Dashboard', 'Dashboard Warehouse' => 'Warehouse Dashboard', 'Dashboard Gudang' => 'Warehouse Dashboard',
    'HRMS' => 'HRMS', 'Marketing Sales' => 'Marketing & Sales', 'After Sales' => 'After Sales', 'After Sales Report' => 'After Sales Reports',
    'Warehouse' => 'Warehouse', 'Warehouse Report' => 'Warehouse Reports', 'Laporan Gudang' => 'Warehouse Reports',
    'Accounting' => 'Accounting', 'Report Accounting' => 'Accounting Reports', 'Management User' => 'User Management',
    // akuntansi
    'Panduan Akuntansi & Pajak' => 'Accounting & Tax Guide', 'Daftar Akun' => 'Chart of Accounts', 'Saldo Awal' => 'Opening Balances',
    'Jurnal Umum' => 'General Journal', 'Kas & Bank' => 'Cash & Bank', 'Piutang Usaha' => 'Accounts Receivable', 'Hutang Usaha' => 'Accounts Payable',
    'Aset Tetap' => 'Fixed Assets', 'Anggaran' => 'Budget', 'Perpajakan' => 'Taxation', 'Tutup Buku & Preferensi' => 'Period Closing & Preferences',
    'Kelompok Akun & Akun Pemasok' => 'Account Groups & Supplier Accounts', 'Panel Setting' => 'Settings Panel',
    'Pusat Laporan Keuangan' => 'Financial Report Center', 'Neraca Saldo' => 'Trial Balance', 'Neraca' => 'Balance Sheet', 'Laba Rugi' => 'Profit & Loss',
    'Arus Kas' => 'Cash Flow', 'Umur Piutang & Hutang' => 'AR & AP Aging', 'Laporan Pajak (PPN & PPh)' => 'Tax Reports (VAT & Income Tax)',
    'Rekonsiliasi Fiskal' => 'Fiscal Reconciliation', 'Buku Besar' => 'General Ledger', 'Analisis Rasio Keuangan' => 'Financial Ratio Analysis',
    'Laporan Keuangan Lengkap' => 'Full Financial Statements', 'Kartu Piutang & Hutang' => 'AR & AP Ledger Cards', 'Pemeriksaan Kesehatan Data' => 'Data Health Check',
    'Report Stok Opname' => 'Stock Count Report',
    // gudang
    'Stock Opname (Baru)' => 'Stock Count (New)', 'Impor Barang (Excel)' => 'Import Items (Excel)', 'Cetak Label QR' => 'Print QR Labels', 'Scan Barang' => 'Scan Item',
    'Harga Rata-rata vs Net' => 'Average vs Net Price', 'Kenaikan Harga Beli' => 'Purchase Price Increase', 'Dashboard & Laporan HRD' => 'HR Dashboard & Reports',
    'Cuti Pegawai' => 'Employee Leave', 'Mesin Absen' => 'Attendance Machine', 'Pengaturan Dashboard' => 'Dashboard Settings', 'Panduan Sistem' => 'System Guide',
    'Panduan Gudang' => 'Warehouse Guide', 'Material & Sparepart' => 'Materials & Spare Parts', 'Material&Sparepart' => 'Materials & Spare Parts',
    'Update Harga' => 'Price Update', 'Update Harga Part' => 'Part Price Update', 'Purchase Order' => 'Purchase Order', 'Part Order' => 'Part Order',
    'Estimasi Penawaran' => 'Quotation Estimate', 'Purchase Order Masuk' => 'Incoming Customer PO', 'PO Customer (Masuk)' => 'Incoming Customer PO',
    'Part Masuk PO' => 'Goods Receipt (PO)', 'Part Masuk Non PO' => 'Goods Receipt (Non-PO)', 'Part Masuk Non-PO' => 'Goods Receipt (Non-PO)',
    'Part Keluar' => 'Goods Issue', 'Part Keluar WO' => 'Goods Issue (Work Order)', 'Part Keluar PO' => 'Goods Issue (Customer PO)',
    'Surat Jalan' => 'Delivery Note', 'Stock Opname' => 'Stock Count', 'Panel Setting Gudang' => 'Warehouse Settings', 'Pengaturan Gudang' => 'Warehouse Settings',
    'Pusat Laporan Gudang' => 'Warehouse Report Center', 'Kartu Stok' => 'Stock Card', 'Mutasi Stok' => 'Stock Movement', 'Stok Minimum & Reorder' => 'Minimum Stock & Reorder',
    'Nilai Persediaan' => 'Inventory Valuation', 'Nilai Persediaan & Stok Minimum' => 'Inventory Value & Minimum Stock',
    'List P O' => 'Purchase Order List', 'List PO Masuk' => 'Customer PO List', 'List Part Order' => 'Part Order List', 'List Penawaran' => 'Quotation List',
    'Report Perbarang' => 'Report per Item', 'Report Per Divisi' => 'Report per Division', 'Penerimaan Barang' => 'Goods Received', 'Pengeluaran Barang' => 'Goods Issued',
    'Report Barang Return' => 'Returned Goods Report', 'Report Barang Masuk' => 'Goods Receipt Report', 'Report Part PK' => 'Part Usage per Work Order (PK)',
    // lain-lain
    'Employe' => 'Employees', 'Panel Setting HRD' => 'HR Settings', 'Panel Seting' => 'Settings Panel', 'Daftar Bus' => 'Bus List', 'Bus Masuk' => 'Bus Arrival',
    'Bus Keluar' => 'Bus Departure', 'Proses PK Aktif' => 'Active Work Orders (PK)', 'Edit Bay & SPK Global' => 'Bay & Global SPK', 'Display Bay' => 'Bay Display',
    'Report PK Selesai' => 'Completed PK Report', 'Report SPK Pertanggal' => 'SPK by Date Report', 'Stock Chasis' => 'Chassis Stock', 'Retail TOP' => 'TOP Retail',
    'List SPK' => 'SPK List', 'Penjualan' => 'Sales', 'Pembelian' => 'Purchasing', 'Barang' => 'Items', 'Kategori' => 'Categories', 'Satuan' => 'Units',
    'Service Appointment' => 'Service Appointment', 'Work Order' => 'Work Order', 'Part Request' => 'Part Request', 'Pre Order' => 'Pre Order',
    'Estimasi Penawaran Service' => 'Service Quotation', 'Services Report' => 'Service Report', 'Work Order Report' => 'Work Order Report',
    'Part Order Report' => 'Part Order Report', 'Xentry Operation Time' => 'Xentry Operation Time', 'List Part Estimasi' => 'Estimate Parts List',
    'Aplikasi' => 'Application', 'Menu' => 'Menus', 'SubMenu' => 'Submenus', 'User' => 'Users', 'User Level' => 'User Levels', 'Customer' => 'Customers',
    'Tampilan & Bahasa' => 'Appearance & Language',
    'Dashboard & Laporan Gudang' => 'Warehouse Dashboard & Reports', 'PO Outstanding' => 'Outstanding POs', 'Pemeriksaan Data Gudang' => 'Warehouse Data Check',
    'Stok Minimum & Saran Pesan' => 'Minimum Stock & Reorder Suggestions',
);
