       <?php
        $no = 1;
        foreach ($dataKet as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->keterangan; ?></td>

           <td class="text-center">
             <button class="btn btn-sm btn-outline-danger delete-keterangan ion-android-delete" data-toggle="modal" data-target="#hapusType" data-id="<?php echo $s->id_ket_spk; ?>"></button>
           </td>
         </tr>
       <?php
          $no++;
        }
        ?>

       <script type="text/javascript">
         var tableKeterangan = $('#list-keterangan').dataTable({
           "responsive": false,
           "paging": true,
           "lengthChange": false,
           "searching": false,
           "ordering": false,
           "info": false,
           "autoWidth": true,
           "pageLength": 5
         });
       </script>