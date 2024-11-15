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
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Report Barang Keluar Per Divisi</h3>
                                </div>
                                
                        
                    <div class="card-body">
								<div class="form-group row">
									<label class="col-sm-1 col-form-label">Divisi</label>
									<div class="col-sm-2">
                    <select name="kategori" id="kategori" class="form-control">
                                                            <option value="">Pilih Divisi..
                                                            </option>
                                                            <?php
                                                                    foreach ($dataKategori as $kat) { ?>
                                                                        <option value="<?php echo $kat->id_kategori; ?>">
                                                                            <?php echo $kat->kategori; ?>
                                                                        </option>
                                                                <?php } ?>
                                                        </select>
											</div>
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
							<button class="btn bg-gradient-primary col-sm-12" onclick="listKategori()" type="submit"><span class="fa fa-search"></span> Cari</button>
								</div>
						</div>
                                            <div id="data-kategori">
                                            </div>
                                <div id="modal-po"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?php
show_my_confirm('startPk', 'start-pk', 'PK Dimulai', 'Ya, Mulai', 'Batal Mulai');
?>
<script type="text/javascript">
    $('#tgl_awal,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});

    function refresh() {
        MyTable = $('#list-data').dataTable();
    }
    var MyTable = $('#list-data').dataTable({
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

    $(document).on("click", ".cetak-po", function() {
		var id = $(this).attr("data-id");
		//var id = document.getElementById('next_proses').value=datakode;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('PurchaseOrder/cetak'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				$('#modal-po').html(data);
				$('#cetak-po').modal('show');
			})
	})
    //end setoran
    // Laporan Po
    function listKategori() {
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		var kat = document.getElementById("kategori").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportWhPerkategori/listKategori'); ?>?date1'+date1+'&date2=' +date2,
		data: 'date1=' +date1+'&date2=' +date2+'&kat=' +kat,
			success:
            function(hasil) {
			MyTable.fnDestroy();
			$('#data-kategori').html(hasil);
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

</script>