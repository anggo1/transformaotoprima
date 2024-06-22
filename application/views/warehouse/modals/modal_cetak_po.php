<script>
  document.getElementById("btnPrint").onclick = function() {
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
.datatable2 {
    border-collapse: collapse;
    font: bold;
}
.datatable td {
    padding: 2px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
    font: bold;
    text-align: right;
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
  <?php
	foreach ($dataPo as $k) {
  }
    $lokasi = $this->session->userdata['lokasi'];
	$apl1 = $this->db->get("aplikasi where lokasi='".$lokasi."'")->row();
    $tgl_sekarang =date("Y-m-d");
	?>
    <div class="modal-body">
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
    <thead>
      <tr align="center">
        <th align="center"><img src="<?php echo base_url(); ?>assets/foto/logo/<?php echo  $apl1->logo; ?>" width="8%" height="8%">
        <h4 style="text-transform: capitalize;"><?php echo  $apl1->nama_owner; ?></h4>
      </tr>
      <tr align="center">
        <th><h6><?php echo  $apl1->alamat.' '.$apl1->kota.' Telp: '.$apl1->tlp; ?></h5></th>
      </tr>
      <tr align="center" >
        <th><h4>PURCHASE ORDER</h4></th>
      </tr>
    </thead>

  </table>
  <table width="100%" border="0" cellpadding="5" style="font-size: 14px;" cellspacing="0" class="datatable1">
    <thead>
      <tr>
        <th><div align="left">Kepada</div></th>
        <th>&nbsp;</th>
        <th><div align="left">Nomor PO</div></th>
        <th><div align="left">: <?php echo $k->kode_po; ?></div></th>
      </tr>
      <tr>
        <th width="659"><div align="left"><?php echo $k->nama_sup; ?></div></th>
        <th width="187">&nbsp;</th>
        <th width="186"><div align="left">Tanggal PO</div></th>
        <th width="334"><div align="left">: <?php echo tglIndoPanjang($k->tgl_po) ?></div></th>
      </tr>
      <tr>
        <th><div align="left"><?php echo $k->alamat ?></div></th>
        <th>&nbsp;</th>
        <th><div align="left">TOP</div></th>
        <th><div align="left">: <?php echo $k->top ?></div></th>
      </tr>
      <tr>
        <th height="32"><div align="left">Telp :<?php echo $k->no_tlp ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="32"><div align="left">Up :<?php echo $k->tlp_person ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

</table>
<br>
  <table>

    <table width="100%" border="1" cellpadding="5" cellspacing="0" class="datatable2">
      <thead>
        <tr align="center">
          <th width="5%">No</th>
          <th width="10%">No Barang</th>
          <th width="30%">Nama Barang</th>
          <th width="10%">Satuan</th>
          <th width="5%">JML</th>
          <th width="10%">Harga</th>
          <th width="10%">Total</th>
        </tr>
        <?php
        $no = 0;
        $grand_total = 0;
        foreach ($detailPo as $d) : $no++;
          $grand_total += $d->total_harga;
          $ppn = $grand_total * $k->ppn / 100;
        ?>
          <tr>
            <td><?php echo $no ?></td>
            <td><?php echo $d->no_part ?></td>
            <td><?php echo $d->nama_part ?></td>
            <td align="center"><?php echo $d->satuan?></td>
            <td align="center"><?php echo number_format($d->jumlah) ?></td>
            <td align="right"><?php echo number_format($d->harga) ?></td>
            <td align="right"><?php echo number_format($d->total_harga,2,",",".") ?></td>
          </tr>
        <?php $no + 1;
        endforeach ?>
        <tr>
          <td colspan="5" rowspan="3">&nbsp;</td>
          <td  align="right">Sub Total</td>
          <td id="sub_total" align="right"><?php echo number_format($grand_total,2,",",".") ?></td>
        </tr>
        <tr>
          <td align="right">PPN <?php echo $k->ppn ?> %</td>
          <td id="t_ppn" align="right"><?php echo number_format($ppn) ?></td>
        </tr>
        <tr>
          <td align="right">Grand Total</td>
          <td id="grand_total" align="right">
            <font size="+1"><?php echo number_format($grand_total + $ppn,2,",",".") ?></font>
          </td>
        </tr>
      </thead>

    </table>
    <table>
<table width="100%" padding="5" border="1" cellpadding="5" cellspacing="0" class="datatable2">
        <tr align="center">
          <td colspan="4"><strong>Terbilang : <i><?php echo terbilang($grand_total + $ppn).' Rupiah'; ?></i></strong></td>
        </tr>
        </table>
      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
        <tr>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">Keterangan :</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="top">-</td>
          <td><div align="left"><?php echo $k->keterangan ?></div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
                      <td width="3%" align="right" valign="top">-</td>
                      <td width="22%"><div align="left"><?php echo $k->keterangan2 ?></div></td>
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                    </tr>
                    <tr>
                      <th colspan="2">&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                    <tr align="center">
                      <th colspan="2">&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th><?php echo tglIndoPanjang($tgl_sekarang)?></th>
                    </tr>
                    <tr align="center">
                      <th colspan="2">Mengetahui</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>Hormat Kami</th>
                    </tr>
                    <tr align="center">
                      <td height="60" colspan="2">
                      <p>&nbsp;</p><p>&nbsp;</p>
                      <p class="under"><?php echo $k->pengesah ?>
                      </p>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><p>&nbsp;</p> <p>&nbsp;</p><?php echo $this->session->userdata['full_name'] ?></td>
                    </tr>
      <tr>
        <td colspan="2"></thead>
                <tbody>
      </table>

</div></div>
<div class="modal-footer justify-content-between">
<button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
  <button class="btn btn-danger" id="tutup" onClick="window.location.assign(" <?php echo base_url(); ?>/Transaksi/Pengiriman");" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
    </div>