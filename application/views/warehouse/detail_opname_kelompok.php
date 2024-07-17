

<div class="content">
						<div class="modal-header">
							<h5 style="display:block; text-align:center;"><span class="ion-android-alert ion-lg text-blue"></span>&nbsp; Stok Opname Perkelompok</h5>
							<div class="text-right">
							</div>
						</div>
<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover nowrap" id="list-po">
									<thead>
										<tr>
											<th>No</th>
											<th>Kelompok</th>
											<th>Jenis Barang</th>
											<th>Lokasi</th>
											<th>Kode Barang</th>
											<th>Nama Barang</th>
											<th>Satuan</th>
											<th>Stok System</th>
											<th style="background-color: lightgrey;">Stok Fisik</th>
										</tr>
									</thead>
									<tbody>
                  <?php
                  $no = 1;
                  foreach ($dataDetail as $s) {
                  ?>
                  <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->kelompok; ?></td>
                    <td><?php echo $s->type; ?></td>
                    <td><?php echo $s->lokasi_part; ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td><?php echo $s->satuan; ?></td>
                    <td><?php echo $s->stok_barang; ?></td>
                    <td style="background-color: lightgrey;"></td>
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

           <script language="javascript">
            var MyTable = $('#list-po').dataTable({
        "dom": "<'row'<'text-left'l><'col-sm-1 text-left'B><'col-sm-4 text-right'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Cetak',
                titleAttr: 'Print',
                title: 'Data Barang',
                className: 'btn btn-outline-secondary',init: function (api, node, config) {
                $(node).removeClass('btn-secondary') },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                }
            }
        ],
              "responsive": false,
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": false,
			        "pageLength": 10, 
              "info": true
            });
        </script>
