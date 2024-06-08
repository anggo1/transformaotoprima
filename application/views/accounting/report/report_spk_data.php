<div class="form-group row">
<button class="btn btn-sm btn-outline-primary" onclick="listPart()" type="submit"><i class="fa fa-eye"></i> Global</button>&nbsp;&nbsp;
<button class="btn btn-sm bg-gradient-info" onclick="detailPart()" type="submit"><i class="fa fa-eye"></i> Detail Material</button>&nbsp;&nbsp;
<button class="btn btn-sm bg-gradient-navy" onclick="detailUpah()" type="submit"><i class="fa fa-eye"></i> Detail Upah</button>&nbsp;&nbsp;
<button class="btn btn-sm bg-gradient-success" onclick="cetak_dataSpk()" type="submit"><i class="fa fa-print"></i> Cetak</button>
</div>
<div class="col-12 ">
    <div class="table-responsive">
        <table width="100%" class="table table-bordered table-hover table-striped nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>ID PK</th>
                    <th>Kode</th>
                    <th>PK</th>
                    <th>No Body</th>
                    <th>Biaya Material</th>
                    <th>Upah Borong</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$total_part=0;
$total_upah=0;
foreach ($dataPart as $s) {
    $total_part += $s->total_hrg_part;
    $total_upah += $s->biaya_borong;
?> 
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $s->id_pk; ?></td>
                  <td><?php echo $s->jns_pk; ?></td>
                  <td><?php echo $s->ket_pk; ?></td>
                  <td><?php echo $s->no_body; ?></td>
                  <td align="right"><?php echo number_format($s->total_hrg_part); ?></td>
                  <td align="right"><?php echo number_format($s->biaya_borong); ?></td>
                </tr>
                <?php
    $no++;
}
?>
            </tbody>
    <tfoot>
			<tr>
                    <td align="right"><font size="0px"></font></td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">Sub Total</td>
                    <td align="right"><?php echo number_format($total_part); ?></td>
                    <td align="right"><?php echo number_format($total_upah); ?></td>
        </tr>
        <tr>
                    <td align="right"><font size="0px"></font></td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">Grand Total </td>
                    <td align="right">&nbsp;</td>
                    <td align="right"><?php echo number_format($total_part + $total_upah); ?></td>
        </tr>
			</tfoot>
    </table>
</div>
</div>

<script>
var MyTable = $('#list-data,#list-data2').DataTable({
    
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [
        {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
			footer: true,
            title: 'Report Pembayaran Borongan',
            className: 'btn btn-sm btn-outline-success',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
            }
        }

    ],
        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 10
    });
</script>