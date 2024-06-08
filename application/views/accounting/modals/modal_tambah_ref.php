
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataRef)){
             foreach ($dataRef as $k){				 
			 }}
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($k->id_ref)) {
                            echo 'Edit No Referensi';
                        } else { echo 'Penambahan Nomor Referensi';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($k->id_ref)) {echo 'id="form-tambah-ref"';} else { echo 'id="form-update-ref"';}?> method="POST">
    <div class="form-group">
      <input type="hidden" name="id_ref" value="<?php if (!empty($k->id_ref)) { echo $k->id_ref; } ?>">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Jenis Akun" value="<?php
                        if (!empty($k->no_ref)) {
                            echo $k->no_ref;
                        }
                        ?>" name="no_ref" aria-describedby="sizing-addon2" maxlength="10">	

    </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Keterangan" value="<?php
                        if (!empty($k->keterangan)) {
                            echo $k->keterangan;
                        }
                        ?>" name="keterangan" aria-describedby="sizing-addon2">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>