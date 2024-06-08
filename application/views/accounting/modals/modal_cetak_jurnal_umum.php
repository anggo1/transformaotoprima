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
        width: 100%;
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
<div id="printThis">
    <div class="alert bg-white">
        <table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000"
            style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;">
            <thead>
                <tr>
                    <th colspan="4">
                        <div align="left">
                            <table width="100%">
                                <tbody>
                                    <tr>
                                        <td width="100%">
                                            <font size="+2">Transaksi Jurnal Umum</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                </tr>
            </thead>
            <tbody>


            </tbody>
        </table>
            <table id="table-cetak" border="0"  class="table table-cetak">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Ref</th>
                        <th>CodeAcc</th>
                        <th>Account</th>
                        <th>Date</th>
                        <th>Keterangan</th>
                        <th>Debet</th>
                        <th>Credit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
            $no = 1;
    foreach ($dataJurnal as $s) {
        ?>
                    <tr>
                        <td width="1%" align="center"><?php echo $s->row_no; ?></td>
                        <td><?php echo $s->no_ref; ?></td>
                        <td width="2%"><?php echo $s->kode_akun; ?></td>
                        <td><?php echo $s->nama_akun; ?></td>
                        <td><?php echo tglIndoPendek($s->tgl_jurnal); ?></td>
                        <td><?php echo $s->keterangan; ?></td>
                        <td align="right" style="color: green;"><?php echo number_format($s->debit); ?></td>
                        <td align="right" style="color: red;"><?php echo number_format($s->kredit); ?></td>
                    </tr>
                    <?php
        $no++;
    }
    ?>
                </tbody>
                <tfoot>
                                    <tr>
						                <th style="text-align: right;" colspan="6" align="right">Total </th>
                                        <th style="text-align: right;" align="right"></th>
                                        <th style="text-align: right;" align="right"></th>
                                    </tr>
                </tfoot>
            </table>
    </div>
</div>
<div class="card-footer">
    <button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K
    </button>
    <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>
<script language="javascript">
var MyTable = $('#table-cetak').dataTable({
    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(6)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        hasil = api
            .column(7)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(6).footer()).html(total);
        $(api.column(7).footer()).html(total);
    },
    "responsive": false,
    "paging": false,
    "searching": false,
    "info": false,
    "autoWidth": true,
    "language": {
        "sEmptyTable": "Data Jurnal Umum Belum Ada",
        "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
    },
    "processing": true,
    "order": [
        [1, 'asc']
    ],
    "rowGroup": {
        "dataSrc": [1],
        "startRender": function(rows, group, level) {
            return group;
        },
        endRender: function ( rows, group ) {
                var debit = rows
                    .data()
                    .pluck(6)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    }, 0);
                debit = $.fn.dataTable.render.number(',', '.', 0).display( debit );
                var kredit = rows
                    .data()
                    .pluck(7)
                    .reduce( function (x, y) {
                        return x + y.replace(/[^\d]/g, '')*1;
                    }, 0);
                kredit = $.fn.dataTable.render.number(',', '.', 0).display( kredit );
                return $('<tr/>')
                    .append( '<td style=align-items:right;color:#17a2b8;font-weight: bolder; colspan="6" align="right">BALANCE '+group+'</td>' )
                    .append( '<td style=color:green; font-weight: bolder; align="Right">'+debit+'</td>' )
                    .append( '<td style=color:red; font-weight: bolder; align="Right">'+kredit+'</td>' );
            },
            dataSrc: 1
        },

    "ordering": false
  
});
</script>