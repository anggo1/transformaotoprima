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
         .dbl-border {
  border: 3px double black;
  padding: 10px, 10px, 10px, 10px;
}
</style>

<div id="printThis">

    <div class="modal-body">
<table width="100%" border="1" cellpadding="0">
  <tbody>
    <tr>
      <td width="100%" style="padding: 15px;">
        <?php
	foreach ($dataPo as $k) {
  }
    $lokasi = $this->session->userdata['lokasi'];
	$apl1 = $this->db->get("aplikasi where lokasi='".$lokasi."'")->row();
    $tgl_sekarang =date("Y-m-d");
	?>
<table width="100%" border="1" cellpadding="5" cellspacing="0" class="datatable2">
    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
      <tr align="center">
        <th width="99%" align="center">
          <img src="<?php echo base_url(); ?>assets\dist\img\logo_mercedes.png" width="25%"></th>
      </tr>

</table>
<table width="100%" border="1">
      <tr align="center" >
        <th height="12" class="dbl-border"><h5>CV PART ORDER</h5></th>
      </tr>
</table>
  <table width="100%" border="0" cellpadding="1" style="font-size: 14px;" cellspacing="0" class="datatable1">
    <thead>
      <tr>
        <th colspan="2"><div align="left">To</div></th>
        <th>&nbsp;</th>
        <th><div align="left">Page : __1__of__1__</div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th colspan="2"><div align="left"><?php echo $k->nama_sup; ?></div></th>
        <th width="301">&nbsp;</th>
        <th width="262">&nbsp;</th>
        <th width="144">&nbsp;</th>
      </tr>
      <tr>
        <th colspan="2"><div align="left"><?php echo $k->detail ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="32" colspan="2"><div align="left"><?php echo $k->alamat ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="28" colspan="2"><div align="left">FAX : <?php echo $k->no_fax ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="27" colspan="2">&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="27" colspan="2"><div align="left">From</div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th width="101" height="28"><div align="left"><strong>Dealer Name</strong></div></th>
        <th width="548"><div align="left">: <?php echo  $apl1->nama_owner; ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="28"><div align="left"><strong>Order No</strong></div></th>
        <th height="28"><div align="left">: <?php echo $k->kode_pesan.' '.$k->ket_pesan; ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="32"><div align="left"><strong>Order Date</strong></div></th>
        <th height="32"><div align="left">: <?php echo tglIndoSedang($k->tgl_part_order) ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

  </table>
<br>
  <table>

    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="datatable2">
      <thead>
        <tr>
          <th width="4%" rowspan="2"><div align="center">No</div></th>
          <th colspan="4"><div align="center">Part Number</div></th>
          <th width="26%" rowspan="2"><div align="center">Description</div></th>
          <th width="4%" rowspan="2"><div align="center">Qty</div></th>
          <th width="3%" rowspan="2"><div align="center"><p>BO Y/N</p></div></th>
          <th width="22%" rowspan="2"><div align="center">Remarks</div></th>
        </tr>
        <tr>
          <th width="3%">SC</th>
          <th width="32%">&nbsp;</th>
          <th width="3%">ES1</th>
          <th width="3%">ES2</th>
        </tr>
        <?php
        $no = 0;
        $grand_total = 0;
        foreach ($detailPo as $d) : $no++;
          $grand_total += $d->total_harga;        ?>
          <tr>
            <td><div align="center"><?php echo $no ?></div></td>
            <td><div align="center">&nbsp;</div></td>
            <td><div align="center"><?php echo $d->no_part ?></div></td>
            <td><div align="center">&nbsp;</div></td>
            <td><div align="center">&nbsp;</div></td>
            <td><div align="center"><?php echo $d->nama_part ?></div></td>
            <td><div align="center"><?php echo number_format($d->jumlah) ?></div></td>
            <td><div align="center"><?php echo $d->bo ?></div></td>
            <td><div align="center"><?php echo $d->remark ?></div></td>
          </tr>
          
        <?php $no + 1;
        endforeach ?>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
      </thead>

    </table>
<table width="100%" padding="5" border="0" cellpadding="5" cellspacing="0" class="datatable2">
        <tr>
          <td colspan="4"><strong>NOTE :<?php echo $k->keterangan; ?></strong></td>
        </tr>
    </table>
      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
        <tr>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
                    <tr align="center">
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                    <tr align="center">
                      <th><strong>Signature</strong></th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                    <tr align="center">
                      <td><img src="<?php echo base_url(); ?>assets\dist\img\topwaranty.png" width="25%">
                          
                      <p></p>(Ryanto Yodi)
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
      </table>
      </table>

</div>
        
    
  
    </div>
<div class="modal-footer justify-content-between">
<button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
<a href="<?php echo base_url('PartOrder/export'); ?>?id=<?php echo $k->id_part_order; ?>"
                                class="btn btn-info" title="Download" target="_blank"><i
                                    class="fas fa-file-excel"></i>&nbsp;  EXCEL</a>
  <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
    </div>
    <script language="javascript">


    </script>