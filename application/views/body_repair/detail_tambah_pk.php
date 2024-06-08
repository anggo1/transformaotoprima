<?php
        $no = 1;
        foreach ($dataPk as $s) {
        ?>
                                    <tr>

                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $s->id_pk; ?></td>
                                        <td><?php echo $s->jns_pk; ?></td>
                                        <td><?php echo $s->ket_pk; ?></td>
                                        <td><?php if($s->status=='Y') {echo 'Aktif';}if($s->status=='P') {echo 'Pending';}if($s->status=='S') {echo 'Selesai';} ?></td>
                                        <td><?php echo $s->pt_pemborong.'|'.$s->pj_borong; ?></td>
                                    </tr>
                                    <?php
          $no++;
        }
        ?>
         <script type="text/javascript">          

        </script>