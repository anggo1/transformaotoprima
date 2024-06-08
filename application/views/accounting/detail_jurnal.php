
<?php if(empty($dataDetail )){echo "";}else{?>
<style>
.table.dataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 11px;
}

table.dataTable td {
    padding: 2px;
}
</style>
<table width="100%" class="table table-striped  table-bordered table-hover nowrap" id="detail-jurnal">
    <thead>
        <tr>
            <th>No</th>
            <th>No Jurnal</th>
            <th>Kode</th>
            <th>Akun</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
    $t_debit=0;
    $t_kredit=0;
    foreach ($dataDetail as $s) {
        $t_debit += $s->debit;
        $t_kredit += $s->kredit;
        $total2=$t_debit-$t_kredit;
        $total=$t_debit-$t_kredit;
        if ($total==0) {
            $total = '<font color=green>BALANCE</font>';
        } else {
            $total=number_format($t_debit-$t_kredit);
        }
        ?>
        <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $s->no_jurnal; ?></td>
            <td><?php echo $s->kode_akun; ?></td>
            <td><?php echo $s->nama_akun; ?></td>
            <td align="right"><?php echo number_format($s->debit); ?></td>
            <td align="right"><?php echo number_format($s->kredit); ?></td>
            <td><?php echo $s->keterangan; ?></td>
            <td class="text-center">
                    <button class="btn btn-sm bg-gradient-red" onclick="delData(event,'<?php echo $s->id_jurnal; ?>')"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
        <?php
        $no++;
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="text-align: right;"><?php if(!empty($t_debit)) {
                echo number_format($t_debit);
            } ?></th>
            <th style="text-align: right;"><?php if(!empty($t_kredit)) {
                echo number_format($t_kredit);
            } ?></th>
            <th style="text-align: right;"><?php if(!empty($total)) {
                echo $total;
            } ?></th>
            <th>
            <input type="hidden" name="total" id="total" value="<?php if(!empty($total2)) {
                echo $total2;
            } ?>" class="form-control">
            </th>
        </tr>
    </tfoot>
</table>
<script language="javascript">
function refreshDetail() {
                MyTable = $('#detail-jurnal').dataTable();
            }
            var MyTable = $('#detail-jurnal').dataTable({
                "responsive": true,
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "pageLength": 10,
                scrollY: '200px',
        scrollCollapse: true,
        "language": {
            "sEmptyTable": "Detail Jurnal Umum Belum Ada",
            "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
            });
function delData(e, id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Jurnal/deleteJurnal_detail')?>",
            data: {
                'id': id
            },

            success: function(response) {
            startCek();
                showDetail();
            }
        });
    }
    
</script>
<?php } ?>