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
                        <a class="nav-link" hidden="hidden" id="tab-estimasi-tab" data-toggle="pill"
                            href="#tab-estimasi" role="tab">
                            <i class="fas fa-luggage-cart"></i>
                            Proses Estimasi Penawaran</a>
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
                                        <th>DateStart</th>
                                        <th>C.In</th>
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
                    <div class="tab-pane fade" id="tab-estimasi" role="tabpanel" aria-labelledby="tab-estimasi-tab">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-body card-outline">
                                        <div class="modal-body">
                                            <div id="data-proses-estimasi"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</section>
<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data PO Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->
<script type="text/javascript">
$('#modal_operation').on('hidden.bs.modal', function() {
    if ($.fn.DataTable.isDataTable('#tabel-operation')) {
        $('#tabel-operation').DataTable().destroy();
        $('#tabel-operation tbody').empty();
    }
});

function fn(o) {
    o.value = o.value.toUpperCase().replace(/([^0-9(),-/])/g, '');
}
$('#tgl_estimasi_penawaran,#tgl_received,#tgl_regis').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
//var Date = 
$('#tgl_deadline').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
})
$('#clear').click(function() {
    $('#tgl_deadline').data("DateTimePicker").clear()
})

$('#tgl_deadline1').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});


$(document).on("click", ".process-estimasi", function() {
    var id = $(this).attr("data-id");

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('EstimasiPenawaranService/processEstimasi'); ?>',
        data: 'id=' + id,
        success: function(hasil) {
            $('#data-proses-estimasi').html(hasil);
            document.getElementById("tab-estimasi-tab").hidden = false;
            $("a[href='#tab-estimasi']").tab('show');
            //panggilTabel();
            tampilKeterangan();
            //tampilLabor();
            //refresh();
        }
    });
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
            "url": "<?php echo site_url('EstimasiPenawaranService/ajax_estimasi') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 12], //first column / numbering column
            "orderable": false,
        }, ],

    })

});

$(document).ready(function() {
    table = $('#table-part').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 10, // Defaults number of rows to display in table
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('EstimasiPenawaranService/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false,
        }, ]
    });
});

$(document).ready(function() {
    var table = $('#table-part').DataTable();
    var tgl_estimasi_penawaran = document.formPo.tgl_estimasi_penawaran.value;
    var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;

    $('#table-part tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var id_part = data[0];
        var no_part = data[1];
        var nama_part = data[2];
        var satuan = data[3];
        var stok = data[4];
        var harga_baru = data[5];
        var jenis = 'P';
        var tgl_estimasi_penawaran = document.formPo.tgl_estimasi_penawaran.value;
        var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;
        $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaranService/prosesDetailPo'); ?>',
            data: "tgl_estimasi_penawaran=" + tgl_estimasi_penawaran +
                "&id_estimasi_penawaran=" + id_estimasi_penawaran +
                "&id_part=" + id_part +
                "&no_part=" + no_part +
                "&nama_part=" + nama_part +
                "&satuan=" + satuan +
                "&stok=" + stok +
                "&harga_baru=" + harga_baru +
                "&jenis=" + jenis
        })
        tampilDetail();
        document.getElementById("simpan").hidden = false;
        $('#modal_form').modal('hide');
        tampilKeterangan();
    });
});
var MyTable = $('#list-po').DataTable({
    "responsive": true,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true
});

function panggilTabel() {
    //datatables
    table = $("#tabel-operation").DataTable({

        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
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
            "url": "<?php echo site_url('EstimasiPenawaranService/list_operation') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 3], //first column / numbering column
            "orderable": false,
        }, ],

    })

    $('#tabel-operation tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var id_x = data[0];
        var code = data[1];
        var hours = 'Hours';
        var operation = data[3];
        var stok_operation = '0';
        var harga = data[4];
        var jenis = 'S';
        var tgl_estimasi_penawaran = document.formPo.tgl_estimasi_penawaran.value;
        var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;
        $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaranService/prosesDetailPo'); ?>',
            data: "tgl_estimasi_penawaran=" + tgl_estimasi_penawaran +
                "&id_estimasi_penawaran=" + id_estimasi_penawaran +
                "&id_part=" + id_x +
                "&no_part=" + code +
                "&nama_part=" + operation +
                "&satuan=" + hours +
                "&stok=" + stok_operation +
                "&harga_baru=" + harga +
                "&jenis=" + jenis
        })
        tampilDetail();
        document.getElementById("simpan").hidden = false;
        $('#modal_operation').modal('hide');
        tampilKeterangan();


        //e.preventDefault();
        //showDetail(id_pk);
        //showDetail(id_pk);
    });

};
var tableKeterangan = $('#list-keterangan').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false,
    "autoWidth": true,
    "pageLength": 5
});

function selectPart(id_part, no_part, nama_part, satuan, stok, harga_baru) {
    var tgl_estimasi_penawaran = document.formPo.tgl_estimasi_penawaran.value;
    var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;

    $.ajax({
        method: 'POST',
        url: '<?php echo base_url('EstimasiPenawaranService/prosesDetailPo'); ?>',
        data: "tgl_estimasi_penawaran=" + tgl_estimasi_penawaran +
            "&id_estimasi_penawaran=" + id_estimasi_penawaran +
            "&id_part=" + id_part +
            "&no_part=" + no_part +
            "&nama_part=" + nama_part +
            "&satuan=" + satuan +
            "&stok=" + stok +
            "&harga_baru=" + harga_baru
    })

    tampilDetail(id_estimasi_penawaran);

    $('#modal_form').modal('hide');

}

function next(dataPo, dataRef) {
    document.getElementById('id_estimasi_penawaran').value = dataPo;
    document.getElementById('kode_ref').value = dataRef;
    var d = document.getElementById("cetak");
    d.setAttribute('data-id', dataPo);

    document.getElementById("cetak").hidden = false;
    //document.getElementById("alamat").readonly = true;
}

function nextref(dataRef) {
    document.getElementById('kode_ref').value = dataRef;
}

function refresh() {
    MyTable = $('#list-po').dataTable();
}

function tampilDetail() {
    //var out = jQuery.parseJSON(data);
    //var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value = dataPo;
    var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('EstimasiPenawaranService/tampilDetail'); ?>',
        data: 'id_estimasi_penawaran=' + id_estimasi_penawaran,
        success: function(hasil) {
            //MyTable.fnDestroy();
            $('#data-po').html(hasil);
        }
    });
}

function insertNote() {
    var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('EstimasiPenawaranService/tambahNote'); ?>',
        data: 'id=' + id_estimasi_penawaran,
        success: function(hasil) {
            tampilKeterangan()
        }
    });
}

function tampilKeterangan() {
    var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('EstimasiPenawaranService/tampilKeterangan'); ?>',
        data: 'id_estimasi_penawaran=' + id_estimasi_penawaran,
        success: function(hasil) {
            tableKeterangan.fnDestroy();
            $('#data-keterangan').html(hasil);
        }
    });
}

/** Form Keterangan */

$('#form-keterangan').submit(function(e) {
    var data = $(this).serialize();
    var id = document.getElementById('id_estimasi_penawaran').value;

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaranService/tambahKeterangan'); ?>',
            data: data + "&id=" + id
        })
        .done(function(data) {
            tampilKeterangan();
            document.getElementById("form-keterangan").reset();
            $('#tambah-keterangan').modal('hide');
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Mantap',
                showConfirmButton: false,
                timer: 1000
            })
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
/** end Keterangan */
function tampilDetailCache(dataPo) {
    //var out = jQuery.parseJSON(data);
    var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value = dataPo;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('EstimasiPenawaranService/tampilDetailCache'); ?>?id_estimasi_penawaran=' +
            id_estimasi_penawaran,
        data: 'id_estimasi_penawaran=' + id_estimasi_penawaran,
        success: function(hasil) {
            MyTable.fnDestroy();
            $('#data-po-cache').html(hasil);
            refresh();
        }
    });
}
$('#formPo').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaranService/prosesPo'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                //toastr.error(out.msg);
                $('.msg').html(out.msg);
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                $('.msg').html(out.msg);
                $('.dataPo').html(out.dataPo);
                //tampilDetail(out.dataPo)
                document.getElementById("formPo"); //reset()	
                $('#tgl_estimasi_penawaran').attr('readonly', 'readonly');
                $('#top').attr('readonly', 'readonly');
                $('#status').attr('readonly', 'readonly');
                $('#supplier').attr('readonly', 'readonly');
                $('#keterangan').attr('readonly', 'readonly');
                $('#ppn').attr('readonly', 'readonly');

                var d = document.getElementById("cetak");
                d.setAttribute('data-id', out.dataPo);
                document.getElementById("cetak").hidden = false;
                document.getElementById("tambah").hidden = false;
                document.getElementById("tambah-part").hidden = true;
                document.getElementById("data-po").hidden = true;
                document.getElementById("simpan").hidden = true;
                tampilDetailCache(out.dataPo);

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

function cetakPo(datakode) {}


$(document).on("click", ".cetak-po", function() {
    var id = $(this).attr("data-id");
    //var id = document.getElementById('next_proses').value=datakode;
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('EstimasiPenawaranService/cetak'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-po').html(data);
            $('#cetak-po').modal('show');
        })
})

var data_id;
$(document).on("click", ".delete-detail", function() {
    data_id = $(this).attr("data-id");
})
$(document).on("click", ".delete-detail", function() {
    var id = data_id;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('EstimasiPenawaranService/deleteDetail'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);
            if (out.status != 'form') {
                //$('.msg').html(out.msg);
                $('#hapusDetail').modal('hide');
                var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;
                //next(next_proses);
                tampilDetail(id_estimasi_penawaran);
            }
        })
})


$(document).on("click", ".delete-keterangan", function() {
    data_id = $(this).attr("data-id");
})
$(document).on("click", ".delete-keterangan", function() {
    var id = data_id;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('EstimasiPenawaranService/deleteKeterangan'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);
            if (out.status != 'form') {
                //$('.msg').html(out.msg);
                $('#hapusKeterangan').modal('hide');
                var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;
                //next(next_proses);
                tampilKeterangan(id_estimasi_penawaran);
            }
        })
})

function startHitung() {
    interval = setInterval("Hitung()", 10);
}

function Hitung() {

    var a = document.formPo.jumlah.value;
    var b = document.formPo.hrg_awal.value;
    document.formPo.total_harga.value = (a * b);
}

function stopHitung() {
    clearInterval(startHitung);
}

function startDiskon() {
    interval = setInterval("Diskon()", 10);
}

function Diskon() {

    var a = document.formPo.jumlah.value;
    var b = document.formPo.hrg_awal.value;
    var c = document.formPo.diskon.value;
    document.formPo.total_diskon.value = (a * b) * c / 100;
}

function stopDiskon() {
    clearInterval(startDiskon);
}

function startPpn() {
    interval = setInterval("Ppn()", 10);
}
</script>