<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover nowrap dataTable" id="list-start-work">
        <thead>
            <tr>
                <th>No</th>
                <th>SPK</th>
                <th>Operation</th>
                <th>Hours</th>
                <th>Type Of Work</th>
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
                <td><?php echo $s->wo_no; ?></td>
                <td><?php echo $s->no_part; ?></td>
                <td><?php echo $s->jumlah; ?></td>
                <td><?php echo $s->nama_part; ?></td>

                <td class="text-center">
                    <button class="btn btn-xs btn-outline-success add-mechanic ion-android-add"
                        data-id="<?php echo $s->wo_no; ?>"> Add Mechanic</button>
                    <button class="btn btn-xs btn-outline-danger delete-operation ion-android-delete"
                        data-id="<?php echo $s->id_detail; ?>"> Delete</button>
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
var MyTable = $('#list-start-work').dataTable({
    "responsive": false,
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false
});
</script>