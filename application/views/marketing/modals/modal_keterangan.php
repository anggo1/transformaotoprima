<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataKet)) {
      foreach ($dataKet as $dataKet) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($dataKet->id_ket_spk)) {
                                                    echo 'Edit Data Keterangan SPK';
                                                  } else {
                                                    echo 'Penambahan Keterangan SPK';
                                                  }
                                                  ?></h4>
    </p>
  </div>
  <div class="modal-body">
    <form <?php if (empty($dataKet->id_ket_spk)) {
            echo 'id="form-keterangan"';
          } else {
            echo 'id="form-update-keterangan"';
          } ?> method="POST">
      <div class="form-group">
        <input type="hidden" name="id_detail_note" value="<?php if (!empty($dataKet->id_ket_spk)) {
                                                        echo $dataKet->id_ket_spk;
                                                      } ?>">
      </div>
      <div class="input-group form-group">
        <input type="text" class="form-control" placeholder="Keterangan" value="<?php
                                                                                  if (!empty($dataKet->keterangan)) {
                                                                                    echo $dataKet->keterangan;
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