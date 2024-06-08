
<div class="col-12 col-md-12 col-lg-12">
			<div class="modal-header">

				<?php
if (!empty($dataKategori)){
             foreach ($dataKategori as $dataKategori){				 
			 }}
?>
  <p></span><h4 style="display:block; text-align:center;"><?php if (!empty($dataKategori->id_kategori)) {
                            echo 'Edit Data Kategori';
                        } else { echo 'Penambahan Data Kategori';}
                        ?></h4>
					</p></div><div class="modal-body">
  <form <?php if (empty($dataKategori->id_kategori)) {echo 'id="form-tambah-kategori"';} else { echo 'id="form-update-kategori"';}?> method="POST">
    <div class="form-group">
      
      <input type="hidden" name="id_kategori" value="<?php if (!empty($dataKategori->id_kategori)) { echo $dataKategori->id_kategori; } ?>">
          </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Kode Kategori" value="<?php
                        if (!empty($dataKategori->kode)) {
                            echo $dataKategori->kode;
                        }
                        ?>" name="kode" aria-describedby="sizing-addon2">	

    </div>
	  <div class="input-group form-group">
      <input type="text" class="form-control" placeholder="Nama Kategori" value="<?php
                        if (!empty($dataKategori->kategori)) {
                            echo $dataKategori->kategori;
                        }
                        ?>" name="kategori" aria-describedby="sizing-addon2">	

    </div>

	  <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
              </div></form>
    </div>
</div>
</div>
</div>