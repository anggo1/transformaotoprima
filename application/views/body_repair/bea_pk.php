<style>
.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 12px;
}

table.dataTable td {
    padding: 5px;
}
</style>
<div class="row">
    <div class="col-12 ">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title">
                                        <i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Daftar Kendaraan Belum Keluar
                                    </h3>
                                </div>
                                <div class="modal-body form">
                                    <div class="card card-first card-outline">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="col-sm-12 col-form-label">Masukan No SPK Jika tidak ada di List (Status Keluar)</label>
                                                <div class="input-group form-group">
                                                    <div class="input-group col-sm-6" id="reservationdate"
                                                        data-target-input="nearest">
                                                        <input type="text" name="no_pk" id="no_pk"
                                                            placeholder="Nomor SPK" class="form-control">
                                                    </div>
                                                    <button class="btn bg-gradient-primary col-sm-2"
                                                        onclick="listPart()" type="submit"><span
                                                            class="fa fa-search"></span> Cari</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="data-pk"></div>
                                    </div>
                                            <div id="modal-pk"></div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title">
                                        <i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Jumlah Upah
                                        Pembayaran
                                    </h3>
                                </div>
                                <div class="modal-body form">
                                    <div class="card card-first card-outline">
                                        <div class="card-body">
                                            <div class="col-12 ">

                                                <form id="formBea" name="formBea" method="POST">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">No Body</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="no_body" id="no_body"
                                                                class="form-control" readonly required
                                                                placeholder="No Body">
                                                        </div>
                                                        <label class="col-sm-2 col-form-label">ID PK</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="id_pk" id="id_pk"
                                                                class="form-control" readonly required
                                                                placeholder="id_pk">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Pekerjaan</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="ket_pk" id="ket_pk"
                                                                class="form-control" readonly required
                                                                placeholder="Pekerjaan">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Pemborong</label>
                                                        <div class="col-sm-4">
                                                            <div class="input-group date" id="reservationdate"
                                                                data-target-input="nearest">
                                                                <input type="text" name="pt_pemborong" id="pt_pemborong"
                                                                    class="form-control" readonly>
                                                            </div>

                                                        </div>
                                                        <label class="col-sm-2 col-form-label">Kepala
                                                            Borong</label>
                                                        <div class="col-sm-4">
                                                            <div class="input-group date" id="reservationdate"
                                                                data-target-input="nearest">
                                                                <input type="text" name="pj_borong" id="pj_borong"
                                                                    class="form-control" readonly>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Biaya
                                                            Pekerjaan</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="biaya" id="biaya"
                                                                class="form-control" value="0"
                                                                onkeyup="formatNumber(this)"
                                                                onkeydown="formatNumber(this)"
                                                                onclick="formatNumber(this)" required>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="id_lapor" id="id_lapor"
                                                        class="form-control">
                                                    <input type="hidden" name="hrg_awal" id="hrg_awal"
                                                        class="form-control">
                                                    <input type="hidden" name="user" id="user"
                                                        value="<?php echo $this->session->userdata['full_name']; ?>"
                                                        class="form-control">
                                                    <div class="modal-footer center-content-between">

                                                        <button class="btn btn-primary" type="submit">
                                                            <span class="fa fa-save"></span>
                                                            Simpan</button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
show_my_confirm('startPk', 'start-pk', 'PK Dimulai', 'Ya, Mulai', 'Batal Mulai');
show_my_confirm('selesaiPk', 'selesai-pk', 'Seluruh PK Telah Selesai?', 'Ya, Selesai', 'Batal');
?>
    <script type="text/javascript">
    window.onload = function() {
        listPart();
    }

    function refresh() {
        MyTable = $('#list-laporan,#list-kategori,#list-pk').DataTable();
    }

    function effect_msg_form() {
        // $('.form-msg').hide();
        $('.form-msg').show(500);
        setTimeout(function() {
            $('.form-msg').fadeOut(500);
        }, 1000);
    }

    function effect_msg() {
        // $('.msg').hide();
        $('.msg').show(500);
        setTimeout(function() {
            $('.msg').fadeOut(500);
        }, 1000);
    }

    function selectPk(no_body, id_pk, ket_pk, pt_pemborong, pj_borong, biaya) {
        $('[name = "no_body"]').val(no_body);
        $('[name = "id_pk"]').val(id_pk);
        $('[name = "ket_pk"]').val(ket_pk);
        $('[name = "pt_pemborong"]').val(pt_pemborong);
        $('[name = "pj_borong"]').val(pj_borong);
        $('[name = "biaya"]').val(biaya);

        //$('#modal-pk').modal('hide');
        $('#cetak-pk').modal('hide');
    }

    function refresh() {
        MyTable = $('#list-pk,#list-pk-mulai').dataTable();
    }
    var MyTable = $('#list-pk,#list-pk-mulai,#table-body').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 5
    });

    function listPart() {
        var no_pk = document.getElementById("no_pk").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BeaPK/showPk'); ?>?no_pk=' + no_pk,
            data: 'no_pk=' + no_pk,
            success: function(hasil) {
                MyTable.fnDestroy();
                $('#data-pk').html(hasil);
                refresh();
            }
        });
    }

    function showPk() {
        $.get('<?php echo base_url('BeaPK/showPk'); ?>',
            function(data) {
                MyTable.fnDestroy();
                $('#data-pk').html(data);
                refresh();
            });
    }

    function tampilPk() {
        var id_lapor = document.getElementById('id_lapor').value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BusPk/tampilPk'); ?>?id_lapor=' +
                id_lapor,
            data: 'id_lapor=' + id_lapor,
            success: function(hasil) {
                MyTable.fnDestroy();
                $('#data-estimasi').html(hasil);
            }
        });
    }

    $(document).on("click", ".cetak-pk", function() {
        var id = $(this).attr("data-id");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BeaPK/cetakPk'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-pk').html(data);
                $('#cetak-pk').modal('show');
            })
    })
    $(document).on('submit', '#formBea', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('BeaPK/update_bea_pk'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showPk();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("formBea").reset();
                    //$('#pause-pk').modal('hide');
                    $('.msg').html(out.msg);
                    listPart();
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            })

        e.preventDefault();
    });
    </script>