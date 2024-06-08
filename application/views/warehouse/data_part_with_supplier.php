       <?php
        $no = 1;
        foreach ($dataDetail as $s) {
        ?>
         <tr>

           <td><?php echo $no; ?></td>
           <td><?php echo $s->no_part; ?></td>
           <td><?php echo $s->nama_part; ?></td>
           <td><?php echo $s->stok; ?></td>
           <td><?php echo $s->satuan; ?></td>
         </tr>
       <?php
          $no++;
        }
        ?>
        <script language="javascript">
var MyTable = $('#tablepart').dataTable({
		"responsive": true,
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"ordering": true,
		"info": true
	});
  </script>