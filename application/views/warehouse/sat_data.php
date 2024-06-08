<?php
$no = 1;
foreach ($dataSat as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->kode_satuan; ?></td>
        <td><?php echo $s->satuan; ?></td>

        <td class="text-center">
            <button class="btn btn-sm btn-outline-success update-dataSatuan ion-edit" data-id="<?php echo $s->id_satuan; ?>"></button>
            <button class="btn btn-sm btn-outline-danger delete-satuan ion-android-delete" data-toggle="modal" data-target="#hapusSatuan" data-id="<?php echo $s->id_satuan; ?>"></button>
        </td>
    </tr>
<?php
    $no++;
}
?>
<script type="text/javascript">
    var tableSatuan = $('#list-satuan').DataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "pageLength": 5
    });
</script>