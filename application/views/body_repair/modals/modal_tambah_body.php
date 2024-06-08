<div class="col-12">
    <div class="modal-header">

        <?php
    if (!empty($dataBody)) {
    foreach ($dataBody as $body) {
    }
    }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($body->no_body)) { echo 'Edit Data Bus'; } else { echo 'Penambahan Data Bus / Body'; } ?></h4>
        </p>
    </div>
        <form <?php if (empty($body->no_body)) { echo 'id="form-tambah-body"'; } else { echo 'id="form-update-body"';} ?> method="POST">
    <div class="modal-body form">
            <div class="form-group row">
                <label class="col-sm-1 col-form-label">No Body</label>
                <div class="col-sm-3">
                    <input type="text" name="no_body" id="no_body" onblur="cariBodyl()" onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($body->no_body)) {
                                                          echo $body->no_body;
                                                        } ?>" <?php if (!empty($body->no_body)) {
                                                            echo 'readonly=readonly';
                                                          } ?> class="form-control" required>
                </div>
                <label class="col-sm-1 col-form-label">No Polisi</label>
                <div class="col-sm-3">
                    <input type="text" name="no_pol" id="no_pol" onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($body->no_pol)) {
                                                          echo $body->no_pol;
                                                        } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Karoseri</label>
                <div class="col-sm-3">
                    <input type="text" name="karoseri" id="karoseri" value="<?php if (!empty($body->karoseri)) {
                                                          echo $body->karoseri;
                                                        } ?>" class="form-control">
                </div>
            </div>
            <div class="form-group row">

                <label class="col-sm-1 col-form-label">Merk</label>
                <div class="col-sm-3">
                    <input type="text" name="merk" id="merk" value="<?php if (!empty($body->merk)) {
                                                          echo $body->merk;
                                                        } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Th Rangka</label>
                <div class="col-sm-3">
                    <input type="text" name="thn_rangka" id="thn_rangka" value="<?php if (!empty($body->thn_rangka)) {
                                                          echo $body->thn_rangka;
                                                        } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Th Body</label>
                <div class="col-sm-3">
                    <input type="text" name="thn_pembuatan" id="thn_pembuatan" value="<?php if (!empty($body->thn_pembuatan)) {
                                                          echo $body->thn_pembuatan;
                                                        } ?>" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-1 col-form-label">No Rangka</label>
                <div class="col-sm-3">
                    <input type="text" name="no_rangka" id="no_rangka" value="<?php if (!empty($body->no_rangka)) {
                                                          echo $body->no_rangka;
                                                        } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">No Mesin</label>
                <div class="col-sm-3">
                    <input type="text" name="no_mesin" id="no_mesin" value="<?php if (!empty($body->no_mesin)) {
                                                          echo $body->no_mesin;
                                                        } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Type</label>
                <div class="col-sm-3"> <input type="text" name="type" id="type"
                        value="<?php if (!empty($body->type)) { echo $body->type; } ?>" class="form-control">
                </div>
            </div>
            <div class="form-group row"> <label class="col-sm-1 col-form-label">Warna</label>
                <div class="col-sm-3">
                    <input type="text" name="warna" id="warna"
                        value="<?php if (!empty($body->warna)) { echo $body->warna; } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Kelas</label>
                <div class="col-sm-3">
                    <select name="kelas" id="kelas" class="form-control">
                        <option value="">Pilih Kelas
                        </option>
                        <?php if (empty($body->kelas)) {  foreach ($dataKl as $kl) {  ?>
                        <option <?php echo $kl == $kl->kelas ? 'selected="selected"' : '' ?>
                            value="<?php echo $kl->kelas ?>">
                            <?php echo $kl->kelas  ?><?php } ?>
                        </option>
                        <?php } else { foreach ($dataKl as $ks) {  ?>
                        <option value="<?php echo $ks->kelas; ?>"
                            <?php if ($ks->kelas == $body->kelas) { echo "selected='selected'"; } ?>>
                            <?php echo $ks->kelas; ?>
                        </option>
                        <?php } } ?>
                    </select>
                </div>
                <label class="col-sm-1 col-form-label">Strip/Ciri</label>
                <div class="col-sm-3">
                    <input type="text" name="strip" id="strip" value="<?php if (!empty($body->strip)) { echo $body->strip; } ?>" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-1 col-form-label">Pool</label>
                <div class="col-sm-3">
                    <select name="pool" id="pool" class="form-control">
                        <option value="">Pilih Pool
                        </option>
                        <?php if (empty($body->pool)) { foreach ($dataPool as $kp) { ?>
                        <option <?php echo $kp == $kp->kode_pool ? 'selected="selected"' : '' ?>
                            value="<?php echo $kp->kode_pool ?>">
                            <?php echo $kp->nama_pool  ?><?php } ?>
                        </option>
                        <?php } else { foreach ($dataPool as $kel) {   ?>
                        <option value="<?php echo $kel->kode_pool; ?>"
                            <?php if ($kel->kode_pool == $body->pool) { echo "selected='selected'";  } ?>>
                            <?php echo $kel->nama_pool; ?>
                        </option>
                        <?php } } ?>
                    </select>
                </div>
                <label class="col-sm-1 col-form-label">Kondisi</label>
                <div class="col-sm-3">
                    <select name="kondisi" class="form-control">
                        <option value="">Kondisi..</option>
                        <option value="BARU" <?php if (!empty($body->kondisi)) { echo $body->kondisi == 'BARU' ? 'selected' : '';  } ?>>BARU</option>
                        <option value="PERBAIKAN" <?php if (!empty($body->kondisi)) { echo $body->kondisi == 'PERBAIKAN' ? 'selected' : ''; }  ?>>PERBAIKAN</option>
                    </select>
                </div>
                <label class="col-sm-1 col-form-label">Status</label>
                <div class="col-sm-3">
                    <select name="status" class="form-control">
                        <option value="">Status..</option>
                        <option value="AKTIF" <?php if (!empty($body->status)) { echo $body->status == 'AKTIF' ? 'selected' : ''; } ?>>AKTIF</option>
                        <option value="PASIF" <?php if (!empty($body->status)) { echo $body->status == 'PASIF' ? 'selected' : ''; } ?>>PASIF</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-1 col-form-label">Rute Asli</label>
                <div class="col-sm-3">
                    <input type="text" name="rute_asli" id="rute_asli" value="<?php if (!empty($body->rute_asli)) { echo $body->rute_asli; } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Rute Aktif</label>
                <div class="col-sm-3">
                    <input type="text" name="rute_aktif" id="rute_aktif" value="<?php if (!empty($body->rute_aktif)) { echo $body->rute_aktif; } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Keterangan</label>
                <div class="col-sm-3">
                    <input type="text" name="keterangan" id="keterangan" value="<?php if (!empty($body->keterangan)) { echo $body->keterangan; } ?>" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-1 col-form-label">kode Trayek</label>
                <div class="col-sm-3">
                    <input type="text" name="kode_trayek" id="kode_trayek" value="<?php if (!empty($body->rute_asli)) { echo $body->rute_asli; } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Seat/Daya</label>
                <div class="col-sm-3">
                    <input type="text" name="seat_daya" id="seat_daya" value="<?php if (!empty($body->rute_aktif)) { echo $body->rute_aktif;  } ?>" class="form-control">
                </div>
                <label class="col-sm-1 col-form-label">Jenis Pelayanan</label>
                <div class="col-sm-3">
                    <input type="text" name="jns_pelayanan" id="jns_pelayanan" value="<?php if (!empty($body->keterangan)) { echo $body->keterangan; } ?>" class="form-control">
                </div>
            </div>
		<div class="form-group row">
                <label class="col-sm-1 col-form-label">Imei GPS</label>
                <div class="col-sm-3">
                    <input type="text" name="imei_gps" id="imei_gps" value="<?php if (!empty($body->imei_gps)) { echo $body->imei_gps; } ?>" class="form-control">
                </div>
		</div>
    </div>
    <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
    </div>
    </form>
</div>