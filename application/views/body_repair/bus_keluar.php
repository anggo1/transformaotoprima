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
                        <div class="card-header bg-light">
						<h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Daftar Kendaraan Yang Siap Keluar</h3>
						<div class="text-right">
							<button class="btn bg-gradient-success" onclick="cetakBus()" type="submit"><span class="fa fa-print"></span> Cetak</button>
						</div>
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
                                                    <th>No SPK</th>
                                                    <th>No Body</th>
                                                    <th>JML PK</th>
                                                    <th>Tgl Masuk</th>
                                                    <th>Tgl Selesai</th>
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
show_my_confirm('selesaiPk', 'selesai-pk', 'Bus Keluar Pool?', 'Ya, Selesai', 'Batal');
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
        $.get('<?php echo base_url('BusKeluar/showPk'); ?>', function(data) {
            MyTable.fnDestroy();
            $('#data-pk').html(data);
            refresh();
        });
    }
    function cetakBus() {
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('BusKeluar/cetakBus'); ?>',
			success:
            function(hasil) {
				$('#modal-pk').html(hasil);
				$('#cetak-pk').modal('show');
			}
		});
	}

$(document).on("click", ".selesai-pk-aktif", function () {
        id_pk = $(this).attr("data-pk");
        body_pk = $(this).attr("body-pk");
    })
    $(document).on("click", ".selesai-pk", function () {
        var id = id_pk;
        var body = body_pk;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('BusKeluar/tutupPk'); ?>",
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