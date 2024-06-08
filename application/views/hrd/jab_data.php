       <?php
  $no = 1;
  foreach ($datajab as $s) {
    ?>
       <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->jabatan; ?></td>

           <td class="text-center">
               <button class="btn btn-xs btn-outline-success update-dataJabatan"
                   data-id="<?php echo $s->id_jabatan; ?>"><i class="fa fa-edit"></i> </button>
               <button class="btn btn-xs btn-outline-danger delete-jabatan" data-toggle="modal"
                   data-target="#hapusJabatan" data-id="<?php echo $s->id_jabatan; ?>"><i
                       class="fa fa-trash"></i></button>
           </td>
       </tr>
       <?php
	 $no++;
  }
?>
       <script type="text/javascript">
var tableJabatan = $('#list-jabatan').DataTable({
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