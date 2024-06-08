<style>
.inEdit {
    background-color: #FFFFFF;
    border: 1px solid #333;
    border-radius: 5px;
    padding: 2px 2px 2px 2px;
}
</style>
<div class="content">
    <div class="modal-header">
        <h5 style="display:block; text-align:center;"><span class="ion-android-alert ion-lg text-blue"></span>&nbsp;
            Proses Input Stok Opname</h5>
        <div class="text-right">
        </div>
    </div>
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-striped  table-bordered table-hover nowrap" id="list-po">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelompok</th>
                        <th>Jenis Barang</th>
                        <th>Lokasi</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Stok System</th>
                        <th>Stok Fisik</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  $no = 1;
                  foreach ($dataDetail as $s) {
                  ?>
                    <tr>

                        <td><?php echo $no; ?></td>
                        <td><?php echo $s->kelompok; ?></td>
                        <td><?php echo $s->type; ?></td>
                        <td><?php echo $s->lokasi; ?></td>
                        <td><?php echo $s->no_part; ?></td>
                        <td><?php echo $s->nama_part; ?></td>
                        <td><?php echo $s->satuan; ?></td>
                        <td><?php echo $s->stok_lama; ?></td>
                        <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan"
                            onclick="this.contentEditable=true; this.className='inEdit';"
                            onblur="this.contentEditable=false; this.className='';"
                            onkeypress="saveData(event,'<?php echo $s->id; ?>','<?php echo $s->id_opname; ?>',$(this).html() )">
                            <?php echo $s->stok_fisik; ?></td>
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

<script language="javascript">
var MyTable = $('#list-po').dataTable({
  stateSave: true,
    "dom": "<'row'<'text-left'l><'col-sm-1 text-left'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [{
        extend: 'print',
        text: '<i class="fas fa-print"></i> Cetak',
        titleAttr: 'Print',
        title: 'Data Barang',
        className: 'btn btn-outline-secondary',
        init: function(api, node, config) {
            $(node).removeClass('btn-secondary')
        },
        exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
        }
    }],
    "responsive": false,
    "paging": true,
    "lengthChange": true,
    "searching": false,
    "ordering": true,
    "pageLength": 10,
    "lengthMenu": [
        [-1, 10, 25, 50],
        ['Seluruhnya', 10, 25, 50],

    ],
});

function saveData(e, id, id_opname, stok_fisik) {
    if (e.keyCode === 13) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('StokOpname/updateDetailSO')?>",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                'id': id,
                'stok_fisik': stok_fisik,
            },
            success: function(response) {
                tampilKelompokUpdate(id_opname);
            }
        });
    }
}
</script>