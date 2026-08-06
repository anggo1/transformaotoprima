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
										<input type="text" id="no_po" name="no_po" class="form-control"
											onclick="showPo()" data-toggle="modal" data-target="#modal_po" placeholder="Daftar PO"
											required></input>
									</div>
									<div class="col-sm-3">
										<input type="text" name="kode_po" onclick="showPo()"  id="kode_po" class="form-control" placeholder="Kode Purchase Order">
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
										<input type="text" name="nama_cus" id="nama_cus" class="form-control" placeholder="Customer">
										<input type="text" name="kode_cus" id="kode_cus" class="form-control" placeholder="Kode Customer">
									</div>
									<label class="col-sm-2 col-form-label">Keterangan</label>
									<div class="col-sm-4">
										<input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan">
									</div>
								</div>

								<div id="data_po_detail"></div>

								<input type="hidden" name="status" id="status" class="form-control">
								<input type="hidden" name="kode_keluar" id="kode_keluar" class="form-control">
								<input type="hidden" name="pengguna" id="pengguna"
									value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">


								<div class="modal-footer center-content-between">
									<button class="btn btn-primary" id="simpan" name="simpan" type="submit"><span
											class="fa fa-save"></span> Simpan</button>
									<button type="button" class="btn btn-success cetak-keluar" hidden="hidden" id="cetak"
										data-id="" title="Add Data"><i class="fas fa-print"></i> &nbsp;Cetak </button>
								</div>
							</form>
						</div>
					</div>
					<div id="modal-keluar"></div>
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

	function PO() {
		document.getElementById("fndisc").hidden = false;
		document.getElementById("fndisc2").hidden = true;
		$('#no_po').attr('required', 'required');
	}

	function nonPO() {
		document.getElementById("fndisc").hidden = true;
		document.getElementById("fndisc2").hidden = false;
	}

	function showPart(no_po, kode_po, nama_cus, kode_cus, kode_pesan, keterangan) {
		//var no_po = document.getElementById("no_po").value;
		//var no_po=document.getElementById("showPart");
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('PartKeluarPo/cari_barang'); ?>',
			data: 'no_po=' + no_po,
			success: function(hasil) {
				//MyTable.fnDestroy();//refresh();
				//$('#data_po').html(hasil);
				$('#data_po_detail').html(hasil);
				$('[name = "no_po"]').val(no_po);
				$('[name = "kode_po"]').val(kode_po);
				$('[name = "nama_cus"]').val(nama_cus);
				$('[name = "kode_cus"]').val(kode_cus);
				$('[name = "kode_pesan"]').val(kode_pesan);
				$('[name = "keterangan"]').val(keterangan);
				$('#modal_po').modal('hide');
			}
		});
	}

	function showPo1() {
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('PartKeluarPo/showPo'); ?>',
			//data: 'id_po=' + id_po,
			success: function(hasil) {
				MyTable.fnDestroy(); //refresh();
				//$('#data_po').html(hasil);
				$('#data_po').html(hasil);
				//$('#modal_po').modal('hide');
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

	function selectPart(no_part, nama_part, hrg_awal, stok_awal, stok_jkt, stok_cbt, stok_sby, jumlah, supplier) {

		$('[name = "no_part"]').val(no_part);
		$('[name = "nama_part"]').val(nama_part);
		$('[name = "hrg_awal"]').val(hrg_awal);
		$('[name = "jumlah"]').val(jumlah);
		$('[name = "supplier"]').val(supplier);
		$('[name = "stok_awal"]').val(stok_awal);
		$('[name = "stok_jkt"]').val(stok_jkt);
		$('[name = "stok_cbt"]').val(stok_cbt);
		$('[name = "stok_sby"]').val(stok_sby);


		$('#modal_form').modal('hide');
	}

	function tampilDetail(no_po) {
		//var out = jQuery.parseJSON(data);
		var id_po = document.getElementById('no_po').value;
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('PartKeluarPo/tampilDetail'); ?>',
			data: 'id_po=' + id_po,
			success: function(hasil) {
				MyTable.fnDestroy();
				$('#data_po_detail').html(hasil);
				refresh();
			}
		});
	}
	$('#formpartkeluarPo').submit(function(e) {
		var data = $(this).serialize();
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
					document.getElementById("cetak").hidden = false;
					var d = document.getElementById("cetak");
					d.setAttribute('data-id', out.dataPo);
					document.getElementById("tambah").hidden = false;
					document.getElementById("simpan").hidden = true;
					document.getElementById("formpartkeluar"); //reset()	
					$('#tgl_keluar').attr('readonly', 'readonly');
					$('#wo_no').attr('readonly', 'readonly');
					$('#no_sj').attr('readonly', 'readonly');
					$('#no_inv').attr('readonly', 'readonly');
					$('#nik').attr('readonly', 'readonly');
					$('#petugas').attr('readonly', 'readonly');
					$('#kode_pr').attr('readonly', 'readonly');
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


	function cetakPart(datakode) {}


	$(document).on("click", ".cetak-keluar", function() {
		var id = $(this).attr("data-id");
		//var id = document.getElementById('next_proses').value=datakode;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('PartKeluarPo/cetak'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				$('#modal-keluar').html(data);
				$('#cetak-keluar').modal('show');
			})
	})
</script>