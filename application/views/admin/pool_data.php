       <?php
        $no = 1;
        foreach ($dataPl as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kode_kota; ?></td>
           <td><?php echo $s->nama_kota; ?></td>
           <td><?php echo $s->wilayah; ?></td>

           <td class="text-center">
             <button class="btn btn-sm btn-outline-success update-dataPool ion-edit" data-id="<?php echo $s->id_kota; ?>"></button>
             <button class="btn btn-sm btn-outline-danger delete-pool ion-android-delete" data-toggle="modal" data-target="#hapusPool" data-id="<?php echo $s->id_kota; ?>"></button>
           </td>
         </tr>
       <?php
          $no++;
        }
        ?>
       <script type="text/javascript">
         var tablePool = $('#list-pool').DataTable({
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