
<div class="modal-content">
      <div class="modal-body form">
        <div class="card card-first card-outline">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped  table-bordered table-hover nowrap responsive" id="list-chasis">
									<thead>
										<tr>
											<th>No</th>
											<th>No Rangka</th>
											<th>Type</th>
											<th>No Mesin</th>
										</tr>
									</thead>
									<tbody>
                        <?php
            $no = 1;
            if (!empty($dataChasis)):
                foreach ($dataChasis as $s):
            ?>
            <tr>
              <td><?php echo $no; ?></td>
              <td onClick="selectData('<?php echo $s->chasis_id; ?>','<?php echo $s->no_rangka; ?>',
              '<?php echo $s->type_body; ?>',
              '<?php echo $s->no_mesin; ?>',
              '<?php echo $s->thn_produksi; ?>')"><?php echo $s->no_rangka; ?></td>
              <td onClick="selectData('<?php echo $s->chasis_id; ?>','<?php echo $s->no_rangka; ?>',
              '<?php echo $s->type_body; ?>',
              '<?php echo $s->no_mesin; ?>',
              '<?php echo $s->thn_produksi; ?>')"><?php echo $s->type_body; ?></td>
              <td onClick="selectData('<?php echo $s->chasis_id; ?>','<?php echo $s->no_rangka; ?>',
              '<?php echo $s->type_body; ?>',
              '<?php echo $s->no_mesin; ?>',
              '<?php echo $s->thn_produksi; ?>')"><?php echo $s->no_mesin; ?></td>
            </tr>
          <?php
              $no++;
                endforeach;
            endif;
            ?>
            </tbody>
            <tfoot></tfoot>
          </table>
            </div>
          </div>
        </div>
      </div>
    </div>

                <script language="javascript">
		var MyTable = $('#list-chasis').DataTable({
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
