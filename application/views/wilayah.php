<style>
    .table.DataTable {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12px;
    }

    table.dataTable td {
        padding: 5px;
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
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Keterangan Laporan</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-laporan" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-laporan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-laporan">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-laporan"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Kategori Perbaikan</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-kategori" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-kategori">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Kategori</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-kategori">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-kategori"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Kelas Pelayanan</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-kelas" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-kelas">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kelas</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-kelas">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-kelas"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Jenis Perintah Pekerjaan (PK)</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-pk" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pk">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-pk">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-pk"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Data Pool</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-pool" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pool">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Pool</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-pool">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-pool"></div>
                            </div>
                        </div>
                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
show_my_confirm('hapusLaporan', 'hapus-laporan', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusKategori', 'hapus-kategori', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusPk', 'hapus-pk', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusKelas', 'hapus-kelas', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusPool', 'hapus-pool', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>
<script type="text/javascript">
    window.onload = function() {
        showLap();
        showKat();
        showPk();
        showKl();
        showPl();
    }

    function refresh() {
        MyTable = $('#list-laporan,#list-kategori,#list-pk,#list-kelas').DataTable();
    }

    function effect_msg_form() {
        // $('.form-msg').hide();
        $('.form-msg').show(500);
        setTimeout(function() {
            $('.form-msg').fadeOut(500);
        }, 1000);
    }

    function effect_msg() {
        // $('.msg').hide();
        $('.msg').show(500);
        setTimeout(function() {
            $('.msg').fadeOut(500);
        }, 1000);
    }

    var tableLaporan = $('#list-laporan').DataTable();
    var tableKategori = $('#list-kategori').DataTable();
    var tablePk = $('#list-pk').DataTable();
    var tableKelas = $('#list-kelas').DataTable();
    var tablePool = $('#list-pool').DataTable();

    //ajax Jabatan
    function showLap() {
        $.get('<?php echo base_url('Settingbr/showLap'); ?>', function(data) {
            tableLaporan.destroy();
            $('#data-laporan').html(data);
            refresh();
        });
    }

    function showKat() {
        $.get('<?php echo base_url('Settingbr/showKat'); ?>', function(data) {
            tableKategori.destroy();
            $('#data-kategori').html(data);
            refresh();
        });
    }

    function showPk() {
        $.get('<?php echo base_url('Settingbr/showPk'); ?>', function(data) {
            tablePk.destroy();
            $('#data-pk').html(data);
            refresh();
        });
    }
    function showKl() {
        $.get('<?php echo base_url('Settingbr/showKl'); ?>', function(data) {
            tableKelas.destroy();
            $('#data-kelas').html(data);
            refresh();
        });
    }
    function showPl() {
        $.get('<?php echo base_url('Settingbr/showPl'); ?>', function(data) {
            tablePool.destroy();
            $('#data-pool').html(data);
            refresh();
        });
    }


    $('#form-tambah-laporan').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesTlaporan'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showLap();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-laporan").reset();
                    $('#tambah-laporan').modal('hide');
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

    $(document).on("click", ".update-dataLaporan", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/updateLaporan'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-laporan').html(data);
                $('#update-laporan').modal('show');
            })
    })
    $(document).on('submit', '#form-update-laporan', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesUlaporan'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showLap();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-laporan").reset();
                    $('#update-laporan').modal('hide');
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

    $('#tambah-laporan').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-laporan').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-laporan", function() {
        id_lap = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-laporan", function() {
        var id = id_lap;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/deleteLaporan'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showLap();
                $('.msg').html(out.msg);
                $('#hapusLaporan').modal('hide');
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

    $('#form-tambah-kategori').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesTkategori'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKat();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-kategori").reset();
                    $('#tambah-kategori').modal('hide');
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

    $(document).on("click", ".update-dataKategori", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/updateKategori'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-kategori').html(data);
                $('#update-kategori').modal('show');
            })
    })
    $(document).on('submit', '#form-update-kategori', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesUkategori'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKat();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-kategori").reset();
                    $('#update-kategori').modal('hide');
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

    $('#tambah-kategori').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-kategori').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-kategori", function() {
        id_kat = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-kategori", function() {
        var id = id_kat;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/deleteKategori'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showKat();
                $('.msg').html(out.msg);
                $('#hapusKategori').modal('hide');
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
    //*** end KAtegori **//

    $('#form-tambah-pk').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesTpk'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showPk();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-pk").reset();
                    $('#tambah-pk').modal('hide');
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

    $(document).on("click", ".update-dataPk", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/updatePk'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-pk').html(data);
                $('#update-pk').modal('show');
            })
    })
    $(document).on('submit', '#form-update-pk', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesUpk'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showPk();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-pk").reset();
                    $('#update-pk').modal('hide');
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

    $('#tambah-pk').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-pk').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-pk", function() {
        id_pk = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-pk", function() {
        var id = id_pk;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/deletePk'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showPk();
                $('.msg').html(out.msg);
                $('#hapusPk').modal('hide');
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
    //** End Keterangan PK */
    $('#form-tambah-kelas').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesTkelas'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKl();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-kelas").reset();
                    $('#tambah-kelas').modal('hide');
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

    $(document).on("click", ".update-dataKelas", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/updateKelas'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-kelas').html(data);
                $('#update-kelas').modal('show');
            })
    })
    $(document).on('submit', '#form-update-kelas', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesUkelas'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKl();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-kelas").reset();
                    $('#update-kelas').modal('hide');
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

    $('#tambah-kelas').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-kelas').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-kelas", function() {
        id_kelas = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-kelas", function() {
        var id = id_kelas;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/deleteKelas'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showKl();
                $('.msg').html(out.msg);
                $('#hapusKelas').modal('hide');
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


    //** End Kelas Pelayanan */

    $('#form-tambah-pool').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesTpool'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showPl();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-pool").reset();
                    $('#tambah-pool').modal('hide');
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

    $(document).on("click", ".update-dataPool", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/updatePool'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-pool').html(data);
                $('#update-pool').modal('show');
            })
    })
    $(document).on('submit', '#form-update-pool', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesUpool'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showPl();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-pool").reset();
                    $('#update-pool').modal('hide');
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

    $('#tambah-pool').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-pool').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-pool", function() {
        id_pool = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-pool", function() {
        var id = id_pool;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingbr/deletePool'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showPl();
                $('.msg').html(out.msg);
                $('#hapusPool').modal('hide');
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
//** end pool */
</script>