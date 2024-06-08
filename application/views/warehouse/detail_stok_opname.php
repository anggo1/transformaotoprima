
				<div class="card card-default">
							<h5 style="display:block;"><span class="ion-android-alert ion-lg text-blue"></span>&nbsp; Detail Stok Opname</h5>
							<div class="table-responsive">
								<table class="table table-striped  table-bordered table-hover nowrap" id="list-po">
									<thead>
										<tr>
											<th>No</th>
											<th>No Part</th>
											<th>Nama Part</th>
											<th>Satuan</th>
											<th>jumlah</th>
											<th>Aksi</th>
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
                    <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan" 
                    onclick="this.contentEditable=true; this.className='inEdit';"
                    onblur="this.contentEditable=false; this.className='';" 
                    onkeypress="saveData(event,'<?php echo $s->no_transaksi; ?>','<?php echo $s->id; ?>',$(this).html() )"><?php echo $s->jumlah; ?></td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-outline-danger delete-detail ion-android-delete" data-id="<?php echo $s->id; ?>"></button>
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

           <script language="javascript">
            var MyTable = $('#list-po').dataTable({
              "responsive": false,
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": false,
			        "pageLength": 5, 
              "info": true
            });
	
            function saveData(e,idpo,id,jml_part) {
          if(e.keyCode === 13){
              e.preventDefault();
              $.ajax({
                type: "POST",
                url: "<?php echo base_url('StokOpname/updateDetailSO')?>",
                data: {  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'id': id,
                        'jml_part' :jml_part,
                      },
                success: function(response){ 
                
                  tampilDetail(idpo);
                }
              });
        }  
        }
        </script>
