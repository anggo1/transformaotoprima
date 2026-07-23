<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">

        <?php
    if (!empty($dataPart)) {
      foreach ($dataPart as $part) {
      }
    }
    ?>
        <p></span>
        <h4 style="display:block; text-align:center;">Perubahan Harga Barang</h4>
        </p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>
    <div class="modal-body form">
        <form id="form-update-harga" method="POST">
            <div class="row">
                <div class="col-sm-12" data-spy="scroll" data-offset="0">
                    <div class="panel panel-default">
                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 ">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td width="20%">No Part</td>
                                                            <td width="30%">:
                                                                <?php if (!empty($part->no_part)) { echo $part->no_part;} ?>
                                                            </td>
                                                            <td width="20%">Nama Part</td>
                                                            <td width="30%">:
                                                                <?php if (!empty($part->nama_part)) { echo $part->nama_part;}?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Satuan</td>
                                                            <td>:
                                                                <?php if (!empty($part->satuan)) { echo $part->satuan;}?>
                                                            </td>
                                                            <td>Type</td>
                                                            <td>: <?php if (!empty($part->type)) { echo $part->type;}?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kategori</td>
                                                            <td>:
                                                                <?php if (!empty($part->kategori)) { echo $part->kategori;}?>
                                                            </td>
                                                            <td>Kelompok</td>
                                                            <td>:<?php if (!empty($part->kelompok)) { echo $part->kelompok;}?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Harga Net Awal</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="net_awal" id="net_awal" readonly
                                                    onkeyup="formatNumber(this)" onchange="formatNumber(this);" value="<?php 
                                                            $lokasi=$this->session->userdata['lokasi'];
                                                            if($lokasi=='Jakarta'){
                                            if (!empty($part->hrg_net_jkt)) { echo number_format($part->hrg_net_jkt);
                                            }
                                            }if($lokasi=='Cibitung'){
                                            if (!empty($part->hrg_net_cbt)) { echo number_format($part->hrg_net_cbt);
                                                            }
                                                            }if($lokasi=='Surabaya'){
                                            if (!empty($part->hrg_net_sby)) { echo number_format($part->hrg_net_sby);
                                                                }}?>" class="form-control">
                                            </div>
                                            <label class="col-sm-2 col-form-label">Harga Pricelist awal</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="price_list_awal" id="price_list_awal" readonly
                                                    onkeyup="formatNumber(this)" onchange="formatNumber(this);" value="<?php 
                                                            $lokasi=$this->session->userdata['lokasi'];
                                                            if($lokasi=='Jakarta'){
                                            if (!empty($part->price_list_jkt)) { echo number_format($part->price_list_jkt);
                                            }
                                            }if($lokasi=='Cibitung'){
                                            if (!empty($part->price_list_cbt)) { echo number_format($part->price_list_cbt);
                                                            }
                                                            }if($lokasi=='Surabaya'){
                                            if (!empty($part->price_list_sby)) { echo number_format($part->price_list_sby);
                                                                }}?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Harga Net</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="harga_net" id="harga_net"
                                                    onkeyup="formatNumber(this)" onchange="formatNumber(this);" value="<?php 
                                                            $lokasi=$this->session->userdata['lokasi'];
                                                            if($lokasi=='Jakarta'){
                                            if (!empty($part->hrg_net_jkt)) { echo number_format($part->hrg_net_jkt);
                                            }
                                            }if($lokasi=='Cibitung'){
                                            if (!empty($part->hrg_net_cbt)) { echo number_format($part->hrg_net_cbt);
                                                            }
                                                            }if($lokasi=='Surabaya'){
                                            if (!empty($part->hrg_net_sby)) { echo number_format($part->hrg_net_sby);
                                                                }}?>" class="form-control">
                                            </div>
                                            <label class="col-sm-2 col-form-label">Harga Pricelist</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="price_list" id="price_list"
                                                    onkeyup="formatNumber(this)" onchange="formatNumber(this);" value="<?php 
                                                            $lokasi=$this->session->userdata['lokasi'];
                                                            if($lokasi=='Jakarta'){
                                            if (!empty($part->price_list_jkt)) { echo number_format($part->price_list_jkt);
                                            }
                                            }if($lokasi=='Cibitung'){
                                            if (!empty($part->price_list_cbt)) { echo number_format($part->price_list_cbt);
                                                            }
                                                            }if($lokasi=='Surabaya'){
                                            if (!empty($part->price_list_sby)) { echo number_format($part->price_list_sby);
                                                                }}?>" class="form-control">
                                            </div>
                                        </div>

                                        <input type="hidden" name="id_part"
                                            value="<?php if (!empty($part->id_part)) { echo $part->id_part;} ?>">
                                        <input type="hidden" name="no_part"
                                            value="<?php if (!empty($part->no_part)) { echo $part->no_part;} ?>">
                                        <input type="hidden" name="user" id="user"
                                            value="<?php echo $this->session->userdata['full_name']; ?>"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
            </div>
        </form>
    </div>
</div>