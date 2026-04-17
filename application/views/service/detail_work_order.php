
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover nowrap dataTable" id="list-po">
                    <thead>
                        <tr>
                            <th>No</th>
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
                            <td><?php echo $s->operation; ?></td>
                            <td><?php echo $s->hours; ?></td>
                            <td><?php echo $s->type_of_work; ?></td>
                           
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger delete-operation ion-android-delete"
                                    data-id="<?php echo $s->id_detail; ?>"></button>
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
var MyTable = $('#list-po').dataTable({
    "responsive": false,
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false
});
</script>