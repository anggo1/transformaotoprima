    <div class="alert bg-white"><?php foreach ($dataPk as $k){}?>
        <table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000"
            style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;">
            <thead>
                <tr>
                    <th colspan="4">
                        <div align="left">
                            <table width="100%">
                                <tbody>
                                    <tr>
                                        <td colspan="5">
                                            <font size="+3">DAFTAR PEKERJAAN</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>ID Laporan</td>
                                        <td>: <?php echo $k->id_lapor ?></td>
                                        <td>No Body</td>
                                        <td>: <?php echo $k->no_body ?></td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Tgl Masuk</td>
                                        <td width="49%">: <?php echo tglIndoPanjang($k->tgl_masuk) ?></td>
                                        <td width="15%">No Pol</td>
                                        <td width="21%">: <?php echo $k->no_pol ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                </tr>
            </thead>
            <tbody>


            </tbody>
        </table>

        <div class="table-responsive">
            <table class="table table-bordered table-hover nowrap" id="list-pk-aktif">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No PK</th>
                        <th>Pekerjaan</th>
                        <th>Keterangan PK</th>
                        <th colspan="2">Pemborong</th>
                        <th>Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
       $no=0;
		$grand_total=0;
       foreach ($dataPk as $d){ 
		   $grand_total += $d->biaya_borong;
	     $no++;
						?>
                    <tr onclick="selectPk('<?php echo $d->no_body; ?>','<?php echo $d->id_pk; ?>','<?php echo $d->ket_pk; ?>','<?php echo $d->pt_pemborong; ?>','<?php echo $d->pj_borong; ?>','<?php echo $d->biaya_borong; ?>')">
                        <th><?php echo $no ?></th>
                        <th><?php echo $d->id_pk ?></th>
                        <th><?php echo $d->ket_pk ?></th>
                        <th><?php echo $d->keterangan ?></th>
                        <th><?php echo $d->pt_pemborong ?></th>
                        <th><?php echo $d->pj_borong ?></th>
                        <th style="text-align: right;font-weight: bold;"><?php echo number_format($d->biaya_borong); ?>
                        </th>
                    </tr>
                    <?php  } ?>
                    <tr>
                        <th colspan="6" style="text-align: right;font: bold;">GRAND TOTAL</th>
                        <th style="text-align: right;font: bold;font-weight: bold;">
                            <?php echo number_format($grand_total); ?></th>
                    </tr>
                    </tbody>
                                            <tfoot></tfoot>
            </table>
        </div>