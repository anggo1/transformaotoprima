<?php
$no = 1;
foreach ($dataAbsen as $s) {
?>
    <tr>

        <td><?php echo $s->pin; ?></td>
        <td><?php echo $s->DateTime; ?></td>
        <td><?php echo $s->Ver; ?></td>
        <td><?php echo $s->ver; ?></td>
        <td><?php echo $s->status; ?></td>
    </tr>
<?php
    $no++;
}
?>