@echo off
REM ==========================================================================
REM  TOP ERP v2 - Merapikan file lama / tidak terpakai / berisiko keamanan
REM  File TIDAK dihapus, hanya DIPINDAHKAN ke folder _to_delete agar bisa
REM  dikembalikan bila perlu. Setelah aplikasi dicek normal, folder
REM  _to_delete boleh dihapus manual.
REM  Cara pakai: klik ganda file ini (dari folder top\tools).
REM ==========================================================================
setlocal
cd /d "%~dp0.."
set DST=_to_delete
if not exist "%DST%" mkdir "%DST%"

call :pindah "cek_koneksi.php"
call :pindah "testlain.php"
call :pindah "database.php"
call :pindah ".htaccess~"
call :pindah ".htaccess1"
call :pindah "db_transforma_oto_prima(18).sql"
call :pindah "db_transforma_oto_prima(19).sql"
call :pindah "db_transforma_oto_prima(20).sql"
call :pindah "tbl_wh_detail_part_keluar_po.sql"
call :pindah "tbl_wh_part_keluar_po.sql"
call :pindah "system1"

call :pindah "application\language\database.php"
call :pindah "application\controllers\ServiceAppointment copy.php"
call :pindah "application\controllers\ReportAfWo.php"
call :pindah "application\controllers\ManagementStok.php"
call :pindah "application\controllers\AksesLevel.php"
call :pindah "application\models\Mod_laporan.php"
call :pindah "application\models\Mod_purchaseorder2.php"
call :pindah "application\models\Mod_template.php"
call :pindah "application\models\Mod_menu_saji.php"
call :pindah "application\models\Mod_transaksi.php"
call :pindah "application\models\Mod_pelanggan.php"
call :pindah "application\models\Mod_barang.php"

call :pindah "application\views\layoutbackend - Copy.php"
call :pindah "application\views\template.php"
call :pindah "application\views\barang.php"
call :pindah "application\views\dataexcel.php"
call :pindah "application\views\form_transaksi.php"
call :pindah "application\views\templates\header1.php"
call :pindah "application\views\templates\menu1.php"
call :pindah "application\views\templates\footer1.php"
call :pindah "application\views\templates\footer.php"
call :pindah "application\views\display\indexlama.php"
call :pindah "application\views\body_repair\bus_masuk_before.php"
call :pindah "application\views\body_repair\detail_estimasilama.php"
call :pindah "application\views\body_repair\modals\modal_masuk_baynew.php"
call :pindah "application\views\warehouse\part_pk_before.php"
call :pindah "application\views\warehouse\detail_part_masuknpo_sebelumnya.php"
call :pindah "application\views\warehouse\detail_wo_part_lama.php"
call :pindah "application\views\warehouse\sparepart_data copy 2.php"
call :pindah "application\views\warehouse\modals\modal_cetak_data_part_keluar_divisi copy.php"
call :pindah "application\views\service\service_appointment(sebelumnya).php"
call :pindah "application\views\service\report_service\wo_service copy.php"
call :pindah "application\views\service\modals\modal_tambah_work_order copy.php"
REM Buku Besar lama (diganti Laporan Keuangan > Buku Besar)
call :pindah "application\views\accounting\report\data_buku_besar_old.php"
call :pindah "application\views\accounting\report\buku_besar.php"
call :pindah "application\views\accounting\report\data_buku_besar.php"
call :pindah "application\views\accounting\report\data_buku_besar_tanggal.php"
call :pindah "application\models\accounting\Mod_bukubesar.php"

REM file dokumentasi/dev bawaan AdminLTE yang tidak dipakai aplikasi
call :pindah "assets\docs"
call :pindah "assets\build"
call :pindah "assets\pages"
call :pindah "assets\starter.html"
call :pindah "assets\.github"

echo.
echo Selesai. File lama dipindahkan ke folder %DST%
pause
exit /b

:pindah
if exist "%~1" (
    for %%I in ("%DST%\%~1") do if not exist "%%~dpI" mkdir "%%~dpI"
    move "%~1" "%DST%\%~1" >nul
    echo  dipindah: %~1
)
exit /b
