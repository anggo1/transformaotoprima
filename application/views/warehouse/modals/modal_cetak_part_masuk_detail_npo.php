<script>
document.getElementById("btnPrint2").onclick = function() {
    printElement(document.getElementById("bagianCetak"));
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
    padding: 2px;
}
.table-cetak td {
    border-bottom: 1pt;
    padding: 2px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:9px;
    font: bold;
}
.table-cetak th {
    border-bottom: 1pt;
    border: 1pt;
    font: bold;
    font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:9px;
}
/*.modal-body {
    max-height: calc(100vh - 10px);
    overflow-y: auto;
}*/
.btn-floating {
    position: fixed;
    right: 25px;
    overflow: hidden;
    width: 50px;
    height: 50px;
    border-radius: 100px;
    border: 0;
    z-index: 9999;
    color: white;
    transition: .2s;
}
.btn-floating img {
   width: 500px;
   height: 600px;
}
.btn-floating:hover {
    width: auto;
    padding: 0 20px;
    cursor: pointer;
}

.btn-floating span {
    font-size: 16px;
    margin-left: 5px;
    transition: .2s;
    line-height: 0px;
    display: none;
}

.btn-floating:hover span {
    display: inline-block;
}

.btn-floating:hover img {
    margin-bottom: -3px;
}

.btn-floating.whatsapp {
    bottom: 25px;
    background-color:red;
    border: 2px solid #fff;
}

.btn-floating.whatsapp:hover {
    background-color:crimson;
}

.btn-floating.facebook {
    bottom: 85px;
    background-color: #1876f3;
    border: 2px solid #fff;
}

.btn-floating.facebook:hover {
    background-color: #1876f3;
}
</style>
        <button class="btn-floating facebook" id="btnPrint2">
        <i class="fas fa-print fa-lg"></i>
            <span>Cetak Data</span>
        </button>
        <button class="btn-floating whatsapp" data-dismiss="modal">
        <i class="fas fa-times fa-lg"></i>
            <span>Tutup</span>
        </button>
<?php
$judul="";
$subB="";
if($status=="PPU"){
$judul="REKAPITULASI PENERIMAAN DETAIL BARANG NON PO";
$subB="PRIMAJASA PERDANARAYA UTAMA";
}
if($status=="MPU") {
$judul="REKAPITULASI PENERIMAAN DETAIL BARANG NON PO";
$subB="MAMERA PERDANA UTAMA";
}
if($status=="GLOBAL") {
$judul="LAPORAN PENERIMAAN BARANG DENGAN PO GLOBAL";
$subB="PPU & MPU";
}
?>
<div class="modal-body">
    <div id="bagianCetak">
        <div class="card-body">
            <div class="col-12 ">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th colspan="2">
                                <div align="left">
                                    <H4><?php echo $judul ?></H4>
                                </div>
                                <div align="left"></div>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <div align="left">Sub Bagian</div>
                            </th>
                            <th>
                                <div align="left"> : <?php echo $subB ?></div>
                            </th>
                        </tr>
                        <tr>
                            <th width="10%">
                                <div align="left">Periode Tanggal</div>
                            </th>
                            <th width="362">
                                <div align="left"> : <?php echo $tgl_awal.' s/d '.$tgl_akhir ?></div>
                            </th>
                        </tr>
                    </thead>
                </table>
                <div class="table-responsive">
                    <table class="table display compact" width="100%" id="list-cetak-detail-npo">
                        <thead>
                        <tr>
                    <th width='5%'>No</th>
                    <th>Kode Masuk</th>
                    <th>No INV</th>
                    <th>No SJ</th>
                    <th>Supplier</th>
                    <th>Tgl Masuk</th>
                    <th>No Part</th>
                    <th>Nama Part</th>
                    <th>QTY</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
                        </thead>
                        <tbody>
                            <?php
$no = 1;
$grand_total=0;
foreach ($dataMasuk as $s) {
    $grand_total += $s->total;
?> <tr>

<td><?php echo $s->row_urut; ?></td>
                    <td><?php echo $s->id_masuk; ?></td>
                    <td><?php echo $s->no_inv_sup; ?></td>
                    <td><?php echo $s->no_sj_sup; ?></td>
                    <td><?php echo $s->nama_sup; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_masuk); ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td><?php echo $s->jumlah; ?></td>
                    <td><?php echo $s->satuan; ?></td>
                    <td><?php echo $s->hrg_awal; ?></td>
                    <td align="right"><?php echo number_format($s->total); ?></td>
                            </tr>
                            <?php
    $no++;
}
?>

                        </tbody>
                        <tfoot>
                            <th colspan="10"></th>
                            <th style="text-align: right;">GRAND TOTAL</th>
                            <th style="text-align: right;"></th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    var table = $('#list-cetak-detail-npo').dataTable({
        "paging": false,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "processing": true,
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(11)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(11).footer()).html(total);
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
                            '<td colspan="11" style=font-weight: bolder; align="right">TOTAL</td>')
                        .append('<td style= font-weight: bolder; align="Right">' + total +
                            '</td>');
                },
                "dataSrc": 1,
            },
            "initComplete": function(settings, json) {
            // in case the initial sort order leads to 
            // cells needing to be altered:
            processColumnNodes($('#list-cetak-detail-npo').DataTable());
        }
    });

    table.on('draw', function() {
        processColumnNodes($('#list-cetak-detail-npo').DataTable());
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
        var officeData = tbl.column(0, selector_modifier).data();
        var officeData1 = tbl.column(1, selector_modifier).data();
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
    }

});

</script>