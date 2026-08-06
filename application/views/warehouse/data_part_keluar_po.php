
<table class="table table-striped  table-bordered table-hover nowrap responsive" id="list-po">
									<thead>
										<tr>
											<th>No</th>
											<th>No PO</th>
											<th>Tgl PO</th>
											<th>Customer</th>
											<th>Kode</th>
										</tr>
									</thead>
									<tbody>
                        <?php
            $no = 1;
            foreach ($dataPo as $s) {
            ?>
            <tr>
              <td><?php echo $no; ?></td>
              <td onClick="showPart('<?php echo $s->id_po_masuk; ?>','<?php echo $s->kode_po; ?>','<?php echo $s->nama_cus; ?>','<?php echo $s->kode_cus; ?>','<?php echo $s->kode_pesan; ?>','<?php echo $s->keterangan; ?>')"><?php echo $s->id_po_masuk; ?></td>
              <td onClick="showPart('<?php echo $s->id_po_masuk; ?>','<?php echo $s->kode_po; ?>','<?php echo $s->nama_cus; ?>','<?php echo $s->kode_cus; ?>','<?php echo $s->kode_pesan; ?>','<?php echo $s->keterangan; ?>')"><?php echo tglIndoSedang($s->tgl_po); ?></td>
              <td onClick="showPart('<?php echo $s->id_po_masuk; ?>','<?php echo $s->kode_po; ?>','<?php echo $s->nama_cus; ?>','<?php echo $s->kode_cus; ?>','<?php echo $s->kode_pesan; ?>','<?php echo $s->keterangan; ?>')"><?php echo $s->nama_cus; ?></td>
              <td onClick="showPart('<?php echo $s->id_po_masuk; ?>','<?php echo $s->kode_po; ?>','<?php echo $s->nama_cus; ?>','<?php echo $s->kode_cus; ?>','<?php echo $s->kode_pesan; ?>','<?php echo $s->keterangan; ?>')"><?php echo $s->kode_po; ?></td>
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
