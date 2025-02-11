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
    position: absolute;
    left:0;
    top:0;
    width: 100%;
  }
}


p, td, th {
    font:4 Verdana, Arial, Helvetica, sans-serif;
	
}
.datatable1 {
    border-collapse: collapse;
    font: normal;
}
.datatable1 td {
    padding: 0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:18px;
    font: normal;
}
.datatable1 th {
    border: 0px solid #000;
    font: normal;
    font-weight: normal;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:18px;
}
    .datatable2 {
    border-collapse: collapse;
    font: normal;
}
.datatable2 td {
    padding: 0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:18px;
    font: normal;
}
.datatable2 th {
    border: 2px solid #000;
    font: normal;
    font-weight: normal;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:18px;
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
<div class="modal-body">
<div id="printThis">
  <?php 
	foreach ($dataPart as $k) {
  }
	$status=$k->lokasi;
	$apl1 = $this->db->get("aplikasi where lokasi='".$this->session->userdata['lokasi']."'")->row();

	?>

  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable1">
    <thead>
      <tr>
        <th width="10%" align="right"><img src="<?php echo base_url(); ?>assets/foto/logo/<?php echo  $apl1->logo; ?>" width="105%" height="100">
          
        </th>
        <th width="90%" align="left"><h4><?php echo  $apl1->nama_owner; ?></h4>
          <h5><?php echo  $apl1->alamat; ?></h5></th>
      </tr>
      <tr>
        <th colspan="2"><h4>SURAT JALAN</h4></th>
      </tr>
    </thead>

  </table>
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable1">
    <thead>
      <tr>
        <th width="169"><div align="left">Tanggal</div></th>
        <th width="652"><div align="left">: <?php echo tglIndoSedang($k->tgl_keluar) ?></div></th>
        <th width="114">&nbsp;</th>
        <th width="234"><div align="left">Kepada</div></th>
        <th width="652"><div align="left">: <?php echo $k->tujuan; ?></div></th>
      </tr>
      <tr>
        <th><div align="left">No Surat</div></th>
        <th><div align="left">: <?php echo $k->id_keluar; ?></div></th>
        <th>&nbsp;</th>
        <th><div align="left">Alamat</div></th>
        <th><div align="left">: <?php echo $k->alamat ?></div></th>
      </tr>
      <tr>
        <th><div align="left">No  PO</div></th>
        <th><div align="left">: <?php echo $k->no_po_cus ?></div></th>
        <th>&nbsp;</th>
        <th><div align="left">Keterangan</div></th>
        <th><div align="left">: <?php echo $k->keterangan ?></div></th>
      </tr>
      <tr>
        <th height="32">&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

  </table>

  <table>

    <table width="100%" border="1" cellpadding="5" cellspacing="0" class="datatable2">
      <thead>
        <tr>
          <th width="10%">No</th>
          <th width="20%">No Barang</th>
          <th width="50%">Nama Barang</th>
          <th width="10%">Jumlah</th>
          <th width="10%">Satuan</th>
        </tr>
        <?php
        $no = 0;
        $grand_total = 0;
        foreach ($detailPart as $d) : $no++;
          $grand_total += $d->jumlah * $d->harga_baru;
        ?>
          <tr>
            <th><?php echo $no ?></th>
            <th><?php echo $d->no_part ?></th>
            <th><?php echo $d->nama_part ?></th>
            <th><?php echo $d->jumlah ?></th>
            <th><?php echo $d->satuan ?></th>
          </tr>
        <?php $no + 1;
        endforeach ?>
      </thead>

    </table>
    <table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="background-color:#000 border: 1px solid #000; background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                      <td width="25%">&nbsp;</td>
                      <td width="25%"><?php echo date('d M Y')?></td>
                    </tr>
                    <tr>
                      <th>Penerima,</th>
                      <th>&nbsp;</th>
                      <th>&nbsp;</th>
                      <th>Dibuat</th>
                    </tr>
                    <tr>
                      <td height="60">
                      </td>
                      <td>
                      </td>
                      <td>
                    </td>
                      <td><p>&nbsp;</p> <p>&nbsp;</p>                       <?php echo $k->user ?></td>
                    </tr>
      </thead>
                <tbody>
</table>

</div>
</div>
<div class="card-footer">
<button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
  <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>