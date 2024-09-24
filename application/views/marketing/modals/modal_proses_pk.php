<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataPk)) {
      foreach ($dataPk as $dataPk) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;">Proses Pekerjaan <?php if (!empty($dataPk->keterangan)) {
                                                        echo $dataPk->keterangan;
                                                      } ?></h4>
    </p>
  </div>
  <div class="modal-body">
    <form id="formTambahPk"method="POST">
      <div class="form-group">
        <input type="hidden" name="id">
      </div>
      <div class="form-group row">
								<label class="col-sm-4 col-form-label">Tanggal Mulai</label>
								<div class="col-sm-8">
									<div class="input-group date" id="reservationdate" data-target-input="nearest">
										<input type="text" name="tgl_mulai" id="tgl_mulai"
											class="form-control tgl_mulai datetimepicker" data-toggle="datetimepicker"
											data-target=".tgl_mulai" data-format="yyy-mm-dd" required>
										<div class="input-group-append" data-toggle="datetimepicker">
											<div class="input-group-text">
												<i class="fa fa-calendar"></i>
											</div>
										</div>
									</div>

								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Jam Mulai</label>
							<div class="col-sm-8">
									<div class="input-group date" id="timepicker" data-target-input="nearest">
										<input type="text" name="jam_mulai" id="jam_mulai"
											class="form-control jam datetimepicker-input" data-toggle="datetimepicker"
											data-target=".jam" data-format="hh:mm" />
										<div class="input-group-append" data-target="#jam" data-toggle="datetimepicker">
											<div class="input-group-text"><i class="far fa-clock"></i></div>
										</div>
									</div>
								</div>
							</div>
              <div class="form-group row">
								<label class="col-sm-4 col-form-label">Pemborong</label>
								<div class="col-sm-8">
									<div class="input-group date" id="reservationdate" data-target-input="nearest">
										<input type="text" name="pt_pemborong" id="pt_pemborong"
											class="form-control">
									</div>

								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Kepala Borong</label>
								<div class="col-sm-8">
									<div class="input-group date" id="reservationdate" data-target-input="nearest">
										<input type="text" name="pj_borong" id="pj_borong"
											class="form-control" required>
									</div>

								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Biaya</label>
								<div class="col-sm-8">
									<div class="input-group date" id="reservationdate" data-target-input="nearest">
										<input type="text" name="biaya_borong" id="biaya_borong" value="0"
											class="form-control" required>
									</div>

								</div>
							</div>
							<input type="hidden" name="id_lapor" id="id_lapor" value="<?php echo $dataPk->id_lapor; ?>" class="form-control">
							<input type="hidden" name="no_body" id="no_body" value="<?php echo $dataPk->no_body; ?>" class="form-control">
							<input type="hidden" name="jns_pk" id="jns_pk" value="<?php echo $dataPk->jns_pk; ?>" class="form-control">
							<input type="hidden" name="ket_pk" id="ket_pk" value="<?php echo $dataPk->keterangan; ?>" class="form-control">
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
					$('#tgl_mulai').datetimepicker({
						format: 'DD-MM-YYYY',
						date: moment()
					});
					$('#jam_mulai').datetimepicker({
						format: 'HH:mm',
						pickDate: false,
						pickSeconds: false,
						pick12HourFormat: false
					})

					$('#formTambahPk').submit(function(e) {
						var data = $(this).serialize();

						$.ajax({
								method: 'POST',
								url: '<?php echo base_url('BusMasuk/inputPk'); ?>',
								data: data
							})
							.done(function(data) {
								var out = jQuery.parseJSON(data);

								if (out.status == 'form') {
									$('.form-msg').html(out.msg);
									//effect_msg_form();
								} else {
									document.getElementById("formTambahPk").reset();
									$('.msg').html(out.msg);
                					$('.datakode').html(out.datakode);
                					$('#proses-pk').modal('hide');
									tampilPkawal(out.datakode);
									tampilPk(out.datakode);
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
					var biaya_borong = document.getElementById('biaya_borong');
					biaya_borong.addEventListener('keyup', function(e)
    {
        biaya_borong.value = formatRupiah(this.value);
    });
    
    /* Fungsi */
    function formatRupiah(angka, prefix)
    {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah     = split[0].substr(0, sisa),
            ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
					</script>