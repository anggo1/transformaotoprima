  <div class="col-12 col-md-12 col-lg-12">
  <div class="modal-header">

    <?php
    if (!empty($dataCus)) {
      foreach ($dataCus as $dataCus) {
      }
    }
    $ci_kons = get_instance();
		$query = "SELECT max(kode_cus) AS maxKode FROM tbl_wh_customer";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 3, 4);
		$noUrut++;
		$huruf = "CS-";
		$kode_po  = $huruf . sprintf("%04s", $noUrut);
    ?>
    <p></span>
    <h4 style="display:block; text-align:center;"><?php if (!empty($dataCus->id_customer)) {
                                                    echo 'Edit  Customer';
                                                  } else {
                                                    echo 'Penambahan Data Customer';
                                                  }
                                                  ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>

  </div>
  <div class="modal-body form">

    <form <?php if (empty($dataCus->id_customer)) {
            echo 'id="form-tambah-customer"';
          } else {
            echo 'id="form-update-customer"';
          } ?> method="POST">
      <div class="form-group">
        <label class="control-label">Kode Customer <span class="required"> *</span></label>
        <input type="hidden" name="id_customer" value="<?php if (!empty($dataCus->id_customer)) {
                                                          echo $dataCus->id_customer;
                                                        } ?>">
        <input type="text" class="form-control" placeholder="Kode Customer" value="<?php
                                                                                    if (!empty($dataCus->kode_cus)) {
                                                                                      echo $dataCus->kode_cus;
                                                                                    } else { echo $kode_po; }
                                                                                    ?>" name="kode_customer" aria-describedby="sizing-addon2" readonly>
      </div>
      <div class="form-group">
        <label class="control-label">Nama Customer <span class="required"> *</span></label>
        <input type="text" class="form-control" placeholder="Nama Customer" value="<?php
                                                                                    if (!empty($dataCus->nama_cus)) {
                                                                                      echo $dataCus->nama_cus;
                                                                                    }
                                                                                    ?>" name="nama_customer" aria-describedby="sizing-addon2" require>
      </div>
      <div class="form-group">
        <label class="control-label">Alamat</label>
        <input type="text" class="form-control" placeholder="Alamat Customer" value="<?php
                                                                                      if (!empty($dataCus->alamat)) {
                                                                                        echo $dataCus->alamat;
                                                                                      }
                                                                                      ?>" name="alamat" aria-describedby="sizing-addon2">
      </div>
      <div class="form-group">
        <label class="control-label">Kota</label>
        <input type="text" class="form-control" placeholder="Kota Customer" value="<?php
                                                                                      if (!empty($dataCus->kota)) {
                                                                                        echo $dataCus->kota;
                                                                                      }
                                                                                      ?>" name="kota" aria-describedby="sizing-addon2">
      </div>
      <div class="form-group">
        <label class="control-label">No Telp</label>
        <input type="text" class="form-control" placeholder="No Telp Customer" value="<?php
                                                                                      if (!empty($dataCus->no_tlp)) {
                                                                                        echo $dataCus->no_tlp;
                                                                                      }
                                                                                      ?>" name="no_tlp" aria-describedby="sizing-addon2">
      </div>
      <div class="form-group">
        <label class="control-label">Kontak Personal</label>
        <input type="text" class="form-control" placeholder="Kontak Personal" value="<?php
                                                                                      if (!empty($dataCus->tlp_person)) {
                                                                                        echo $dataCus->tlp_person;
                                                                                      }
                                                                                      ?>" name="tlp_person" aria-describedby="sizing-addon2">
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
      </div>
    </form>
  </div>
</div>
</div>