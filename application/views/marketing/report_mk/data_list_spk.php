<div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pk">
                                            <thead>
                                                <tr>
                                                    <th width='5%'>No</th>
                                                    <th>No SPK</th>
                                                    <th>Tgl SPK</th>
                                                    <th>Customer</th>
                                                    <th>Type</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
$no = 1;
foreach ($dataPo as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->no_spk; ?></td>
        <td><?php echo tglIndoSedang($s->tgl_spk); ?></td>
        <td><?php echo $s->nama_pemesan; ?></td>
        <td><?php echo $s->type_kendaraan; ?></td>

        <td class="text-center">
            <button class="btn btn-xs btn-outline-primary cetak-spk" data-id="<?php echo $s->no_urut; ?>"><i class="fa fa-print"></i> Print</button>
			
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
    </script>