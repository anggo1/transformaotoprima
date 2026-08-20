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
    border: 2px solid black; 
    padding: 1px 1px 5px 5px;
     margin-bottom: 30px;
}

th,
td {
    border: 2px solid #000;
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
                        <th colspan="2">&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>
                            <div align="left"></div>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <th colspan="2">&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>
                            <div align="left"><?php echo  $apl1->nama_owner; ?></div>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <th width="179">&nbsp;</th>
                        <th width="467">&nbsp;</th>
                        <th width="198">&nbsp;</th>
                        <th width="273">
                            <div align="left"><?php echo  $apl1->status; ?></div>
                        </th>
                        <th width="221">&nbsp;</th>
                    </tr>
                    <tr>
                        <th height="20">&nbsp;</th>
                        <th height="20">&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

            </table>
            <h4>JOB TIME CARD REPORT</h4>

            <table class="table-isi header">

                <tr>
                    <?php foreach ($dataJob as $row1){} ?>
                    <td width="50%">
                        <b>Mechanic Name <br>
                            <?= $row1->nik.' &nbsp; '.$row1->nama; ?>
                        </b><br>
                    </td>

                    <td>
                        <b>WIP Number</b><br>
                        <b>
                            <?= $row1->wo_no; ?>
                        </b>
                    </td>

                </tr>

            </table>

            <br>

            <i>This card must be punched together with the workshop order.</i>

            <br><br>

            <table class="table-isi" id="mytable" width="100%">

                <tr class="blue">

                    <th width="8%">CIW/GW</th>

                    <th width="35%">
                        Operational Code<br>
                        Description
                    </th>

                    <th width="5%"></th>

                    <th width="18%">Time Recording</th>

                    <th width="15%">Date</th>

                    <th width="15%">Total Time</th>

                </tr>

                <?php 
          $lokasi = $this->session->userdata['lokasi'];
          $apl1 = $this->db->get("aplikasi where lokasi='" . $lokasi . "'")->row();
          $tgl_sekarang = date("Y-m-d");
    $grand_total = 0; 
    foreach ($dataJob as $row): ?>

                <?php

            $start = strtotime($row->start_date);
            $end   = strtotime($row->end_date);

            $total = ($end - $start) / 3600;
    $grand_total += $total; 
    
        $ciw='';
        $status_service=$row->status_service;
        if($status_service == 'C'){
            $ciw = 'CASH';
        }if($status_service == 'I'){
            $ciw = 'INTERNAL';
        }if($status_service == 'W'){
            $ciw = 'WARRANTY';
        }if($status_service == 'GW'){
            $ciw = 'GoodWill';
        }

            ?>

                <tr>

                    <td class="center">

                        <b><?= $row->status_service; ?></b>
                    </td>

                    <td>

                        <b><?= $row->operation; ?></b>

                        <br>

                        <?= $row->type_of_work; ?>

                    </td>

                    <td class="center">

                        S

                        <br><br>

                        E

                    </td>

                    <td>

                        <?= date('H:i', strtotime($row->start_date)); ?>

                        <br><br>

                        <?= date('H:i', strtotime($row->end_date)); ?>

                    </td>

                    <td class="center">

                        <?= date('d/m/Y', strtotime($row->start_date)); ?>
                        <br><br>
                        <?= date('d/m/Y', strtotime($row->end_date)); ?>

                    </td>

                    <td class="center">

                        <?php // echo number_format($total,2); 
                    ?>
                        <?= number_format($row->total_time, 2); ?>

                    </td>

                </tr>

                <?php endforeach; ?>

            </table>
            <table cellspacing="0" cellpadding="0" class="table-isi" id="mytable">
                <col width="53">
                <col width="242">
                <col width="14">
                <col width="22">
                <col width="28">
                <col width="31">
                <col width="44">
                <col width="33">
                <col width="44">
                <col width="38">
                <col width="43">
                <col width="44">
                <col width="120">
                <tr>
                    <td bgcolor="#d8e4f2"width="53">Hours</td>
                    <td bgcolor="#EFA67B" width="242">&nbsp;</td>
                    <td bgcolor="#EFA67B" colspan="2" width="36">C</td>
                    <td bgcolor="#EFA67B" width="28">I</td>
                    <td bgcolor="#EFA67B" width="31">W/G</td>
                    <td bgcolor="#EFA67B" width="44">W1</td>
                    <td bgcolor="#EFA67B" width="33">W2</td>
                    <td bgcolor="#EFA67B" width="44">W3</td>
                    <td bgcolor="#EFA67B" width="38">W4</td>
                    <td bgcolor="#EFA67B" width="43">W5</td>
                    <td bgcolor="#EFA67B" width="44">W6</td>
                    <td bgcolor="#EFA67B" width="120">W…</td>
                </tr>
                <tr>
                    <td bgcolor="#d8e4f2" width="53">Total</td>
                    <td width="242">&nbsp;</td>
                    <td colspan="2" width="36">&nbsp;</td>
                    <td width="28">&nbsp;</td>
                    <td width="31">&nbsp;</td>
                    <td width="44">&nbsp;</td>
                    <td width="33">&nbsp;</td>
                    <td width="44">&nbsp;</td>
                    <td width="38">&nbsp;</td>
                    <td width="43">&nbsp;</td>
                    <td width="44">&nbsp;</td>
                    <td width="120"><?=number_format($grand_total, 2);?></td>
                </tr>
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