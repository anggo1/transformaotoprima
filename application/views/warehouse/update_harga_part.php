<style>
    .table.DataTable {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12px;
    }

    table.dataTable td {
        padding-bottom: 5px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> Data Spare Part </h3>
                        <div class="text-right">
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabel-part" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Part</th>
                                    <th>Nama Part</th>
                                    <th>Stok</th>
                                    <th>H Awal</th>
                                    <th>Harga 1</th>
                                    <th>Harga 2</th>
                                    <th>Kategori</th>
                                    <th>Kelompok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                <div id="tempat-modal"></div>
                <div id="modal-label"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
show_my_confirm('hapusPart', 'hapus-part', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>

 


<script type="text/javascript">
    $(document).ready(function() {

        //datatables
        table = $("#tabel-part").DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "sEmptyTable": "Data Sparepart Belum Ada"
            },
            "processing": true, //Feature control the processing indicator.
            "serverSide": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
            },
            "order": [],

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('UpdateHpart/ajax_list') ?>",
                "type": "POST"
            },
            "columnDefs": [
        {
            "targets": [ 0 ],
            "orderable": false,
        },
        ],

    });

});

$(document).on("click", ".update-harga", function() {
		var id = $(this).attr("data-id");
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('UpdateHpart/updateHarga'); ?>",
			data: "id=" +id
		})
		.done(function(data) {
			$('#tempat-modal').html(data);
			$('#update-harga').modal('show');
		})
	})
	$(document).on('submit', '#form-update-harga', function(e){
		var data = $(this).serialize();

		$.ajax({
			method: 'POST',
			url: '<?php echo base_url('UpdateHpart/prosesUharga'); ?>',
			data: data
		})
		.done(function(data) {
			var out = jQuery.parseJSON(data);

			table.ajax.reload();
			if (out.status == 'form') {
				$('.form-msg').html(out.msg);
				effect_msg_form();
			} else {
				document.getElementById("form-update-harga").reset();
				$('#update-harga').modal('hide');
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


	$('#update-harga').on('hidden.bs.modal', function () {
	$('.form-msg').html('');
	})
</script>
