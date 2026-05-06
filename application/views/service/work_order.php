<style>
.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 9px;
}

table.dataTable td {
    padding-bottom: 9px;
}
</style>

<section class="content">
    <div class="card-body card-outline">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs " id="custom-content-above-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-work-order" data-toggle="pill" href="#tab-wo" role="tab">
                            <i class="fa fa-bus"></i>
                            Work Order List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" hidden="hidden" id="tab-pk-tab" data-toggle="pill" href="#tab-pk"
                            role="tab">
                            <i class="fas fa-luggage-cart"></i>
                            Work Order Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" hidden="hidden" id="tab-start-tab" data-toggle="pill" href="#tab-start"
                            role="tab">
                            <i class="fas fa-retweet"></i>
                            Detail Wo Process</a>
                    </li>

                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">

                    <div class="tab-pane fade show active" id="tab-wo" role="tabpanel" aria-labelledby="tab-work-order">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tabel-appointment" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No WO</th>
                                        <th>SA</th>
                                        <th>Customer</th>
                                        <th>Complain</th>
                                        <th>Vin</th>
                                        <th>LcPlate</th>
                                        <th>VcType</th>
                                        <th>Storing</th>
                                        <th>Status</th>
                                        <th>DateStart</th>
                                        <th>C.In</th>
                                        <th>Work Order</th>
                                        <th>User</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="tempat-modal"></div>
                        <div id="cetak-wo-modal"></div>
                    </div>

                    <div class="tab-pane show" id="tab-pk" role="tabpanel" aria-labelledby="tab-pk-tab">

                        <div class="card-body">
                            <div class="col-md-12">

                                <div id="data-proses-pk"></div>

                                <div id="modal-pk"></div>
                            </div>
                            <div class="col-md-6">

                                <div id="data-pk-mulai"></div>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane show" id="tab-start" role="tabpanel" aria-labelledby="tab-start-tab">

                        <div class="card-body">
                            <div class="col-md-12">
                                <div id="data-start-work"></div>

                                <div id="data-proses-start"></div>

                                <div id="modal-pk"></div>
                            </div>
                            <div class="col-md-6">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
show_my_confirm('hapusOperation', 'hapus-operation', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('finishWorkOrder', 'hapusWorkOder', 'Selesaikan Pekerjaan Ini?', 'Ya, Selesaikan', 'Batal');
?>

<script type="text/javascript">
function fn(o) {
    o.value = o.value.toUpperCase().replace(/([^0-9(),-/])/g, '');
}
$('#date_open_wo,#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
$(function() {
    $('#timepicker').datetimepicker({
        format: 'LT'
    })
})
$(document).ready(function() {

    //datatables
    table = $("#tabel-appointment").DataTable({

        "responsive": true,
        "paging": true,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,

        "language": {
            "sEmptyTable": "Data Service Appointment Belum Ada"
        },
        "processing": true, //Feature control the processing indicator.
        "serverSide": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('WorkOrder/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 12], //first column / numbering column
            "orderable": false,
        }, ],

    })

});

//cari operation

//end cari operation
$(document).on('submit', '#form-work-order', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('WorkOrder/inputWorkOrder'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            //table.ajax.reload();
            if (out.status == 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                document.getElementById('tab-work-order').click();
                document.getElementById("tab-pk-tab").hidden = true;
                document.getElementById("tab-work-order").active = true;
                $('#tabel-appointment').DataTable().ajax.reload();
                $('.msg').html(out.msg);
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })

                //window.location.reload() ;
            }
        })

    e.preventDefault();
});

$('#tambah-appointment').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-appointment').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})
$(document).on("click", ".delete-operation", function() {
    idS = $(this).attr("data-id");
})
$(document).on("click", ".delete-operation", function() {
    var id = idS;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('WorkOrder/deleteOperation'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            table.ajax.reload();
            $('.msg').html(out.msg);
            $('#hapusOperation').modal('hide');
            tampilKeterangan()
            if (out.status != 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 500
                })
            }
        })
})

function showOperationForm() {
    document.getElementById("operation-body").hidden = false;
    panggilTabel();
    //document.getElementById("alamat").readonly = true;
}

function insertOperation() {
    var wo_no = document.getElementById('wo_no').value;
    var operation = document.getElementById('operation').value;
    var hours = document.getElementById('hours').value;
    var type_of_work = document.getElementById('type_of_work').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('WorkOrder/tambahXot'); ?>',
        data: {
            'wo_no': wo_no,
            'operation': operation,
            'hours': hours,
            'type_of_work': type_of_work
        },
        success: function(hasil) {
            tampilKeterangan();

            //$('#tabel-operation').DataTable().ajax.reload();
            //$('#tabel-operation').DataTable();
            //document.getElementById("operation-body").hidden = true;
            //$('.nav-tabs a[href="#tab-wo"]').tab('show');
            //window.location.reload() ;
            operation = document.getElementById('operation').value = '';
            hours = document.getElementById('hours').value = '';
            type_of_work = document.getElementById('type_of_work').value = '';
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Data Berhasil Ditambahkan',
                showConfirmButton: false,
                timer: 500
            })
        }
    });
}


function insertMechanic() {
    var wo_no = document.getElementById('wo_no').value;
    var nik = document.getElementById('nik').value;
    var nama = document.getElementById('nama').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('WorkOrder/tambahLabor'); ?>',
        data: {
            'wo_no': wo_no,
            'nik': nik,
            'nama': nama
        },
        success: function(hasil) {
            tampilMechanic();
            nik = document.getElementById('nik').value = '';
            nama = document.getElementById('nama').value = '';
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Data Berhasil Ditambahkan',
                showConfirmButton: false,
                timer: 500
            })
        }
    });
}

function tampilKeterangan() {
    var wo_no = document.getElementById('wo_no').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('WorkOrder/tampilOperationDetail'); ?>',
        data: 'wo_no=' + wo_no,
        success: function(hasil) {
            //tableKeterangan.fnDestroy();
            $('#data-detail-wo').html(hasil);
        }
    });
}



function tampilLabor() {
    var wo_no = document.getElementById('wo_no').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('WorkOrder/tampilLabor'); ?>',
        data: 'wo_no=' + wo_no,
        success: function(hasil) {
            //tableKeterangan.fnDestroy();
            $('#data-detail-labor').html(hasil);
        }
    });
}


$(document).on("click", ".add-mechanic", function() {
    var id = $(this).attr("data-id");

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('WorkOrder/tampilLabor'); ?>',
        data: 'id=' + id,
        success: function(hasil) {
            $('#data-proses-mechanic').html(hasil);
            tampilMechanic();
            //refresh();
        }
    });
})

function cetakPo(datakode) {}


$(document).on("click", ".cetak-work-order", function() {
    var id = $(this).attr("data-id");
    //var id = document.getElementById('next_proses').value=datakode;
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('WorkOrder/cetak_work_order'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#cetak-wo-modal').html(data);
            $('#cetak-work-order').modal('show');
        })
})

function selectPart3(id_barang, no_part, nama_part, stok, stok_a, stok_p, hrg_awal) {
    var no_body = document.formKeluar.no_body.value;
    var id_pk = document.formKeluar.id_pk.value;
    var id_lapor = document.formKeluar.id_lapor.value;
    var status_part = document.formKeluar.status_part.value;
    var jumlah = document.formKeluar.jumlah.value;
    var user = document.formKeluar.user.value;
    $.ajax({
        method: 'POST',
        url: '<?php echo base_url('PartPk/prosesKeluar'); ?>',
        data: "&id_pk=" + id_pk +
            "&id_lapor=" + id_lapor +
            "&no_body=" + no_body +
            "&id_barang=" + id_barang +
            "&no_part=" + no_part +
            "&nama_part=" + nama_part +
            "&status_part=" + status_part +
            "&stok=" + stok +
            "&stok_a=" + stok_a +
            "&stok_p=" + stok_p +
            "&hrg_awal=" + hrg_awal +
            "&jumlah=" + jumlah +
            "&user=" + user
    })
    showDetail(id_pk);
    $('#modal_form').modal('hide');
}
$(document).on("click", ".process-work-order", function() {
    var id = $(this).attr("data-id");

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('WorkOrder/processWorkOrder'); ?>',
        data: 'id=' + id,
        success: function(hasil) {
            //$('#id_lapor').val(id);
            //MyTable.fnDestroy();

            //$('#tabel-operation').DataTable();
            $('#data-proses-pk').html(hasil);
            document.getElementById("tab-pk-tab").hidden = false;
            $("a[href='#tab-pk']").tab('show');
            panggilTabel();
            tampilKeterangan();
            //tampilLabor();
            //refresh();
        }
    });
})
//$(document).ready(function() {
function panggilTabel() {
    //datatables
    table = $("#tabel-operation").DataTable({

        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "processing": true,
        "serverSide": true,
        "pageLength": 5,
        "autoWidth": false,


        "language": {
            "sEmptyTable": "Data Service Appointment Belum Ada"
        },
        "processing": true, //Feature control the processing indicator.
        "serverSide": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('WorkOrder/list_operation') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 3], //first column / numbering column
            "orderable": false,
        }, ],

    })
    $('#tabel-operation tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var code = data[1];
        var hours = data[2];
        var operation = data[3];

        document.getElementById('operation').value = code;
        document.getElementById('hours').value = hours;
        document.getElementById('type_of_work').value = operation;
        $('#modal-operation').modal('hide');


        //e.preventDefault();
        //showDetail(id_pk);
        //showDetail(id_pk);
    });

};

//proses start work order
$(document).on("click", ".process-start", function() {
    var id = $(this).attr("data-id");

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('WorkOrder/processStartWorkOrder'); ?>',
        data: 'id=' + id,
        success: function(hasil) {
            //$('#id_lapor').val(id);
            //MyTable.fnDestroy();

            //$('#tabel-operation').DataTable();
            $('#data-start-work').html(hasil);
            document.getElementById("tab-start-tab").hidden = false;
            $("a[href='#tab-start']").tab('show');
            panggilTabel();
            tampilPekerjaan();
            //tampilLabor();
            //refresh();
        }
    });
})
function tampilPekerjaan() {
    var wo_no = document.getElementById('wo_no').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('WorkOrder/tampilStartDetail'); ?>',
        data: 'wo_no=' + wo_no,
        success: function(hasil) {
            //tableKeterangan.fnDestroy();
            $('#data-proses-start').html(hasil);
        }
    });
}
$(document).on("click", ".hapus-work-order", function() {
    var id = $(this).attr("data-id");
            $('#finishWorkOrder').modal('show');
$(document).on("click", ".hapusWorkOder", function() {
    var wo_no = id;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('WorkOrder/finishWorkOrder'); ?>",
            data: "wo_no=" + wo_no
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            $('#tabel-appointment').DataTable().ajax.reload();
            $('.msg').html(out.msg);
            $('#finishWorkOrder').modal('hide');
            if (out.status != 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1200
                })
            }
        })
})
})

</script>