       <?php
        $no = 1;
        foreach ($dataKet as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->remark; ?></td>

           <td class="text-center">
             <button class="btn btn-sm btn-outline-success update-dataType ion-edit" data-id="<?php echo $s->id_detail_note; ?>"></button>
             <button class="btn btn-sm btn-outline-danger delete-type ion-android-delete" data-toggle="modal" data-target="#hapusType" data-id="<?php echo $s->id_detail_note; ?>"></button>
           </td>
         </tr>
       <?php
          $no++;
        }
        ?>

       <script type="text/javascript">
         var tableType = $('#list-keterangan').DataTable({
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