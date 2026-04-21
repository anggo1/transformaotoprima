<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover nowrap dataTable" id="list-labora">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                  $no = 1;
                  foreach ($dataM as $m) {
                  ?>
            <tr>

                <td><?php echo $no; ?></td>
                <td><?php echo $m->nik; ?></td>
                <td><?php echo $m->nama; ?></td>

                <td class="text-center">
                    <button class="btn btn-xs btn-outline-danger delete-mechanic ion-android-delete"
                        data-id="<?php echo $m->id_labor; ?>"> Delete</button>
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
var MyTable = $('#list-labora').dataTable({
    "responsive": false,
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false
});
</script>