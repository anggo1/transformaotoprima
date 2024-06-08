<style>
	.table.DataTable {
		font-family: Verdana, Geneva, Tahoma, sans-serif;
		font-size: 12px;
	}

	table.dataTable td {
		padding-bottom: 5px;
	}
</style>
<?php if (!empty($dataPart)) {
	foreach ($dataPart as $part) {
	}
} ?>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="card card-default">
					<!-- /.card-header -->
					<div class="modal-content">
						<div class="modal-header text-blue">

							<h5 style="display:block; text-align:center;"><span class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Barang Keluar</h5>
						</div>
						<div class="modal-body">
							<form id="formKeluar" name="formKeluar" method="POST">

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Tanggal</label>
									<div class="col-sm-4">
										<div class="input-group date" id="reservationdate" data-target-input="nearest">

											<input type="text" name="tgl_keluar" id="tgl_keluar" value="" class="form-control tgl_keluar datetimepicker" data-toggle="datetimepicker" data-target=".tgl_keluar" data-format="yyy-mm-dd" required>

											<div class="input-group-append" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
									<label class="col-sm-2 col-form-label">Tujuan</label>
									<div class="col-sm-4">
										<input type="text" name="tujuan" id="tujuan" class="form-control" placeholder="Tujuan Keluar">
									</div>
								</div>
								<div class="form-group row">
								<label class="col-sm-2 col-form-label">Ket Keluar</label>
									<div class="col-sm-4">
										<input type="text" name="ket_surat" id="ket_surat" class="form-control" placeholder="Keterangan Keluar">
									</div>
								</div>
								<div class="form-group row">
									<label for="No Part" class="col-sm-2 col-form-label">No Part</label>
									<div class="col-sm-4">
										<div class="input-group date" id="reservationdate" data-target-input="nearest">
											<input type="text" name="no_part" id="no_part" readonly class="form-control">
											<span class="input-group-append">
												<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_form"><i class="glyphicon glyphicon-plus-sign"><i class="fa fa-search"></i></button></i>
											</span>
										</div>
									</div>
									<label for="Nama Part" class="col-sm-2 col-form-label">Nama</label>
									<div class="col-sm-4">
										<input type="text" name="nama_part" id="nama_part" readonly class="form-control">
									</div>
								</div>
								<div class="form-group row">

									<label class="col-sm-2 col-form-label">Supplier</label>
									<div class="col-sm-4">
										<select name="supplier" class="form-control">
											<option value="">Supplier...
											</option>
											<?php
											if (!empty($dataSupplier)) {
												foreach ($dataSupplier as $sp) {   ?>
													<option value="<?php echo $sp->id_supplier; ?>">
														<?php echo $sp->nama_sup; ?>
													</option>
											<?php
												}
											}
											?>
										</select>
									</div>
									<label class="col-sm-2 col-form-label">Status Barang</label>
									<div class="col-sm-4">
										<select name="status_part" class="form-control" required>
											<option value="">Pilih Status ...</option>
											<option value="AKTIF">AKTIF</option>
											<option value="PASIF">PASIF</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Status ?</label>
									<div class="col-sm-4">
										<div class="icheck-red d-inline">
											<input type="radio" name="status" onClick="nonSpk()" value="N" id="radioSuccess6" checked>
											<label for="radioSuccess6"> Non SPK
											</label>

										</div>&nbsp;&nbsp;
										<div class="icheck-green d-inline">
											<input type="radio" name="status" onClick="Spk()" value="Y" id="radioSuccess5">
											<label for="radioSuccess5"> SPK
											</label>
										</div>
									</div>
									<label class="col-sm-2 col-form-label">Jumlah</label>
									<div class="col-sm-4">
										<input type="text" name="jumlah" id="jumlah" value="" onKeyup="startCek()" onKeypress="startCek()" class=" form-control" placeholder="Jumlah Barang" required>
									</div>
								</div>
								<div class="form-group row" id="fnpart" hidden="">
									<label class="col-sm-2 col-form-label">No SPK</label>
									<div class="col-sm-4">
										<input type="text" name="no_spk" id="no_spk" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Ket Barang</label>
									<div class="col-sm-10">
										<input type="text" name="keterangan" id="keterangan" value="" class="form-control" placeholder="Keterangan Barang">
									</div>
								</div>
						</div>
						<input type="hidden" name="id_keluar" id="id_keluar" class="form-control">
						<input type="hidden" name="hrg_awal" id="hrg_awal" class="form-control">
						<input type="hidden" name="stok_awal" id="stok_awal" class="form-control">
						<input type="hidden" name="stok_a" id="stok_a" class="form-control">
						<input type="hidden" name="stok_p" id="stok_p" class="form-control">
						<input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
						<div class="modal-footer center-content-between">
							<button class="btn btn-primary" id="btnSimpan" type="submit" disabled=""><span class=" fa fa-save"></span> Simpan</button>
						</div>

						</form>
					</div>
				</div>
				<div id="modal-keluar"></div>
			</div>

			<div class="col-md-6" id="detailPart" hidden="">
				<div class="card card-default">
					<div class="modal-content text-blue">
						<div class="modal-header text-blue">
							<h5 style="display:block; text-align:center;"><span class="ion-android-alert ion-lg text-blue"></span>&nbsp; Detail Part Keluar</h5>
							<div class="text-right">
								<button type="button" class="btn btn-sm btn-outline-success cetak-keluar" id="cetak" data-id="" title="Add Data"><i class="fas fa-print"></i> Cetak</button>
							</div>
						</div>
						<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-striped  table-bordered table-hover nowrap responsive" id="list-keluar">
									<thead>
										<tr>
											<th>No</th>
											<th>No Part</th>
											<th>Nama Part</th>
											<th>jumlah</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody id="data-keluar"></tbody>
									<tfoot></tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal_form" role="dialog">
				<div class="modal-dialog modal-xm">
					<div class="modal-content">
						<div class="modal-body form">
							<div class="card card-first card-outline">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table no-wrap table-hover nowrap" id="table-part">
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
</section>
<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->
<script type="text/javascript">
	$('#tgl_keluar,#tgl_awal,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});

	function Spk() {
		document.getElementById("fnpart").hidden = false;
	}

	function nonSpk() {
		document.getElementById("fnpart").hidden = true;
	}
	
	function startCek() {
		var jml = document.formKeluar.jumlah.value;
		var stok = document.formKeluar.stok_awal.value;
		if (stok-jml < 0) {
			document.getElementById("btnSimpan").disabled = true;
		}else{
			
			document.getElementById("btnSimpan").disabled = false;
		}
		}
	$(document).ready(function() {
		table = $('#table-part').dataTable({
			"responsive": false,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"processing": true,
			"serverSide": true,
			"pageLength": 5, // Defaults number of rows to display in table
			"order": [],
			"ajax": {
				"url": "<?php echo site_url('PurchaseOrder/ajax_list') ?>",
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],
			"dom": '<"top"f>rt<"bottom"lp><"clear">',
			"fnDrawCallback": function() {
				$("input[type                                = 'search']").attr("id", "searchBox");
				$('#table-select').css('cssText', "margin-top: 0px !important;");
				$('#searchBox').css("width", "300px").focus();
				$('#table-select_filter').removeClass('dataTables_filter');
			}
		});
	});

	function selectPart(id_barang) {
		$.ajax({
			url: "<?php echo site_url('PurchaseOrder/cariKode') ?>/" + id_barang,
			type: "GET",
			dataType: "JSON",
			success: function(data) {

				$('[name = "id_barang"]').val(data.id_barang);
				$('[name = "no_part"]').val(data.no_part);
				$('[name = "nama_part"]').val(data.nama_part);
				$('[name = "stok_awal"]').val(data.stok);
				$('[name = "stok_a"]').val(data.stok_a);
				$('[name = "stok_p"]').val(data.stok_p);
				$('[name = "supplier"]').val(data.supplier);
				$('[name = "hrg_awal"]').val(data.hrg_awal);
				//document.getElementById('supplier').innerHTML   = data.supplier;
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});

		$('#modal_form').modal('hide');
	}

	var MyTable = $('#list-keluar').dataTable({
		"responsive": true,
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"ordering": true,
		"info": true
	});

	function next(dataKeluar) {
		document.getElementById('id_keluar').value = dataKeluar;
		var d = document.getElementById("cetak");
		d.setAttribute('data-id', dataKeluar);

		//document.getElementById("cetak").hidden = false;
		//document.getElementById("alamat").readonly = true;
	}

	function refresh() {
		MyTable = $('#list-keluar').dataTable();
	}

	function tampilDetail(dataKeluar) {
		//var out = jQuery.parseJSON(data);
		var id_keluar = document.getElementById('id_keluar').value = dataKeluar;
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('Part_keluar/tampilDetail'); ?>?id_keluar=' + id_keluar,
			data: 'id_keluar=' + id_keluar,
			success: function(hasil) {
				MyTable.fnDestroy();
				$('#data-keluar').html(hasil);
				refresh();
			}
		});
	}
	$('#formKeluar').submit(function(e) {
		document.getElementById("detailPart").hidden = false;
		var data = $(this).serialize();

		$.ajax({
				method: 'POST',
				url: '<?php echo base_url('Part_keluar/prosesKeluar'); ?>',
				data: data
			})
			.done(function(data) {
				var out = jQuery.parseJSON(data);

				if (out.status == 'form') {
					//toastr.error(out.msg);
					$('.msg').html(out.msg);
					refresh();
					effect_msg();
				} else {
					$('.msg').html(out.msg);
					$('.dataKeluar').html(out.dataKeluar);
					tampilDetail(out.dataKeluar)
					next(out.dataKeluar);
					document.getElementById("formKeluar"); //reset()	
					$('#tgl_keluar').attr('readonly', 'readonly');
					$('#tujuan').attr('readonly', 'readonly');
					$('#no_part').val('');
					$('#nama_part').val('');
					$('#jumlah').val('');
					$('#keterangan').val('');
					Swal.fire({
						position: 'center',
						icon: 'success',
						title: out.msg,
						showConfirmButton: false,
						timer: 1500
					})
				}
			})

		e.preventDefault();
	});

	function cetakKeluar(datakode) {}


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
	var data_id;
	$(document).on("click", ".delete-detail", function() {
		data_id = $(this).attr("data-id");
		data_stok = $(this).attr("data-stok");
		data_status = $(this).attr("data-status");
		data_part = $(this).attr("data-part");
	})
	$(document).on("click", ".hapus-detail", function() {
		var id = data_id;
		var stok = data_stok;
		var status = data_status;
		var kodePart = data_part;

		$.ajax({
				method: "POST",
				url: "<?php echo base_url('Part_keluar/deleteDetail'); ?>",
				data: "id=" + id+"&stok=" + stok+"&status=" + status+"&kodePart=" + kodePart
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
					//$('.msg').html(out.msg);
					$('#hapusDetail').modal('hide');
					var id_keluar = document.formKeluar.id_keluar.value;
					//next(next_proses);
					tampilDetail(id_keluar);
				}
			})
	})


</script>