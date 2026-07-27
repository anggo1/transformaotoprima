
<table class="table table-striped  table-bordered table-hover nowrap responsive" id="list-po">
									<thead>
										<tr>
											<th>No</th>
											<th>No WO</th>
											<th>Tgl WO</th>
											<th>Customer</th>
											<th>Petugas</th>
										</tr>
									</thead>
									<tbody>
                        <?php
            $no = 1;
            foreach ($dataPo as $s) {
            ?>
            <tr>
              <td><?php echo $no; ?></td>
              <td onClick="showPart('<?php echo $s->wo_no; ?>','<?php echo $s->nik; ?>','<?php echo $s->nama; ?>','<?php echo $s->kode_pr; ?>')"><?php echo $s->wo_no; ?></td>
              <td onClick="showPart('<?php echo $s->wo_no; ?>','<?php echo $s->nik; ?>','<?php echo $s->nama; ?>','<?php echo $s->kode_pr; ?>')"><?php echo $s->tgl_estimasi_penawaran; ?></td>
              <td onClick="showPart('<?php echo $s->wo_no; ?>','<?php echo $s->nik; ?>','<?php echo $s->nama; ?>','<?php echo $s->kode_pr; ?>')"><?php echo $s->nama_cus; ?></td>
              <td onClick="showPart('<?php echo $s->wo_no; ?>','<?php echo $s->nik; ?>','<?php echo $s->nama; ?>','<?php echo $s->kode_pr; ?>')"><?php echo $s->received_by; ?></td>
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
