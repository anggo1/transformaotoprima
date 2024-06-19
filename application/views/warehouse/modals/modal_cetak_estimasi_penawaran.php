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
    border: 2px solid #000;
    font: bold;
    font-weight: normal;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    padding: 1px 1px 1px 1px;
}
         
.datatable3 {
    border-collapse: collapse;
    font: bold;
}
.datatable3 td {
    padding: 0px;
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
	foreach ($dataPo as $k) {
  }
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
        <th colspan="2"><div align="left"></div></th>
        <th>&nbsp;</th>
        <th><div align="left">: <?php echo  $apl1->nama_owner; ?></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th colspan="2"><div align="left">Estimasi Penawaran Sparepart</div></th>
        <th>&nbsp;</th>
        <th><div align="left"></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th><div align="left">Customer Name</div></th>
        <th><div align="left">: <?php echo $k->nama_cus; ?></div></th>
        <th width="255">&nbsp;</th>
        <th width="319">&nbsp;</th>
        <th width="148">&nbsp;</th>
      </tr>
      <tr>
        <th><div align="left">Address</div></th>
        <th><div align="left">: <?php echo $k->alamat ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="32"><div align="left">City</div></th>
        <th height="32"><div align="left">: <?php echo $k->kota ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="28"><div align="left">Up</div></th>
        <th height="28"><div align="left">: <?php echo $k->tlp_person ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="27"><div align="left">Telp or Mobile no</div></th>
        <th height="27"><div align="left">: <?php echo $k->no_tlp ?></div></th>
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
        <th width="185" height="28">&nbsp;</th>
        <th width="489">&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

  </table>
    <table width="60%" border="1" cellpadding="1" style="font-size: 14px;" cellspacing="0" align="right">
    <thead>
      <tr>
        <th colspan="3">For Reference please quote the following no</th>
        </tr>
      <tr>
        <th colspan="3">&nbsp;</th>
        </tr>
      <tr>
        <th width="30%" height="36"><font size="-2">Date :</font><br>
          <?php echo $k->tgl_estimasi_penawaran ?></br></th>
        <th width="30%" align="center"><font size="-2">Customer No.</font><br>
          <?php echo $k->kode_cus ?></br></th>
        <th width="30%"><font size="-2">Page<br></font></th>
      </tr>
    </thead>

  </table>
    <p>&nbsp; </p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="100%" border="1" cellpadding="1" cellspacing="0" class="datatable2">
        <tr>
          <th><font size="-2">Registration No</font><br>
          <?php echo $k->tgl_estimasi_penawaran ?></br></th></th>
          <th>Vin No</th>
          <th>Sales designation</th>
          <th>Date / time received</th>
        </tr>
        <tr>
          <th>Milage/Km</th>
          <th>Engine No.</th>
          <th>Account No.</th>
          <th>Received By</th>
        </tr>
        <tr>
          <th>Routing No.</th>
          <th>Las Service date/Millage/km</th>
          <th>Date of 1st registration</th>
          <th>&nbsp;</th>
        </tr>
          </table>
    <br>
    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="datatable3">
      <thead>
        <tr>
          <th width="3%" height="31"><div align="center">No</div></th>
          <th width="11%"><div align="center">Part Number</div></th>
          <th ><strong>Description</strong></th>
          <th width="8%">Price</th>
          <th width="4%"><div align="center">Pcs</div></th>
          <th width="7%"><div align="center">Amount</div></th>
          <th width="14%"><div align="center">Remarks</div></th>
        </tr>
        <?php
        $no = 0;
        $grand_total = 0;
        foreach ($detailPo as $d) : $no++;
          $grand_total += $d->total_harga;        ?>
          <tr>
            <td><div align="center"><?php echo $no ?></div></td>
            <td><div align="center">&nbsp;<?php echo $d->no_part ?></div></td>
            <td width="53%"><div align="center"><?php echo $d->nama_part ?></div></td>
            <td width="8%"><div align="center">&nbsp;<?php echo number_format($d->harga_net) ?></div></td>
            <td width="4%"><div align="center">&nbsp;<?php echo number_format($d->jumlah) ?></div></td>
            <td><div align="center"><?php echo number_format($d->total_harga) ?></div></td>
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
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <?php
        foreach ($detailKet as $k) :    ?>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
              <td><font size="-2">** <?php echo $k->remark; ?></font></td>
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
          </tr>
          <tr>
            <td colspan="3" rowspan="5">&nbsp;</td>
            <td>Total</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="5">&nbsp;</td>
          </tr>
          <tr>
            <td>PPN</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Voucher Sparepart</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Ongkos Kirim</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Grand Total</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
      </thead>

  </table>
<table width="100%" padding="5" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
    </table>
      <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
        </tr>
        <tr align="center">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>Best Regard</td>
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
                      <td>........................................<br>
                      Nama dan tanda tangan</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><?php echo $this->session->userdata['full_name'] ?></td>
                    </tr>
      </table>
      <!--</table>-->

</div>
        
    
  
    </div>
<div class="modal-footer justify-content-between">
<button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
  <button class="btn btn-danger" id="tutup" onClick="window.location.assign(" <?php echo base_url(); ?>/Transaksi/Pengiriman");" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
    </div>