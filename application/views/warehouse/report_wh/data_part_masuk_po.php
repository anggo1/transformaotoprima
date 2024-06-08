<?php
if(!empty($dataMasuk)){
foreach ($dataMasuk as $st) {}}
$btColor="";
$btColor1="";
$btColor2="";
if($st->status=="PPU"){
$btColor='bg-gradient-navy';
$btColor1='bg-gradient-blue';
$btColor2='bg-gradient-blue';
} if($st->status=="MPU") {
$btColor='bg-gradient-blue';
$btColor1='bg-gradient-navy';
$btColor2='bg-gradient-navy';
}
?>
<div class="col-12 ">
    
<button type="button" class="btn <?php echo $btColor ?> shadow mb-3 rounded list-barang-ppu"><i class="fa fa-id-card-alt"></i>  &nbsp;P P U</button>
    <button type="button" class="btn <?php echo $btColor1 ?> shadow mb-3 rounded list-barang-mpu"><i class="fa fa-id-card-alt"></i>  &nbsp;M P U</button>
    <button type="button" class="btn bg-gradient-blue shadow mb-3 rounded list-detail-barang" data-status="<?php echo $st->status ?>"><i class="fa fa-indent"></i>  &nbsp;Detail <?php echo $st->status ?></button>
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded <?php if($st->status=="PPU"){ echo 'cetak-masuk-ppu';} if($st->status=="MPU") { echo 'cetak-masuk-mpu';}?>"><i class="fa fa-print"></i>  &nbsp;CETAK</button>
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Tgl Masuk</th>
                    <th>Kode Masuk</th>
                    <th>Supplier</th>
                    <th>No PO</th>
                    <th>No INV</th>
                    <th>No SJ</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$grand_total=0;
foreach ($dataMasuk as $s) {
    $grand_total += $s->total;
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_masuk); ?></td>
                    <td><?php echo $s->id_masuk; ?></td>
                    <td><?php echo $s->nama_supplier; ?></td>
                    <td><?php echo $s->no_po; ?></td>
                    <td><?php echo $s->no_inv_sup; ?></td>
                    <td><?php echo $s->no_sj_sup; ?></td>
                    <td align="right"><?php echo number_format($s->total); ?></td>
                    <td class="text-center">
                        <?php if($s->status_po=='Y'){?>
                        <button class="btn btn-xs btn-outline-primary cetak-masuk"
                            data-id="<?php echo $s->kode_masuk; ?>"><i class="fa fa-print"></i> Print</button>
                            <?php }else{ ?>
                        <button class="btn btn-xs btn-outline-primary cetak-masuknon"
                            data-id="<?php echo $s->kode_masuk; ?>"><i class="fa fa-print"></i> Print</button>
                           <?php } ?>
                           <?php foreach($viewLevel as $v) { } if ($v->delete_level =='Y') {?>
                                    <button type="button" class="btn btn-xs bg-gradient-danger delete-detail" id="delete"
                                        data-id="<?php echo $s->kode_masuk; ?>" data-status="<?php echo $s->status_part; ?>" title="Delete Data"  data-toggle="modal" data-target="#hapusDetail"><i
                                            class="fas fa-trash"></i> Delete</button>
                                            <?php } ?>
                    </td>
                </tr>
                <?php
    $no++;
}
?>

            </tbody>
            <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Grand Total</th>
            <th style="text-align: right;"></th>
            <th></th>
            </tfoot>
        </table>
    </div>
</div>
<script>
var MyTable = $('#list-data').DataTable({
    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(7)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(7).footer()).html(total);
    },

    "responsive": false,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 10
});
</script>