<div class="card">
    <div class="modal-content">
        <div class="card-body">

            <div class="col-12 ">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover nowrap" id="list-data">
                        <thead>
                            <tr>
                                <th width='5%'>No</th>
                                <th width="15%">Tgl Keluar</th>
                                <th width="11%">No Bon</th>
                                <th width="18%">Tujuan</th>
                                <th width="18%">Divisi</th>
                                <th width="38%">Keterangan</th>
                                <th width="13%" class = "noprint">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$no = 1;
foreach ($dataKeluar as $s) {
?> <tr>

                                <td><?php echo $no; ?></td>
                                <td><?php echo tglIndoSedang($s->tgl_keluar); ?></td>
                                <td><?php echo $s->id_keluar; ?></td>
                                <td><?php echo $s->tujuan; ?></td>
                                <td><?php echo $s->nama_divisi; ?></td>
                                <td><?php echo $s->keterangan; ?></td>
                                <td class="noprint">
                                <button type="button" class="btn btn-xs bg-gradient-success cetak-bon" id="cetakBon"
                                        data-id="<?php echo $s->id_keluar; ?>" title="Cetak Bon"><i
                                            class="fas fa-print"></i> Bon</button>
                                <?php foreach($viewLevel as $v) { } if ($v->delete_level =='Y') {?>
                                    <button type="button" class="btn btn-xs bg-gradient-danger delete-detailnon" id="delete"
                                        data-id="<?php echo $s->id_keluar; ?>" data-status="<?php echo $s->status; ?>" title="Delete Data"  data-toggle="modal" data-target="#hapusDetail"><i
                                            class="fas fa-trash"></i> Delete</button>
                                    <?php } ?>
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
        </div>
    </div>
    <script>
    var MyTable = $('#list-data').DataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [
        //{
        //    extend: 'excelHtml5',
        //    text: '<i class="fas fa-file-excel"></i> Excel',
        //    titleAttr: 'Excel',
		//	footer: true,
        //    title: function() {
        //        return "<div style='font-size: 20px;'>Report Barang Keluar Dengan PK</div>";
        //        },
        //    className: 'btn btn-sm btn-outline-primary',
        //    init: function(api, node, config) {
        //        $(node).removeClass('btn-secondary')
        //    },
        //    exportOptions: {
        //        columns: [0, 1, 2, 3, 4, 5]
        //    }
        //},
        {
            text: '<i class="fa fa-list-ol"></i> Detail',
            className: 'btn btn-sm btn-outline-primary detail-barang',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            }
        },
        {
            text: '<i class="fa fa-print"></i> Cetak',
            className: 'btn btn-sm btn-outline-primary cetak-keluar-data',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            }
        }
    ],
        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 10
    });
    </script>