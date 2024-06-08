<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>No Body</th>
                    <th>Tgl</th>
                    <th>No PK</th>
                    <th>No Part</th>
                    <th>Nama Part</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$total=0;
$akumulasi=0;
foreach ($dataBody as $s) {
    $akumulasi += $s->jumlah * $s->hrg_part;
    $total += $akumulasi;
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->no_body; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_keluar); ?></td>
                    <td><?php echo $s->no_pk; ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td style="text-align: center;"><?php echo $s->jumlah; ?></td>
                    <td style="text-align: center;"><?php echo $s->kode_satuan; ?></td>
                    <td style="text-align: right;"><?php echo number_format($s->hrg_part); ?></td>
                    <td style="text-align: right;"><?php echo number_format($s->jumlah * $s->hrg_part); ?></td>
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
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">Grand Total</td>
                  <td align="right"><?php echo number_format($akumulasi); ?></td>
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
            title: function() {
                return "<div style='font-size: 20px;'>Data Penggunaan Barang Per Divisi</div>";
                },
                className: 'btn btn-sm btn-outline-primary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Cetak',
                titleAttr: 'Print',
			footer: true,
            title: function() {
                return "<div style='font-size: 20px;'>Data Penggunaan Barang Per Divisi</div>";
                },
                className: 'btn btn-sm btn-outline-primary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
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