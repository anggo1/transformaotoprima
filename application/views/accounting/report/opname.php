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
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Riwayat Stok Opname</h3>
                                </div>
                                
                        
                    <div class="card-body">

								<div class="form-group row">
									<label class="col-sm-1 col-form-label">Tanggal Awal</label>
									<div class="col-sm-3">
										<div class="input-group date" id="reservationdate" data-target-input="nearest">

											<input type="text" name="tgl_awal" id="tgl_awal" class="form-control tgl_awal datetimepicker" data-toggle="datetimepicker" data-target=".tgl_awal" data-format="yyy-mm-dd" required>

											<div class="input-group-append" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
                                    <label class="col-sm-1 col-form-label">Tanggal Akhir</label>
									<div class="col-sm-3">
										<div class="input-group date" id="reservationdate" data-target-input="nearest">

											<input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control tgl_akhir datetimepicker" data-toggle="datetimepicker" data-target=".tgl_akhir" data-format="yyy-mm-dd" required>

											<div class="input-group-append" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-1">
							<button class="btn bg-gradient-primary col-sm-12" onclick="listOpname()" type="submit"><span class="fa fa-search"></span> Cari</button>
								</div>
						</div>
                                            <div id="data-opname">
                            </div>
                                <div id="modal-cetak-detail"></div>
                        </div>
                    </div>
                </div>
            </div>

<?php show_my_confirm('hapusOpname', 'hapus-opname', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>
<?php show_my_confirm('generateOpname', 'generate-opname', 'Data yang sudah di generate tidak dapat di batalkan !!', 'Proses', 'Batal'); ?>
<script type="text/javascript">
	
    $('#tgl_awal,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});

    function refresh() {
        MyTable = $('#list-po').dataTable();
    }
    var MyTable = $('#list-po').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 5
    });

    //end setoran
    // Laporan Po
	function listOpnameDetail() {
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccOpname/detailOpname'); ?>?date1'+date1+'&date2=' +date2,
		data: 'date1=' +date1+'&date2=' +date2,
			success:
            function(hasil) {
			MyTable.fnDestroy();
			$('#data-opname').html(hasil);
			refresh();
			}
		});
	}
    function listOpname() {
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccOpname/listOpname'); ?>?date1'+date1+'&date2=' +date2,
		data: 'date1=' +date1+'&date2=' +date2,
			success:
            function(hasil) {
			MyTable.fnDestroy();
			$('#data-opname').html(hasil);
			refresh();
			}
		});
	}
	function cetaklistOpnameDetail() {
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccOpname/cetak_detailOpname'); ?>?date1'+date1+'&date2=' +date2,
		data: 'date1=' +date1+'&date2=' +date2,
        success: function(hasil) {
                $('#modal-cetak-detail').html(hasil);
                $('#cetak-masuk-detail').modal('show');
			}
		});
	}
$(document).on("click", ".delete-opname", function() {
		data_id = $(this).attr("data-id");
	})
	$(document).on("click", ".hapus-opname", function() {
		var id = data_id;

		$.ajax({
				method: "POST",
				url: "<?php echo base_url('ReportAccOpname/deleteOpname'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				var out = jQuery.parseJSON(data);
				if (out.status != 'form') {
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: out.msg,
						showConfirmButton: false,
						timer: 1000
					})
					$('#hapusOpname').modal('hide');
					listOpname();
				}
			})
	})
$(document).on("click", ".generate", function() {
		data_id = $(this).attr("data-id");
	})
	$(document).on("click", ".generate-opname", function() {
		var id = data_id;

		$.ajax({
				method: "POST",
				url: "<?php echo base_url('ReportOpname/generate'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				var out = jQuery.parseJSON(data);
				if (out.status != 'form') {
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: out.msg,
						showConfirmButton: false,
						timer: 1000
					})
					$('#generateOpname').modal('hide');
					listOpname();
				}
			})
	})
</script>