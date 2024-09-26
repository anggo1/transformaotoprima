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

.datatable td,
th {
    padding: 0px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font: bold;
}

.datatable1 th {
  padding: 0px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px;
    font: bold;
    text-align: center;
    border-top: 1px solid #000;
    border-bottom: double;
    height: 10px;
}

.datatable2 th {
    border: 0px solid #000;
    height: 10px;
}

.datatable3 {
    border-collapse: collapse;
    font: bold;
}

.datatable3 td {
    padding: 2px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 8px;
    font: bold;
}

.datatable3 th {
    border: 1px solid #000;
    font-display: block;
    align-content: center;
    font-weight: bolder;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: center;
}

.table-atas {
    border-collapse: collapse;
    font: bold;
    float: right;
}

.table-atas td {
    padding: 0px;
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
                                <img src="<?php echo base_url(); ?>assets\dist\img\logo_mercedes.png" width="25%">
                            </th>
                        </tr>

                    </table>
                    <table width="100%" border="0" cellpadding="1" style="font-size: 14px;" cellspacing="0"
                        class="datatable">
                        <thead>
                            <tr>
                                <th colspan="2">
                                    <div align="left"></div>
                                </th>
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
                                <th width="185">&nbsp;</th>
                                <th width="489">&nbsp;</th>
                                <th width="138">&nbsp;</th>
                                <th width="513">
                                    <div align="left"><?php echo  $apl1->status; ?></div>
                                </th>
                                <th width="71">&nbsp;</th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>
                                    <div align="left"><?php echo  $apl1->alamat; ?></div>
                                </th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <th height="20">&nbsp;</th>
                                <th height="20">&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>
                                    <div align="left"><?php echo  $apl1->kota.' '.$apl1->kode_pos; ?> Indonesia</div>
                                </th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <th height="20">&nbsp;</th>
                                <th height="20">&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>
                                    <div align="left"><?php echo  $apl1->tlp; ?></div>
                                </th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                    </table>

                    <table width="100%" cellpadding="1" cellspacing="0" class="datatable1">
                        <tr>
                            <th height="37">SURAT PESANAN KENDARAAN</th>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="1" cellspacing="0" class="datatable2">
                        <tr>
                            <th width="13%" height="37">
                                <font size="-2">Nama Perusahaan</font>
                            </th>
                            <th width="42%">
                                <div class="text1">
                                    <font size="-2">Vin No</font>
                                </div>
                                <div class="text2"></div>
                            </th>
                            <th width="13%">
                                <font size="-2">Nama BPKB/STNK</font>
                            </th>
                            <th width="32%">
                                <div class="text1">
                                    <font size="-2">Date / time received</font>
                                </div>
                                <div class="text2"></div>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">Alamat</font>
                            </th>
                            <th>
                                <div class="text1">
                                    <font size="-2">Engine No.</font>
                                </div>
                                <div class="text2"></div>
                            </th>
                            <th>
                                <font size="-2">No KTP/No TDP</font>
                            </th>
                            <th>
                                <div class="text1">
                                    <font size="-2">Received By</font>
                                </div>
                                <div class="text2"></div>
                            </th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>
                                <font size="-2">Alamat</font>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">No Telepone/Fax</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">Faktur Pajak</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">No. NPWP</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>
                                <font size="-2">Plat Kendaraan</font>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">Alamat NPWP</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>
                                <font size="-2">Type Body</font>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">Contact Person</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <div class="text1">
                                    <font size="-2">No. Telepon/HP.</font>
                                </div>
                                <div class="text2"></div>
                            </th>
                            <th>
                                <div class="text1">
                                    <font size="-2">Las Service date/Millage/km</font>
                                </div>
                                <div class="text2"></div>
                            </th>
                            <th>
                                <div class="text1">
                                    <font size="-2">Date of 1st registration</font>
                                </div>
                                <div class="text2"></div>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                    </table>
                    <br>
                    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="datatable3">
                        <thead>
                            <tr>
                                <th width="3%" height="31">
                                    <div align="center">No</div>
                                </th>
                                <th width="11%">
                                    <div align="center">Part Number</div>
                                </th>
                                <th><strong>Description</strong></th>
                                <th width="8%">Price</th>
                                <th width="4%">
                                    <div align="center">Pcs</div>
                                </th>
                                <th width="7%">
                                    <div align="center">Amount</div>
                                </th>
                                <th width="14%">
                                    <div align="center">Remarks</div>
                                </th>
                            </tr>
                            <?php
        $no = 0;
        foreach ($dataPo as $d) : $no++;
          ?>
                            <tr>
                                <td>
                                    <div align="center"></div>
                                </td>
                                <td>
                                    <div align="center">&nbsp;</div>
                                </td>
                                <td width="53%">
                                    <div align="center"></div>
                                </td>
                                <td width="8%">
                                    <div align="right">&nbsp;</div>
                                </td>
                                <td width="4%">
                                    <div align="center">&nbsp;</div>
                                </td>
                                <td>
                                    <div align="right"></div>
                                </td>
                                <td>
                                    <div align="center"></div>
                                </td>
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
                                <td>
                                    <font size="-2">** </font>
                                </td>
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
    <button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K
    </button>
    <button class="btn btn-danger" id="tutup" onClick="window.location.assign("
        <?php echo base_url(); ?>/Transaksi/Pengiriman");" data-dismiss="modal"><span
            class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>