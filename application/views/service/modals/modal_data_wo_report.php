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

    .footer-image-container {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
    }

    .footer-image-container img {
        width: 100%;
        /* Gambar akan melebar memenuhi lebar halaman */
        height: auto;
        /* Menjaga proporsi gambar */
        display: block;
    }
}

body {
    font-family: Arial;
    font-size: 12px;
}

.table-isi {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid black; 
    padding: 1px 1px 5px 5px;
     margin-bottom: 30px;
}

th,
td {
    border: 1px solid #000;
    padding: 4px;
}

.header td {
    height: 45px;
}

.blue {
    background: #d8e4f2;
    font-weight: bold;
    text-align: center;
    border: 2px;
    border-top: #000 2px;
}

.center {
    text-align: center;
}

.tableCetak th{
    padding: 1px 1px 5px 5px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font: bold;
    border: none;
}
.datatable th {
    
    padding: 1px 1px 5px 5px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font: bold;
    border: 2px;
}
    
    .tabel-spesifikasi {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    /* Membuat garis kotak paling luar (kiri, kanan, atas, bawah) */
    border: 1.5px solid #000000; 
     margin-bottom: 30px;
}

/* Mengatur seluruh baris dan sel di dalam tabel */
.tabel-spesifikasi th, 
.tabel-spesifikasi td {
    /* Menghapus garis vertikal (kiri & kanan) di dalam tabel */
    border-left: none;
    border-right: none;
    
    /* Membuat garis horizontal pemisah antar baris */
    border-top: 1px solid #000000;
    border-bottom: 1px solid #000000;
    
    padding: 8px 10px;
    font-size: 11pt;
    text-align: left;
    vertical-align: middle;
}

/* Khusus untuk baris judul "Supporting Document" agar teksnya tebal */
.tabel-spesifikasi .row-judul td {
    font-weight: bold;
    font-size: 12pt;
}

/* Kolom kiri untuk label teks */
.tabel-spesifikasi td.label-text {
    font-size: 10.5pt;
    border: 0px;
}

/* Kolom kanan untuk data/isi */
.tabel-spesifikasi td.value-text {
}

</style>

</head>
<div id="printThis">

    <div class="modal-body">

        <body>
            <?php 
    
          $lokasi = $this->session->userdata['lokasi'];
          $apl1 = $this->db->get("aplikasi where lokasi='" . $lokasi . "'")->row();
          $tgl_sekarang = date("Y-m-d");
    ?>
            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableCetak" id="tableCetak"> 
                <tr align="center">
                    <th width="99%" align="center">
                        <img src="<?php echo base_url(); ?>assets\dist\img\logo_mercedes.png" width="10%">
                    </th>
                </tr>

            </table>
        <table width="100%" border="0" cellpadding="1" style="font-size: 14px;" cellspacing="0"
                class="tableCetak">
                <thead>
                    <tr>
                        <th width="1159">&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>
                            <div align="left"></div>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>
                            <div align="left"><?php echo  $apl1->nama_owner; ?></div>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <th>Workshop Report</th>
                        <th width="131">&nbsp;</th>
                        <th width="461">
                            <div align="left"><?php echo  $apl1->status; ?></div>
                        </th>
                        <th width="112">&nbsp;</th>
                    </tr>
                    <tr>
                        <th height="20">&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

            </table><?php foreach ($dataJob as $row1){} ?>
            <table border="1" cellspacing="0" cellpadding="0" class="tabel-spesifikasi" width="100%">
  <tr>
    <td width="221" class="label-text">Date:</td>
    <td width="743"><?= tglIndoSedang($row1->date_open_wo) ;?></td>
    <td width="21" rowspan="4" style="border-right: 1.5px solid #000000;">&nbsp;</td>
    <td class="label-text">Customer    Name:</td>
    <td colspan="4"><?= $row1->customer_name ;?></td>
    </tr>
  <tr>
    <td width="221" class="label-text">WIP    Number:</td>
    <td><?= $row1->wo_no ;?></td>
    <td width="234" class="label-text">Reg.    Number:</td>
    <td width="212"><?= $row1->no_pol ;?></td>
    <td width="158" class="label-text">Reg    Date:</td>
    <td width="264"><?= tglIndoSedang($row1->date_open_wo) ;?></td>
  </tr>
  <tr>
    <td width="221" class="label-text">Chassis    Number:</td>
    <td><?= $row1->vin ;?></td>
    <td width="234" class="label-text">Type</td>
    <td width="212"><?= $row1->type ;?></td>
    <td width="158" class="label-text">Mileage:</td>
    <td width="264"><?= $row1->mileage ;?></td>
  </tr>
  <tr>
    <td width="221" height="36" class="label-text">Engine    Number:</td>
    <td><?= $row1->engine_no ;?></td>
    <td width="234" class="label-text"></td>
    <td width="212">&nbsp;</td>
    <td colspan="2" class="label-text">&nbsp;</td>
  </tr>
  
</table>
            <table border="1" cellspacing="0" cellpadding="0" class="table-isi" width="658">
  <tr>
    <td width="658" colspan="10"><strong>Costumer Complaint</strong><strong> </strong></td>
  </tr>
  <tr>
    <td width="658" height="57" colspan="10" valign="top"><p>
      <?= $row1->customer_complain ;?>
    </p></td>
  </tr>
  
</table>
  <table class="tabel-spesifikasi">
    <!-- Baris Judul Utama -->
    <tr class="row-judul">
        <td colspan="2">Supporting Document</td>
    </tr>
    
    <!-- Baris Data 1 -->
    <tr>
      <td width="17%" class="label-text">TIPS Document Number:</td>
      <td width="83%" class="value-text">&nbsp;</td> 
        <!-- Isi dengan variabel PHP Anda -->
    </tr>

    <!-- Baris Data 2 -->
    <tr>
      <td class="label-text">WIS Document Number:</td>
      <td class="value-text">&nbsp;</td>
    </tr>

    <!-- Baris Data 3 -->
    <tr>
      <td class="label-text">SD Media:</td>
      <td class="value-text">&nbsp;</td>
    </tr>

    <!-- Baris Data 4 -->
    <tr>
      <td class="label-text">Spare Parts Number:</td>
      <td class="value-text">&nbsp;</td>
    </tr>
</table>

        <table border="1" cellspacing="0" cellpadding="0" class="table-isi" width="658">
  <tr>
    <td colspan="10"><strong>Workshop Findings</strong><strong> </strong></td>
  </tr>
  <tr>
    <td width="206">&nbsp;</td>
    <td width="130">Ada</td>
    <td width="158">Tidak    Ada</td>
    <td width="724" colspan="4" rowspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="206">Photo    (# Lembar)</td>
    <td width="130">&nbsp;</td>
    <td width="158">&nbsp;</td>
    </tr>
  <tr>
    <td width="206">Video    (Nama File)</td>
    <td width="130">&nbsp;</td>
    <td width="158">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="10" valign="top">Note:</td>
  </tr>     
</table>           
<table border="1" cellspacing="0" cellpadding="0" class="table-isi" width="100%">
  <tr>
    <td width="658" colspan="10"><strong>Rectification:</strong><strong> </strong></td>
  </tr>
  <tr>
    <td width="658" height="90" colspan="10" valign="top"><p>&nbsp;</p></td>
  </tr>
  
</table>
  
</table>
            <p><span style="padding: 15px;" class="footer-image-container"><img
                        src="<?php echo base_url(); ?>assets\foto\logo\<?php echo  $apl1->footer; ?>"
                        width="100%"></span></p>


    </div>



</div>
<div class="modal-footer justify-content-between">
    <button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K
    </button>
    <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U
        P</button>
</div>