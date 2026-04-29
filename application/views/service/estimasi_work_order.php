<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card-body card-outline">
                <div class="modal-body">

                    <?php
                                            if (!empty($dataCus)) {
                                                foreach ($dataCus as $a) {
                                                 foreach ($dataSa as $b) {
                                                }}}

						
						?>
                    <form id="formPo" name="formPo" method="POST">
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">No WO</label>
                                <input type="text" name="wo_no" id="wo_no" value="<?php echo $b->wo_no ?>"
                                    class="form-control" placeholder="Nomor Referensi">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Date</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                    <input type="text" name="tgl_estimasi_penawaran" id="tgl_estimasi_penawaran"
                                        value="" class="form-control tgl_estimasi_penawaran datetimepicker"
                                        data-toggle="datetimepicker" data-target=".tgl_estimasi_penawaran"
                                        data-format="yyy-mm-dd" required>

                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Customer No</label>
                                <input type="hidden" name="kode_cus" id="kode_cus" value="<?php echo $a->kode_cus; ?>"
                                    class="form-control" placeholder="Customer No">
                                <input type="text" name="nama_cus" id="nama_cus" value="<?php echo $a->nama_cus; ?>"
                                    class="form-control" placeholder="Customer Name">

                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Registration No</label>
                                <input type="text" name="no_reg" id="no_reg" value="" class="form-control"
                                    placeholder="No Registration">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">VIN No</label>
                                <input type="text" name="no_vin" id="no_vin" value="<?php echo $b->vin; ?>" class="form-control"
                                    placeholder="VIN">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Sales designation</label>
                                <input type="text" name="sales_design" id="sales_design" value="<?php echo $b->sa_name; ?>" class="form-control"
                                    placeholder="Sales designation">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Date/time received</label>
                                <input type="text" name="date_received" id="date_received" value="" class="form-control"
                                    placeholder="Date Received">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Millage/Km</label>
                                <input type="text" name="millage" id="millage" value="" class="form-control"
                                    placeholder="Millage / Km">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">Engine No</label>
                                <input type="text" name="engine_no" id="engine_no" value="<?php echo $b->engine_no; ?>" class="form-control"
                                    placeholder="Engine No">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Account No</label>
                                <input type="text" name="acc_no" id="acc_no" value="" class="form-control"
                                    placeholder="Account No">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Received by</label>
                                <input type="text" name="received_by" id="received_by" value="<?php echo $b->sa_name; ?>" class="form-control"
                                    placeholder="Received by">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Routing No</label>
                                <input type="text" name="routing_no" id="routing_no" value="" class="form-control"
                                    placeholder="Routing No">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">Last Service
                                    date/millage/km</label>
                                <input type="text" name="last_km" id="last_km" value="" class="form-control"
                                    placeholder="Last Service date/millage/km">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Date of 1st
                                    registration</label>
                                <input type="text" name="date_of_regis" id="date_of_regis" value="" class="form-control"
                                    placeholder="Date of 1st registration">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">P P N</label>
                                <input type="text" name="ppn" id="ppn" value="" class="form-control" placeholder="PPN">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Bea Kirim</label>
                                <input type="text" name="bea_kirim" id="bea_kirim" value="0"
                                    onkeyup="formatNumber(this)" onchange="formatNumber(this);" class="form-control"
                                    placeholder="Bea Pengiriman Barang" style="text-align:right; color:red;">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="Nama Konsumen" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-4">

                            </div>
                        </div>
                        <input type="hidden" name="user" id="user"
                            value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                        <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-xl bg-gradient-success" id="tambah-part"
                        onclick="panggilPart()" title="Add Part" data-toggle="modal" data-target="#modal_part"><i
                            class="fas fa-plus"></i> Tambah
                        Barang</button>
                    <button type="button" class="btn btn-xl bg-gradient-info" id="tambah-jasa" onclick="panggilTabel()"
                        title="Add Part" data-toggle="modal" data-target="#modal_operation"><i class="fas fa-plus"></i>
                        Tambah
                        Jasa</button>
                            <button class="btn btn-primary" id="simpan" type="submit" hidden="hidden"><span
                                    class="fa fa-save"></span>
                                Save All Data</button>
                            <button type="button" class="btn btn-secondary cetak-po" id="cetak" hidden="hidden" data-id=""
                                title="Add Data"><i class="fas fa-print"></i> Cetak Estimasi
                                Penawaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-body card-outline">
                <div class="card-header card-dark">
                    <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i>
                        &nbsp; Keterangan</h3>
                    <div class="text-right">
                        <button type="button" class="btn btn-sm btn-dark" onclick="insertNote()"><i
                                class="fas fa-plus"></i>
                            Standart Keterangan</button>
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
        <div class="card-body card-outline">
            <div id="modal-po"></div>
            <div id="data-po"></div>
            <div id="data-po-cache"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_part" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body form">
                            <div class="card card-first card-outline">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table width="100%" class="table no-wrap table-hover nowrap" id="tabel-part">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>No Part</th>
                                                    <th>Nama Part</th>
                                                    <th>Satuan</th>
                                                    <th>Stok</th>
                                                    <th>Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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

            <div class="modal fade" id="modal_operation" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body form">
                            <div class="card card-first card-outline">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table width="100%" class="table no-wrap table-hover nowrap"
                                            id="tabel-operation">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Code</th>
                                                    <th>Hours</th>
                                                    <th>Type of Work</th>
                                                    <th>Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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
</section>
<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data PO Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

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
   

$('#modal_operation').on('hidden.bs.modal', function() {
    if ($.fn.DataTable.isDataTable('#tabel-operation')) {
        $('#tabel-operation').DataTable().destroy();
        $('#tabel-operation tbody').empty();
    }
});
$('#modal_part').on('hidden.bs.modal', function() {
    if ($.fn.DataTable.isDataTable('#tabel-part')) {
        $('#tabel-part').DataTable().destroy();
        $('#tabel-part tbody').empty();
    }
});
function panggilPart() {
    //datatables
    table = $("#tabel-part").DataTable({

        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 5,


        "language": {
            "sEmptyTable": "Data Service Appointment Belum Ada"
        },
        "processing": true, //Feature control the processing indicator.
        "serverSide": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('EstimasiPenawaranService/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 3], //first column / numbering column
            "orderable": false,
        }, ],

    })
}
    $('#tabel-part tbody').on('click', 'tr', function(e) {
        var data = table.row(this).data();
            var no_part = data[1];
            var nama_part = data[2];
            var satuan = data[3];
            var stok = data[4];
            var harga_baru = data[5];
            var jenis = 'P';
        var wo_no = document.getElementById('wo_no').value;
        $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaranService/prosesDetailPo'); ?>',
            data: "wo_no=" + wo_no +
                "&no_part=" + no_part +
                "&nama_part=" + nama_part +
                "&satuan=" + satuan +
                "&stok=" + stok +
                "&harga_baru=" + harga_baru +
                "&jenis=" + jenis
        })
        .done(function(data) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                class: 'bg-success', 
                //title: 'Mantap',
                showConfirmButton: false,
                timer: 1000
            })
        tampilDetail();
        tampilKeterangan();
        document.getElementById("simpan").hidden = false;
        $('#modal_part').modal('hide');
        })
        e.preventDefault();
    });


function panggilTabel() {
    //datatables
    table = $("#tabel-operation").DataTable({

        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 5,


        "language": {
            "sEmptyTable": "Data Service Appointment Belum Ada"
        },
        "processing": true, //Feature control the processing indicator.
        "serverSide": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('EstimasiPenawaranService/list_operation') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0, 3], //first column / numbering column
            "orderable": false,
        }, ],

    })
}
    $('#tabel-operation tbody').on('click', 'tr', function(e) {
        var data = table.row(this).data();
        var code = data[1];
        var hours = 'Hours';
        var operation = data[3];
        var stok_operation = '0';
        var harga = data[4];
        var jenis = 'S';
        var wo_no = document.getElementById('wo_no').value;
        $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaranService/prosesDetailPo'); ?>',
            data: "wo_no=" + wo_no +
                "&no_part=" + code +
                "&nama_part=" + operation +
                "&satuan=" + hours +
                "&stok=" + stok_operation +
                "&harga_baru=" + harga +
                "&jenis=" + jenis
        })
       .done(function(data) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                class: 'bg-success', 
                //title: 'Mantap',
                showConfirmButton: false,
                timer: 1000
            })
        tampilDetail();
        tampilKeterangan();
        document.getElementById("simpan").hidden = false;
        $('#modal_operation').modal('hide');
        })
        e.preventDefault();
    });
$('#formPo').submit(function(e) {
    var data = $(this).serialize();
        var wo_no = document.getElementById('wo_no').value;
        var kode_cus = document.getElementById('kode_cus').value;

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaranService/prosesPo'); ?>',
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
                /* document.getElementById("formPo"); //reset()	
                $('#tgl_estimasi_penawaran').attr('readonly', 'readonly');
                $('#top').attr('readonly', 'readonly');
                $('#status').attr('readonly', 'readonly');
                $('#supplier').attr('readonly', 'readonly');
                $('#keterangan').attr('readonly', 'readonly');
                $('#ppn').attr('readonly', 'readonly');

                var d = document.getElementById("cetak");
                d.setAttribute('data-id', wo_no + '|' + kode_cus);
                document.getElementById("cetak").hidden = false;
                //document.getElementById("tambah").hidden = false;
                document.getElementById("tambah-part").hidden = true;
                document.getElementById("data-po").hidden = true;
                document.getElementById("simpan").hidden = true;
                tampilDetailCache(wo_no);
                */
                document.getElementById('tab-work-order').click();
                document.getElementById("tab-estimasi-tab").hidden = true;
                document.getElementById("tab-work-order").active = true;
                $('#tabel-appointment').DataTable().ajax.reload();
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