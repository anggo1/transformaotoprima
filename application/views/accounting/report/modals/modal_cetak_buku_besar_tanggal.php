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
    border-collapse: collapse;
    font: bold;
    padding: 2px;
}
.datatable td {
    border: 1px solid #000;
    padding: 2px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.datatable th {
    border: 1px solid #000;
    font: bold;
    font-weight: bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
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
<div id="printThis">
<div class="alert bg-white">
<h4>Buku Besar Pertanggal</h4>
                                <table id="list-saldo" width="100%" class="table datatable">
                                    <thead>
                                        <tr>
                                            <th width="5%">Reference</th>
                                            <th width="10%">Tanggal</th>
                                            <th>No Ref</th>
                                            <th>Keterangan</th>
                                            <th width="5%">Code</th>
                                            <th>Account</th>
                                            <th>Debet</th>
                                            <th>Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
											<?php 
                                            if(!empty($dataBuku)){
                                                $no=1; 
                                                $debit1=0; 
                                                $kredit1=0; 
                                                $kredit2=0; 
												$total_global=0;
											foreach ($dataBuku as $a){		  
												$debit1 += $a->debit; 
												$kredit2 -= $a->kredit;		
												$kredit1 += $a->kredit;		
																	  
																	  
                                            ?>
                                        <tr>
                                            <td     width="5%"><?php echo $a->no_jurnal ?></td>
                                            <td width="10%"><?php echo tglIndopendek($a->tgl_jurnal) ?></td>
                                            <td><?php echo $a->no_ref ?></td>
                                            <td><?php echo $a->keterangan ?></td>
                                            <td width="5%"><?php echo $a->kode_akun ?></td>
                                            <td><?php echo $a->nama_akun ?></td>
                                            <td align="right"><?php echo number_format($a->debit) ?></td>
                                            <td style=color:red; font-weight: bolder; align="Right"><?php echo number_format($a->kredit) ?></td>
                                        </tr><?php }} ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Total</th>
                                            <th style="text-align: right;">Debet</th>
                                            <th style="text-align: right;">Credit</th>
                                        </tr>
									</tfoot>
                                </table>
                            </div>
</div>
        <div class="card-footer">
        <button type="button" id="btnPrint" class="btn btn-success" ><span class="fa fa-print"></span>&nbsp;&nbsp;  C E T A K </button>
      <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>
<script type="text/javascript">
var MyTable = $('#list-saldo').DataTable({
        "responsive": false,
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        
    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(6)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        hasil = api
            .column(7)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(6).footer()).html(total);
        $(api.column(7).footer()).html(total);
    },
    order: [[0,5, 'asc']],
        rowGroup: {
            "startRender": function ( rows, group, level ) {
                return '<strong>Data '+group+'</strong>';
             },
            endRender: function ( rows, group ) {
                var debit = rows
                    .data()
                    .pluck(6)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    }, 0);
                debit = $.fn.dataTable.render.number(',', '.', 0).display( debit );
                var kredit = rows
                    .data()
                    .pluck(7)
                    .reduce( function (x, y) {
                        return x + y.replace(/[^\d]/g, '')*1;
                    }, 0);
                kredit = $.fn.dataTable.render.number(',', '.', 0).display( kredit );
                return $('<tr/>')
                    .append( '<td colspan="6" style=color:#17a2b8;font-weight: bolder; align="Right">SALDO '+group+'</td>' )
                    .append( '<td style=color:green; font-weight: bolder; align="Right">'+debit+'</td>' )
                    .append( '<td style=color:red; font-weight: bolder; align="Right">'+kredit+'</td>' );
            },
            dataSrc: 5
        }
});
</script>