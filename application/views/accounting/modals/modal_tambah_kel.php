
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataKelompok)){
             foreach ($dataKelompok as $k){				 
			 }}
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($k->id_kelompok)) {
                            echo 'Edit Data Kelompok';
                        } else { echo 'Penambahan Data Kelompok';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($k->id_kelompok)) {echo 'id="form-tambah-kelompok"';} else { echo 'id="form-update-kelompok"';}?> method="POST">
    <div class="form-group">
      <input type="hidden" name="id_kelompok" value="<?php if (!empty($k->id_kelompok)) { echo $k->id_kelompok; } ?>">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Nama Kelompok" value="<?php
                        if (!empty($k->kelompok)) {
                            echo $k->kelompok;
                        }
                        ?>" name="kelompok" aria-describedby="sizing-addon2">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>