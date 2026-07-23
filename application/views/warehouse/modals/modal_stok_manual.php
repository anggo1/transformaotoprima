<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataPart)) {
      foreach ($dataPart as $part) {
      }
    }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;">Edit Stok Sparepart
            <?php echo $this->session->userdata['lokasi']; ?></h4>
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

                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="col-form-label">Stok Barang</label>
                                                        <input type="text" name="stok" id="stok" value="<?php 
                                                            $lokasi=$this->session->userdata['lokasi'];
                                                            if($lokasi=='Cibitung'){
                                                                if (!empty($part->stok_cbt)) { echo $part->stok_cbt;} else { echo"0";}
                                                            }if($lokasi=='Jakarta'){
                                                                if (!empty($part->stok_jkt)) { echo $part->stok_jkt;} else { echo"0";}
                                                            }if($lokasi=='Surabaya'){
                                                                if (!empty($part->stok_sby)) { echo $part->stok_sby;} else { echo"0";}
                                                            }
                                                            ?>" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="col-form-label">Lokasi</label>
                                                        <input type="text" name="lokasi" id="lokasi" value="<?php 
                                                            $lokasi=$this->session->userdata['lokasi'];
                                                            if($lokasi=='Jakarta'){
                                                                if (!empty($part->lok_jkt)) { echo $part->lok_jkt;}
                                                            }if($lokasi=='Cibitung'){
                                                                if (!empty($part->lok_cbt)) { echo $part->lok_cbt;}
                                                            }if($lokasi=='Surabaya'){
                                                                if (!empty($part->lok_sby)) { echo $part->lok_sby;}
                                                            }
                                                            ?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <input type="hidden" name="id_part"
                                                            value="<?php if (!empty($part->id_part)) { echo $part->id_part;} ?>">
                                                        <input type="hidden" name="user" id="user"
                                                            value="<?php echo $this->session->userdata['full_name']; ?>"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save
                                        changes</button>
                                </div>
        </form>
    </div>
</div>