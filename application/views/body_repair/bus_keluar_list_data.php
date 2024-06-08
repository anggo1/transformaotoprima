<?php
$no = 1;
foreach ($dataPk as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->id_lapor; ?></td>
        <td><?php echo $s->no_body; ?></td>
        <td><?php echo $s->jml_pk.' PK'; ?></td>
        <td><?php echo tglIndoSedang($s->tgl_masuk); ?></td>
        <td><?php echo tglIndoSedang($s->tgl_selesai); ?></td>

        <td class="text-left">
        <button class="btn btn-sm btn-primary selesai-pk-aktif" data-toggle="modal" data-target="#selesaiPk" body-pk="<?php echo $s->no_body; ?>" data-pk="<?php echo $s->id_lapor; ?>"><i class="fa fa-check-double"></i> Keluar</button>
    </td>
    </tr>
<?php
    $no++;
}
?>
