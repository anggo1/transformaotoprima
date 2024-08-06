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
                    <div class="col-lg-3">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Voucher</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-voucher" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-voucher">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Periode</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-voucher">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-voucher"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Satuan</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-satuan" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-satuan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Satuan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-satuan">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-satuan"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Kategori</h3>
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
                                                    <th>Divisi</th>
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
                    <div class="col-lg-3">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-gear-b ion-lg text-blue"></i> &nbsp; Type Mesin
                                    </h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-type" title="Add Data"><i class="fas fa-plus"></i> Add
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-type">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-type">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-type"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Kode PO</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-kode-po" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-po">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-kode-po">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-kode-po"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-gear-a ion-lg text-blue"></i> &nbsp; Kelompok</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-kelompok" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-kelompok">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Kelompok</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-kelompok">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-kelompok"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i> &nbsp; Supplier</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-supplier" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-supplier">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Sup</th>
                                                    <th>Nama Panjang</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-supplier">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-supplier"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i> &nbsp; Customer</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-customer" title="Add Data"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-customer">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-customer">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-customer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
show_my_confirm('hapusSatuan', 'hapus-satuan', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusKategori', 'hapus-kategori', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusType', 'hapus-type', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusSupplier', 'hapus-supplier', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusCustomer', 'hapus-customer', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusKelompok', 'hapus-kelompok', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusVoucher', 'hapus-voucher', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusKode_po', 'hapus-kode-po', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>
<script type="text/javascript">
    window.onload = function() {
        showSat();
        showKat();
        showType();
        showSup();
        showCus();
        showKp();
        showVoucher();
        showPo();
    }
    $('#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY'
});
    function refresh() {
        MyTable = $('#list-satuan,#list-kategori,#list-mesin,#list-supplier,#list-customer').DataTable();
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

    var tableSatuan = $('#list-satuan').DataTable();
    var tableKategori = $('#list-kategori').DataTable();
    var tableType = $('#list-type').DataTable();
    var tableSupplier = $('#list-supplier').DataTable();
    var tableCustomer = $('#list-customer').DataTable();
    var tableKelompok = $('#list-kelompok').DataTable();
    var tableVoucher = $('#list-voucher').DataTable();
    var tablePo = $('#list-po').DataTable();

    //ajax Jabatan
    function showSat() {
        $.get('<?php echo base_url('Settingwh/showSat'); ?>', function(data) {
            tableSatuan.destroy();
            $('#data-satuan').html(data);
            refresh();
        });
    }

    function showKat() {
        $.get('<?php echo base_url('Settingwh/showKat'); ?>', function(data) {
            tableKategori.destroy();
            $('#data-kategori').html(data);
            refresh();
        });
    }

    function showType() {
        $.get('<?php echo base_url('Settingwh/showType'); ?>', function(data) {
            tableType.destroy();
            $('#data-type').html(data);
            refresh();
        });
    }

    function showSup() {
        $.get('<?php echo base_url('Settingwh/showSup'); ?>', function(data) {
            tableSupplier.destroy();
            $('#data-supplier').html(data);
            refresh();
        });
    }

    function showCus() {
        $.get('<?php echo base_url('Settingwh/showCus'); ?>', function(data) {
            tableCustomer.destroy();
            $('#data-customer').html(data);
            refresh();
        });
    }
    function showKp() {
        $.get('<?php echo base_url('Settingwh/showKp'); ?>', function(data) {
            tableKelompok.destroy();
            $('#data-kelompok').html(data);
            refresh();
        });
    }
    
    function showVoucher() {
        $.get('<?php echo base_url('Settingwh/showVoucher'); ?>', function(data) {
            tableVoucher.destroy();
            $('#data-voucher').html(data);
            refresh();
        });
    }
    
    function showPo() {
        $.get('<?php echo base_url('Settingwh/showPo'); ?>', function(data) {
            tablePo.destroy();
            $('#data-kode-po').html(data);
            refresh();
        });
    }

    $('#form-tambah-voucher').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesTvoucher'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showVoucher();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-voucher").reset();
                    $('#tambah-voucher').modal('hide');
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

    $(document).on("click", ".update-dataVoucher", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/updateVoucher'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-voucher').html(data);
                $('#update-voucher').modal('show');
            })
    })
    $(document).on('submit', '#form-update-voucher', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesUvoucher'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showVoucher();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-voucher").reset();
                    $('#update-voucher').modal('hide');
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

    $('#tambah-voucher').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-voucher').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-voucher", function() {
        id_sat = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-voucher", function() {
        var id = id_sat;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/deleteVoucher'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showVoucher();
                $('.msg').html(out.msg);
                $('#hapusVoucher').modal('hide');
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


    //*** end Voucher **//

    $('#form-tambah-kode-po').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesTkode_po'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showPo();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-kode-po").reset();
                    $('#tambah-kode-po').modal('hide');
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

    $(document).on("click", ".update-dataKode-po", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/updateKode_po'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-kode-po').html(data);
                $('#update-kode-po').modal('show');
            })
    })
    $(document).on('submit', '#form-update-kode-po', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesUkode_po'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showPo();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-kode-po").reset();
                    $('#update-kode-po').modal('hide');
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

    $('#tambah-kode-po').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-kode-po').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-kode-po", function() {
        id_sat = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-kode-po", function() {
        var id = id_sat;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/deleteKode_po'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showPo();
                $('.msg').html(out.msg);
                $('#hapusKode_po').modal('hide');
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


    //*** end KOde_po **//


    $('#form-tambah-satuan').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesTsatuan'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showSat();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-satuan").reset();
                    $('#tambah-satuan').modal('hide');
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

    $(document).on("click", ".update-dataSatuan", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/updateSatuan'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-satuan').html(data);
                $('#update-satuan').modal('show');
            })
    })
    $(document).on('submit', '#form-update-satuan', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesUsatuan'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showSat();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-satuan").reset();
                    $('#update-satuan').modal('hide');
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

    $('#tambah-satuan').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-satuan').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-satuan", function() {
        id_sat = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-satuan", function() {
        var id = id_sat;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/deleteSatuan'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showSat();
                $('.msg').html(out.msg);
                $('#hapusSatuan').modal('hide');
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


    //*** end Satuan **//

    $('#form-tambah-kategori').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesTkategori'); ?>',
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
                url: "<?php echo base_url('Settingwh/updateKategori'); ?>",
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
                url: '<?php echo base_url('Settingwh/prosesUkategori'); ?>',
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
                url: "<?php echo base_url('Settingwh/deleteKategori'); ?>",
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

    $('#form-tambah-type').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesTtype'); ?>',
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
                url: "<?php echo base_url('Settingwh/updateType'); ?>",
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
                url: '<?php echo base_url('Settingwh/prosesUtype'); ?>',
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
        id_sat = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-type", function() {
        var id = id_sat;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/deleteType'); ?>",
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
    $('#form-tambah-supplier').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesTsupplier'); ?>',
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
                url: "<?php echo base_url('Settingwh/updateSupplier'); ?>",
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
                url: '<?php echo base_url('Settingwh/prosesUsupplier'); ?>',
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
                url: "<?php echo base_url('Settingwh/deleteSupplier'); ?>",
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
    
    $('#form-tambah-customer').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesTcustomer'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showCus();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-customer").reset();
                    $('#tambah-customer').modal('hide');
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

    $(document).on("click", ".update-dataCustomer", function() {
        var id_sup = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/updateCustomer'); ?>",
                data: "id=" + id_sup
            })
            .done(function(data) {
                $('#modal-customer').html(data);
                $('#update-customer').modal('show');
            })
    })
    $(document).on('submit', '#form-update-customer', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesUcustomer'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showCus();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-customer").reset();
                    $('#update-customer').modal('hide');
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

    $('#tambah-customer').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-customer').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-customer", function() {
        id_cus = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-customer", function() {
        var id = id_cus;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/deleteCustomer'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showCus();
                $('.msg').html(out.msg);
                $('#hapusCustomer').modal('hide');
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
    //** end Customer */

    $('#form-tambah-kelompok').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesTkelompok'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKp();
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
                url: "<?php echo base_url('Settingwh/updateKelompok'); ?>",
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
                url: '<?php echo base_url('Settingwh/prosesUkelompok'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKp();
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
        id_kat = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-kelompok", function() {
        var id = id_kat;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/deleteKelompok'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showKp();
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
    //** End Kelompok */
</script>