<script>
document.getElementById("btnPrint").onclick = function() {
    printElement(document.getElementById("bagianCetak"));
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
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font: bold;
}

.table-cetak th {
    border: 1px solid #000;
    font: bold;
    font-weight: bold;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
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
    background-color: red;
    border: 2px solid #fff;
}

.btn-floating.whatsapp:hover {
    background-color: crimson;
}

.btn-floating.facebook {
    bottom: 85px;
    background-color: #1876f3;
    border: 2px solid #fff;
}

.btn-floating.facebook:hover {
    background-color: #1876f3;
}

.inEdit {
    background-color: #FFFFFF;
    border: 1px solid #333;
    border-radius: 5px;
    padding: 2px 2px 2px 2px;
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

<div class="modal-body">
    <div id="bagianCetak">
        <div class="card-body">
            <div class="col-12 ">
                <div align="left">
                    <H4>Data Detail Stok Opname</H4>
                </div>
                <div class="col-12 ">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover nowrap" id="detail-opname">
                            <thead>
                                <tr>
                                    <th width='5%'>No</th>
                                    <th>ID Opname</th>
                                    <th>Kelompok</th>
                                    <th>Jenis</th>
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
foreach ($dataOpname as $s) {
?> <tr>

                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $s->id_opname; ?></td>
                                    <td><?php echo $s->kelompok; ?></td>
                                    <td><?php echo $s->type; ?></td>
                                    <td><?php echo $s->lokasi; ?></td>
                                    <td><?php echo $s->no_part; ?></td>
                                    <td><?php echo $s->nama_part; ?></td>
                                    <td><?php echo $s->satuan; ?></td>
                                    <td><?php echo $s->stok_lama; ?></td>
                                    <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan"
                                    onfocus="this.contentEditable=true; this.className='inEdit';"
                                        onclick="this.contentEditable=true; this.className='inEdit';"
                                        onblur="saveData(event,'<?php echo $s->id; ?>','<?php echo $s->id_opname; ?>',$(this).html() )"
                                        onkeypress="saveData2(event,'<?php echo $s->id; ?>','<?php echo $s->id_opname; ?>',$(this).html() )"
                                        >
                                        <?php echo $s->stok_fisik; ?></td>
                                </tr>
                                <?php
    $no++;
}
?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {

        var row_group_index = 0;
        var row_group_td = 0;
        var table = $('#detail-opname').DataTable({

            "responsive": false,
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "pageLength": 10,
            "lengthMenu": [
                [-1, 10, 25, 50],
                ['Seluruhnya', 10, 25, 50],

            ],

            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();
                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                        'number' ? i : 0;
                };
                hasil = api
                    .column(8)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                total = $.fn.dataTable.render.number(',', '.', 0).display(hasil);
                $(api.column(9).footer()).html(total);
            },
            order: [
                [2, 'desc']
            ],
            rowGroup: {
                "startRender": function(rows, group, level) {
                    row_group_index++;
                    return row_group_index + '. ' + group + ' (' + rows.count() + ' rows)';
                },


                endRender: function(rows, group) {
                    row_group_td++;
                    var total1 = rows
                        .data()
                        .pluck(8)
                        .reduce(function(a, b) {
                            return a + b.replace(/[^\d]/g, '') * 1;
                        }, 0);
                    total1 = $.fn.dataTable.render.number(',', '.', 0).display(total1);
                    var total2 = rows
                        .data()
                        .pluck(9)
                        .reduce(function(a, b) {
                            return a + b.replace(/[^\d]/g, '') * 1;
                        }, 0);
                    total2 = $.fn.dataTable.render.number(',', '.', 0).display(total2);
                    return $('<tr/>')
                        .append(
                            '<td colspan="8" style=color:#17a2b8;font-weight: bolder; align="right">Total  ' +
                            group + '</td>')
                        .append('<td style=color:green; font-weight: bolder; align="Right">' +
                            total1 +
                            '</td>')
                        .append('<td style=color:green; font-weight: bolder; align="Right">' +
                            total2 +
                            '</td>')
                },
                dataSrc: 2
            }
        });

        table.on('draw', function() {
            row_group_index = 0;
        });


    });

    function saveData(e, id, id_opname, stok_fisik) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('StokOpname/updateDetailSO')?>",
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                    'id': id,
                    'stok_fisik': stok_fisik,
                },
                success: function(response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Sukses",
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            });
        
    }
    function saveData2(e, id, id_opname, stok_fisik) {
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
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Sukses",
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            });
        }
    }
    </script>