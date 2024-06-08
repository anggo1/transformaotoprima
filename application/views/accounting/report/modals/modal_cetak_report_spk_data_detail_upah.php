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
<div id="printThis"><div class="alert bg-white"><?php foreach ($dataKop as $k) {}?>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                          <tbody>
                            <tr>
                              <td>ID Laporan</td>
                              <td>: <?php echo $k->id_lapor ?></td>
                              <td width="24%" rowspan="6">&nbsp;</td>
                              <td>Pool</td>
                              <td>: <?php echo $k->pool ?></td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>Tgl Masuk</td>
                              <td>: <?php echo tglIndoPanjang($k->tgl_masuk) ?></td>
                              <td>Rute</td>
                              <td>: <?php echo $k->rute_aktif ?></td>
                            </tr>
                            <tr>
                              <td>Kategori</td>
                              <td>: <?php echo $k->nama_kategori ?></td>
                              <td>No Body</td>
                              <td>: <?php echo $k->no_body ?></td>
                            </tr>
                            <tr>
                              <td>Type</td>
                              <td>: <?php echo $k->type ?></td>
                              <td>No Pol</td>
                              <td>: <?php echo $k->no_pol ?></td>
                            </tr>
                            <tr>
                              <td>Catatan/Keterangan</td>
                              <td>: <?php echo $k->keterangan ?></td>
                              <td>Seat</td>
                              <td>: <?php echo $k->kelas ?></td>
                            </tr>
                            <tr>
                              <td width="15%">&nbsp;</td>
                              <td width="25%">&nbsp;</td>
                              <td width="15%">&nbsp;</td>
                              <td width="21%">&nbsp;</td>
                            </tr>
							  
                          </tbody>
                        </table>
<div class="table-responsive">
        <table width="100%" class="datatable">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Tgl</th>
                    <th>ID Pekerjaan</th>
                    <th>Pekerjaan</th>
                    <th>No Body</th>
                    <th>Biaya</th>
                    <th>Pembayaran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$dibayar=0;
$total_jumlah=0;
foreach ($dataPart as $s) {
    $dibayar += $s->jumlah;
    $total_jumlah += $s->biaya_borong;
?> 
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo tglIndoPendek($s->tgl_bayar); ?></td>
                  <td><?php echo $s->id_pk; ?></td>
                  <td><?php echo $s->ket_pk; ?></td>
                  <td><?php echo $s->no_body; ?></td>
                  <td align="right"><?php echo number_format($s->biaya_borong); ?></td>
                  <td align="right"><?php echo number_format($s->jumlah); ?></td>
                  <td align="right"><?php echo $s->keterangan; ?></td>
                </tr>
                
                <?php
    $no++;
}
?>
				<tr>
                  <td align="right" style="color: #fff; color: rgba(0, 0, 0, 0.0);">x1</td>
                  <td align="right"></td>                 
                  <td align="right"></td>                  
                  <td align="right"></td>                  
                  <td align="right"></td>                  
                  <td align="right">Total Pembayaran</td>                                    
                  <td align="right" style="text-align: right"><?php echo number_format($s->beaBorong); ?></td>
                  <td align="right">&nbsp;</td>
              </tr>
                <tr>
				  <td align="right" style="color: #fff; color: rgba(0, 0, 0, 0.0);">x2</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">Jumlah Pembayaran</td>
                  <td align="right" style="text-align: right"><?php echo number_format($dibayar); ?></td>
                  <td align="right">&nbsp;</td>
                  </tr>
                <tr>
                    <td align="right" style="color: #fff; color: rgba(0, 0, 0, 0.0);">x3</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">Sisa Pembayaran</td>
                    <td align="right" style="text-align: right"><?php echo number_format($s->beaBorong-$dibayar); ?></td>
                    <td align="right">&nbsp;</td>
                  </tr>
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