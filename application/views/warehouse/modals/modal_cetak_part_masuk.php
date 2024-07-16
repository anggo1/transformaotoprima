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

    @page {
      margin: 0;
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

    .datatable {
      border: 2px solid #000;
      border-collapse: collapse;
    }

    .datatable td {
      padding: 0px;
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 14px;
    }

    .datatable th {
      border: 2px solid #000;
      font: normal;
      font-weight: normal;
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 12px;
    }

    p,
    td,
    th {
      font: Verdana, Arial, Helvetica, sans-serif;

    }

    .datatable1 {
      border-collapse: collapse;
    }

    .datatable1 th,
    td {
      border: 0px;
      padding-top: 1px;
      padding-left: 1px;
      padding-right: 1px;
      padding-bottom: 1px font-family:Verdana, Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: normal;
      text-align: left;
        align-items: left;
        align-content: left;
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
    
</style>
<div id="printThis">
  <?php
	foreach ($dataMasuk as $k) {
  }
	$status=$k->status;
	$apl = $this->db->get("aplikasi where lokasi='".$this->session->userdata['lokasi']."'")->row();

	?>

<div class="modal-body">
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
    <thead>
      <tr>
        <th style="text-align: center;"><h4>BARANG MASUK</h4></th>
      </tr>
    </thead>

  </table>
  <table width="100%" border="0" cellpadding="2" style="font-size: 14px; text-align: left;" cellspacing="0" class="datatable1">
    <thead>
      <tr>
        <th width="429"><?php  echo $apl->nama_owner; ?></th>
        <th width="132">Kode</th>
        <th width="250">: <?php echo $k->id_masuk; ?></th>
        <th width="110">Kode Supplier</th>
        <th width="9">: </th>
        <th width="327"><?php echo $k->kode_sup; ?></th>
      </tr>
      <tr>
        <th rowspan="4" align="left" style="vertical-align: top;
  text-align: left;"><?php  echo $apl->alamat; ?></th>
        <th>Tanggal</th>
        <th>: <?php echo tglIndoPanjang($k->tgl_masuk); ?></th>
        <th>Nama Supplier</th>
        <th>: </th>
        <th><?php echo $k->nama_sup ?></th>
      </tr>
      <tr>
        <th>Kode PO</th>
        <th>: <?php echo $k->no_ponye; ?></th>
        <th>Alamat</th>
        <th>: </th>
        <th rowspan="2" style="align-items: top; align-content: flex-start;vertical-align: text-top;"><?php echo $k->alamat ?></th>
      </tr>
      <tr>
        <th>No. SJ Supplier</th>
        <th>: <?php echo $k->no_sj_sup; ?></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr>
        <th>No. Inv Supplier</th>
        <th>: <?php echo $k->no_inv_sup; ?></th>
        <th>No. Telp</th>
        <th>:</th>
        <th><?php echo $k->no_tlp ?></th>
      </tr>
    </thead>

  </table>

  <table>

    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="datatable2">
      <thead>
        <tr>
          <th width="5%" rowspan="2" style="text-align: center;">No</th>
          <th width="9%" rowspan="2" style="text-align: center;">Kode</th>
          <th width="25%" rowspan="2" style="text-align: center;">Nama Barang</th>
          <th width="7%" rowspan="2" style="text-align: center;">Keterangan</th>
          <th width="6%" rowspan="2" style="text-align: center;">Jumlah</th>
          <th width="6%" rowspan="2" style="text-align: center;">Satuan</th>
          <th width="6%" rowspan="2" style="text-align: center;">Harga</th>
          <th width="10%" rowspan="2" style="text-align: center;">Total</th>
          <th colspan="3" style="text-align: center;">Stok</th>
        </tr>
          <tr>
            <th width="5%" style="text-align: center;">Awal</th>
            <th width="5%" style="text-align: center;">Masuk</th>
            <th width="5%" style="text-align: center;">Akhir</th>
          </tr>
        <?php
        $no = 0;
        $total = 0;
        foreach ($detailMasuk as $d) : $no++;
          $total += $d->harga_baru * $d->jumlah;
        ?>
          <tr>
            <th style="text-align: center;"><?php echo $no ?></th>
            <th style="text-align: center;"><?php echo $d->no_part ?></th>
            <th><?php echo $d->nama_part ?></th>
            <th><?php echo $d->ket?></th>
            <th style="text-align: right;"><?php echo $d->jumlah ?></th>
            <th style="text-align: right;"><?php echo $d->nama_satuan ?></th>
            <th style="text-align: right;"><?php echo number_format($d->harga_baru) ?></th>
            <th style="text-align: right;"><?php echo number_format($d->harga_baru * $d->jumlah) ?></th>
            <th style="text-align: center;"><?php echo $d->stok-$d->jumlah ?></th>
            <th style="text-align: center;"><?php echo $d->jumlah ?></th>
            <th style="text-align: center;"><?php echo $d->stok ?></th>
          </tr>
        <?php $no + 1;
        endforeach ?>
        <tr>
          <th colspan="7" style="text-align: right;">Grand Total</th>
          <th style="text-align: right;"><?php echo number_format($total) ?></th>
          <th colspan="3" align="right">&nbsp;</th>
        </tr>
      </thead>

    </table>
    <table>
      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
        <thead>
          <tr>
            <th width="20%" style="text-align: center; align-content: center;">&nbsp;</th>
            <th width="20%" style="text-align: center; align-content: center;">&nbsp;</th>
            <th width="20%" style="text-align: center; align-content: center;">&nbsp;</th>
            <th width="25%">Petugas</th>
          </tr>
          <tr>
            <th style="text-align: center; align-content: center;">&nbsp;</th>
            <th style="text-align: center; align-content: center;">&nbsp;</th>
            <th height="60" style="text-align: center; align-content: center;">
              <p>&nbsp;</p>
            </th>
            <th>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p><?php echo $k->user ?></p>
            </th>
          </tr>
        </thead>

      </table>

</div>
</div>
<div class="card-footer">
<button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K </button>
<button class="btn btn-danger" id="tutup" onClick="window.location.assign(" <?php echo base_url(); ?>/Transaksi/Pengiriman");" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>