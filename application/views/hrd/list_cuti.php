       <?php
        $no = 1;
        foreach ($dataCuti as $s) {
        ?>
           <tr>

               <td><?php echo $no; ?></td>
               <td><?php echo $s->nip; ?></td>
               <td><?php echo $s->nama_depan; ?></td>
               <td><?php echo $s->departement; ?></td>
               <td><?php echo $s->tgl_cuti; ?></td>
               <td><?php echo $s->alasan; ?></td>

               <td class="text-center">
                   <button class="btn btn-xs btn-outline-danger delete-cuti" data-toggle="modal" data-target="#hapusCuti" data-id="<?php echo $s->id_cuti; ?>"><i class="fa fa-trash"></i></button>
               </td>
           </tr>
       <?php
            $no++;
        }
        ?>