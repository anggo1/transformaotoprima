<div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataPart)) {
      foreach ($dataPart as $part) {
      }
    }
    ?>
<p></span>
    <h4 style="display:block; text-align:center;">Edit Stok Barang</h4>
    </p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body form">
            <form id="form-update-stok" method="POST">
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
                                            <label class="col-sm-2 col-form-label">Stok Aktif</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="stok_a" id="stok_a" value="<?php if (!empty($part->stok_a)) { echo $part->stok_a;} else { echo"0";}?>" class="form-control">
                                            </div>
                                            <label class="col-sm-2 col-form-label">Stok Pasif</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="stok_p" id="stok_p" value="<?php if (!empty($part->stok_p)) { echo $part->stok_p;}  else { echo"0";}?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <input type="hidden" name="id_barang" value="<?php if (!empty($part->id_barang)) { echo $part->id_barang;} ?>">
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