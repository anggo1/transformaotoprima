<div class="table-responsive">
    <table class="table table-bordered table-hover nowrap" id="list-jenis">
        <thead>
            <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $no = 1;
        foreach ($dataJenis as $s) {
        ?>
            <tr>

                <td><?php echo $no; ?></td>
                <td><?php echo $s->jenis_beban; ?></td>

                <td class="text-center">
                    <button class="btn btn-sm btn-outline-success update-dataJenis ion-edit"
                        data-id="<?php echo $s->id_jenis; ?>"></button>
                    <button class="btn btn-sm btn-outline-danger delete-jenis ion-android-delete" data-toggle="modal"
                        data-target="#hapusJenis" data-id="<?php echo $s->id_jenis; ?>"></button>
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
$('#list-jenis').DataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 5
});
       </script>