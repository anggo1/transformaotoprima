<div class="col-12 ">
    <div class="table-responsive">
        <table width="100%" class="table table-bordered table-hover nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Tgl</th>
                    <th>No Part</th>
                    <th>Nama Part</th>
                    <th>No Body</th>
                    <th>No PK</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
foreach ($dataPart as $s) {
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_keluar); ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td><?php echo $s->no_body; ?></td>
                    <td><?php echo $s->no_pk; ?></td>
                    <td><?php echo $s->jumlah; ?></td>
                    <td><?php echo $s->nama_satuan; ?></td>
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
<script type="text/javascript">
	
var MyTable = $('#list-data').DataTable({
    "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [{
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
			footer: true,
            title: function() {
                return "<div style='font-size: 20px;'>Report Barang Keluar</div>";
                },
            className: 'btn btn-sm btn-outline-primary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            }
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Cetak',
            titleAttr: 'Print',
			footer: true,
            title: function() {
                return "<div style='font-size: 20px;'>Report Barang Keluar</div>";
                },
            className: 'btn btn-sm btn-outline-primary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            }
        }
    ],
});
</script>