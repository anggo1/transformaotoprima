<div class="table-responsive">
    <table class="table table-hover nowrap w-100" id="list-type">
        <thead>
            <tr>
                <th>No</th>
                <th>Type</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $no = 1;
        foreach ($dataType as $s) {
        ?>
            <tr>

                <td><?php echo $no; ?></td>
                <td><?php echo $s->type; ?></td>

                <td class="text-center">
                    <button class="btn btn-xs btn-outline-success update-dataType ion-edit"
                        data-id="<?php echo $s->id_type; ?>"></button>
                    <button class="btn btn-xs btn-outline-danger delete-type ion-android-delete" data-toggle="modal"
                        data-target="#hapusType" data-id="<?php echo $s->id_type; ?>"></button>
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
$('#list-type').DataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 10
});
       </script>