<style>

#modal-kotak{
	margin:5% 30% 30% 30%;
	width: 500px;	
	height: 200px;
	position: absolute;
	position:fixed;
	z-index:1002;
	display: none;
	background: white;	
}

.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 10px;
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
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> &nbsp; List Data </h3>
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-operation-time" title="Add Data"><i class="fas fa-plus"></i> Tambah Data</button>
						</div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabel-operation-time" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Duration/H</th>
                                    <th>IM</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="tempat-modal"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
show_my_confirm('hapusOperationTime', 'hapus-operation-time', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>

<script type="text/javascript">
    function fn(o) {
    o.value = o.value.toUpperCase().replace(/([^0-9(),-/])/g, '');
}
$(document).ready(function() {

    //datatables
    table = $("#tabel-operation-time").DataTable({
    
			"responsive": true,
			"paging": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"searching": true,
			"ordering": true,
			"info": true,
        "autoWidth": false,
        
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
            "url": "<?php echo site_url('OperationTime/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0,4], //first column / numbering column
            "orderable": false,
        }, ],

    })

});
$('#form-tambah-operation-time').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('OperationTime/prosesToperation_time'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                document.getElementById("form-tambah-operation-time").reset();
                $('#tambah-operation-time').modal('hide');
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

$(document).on("click", ".update-operation-time", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('OperationTime/updateOperationTime'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#tempat-modal').html(data);
            $('#update-operation-time').modal('show');
        })
})
$(document).on('submit', '#form-update-operation-time', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('OperationTime/prosesUoperation_time'); ?>',
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
                document.getElementById("form-update-operation-time").reset();
                $('#update-operation-time').modal('hide');
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

$('#tambah-operation-time').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-operation-time').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})
$(document).on("click", ".delete-operation-time", function() {
    id_operation_time = $(this).attr("data-id");
})
$(document).on("click", ".hapus-operation-time", function() {
    var id = id_operation_time;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('OperationTime/deleteOpTime'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            table.ajax.reload();
            $('.msg').html(out.msg);
            $('#hapusOperationTime').modal('hide');
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