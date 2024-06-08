
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
						<h3 class="card-title"><i class="fa fa-list text-blue"></i>  Data Bus</h3>
						<div class="text-right">
						<?php foreach ($viewLevel as $l) {
                            if ($l->add_level=='Y'){
                            echo '<button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-body" title="Add Data"><i class="fas fa-plus"></i> Tambah Data</button>';
                            }}
                            ?>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<table id="tabel-body" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>No Body</th>
									<th>Type</th>
									<th>Thn Rangka</th>
									<th>Thn Buat</th>
									<th>Karoseri</th>
									<th>Warna</th>
									<th>Kelas</th>
									<th>Strip</th>
									<th>Keterangan</th>
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
		</div>
	</div>
</section>

<?php
show_my_confirm('hapusBody', 'hapus-body', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>

 


<script type="text/javascript">
    $(document).ready(function() {

        //datatables
        table = $("#tabel-body").DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "sEmptyTable": "Data Body Belum Ada"
            },
            "processing": true, //Feature control the processing indicator.
            "serverSide": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x"></i>'
            },
            "order": [],

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('ListBus/ajax_list') ?>",
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
$('#form-tambah-body').submit(function(e) {
		var data = $(this).serialize();

		$.ajax({
			method: 'POST',
			url: '<?php echo base_url('ListBus/prosesTbody'); ?>',
			data: data
		})
		.done(function(data) {
			var out = jQuery.parseJSON(data);

			if (out.status == 'form') {
				$('.form-msg').html(out.msg);
				effect_msg_form();
			} else {
				document.getElementById("form-tambah-body").reset();
				$('#tambah-body').modal('hide');
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

$(document).on("click", ".update-body", function() {
		var id = $(this).attr("data-id");
		
		$.ajax({
			method: "POST",
			url: "<?php echo base_url('ListBus/updateBody'); ?>",
			data: "id=" +id
		})
		.done(function(data) {
			$('#tempat-modal').html(data);
			$('#update-body').modal('show');
		})
	})
	$(document).on('submit', '#form-update-body', function(e){
		var data = $(this).serialize();

		$.ajax({
			method: 'POST',
			url: '<?php echo base_url('ListBus/prosesUbody'); ?>',
			data: data
		})
		.done(function(data) {
			var out = jQuery.parseJSON(data);

			table.ajax.reload();
			if (out.status == 'form') {
				$('.form-msg').html(out.msg);
				effect_msg_form();
			} else {
				document.getElementById("form-update-body").reset();
				$('#update-body').modal('hide');
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

	$('#tambah-body').on('hidden.bs.modal', function () {
	$('.form-msg').html('');
	})

	$('#update-body').on('hidden.bs.modal', function () {
	$('.form-msg').html('');
	})
    $(document).on("click", ".delete-body", function() {
        id_body = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-body", function() {
        var id = id_body;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('ListBus/deleteBody'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
			table.ajax.reload();
                $('.msg').html(out.msg);
                $('#hapusBody').modal('hide');
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
	function cariBodyl(){
		var a =document.getElementById('no_body').value;
        $.ajax({
			url:"<?php echo base_url();?>ListBus/cariKodeBody",
				data:'&a='+a,
				success:function(data){
				var hasil = JSON.parse(data);  
					if ( hasil.length == 1 ) {
						document.getElementById("form-tambah-body");
                		$('#no_body').val('');
					   Swal.fire({
								position: 'center',
								icon: 'error',
								title: 'No Body Sudah Ada',
								showConfirmButton: false,
								timer: 1000
				})
			} 
					
            }
         });
                   
    }


</script>