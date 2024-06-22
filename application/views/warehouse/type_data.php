       <?php
        $no = 1;
        foreach ($dataType as $s) {
        ?>
       <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kode_type; ?></td>
           <td><?php echo $s->type; ?></td>

           <td class="text-center">
               <button class="btn btn-sm btn-outline-success update-dataType ion-edit"
                   data-id="<?php echo $s->id_type; ?>"></button>
               <button class="btn btn-sm btn-outline-danger delete-type ion-android-delete" data-toggle="modal"
                   data-target="#hapusType" data-id="<?php echo $s->id_type; ?>"></button>
           </td>
       </tr>
       <?php
          $no++;
        }
        ?>

       <script type="text/javascript">
var tableType = $('#list-type').DataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "pageLength": 5,
    "order": [],
    "columnDefs": [{
        "targets": [0, 3],
        "orderable": false,
    }, ]
});
       </script>