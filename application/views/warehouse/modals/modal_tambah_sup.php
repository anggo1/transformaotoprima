<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataSup)) {
      foreach ($dataSup as $dataSup) {
      }
    }
    $ci_kons = get_instance();
		$query = "SELECT max(kode_sup) AS maxKode FROM tbl_wh_supplier";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 3, 4);
		$noUrut++;
		$huruf = "SP-";
		$kode_po  = $huruf . sprintf("%04s", $noUrut);
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($dataSup->id_supplier)) {
                                                    echo 'Edit  Supplier';
                                                  } else {
                                                    echo 'Penambahan Data Supplier';
                                                  }
                                                  ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>
    <div class="modal-body form">

        <form <?php if (empty($dataSup->id_supplier)) {
            echo 'id="form-tambah-supplier"';
          } else {
            echo 'id="form-update-supplier"';
          } ?> method="POST">
            <div class="form-group">
                <input type="hidden" name="id_supplier" value="<?php if (!empty($dataSup->id_supplier)) {
                                                          echo $dataSup->id_supplier;
                                                        } ?>">
                <input type="hidden" class="form-control" placeholder="Kode Supplier" value="<?php
                                                                                    if (!empty($dataSup->kode_sup)) {
                                                                                      echo $dataSup->kode_sup;
                                                                                    } else { echo $kode_po; }
                                                                                    ?>" name="kode_sup"
                    aria-describedby="sizing-addon2" readonly>
            </div>
            <div class="form-group">
                <label class="control-label">Nama Singkat Supplier <span class="required red"> *</span></label>
                <input type="text" class="form-control" placeholder="Nama Supplier" value="<?php
                                                                                    if (!empty($dataSup->kode_nama)) {
                                                                                      echo $dataSup->kode_nama;
                                                                                    }
                                                                                    ?>" name="kode_nama"
                    aria-describedby="sizing-addon2" require>
            </div>
            <div class="form-group">
                <label class="control-label">Nama Supplier <span class="required red"> *</span></label>
                <input type="text" class="form-control" placeholder="Nama Supplier" value="<?php
                                                                                    if (!empty($dataSup->nama_sup)) {
                                                                                      echo $dataSup->nama_sup;
                                                                                    }
                                                                                    ?>" name="nama_supplier"
                    aria-describedby="sizing-addon2" require>
            </div>
            <div class="form-group">
                <label class="control-label">Detail Supplier</label>
                <input type="text" class="form-control" placeholder="Detail Supplier" value="<?php
                                                                                    if (!empty($dataSup->detail)) {
                                                                                      echo $dataSup->detail;
                                                                                    }
                                                                                    ?>" name="detail"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="form-group">
                <label class="control-label">Alamat</label>
                <input type="text" class="form-control" placeholder="Alamat Supplier" value="<?php
                                                                                      if (!empty($dataSup->alamat)) {
                                                                                        echo $dataSup->alamat;
                                                                                      }
                                                                                      ?>" name="alamat"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="form-group">
                <label class="control-label">Telphone Perusahaan</label>
                <input type="text" class="form-control" placeholder="No Telp Supplier" value="<?php
                                                                                      if (!empty($dataSup->no_tlp)) {
                                                                                        echo $dataSup->no_tlp;
                                                                                      }
                                                                                      ?>" name="no_tlp"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="form-group">
                <label class="control-label">No Fax</label>
                <input type="text" class="form-control" placeholder="No Fax Supplier" value="<?php
                                                                                      if (!empty($dataSup->no_fax)) {
                                                                                        echo $dataSup->no_fax;
                                                                                      }
                                                                                      ?>" name="no_fax"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="form-group">
                <label class="control-label">No Telp</label>
                <input type="text" class="form-control" placeholder="Kontak Personal" value="<?php
                                                                                      if (!empty($dataSup->tlp_person)) {
                                                                                        echo $dataSup->tlp_person;
                                                                                      }
                                                                                      ?>" name="tlp_person"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
            </div>
        </form>
    </div>
</div>
</div>