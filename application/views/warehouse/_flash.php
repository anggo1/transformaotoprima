<?php defined('BASEPATH') OR exit('No direct script access allowed');
$m = $this->session->flashdata('wh_msg');
if (is_array($m)) { ?>
<div class="alert alert-<?php echo html_escape($m[0]); ?> alert-dismissible fade show" role="alert">
    <i class="fas fa-<?php echo $m[0] === 'success' ? 'check-circle' : ($m[0] === 'danger' ? 'times-circle' : 'exclamation-triangle'); ?> mr-1"></i> <?php echo $m[1]; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
</div>
<?php }
