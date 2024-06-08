<script>
        document.getElementById("btnPrint").onclick = function () {
    printElement(document.getElementById("printThis"));
}

function printElement(elem) {
    var domClone = elem.cloneNode(true);
    
    var $printSection = document.getElementById("printSection");
    
    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }
    
    $printSection.innerHTML = "";
    $printSection.appendChild(domClone);
    window.print();
}
    </script>
     <style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible;
  }
  #printSection {
    position: absolute;
    left:0;
    top:0;
    width: 100%;
  }
}


p, td, th {
    font:2 Verdana, Arial, Helvetica, sans-serif;
	
}
.datatable {
    border-collapse: collapse;
    font: bold;
    padding: 2px;
}
.datatable td {
    border: 1px solid #000;
    padding: 2px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.datatable th {
    border: 1px solid #000;
    font: bold;
    font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
}
.under { text-decoration: underline; }
#A4 {background-color:#FFFFFF;
left:1px;
right:1px;
height:5.51in ; /*Ukuran Panjang Kertas */
width: 8.50in; /*Ukuran Lebar Kertas */
margin:1px solid #FFFFFF;
 
font-family:Georgia, "Times New Roman", Times, serif;
}
    </style>
<div id="printThis">
<div class="alert bg-white">
<div class="alert bg-white"><?php foreach ($dataBuku as $k){}?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <th colspan="4"><div align="left">
                        <table width="100%">
                          <tbody>
                            <tr>
                              <td colspan="5"><h4>Buku Besar Berdasarkan Akun</h4></td>
                            </tr>
                            <tr>
                              <td>No Akun</td>
                              <td>: <?php echo $k->kode_akun ?></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td width="15%">Nama Akun</td>
                              <td width="49%">: <?php echo $k->nama_akun ?></td>
                              <td width="15%">&nbsp;</td>
                              <td width="21%">&nbsp;</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </tr>
   </thead>
                <tbody>
               
			
                </tbody>
</table>
                                <table id="list-saldo2" width="100%" class="table datatable">
                                    <thead> <tr>
                                            <th>No</th>
                                            <th>Reference</th>
                                            <th>Tanggal</th>
                                            <th>Account</th>
                                            <th>Keterangan</th>
                                            <th>Debet</th>
                                            <th>Credit</th>
                                            <th>Saldo</th>
                                        </tr>
										 <?php
                                        if(!empty($dataBuku )){
										foreach ($dataBuku as $s){ $totalSaldo= $s->data_debit-$s->data_kredit;} }?>
                                        <tr>
                                          <th colspan="7">Saldo</th>
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
                                            <td><?php echo $a->keterangan ?></td>
                                            <td align="right"><?php echo number_format($a->debit) ?></td>
                                            <td align="right"><?php echo number_format($a->kredit) ?></td>
                                            <td align="right"><?php echo number_format($totalnye)?></td>
                                        </tr><?php }} ?>
                                    </tbody>
                                    <tfoot>
									 <tr>
                                            
						                    <th style="text-align: right;" colspan="5" align="right">Total </th>
                                            <th style="text-align: right;"><?php echo number_format($debit1) ?></th>
                                            <th style="text-align: right;"><?php echo number_format($kredit1) ?></th>
                                            <th style="text-align: right;"><?php echo number_format($debit1-$kredit1+$totalSaldo)?></th>
                                      </tr>
                                    </tbody>
                                    <tfoot>
									</tfoot>
                                </table>
                            </div>
</div>
</div>
        <div class="card-footer">
        <button type="button" id="btnPrint" class="btn btn-success" ><span class="fa fa-print"></span>&nbsp;&nbsp;  C E T A K </button>
      <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>
<script type="text/javascript">
var MyTable = $('#list-saldo2').dataTable({
        "responsive": false,
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false
});
</script>