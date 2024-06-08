<div class="col-12 ">
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded" onclick="listOpnameDetail()"><i
            class="fa fa-indent"></i> &nbsp;Detail</button>
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded" onclick="cetaklistOpnameDetail()"><i
            class="fa fa-print"></i> &nbsp;CETAK DETAIL</button>
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-dataDetail">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>ID Opname</th>
                    <th>Kelompok</th>
                    <th>Jenis</th>
                    <th>Lokasi</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Stok System</th>
                    <th>Stok Fisik</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
foreach ($dataOpname as $s) {
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->id_opname; ?></td>
                    <td><?php echo $s->kelompok; ?></td>
                    <td><?php echo $s->type; ?></td>
                    <td><?php echo $s->lokasi; ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td><?php echo $s->satuan; ?></td>
                    <td><?php echo $s->stok_lama; ?></td>
                    <td><?php echo $s->stok_fisik; ?></td>
                    <td style="text-align: right;"><?php echo number_format($s->hrg_awal); ?></td>
                    <td style="text-align: right;"><?php if(!empty($s->stok_fisik)){ echo number_format($s->stok_fisik*$s->hrg_awal);} if(empty($s->stok_fisik < 0)){echo "0";} ?>
                    </td>
                </tr>
                <?php
    $no++;
}
?>

            </tbody>
        </table>
    </div>
</div>
<script>
$(document).ready(function() {

    var row_group_index = 0;
    var row_group_td = 0;
    var table = $('#list-dataDetail').DataTable({


        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": false,
        "ordering": true,
        "pageLength": 10,
        "lengthMenu": [
            [-1, 10, 25, 50],
            ['Seluruhnya', 10, 25, 50],

        ],

        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();
            var intVal = function(i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                    'number' ? i : 0;
            };
            hasil = api
                .column(8)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            total = $.fn.dataTable.render.number(',', '.', 0).display(hasil);
            $(api.column(9).footer()).html(total);
        },
        order: [
            [2, 'desc']
        ],
        rowGroup: {
            "startRender": function(rows, group, level) {
                row_group_index++;
                return row_group_index + '. ' + group + ' (' + rows.count() + ' rows)';
            },


            endRender: function(rows, group) {
                row_group_td++;
                var total1 = rows
                    .data()
                    .pluck(8)
                    .reduce(function(a, b) {
                        return a + b.replace(/[^\d]/g, '') * 1;
                    }, 0);
                total1 = $.fn.dataTable.render.number(',', '.', 0).display(total1);
                var total2 = rows
                    .data()
                    .pluck(9)
                    .reduce(function(a, b) {
                        return a + b.replace(/[^\d]/g, '') * 1;
                    }, 0);
                total2 = $.fn.dataTable.render.number(',', '.', 0).display(total2);
                var total3 = rows
                    .data()
                    .pluck(10)
                    .reduce(function(a, b) {
                        return a + b.replace(/[^\d]/g, '') * 1;
                    }, 0);
                total3 = $.fn.dataTable.render.number(',', '.', 0).display(total3);
                var total4 = rows
                    .data()
                    .pluck(11)
                    .reduce(function(a, b) {
                        return a + b.replace(/[^\d]/g, '') * 1;
                    }, 0);
                total4 = $.fn.dataTable.render.number(',', '.', 0).display(total4);
                return $('<tr/>')
                    .append(
                        '<td colspan="8" style=color:#17a2b8;font-weight: bolder; align="right">Total  ' +
                        group + '</td>')
                    .append('<td style=color:green; font-weight: bolder; align="Right">' + total1 +
                        '</td>')
                    .append('<td style=color:green; font-weight: bolder; align="Right">' + total2 +
                        '</td>')
                    .append('<td style=color:green; font-weight: bolder; align="Right">' + total3 +
                        '</td>')
                    .append('<td style=color:green; font-weight: bolder; align="Right">' + total4 +
                        '</td>')
            },
            dataSrc: 2
        }
    });

    table.on('draw', function() {
        row_group_index = 0;
    });


});
</script>