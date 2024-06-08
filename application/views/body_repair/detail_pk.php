<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-detail">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Detail</th>
                    <th width="5%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                                            $no = 1;
                                            foreach ($dataDetail as $s) {
                                            ?> <tr>

                    <td width="5%"><?php echo $no; ?></td>
                    <td><?php echo $s->ket_detail; ?></td>

                    <td width="5%" class="text-center">
                        <button class="btn btn-xs btn-outline-danger delete-detail"
                            data-id="<?php echo $s->id_detail; ?>"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php
                                                $no++;
                                            }
                                            ?>
            </tbody>
            </tbody>
            <tfoot></tfoot>
        </table>
    </div>
</div>
</div>
<script language="javascript">
function refreshDetail() {
    MyDetail = $('#list-detail').dataTable();
}
var MyDetail = $('#list-detail').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "pageLength": 5
});
</script>