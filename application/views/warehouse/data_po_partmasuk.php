<?php
$no = 1;
foreach ($dataPart as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <?php 
        if(!empty($s->supplier)){
        ?>
        <td  onClick="selectPart('<?php echo $s->no_part; ?>','<?php echo $s->nama_part; ?>','<?php echo $s->hrg_awal; ?>','<?php echo $s->stok; ?>','<?php echo $s->stok_a; ?>','<?php echo $s->stok_p; ?>','<?php echo $s->jumlah; ?>','<?php echo $s->supplier; ?>')"><?php echo $s->no_part; ?></td>
        <td  onClick="selectPart('<?php echo $s->no_part; ?>','<?php echo $s->nama_part; ?>','<?php echo $s->hrg_awal; ?>','<?php echo $s->stok; ?>','<?php echo $s->stok_a; ?>','<?php echo $s->stok_p; ?>','<?php echo $s->jumlah; ?>','<?php echo $s->supplier; ?>')"><?php echo $s->nama_part; ?></td>
            <?php } else {?>
        <td  onClick="selectPart('<?php echo $s->no_part; ?>','<?php echo $s->nama_part; ?>','<?php echo $s->hrg_awal; ?>','<?php echo $s->stok; ?>','<?php echo $s->stok_a; ?>','<?php echo $s->stok_p; ?>')"><?php echo $s->no_part; ?></td>
        <td  onClick="selectPart('<?php echo $s->no_part; ?>','<?php echo $s->nama_part; ?>','<?php echo $s->hrg_awal; ?>','<?php echo $s->stok; ?>','<?php echo $s->stok_a; ?>','<?php echo $s->stok_p; ?>')"><?php echo $s->nama_part; ?></td>
            

                <?php } ?>
    </tr>
<?php
    $no++;
}
?>
<script type="text/javascript">
    
	var tablePart = $('#table-part').DataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "pageLength": 5
    });
</script>