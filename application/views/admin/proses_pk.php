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
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pk">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Id PK</th>
                                                    <th>No Body</th>
                                                    <th>Kode PK</th>
                                                    <th>Keterangan</th>
                                                    <th>Tgl Mulai</th>
                                                    <th>Pemborong</th>
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
show_my_confirm('selesaiPk', 'selesai-pk', 'PK Telah Selesai?', 'Ya, Selesai', 'Batal');
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
        $.get('<?php echo base_url('ProsesPk/showPk'); ?>', function(data) {
            MyTable.fnDestroy();
            $('#data-pk').html(data);
            refresh();
        });
    }

    $(document).on("click", ".cetak-pk", function () {
        var id = $(this).attr("data-id");
           $ .ajax({
                method: "POST",
                url: "<?php echo base_url('ProsesPk/cetakPk'); ?>",
                data: "id="+id
            })
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#cetak-pk').modal('show');
            })
    })
    $(document).on("click", ".cetak-detail", function () {
        var id = $(this).attr("data-id");
           $ .ajax({
                method: "POST",
                url: "<?php echo base_url('ProsesPk/cetakDetail'); ?>",
                data: "id="+id
            })
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#cetak-detail').modal('show');
            })
    })
    $(document).on("click", ".pause-pk", function () {
        var id = $(this).attr("data-id");

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('ProsesPk/Pause'); ?>",
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
								url: '<?php echo base_url('ProsesPk/pausePk'); ?>',
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
                url: "<?php echo base_url('ProsesPk/startPk'); ?>",
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
    })
    $(document).on("click", ".selesai-pk", function () {
        var id = id_pk;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ProsesPk/tutupPk'); ?>",
                data: "id=" + id
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
function refreshDetail() {
        MyDetail = $('#list-detail').dataTable();
    }
    var MyDetail = $('#list-detail').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 5
    });
    
    function showDetail() {
  var id_pk = document.getElementById('id_pk').value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('ProsesPk/showDetail'); ?>?id_pk=' + id_pk,
            data: 'id_pk=' + id_pk,
            success: function (hasil) {
                MyDetail.fnDestroy();
                refreshDetail();
                $('#detail-datapk').html(hasil);
            }
        });

    }

$(document).on("click", ".detail-pk", function () {
        var id = $(this).attr("data-id");

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('ProsesPk/Detail'); ?>",
                data: "id=" + id
            })
            
            .done(function (data) {
                $('#modal-pk').html(data);
                $('#detail-pk').modal('show');
                showDetail();
            })
    })
    $(document).on('submit', '#detailPkaktif', function (e) {
						var data = $(this).serialize();

						$.ajax({
								method: 'POST',
								url: '<?php echo base_url('ProsesPk/detailPk'); ?>',
								data: data
							})
							.done(function(data) {
                                showDetail();
								var out = jQuery.parseJSON(data);
                                if (out.status == 'form') {
                                    $('.form-msg').html(out.msg);
                                    effect_msg_form();
                                } else {
                                    document.getElementById("detailPkaktif").reset();
                                   // $('#detail-pk').modal('hide');
        
                                }
							})

						e.preventDefault();
					});
  $(document).on("click", ".delete-detail", function () {
        id_lapor = $(this).attr("data-id");
    })
    $(document).on("click", ".delete-detail", function () {
        var id = id_lapor;

        $
            .ajax({
                method: "POST",
                url: "<?php echo base_url('ProsesPk/deleteDetail'); ?>",
                data: "id=" + id
            })
            .done(function (data) {
                var out = jQuery.parseJSON(data);
                $('.msg').html(out.msg);
                showDetail();
                if (out.status != 'form') {
                    Swal.fire(
                        {position: 'center', icon: 'error', title: out.msg, showConfirmButton: false, timer: 1000}
                    )
                }
            })
    })
</script>