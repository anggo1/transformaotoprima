<style>
.table.dataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 11px;
}

table.dataTable td {
    padding: 3px;
}
</style>
<div class="row">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card ">
                                <div class="modal-body">
                            <div class="table-responsive">
                                <table id="list-saldo" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th height="1%">No</th>
                                            <th>Reference</th>
                                            <th>Code</th>
                                            <th>Account</th>
                                            <th>Debet</th>
                                            <th>Credit</th>
                                            <th>Keterangan</th>
                                            <th>Tgl Input</th>
                                            <?php foreach($viewLevel as $v) { } if ($v->delete_level =='Y') {?>
                                            <th>Aksi</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    $no=0; foreach ($dataSaldo as $a){ $no++; ?>
                                        <tr>
                                            <td><?php echo $no ?></td>
                                            <td><?php echo $a->id_saldo ?></td>
                                            <td><?php echo $a->kode_akun ?></td>
                                            <td><?php echo $a->nama_akun ?></td>
                                            <td><?php echo number_format($a->debit) ?></td>
                                            <td><?php echo number_format($a->kredit) ?></td>
                                            <td><?php echo $a->keterangan ?></td>
                                            <td><?php echo tglIndopendek($a->tgl_insert) ?></td>
                                            <td>
                                                <?php foreach($viewLevel as $v) { } if ($v->delete_level =='Y') {?>
                                                <button type="button" class="btn btn-xs bg-gradient-danger delete-saldo"
                                                    id="delete" data-id="<?php echo $a->id_saldo; ?>"
                                                    title="Delete Data" data-toggle="modal" data-target="#hapusSaldo"><i
                                                        class="fas fa-trash"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                        <div class="card ">
                                <div class="modal-body">
                            <div class="table-responsive">
                                <table id="list-global" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Periode</th>
                                            <th>Code</th>
                                            <th>Debet</th>
                                            <th>Credit</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    $no=0; 
                                    $debit=0;
                                    $kredit=0;
                                    foreach ($saldoGlobal as $s){ 
                                        $kredit += $s->data_kredit;
                                        $debit += $s->data_debit;
                                        $total = $debit - $kredit;
                                        $no++; ?>
                                        <tr>
                                            <td><?php echo $no ?></td>
                                            <td><?php echo $s->periode ?></td>
                                            <td><?php echo $s->kode_akun ?></td>
                                            <td><?php echo number_format($s->data_debit) ?></td>
                                            <td><?php echo number_format($s->data_kredit) ?></td>
                                            <td><?php echo number_format($s->data_debit-$s->data_kredit) ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total Saldo Global</td>
                                            <td><?php echo number_format($total) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
var MyTable = $('#list-saldo').dataTable({
    "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [{
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
            title: 'Report Saldo Awal',
            className: 'btn btn-sm btn-outline-secondary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7]
            }
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Cetak',
            titleAttr: 'Print',
            title: 'Report Saldo Awal',
            className: 'btn btn-sm btn-outline-secondary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7]
            }
        }
    ],
});
var MyTable = $('#list-global').dataTable({
    "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [{
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
            title: 'Report Saldo Awal',
            className: 'btn btn-sm btn-outline-secondary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
            }
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Cetak',
            titleAttr: 'Print',
            title: 'Report Saldo Awal',
            className: 'btn btn-sm btn-outline-secondary',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
            }
        }
    ],
});
</script>