       <?php
        $no = 1;
        foreach ($dataV as $s) {
        ?>
       <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kode_voucher; ?></td>
           <td><?php echo $s->tgl_awal.' s/d '.$s->tgl_akhir; ?></td>
           <td><?php echo $s->keterangan; ?></td>

           <td class="text-center">
               <button class="btn btn-sm btn-outline-success update-dataVoucher ion-edit"
                   data-id="<?php echo $s->id_voucher; ?>"></button>
               <button class="btn btn-sm btn-outline-danger delete-voucher ion-android-delete" data-toggle="modal"
                   data-target="#hapusVoucher" data-id="<?php echo $s->id_voucher; ?>"></button>
           </td>
       </tr>
       <?php
          $no++;
        }
        ?>

       <script type="text/javascript">
var tableVoucher = $('#list-voucher').DataTable({
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