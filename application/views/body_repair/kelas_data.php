       <?php
        $no = 1;
        foreach ($dataKl as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kelas; ?></td>

           <td class="text-center">
             <button class="btn btn-sm btn-outline-success update-dataKelas ion-edit" data-id="<?php echo $s->id_kelas; ?>"></button>
             <button class="btn btn-sm btn-outline-danger delete-kelas ion-android-delete" data-toggle="modal" data-target="#hapusKelas" data-id="<?php echo $s->id_kelas; ?>"></button>
           </td>
         </tr>
       <?php
          $no++;
        }
        ?>
       <script type="text/javascript">
         var tableKelas = $('#list-kelas').DataTable({
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