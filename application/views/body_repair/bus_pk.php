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
                    <div class="col-lg-12">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Pekerjaan Yang Masih Aktif</h3>
                                </div>
                                <div class="modal-body form">
    <div class="card card-first card-outline">
        <div class="card-body">
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pk">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Id PK</th>
                                                    <th>No Body</th>
                                                    <th>Tgl Masuk</th>
                                                    <th>NIP</th>
                                                    <th>Pengemudi</th>
                                                    <th>PK Aktif</th>
                                                    <th>No Bay</th>
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
        showPk();
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

    function showPk() {
        $.get('<?php echo base_url('BusPk/showPk'); ?>', function(data) {
            MyTable.fnDestroy();
            $('#data-pk').html(data);
            refresh();
        });
    }
    
    $(document).on("click", ".edit-estimasi", function () {
        var id_lapor = $(this).attr("id_lapor");
        var no_body = $(this).attr("no_body");
        //window.location = 'Estimator'
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url('Estimator/tampilEstimasi'); ?>?id_lapor=" + id_lapor,
            data: "id_lapor="+id_lapor,
            success: function(data){
                //document.getElementById('id_lapor').value = id_lapor;
				$('[name = "id_lapor"]').val(id_lapor);
            window.location.href = 'Estimator';
                //$('Estimator').html(data);
            
       // document.getElementById('body_pk').value = no_body;
        //document.getElementById('id_lapor').value = id_lapor;// your action should return an object having [redirecturl] property
            },
            error: function(xhr, type, exception) { 
                // if ajax fails display error alert
                alert("ajax error response type "+type);
            }
        });
    })

    $(document).on("click", ".cetak-pk", function () {
        var id = $(this).attr("data-id");
           $ .ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/cetakPk'); ?>",
                data: "id="+id
            })
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#cetak-pk').modal('show');
            })
    })
    $(document).on("click", ".pause-pk", function () {
        var id = $(this).attr("data-id");

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/Pause'); ?>",
                data: "id=" + id
            })
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#pause-pk').modal('show');
            })
    })
    $(document).on('submit', '#pausePkaktif', function (e) {
						var data = $(this).serialize();

						$.ajax({
								method: 'POST',
								url: '<?php echo base_url('BusPk/pausePk'); ?>',
								data: data
							})
							.done(function(data) {
								var out = jQuery.parseJSON(data);
                                showPk();
                                if (out.status == 'form') {
                                    $('.form-msg').html(out.msg);
                                    effect_msg_form();
                                } else {
                                    document.getElementById("pausePkaktif").reset();
                                    $('#pause-pk').modal('hide');
                                    $('.msg').html(out.msg);
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

    $(document).on("click", ".start-pk-aktif", function () {
        id_pk = $(this).attr("data-pk");
    })
    $(document).on("click", ".start-pk", function () {
        var id = id_pk;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/startPk'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#startPk').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})

$(document).on("click", ".selesai-pk-aktif", function () {
        id_pk = $(this).attr("data-pk");
        body_pk = $(this).attr("body-pk");
    })
    $(document).on("click", ".selesai-pk", function () {
        var id = id_pk;
        var body = body_pk;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/tutupPk'); ?>",
                data: "id=" + id+"&body=" + body
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#selesaiPk').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})
$(document).on("click", ".input-bay", function () {
        var id = $(this).attr("data-pk");
        var bay = $(this).attr("data-bay");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/inputBay'); ?>",
                data: "id=" + id+"&bay=" + bay
            })
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#input-bay').modal('show');
            })
    })
    $(document).on("click", ".masuk-bay", function () {
        var idLapor = $(this).attr("id-lapor");
        var idBody = $(this).attr("id-body");
        var idBay = $(this).attr("id-bay");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/masukBay'); ?>",
                data: "idLapor=" + idLapor+"&idBody=" + idBody+"&idBay=" + idBay
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#input-bay').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})
$(document).on("click", ".masuk-cat", function () {
        var idLapor = $(this).attr("id-lapor");
        var idBody = $(this).attr("id-body");
        var idBay = $(this).attr("id-bay");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/masukCat'); ?>",
                data: "idLapor=" + idLapor+"&idBody=" + idBody+"&idBay=" + idBay
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#input-bay').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})
$(document).on("click", ".masuk-triming", function () {
        var idLapor = $(this).attr("id-lapor");
        var idBody = $(this).attr("id-body");
        var idBay = $(this).attr("id-bay");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/masukTriming'); ?>",
                data: "idLapor=" + idLapor+"&idBody=" + idBody+"&idBay=" + idBay
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#input-bay').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})
$(document).on("click", ".masuk-elektrik", function () {
        var idLapor = $(this).attr("id-lapor");
        var idBody = $(this).attr("id-body");
        var idBay = $(this).attr("id-bay");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/masukElektrik'); ?>",
                data: "idLapor=" + idLapor+"&idBody=" + idBody+"&idBay=" + idBay
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#input-bay').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})
$(document).on("click", ".masuk-qc", function () {
        var idLapor = $(this).attr("id-lapor");
        var idBody = $(this).attr("id-body");
        var idBay = $(this).attr("id-bay");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/masukQc'); ?>",
                data: "idLapor=" + idLapor+"&idBody=" + idBody+"&idBay=" + idBay
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#input-bay').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})
$(document).on("click", ".masuk-jok", function () {
        var idLapor = $(this).attr("id-lapor");
        var idBody = $(this).attr("id-body");
        var idBay = $(this).attr("id-bay");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/masukJok'); ?>",
                data: "idLapor=" + idLapor+"&idBody=" + idBody+"&idBay=" + idBay
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#input-bay').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})
$(document).on("click", ".masuk-ph", function () {
        var idLapor = $(this).attr("id-lapor");
        var idBody = $(this).attr("id-body");
        var idBay = $(this).attr("id-bay");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/masukPh'); ?>",
                data: "idLapor=" + idLapor+"&idBody=" + idBody+"&idBay=" + idBay
            })
            .done(function(data) {
        var out = jQuery.parseJSON(data);
		showPk();
        $('.msg').html(out.msg);
        $('#input-bay').modal('hide');
        if (out.status != 'form') {
            Swal.fire({
                position: 'center',
										icon: 'success',
										title: out.msg,
										showConfirmButton: false,
										timer: 1000
            })
        }
    })
})
$(document).on("click", ".tambah-pk", function () {
        var id = $(this).attr("data-pk");

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('BusPk/revPk'); ?>",
                data: "id=" + id
            })
            
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#tambah-pk').modal('show');
                tampilPk();
            })
    })

    
    function tampilPk() {
        var id_lapor = document.getElementById('id_lapor').value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BusPk/tampilPk'); ?>?id_lapor=' + id_lapor,
            data: 'id_lapor=' + id_lapor,
            success: function (hasil) {
            MyTable.fnDestroy();
                $('#data-estimasi').html(hasil);
            }
        });
    }
</script>