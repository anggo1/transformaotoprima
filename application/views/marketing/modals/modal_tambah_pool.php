
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataPool)){
          foreach ($dataPool as $dataPool){
			}}
      
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($dataPool->kode_pool)) {
                            echo 'Edit Data Pool';
                        } else { echo 'Penambahan Data Pool';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($dataPool->kode_pool)) {echo 'id="form-tambah-pool"';} else { echo 'id="form-update-pool"';}?> method="POST">
    <div class="form-group">
          </div>
	  <div class="input-group form-group">
      <input type="hidden" name="kode_pool" value="<?php if (!empty($dataPool->kode_pool)) { echo $dataPool->kode_pool; } ?>">
      <input type="text" class="form-control" placeholder="Pool" value="<?php
                        if (!empty($dataPool->nama_pool)) {
                            echo $dataPool->nama_pool;
                        }
                        ?>" name="nama_pool" aria-describedby="sizing-addon2" onkeyup="this.value = this.value.toUpperCase();">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>