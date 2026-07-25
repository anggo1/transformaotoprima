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
           $idlokasi = $this->session->userdata['lokasi'];
            $no = 1;
            foreach ($dataDetail as $d) {
                if($idlokasi=='Cibitung'){
            $stok=$d->stok_cbt;
            $harga=$d->hrg_net_cbt;
                }
          if($idlokasi=='Jakarta'){
            $stok=$d->stok_jkt;
            $harga=$d->hrg_net_jkt;
          $total += $d->hrg_net_jkt* $d->jumlah;
          }
          if($idlokasi=='Surabaya'){
            $stok=$d->stok_sby;
            $harga=$d->hrg_net_sby;
          $total += $d->hrg_net_sby * $d->jumlah;
          }
            ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $d->no_part; ?></td>
            <td><?php echo $d->nama_part; ?></td>
            <td><?php echo $d->satuan; ?></td>
            <td><?php echo $stok; ?></td>
            <td><?php echo number_format($harga); ?></td>
            <td><?php echo number_format($d->hrg_part);?></td>
            <td><?php echo $d->jumlah; ?></td>
            <td><?php if(empty($d->hrg_part)) { echo number_format($harga * $d->jumlah);}else{ echo number_format($d->hrg_part * $d->jumlah);}  ?>
            </td>
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