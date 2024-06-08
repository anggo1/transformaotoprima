       <?php
        $no = 1;
        foreach ($dataDetail as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->no_body; ?></td>
           <td><?php echo $s->no_pol; ?></td>
           <td><?php echo $s->nama_sp; ?></td>
           <td><?php echo $s->keterangan; ?></td>

           <td class="text-center">
           <button type="button" class="btn btn-xs btn-outline-success cetak-bast" id="cetak" data-id="<?php echo $s->id_bast; ?>"><i class="fas fa-print"></i></button>
             <button class="btn btn-xs btn-outline-danger delete-bast" data-toggle="modal" data-target="#hapusBast" data-id="<?php echo $s->id_bast; ?>"><i class="fas fa-trash"></i></button>
           </td>
         </tr>
       <?php
          $no++;
        }
        ?>