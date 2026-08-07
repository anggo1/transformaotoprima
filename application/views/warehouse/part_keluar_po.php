<style>
	.table.DataTable {
		font-family: Verdana, Geneva, Tahoma, sans-serif;
		font-size: 12px;
	}

	table.dataTable td {
		padding-bottom: 5px;
	}
</style>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-default">
					<!-- /.card-header -->
					<div class="modal-content">
						<div class="modal-header text-blue">

							<h5 style="display:block; text-align:center;"><span
									class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Pengeluaran Barang Purchase Order (PO)
							</h5>
							<button type="button" class="btn btn-success" id="tambah" hidden="hidden"
								onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> Data
								Baru</button>
						</div>
						<div class="modal-body">
							<form id="formpartkeluarPo" name="formpartkeluarPo" method="POST">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Tanggal</label>
									<div class="col-sm-4">
										<div class="input-group date" id="reservationdate" data-target-input="nearest">

											<input type="text" name="tgl_keluar" id="tgl_keluar"
												class="form-control tgl_keluar datetimepicker" data-toggle="datetimepicker"
												data-target=".tgl_keluar" data-format="yyy-mm-dd" required>

											<div class="input-group-append" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
									<label class="col-sm-2 col-form-label">No PO</label>
									<div class="col-sm-1">
										<input type="text" name="id_po_masuk" id="id_po_masuk" class="form-control" placeholder="Surat Jalan Barang">
										</div>
									<div class="col-sm-3">
										<input type="text" name="kode_po" onclick="showPo()" id="kode_po" data-toggle="modal" data-target="#modal_po" class="form-control" placeholder="Kode Purchase Order">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">No SJ</label>
									<div class="col-sm-4">
										<input type="text" name="no_sj" id="no_sj" class="form-control" placeholder="Surat Jalan Barang">
									</div>
									<label class="col-sm-2 col-form-label">No Order</label>
									<div class="col-sm-4">
										<input type="text" name="kode_pesan" id="kode_pesan" class="form-control" placeholder="No Order">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Customer</label>
									<div class="col-sm-4">
										<input type="hidden" name="nama_cus" id="nama_cus" class="form-control" placeholder="Customer">
										<input type="text" name="kode_cus" id="kode_cus" class="form-control" placeholder="Kode Customer">
									</div>
									<label class="col-sm-2 col-form-label">Keterangan</label>
									<div class="col-sm-4">
										<input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan">
									</div>
								</div>

								<div id="data_po_detail"></div>
								<div id="data-keluar-cache"></div>

								<input type="hidden" name="status" id="status" class="form-control">
								<input type="hidden" name="pengguna" id="pengguna"
									value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">


								<div class="modal-footer center-content-between">
									<button class="btn btn-primary" id="simpan" name="simpan" type="submit"><span
											class="fa fa-save"></span> Simpan</button>
									<button type="button" class="btn bg-gradient-info cetak-keluar" id="cetak" hidden="hidden" data-id="" title="Cetak"><i class="fas fa-print"></i> Surat Jalan</button>
									<button type="button" class="btn bg-gradient-indigo cetak-bon-keluar" id="cetakBon" hidden="hidden" title="Cetak Bon"><i
											class="fas fa-print"></i> Bon</button>
								</div>
							</form>
						</div>
					</div>
					<div id="modal-keluar"></div>
					<div id="cetak-bon-keluar"></div>
				</div>
			</div>
			<div class="modal fade" id="modal_po" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-body form">
							<div class="card card-first card-outline">
								<div class="card-body">
									<div class="table-responsive">
										<div id="data-po"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

</section><!-- /.modal-content -->
<script type="text/javascript">
	$('#date1,#tgl_keluar,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});
	var MyTable = $('#list-masuk,#list-po,#table-part').dataTable({
		"responsive": false,
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"ordering": true,
		"info": true
	});

	function refresh() {
		MyTable = $('#list-masuk,#list-po,#table-part').dataTable();
	}


	function nonPO() {
		document.getElementById("fndisc").hidden = true;
		document.getElementById("fndisc2").hidden = false;
	}

	function showPart(id_po_masuk, kode_po, nama_cus, kode_cus, kode_pesan, keterangan) {
		//var id_po_masuk = document.getElementById("id_po_masuk").value;
		//var id_po_masuk=document.getElementById("showPart");
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('PartKeluarPo/cari_barang'); ?>',
			data: 'id_po_masuk=' + id_po_masuk,
			success: function(hasil) {
				//MyTable.fnDestroy();//refresh();
				//$('#data_po').html(hasil);
				$('#data_po_detail').html(hasil);
				$('[name = "id_po_masuk"]').val(id_po_masuk);
				$('[name = "kode_po"]').val(kode_po);
				$('[name = "nama_cus"]').val(nama_cus);
				$('[name = "kode_cus"]').val(kode_cus);
				$('[name = "kode_pesan"]').val(kode_pesan);
				$('[name = "keterangan"]').val(keterangan);
				$('#modal_po').modal('hide');
			}
		});
	}
	function showPo() {
		//var tgl_po = document.getElementById("tgl_po").value;
		$.get('<?php echo base_url('PartKeluarPo/showPo'); ?>',
			function(data) {
				// success: function (data) {
				MyTable.fnDestroy(); //refresh();
				$('#data-po').html(data);
			})
	}

	
	$('#formpartkeluarPo').submit(function(e) {		
		var data = $(this).serialize();
		var id_po_masuk = document.getElementById('id_po_masuk').value;
		//var data = $('td').find('input[name="qty_masuk[]"]').val();

		$.ajax({
				method: 'POST',
				url: '<?php echo base_url('PartKeluarPo/prosesPartkeluar'); ?>',
				data: data
			})
			.done(function(data) {
				var out = jQuery.parseJSON(data);

				if (out.status == 'form') {
					//toastr.error(out.msg);
					$('.msg').html(out.msg);
					refresh();
					Swal.fire({
						position: 'center',
						icon: 'error',
						title: out.msg,
						showConfirmButton: false,
						timer: 1500
					})
				} else {
					$('.msg').html(out.msg);
					//$('.dataPo').html(out.dataPo);
					//next(out.dataPo);
					tampilDetailCache(out.dataPo);

					document.getElementById("tambah").hidden = false;
					document.getElementById("simpan").hidden = true;
					document.getElementById("formpartkeluar"); //reset()
					
					var d = document.getElementById("cetak");
					d.setAttribute('data-id', id_po_masuk);
					var d = document.getElementById("cetakBon");
					d.setAttribute('data-id', id_po_masuk);
					document.getElementById("cetak").hidden = false;
					document.getElementById("cetakBon").hidden = false;

					$('#tgl_keluar').attr('readonly', 'readonly');
					$('#id_po_masuk').attr('readonly', 'readonly');
					$('#no_sj').attr('readonly', 'readonly');
					$('#kode_pesan').attr('readonly', 'readonly');
					$('#nama_cus').attr('readonly', 'readonly');
					$('#keterangan').attr('readonly', 'readonly');
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
	function tampilDetail(id_po_masuk) {
		//var out = jQuery.parseJSON(data);
		var id_po_masuk = document.getElementById('id_po_masuk').value;
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('PartKeluarPo/tampilDetail'); ?>',
			data: 'id_po_masuk=' + id_po_masuk,
			success: function(hasil) {
				MyTable.fnDestroy();
				$('#data_po_detail').html(hasil);
				refresh();
			}
		});
	}

	function tampilDetailCache(id_po_masuk) {
		//var out = jQuery.parseJSON(data);
		var id_po_masuk = document.getElementById('id_po_masuk').value;
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('PartKeluarPo/tampilDetailCache'); ?>',
			data: 'id_po_masuk=' + id_po_masuk,
			success: function(hasil) {
				MyTable.fnDestroy();
				$('#data-keluar-cache').html(hasil);
				refresh();
			}
		});
	}

	function cetakPo(datakode) {}
	$(document).on("click", ".cetak-bon-keluar", function() {
		var id_po_masuk = document.getElementById('id_po_masuk').value;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('PartKeluarPo/cetak_bon'); ?>",
				data: "id_po_masuk=" + id_po_masuk
			})
			.done(function(data) {
				// $('#part-pk').modal('hide');
				$('#modal-pk-bon').html(data);
				$('#cetak-bon-keluar').modal('show');
			})
	})

	$(document).on("click", ".cetak-keluar", function() {
		var id_po_masuk = document.getElementById('id_po_masuk').value;
		//var id = document.getElementById('next_proses').value=datakode;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('PartKeluarPo/cetak'); ?>",
				data: "id_po_masuk=" + id_po_masuk
			})
			.done(function(data) {
				$('#modal-keluar').html(data);
				$('#cetak-keluar').modal('show');
			})
	})
</script>