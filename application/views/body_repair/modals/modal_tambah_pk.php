<div class="modal-body form">
    <div class="card card-first card-outline">
        <div class="card-header bg-light">
            <h3 class="card-title">
                <i class="fa fa-bus text-blue"></i>
                Penambahan PK Body
            </h3>
        </div>
        <div class="card-body">
            <div class="card-body">
                <?php foreach ($dataPk as $s) { }?>
                <form id="formEstimasi" name="formEstimasi" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-4">
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                <input
                                    type="text"
                                    name="tgl_estimasi"
                                    id="tgl_estimasi"
                                    value=""
                                    class="form-control tgl_estimasi datetimepicker"
                                    data-toggle="datetimepicker"
                                    data-target=".tgl_estimasi"
                                    data-format="yyy-mm-dd"
                                    required="required">

                                <div class="input-group-append" data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="col-sm-2 col-form-label">No Body</label>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                name="body_pk"
                                id="body_pk"
                                class="form-control"
                                readonly="readonly"
                                required="required"
                                value="<?php echo $s->no_body ?>"
                                placeholder="No Body Tidak Boleh Kosong">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pekerjaan</label>
                        <div class="col-sm-2">
                            <select name="jns_pk" id="jns_pk" class="form-control">
                                <option value="">Jenis PK...
                                </option>
                                <?php
											if (!empty($jenisPk)) {
												foreach ($jenisPk as $pk) {   ?>
                                <option value="<?php echo $pk->kode; ?>">
                                    <?php echo $pk->kode.' => '.$pk->keterangan; ?>
                                </option>
                                <?php
												}
											}
											?>
                            </select>
                        </div>
                        <label class="col-sm-1 col-form-label">Jam Kerja</label>
                        <div class="col-sm-1">
                            <input
                                type="text"
                                name="jam_kerja"
                                id="jam_kerja"
                                class="form-control"
                                placeholder="Total Jam">
                        </div>
                        <label class="col-sm-2 col-form-label">Biaya Pekerjaan</label>
                        <div class="col-sm-4">
                            <input
                                type="text"
                                name="biaya"
                                id="biaya"
                                class="form-control"
                                value="0"
                                placeholder="Persetujuan">
                        </div>
                    </div>
                    <div class="form-group row">
								<label class="col-sm-2 col-form-label">Pemborong</label>
								<div class="col-sm-4">
									<div class="input-group date" id="reservationdate" data-target-input="nearest">
										<input type="text" name="pt_pemborong" id="pt_pemborong"
											class="form-control">
									</div>

								</div>
								<label class="col-sm-2 col-form-label">Kepala Borong</label>
								<div class="col-sm-4">
									<div class="input-group date" id="reservationdate" data-target-input="nearest">
										<input type="text" name="pj_borong" id="pj_borong"
											class="form-control" required>
									</div>

								</div>
							</div>
                    <input type="hidden" name="id_lapor" id="id_lapor" value="<?php echo $s->id_lapor ?>" class="form-control">
                    <input type="hidden" name="hrg_awal" id="hrg_awal" class="form-control">
                    <input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>"
                        class="form-control">
                    <div class="modal-footer center-content-between">
                        
                        <button class="btn btn-primary" type="submit">
                            <span class="fa fa-save"></span>
                            Simpan</button>
                    </form>
                </div></div>
            <div class="modal-body form">
                <div class="card card-first card-outline">
                    <div class="card-body">                    
                    <div class="table-responsive">
                            <table class="table no-wrap table-hover nowrap" id="list-estimasi">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Laporan</th>
                                        <th>Jenis PK</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Pemborong</th>
                                    </tr>
                                </thead>
                                <tbody id="data-estimasi">
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                                <div id="modal-pk"></div>
            </div>
            <div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-xm">
        <div class="modal-content">
            <div class="modal-body form">
                <div class="card card-first card-outline">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-wrap table-hover nowrap" id="table-part">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Part</th>
                                        <th>Nama Part</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <script type="text/javascript">
    $('#tgl_masuk,#tgl_estimasi,#tgl_pk').datetimepicker({format: 'DD-MM-YYYY', date: moment()});
    //Timepicker
    $('#jam_masuk').datetimepicker(
        {format: 'HH:mm', pickDate: false, pickSeconds: false, pick12HourFormat: false}
    )
    window.onload = function() {
        tampilEstimasi();
    }
    function refresh() {
        MyTable = $('#list-estimasi').dataTable();
    }
    var MyTable = $('#list-estimasi').dataTable({
        "responsive": true,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 5
    });
    function selectBody(id_bast,no_body,no_pol,nip_sp,nama_sp) {

                //$('[name = "no_body"]').val(data.no_body);
				document.getElementById('id_bast').value=id_bast;
				document.getElementById('no_body').value=no_body;
				document.getElementById('no_pol').value=no_pol;
				document.getElementById('nip_sp').value=nip_sp;
				document.getElementById('nama_sp').value=nama_sp;
        $('#modal_body').modal('hide');
    }
    $('#formEstimasi').submit(function (e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('BusPk/prosesEstimasi'); ?>',
                data: data
            })
            .done(function (data) {
                var out = jQuery.parseJSON(data);
                tampilPk();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("formEstimasi").reset();
                }
            })

        e.preventDefault();
    });
    $(document).on("click", ".delete-laporan", function () {
        id_lapor = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-laporan", function () {
        var id = id_lapor;

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('BusMasuk/deleteLaporan'); ?>",
                data: "id=" + id
            })
            .done(function (data) {
                var out = jQuery.parseJSON(data);
                table
                    .ajax
                    .reload();
                $('.msg').html(out.msg);
                $('#hapusLaporan').modal('hide');
                if (out.status != 'form') {
                    Swal.fire(
                        {position: 'center', icon: 'error', title: out.msg, showConfirmButton: false, timer: 1200}
                    )
                }
            })
    })
    $(document).on("click", ".delete-estimasi", function () {
        id_detail = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-estimasi", function () {
        var id = id_detail;

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('BusMasuk/deleteEstimasi'); ?>",
                data: "id=" + id
            })
            .done(function (data) {
                var out = jQuery.parseJSON(data);
                tampilEstimasi();
                $('.msg').html(out.msg);
                $('#hapusEstimasi').modal('hide');
                if (out.status != 'form') {
                    Swal.fire(
                        {position: 'center', icon: 'error', title: out.msg, showConfirmButton: false, timer: 1200}
                    )
                }
            })
    })

    //** proses PK */

    $(document).on("click", ".cetak-pk", function () {
        var id = $(this).attr("data-id");
           $ .ajax({
                method: "POST",
                url: "<?php echo base_url('TambahPk/cetakUlangPk'); ?>",
                data: "id="+id
            })
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#cetak-pk').modal('show');
            })
    })
    var biaya = document.getElementById('biaya');
					biaya.addEventListener('keyup', function(e)
    {
        biaya.value = formatRupiah(this.value);
    });
    function formatRupiah(angka, prefix)
    {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah     = split[0].substr(0, sisa),
            ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>