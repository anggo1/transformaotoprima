<style>
.table.dataTable {
    font-size: 12px;
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
    <div class="modal-content text-dark">
        <div class="modal-header text-dark">
            <h5 style="display:block; text-align:center;"><span class="ion-android-alert ion-lg text-blue"></span>&nbsp;
                Detail Part Penawaran</h5>
            <div class="text-right">
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped  table-bordered table-hover nowrap dataTable" id="list-po">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Part Number</th>
                            <th>Part Name</th>
                            <th>Stock</th>
                            <th>Price List</th>
                            <th>Discount</th>
                            <th>Net Price</th>
                            <th>Qty</th>
                            <th>Ammount</th>
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
                            <td><?php echo number_format($s->harga); ?></td>
                            <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan"
                                onclick="this.contentEditable=true; this.className='inEdit';"
                                onblur="this.contentEditable=false; this.className='inEdit';"
                                onkeypress="saveDiskon(event,'<?php echo $s->id_estimasi_penawaran; ?>','<?php echo $s->id_detail; ?>','<?php echo $s->harga; ?>',$(this).html() )">
                                <?php echo $s->diskon; ?></td>
                            <td><?php echo number_format($s->harga_net); ?></td>
                            <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan"
                                onclick="this.contentEditable=true; this.className='inEdit';"
                                onblur="this.contentEditable=false; this.className='inEdit';"
                                onkeypress="saveData(event,'<?php echo $s->id_estimasi_penawaran; ?>','<?php echo $s->id_detail; ?>','<?php echo $s->harga_net; ?>',$(this).html() )">
                                <?php echo $s->jumlah; ?></td>
                            <td><?php echo number_format($s->harga_net*$s->jumlah); ?></td>
                            <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan"
                                onclick="this.contentEditable=true; this.className='inEdit';"
                                onblur="this.contentEditable=false; this.className='inEdit';"
                                onkeypress="saveRemark(event,'<?php echo $s->id_estimasi_penawaran; ?>','<?php echo $s->id_detail; ?>',$(this).html() )">
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
function saveDiskon(e, idpo, id, hrg_part, diskon) {
    if (e.keyCode === 13) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('EstimasiPenawaran/updateDiskon')?>",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                'id': id,
                'hrg_part': hrg_part,
                'diskon': diskon,
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
            url: "<?php echo base_url('EstimasiPenawaran/updateDetailPo')?>",
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
            url: "<?php echo base_url('EstimasiPenawaran/updateRemark')?>",
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