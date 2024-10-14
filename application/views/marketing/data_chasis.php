<style>
.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 11px;
}

table.dataTable td {
    padding-bottom: 5px;
}

.Blink-warning {
    animation: blinker 5s cubic-bezier(.5, 0, 1, 1) infinite alternate;
}

@keyframes blinker {
    from {
        opacity: 1;
    }

    to {
        opacity: 0;
    }
}

.Blink-danger {
    animation: blinker 0.1s cubic-bezier(.5, 0, 1, 1) infinite alternate;
}

@keyframes blinker {
    from {
        opacity: 1;
    }

    to {
        opacity: 0;
    }
}


.tombol-success {
    background-color: green;
    border: none;
    color: white;
    padding: 2px 5px 2px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 10px;
    float: right;
}


.tombol-success {
    border-radius: 50%;
}

.tombol-warning {
    background-color: #ffc107;
    border: none;
    color: white;
    padding: 2px 5px 2px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 10px;
    margin: 4px 2px;
    float: right;
}

.tombol-warning {
    border-radius: 50%;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> Data Chasis</h3>
                        <div class="text-right">
                            <?php foreach ($viewLevel as $l) {
                            if ($l->add_level=='Y'){
                            echo '<button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-chasis" title="Add Data"><i class="fas fa-plus"></i> Tambah Data</button>';
                            }}
                            ?>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabel-chasis" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tgl Masuk</th>
                                    <th>Retail</th>
                                    <th>Type</th>
                                    <th>No Rangka</th>
                                    <th>No Mesin</th>
                                    <th>Sales</th>
                                    <th>Gesekan</th>
                                    <th>Thn Produksi</th>
                                    <th>Customer</th>
                                    <th>Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                <div id="tempat-modal"></div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
show_my_confirm('hapusChasis', 'hapus-chasis', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>
<script type="text/javascript">
$('#tgl_masuk').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
$('#thn_produksi').datetimepicker({
    format: 'YYYY',
    viewMode: 'years',
    date: moment()
});

$(document).ready(function() {

    //datatables
    table = $("#tabel-chasis").DataTable({
        "responsive": false,
        "autoWidth": true,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,

        "language": {
            "sEmptyTable": "Data Chasis Belum Ada"
        },
        "processing": true, //Feature control the processing indicator.
        "serverSide": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('Chasis/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 11],
            "orderable": false,
        }, ],

    });

});
$('#form-tambah-chasis').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Chasis/prosesTchasis'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-tambah-chasis").reset();
                $('#tambah-chasis').modal('hide');
                $('.msg').html(out.msg);
                table.ajax.reload();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })

    e.preventDefault();
});

$(document).on("click", ".update-chasis", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Chasis/updateChasis'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            //var out = jQuery.parseJSON(data);
            $('#tempat-modal').html(data);
            $('#update-chasis').modal('show');

        })

})
$(document).on('submit', '#form-update-chasis', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Chasis/prosesUchasis'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            table.ajax.reload();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-update-chasis").reset();
                $('#update-chasis').modal('hide');
                $('.msg').html(out.msg);
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })

    e.preventDefault();
});

$('#tambah-chasis').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-chasis').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})
$(document).on("click", ".delete-chasis", function() {
    id_chasis = $(this).attr("data-id");
})
$(document).on("click", ".hapus-chasis", function() {
    var id = id_chasis;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Chasis/deleteChasis'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            table.ajax.reload();
            $('.msg').html(out.msg);
            $('#hapusChasis').modal('hide');
            if (out.status != 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1200
                })
            }
        })
})

function cariBodyl() {
    var a = document.getElementById('no_body').value;
    $.ajax({
        url: "<?php echo base_url();?>Body/cariKodeBody",
        data: '&a=' + a,
        success: function(data) {
            var hasil = JSON.parse(data);
            if (hasil.length == 1) {
                document.getElementById("form-tambah-body");
                $('#no_body').val('');
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'No Body Sudah Ada',
                    showConfirmButton: false,
                    timer: 1000
                })
            }

        }
    });

}
</script>