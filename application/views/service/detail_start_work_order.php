<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover nowrap dataTable" id="list-po">
        <thead>
            <tr>
                <th>No</th>
                <th>SPK</th>
                <th>Operation</th>
                <th>Hours</th>
                <th>Start Date</th>
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
                <td><?php echo $s->spk; ?></td>
                <td><?php echo $s->operation; ?></td>
                <td><?php echo $s->jumlah; ?></td>
                <td><?php echo $s->start_date; ?></td>
                <td><?php echo $s->type_of_work; ?></td>
                <td class="text-center">
                    <?php if($s->status=='N' or $s->status=='P') {?>
                    <button class="btn btn-xs btn-outline-success add-mechanic ion-android-play"
                        onclick="Start('<?php echo $s->id_detail; ?>', '<?php echo $s->spk; ?>')">
                        Start</button>
                    <?php }if($s->status=='R') {?>
                    <button class="btn btn-xs btn-outline-warning pause-operation ion-android-pause"
                        onclick="Pause('<?php echo $s->id_detail; ?>', '<?php echo $s->spk; ?>', '<?php echo $s->start_date; ?>')">
                        Pause</button>
                    <button class="btn btn-xs btn-outline-danger complete-operation ion-android-stop"
                        onclick="End('<?php echo $s->id_detail; ?>', '<?php echo $s->spk; ?>', '<?php echo $s->start_date; ?>')">
                        Complete</button>
                    <?php } if($s->status=='F') { echo 'Finish';}?>
                </td>
            </tr>
            <?php
                    $no++;
                }
                ?>
        </tbody>
        <tfoot></tfoot>
    </table>
    <button class="btn btn-xl bg-gradient-red hapus-work-order" title="Edit" data-id="<?php echo $s->wo_no; ?>"><i class="fa fa-stop"></i> Finish
                </button>
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

function Pause(id_detail, no_work_order, start_date) {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('WorkOrder/PauseWork')?>",
        data: {
            'id_detail': id_detail,
            'no_work_order': no_work_order,
            'start_date': start_date
        },
        success: function(response) {

            tampilPekerjaan();
        }
    });
}

function End(id_detail, no_work_order, start_date) {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('WorkOrder/EndWork')?>",
        data: {
            'id_detail': id_detail,
            'no_work_order': no_work_order,
            'start_date': start_date
        },
        success: function(response) {

            tampilPekerjaan();
        }
    });
}
</script>