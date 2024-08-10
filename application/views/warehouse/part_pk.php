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

            <div class="card">

                <div class="modal-content">

                    <div class="card-header card-blue card-outline">

                        <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Pekerjaan Yang Masih
                            Aktif</h3>

                    </div>
                    <p></p>

                    <div class="col-12 ">

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover nowrap" id="list-pkaktif">

                                <thead>

                                    <tr>

                                        <th width='5%'>No</th>

                                        <th>Id PK</th>

                                        <th>No Body</th>

                                        <th>Kode PK</th>

                                        <th>Keterangan</th>

                                        <th>Aksi</th>

                                    </tr>

                                </thead>

                                <tbody id="data-pk">

                                </tbody>

                                <tfoot></tfoot>

                            </table>

                        </div>

                    </div>

                    <div id="modal-pk"></div>

                    <div id="modal-print"></div>

                </div>

            </div>

        </div>

    </div>



    <?php

show_my_confirm('startPk', 'start-pk', 'PK Dimulai', 'Ya, Mulai', 'Batal Mulai');

?>

    <script type="text/javascript">
    window.onload = function() {

        showPk();

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



    function refresh() {

        MyTable = $('#list-pkaktif,#list-detail').dataTable();

    }

    var MyTable = $('#list-pkaktif,#list-detail').dataTable();



    function showPk() {

        $.get('<?php echo base_url('PartPk/showPk'); ?>', function(data) {

            MyTable.fnDestroy();

            $('#data-pk').html(data);

            refresh();

        });

    }





    $(document).on("click", ".part-pk", function() {

        var id = $(this).attr("data-id");
        var id_spk = $(this).attr("data-spk");



        $

            .ajax({

                method: "POST",

                url: "<?php echo base_url('PartPk/Part'); ?>",

                data: "id=" + id + "&id_spk=" + id_spk

            })

            .done(function(data) {

                $('#modal-pk').html(data);

                $('#part-pk').modal('show');

            })

    });

    $(document).on('submit', '#formKeluar', function(e) {

        var data = $(this).serialize();



        $.ajax({

                method: 'POST',

                url: '<?php echo base_url('PartPk/prosesKeluar'); ?>',

                data: data

            })

            .done(function(data) {

                var out = jQuery.parseJSON(data);



                if (out.status == 'form') {

                    //toastr.error(out.msg);

                    $('.msg').html(out.msg);

                    refresh();

                    effect_msg();

                } else {

                    $('.msg').html(out.msg);

                    $('.dataKeluar').html(out.dataKeluar);

                    Swal.fire({

                        position: 'center',

                        icon: 'success',

                        title: 'Sukses',

                        showConfirmButton: false,

                        timer: 900,

                        imageUrl: 'assets/dist/img/thumbs.png'

                    })

                    document.getElementById("btnCetak").hidden = false;

                    document.getElementById("btnSimpan").hidden = true;

                    //document.getElementById("formKeluar").reset();

                    //next(out.dataKeluar);					

                    //showDetail(out.dataKeluar);

                    //tampilDetail(out.dataKeluar)

                }

            })



        e.preventDefault();

    });



    function next(dataKeluar) {

        document.getElementById('id_keluar').value = dataKeluar;

        //var d = document.getElementById("cetak");

        //d.setAttribute('data-id', dataKeluar);



        //document.getElementById("cetak").hidden = false;

        //document.getElementById("alamat").readonly = true;

    }



    function showDetail() {

        var id_keluar = document.getElementById('id_keluar').value;
        var id_lapor = document.getElementById('id_lapor').value;

        //var id_keluar = document.formKeluar.id_keluar.value;

        $.ajax({

            type: 'GET',

            url: '<?php echo base_url('PartPk/showDetail'); ?>?id_keluar=' + id_keluar,

            data: 'id_keluar=' + id_keluar + '&id_lapor=' + id_lapor,

            success: function(hasil) {

                MyTable.fnDestroy();

                $('#detail-partpk').html(hasil);

                refresh();

            }

        });





    }

    function isi_otomatis() {

        var no_part = document.getElementById('no_part').value;

        $.ajax({

            url: "<?php echo site_url('PartPk/cariPart') ?>/" + no_part,

            type: "GET",

            dataType: "JSON",

            success: function(data) {



                //$('[name = "id_barang"]').val(data.id_barang);

                $('[name = "no_part"]').val(data.no_part);

                $('[name = "nama_part"]').val(data.nama_part);

                $('[name = "stok_awal"]').val(data.stok);

                $('[name = "stok_a"]').val(data.stok_a);

                $('[name = "stok_p"]').val(data.stok_p);

                $('[name = "supplier"]').val(data.supplier);

                $('[name = "hrg_awal"]').val(data.hrg_awal);

                //document.getElementById('supplier').innerHTML   = data.supplier;

            },

            error: function(jqXHR, textStatus, errorThrown) {

                alert('Error get data from ajax');

            }

        });

    }

    //dari PO

    function selectPart3(id_barang, no_part, nama_part, stok, stok_a, stok_p, hrg_awal) {

        var no_body = document.formKeluar.no_body.value;

        var id_pk = document.formKeluar.id_pk.value;

        var id_lapor = document.formKeluar.id_lapor.value;

        var status_part = document.formKeluar.status_part.value;

        var jumlah = document.formKeluar.jumlah.value;

        var user = document.formKeluar.user.value;

        $.ajax({

            method: 'POST',

            url: '<?php echo base_url('PartPk/prosesKeluar'); ?>',

            data:

                "&id_pk=" + id_pk +

                "&id_lapor=" + id_lapor +

                "&no_body=" + no_body +

                "&id_barang=" + id_barang +

                "&no_part=" + no_part +

                "&nama_part=" + nama_part +

                "&status_part=" + status_part +

                "&stok=" + stok +

                "&stok_a=" + stok_a +

                "&stok_p=" + stok_p +

                "&hrg_awal=" + hrg_awal +

                "&jumlah=" + jumlah +

                "&user=" + user

        })

        showDetail(id_pk);

        $('#modal_form').modal('hide');

    }



    function selectPart2(id_barang) {

        $.ajax({

            url: "<?php echo site_url('PurchaseOrder/cariKode') ?>/" + id_barang,

            type: "GET",

            dataType: "JSON",

            success: function(data) {



                $('[name = "id_barang"]').val(data.id_barang);

                $('[name = "no_part"]').val(data.no_part);

                $('[name = "nama_part"]').val(data.nama_part);

                $('[name = "stok_awal"]').val(data.stok);

                $('[name = "stok_a"]').val(data.stok_a);

                $('[name = "stok_p"]').val(data.stok_p);

                $('[name = "supplier"]').val(data.supplier);

                $('[name = "hrg_awal"]').val(data.hrg_awal);

                //document.getElementById('supplier').innerHTML   = data.supplier;

            },

            error: function(jqXHR, textStatus, errorThrown) {

                alert('Error get data from ajax');

            }

        });



        $('#modal_form').modal('hide');

    }



    $(document).on("click", ".detail-part-pk", function() {

        var id = $(this).attr("data-id");

        $.ajax({

                method: "POST",

                url: "<?php echo base_url('PartPk/cetakDetail'); ?>",

                data: "id=" + id

            })

            .done(function(data) {

                $('#modal-pk').html(data);

                $('#cetak-detail').modal('show');

            })

    })

    function cetakBon(datakode) {}



    $(document).on("click", ".cetak-bon", function() {

        var id = document.getElementById('id_keluar').value;

        if (id == "") {

            Swal.fire({

                position: 'center',

                icon: 'error',

                title: 'Data Tidak Ada',

                showConfirmButton: false,

                timer: 900

            })

        } else {

            //var id = document.getElementById('next_proses').value=datakode;

            $.ajax({

                    method: "POST",

                    url: "<?php echo base_url('PartPk/cetakBon'); ?>",

                    data: "id=" + id

                })

                .done(function(data) {

                    $('#part-pk').modal('hide');

                    $('#modal-print').html(data);

                    $('#cetak-bon').modal('show');

                })

        }

    })
    </script>