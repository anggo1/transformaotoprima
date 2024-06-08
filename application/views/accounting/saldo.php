<style>
.table.dataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 12px;
}

table.dataTable td {
    padding: 5px;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> Saldo Awal</h3>
                        <div class="text-right">
                            <?php foreach ($viewLevel as $l) {
                            if ($l->add_level=='Y'){
                            echo '<button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-saldo" title="Add Data"><i class="fas fa-plus"></i> Tambah Data</button>';
                            }}
                            ?>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label">Tahun Saldo</label>
                            <div class="col-sm-2">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                    <input type="text" name="thn_saldo" id="thn_saldo"
                                        class="form-control thn_saldo datetimepicker" data-toggle="datetimepicker"
                                        onchange="listSaldo()" onclick="listSaldo()" data-target=".thn_saldo"
                                        data-format="yyyy" required>

                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="data-saldo"></div>
                    </div>
                </div>
            </div>
            <div id="tempat-modal"></div>
        </div>
    </div>
</section>
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body form">
                <div class="card card-first card-outline">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%" class="table table-hover nowrap" id="list-akun">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Akun</th>
                                        <th>Nama Akun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no=0; foreach ($dataAkun as $a){ $no++; ?>
                                    <tr
                                        onclick="selectAkun('<?php echo $a->kode_akun; ?>','<?php echo $a->nama_akun; ?>')">
                                        <th><?php echo $no ?></th>
                                        <th><?php echo $a->kode_akun ?></th>
                                        <th><?php echo $a->nama_akun ?></th>
                                        </th>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
show_my_confirm('hapusSaldo', 'hapus-saldo', 'Menghapus data ini mungkin akan mempengaruhi beberapa data terkait, Anda yakin akan menghapus data ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>

<script type="text/javascript">
$('#thn_saldo,#thn_saldo_input').datetimepicker({
    format: 'YYYY',
    date: moment()
});

function refresh() {
    MyTable = $('#list-akun,#list-saldo,#list-global').dataTable();
}
var MyTable = $('#list-akun,#list-saldo,#list-global').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 10
});

$('#form-tambah-saldo').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Saldo/prosesTsaldo'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                document.getElementById("form-tambah-saldo").reset();
                $('#tambah-saldo').modal('hide');
                $('.msg').html(out.msg);
                listSaldo();
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

function listSaldo() {
    var periode = document.getElementById("thn_saldo").value;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('Saldo/showSaldo'); ?>?periode=' + periode,
        data: 'periode=' + periode,
        success: function(hasil) {
            MyTable.fnDestroy();
            $('#data-saldo').html(hasil);
            refresh();
        }
    });
}
$(document).on("click", ".update-saldo", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Saldo/updateSaldo'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#tempat-modal').html(data);
            $('#update-saldo').modal('show');
        })
})
$(document).on('submit', '#form-update-saldo', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Saldo/prosesUsaldo'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            table.ajax.reload();
            if (out.status == 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                document.getElementById("form-update-saldo").reset();
                $('#update-saldo').modal('hide');
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

$('#tambah-saldo').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-saldo').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})
$(document).on("click", ".delete-saldo", function() {
    id_saldo = $(this).attr("data-id");
})
$(document).on("click", ".hapus-saldo", function() {
    var id = id_saldo;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Saldo/deleteSaldo'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            listSaldo();
            $('.msg').html(out.msg);
            $('#hapusSaldo').modal('hide');
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
</script>