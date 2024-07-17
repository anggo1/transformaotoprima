<style>
.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 12px;
}

table.dataTable td {
    padding-bottom: 2px;
}
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
                                    class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Stok Opname</h5>
                            <button type="button" class="btn btn-success" id="tambah" hidden="hidden"
                                onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> Stok
                                Opname Baru</button>
                        </div>
                        <div class="modal-body">
                                <form id="formPo" name="formPo" method="POST">
                            <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-4">
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                            <input type="text" name="tgl_opname" id="tgl_opname" value=""
                                                class="form-control tgl_po datetimepicker" data-toggle="datetimepicker"
                                                data-target=".tgl_po" data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            
                                    <label class="col-sm-2 col-form-label">Stok Cabang</label>
                                    <div class="col-sm-4">
                                        <select name="lokasi" id="lokasi" class="form-control" <?php  $lvl = $this->session->userdata['id_level']; 
                                        if ($lvl !='1' && $lvl !='12'){ echo 'disabled';} ?>>
                                            <option value="">Cabang Dealer...
                                            </option>
                                            <?php
                                            $lok = $this->session->userdata['lokasi'];
                                                                    foreach ($dataKota as $kel) { ?>
                                                                <option
                                                                    value="<?php echo $kel->kode_kota.'|'.$kel->nama_kota; ?>"
                                                                    <?php if ($kel->nama_kota == $lok) { echo "selected='selected'"; } ?>>
                                                                    <?php echo $kel->nama_kota; ?>
                                                                </option>
                                                                <?php }  ?>
                                        </select>
                                    </div> 
                        </div>
						<div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kelompok</label>
                                <div class="col-sm-4">
                                    <select name="kelompok" id="kelompok" class="form-control"
                                        required>
                                        <?php
											if (!empty($dataKelompok)) {
												foreach ($dataKelompok as $kel) {   ?>
                                        <option value="<?php echo $kel->kelompok; ?>|<?php echo $kel->kelompok; ?>">
                                            <?php echo $kel->kelompok; ?>
                                        </option>
                                        <?php
												}
											}
											?>
                                            
                                        <option value="">Uncategorized
                                        </option>
                                    </select>
                                </div>
									
									<label class="col-sm-2 col-form-label">Keterangan</label>
									<div class="col-sm-4">
										<input type="text" name="keterangan" id="keterangan" value="" class="form-control" required placeholder="Keterangan">
									</div>
                        </div>
                    </div>
                    <?php
						$date = date("y-m");
						$ci_kons = get_instance();
						$query = "SELECT max(id_opname) AS maxKode FROM tbl_wh_opname WHERE id_opname LIKE '%$date%'";
						$hasil = $ci_kons->db->query($query)->row_array();
						$noOrder = $hasil['maxKode'];
						$noUrut = (int)substr($noOrder, 5, 4);
						$noUrut++;
						$tahun = substr($date, 0, 2);
						$bulan = substr($date, 3, 2);
						$kode_po  = $tahun.'-'.$bulan.sprintf("%04s", $noUrut);
						?>
                    <input type="hidden" name="id_opname" id="id_opname" value="<?php echo $kode_po ?>" class="form-control">
                    <input type="hidden" name="kode_ref" id="kode_ref" class="form-control">
                    <input type="hidden" name="user" id="user"
                        value="<?php echo $this->session->userdata['full_name']; ?>"class="form-control">
                    <div class="modal-footer right-content-between">
                        <button class="btn btn-success" id="simpan"  onclick="tampilKelompok()" ><span class="fa fa-search"></span>
                            Cari</button>
                        <button class="btn btn-primary" id="simpan" type="submit"><span class="fa fa-save"></span>
                            Simpan Data</button>
                    </div>
                    </form>
                <div class="card-body">
                    <div id="modal-po"></div>
                    <div id="data-kelompok"></div>
                    <div id="data-po-cache"></div>
                </div>
            </div>
                    </div>
                </div>
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body form">
                            <div class="card card-first card-outline">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table width="100%" class="table no-wrap table-hover nowrap" id="table-part">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>No Part</th>
                                                    <th>Nama Part</th>
                                                    <th>Stok</th>
                                                    <th>Harga</th>
                                                    <th>Satuan</th>
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
$('#tgl_opname,#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
var MyTable = $('#list-po').dataTable({
    "responsive": true,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "pageLength": 5,
    "info": true
});

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

function tampilKelompok(dataKelompok) {
        var id_kelompok = document.getElementById("kelompok").value;
        var lokasi = document.getElementById("lokasi").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('StokOpname/tampilKelompok'); ?>?id_kelompok=' + id_kelompok+'&lokasi=' + lokasi,
            data: 'id_kelompok=' + id_kelompok+'&lokasi=' + lokasi,
            success: function (hasil) {
                MyTable.fnDestroy();
                $('#data-kelompok').html(hasil);
				//document.getElementById("cetak").hidden = false;
            }
        });
    }
    function tampilCabang(dataKelompok) {
        var id_kelompok = document.getElementById("kelompok").value;
        var lokasi = document.getElementById("lokasi").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('StokOpname/tampilCabang'); ?>?id_kelompok=' + id_kelompok+'&lokasi=' + lokasi,
            data: 'id_kelompok=' + id_kelompok+'&lokasi=' + lokasi,
            success: function (hasil) {
                MyTable.fnDestroy();
                $('#data-kelompok').html(hasil);
				//document.getElementById("cetak").hidden = false;
            }
        });
    }
function tampilKelompokUpdate(dataOpname) {
        var id_opname = document.getElementById("kelompok").value = dataOpname;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('StokOpname/tampilKelompokUpdate'); ?>?id_opname=' + id_opname,
            data: 'id_opname=' + id_opname,
            success: function (hasil) {
                MyTable.fnDestroy();
                $('#data-kelompok').html(hasil);
				document.getElementById("cetak").hidden = false;
            }
        });
    }
$('#formPo').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('StokOpname/prosesOpname'); ?>',
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
                tampilKelompokUpdate(out.dataOpname)
                document.getElementById("formPo"); //reset()	
                $('#tgl_opname').attr('readonly', 'readonly');
    			document.getElementById("kelompok").setAttribute("disabled", "disabled");
                $('#keterangan').attr('readonly', 'readonly');
                document.getElementById("tambah").hidden = false;
                document.getElementById("simpan").hidden = true;

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
            url: "<?php echo base_url('StokOpname/deleteDetail'); ?>",
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