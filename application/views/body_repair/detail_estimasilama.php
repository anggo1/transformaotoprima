                                <div class="modal-body form">
                <div class="card card-first card-outline">
                    <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-estimasi">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Body</th>
                                                    <th>PK</th>
                                                    <th>No Part</th>
                                                    <th>Nama Part</th>
                                                    <th>Jumlah</th>
                                                    <th width="5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
      <?php
        $no = 1;
        foreach ($dataEstimasi as $s) {
          $id=$s->id_detail;
        ?>
        <tr>

          <td><?php echo $no; ?></td>
          <td><?php echo $s->no_body; ?></td>
          <td><?php echo $s->jns_pk; ?></td>
          <td><?php echo $s->no_part; ?></td>
          <td><?php echo $s->nama_part; ?></td>
          <td contenteditable="true" data-id="<?php echo $s->id_detail; ?>" onblur="fungsiUpdate(this,'name','<?php echo $s->id_detail; ?>')"><?php echo $s->jml_part; ?></td>

          <td class="text-center">
            <button class="btn btn-xs btn-outline-danger delete-estimasi" data-toggle="modal" data-target="#hapusEstimasi" data-id="<?php echo $s->id_detail; ?>"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
      <?php
          $no++;
        }
        ?>
        </tbody>
            </tbody>
          <tfoot></tfoot>
        </table>
        </div>
        </div>
        </div>
        </div>
        <script type="text/javascript">
					var tableEstimasi = $('#list-estimasi').dataTable({
						"responsive": false,
						"paging": true,
						"lengthChange": false,
						"searching": true,
						"ordering": false,
						"info": true,
						"autoWidth": true,
						"pageLength": 10
						});
        </script>