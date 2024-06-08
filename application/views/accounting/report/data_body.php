<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>No Body</th>
                    <th>No SPK</th>
                    <th>Tgl</th>
                    <th>No PK</th>
                    <th>Ket PK</th>
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
                    <td><?php echo $s->no_spk; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_keluar); ?></td>
                    <td><?php echo $s->no_pk; ?></td>
                    <td><?php echo $s->ket_pk; ?></td>
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
                <td align="right">&nbsp;</td>
                <td align="right">Grand Total</td>
                <td align="right" colspan="2"><?php echo number_format($akumulasi); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script language="javascript">
    

$(document).ready( function () {

  var row_group_index = 0;
  var row_group_td = 0;
  var table = $('#list-data').DataTable({
    
    "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "lengthMenu": [
        [-1,10, 25, 50],
        [ 'Seluruhnya',10, 25, 50],
        
    ],
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
                return "Report Barang Keluar Per Body";
                },
                className: 'btn btn-sm btn-outline-primary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }

        ], 

    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(11)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(11).footer()).html(total);
    },
        order: [[5, 'desc']],
        rowGroup: {
            "startRender": function(rows, group, level) {
              row_group_index++;
            return row_group_index + '. ' + group + ' (' + rows.count() + ' rows)';
        },

        
            endRender: function ( rows, group ) {
              row_group_td++;
                var debit = rows
                    .data()
                    .pluck(11)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    }, 0);
                debit = $.fn.dataTable.render.number(',', '.', 0).display( debit );
                return $('<tr/>')
                    .append( '<td colspan="11" style=color:#17a2b8;font-weight: bolder; align="right">Total  '+group+'</td>' )
                    .append( '<td style=color:green; font-weight: bolder; align="Right">'+debit+'</td>' )
            },
            dataSrc: 5
        }
  });
  
table.on( 'draw', function () {
    row_group_index = 0;
} );
  
        
} );


</script>