
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataJenis)){
             foreach ($dataJenis as $k){				 
			 }}
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($k->id_jenis)) {
                            echo 'Edit Jenis Beban';
                        } else { echo 'Penambahan Jenis Beban';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($k->id_jenis)) {echo 'id="form-tambah-jenis"';} else { echo 'id="form-update-jenis"';}?> method="POST">
    <div class="form-group">
      <input type="hidden" name="id_jenis" value="<?php if (!empty($k->id_jenis)) { echo $k->id_jenis; } ?>">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Jenis Beban" value="<?php
                        if (!empty($k->jenis_beban)) {
                            echo $k->jenis_beban;
                        }
                        ?>" name="jenis_beban" aria-describedby="sizing-addon2">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>