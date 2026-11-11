<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* daftar menu favorit di sidebar; $fav = [ [link, nama, icon], ... ] */
?>
<li class="nav-header top-sb-section top-sb-fav-head"><i class="fas fa-star"></i> <?php echo html_escape(t('favorit')); ?></li>
<?php if ( ! $fav) { ?>
<li class="nav-item top-sb-fav-empty"><span class="top-sb-hint"><?php echo html_escape(t('favorit_kosong')); ?></span></li>
<?php } foreach ($fav as $f) { ?>
<li class="nav-item top-sb-fav" data-link="<?php echo html_escape($f[0]); ?>">
    <a href="<?php echo site_url($f[0]); ?>" class="nav-link<?php echo ! empty($f[3]) ? ' active' : ''; ?>">
        <i class="nav-icon <?php echo html_escape($f[2]); ?>"></i>
        <p><?php echo html_escape($f[1]); ?></p>
    </a>
    <button type="button" class="top-fav-btn is-on" data-link="<?php echo html_escape($f[0]); ?>" title="<?php echo html_escape(t('lepas')); ?>"><i class="fas fa-star"></i></button>
</li>
<?php } ?>
