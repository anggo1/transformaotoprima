<style>
.inEdit {
    background-color: #FFFFFF;
    border: 2px solid #000;
    border-radius: 5px;
    padding: 0;
}
</style>
<table class="table table-striped  table-bordered table-hover nowrap" id="listpomasuk">
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
            foreach ($dataDetail as $s) {
            ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $s->no_part; ?></td>
            <td><?php echo $s->nama_part; ?></td>
            <td><?php echo $s->satuan; ?></td>
            <td><?php echo $s->stok; ?></td>
            <td><?php echo $s->harga_baru; ?></td>
            <td><?php echo $s->hrg_part;?></td>
            <td><?php echo $s->jumlah; ?></td>
            <td><?php if(empty($s->hrg_part)) { echo number_format($s->harga_baru * $s->jumlah);}else{ echo number_format($s->hrg_part * $s->jumlah);}  ?></td>
        </tr>
        <?php
            $no++;
            }
            ?>
    </tbody>
    <tfoot></tfoot>
</table>
<script language="javascript">
var MyTable = $('#listpomasuk').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": false,
    "info": true
});
</script>