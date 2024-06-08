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
<div class="card-body card-outline">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs " id="custom-content-above-tab" role="tablist">
                <li class="nav-item">
                    <a
                        class="nav-link active"
                        id="tab-daftar-tab"
                        data-toggle="pill"
                        href="#tab-daftar"
                        role="tab">
                        <i class="fa fa-bus"></i>
                        Laporan Bus Masuk</a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        hidden="hidden"
                        id="tab-proses-tab"
                        data-toggle="pill"
                        href="#tab-proses"
                        role="tab">
                        <i class="fas fa-luggage-cart"></i>
                        Estimasi Perbaikan</a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        hidden="hidden"
                        id="tab-pk-tab"
                        data-toggle="pill"
                        href="#tab-pk"
                        role="tab">
                        <i class="fas fa-retweet"></i>
                        Proses Pekerjaan</a>
                </li>

            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">

                <div
                    class="tab-pane fade show active"
                    id="tab-daftar"
                    role="tabpanel"
                    aria-labelledby="tab-daftar-tab">

                        <div class="card-body">
                    <form id="form-tambah-laporan" method="POST">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tanggal &amp; Jam</label>
                                <div class="col-sm-2">
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input
                                            type="text"
                                            name="tgl_masuk"
                                            id="tgl_masuk"
                                            class="form-control tgl_masuk datetimepicker"
                                            data-toggle="datetimepicker"
                                            data-target=".tgl_masuk"
                                            data-format="yyy-mm-dd"
                                            required="required">
                                        <div class="input-group-append" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                                        <input
                                            type="text"
                                            name="jam_masuk"
                                            id="jam_masuk"
                                            class="form-control jam datetimepicker-input"
                                            data-toggle="datetimepicker"
                                            data-target=".jam"
                                            data-format="hh:mm"/>
                                        <div class="input-group-append" data-target="#jam" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">No Body</label>
                                <div class="col-sm-1">
                                <input
                                            type="text"
                                            name="no_body"
                                            id="no_body"
                                            readonly="readonly"
                                            class="form-control"
                                                data-toggle="modal"
                                                data-target="#modal_body"
                                        placeholder="Nomor Body" required>
                                </div>
                                <label class="col-sm-1 col-form-label">No Pol</label>
                                <div class="col-sm-2">
                                    <input
                                        type="text"
                                        name="no_pol"
                                        id="no_pol"
                                        class="form-control"
                                        style="text-transform: uppercase;"
                                        placeholder="Nomor Polisi ?">
                                </div>
                                <label class="col-sm-2 col-form-label">NIP Pengemudi</label>
                                <div class="col-sm-1">
                                    <input type="text" name="nip_sp" id="nip_sp" class="form-control" placeholder="NIP">
                                </div>
                                <label class="col-sm-1 col-form-label">Nama</label>
                                <div class="col-sm-2">
                                    <input
                                        type="text"
                                        name="nama_sp"
                                        id="nama_sp"
                                        class="form-control"
                                        placeholder="Nama Pengemudi ?">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Keterangan Lapor</label>
                                <div class="col-sm-4">
                                    <select name="ket_lapor" class="form-control" required> 
                                        <option value="">Pilih...
                                        </option>
                                        <?php
											if (!empty($dataLap)) {
												foreach ($dataLap as $l) {   ?>
                                        <option value="<?php echo $l->id; ?>">
                                            <?php echo $l->kode.' | '.$l->keterangan; ?>
                                        </option>
                                        <?php
												}
											}
											?>
                                    </select>
                                </div>
                                <label class="col-sm-2 col-form-label">Kategori Perbaikan</label>
                                <div class="col-sm-4">
                                    <select name="kategori" class="form-control" required>
                                        <option value="">Pilih...
                                        </option>
                                        <?php
											if (!empty($dataKat)) {
												foreach ($dataKat as $j) {   ?>
                                        <option value="<?php echo $j->id_kategori; ?>|<?php echo $j->kode; ?>">
                                            <?php echo $j->kategori; ?>
                                        </option>
                                        <?php
												}
											}
											?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-4">
                                    <input
                                        type="text"
                                        name="keterangan"
                                        id="keterangan"
                                        value=""
                                        class="form-control"
                                        placeholder="Keterangan">
                                </div>
                            </div>

                            <input type="hidden" name="status_body" id="status_body" class="form-control">
                            <input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                            <input type="hidden" name="id_bast" id="id_bast" value="" class="form-control">
                            <div class="modal-footer center-content-between">
                                <button class="btn btn-primary" type="submit">
                                    <span class="fa fa-save"></span>
                                    Simpan</button>
                            </div>
                        </form>
                        <?php show_my_confirm('hapusLaporan', 'hapus-laporan', 'Hapus Data Laporan Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabel-masuk" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr class="bg-indigo">
                                                <th>No</th>
                                                <th>No Body</th>
                                                <th>No Pol</th>
                                                <th>Pelapor</th>
                                                <th>K.Lapor</th>
                                                <th>Kategori</th>
                                                <th>Tgl Masuk</th>
                                                <th>Jam Masuk</th>
                                                <th>Keterangan</th>
                                                <th>AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                            <div id="modal-estimasi"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane show" id="tab-proses" role="tabpanel" aria-labelledby="tab-proses-tab">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="modal-body form">
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
                                readonly="readonly">
                            <input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>"
                                class="form-control">
                            <div class="modal-footer center-content-between">
                                <button class="btn btn-primary" id="sEstimasi" name="sEstimasi" type="submit"><span class="fa fa-save"></span> Simpan</button>
                        </form>
                            </div>
                                <button class="btn btn-warning cetak-estimasi2 col-12" id="cetakEstimasi" hidden="" title="Cetak Estimasi"><i class="fa fa-print"></i> Cetak Estimasi
								</button>
                            </div>
                            </div>
                            </div>
                            </div>
                            <div class="col-md-8">
                                <div id="data-estimasi"></div>

                            </div>
                            </div>
						
                    </div>
                </div>
                <div id="modal-estimasi2"></div>
                <div
                    class="tab-pane show"
                    id="tab-pk"
                    role="tabpanel"
                    aria-labelledby="tab-pk-tab">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">

                                <div id="data-proses-pk"></div>

								<div id="modal-pk"></div>
                            </div>
                            <div class="col-md-6">

                                <div id="data-pk-mulai"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_body" role="dialog">
    <div class="modal-dialog modal-xm">
        <div class="modal-content">
            <div class="modal-body form">
                <div class="card card-first card-outline">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-wrap table-hover nowrap" id="list-bast">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Body</th>
                                        <th>No pol</th>
                                        <th>Tgl BAST</th>
                                    </tr>
                                </thead>
                                <tbody id="data-bast">
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<?php show_my_confirm('hapusEstimasi', 'hapus-estimasi', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>
<?php show_my_confirm('hapusProses', 'hapus-proses', 'Anda Akan Membatalkan Semua Proses ?', 'Ya, Batalkan Data Ini', 'Batal Hapus data'); ?>
<script type="text/javascript">
    window.onload = function() {
        tampilBast();
    }
    $('#tgl_masuk,#tgl_estimasi,#tgl_pk').datetimepicker({format: 'DD-MM-YYYY', date: moment()});
    //Timepicker
    $('#jam_masuk').datetimepicker(
        {
            date: moment(),
            format: 'HH:mm', 
            pickDate: true, 
            pickSeconds: true, 
            pick12HourFormat: false,
            showTime:true,
}
    )
    $(document).ready(function () {
        table = $('#table-part').dataTable({
            "responsive": false,
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "processing": true,
            "serverSide": true,
            "pageLength": 5, // Defaults number of rows to display in table
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('PurchaseOrder/ajax_list') ?>",
                "type": "POST"
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false
                }
            ],
            "dom": '<"top"f>rt<"bottom"lp><"clear">',
            "fnDrawCallback": function () {
                $("input[type                                = 'search']").attr(
                    "id",
                    "searchBox"
                );
                $('#table-select').css('cssText', "margin-top: 0px !important;");
                $('#searchBox')
                    .css("width", "300px")
                    .focus();
                $('#table-select_filter').removeClass('dataTables_filter');
            }
        });
    });
    function selectBody(id_bast,no_body,no_pol,nip_sp,nama_sp,status_bus) {

                //$('[name = "no_body"]').val(data.no_body);
				document.getElementById('id_bast').value=id_bast;
				document.getElementById('no_body').value=no_body;
				document.getElementById('no_pol').value=no_pol;
				document.getElementById('nip_sp').value=nip_sp;
				document.getElementById('nama_sp').value=nama_sp;
				document.getElementById('status_body').value=status_bus;
        $('#modal_body').modal('hide');
    }


    $(document).ready(function () {

        //datatables
        table = $("#tabel-masuk").DataTable({
            "responsive": false,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,

            "autoWidth": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
            },
            "order": [],

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('BusMasuk/ajax_list') ?>",
                "type": "POST"
            },
            "columnDefs": [
                {
                    "targets": [0,9],
                    "orderable": false
                }
            ]
        });

    });
    $('#form-tambah-laporan').submit(function (e) {
        var data = $(this).serialize();

        $
            .ajax({
                method: 'POST',
                url: '<?php echo base_url('BusMasuk/prosesLaporan'); ?>',
                data: data
            })
            .done(function (data) {
                var out = jQuery.parseJSON(data);

                if (out.status == 'form') {
                    Swal.fire(
                        {position: 'center', icon: 'error', title: out.msg, showConfirmButton: true}
                    )
                } else {
                    document
                        .getElementById("form-tambah-laporan")
                        .reset();
                    $('.msg').html(out.msg);
                    table.ajax.reload();
                    tampilBast();
                    Swal.fire(
                        {position: 'center', icon: 'success', title: out.msg, showConfirmButton: false, timer: 1500}
                    )
                }
            })

        e.preventDefault();
    });
    function refreshEstimasi() {
        MyTable = $('#list-estimasi,#list-bast').dataTable();
    }
    function refresh() {
        MyTable = $('#list-bast').dataTable();
    }
    
    var tableBast = $('#list-bast').DataTable();
    var MyTable = $('#list-estimasi').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength":10
    });
    function tampilBast() {
        $.get('<?php echo base_url('BusMasuk/showBast'); ?>', function(data) {
            tableBast.destroy();
            $('#data-bast').html(data);
            refreshEstimasi();
        });
    }
    function tampilEstimasi() {
        var id_lapor = document.formEstimasi.id_lapor.value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BusMasuk/tampilEstimasi'); ?>?id_lapor=' + id_lapor,
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
                url: '<?php echo base_url('BusMasuk/prosesEstimasi'); ?>',
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
                    table.ajax.reload();
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
                table.ajax.reload();
                $('.msg').html(out.msg);
                $('#hapusLaporan').modal('hide');
                if (out.status != 'form') {
                    tampilBast();
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
                $('#hapusEstimasi').modal('hide');
                if (out.status != 'form') {
                    
                }
            })
    })
    $(document).on("click", ".delete-proses", function () {
        id_lapor = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-proses", function () {
        var id = id_lapor;

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('BusMasuk/deleteProses'); ?>",
                data: "id=" + id
            })
            .done(function (data) {
                var out = jQuery.parseJSON(data);
                    table.ajax.reload();
                $('.msg').html(out.msg);
                $('#hapusProses').modal('hide');
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
                url: "<?php echo base_url('BusMasuk/cetakEstimasi'); ?>",
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
                url: "<?php echo base_url('BusMasuk/cetakEstimasi2'); ?>",
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