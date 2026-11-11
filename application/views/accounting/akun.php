<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0"><i class="fas fa-sitemap mr-2 text-muted"></i>Daftar Akun (Chart of Accounts)</h3>
        <div class="d-flex" style="gap:.4rem">
            <a href="<?php echo site_url('LaporanKeuangan/neraca_saldo'); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-balance-scale-left mr-1"></i> Neraca Saldo</a>
            <?php if ( ! empty($boleh['add'])) { ?><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-akun"><i class="fas fa-plus mr-1"></i> Tambah Akun</button><?php } ?>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3"><i class="fas fa-info-circle"></i> <b>Tipe Akun</b> menentukan letak akun pada Neraca, Laba Rugi &amp; Arus Kas (mengikuti standar Accurate). Akun yang sudah dipakai transaksi tidak dapat dihapus — ubah statusnya menjadi Nonaktif.</p>
        <div id="data-acc"><div class="text-center text-muted p-4"><i class="fas fa-circle-notch fa-spin"></i> Memuat...</div></div>
        <div id="modal-acc"></div>
    </div>
</div>
<script>
function showAcc() {
    $.get('<?php echo site_url('Account/showAcc'); ?>', function (html) {
        if ($.fn.DataTable.isDataTable('#list-akun')) $('#list-akun').DataTable().destroy();
        $('#data-acc').html(html);
    });
}
function kirimAkun($form, url, $modal) {
    $.post(url, $form.serialize(), null, 'json').done(function (out) {
        if (out.status === 'ok') {
            $modal.modal('hide');
            if ($form.attr('id') === 'form-tambah-akun') $form[0].reset();
            toastr.success(out.msg);
            showAcc();
        } else {
            $form.find('.form-msg').html(out.msg).show();
        }
    });
}
$(function () {
    showAcc();
    $(document).on('submit', '#form-tambah-akun', function (e) { e.preventDefault(); kirimAkun($(this), '<?php echo site_url('Account/prosesTacc'); ?>', $('#tambah-akun')); });
    $(document).on('submit', '#form-update-akun', function (e) { e.preventDefault(); kirimAkun($(this), '<?php echo site_url('Account/prosesUakun'); ?>', $('#update-akun')); });
    $(document).on('click', '.update-akun', function () {
        $.post('<?php echo site_url('Account/updateAkun'); ?>', { id: $(this).data('id') }).done(function (html) {
            $('#update-akun').remove(); $('#modal-acc').html(html); $('#update-akun').modal('show');
        });
    });
    $(document).on('click', '.delete-akun', function () {
        var id = $(this).data('id'), kode = $(this).data('kode');
        TOP.konfirmasi('Hapus akun ' + kode + '?', 'Akun yang sudah dipakai transaksi tidak dapat dihapus.', function () {
            $.post('<?php echo site_url('Account/deleteAkun'); ?>', { id: id }, null, 'json').done(function (out) {
                if (out.status === 'ok') { toastr.success(out.msg); showAcc(); } else toastr.error(out.msg);
            });
        }, 'Ya, hapus');
    });
});
</script>
