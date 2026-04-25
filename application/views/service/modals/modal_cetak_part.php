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
    padding: 0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.datatable2 th {
    border: 1px solid #000;
    height: 10px;
}
         
.datatable3 {
    border-collapse: collapse;
    font: bold;
}
.datatable3 td {
    padding: 2px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:8px;
    font: bold;
}
.datatable3 th {
    border: 1px solid #000;
    font-display: block;
    align-content: center;
    font-weight: bolder;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
    text-align: center;
}
         
.table-atas {
    border-collapse: collapse;
    font: bold;
    float: right;
}
.table-atas td {
    padding: 0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.table-atas th {
    border: 2px solid #000;
    font: bold;
    font-weight: normal;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
}
   .text1 {
    font: bold;
    font-weight: normal;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:8px;
       padding: 1px 1px 1px 1px;
       padding-bottom: 0px;
       width: auto;
}
    .text2 {
    font: bold;
    font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
    align-content: center;
        text-align: center;
       padding: 1px 1px 1px 1px;
       width: auto;
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
  <tbody>
    <tr>
      <td width="100%" style="padding: 15px;">
        <?php
			if (!empty($dataCus)) {
			foreach ($dataCus as $c)
                if (!empty($dataSa)) {
                foreach ($dataSa as $s)  {{}}}} 
    $lokasi = $this->session->userdata['lokasi'];
	$apl1 = $this->db->get("aplikasi where lokasi='".$lokasi."'")->row();
    $tgl_sekarang =date("Y-m-d");
	?>
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr align="center">
        <th width="99%" align="center">
          <img src="<?php echo base_url(); ?>assets\dist\img\logo_mercedes.png" width="25%"></th>
      </tr>

</table>
  <table width="100%" border="0" cellpadding="1" style="font-size: 14px;" cellspacing="0" class="datatable1">
    <thead>
      <tr>
        <th colspan="3"><div align="left"><font size="2">Work Order Form</font></div></th>
        <th colspan="2">Dealer Name</th>
        <th><div align="left"><?php echo  $apl1->nama_owner; ?></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th colspan="3"><div align="left"></div></th>
        <th colspan="2">&nbsp;</th>
        <th><div align="left"></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th width="85">&nbsp;</th>
        <th width="207"><div align="left">Customer Name</div></th>
        <th><div align="left">: <?php echo $c->nama_cus; ?></div></th>
        <th width="162"><div align="left">Model</div></th>
        <th width="113">&nbsp;</th>
        <th width="193"><div align="left">Workshop order no</div></th>
        <th width="350"><div align="left">: <?php echo $s->wo_no ?></div></th>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th><div align="left">Address</div></th>
        <th rowspan="2"><div align="left">: <?php echo $c->alamat ?>, <?php echo $c->kota ?></div>          <div align="left"></div></th>
        <th><div align="left">VIN</div></th>
        <th><span class="text2"><?php echo $s->vin ?></span></th>
        <th><div align="left">Oredered by</div></th>
        <th><div align="left">: <?php echo $c->nama_cus; ?></div></th>
      </tr>
      <tr>
        <th height="20">&nbsp;</th>
        <th height="20">&nbsp;</th>
        <th><div align="left">Engine No</div></th>
        <th><span class="text2"><?php echo $s->vin ?></span></th>
        <th><div align="left">received by</div></th>
        <th><div align="left">: <span class="text2"><?php echo $s->sa_name ?></span></div></th>
      </tr>
      <tr>
        <th height="20">&nbsp;</th>
        <th height="20"><div align="left">Car Registration no</div></th>
        <th height="20"><div align="left">: <?php echo $c->tlp_person ?></div></th>
        <th><div align="left">Date of 1st registration</div></th>
        <th><span class="text2"><?php echo tglIndoPendek($s->date_open_wo) ?></span></th>
        <th><div align="left">Issued on</div></th>
        <th><div align="left">: <?php echo $s->customer_complain; ?></div></th>
      </tr>
      <tr>
        <th height="20">&nbsp;</th>
        <th height="20"><div align="left">Date</div></th>
        <th height="20"><div align="left">: <?php echo $c->no_tlp ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="19" colspan="3">&nbsp;</th>
        <th colspan="2">&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="19" colspan="2">&nbsp;</th>
        <th width="224">&nbsp;</th>
        <th colspan="2">&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

  </table>
    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="datatable3">
      <thead>
        <tr>
          <th width="6%" height="31"><div align="center">No</div></th>
          <th width="13%">Part Number</th>
          <th width="4%"><div align="center">ES1</div></th>
          <th ><strong>ES2</strong></th>
          <th width="13%">Quantity Requested</th>
          <th width="28%">Description</th>
          <th width="11%">Unty Price</th>
          <th colspan="2">Total Price</th>
          </tr>
        <?php
        if(!empty($dataPart)){
        foreach ($dataPart as $d) { 
            $no=1;
          ?>
          <tr>
            <td><div align="center"><?php echo $no ?></div></td>
            <td><div align="center"><?php echo $d->no_part; ?>&nbsp;</div></td>
            <td>&nbsp;</td>
            <td width="4%"><div align="center"></div></td>
            <td width="13%"><div align="center"><?php echo $d->jumlah; ?></div></td>
            <td width="28%"><div align="center"><?php echo $d->keterangan; ?>&nbsp;</div></td>
            <td width="11%"><div align="center"><?php echo number_format($d->harga, 0, ',', '.'); ?>&nbsp;</div></td>
            <td width="9%"><div align="center"><?php echo 'Rp. '.number_format($d->total, 0, ',', '.'); ?>&nbsp;</div></td>
            <td width="12%"><div align="center"><?php echo 'Rp. '.number_format($d->total, 0, ',', '.'); ?>&nbsp;</div></td>
            </tr>
          
        <?php $no + 1;
        }} ?>
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
          <?php
        foreach ($dataPart as $c) :    ?>
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
          <?php endforeach ?>
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

      <table width="100%" border="0" cellpadding="5" cellspacing="0" style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
        <tr>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
        </tr>
        <tr align="center">
          <td>&nbsp;</td>
          <td>Foreman</td>
          <td>Mechanic</td>
          <td>Delivered by</td>
        </tr>
                    <tr align="center">
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                    <tr align="center">
                      <th height="61">&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                    </tr>
                    <tr align="center">
                      <td>&nbsp;</td>
                      <td><?php echo $this->session->userdata['full_name'] ?></td>
                      <td><?php echo $this->session->userdata['full_name'] ?></td>
                      <td><?php echo $this->session->userdata['full_name'] ?></td>
                    </tr>
      </table>
      <!--</table>-->

</div>
        
    
  
    </div>
<div class="modal-footer justify-content-between">
<button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
  <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
    </div>