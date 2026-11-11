<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$apl = $this->db->get('aplikasi', 1)->row();
$this->load->view('templates/header');
$this->load->view('templates/menu', array('apl' => $apl));
?>
    <!-- Content Header -->
    <section class="content-header top-content-header">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h1 class="top-h1"><?php echo isset($page) ? $page : ''; ?></h1>
                </div>
                <ol class="breadcrumb top-breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>"><i class="fas fa-home"></i></a></li>
                    <?php if ($this->uri->segment(1) && strtolower($this->uri->segment(1)) !== 'dashboard') { ?>
                    <li class="breadcrumb-item"><a href="<?php echo site_url($this->uri->segment(1)); ?>"><?php echo html_escape(trim(preg_replace('/(?<!^)([A-Z])/', ' $1', str_replace('_', ' ', ucfirst($this->uri->segment(1)))))); ?></a></li>
                    <?php } ?>
                    <li class="breadcrumb-item active"><?php echo isset($judul) ? $judul : ''; ?></li>
                </ol>
            </div>
        </div>
    </section>

    <?php echo isset($pre_content) ? $pre_content : ''; ?>

    <!-- Main content -->
    <div class="container-fluid top-content">
        <?php $this->load->view('templates/panduan_box'); ?>
        <?php echo $contents; ?>
    </div>
</div>
<!-- /.content-wrapper -->

<footer class="main-footer top-footer">
    <div class="d-flex flex-wrap justify-content-between">
        <span>&copy; <?php echo $apl ? html_escape($apl->tahun) : date('Y'); ?> <strong><?php echo $apl ? html_escape($apl->nama_owner) : ''; ?></strong>. All rights reserved.</span>
        <span class="text-muted d-none d-sm-inline">Versi <?php echo $apl ? html_escape($apl->versi) : ''; ?> &middot; PHP <?php echo PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION; ?></span>
    </div>
</footer>
</div>
<!-- /.wrapper -->
</body>

</html>
