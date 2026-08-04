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
            <?php if (!empty($dataChasis)) {
                echo 'Edit Data Chasis Retail';
            } else {
                echo 'Penambahan Data Chasis Retail';
            } ?></h4>
        </p>
    </div>
    <div class="modal-body form">
        <form <?php if (empty($dataChasis)) {
                    echo 'id="form-tambah-chasis-retail"';
                } else {
                    echo 'id="form-update-chasis-retail"';
                } ?> method="POST">
            <div class="col-sm-12" data-spy="scroll" data-offset="0">
                <div class="panel panel-default">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tanggal Masuk</label>
                                <div class="col-sm-4">
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="tgl_masuk" id="tgl_masuk"
                                            class="form-control tgl_masuk datetimepicker" data-toggle="datetimepicker"
                                            data-target=".tgl_masuk" data-format="yyy-mm-dd" value="<?php
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
                                <label class="col-sm-2 col-form-label">No rangka</label>
                                <div class="col-sm-4">
                                <input type="hidden" name="kode_cus" id="kode_cus" value="<?php echo !empty($ch->kode_cus) ? $ch->kode_cus : ''; ?>" class="form-control">
                                <input type="hidden" name="chasis_id" id="chasis_id" value="<?php echo !empty($ch->chasis_id) ? $ch->chasis_id : ''; ?>" class="form-control">
                                    <input type="text" name="no_rangka" id="no_rangka" value="<?php if (!empty($ch->no_rangka)) {
                                                                                            echo $ch->no_rangka;
                                                                                        } ?>" class="form-control cari-chasis">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama Pemesan</label>
                                <div class="col-sm-4">
                                    <input type="text" name="nama_pemesan" id="nama_pemesan" class="form-control cari-customer" 
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->nama_pemesan)) {
                                                                                                    echo $ch->nama_pemesan;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                                <label class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-4">

                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="alamat_pemesan" id="alamat_pemesan"
                                            class="form-control" value="<?php if (!empty($ch->alamat_pemesan)) {
                                                                            echo $ch->alamat_pemesan;
                                                                        } ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">No NPWP</label>
                                <div class="col-sm-4">
                                    <input type="text" name="no_npwp" id="no_npwp"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->no_npwp)) {
                                                                                                    echo $ch->no_npwp;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                                <label class="col-sm-2 col-form-label">Nama NPWP</label>
                                <div class="col-sm-4">

                                    <input type="text" name="nama_npwp" id="nama_npwp" class="form-control"
                                        value="<?php if (!empty($ch->nama_npwp)) {
                                                                                                                        echo $ch->nama_npwp;
                                                                                                                    } ?>">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Alamat NPWP</label>
                                <div class="col-sm-4">
                                    <input type="text" name="alamat_npwp" id="alamat_npwp"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->alamat_npwp)) {
                                                                                                    echo $ch->alamat_npwp;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                                <label class="col-sm-2 col-form-label">Tlp Pemesan</label>
                                <div class="col-sm-4">

                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="telp_pemesan" id="telp_pemesan" class="form-control"
                                            value="<?php if (!empty($ch->telp_pemesan)) {
                                                        echo $ch->telp_pemesan;
                                                    } ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Contact Person</label>
                                <div class="col-sm-4">
                                    <input type="text" name="contact_person" id="contact_person"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->contact_person)) {
                                                                                                    echo $ch->contact_person;
                                                                                                } ?>"
                                        class="form-control" placeholder="Nama Contact Person">
                                </div>
                                <label class="col-sm-2 col-form-label">Telp Contact Person</label>
                                <div class="col-sm-4">

                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="telp_contact_person" id="telp_contact_person"
                                            class="form-control" value="<?php if (!empty($ch->telp_contact_person)) {
                                                                            echo $ch->telp_contact_person;
                                                                        } ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama BPKB</label>
                                <div class="col-sm-4">
                                    <input type="text" name="nama_bpkb" id="nama_bpkb" value="<?php if (!empty($ch->nama_bpkb)) {
                                                                                                    echo $ch->nama_bpkb;
                                                                                                } ?>"
                                        class="form-control" placeholder="Nama di BPKB">
                                </div>
                                <label class="col-sm-2 col-form-label">No KTP</label>
                                <div class="col-sm-4">

                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="no_ktp" id="no_ktp" class="form-control"
                                            value="<?php if (!empty($ch->no_ktp)) {
                                                                                                                        echo $ch->no_ktp;
                                                                                                                    } ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Alamat BPKB</label>
                                <div class="col-sm-4">
                                    <input type="text" name="alamat_faktur" id="alamat_faktur" value="<?php if (!empty($ch->alamat_faktur)) {
                                                                                                            echo $ch->alamat_faktur;
                                                                                                        } ?>"
                                        class="form-control" placeholder="Alamat di BPKB">
                                </div>
                                <label class="col-sm-2 col-form-label">Type</label>
                                <div class="col-sm-4">
                                    <input type="text" name="type_body" id="type_body"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->type_body)) {
                                                                                                    echo $ch->type_body;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label">Retail</label>
                                <div class="col-sm-4">
                                    <input type="text" name="retail" id="retail"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->retail)) {
                                                                                                    echo $ch->retail;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                                <label class="col-sm-2 col-form-label">No Mesin</label>
                                <div class="col-sm-4">
                                    <input type="text" name="no_mesin" id="no_mesin"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->no_mesin)) {
                                                                                                    echo $ch->no_mesin;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Sales</label>
                                <div class="col-sm-4">
                                    <input type="text" name="sales" id="sales"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->sales)) {
                                                                                                    echo $ch->sales;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                                <label class="col-sm-2 col-form-label">Gesekan</label>
                                <div class="col-sm-4">
                                    <input type="text" name="gesekan" id="gesekan"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->gesekan)) {
                                                                                                    echo $ch->gesekan;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tahun Produksi</label>
                                <div class="col-sm-4">

                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="thn_produksi" id="thn_produksi" class="form-control"
                                            data-toggle="datetimepicker" data-target=".thn_produksi" data-format="yyy"
                                            value="<?php if (!empty($ch->thn_produksi)) {
                                                        echo $ch->thn_produksi;
                                                    } ?>">

                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label">Warna</label>
                              
                                <div class="col-sm-4">

                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="warna" id="warna" class="form-control"
                                            value="<?php if (!empty($ch->warna)) {
                                                        echo $ch->warna;
                                                    } ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Pengiriman</label>
                                <div class="col-sm-4">
                                    <input type="text" name="pengiriman" id="pengiriman"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->pengiriman)) {
                                                                                                    echo $ch->pengiriman;
                                                                                                } ?>"
                                        class="form-control">
                                </div>

                                <label class="col-sm-2 col-form-label">Jumlah</label>
                                <div class="col-sm-4"> <input type="text" name="jumlah" id="jumlah" value="<?php if (!empty($ch->jumlah)) {
                                                                                                    echo number_format($ch->jumlah);
                                                                                                } ?>"
                                        onkeyup="this.value = this.value.toUpperCase();" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-4">
                                    <input type="text" name="keterangan" id="keterangan"
                                        onkeyup="this.value = this.value.toUpperCase();" value="<?php if (!empty($ch->keterangan)) {
                                                                                                    echo $ch->keterangan;
                                                                                                } ?>"
                                        class="form-control">
                                </div>
                                <label class="col-sm-2 col-form-label">Harga</label>
                                <div class="col-sm-4"> <input type="text" name="harga_retail" id="harga_retail" 
                                onkeyup="formatNumber(this)" onchange="formatNumber(this);" value="<?php if (!empty($ch->harga_retail)) {
                                                                                                    echo number_format( $ch->harga_retail);
                                                                                                } ?>" onblur="formatNumber(this);" class="form-control">
                                </div>

                            </div>
                            <input type="hidden" name="user" id="user"
                                value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
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