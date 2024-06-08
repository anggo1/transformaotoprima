
<?php
$no = 1;
foreach ($dataPk as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->id_pk; ?></td>
        <td><?php echo $s->no_body; ?></td>
        <td><?php echo $s->jns_pk; ?></td>
        <td><?php echo $s->ket_pk; ?></td>

        <td class="text-center">
            <button class="btn btn-xs btn-outline-success detail-part-pk" data-id="<?php echo $s->id_pk; ?>"><i class="fa fa-eye"></i> Detail</button>
            <button class="btn btn-xs btn-outline-primary part-pk" data-id="<?php echo $s->id_pk; ?>" data-spk="<?php echo $s->id_lapor; ?>"><i class="fa fa-share-square"></i> Part Keluar</button>
    </td>
    </tr>
<?php
    $no++;
}
?>
<script language="javascript">
    
    var MyTable = $('#list-pkaktif').DataTable({
        "responsive": true,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 10
    });
</script>