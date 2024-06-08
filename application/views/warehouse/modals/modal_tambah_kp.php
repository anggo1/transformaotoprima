<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataKelompok)) {
      foreach ($dataKelompok as $dataKelompok) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($dataKelompok->id_kelompok)) {
                                                    echo 'Edit Data Pengelompokan';
                                                  } else {
                                                    echo 'Penambahan Data Pengelompokan';
                                                  }
                                                  ?></h4>
    </p>
  </div>
  <div class="modal-body">
    <form <?php if (empty($dataKelompok->id_kelompok)) {
            echo 'id="form-tambah-kelompok"';
          } else {
            echo 'id="form-update-kelompok"';
          } ?> method="POST">
      <div class="form-group">
        <input type="hidden" name="id_kelompok" value="<?php if (!empty($dataKelompok->id_kelompok)) {
                                                          echo $dataKelompok->id_kelompok;
                                                        } ?>">
      </div>
      <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Kode Kelompok" value="<?php
                        if (!empty($dataKelompok->kode_kelompok)) {
                            echo $dataKelompok->kode_kelompok;
                        }
                        ?>" name="kode_kelompok" aria-describedby="sizing-addon2" maxlength="1"
                        onkeyup="this.value = this.value.toUpperCase();">	
                        </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Nama Kelompok" value="<?php
                                                                                    if (!empty($dataKelompok->kelompok)) {
                                                                                      echo $dataKelompok->kelompok;
                                                                                    }
                                                                                    ?>" name="kelompok" aria-describedby="sizing-addon2">

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