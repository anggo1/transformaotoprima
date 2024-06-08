
<table class="table table-striped  table-bordered table-hover nowrap responsive" id="list-po">
									<thead>
										<tr>
											<th>No</th>
											<th>No PO</th>
											<th>Tgl Po</th>
											<th>Supplier</th>
											<th>Keterangan</th>
										</tr>
									</thead>
									<tbody>
                        <?php
            $no = 1;
            foreach ($dataPo as $s) {
            ?>
            <tr>
              <td><?php echo $no; ?></td>
              <td onClick="showPart('<?php echo $s->id_po; ?>','<?php echo $s->kode_po; ?>','<?php echo $s->kode_sup; ?>','<?php echo $s->supplier; ?>','<?php echo $s->status; ?>')"><?php echo $s->kode_po; ?></td>
              <td onClick="showPart('<?php echo $s->id_po; ?>','<?php echo $s->kode_po; ?>','<?php echo $s->kode_sup; ?>','<?php echo $s->supplier; ?>','<?php echo $s->status; ?>')"><?php echo $s->tgl_po; ?></td>
              <td onClick="showPart('<?php echo $s->id_po; ?>','<?php echo $s->kode_po; ?>','<?php echo $s->kode_sup; ?>','<?php echo $s->supplier; ?>','<?php echo $s->status; ?>')"><?php echo $s->supplier; ?></td>
              <td onClick="showPart('<?php echo $s->id_po; ?>','<?php echo $s->kode_po; ?>','<?php echo $s->kode_sup; ?>','<?php echo $s->supplier; ?>','<?php echo $s->status; ?>')"><?php echo $s->keterangan; ?></td>
            </tr>
          <?php
              $no++;
            }
            ?>
            </tbody>
            <tfoot></tfoot>
          </table>

                <script language="javascript">
		var MyTable = $('#list-po').DataTable({
				"responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "pageLength": 5
			});
        </script>
