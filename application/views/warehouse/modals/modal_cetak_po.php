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
  <?php
	foreach ($dataPo as $k) {
  }
	$status_po=$k->status;
	$apl1 = $this->db->get("aplikasi where status='".$k->status."'")->row();

	?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
    <thead>
      <tr>
        <th width="10%" align="right"><img src="<?php echo base_url(); ?>assets/foto/logo/<?php echo  $apl1->logo; ?>" width="100%" height="100">
          
        </th>
        <th width="99%" align="left"><h4><?php echo  $apl1->nama_owner; ?></h4>
          <h5><?php echo  $apl1->alamat; ?></h5></th>
      </tr>
      <tr>
        <th colspan="2">NPWP :
        <?php  echo $apl1->npwp; ?></th>
      </tr>
      <tr>
        <th colspan="2"><h4>PURCHASE ORDER</h4></th>
      </tr>
    </thead>

  </table>
  <table width="100%" border="0" cellpadding="5" style="font-size: 14px;" cellspacing="0" class="datatable1">
    <thead>
      <tr>
        <th width="70">
          <div align="left">Kepada</div>
        </th>
        <th width="362">
          <div align="left"><?php echo $k->nama_sup; ?></div>
        </th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th>
          <div align="left"><?php echo $k->alamat ?></div>
        </th>
      </tr>
      <tr>
        <th height="32">&nbsp;</th>
        <th>
          <div align="left"><?php echo $k->no_tlp ?></div>
        </th>
      </tr>
    </thead>

  </table>
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
    <thead>
      <tr>
        <th width="70">
          <div align="left">Nomor PO</div>
        </th>
        <th width="362">
          <div align="left">: <?php echo $k->kode_po; ?></div>
        </th>
        <th width="70">
          <div align="left">TOP</div>
        </th>
        <th width="325">
          <div align="left">: <?php echo $k->top ?></div>
          <div align="left"></div>
        </th>
      </tr>
      <tr>
        <th>
          <div align="left">Tanggal PO</div>
        </th>
        <th>
          <div align="left">: <?php echo tglIndoPanjang($k->tgl_po) ?></div>
        </th>
        <th>
          <div align="left">Keterangan</div>
        </th>
        <th>
          <div align="left">: <?php echo $k->keterangan ?></div>
          <div align="left"></div>
        </th>
      </tr>
    </thead>

  </table>

  <table>

    <table width="100%" border="1" cellpadding="5" cellspacing="0" class="datatable2">
      <thead>
        <tr>
          <th width="6%">No</th>
          <th width="16%">No Barang</th>
          <th width="20%">Nama Barang</th>
          <th width="10%">JML</th>
          <th width="10%">Satuan</th>
          <th width="12%">Harga</th>
          <th width="15%">Sub Total</th>
          <th width="9%">Total</th>
        </tr>
        <?php
        $no = 0;
        $grand_total = 0;
        foreach ($detailPo as $d) : $no++;
          $grand_total += $d->total_harga;
          $ppn = $grand_total * $k->ppn / 100;
        ?>
          <tr>
            <th><?php echo $no ?></th>
            <th><?php echo $d->no_part ?></th>
            <th><?php echo $d->nama_part ?></th>
            <th align="right"><?php echo number_format($d->jumlah) ?></th>
            <th><?php echo $d->satuan?></th>
            <th><?php echo number_format($d->harga) ?></th>
            <th><?php echo number_format($d->total_harga + $d->total_diskon) ?></th>
            <th><?php echo number_format($d->total_harga) ?></th>
          </tr>
        <?php $no + 1;
        endforeach ?>
        <tr>
          <th colspan="5" rowspan="3">&nbsp;</th>
          <th colspan="2" align="right">Sub Total</th>
          <th id="sub_total"><?php echo number_format($grand_total) ?></th>
        </tr>
        <tr>
          <th colspan="2" align="right">PPN <?php echo $k->ppn ?> %</th>
          <th id="t_ppn"><?php echo number_format($ppn) ?></th>
        </tr>
        <tr>

          <th colspan="2" align="right">Grand Total</th>
          <th id="grand_total">
            <font size="+1"><?php echo number_format($grand_total + $ppn) ?></font>
          </th>
        </tr>
      </thead>

    </table>
    <table>
      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
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
<div class="modal-footer justify-content-between">
<button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
  <button class="btn btn-danger" id="tutup" onClick="window.location.assign(" <?php echo base_url(); ?>/Transaksi/Pengiriman");" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
    </div>