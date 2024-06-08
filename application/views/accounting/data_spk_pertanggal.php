<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>No SPK</th>
                    <th>No Body</th>
                    <th>Tgl Masuk</th>
                    <th>Kategori</th>
                    <th>Keterangan Masuk</th>
                    <th>Pool</th>
                    <th>Status</th>
                    <th>Biaya</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$grand_total=0;
foreach ($dataPk as $s) {
    $grand_total += $s->total_harga;
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->id_lapor; ?></td>
                    <td><?php echo $s->no_body; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_masuk); ?></td>
                    <td><?php echo $s->nama_kategori; ?></td>
                    <td><?php echo $s->keterangan; ?></td>
                    <td><?php echo $s->nama_pool; ?></td>
                    <td><?php if($s->status=="Y") {echo "Proses";}if($s->status=="P") {echo "Pending";}if($s->status=="S") {echo "Selesai";}if($s->status=="K") {echo "Keluar";} if($s->status=="N") {echo "Antrian";}?>
                    </td>
                    <td align="right"><?php echo number_format($s->total_harga); ?></td>
                    <td align="center">
                        <button class="btn btn-xs btn-outline-success cetak-pk" id="cetakPk" title="Cetak PK"
                            data-id="<?php echo $s->id_lapor; ?>"><i class="fa fa-print"></i> Cetak PK</button>
                    </td>
                </tr>
                <?php
    $no++;
}
?>
            </tbody>
            <tfoot>
                <th  colspan="8" style="text-align: right; font-size:larger;">GRAND TOTAL</th>
                <th style="text-align: right; font-size:larger;"><?php echo number_format($grand_total) ?></th>
                    <th></th>
                </tfoot>
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
                    text: '<i class="fa fa-print"></i> Cetak',
                    className: 'btn btn-sm btn-outline-primary cetak-list-pk',
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary')
                    }
                },
                {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
            footer: true,
            title: 'Data Penggunaan Barang Dengan SPK',
                    className: 'btn btn-sm btn-outline-primary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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