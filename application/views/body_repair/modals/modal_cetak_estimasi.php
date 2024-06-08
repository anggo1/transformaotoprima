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
    position:absolute;
    left:0;
    top:0;
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
<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <th colspan="4"><div align="left">
                        <table width="100%">
                          <tbody>
                            <tr>
                              <td colspan="5">ESTIMASI PERBAIKAN </td>
                            </tr>
                            <tr>
                              <td colspan="5">&nbsp;</td>
                            </tr>
                            <tr>
                              <td>ID Laporan</td>
                              <td><?php echo $k->id_lapor ?></td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td width="15%">Tgl Masuk</td>
                              <td width="49%"><?php echo $k->tgl_masuk ?></td>
                              <td width="15%">Catatan/Keterangan</td>
                              <td width="21%"><?php echo $k->keterangan ?></td>
                            </tr>
                            <tr>
                              <td>Pengemudi</td>
                              <td><?php echo $k->nip_sp.'&nbsp; | &nbsp;'.$k->nama_sp ?></td>
                              <td>Jenis Perbaikan</td>
                              <td><?php echo $k->kategori ?></td>
                            </tr>
                            <tr>
                              <td>No Body</td>
                              <td><?php echo $k->no_body ?></td>
                              <td>Keterangan Laporan</td>
                              <td><?php echo $k->ket_lapor ?></td>
                            </tr>
                            <tr>
                              <td>No Pol</td>
                              <td><?php echo $k->no_pol ?></td>
                              <td>Status Body</td>
                              <td><?php echo $k->status_body;?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </tr>
   </thead>
                <tbody>
               
			
                </tbody>
</table>
 <table>DETAIL BARANG</table>
                    <div class="table-responsive">
<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000" class="datatable"  id="table-1" >
   <thead>
     <tr>
       <th>No</th>
       <th>PEKERJAAN</th>
       <th>NO PART</th>
       <th>NAMA PART</th>
       <th>JUMLAH</th>
       <th>HARGA</th>
       <th>SATUAN</th>
       <th>TOTAL</th>
       </tr>
     <?php
       $bea_part=0;
       $bea_pk=0;
	   foreach ($hargaEstimasi as $p){
		   $id=$p->id_lapor;
		   $pk=$p->jns_pk;
		   $biaya_pk=$p->biaya+($p->biaya*$p->hrg_naik/100);
		   $biaya_part=$p->totalnye+($p->totalnye*$p->hrg_naik/100);
		   $bea_part += $biaya_part;
		   $bea_pk += $biaya_pk;
	   
	   $ci_bay1 = get_instance();
		$bay1 = "SELECT a.*,c.satuan AS satuan 
		FROM tbl_br_detail_estimasi as a
		LEFT JOIN tbl_wh_barang AS b ON b.no_part=a.no_part 
		LEFT JOIN tbl_wh_satuan AS c ON c.id_satuan=b.satuan 
		
		WHERE a.id_lapor = '$id' AND a.jns_pk = '$pk' ORDER BY a.id_detail";
		$dbay1 = $ci_bay1->db->query($bay1);
       $no=0;
       $harganye=0;
       $grand_total=0;
       $total_biaya=0;
		   foreach($dbay1->result_array() as $dbay1){
		$jns_pk = $dbay1['jns_pk'];
		$no_part = $dbay1['no_part'];
		$nama_part = $dbay1['nama_part'];
		$jml_part = $dbay1['jml_part'];
		$hrg_part = $dbay1['hrg_part']+($dbay1['hrg_part']*$dbay1['hrg_naik']/100);
		$satuan = $dbay1['satuan'];
		$total = $dbay1['total']+($dbay1['total']*$dbay1['hrg_naik']/100);
			
			
       //foreach ($detailPk as $d){ 
	     $no++;
       //$harganye += $k->hrg_part;
		   $grand_total += $total;
		   $total_biaya += $grand_total; 
						?>
      <tr>
          <th><?php echo $no ?></th>
       <th><?php echo $jns_pk ?></th>
       <th><?php echo $no_part ?></th>
       <th><?php echo $nama_part ?></th>
       <th><?php echo $jml_part ?></th>
       <th><?php echo number_format($hrg_part) ?></th>
       <th><?php echo $satuan ?></th>
       <th><div align="right"><?php echo number_format($total) ?></th>
       </tr>
     <?php  } ?>
     <tr>
       <th colspan="7"><div align="right">BIAYA PEKERJAAN</div></th>
       <th colspan="3"><div align="right"><?php echo number_format($biaya_pk); ?></th>
     </tr>
     <tr>

       <th colspan="7"><div align="right"> TOTAL</div></th>
       <th colspan="3"><div align="right"><?php echo number_format($grand_total+$biaya_pk) ?></th>
       </tr>
     <?php }?>
	   <th colspan="7"> <div align="right"> GRAND TOTAL</div></th>
       <th colspan="3"><div align="right"><?php echo number_format($bea_part+$bea_pk) ?></th>
       </tr>
   </thead>
   <tbody>
</table></div>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                      <td width="25%"><?php echo date('D M Y')?></td>
                    </tr>
                    <tr>
                      <th>Mengetahui</th>
                      <th>Menyetujui</th>
                      <th>Keuangan</th>
                      <th>Dibuat</th>
                    </tr>
                    <tr>
                      <td height="60">
                      <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </p>
                      </td>
                      <td>    
                      <p>&nbsp;</p><p>&nbsp;</p>                    
                      <p class="under">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </p>
                      </td>
                      <td>
                      <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </p>
                    </td>
                      <td><p>&nbsp;</p> <p>&nbsp;</p>                       <?php echo $this->session->userdata['full_name'] ?></td>
                    </tr>
      </thead>
                <tbody>
</table>
             
</div>
</div>
        
<div class="modal-footer justify-content-between">
        <button type="button" id="btnPrint" class="btn btn-success" ><span class="fa fa-print"></span>&nbsp;&nbsp;  C E T A K </button>
      <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>