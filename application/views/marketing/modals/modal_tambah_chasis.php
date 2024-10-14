<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataChasis)) {
    foreach ($dataChasis as $ch) {
    }
    }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;">
            <?php if (!empty($dataChasis)) { echo 'Edit Data Chasis'; } else { echo 'Penambahan Data Chasis'; } ?></h4>
        </p>
    </div>
    <div class="modal-body form">
    <form
            <?php if (empty($dataChasis)) { echo 'id="form-tambah-chasis"'; } else { echo 'id="form-update-chasis"';} ?>
            method="POST">
            <div class="col-sm-12" data-spy="scroll" data-offset="0">
                    <div class="panel panel-default">
                        <section class="content">
                            <div class="container-fluid">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tanggal Masuk</label>
                <div class="col-sm-4">
                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input type="text" name="tgl_masuk" id="tgl_masuk" class="form-control tgl_masuk datetimepicker"
                            data-toggle="datetimepicker" data-target=".tgl_masuk" data-format="yyy-mm-dd" value="<?php
                            if (!empty($ch->tgl_masuk)) {
                            $tgl_masuk = $ch->tgl_masuk;
                            $tgl1 = explode('-', $tgl_masuk);
                            $tgl_masuknya = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
                            echo $tgl_masuknya;
                            }
                            ?>" required>

                        <div class="input-group-append" data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <label class="col-sm-2 col-form-label">Retail</label>
                <div class="col-sm-4">
                    <input type="text" name="retail" id="retail" value="<?php if (!empty($ch->retail)) {
                                                          echo $ch->retail;
                                                        } ?>" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Type</label>
                <div class="col-sm-4">
                    <input type="text" name="type" id="type" onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->type)) {
                                                          echo $ch->type;
                                                        } ?>" class="form-control">
                </div>

                <label class="col-sm-2 col-form-label">No Rangka</label>
                <div class="col-sm-4">
                    <input type="text" name="no_rangka" id="no_rangka" onkeyup="this.value = this.value.toUpperCase();"
                        value="<?php if (!empty($ch->no_rangka)) {
                                                          echo $ch->no_rangka;
                                                        } ?>" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">No Mesin</label>
                <div class="col-sm-4">
                    <input type="text" name="no_mesin" id="no_mesin" onkeyup="this.value = this.value.toUpperCase();"
                        value="<?php if (!empty($ch->no_mesin)) {
                                                          echo $ch->no_mesin;
                                                        } ?>" class="form-control">
                </div>
                <label class="col-sm-2 col-form-label">Sales</label>
                <div class="col-sm-4">
                    <input type="text" name="sales" id="sales" onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->sales)) {
                                                          echo $ch->sales;
                                                        } ?>" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Gesekan</label>
                <div class="col-sm-4">
                    <input type="text" name="gesekan" id="gesekan" onkeyup="this.value = this.value.toUpperCase();"
                        value="<?php if (!empty($ch->gesekan)) {
                                                          echo $ch->gesekan;
                                                        } ?>" class="form-control">
                </div>
                <label class="col-sm-2 col-form-label">Tahun Produksi</label>
                <div class="col-sm-4">

                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input type="text" name="thn_produksi" id="thn_produksi" class="form-control"
                            data-toggle="datetimepicker" data-target=".thn_produksi" data-format="yyy" value="<?php if (!empty($ch->thn_produksi)) {
                                                          echo $ch->thn_produksi;}?>">

                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Customer</label>
                <div class="col-sm-4"> <input type="text" name="nama_customer" id="nama_customer"
                        onkeyup="this.value = this.value.toUpperCase();"
                        value="<?php if (!empty($ch->nama_customer)) { echo $ch->nama_customer; } ?>"
                        class="form-control">
                </div>
                <label class="col-sm-2 col-form-label">Pengiriman</label>
                <div class="col-sm-4">
                    <input type="text" name="pengiriman" id="pengiriman"
                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->pengiriman)) {
                                                          echo $ch->pengiriman;
                                                        } ?>" class="form-control">
                </div>

            </div>
            <input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>"
                class="form-control">
            <input type="hidden" name="id_chasis" id="id_chasis" value="<?php if (!empty($ch->id_chasis)) {
                                                          echo $ch->id_chasis;
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
<!-- Tempusdominus Bootstrap 4 -->