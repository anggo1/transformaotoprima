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
                        <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Data SPK Pertanggal
                        </h3>
                    </div>


                    <div class="card-body">
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
                            <!--<div class="col-sm-1">
							<button class="btn bg-gradient-info col-sm-12 " onclick="cetakPk()" type="submit"><span class="fa fa-print"></span> Cetak</button>
								</div>-->
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

    <script type="text/javascript">
    $('#tgl_awal,#tgl_akhir').datetimepicker({
        format: 'DD-MM-YYYY',
        date: moment()
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
                url: "<?php echo base_url('ReportBrSpkPertanggal/cetakPk'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-pk').html(data);
                $('#cetak-pk').modal('show');
            })
    })
    //end setoran
    // Laporan Po
    function listPart() {
        var date1 = document.getElementById("tgl_awal").value;
        var date2 = document.getElementById("tgl_akhir").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ReportBrSpkPertanggal/listPk'); ?>?date1' + date1 + '&date2=' + date2,
            data: 'date1=' + date1 + '&date2=' + date2,
            success: function(hasil) {
                $('#data-pk').html(hasil);
            }
        });
    }
    $(document).on("click", ".cetak-estimasi", function() {
        var id = $(this).attr("data-id");

        //var id = document.getElementById('next_proses').value=datakode;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ReportBrSpkPertanggal/cetakEstimasi'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-estimasi').html(data);
                $('#cetak-estimasi').modal('show');
            })
    })
    
    $(document).on("click", ".list-pk", function() {
        var date1 = document.getElementById("tgl_awal").value;
        var date2 = document.getElementById("tgl_akhir").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ReportBrSpkPertanggal/listPk'); ?>?date1' + date1 + '&date2=' + date2,
            data: 'date1=' + date1 + '&date2=' + date2,
            success: function(hasil) {
                //MyTable.fnDestroy();
                $('#data-pk').html(hasil);
            }
        });
    })
    $(document).on("click", ".detail-pk", function() {
        var date1 = document.getElementById("tgl_awal").value;
        var date2 = document.getElementById("tgl_akhir").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ReportBrSpkPertanggal/detail_listPk'); ?>?date1' + date1 + '&date2=' + date2,
            data: 'date1=' + date1 + '&date2=' + date2,
            success: function(hasil) {
                //MyTable.fnDestroy();
                $('#data-pk').html(hasil);
            }
        });
    })
    </script>