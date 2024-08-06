<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataPart)) {
      foreach ($dataPart as $part) {
      }
    }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($part->id_part)) {
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
        <form
            <?php if (empty($part->id_part)) { echo 'id="form-tambah-sparepart"'; } else { echo 'id="form-update-sparepart"';} ?>
            method="POST">
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
                                                    <input type="hidden" name="id_part" value="<?php if (!empty($part->id_part)) {echo $part->id_part;} ?>">
                                                <label class="col-sm-2 col-form-label">No Part</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="no_part" id="no_part" value="<?php if (!empty($part->no_part)) {
                                                          echo $part->no_part;
                                                        } ?>" class="form-control" required>
                                                        </div>
                                                        </div>
                                                        <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nama Part</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="nama_part" id="nama_part" value="<?php if (!empty($part->nama_part)) {
                                                          echo $part->nama_part;
                                                        } ?>" class="form-control" required>
                                                        </div>
                                                    <label class="col-sm-2 col-form-label">Name of Part</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="nama_part_e" id="nama_part_e" value="<?php if (!empty($part->nama_part_e)) {
                                                          echo $part->nama_part_e;
                                                        } ?>" class="form-control">
                                                        </div>
                                                        </div>
                                                        
                                                <div class="form-group row">
                                                    
                                                <label class="col-sm-2 col-form-label">Satuan</label>
                                                <div class="col-sm-4">
                                                    <select name="satuan" class="form-control">
                                                        <option value="">Pilih Satuan
                                                        </option>
                                                        <?php
                                                                    if (empty($part->satuan)) {
                                                                        foreach ($dataSatuan as $sat) {
                                                                    ?>
                                                        <option
                                                            <?php echo $sat == $sat->satuan ? 'selected="selected"' : '' ?>
                                                            value="<?php echo $sat->satuan ?>">
                                                            <?php echo $sat->satuan  ?><?php } ?>
                                                        </option>
                                                        <?php
                                                                        } else {
                                                                            foreach ($dataSatuan as $st) { ?>
                                                        <option value="<?php echo $st->satuan; ?>"
                                                            <?php if ($st->satuan == $part->satuan) { echo "selected='selected'"; } ?>>
                                                            <?php echo $st->satuan; ?>
                                                        </option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                                        <label class="col-sm-2 col-form-label">Kelompok</label>
                                                        <div class="col-sm-4">
                                                            <select name="kelompok" class="form-control">
                                                                <option value="">Pengelompokan.....
                                                                </option>
                                                                <?php
                                                            if (empty($part->kelompok)) {
                                                                foreach ($dataKelompok as $kp) {
                                                            ?>
                                                                <option
                                                                    <?php echo $kp == $kp->kelompok ? 'selected="selected"' : '' ?>
                                                                    value="<?php echo $kp->kelompok ?>">
                                                                    <?php echo $kp->kelompok  ?><?php } ?>
                                                                </option>
                                                                <?php
                                                                } else {
                                                                    foreach ($dataKelompok as $kel) { ?>
                                                                <option
                                                                    value="<?php echo $kel->kelompok; ?>"
                                                                    <?php if ($kel->kelompok == $part->kelompok) { echo "selected='selected'"; } ?>>
                                                                    <?php echo $kel->kelompok; ?>
                                                                </option>
                                                                <?php } } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Type</label>
                                                        <div class="col-sm-4">
                                                            <select name="type" class="form-control">
                                                                <option value="">Pilih Type
                                                                </option>
                                                                <?php
                                                                    if (empty($part->type)) {
                                                                        foreach ($dataType as $ty) {
                                                                    ?>
                                                                <option
                                                                    <?php echo $ty == $ty->type ? 'selected="selected"' : '' ?>
                                                                    value="<?php echo $ty->type ?>">
                                                                    <?php echo $ty->type  ?><?php } ?>
                                                                </option>
                                                                <?php
                                                                        } else {
                                                                            foreach ($dataType as $tp) { ?>
                                                                <option
                                                                    value="<?php echo $tp->type; ?>"
                                                                    <?php if ($tp->type == $part->type) {  echo "selected='selected'"; } ?>>
                                                                    <?php echo $tp->type; ?>
                                                                </option>
                                                                <?php } } ?>
                                                            </select>
                                                        </div>

                                                        <label class="col-sm-2 col-form-label">Kategori</label>
                                                        <div class="col-sm-4">
                                                            <select name="kategori" class="form-control">
                                                                <option value="">Pilih Kategori
                                                                </option>
                                                                <?php
                                                                    if (empty($part->kategori)) {
                                                                        foreach ($dataKategori as $kt) {
                                                                    ?>
                                                                <option
                                                                    <?php echo $kt == $kt->kategori ? 'selected="selected"' : '' ?>
                                                                    value="<?php echo $kt->kategori ?>">
                                                                    <?php echo $kt->kategori  ?><?php } ?>
                                                                </option>
                                                                <?php
                                                                        } else {
                                                                            foreach ($dataKategori as $tk) { ?>
                                                                <option
                                                                    value="<?php echo $tk->kategori; ?>"
                                                                    <?php if ($tk->kategori == $part->kategori) {  echo "selected='selected'"; } ?>>
                                                                    <?php echo $tk->kategori; ?>
                                                                </option>
                                                                <?php } } ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Supplier</label>
                                        <div class="col-sm-4">
                                            <select name="supplier" class="form-control">
                                                <option value="">Supplier...
                                                </option>
                                                <?php
                                                            if (empty($part->kode_sup)) {
                                                                foreach ($dataSupplier as $sp) {
                                                            ?>
                                                <option
                                                    <?php echo $sp == $sp->kode_nama ? 'selected="selected"' : '' ?>
                                                    value="<?php echo $sp->kode_nama ?>">
                                                    <?php echo $sp->kode_nama  ?><?php } ?>
                                                </option>
                                                <?php
                                                                } else {
                                                                    foreach ($dataSupplier as $sup) {          ?>
                                                <option value="<?php echo $sup->kode_nama; ?>"
                                                    <?php if ($sup->kode_nama == $part->kode_sup) {
                                                                                                                                echo "selected='selected'";
                                                                                                                            } ?>>
                                                    <?php echo $sup->kode_nama; ?>
                                                </option>
                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Lokasi</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="lokasi" id="lokasi" value="<?php if (!empty($part->lokasi)) {
                                                          echo $part->lokasi;
                                                        } ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Stok</label>
                                        <div class="col-sm-4">
                                            <input type="number" name="stok" id="stok" value="<?php if (!empty($part->stok)) {
                                                          echo $part->stok;
                                                        } ?>" class="form-control">
                                        </div>
                                        <label class="col-sm-2 col-form-label">Harga Baru</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="harga_baru" id="harga_baru" onkeyup="formatNumber(this)"onchange="formatNumber(this);" onclick="formatNumber(this);"
                                             value="<?php if (!empty($part->harga_baru)) {
                                                          echo $part->harga_baru;
                                                        }else{ echo '0';} ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Std Pakai</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="std_pakai" id="std_pakai" value="<?php if (!empty($part->std_pakai)) {
                                                          echo $part->std_pakai;
                                                        } ?>" class="form-control">
                                        </div>
                                        <label class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="ket" id="ket" value="<?php if (!empty($part->ket)) {
                                                          echo $part->ket;
                                                        } ?>" class="form-control">
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