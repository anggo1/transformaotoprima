
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataKelas)){
          foreach ($dataKelas as $dataKelas){
			}}
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($dataKelas->id_kelas)) {
                            echo 'Edit Data Kelas Pelayanan';
                        } else { echo 'Penambahan Data Kelas Pelayanan';}
                        ?></h4>
					</p></div>
          <div class="modal-body">
  <form <?php if (empty($dataKelas->id_kelas)) {echo 'id="form-tambah-kelas"';} else { echo 'id="form-update-kelas"';}?> method="POST">
    <div class="form-group">
      <input type="hidden" name="id_kelas" value="<?php if (!empty($dataKelas->id_kelas)) { echo $dataKelas->id_kelas; } ?>">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Kelas Pelayanan" value="<?php
                        if (!empty($dataKelas->kelas)) {
                            echo $dataKelas->kelas;
                        }
                        ?>" name="kelas" aria-describedby="sizing-addon2" onkeyup="this.value = this.value.toUpperCase();">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>