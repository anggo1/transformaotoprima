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
    body {
        visibility: hidden;
        padding-top: 5cm !important;
        padding-bottom: 5cm !important;
    }

    #printSection,
    #printSection * {
        visibility: visible;
    }

    #printSection {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    @page {
        size: auto;
        margin: 0mm;
    }

}

p,
td,
th {
    font: 2 Verdana, Arial, Helvetica, sans-serif;

}

.table-cetak {
    border-collapse: collapse;
    font: bold;
    padding: 2px;
}

.table-cetak td {
    border: 1px solid #000;
    padding: 2px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font: bold;
}

.table-cetak th {
    border: 1px solid #000;
    font: bold;
    font-weight: bold;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
}
</style>
<div class="modal-body">
    <div id="printThis">
    <div class="alert bg-white"><?php foreach ($dataPk as $k){}?>
        <table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#000000"
            style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;">
            <thead>
            <tbody>
                <tr>
                    <td colspan="6">
                        <font size="+2">DATA PEKERJAAN <?php echo $k->no_body ?></font>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td rowspan="7">
                        <!--<img src="<?php //base_url('./assets/img_qr/'.$k->id_pk .'.png') ?>" alt="<?php //echo $k->id_pk ?>" width="100" height="100">-->
                    </td>
                    <td>Pool</td>
                    <td>: <?php echo $k->nama_pool ?></td>
                </tr>
                <tr>
                    <td>ID Laporan</td>
                    <td>: <?php echo $k->id_lapor ?></td>
                    <td>Rute</td>
                    <td>: <?php echo $k->rute_aktif ?></td>
                </tr>
                <tr>
                    <td>Tgl Masuk</td>
                    <td>: <?php echo tglIndoPanjang($k->tgl_masuk) ?></td>
                    <td>No Body</td>
                    <td>: <?php echo $k->no_body ?></td>
                </tr>
                <tr>
                    <td>Nomor PK</td>
                    <td>: <?php echo $k->id_pk ?></td>
                    <td>No Pol</td>
                    <td>: <?php echo $k->no_pol ?></td>
                </tr>
                <tr>
                    <td>Kategori</td>
                    <td>: <?php echo $k->kategori ?></td>
                    <td>PK</td>
                    <td>: <?php echo $k->ket_pk ?></td>
                </tr>
                <tr>
                    <td width="15%">Type</td>
                    <td width="25%">: <?php echo $k->type ?></td>
                    <td width="15%">Seat</td>
                    <td width="21%">: <?php echo $k->kelas ?></td>
                </tr>
                <tr>
                    <td width="15%">&nbsp;</td>
                    <td width="25%">&nbsp;</td>
                    <td width="15%">Catatan/Keterangan</td>
                    <td width="21%">: <?php echo $k->keterangan ?></td>
                </tr>
                <tr>
                    <td width="15%"></td>
                    <td width="25%"></td>
                    <td width="15%"></td>
                    <td width="21%"></td>
                </tr>

            </tbody>
        </table>
                <div class="table-responsive">
                    <table width="100%" class="table table-cetak" id="list-data_detail">
                        <thead>
                            <tr>
                        <th>No</th>
                        <th>No PK</th>
                        <th>Kode PK</th>
                        <th>Jenis PK</th>
                            <th>No Ket</th>
                        <th>Keterangan</th>
                        <th>Pemborong</th>
                        <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($detailPk as $s){
						?>
                        <tr>

                                <td width="10%"><?php echo $s->row_urut; ?></td>
                                <td width="10%"><?php echo $s->id_pk; ?></td>
                                <td width="5%"><?php echo $s->jns_pk; ?></td>
                                <td width="15%"><?php echo $s->ket_pk; ?></td>
                                <td width="2%"><?php echo $s->row_no; ?></td>
                                <td width="25%"><?php echo $s->keterangan; ?></td>
                                <td width="10%"><?php echo $s->pt_pemborong; ?></td>
                                <td width="10%"><?php echo $s->pj_borong; ?></td>
                            </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
    <div class="card-footer justify-content-between">
        <button type="button" id="btnPrint" class="btn btn-success float-right"><span
                class="fa fa-print"></span>&nbsp;&nbsp; C E T A K
        </button>
        <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U
            T U P</button>
    </div>
<script>
$(document).ready( function () {
  
  var table = $('#list-data_detail').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "processing": false,
        "language": {
        "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
            "sPaginationType": "full_numbers",
            "sorting": [
                [0, 'asc']
            ],
            "data": table,
            "rowsGroup": [0,1,2,3]
        });
});
</script>