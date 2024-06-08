       <?php
  $no = 1;
  foreach ($dataDep as $s) {
    ?>
       <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->departement; ?></td>

           <td class="text-center">
               <button class="btn btn-xs btn-outline-success update-dataDepartement"
                   data-id="<?php echo $s->id_departement; ?>"><i class="fa fa-edit"></i></button>
               <button class="btn btn-xs btn-outline-danger delete-departement" data-toggle="modal"
                   data-target="#hapusDepartement" data-id="<?php echo $s->id_departement; ?>"><i
                       class="fa fa-trash"></i></button>
           </td>
       </tr>
       <?php
	 $no++;
  }
?>
       <script type="text/javascript">
var tableDepartement = $('#list-departement').DataTable({
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