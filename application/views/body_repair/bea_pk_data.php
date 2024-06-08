<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-pk">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No SPK</th>
                    <th>No Body</th>
                    <th>Tgl Masuk</th>
                    <th>Jumlah PK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
foreach ($dataPk as $s) {
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->id_lapor; ?></td>
                    <td><?php echo $s->no_body; ?></td>
                    <td><?php echo tglIndoSedang($s->tgl_masuk); ?></td>
                    <td><?php echo $s->jml_pk.' PK'; ?></td>

                    <td>
                        <button class="btn btn-xs btn-outline-success cetak-pk" id="cetakPk" title="Cetak PK"
                            data-id="<?php echo $s->id_lapor; ?>"><i class="fa fa-eye"></i> Proses</button>
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