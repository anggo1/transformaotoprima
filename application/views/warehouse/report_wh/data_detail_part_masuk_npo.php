<?php
if(!empty($dataMasuk)){
foreach ($dataMasuk as $st) {}}
$btColor='bg-gradient-navy';
$btColor1='bg-gradient-blue';
$btColor='bg-gradient-blue';
$btColor1='bg-gradient-navy';
$btColor2='bg-gradient-navy';
?>
<div class="col-12 ">
    
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded list-detail-barang" data-status="" data-po="<?php echo $status_po ?>"><i class="fa fa-indent"></i>  &nbsp;Detail <?php echo $st->status ?></button>
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded cetak-masuk-detail" data-status="" data-po="<?php echo $status_po ?>"><i class="fa fa-print"></i>  &nbsp;CETAK DETAIL</button>
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-dataDetail">
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
                    <td><?php echo $s->hrg_part; ?></td>
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
<script>
$(document).ready(function() {
    var table = $('#list-dataDetail').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "processing": true,
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [
        {
           extend: 'excelHtml5',
           text: '<i class="fas fa-file-excel"></i> Excel',
           titleAttr: 'Excel',
			footer: true,
            title: function() {
                return "<div style='font-size: 20px;'>Report Barang Keluar Dengan PK</div>";
               },
            className: 'btn btn-sm btn-outline-primary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
           },
            exportOptions: {
               columns: [0, 1, 2, 3, 4, 5,6,7,8,9,10,11]
           }
        },
        {
            text: '<i class="fa fa-list-ol"></i> Detail',
            className: 'btn btn-sm btn-outline-primary list-detail-barang',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            }
        },
        {
            text: '<i class="fa fa-print"></i> Cetak',
            className: 'btn btn-sm btn-outline-primary cetak-keluar-data',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            }
        }
    ],
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
            processColumnNodes($('#list-dataDetail').DataTable());
        }
    });

    table.on('draw', function() {
        processColumnNodes($('#list-dataDetail').DataTable());
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