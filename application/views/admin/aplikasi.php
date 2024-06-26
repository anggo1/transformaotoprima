<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header bg-light card-blue card-outline">
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> Data Aplikasi</h3>
                        <div class="text-right">
                            <a href="<?php echo base_url("aplikasi/export"); ?>" type="button"
                                class="btn btn-sm btn-outline-info" title="Download" target="_blank"><i
                                    class="fas fa-download"></i> Download1</a>
                            <a href="<?=base_url('aplikasi/download');?>" type="button"
                                class="btn btn-sm btn-outline-info" title="Download" target="_blank"><i
                                    class="fas fa-download"></i> Download</a>
                            <a href="<?=base_url('backup/backupdb');?>" type="button"
                                class="btn btn-sm btn-outline-warning" title="Backup"><i class="fas fa-hdd"></i> Backup
                                Database</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabelsubmenu" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-info">
                                    <th>Nama Owner</th>
                                    <th>Alamat</th>
                                    <th>No Telp</th>
                                    <th>Title</th>
                                    <th>Nama Aplikasi</th>
                                    <th>Copy Right</th>
                                    <th>Versi</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th>Logo</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-lg-4">
                <div class="card ">
                    <div class="modal-content">
                        <div class="card-header card-blue card-outline">
                            <h3 class="card-title"><i class="ion-ios-cog-outline ion-lg text-blue"></i> &nbsp; Data Cabang
                            </h3>
                            <div class="text-right">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                    data-target="#tambah-pool" title="Add Data"><i class="fas fa-plus"></i>
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
                                            <th>Nama</th>
                                            <th>Wilayah</th>
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
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<?php 
show_my_confirm('hapusPool', 'hapus-pool', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>







<script type="text/javascript">
    window.onload = function() {
        showPl();
    }
    function refresh() {
        MyTable = $('#list-laporan,#list-kategori,#list-pk,#list-kelas').DataTable();
    }
  function showPl() {
        $.get('<?php echo base_url('Aplikasi/showPl'); ?>', function(data) {
            tablePool.destroy();
            $('#data-pool').html(data);
            refresh();
        });
    }
    $('#form-tambah-pool').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Aplikasi/prosesTpool'); ?>',
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
                url: "<?php echo base_url('Aplikasi/updatePool'); ?>",
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
                url: '<?php echo base_url('Aplikasi/prosesUpool'); ?>',
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
                url: "<?php echo base_url('Aplikasi/deletePool'); ?>",
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

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $("#tabelsubmenu").DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "sEmptyTable": "Data Aplikasi Belum Ada"
        },
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('aplikasi/ajax_list')?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
                "targets": [-1], //last column
                "render": function(data, type, row) {

                    return "<a class=\"btn btn-xs btn-outline-primary\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit_aplikasi(" +
                        row[10] + ")\"><i class=\"fas fa-edit\"></i></a>";

                },

                "orderable": false, //set not orderable
            },
            {
                "targets": [9],
                "render": function(data, type, row) {
                    if (row[9] != null) {
                        return "<img class=\"myImgx\"  src='<?php echo base_url("assets/foto/logo/");?>" +
                            row[9] + "' width=\"100px\" height=\"100px\">";
                    } else {
                        return "<img class=\"myImgx\"  src='<?php echo base_url("assets/foto/logo/Logo.png");?>' width=\"100px\" height=\"100px\">";
                    }
                }
            },
        ],
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
        $(this).removeClass('is-invalid');
    });
    $("textarea").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
        $(this).removeClass('is-invalid');
    });
    $("select").change(function() {
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
        $(this).removeClass('is-invalid');
    });

});

function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax 
}

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});



function edit_aplikasi(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url: "<?php echo site_url('aplikasi/edit_aplikasi')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {

            $('[name="id"]').val(data.id);
            $('[name="nama_owner"]').val(data.nama_owner);
            $('[name="alamat"]').val(data.alamat);
            $('[name="kota"]').val(data.kota);
            $('[name="kode_pos"]').val(data.kode_pos);
            $('[name="tlp"]').val(data.tlp);
            $('[name="title"]').val(data.title);
            $('[name="nama_aplikasi"]').val(data.nama_aplikasi);
            $('[name="copy_right"]').val(data.copy_right);
            $('[name="tahun"]').val(data.tahun);
            $('[name="versi"]').val(data.versi);
            $('[name="npwp"]').val(data.npwp);
            $('[name="status"]').val(data.status);
            if (data.logo == null) {
                var image = "<?php echo base_url('assets/foto/logo/Logo.png')?>";
                $("#v_image").attr("src", image);
            } else {
                var image = "<?php echo base_url('assets/foto/logo/')?>";
                $("#v_image").attr("src", image + data.logo);
            }
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Aplikasi'); // Set title to Bootstrap modal title

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}

function save() {
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled', true); //set button disable 
    var url = "<?php echo site_url('aplikasi/update')?>";
    var formdata = new FormData($('#form')[0]);
    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {

            if (data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
                Toast.fire({
                    icon: 'success',
                    title: 'Success!!.'
                });
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid');
                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]).addClass(
                        'invalid-feedback');
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable 


        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable 

        }
    });
}
var loadFile = function(event) {
    var image = document.getElementById('v_image');
    image.src = URL.createObjectURL(event.target.files[0]);
};

/** Start Pool */

var tablePool = $('#list-pool').DataTable();

$('#form-tambah-pool').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Aplikasi/prosesTpool'); ?>',
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
                url: "<?php echo base_url('Aplikasi/updatePool'); ?>",
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
                url: '<?php echo base_url('Aplikasi/prosesUpool'); ?>',
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
                url: "<?php echo base_url('Aplikasi/deletePool'); ?>",
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



<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">Person Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" value="" name="id" />
                    <div class="card-body">
                        <div class="form-group row ">
                            <label for="nama_aplikasi" class="col-sm-3 col-form-label">Nama Aplikasi</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="nama_aplikasi" id="nama_aplikasi"
                                    placeholder="Nama Aplikasi">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="nama_owner" class="col-sm-3 col-form-label">Nama Owner</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="nama_owner" id="nama_owner"
                                    placeholder="Nama Owner">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="alamat" class="col-sm-3 col-form-label">Kota</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="kota" id="kota" placeholder="Kota">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="alamat" class="col-sm-3 col-form-label">Kode Pos</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="kode_pos" id="kode_pos" placeholder="Kode Pos">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="tlp" class="col-sm-3 col-form-label">No Telp</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="tlp" id="tlp" placeholder="Telpone">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-3 col-form-label">Title</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="versi" class="col-sm-3 col-form-label">Versi</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="versi" id="versi" placeholder="Title">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="copy_right" class="col-sm-3 col-form-label">Copy Right</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="copy_right" id="copy_right"
                                    placeholder="Copy Right">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="tahun" id="tahun"
                                    placeholder="Nama Aplikasi">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="tahun" class="col-sm-3 col-form-label">NPWP</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="npwp" id="npwp" placeholder="NPWP">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group row ">
                            <label for="tahun" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9 kosong">
                                <input type="text" class="form-control" name="status" id="status" placeholder="Status Office">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="logo" class="col-sm-3 col-form-label">Logo</label>
                            <div class="col-sm-9 kosong">
                                <img id="v_image" width="100px" height="100px">
                                <input type="file" class="form-control btn-file" onchange="loadFile(event)"
                                    name="imagefile" id="imagefile" placeholder="Image" value="UPLOAD">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->