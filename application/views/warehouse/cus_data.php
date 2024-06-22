       <?php
        $no = 1;
        foreach ($dataCus as $s) {
        ?>
       <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kode_cus; ?></td>
           <td><?php echo $s->nama_cus; ?></td>

           <td class="text-center">
               <button class="btn btn-sm btn-outline-success update-dataCustomer ion-edit"
                   data-id="<?php echo $s->id_customer; ?>"></button>
               <button class="btn btn-sm btn-outline-danger delete-customer ion-android-delete" data-toggle="modal"
                   data-target="#hapusCustomer" data-id="<?php echo $s->id_customer; ?>"></button>
           </td>
       </tr>
       <?php
          $no++;
        }
        ?>

       <script type="text/javascript">
var tableCustomer = $('#list-customer').DataTable({
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