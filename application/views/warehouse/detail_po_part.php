<style>
.inEdit {
    background-color: #FFFFFF;
    border: 2px solid #000;
    border-radius: 5px;
    padding: 25;
}
</style>
<table class="table table-striped  table-bordered table-hover nowrap" id="listpomasuk">
    <thead>
        <tr>
            <th>No</th>
            <th>No Part</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Hrg Satuan</th>
            <th>Qty PO</th>
            <th>Qty Terima</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
            foreach ($dataPo as $s) {
            ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $s->no_part; ?></td>
            <td><?php echo $s->nama_part; ?></td>
            <td><?php echo $s->satuan; ?></td>
            <td align="right"><?php echo number_format($s->harga) ?></td>
            <td><?php echo $s->sisa; ?></td>
            <td class="qty"><input type="number" name="qty_masuk[]" id="qty_masuk[]"
                    value="<?php echo $s->jml_masuk; ?>"
                    onkeypress="saveData(event,'<?php echo $s->id_detail; ?>','<?php echo $s->jumlah; ?>',$(this).val() )"
                    class="form-control col-sm-10">
                <input type="hidden" name="harga[]" id="harga[]" value="<?php echo $s->harga; ?>">
                <input type="hidden" name="no_part[]" id="no_part[]" value="<?php echo $s->no_part; ?>">
                <input type="hidden" name="nama_part[]" id="nama_part[]" value="<?php echo $s->nama_part; ?>">
                <input type="hidden" name="satuan[]" id="satuan[]" value="<?php echo $s->satuan; ?>">
                <input type="hidden" name="stok[]" id="stok[]" value="<?php echo $s->stok_akhir; ?>">
                <input type="hidden" name="stok_jkt[]" id="stok_jkt[]" value="<?php echo $s->stok_jkt; ?>">
                <input type="hidden" name="stok_cbt[]" id="stok_cbt[]" value="<?php echo $s->stok_cbt; ?>">
                <input type="hidden" name="stok_sby[]" id="stok_sby[]" value="<?php echo $s->stok_sby; ?>">
            </td>
            <td><?php echo number_format($s->jml_masuk * $s->harga); ?></td>
            <td class="text-center">
			<div class="input-group mb-3 danger">
                  <div class="input-group-prepend">
                    <span class="input-group-text btn bg-danger" onclick="delData(event,'<?php echo $s->id_detail; ?>','<?php echo $s->sisa; ?>')"><i class="fas fa-trash"></i></span>
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
    "responsive": false,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": false,
    "info": true
});

function saveData(e, id, qty_awal, qty_masuk) {
    var id_po = document.getElementById("id_po").value;
    var no_po = document.getElementById("no_po").value;
    var status = document.getElementById("status").value;
    var kode_sup = document.getElementById("kode_sup").value;
    var supplier = document.getElementById("supplier").value;
    if (e.keyCode === 13) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Part_masuk/updatePart')?>",
            data: {
                'id': id,
                'qty_awal': qty_awal,
                'qty_masuk': qty_masuk,
            },

            success: function(response) {
                showPart(id_po, no_po, kode_sup, supplier, status);
            }
        });
    }
}
function delData(e, id, sisa) {
    var id_po = document.getElementById("id_po").value;
    var no_po = document.getElementById("no_po").value;
    var status = document.getElementById("status").value;
    var kode_sup = document.getElementById("kode_sup").value;
    var supplier = document.getElementById("supplier").value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Part_masuk/deletepartDetail')?>",
            data: {
                'id': id,
                'sisa': sisa,
            },

            success: function(response) {
                showPart(id_po, no_po, kode_sup, supplier, status);
            }
        });
    }
</script>