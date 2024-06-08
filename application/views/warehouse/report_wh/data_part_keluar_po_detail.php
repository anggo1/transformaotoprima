<div class="card">
    <div class="modal-content">
        <div class="card-body">

            <div class="col-12 ">
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered table-hover nowrap" id="list-data">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Ref</th>
                                <th>No PK</th>
                                <th>No Body</th>
                                <th>Proses</th>
                                <th>Tgl Keluar</th>
                                <th>No</th>
                                <th>Kode Barang</th>
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
                            foreach ($detailKeluar as $s) {
                                $total += $s->jumlah * $s->hrg_part;
                                $sub_total = $s->jumlah * $s->hrg_part;
                            ?>
                            <tr>
                                <td width="2%" align="center"><?php echo $s->row_urut; ?></td>
                                <td><?php echo $s->id_keluar; ?></td>
                                <td><?php echo $s->no_pk; ?></td>
                                <td><?php echo $s->no_body; ?></td>
                                <td><?php echo $s->ket_pk; ?></td>
                                <td><?php echo tglIndoSedang($s->tgl_keluar); ?></td>
                                <td width="2%"><?php echo $s->row_no; ?></td>
                                <td><?php echo $s->no_part; ?></td>
                                <td><?php echo $s->nama_part; ?></td>
                                <td align="center"><?php echo $s->jumlah; ?></td>
                                <td><?php echo $s->satuan; ?></td>
                                <td align="right"><?php echo number_format($s->hrg_part); ?></td>
                                <td align="right"><?php echo number_format($sub_total); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        var table = $('#list-data').DataTable({
            "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "buttons": [
                // {
                //     extend: 'excelHtml5',
                //     text: '<i class="fas fa-file-excel"></i> Excel',
                //     titleAttr: 'Excel',
                //	footer: true,
                //      title: function() {
                //          return "Report Barang Keluar Dengan PK";
                //          },
                //     className: 'btn btn-sm btn-outline-primary',
                //      init: function(api, node, config) {
                //          $(node).removeClass('btn-secondary')
                //      },
                //      exportOptions: {
                //          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                //      }
                //  },
                {
                    text: '<i class="fa fa-reply-all"></i> Kembali',
                    className: 'btn btn-sm btn-outline-primary list-barang',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    }
                },
                {
                    text: '<i class="fa fa-print"></i> Cetak',
                    className: 'btn btn-sm btn-outline-primary cetak-detail-keluar',
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
            "rowGroup": {
                "startRender": null,
                "endRender": function(rows, group, type) {
                    var total = rows
                        .data()
                        .pluck(12)
                        .reduce(function(x, y) {
                            return x + y.replace(/[^\d]/g, '') * 1;
                        }, 0);
                        total = $.fn.dataTable.render.number(',', '.', 0).display(total);
                    return $('<tr/>')
                        .append(
                            '<td colspan="12" style=font-weight: bolder; align="right">GRAND TOTAL</td>')
                        .append('<td style= font-weight: bolder; align="Right">' + total +
                            '</td>');
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
                processColumnNodes($('#list-data').DataTable());
            }
        });

        table.on('draw', function() {
            processColumnNodes($('#list-data').DataTable());
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