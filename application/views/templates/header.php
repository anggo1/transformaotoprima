<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$sess  = $this->session->userdata();
$title = isset($sess['title']) ? $sess['title'] : 'TOP';
$asset = base_url('assets/');
$ver   = '2.1.0'; // ubah untuk memaksa browser memuat ulang CSS/JS
$CI =& get_instance();
$CI->load->library('preferensi');
$pref = isset($sess['pref']) && is_array($sess['pref']) ? $sess['pref'] : Preferensi::$DEFAULT;
?>
<!DOCTYPE html>
<html lang="<?php echo $pref['bahasa'] === 'en' ? 'en' : 'id'; ?>"<?php echo Preferensi::atribut($pref); ?>>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#0f1b2d">
    <title><?php echo html_escape($title); ?> | <?php echo html_escape(isset($page) ? $page : (isset($sess['username']) ? $sess['username'] : '')); ?></title>
    <link rel="icon" href="<?php echo $asset; ?>dist/img/Logo.png" type="image/png">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php if ($pref['font'] === 'inter') { ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php } elseif ($pref['font'] === 'jakarta') { ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php } ?>

    <!-- Ikon -->
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/fontawesome-5.5.0/css/all.min.css">

    <!-- Plugin yang dipakai halaman -->
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/datepicker-bs4/css/bootstrap-datepicker3.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/datatables-rowgroup/css/rowGroup.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/datatables-select/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>plugins/print/print.min.css">

    <!-- Tema -->
    <link rel="stylesheet" href="<?php echo $asset; ?>dist/css/adminlte.css">
    <link rel="stylesheet" href="<?php echo $asset; ?>dist/css/top-theme.css?v=<?php echo $ver; ?>">
    <link rel="stylesheet" href="<?php echo $asset; ?>dist/css/top-custom.css?v=<?php echo $ver; ?>">
    <style><?php echo Preferensi::css_aksen($pref['aksen']); ?></style>
    <script>
        /* pulihkan status sidebar sebelum render agar tidak berkedip */
        (function () { var h = document.documentElement, t = h.getAttribute('data-tema');
            if (t === 'gelap' || (t === 'otomatis' && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) h.className += ' top-dark'; })();
        (function () { var sb = null; try { sb = localStorage.getItem('top.sidebar'); } catch (e) {}
            if (sb === null) sb = '<?php echo $pref['sidebar_mini'] === 'Y' ? 'collapsed' : 'open'; ?>';
            if (sb === 'collapsed' && window.innerWidth >= 992) { document.documentElement.className += ' js-sb-collapsed'; } })();
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed top-app">
    <script>if (document.documentElement.className.indexOf('js-sb-collapsed') > -1) { document.body.className += ' sidebar-collapse'; }</script>
    <div class="wrapper">
