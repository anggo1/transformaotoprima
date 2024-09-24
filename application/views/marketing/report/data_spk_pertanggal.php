<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Tgl</th>
                    <th>No SPK</th>
                    <th>No Body</th>
                    <th>No Pol</th>
                    <th>NIP </th>
                    <th>Nama</th>
                    <th width='5%'>Jml PK</th>
                    <th>Kategori</th>
                    <th>Ket Masuk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
foreach ($dataPk as $s) {
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_masuk); ?></td>
                    <td><?php echo $s->id_lapor; ?></td>
                    <td><?php echo $s->no_body; ?></td>
                    <td><?php echo $s->no_pol; ?></td>
                    <td><?php echo $s->nip_sp; ?></td>
                    <td><?php echo $s->nama_sp; ?></td>
                    <td align="center"><?php echo $s->jml_pk; ?></td>
                    <td><?php echo $s->nama_kategori; ?></td>
                    <td><?php echo $s->keterangan; ?></td>
                    <td><?php if($s->status=="Y") {echo "Proses";}if($s->status=="P") {echo "Pending";}if($s->status=="S") {echo "Selesai";}if($s->status=="K") {echo "Keluar";} if($s->status=="N") {echo "Antrian";}?>
                    </td>
                    <td>
                        <button class="btn btn-xs btn-outline-info cetak-pk" id="cetakPk" title="Cetak PK"
                            data-id="<?php echo $s->id_lapor; ?>"><i class="fa fa-print"></i> Cetak PK</button>
                        <button class="btn btn-xs btn-outline-warning cetak-estimasi" title="Cetak Estimasi"
                            data-id="<?php echo $s->id_lapor; ?>"><i class="fa fa-print"></i> Cetak Estimasi
                        </button>
                    </td>
                </tr>
                <?php
    $no++;
}
?>
            </tbody>
            <tfoot></tfoot>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        var MyTable = $('#list-data').DataTable({
            "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
            "buttons": [
                {
                    text: '<i class="fa fa-indent"></i> Detail',
                    className: 'btn btn-sm btn-outline-primary detail-pk',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    }
                },
                {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
            footer: true,
            title: 'Data SPK Pertanggal',
                    className: 'btn btn-sm btn-outline-primary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
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
        });


    });
    </script>