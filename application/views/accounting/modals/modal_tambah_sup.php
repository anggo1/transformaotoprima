<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataSup)) {
      foreach ($dataSup as $dataSup) {
      }
    }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($dataSup->id_supplier)) { echo 'Edit Data Akun Supplier'; } else {  echo 'Penambahan Data Supplier'; } ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>
    <table width="100%">
        <tbody>
            <tr>
                <td width="30%">Nama Supplier</td>
                <td width="1%">:</td>
                <td width="68%"><?php if (!empty($dataSup->nama_sup)) { echo $dataSup->nama_sup; } ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?php if (!empty($dataSup->alamat)) { echo $dataSup->alamat; } ?></td>
            </tr>
        </tbody>
    </table>

    <div class="modal-body form">

        <form <?php if (empty($dataSup->id_supplier)) {
            echo 'id="form-tambah-supplier"';
          } else {
            echo 'id="form-update-supplier"';
          } ?> method="POST">
            <div class="form-group">
                <input type="hidden" name="id_supplier" value="<?php if (!empty($dataSup->id_supplier)) { echo $dataSup->id_supplier; } ?>">
                <input type="hidden" class="form-control" placeholder="Kode Supplier" value="<?php if (!empty($dataSup->kode_sup)) { echo  $dataSup->kode_sup;
                                                                                    } else { echo $kode_po; } ?>" name="kode_supplier"
                    aria-describedby="sizing-addon2" readonly>
            </div>
            <div class="form-group">
                <label class="control-label">No Account</label>
                <input type="text" class="form-control" placeholder="Kode Akun" value="<?php if (!empty($dataSup->kode_akun)) { echo $dataSup->kode_akun; } ?>" name="kode_akun"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="form-group">
                <label class="control-label">Nama Account</label>
                <input type="text" class="form-control" placeholder="Nama Akun" value="<?php if (!empty($dataSup->nama_akun)) { echo $dataSup->nama_akun; } ?>" name="nama_akun"
                    aria-describedby="sizing-addon2">
            </div>

            <div class="form-group">
                <label class="control-label">Status</label>
                <select name="status_akun" id="status_akun" class="form-control" required>
                    <option value="">Pilih status...</option>
                    <option value="D">Debit</option>
                    <option value="K">Kredit</option>
                </select>
            </div>
            <div class="form-group">

                <label class="control-label">No Account Lawan</label>
                <input type="text" class="form-control" placeholder="Lawan Kode Akun" value="<?php if (!empty($dataSup->lawan_kode_akun)) { echo $dataSup->lawan_kode_akun; } ?>" name="lawan_kode_akun"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="form-group">
                <label class="control-label">Nama Account Lawan</label>
                <input type="text" class="form-control" placeholder="Nama Lawan Akun" value="<?php if (!empty($dataSup->lawan_nama_akun)) { echo $dataSup->lawan_nama_akun; } ?>" name="lawan_nama_akun"
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