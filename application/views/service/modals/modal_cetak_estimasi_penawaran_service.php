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
.datatable1 td {
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
	foreach ($dataPo as $k) {
        foreach ($dataCus as $c) {
  }
  }
    $lokasi = $this->session->userdata['lokasi'];
	$apl1 = $this->db->get("aplikasi where lokasi='".$lokasi."'")->row();
    $tgl_sekarang =date("Y-m-d");
	?>
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr align="center">
        <th width="99%" align="center">
          <img src="<?php echo base_url(); ?>assets\dist\img\logo_mercedes.png" width="10%"></th>
      </tr>

</table>
  <table width="100%" border="0" cellpadding="1" style="font-size: 14px;" cellspacing="0" class="datatable1">
    <thead>
      <tr>
        <th colspan="2"><div align="left"></div></th>
        <th>&nbsp;</th>
        <th><div align="left"></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th colspan="2"><div align="left">Estimasi Penawaran Sparepart</div></th>
        <th>&nbsp;</th>
        <th><div align="left"><?php echo  $apl1->nama_owner; ?></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th><div align="left">Customer Name</div></th>
        <th><div align="left">: <?php echo $c->nama_cus; ?></div></th>
        <th width="138">&nbsp;</th>
        <th width="513"><div align="left"><?php echo  $apl1->status; ?></div></th>
        <th width="71">&nbsp;</th>
      </tr>
      <tr>
        <th><div align="left">Address</div></th>
        <th><div align="left">: <?php echo $c->alamat ?></div></th>
        <th>&nbsp;</th>
        <th><div align="left"><?php echo  $apl1->alamat; ?></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="20"><div align="left">City</div></th>
        <th height="20"><div align="left">: <?php echo $c->kota ?></div></th>
        <th>&nbsp;</th>
        <th><div align="left"><?php echo  $apl1->kota.' '.$apl1->kode_pos; ?> Indonesia</div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="20"><div align="left">Up</div></th>
        <th height="20"><div align="left">: <?php echo $c->tlp_person ?></div></th>
        <th>&nbsp;</th>
        <th><div align="left"><?php echo  $apl1->tlp; ?></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="20"><div align="left">Telp or Mobile no</div></th>
        <th height="20"><div align="left">: <?php echo $c->no_tlp ?></div></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th height="19" colspan="2">&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th width="185" height="19">&nbsp;</th>
        <th width="489">&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

  </table>
    <table width="60%" border="1" cellpadding="1" style="font-size: 14px;" cellspacing="0" align="right">
    <thead>
      <tr align="center">
        <th colspan="3">For Reference please quote the following no</th>
        </tr>
      <tr align="center">
        <th colspan="3"><?php echo $k->kode_estimasi_penawaran ?></th>
        </tr>
      <tr>
        <th width="30%" height="36"><font size="-2">Date :</font><br>
          <?php echo tglIndoSedang($k->tgl_estimasi_penawaran) ?></br></th>
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
            <th height="37"><div class="text1"><font size="-2">Registration No</font></div>
          <div class="text2"><?php echo $k->tgl_estimasi_penawaran ?></div></th>
          <th><div class="text1"><font size="-2">Vin No</font></div>
            <div class="text2"><?php echo $k->no_vin ?></div></th>
          <th><div class="text1"><font size="-2">Sales designation</font></div>
            <div class="text2"><?php echo $k->sales_design ?></div></th>
          <th><div class="text1"><font size="-2">Date / time received</font></div>
            <div class="text2"><?php echo $k->date_received ?></div></th>
        </tr>
        <tr>
          <th><div class="text1"><font size="-2">Millage/Km</font></div>
            <div class="text2"><?php echo $k->millage ?></div></th>
          <th><div class="text1"><font size="-2">Engine No.</font></div>
            <div class="text2"><?php echo $k->engine_no ?></div></th>
          <th><div class="text1"><font size="-2">Account No.</font></div>
            <div class="text2"><?php echo $k->acc_no?></div></th>
          <th><div class="text1"><font size="-2">Received By</font></div>
            <div class="text2"><?php echo $k->received_by ?></div></th>
        </tr>
        <tr>
          <th><div class="text1"><font size="-2">Routing No.</font></div>
            <div class="text2"><?php echo $k->routing_no ?></div></th>
          <th><div class="text1"><font size="-2">Las Service date/Millage/km</font></div>
            <div class="text2"><?php echo $k->last_km ?></div></th>
          <th><div class="text1"><font size="-2">Date of 1st registration</font></div>
            <div class="text2"><?php echo $k->date_of_regis ?></div></th>
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
          <th width="4%"><div align="center">Pcs/LO</div></th>
          <th width="7%"><div align="center">Amount</div></th>
          <th width="14%"><div align="center">Remarks</div></th>
        </tr>
        <?php
        $no = 0;
        $grand_total = 0;
        foreach ($detailPo as $d) : $no++;
        $ppn = $k->ppn;
        if(empty($ppn)){
          $k->ppn = 0;
        }
          $ppnnya = $k->ppn;
        
          $grand_total += $d->total_harga;
          $ppn = $grand_total * $k->ppn / 100;
            $totalnya = $d->harga_net * $d->jumlah;

          ?>
          <tr>
            <td><div align="center"><?php echo $no ?></div></td>
            <td><div align="center">&nbsp;<?php echo $d->no_part ?></div></td>
            <td width="53%"><div align="center"><?php echo $d->nama_part ?></div></td>
            <td width="8%"><div align="right">&nbsp;<?php echo "Rp. " .number_format($d->harga_net,0,",",".") ?></div></td>
            <td width="4%"><div align="center">&nbsp;<?php echo $d->jumlah ?></div></td>
            <td><div align="right"><?php echo "Rp. " .number_format($totalnya,0,",",".") ?></div></td>
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
        foreach ($detailKet as $c) :    ?>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
              <td><font size="-2">** <?php echo $c->remark; ?></font></td>
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
      </thead>

  </table>
<table width="100%" cellpadding="1" cellspacing="0" class="data1"
                        style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
                        <thead>
                        <tbody>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="30" colspan="3"
                                    style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
                                    Standard Checking</td>
                                <td width="3%">&nbsp;</td>
                                <td colspan="2"
                                    style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
                                    Valuable Item Missing</td>
                            </tr>
                            <tr>
                                <td width="38%">X : Stone chipping</td>
                                <td width="14%">Lekage</td>
                                <td width="5%">
                                    <input type="hidden" id="leakage" name="leakage" value="N">
                                    <input type="checkbox" id="leakage" name="leakage" value="Y"></td>
                                <td width="3%">&nbsp;</td>
                                <td width="30%">First Air Kit</td>
                                <td><input type="hidden" id="fak" name="fak" value="N">
                                    <input type="checkbox" id="fak" name="fak" value="Y"></td>
                            </tr>
                            <tr>
                                <td>O : Dent</td>
                                <td>Abnormal Noise</td>
                                <td>
                                    <input type="hidden" id="abnormal_noise" name="abnormal_noise" value="N">
                                    <input type="checkbox" id="abnormal_noise" name="abnormal_noise" value="Y"></td>
                                <td>&nbsp;</td>
                                <td>Spare Kit</td>
                                <td><input type="hidden" id="spare_kit" name="spare_kit" value="N">
                                    <input type="checkbox" id="spare_kit" name="spare_kit" value="Y"></td>
                            </tr>
                            <tr>
                                <td>&and; : Scrathes</td>
                                <td>Error Code/ Indicator</td>
                                <td><input type="hidden" id="error_code" name="error_code" value="N">
                                    <input type="checkbox" id="error_code" name="error_code" value="Y"></td>
                                <td>&nbsp;</td>
                                <td>STNK</td>
                                <td><input type="hidden" id="stnk" name="stnk" value="N">
                                    <input type="checkbox" id="stnk" name="stnk" value="Y"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>Brake,Clutch &amp; Tire 10 Minutes Cyle Check</td>
                                <td><input type="hidden" id="brake" name="brake" value="N">
                                    <input type="checkbox" id="brake" name="brake" value="Y"></td>
                                <td>&nbsp;</td>
                                <td>Operational Manual</td>
                                <td width="10%"><input type="hidden" id="manual" name="manual" value="N">
                                    <input type="checkbox" id="manual" name="manual" value="Y"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>Vehicle Tool Kit</td>
                                <td><input type="hidden" id="vtk" name="vtk" value="N">
                                    <input type="checkbox" id="vtk" name="vtk" value="Y"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="21">&nbsp;</td>
                                <td height="21">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
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
          <td>Service Ad</td>
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