       <div class="table-responsive">
           <table class="table table-hover nowrap w-100" id="list-ref">
               <thead>
                   <tr>
                       <th>No</th>
                       <th>Jenis Akun</th>
                       <th>Keterangan</th>
                       <th>Aksi</th>
                   </tr>
               </thead>
               <tbody>
                   <?php
        $no = 1;
        foreach ($dataRef as $s) {
        ?>
                   <tr>

                       <td><?php echo $no; ?></td>
                       <td><?php echo $s->no_ref; ?></td>
                       <td><?php echo $s->keterangan; ?></td>

                       <td class="text-center">
                           <button class="btn btn-xs btn-outline-success update-dataRef ion-edit"
                               data-id="<?php echo $s->id_ref; ?>"></button>
                           <button class="btn btn-xs btn-outline-danger delete-ref ion-android-delete"
                               data-toggle="modal" data-target="#hapusRef" data-id="<?php echo $s->id_ref; ?>"></button>
                       </td>
                   </tr>
                   <?php
          $no++;
        }
        ?>
               </tbody>
               <tfoot></tfoot>
           </table>
       </div>
       <script language="javascript">
$('#list-ref').DataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 10
});
       </script>