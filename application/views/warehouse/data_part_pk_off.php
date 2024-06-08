<div class="table-responsive">

    <table class="table table-bordered table-hover nowrap" id="list-pkaktif">

        <thead>

            <tr>

                <th width='5%'>No</th>

                <th>Id PK</th>

                <th>No Body</th>

                <th>Kode PK</th>

                <th>Keterangan</th>

                <th>Aksi</th>

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

                <td class="text-center">
                    <button class="btn btn-xs btn-outline-success detail-part-pk" data-id="<?php echo $s->id_pk; ?>"><i
                            class="fa fa-eye"></i> Detail</button>
                    <button class="btn btn-xs btn-outline-primary part-pk" data-id="<?php echo $s->id_pk; ?>"
                        data-spk="<?php echo $s->id_lapor; ?>"><i class="fa fa-share-square"></i> Part Keluar</button>
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

<script language="javascript">

//var columnDefs = [{
 // title: "No"}, {
 // title: "Column 2"},{
 // title: "Column 2"},{
 // title: "Column 2"},{
 // title: "Column 2"},{
 // title: "Column 2"}];

$(document).ready(function() {
  $('#list-pkaktif').DataTable({
    "language": {
      "emptyTable": "<h4>Pekerjaan Masih Aktif</h4><div><span>Cek Kembali data SPK Aktif</span></div>"
    },
  //  columns: columnDefs,
  });
});

</script>