<?php
if(!empty($dataMasuk)){
foreach ($dataMasuk as $st) {}}
$btColor="";
$btColor1="";
$btColor2="";
if($status=="PPU"){
$btColor='bg-gradient-navy';
$btColor1='bg-gradient-blue';
} if($status=="MPU") {
$btColor='bg-gradient-blue';
$btColor1='bg-gradient-navy';
$btColor2='bg-gradient-navy';
}
?>
<div class="col-12 ">
    
<button type="button" class="btn <?php echo $btColor ?> shadow mb-3 rounded list-barang-ppu"><i class="fa fa-id-card-alt"></i>  &nbsp;P P U</button>
    <button type="button" class="btn <?php echo $btColor1 ?> shadow mb-3 rounded list-barang-mpu"><i class="fa fa-id-card-alt"></i>  &nbsp;M P U</button>
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded list-detail-barang" data-status="<?php echo $status ?>" data-po="<?php echo $status_po ?>"><i class="fa fa-indent"></i>  &nbsp;Detail <?php echo $st->status ?></button>
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded cetak-masuk-detail" data-status="<?php echo $status ?>" data-po="<?php echo $status_po ?>"><i class="fa fa-print"></i>  &nbsp;CETAK DETAIL</button>
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