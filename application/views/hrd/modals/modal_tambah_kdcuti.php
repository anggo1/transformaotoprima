<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
if (!empty($dataKdcuti)){
             foreach ($dataKdcuti as $dataKdcuti){				 
			 }}
?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($dataKdcuti->id_kodecuti)) {
                            echo 'Edit Kode Cuti';
                        } else { echo 'Penambahan Kode Cuti';}
                        ?></h4>
        </p>
    </div>
    <div class="modal-body">
        <form
            <?php if (empty($dataKdcuti->id_kodecuti)) {echo 'id="form-tambah-kdcuti"';} else { echo 'id="form-update-kdcuti"';}?>
            method="POST">
            <div class="form-group">
                <input type="hidden" name="id_kodecuti"
                    value="<?php if (!empty($dataKdcuti->id_kodecuti)) { echo $dataKdcuti->id_kodecuti; } ?>">
            </div>
            <div class="input-group form-group">
                <input type="text" class="form-control" placeholder="Kode Cuti" value="<?php
                        if (!empty($dataKdcuti->kode)) {
                            echo $dataKdcuti->kode;
                        }
                        ?>" name="kode" aria-describedby="sizing-addon2">

            </div>
            <div class="input-group form-group">
                <input type="text" class="form-control" placeholder="Nama Cuti" value="<?php
                        if (!empty($dataKdcuti->nama_cuti)) {
                            echo $dataKdcuti->nama_cuti;
                        }
                        ?>" name="nama_cuti" aria-describedby="sizing-addon2">

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