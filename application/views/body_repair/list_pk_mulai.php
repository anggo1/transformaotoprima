<div class="modal-body form">
    
<button class="btn btn-warning cetak-pk" id="cetakPk" title="Cetak Estimasi"><i class="fa fa-print"></i> Cetak PK
								</button>
    <div class="card card-first card-outline">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table no-wrap table-hover nowrap" id="list-pk-mulai">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No PK</th>
                            <th>Kode PK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
        $no = 1;
        foreach ($dataMulai as $s) {
        ?>
                        <tr>

                            <td><?php echo $no; ?></td>
                            <td><?php echo $s->id_pk; ?></td>
                            <td><?php echo $s->jns_pk; ?></td>

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