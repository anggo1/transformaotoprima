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
                        <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Data Barang Keluar
                        </h3>
                    </div>


                    <div class="card-body">

                    <div class="form-group row">
									<label class="col-sm-1 col-form-label">Status</label>
									<div class="col-sm-2">
									<select name="status_po" id="status_po" class="form-control" required>
                                                            <option value="">Pilih status...</option>
                                                            <option value="Y">PK</option>
                                                            <option value="N">Non PK...</option>
                                                            <option value="D">Divisi...</option>
                                                        </select>
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
                            <div class="col-sm-2">
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
                                <button class="btn bg-gradient-primary col-sm-12" onclick="listKeluar()"
                                    type="submit"><span class="fa fa-search"></span> Cari</button>
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                    <div id="data-keluar"></div>
            <div id="modal-keluar"></div>
            <div id="modal-pk"></div>
            <div id="modal-pk-bon"></div>
            <div id="modal-cetak-pk"></div>
            <div id="modal-cetak-detail-pk"></div>
</div>

<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data?', 'Ya, Hapus', 'Batal'); ?>
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
//** Format Cetak */
$(document).on("click", ".cetak-keluar-data", function() {
    
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
        url: '<?php echo base_url('ReportWhKeluar/listKeluarCetak'); ?>?date1' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        data: 'date1=' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        success: function(hasil) {
                $('#modal-cetak-pk').html(hasil);
                $('#cetak-keluar-pk').modal('show');
        }
    });
}
})

//** Format Cetak Detail*/
$(document).on("click", ".cetak-detail-keluar", function() {
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
        url: '<?php echo base_url('ReportWhKeluar/listKeluarDetailCetak'); ?>?date1' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        data: 'date1=' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        success: function(hasil) {
                $('#modal-cetak-detail-pk').html(hasil);
                $('#cetak-keluar-pk-detail').modal('show');
        }
    });
}
	})
//** Format detail */
$(document).on("click", ".detail-barang", function() {
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
        url: '<?php echo base_url('ReportWhKeluar/listKeluarDetail'); ?>?date1' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        data: 'date1=' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        success: function(hasil) {
            $('#data-keluar').html(hasil);
        }
    });
}
})
$(document).on("click", ".list-barang", function() {
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
        url: '<?php echo base_url('ReportWhKeluar/listKeluar'); ?>?date1' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        data: 'date1=' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        success: function(hasil) {
            $('#data-keluar').html(hasil);
        }
    });
}
})

function listKeluar() {
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
        url: '<?php echo base_url('ReportWhKeluar/listKeluar'); ?>?date1' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        data: 'date1=' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        success: function(hasil) {
            $('#data-keluar').html(hasil);
        }
    });
}
}
function listKeluarDetail() {
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
        url: '<?php echo base_url('ReportWhKeluar/listKeluarDetail'); ?>?date1' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        data: 'date1=' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        success: function(hasil) {
            $('#data-keluar').html(hasil);
        }
    });
}
}
function listKeluarCetak() {
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
        url: '<?php echo base_url('ReportWhKeluar/listKeluarCetak'); ?>?date1' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        data: 'date1=' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        success: function(hasil) {
                $('#modal-cetak-pk').html(hasil);
                $('#cetak-keluar-pk').modal('show');
        }
    });
}
}
function listKeluarDetailCetak() {
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
        url: '<?php echo base_url('ReportWhKeluar/listKeluarDetailCetak'); ?>?date1' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        data: 'date1=' + date1 + '&date2=' + date2+ '&status_po=' + status_po,
        success: function(hasil) {
                $('#modal-cetak-detail-pk').html(hasil);
                $('#cetak-keluar-pk-detail').modal('show');
        }
    });
}
}
$(document).on("click", ".delete-detailnon", function() {
		data_id = $(this).attr("data-id");
		data_status = $(this).attr("data-status");
	})
	$(document).on("click", ".hapus-detail", function() {
		var id = data_id;
		var status = data_status;

		$.ajax({
				method: "POST",
				url: "<?php echo base_url('ReportWhKeluar/deleteDatanon'); ?>",
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
						title: 'Deleted',
						showConfirmButton: false,
						timer: 1500
					})
					$('#hapusDetail').modal('hide');
					//var id_po = document.formPo.id_po.value;
					//next(next_proses);
					listKeluar();
				}
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
				url: "<?php echo base_url('ReportWhKeluar/deleteData'); ?>",
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
						title: 'Deleted',
						showConfirmButton: false,
						timer: 1500
					})
					$('#hapusDetail').modal('hide');
					//var id_po = document.formPo.id_po.value;
					//next(next_proses);
					listKeluar();
				}
			})
	})
$(document).on("click", ".cetak-keluar", function() {
        var id = $(this).attr("data-id");
        //var id = document.getElementById('next_proses').value=datakode;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Part_keluar/cetak'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-keluar').html(data);
                $('#cetak-keluar').modal('show');
            })
    })
    $(document).on("click", ".cetak-bon", function() {
        var id = $(this).attr("data-id");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('PartPk/cetakBon'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                // $('#part-pk').modal('hide');
                $('#modal-pk').html(data);
                $('#cetak-bon').modal('show');
            })
    })
    $(document).on("click", ".cetak-bon-keluar", function() {
        var id = $(this).attr("data-id");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Part_keluar/cetak_bon'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                // $('#part-pk').modal('hide');
                $('#modal-pk-bon').html(data);
                $('#cetak-bon-keluar').modal('show');
            })
    })
</script>