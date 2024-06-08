<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataSaldo)) {
      foreach ($dataSaldo as $j) {
      }
    }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($j->no_jurnal)) {
                                                    echo 'Edit Saldo';
                                                } else {
                                                    echo 'Penambahan Data Saldo';
                                                }
                                                ?></h4>
        </p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>
    <div class="modal-body">
        <form id=<?php if (empty($j->periode)) { echo 'form-tambah-saldo'; } else { echo 'form-update-saldo';} ?> method="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Periode</label>
                        <div class="col-sm-4">
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="text" name="thn_saldo_input" id="thn_saldo_input"
                                    class="form-control thn_saldo_input datetimepicker" data-toggle="datetimepicker"
                                    data-target=".thn_saldo_input" data-format="yyyy" required>

                                <div class="input-group-append" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="No Akun" class="col-sm-2 col-form-label">Kode
                            Acc</label>
                        <div class="col-sm-4">
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="text" name="kode_akun" id="kode_akun" value="<?php if (!empty($j->kode_akun)) {
                                                          echo $j->kode_akun;}?>" class="form-control" readonly>
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#modal_form"><i class="glyphicon glyphicon-plus-sign"><i
                                                class="fa fa-search"></i></button></i>
                                </span>
                            </div>
                        </div>
                        <label for="Nama Akun" class="col-sm-2 col-form-label">Nama
                            Acc</label>
                        <div class="col-sm-4">
                            <input type="text" name="nama_akun" id="nama_akun" readonly value="<?php if (!empty($j->nama_akun)) {
                                                          echo $j->nama_akun;}?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">

                            <input type="text" name="keterangan" id="keterangan" value="<?php if (!empty($j->keterangan)) {
                                                          echo $j->keterangan;
                                                        } ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Debit</label>
                        <div class="col-sm-4">
                            <input type="text" name="debit" id="debit" onkeyup="formatNumber(this);" onchange="formatNumber(this);" onclick="formatNumber(this);" value="<?php if (!empty($j->debit)) {
                                                          echo $j->debit;
                                                        } else {echo '0';}  ?>" class="form-control">
                        </div>
                        <label class="col-sm-2 col-form-label">Kredit</label>
                        <div class="col-sm-4">
                            <input type="text" name="kredit" id="kredit" onkeyup="formatNumber(this);" onchange="formatNumber(this);" onclick="formatNumber(this);" value="<?php if (!empty($j->kredit)) {
                                                          echo $j->kredit;
                                                        } else {echo '0';} ?>" class="form-control">
                            <input type="hidden" name="user" id="user"
                                value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                            Simpan</button>
                    </div>
        </form>
        </div>
    </div>
<script language="javascript">
function refresh() {
    MyTable = $('#list-akun').dataTable();
}

function selectAkun(kode_akun, nama_akun) {
    $('[name = "kode_akun"]').val(kode_akun);
    $('[name = "nama_akun"]').val(nama_akun);

    //$('#modal-pk').modal('hide');
    $('#modal_form').modal('hide');
}
</script>