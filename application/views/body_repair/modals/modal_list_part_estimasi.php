  <div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <p>
    <h4 style="display:block; text-align:center;">Part Keluar Dengan PK</h4>
    </p>
  </div>
  <form id="formList" name="formList" method="POST">
    <div class="modal-body">
	<div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pekerjaan</label>
                                <div class="col-sm-9">
                                    <select name="proses" id="proses" class="form-control" required>
                                        <option value="">Jenis PK...
                                        </option>
                                        <?php
											if (!empty($dataPk)) {
												foreach ($dataPk as $pk) {   ?>
                                        <option value="<?php echo $pk->id; ?>|<?php echo $pk->kode; ?>">
                                            <?php echo $pk->keterangan; ?>
                                        </option>
                                        <?php
												}
											}
											?>
                                    </select>
                                </div>
                                </div>
								<div class="form-group row">
									<label for="No Part" class="col-sm-3 col-form-label">No Part</label>
									<div class="col-sm-9">
										<div class="input-group date" id="reservationdate" data-target-input="nearest">
											<input type="text" name="no_part" id="no_part" readonly class="form-control" data-toggle="modal" data-target="#modal_form">
											<span class="input-group-append">
												<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_form"><i class="glyphicon glyphicon-plus-sign"><i class="fa fa-search"></i></button></i>
											</span>
										</div>
									</div>
                                </div>
								<div class="form-group row">
									<label for="Nama Part" class="col-sm-3 col-form-label">Nama Part</label>
									<div class="col-sm-9">
										<input type="text" name="nama_part" id="nama_part" readonly class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Jumlah</label>
									<div class="col-sm-9">
										<input type="text" name="qty" id="qty" value="0" required class=" form-control" placeholder="Jumlah Barang" required>
									</div>
								</div>
						</div>

						<div class="modal-footer center-content-between">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button class="btn btn-primary" id="btnSimpan" type="submit"><span class=" fa fa-save"></span> Simpan</button>
						</div>

						</form>
  </div>
<div class="modal fade" id="modal_form" role="dialog">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-body form">
							<div class="card card-first card-outline">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-hover nowrap" id="table-part">
											<thead>
												<tr>
													<th>#</th>
													<th>No Part</th>
													<th>Nama Part</th>
													<th>Stok</th>
													<th>Harga</th>
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