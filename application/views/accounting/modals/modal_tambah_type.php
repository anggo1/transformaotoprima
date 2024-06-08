
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataType)){
             foreach ($dataType as $k){				 
			 }}
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($k->id_type)) {
                            echo 'Edit Type';
                        } else { echo 'Penambahan Type';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($k->id_type)) {echo 'id="form-tambah-type"';} else { echo 'id="form-update-type"';}?> method="POST">
    <div class="form-group">
      <input type="hidden" name="id_type" value="<?php if (!empty($k->id_type)) { echo $k->id_type; } ?>">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Type" value="<?php
                        if (!empty($k->type)) {
                            echo $k->type;
                        }
                        ?>" name="type" aria-describedby="sizing-addon2">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>