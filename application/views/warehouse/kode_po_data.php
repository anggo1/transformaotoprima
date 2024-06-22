<?php
$no = 1;
foreach ($dataPo as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->kode_po; ?></td>
        <td><?php echo $s->keterangan; ?></td>

        <td class="text-center">
            <button class="btn btn-sm btn-outline-success update-dataKode-po ion-edit" data-id="<?php echo $s->id_kode_po; ?>"></button>
            <button class="btn btn-sm btn-outline-danger delete-kode-po ion-android-delete" data-toggle="modal" data-target="#hapusKode_po" data-id="<?php echo $s->id_kode_po; ?>"></button>
        </td>
    </tr>
<?php
    $no++;
}
?>
<script type="text/javascript">
    var tablePo = $('#list-po').DataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "pageLength": 5,
        "order": [],
        "columnDefs": [{
            "targets": [0,3],
            "orderable": false,
        }, ]
    });
</script>