<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
if (!empty($dataMesin)){
             foreach ($dataMesin as $dataMesin){				 
			 }}
?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($dataMesin->id)) {
                            echo 'Edit Data Mesin Absen';
                        } else { echo 'Penambahan Data Mesin Absen';}
                        ?></h4>
        </p>
    </div>
    <div class="modal-body">
        <form <?php if (empty($dataMesin->id)) {echo 'id="form-tambah-mesin"';} else { echo 'id="form-update-mesin"';}?>
            method="POST">
            <div class="form-group">
                <input type="hidden" name="id" value="<?php if (!empty($dataMesin->id)) { echo $dataMesin->id; } ?>">
            </div>
            <div class="input-group form-group">
                <input type="text" class="form-control" placeholder="IP Mesin" value="<?php
                        if (!empty($dataMesin->ip)) {
                            echo $dataMesin->ip;
                        }
                        ?>" name="ip" aria-describedby="sizing-addon2">

            </div>
            <div class="input-group form-group">
                <input type="text" class="form-control" placeholder="Nama Mesin" value="<?php
                        if (!empty($dataMesin->nama_mesin)) {
                            echo $dataMesin->nama_mesin;
                        }
                        ?>" name="nama_mesin" aria-describedby="sizing-addon2">

            </div>
            <div class="input-group form-group">
                <input type="text" class="form-control" placeholder="Com Key" value="<?php
                        if (!empty($dataMesin->nama_mesin)) {
                            echo $dataMesin->pass;
                        }
                        ?>" name="pass" aria-describedby="sizing-addon2" value="0">

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