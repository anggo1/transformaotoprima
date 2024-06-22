       <?php
        $no = 1;
        foreach ($dataKel as $s) {
        ?>
       <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kode_kelompok; ?></td>
           <td><?php echo $s->kelompok; ?></td>

           <td class="text-center">
               <button class="btn btn-sm btn-outline-success update-dataKelompok ion-edit"
                   data-id="<?php echo $s->id_kelompok; ?>"></button>
               <button class="btn btn-sm btn-outline-danger delete-kelompok ion-android-delete" data-toggle="modal"
                   data-target="#hapusKelompok" data-id="<?php echo $s->id_kelompok; ?>"></button>
           </td>
       </tr>
       <?php
          $no++;
        }
        ?>
       <script type="text/javascript">
var tableKelompok = $('#list-kelompok').DataTable({
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