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
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Daftar Pembayaran</h3>
                                </div>
                                
                        
                    <div class="card-body">
                    <div class="form-group row">
									<label class="col-sm-1 col-form-label">No Body</label>
									<div class="col-sm-2">
										
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
											<input type="text" name="no_body" id="no_body" placeholder="No Body Dapat Kosongkan" class="form-control">
										</div>
									</div>
                        <label class="col-sm-1 col-form-label">No SPK</label>
									<div class="col-sm-2">
										
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
											<input type="text" name="no_pk" id="no_pk" placeholder="Surat Perintah Kerja" class="form-control">
										</div>
									</div>
									<div class="col-sm-1">
							<button class="btn bg-gradient-primary col-sm-12" onclick="listPart()" type="submit"><span class="fa fa-search"></span> Cari</button>
									</div>
									<div class="col-sm-1">
							<button class="btn bg-gradient-dark col-sm-12" onclick="detailPart()" type="submit"><span class="fa fa-search"></span> Detail</button>
								</div>
									</div>
								<div class="form-group row">
									<label class="col-sm-1 col-form-label">Tanggal Awal</label>
									<div class="col-sm-2">
										<div class="input-group date" id="reservationdate" data-target-input="nearest">

											<input type="text" name="tgl_awal" id="tgl_awal" class="form-control tgl_awal datetimepicker" data-toggle="datetimepicker" data-target=".tgl_awal" data-format="yyy-mm-dd" required>

											<div class="input-group-append" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
                                    <label class="col-sm-1 col-form-label">Tanggal Akhir</label>
									<div class="col-sm-2">
										<div class="input-group date" id="reservationdate" data-target-input="nearest">

											<input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control tgl_akhir datetimepicker" data-toggle="datetimepicker" data-target=".tgl_akhir" data-format="yyy-mm-dd" required>

											<div class="input-group-append" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
                                <div class="col-sm-1">
							<button class="btn bg-gradient-success col-sm-12 " onclick="cetakPk()" type="submit"><span class="fa fa-print"></span> Cetak</button>
								</div>
						</div>
                                <div id="modal-pk"></div>
                          <div id="data-pk">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?php
show_my_confirm('startPk', 'start-pk', 'PK Dimulai', 'Ya, Mulai', 'Batal Mulai');
show_my_confirm('selesaiPk', 'selesai-pk', 'PK Telah Selesai?', 'Ya, Selesai', 'Batal');
?>
<script type="text/javascript">
    $('#tgl_awal,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});
    function refresh() {
        MyTable = $('#list-data').dataTable();
    }

    function showPk() {
        $.get('<?php echo base_url('PartPk/showPk'); ?>', function(data) {
            MyTable.fnDestroy();
            $('#data-pk').html(data);
            refresh();
        });
    }

    function listPart() {
		var no_body = document.getElementById("no_body").value;
		var no_pk = document.getElementById("no_pk").value;
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportUpahBorong/listBayar'); ?>?date1'+date1+'&date2=' +date2+'&no_body=' +no_body,
		data: 'date1=' +date1+'&date2=' +date2+'&no_body=' +no_body+'&no_pk=' +no_pk,
			success:
            function(hasil) {
			//MyTable.fnDestroy();
			$('#data-pk').html(hasil);
			//refresh();
			}
		});
	}
	function detailPart() {
		var no_body = document.getElementById("no_body").value;
		var no_pk = document.getElementById("no_pk").value;
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportUpahBorong/listBayarDetail'); ?>?date1'+date1+'&date2=' +date2+'&no_body=' +no_body,
		data: 'date1=' +date1+'&date2=' +date2+'&no_body=' +no_body+'&no_pk=' +no_pk,
			success:
            function(hasil) {
			//MyTable.fnDestroy();
			$('#data-pk').html(hasil);
			//refresh();
			}
		});
	}

	function cetakPk() {
		var no_body = document.getElementById("no_body").value;
		var no_pk = document.getElementById("no_pk").value;
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportUpahBorong/cetakBayar'); ?>?date1'+date1+'&date2=' +date2+'&no_body=' +no_body+'&no_pk=' +no_pk,
		data: 'date1=' +date1+'&date2=' +date2+'&no_body=' +no_body+'&no_pk=' +no_pk,
			success:
            function(hasil) {
				$('#modal-pk').html(hasil);
				$('#cetak-pk').modal('show');
			}
		});
	}

</script>