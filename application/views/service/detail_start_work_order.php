<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover nowrap dataTable" id="list-po">
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
                <td><?php echo $s->no_work_order; ?></td>
                <td><?php echo $s->operation; ?></td>
                <td><?php echo $s->hours; ?></td>
                <td><?php echo $s->type_of_work; ?></td>

                <td class="text-center">
                    <button class="btn btn-xs btn-outline-success add-mechanic ion-android-add"
                        onclick="Start('<?php echo $s->id_detail; ?>', '<?php echo $s->no_work_order; ?>')">
                        Start</button>
                    <button class="btn btn-xs btn-outline-warning pause-operation ion-android-delete"
                        onclick="Start('<?php echo $s->id_detail; ?>', '<?php echo $s->no_work_order; ?>')">
                        Pause</button>
                    <button class="btn btn-xs btn-outline-danger complete-operation ion-android-delete"
                        onclick="Start('<?php echo $s->id_detail; ?>', '<?php echo $s->no_work_order; ?>')">
                        Complete</button>
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

function Start(id_detail, no_work_order) {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('WorkOrder/StartWork')?>",
        data: {
            'id_detail': id_detail,
            'no_work_order': no_work_order
        },
        success: function(response) {

            tampilPekerjaan();
        }
    });
}
</script>