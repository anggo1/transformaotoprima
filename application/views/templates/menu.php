<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Navbar + Sidebar.
 * Menu dibangun dengan 2 query saja (sebelumnya 2 query per menu) dan di-cache
 * di sesi selama 10 menit per level user.
 */
$sess    = $this->session->userdata();
$idlevel = isset($sess['id_level']) ? (int) $sess['id_level'] : 0;
$asset   = base_url('assets/');

$apl = isset($apl) && is_object($apl) ? $apl : $this->db->get('aplikasi', 1)->row();

$menuCache = $this->session->userdata('menu_cache');
if ( ! is_array($menuCache) || (int) $this->session->userdata('menu_cache_level') !== $idlevel
    || (time() - (int) $this->session->userdata('menu_cache_time')) > 600) {

    $mains = $this->db->select('b.id_menu, b.nama_menu, b.icon, b.link')
        ->from('tbl_akses_menu a')
        ->join('tbl_menu b', 'a.id_menu = b.id_menu')
        ->where('a.id_level', $idlevel)
        ->where('a.view_level', 'Y')
        ->group_start()->where('b.is_active', 'Y')->or_where('b.is_active IS NULL', null, false)->group_end()
        ->order_by('b.urutan', 'ASC')
        ->get()->result_array();

    $subs = $this->db->select('b.id_submenu, b.id_menu, b.nama_submenu, b.icon, b.link')
        ->from('tbl_akses_submenu a')
        ->join('tbl_submenu b', 'a.id_submenu = b.id_submenu')
        ->where('a.id_level', $idlevel)
        ->where('a.view_level', 'Y')
        ->group_start()->where('b.is_active', 'Y')->or_where('b.is_active IS NULL', null, false)->group_end()
        ->order_by('b.urutan', 'ASC')
        ->get()->result_array();

    $byMenu = array();
    foreach ($subs as $s) {
        $byMenu[$s['id_menu']][] = $s;
    }
    $menuCache = array();
    foreach ($mains as $m) {
        $m['subs']   = isset($byMenu[$m['id_menu']]) ? $byMenu[$m['id_menu']] : array();
        $menuCache[] = $m;
    }
    $this->session->set_userdata(array('menu_cache' => $menuCache, 'menu_cache_level' => $idlevel, 'menu_cache_time' => time()));
}

$uriNow = strtolower(trim($this->uri->uri_string(), '/'));
$seg1   = strtolower((string) $this->uri->segment(1));
// cari satu link yang paling cocok (paling spesifik) dengan URL saat ini
$aktifLink = null; $aktifLen = -1;
$cekLink = function ($link) use ($uriNow, $seg1, &$aktifLink, &$aktifLen) {
    $l = strtolower(trim((string) $link, '/'));
    if ($l === '' || $l === '#') return;
    $cocok = ($l === $uriNow) || (strpos($l, '/') === false ? $l === $seg1 : strpos($uriNow . '/', $l . '/') === 0);
    if ($cocok && strlen($l) > $aktifLen) { $aktifLink = $l; $aktifLen = strlen($l); }
};
foreach ($menuCache as $m) {
    $cekLink($m['link']);
    foreach ($m['subs'] as $s) $cekLink($s['link']);
}
$isActive = function ($link) use (&$aktifLink) {
    return $aktifLink !== null && strtolower(trim((string) $link, '/')) === $aktifLink;
};
$menuUrl = function ($link) {
    $link = trim((string) $link);
    if ($link === '' || $link === '#') return '#';
    if (preg_match('#^https?://#i', $link)) return $link;
    return site_url($link);
};
$foto = ! empty($sess['image']) ? base_url('assets/foto/user/' . $sess['image']) : base_url('assets/dist/img/avatar.png');
$nama = isset($sess['full_name']) ? $sess['full_name'] : '';
$lokasi = isset($sess['lokasi']) ? $sess['lokasi'] : '';
$logo = ($apl && ! empty($apl->logo)) ? base_url('assets/foto/logo/' . $apl->logo) : base_url('assets/dist/img/Logo.png');

/* ---- preferensi, kelompok menu & favorit ---- */
$this->load->helper('bahasa');
$pref = $this->session->userdata('pref');
if ( ! is_array($pref)) { $this->load->library('preferensi'); $pref = Preferensi::$DEFAULT; }
$this->config->load('tampilan', true, true);
$kelMenu = (array) $this->config->item('kelompok_menu', 'tampilan');
$tebakKel = function ($m) use ($kelMenu) {
    if (isset($kelMenu[(int) $m['id_menu']])) return $kelMenu[(int) $m['id_menu']];
    $n = strtolower($m['nama_menu']);
    if (strpos($n, 'dashboard') !== false) return 'sec_utama';
    if (preg_match('/warehouse|gudang|stok|stock/', $n)) return 'sec_gudang';
    if (preg_match('/account|akuntansi|keuangan|finance|pajak/', $n)) return 'sec_keuangan';
    if (preg_match('/user|management|setting|sistem|aplikasi/', $n)) return 'sec_sistem';
    return 'sec_operasional';
};
$semuaSub = array();
foreach ($menuCache as $m) foreach ($m['subs'] as $s) $semuaSub[strtolower(trim($s['link'], '/'))] = $s;
$fav = array();
foreach ((array) $pref['favorit'] as $l) {
    $k = strtolower(trim($l, '/'));
    if (isset($semuaSub[$k])) $fav[] = array($semuaSub[$k]['link'], menu_t($semuaSub[$k]['nama_submenu']), trim((string) $semuaSub[$k]['icon']) !== '' ? $semuaSub[$k]['icon'] : 'far fa-circle', $isActive($semuaSub[$k]['link']));
}
$favSet = array(); foreach ($fav as $f) $favSet[strtolower(trim($f[0], '/'))] = true;
$namaLevel = '';
if ($idlevel) { $lv = $this->db->select('nama_level')->get_where('tbl_userlevel', array('id_level' => $idlevel), 1)->row(); $namaLevel = $lv ? $lv->nama_level : ''; }
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light top-navbar">
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link top-burger" data-widget="pushmenu" href="#" role="button" aria-label="Menu"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-md-block">
            <div class="top-page-title">
                <span class="top-page-title__app"><?php echo html_escape($apl ? $apl->nama_aplikasi : ''); ?></span>
                <span class="top-page-title__sep">/</span>
                <span class="top-page-title__page"><?php echo html_escape(isset($page) ? $page : ''); ?></span>
            </div>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
        <?php if ($lokasi !== '') { ?>
        <li class="nav-item d-none d-sm-block">
            <span class="top-chip"><i class="fas fa-map-marker-alt"></i> <?php echo html_escape($lokasi); ?></span>
        </li>
        <?php } ?>
        <li class="nav-item d-none d-lg-block">
            <span class="top-clock" id="top-clock"><?php echo date('d M Y'); ?></span>
        </li>
        <li class="nav-item dropdown d-none d-sm-block">
            <a class="nav-link top-lang" data-toggle="dropdown" href="#" title="<?php echo html_escape(t('ganti_bahasa')); ?>"><i class="fas fa-globe-asia"></i><span class="top-lang__code"><?php echo strtoupper($pref['bahasa']); ?></span></a>
            <div class="dropdown-menu dropdown-menu-right top-user-menu" style="min-width:180px">
                <a class="dropdown-item<?php echo $pref['bahasa'] === 'id' ? ' active' : ''; ?>" href="<?php echo site_url('Tampilan/bahasa/id'); ?>"><span class="top-flag">ID</span> Bahasa Indonesia</a>
                <a class="dropdown-item<?php echo $pref['bahasa'] === 'en' ? ' active' : ''; ?>" href="<?php echo site_url('Tampilan/bahasa/en'); ?>"><span class="top-flag">EN</span> English</a>
            </div>
        </li>
        <li class="nav-item d-none d-sm-block">
            <a class="nav-link" href="#" id="top-tema-toggle" title="<?php echo html_escape(t('mode_gelap')); ?>"><i class="fas <?php echo $pref['tema'] === 'gelap' ? 'fa-sun' : 'fa-moon'; ?>"></i></a>
        </li>
        <li class="nav-item d-none d-sm-block">
            <a class="nav-link" href="#" id="top-fullscreen" title="<?php echo html_escape(t('layar_penuh')); ?>"><i class="fas fa-expand"></i></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link top-user" data-toggle="dropdown" href="#" aria-expanded="false">
                <img src="<?php echo $foto; ?>" alt="" class="top-user__avatar" onerror="this.src='<?php echo base_url('assets/dist/img/avatar.png'); ?>'">
                <span class="top-user__name d-none d-md-inline"><?php echo html_escape($nama); ?></span>
                <i class="fas fa-chevron-down top-user__caret d-none d-md-inline"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right top-user-menu">
                <div class="top-user-menu__head">
                    <img src="<?php echo $foto; ?>" alt="" onerror="this.src='<?php echo base_url('assets/dist/img/avatar.png'); ?>'">
                    <div>
                        <div class="top-user-menu__name"><?php echo html_escape($nama); ?></div>
                        <div class="top-user-menu__meta"><?php echo html_escape(isset($sess['username']) ? $sess['username'] : ''); ?><?php echo $lokasi !== '' ? ' &middot; ' . html_escape($lokasi) : ''; ?></div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="<?php echo site_url('Profile'); ?>" class="dropdown-item"><i class="far fa-user-circle mr-2"></i> <?php echo html_escape(t('profil_saya')); ?></a>
                <a href="<?php echo site_url('Tampilan'); ?>" class="dropdown-item"><i class="fas fa-palette mr-2"></i> <?php echo html_escape(t('tampilan_bahasa')); ?></a>
                <div class="dropdown-divider"></div>
                <a href="<?php echo site_url('login/logout'); ?>" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt mr-2"></i> <?php echo html_escape(t('keluar')); ?></a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Sidebar -->
<aside class="main-sidebar <?php echo $pref['sidebar'] === 'terang' ? 'sidebar-light-primary' : 'sidebar-dark-primary'; ?> top-sidebar">
    <a href="<?php echo site_url('dashboard'); ?>" class="brand-link top-brand">
        <img src="<?php echo $logo; ?>" alt="<?php echo html_escape($apl ? $apl->title : ''); ?>" class="brand-image" onerror="this.src='<?php echo base_url('assets/dist/img/Logo.png'); ?>'">
        <span class="brand-text">
            <strong><?php echo html_escape($apl ? $apl->title : 'TOP'); ?></strong>
            <small><?php echo html_escape($apl ? $apl->lokasi : ''); ?></small>
        </span>
    </a>

    <div class="sidebar">
        <div class="top-menu-search">
            <i class="fas fa-search"></i>
            <input type="search" id="top-menu-search" placeholder="<?php echo html_escape(t('cari_menu')); ?>" autocomplete="off" aria-label="<?php echo html_escape(t('cari_menu')); ?>">
            <kbd>/</kbd>
        </div>

        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <?php $this->load->view('templates/_favorit', array('fav' => $fav)); ?>
                <?php $secNow = null;
                foreach ($menuCache as $main) {
                    $hasSub = ! empty($main['subs']);
                    if ($pref['kelompok_menu'] === 'Y') {
                        $sec = $tebakKel($main);
                        if ($sec !== $secNow) { $secNow = $sec; echo '<li class="nav-header top-sb-section">' . html_escape(t($sec)) . '</li>'; }
                    }
                    $open = false;
                    if ($hasSub) {
                        foreach ($main['subs'] as $s) { if ($isActive($s['link'])) { $open = true; break; } }
                    }
                    $icon = trim((string) $main['icon']) !== '' ? $main['icon'] : 'fas fa-circle';
                ?>
                <?php if ($hasSub) { ?>
                <li class="nav-item has-treeview<?php echo $open ? ' menu-open' : ''; ?>">
                    <a href="#" class="nav-link<?php echo $open ? ' active' : ''; ?>">
                        <i class="nav-icon <?php echo html_escape($icon); ?>"></i>
                        <p><?php echo html_escape(menu_t($main['nama_menu'])); ?><span class="top-sb-count"><?php echo count($main['subs']); ?></span><i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php foreach ($main['subs'] as $sub) { $act = $isActive($sub['link']); $lk = strtolower(trim($sub['link'], '/')); $fv = isset($favSet[$lk]); ?>
                        <li class="nav-item">
                            <a href="<?php echo $menuUrl($sub['link']); ?>" class="nav-link<?php echo $act ? ' active' : ''; ?>">
                                <i class="nav-icon <?php echo html_escape(trim((string) $sub['icon']) !== '' ? $sub['icon'] : 'far fa-circle'); ?>"></i>
                                <p><?php echo html_escape(menu_t($sub['nama_submenu'])); ?></p>
                            </a>
                            <?php if ($lk !== '' && $lk !== '#') { ?><button type="button" class="top-fav-btn<?php echo $fv ? ' is-on' : ''; ?>" data-link="<?php echo html_escape($sub['link']); ?>" title="<?php echo html_escape($fv ? t('lepas') : t('sematkan')); ?>"><i class="<?php echo $fv ? 'fas' : 'far'; ?> fa-star"></i></button><?php } ?>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } else { $act = $isActive($main['link']); ?>
                <li class="nav-item">
                    <a href="<?php echo $menuUrl($main['link']); ?>" class="nav-link<?php echo $act ? ' active' : ''; ?>">
                        <i class="nav-icon <?php echo html_escape($icon); ?>"></i>
                        <p><?php echo html_escape(menu_t($main['nama_menu'])); ?></p>
                    </a>
                </li>
                <?php } ?>
                <?php } ?>
            </ul>
        </nav>
        <div class="top-sb-user">
            <img src="<?php echo $foto; ?>" alt="" onerror="this.src='<?php echo base_url('assets/dist/img/avatar.png'); ?>'">
            <div class="top-sb-user__info">
                <strong><?php echo html_escape($nama); ?></strong>
                <span><?php echo html_escape($namaLevel); ?><?php echo $lokasi !== '' ? ' · ' . html_escape($lokasi) : ''; ?></span>
            </div>
            <a href="<?php echo site_url('Tampilan'); ?>" title="<?php echo html_escape(t('tampilan_bahasa')); ?>"><i class="fas fa-sliders-h"></i></a>
            <a href="<?php echo site_url('login/logout'); ?>" title="<?php echo html_escape(t('keluar')); ?>"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</aside>

<!-- Script inti (dimuat sebelum konten karena skrip di tiap halaman memakai jQuery) -->
<script src="<?php echo $asset; ?>plugins/jquery/jquery.min.js"></script>
<script src="<?php echo $asset; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $asset; ?>plugins/moment/moment.min.js"></script>
<script src="<?php echo $asset; ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo $asset; ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datepicker-bs4/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datepicker-bs4/js/bootstrap-datepicker.id.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-rowgroup/js/dataTables.rowGroup.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-rowgroup/js/rowGroup.bootstrap4.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-select/js/dataTables.select.min.js"></script>
<script src="<?php echo $asset; ?>plugins/datatables-select/js/select.bootstrap4.min.js"></script>
<script src="<?php echo $asset; ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="<?php echo $asset; ?>plugins/toastr/toastr.min.js"></script>
<script src="<?php echo $asset; ?>plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo $asset; ?>dist/js/adminlte.min.js"></script>
<script src="<?php echo $asset; ?>plugins/print/print.min.js"></script>
<script src="<?php echo $asset; ?>plugins/jszip/jszip.min.js"></script>
<!-- pdfmake (+-2 MB) tidak dimuat di awal; dimuat otomatis saat tombol PDF diklik (lihat top-app.js) -->
<?php if ($seg1 === '' || $seg1 === 'dashboard') { ?>
<script src="<?php echo $asset; ?>plugins/chart.js/Chart.min.js"></script>
<?php } ?>
<script>window.TOP = { baseUrl: '<?php echo base_url(); ?>', siteUrl: '<?php echo rtrim(site_url(), '/'); ?>/', lang: '<?php echo $pref['bahasa']; ?>', tema: '<?php echo $pref['tema']; ?>', txt: { sematkan: <?php echo json_encode(t('sematkan')); ?>, lepas: <?php echo json_encode(t('lepas')); ?> } };</script>
<script src="<?php echo $asset; ?>js/top-app.js?v=3.0.0"></script>
<script src="<?php echo $asset; ?>js/top-chart.js?v=3.0.0" defer></script>

<!-- Content Wrapper -->
<div class="content-wrapper">
<?php include FCPATH . 'assets/js/ajx_user.php'; ?>
