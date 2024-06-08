
<style>
.table.dataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 11px;
}

table.dataTable td {
    padding: 2px;
}
</style>
<div class="row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card ">
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="table-jurnal2" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Ref</th>
                                        <th>No Acc</th>
                                        <th>No Akun</th>
                                        <th>Account</th>
                                        <th>Date</th>
                                        <th>Keterangan</th>
                                        <th>Debet</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
    foreach ($dataJurnal as $s) {
        ?>
                                    <tr>
                                        <td><?php echo $s->row_no; ?></td>
                                        <td><?php echo $s->no_ref; ?></td>
                                        <td><?php echo $s->no_jurnal; ?></td>
                                        <td><?php echo $s->kode_akun; ?></td>
                                        <td><?php echo $s->nama_akun; ?></td>
                                        <td><?php echo tglIndoPendek($s->tgl_jurnal); ?></td>
                                        <td><?php echo $s->keterangan; ?></td>
                                        <td align="right" style="color: green;"><?php echo number_format($s->debit); ?></td>
                                        <td align="right" style="color: red;;"><?php echo number_format($s->kredit); ?></td>
                                    </tr>
                                    <?php
    }
    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
						                <th style="text-align: right;" colspan="7" align="right">Total </th>
                                        <th style="text-align: right;"></th>
                                        <th style="text-align: right;"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="javascript">
    

$(document).ready( function () {
  var row_group_index = 0;
  var row_group_td = 0;
  var table = $('#table-jurnal2').DataTable({
    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(7)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        hasil = api
            .column(8)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(7).footer()).html(total);
        $(api.column(8).footer()).html(total);
    },
        order: [[1, 'desc']],
        rowGroup: {
            "startRender": function(rows, group, level) {
              row_group_index++;
            return row_group_index + '. ' + group + ' (' + rows.count() + ' rows)' +
                '&nbsp;&nbsp;&nbsp;&nbsp; <button type="submit" class="btn btn-xs btn-success cetak-jurnal" data-id="' +
                group +
                '"><i class="fa fa-print"></i> Cetak</button>&nbsp;&nbsp;&nbsp; <button class="btn btn-xs btn-danger delete-jurnal" data-toggle="modal" data-target="#hapusJurnal" data-id="' +
                group + '"><i class="fa fa-times"></i> Hapus</button>';
        },

        
            endRender: function ( rows, group ) {
              row_group_td++;
                var debit = rows
                    .data()
                    .pluck(7)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    }, 0);
                debit = $.fn.dataTable.render.number(',', '.', 0).display( debit );
                var kredit = rows
                    .data()
                    .pluck(8)
                    .reduce( function (x, y) {
                        return x + y.replace(/[^\d]/g, '')*1;
                    }, 0);
                kredit = $.fn.dataTable.render.number(',', '.', 0).display( kredit );
                return $('<tr/>')
                    .append( '<td colspan="7" style=color:#17a2b8;font-weight: bolder; align="right">BALANCE '+group+'</td>' )
                    .append( '<td style=color:green; font-weight: bolder; align="Right">'+debit+'</td>' )
                    .append( '<td style=color:red; font-weight: bolder; align="Right">'+kredit+'</td>' );
            },
            dataSrc: 1
        }
  });
  
table.on( 'draw', function () {
    row_group_index = 0;
} );
  
  
} );
</script>