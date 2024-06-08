<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php         
            $date = date("ym");
            $ci_kons = get_instance();
            $query = "SELECT max(kode_awal) AS maxKode FROM tbl_acc_jurnal_umum";
            $hasil = $ci_kons->db->query($query)->row_array();
            $noOrder = $hasil['maxKode'];
            $noUrut = (int)substr($noOrder, 3, 5);
            $noUrut++;
            $tahun = substr($date, 0, 2);
        
            $kode_awal  = $tahun.sprintf("%05s", $noUrut);


        if (!empty($dataJurnal)) {
        foreach ($dataJurnal as $j) {
        }
        }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($j->no_jurnal)) {
                                                    echo 'Edit Jurnal Umum';
                                                } else {
                                                    echo 'Penambahan Jurnal Umum';
                                                }
                                                ?></h4>
        </p>

        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i
                class="fa fa-times-circle"></i></button>

    </div>
    <div class="modal-body">
        <form id=<?php if (empty($j->no_jurnal)) { echo 'form-tambah-jurnal'; } else { echo 'form-update-jurnal';} ?>
            method="POST">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tanggal</label>
                <div class="col-sm-4">
                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input type="text" name="tgl_jurnal" id="tgl_jurnal"
                            class="form-control tgl_jurnal datetimepicker" data-toggle="datetimepicker"
                            data-target=".tgl_jurnal" data-format="dd-mm-yyy" required
                            value="<?php if (!empty($j->tgl_jurnal)) {
                                                                $date1 = $j->tgl_jurnal;
                                                                $tgl1 = explode('-', $date1);
                                                                $tgl_edit = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . ""; echo $tgl_edit;}?>"
                            <?php if (!empty($j->tgl_jurnal)) { echo 'disabled';}?>>

                        <div class="input-group-append" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <label class="col-sm-2 col-form-label">No Reff</label>


                <div class="col-sm-4">
                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input type="text" name="no_ref" id="no_ref" onkeyup="cariKodeRef()" onclick="cariKodeRef()" onchange="cariKodeRef()"
                            value="<?php if (!empty($j->no_ref)) { echo $j->no_ref; } ?>"
                            class="form-control" required>
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_ref"><i
                                    class="glyphicon glyphicon-plus-sign"><i class="fa fa-search"></i></button></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="No Akun" class="col-sm-2 col-form-label">Kode
                    Acc</label>
                <div class="col-sm-1">
                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input type="text" name="kode_akun" id="kode_akun"
                            value="<?php if (!empty($j->kode_akun)) { echo $j->kode_akun;}?>" class="form-control"
                            data-toggle="modal" data-target="#modal_form" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <input type="hidden" name="no_jurnal" id="no_jurnal"
                        value="<?php if (!empty($j->no_jurnal)) { echo $j->no_jurnal; } ?>" class="form-control">
                    <input type="text" name="nama_akun" id="nama_akun" readonly
                        value="<?php if (!empty($j->nama_akun)) { echo $j->nama_akun;}?>" class="form-control">
                    <input type="hidden" name="kode_awal" id="kode_awal"
                        value="<?php if (!empty($j->kode_awal)) { echo $j->kode_awal; } else echo $kode_awal; ?>"
                        readonly>
                    <input type="hidden" name="kelompok" id="kelompok" class="form-control">
                    <input type="hidden" name="type_akun" id="type_akun" class="form-control">
                    <input type="hidden" name="jenis_beban" id="jenis_beban" class="form-control">
                </div>
                <label class="col-sm-2 col-form-label">Keterangan</label>
                <div class="col-sm-4">
                    <input type="text" name="keterangan" id="keterangan" value="<?php if (!empty($j->keterangan)) { echo $j->keterangan;
                                                        } ?>" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Debit</label>
                <div class="col-sm-4">
                    <input type="text" name="debit" id="debit" onkeyup="formatNumber(this),startDebit();"
                        onchange="formatNumber(this);" onclick="formatNumber(this);" value="<?php if (!empty($j->debit)) {
                                                        echo $j->debit;
                                                        } else {echo '0';}  ?>" class="form-control">
                </div>
                <label class="col-sm-2 col-form-label">Kredit</label>
                <div class="col-sm-4">
                    <input type="text" name="kredit" id="kredit" onkeyup="formatNumber(this),startKredit();"
                        onchange="formatNumber(this);" onclick="formatNumber(this);" value="<?php if (!empty($j->kredit)) { echo $j->kredit;
                                                        } else {echo '0';} ?>" class="form-control">
                    <input type="hidden" name="user" id="user"
                        value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                </div>
            </div>

            <div class="justify-content-between">
                <button type="submit" class="btn btn-success float-right" id="btnTambah" name="btnTambah"><i
                        class="fa fa-plus"></i> Tambah</button>
            </div>
        </form>
        <button type="submit" class="btn btn-primary" id="btnGenerate" name="btnGenerate" disabled
            onclick="generateJurnal()"><i class="fa fa-cogs"></i> Generate Jurnal</button>
        <div id="data-jurnal"></div>
    </div>
</div>
<script language="javascript">
$('#tgl_jurnal').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});

function refresh() {
    MyTable = $('#list-akun').dataTable();
}

function cariKodeRef() {
    var a = document.getElementById('no_ref').value;
    $.ajax({
        url: "<?php echo base_url();?>Jurnal/cariRef",
        data: '&a=' + a,
        success: function(data) {
            var hasil = JSON.parse(data);
            if (hasil.length > 1) {
                document.getElementById("btnTambah").disabled = true;
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'ID Referensi Sudah digunakan',
                    text: "Apakah anda ingin menambahkan data dengan nomor yang sama?!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: "Tidak!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function(result) {
        if (result.value) {
                showDetailEdit();
                document.getElementById("btnTambah").disabled = false;
             } })
            } else {

                document.getElementById("btnTambah").disabled = false;
            }

        }
    });

}

function selectAkun(kode_akun, nama_akun, kelompok, type_akun, jenis_beban) {
    $('[name = "kode_akun"]').val(kode_akun);
    $('[name = "nama_akun"]').val(nama_akun);
    $('[name = "kelompok"]').val(kelompok);
    $('[name = "type_akun"]').val(type_akun);
    $('[name = "jenis_beban"]').val(jenis_beban);

    document.getElementById("debit").readOnly = false;
    document.getElementById("kredit").readOnly = false;
    $('#modal_form').modal('hide');
}

function selectRef(no_ref) {
    $('[name = "no_ref"]').val(no_ref);
    $('#modal_ref').modal('hide');
}
$('#form-tambah-jurnal').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Jurnal/prosesTjurnal'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                //document.getElementById("form-tambah-jurnal").reset();

                $('#debit').val('0');
                $('#kredit').val('0');
                //$('#tambah-jurnal').modal('hide');
                refresh();
                $('.msg').html(out.msg);
                showDetailEdit();
                document.getElementById("debit").readOnly = false;
                document.getElementById("kredit").readOnly = false;
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
</script>