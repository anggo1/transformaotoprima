<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataPart)) {
      foreach ($dataPart as $part) {
      }
    }
    ?>
<p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($part->id_barang)) {
                                                    echo 'Edit Data Barang';
                                                  } else {
                                                    echo 'Penambahan Data Barang';
                                                  }
                                                  ?></h4>
    </p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body form">
            <form id="form-update-harga" method="POST">
                    <div class="row">
                        <div class="col-sm-12" data-spy="scroll" data-offset="0">
                            <div class="panel panel-default">
                                <section class="content">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12 ">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">No
                                                                Part</label>
                                                            <div class="col-sm-4">: 
                                                                <?php if (!empty($part->no_part)) {
                                                          echo $part->no_part;
                                                        } ?>
                                                            </div>
                                                            <label class="col-sm-2 col-form-label">Nama
                                                                Part</label>
                                                            <div class="col-sm-4">: 
                                                                <?php if (!empty($part->nama_part)) { echo $part->nama_part;}?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Satuan</label>
                                                            <div class="col-sm-4">: 
                                                            <?php if (!empty($part->satuan)) { echo $part->satuan;}?>
                                                            </div>

                                                            <label class="col-sm-2 col-form-label">Type</label>
                                                            <div class="col-sm-4">: 
                                                            <?php if (!empty($part->type_mesin)) { echo $part->type_mesin;}?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Kategori</label>
                                                    <div class="col-sm-4">: 
                                                    <?php if (!empty($part->kategori)) { echo $part->kategori;}?>
                                                    </div>
                                                    <label class="col-sm-2 col-form-label">Kelompok</label>
                                                    <div class="col-sm-4">: 
                                                    <?php if (!empty($part->kelompok)) { echo $part->kelompok;}?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Harga Awal</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="hrg_awal" id="hrg_awal" value="<?php if (!empty($part->hrg_awal)) { echo $part->hrg_awal;}?>" class="form-control">
                                            </div>
                                            <label class="col-sm-2 col-form-label">Harga 1</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="hrg_1" id="hrg_1" value="<?php if (!empty($part->hrg_1)) { echo $part->hrg_1;}?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Harga 2</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="hrg_2" id="hrg_2" value="<?php if (!empty($part->hrg_2)) { echo $part->hrg_2;}?>" class="form-control">
                                                <input type="hidden" name="id_barang" value="<?php if (!empty($part->id_barang)) { echo $part->id_barang;} ?>">
                                                <input type="hidden" name="no_part" value="<?php if (!empty($part->no_part)) { echo $part->no_part;} ?>">
			    								<input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
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