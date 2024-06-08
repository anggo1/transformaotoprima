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
    padding: 2px 2px 2px 2px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
    font: bold;
    border: 1px solid #000000;
}
.datatable th {
    border: 1px solid #000000;
    font: bold;
    font-weight: normal;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
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

<div class="card-body">
<div id="printThis">
<?php foreach ($dataPk as $k){}?>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                          <tbody>
                            <tr>
                              <td colspan="6"><font size="+2">DETAIL PERINTAH KERJA</font> </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td width="24%" rowspan="7"><img src="<?= base_url('./assets/img_qr/'.$k->id_pk.'.png') ?>" alt="QRcode-part" width="100" height="100"></td>
                              <td>Pool</td>
                              <td>: <?php echo $k->pool ?></td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>ID Laporan</td>
                              <td>: <?php echo $k->id_lapor ?></td>
                              <td>Rute</td>
                              <td>: <?php echo $k->rute_aktif ?></td>
                            </tr>
                            <tr>
                              <td>Tgl Masuk</td>
                              <td>: <?php echo tglIndoPanjang($k->tgl_masuk) ?></td>
                              <td>No Body</td>
                              <td>: <?php echo $k->no_body ?></td>
                            </tr>
                            <tr>
                              <td>Nomor PK</td>
                              <td>: <?php echo $k->id_pk ?></td>
                              <td>No Pol</td>
                              <td>: <?php echo $k->no_pol ?></td>
                            </tr>
                            <tr>
                              <td>Kategori</td>
                              <td>: <?php echo $k->nama_kategori ?></td>
                              <td>PK</td>
                              <td>: <?php echo $k->ket_pk ?></td>
                            </tr>
                            <tr>
                              <td width="15%">Type</td>
                              <td width="25%">: <?php echo $k->type ?></td>
                              <td width="15%">Seat</td>
                              <td width="21%">: <?php echo $k->kelas ?></td>
                            </tr>
							  <tr>
                              <td width="15%">&nbsp;</td>
                              <td width="25%">&nbsp;</td>
                              <td width="15%">Catatan/Keterangan</td>
                              <td width="21%">: <?php echo $k->keterangan ?></td>
                            </tr>
							  
                          </tbody>
                        </table>
	<h5>Schedule Kerja</h5>
	<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000" class="datatable"  id="table-1" >
                          <tbody>
                            <tr>
                              <td colspan="2" align="center"><strong>BODY</strong></td>
                              <td colspan="2" align="center"><strong>CAT</strong></td>
                              <td colspan="2" align="center"><strong>INTERIOR</strong></td>
                              <td colspan="2" align="center"><strong>ELEKTRIK</strong></td>
                              <td colspan="2" align="center"><strong>JOK</strong></td>
                              <td colspan="2" align="center"><strong>FDI</strong></td>
                            </tr>
                            <tr>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                            </tr>
                            <tr>
                              <td >Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
    </table>
<h5>Actualisasi</h5>
	<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000" class="datatable"  id="table-1" >
                          <tbody>
                            <tr>
                              <td colspan="2" align="center"><strong>BODY</strong></td>
                              <td colspan="2" align="center"><strong>CAT</strong></td>
                              <td colspan="2" align="center"><strong>INTERIOR</strong></td>
                              <td colspan="2" align="center"><strong>ELEKTRIK</strong></td>
                              <td colspan="2" align="center"><strong>JOK</strong></td>
                              <td colspan="2" align="center"><strong>FDI</strong></td>
                            </tr>
                            <tr>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                              <td width="8%">Masuk</td>
                              <td width="8%">&nbsp;</td>
                            </tr>

                            <tr>
                              <td >Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                              <td>Keluar</td>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
    </table>
                    </tr>
   </thead>
                <tbody>
               
			
                </tbody>
</table>
	<h5>Perintah Kerja</h5>
<table width="100%" border="1" cellpadding="5" cellspacing="0"  class="datatable"  id="table-1" >
   <thead>
     <tr>
       <th width="4%">No</th>
       <th width="96%">Keterangan</th>
       </tr>
     <?php
       $no=0;
       foreach ($dataDetail as $x){ 
	     $no++;
						?>
      <tr>
          <th><?php echo $no ?></th>
       <th><?php echo $x->ket_detail ?></th>
       </tr>
     <?php  } ?>
   </thead>
</table>
	<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="background-color:#000 border: 1px solid #000; background-repeat: repeat-x; font-family: arial; font-size: 13px;">
  <thead>
                    <tr>
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                      <td width="25%"><?php echo date('d M Y')?></td>
                    </tr>
                    <tr>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th><strong>Service Advisor</strong></th>
                    </tr>
                    <tr>
                      <td height="60">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><p>&nbsp;</p> <p>&nbsp;</p>                       <?php echo $k->user ?></td>
                    </tr>
      </thead>
                <tbody>
               
			
                </tbody>
</table>

</div>
</div>
<div class="card-footer">
  <button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
  <button class="btn btn-danger" id="tutup" onClick="window.location.assign(" <?php echo base_url(); ?>/Transaksi/Pengiriman");" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>