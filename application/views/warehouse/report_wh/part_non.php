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
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Barang Keluar Dengan PK Atau Non PK</h3>
                                </div>
                                
                        
                    <div class="card-body">
								<div class="form-group row">
									<label class="col-sm-1 col-form-label">Status PK</label>
									<div class="col-sm-2">
                    <select name="kategori" id="kategori" class="form-control">
                                                            <option value="">Pilih status.
                                                            </option>
                                                            <option value="pk">PK</option>
                                                            <option value="non">Non PK</option>
                                                        </select>
											</div>
											</div>

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
							<button class="btn bg-gradient-primary col-sm-12" onclick="listPart()" type="submit"><span class="fa fa-search"></span> Cari</button>
								</div>
                                <div class="col-sm-1">
							<button class="btn bg-gradient-info col-sm-12 " type="submit"><span class="fa fa-print"></span> Cetak</button>
								</div>
						</div>
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pk">
                                            <thead>
                                                <tr>
                                                    <th width='5%'>No</th>
                                                    <th>PKB</th>
                                                    <th>Tanggal</th>
                                                    <th>Status</th>
                                                    <th>No Body</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-part">
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-part"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?php show_my_confirm('hapusPart', 'hapus-part', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>
<script type="text/javascript">
    $('#tgl_awal,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});

    function refresh() {
        MyTable = $('#list-pk').dataTable();
    }
    var MyTable = $('#list-pk').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 5
    });

    function showPk() {
        $.get('<?php echo base_url('PartPk/showPk'); ?>', function(data) {
            MyTable.fnDestroy();
            $('#data-pk').html(data);
            refresh();
        });
    }


$(document).on("click", ".cetak-part", function() {
	var id = $(this).attr("data-id");
	//var id = document.getElementById('next_proses').value=datakode;
	$.ajax({
			method: "POST",
			url: "<?php echo base_url('Part_keluar/cetak'); ?>",
			data: "id=" + id
		})
		.done(function(data) {
			$('#modal-part').html(data);
			$('#cetak-keluar').modal('show');
		})
})
    //end setoran
    // Laporan Po
    function listPart() {
		var kategori = document.getElementById("kategori").value;
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportWhPartpk/listPart'); ?>?date1'+date1+'&date2=' +date2+'&kategori=' +kategori,
		data: 'date1=' +date1+'&date2=' +date2+'&kategori=' +kategori,
			success:
            function(hasil) {
			MyTable.fnDestroy();
			$('#data-part').html(hasil);
			refresh();
			}
		});
	}
function showDetail() {
var id_keluar = document.getElementById('id_keluar').value;
	//var id_keluar = document.formKeluar.id_keluar.value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('PartPk/showDetail'); ?>?id_keluar=' + id_keluar,
            data: 'id_keluar=' + id_keluar,
            success: function (hasil) {
            MyTable.fnDestroy();
                $('#detail-partpk').html(hasil);
            refresh();
            }
        });

    }
$(document).on("click", ".delete-part", function() {
		data_id = $(this).attr("data-id");
	})
	$(document).on("click", ".hapus-part", function() {
		var id = data_id;

		$.ajax({
				method: "POST",
				url: "<?php echo base_url('ReportWhPartpk/deletePart'); ?>",
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
					$('#hapusPart').modal('hide');
					listPart();
				}
			})
	})
</script>