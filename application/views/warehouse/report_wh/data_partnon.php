<?php
$no = 1;
foreach ($dataPart as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->id_keluar; ?></td>
        <td><?php echo tglIndoSedang($s->tgl_keluar); ?></td>
        <td><?php echo $s->status; ?></td>
        <td><?php echo $s->no_body; ?></td>
        <td><?php echo $s->keterangan; ?></td>

        <td class="text-center">
            <button class="btn btn-xs btn-outline-primary cetak-part" data-id="<?php echo $s->id_keluar; ?>"><i class="fa fa-print"></i> Print</button>
		<button class="btn btn-xs btn-outline-danger delete-part" data-toggle="modal" data-target="#hapusPart" data-id="<?php echo $s->id_keluar; ?>"><i class="fa fa-trash"></i> Delete</button>
			
    </td>
    </tr>
<?php
    $no++;
}
?>