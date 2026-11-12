<?php
/**
 * TOP ERP - Pemeriksa server (login / sesi / database).
 * Taruh di folder aplikasi (sejajar index.php), buka http://IP-SERVER/top/cek_server.php
 * HAPUS file ini setelah selesai dipakai.
 * Tidak menampilkan password apa pun.
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 1);
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . '/');
define('SELF', 'index.php');
define('VIEWPATH', APPPATH . 'views/');

$hasil = array();
function cek($judul, $ok, $info = '', $saran = '') {
    global $hasil; $hasil[] = array($judul, $ok, $info, $saran);
}
function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }

// 1. PHP
cek('Versi PHP', version_compare(PHP_VERSION, '7.4', '>='), PHP_VERSION);
cek('Ekstensi mysqli', extension_loaded('mysqli'), extension_loaded('mysqli') ? 'ada' : 'tidak ada', 'sudo apt install php-mysql && sudo systemctl restart apache2');
$webUser = function_exists('posix_geteuid') ? (posix_getpwuid(posix_geteuid())['name'] ?? posix_geteuid()) : get_current_user();
cek('PHP berjalan sebagai user', true, $webUser);

// 2. config.php
$config = array();
$cfgFile = APPPATH . 'config/config.php';
if (is_file($cfgFile)) { include $cfgFile; }
cek('File application/config/config.php', is_file($cfgFile), is_file($cfgFile) ? 'diubah ' . date('d-m-Y H:i', filemtime($cfgFile)) : 'tidak ditemukan');
$drv  = $config['sess_driver'] ?? '(kosong)';
$path = $config['sess_save_path'] ?? '(kosong)';
cek('Penyimpanan sesi (sess_driver)', $drv === 'database', $drv . ' / ' . (is_string($path) ? $path : '(NULL)'),
    "Ganti di config.php: \$config['sess_driver'] = 'database'; dan \$config['sess_save_path'] = 'ci_sessions';  (atau upload config.php versi baru)");
if ($drv === 'files') {
    $p = $path ?: session_save_path();
    $own = (is_dir($p) && function_exists('posix_getpwuid')) ? (posix_getpwuid(fileowner($p))['name'] ?? fileowner($p)) : '-';
    cek('Folder sesi bisa ditulis web server', is_dir($p) && is_writable($p), $p . ' (pemilik: ' . $own . ')',
        'sudo chown -R www-data:www-data ' . rtrim($p, '/'));
}
cek('cookie_secure = FALSE (server memakai http)', empty($config['cookie_secure']) || ! empty($_SERVER['HTTPS']), ! empty($config['cookie_secure']) ? 'TRUE' : 'FALSE',
    "Di config.php set \$config['cookie_secure'] = FALSE; karena server diakses lewat http://");
cek('cookie_domain kosong', empty($config['cookie_domain']), $config['cookie_domain'] ?: '(kosong)', "Di config.php set \$config['cookie_domain'] = '';");

// 3. File perbaikan lain
$myc = @file_get_contents(APPPATH . 'core/MY_Controller.php');
cek('MY_Controller.php versi baru (mode MySQL 8)', $myc && strpos($myc, 'NO_ENGINE_SUBSTITUTION') !== false, $myc ? 'diubah ' . date('d-m-Y H:i', filemtime(APPPATH . 'core/MY_Controller.php')) : 'tidak ditemukan', 'Upload application/core/MY_Controller.php versi baru');
$dw = @file_get_contents(APPPATH . 'libraries/Dashwidget.php');
cek('Dashwidget.php versi baru', $dw && strpos($dw, "date_close_wo = '' OR") === false, $dw ? 'ok' : 'tidak ditemukan', 'Upload application/libraries/Dashwidget.php versi baru');

// 4. Database
$db = array(); $active_group = 'default'; $query_builder = TRUE;
$dbFile = APPPATH . 'config/database.php';
if (is_file($dbFile)) { include $dbFile; }
$d = $db[$active_group] ?? array();
$m = null;
if ($d && extension_loaded('mysqli')) {
    mysqli_report(MYSQLI_REPORT_OFF);
    $m = @new mysqli($d['hostname'], $d['username'], $d['password'], $d['database']);
    $okc = $m && ! $m->connect_errno;
    cek('Koneksi database (' . e($d['username']) . '@' . e($d['hostname']) . ' / ' . e($d['database']) . ')', $okc, $okc ? 'berhasil, MySQL ' . $m->server_info : ($m ? $m->connect_error : 'gagal'),
        'Periksa username/password di application/config/database.php');
    if ($okc) {
        $t = $m->query("SHOW TABLES LIKE 'ci_sessions'");
        $ada = $t && $t->num_rows;
        cek('Tabel ci_sessions ada', (bool) $ada, $ada ? 'ada' : 'tidak ada', 'Import ulang db_transforma_oto_prima_LENGKAP.zip');
        if ($ada) {
            $pk = $m->query("SHOW INDEX FROM ci_sessions WHERE Key_name='PRIMARY'");
            cek('ci_sessions punya PRIMARY KEY', $pk && $pk->num_rows, $pk && $pk->num_rows ? 'ada' : 'belum', 'Jalankan di phpMyAdmin: TRUNCATE TABLE ci_sessions; ALTER TABLE ci_sessions ADD PRIMARY KEY (id);');
            $r = $m->query("SELECT COUNT(*) n, MAX(`timestamp`) t FROM ci_sessions")->fetch_assoc();
            cek('Isi ci_sessions', true, $r['n'] . ' sesi' . ($r['t'] ? ', terakhir ' . date('d-m-Y H:i:s', $r['t']) : ''));
            $w = $m->query("INSERT INTO ci_sessions (id, ip_address, `timestamp`, data) VALUES ('cek_server_tes', '127.0.0.1', UNIX_TIMESTAMP(), '')");
            cek('User database boleh menulis ke ci_sessions', (bool) $w, $w ? 'boleh' : $m->error, 'GRANT ALL PRIVILEGES ON ' . e($d['database']) . '.* TO user aplikasi');
            $m->query("DELETE FROM ci_sessions WHERE id='cek_server_tes'");
        }
        $n = $m->query("SELECT COUNT(*) n FROM information_schema.tables WHERE table_schema = DATABASE()")->fetch_assoc()['n'];
        cek('Jumlah tabel (harus 97)', (int) $n >= 97, $n, 'Import db_transforma_oto_prima_LENGKAP.zip');
        $u = $m->query("SELECT COUNT(*) n FROM tbl_user WHERE is_active='Y'");
        if ($u) cek('User aktif di tbl_user', true, $u->fetch_assoc()['n']);
    }
}

// 5. Opcache (file lama masih dipakai?)
$oc = function_exists('opcache_get_status') ? @opcache_get_status(false) : false;
cek('OPcache', true, $oc && ! empty($oc['opcache_enabled']) ? 'aktif (validate_timestamps=' . ini_get('opcache.validate_timestamps') . ')' : 'tidak aktif',
    'Bila file sudah diganti tapi hasil sama: sudo systemctl restart apache2');

// 6. Tes cookie browser
setcookie('cek_top', '1', 0, '/');
$cookieOk = isset($_COOKIE['cek_top']);
cek('Browser mengirim cookie', $cookieOk, $cookieOk ? 'ya' : 'belum (muat ulang halaman ini sekali)', 'Bila tetap "belum": izinkan cookie untuk alamat ini di browser');
?><!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cek Server TOP</title>
<style>
body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#f3f5f9;color:#1d2939;margin:0;padding:24px}
.box{max-width:900px;margin:auto;background:#fff;border:1px solid #e3e8ef;border-radius:10px;padding:20px}
h1{font-size:20px;margin:0 0 4px}p{color:#667085;margin:0 0 16px}
table{width:100%;border-collapse:collapse;font-size:14px}td{padding:8px;border-top:1px solid #eef1f5;vertical-align:top}
.ok{color:#0a7d3e;font-weight:700}.no{color:#c0392b;font-weight:700}.s{color:#c0392b;font-size:13px;margin-top:4px;font-family:Consolas,monospace}
</style></head><body><div class="box">
<h1>Pemeriksaan Server TOP ERP</h1>
<p><?php echo e(date('d-m-Y H:i:s')); ?> &middot; Hapus file <b>cek_server.php</b> setelah selesai.</p>
<table>
<?php foreach ($hasil as $h): ?>
<tr><td style="width:34px" class="<?php echo $h[1] ? 'ok' : 'no'; ?>"><?php echo $h[1] ? '&#10004;' : '&#10008;'; ?></td>
<td><b><?php echo e($h[0]); ?></b><br><?php echo e($h[2]); ?>
<?php if ( ! $h[1] && $h[3]): ?><div class="s"><?php echo e($h[3]); ?></div><?php endif; ?></td></tr>
<?php endforeach; ?>
</table></div></body></html>
