<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataType)) {
      foreach ($dataType as $dataType) {
      }
    }
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($dataType->id_type)) {
                                                    echo 'Edit  Jenis';
                                                  } else {
                                                    echo 'Penambahan Data Jenis';
                                                  }
                                                  ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>

  </div>
  <div class="modal-body form">

    <form <?php if (empty($dataType->id_type)) {
            echo 'id="form-tambah-type"';
          } else {
            echo 'id="form-update-type"';
          } ?> method="POST">
      <div class="input-group form-group">
        <span class="input-group-addon" id="sizing-addon2">
          <i class="glyphicon glyphicon-user"></i>
        </span>
        <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Kode Jenis" value="<?php
                        if (!empty($dataType->kode_type)) {
                            echo $dataType->kode_type;
                        }
                        ?>" name="kode_type" aria-describedby="sizing-addon2" maxlength="2"
                        onkeyup="this.value = this.value.toUpperCase();">	

    </div>
        <input type="hidden" name="id_type" value="<?php if (!empty($dataType->id_type)) {
                                                      echo $dataType->id_type;
                                                    } ?>">
        <input type="text" class="form-control" placeholder="Nama Jenis" value="<?php
                                                                                if (!empty($dataType->type_mesin)) {
                                                                                  echo $dataType->type_mesin;
                                                                                }
                                                                                ?>" name="type" aria-describedby="sizing-addon2">
      </div>

      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
      </div>
    </form>
  </div>
</div>