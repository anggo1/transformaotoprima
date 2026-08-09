<div class="col-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pk">
                                            <thead>
                                                <tr>
                                                    <th width='5%'>No</th>
                                                    <th>Kode PO</th>
                                                    <th>Tgl PO</th>
                                                    <th>PO Customer</th>
                                                    <th>Customer</th>
                                                    <th>No Part</th>
                                                    <th>Nama Part</th>
                                                    <th>Satuan</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
                                                    <th>Remark</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
$no = 1;
foreach ($dataPo as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->kode_po; ?></td>
        <td><?php echo tglIndoSedang($s->tgl_po); ?></td>
        <td><?php echo $s->kode_pesan; ?></td>
        <td><?php echo $s->nama_cus; ?></td>
        <td><?php echo $s->no_part; ?></td>
        <td><?php echo $s->nama_part; ?></td>
        <td><?php echo $s->satuan; ?></td>
        <td><?php echo $s->jumlah; ?></td>
        <td><?php echo number_format($s->harga, 0, ',', '.'); ?></td>
        <td><?php echo number_format($s->harga * $s->jumlah, 0, ',', '.'); ?></td>
        <td><?php echo $s->remark; ?></td>

        <td class="text-center">
            <button class="btn btn-xs btn-outline-primary cetak-po" data-id="<?php echo $s->id_po_masuk; ?>"><i class="fa fa-print"></i> Print</button>
			
    </td>
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
                    title: 'Work Order Report',
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
                    title: 'Work Order Report',
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
                    title: 'Work Order Report',
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
                    title: 'Work Order Report',
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
                    .column(8)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                total = $.fn.dataTable.render.number(',', '.', 0).display(hasil);
                $(api.column(8).footer()).html(total);
            },
            "rowGroup": {
                "startRender": null,
                "endRender": function(rows, group, type) {
                    var total = rows
                        .data()
                        .pluck(9)
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
    };

    // Menyimpan daftar indeks kolom yang ingin digabungkan datanya (Kolom 0 sampai 4)
    var targetColumns = [0, 1, 2,4];

    targetColumns.forEach(function(colIndex) {
        var nodes = tbl.column(colIndex, selector_modifier).nodes();
        var data = tbl.column(colIndex, selector_modifier).data();
        
        var previousData = null;
        var startCell = null;
        var rowspan = 1;

        for (var i = 0; i < data.length; i++) {
            var currentData = data[i];

            if (currentData === previousData) {
                rowspan++;
                // Sembunyikan sel yang sama dan hapus garis atasnya agar terlihat menyatu
                $(nodes[i]).hide().css("border-top", "none");
                // Perbarui atribut rowspan pada sel utama paling atas
                $(startCell).attr('rowspan', rowspan);
            } else {
                // Jika data baru ditemukan, reset hitungan rowspan untuk grup baru
                previousData = currentData;
                startCell = nodes[i];
                rowspan = 1;
                // Pastikan sel utama tampil normal tanpa gangguan rowspan lama
                $(startCell).removeAttr('rowspan').show().css("border-top", "");
                $(startCell).text(currentData);
            }
        }
    });
}

    });
    $(document).on("click", ".cetak-keluar", function() {
		var id = $(this).attr("data-id");
		//var id = document.getElementById('next_proses').value=datakode;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('Part_keluar/cetak'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				$('#modal-keluar').html(data);
				$('#cetak-keluar').modal('show');
			})
	})
    function cetakBon(datakode) {}


$(document).on("click", ".cetak-bon", function() {
		var id = $(this).attr("data-id");
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('PartPk/cetakBon'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
           // $('#part-pk').modal('hide');
            $('#modal-pk').html(data);
            $('#cetak-bon').modal('show');
        })
})
    </script>