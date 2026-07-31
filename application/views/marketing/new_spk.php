<?php
defined('BASEPATH') or exit('No direct script access allowed');?>
<style>
    .radio-buttons {
        height: auto;
        display: flex;
        flex-wrap: wrap;
    }

    .radio-button {
        display: flex;
        align-items: center;
        margin-right: auto;
        position: relative;
        padding: 5px 10px;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 20px;
        cursor: pointer;
        width: auto;
        height: auto;
        display: flex;
        flex-wrap: wrap;
    }

    .radio-button input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .radio-tick {
        width: 20px;
        height: 20px;
        border: 2px solid #000000;
        border-radius: 20%;
        margin-right: 10px;
    }

    .radio-tick-merah {
        width: 20px;
        height: 20px;
        border: 2px solid #eb3449;
        border-radius: 20%;
        margin-right: 10px;
    }

    .radio-tick-kuning {
        width: 20px;
        height: 20px;
        border: 2px solid #f2cc0c;
        border-radius: 20%;
        margin-right: 10px;
    }

    .radio-tick-hijau {
        width: 20px;
        height: 20px;
        border: 2px solid #f2cc0c;
        border-radius: 20%;
        margin-right: 10px;
    }

    .radio-tick-lain {
        width: 20px;
        height: 20px;
        border: 2px solid #5187d6;
        border-radius: 20%;
        margin-right: 10px;
    }

    .radio-button input[type="radio"]:checked+.radio-tick::before {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    .radio-button input[type="radio"]:checked+.radio-tick-merah::before {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    .radio-button input[type="radio"]:checked+.radio-tick-kuning::before {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    .radio-button input[type="radio"]:checked+.radio-tick-lain::before {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
    }


    .radio-button input[type="radio"]:checked~.radio-tick {
        background-color: #007bff;
    }

    .radio-button input[type="radio"]:checked~.radio-tick-merah {
        background-color: #007bff;
    }

    .radio-button input[type="radio"]:checked~.radio-tick-kuning {
        background-color: #007bff;
    }

    .radio-button input[type="radio"]:checked~.radio-tick-lain {
        background-color: #007bff;
    }
</style>

<?php if (!empty($dataSpk)) {
    foreach ($dataSpk as $ch) {
    }
} ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-dark">
                    <!-- /.card-header -->
                    <div class="modal-body">


                        <?php
                        $date = date("Y-m");
                        $ci_kons = get_instance();
                        $query = "SELECT max(no_urut) AS maxKode FROM tbl_mk_spk WHERE no_urut LIKE '%$date%'";
                        $hasil = $ci_kons->db->query($query)->row_array();
                        $noOrder = $hasil['maxKode'];
                        $noUrut = (int)substr($noOrder, 8, 3);
                        $noUrut++;
                        $tahun = substr($date, 0, 4);
                        $bulan = substr($date, 5, 2);
                        $kode_po  = $tahun . '-' . $bulan . '-' . sprintf("%03s", $noUrut);
                        $kode_ref = 'TOP-' . sprintf("%03s", $noUrut) . '-' . $bulan . '-SBY-' . $tahun;
                        if (!empty($dataRetail)) {
                            foreach ($dataRetail as $ch) {
                            }
                        }
                        ?>
                        <form id="formSpk" name="formSpk" method="POST">
                            <div class="row">

                                <input type="hidden" name="id_chasis" id="id_chasis" value="<?php echo !empty($ch->id_chasis) ? $ch->id_chasis : ''; ?>" class="form-control">
                                <input type="hidden" name="id_spk" id="id_spk" value="<?php echo !empty($ch->id_spk) ? $ch->id_spk : ''; ?>" class="form-control">
                                <input type="hidden" name="no_urut" id="no_urut" value="<?php echo $kode_po ?>" class="form-control">
                                <input type="hidden" name="user" id="user"
                                    value="<?php echo $this->session->userdata['full_name']; ?>"
                                    class="form-control">

                                <div class="col-2">
                                    <label class="col-form-label">Nomor</label>
                                    <input type="text" name="no_ref" id="no_ref" value="<?php echo $kode_ref ?>"
                                        class="form-control" placeholder="Nomor Referensi" readonly>
                                </div>
                                <div class="col-1">
                                    <label class="col-form-label">Kode</label>
                                    <input type="text" name="kode" id="kode" class="form-control" placeholder="Kode">
                                </div>
                                <div class="col-3">
                                    <label class="col-form-label">Nama Pemesan</label>
                                    <input type="text" name="nama_pemesan" id="nama_pemesan" value="<?php echo !empty($ch->nama_pemesan) ? $ch->nama_pemesan : ''; ?>"
                                        class="form-control" placeholder="Nama Pemesan">
                                </div>
                                <div class="col-6">
                                    <label class="col-form-label">Alamat Pemesan</label>
                                    <input type="text" name="alamat_pemesan" id="no_reg" value="<?php echo !empty($ch->alamat_pemesan) ? $ch->alamat_pemesan : ''; ?>"
                                        class="form-control" placeholder="Alamat">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label class="col-form-label">No Telp</label>
                                    <input type="text" name="telp_pemesan" id="telp_pemesan" value="<?php echo !empty($ch->telp_pemesan) ? $ch->telp_pemesan : ''; ?>" class="form-control"
                                        placeholder="Telp Pemesan">
                                </div>
                                <div class="col-3">
                                    <label class="col-form-label">Faktur Pajak</label>
                                    <div class="radio-buttons">
                                        <label class="radio-button">
                                            <input type="radio" name="faktur_pajak" value="Y" checked />
                                            <span class="radio-tick-lain"></span>
                                            Ya
                                        </label>
                                        <label class="radio-button">
                                            <input type="radio" name="faktur_pajak" value="T" />
                                            <span class="radio-tick-lain"></span>
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label class="col-form-label">No NPWP</label>
                                    <input type="text" name="npwp_pemesan" id="npwp_pemesan" value="<?php echo !empty($ch->no_npwp) ? $ch->no_npwp : ''; ?>"
                                        class="form-control" placeholder="No NPWP">
                                </div>
                                <div class="col-3">
                                    <label class="col-form-label">Nama NPWP</label>
                                    <input type="text" name="nama_npwp_pemesan" id="nama_npwp_pemesan" value="<?php echo !empty($ch->nama_npwp) ? $ch->nama_npwp : ''; ?>" class="form-control"
                                        placeholder="Nama NPWP">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="col-form-label">Alamat NPWP</label>
                                    <input type="text" name="alamat_npwp" id="alamat_npwp" value="<?php echo !empty($ch->alamat_npwp) ? $ch->alamat_npwp : ''; ?>" class="form-control"
                                        placeholder="Alamat NPWP">
                                </div>
                                <div class="col-3">
                                    <label class="col-form-label">Contact Person</label>
                                    <input type="text" name="contact_person" id="contact_person" value="<?php echo !empty($ch->contact_person) ? $ch->contact_person : ''; ?>"
                                        class="form-control" placeholder="No NPWP">
                                </div>
                                <div class="col-3">
                                    <label class="col-form-label">No Telepon / HP</label>
                                    <input type="text" name="telp_contact_person" id="telp_contact_person" value="<?php echo !empty($ch->telp_contact_person) ? $ch->telp_contact_person : ''; ?>" class="form-control"
                                        placeholder="No Telepon">
                                </div>
                            </div>

                    <h1 style="display:block; text-align:center;">
                    </h1>
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-alt"></i>&nbsp; Data Faktur Kendaraan
                        </h3>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">Nama BPKB/STNK</label>
                                <input type="text" name="nama_bpkb" id="nama_bpkb" value="<?php echo !empty($ch->nama_bpkb) ? $ch->nama_bpkb : ''; ?>" class="form-control"
                                    placeholder="Nama BPKB / STNK">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">No KTP/No TDP</label>
                                <input type="text" name="no_ktp" id="no_ktp" value="<?php echo !empty($ch->no_ktp) ? $ch->no_ktp : ''; ?>" class="form-control"
                                    placeholder="Nomor KTP">
                            </div>
                            <div class="col-6">
                                <label class="col-form-label">Alamat</label>
                                <input type="text" name="alamat_faktur" id="alamat_faktur" value="<?php echo !empty($ch->alamat_faktur) ? $ch->alamat_faktur : ''; ?>" class="form-control"
                                    placeholder="Received by">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3">
                                <label class="col-form-label">Plat Kendaraan</label>

                                <div class="radio-buttons">
                                    <label class="radio-button">
                                        <input type="radio" name="plat_kendaraan" <?php echo (!empty($ch->plat_kendaraan) && $ch->plat_kendaraan == 'K') ? 'checked' : ''; ?> value="K" />
                                        <span class="radio-tick-kuning"></span>
                                        Kuning
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="plat_kendaraan" <?php echo (!empty($ch->plat_kendaraan) && $ch->plat_kendaraan == 'H') ? 'checked' : ''; ?>  value="H" />
                                        <span class="radio-tick"></span>
                                        Hitam
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="plat_kendaraan" <?php echo (!empty($ch->plat_kendaraan) && $ch->plat_kendaraan == 'M') ? 'checked' : ''; ?>  value="M" />
                                        <span class="radio-tick-merah"></span>Merah
                                    </label>
                                </div>
                            </div>
                            <div class="col-9">
                                <label class="col-form-label">Type Body</label>

                                <div class="radio-buttons">
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == 'LosBak') ? 'checked' : ''; ?>  value="LosBak" id="radio_t1">
                                        <span class="radio-tick"></span>Los Bak
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == 'Box') ? 'checked' : ''; ?> value="Box" id="radio_t2">
                                        <span class="radio-tick"></span> Box
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == 'Tangki') ? 'checked' : ''; ?>  value="Tangki" id="radio_t3">
                                        <span class="radio-tick"></span> Tangki
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == 'Dump') ? 'checked' : ''; ?>  value="Dump" id="radio_t4">
                                        <span class="radio-tick"></span> Dump
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == 'Mixer') ? 'checked' : ''; ?>  value="Mixer" id="radio_t5">
                                        <span class="radio-tick"></span> Mixer
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == 'TractorHead') ? 'checked' : ''; ?>  value="TranctorHead" id="radio_t6">
                                        <span class="radio-tick"></span> Tranctor Head
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == 'TangkiTrailer') ? 'checked' : ''; ?>  value="TangkiTrailer" id="radio_t7">
                                        <span class="radio-tick"></span> Tangki Trailer
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == 'Bus') ? 'checked' : ''; ?>  value="Bus" id="radio_t8">
                                        <span class="radio-tick"></span> Bus
                                    </label>
                                    <label class="radio-button">
                                        <input type="radio" name="type_body" <?php echo (!empty($ch->type_body) && $ch->type_body == '...') ? 'checked' : ''; ?>  value="..." id="radio_t9">
                                        <span class="radio-tick"></span> ........
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h1 style="display:block; text-align:center;">
                    </h1>
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-alt"></i>&nbsp; Keterangan Unit dan Estimasi
                            Harga
                        </h3>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="card card-body">

                                    <div class="col-12">
                                        <label class="col-form-label">Jumlah Unit</label>
                                        <input type="text" name="jml_unit" id="jml_unit" readonly value="<?php echo !empty($ch->jumlah) ? $ch->jumlah : ''; ?>" onkeypress="startCalculate()" onkeyup="startCalculate()" class="form-control"
                                            placeholder="Jumlah Unit">
                                    </div>
                                    <div class="col-12">
                                        <label class="col-form-label">Kategori</label>
                                        <div class="radio-buttons">
                                            <label class="radio-button">
                                                <input type="radio" name="kategori" <?php echo (!empty($ch->kategori) && $ch->kategori == 'BUS') ? 'checked' : ''; ?> value="BUS" />
                                                <span class="radio-tick-lain"></span>
                                                BUS
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="kategori" value="TA" <?php echo (!empty($ch->kategori) && $ch->kategori == 'TA') ? 'checked' : ''; ?> />
                                                <span class="radio-tick-lain"></span>
                                                TA
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="kategori" value="TE" <?php echo (!empty($ch->kategori) && $ch->kategori == 'TE') ? 'checked' : ''; ?> />
                                                <span class="radio-tick-lain"></span>
                                                TE
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="col-form-label">Type</label>
                                        <input type="text" name="type_kendaraan" id="type_kendaraan"  value="<?php echo !empty($ch->type) ? $ch->type : ''; ?>"
                                            class="form-control" placeholder="Type Kendaraan">
                                    </div>
                                    <div class="col-12">
                                        <label class="col-form-label">Warna / Tahun</label>
                                        <input type="text" name="warna_tahun" id="warna_tahun"  value="<?php echo !empty($ch->thn_produksi) ? $ch->thn_produksi : ''; ?>"
                                            class="form-control" placeholder="Warna / Tahun" />
                                    </div>
                                    <div class="col-12">
                                        <label class="col-form-label">Harga Off The Road</label>
                                        <input type="text" name="harga_unit" id="harga_unit" value="<?php echo !empty($ch->harga_retail) ? number_format($ch->harga_retail) : ''; ?>"
                                            onkeypress="startCalculate(),formatNumber(this)" onmousemove="formatNumber(this);" onmouseover="formatNumber(this);" onblur="formatNumber(this);" onkeyup="startCalculate(),formatNumber(this)"


                                            class="form-control" placeholder="0" style="text-align:right;">
                                    </div>
                                    <div class="col-12">
                                        <label class="col-form-label">Biaya BBN</label>
                                        <input type="text" name="biaya_bbn" id="biaya_bbn" value="<?php echo !empty($ch->biaya_bbn) ? number_format($ch->biaya_bbn) : ''; ?>"
                                            onkeypress="startCalculate(),formatNumber(this)" onkeyup="startCalculate(),formatNumber(this)"
                                            class="form-control" placeholder="Biaya BBN"
                                            style="text-align:right;">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label class="col-form-label">Harga On The Road</label>
                                                <input type="text" name="hrg_on_the_road" id="hrg_on_the_road" value="0"
                                                    onkeyup="formatNumber(this)" onchange="formatNumber(this);"
                                                    class="form-control" placeholder="Bea Pengiriman Barang" readonly
                                                    style="text-align:right;">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Tambahan 1 </label>
                                                <input type="text" name="tambahan_1" id="tambahan_1" value="<?php echo !empty($ch->tambahan_1) ? $ch->tambahan_1 : ''; ?>"
                                                    class="form-control" placeholder="Keterangan Tambahan">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Harga</label>
                                                <input type="text" name="hrg_tambahan_1" id="hrg_tambahan_1" value="<?php echo !empty($ch->hrg_tambahan_1) ? number_format($ch->hrg_tambahan_1) : ''; ?>"
                                                    value="0" onkeyup="formatNumber(this)"
                                                    onchange="formatNumber(this);" class="form-control"
                                                    placeholder="Harga Tambahan" style="text-align:right;">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Tambahan 2 </label>
                                                <input type="text" name="tambahan_2" id="tambahan_2" value="<?php echo !empty($ch->tambahan_2) ? $ch->tambahan_2 : ''; ?>"
                                                    class="form-control" placeholder="Keterangan Tambahan">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Harga</label>
                                                <input type="text" name="hrg_tambahan_2" id="hrg_tambahan_2" value="<?php echo !empty($ch->hrg_tambahan_2) ? number_format($ch->hrg_tambahan_2) : ''; ?>"
                                                    value="0" onkeyup="formatNumber(this)"
                                                    onchange="formatNumber(this);" class="form-control"
                                                    placeholder="Harga Tambahan" style="text-align:right;">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Tambahan 3 </label>
                                                <input type="text" name="tambahan_3" id="tambahan_3" value="<?php echo !empty($ch->tambahan_3) ? $ch->tambahan_3 : ''; ?>"
                                                    class="form-control" placeholder="Keterangan Tambahan">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Harga</label>
                                                <input type="text" name="hrg_tambahan_3" id="hrg_tambahan_3" value="<?php echo !empty($ch->hrg_tambahan_3) ? number_format($ch->hrg_tambahan_3) : ''; ?>"
                                                    value="0" onkeyup="formatNumber(this)"
                                                    onchange="formatNumber(this);" class="form-control"
                                                    placeholder="Harga Tambahan" style="text-align:right;">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Tambahan 4 </label>
                                                <input type="text" name="tambahan_4" id="tambahan_4" value="<?php echo !empty($ch->tambahan_4) ? $ch->tambahan_4 : ''; ?>"
                                                    class="form-control" placeholder="Keterangan Tambahan">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Harga</label>
                                                <input type="text" name="hrg_tambahan_4" id="hrg_tambahan_4" value="<?php echo !empty($ch->hrg_tambahan_4) ? number_format($ch->hrg_tambahan_4) : ''; ?>"
                                                    value="0" onkeyup="formatNumber(this)"
                                                    onchange="formatNumber(this);" class="form-control"
                                                    placeholder="Harga Tambahan" style="text-align:right;">
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Harga Jual Per Unit</label>
                                                <input type="text" name="hrg_jual_perunit" id="hrg_jual_perunit"
                                                    value="0" onkeyup="formatNumber(this)"
                                                    onchange="formatNumber(this);" class="form-control"
                                                    placeholder="Total Harga Jual" style="text-align:right;" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label class="col-form-label">Total Harga Jual</label>
                                                <input type="text" name="total_harga_jual" id="total_harga_jual"
                                                    value="0" onkeyup="formatNumber(this)"
                                                    onchange="formatNumber(this);" onblur="formatNumber(this);" class="form-control"
                                                    placeholder="Total Harga Jual" style="text-align:right;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" id="simpan" type="submit"><span class="fa fa-save"></span>
                            Simpan Data</button>
                        <button type="button" class="btn btn-info cetak-po" id="cetak" hidden="hidden" data-id=""
                            title="Add Data"><i class="fas fa-print"></i>
                            Cetak Data</button>
                    </div>
                    </div>
                        </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="modal-content">
                        <div class="card-header card-dark card-outline">
                            <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i> &nbsp;
                                Keterangan</h3>
                            <div class="text-right">
                                <!--<button type="button" class="btn btn-sm btn-dark" onclick="insertNote()"><i
                                        class="fas fa-plus"></i>
                                    Standart Keterangan</button>-->
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#tambah-keterangan" title="Add Data"><i class="fas fa-plus"></i>
                                    Add</button>
                            </div>
                        </div>
                        <div class="col-12">
                            <p></p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover nowrap" id="list-keterangan">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-keterangan">
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="modal-keterangan"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div id="modal-po"></div>
            <div id="data-po"></div>
            <div id="data-po-cache"></div>
        </div>
        <div class="modal fade" id="modal_form" role="dialog">
</section>
<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data PO Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->
<script type="text/javascript">
    function fn(o) {
        o.value = o.value.toUpperCase().replace(/([^0-9(),-/])/g, '');
    }
    $('#tgl_estimasi_penawaran,#tgl_received,#tgl_regis').datetimepicker({
        format: 'DD-MM-YYYY',
        date: moment()
    });
    //var Date = 
    $('#tgl_deadline').datetimepicker({
        format: 'DD-MM-YYYY',
        date: moment()
    })
    $('#clear').click(function() {
        $('#tgl_deadline').data("DateTimePicker").clear()
    })

    $('#tgl_deadline1').datetimepicker({
        format: 'DD-MM-YYYY',
        date: moment()
    });


    var tableKeterangan = $('#list-keterangan').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": true,
        "pageLength": 5
    });



    function NilaiRupiah(jumlah) {
        var titik = ",";
        var nilai = new String(jumlah);
        var pecah = [];
        while (nilai.length > 3) {
            var asd = nilai.substr(nilai.length - 3);
            pecah.unshift(asd);
            nilai = nilai.substr(0, nilai.length - 3);
        }

        if (nilai.length > 0) {
            pecah.unshift(nilai);
        }
        nilai = pecah.join(titik);
        return nilai;
    }

    function startCalculate() {
        interval = setInterval("Calculate()", 10);
    }

    //function ganti(",");
    function Calculate() {

        //var a = document.formSpk.jml_unit.value;
        //var a = document.getElementById("jml_unit").value;
        var a = document.getElementById("jml_unit").value.replace(/\D/g, '');
        var b = document.getElementById("harga_unit").value.replace(/\D/g, '');
        var c = document.getElementById("biaya_bbn").value.replace(/\D/g, '');
        var d = document.getElementById("hrg_tambahan_1").value.replace(/\D/g, '');
        var e = document.getElementById("hrg_tambahan_2").value.replace(/\D/g, '');
        var f = document.getElementById("hrg_tambahan_3").value.replace(/\D/g, '');
        var g = document.getElementById("hrg_tambahan_4").value.replace(/\D/g, '');
        document.getElementById("hrg_on_the_road").value = NilaiRupiah(((b * 1) + (c * 1)) * a);
        document.getElementById("hrg_jual_perunit").value = NilaiRupiah((b * 1) + (c * 1) + (d * a) + (e * a) + (f * a) + (g * a) / (a * 1));
        document.getElementById("total_harga_jual").value = NilaiRupiah((((b * 1) + (c * 1)) * a) + (d * a) + (e * a) + (f * a) + (g * a));
        //document.formSpk.hrg_on_the_road.value = NilaiRupiah(((b * 1) + (c * 1)+ (d * 1)+ (e * 1)+ (f * 1)+ (g * 1)) * a);

    }

    function stopCalc() {
        clearInterval(startCalculate);
    }

    function next(dataPo, dataRef) {
        document.getElementById('no_ref').value = dataPo;
        document.getElementById('kode_ref').value = dataRef;
        var d = document.getElementById("cetak");
        d.setAttribute('data-id', dataPo);

        document.getElementById("cetak").hidden = false;
        //document.getElementById("alamat").readonly = true;
    }

    function nextref(dataRef) {
        document.getElementById('kode_ref').value = dataRef;
    }

    function refresh() {
        MyTable = $('#list-po').dataTable();
    }

    function insertNote() {
        var no_ref = document.getElementById('no_ref').value;
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('ChasisRetail/tambahNote'); ?>',
            data: 'id=' + no_ref,
            success: function(hasil) {
                tampilKeterangan()
            }
        });
    }

    function tampilKeterangan() {
        var no_urut = document.getElementById('no_urut').value;
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('ChasisRetail/tampilKeterangan'); ?>',
            data: 'no_urut=' + no_urut,
            success: function(hasil) {
                tableKeterangan.fnDestroy();
                $('#data-keterangan').html(hasil);
            }
        });
    }

    /** Form Keterangan */

    $('#form-keterangan').submit(function(e) {
        var data = $(this).serialize();
        var id = document.getElementById('no_urut').value;
        var no_spk = document.getElementById('no_ref').value;

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('ChasisRetail/tambahKeterangan'); ?>',
                data: data + "&id=" + id + "&no_spk=" + no_spk
            })
            .done(function(data) {
                tampilKeterangan();
                document.getElementById("form-keterangan").reset();
                $('#tambah-keterangan').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Mantap',
                    showConfirmButton: false,
                    timer: 1000
                })
            })

        e.preventDefault();
    });

    $(document).on("click", ".update-dataType", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Settingwh/updateType'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-type').html(data);
                $('#update-type').modal('show');
            })
    })
    $(document).on('submit', '#form-update-type', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingwh/prosesUtype'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showType();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-type").reset();
                    $('#update-type').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });
    /** end Keterangan */
    function tampilDetailCache(dataPo) {
        //var out = jQuery.parseJSON(data);
        var no_ref = document.getElementById('no_ref').value = dataPo;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ChasisRetail/tampilDetailCache'); ?>?no_ref=' +
                no_ref,
            data: 'no_ref=' + no_ref,
            success: function(hasil) {
                MyTable.fnDestroy();
                $('#data-po-cache').html(hasil);
                refresh();
            }
        });
    }
    $('#formSpk').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('ChasisRetail/prosesSpk'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                if (out.status == 'form') {
                    //toastr.error(out.msg);
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else {
                    $('.msg').html(out.msg);
                    $('.dataPo').html(out.dataPo);
                    //tampilDetail(out.dataPo)
                    //document.getElementById("formSpk").reset();

                    var d = document.getElementById("cetak");
                    d.setAttribute('data-id', out.dataPo);
                    var d = document.getElementById("cetak-ulang");
                    d.setAttribute('data-id', out.dataPo);
                    document.getElementById("cetak").hidden = false;
                    document.getElementById("data-po").hidden = true;
                    document.getElementById("simpan").hidden = true;

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });
     $('#editSpk').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('ChasisRetail/Proses_updateSpk'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                if (out.status == 'form') {
                    //toastr.error(out.msg);
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else {
                    $('.msg').html(out.msg);
                    $('.dataPo').html(out.dataPo);
                    //tampilDetail(out.dataPo)
                    //document.getElementById("formSpk").reset();

                    var d = document.getElementById("cetak");
                    d.setAttribute('data-id', out.dataPo);
                    var d = document.getElementById("cetak-ulang");
                    d.setAttribute('data-id', out.dataPo);
                    document.getElementById("cetak").hidden = false;
                    document.getElementById("data-po").hidden = true;
                    document.getElementById("simpan").hidden = true;

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });

    function cetakPo(datakode) {}


    $(document).on("click", ".cetak-po", function() {
        var id = $(this).attr("data-id");
        //var id = document.getElementById('next_proses').value=datakode;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ChasisRetail/cetak'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-po').html(data);
                $('#cetak-po').modal('show');
            })
    })

    $(document).on("click", ".delete-keterangan", function() {
        data_id = $(this).attr("data-id");
    })
    $(document).on("click", ".delete-keterangan", function() {
        var id = data_id;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ChasisRetail/deleteKeterangan'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);
                if (out.status != 'form') {
                    //$('.msg').html(out.msg);
                    $('#hapusKeterangan').modal('hide');
                    var no_urut = document.formSpk.no_urut.value;
                    //next(next_proses);
                    tampilKeterangan(no_urut);
                }
            })
    })

    function startHitung() {
        interval = setInterval("Hitung()", 10);
    }

    function Hitung() {

        var a = document.formPo.jumlah.value;
        var b = document.formPo.hrg_awal.value;
        document.formPo.total_harga.value = (a * b);
    }

    function stopHitung() {
        clearInterval(startHitung);
    }

    function startDiskon() {
        interval = setInterval("Diskon()", 10);
    }

    function Diskon() {

        var a = document.formPo.jumlah.value;
        var b = document.formPo.hrg_awal.value;
        var c = document.formPo.diskon.value;
        document.formPo.total_diskon.value = (a * b) * c / 100;
    }

    function stopDiskon() {
        clearInterval(startDiskon);
    }

    function startPpn() {
        interval = setInterval("Ppn()", 10);
    }
</script>