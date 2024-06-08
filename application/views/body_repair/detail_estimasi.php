<style>
.inEdit{
      background-color: #FFFFFF;
      border: 1px solid #333 ;
      border-radius: 5px;
      padding:2px 2px 2px 2px;
}
</style>
<div class="modal-body form">
                <div class="card card-first card-outline">
                    <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="list-estimasi">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Body</th>
                                                    <th>PK</th>
                                                    <th>No Part</th>
                                                    <th>Nama Part</th>
                                                    <th>Jumlah</th>
                                                    <th>Balance %</th>
                                                    <th>Total</th>
                                                    <th width="5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
      <?php
        $no = 1;
        foreach ($dataEstimasi as $s) {
          $id=$s->id_detail;
          $jml=$s->jml_part;
        ?>
        <tr>

          <td><?php echo $no; ?></td>
          <td><?php echo $s->no_body; ?></td>
          <td><?php echo $s->jns_pk; ?></td>
          <td><?php echo $s->no_part; ?></td>
          <td><?php echo $s->nama_part; ?></td>
          <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan" onclick="this.contentEditable=true; this.className='inEdit';" onblur="this.contentEditable=false; this.className='';" onkeypress="saveData(event,'<?php echo $id; ?>','<?php echo $s->hrg_part; ?>',$(this).html() )"><?php echo $s->jml_part; ?></td>
          <td><?php echo number_format($s->hrg_naik); ?></td>
          <td><?php echo number_format($s->total); ?></td>
          <td class="text-center">
            <button class="btn btn-xs btn-outline-danger delete-estimasi" data-id="<?php echo $s->id_detail; ?>"><i class="fas fa-trash"></i></button>
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
						"ordering": true,
						"info": true,
						"autoWidth": true,
						"pageLength": 10,
            "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem('offersDataTables', JSON.stringify(oData));
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse(localStorage.getItem('offersDataTables'));
        }
						});
  function saveData(e,id,hrg_part,jml_part) {
  if(e.keyCode === 13){
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Estimator/updateEstimasi')?>",
        data: {  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                'id': id,
                'hrg_part' :hrg_part,
                'jml_part' :jml_part,
              },
        success: function(response){ 
        
          tampilEstimasi();
        }
      });
 }  
}
        </script>