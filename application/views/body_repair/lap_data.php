<?php
$no = 1;
foreach ($dataLap as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->kode; ?></td>
        <td><?php echo $s->keterangan; ?></td>

        <td class="text-center">
            <button class="btn btn-sm btn-outline-success update-dataLaporan ion-edit" data-id="<?php echo $s->id; ?>"></button>
            <button class="btn btn-sm btn-outline-danger delete-laporan ion-android-delete" data-toggle="modal" data-target="#hapusLaporan" data-id="<?php echo $s->id; ?>"></button>
        </td>
    </tr>
<?php
    $no++;
}
?>
<script type="text/javascript">
    var tableLaporan = $('#list-laporan').DataTable({
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