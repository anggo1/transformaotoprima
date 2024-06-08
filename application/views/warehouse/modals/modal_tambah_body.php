<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataBody)) {
      foreach ($dataBody as $body) {
      }
    }
    ?>
<p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($body->no_body)) {
                                                    echo 'Edit Data Bus';
                                                  } else {
                                                    echo 'Penambahan Data Bus';
                                                  }
                                                  ?></h4>
    </p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body form">
                    <div class="row">
                                                    <form <?php if (empty($body->no_body)) { echo 'id="form-tambah-body'; } else { echo 'id="form-update-body"';} ?> method="POST">
                                            <div class="col-12 ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">No Body</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="no_body" id="no_body" onblur="cariBodyl()" value="<?php if (!empty($body->no_body)) {
                                                          echo $body->no_body;
                                                        } ?>" <?php if (!empty($body->no_body)) {
                                                            echo 'readonly=readonly';
                                                          } ?> class="form-control">
                                                            </div>
                                                            <label class="col-sm-2 col-form-label">Type</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="type" id="type" value="<?php if (!empty($body->type)) {
                                                          echo $body->type;
                                                        } ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Thn Rangka</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="thn_rangka" id="thn_rangka" value="<?php if (!empty($body->thn_rangka)) {
                                                          echo $body->thn_rangka;
                                                        } ?>" class="form-control">
                                                            </div>
                                                            <label class="col-sm-2 col-form-label">Thn Pembuatan</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="thn_pembuatan" id="thn_pembuatan" value="<?php if (!empty($body->thn_pembuatan)) {
                                                          echo $body->thn_pembuatan;
                                                        } ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Karoseri</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="karoseri" id="karoseri" value="<?php if (!empty($body->karoseri)) {
                                                          echo $body->karoseri;
                                                        } ?>" class="form-control">
                                                            </div>
                                                            <label class="col-sm-2 col-form-label">Warna</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="warna" id="warna" value="<?php if (!empty($body->warna)) {
                                                          echo $body->warna;
                                                        } ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Kelas</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="kelas" id="kelas" value="<?php if (!empty($body->kelas)) {
                                                          echo $body->kelas;
                                                        } ?>" class="form-control">
                                                            </div>
                                                            <label class="col-sm-2 col-form-label">Strip/Ciri</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="strip" id="strip" value="<?php if (!empty($body->strip)) {
                                                          echo $body->strip;
                                                        } ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Keterangan</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" name="keterangan" id="keterangan" value="<?php if (!empty($body->keterangan)) {
                                                          echo $body->keterangan;
                                                        } ?>" class="form-control">
                                                            </div>
                                                        </div>
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