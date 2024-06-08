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
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered table-hover nowrap" id="list-part-detail">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No SPK</th>
                                <th>No Body</th>
                                <th>No PK</th>
                                <th>No Bon</th>
                                <th>Proses</th>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>QTY</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total=0;
                            $grand_total=0;
                            foreach ($detailKeluar as $s) {
                                $total += $s->jumlah * $s->hrg_part;
                                $sub_total = $s->jumlah * $s->hrg_part;
                                $grand_total += $sub_total;
                            ?>
                            <tr>
                                <td width="2%" align="center"><?php echo $s->row_urut; ?></td>
                                <td><?php echo $s->no_spk; ?></td>
                                <td><?php echo $s->no_body; ?></td>
                                <td><?php echo $s->no_pk; ?></td>
                                <td><?php echo $s->id_keluar; ?></td>
                                <td><?php echo $s->ket_pk; ?></td>
                                <td width="1%"><?php echo $s->row_no; ?></td>
                                <td><?php echo $s->nama_part; ?></td>
                                <td align="center"><?php echo $s->jumlah; ?></td>
                                <td><?php echo $s->satuan; ?></td>
                                <td align="right"><?php echo number_format($s->hrg_part); ?></td>
                                <td align="right"><?php echo number_format($sub_total); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
            <tfoot>
                <th  colspan="11" style="text-align: right;">GRAND TOTAL</th>
                <th style="text-align: right;"><?php echo number_format($grand_total) ?></th>
            </tfoot>
                    </table>
                </div>
            </div>
            </div>
    <script>
    $(document).ready(function() {
        var table = $('#list-part-detail').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "processing": false,
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
            },
            "rowGroup": {
                "startRender": null,
                "endRender": function(rows, group, type) {
                    var total = rows
                        .data()
                        .pluck(11)
                        .reduce(function(x, y) {
                            return x + y.replace(/[^\d]/g, '') * 1;
                        }, 0);
                        total = $.fn.dataTable.render.number(',', '.', 0).display(total);
                    return $('<tr/>')
                        .append(
                            '<td colspan="11" style=font-weight: bolder; align="right">SUB TOTAL</td>')
                        .append('<td style= font-weight: bolder; align="Right">' + total + '</td>')
                            ;
                },
                "dataSrc": 1,
            },
            data: table,
            "order": [
                [0, 'asc']
            ],
            "initComplete": function(settings, json) {
                // in case the initial sort order leads to 
                // cells needing to be altered:
                processColumnNodes($('#list-part-detail').DataTable());
            }
        });

        table.on('draw', function() {
            processColumnNodes($('#list-part-detail').DataTable());
        });

        function processColumnNodes(tbl) {
            // see https://datatables.net/reference/type/selector-modifier
            var selector_modifier = {
                order: 'current',
                page: 'current',
                search: 'applied'
            }

            var previous = '';
            var officeNodes = tbl.column(0, selector_modifier).nodes();
            var officeNodes1 = tbl.column(1, selector_modifier).nodes();
            var officeNodes2 = tbl.column(2, selector_modifier).nodes();
            var officeNodes3 = tbl.column(3, selector_modifier).nodes();
            var officeNodes4 = tbl.column(4, selector_modifier).nodes();
            var officeData = tbl.column(0, selector_modifier).data();
            var officeData1 = tbl.column(1, selector_modifier).data();
            var officeData2 = tbl.column(2, selector_modifier).data();
            var officeData3 = tbl.column(3, selector_modifier).data();
            var officeData4 = tbl.column(4, selector_modifier).data();
            for (var i = 0; i < officeData.length; i++) {
                var current = officeData[i];
                if (current === previous) {
                    officeNodes[i].textContent = '';
                    officeNodes[i].setAttribute("style", "border-top:none;");
                } else {
                    officeNodes[i].textContent = current;
                }
                previous = current;
            }
            for (var i = 0; i < officeData1.length; i++) {
                var current = officeData1[i];
                if (current === previous) {
                    officeNodes1[i].textContent = '';
                    officeNodes1[i].setAttribute("style", "border-top:none;");
                } else {
                    officeNodes1[i].textContent = current;
                }
                previous = current;
            }
            for (var i = 0; i < officeData2.length; i++) {
                var current = officeData2[i];
                if (current === previous) {
                    officeNodes2[i].textContent = '';
                    officeNodes2[i].setAttribute("style", "border-top:none;");
                } else {
                    officeNodes2[i].textContent = current;
                }
                previous = current;
            }
            for (var i = 0; i < officeData3.length; i++) {
                var current = officeData3[i];
                if (current === previous) {
                    officeNodes3[i].textContent = '';
                    officeNodes3[i].setAttribute("style", "border-top:none;");
                } else {
                    officeNodes3[i].textContent = current;
                }
                previous = current;
            }
            
            for (var i = 0; i < officeData4.length; i++) {
                var current = officeData4[i];
                if (current === previous) {
                    officeNodes4[i].textContent = '';
                    officeNodes4[i].setAttribute("style", "border-top:none;");
                } else {
                    officeNodes4[i].textContent = current;
                }
                previous = current;
            }
        }

    });
    </script>