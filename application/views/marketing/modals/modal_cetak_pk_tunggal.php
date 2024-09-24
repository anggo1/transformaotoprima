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
}
.datatable td {
    padding: 0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.datatable th {
    border: 2px solid #000;
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
<div class="alert bg-white"><?php foreach ($dataPk as $k){}?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <th colspan="4"><div align="left">
                        <table width="100%">
                          <tbody>
                            <tr>
                              <td colspan="6"><font size="+2">PERINTAH KERJA</font> </td>
                            </tr>
                            <tr>
                              <td colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="32">&nbsp;</td>
                              <td>&nbsp;</td>
                              <td width="24%" rowspan="4">
                                <img src="<?= base_url('./assets/img_qr/'.$k->id_pk.'.png') ?>" alt="QRcode-part" width="100" height="100">
                                </td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="32">&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="26">ID Laporan</td>
                              <td>: <?php echo $k->id_lapor ?></td>
                              <td>No Body</td>
                              <td>: <?php echo $k->no_body ?></td>
                            </tr>
                            <tr>
                              <td width="15%" height="24">Tgl Masuk</td>
                              <td width="25%">: <?php echo tglIndoPanjang($k->tgl_masuk) ?></td>
                              <td width="15%">No Pol</td>
                              <td width="21%">: <?php echo $k->no_pol ?></td>
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
<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000" class="datatable"  id="table-1" >
   <thead>
     <tr>
       <th>No</th>
       <th>No PK</th>
       <th>KODE PK</th>
       <th>KETERANGAN PK</th>
       <th>Pemborong</th>
       <th>BIAYA</th>
       </tr>
     <?php
       $no=0;
       foreach ($dataPk as $d){ 
	     $no++;
						?>
      <tr>
          <th><?php echo $no ?></th>
       <th><?php echo $d->id_pk ?></th>
       <th><?php echo $d->jns_pk ?></th>
       <th><?php echo $d->ket_pk ?></th>
       <th><?php echo $d->pt_pemborong ?>,<?php echo $d->pj_borong ?></th>
       <th><?php echo number_format($d->biaya_borong); ?></th>
       </tr>
     <?php  } ?>
   </thead>
   <tbody>
</table></div>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th><?php echo date('d M Y')?></th>
                    </tr>
                    <tr>
                      <th width="25%">Mengetahui</th>
                      <th width="25%">Menyetujui</th>
                      <th width="25%">Keuangan</th>
                      <th width="25%">Dibuat</th>
                    </tr>
                    <tr>
                      <th height="60">
                        <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </p>
                      </th>
                      <th>
                        <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </p>
                      </th>
                      <th>
                        <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </p>
                      </th>
                      <th><p>&nbsp;</p>                        <?php echo $d->user ?></th>
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