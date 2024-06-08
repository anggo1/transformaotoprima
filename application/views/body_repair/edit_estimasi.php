<style>
    .table.DataTable {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 11px;
    }

    table.dataTable td {
        padding-bottom: 5px;
    }

    .Blink-warning {
        animation: blinker 5s cubic-bezier(.5, 0, 1, 1) infinite alternate;
    }

    @keyframes blinker {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    .Blink-danger {
        animation: blinker 0.1s cubic-bezier(.5, 0, 1, 1) infinite alternate;
    }

    @keyframes blinker {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    .tombol-success {
        background-color: green;
        border: none;
        color: white;
        padding: 2px 5px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 10px;
        float: right;
    }

    .tombol-success {
        border-radius: 50%;
    }

    .tombol-warning {
        background-color: #ffc107;
        border: none;
        color: white;
        padding: 2px 5px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 10px;
        margin: 4px 2px;
        float: right;
    }

    .tombol-warning {
        border-radius: 50%;
    }
</style>
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="modal-body form">
                                <?php foreach ($dataLapor as $s) { }?>
                <div class="card card-first card-outline">
                    <div class="card-body">
                        <form id="formEstimasi" name="formEstimasi" method="POST">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tanggal</label>
                                <div class="col-sm-9">
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
                                </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">No Body</label>
                                <div class="col-sm-9">
                                    <input
                                        type="text"
                                        name="body_pk"
                                        id="body_pk"
                                        class="form-control"
                                        readonly="readonly"
                                        required="required"
                                        value="<?php echo $s->no_body ?>"
                                        placeholder="No Body Tidak Boleh Kosong" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Pekerjaan</label>
                                <div class="col-sm-9">
                                <select name="jns_pk" id="jns_pk" class="form-control"onchange="myFunction()" onclick="myFunction()" required>
                                        <option value="">Jenis PK...
                                        </option>
                                        <?php
											if (!empty($dataPk)) {
												foreach ($dataPk as $pk) {   ?>
                                        <option value="<?php echo $pk->kode; ?>|<?php echo $pk->jml_part; ?>">
                                            <?php echo $pk->keterangan; ?>&nbsp; => &nbsp;<?php echo $pk->jml_part; ?> Barang
                                        </option>
                                        <?php
												}
											}
											?>
                                    </select>
                                </div>
                                </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Estimasi Waktu</label>
                                <div class="col-sm-9">
                                    <input
                                        type="text"
                                        name="jam_kerja"
                                        id="jam_kerja"
                                        class="form-control">
                                </div>
                                </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Biaya Pekerjaan</label>
                                <div class="col-sm-9">
                                    <input type="text" name="biaya" id="biaya" class="form-control" value="0" placeholder="Biaya Pekerjaan">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Penyesuaian Harga</label>
                                <div class="col-sm-6">
                                    <input type="text" name="hrg_naik" id="hrg_naik" class="form-control" value="0" placeholder="Penyesuaian Harga Barang dan Proses">
                                </div> %
                            </div>
                            <input
                                type="hidden"
                                name="id_lapor"
                                id="id_lapor"
                                class="form-control"
                                readonly="readonly" value="<?php echo $s->id_lapor ?>">
                            <input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>"
                                class="form-control">
                            <div class="modal-footer center-content-between">
                                <button class="btn btn-primary" id="sEstimasi" name="sEstimasi" type="submit"><span class="fa fa-save"></span> Simpan</button>
                        </form>
                            </div>
                                <button class="btn btn-warning cetak-estimasi2 col-12" id="cetakEstimasi" title="Cetak Estimasi"><i class="fa fa-print"></i> Cetak Estimasi
								</button>
                            </div>
                            </div>
                            </div>
                            </div>
                            <div class="col-md-8">
                                <div id="data-estimasi"></div>
                            <div id="modal-estimasi2"></div>

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

    function refreshEstimasi() {
        MyTable = $('#list-estimasi').dataTable();
    }
    var MyTable = $('#list-estimasi').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength":10,
        "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem('offersDataTables', JSON.stringify(oData));
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse(localStorage.getItem('offersDataTables'));
        }
    });

    function tampilEstimasi() {
		var id_lapor = document.getElementById('id_lapor').value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('Estimator/tampilEstimasi')?>',
            data: 'id_lapor=' + id_lapor,
            success: function (hasil) {
            MyTable.fnDestroy();
                $('#data-estimasi').html(hasil);
                refreshEstimasi();
            }
        });
    }

    $('#formEstimasi').submit(function (e) {
        var data = $(this).serialize();

        $
            .ajax({
                method: 'POST',
                url: '<?php echo base_url('Estimator/prosesEstimasi'); ?>',
                data: data
            })
            .done(function (data) {
                var out = jQuery.parseJSON(data);

                if (out.status == 'form') {
                    Swal.fire(
                        {position: 'center', icon: 'error', title: out.msg, showConfirmButton: false, timer: 1500}
                    )
                } else {
                    document.getElementById("cetakEstimasi").hidden = false;
                    document.getElementById("formEstimasi");
                    $('#no_part').val('');
                    $('#nama_part').val('');
                    $('#ket_part').val('');
                    $('#jml_part').val('');
                    $('#biaya').val('0');
                    $('.msg').html(out.msg);
                    //table.ajax.reload();
                    tampilEstimasi();
                    Swal.fire(
                        {position: 'center', icon: 'success', title: out.msg, showConfirmButton: false, timer: 1500}
                    )
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
    $(document).on("click", ".delete-estimasi", function () {
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
                if (out.status != 'form') {
                }
            })
    })
    $(document).on("click", ".estimasi", function () {
        var id_lapor = $(this).attr("id_lapor");
        var no_body = $(this).attr("no_body");

        $("a[href='#tab-proses-tab']").tab('show');
        $("a[href='#tab-proses']").tab('show');
        document.getElementById("tab-proses-tab").hidden = false;
        document.getElementById('body_pk').value = no_body;
        document.getElementById('id_lapor').value = id_lapor;
        //$("#pekerjaan").attr('disabled', 'disabled');
    })

    function fungsiUpdate() {
        var id = $(this).attr("data-id");
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BusMasuk/tampilPk'); ?>?id=' + id,
            data: 'id=' + id,
            success: function (hasil) {
                $('#id_lapor').val(id);
                MyTable.fnDestroy();
                $('#data-proses-pk').html(hasil);
            }
        });
    }
    function myFunction() {
  var x = document.getElementById("jns_pk").value;
  var mystr = x;
        var myarr = mystr.split("|");
        var myvar = myarr[1];
        var myvar2 = myarr[2];
        console.log(myvar);
        if(myvar == 0){
            document.getElementById("sEstimasi").disabled = true;
		}else{
			
			document.getElementById("sEstimasi").disabled = false;
		}
        }
//** End Estimasi */
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
    $(document).on("click", ".proses-pk", function () {
        var id = $(this).attr("data-id");

        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BusMasuk/tampilPk'); ?>?id' + id,
            data: 'id=' + id,
            success: function (hasil) {
                $('#id_lapor').val(id);
                MyTable.fnDestroy();
                $('#data-proses-pk').html(hasil);
                document.getElementById("tab-pk-tab").hidden = false;
                $("a[href='#tab-pk']").tab('show');
                //refresh();
            }
        });
    })

    $(document).on("click", ".cetak-estimasi", function () {
        var id = $(this).attr("data-id");
		
        //var id = document.getElementById('next_proses').value=datakode;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Estimator/cetakEstimasi'); ?>",
                data: "id="+id
            })
            .done(function (data) {
                $('#modal-estimasi').html(data);
                $('#cetak-estimasi').modal('show');
            })
    })
	$(document).on("click", ".cetak-estimasi2", function () {
		var id = document.getElementById('id_lapor').value;
           $ .ajax({
                method: "POST",
                url: "<?php echo base_url('Estimator/cetakEstimasi2'); ?>",
                data: "id="+id
            })
            .done(function (data) {
                $('#modal-estimasi2').html(data);
                $('#cetak-estimasi2').modal('show');
            })
    })
    $(document).on("click", ".update-pk", function () {
        var id = $(this).attr("data-id");
        var kode = $(this).attr("kode");

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('BusMasuk/cariPKproses'); ?>",
                data: "id=" + id + "&kode=" + kode
            })
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#proses-pk').modal('show');
            })
    })
    $(document).on('submit', '#form-update-pk', function (e) {
        var data = $(this).serialize();

        $
            .ajax({
                method: 'POST',
                url: '<?php echo base_url('Settingbr/prosesUpk'); ?>',
                data: data
            })
            .done(function (data) {
                var out = jQuery.parseJSON(data);

                showPk();
                if (out.status == 'form') {                    
                    Swal.fire({position: 'top-end', icon: 'error', title: out.msg, showConfirmButton: false, timer: 1000})
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-pk").reset();
                    $('#update-pk').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({position: 'top-end', icon: 'success', title: out.msg, showConfirmButton: false, timer: 1500})
                }
            })

        e.preventDefault();
    });
    //** proses PK */
	function tampilPkawal(datakode) {
        var id = document.getElementById('id_lapor').value = datakode;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BusMasuk/tampilPk'); ?>?id=' + id,
            data: 'id=' + id,
            success: function (hasil) {
                $('#id_lapor').val(id);
                MyTable.fnDestroy();
                $('#data-proses-pk').html(hasil);
            }
        });
    }

    function tampilPk(datakode) {
        var id_lapor = document.getElementById('id_lapor').value = datakode;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BusMasuk/tampilPkMulai'); ?>?id_lapor=' + id_lapor,
            data: 'id_lapor=' + id_lapor,
            success: function (hasil) {
                MyTable.fnDestroy();
                $('#data-pk-mulai').html(hasil);
            }
        });
    }
    $(document).on("click", ".cetak-pk", function () {
		var id = document.getElementById('id_lapor').value;
           $ .ajax({
                method: "POST",
                url: "<?php echo base_url('BusMasuk/cetakPk'); ?>",
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