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
        <div class="card-body">
            <div class="col-12 ">
                <H4>Report Detail Barang Keluar Per Divisi</H4>
                <div class="table-responsive">
                    <table class="table table-cetak" id="list-data_detail">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Ref</th>
                                <th>Tgl Keluar</th>
                                <th>Divisi</th>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>QTY</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$no = 1;
foreach ($detailKeluar as $s) {
?> <tr>

                                <td width="2%" align="center"><?php echo $s->row_urut; ?></td>
                                <td><?php echo $s->id_keluar; ?></td>
                                <td><?php echo tglIndoSedang($s->tgl_keluar); ?></td>
                                <td width="2%"><?php echo $s->nama_divisi; ?></td>
                                <td width="2%"><?php echo $s->row_no; ?></td>
                                <td><?php echo $s->no_part; ?></td>
                                <td><?php echo $s->nama_part; ?></td>
                                <td><?php echo $s->jumlah; ?></td>
                                <td><?php echo $s->satuan; ?></td>
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
<script>
$(document).ready(function() {
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
        "rowsGroup": [0, 1, 2, 3],
        "columns": [{
                "data": "row_urut",
                sDefaultContent: ""
            },
            {
                "data": "row_no",
                sDefaultContent: ""
            },
            {
                "data": "id_keluar",
                sDefaultContent: ""
            },
            {
                "data": "nama_divisi",
                sDefaultContent: ""
            },
            {
                sDefaultContent: ""
            },
            {
                sDefaultContent: ""
            },
            {
                sDefaultContent: ""
            },
            {
                sDefaultContent: ""
            },
            {
                sDefaultContent: ""
            }
        ]
    });
});
</script>