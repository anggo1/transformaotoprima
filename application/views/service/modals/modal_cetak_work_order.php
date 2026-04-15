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
        <th colspan="2"><div align="left"><font size="2">Work Order Form</font></div></th>
        <th>&nbsp;</th>
        <th><div align="left"></div></th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th colspan="2"><div align="left"></div></th>
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
      <tr>
        <th height="36">Order No:</th>
        <th colspan="2" align="center"><?php echo $s->wo_no ?></th>
        </tr>
      <tr>
        <th width="30%" height="36"><font size="-2">Date :</font><br>
          <?php echo tglIndoSedang($s->date_open_wo) ?></br></th>
        <th width="30%" align="center"><font size="-2">Customer No.</font><br>
          <?php echo $s->customer ?></br></th>
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
          <div class="text2"><?php echo $s->wo_no ?></div></th>
          <th><div class="text1"><font size="-2">Vin No</font></div>
            <div class="text2"><?php echo $s->vin ?></div></th>
          <th><div class="text1"><font size="-2">Sales designation</font></div>
            <div class="text2"></div></th>
          <th><div class="text1"><font size="-2">Reception date/time</font></div>
            <div class="text2"><?php echo $s->date_open_wo ?></div></th>
        </tr>
        <tr>
          <th><div class="text1"><font size="-2">Mileage/Km</font></div>
            <div class="text2"></div></th>
          <th><div class="text1"><font size="-2">Engine No.</font></div>
            <div class="text2"></div></th>
          <th><div class="text1"><font size="-2">Active reception</font></div>
            <div class="text2"></div></th>
          <th><div class="text1"><font size="-2">Received By</font></div>
            <div class="text2"><?php echo $s->sa_name ?></div></th>
        </tr>
        <tr>
          <th><div class="text1"><font size="-2">Routing No.</font></div>
            <div class="text2"></div></th>
          <th><div class="text1"><font size="-2">Las Service date/Millage/km</font></div>
            <div class="text2"></div></th>
          <th><div class="text1"><font size="-2">Date of 1st registration</font></div>
            <div class="text2"><?php echo $s->date_open_wo ?></div></th>
          <th>&nbsp;</th>
        </tr>
        </table>
    Is this vehicle related to campaign □ No □ Yes Mobility Service □ No □ Yes<br>
    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="datatable3">
      <thead>
        <tr>
          <th width="9%" height="31"><div align="center">Item</div></th>
          <th width="20%"><div align="center">Operation No.</div></th>
          <th ><strong>Hours</strong></th>
          <th width="63%">Type Of Work</th>
          </tr>
        <?php
        if(!empty($detailKet)){
        foreach ($detailKet as $d) { 
            $no=1;
          ?>
          <tr>
            <td><div align="center"><?php echo $no ?></div></td>
            <td><div align="center"><?php echo $d->operation; ?>&nbsp;</div></td>
            <td width="8%"><div align="center"><?php echo $d->hours; ?></div></td>
            <td width="63%"><?php echo $d->type_of_work ?>&nbsp;</td>
            </tr>
          
        <?php $no + 1;
        }} ?>
          <tr>
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
            </tr>
          <tr>
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
            </tr>
          <?php
        foreach ($detailKet as $c) :    ?>
          <tr>
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
            </tr>
      </thead>

  </table>
<table width="100%" border="0" cellpadding="5" cellspacing="0" style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
        <tr>
          <td>Ketentuan :</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="38%" align="justify">1. Lembar Work order yang sudah ditandatangai merupakan surat kuasa dari pelanggan ke dealer untuk dilakukan pekerjaan sesuai yang tertera di work order, serta memberi wewenang untuk melakukan uji coba kendaraan di luar bengkel.<br>
            2. Kendaraan yang sudah selesai diperbaiki wajib diambil oleh pelanggan dalam waktu ...x 24 jam dari waktu pemberitahuan. Apabila melebihi dari waktu tersebut, Dealer tidak bertanggung jawab lagi atas kondisi kendaraan tersebut dan dikenakan biaya sebesar<br>
            Rp..................../hari.</td>
          <td width="39%" align="justify">            3. Dealer tidak bertanggung jawab atas segala kerugian akibat musibah, bencana alam<br>
            ataupun kejadian lain yang tidak dipredikasi atau di luar tanggung jawab dealer.<br>
            4. Tidak diperkenankan menyimpan barang barang berharga didalam kendaraan. Dealer<br>
            tidak bertanggung jawab apabila terjadi kehilangan atas barang barang tersebut.<br>
            5. Hal lain diluar ketentuan ini akan diatur tersendiri oleh masing-masing dealer</td>
          </tr>
      </table>
<table width="100%" padding="5" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
    </table>
      <table width="100%" border="0" cellpadding="5" cellspacing="0" style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
        <tr>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
          <td width="25%">&nbsp;</td>
        </tr>
        <tr align="center">
          <td>Customer's signature &amp; name</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>Service Adviser's signature and name</td>
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