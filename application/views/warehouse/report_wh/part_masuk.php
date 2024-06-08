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
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Data Barang Masuk</h3>
                                </div>
                                
                        
                    <div class="card-body">
                    <div class="form-group row">
									<label class="col-sm-1 col-form-label">Status</label>
									<div class="col-sm-3">
									<select name="status_po" id="status_po" class="form-control" required>
                                                            <option value="">Pilih status...</option>
                                                            <option value="Y">PO</option>
                                                            <option value="N">Non PO</option>
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
							<button class="btn bg-gradient-primary col-sm-12" onclick="listMasuk()" type="submit"><span class="fa fa-search"></span> Cari</button>
								</div>
									<div class="col-sm-1">
    <button type="button" class="btn bg-gradient-green shadow mb-3 rounded cetak-masuk-global" id="cetakGlobal" hidden="hidden" ><i class="fa fa-print"></i>  &nbsp;Cetak</button>
								</div>
                                
						</div>
            </div>
                        <div id="data-masuk"></div>
                            </div>
                                <div id="modal-non"></div>
                                <div id="modal-masuk"></div>
                                <div id="modal-cetak-global"></div>
                                <div id="modal-cetak-detail"></div>
                        </div>
                    </div>
                </div>


                <?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data?', 'Ya, Hapus', 'Batal'); ?>
<script type="text/javascript">
    $('#tgl_awal,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});

    function refresh() {
        MyTable = $('#list-data,#list-data1').dataTable();
    }
    var MyTable = $('#list-data,#list-data1').dataTable({
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

    $(document).on("click", ".cetak-masuk", function() {
		var id = $(this).attr("data-id");
		//var id = document.getElementById('next_proses').value=datakode;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('Part_masuk/cetak'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				$('#modal-masuk').html(data);
				$('#cetak-masuk').modal('show');
			})
	})

    $(document).on("click", ".cetak-masuknon", function() {
		var id = $(this).attr("data-id");
		//var id = document.getElementById('next_proses').value=datakode;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('Part_masuk_npo/cetak'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				$('#modal-non').html(data);
				$('#cetak-masuknpo').modal('show');
			})
	})
    $(document).on("click", ".delete-detail", function() {
		data_id = $(this).attr("data-id");
		data_status = $(this).attr("data-status");
	})
	$(document).on("click", ".hapus-detail", function() {
		var id = data_id;
		var status = data_status;

		$.ajax({
				method: "POST",
				url: "<?php echo base_url('ReportWhMasuk/deleteData'); ?>",
				data:
                "id=" + id +
                "&status=" + status
			})
			.done(function(data) {
				var out = jQuery.parseJSON(data);
				if (out.status != 'form') {
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: out.msg,
						showConfirmButton: false,
						timer: 1500
					})
					$('#hapusDetail').modal('hide');
					//var id_po = document.formPo.id_po.value;
					//next(next_proses);
					listMasuk();
				}
			})
	})


// Global masuk
	
$(document).on("click", ".list-barang-ppu", function() {
    var status_po = document.getElementById("status_po").value;
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
    var status = "PPU";
    
if (status_po == ""){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Status Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
        type: 'GET',
		url: '<?php echo base_url('ReportWhMasuk/listMasukStatus'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
        success: function(hasil) {
			$('#data-masuk').html(hasil);
        }
    });
}
})
$(document).on("click", ".list-barang-mpu", function() {
    var status_po = document.getElementById("status_po").value;
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
    var status = "MPU";
    
if (status_po == ""){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Status Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
        type: 'GET',
		url: '<?php echo base_url('ReportWhMasuk/listMasukStatus'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
        success: function(hasil) {
			$('#data-masuk').html(hasil);
        }
    });
}
})

    // Global Dengan PO	

 // Global Non PO	
 $(document).on("click", ".list-barang-npo", function() {
    var status_po = document.getElementById("status_po").value;
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
    
if (status_po == ""){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Status Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
        type: 'GET',
		url: '<?php echo base_url('ReportWhMasuk/listMasukNpo'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po,
        success: function(hasil) {
			$('#data-masuk').html(hasil);
        }
    });
}
})
//** Format Cetak tanpa sub bagian */
$(document).on("click", ".cetak-masuk-global", function() {
    
    var status_po = document.getElementById("status_po").value;
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
    
if (status_po == ""){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Status Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('ReportWhMasuk/listMasukCetak'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po,
        success: function(hasil) {
                $('#modal-cetak-global').html(hasil);
                $('#cetak-masuk').modal('show');
        }
    });
}
})
//** Format Cetak dengan sub bagian */
$(document).on("click", ".cetak-masuk-ppu", function() {
    
    var status_po = document.getElementById("status_po").value;
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
    var status = "PPU";
    
if (status_po == ""){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Status Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
        type: 'GET',
		url: '<?php echo base_url('ReportWhMasuk/listMasukStatusCetak'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po,
        success: function(hasil) {
                $('#modal-cetak-global').html(hasil);
                $('#cetak-masuk').modal('show');
        }
    });
}
})
$(document).on("click", ".cetak-masuk-mpu", function() {
    
    var status_po = document.getElementById("status_po").value;
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
    var status = "MPU";
    
if (status_po == ""){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Status Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
        type: 'GET',
		url: '<?php echo base_url('ReportWhMasuk/listMasukStatusCetak'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po,
        success: function(hasil) {
                $('#modal-cetak-global').html(hasil);
                $('#cetak-masuk').modal('show');
        }
    });
}
})
// Global Detail Masuk
	
$(document).on("click", ".list-detail-barang", function() {
    var status_po = document.getElementById("status_po").value;
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
	var status = $(this).attr("data-status");
    
if (status_po == ""){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Status Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
        type: 'GET',
		url: '<?php echo base_url('ReportWhMasuk/listDetailMasuk'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
        success: function(hasil) {
			$('#data-masuk').html(hasil);
        }
    });
}
})
//** Cetak Detail Barang Masuk */
$(document).on("click", ".cetak-masuk-detail", function() {
    
    var status_po = document.getElementById("status_po").value;
    var date1 = document.getElementById("tgl_awal").value;
    var date2 = document.getElementById("tgl_akhir").value;
	var status = $(this).attr("data-status");
    
if (status_po == ""){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Status Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
        type: 'GET',
		url: '<?php echo base_url('ReportWhMasuk/CetakDetailMasuk'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po+'&status=' +status,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po,
        success: function(hasil) {
                $('#modal-cetak-detail').html(hasil);
                $('#cetak-masuk-detail').modal('show');
        }
    });
}
})

    function listMasuk() {//
		var status_po = document.getElementById("status_po").value;
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
        if (status_po == ""){
        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Status Belum ditentukan',
                            showConfirmButton: false,
                            timer: 900
                        })
        } else{
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportWhMasuk/listMasuk'); ?>?date1'+date1+'&date2=' +date2+'&status_po=' +status_po,
		data: 'date1=' +date1+'&date2=' +date2+'&status_po=' +status_po,
			success:
            function(hasil) {
                document.getElementById("cetakGlobal").hidden = false;
			//MyTable.fnDestroy();
			$('#data-masuk').html(hasil);
			//refresh();
			}
		});
	}}
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