<div class="card">
    <div class="modal-content">
        <div class="card-body">

            <div class="col-12 ">
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered table-hover nowrap" id="list-data">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Ref</th>
                                <th>Tujuan</th>
                                <th>Tgl Keluar</th>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>QTY</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
$no = 1;
foreach ($detailKeluar as $s) {
?> <tr>

                                <td width="2%" align="center"><?php echo $s->row_urut; ?></td>
                                <td><?php echo $s->id_keluar; ?></td>
                                <td><?php echo $s->tujuan; ?></td>
                                <td><?php echo tglIndoSedang($s->tgl_keluar); ?></td>
                                <td width="2%"><?php echo $s->row_no; ?></td>
                                <td><?php echo $s->no_part; ?></td>
                                <td><?php echo $s->nama_part; ?></td>
                                <td><?php echo $s->jumlah; ?></td>
                                <td><?php echo $s->satuan; ?></td>
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
    </div><?php //foreach($detailKeluar as $d){}//echo $d->tgl_keluar;} ?>
    <script>

$(document).ready( function () {
        var table = $('#list-data').DataTable({
            "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [
       // {
       //     extend: 'excelHtml5',
       //     text: '<i class="fas fa-file-excel"></i> Excel',
       //     titleAttr: 'Excel',
	//	footer: true,
      //      title: function() {
      //          return "Report Barang Keluar Dengan PK";
      //          },
      //     className: 'btn btn-sm btn-outline-primary',
      //      init: function(api, node, config) {
      //          $(node).removeClass('btn-secondary')
      //      },
      //      exportOptions: {
      //          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
      //      }
      //  },
      {
            text: '<i class="fa fa-reply-all"></i> Kembali',
            className: 'btn btn-sm btn-outline-primary list-barang',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            }
        },
        {
            text: '<i class="fa fa-print"></i> Cetak',
            className: 'btn btn-sm btn-outline-primary cetak-detail-keluar',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            }
        }
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
            "rowsGroup": [0,1,2,3],
            "columns": [
                { "data": "row_urut", sDefaultContent: "" },
                { "data": "row_no", sDefaultContent: "" },
                { "data": "id_keluar", sDefaultContent: "" },
                { "data": "tujuan", sDefaultContent: "" },
                { sDefaultContent: "" },
                { sDefaultContent: "" },
                { sDefaultContent: "" },
                { sDefaultContent: "" },
                { sDefaultContent: "" }
            ]
        });
}
);
</script>