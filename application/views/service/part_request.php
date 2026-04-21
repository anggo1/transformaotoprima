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
                    <div id="cetak-pre-modal"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
show_my_confirm('hapusOperation', 'hapus-operation', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>

<script type="text/javascript">
    function onload() {
        dataPart();
}
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
            dataPart();
            $('#tempat-modal').html(data);
            $('#process-part-request').modal('show');
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

        document.getElementById('no_part').value = no_part;
        document.getElementById('nama_part').value = nama_part;
        $('#modal-part').modal('hide');
    });

};
</script>