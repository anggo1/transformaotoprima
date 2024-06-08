<div class="col-12 ">
    <div class="table-responsive">
        <table width="100%" class="table table-bordered table-hover table-striped nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Tgl</th>
                    <th>ID Pekerjaan</th>
                    <th>Pekerjaan</th>
                    <th>No Body</th>
                    <th>Biaya</th>
                    <th>Pembayaran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$total=0;
foreach ($dataPk as $s) {
    $total += $s->jumlah;
?> 
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo tglIndoPendek($s->tgl_bayar); ?></td>
                  <td><?php echo $s->id_pk; ?></td>
                  <td><?php echo $s->jns_pk; ?></td>
                  <td><?php echo $s->no_body; ?></td>
                  <td><?php echo number_format($s->biaya_awal); ?></td>
                  <td><?php echo number_format($s->jumlah); ?></td>
                  <td><?php echo $s->keterangan; ?></td>
                </tr>
                <?php
    $no++;
}
?>
            </tbody>
    <tfoot>
			<tr>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">Total </td>
                    <td align="right"><?php echo number_format($total); ?></td>
                    <td align="right">&nbsp;</td>
        </tr>
			</tfoot>
    </table>
</div>
</div>

<script>
var MyTable = $('#list-data').DataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [
        {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
			footer: true,
            title: 'Report Pembayaran Borongan',
            className: 'btn btn-outline-secondary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
            }
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Cetak',
            titleAttr: 'Print',
			footer: true,
            title: 'Report Barang Masuk',
            className: 'btn btn-sm btn-outline-secondary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
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