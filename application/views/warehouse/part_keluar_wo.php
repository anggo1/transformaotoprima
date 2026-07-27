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
									class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Pengeluaran Barang WO
							</h5>
							<button type="button" class="btn btn-success" id="tambah" hidden="hidden"
								onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> Data
								Baru</button>
						</div>
						<div class="modal-body">
							<form id="formpartkeluar" name="formpartkeluar" method="POST">
								<input type="hidden" name="id_masuk" id="id_masuk" class="form-control" readonly>

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
									<label class="col-sm-2 col-form-label">No WO</label>
									<div class="col-sm-4">
										<input type="hidden" id="id" name="id" class="form-control"></input>
										<input type="text" id="wo_no" name="wo_no" class="form-control"
											onclick="showWo()" data-toggle="modal" data-target="#modal_po" placeholder="Daftar WO"
											required></input>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">No SJ</label>
									<div class="col-sm-4">
										<input type="text" name="no_sj" id="no_sj" class="form-control" placeholder="Surat Jalan Barang">
									</div>
									<label class="col-sm-2 col-form-label">No INV</label>
									<div class="col-sm-4">
										<input type="text" name="no_inv" id="no_inv" class="form-control" placeholder="No Invoice">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Petugas</label>
									<div class="col-sm-1">
										<input type="text" name="nik" id="nik" class="form-control" placeholder="Surat Jalan Barang">
									</div>
									<div class="col-sm-3">
										<input type="text" name="petugas" id="petugas" class="form-control" placeholder="Surat Jalan Barang">
									</div>
									<label class="col-sm-2 col-form-label">No INV</label>
									<div class="col-sm-4">
										<input type="text" name="no_inv" id="no_inv" class="form-control" placeholder="No Invoice">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Part Request No</label>
									<div class="col-sm-2">
										<input type="text" name="kode_pr" id="kode_pr" class="form-control" placeholder="Surat Jalan Barang">
									</div>
								</div>
								
								<div id="data_po_detail"></div>

								<input type="hidden" name="status" id="status" class="form-control">
								<input type="hidden" name="user" id="user"
									value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">


								<div class="modal-footer center-content-between">
									<button class="btn btn-primary" id="simpan" name="simpan" type="submit"><span
											class="fa fa-save"></span> Simpan</button>
									<button type="button" class="btn btn-success cetak-masuk" hidden="hidden" id="cetak"
										data-id="" title="Add Data"><i class="fas fa-print"></i> &nbsp;Cetak </button>
								</div>
							</form>
						</div>
					</div>
					<div id="modal-masuk"></div>
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

	function showPart(wo_no,nik,petugas,kode_pr) {
		//var no_po = document.getElementById("no_po").value;
		//var no_po=document.getElementById("showPart");
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('PartKeluarWo/cari_barang'); ?>',
			data: 'wo_no=' + wo_no,
			success: function(hasil) {
				//MyTable.fnDestroy();//refresh();
				//$('#data_po').html(hasil);
				$('#data_po_detail').html(hasil);
				$('[name = "wo_no"]').val(wo_no);
				$('[name = "id"]').val(id);
				$('[name = "nik"]').val(nik);
				$('[name = "petugas"]').val(petugas);
				$('[name = "kode_pr"]').val(kode_pr);
				$('#modal_po').modal('hide');
			}
		});
	}

	function showPo1() {
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('Part_masuk/showPo'); ?>',
			//data: 'id_po=' + id_po,
			success: function(hasil) {
				MyTable.fnDestroy(); //refresh();
				//$('#data_po').html(hasil);
				$('#data_po').html(hasil);
				//$('#modal_po').modal('hide');
			}
		});
	}

	function showWo() {
		//var tgl_po = document.getElementById("tgl_po").value;
		$.get('<?php echo base_url('PartKeluarWo/showWo'); ?>',
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

	function tampilDetail(dataPo) {
		//var out = jQuery.parseJSON(data);
		var id_po = document.getElementById('id_masuk').value = dataPo;
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('Part_masuk/tampilDetail'); ?>?id_po=' + id_po,
			data: 'id_po=' + id_po,
			success: function(hasil) {
				MyTable.fnDestroy();
				$('#data-masuk').html(hasil);
				refresh();
			}
		});
	}
	$('#formpartmasuk').submit(function(e) {
		var data = $(this).serialize();
		//var data = $('td').find('input[name="qty_masuk[]"]').val();

		$.ajax({
				method: 'POST',
				url: '<?php echo base_url('Part_masuk/prosesPartmasuk'); ?>',
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
					$('.dataPo').html(out.dataPo);
					next(out.dataPo);
					document.getElementById("cetak").hidden = false;
					document.getElementById("tambah").hidden = false;
					document.getElementById("simpan").hidden = true;
					document.getElementById("formPo"); //reset()	
					$('#tgl_po').attr('readonly', 'readonly');
					$('#no_po').attr('readonly', 'readonly');
					$('#keterangan').attr('readonly', 'readonly');
					$('#no_sj_sup').attr('readonly', 'readonly');
					$('#no_inv_sup').attr('readonly', 'readonly');
					$('#supplier').attr('readonly', 'readonly');
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

	function next(dataPo) {
		document.getElementById('id_masuk').value = dataPo;
		var d = document.getElementById("cetak");
		d.setAttribute('data-id', dataPo);

		//document.getElementById("cetak").hidden = false;
		//document.getElementById("alamat").readonly = true;
	}

	function cetakPo(datakode) {}


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
</script>