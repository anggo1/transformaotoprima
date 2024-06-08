<script>
        document.getElementById("btnPrint").onclick = function () {
    printElement(document.getElementById("printThis"));
}

function printElement(elem) {
    var domClone = elem.cloneNode(true);
    
    var $printSection = document.getElementById("printSection");
    
    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }
    
    $printSection.innerHTML = "";
    $printSection.appendChild(domClone);
    window.print();
}
    </script>
     <style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible;
  }
  #printSection {
    position: absolute;
    left:0;
    top:0;
    width: 100%;
  }
}
p, td, th {
    font:2 Verdana, Arial, Helvetica, sans-serif;
	
}
.datatable {
    border: 1px solid #000;
    font: bold;
    padding: 1px;
}
.datatable td {
    border: 1px solid #000;
    padding: 5px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.datatable th {
    border: 2px solid #000;
    padding: 5px;
    font: bold;
    font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    text-align: center;
}
.under { text-decoration: underline; }
#A4 {background-color:#FFFFFF;
left:1px;
right:1px;
height:5.51in ; /*Ukuran Panjang Kertas */
width: 8.50in; /*Ukuran Lebar Kertas */
margin:1px solid #FFFFFF;
 
font-family:Georgia, "Times New Roman", Times, serif;
}
    </style>
<div id="printThis"><div class="alert bg-white"><?php foreach ($dataKop as $k) {}?>
<table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                          <tbody>
                            <tr>
                              <td>ID Laporan</td>
                              <td>: <?php echo $k->id_lapor ?></td>
                              <td width="24%" rowspan="6">&nbsp;</td>
                              <td>Pool</td>
                              <td>: <?php echo $k->pool ?></td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td>Tgl Masuk</td>
                              <td>: <?php echo tglIndoPanjang($k->tgl_masuk) ?></td>
                              <td>Rute</td>
                              <td>: <?php echo $k->rute_aktif ?></td>
                            </tr>
                            <tr>
                              <td>Kategori</td>
                              <td>: <?php echo $k->nama_kategori ?></td>
                              <td>No Body</td>
                              <td>: <?php echo $k->no_body ?></td>
                            </tr>
                            <tr>
                              <td>Type</td>
                              <td>: <?php echo $k->type ?></td>
                              <td>No Pol</td>
                              <td>: <?php echo $k->no_pol ?></td>
                            </tr>
                            <tr>
                              <td>Catatan/Keterangan</td>
                              <td>: <?php echo $k->keterangan ?></td>
                              <td>Seat</td>
                              <td>: <?php echo $k->kelas ?></td>
                            </tr>
                            <tr>
                              <td width="15%">&nbsp;</td>
                              <td width="25%">&nbsp;</td>
                              <td width="15%">&nbsp;</td>
                              <td width="21%">&nbsp;</td>
                            </tr>
							  
                          </tbody>
                        </table>
<div class="table-responsive">
        <table width="100%" class="table table-bordered table-hover table-striped nowrap" id="list-data1">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>ID PK</th>
                    <th>Kode</th>
                    <th>PK</th>
                    <th>No Body</th>
                    <th>No Part</th>
                    <th>Nama Part</th>
                    <th>QTY</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$total_part=0;
foreach ($dataPart as $s) {
    $total_part += $s->total_hrg_part;
?> 
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $s->id_pk; ?></td>
                  <td><?php echo $s->jns_pk; ?></td>
                  <td><?php echo $s->ket_pk; ?></td>
                  <td><?php echo $s->no_body; ?></td>
                  <td><?php echo $s->no_part; ?></td>
                  <td><?php echo $s->nama_part; ?></td>
                  <td><?php echo $s->jumlah; ?></td>
                  <td><?php echo $s->satuan; ?></td>
                  <td align="right"><?php echo number_format($s->hrg_part); ?></td>
                  <td align="right"><?php echo number_format($s->total_hrg_part); ?></td>
                </tr>
                <?php
    $no++;
}
?>
            </tbody>
    <tfoot>
			<tr>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">Grand Total</td>
                    <td align="right"><?php echo number_format($total_part); ?></td>
        </tr>
			</tfoot>
    </table>
</div>
</div>
</div>
        <div class="card-footer">
        <button type="button" id="btnPrint" class="btn btn-success" ><span class="fa fa-print"></span>&nbsp;&nbsp;  C E T A K </button>
      <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>
<script>
    

$(document).ready( function () {

  var row_group_index = 0;
  var row_group_td = 0;
  var table = $('#list-data1').DataTable({
    
    "responsive": false,
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "lengthMenu": [
        [-1,10, 25, 50],
        [ 'Seluruhnya',10, 25, 50],
        
    ],

    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(10)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(10).footer()).html(total);
    },
        order: [[3, 'desc']],
        rowGroup: {
            "startRender": function(rows, group, level) {
              row_group_index++;
            return row_group_index + '. ' + group + ' (' + rows.count() + ' rows)';
        },

        
            endRender: function ( rows, group ) {
              row_group_td++;
                var debit = rows
                    .data()
                    .pluck(10)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    }, 0);
                debit = $.fn.dataTable.render.number(',', '.', 0).display( debit );
                return $('<tr/>')
                    .append( '<td colspan="10" style=color:#17a2b8;font-weight: bolder; align="right">Total  '+group+'</td>' )
                    .append( '<td style=color:green; font-weight: bolder; align="Right">'+debit+'</td>' )
            },
            dataSrc: 3
        }
  });
  
table.on( 'draw', function () {
    row_group_index = 0;
} );
  
        
} );

</script>