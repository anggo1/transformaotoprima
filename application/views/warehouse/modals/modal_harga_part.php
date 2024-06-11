<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataPart)) {
      foreach ($dataPart as $part) {
      }
    }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;">Perubahan Harga Barang</h4>
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
                                                <table width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td width="20%">No Part</td>
                                                            <td width="30%">: <?php if (!empty($part->no_part)) { echo $part->no_part;} ?></td>
                                                            <td width="20%">Nama Part</td>
                                                            <td width="30%">: <?php if (!empty($part->nama_part)) { echo $part->nama_part;}?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Satuan</td>
                                                            <td>: <?php if (!empty($part->satuan)) { echo $part->satuan;}?></td>
                                                            <td>Type</td>
                                                            <td>: <?php if (!empty($part->type)) { echo $part->type;}?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kategori</td>
                                                            <td>: <?php if (!empty($part->kategori)) { echo $part->kategori;}?></td>
                                                            <td>Kelompok</td>
                                                            <td>:<?php if (!empty($part->kelompok)) { echo $part->kelompok;}?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Harga Awal</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="harga_baru" id="harga_baru"
                                            value="<?php if (!empty($part->harga_baru)) { echo number_format($part->harga_baru);}?>" onkeyup="formatNumber(this)"onchange="formatNumber(this);"
                                            class="form-control">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Diskon</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="diskon" id="diskon"
                                            value="<?php if (!empty($part->diskon)) { echo $part->diskon;}?>"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Harga Net</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="harga_net" id="harga_net" onkeyup="formatNumber(this)"onchange="formatNumber(this);"
                                            value="<?php if (!empty($part->harga_net)) { echo number_format($part->harga_net);}?>"
                                            class="form-control">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Harga Rata-rata</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="harga_rata" id="harga_rata" onkeyup="formatNumber(this)"onchange="formatNumber(this);"
                                            value="<?php if (!empty($part->harga_rata)) { echo number_format($part->harga_rata);}?>"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Harga Valid</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="harga_valid" id="harga_valid" onkeyup="formatNumber(this)"onchange="formatNumber(this);"
                                            value="<?php if (!empty($part->harga_valid)) { echo number_format($part->harga_valid);}?>"
                                            class="form-control">
                                    </div>
                                    <label class="col-sm-2 col-form-label">PPN</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="ppn" id="ppn"
                                            value="<?php if (!empty($part->ppn)) { echo $part->ppn;}?>"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Keterangan Harga</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="ket_harga" id="ket_harga"
                                            value="<?php if (!empty($part->ket_harga)) { echo $part->ket_harga;}?>"
                                            class="form-control">
                                        <input type="hidden" name="id_part"
                                            value="<?php if (!empty($part->id_part)) { echo $part->id_part;} ?>">
                                        <input type="hidden" name="no_part"
                                            value="<?php if (!empty($part->no_part)) { echo $part->no_part;} ?>">
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
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
            </div>
        </form>
    </div>
</div>