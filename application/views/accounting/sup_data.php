       <div class="table-responsive">
           <table class="table table-bordered table-hover nowrap" id="list-supplier">
               <thead>
                   <tr>
                       <th>No</th>
                       <th>Kode</th>
                       <th>Nama</th>
                       <th>NoACC</th>
                       <th>Nama Acc</th>
                       <th>Status</th>
                       <th>No Acc</th>
                       <th>Nama Acc</th>
                       <th>Aksi</th>
                   </tr>
               </thead>
               <tbody>
                   <?php
        $no = 1;
        foreach ($dataSup as $s) {
        ?>
                   <tr>

                       <td><?php echo $no; ?></td>
                       <td><?php echo $s->kode_sup; ?></td>
                       <td><?php echo $s->nama_sup; ?></td>
                       <td><?php echo $s->kode_akun; ?></td>
                       <td><?php echo $s->nama_akun; ?></td>
                       <td><?php echo $s->status_akun; ?></td>
                       <td><?php echo $s->lawan_kode_akun; ?></td>
                       <td><?php echo $s->lawan_nama_akun; ?></td>

                       <td class="text-center">
                           <button class="btn btn-sm btn-outline-success update-dataSupplier ion-edit"
                               data-id="<?php echo $s->id_supplier; ?>"></button>

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
$('#list-supplier').DataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 5
});
       </script>