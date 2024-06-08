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
                                            <th>No</th>
                                            <th>Reference</th>
                                            <th>Tanggal</th>
                                            <th>No Bukti</th>
                                            <th>Keterangan</th>
                                            <th>Code</th>
                                            <th>Account</th>
                                            <th>Debet</th>
                                            <th>Credit</th>
                                            <th>Balance</th>
                                        </tr>
										 <?php
                                        if(!empty($dataBuku )){
										foreach ($dataBuku as $s){ $totalSaldo= $s->data_debit-$s->data_kredit;} }?>
                                        <tr>
                                          <th colspan="9">Saldo <?php //echo $s->periode ?></th>
                                          <th style="text-align: right;"><?php echo number_format($s->data_debit-$s->data_kredit) ?></th>
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
												$totalnye = $totalSaldo+$debit1+$kredit2;
												$total_global += $totalSaldo+$debit1+$kredit2;
																	  
																	  
                                            ?>
                                        <tr>
                                            <td align="center"><?php echo $no++ ?></td>
                                            <td><?php echo $a->no_jurnal ?></td>
                                            <td><?php echo tglIndopendek($a->tgl_jurnal) ?></td>
                                            <td><?php echo $a->no_bukti ?></td>
                                            <td><?php echo $a->keterangan ?></td>
                                            <td><?php echo $a->kode_akun ?></td>
                                            <td><?php echo $a->nama_akun ?></td>
                                            <td align="right"><?php echo number_format($a->debit) ?></td>
                                            <td align="right"><?php echo number_format($a->kredit) ?></td>
                                            <td align="right"><?php echo number_format($totalnye)?></td>
                                        </tr><?php }} ?>
                                    </tbody>
                                    <tfoot>
									 <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th style="text-align: right;"><?php echo number_format($debit1) ?></th>
                                            <th style="text-align: right;"><?php echo number_format($kredit1) ?></th>
                                            <th style="text-align: right;"><?php echo number_format($debit1-$kredit1+$totalSaldo)?></th>
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
	
var MyTable = $('#list-saldo').dataTable({
    "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [{
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
            title: 'Report Saldo Awal',
            className: 'btn btn-sm btn-outline-primary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
            }
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Cetak',
            titleAttr: 'Print',
            title: 'Report Saldo Awal',
            className: 'btn btn-sm btn-outline-primary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
            }
        }
    ],
});
</script>