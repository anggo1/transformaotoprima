<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataPk)) {
      foreach ($dataPk as $dataPk) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;">Pause PK Aktif</h4>
    </p>
  </div>
  <div class="modal-body">
    <form id="pausePkaktif"method="POST">
							<div class="form-group">
								<label class="col-sm-4 col-form-label">Keterangan Pause</label>
								<div class="col-sm-12">
									<div class="input-group date" id="reservationdate" data-target-input="nearest">
										<input type="text" name="ket_pause" id="ket_pause"
											class="form-control" required>
									</div>

								</div>
							</div>
							<input type="hidden" name="id_lapor" id="id_lapor" value="<?php echo $dataPk->id_lapor; ?>" class="form-control">
							<input type="hidden" name="id_pk" id="id_pk" value="<?php echo $dataPk->id_pk; ?>" class="form-control">
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
      </div>
    </form>
  </div>
</div>