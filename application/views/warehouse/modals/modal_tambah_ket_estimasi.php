<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataSatuan)) {
      foreach ($dataSatuan as $dataSatuan) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($dataSatuan->id_satuan)) {
                                                    echo 'Edit Data Satuan';
                                                  } else {
                                                    echo 'Penambahan Data Satuan';
                                                  }
                                                  ?></h4>
    </p>
  </div>
  <div class="modal-body">
    <form <?php if (empty($dataSatuan->id_satuan)) {
            echo 'id="form-tambah-satuan"';
          } else {
            echo 'id="form-update-satuan"';
          } ?> method="POST">
      <div class="form-group">
        <input type="hidden" name="id_satuan" value="<?php if (!empty($dataSatuan->id_satuan)) {
                                                        echo $dataSatuan->id_satuan;
                                                      } ?>">
      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Kode satuan" value="<?php
                                                                                  if (!empty($dataSatuan->kode_satuan)) {
                                                                                    echo $dataSatuan->kode_satuan;
                                                                                  }
                                                                                  ?>" name="kode_satuan" aria-describedby="sizing-addon2">

      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Nama satuan" value="<?php
                                                                                  if (!empty($dataSatuan->satuan)) {
                                                                                    echo $dataSatuan->satuan;
                                                                                  }
                                                                                  ?>" name="satuan" aria-describedby="sizing-addon2">

      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
      </div>
    </form>
  </div>
</div>