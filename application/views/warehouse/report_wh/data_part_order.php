<div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pk">
                                            <thead>
                                                <tr>
                                                    <th width='5%'>No</th>
                                                    <th>Reference</th>
                                                    <th>Date</th>
                                                    <th>Registration</th>
                                                    <th>Customer</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
$no = 1;
foreach ($dataPo as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->kode_part_order; ?></td>
        <td><?php echo tglIndoSedang($s->tgl_part_order); ?></td>
        <td><?php echo $s->kode_pesan; ?></td>
        <td><?php echo $s->nama_sup; ?></td>

        <td class="text-center">
            <button class="btn btn-sm btn-outline-primary cetak-po" data-id="<?php echo $s->id_part_order; ?>"><i class="fa fa-print"></i> Print</button>
            <a href="<?php echo base_url('PartOrder/export'); ?>?id=<?php echo $s->id_part_order; ?>"
                                class="btn btn-sm btn-outline-info" title="Download" target="_blank"><i
                                    class="fas fa-file-excel"></i> Excel</a>
			
    </td>
    </tr>
<?php
    $no++;
}
?>

</tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
<script>
var MyTable = $('#list-pk').DataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [{
                extend: 'copyHtml5',
                text: '<i class="fas fa-copy"></i> Copy',
                titleAttr: 'Copy',
                title: 'Report Purchase Order',
                className: 'btn btn-sm  btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
            }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel',
                title: 'Report Purchase Order',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
            }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'PDF',
                title: 'Report Purchase Order',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
            }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Cetak',
                titleAttr: 'Print',
                title: 'Report Purchase Order',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
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
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 10
    });
    $(document).on("click", ".cetak-keluar", function() {
		var id = $(this).attr("data-id");
		//var id = document.getElementById('next_proses').value=datakode;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('Part_keluar/cetak'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				$('#modal-keluar').html(data);
				$('#cetak-keluar').modal('show');
			})
	})
    function cetakBon(datakode) {}


$(document).on("click", ".cetak-bon", function() {
		var id = $(this).attr("data-id");
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('PartPk/cetakBon'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
           // $('#part-pk').modal('hide');
            $('#modal-pk').html(data);
            $('#cetak-bon').modal('show');
        })
})
    </script>