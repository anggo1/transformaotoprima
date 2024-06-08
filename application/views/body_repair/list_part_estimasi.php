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
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Data Part Untuk Estimator</h3>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-list" title="Add Data"><i class="fas fa-plus"></i> Tambah Data</button>
                                </div>
                                <div class="modal-body form">
    <div class="card card-first card-outline">
        <div class="card-body">
                                <div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-part">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Pekerjaan</th>
                                                    <th>No Part</th>
                                                    <th>Nama Part</th>
                                                    <th>Jumlah</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="modal-list"></div>
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
            </div>

<?php
show_my_confirm('hapusPart', 'hapus-part', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>
<script type="text/javascript">
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
    $(document).ready(function() {
		tablelist = $('#list-part').DataTable({
			"responsive": false,
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": true,
			"info": true,
			"processing": true,
			"serverSide": true,
			"pageLength": 10, // Defaults number of rows to display in table
			"order": [],
			"ajax": {
				"url": "<?php echo site_url('ListPartEstimasi/ajax_estimasi') ?>",
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ]
		});
	});
    $(document).ready(function() {
		table = $('#table-part').dataTable({
			"responsive": false,
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"processing": true,
			"serverSide": true,
			"pageLength": 10, // Defaults number of rows to display in table
			"order": [],
			"ajax": {
				"url": "<?php echo site_url('PartPk/ajax_list') ?>",
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ]
		});
	});
    $(document).ready(function() {
    var table = $('#table-part').DataTable();
    $('#table-part tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
		var no_part		= data[1];
		var nama_part	= data[2];
		
        $('[name = "no_part"]').val(no_part);
				$('[name = "nama_part"]').val(nama_part);
		$('#modal_form').modal('hide');
    } );
	} );
    $(document).on('submit', '#formList', function (e) {
						var data = $(this).serialize();

						$.ajax({
								method: 'POST',
								url: '<?php echo base_url('ListPartEstimasi/proseslist'); ?>',
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
					$('#tambah-list').modal('hide');
                    Swal.fire(
                        {position: 'center', icon: 'success', title: out.msg, showConfirmButton: false, timer: 1200}
                    )
                    document.getElementById("formList").reset();
                    tablelist.ajax.reload();
				}
			})

		e.preventDefault();
	});

    function selectPart(id_barang) {
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
    $(document).on("click", ".delete-part", function() {
        id_part = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-part", function() {
        var id = id_part;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ListPartEstimasi/deleteList'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                tablelist.ajax.reload();
                $('.msg').html(out.msg);
                $('#hapusPart').modal('hide');
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
</script>