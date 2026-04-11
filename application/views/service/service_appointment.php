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
#atas{
	font-size: 15pt;
	padding: 20px;	
	height: 80%;
}
#bawah{
	background: #fff;
}
 
#tombol-tutup{	
	background: #e74c3c;
}
#tombol-tutup,#tombol{
	height: 30px;
	width: 100px;
	color: #fff;
	border: 0px;
}
#bg{
	opacity:.80;
	position: absolute;
	display: none;
	position: fixed;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color: #000;
	z-index:1001;
	opacity: 0.8;
}
#tombol{
	background: #e74c3c;        
}
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
                        <h3 class="card-title"><i class="fa fa-list text-blue"></i> &nbsp; List Data </h3>
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#tambah-appointment" title="Add Data"><i class="fas fa-plus"></i> Tambah Data</button>
						</div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabel-appointment" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No WO</th>
                                    <th>SA</th>
                                    <th>Customer</th>
                                    <th>Kasus</th>
                                    <th>Vin</th>
                                    <th>Licence Plate</th>
                                    <th>Vehicle Type</th>
                                    <th>Storing</th>
                                    <th>Date Start</th>
                                    <th>Clock In</th>
                                    <th>Date End</th>
                                    <th>Clock Out</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="tempat-modal"></div>
                    <div id="modal-label"></div>
                                <div id="modal-part"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
show_my_confirm('hapusAppointment', 'hapus-appointment', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>

<script type="text/javascript">
    function fn(o) {
    o.value = o.value.toUpperCase().replace(/([^0-9(),-/])/g, '');
}
$('#tgl_part_order,#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
$(document).ready(function() {

    //datatables
    table = $("#tabel-appointment").DataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [
            {
                extend: 'copyHtml5',
                text: '<i class="fas fa-copy"></i> Copy',
                titleAttr: 'Copy',
                title: 'Data Barang',
                className: 'btn btn-sm  btn-outline-secondary',init: function (api, node, config) {
                $(node).removeClass('btn-secondary') }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel',
                title: 'Data Barang',
                className: 'btn btn-outline-secondary',init: function (api, node, config) {
                $(node).removeClass('btn-secondary') }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'PDF',
                title: 'Data Barang',
                className: 'btn btn-outline-secondary',init: function (api, node, config) {
                $(node).removeClass('btn-secondary') }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Cetak',
                titleAttr: 'Print',
                title: 'Data Barang',
                className: 'btn btn-outline-secondary',init: function (api, node, config) {
                $(node).removeClass('btn-secondary') },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-eye"></i> Tampilan',
                titleAttr: 'Costum Tampilan',
                className: 'btn btn-outline-secondary',init: function (api, node, config) {
                $(node).removeClass('btn-secondary') }
            }
 
        ],
    
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
            "url": "<?php echo site_url('ServiceAppointment/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0,10],
            "orderable": false,
        }, ],

    })

});
$('#form-tambah-appointment').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('ServiceAppointment/prosesTappointment'); ?>',
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
                document.getElementById("form-tambah-appointment").reset();
                $('#tambah-appointment').modal('hide');
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

$(document).on("click", ".update-appointment", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('ServiceAppointment/updateAppointment'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#tempat-modal').html(data);
            $('#update-appointment').modal('show');
        })
})
$(document).on('submit', '#form-update-appointment', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('ServiceAppointment/prosesUappointment'); ?>',
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
                document.getElementById("form-update-appointment").reset();
                $('#update-appointment').modal('hide');
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

$('#tambah-appointment').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-appointment').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})
$(document).on("click", ".delete-appointment", function() {
    id_appointment = $(this).attr("data-id");
})
$(document).on("click", ".hapus-appointment", function() {
    var id = id_appointment;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('ServiceAppointment/deleteAppointment'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            table.ajax.reload();
            $('.msg').html(out.msg);
            $('#hapusAppointment').modal('hide');
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