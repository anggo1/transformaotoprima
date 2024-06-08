<?php
$no = 1;
function if_exist_check($PIN, $DateTime){
    $data = $this->db->get_where('tbl_hrd_data_absen', array('pin' => $PIN, 'date_time' => $DateTime))->row();
    return $data;
}
foreach ($dataDev as $s) {
    $IP = $s->ip;
    $key = $s->pass;
    $hasil = "";
    $connect = fsockopen($s->ip, "80", $errno, $errstr, 1);
    if ($connect) {
        $hasil = "<i class='fas fa-check-circle text-success btn'> OK</i>";
        error_reporting(0);
        $IP = $s->ip;
        $Key = $key;
      
    } else {
        $hasil = "<i class='fa fa-times-circle text-red btn'> False</i>";
    }
?>
    <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->ip; ?></td>
        <td><?php echo $s->pass; ?></td>
        <td><?php echo $s->nama_mesin; ?></td>
        <td><?php echo $hasil; ?></td>

        <td class="text-center">
            <button class="btn btn-sm btn-outline-primary download-dataMesin" data-ip="<?php echo $s->ip; ?>" data-pass="<?php echo $s->pass; ?>">
                <i class="fa fa-download"></i>
            </button>
            <button class="btn btn-sm btn-outline-success update-dataMesin" data-id="<?php echo $s->id; ?>">
                <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger delete-mesin" data-toggle="modal" data-target="#hapusMesin" data-id="<?php echo $s->id; ?>">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
<?php
    $no++;
}
?>