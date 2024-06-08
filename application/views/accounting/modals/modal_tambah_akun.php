<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
if (!empty($dataAkun)){
             foreach ($dataAkun as $k){				 
			 }}
?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($k->id)) {
                            echo 'Edit Data Akun';
                        } else { echo 'Penambahan Data Akun';}
                        ?></h4>
        </p>
    </div>
    <div class="modal-body">
        <form <?php if (empty($k->id)) {echo 'id="form-tambah-akun"';} else { echo 'id="form-update-akun"';}?>
            method="POST">
            <div class="form-group">
                <input type="hidden" name="id" value="<?php if (!empty($k->id)) { echo $k->id; } ?>">
            </div>
            <label class="col-sm-12 col-form-label">Nomor Akun</label>
            <div class="input-group form-group">
                <input type="text" class="form-control" placeholder="Nomor Akun" value="<?php
                        if (!empty($k->kode_akun)) {
                            echo $k->kode_akun;
                        }
                        ?>" name="kode_akun" aria-describedby="sizing-addon2" <?php if (!empty($k->id)) { echo 'readonly'; } ?>>

            </div>
            <label class="col-sm-12 col-form-label">Nama Akun</label>
            <div class="input-group form-group">
                <input type="text" class="form-control" placeholder="Nama Akun" value="<?php
                        if (!empty($k->nama_akun)) {
                            echo $k->nama_akun;
                        }
                        ?>" name="nama_akun" aria-describedby="sizing-addon2">

            </div>
            <label class="col-sm-12 col-form-label">Kelompok Akun</label>
            <div class="input-group form-group">
                <select name="kelompok" id="kelompok" class="form-control">
                    <option value="">Pilih Kelompok </option>
                    <?php
                     if (empty($k->kelompok)) { foreach ($dataKl as $kl) { ?>
                    <option <?php echo $kl == $kl->id_kelompok ? 'selected="selected"' : '' ?>
                        value="<?php echo $kl->id_kelompok ?>">
                        <?php echo $kl->kelompok  ?><?php } ?>
                    </option>
                    <?php
                                                                        } else {
                                                                            foreach ($dataKl as $ky) { ?>
                    <option value="<?php echo $ky->id_kelompok; ?>"
                        <?php if ($ky->id_kelompok == $k->kelompok) {  echo "selected='selected'"; } ?>>
                        <?php echo $ky->kelompok; ?>
                    </option>
                    <?php } } ?>
                </select>
            </div>
            <label class="col-sm-12 col-form-label">Type Akun</label>
            <div class="input-group form-group">
                <select name="type" id="type" class="form-control">
                    <option value="">Pilih Type </option>
                    <?php
     if (empty($k->type_akun)) { foreach ($dataType as $ty) { ?>
                    <option <?php echo $ty == $ty->type ? 'selected="selected"' : '' ?>
                        value="<?php echo $ty->id_type ?>">
                        <?php echo $ty->type  ?><?php } ?>
                    </option>
                    <?php } else {
        foreach ($dataType as $ts) {          ?>
                    <option value="<?php echo $ts->id_type; ?>"
                        <?php if ($ts->id_type == $k->type_akun) { echo "selected='selected'";} ?>>
                        <?php echo $ts->type; ?></option><?php }} ?>
                </select>
            </div>
            <label class="col-xl-12 col-form-label">Jenis Akun</label>
            <div class="input-group form-group">
                <select name="jns_beban" id="jns_beban" class="form-control">
                    <option value="">Pilih Jenis Beban </option>
                    <?php
     if (empty($k->jenis_beban)) { foreach ($dataJn as $jn) { ?>
                    <option <?php echo $jn == $jn->jenis_beban ? 'selected="selected"' : '' ?>
                        value="<?php echo $jn->id_jenis ?>">
                        <?php echo $jn->jenis_beban  ?><?php } ?>
                    </option>
                    <?php } else {
        foreach ($dataJn as $kj) {          ?>
                    <option value="<?php echo $kj->id_jenis; ?>"
                        <?php if ($kj->id_jenis == $k->jenis_beban) { echo "selected='selected'";} ?>>
                        <?php echo $kj->jenis_beban; ?></option><?php }} ?>
                </select>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>