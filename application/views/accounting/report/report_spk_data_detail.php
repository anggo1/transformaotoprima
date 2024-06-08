<div class="form-group row">
<button class="btn btn-sm bg-gradient-primary" onclick="listPart()" type="submit"><i class="fa fa-eye"></i> Global</button>&nbsp;&nbsp;
<button class="btn btn-sm btn-outline-info" onclick="detailPart()" type="submit"><i class="fa fa-eye"></i> Detail Material</button>&nbsp;&nbsp;
<button class="btn btn-sm bg-gradient-navy" onclick="detailUpah()" type="submit"><i class="fa fa-eye"></i> Detail Upah</button>&nbsp;&nbsp;
<button class="btn btn-sm bg-gradient-success" onclick="cetak_dataSpk_detail()" type="submit"><i class="fa fa-print"></i> Cetak</button>
</div>
<div class="col-12 ">
    <div class="table-responsive">
        <table width="100%" class="table table-bordered table-hover table-striped nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>ID PK</th>
                    <th>Kode</th>
                    <th>PK</th>
                    <th>No Body</th>
                    <th>No Part</th>
                    <th>Nama Part</th>
                    <th>QTY</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$total_part=0;
foreach ($dataPart as $s) {
    $total_part += $s->total_hrg_part;
?> 
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $s->id_pk; ?></td>
                  <td><?php echo $s->jns_pk; ?></td>
                  <td><?php echo $s->ket_pk; ?></td>
                  <td><?php echo $s->no_body; ?></td>
                  <td><?php echo $s->no_part; ?></td>
                  <td><?php echo $s->nama_part; ?></td>
                  <td><?php echo $s->jumlah; ?></td>
                  <td><?php echo $s->satuan; ?></td>
                  <td align="right"><?php echo number_format($s->hrg_part); ?></td>
                  <td align="right"><?php echo number_format($s->total_hrg_part); ?></td>
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
                    <td align="right"><?php echo number_format($total_part); ?></td>
        </tr>
			</tfoot>
    </table>
</div>
</div>

<script>
    

$(document).ready( function () {

  var row_group_index = 0;
  var row_group_td = 0;
  var table = $('#list-data').DataTable({
    
    "responsive": false,
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "lengthMenu": [
        [-1,10, 25, 50],
        [ 'Seluruhnya',10, 25, 50],
        
    ],

    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(10)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(10).footer()).html(total);
    },
        order: [[3, 'desc']],
        rowGroup: {
            "startRender": function(rows, group, level) {
              row_group_index++;
            return row_group_index + '. ' + group + ' (' + rows.count() + ' rows)';
        },

        
            endRender: function ( rows, group ) {
              row_group_td++;
                var debit = rows
                    .data()
                    .pluck(10)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    }, 0);
                debit = $.fn.dataTable.render.number(',', '.', 0).display( debit );
                return $('<tr/>')
                    .append( '<td colspan="10" style=color:#17a2b8;font-weight: bolder; align="right">Total  '+group+'</td>' )
                    .append( '<td style=color:green; font-weight: bolder; align="Right">'+debit+'</td>' )
            },
            dataSrc: 3
        }
  });
  
table.on( 'draw', function () {
    row_group_index = 0;
} );
  
        
} );

</script>