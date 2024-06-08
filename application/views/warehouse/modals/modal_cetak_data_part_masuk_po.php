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
    body {
        visibility: hidden;
    padding-top: 5cm !important;
    padding-bottom: 5cm !important;
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
    size: auto;
    margin: 0mm;
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
</style>
        <button class="btn-floating facebook" id="btnPrint">
        <i class="fas fa-print fa-lg"></i>
            <span>Cetak Data</span>
        </button>
        <button class="btn-floating whatsapp" data-dismiss="modal">
        <i class="fas fa-times fa-lg"></i>
            <span>Tutup</span>
        </button>
<?php
$judul="";
$subB="";
if($sub_status=="PPU"){
$judul="REKAPITULASI PENERIMAAN BARANG PO";
$subB="PRIMAJASA PERDANARAYA UTAMA";
}
if($sub_status=="MPU") {
$judul="REKAPITULASI PENERIMAAN BARANG PO";
$subB="MAMERA PERDANA UTAMA";
}
if($sub_status=="GLOBAL") {
$judul="LAPORAN PENERIMAAN BARANG PO GLOBAL";
$subB="PPU & MPU";
}
?>
<div class="modal-body">
<div id="printThis">
        <div class="card-body">
            <div class="col-12 ">
                <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
                  <thead>
                    <tr>
                      <th colspan="2"> <div align="left"><H4><?php echo $judul ?></H4></div>                       <div align="left"></div>
                      </th>
                    </tr>
                    <tr>
                      <th><div align="left">Sub Bagian</div></th>
                      <th><div align="left"> : <?php echo $subB ?></div></th>
                    </tr>
                    <tr>
                      <th width="10%"> <div align="left">Periode Tanggal</div>
                      </th>
                      <th width="362"> <div align="left"> : <?php echo $tgl_awal.' s/d '.$tgl_akhir ?></div>
                      </th>
                    </tr>
                  </thead>
                </table>
                <div class="table-responsive">
                  <table width="100%" class="table table-bordered table-hover nowrap" id="list-cetak-global">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Tgl Masuk</th>
                    <th>No Ref</th>
                    <th>Supplier</th>
                    <th>No PO</th>
                    <th>No INV</th>
                    <th>No SJ</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
$no = 1;
$grand_total=0;
foreach ($dataMasuk as $s) {
    $grand_total += $s->total;
?> <tr>

                    <td><?php echo $no; ?></td>
                    <td><?php echo tglIndoPendek($s->tgl_masuk); ?></td>
                    <td><?php echo $s->id_masuk; ?></td>
                    <td><?php echo $s->nama_supplier; ?></td>
                    <td><?php echo $s->no_po; ?></td>
                    <td><?php echo $s->no_inv_sup; ?></td>
                    <td><?php echo $s->no_sj_sup; ?></td>
                    <td align="right"><?php echo number_format($s->total); ?></td>
                </tr>
                <?php
    $no++;
}
?>

            </tbody>
            <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Grand Total</th>
            <th style="text-align: right;"></th>
            </tfoot>
        </table>
              </div>
          </div>
        </div>
        </div>
    </div>
    <script>
    var MyTable = $('#list-cetak-global').DataTable({
    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();
        var intVal = function (i) {
            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
        };
        hasil = api
            .column(7)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            total=$.fn.dataTable.render.number(',', '.', 0).display(hasil);
        $(api.column(7).footer()).html(total);
    },

    "responsive": false,
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false
});
    </script>