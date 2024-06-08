<div class="form-group row">
<button class="btn btn-sm bg-gradient-primary" onclick="listPart()" type="submit"><i class="fa fa-eye"></i> Global</button>&nbsp;&nbsp;
<button class="btn btn-sm bg-gradient-info" onclick="detailPart()" type="submit"><i class="fa fa-eye"></i> Detail Material</button>&nbsp;&nbsp;
<button class="btn btn-sm btn-outline-secondary" onclick="detailUpah()" type="submit"><i class="fa fa-eye"></i> Detail Upah</button>&nbsp;&nbsp;
<button class="btn btn-sm bg-gradient-success" onclick="cetak_dataSpk_upah_detail()" type="submit"><i class="fa fa-print"></i> Cetak</button>
</div>
<div class="col-12 ">
    <div class="table-responsive">
        <table width="100%" class="table table-bordered table-hover table-striped nowrap" id="list-data">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Tgl</th>
                    <th>ID Pekerjaan</th>
                    <th>Pekerjaan</th>
                    <th>No Body</th>
                    <th>Biaya</th>
                    <th>Pembayaran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$dibayar=0;
$total_jumlah=0;
foreach ($dataPart as $s) {
    $dibayar += $s->jumlah;
    $total_jumlah += $s->biaya_borong;
?> 
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo tglIndoPendek($s->tgl_bayar); ?></td>
                  <td><?php echo $s->id_pk; ?></td>
                  <td><?php echo $s->ket_pk; ?></td>
                  <td><?php echo $s->no_body; ?></td>
                  <td align="right"><?php echo number_format($s->biaya_borong); ?></td>
                  <td align="right"><?php echo number_format($s->jumlah); ?></td>
                  <td align="right"><?php echo $s->keterangan; ?></td>
                </tr>
                
                <?php
    $no++;
}
?>
				<tr>
                  <td align="right" style="color: #fff; color: rgba(0, 0, 0, 0.0);">x1</td>
                  <td align="right"></td>                 
                  <td align="right"></td>                  
                  <td align="right"></td>                  
                  <td align="right"></td>                  
                  <td align="right">Total Pembayaran</td>                                    
                  <td align="right" style="text-align: right"><?php if(!empty($s->beaBorong)) {echo number_format($s->beaBorong);} ?></td>
                  <td align="right">&nbsp;</td>
              </tr>
                <tr>
				  <td align="right" style="color: #fff; color: rgba(0, 0, 0, 0.0);">x2</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">&nbsp;</td>
                  <td align="right">Jumlah Pembayaran</td>
                  <td align="right" style="text-align: right"><?php echo number_format($dibayar); ?></td>
                  <td align="right">&nbsp;</td>
                  </tr>
                <tr>
                    <td align="right" style="color: #fff; color: rgba(0, 0, 0, 0.0);">x3</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">Sisa Pembayaran</td>
                    <td align="right" style="text-align: right"><?php if(!empty($s->beaBorong)) { echo number_format($s->beaBorong-$dibayar);} ?></td>
                    <td align="right">&nbsp;</td>
                  </tr>
				<tfoot>
                
			</tfoot>
    </table>
</div>
</div>
<script>
var MyTable = $('#list-data,#list-data2').DataTable({
    "aoColumnDefs": [{
  "aTargets": [5,4],
  "defaultContent": "",
}],
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    "buttons": [
        {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel',
			footer: true,
			customize: (xlsx, config, dataTable) => {
          let sheet = xlsx.xl.worksheets['sheet1.xml'];
          let footerIndex = $('sheetData row', sheet).length;
          let $footerRows = $('tr', dataTable.footer());

          // If there are more than one footer rows
          if ($footerRows.length > 1) {
            // First header row is already present, so we start from the second row (i = 1)
            for (let i = 1; i < $footerRows.length; i++) {
              // Get the current footer row
              let $footerRow = $footerRows[i];

              // Get footer row columns
              let $footerRowCols = $('th', $footerRow);

              // Increment the last row index
              footerIndex++;

              // Create the new header row XML using footerIndex and append it at sheetData
              $('sheetData', sheet).append(`
                <row r="${footerIndex}">
                  ${$footerRowCols.map((index, el) => `
                    <c t="inlineStr" r="${String.fromCharCode(65 + index)}${footerIndex}" s="2">
                      <is>
                        <t xml:space="preserve">${$(el).text()}</t>
                      </is>
                    </c>
                  `).get().join('')}
                </row>
              `);
            }
		  }
			},
            title: 'Report Detail Pembayaran Borongan',
            className: 'btn btn-sm btn-outline-success',
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7]
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