<?php if(empty($dataBuku )){ echo "Belum Ada data pada tanggal yang di pilih";} else { ?>
<button class="btn btn-sm bg-gradient-success" onclick="cetakBukuAkun()"><i class="fa fa-print"></i> Cetak</button>
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
                                            <th>NoAcc</th>
                                            <th>Code</th>
                                            <th>Account</th>
                                            <th>Keterangan</th>
                                            <th>Debet</th>
                                            <th>Credit</th>
                                            <th>Balance</th>
                                        </tr>
										 <?php
                                        if(!empty($dataBuku )){
										foreach ($dataBuku as $s){ $totalSaldo= $s->data_debit-$s->data_kredit;} }?>
                                        <tr>
                                          <th colspan="9">Buku Besar</th>
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
                                            <td><?php echo $a->no_ref ?></td>
                                            <td><?php echo tglIndopendek($a->tgl_jurnal) ?></td>
                                            <td><?php echo $a->no_jurnal ?></td>
                                            <td><?php echo $a->kode_akun ?></td>
                                            <td><?php echo $a->nama_akun ?></td>
                                            <td><?php echo $a->keterangan ?></td>
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
});
</script>
<?php } ?>