<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataPo)) {
      foreach ($dataPo as $dataPo) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($dataPo->id_kode_po)) {
                                                    echo 'Edit Data Kode PO';
                                                  } else {
                                                    echo 'Penambahan Data Kode PO';
                                                  }
                                                  ?></h4>
    </p>
  </div>
  <div class="modal-body">
    <form <?php if (empty($dataPo->id_kode_po)) {
            echo 'id="form-tambah-kode-po"';
          } else {
            echo 'id="form-update-kode-po"';
          } ?> method="POST">
      <div class="form-group">
        <input type="hidden" name="id_kode_po" value="<?php if (!empty($dataPo->id_kode_po)) {
                                                        echo $dataPo->id_kode_po;
                                                      } ?>">
      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Kode Po" value="<?php
                                                                                  if (!empty($dataPo->kode_po)) {
                                                                                    echo $dataPo->kode_po;
                                                                                  }
                                                                                  ?>" name="kode_po" aria-describedby="sizing-addon2">

      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Keterangan Kode PO" value="<?php
                                                                                  if (!empty($dataPo->keterangan)) {
                                                                                    echo $dataPo->keterangan;
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