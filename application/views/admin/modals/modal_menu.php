
                        <?php
$no = 1;
foreach ($dataMenu as $s) {
?> <tr>

                            <td><?php echo $no; ?></td>
                            <td><?php echo $s->nama_menu; ?></td>
                            <td>
                                <?php if ($s->view_level=="Y") {?>
                                <div onClick="saveData2(event,'<?php echo $s->id; ?>','<?php echo $s->id_level; ?>')">
                                    <i class="fas fa-check-circle text-success btn"></i>
                                </div>
                                <?php }else{ ?>
                                <div onClick="saveData(event,'<?php echo $s->id; ?>','<?php echo $s->id_level; ?>')">
                                    <i class="fa fa-times-circle text-red btn"></i>
                                </div>
                                <?php } ?>
                            </td>
                            </td>
                        </tr>
                        <?php
    $no++;
}
?>

<script type="text/javascript">
$(function () {
    $('#list-menqu').dataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>