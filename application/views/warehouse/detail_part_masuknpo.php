<style>
.inEdit {
    background-color: #FFFFFF;
    border: 2px solid #000;
    border-radius: 5px;
    padding: 0;
}
</style>
<table class="table table-striped  table-bordered table-hover nowrap" id="listpomasuk">
    <thead>
        <tr>
            <th>No</th>
            <th>No Part</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Stok</th>
            <th>Harga Satuan</th>
            <th>Harga Baru</th>
            <th>Qty Masuk</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        $idlokasi = $this->session->userdata['lokasi'];
            $no = 1;
            foreach ($dataDetail as $d) {
                if($idlokasi=='Cibitung'){
            $stok=$d->stok_cbt;
            $harga=$d->hrg_net_cbt;
                }
          if($idlokasi=='Jakarta'){
            $stok=$d->stok_jkt;
            $harga=$d->hrg_net_jkt;
          $total += $d->hrg_net_jkt* $d->jumlah;
          }
          if($idlokasi=='Surabaya'){
            $stok=$d->stok_sby;
            $harga=$d->hrg_net_sby;
          $total += $d->hrg_net_sby * $d->jumlah;
          }
          //$total += $harga * $d->jumlah;
            ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $d->no_part; ?></td>
            <td><?php echo $d->nama_part; ?></td>
            <td><?php echo $d->nama_satuan; ?></td>
            <td><?php echo $stok; ?></td>
            <td><?php echo $harga; ?></td>
            <td class="qty">
                <input type="number" name="harga[]" id="harga[]"
                    value="<?php if(empty($harga)){echo $harga; }else{echo $harga;} ?>"
                    onkeypress="saveHarga(event,'<?php echo $d->id; ?>','<?php echo $d->id_masuk; ?>',$(this).val() )"
                    class="form-control col-sm-10">
            </td>
            <td class="hrg">
                <input type="number" name="qty_masuk[]" id="qty_masuk[]" value="<?php echo $d->jumlah ?>"
                    onkeypress="saveJumlah(event,'<?php echo $d->id; ?>','<?php echo $d->id_masuk; ?>',$(this).val() )"
                    class="form-control col-sm-10">
                <input type="hidden" name="no_part[]" id="no_part[]" value="<?php echo $d->no_part; ?>">
                <input type="hidden" name="nama_part[]" id="nama_part[]" value="<?php echo $d->nama_part; ?>">
                <input type="hidden" name="satuan[]" id="satuan[]" value="<?php echo $d->satuan; ?>">
                <input type="hidden" name="stok_jkt[]" id="stok_jkt[]" value="<?php echo $d->stok_jkt; ?>">
                <input type="hidden" name="stok_cbt[]" id="stok_cbt[]" value="<?php echo $d->stok_cbt; ?>">
                <input type="hidden" name="stok_sby[]" id="stok_sby[]" value="<?php echo $d->stok_sby; ?>">
            </td>
            <td><?php 
            if(!empty($d->jumlah)){
                 if(empty($harga)) { echo number_format($harga * $d->jumlah);}else{ echo number_format($harga * $d->jumlah);}
            }
             ?></td>
            <td class="text-center">
                <div class="input-group mb-3 danger">
                    <div class="input-group-prepend">
                        <span class="input-group-text btn bg-danger"
                            onclick="delData(event,'<?php echo $d->id; ?>','<?php echo $d->id_masuk; ?>')"><i
                                class="fas fa-trash"></i></span>
                    </div>
                </div>
            </td>
        </tr>
        <?php
            $no++;
            }
            ?>
    </tbody>
    <tfoot></tfoot>
</table>
<script language="javascript">
    
var MyTable = $('#listpomasuk').dataTable({
    "responsive": true,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true
});
function saveHarga(e, id, id_masuk, hrg_part) {
    if (e.keyCode === 13) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Part_masuk_npo/updateHarga')?>",
            data: {
                'id': id,
                'hrg_part': hrg_part,
            },
            success: function(response) {
                tampilDetail(id_masuk);
            }
        });
    }
}

function saveJumlah(e, id, id_masuk, jml_part) {
    if (e.keyCode === 13) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Part_masuk_npo/updateJumlah')?>",
            data: {
                'id': id,
                'jml_part': jml_part,
            },

            success: function(response) {
                tampilDetail(id_masuk);
            }
        });
    }
}

function delData(e, id_detail, id_masuk) {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('Part_masuk_npo/deletepartDetail')?>",
        data: {
            'id_detail': id_detail,
            'id': id_masuk,
        },

        success: function(response) {
            tampilDetail(id_masuk);
        }
    });
}
</script>