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
.datatable2 {
    border-collapse: collapse;
    font: bold;
    padding: 2px;
}
.datatable2 td {
    border: 1px solid #000;
    padding: 2px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.datatable2 th {
    border: 1px solid #000;
    font: bold;
    font-weight: normal;
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
<div class="alert bg-white"><?php foreach ($dataJurnal as $k){}?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <th colspan="4"><div align="left">
                        <table width="100%">
                          <tbody>
                            <tr>
                              <td width="100%"><font size="+2">Transaksi Jurnal <?php echo $k->no_ref; ?></font> </td>
                            </tr>
                            <tr>
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
    <div class="table-responsive">
                    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2"  id="table-1" >
                                            <thead>
                                                <tr>
                                                <th width='5%'>No</th>
                                                <th>No Ref</th>
                                                <th>No ACC</th>
                                                <th>Nama ACC</th>
                                                    <th>Tgl</th>
                                                    <th>Keterangan</th>
                                                    <th>Debit</th>
                                                    <th>Kredit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $no = 1;
                                            $tdebit=0;
                                            $tkredit=0;
											if(!empty($dataJurnal)){										
											foreach ($dataJurnal as $s) {
												$tdebit += $s->debit;	
												$tkredit += $s->kredit;	
                                            ?> <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $s->no_ref; ?></td>
                                            <td><?php echo $s->kode_akun; ?></td>
                                            <td><?php echo $s->nama_akun; ?></td>
                                            <td><?php echo tglIndoPendek($s->tgl_jurnal); ?></td>
                                            <td><?php echo $s->keterangan; ?></td>
                                            <td align="right" style="color: green;"><?php echo number_format($s->debit); ?></td>
                                            <td align="right" style="color: red;"><?php echo number_format($s->kredit); ?></td>
                                                </tr>
                                            <?php
                                                $no++;
                                            }}
                                            ?>
                                            </tbody>
                                            <tfoot>
						 <tr>
						   <td colspan="6" align="right">Total </td>
						   <td align="right" style="color: green;"><?php echo number_format($tdebit); ?></td>
						   <td align="right" style="color: red;"><?php echo number_format($tkredit); ?></td>
                    </tfoot>
      </table>
                                      </div>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th><?php echo date('d M Y')?></th>
                    </tr>
                    <tr>
                      <th width="25%">&nbsp;</th>
                      <th width="25%">&nbsp;</th>
                      <th width="25%">&nbsp;</th>
                      <th width="25%">Dibuat Oleh</th>
                    </tr>
                    <tr>
                      <th height="60">
                        <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under">&nbsp;</p>
                      </th>
                      <th>
                        <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under">&nbsp;</p>
                      </th>
                      <th>
                        <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under">&nbsp;</p>
                      </th>
                      <th><p>&nbsp;</p><?php echo $this->session->userdata['full_name']; ?></th>
                    </tr>
      </thead>
                <tbody>
</table>
             
</div>
</div>
        <div class="card-footer">
        <button type="button" id="btnPrint" class="btn btn-success" ><span class="fa fa-print"></span>&nbsp;&nbsp;  C E T A K </button>
      <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>