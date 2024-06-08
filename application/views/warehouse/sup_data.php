       <?php
        $no = 1;
        foreach ($dataSup as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kode_sup; ?></td>
           <td><?php echo $s->nama_sup; ?></td>

           <td class="text-center">
             <button class="btn btn-sm btn-outline-success update-dataSupplier ion-edit" data-id="<?php echo $s->id_supplier; ?>"></button>
             <button class="btn btn-sm btn-outline-danger delete-supplier ion-android-delete" data-toggle="modal" data-target="#hapusSupplier" data-id="<?php echo $s->id_supplier; ?>"></button>
           </td>
         </tr>
       <?php
          $no++;
        }
        ?>

       <script type="text/javascript">
         var tableSupplier = $('#list-supplier').DataTable({
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