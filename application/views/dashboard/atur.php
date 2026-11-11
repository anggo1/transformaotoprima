<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="alert alert-light border small"><i class="fas fa-info-circle text-primary mr-1"></i> Dashboard Anda saat ini: <b><?php echo html_escape($sumber); ?></b>. Susunan yang disimpan di sini hanya berlaku untuk akun Anda. Klik <i>Kembalikan bawaan</i> untuk kembali mengikuti pengaturan level/departemen.</div>
<?php $this->load->view('dashboard/_editor'); ?>
