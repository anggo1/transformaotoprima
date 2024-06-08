<div class="table-responsive">
    <table class="table table-bordered table-hover nowrap" id="list-pk">
        <thead>
            <tr>
                <th>No</th>
                <th>Id PK</th>
                <th>No Body</th>
                <th>Pekerjaan</th>
                <th>Pemborong</th>
                <th>Upah</th>
                <th>Sisa</th>
            </tr>
        </thead>
        <tbody id="data-pk">
            <?php
$no = 1;
foreach ($dataPk as $s) {
?> <tr onclick="selectPk('<?php echo $s->no_body; ?>','<?php echo $s->id_pk; ?>','<?php echo $s->ket_pk; ?>','<?php echo $s->pj_borong; ?>','<?php echo $s->biaya_borong; ?>','<?php echo $s->sisanye; ?>')">

                <td><?php echo $no; ?></td>
                <td><?php echo $s->id_pk; ?></td>
                <td><?php echo $s->no_body; ?></td>
                <td><?php echo $s->ket_pk; ?></td>
                <td><?php echo $s->pj_borong; ?></td>
                <td><?php echo number_format($s->biaya_borong); ?></td>
                <td><?php echo number_format($s->sisanye); ?></td>
            </tr>
            <?php
    $no++;
}
?>

        </tbody>
        <tfoot></tfoot>
    </table>
</div>
<script>
    var MyTable = $('#list-pk').dataTable({
    "responsive": false,
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false,
    "pageLength": 5
});
</script>