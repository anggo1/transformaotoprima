       <?php
  $no = 1;
  foreach ($datajab as $s) {
    ?>
    <tr>
    
    <td><?php echo $no; ?></td>
    <td><?php echo $s->jabatan; ?></td>

      <td class="text-center">
        <button class="btn btn-sm btn-outline-success update-dataJabatan" data-id="<?php echo $s->id_jabatan; ?>"><i class="glyphicon glyphicon-repeat"></i> Edit</button>
		  <button class="btn btn-sm btn-outline-danger delete-jabatan" data-toggle="modal" data-target="#konfirmasiHapus" data-id="<?php echo $s->id_jabatan; ?>"><i class="fa fa-delete"></i> Hapus</button>
      </td>
    </tr>
    <?php
	 $no++;
  }
?> 