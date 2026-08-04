    <div class="modal-content">
      <div class="modal-body form">
        <div class="card card-first card-outline">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped  table-bordered table-hover nowrap responsive" id="list-customer">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Detail</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  if (!empty($dataCustomer)):
                    foreach ($dataCustomer as $s):
                  ?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td onClick="selectCustomer('<?php echo $s->kode_cus; ?>','<?php echo $s->nama_cus; ?>',
              '<?php echo $s->alamat; ?>',
              '<?php echo $s->no_tlp; ?>',
              '<?php echo $s->tlp_person; ?>')"><?php echo $s->kode_cus; ?></td>
                        <td onClick="selectCustomer('<?php echo $s->kode_cus; ?>','<?php echo $s->nama_cus; ?>',
              '<?php echo $s->alamat; ?>',
              '<?php echo $s->no_tlp; ?>',
              '<?php echo $s->tlp_person; ?>')"><?php echo $s->nama_cus; ?></td>
                        <td onClick="selectCustomer('<?php echo $s->kode_cus; ?>','<?php echo $s->nama_cus; ?>',
              '<?php echo $s->alamat; ?>',
              '<?php echo $s->no_tlp; ?>',
              '<?php echo $s->tlp_person; ?>')"><?php echo $s->detail; ?></td>
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

<script language="javascript">
  var MyTable = $('#list-customer').DataTable({
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