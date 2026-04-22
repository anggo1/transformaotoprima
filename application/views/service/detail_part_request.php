<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover nowrap dataTable" id="list-request">
        <thead>
            <tr>
                <th>No</th>
                <th>No Part</th>
                <th>Nama Part</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Aksi</th>
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
                <td><?php echo $s->jumlah; ?></td>
                <td><?php echo $s->keterangan; ?></td>

                <td class="text-center">
                    <button class="btn btn-xs btn-outline-danger delete-request ion-android-delete"
                        data-id="<?php echo $s->id_request; ?>"> Delete</button>
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
var MyTable = $('#list-request').dataTable({
    "responsive": false,
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false
});
</script>