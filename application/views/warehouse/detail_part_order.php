<style>
.table.dataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
}

table.dataTable td {
    padding: 5px 5px 5px 5px;
}

.inEdit {
    background-color: #FFFFFF;
    border: 0px solid #000;
    border-radius: 5px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.94);
}
</style>
<div class="card card-default">
    <div class="modal-content text-blue">
        <div class="modal-header text-blue">
            <h5 style="display:block; text-align:center;"><span class="ion-android-alert ion-lg text-blue"></span>&nbsp;
                Detail Part Order</h5>
            <div class="text-right">
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped  table-bordered table-hover nowrap dataTable" id="list-po">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Part</th>
                            <th>Nama Part</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>BO</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Remark</th>
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
                            <td><?php echo $s->stok_akhir; ?></td>
                            <td><?php echo $s->satuan; ?></td>
                            <td><?php echo number_format($s->harga); ?></td>
                            <td>
                                <input type="checkbox" id="bo" name="bo" <?php if($s->bo == 'Y') echo 'checked' ?>
                                    onClick="saveBo(event,'<?php echo $s->id_part_order; ?>','<?php echo $s->id_detail; ?>','Y' )">
                                <label for="minimum">Y
                                </label>
                                <input type="checkbox" id="bo" name="bo" <?php if($s->bo == 'N') echo 'checked' ?>
                                    onClick="saveBo(event,'<?php echo $s->id_part_order; ?>','<?php echo $s->id_detail; ?>','N' )">
                                <label for="minimum">N
                                </label>
                                <input type="checkbox" id="bo" name="bo" <?php if(empty($s->bo)) echo 'checked' ?>
                                    onClick="saveBo(event,'<?php echo $s->id_part_order; ?>','<?php echo $s->id_detail; ?>','' )">
                                <label for="minimum">E
                                </label>
                            </td>
                            <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan"
                                onclick="this.contentEditable=true; this.className='inEdit';"
                                onblur="this.contentEditable=false; this.className='inEdit';"
                                onkeypress="saveData(event,'<?php echo $s->id_part_order; ?>','<?php echo $s->id_detail; ?>','<?php echo $s->harga; ?>',$(this).html() )">
                                <?php echo $s->jumlah; ?></td>
                            <td><?php echo number_format($s->harga*$s->jumlah); ?></td>
                            <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan"
                                onclick="this.contentEditable=true; this.className='inEdit';"
                                onblur="this.contentEditable=false; this.className='inEdit';"
                                onkeypress="saveRemark(event,'<?php echo $s->id_part_order; ?>','<?php echo $s->id_detail; ?>',$(this).html() )">
                                <?php echo $s->remark; ?></td>

                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger delete-detail ion-android-delete"
                                    data-id="<?php echo $s->id_detail; ?>"></button>
                            </td>
                        </tr>
                        <?php
                    $no++;
                  }
                  ?>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script language="javascript">
var MyTable = $('#list-po').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": true
});

function saveBo(e, idpo, id, bo) {
    if (e.type == "click" || e.keyCode == 27) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('PartOrder/updateBo')?>",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                'id': id,
                'bo': bo,
            },
            success: function(response) {

                tampilDetail(idpo);
            }
        });
    }
}

function saveData(e, idpo, id, hrg_part, jml_part) {
    if (e.keyCode === 13) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('PartOrder/updateDetailPo')?>",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                'id': id,
                'hrg_part': hrg_part,
                'jml_part': jml_part,
            },
            success: function(response) {

                tampilDetail(idpo);
            }
        });
    }
}

function saveRemark(e, idpo, id, remark) {
    if (e.keyCode === 13) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('PartOrder/updateRemark')?>",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                'id': id,
                'remark': remark,
            },
            success: function(response) {

                tampilDetail(idpo);
            }
        });
    }
}
</script>