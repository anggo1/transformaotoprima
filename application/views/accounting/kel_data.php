<div class="table-responsive">
    <table class="table table-bordered table-hover nowrap" id="list-kelompok">
        <thead>
            <tr>
                <th>No</th>
                <th>Kelompok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $no = 1;
        foreach ($dataKelompok as $s) {
        ?>
            <tr>

                <td><?php echo $no; ?></td>
                <td><?php echo $s->kelompok; ?></td>

                <td class="text-center">
                    <button class="btn btn-sm btn-outline-success update-dataKelompok ion-edit"
                        data-id="<?php echo $s->id_kelompok; ?>"></button>
                    <button class="btn btn-sm btn-outline-danger delete-kelompok ion-android-delete" data-toggle="modal"
                        data-target="#hapusKelompok" data-id="<?php echo $s->id_kelompok; ?>"></button>
                </td>
            </tr>
            <?php
          $no++;
        }
        ?>
            <script type="text/javascript">
            </script>
        </tbody>
        <tfoot></tfoot>
    </table>
</div>
<script language="javascript">
$('#list-kelompok').DataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 5
});
       </script>