<button class="btn btn-sm bg-gradient-success" onclick="cetakBuku()"><i class="fa fa-print"></i> Cetak</button>
<style>
.table.dataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 11px;
}

table.dataTable td {
    padding: 3px;
}
</style>
<div class="row">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card ">
                                <div class="modal-body">
                            <div class="table-responsive">
                                <table id="list-saldo" width="100%" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Reference</th>
                                            <th>Tanggal</th>
                                            <th>No Ref</th>
                                            <th>Keterangan</th>
                                            <th>Code</th>
                                            <th>Account</th>
                                            <th>Debet</th>
                                            <th>Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
											<?php 
                                            if(!empty($dataBuku)){
                                                $no=1; 
                                                $debit1=0; 
                                                $kredit1=0; 
                                                $kredit2=0; 
												$total_global=0;
											foreach ($dataBuku as $a){		  
												$debit1 += $a->debit; 
												$kredit2 -= $a->kredit;		
												$kredit1 += $a->kredit;		
																	  
																	  
                                            ?>
                                        <tr>
                                            <td><?php echo $a->no_jurnal ?></td>
                                            <td><?php echo tglIndopendek($a->tgl_jurnal) ?></td>
                                            <td><?php echo $a->no_ref ?></td>
                                            <td><?php echo $a->keterangan ?></td>
                                            <td><?php echo $a->kode_akun ?></td>
                                            <td><?php echo $a->nama_akun ?></td>
                                            <td align="right"><?php echo number_format($a->debit) ?></td>
                                            <td style=color:red; font-weight: bolder; align="Right"><?php echo number_format($a->kredit) ?></td>
                                        </tr><?php }} ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Total</th>
                                            <th style="text-align: right;">Debet</th>
                                            <th style="text-align: right;">Credit</th>
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

<script type="text/javascript">
	
var MyTable = $('#list-saldo').DataTable({
    
    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(6)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        hasil = api
            .column(7)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(6).footer()).html(total);
        $(api.column(7).footer()).html(total);
    },
    order: [[0,5, 'asc']],
        rowGroup: {
            "startRender": function ( rows, group, level ) {
                return '<td style=color:#007bff;font-weight: bolder;>'+group+'</td>';
             },
            endRender: function ( rows, group ) {
                var debit = rows
                    .data()
                    .pluck(6)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    }, 0);
                debit = $.fn.dataTable.render.number(',', '.', 0).display( debit );
                var kredit = rows
                    .data()
                    .pluck(7)
                    .reduce( function (x, y) {
                        return x + y.replace(/[^\d]/g, '')*1;
                    }, 0);
                kredit = $.fn.dataTable.render.number(',', '.', 0).display( kredit );
                return $('<tr/>')
                    .append( '<td colspan="5"></td>' )
                    .append( '<td style=color:#17a2b8;font-weight: bolder;>SALDO '+group+'</td>' )
                    .append( '<td style=color:green; font-weight: bolder; align="Right">'+debit+'</td>' )
                    .append( '<td style=color:red; font-weight: bolder; align="Right">'+kredit+'</td>' );
            },
            dataSrc: 5
        },

    "ordering": true
});
</script>