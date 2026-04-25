<style>
#modal-kotak {
    margin: 5% 30% 30% 30%;
    width: 500px;
    height: 200px;
    position: absolute;
    position: fixed;
    z-index: 1002;
    display: none;
    background: white;
}

.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 10px;
}

table.dataTable td {
    padding-bottom: 5px;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> &nbsp; List Data </h3>
                    </div>
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
                                    <th>Pre Order</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="tempat-modal"></div>
                    <div id="cetak-part"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$(document).ready(function() {

    // $('#tempat-modal').on('shown.bs.modal', function () {
    //   dataPart();
    //});

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
            "url": "<?php echo site_url('PartRequest/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 12], //first column / numbering column
            "orderable": false,
        }, ],

    })

});
$(document).on("click", ".process-part", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('PartRequest/processPart_request'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#tempat-modal').html(data);
            $('#process-part-request').modal('show');
            showPartRequestDetail();
        })
})

function dataPart() {
    table = $("#tabel-part-request").DataTable({

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
            "url": "<?php echo site_url('PartRequest/list_parts') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 2], //first column / numbering column
            "orderable": false,
        }, ],

    })
    $('#tabel-part-request tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var no_part = data[1];
        var nama_part = data[2];
        var harga = data[3];

        document.getElementById('no_part').value = no_part;
        document.getElementById('nama_part').value = nama_part;
        document.getElementById('harga').value = harga;
        $('#modal-part').modal('hide');
    });

};

function insertRequest() {
    var wo_no = document.getElementById('wo_no').value;
    var no_part = document.getElementById('no_part').value;
    var nama_part = document.getElementById('nama_part').value;
    var harga = document.getElementById('harga').value;
    var jumlah = document.getElementById('jumlah').value;
    var keterangan = document.getElementById('keterangan').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('PartRequest/tambahPart'); ?>',
        data: {
            'wo_no': wo_no,
            'no_part': no_part,
            'nama_part': nama_part,
            'harga': harga,
            'jumlah': jumlah,
            'keterangan': keterangan
        },
        success: function(hasil) {
            $('#tabel-part-request').DataTable().destroy();
            showPartRequestDetail();
           // $('#modal-part').modal('hide');
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Data Berhasil Ditambahkan',
                showConfirmButton: false,
                timer: 500
            });
            no_part = document.getElementById('no_part').value = '';
            nama_part = document.getElementById('nama_part').value = '';
            harga = document.getElementById('harga').value = '';
            jumlah = document.getElementById('jumlah').value = '';
            keterangan = document.getElementById('keterangan').value = '';
            //dataPart();
        }
    });
}

$(document).on('submit', '#form-part-request', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('PartRequest/inputPartRequest'); ?>',
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
                //document.getElementById("process-pre-order").reset();
                $('#process-part-request').modal('hide');
                //table.ajax.reload();
                $('#tabel-appointment').DataTable().ajax.reload();
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

function showPartRequestDetail() {
    var wo_no = document.getElementById('wo_no').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('PartRequest/tampilRequestDetail'); ?>',
        data: 'wo_no=' + wo_no,
        success: function(hasil) {
            //tableKeterangan.fnDestroy();
            $('#data-detail-request').html(hasil);
        }
    });
}
$(document).on("click", ".delete-request", function() {
    idS = $(this).attr("data-id");
})
$(document).on("click", ".delete-request", function() {
    var id = idS;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('PartRequest/deleteRequest'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            table.ajax.reload();
            $('.msg').html(out.msg);
            showPartRequestDetail();
        })
})
$(document).on("click", ".cetak-part-request", function() {
    var id = $(this).attr("data-id");
    //var id = document.getElementById('next_proses').value=datakode;
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('PartRequest/cetak_part_request'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#cetak-part').html(data);
            $('#cetak-part-request').modal('show');
        })
})
</script>