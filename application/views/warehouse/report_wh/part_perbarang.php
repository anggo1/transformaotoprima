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
            <div class="card">
                <div class="modal-content">
                    <div class="card-header card-blue card-outline">
                        <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Data Penggunaan per
                            Barang</h3>
                    </div>


                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label">No Part</label>
                            <div class="col-sm-2">

                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" name="no_part" id="no_part" readonly class="form-control">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-info" data-toggle="modal"
                                            data-target="#modal_form"><i class="glyphicon glyphicon-plus-sign"><i
                                                    class="fa fa-search"></i></button></i>
                                    </span>
                                </div>
                            </div>
                            <label class="col-sm-1 col-form-label">Nama Barang</label>
                            <div class="col-sm-3">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" name="nama_part" id="nama_part" class="form-control" readonly>
                                </div>
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
                            <div class="col-sm-3">
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
                                <button class="btn bg-gradient-primary col-sm-12" onclick="listPart()"
                                    type="submit"><span class="fa fa-search"></span> Cari</button>
                            </div>
                        </div>
                        <div id="data-part"></div>
                        <div class="modal fade" id="modal_form" role="dialog">
                            <div class="modal-dialog modal-xm">
                                <div class="modal-content">
                                    <div class="modal-body form">
                                        <div class="card card-first card-outline">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table width="100%" class="table no-wrap table-hover nowrap"
                                                        id="table-part">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>No Part</th>
                                                                <th>Nama Part</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
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
            "url": "<?php echo site_url('ReportWhPerbarang/ajax_list') ?>",
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
    $('#table-part tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var no_part = data[1];
        var nama_part = data[2];
        $('[name = "no_part"]').val(no_part);
        $('[name = "nama_part"]').val(nama_part);
        $('#modal_form').modal('hide');
    });
});

function selectPart(id_barang) {

    $('[name = "id_barang"]').val(data.id_barang);
    $('[name = "no_part"]').val(data.no_part);
    $('[name = "nama_part"]').val(data.nama_part);

    $('#modal_form').modal('hide');
}


function listPart() {
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
    var no_part = document.getElementById("no_part").value;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('ReportWhPerbarang/listPart'); ?>?date1' + date1 + '&date2=' + date2,
        data: 'date1=' + date1 + '&date2=' + date2 + '&no_part=' + no_part,
        success: function(hasil) {
            $('#data-part').html(hasil);
        }
    });
}
</script>