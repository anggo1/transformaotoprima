<div class="msg" style="display:none;">
  <?php echo @$this->session->flashdata('msg'); ?>
</div>
            <button class="form-control btn btn-success" data-toggle="modal" data-target="#tambah-jabatan"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Data</button>
            <p class="section-lead">             
            </p>
					
					<div class="row ">
              <div class="col-12 ">
				 <div class="card card-first card-outline">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped  table-bordered table-hover nowrap" id="list-data">
                        <thead>       
        <tr>
         <td>No</td>
    <td>Nama Jabatan</td>				
    <td>Aksi</td>					
      </thead>
      <tbody id="data-jabatan">	

      </tbody>
    </table>
  </div>
</div>
		 
</div>
<?php  //echo $modal_tambah_jabatan; ?><div id="tempat-modal"></div>
</div>

<?php show_my_confirm('konfirmasiHapus', 'hapus-jabatan', 'Hapus Data Ini?', 'Ya, Hapus Data Ini'); ?>