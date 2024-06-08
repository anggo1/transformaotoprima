
<?php
$no = 1;
foreach ($dataBody as $b) {
?>
<tr>
    <td onClick="selectBody('<?php echo $b->id_bast; ?>','<?php echo $b->no_body; ?>','<?php echo $b->no_pol; ?>','<?php echo $b->nip_sp; ?>','<?php echo $b->nama_sp; ?>','<?php echo $b->status_bus; ?>')"><?php echo $no; ?></td>
    <td onClick="selectBody('<?php echo $b->id_bast; ?>','<?php echo $b->no_body; ?>','<?php echo $b->no_pol; ?>','<?php echo $b->nip_sp; ?>','<?php echo $b->nama_sp; ?>','<?php echo $b->status_bus; ?>')"><?php echo $b->no_body; ?></td>
    <td onClick="selectBody('<?php echo $b->id_bast; ?>','<?php echo $b->no_body; ?>','<?php echo $b->no_pol; ?>','<?php echo $b->nip_sp; ?>','<?php echo $b->nama_sp; ?>','<?php echo $b->status_bus; ?>')"><?php echo $b->no_pol; ?></td>
    <td onClick="selectBody('<?php echo $b->id_bast; ?>','<?php echo $b->no_body; ?>','<?php echo $b->no_pol; ?>','<?php echo $b->nip_sp; ?>','<?php echo $b->nama_sp; ?>','<?php echo $b->status_bus; ?>')"><?php echo tglIndoPendek($b->tgl_bast); ?></td>

</tr>
<?php
$no++;
}
?>
 <script type="text/javascript">
         var tableBast = $('#list-bast').DataTable({
           "responsive": false,
           "paging": true,
           "lengthChange": true,
           "searching": true,
           "ordering": true,
           "info": true,
           "autoWidth": true,
           "pageLength": 5
         });
       </script>