<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-akun">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Akun</th>
                    <th>Kelompok</th>
                    <th>Type</th>
                    <th>Jenis Beban</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
$no = 1;
foreach ($dataPk as $s) {
?> <tr>

                <td><?php echo $no; ?></td>
                <td><?php echo $s->kode_akun; ?></td>
                <td><?php echo $s->nama_akun; ?></td>
                <td><?php echo $s->kelompok; ?></td>
                <td><?php echo $s->type; ?></td>
                <td><?php echo $s->jenis_beban; ?></td>

                <td class="text-center">
                    <button class="btn btn-xs btn-outline-success update-akun" id="edit_akun" title="Edit Akun"
                        data-id="<?php echo $s->id; ?>"><i class="fa fa-edit"></i> Edit</button>
             <button class="btn btn-xs btn-outline-danger delete-akun" data-toggle="modal" title="Hapus Akun" data-target="#hapusAkun" data-id="<?php echo $s->id; ?>"><i class="fa fa-trash"></i>  Hapus</button>
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
<script type="text/javascript">
    var tableLaporan = $('#list-akun').dataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [
        //{
        //    extend: 'excelHtml5',
        //    text: '<i class="fas fa-file-excel"></i> Excel',
        //    titleAttr: 'Excel',
        //    title: 'Report Saldo Awal',
        //    className: 'btn btn-sm btn-outline-secondary',
        //    init: function(api, node, config) {
        //        $(node).removeClass('btn-secondary')
        //    },
        //    exportOptions: {
        //        columns: [0, 1, 2, 3, 4, 5]
        //    }
        //},
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Cetak',
            titleAttr: 'Print',
            title: 'Daftar Akun',
            className: 'btn btn-sm btn-outline-secondary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
            }
        }
    ],
        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "pageLength": 10
    });
</script>