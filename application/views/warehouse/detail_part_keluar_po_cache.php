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
            <th>Qty Keluar</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $idlokasi = $this->session->userdata['lokasi'];
        $no = 1;
        foreach ($dataPo as $s) {
            if ($idlokasi == 'Cibitung') {
                $stok = $s->stok_cbt;
            }
            if ($idlokasi == 'Jakarta') {
                $stok = $s->stok_jkt;
            }
            if ($idlokasi == 'Surabaya') {
                $stok = $s->stok_sby;
            }
            ?>
        <tr>
            <td><?php echo $no; ?></td>
                <td><?php echo $s->no_part; ?></td>
                <td><?php echo $s->nama_part; ?></td>
                <td><?php echo $s->satuan; ?></td>
                <td><?php echo $stok; ?></td>
                <td><?php echo number_format($s->harga); ?></td>
            <td><?php echo $s->jumlah ?></td>
            <td><?php if(!empty($s->harga)) { echo number_format($s->harga * $s->jumlah);} ?></td>
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