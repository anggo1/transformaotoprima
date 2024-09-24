<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="list-data_detail">
            <thead>
                <tr>
                    <th width="5px">No</th>
                    <th>No SPK</th>
                    <th>No Body</th>
                    <th>No Pol</th>
                    <th>Pengemudi</th>
                    <th>No Ket</th>
                    <th>No PK</th>
                    <th>Jenis PK</th>
                    <th>Keterangan</th>
                    <th>Pemborong</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($dataPk as $s){ 
                        $a=$s->tgl_mulai.$s->jam_mulai;
                        $b=$s->tgl_selesai.$s->jam_selesai;

                        //$a=$s->tgl_mulai;
                        //$b=$s->jam_mulai;
                        //$c=$s->tgl_selesai;
                        //$d=$s->jam_selesai;
                        $awal = strtotime($a); 
                        $akhir = strtotime($b);  
                        $diff  = $akhir - $awal;

                        $jam   = floor($diff / (60 * 60));
                        $menit = $diff - ( $jam * (60 * 60) );
                        $detik = $diff % 60;

                        $diff  = date_diff( date_create($s->tgl_mulai), date_create($s->tgl_selesai) );
                        $hari= $diff->format('%a hari');

						?>
                <tr>

                    <td width="5px"><?php echo $s->row_urut; ?></td>
                    <td><?php echo $s->id_lapor; ?></td>
                    <td><?php echo $s->no_body; ?></td>
                    <td><?php echo $s->no_pol; ?></td>
                    <td><?php echo $s->nip_sp.' ('.$s->nama_sp.')'; ?></td>
                    <td width="2%"><?php echo $s->row_no; ?></td>
                    <td width="10%"><?php echo $s->id_pk; ?></td>
                    <td width="15%"><?php echo $s->ket_pk; ?></td>
                    <td width="25%"><?php echo $s->keterangan; ?></td>
                    <td width="10%"><?php echo $s->pt_pemborong; ?></td>
                    <td width="10%"><?php echo $s->pj_borong; ?></td>
                    <td <?php if($s->status == 'A'){echo 'bgcolor="#dc3545"';}
                                if($s->status == 'P'){echo 'bgcolor="yellow"';}
                                if($s->status == 'S'){echo 'bgcolor="#20c997"';} ?>>
                                <?php if($s->status == 'Y'){echo "Aktif";}
                                if($s->status == 'P'){echo "Pending";}
                                if($s->status == 'S'){echo "Selesai";} ?>
                    </td>
                    <td width="10%"><?php echo tglIndoPendek($s->tgl_mulai).' '.$s->jam_mulai; ?></td>
                    <td width="10%"><?php echo tglIndoPendek($s->tgl_selesai).' '.$s->jam_selesai; ?></td>
                    <td><?php if($s->status == 'S'){echo $hari.' '. $jam . ' jam, ' . floor($menit / 60) . ' menit, ';}?></td>
                </tr>
                <?php } ?>

            </tbody>
            <tfoot></tfoot>
        </table>
    </div>
</div>
<script>
$(document).ready(function() {

    var table = $('#list-data_detail').DataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [{
                text: '<i class="fa fa-reply-all"></i> Kembali',
                className: 'btn btn-sm btn-outline-success list-pk',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel',
                footer: true,
                title: 'Data Detail SPK Pertanggal',
                className: 'btn btn-sm btn-outline-success',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                    
        data: table,
        rowsGroup: [0, 1, 2, 3, 4]
                }
            },
        ],
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "processing": true,
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "sPaginationType": "full_numbers",
        "sorting": [
            [0, 'asc']
        ],
        "data": table,
        "rowsGroup": [0, 1, 2, 3, 4],
    });
});
</script>