
<div class="card card-default">
					<div class="modal-content text-blue">
						<div class="modal-header text-blue">
							<h5 style="display:block; text-align:center;"><span class="ion-android-alert ion-lg text-blue"></span>&nbsp; Detail Stok Opname</h5>
							<div class="text-right">
							</div>
						</div>
<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-striped  table-bordered table-hover nowrap" id="list-po">
									<thead>
										<tr>
											<th>No</th>
											<th>No Part</th>
											<th>Nama Part</th>
											<th>Satuan</th>
											<th>jumlah</th>
										</tr>
									</thead>
									<tbody>
                  <?php
                  $no = 1;
                  foreach ($dataDetail as $s) {
                  ?>
                  <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td><?php echo $s->satuan; ?></td>
                    <td><?php echo $s->jumlah; ?></td>
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

           <script language="javascript">
            var MyTable = $('#list-po').dataTable({
              "responsive": false,
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": false,
              "info": true
            });
        </script>
