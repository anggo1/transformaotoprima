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
			<div class="col-md-12">
				<div class="card card-default">
					<!-- /.card-header -->
					<div class="modal-content">
						<div class="modal-header text-blue">

							<h5 style="display:block; text-align:center;"><span class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Barang Keluar</h5>
							<button type="button" class="btn btn-success" id="tambah" hidden="hidden" onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> Data Baru</button>
						</div>
						<div class="modal-body">
						<?php
						$kd='TOP-';
								$tgl_keluar = date("y-m-d");
								$date = date("ym");
								$ci_kons = get_instance();
								$query = "SELECT max(kode_keluar) AS maxKode FROM tbl_wh_part_keluar WHERE kode_keluar LIKE '%$date%'";
								$hasil = $ci_kons->db->query($query)->row_array();
								$noOrder = $hasil['maxKode'];
								$noUrut = (int)substr($noOrder, 4, 5);
								$noUrut++;
								$tahun = substr($date, 0, 2);
								$bulan = substr($date, 2, 2);

								$id_keluar  = $tahun.$bulan.sprintf("%04s", $noUrut);
								$kode_keluar  = $kd.$tahun.$bulan.sprintf("%04s", $noUrut);
							?>
							<form id="formKeluar" name="formKeluar" method="POST">

							<input type="hidden" name="id_keluar" id="id_keluar" value="<?php echo $kode_keluar ?>" class="form-control">
							<input type="hidden" name="kode_keluar" id="kode_keluar" value="<?php echo $id_keluar ?>" class="form-control">
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
								<label class="col-sm-2 col-form-label">No Po</label>
									<div class="col-sm-4">
										<input type="text" name="no_po_cus" id="no_po_cus" class="form-control" placeholder="No PO">
									</div>
									<label class="col-sm-2 col-form-label">Alamat</label>
									<div class="col-sm-4">
										<input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat Tujuan">
									</div>
								</div>
								<div class="form-group row">
								<label class="col-sm-2 col-form-label">Faktur</label>
									<div class="col-sm-4">
										<input type="text" name="faktur" id="faktur" class="form-control" placeholder="No Faktur">
									</div>
								<label class="col-sm-2 col-form-label">Ket Keluar</label>
									<div class="col-sm-4">
										<input type="text" name="ket_surat" id="ket_surat" class="form-control" placeholder="Keterangan Keluar">
									</div>
									</div>
								<div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Stok Cabang</label>
                                    <div class="col-sm-4">
                                        <select name="lokasi" id="lokasi" class="form-control" <?php  $lvl = $this->session->userdata['id_level']; 
                                        if ($lvl !='1' && $lvl !='12'){ echo 'readonly';} ?>>
                                            <option value="">Cabang Dealer...
                                            </option>
                                            <?php
                                            $lok = $this->session->userdata['lokasi'];
                                                                    foreach ($dataKota as $kel) { ?>
                                                                <option
                                                                    value="<?php echo $kel->kode_kota.'|'.$kel->nama_kota; ?>"
                                                                    <?php if ($kel->nama_kota == $lok) { echo "selected='selected'"; } ?>>
                                                                    <?php echo $kel->nama_kota; ?>
                                                                </option>
                                                                <?php }  ?>
                                        </select>
                                    </div>
									<label class="col-sm-2 col-form-label">Kategori</label>
                                                        <div class="col-sm-4">
                                                            <select name="divisi" id="divisi" class="form-control">
                                                                <option value="">Pilih Kategori...
                                                                </option>
                                                                <?php
                                                            if (empty($part->kategori)) { foreach ($dataKategori as $kt) {
                                                            ?>
                                                                <option
                                                                    <?php echo $kt == $kt->id_kategori ? 'selected="selected"' : '' ?>
                                                                    value="<?php echo $kt->id_kategori ?>|<?php echo $kt->kategori; ?>">
                                                                    <?php echo $kt->kategori  ?><?php } ?>
                                                                </option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
								</div>
								<div class="form-group row">
								<label for="Barang" class="col-sm-2 col-form-label"></label>
								<div class="col-sm-4"></div>
								</div>
						<div id="data-keluar"></div>
						<div id="data-keluar-cache"></div>
						</div>
						<input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
						<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-xl btn-success" id="tambahBarang" title="Add Part" data-toggle="modal" data-target="#modal_form"><i class="fas fa-plus"></i> Tambah Barang</button>
									<button class="btn btn-primary" id="simpan" type="submit"><span class="fa fa-save"></span> Simpan</button>
								<button type="button" class="btn bg-gradient-info cetak-keluar" id="cetak" hidden="hidden" data-id="" title="Cetak"><i class="fas fa-print"></i> Surat Jalan</button>
                                <button type="button" class="btn bg-gradient-indigo cetak-bon-keluar" id="cetakBon" hidden="hidden" title="Cetak Bon"><i
                                            class="fas fa-print"></i> Bon</button>
						</div>

						</form>
					</div>
				<div id="modal-keluar"></div>
				<div id="modal-pk-bon"></div>
				</div>
			</div>
			<div class="modal fade" id="modal_form" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-body form">
							<div class="card card-first card-outline">
								<div class="card-body">
									<div class="table-responsive">
										<table width="100%" class="table no-wrap table-hover nowrap" id="table-part">
											<thead>
												<tr>
													<th>#</th>
													<th>No Part</th>
													<th>Nama Part</th>
													<th>Stok</th>
													<th>Satuan</th>
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

</section><!-- /.modal-content -->
<script type="text/javascript">
	$('#tgl_keluar,#tgl_po,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});
	function myFunction() {
  var x = document.getElementById("status_part").value;
        if(x == ""){
            document.getElementById("tambahBarang").disabled = true;
		}else{
			
			document.getElementById("tambahBarang").disabled = false;
		}
        }
	var MyTable = $('#list-keluar').dataTable({
		"responsive": true,
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"ordering": true,
		"info": true
	});
	$(document).ready(function() {
		table = $('#table-part').dataTable({
			"responsive": false,
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"processing": true,
			"serverSide": true,
			"pageLength": 10, // Defaults number of rows to display in table
			"order": [],
			"ajax": {
				"url": "<?php echo site_url('Part_keluar/ajax_list') ?>",
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ]
		});
	});
	$(document).ready(function() {
    var table = $('#table-part').DataTable();

    $('#table-part tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        var id_part = data[5];
        var no_part = data[1];
        var nama_part = data[2];
        var stok = data[3];
        var satuan = data[4];
        var stok_jkt = data[6];
        var stok_cbt = data[7];
        var stok_sby = data[8];
        var harga_baru = data[9];
        selectPart(id_part,no_part,nama_part,satuan,stok,stok_jkt,stok_cbt,stok_sby,harga_baru);
    } );
} );
	function refresh() {
		//MyTable = $('#table-part').dataTable();
	}
    
	function selectPart(id_part,no_part,nama_part,satuan,stok,harga_baru) {
		var tgl_keluar = document.formKeluar.tgl_keluar.value;
		var id_keluar = document.formKeluar.id_keluar.value;
		var lokasi = document.formKeluar.lokasi.value;
		
				$.ajax({
				method: 'POST',
				url: '<?php echo base_url('Part_keluar/prosesDetailInput'); ?>',
				data: 
				"tgl_keluar=" + tgl_keluar +
				"&id_keluar=" + id_keluar +
				"&id_part="+id_part + 
				"&no_part="+no_part + 
				"&nama_part="+nama_part + 
				"&satuan="+satuan + 
				"&lokasi="+lokasi + 
				"&stok="+stok + 
				"&harga_baru="+harga_baru 
			})
			
			tampilDetail(id_keluar);
			$('#modal_form').modal('hide');
			}

	function tampilDetail(id_keluar) {
		var id_keluar = document.getElementById('id_keluar').value = id_keluar;
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
	function tampilDetailCache(id_keluar) {
		//var out = jQuery.parseJSON(data);
		var id_keluar = document.getElementById('id_keluar').value = id_keluar;
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('Part_keluar/tampilDetailCache'); ?>?id_keluar=' + id_keluar,
			data: 'id_keluar=' + id_keluar,
			success: function(hasil) {
				MyTable.fnDestroy();
				$('#data-keluar-cache').html(hasil);
				refresh();
			}
		});
	}
	$('#formKeluar').submit(function(e) {
		//document.getElementById("detailPart").hidden = false;
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
					document.getElementById("formKeluar"); //reset()	
					$('#tgl_keluar').attr('readonly', 'readonly');
					$('#ket_surat').attr('readonly', 'readonly');
					$('#status_part').attr('disabled','disabled');
					$('#tujuan').attr('readonly','readonly');
					$('#alamat').attr('readonly','readonly');
					$('#no_po_cus').attr('readonly','readonly');
					$('#faktur').attr('readonly','readonly');
					var d = document.getElementById("cetak");
					d.setAttribute('data-id', out.dataKeluar);
					var d = document.getElementById("cetakBon");
					d.setAttribute('data-id', out.dataKeluar);
					document.getElementById("cetak").hidden = false;
					document.getElementById("cetakBon").hidden = false;
					document.getElementById("tambah").hidden = false;
					document.getElementById("tambahBarang").hidden = true;
					document.getElementById("simpan").hidden = true;
					document.getElementById("data-keluar").hidden = true;
					tampilDetailCache(out.dataKeluar);
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
	
	function cetakPo(datakode) {}
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
	})
	$(document).on("click", ".hapus-detail", function() {
		var id = data_id;
		
        var dataPo = document.getElementById("id_keluar").value;

		$.ajax({
				method: "POST",
				url: "<?php echo base_url('Part_keluar/deleteDetail'); ?>",
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
						timer: 1500
					})
					$('#hapusDetail').modal('hide');
					//var id_po = document.formPo.id_po.value;
					//next(next_proses);
					tampilDetail(dataPo);
				}
			})
	})


</script>