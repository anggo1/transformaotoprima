       <?php
  $no = 1;
  foreach ($dataKdcuti as $s) {
    ?>
       <tr back>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->kode; ?></td>
           <td><?php echo $s->nama_cuti; ?></td>

           <td class="text-center">
               <button class="btn btn-xs btn-outline-success update-dataKdcuti"
                   data-id="<?php echo $s->id_kodecuti; ?>"><i class="fa fa-edit"></i></button>
               <button class="btn btn-xs btn-outline-danger delete-kdcuti" data-toggle="modal"
                   data-target="#hapusKdcuti" data-id="<?php echo $s->id_kodecuti; ?>"><i
                       class="fa fa-trash"></i></button>
           </td>
       </tr>
       <?php
	 $no++;
  }
?>
       <script type="text/javascript">
var tableCt = $('#list-kdcuti').DataTable({
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