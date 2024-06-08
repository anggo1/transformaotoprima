
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataJabatan)){
             foreach ($dataJabatan as $dataJabatan){				 
			 }}
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($dataJabatan->id_jabatan)) {
                            echo 'Edit Data Jabatan';
                        } else { echo 'Penambahan Data Jabatan';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($dataJabatan->id_jabatan)) {echo 'id="form-tambah-jabatan"';} else { echo 'id="form-update-jabatan"';}?> method="POST">
    <div class="form-group">
      <input type="hidden" name="id_jabatan" value="<?php if (!empty($dataJabatan->id_jabatan)) { echo $dataJabatan->id_jabatan; } ?>">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Nama Jabatan" value="<?php
                        if (!empty($dataJabatan->jabatan)) {
                            echo $dataJabatan->jabatan;
                        }
                        ?>" name="jabatan" aria-describedby="sizing-addon2">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>