<script>
document.getElementById("btnPrint").onclick = function() {
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
    html,body {
        visibility: hidden;
    padding-top: 5cm !important;
    padding-bottom: 5cm !important;
    width: 210mm;
    height: 297mm;
    }

    #printSection,
    #printSection * {
        visibility: visible;
    }

    #printSection {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    @page {
  size: A4;
  margin: 0;
  }

}
p,
td,
th {
    font: 2 Verdana, Arial, Helvetica, sans-serif;

}

.table-cetak {
    border-collapse: collapse;
    font: bold;
    padding: 2px;
}
.table-cetak td {
    border: 1px solid #000;
    padding: 2px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.table-cetak th {
    border: 1px solid #000;
    font: bold;
    font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
}
/*.modal-body {
    max-height: calc(100vh - 10px);
    overflow-y: auto;
}*/
.btn-floating {
    position: fixed;
    right: 25px;
    overflow: hidden;
    width: 50px;
    height: 50px;
    border-radius: 100px;
    border: 0;
    z-index: 9999;
    color: white;
    transition: .2s;
}
.btn-floating img {
   width: 500px;
   height: 600px;
}
.btn-floating:hover {
    width: auto;
    padding: 0 20px;
    cursor: pointer;
}

.btn-floating span {
    font-size: 16px;
    margin-left: 5px;
    transition: .2s;
    line-height: 0px;
    display: none;
}

.btn-floating:hover span {
    display: inline-block;
}

.btn-floating:hover img {
    margin-bottom: -3px;
}

.btn-floating.whatsapp {
    bottom: 25px;
    background-color:red;
    border: 2px solid #fff;
}

.btn-floating.whatsapp:hover {
    background-color:crimson;
}

.btn-floating.facebook {
    bottom: 85px;
    background-color: #1876f3;
    border: 2px solid #fff;
}

.btn-floating.facebook:hover {
    background-color: #1876f3;
}
table.dataTable>thead .sorting::before,
 table.dataTable>thead .sorting_asc::before,
 table.dataTable>thead .sorting_desc::before,
 table.dataTable>thead .sorting_asc_disabled::before,
 table.dataTable>thead .sorting_desc_disabled::before {
    right: 0;
    content: "";
}
 
 table.dataTable>thead .sorting::after,
 table.dataTable>thead .sorting_asc::after,
 table.dataTable>thead .sorting_desc::after,
 table.dataTable>thead .sorting_asc_disabled::after,
 table.dataTable>thead .sorting_desc_disabled::after {
    right: 0;
    content: "";
}
 
 table.dataTable>thead>tr>th:not(.sorting_disabled),
 table.dataTable>thead>tr>td:not(.sorting_disabled) {
    padding-right: 2px;
    padding-left: 4px;
}
 
 table.dataTable>thead>tr>th,
 table.dataTable>thead>tr>td {
    padding-right: 2px;
    padding-left: 4px;
}
</style>
        <button class="btn-floating facebook" id="btnPrint">
        <i class="fas fa-print fa-lg"></i>
            <span>Cetak Data</span>
        </button>
        <button class="btn-floating whatsapp" data-dismiss="modal">
        <i class="fas fa-times fa-lg"></i>
            <span>Tutup</span>
        </button>
<div id="printThis">
	<div class="alert bg-white"><?php foreach ($dataPart as $k) {}?>
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
<div class="alert bg-white">
    <div class="table-responsive">
        <table width="100%" class="table table-bordered table-hover nowrap" id="list-data1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Body</th>
                    <th>No SPK</th>
                    <th>Tgl</th>
                    <th>No PK</th>
                    <th>Ket PK</th>
                    <th>No Part</th>
                    <th>Nama Part</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$total=0;
$akumulasi=0;
foreach ($dataBody as $s) {
    $akumulasi += $s->jumlah * $s->hrg_part;
    $total += $akumulasi;
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->no_body; ?></td>
                    <td><?php echo $s->no_spk; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_keluar); ?></td>
                    <td><?php echo $s->no_pk; ?></td>
                    <td><?php echo $s->ket_pk; ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td style="text-align: center;"><?php echo $s->jumlah; ?></td>
                    <td style="text-align: center;"><?php echo $s->kode_satuan; ?></td>
                    <td style="text-align: right;"><?php echo number_format($s->hrg_part); ?></td>
                    <td style="text-align: right;"><?php echo number_format($s->jumlah * $s->hrg_part); ?></td>
                </tr>
                <?php
    $no++;
}
?>

            </tbody>
            <tfoot>
            <tr>
                  <td align="right" colspan="10">Grand Total</td>
                  <td align="right" colspan="2" style="font-weight: bold;font-size: large;"><?php echo number_format($akumulasi); ?></td>
                  </tr>
            </tfoot>
        </table>
    </div>
</div>
</div>
      <!--  <div class="card-footer">
    <button type="button" id="btnPrint" class="btn btn-success" ><span class="fa fa-print"></span>&nbsp;&nbsp;  C E T A K </button>
      <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>

</div>
-->
<script language="javascript">
    

$(document).ready( function () {
var row_group_index = 0;
var row_group_td = 0;
var table = $('#list-data1').DataTable({
  
  "responsive": false,
      "paging": false,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info":false,"bSort": false,
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
          .column(11)
          .data()
          .reduce(function (a, b) {
              return intVal(a) + intVal(b);
          }, 0);
          total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
      $(api.column(11).footer()).html(total);
  },
      order: [[5, 'desc']],
      rowGroup: {
          "startRender": function(rows, group, level) {
            row_group_index++;
          return row_group_index + '. ' + group + ' ( ' + rows.count() + ' Data )';
      },

      
          endRender: function ( rows, group ) {
            row_group_td++;
              var debit = rows
                  .data()
                  .pluck(11)
                  .reduce( function (a, b) {
                      return a + b.replace(/[^\d]/g, '')*1;
                  }, 0);
              debit = $.fn.dataTable.render.number(',', '.', 0).display( debit );
              return $('<tr/>')
                  .append( '<td colspan="11" style=color:#17a2b8;font-weight: bolder; align="right">Total  '+group+'</td>' )
                  .append( '<td style=color:green; font-weight: bolder; align="Right">'+debit+'</td>' )
          },
          dataSrc: 5
      }
});

table.on( 'draw', function () {
  row_group_index = 0;
} );

      
} );


</script>