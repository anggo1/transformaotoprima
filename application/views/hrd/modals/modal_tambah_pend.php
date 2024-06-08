<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
if (!empty($dataPendidikan)){
             foreach ($dataPendidikan as $dataPendidikan){				 
			 }}
?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($dataPendidikan->id_pendidikan)) {
                            echo 'Edit Data pendidikan';
                        } else { echo 'Penambahan Data Pendidikan';}
                        ?></h4>
        </p>
    </div>
    <div class="modal-body">
        <form
            <?php if (empty($dataPendidikan->id_pendidikan)) {echo 'id="form-tambah-pendidikan"';} else { echo 'id="form-update-pendidikan"';}?>
            method="POST">
            <div class="form-group">
                <input type="hidden" name="id_pendidikan"
                    value="<?php if (!empty($dataPendidikan->id_pendidikan)) { echo $dataPendidikan->id_pendidikan; } ?>">
            </div>
            <div class="input-group form-group">
                <input type="text" class="form-control" placeholder="Nama Pendidikan" value="<?php
                        if (!empty($dataPendidikan->pendidikan)) {
                            echo $dataPendidikan->pendidikan;
                        }
                        ?>" name="pendidikan" aria-describedby="sizing-addon2">

            </div>

            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>