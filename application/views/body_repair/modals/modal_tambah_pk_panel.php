<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataPk)) {
      foreach ($dataPk as $dataPk) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($dataPk->id)) {
                                                    echo 'Edit Kode Pekerjaan';
                                                  } else {
                                                    echo 'Penambahan Kode Pekerjaan';
                                                  }
                                                  ?></h4>
    </p>
  </div>
  <div class="modal-body">
    <form <?php if (empty($dataPk->id)) {
            echo 'id="form-tambah-pk"';
          } else {
            echo 'id="form-update-pk"';
          } ?> method="POST">
      <div class="form-group">
        <input type="hidden" name="id" value="<?php if (!empty($dataPk->id)) {
                                                        echo $dataPk->id;
                                                      } ?>">
      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Kode Pekerjaan" value="<?php
                                                                                  if (!empty($dataPk->kode)) {
                                                                                    echo $dataPk->kode;
                                                                                  }
                                                                                  ?>" name="kode" aria-describedby="sizing-addon2">

      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Keterangan" value="<?php
                                                                                  if (!empty($dataPk->keterangan)) {
                                                                                    echo $dataPk->keterangan;
                                                                                  }
                                                                                  ?>" name="keterangan" aria-describedby="sizing-addon2">

      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
      </div>
    </form>
  </div>
</div>