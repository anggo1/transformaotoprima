
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataDepartement)){
             foreach ($dataDepartement as $dataDepartement){				 
			 }}
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($dataDepartement->id_departement)) {
                            echo 'Edit Data Departement';
                        } else { echo 'Penambahan Data Departement';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($dataDepartement->id_departement)) {echo 'id="form-tambah-departement"';} else { echo 'id="form-update-departement"';}?> method="POST">
    <div class="form-group">
      <input type="hidden" name="id_departement" value="<?php if (!empty($dataDepartement->id_departement)) { echo $dataDepartement->id_departement; } ?>">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Nama Departement" value="<?php
                        if (!empty($dataDepartement->departement)) {
                            echo $dataDepartement->departement;
                        }
                        ?>" name="departement" aria-describedby="sizing-addon2">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>