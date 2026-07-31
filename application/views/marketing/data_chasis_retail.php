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
        padding: 2px 5px 2px 5px;
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
        padding: 2px 5px 2px 5px;
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

    .select2-container {
        width: 100% !important;
        /* Memastikan container tidak mengecil */
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
        /* Menjaga tinggi tetap konsisten */
    }
</style>
<div class="text-right">
    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-chasis-retail" title="Add Data"><i class="fas fa-plus"></i> Tambah Data</button>
</div>
<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs " id="custom-content-above-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-chasis-retail" data-toggle="pill" href="#tab-chasis" role="tab">
                    <i class="fa fa-bus"></i>
                    Chasis Retail</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" hidden="hidden" id="tab-spk-tab" data-toggle="pill" href="#tab-spk"
                    role="tab">
                    <i class="fas fa-luggage-cart"></i>
                    Proses SPK</a>
            </li>

        </ul>
        <div class="tab-content" id="custom-content-below-tabContent">

            <div class="tab-pane fade show active" id="tab-chasis" role="tabpanel" aria-labelledby="tab-chasis-retail">
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-nowrap" id="tabel-chasis">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tgl Masuk</th>
                                    <th>Retail</th>
                                    <th>Pemesan</th>
                                    <th>Alamat</th>
                                    <th>No NPWP</th>
                                    <th>Nama NPWP</th>
                                    <th>Alamat NPWP</th>
                                    <th>Tlp Pemesan</th>
                                    <th>Contact Person</th>
                                    <th>Nama BPKB</th>
                                    <th>No KTP</th>
                                    <th>Alamat BPKP</th>
                                    <th>Type</th>
                                    <th>No Rangka</th>
                                    <th>No Mesin</th>
                                    <th>Sales</th>
                                    <th>Gesekan</th>
                                    <th>Thn Produksi</th>
                                    <th>Pengiriman</th>
                                    <th>Harga Retail</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="tempat-modal"></div>
            <div id="modal-po"></div>
            <div id="data-po"></div>
            <div class="tab-pane show" id="tab-spk" role="tabpanel" aria-labelledby="tab-spk-tab">


                <div id="data-proses-spk"></div>

            </div>
        </div>
    </div>

</div>

<?php
show_my_confirm('hapusChasis', 'hapus-chasis', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>
<script type="text/javascript">
    $('#customer').select2({
        escapeMarkup: function(markup) {
            return markup; // Allow HTML in results
        },
        templateResult: function(data) {
            if (!data.id) {
                return data.text; // Return placeholder for empty search
            }
            // Read custom data attributes or split your content
            var col1 = data.text;
            var col2 = $(data.element).data('details') || '';

            // Return custom multi-column HTML
            return '<div style="display: flex; justify-content: space-between;">' +
                '<span style="font-weight: bold;">' + col1 + '</span>' +
                '<span style="color: gray;">' + col2 + '</span>' +
                '</div>';
        }
    });

    $('#tgl_masuk').datetimepicker({
        format: 'DD-MM-YYYY',
        date: moment()
    });
    $('#thn_produksi').datetimepicker({
        format: 'YYYY',
        viewMode: 'years',
        date: moment()
    });

    $(document).ready(function() {

        //datatables
        table = $("#tabel-chasis").DataTable({
            "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "buttons": [{
                    extend: 'copyHtml5',
                    text: '<i class="fas fa-copy"></i> Copy',
                    titleAttr: 'Copy',
                    title: 'Data Chasis Retail',
                    className: 'btn btn-sm  btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    titleAttr: 'Excel',
                    title: 'MoData Chasis Retail',
                    className: 'btn btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    titleAttr: 'PDF',
                    title: 'MoData Chasis Retail',
                    className: 'btn btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Cetak',
                    titleAttr: 'Print',
                    title: 'MoData Chasis Retail',
                    className: 'btn btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="fas fa-eye"></i> Tampilan',
                    titleAttr: 'Costum Tampilan',
                    className: 'btn btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    }
                }

            ],
            "responsive": false,
            "autoWidth": true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,

            "language": {
                "sEmptyTable": "Data Chasis Belum Ada"
            },
            "processing": true, //Feature control the processing indicator.
            "serverSide": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
            },
            "order": [],

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('ChasisRetail/ajax_list') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 19],
                "orderable": false,
                "visible": false,
                "targets": [4, 5, 6, 7, 8, 9, 10, 11, 12]
            }, ],

        });

    });
    $('#form-tambah-chasis-retail').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('ChasisRetail/prosesTchasis'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-chasis-retail").reset();
                    $('#tambah-chasis-retail').modal('hide');
                    $('.msg').html(out.msg);
                    table.ajax.reload();
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

    $(document).on("click", ".update-chasis", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ChasisRetail/updateChasis'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                //var out = jQuery.parseJSON(data);
                $('#tempat-modal').html(data);
                $('#update-chasis').modal('show');

            })

    })
    $(document).on('submit', '#form-update-chasis-retail', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('ChasisRetail/prosesUchasis'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                table.ajax.reload();
                if (out.status == 'form') {
                   Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else {
                    //document.getElementById("form-update-chasis").reset();
                    $('#update-chasis').modal('hide');
                    $('.msg').html(out.msg);
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

    $('#tambah-chasis-retail').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-chasis-retail').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-chasis", function() {
        id_chasis = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-chasis", function() {
        var id = id_chasis;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ChasisRetail/deleteChasis'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                table.ajax.reload();
                $('.msg').html(out.msg);
                $('#hapusChasis').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1200
                    })
                }
            })
    })

    //SPK

     $(document).on("click", ".update-spk", function() {
        var id = $(this).attr("data-id");

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('ChasisRetail/updateSpk'); ?>',
            data: 'id=' + id,
            success: function(hasil) {
                //$('#id_lapor').val(id);
                //MyTable.fnDestroy();

                //$('#tabel-operation').DataTable();
                $('#data-proses-spk').html(hasil);
                document.getElementById("tab-spk-tab").hidden = false;
                $("a[href='#tab-spk']").tab('show');
                startCalculate();
                //tampilLabor();
                //refresh();
            }
        });
    })
    $(document).on('submit', '#form-update-spk', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('ChasisRetail/prosesUchasis'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                table.ajax.reload();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-chasis").reset();
                    $('#update-chasis').modal('hide');
                    $('.msg').html(out.msg);
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


    $(document).on("click", ".proses-spk", function() {
        var id = $(this).attr("data-id");

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('ChasisRetail/processSPK'); ?>',
            data: 'id=' + id,
            success: function(hasil) {
                //$('#id_lapor').val(id);
                //MyTable.fnDestroy();

                //$('#tabel-operation').DataTable();
                $('#data-proses-spk').html(hasil);
                document.getElementById("tab-spk-tab").hidden = false;
                $("a[href='#tab-spk']").tab('show');
                startCalculate();
                //tampilLabor();
                //refresh();
            }
        });
    })

    function cariChasis() {
        //var tgl_po = document.getElementById("tgl_po").value;
        $.get('<?php echo base_url('ChasisRetail/dataChasis'); ?>',
            function(data) {
                // success: function (data) {
                //MyTable.fnDestroy(); //refresh();
                $('#data-chasis').html(data);
            })
    }
    var MyTable = $('#list-chasis').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true
    });

    function selectData(chasis_id, no_rangka, type, no_mesin, thn_produksi) {

        $('[name = "chasis_id"]').val(chasis_id);
        $('[name = "no_rangka"]').val(no_rangka);
        $('[name = "type"]').val(type);
        $('[name = "no_mesin"]').val(no_mesin);
        $('[name = "thn_produksi"]').val(thn_produksi);


        $('#modal_chasis').modal('hide');
    }
    $(document).on("click", ".print-spk", function() {
        var id = $(this).attr("data-id");
        //var id = document.getElementById('next_proses').value=datakode;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ChasisRetail/cetak_ulang'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#data-po').html(data);
                $('#cetak-po').modal('show');
            })
    })
    
</script>