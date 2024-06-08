<style>
.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 12px;
}

table.dataTable td {
    padding: 5px;
}

.bootstrap-datetimepicker-widget .datepicker-days table tbody tr:hover {
    background-color: #eee;
}

.datepicker .datepicker-days .table-condensed tr:hover {
    background-color: rgb(52, 174, 235);
    padding: 25px;
    box-shadow: 0 2.2px 2.2px rgba(0, 0, 0, 0.068),
        0 5.3px 5.3px rgba(0, 0, 0, 0.093), 0 10px 10px rgba(0, 0, 0, 0.103),
        0 17.9px 17.9px rgba(0, 0, 0, 0.11), 0 33.4px 33.4px rgba(0, 0, 0, 0.115),
        0 80px 80px rgba(0, 0, 0, 0.12);
    border-radius: 50%;
}

.datepicker .datepicker-days tr td.active~td,
.datepicker .datepicker-days tr td.active {
    color: #fff;
    background-color: #04c;
    border-radius: 50%;
    box-shadow: 0 2.2px 2.2px rgba(0, 0, 0, 0.068),
        0 5.3px 5.3px rgba(0, 0, 0, 0.093), 0 10px 10px rgba(0, 0, 0, 0.103),
        0 17.9px 17.9px rgba(0, 0, 0, 0.11), 0 33.4px 33.4px rgba(0, 0, 0, 0.115),
        0 80px 80px rgba(0, 0, 0, 0.12) !important;
}

.datepicker td.highlight {
    background: #357ebd;
    cursor: pointer;
}

.datepicker tbody tr.active td.cw {
    color: #357ebd !important;
    background-color: #f2f2f2 !important;
    font-weight: bold;

}

.datepicker .cw,
.datepicker .cw:hover {
    background-color: #f2f2f2 !important;
    font-weight: bold;
    font-size: medium;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}
</style>
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">-->
<div class="row">
    <div class="col-12 ">
        <div class="content">
            <div class="card">
                <div class="modal-content">
                    <div class="card-header card-blue card-outline">
                        <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Data Penggunaan Barang Dengan SPK
                        </h3>
                    </div>


                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label">Data Mingguan</label>
                            <div class="col-sm-3">
                                <div class="input-group tanggal" id="reservationdate" data-target-input="nearest">
                                    <input type="text" name="tgl_awal" id="tanggal" class="form-control datepicker tanggal"
                                        data-toggle="tanggal" data-target=".tanggal" data-format="dd-mm-yyy"
                                        required >

                                    <div class="input-group-append date" data-toggle="tanggal">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-sm-1">
                                <button class="btn bg-gradient-primary col-sm-12" onclick="listPart()"
                                    type="submit"><span class="fa fa-search"></span> Cari</button>
                            </div>
                            <!--<div class="col-sm-1">
                                <button class="btn bg-gradient-info col-sm-12 " onclick="cetakPk()" type="submit"><span
                                        class="fa fa-print"></span> Cetak</button>-->
                            </div>
                        </div>
                                    <div id="data-pk">
                                    </div>
                        <div id="modal-pk"></div>
                        <div id="modal-estimasi"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script type="text/javascript">
    $('#tanggal').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        forceParse: false,
        language: "id",
        //weeks: true,
        //calendarWeeks: true,
        orientation: "auto",
        todayHighlight: true,
        toggleActive: true,
        singleDatePicker: true, //to make one click and one calendar
        //weekStart:0,

    }).on('changeDate', function(e) {
        var value = $("#tanggal").val();
        var firstDate = moment(value, "DD-MM-YYYY").day(0).format("DD-MM-YYYY");
        var lastDate = moment(value, "DD-MM-YYYY").day(6).format("DD-MM-YYYY");
        //var week = moment(value, "DD-MM-YYYY").year(1).format("[Minggu ke] W => ");
        $("#tanggal").val(firstDate + " / " + lastDate);
    });





    function showPk() {
        $.get('<?php echo base_url('PartPk/showPk'); ?>', function(data) {
            MyTable.fnDestroy();
            $('#data-pk').html(data);
            refresh();
        });
    }

    $(document).on("click", ".cetak-pk", function() {
        var id = $(this).attr("data-id");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ReportAccSpkPerminggu/cetakPk'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-pk').html(data);
                $('#cetak-pk').modal('show');
            })
    })
    $(document).on("click", ".list-pk", function() {
        var date1 = document.getElementById("tanggal").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ReportAccSpkPerminggu/listPk'); ?>?date1' + date1,
            data: 'date1=' + date1,
            success: function(hasil) {
                //MyTable.fnDestroy();
                $('#data-pk').html(hasil);
                //refresh();
            }
        });
    })
    
    $(document).on("click", ".cetak-list-pk", function() {
        var date1 = document.getElementById("tanggal").value;
        $.ajax({
                method: "POST",
            url: '<?php echo base_url('ReportAccSpkPerminggu/CetaklistPk'); ?>?date1' + date1,
            data: 'date1=' + date1,
            })
            .done(function(data) {
                $('#modal-pk').html(data);
                $('#cetak-list-pk-data').modal('show');
            })
    })
    
    $(document).on("click", ".detail-pk", function() {
        var date1 = document.getElementById("tanggal").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ReportAccSpkPerminggu/listKeluarDetail'); ?>?date1' + date1,
            data: 'date1=' + date1,
            success: function(hasil) {
                //MyTable.fnDestroy();
                $('#data-pk').html(hasil);
            }
        });
    })
    $(document).on("click", ".cetak-detail-pk", function() {
        var date1 = document.getElementById("tanggal").value;
        $.ajax({
                method: "POST",
            url: '<?php echo base_url('ReportAccSpkPerminggu/cetak_listKeluarDetail'); ?>?date1' + date1,
            data: 'date1=' + date1,
            })
            .done(function(data) {
                $('#modal-pk').html(data);
                $('#cetak-detail-pk').modal('show');
            })
    })
    //end setoran
    // Laporan Po
    function listPart() {
        var date1 = document.getElementById("tanggal").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ReportAccSpkPerminggu/listPk'); ?>?date1' + date1,
            data: 'date1=' + date1,
            success: function(hasil) {
                //MyTable.fnDestroy();
                $('#data-pk').html(hasil);
                //refresh();
            }
        });
    }
    
    function detailPart() {
        var date1 = document.getElementById("tanggal").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ReportAccSpkPerminggu/listKeluarDetail'); ?>?date1' + date1,
            data: 'date1=' + date1,
            success: function(hasil) {
                //MyTable.fnDestroy();
                $('#data-pk').html(hasil);
                //refresh();
            }
        });
    }
    $(document).on("click", ".cetak-estimasi", function() {
        var id = $(this).attr("data-id");

        //var id = document.getElementById('next_proses').value=datakode;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ReportAccSpkPerminggu/cetakEstimasi'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-estimasi').html(data);
                $('#cetak-estimasi').modal('show');
            })
    })
    </script>