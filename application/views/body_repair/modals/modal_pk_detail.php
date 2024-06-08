<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataPk)) {
      foreach ($dataPk as $dataPk) {
      }
    }
    ?>
    <p>
    <h4 style="display:block; text-align:center;">Penambahan Detail Pekerjaan</h4>
    </p>
  </div>
  <div class="modal-body">
  
  <label class="col-sm-6 col-form-label">ID PK : <?php echo $dataPk->id_pk; ?></label>
  <label class="col-sm-6 col-form-label">Jenis PK : <?php echo $dataPk->ket_pk; ?></label>
    <form id="detailPkaktif"method="POST">
							<div class="form-group">
								<label class="col-sm-4 col-form-label">Keterangan Detail</label>
								<div class="col-sm-12">
									<div class="input-group date" id="reservationdate" data-target-input="nearest">
										<input type="text" name="ket_detail" id="ket_detail"
											class="form-control" required>
									</div>

								</div>
							</div>
							<input type="hidden" name="jns_pk" id="jns_pk" value="<?php echo $dataPk->jns_pk; ?>" class="form-control">
							<input type="hidden" name="ket_pk" id="ket_pk" value="<?php echo $dataPk->ket_pk; ?>" class="form-control">
							<input type="hidden" name="id_lapor" id="id_lapor" value="<?php echo $dataPk->id_lapor; ?>" class="form-control">
							<input type="hidden" name="id_pk" id="id_pk" value="<?php echo $dataPk->id_pk; ?>" class="form-control">
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
      </div>
    </form>
  </div>
                                            <div id="detail-datapk">
                                            </div>
</div>
<script language="javascript">

 </script>