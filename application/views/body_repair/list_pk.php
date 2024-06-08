<div class="modal-body form">
    <div class="card card-first card-outline">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table no-wrap table-hover nowrap" id="list-pk">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Laporan</th>
                            <th>Jenis PK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
        $no = 1;
        foreach ($dataPk as $s) {
        ?>
                        <tr>

                            <td><?php echo $no; ?></td>
                            <td><?php echo $s->id_lapor; ?></td>
                            <td><?php echo $s->jns_pk; ?></td>

                            <td class="text-center">
                                <?php if($s->status=='N'){ ?>
                                <button
                                    class="btn btn-xs btn-outline-success update-pk"
                                    title="Proses PK"
                                    kode="<?php echo $s->jns_pk; ?>"
                                    data-id="<?php echo $s->id_lapor; ?>">
                                    <i class="fa fa-user-clock">
                                        Proses</i>
                                    <?php } if($s->status=='Y') { echo "";}?>
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
    </div>
</div>