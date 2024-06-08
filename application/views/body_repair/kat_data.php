       <?php
        $no = 1;
        foreach ($dataKat as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kode; ?></td>
           <td><?php echo $s->kategori; ?></td>

           <td class="text-center">
             <button class="btn btn-sm btn-outline-success update-dataKategori ion-edit" data-id="<?php echo $s->id_kategori; ?>"></button>
             <button class="btn btn-sm btn-outline-danger delete-kategori ion-android-delete" data-toggle="modal" data-target="#hapusKategori" data-id="<?php echo $s->id_kategori; ?>"></button>
           </td>
         </tr>
       <?php
          $no++;
        }
        ?>
       <script type="text/javascript">
         var tableKategori = $('#list-kategori').DataTable({
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