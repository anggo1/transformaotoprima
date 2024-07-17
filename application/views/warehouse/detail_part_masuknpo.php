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
            $no = 1;
            foreach ($dataDetail as $s) {
            ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $s->no_part; ?></td>
            <td><?php echo $s->nama_part; ?></td>
            <td><?php echo $s->nama_satuan; ?></td>
            <td><?php echo $s->stok; ?></td>
            <td><?php echo $s->harga_baru; ?></td>
            <td class="qty">
              <input type="number" name="harga[]" id="harga[]"
              value="<?php if(empty($s->hrg_part)){echo $s->harga_baru; }else{echo $s->hrg_part;} ?>"
                    onkeypress="saveHarga(event,'<?php echo $s->id; ?>','<?php echo $s->id_masuk; ?>',$(this).val() )"
                    class="form-control col-sm-10">
            </td>
            <td class="hrg">
              <input type="number" name="qty_masuk[]" id="qty_masuk[]"
                    value="<?php echo $s->jumlah ?>"
                    onkeypress="saveJumlah(event,'<?php echo $s->id; ?>','<?php echo $s->id_masuk; ?>',$(this).val() )"
                    class="form-control col-sm-10">
                <input type="hidden" name="no_part[]" id="no_part[]" value="<?php echo $s->no_part; ?>">
                <input type="hidden" name="nama_part[]" id="nama_part[]" value="<?php echo $s->nama_part; ?>">
                <input type="hidden" name="satuan[]" id="satuan[]" value="<?php echo $s->satuan; ?>">
                <input type="hidden" name="stok[]" id="stok[]" value="<?php echo $s->stok; ?>">
                <input type="hidden" name="stok_jkt[]" id="stok_jkt[]" value="<?php echo $s->stok_jkt; ?>">
                <input type="hidden" name="stok_cbt[]" id="stok_cbt[]" value="<?php echo $s->stok_cbt; ?>">
                <input type="hidden" name="stok_sby[]" id="stok_sby[]" value="<?php echo $s->stok_sby; ?>">
            </td>
            <td><?php 
            if(!empty($s->jumlah)){
                 if(empty($s->hrg_part)) { echo number_format($s->harga_baru * $s->jumlah);}else{ echo number_format($s->hrg_part * $s->jumlah);}
            }
             ?></td>
            <td class="text-center">
			<div class="input-group mb-3 danger">
                  <div class="input-group-prepend">
                    <span class="input-group-text btn bg-danger" onclick="delData(event,'<?php echo $s->id; ?>','<?php echo $s->id_masuk; ?>')"><i class="fas fa-trash"></i></span>
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
    
function saveHarga(e, id,id_masuk,hrg_part) {
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
function saveJumlah(e, id,id_masuk,jml_part) {
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
function delData(e, id_detail,id_masuk) {
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