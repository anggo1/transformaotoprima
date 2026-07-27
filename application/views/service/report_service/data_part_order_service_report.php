<div class="col-12 ">

    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-pk">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Wo No</th>
                    <th>Date Estimate</th>
                    <th>Estimation Code</th>
                    <th>Customer</th>
                    <th>Part No</th>
                    <th>Part Name</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>QTY</th>
                    <th>Total</th>
                    <th>Sales Disgn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($dataPo as $s) {
                ?> <tr>

                        <td><?php echo $no; ?></td>
                        <td><?php echo $s->wo_no; ?></td>
                        <td><?php echo tglIndoSedang($s->tgl_estimasi_penawaran); ?></td>
                        <td><?php echo $s->kode_estimasi_penawaran; ?></td>
                        <td><?php echo $s->nama_cus; ?></td>
                        <td><?php echo $s->no_part; ?></td>
                        <td><?php echo $s->nama_part; ?></td>
                        <td><?php echo $s->satuan; ?></td>
                        <td><?php echo number_format($s->harga); ?></td>
                        <td><?php echo $s->jumlah; ?></td>
                        <td><?php echo number_format($s->total_harga); ?></td>
                        <td><?php echo $s->sales_design; ?></td>


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
<script>
    $(document).ready(function() {
        var table = $('#list-pk').DataTable({
            "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "buttons": [{
                    extend: 'copyHtml5',
                    text: '<i class="fas fa-copy"></i> Copy',
                    titleAttr: 'Copy',
                    title: 'Part Order',
                    className: 'btn btn-sm  btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    titleAttr: 'Excel',
                    title: 'Part Order',
                    className: 'btn btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    titleAttr: 'PDF',
                    title: 'Part Order',
                    className: 'btn btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Cetak',
                    titleAttr: 'Print',
                    title: 'Part Order',
                    className: 'btn btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="fas fa-eye"></i> Tampilan',
                    titleAttr: 'Costum Tampilan',
                    className: 'btn btn-outline-secondary',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    }
                }

            ],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "processing": true,
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
            },
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();
                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };
                hasil = api
                    .column(10)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                total = $.fn.dataTable.render.number(',', '.', 0).display(hasil);
                $(api.column(10).footer()).html(total);
            },
            "rowGroup": {
                "startRender": null,
                "endRender": function(rows, group, type) {
                    var total = rows
                        .data()
                        .pluck(10)
                        .reduce(function(x, y) {
                            return x + y.replace(/[^\d]/g, '') * 1;
                        }, 0);
                    total = $.fn.dataTable.render.number(',', '.', 0).display(total);
                    return $('<tr/>')
                        .append(
                            '<td colspan="10" style=font-weight: bolder; align="right">TOTAL</td>')
                        .append('<td style= font-weight: bolder; align="Right">' + total +
                            '</td>');
                },
                "dataSrc": 1,
            },
            "initComplete": function(settings, json) {
                // in case the initial sort order leads to 
                // cells needing to be altered:
                processColumnNodes($('#list-pk').DataTable());
            }
        });

        table.on('draw', function() {
            processColumnNodes($('#list-pk').DataTable());
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
            var officeData = tbl.column(0, selector_modifier).data();
            var officeData1 = tbl.column(1, selector_modifier).data();
            var officeData2 = tbl.column(2, selector_modifier).data();
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


    function exportExcel() {
        var date1 = document.getElementById("tgl_awal").value;
        var date2 = document.getElementById("tgl_akhir").value;
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('ReportService/export_excel'); ?>?',
            data: 'date1=' + date1 + '&date2=' + date2
        });
    }

    function exportTableToExcel() {
        // Select the HTML table
        var el = document.getElementById('myTable');

        // Convert table to worksheet
        var wb = XLSX.utils.table_to_book(el, {
            sheet: "SheetJS"
        });

        // Save/Download the file
        XLSX.writeFile(wb, 'exported_data.xlsx');
    }
</script>