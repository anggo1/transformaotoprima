<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Kode Masuk</th>
                    <th>Tgl</th>
                    <th>Status</th>
                    <th>No Part</th>
                    <th>Nama Part</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
foreach ($dataBody as $s) {
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->id_masuknye; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_masuk); ?></td>
                    <td><?php echo $s->status_part; ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td><?php echo $s->jumlah; ?></td>
                    <td><?php echo number_format($s->hrg_part); ?></td>
                    <td><?php echo number_format($s->hrg_part * $s->jumlah); ?></td>
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
    var MyTable = $('#list-data').DataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [{
                extend: 'copyHtml5',
                text: '<i class="fas fa-copy"></i> Copy',
                titleAttr: 'Copy',
                title: 'Report Barang Return',
                className: 'btn btn-sm  btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel',
                title: 'Report Barang Return',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'PDF',
                title: 'Report Barang Return',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Cetak',
                titleAttr: 'Print',
                title: 'Report Barang Return',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
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