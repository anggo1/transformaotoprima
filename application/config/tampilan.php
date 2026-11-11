<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Kelompok menu di sidebar (judul kecil di atas sekelompok menu).
| Kunci = id_menu, nilai = salah satu: sec_utama, sec_operasional, sec_gudang, sec_keuangan, sec_sistem.
| Menu yang tidak tercantum ditebak dari namanya.
*/
$config['kelompok_menu'] = array(
    1 => 'sec_utama',
    68 => 'sec_operasional', 0 => 'sec_operasional', 66 => 'sec_operasional', 67 => 'sec_operasional',
    63 => 'sec_gudang', 57 => 'sec_gudang', 61 => 'sec_gudang',
    60 => 'sec_keuangan', 65 => 'sec_keuangan',
    2 => 'sec_sistem',
);

/* level user yang boleh menyimpan tampilan sebagai default untuk semua pengguna */
$config['level_admin_tampilan'] = array(1, 12);
