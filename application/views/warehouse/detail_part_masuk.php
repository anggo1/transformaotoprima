       <?php
        $no = 1;
        foreach ($dataDetail as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->no_part; ?></td>
           <td><?php echo $s->nama_part; ?></td>
           <td><?php echo $s->jumlah; ?></td>
           <td><?php echo $s->stok; ?></td>
<!--
           <td class="text-center">
             <button class="btn btn-sm btn-outline-danger delete-detail ion-android-delete" data-toggle="modal" data-target="#hapusDetail" data-id="<?php echo $s->id; ?>"></button>
           </td> -->
         </tr>
       <?php
          $no++;
        }
        ?>