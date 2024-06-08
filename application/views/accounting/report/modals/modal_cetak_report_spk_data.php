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
    border: 1px solid #000;
    font: bold;
    padding: 1px;
}
.datatable td {
    border: 1px solid #000;
    padding: 5px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.datatable th {
    border: 2px solid #000;
    padding: 5px;
    font: bold;
    font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    text-align: center;
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
<div class="alert bg-white"><?php foreach ($dataPart as $s) {}?>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                          <tbody>
                            <tr>
                              <td colspan="6"><font size="+2">Total Biaya SPK No. <?php echo $s->id_lapor;?></font> </td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td width="24%" rowspan="7">&nbsp;</td>
                              <td>Pool</td>
                              <td>: <?php echo $s->pool ?></td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>ID Laporan</td>
                              <td>: <?php echo $s->id_lapor ?></td>
                              <td>Rute</td>
                              <td>: <?php echo $s->rute_aktif ?></td>
                            </tr>
                            <tr>
                              <td>Tgl Masuk</td>
                              <td>: <?php echo tglIndoPanjang($s->tgl_masuk) ?></td>
                              <td>No Body</td>
                              <td>: <?php echo $s->no_body ?></td>
                            </tr>
                            <tr>
                              <td>Kategori</td>
                              <td>: <?php echo $s->nama_kategori ?></td>
                              <td>No Pol</td>
                              <td>: <?php echo $s->no_pol ?></td>
                            </tr>
                            <tr>
                              <td>Type</td>
                              <td>: <?php echo $s->type ?></td>
                              <td>Seat</td>
                              <td>: <?php echo $s->kelas ?></td>
                            </tr>
                            <tr>
                              <td width="15%">&nbsp;</td>
                              <td width="25%">&nbsp;</td>
                              <td width="15%">Catatan/Keterangan</td>
                              <td width="21%">: <?php echo $s->keterangan ?></td>
                            </tr>
							  <tr>
                              <td width="15%">&nbsp;</td>
                              <td width="25%">&nbsp;</td>
                              <td width="15%">&nbsp;</td>
                              <td width="21%">&nbsp;</td>
                            </tr>
							  
                          </tbody>
                        </table>
<table width="100%" class="datatable">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>ID PK</th>
                    <th>Kode</th>
                    <th>PK</th>
                    <th>No Body</th>
                    <th>Biaya Material</th>
                    <th>Upah Borong</th>
                </tr>
      </thead>
            <tbody>
                <?php
$no = 1;
$total_part=0;
$total_upah=0;
foreach ($dataPart as $s) {
    $total_part += $s->total_hrg_part;
    $total_upah += $s->biaya_borong;
?> 
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $s->id_pk; ?></td>
                  <td><?php echo $s->jns_pk; ?></td>
                  <td><?php echo $s->ket_pk; ?></td>
                  <td><?php echo $s->no_body; ?></td>
                  <td align="right"><?php echo number_format($s->total_hrg_part); ?></td>
                  <td align="right"><?php echo number_format($s->biaya_borong); ?></td>
                </tr>
                <?php
    $no++;
}
?>
            </tbody>
    <tfoot>
			<tr>
                    <td align="right"><font size="0px"></font></td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">Sub Total</td>
                    <td align="right"><?php echo number_format($total_part); ?></td>
                    <td align="right"><?php echo number_format($total_upah); ?></td>
        </tr>
        <tr>
                    <td align="right"><font size="0px"></font></td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">Grand Total </td>
                    <td align="right">&nbsp;</td>
                    <td align="right"><?php echo number_format($total_part + $total_upah); ?></td>
        </tr>
	  </tfoot>
    </table>
</div>
</div>
        <div class="card-footer">
        <button type="button" id="btnPrint" class="btn btn-success" ><span class="fa fa-print"></span>&nbsp;&nbsp;  C E T A K </button>
      <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>