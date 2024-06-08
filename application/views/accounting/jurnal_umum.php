<style>
.table.dataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 11px;
}

table.dataTable td {
    padding: 5px;
}
</style>
<section class="content" id="dataJurnal">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> Data Jurnal Umum</h3>
                        <div class="text-right">
                            <?php foreach ($viewLevel as $l) {
                            if ($l->add_level=='Y'){
                            echo '<button type="button" class="btn btn-sm btn-outline-primary tambah-jurnal"  title="Add Data"><i class="fas fa-plus"></i> Tambah Data</button>';
                            }}
                            ?>
                        </div>
                    </div>
                    <div class="card-body">
                    <div class="form-group row">
                            <label for="No Ref" class="col-sm-1 col-form-label">No Refrensi</label>
                            <div class="col-sm-2">
                                    <input type="text" name="no_ref_cari" id="no_ref_cari" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label">Tanggal Awal</label>
                            <div class="col-sm-2">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                    <input type="text" name="tgl_awal" id="tgl_awal"
                                        class="form-control tgl_awal datetimepicker" data-toggle="datetimepicker"
                                        data-target=".tgl_awal" data-format="yyy-mm-dd" required>

                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-sm-1 col-form-label">Tanggal Akhir</label>
                            <div class="col-sm-2">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                    <input type="text" name="tgl_akhir" id="tgl_akhir"
                                        class="form-control tgl_akhir datetimepicker" data-toggle="datetimepicker"
                                        data-target=".tgl_akhir" data-format="yyy-mm-dd" required>

                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn bg-gradient-primary col-sm-12" onclick="listJurnal()" type="submit"><span class="fa fa-search"></span> Cari</button>
                            </div>
                            <div class="col-sm-1">
                                    <button class="btn bg-gradient-success" onclick="cetakJurnalumum()"><i class="fa fa-print"></i> Cetak</button>
                            </div>
                        </div>
                <div id="data-jurnal-global"></div>
                <div id="tempat-modal"></div>
                <div id="modal-jurnal"></div>
                <div id="modal-jurnal-umum"></div>
                    </div>
                </div>
            </div>
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
                            <table width="100%" class="table table-hover nowrap" id="list-akun-data">
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
                                        onclick="selectAkun('<?php echo $a->kode_akun; ?>','<?php echo $a->nama_akun; ?>','<?php echo $a->kelompok; ?>','<?php echo $a->type_akun; ?>','<?php echo $a->jenis_beban; ?>')">
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

<div class="modal fade" id="modal_ref" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body form">
                <div class="card card-first card-outline">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%" class="table table-hover nowrap" id="list-ref">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Ref</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no=0; foreach ($dataRef as $r){ $no++; ?>
                                    <tr
                                        onclick="selectRef('<?php echo $r->no_ref; ?>')">
                                        <th><?php echo $no ?></th>
                                        <th><?php echo $r->no_ref ?></th>
                                        <th><?php echo $r->keterangan ?></th>
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
show_my_confirm('hapusJurnal', 'hapus-jurnal', 'Menghapus data ini mungkin dapat menyebabkan data lain terkena efek, anda yakin akan menghapus?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>

<script type="text/javascript">
$('#tgl_jurnal,#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
window.onload = function() {
    listJurnal();
}

function refresh() {
    MyTable = $('#table-jurnal1').dataTable();
}

$(document).ready(function() {
    $('#list-akun-data,#list-ref').DataTable();
});
var MyTable = $('#table-jurnal1,#table-cetak').dataTable({});

function listJurnal() {
    var tgl_awal = document.getElementById("tgl_awal").value;
    var tgl_akhir = document.getElementById("tgl_akhir").value;
    var no_ref_cari = document.getElementById("no_ref_cari").value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('Jurnal/showJurnal'); ?>?',
        data: 'tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir + '&no_ref_cari=' + no_ref_cari,
        success: function(hasil) {
            MyTable.fnDestroy();
            $('#data-jurnal-global').html(hasil);
            refresh();
        }
    });
}
$(document).on("click", ".tambah-jurnal", function() {
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Jurnal/tambahJurnal'); ?>"
        })
        .done(function(data) {
            $('#tempat-modal').html(data);
            $('#tambah-jurnal').modal('show');
        })
})
function cetakJurnalumum() {
    var tgl_awal = document.getElementById("tgl_awal").value;
    var tgl_akhir = document.getElementById("tgl_akhir").value;
    var no_ref_cari = document.getElementById("no_ref_cari").value;
		$.ajax({
		type: 'POST',
		url: '<?php echo base_url('Jurnal/cetakJurnalUmum'); ?>?',
        data: 'tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir + '&no_ref_cari=' + no_ref_cari,
			success:
            function(hasil) {
				$('#modal-jurnal-umum').html(hasil);
				$('#cetak-jurnal').modal('show');
			}
		});
	}
$(document).on("click", ".cetak-jurnal", function() {
    var no_ref = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Jurnal/cetakJurnal'); ?>",
            data: 'no_ref=' + no_ref,
        })
        .done(function(data) {
            $('#modal-jurnal').html(data);
            $('#cetak-jurnal-detail').modal('show');
        })
})

function showDetail() {
    $.get('<?php echo base_url('Jurnal/showDetail'); ?>', function(data) {
        MyTable.fnDestroy();
        $('#data-jurnal').html(data);
        startCek();
    });
}
function showDetailEdit() {
    var no_ref = document.getElementById("no_ref").value;
    $.get('<?php echo base_url('Jurnal/showDetailEdit'); ?>?no_ref=' + no_ref, function(data) {
        MyTable.fnDestroy();
        $('#data-jurnal').html(data);
        startCek();
    });
}
$(document).on("click", ".update-jurnal", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Jurnal/updateJurnal'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#tempat-modal').html(data);
            $('#update-jurnal').modal('show');
        })
})

function startDebit() {
    document.getElementById("kredit").readOnly = true;
}

function startKredit() {
    document.getElementById("debit").readOnly = true;
}

function startCek() {
    var total = document.getElementById("total").value;
    if (total == 0) {
        document.getElementById("btnGenerate").disabled = false;
    } else {
        document.getElementById("btnGenerate").disabled = true;
    }
}

function generateJurnal() {
    var kode_awal = document.getElementById("kode_awal").value;
    var no_ref = document.getElementById("no_ref").value;

    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('jurnal/Generate'); ?>?kode_awal' + kode_awal+ '&no_ref' + no_ref,
        data: 'kode_awal=' + kode_awal+'&no_ref=' + no_ref,
        success: function(hasil) {
            //table.ajax.reload();
                listJurnal();
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Data Jurnal Telah ditambahkan',
                showConfirmButton: false,
                timer: 1000,
                imageUrl: 'assets/dist/img/thumbs.png'
            })
            $('#tambah-jurnal').modal('hide');
            refresh();
        }
    });
}
$(document).on('submit', '#form-update-jurnal', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Jurnal/prosesUjurnal'); ?>',
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
                document.getElementById("form-update-jurnal").reset();
                $('#update-jurnal').modal('hide');
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

$('#tambah-jurnal').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-jurnal').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})
$(document).on("click", ".delete-jurnal", function() {
    var no_jurnal = $(this).attr("data-id");
    $(document).on("click", ".hapus-jurnal", function() {
        var no_ref = no_jurnal;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Jurnal/deleteJurnal'); ?>",
                data: "no_ref=" + no_ref
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                listJurnal();
                $('.msg').html(out.msg);
                $('#hapusJurnal').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Data Telah dihapus',
                        showConfirmButton: false,
                        timer: 1200
                    })
                }
            })
    })
})
</script>