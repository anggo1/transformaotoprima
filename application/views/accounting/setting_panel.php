<style>
    .table.DataTable {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12px;
    }

    table.dataTable td {
        padding: 3px;
    }
</style>
<div class="row">
    <div class="col-12 ">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-body card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Kelompok Akun</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-kelompok" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                            <div id="data-kelompok">
                                            </div>
                                </div>
                                <div id="modal-kelompok"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-body card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Type Akun</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-type" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                            <div id="data-type">
                                            </div>
                                </div>
                                <div id="modal-type"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-body card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Jenis Beban</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-jenis" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                            <di id="data-jenis">
                                            </di>
                                </div>
                                <div id="modal-jenis"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-body card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Jenis Akun</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-ref" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                            <div id="data-ref"></div>
                                </div>
                                <div id="modal-ref"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-8">
                            <div class="modal-content">
                                <div class="card-body card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i> &nbsp; Supplier</h3>
                                </div>
                                <div class="col-12 ">
                                            <div id="data-supplier"></div>
                                <div id="modal-supplier"></div>
                            </div>
                        </div>
                    </div>
                    </div>
                    
                            </div>
                        </div>
                    </div>
                </div>

<?php
show_my_confirm('hapusKelompok', 'hapus-kelompok', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusType', 'hapus-type', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusJenis', 'hapus-jenis', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusRef', 'hapus-ref', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>
<script type="text/javascript">
    window.onload = function() {
        showKel();
        showType();
        showJen();
        showRef();
        showSup();
    }
    function showKel() {
        $.get('<?php echo base_url('SettingAcc/showKel'); ?>', function(data) {
            $('#data-kelompok').html(data);
        });
    }

    function showType() {
        $.get('<?php echo base_url('SettingAcc/showType'); ?>', function(data) {
            $('#data-type').html(data);
        });
    }

    function showJen() {
        $.get('<?php echo base_url('SettingAcc/showJenis'); ?>', function(data) {
            $('#data-jenis').html(data);
        });
    }
    function showRef() {
        $.get('<?php echo base_url('SettingAcc/showRef'); ?>', function(data) {
            $('#data-ref').html(data);
        });
    }
    function showSup() {
        $.get('<?php echo base_url('SettingAcc/showSup'); ?>', function(data) {
            $('#data-supplier').html(data);
        });
    }

    $('#form-tambah-kelompok').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTkelompok'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKel();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-kelompok").reset();
                    $('#tambah-kelompok').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $(document).on("click", ".update-dataKelompok", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateKelompok'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-kelompok').html(data);
                $('#update-kelompok').modal('show');
            })
    })
    $(document).on('submit', '#form-update-kelompok', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUkelompok'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKel();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-kelompok").reset();
                    $('#update-kelompok').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $('#tambah-kelompok').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-kelompok').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-kelompok", function() {
        id_lap = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-kelompok", function() {
        var id = id_lap;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteKelompok'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showKel();
                $('.msg').html(out.msg);
                $('#hapusKelompok').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })


    //*** end Keterangan Laporan **//

    $('#form-tambah-type').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTtype'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showType();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-type").reset();
                    $('#tambah-type').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $(document).on("click", ".update-dataType", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateType'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-type').html(data);
                $('#update-type').modal('show');
            })
    })
    $(document).on('submit', '#form-update-type', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUtype'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showType();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-type").reset();
                    $('#update-type').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $('#tambah-type').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-type').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-type", function() {
        id_kat = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-type", function() {
        var id = id_kat;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteType'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showType();
                $('.msg').html(out.msg);
                $('#hapusType').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })
    //*** end Type **//

    $('#form-tambah-jenis').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTjenis'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showJen();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-jenis").reset();
                    $('#tambah-jenis').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $(document).on("click", ".update-dataJenis", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateJenis'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-jenis').html(data);
                $('#update-jenis').modal('show');
            })
    })
    $(document).on('submit', '#form-update-jenis', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUjenis'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showJen();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-jenis").reset();
                    $('#update-jenis').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $('#tambah-jenis').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-jenis').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-jenis", function() {
        id_j = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-jenis", function() {
        var id = id_j;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteJenis'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showJen();
                $('.msg').html(out.msg);
                $('#hapusJenis').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })
    //** End Jenis akun */

    
    $('#form-tambah-supplier').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTsupplier'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showSup();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-supplier").reset();
                    $('#tambah-supplier').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $(document).on("click", ".update-dataSupplier", function() {
        var id_sup = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateSupplier'); ?>",
                data: "id=" + id_sup
            })
            .done(function(data) {
                $('#modal-supplier').html(data);
                $('#update-supplier').modal('show');
            })
    })
    $(document).on('submit', '#form-update-supplier', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUsupplier'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showSup();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-supplier").reset();
                    $('#update-supplier').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $('#tambah-supplier').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-supplier').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-supplier", function() {
        id_sup = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-supplier", function() {
        var id = id_sup;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteSupplier'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showSup();
                $('.msg').html(out.msg);
                $('#hapusSupplier').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })
    //** end Supplier */
    
    $('#form-tambah-ref').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTref'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showRef();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-ref").reset();
                    $('#tambah-ref').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $(document).on("click", ".update-dataRef", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateRef'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-ref').html(data);
                $('#update-ref').modal('show');
            })
    })
    $(document).on('submit', '#form-update-ref', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUref'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showRef();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-ref").reset();
                    $('#update-ref').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    $('#tambah-ref').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-ref').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-ref", function() {
        id_j = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-ref", function() {
        var id = id_j;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteRef'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showRef();
                $('.msg').html(out.msg);
                $('#hapusRef').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })
</script>