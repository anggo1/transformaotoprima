
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataPool)){
          foreach ($dataPool as $dataPool){
			}}
      
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($dataPool->kode_kota)) {
                            echo 'Edit Data Pool';
                        } else { echo 'Penambahan Data Pool';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($dataPool->kode_kota)) {echo 'id="form-tambah-pool"';} else { echo 'id="form-update-pool"';}?> method="POST">
    <div class="form-group">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Kode Kota" value="<?php
                        if (!empty($dataPool->kode_kota)) {
                            echo $dataPool->kode_kota;
                        }
                        ?>" name="kode_kota" aria-describedby="sizing-addon2" onkeyup="this.value = this.value.toUpperCase();">	

    </div>
    
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Nama Kota" value="<?php
                        if (!empty($dataPool->nama_kota)) {
                            echo $dataPool->nama_kota;
                        }
                        ?>" name="nama_kota" aria-describedby="sizing-addon2">	

    </div>
    
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Wilayah" value="<?php
                        if (!empty($dataPool->nama_kota)) {
                            echo $dataPool->nama_kota;
                        }
                        ?>" name="wilayah" aria-describedby="sizing-addon2">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>