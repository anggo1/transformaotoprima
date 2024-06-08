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
    <div class="card-footer justify-content-between">
        <button type="button" id="btnPrint" class="btn btn-success float-right"><span
                class="fa fa-print"></span>&nbsp;&nbsp; C E T A K
        </button>
        <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U
            T U P</button>
    </div>
    <div id="printThis">
    <div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-data">
            <thead>
            <tr>
                    <th width='5%'>No</th>
                    <th>No SPK</th>
                    <th>No Body</th>
                    <th>Tgl Masuk</th>
                    <th>Kategori</th>
                    <th>Keterangan Masuk</th>
                    <th>Pool</th>
                    <th>Status</th>
                    <th>Biaya</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$grand_total=0;
foreach ($dataPk as $s) {
    $grand_total += $s->total_harga;
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->id_lapor; ?></td>
                    <td><?php echo $s->no_body; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_masuk); ?></td>
                    <td><?php echo $s->nama_kategori; ?></td>
                    <td><?php echo $s->keterangan; ?></td>
                    <td><?php echo $s->nama_pool; ?></td>
                    <td><?php if($s->status=="Y") {echo "Proses";}if($s->status=="P") {echo "Pending";}if($s->status=="S") {echo "Selesai";}if($s->status=="K") {echo "Keluar";} if($s->status=="N") {echo "Antrian";}?>
                    </td>
                    <td align="right"><?php echo number_format($s->total_harga); ?></td>
                </tr>
                <?php
    $no++;
}
?>
            </tbody>
            <tfoot>
                <th  colspan="8" style="text-align: right; font-size:larger;">GRAND TOTAL</th>
                <th style="text-align: right; font-size:larger;"><?php echo number_format($grand_total) ?></th>
                </tfoot>
        </table>
    </div>
</div>
</div>
</div>