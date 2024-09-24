<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataLapor)) {
      foreach ($dataLapor as $dataLapor) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($dataLapor->id)) {
                                                    echo 'Edit Keterangan Laporan';
                                                  } else {
                                                    echo 'Penambahan Keterangan Laporan';
                                                  }
                                                  ?></h4>
    </p>
  </div>
  <div class="modal-body">
    <form <?php if (empty($dataLapor->id)) {
            echo 'id="form-tambah-laporan"';
          } else {
            echo 'id="form-update-laporan"';
          } ?> method="POST">
      <div class="form-group">
        <input type="hidden" name="id" value="<?php if (!empty($dataLapor->id)) {
                                                        echo $dataLapor->id;
                                                      } ?>">
      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Kode" value="<?php
                                                                                  if (!empty($dataLapor->kode)) {
                                                                                    echo $dataLapor->kode;
                                                                                  }
                                                                                  ?>" name="kode" aria-describedby="sizing-addon2">

      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Keterangan" value="<?php
                                                                                  if (!empty($dataLapor->keterangan)) {
                                                                                    echo $dataLapor->keterangan;
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