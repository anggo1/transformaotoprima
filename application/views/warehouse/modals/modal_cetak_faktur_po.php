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
      visibility: hidden;
    }

    #printSection,
    #printSection * {
      visibility: visible;
    }

    #printSection {
      position: absolute;
      left: 0;
      top: 0;
    }
  }


  p,
  td,
  th {
    font: 2 Verdana, Arial, Helvetica, sans-serif;

  }

  .datatable2 {
    border-collapse: collapse;
    font: bold;
  }

  .datatable td {
    padding: 1px 1px 5px 5px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
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
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px;
    font: bold;
  }

  .datatable3 th {
    border: 1px solid #000;
    font-display: block;
    align-content: center;
    font-weight: bolder;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px;
    text-align: center;
  }

  .table-atas {
    border-collapse: collapse;
    font: bold;
    float: right;
  }

  .table-atas td {
    padding: 1px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font: bold;
  }

  .table-atas th {
    border: 2px solid #000;
    font: bold;
    font-weight: normal;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
  }

  .text1 {
    font: bold;
    font-weight: normal;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 8px;
    padding: 1px 1px 1px 1px;
    padding-bottom: 0px;
    width: auto;
  }

  .text2 {
    font: bold;
    font-weight: bold;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 11px;
    align-content: center;
    text-align: center;
    padding: 1px 1px 1px 1px;
    width: auto;
  }

  .under {
    text-decoration: underline;
  }

  #A4 {
    background-color: #FFFFFF;
    left: 1px;
    right: 1px;
    height: 5.51in;
    /*Ukuran Panjang Kertas */
    width: 8.50in;
    /*Ukuran Lebar Kertas */
    margin: 1px solid #FFFFFF;

    font-family: Georgia, "Times New Roman", Times, serif;
  }

  .dbl-border {
    border: 3px double black;
    padding: 10px, 10px, 10px, 10px;
  }
  .kotak-timbul {
  width: 15px;
  height: 15px;
  margin-left: 15px;
  padding: 5px;
  background-color: #ffffff;
  border: 2px solid #000000;
}
</style>

<div id="printThis">

  <div class="modal-body">
    <tbody>
      <tr>
        <td width="100%" style="padding: 15px;">
          <?php
          if (!empty($dataKeluar)) {
            foreach ($dataKeluar as $c)
              if (!empty($dataSa)) {
                foreach ($dataSa as $s) { {
                  }
                }
              }
          }
          $lokasi = $this->session->userdata['lokasi'];
          $apl1 = $this->db->get("aplikasi where lokasi='" . $lokasi . "'")->row();
          $tgl_sekarang = date("Y-m-d");
          ?>
          <table width="100%" border="0" cellpadding="5" cellspacing="0">
            <tr align="center">
              <th width="99%" align="center">
                <img src="<?php echo base_url(); ?>assets\dist\img\logo_mercedes.png" width="10%">
              </th>
            </tr>

          </table>
          <table width="100%" border="0" cellpadding="1" style="font-size: 14px;" cellspacing="0" class="datatable1">
            <thead>
              <tr>
                <th colspan="2">
                  <font size="4">INVOICE</font>
                </th>
                <th>&nbsp;</th>
                <th>
                  <div align="left"></div>
                </th>
                <th>&nbsp;</th>
              </tr>
              <tr>
                <th colspan="2">
                  <div align="left"></div>
                </th>
                <th>&nbsp;</th>
                <th>
                  <div align="left"><?php echo  $apl1->nama_owner; ?></div>
                </th>
                <th>&nbsp;</th>
              </tr>
              <tr>
                <th>
                  <div align="left">Customer Name</div>
                </th>
                <th>
                  <div align="left">: <?php echo $c->nama_cus; ?></div>
                </th>
                <th width="198">&nbsp;</th>
                <th width="273">
                  <div align="left"><?php echo  $apl1->status; ?></div>
                </th>
                <th width="221">&nbsp;</th>
              </tr>
              <tr>
                <th>
                  <div align="left">Address</div>
                </th>
                <th>
                  <div align="left">: <?php echo $c->alamat ?></div>
                </th>
                <th>&nbsp;</th>
                <th>
                  <div align="left"></div>
                </th>
                <th>&nbsp;</th>
              </tr>
              <tr>
                <th height="20">
                  <div align="left">City</div>
                </th>
                <th height="20">
                  <div align="left">: <?php echo $c->kota ?></div>
                </th>
                <th>&nbsp;</th>
                <th>
                  <div align="left"></div>
                </th>
                <th>&nbsp;</th>
              </tr>
              <tr>
                <th height="20">
                  <div align="left">Up</div>
                </th>
                <th height="20">
                  <div align="left">: <?php echo $c->tlp_person ?></div>
                </th>
                <th>&nbsp;</th>
                <th>
                  <div align="left"></div>
                </th>
                <th>&nbsp;</th>
              </tr>
              <tr>
                <th height="20">
                  <div align="left">Telp or Mobile no</div>
                </th>
                <th height="20">
                  <div align="left">: <?php echo $c->no_tlp ?></div>
                </th>
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
                <th width="179" height="19">&nbsp;</th>
                <th width="467">&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
            </thead>

          </table>
          <table width="60%" border="1" cellpadding="1" style="font-size: 14px;" cellspacing="0" align="right">
            <thead>
              <tr align="center">
                <th colspan="2">For Reference please quote the following no</th>
              </tr>
              <tr>
                <th height="36">Order No:</th>
                <th align="center"><?php echo $c->kode_pesan ?></th>
              </tr>
              <tr>
                <th width="30%" height="36"><font size="1">Date</font>:</th>
                <th align="center"><font size="-2">Date :</font><br>
                <?php echo tglIndoSedang($c->tgl_po) ?> </th>
              </tr>
            </thead>

          </table>
          <p>&nbsp; </p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000" class="datatable"  id="table-1" >
   <thead>
     <tr>
       <th width="4%" align="center">No</th>
       <th width="14%">Part No</th>
       <th width="28%">Part Name</th>
       <th width="13%">Unit</th>
       <th width="15%">Price</th>
       <th width="4%">Qty</th>
       <th width="22%">Total</th>
       </tr>
     <?php
       $no=0; $grandtotal=0;
       foreach ($detailKeluar as $x): 
        $grandtotal += $x->harga * $x->jumlah;
								  
	     $no++;
						?>
      <tr>
        <td align="center"><?php echo $no ?></td>
        <td><?php echo $x->no_part ?></td>
        <td><?php echo $x->nama_part ?></td>
        <td><?php echo $x->satuan ?></td>
        <td align="right"><?php echo number_format($x->harga) ?></td>
        <td align="center"><?php echo $x->jumlah ?></td>
        <td align="right"><?php echo number_format($x->harga * $x->jumlah) ?></td>
      </tr>
      <?php  endforeach ?>
      <tr>
        <td colspan="6" align="right">Grand Total</td>
        <td align="right"><?php echo number_format($grandtotal) ?></td>
      </tr>
     
   </thead>
   <tbody>
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
              <td>Hormat Kami</td>
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
          <img src="<?php echo base_url(); ?>assets\foto\logo\<?php echo  $apl1->footer; ?>" width="100%">

  </div>



</div>
<div class="modal-footer justify-content-between">
  <button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
  <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>