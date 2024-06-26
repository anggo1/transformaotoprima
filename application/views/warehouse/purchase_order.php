<style>

</style>
<?php if (!empty($dataPart)) {
	foreach ($dataPart as $part) {
	}
} ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <!-- /.card-header -->
                    <div class="modal-content">
                        <div class="modal-header text-blue">

                            <h5 style="display:block; text-align:center;"><span
                                    class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Purchase Order</h5>
                            <button type="button" class="btn btn-success" id="tambah" hidden="hidden"
                                onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> PO
                                BARU</button>
                        </div>
                        <div class="modal-body">

                            <form id="formPo" name="formPo" method="POST">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-4">
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                            <input type="text" name="tgl_po" id="tgl_po" value=""
                                                class="form-control tgl_po datetimepicker" data-toggle="datetimepicker"
                                                data-target=".tgl_po" data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 col-form-label">TOP</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="top" id="top" value="" class="form-control"
                                            placeholder="Term of Payment">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">No Penawaran</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="kode_pesan" id="kode_pesan" value=""
                                            class="form-control" placeholder="No Penawaran / Kode Pesanan">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Supplier</label>
                                    <div class="col-sm-4">
                                        <select name="supplier" id="supplier" class="form-control">
                                            <option value="">Supplier...
                                            </option>
                                            <?php
											if (!empty($dataSupplier)) {
												foreach ($dataSupplier as $sp) {   ?>
                                            <option value="<?php echo $sp->kode_sup; ?>">
                                                <?php echo $sp->nama_sup; ?>
                                            </option>
                                            <?php
												}
											}
											?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">PPN</label>
                                    <div class="col-sm-3">
                                        <input type="number" name="ppn" id="ppn" value="0" class="form-control"
                                            onKeyDown="fn(this)" onKeyPress="fn(this)"
                                            placeholder="Pajak Pertambahan Nilai">
                                    </div>
                                    <label for="Nama Konsumen" class="col-sm-1 col-form-label">%</label>

                                    <label class="col-sm-2 col-form-label">Keterangan</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pengesah</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="pengesah" id="pengesah" class="form-control"
                                            placeholder="Pengesahan Oleh">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Keterangan 2</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="keterangan2" id="keterangan2" value=""
                                            class="form-control" placeholder="Keterangan 2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Nama Konsumen" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-4">

                                    </div>
                                </div>
                                <?php
						$date = date("y-m");
						$ci_kons = get_instance();
						$query = "SELECT max(id_po) AS maxKode FROM tbl_wh_po WHERE id_po LIKE '%$date%'";
						$hasil = $ci_kons->db->query($query)->row_array();
						$noOrder = $hasil['maxKode'];
						$noUrut = (int)substr($noOrder, 5, 4);
						$noUrut++;
						$tahun = substr($date, 0, 2);
						$bulan = substr($date, 3, 2);
						$kode_po  = $tahun.'-'.$bulan.sprintf("%04s", $noUrut);
						?>
                                <input type="hidden" name="id_po" id="id_po" value="<?php echo $kode_po ?>"
                                    class="form-control">
                                <input type="hidden" name="kode_ref" id="kode_ref" class="form-control">
                                <input type="hidden" name="user" id="user"
                                    value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                                <div class="modal-footer right-content-between">
                                    <button class="btn btn-primary" id="simpan" type="submit"><span
                                            class="fa fa-save"></span> Simpan Data</button>
                                    <button type="button" class="btn btn-info cetak-po" id="cetak" hidden="hidden"
                                        data-id="" title="Add Data"><i class="fas fa-print"></i> Cetak PO</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="modal-po"></div>
                            <div id="data-po"></div>
                            <div id="data-po-cache"></div>
                            <button type="button" class="btn btn-xl bg-gradient-success" id="tambah-part"
                                title="Add Part" data-toggle="modal" data-target="#modal_form"><i
                                    class="fas fa-plus"></i> Tambah Barang PO</button>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_form" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-body form">
                                    <div class="card card-first card-outline">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table width="100%" class="table no-wrap table-hover nowrap"
                                                    id="table-part">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>No Part</th>
                                                            <th>Nama Part</th>
                                                            <th>Satuan</th>
                                                            <th>Stok</th>
                                                            <th>Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot></tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>
<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data PO Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->
<script type="text/javascript">
function fn(o) {
    o.value = o.value.toUpperCase().replace(/([^0-9(),-/])/g, '');
}
$('#tgl_po,#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
$(document).ready(function() {
    table = $('#table-part').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 10, // Defaults number of rows to display in table
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('PurchaseOrder/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false,
        }, ]
    });
});

$(document).ready(function() {
    var table = $('#table-part').DataTable();
		var tgl_po = document.formPo.tgl_po.value;
		var id_po = document.formPo.id_po.value;

    $('#table-part tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
		var id_part = data[0];
		var no_part		= data[1];
		var nama_part	= data[2];
		var satuan		= data[3];
		var stok		= data[4];
		var harga_baru	= data[5];
		var tgl_po = document.formPo.tgl_po.value;
		var id_po = document.formPo.id_po.value;
				$.ajax({
				method: 'POST',
				url: '<?php echo base_url('PurchaseOrder/prosesDetailPo'); ?>',
				data:
				"tgl_po=" + tgl_po +
                "&id_po=" + id_po +
                "&id_part=" + id_part +
                "&no_part=" + no_part +
                "&nama_part=" + nama_part +
                "&satuan=" + satuan +
                "&stok=" + stok +
                "&harga_baru=" + harga_baru
			})
			tampilDetail();
		$('#modal_form').modal('hide');
			tampilDetail();
    } );
	} );
var MyTable = $('#list-po').dataTable({
    "responsive": true,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true
});

function selectPart(id_part, no_part, nama_part, satuan, stok, harga_baru) {
    var tgl_po = document.formPo.tgl_po.value;
    var id_po = document.formPo.id_po.value;

    $.ajax({
        method: 'POST',
		url: '<?php echo base_url('PurchaseOrder/prosesDetailPo'); ?>',
            data: "tgl_po=" + tgl_po +
                "&id_po=" + id_po +
                "&id_part=" + id_part +
                "&no_part=" + no_part +
                "&nama_part=" + nama_part +
                "&satuan=" + satuan +
                "&stok=" + stok +
                "&harga_baru=" + harga_baru
    })

	tampilDetail(id_po);

    $('#modal_form').modal('hide');

}

function next(dataPo, dataRef) {
    document.getElementById('id_po').value = dataPo;
    document.getElementById('kode_ref').value = dataRef;
    var d = document.getElementById("cetak");
    d.setAttribute('data-id', dataPo);

    document.getElementById("cetak").hidden = false;
    //document.getElementById("alamat").readonly = true;
}

function nextref(dataRef) {
    document.getElementById('kode_ref').value = dataRef;
}

function refresh() {
    MyTable = $('#list-po').dataTable();
}

function tampilDetail() {
    //var out = jQuery.parseJSON(data);
    //var id_po = document.getElementById('id_po').value = dataPo;
    var id_po = document.getElementById('id_po').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('PurchaseOrder/tampilDetail'); ?>',
        data: 'id_po=' + id_po,
        success: function(hasil) {
            //MyTable.fnDestroy();
            $('#data-po').html(hasil);
        }
    });
}

function tampilDetailCache(dataPo) {
    //var out = jQuery.parseJSON(data);
    var id_po = document.getElementById('id_po').value = dataPo;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('PurchaseOrder/tampilDetailCache'); ?>?id_po=' + id_po,
        data: 'id_po=' + id_po,
        success: function(hasil) {
            MyTable.fnDestroy();
            $('#data-po-cache').html(hasil);
            refresh();
        }
    });
}
$('#formPo').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('PurchaseOrder/prosesPo'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                //toastr.error(out.msg);
                $('.msg').html(out.msg);
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                $('.msg').html(out.msg);
                $('.dataPo').html(out.dataPo);
                //tampilDetail(out.dataPo)
                document.getElementById("formPo"); //reset()	
                $('#tgl_po').attr('readonly', 'readonly');
                $('#top').attr('readonly', 'readonly');
                $('#status').attr('readonly', 'readonly');
                $('#supplier').attr('readonly', 'readonly');
                $('#keterangan').attr('readonly', 'readonly');
                $('#ppn').attr('readonly', 'readonly');

                var d = document.getElementById("cetak");
                d.setAttribute('data-id', out.dataPo);
                document.getElementById("cetak").hidden = false;
                document.getElementById("tambah").hidden = false;
                document.getElementById("tambah-part").hidden = true;
                document.getElementById("data-po").hidden = true;
                document.getElementById("simpan").hidden = true;
                tampilDetailCache(out.dataPo);

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })

    e.preventDefault();
});

function cetakPo(datakode) {}


$(document).on("click", ".cetak-po", function() {
    var id = $(this).attr("data-id");
    //var id = document.getElementById('next_proses').value=datakode;
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('PurchaseOrder/cetak'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-po').html(data);
            $('#cetak-po').modal('show');
        })
})
var data_id;
$(document).on("click", ".delete-detail", function() {
    data_id = $(this).attr("data-id");
})
$(document).on("click", ".delete-detail", function() {
    var id = data_id;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('PurchaseOrder/deleteDetail'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);
            if (out.status != 'form') {
                //$('.msg').html(out.msg);
                $('#hapusDetail').modal('hide');
                var id_po = document.formPo.id_po.value;
                //next(next_proses);
                tampilDetail(id_po);
            }
        })
})

function startHitung() {
    interval = setInterval("Hitung()", 10);
}

function Hitung() {

    var a = document.formPo.jumlah.value;
    var b = document.formPo.hrg_awal.value;
    document.formPo.total_harga.value = (a * b);
}

function stopHitung() {
    clearInterval(startHitung);
}

function startDiskon() {
    interval = setInterval("Diskon()", 10);
}

function Diskon() {

    var a = document.formPo.jumlah.value;
    var b = document.formPo.hrg_awal.value;
    var c = document.formPo.diskon.value;
    document.formPo.total_diskon.value = (a * b) * c / 100;
}

function stopDiskon() {
    clearInterval(startDiskon);
}

function startPpn() {
    interval = setInterval("Ppn()", 10);
}
</script>