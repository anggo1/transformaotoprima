<table class="table table-striped  table-bordered table-hover nowrap" id="list-pkaktif">
    <thead>
        <tr>
            <th>No</th>
            <th>No Part</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Stok</th>
            <th>Harga Satuan</th>
            <th>Harga Baru</th>
            <th>Qty Masuk</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
$no = 1;
foreach ($dataPk as $s) {
?> <tr>

            <td><?php echo $no; ?></td>
            <td><?php echo $s->id_pk; ?></td>
            <td><?php echo $s->no_body; ?></td>
            <td><?php echo $s->jns_pk; ?></td>
            <td><?php echo $s->ket_pk; ?></td>
        </tr>
        <?php
    $no++;
}
?>
        <tbody>
    <tfoot></tfoot>
</table>
<script language="javascript">
var MyTable = $('#list-pkaktif').DataTable({
    "responsive": true,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 10
});
</script>