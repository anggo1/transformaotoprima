<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataV)) {
      foreach ($dataV as $dataV) {
      }
    }
    $ci_kons = get_instance();
		$query = "SELECT max(kode_voucher) AS maxKode FROM tbl_wh_voucher";
		$hasil = $ci_kons->db->query($query)->row_array();
		$noOrder = $hasil['maxKode'];
		$noUrut = (int)substr($noOrder, 3, 4);
		$noUrut++;
		$huruf = "VR-";
		$kode_po  = $huruf . sprintf("%04s", $noUrut);
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($dataV->id_voucher)) {
                                                    echo 'Edit  Voucher';
                                                  } else {
                                                    echo 'Penambahan Data Voucher';
                                                  }
                                                  ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>
    <div class="modal-body form">

        <form <?php if (empty($dataV->id_voucher)) {
            echo 'id="form-tambah-voucher"';
          } else {
            $tgl_awal = $dataV->tgl_awal;
            $tglnye1 = explode('-', $tgl_awal);
            $tgl1 = $tglnye1[2] . "-" . $tglnye1[1] . "-" . $tglnye1[0] . "";
            $tgl_akhir = $dataV->tgl_akhir;
            $tglnye2 = explode('-', $tgl_akhir);
            $tgl2 = $tglnye2[2] . "-" . $tglnye2[1] . "-" . $tglnye2[0] . "";

            echo 'id="form-update-voucher"';
          } 
          
         ?> method="POST">
            <div class="form-group">
                <label class="control-label">Kode Voucher <span class="required danger"> *</span></label>
                <input type="hidden" name="id_voucher" value="<?php if (!empty($dataV->id_voucher)) {
                                                          echo $dataV->id_voucher;
                                                        } ?>">
                <input type="text" class="form-control" placeholder="Kode Voucher" value="<?php
                                                                                    if (!empty($dataV->kode_voucher)) {
                                                                                      echo $dataV->kode_voucher;
                                                                                    } else { echo $kode_po; }
                                                                                    ?>" name="kode_voucher"
                    aria-describedby="sizing-addon2" readonly>
            </div>
            <div class="form-group">
                <label class="control-label">Tgl Awal Berlaku <span class="required red"> *</span></label>
                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                    <input type="text" name="tgl_awal" id="tgl_awal" value="<?php
                                                                                   if (!empty($dataV->tgl_awal)) {
                                                                                    echo $tgl_awal;
                                                                                  }
                                                                                  ?>"
                                                class="form-control tgl_awal datetimepicker" data-toggle="datetimepicker"
                                                data-target=".tgl_awal" data-format="DD-MM-YYYY" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
            </div>
            </div>
            <div class="form-group">
                <label class="control-label">Tgl Akhir Berlaku</label>
                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                    <input type="text" name="tgl_akhir" id="tgl_akhir" value="<?php
                                                                                    if (!empty($dataV->kode_voucher)) {
                                                                                      echo $tgl_akhir;
                                                                                    }
                                                                                    ?>"
                                                class="form-control tgl_akhir datetimepicker" data-toggle="datetimepicker"
                                                data-target=".tgl_akhir" data-format="dDD-MM-YYYY" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
            </div>
            </div>
            <div class="form-group">
                <label class="control-label">Keterangan</label>
                <input type="text" class="form-control" placeholder="Keterangan Voucher" value="<?php
                                                                                      if (!empty($dataV->keterangan)) {
                                                                                        echo $dataV->keterangan;
                                                                                      }
                                                                                      ?>" name="keterangan"
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

<script type="text/javascript">
   $('#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY'
});
</script>