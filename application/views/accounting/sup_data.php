       <div class="table-responsive">
           <table class="table table-hover w-100" id="list-supplier">
               <thead>
                   <tr>
                       <th>No</th>
                       <th>Kode</th>
                       <th>Nama</th>
                       <th>Akun hutang</th>
                       <th>Nama akun</th>
                       <th>Status</th>
                       <th>Lawan akun</th>
                       <th>Nama lawan akun</th>
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
                       <td><?php echo trim((string) $s->kode_akun) !== '' ? html_escape($s->kode_akun) : '<span class="badge badge-warning">belum diisi</span>'; ?></td>
                       <td><?php echo $s->nama_akun; ?></td>
                       <td><?php echo $s->status_akun; ?></td>
                       <td><?php echo $s->lawan_kode_akun; ?></td>
                       <td><?php echo $s->lawan_nama_akun; ?></td>

                       <td class="text-center">
                           <button class="btn btn-xs btn-outline-success update-dataSupplier ion-edit"
                               data-id="<?php echo $s->id_supplier; ?>"></button>
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
$('#list-supplier').DataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 10
});
       </script>