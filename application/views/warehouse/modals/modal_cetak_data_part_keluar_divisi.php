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
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.table-cetak th {
    border: 1px solid #000;
    font: bold;
    font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
}
</style>
<div class="modal-body">
<div id="printThis">
        <div class="card-body">
            <div class="col-12 ">
                <H4>Report Barang Keluar Perdivisi</H4>
                <div class="table-responsive">
                    <table class="table table-cetak" id="list-pk">
                        <thead>
                            <tr>
                                <th width='5%'>No</th>
                                <th width="15%">Tgl Keluar</th>
                                <th width="11%">No Bon</th>
                                <th width="18%">Tujuan</th>
                                <th width="38%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$no = 1;
foreach ($dataKeluar as $s) {
?> <tr>

                                <td><?php echo $no; ?></td>
                                <td><?php echo tglIndoSedang($s->tgl_keluar); ?></td>
                                <td><?php echo $s->id_keluar; ?></td>
                                <td><?php echo $s->tujuan; ?></td>
                                <td><?php echo $s->keterangan; ?></td>
                            </tr>
                            <?php
    $no++;
}
?>

                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
<div class="card-footer justify-content-between">
    <button type="button" id="btnPrint" class="btn btn-success float-right"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K
    </button>
    <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>
    <script>
    var MyTable = $('#list-pk').DataTable({
        "responsive": false,
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false
    });
    </script>