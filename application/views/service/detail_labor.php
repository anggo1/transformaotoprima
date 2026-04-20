<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover nowrap dataTable" id="list-labor">
        <thead>
            <tr>
                <th>No</th>
                <th>No WO</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                  $no = 1;
                  foreach ($dataLabor as $l) {
                  ?>
            <tr>

                <td><?php echo $no; ?></td>
                <td><?php echo $l->wo_no; ?></td>
                <td><?php echo $l->nik; ?></td>
                <td><?php echo $l->nama; ?></td>

                <td class="text-center">
                    <button class="btn btn-xs btn-outline-danger delete-operation ion-android-delete"
                        data-id="<?php echo $l->id_labor; ?>"> Delete</button>
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
var MyTable = $('#list-labor').dataTable({
    "responsive": false,
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false
});
</script>