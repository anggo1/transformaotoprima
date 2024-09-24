<style>
.radio-buttons {
    display: flex;
}

.radio-button {
    display: flex;
    align-items: center;
    margin-right: 5px;
    position: relative;
    padding: 5px 10px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 20px;
    cursor: pointer;
}

.radio-button input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.radio-tick {
    width: 20px;
    height: 20px;
    border: 2px solid #000000;
    border-radius: 20%;
    margin-right: 10px;
}

.radio-tick-merah {
    width: 20px;
    height: 20px;
    border: 2px solid #eb3449;
    border-radius: 20%;
    margin-right: 10px;
}

.radio-tick-kuning {
    width: 20px;
    height: 20px;
    border: 2px solid #f2cc0c;
    border-radius: 20%;
    margin-right: 10px;
}

.radio-tick-hijau {
    width: 20px;
    height: 20px;
    border: 2px solid #f2cc0c;
    border-radius: 20%;
    margin-right: 10px;
}
.radio-tick-lain {
    width: 20px;
    height: 20px;
    border: 2px solid #5187d6;
    border-radius: 20%;
    margin-right: 10px;
}

.radio-button input[type="radio"]:checked+.radio-tick::before {
    content: "\f00c";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
}
.radio-button input[type="radio"]:checked+.radio-tick-merah::before {
    content: "\f00c";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
}
.radio-button input[type="radio"]:checked+.radio-tick-kuning::before {
    content: "\f00c";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
}
.radio-button input[type="radio"]:checked+.radio-tick-lain::before {
    content: "\f00c";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
}


.radio-button input[type="radio"]:checked~.radio-tick {
    background-color: #007bff;
}
.radio-button input[type="radio"]:checked~.radio-tick-merah {
    background-color: #007bff;
}
.radio-button input[type="radio"]:checked~.radio-tick-kuning {
    background-color: #007bff;
}
.radio-button input[type="radio"]:checked~.radio-tick-lain {
    background-color: #007bff;
}
</style>

<?php if (!empty($dataPart)) {
	foreach ($dataPart as $part) {
	}
} ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-dark card-outline">
                    <!-- /.card-header -->
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 style="display:block; text-align:center;"><span
                                    class="ion-soup-can-outline ion-lg"></span>&nbsp; Surat Pesanan Kendaraan / SPK
                            </h5>
                            <button type="button" class="btn btn-success" id="tambah" hidden="hidden"
                                onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> DATA
                                BARU</button>
                        </div>
                        <div class="modal-body">

                            <?php
						$date = date("y-m");
						$ci_kons = get_instance();
						$query = "SELECT max(id_estimasi_penawaran) AS maxKode FROM tbl_wh_estimasi_penawaran WHERE id_estimasi_penawaran LIKE '%$date%'";
						$hasil = $ci_kons->db->query($query)->row_array();
						$noOrder = $hasil['maxKode'];
						$noUrut = (int)substr($noOrder, 5, 4);
						$noUrut++;
						$tahun = substr($date, 0, 2);
						$bulan = substr($date, 3, 2);
						$kode_po  = $tahun.'-'.$bulan.sprintf("%03s", $noUrut);
						$kode_ref = 'SP/TOP/'.$bulan.'/'.$tahun.'/'.sprintf("%03s", $noUrut);
						?>
                            <form id="formPo" name="formPo" method="POST">
                                <div class="row">
                                    <div class="col-3">
                                        <label class="col-form-label">No Reff</label>
                                        <input type="text" name="no_ref" id="no_ref" value="<?php echo $kode_ref ?>"
                                            class="form-control" placeholder="Nomor Referensi">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Nama Pemesan</label>
                                        <input type="text" name="nama_pemesan" id="nama_pemesan" value=""
                                            class="form-control" placeholder="Nama Pemesan">
                                    </div>
                                    <div class="col-6">
                                        <label class="col-form-label">Alamat Pemesan</label>
                                        <input type="text" name="alamat_pemesan" id="no_reg" value=""
                                            class="form-control" placeholder="Alamat">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="col-form-label">No Telp</label>
                                        <input type="text" name="no_vin" id="no_vin" value="" class="form-control"
                                            placeholder="VIN">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Faktur Pajak</label>
                                        <div class="radio-buttons">
                                            <label class="radio-button">
                                                <input type="radio" name="faktur_pajak" value="Y" checked />
                                                <span class="radio-tick-lain"></span>
                                                Ya
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="faktur_pajak" value="T" />
                                                <span class="radio-tick-lain"></span>
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">No NPWP</label>
                                        <input type="text" name="date_received" id="date_received" value=""
                                            class="form-control" placeholder="Date Received">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Contact Person</label>
                                        <input type="text" name="millage" id="millage" value="" class="form-control"
                                            placeholder="Millage / Km">
                                    </div>
                                </div>

                                <h1 style="display:block; text-align:center;">
                                </h1>
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-file-alt"></i>&nbsp; Data Faktur Kendaraan
                                    </h3>
                                </div>

                                <div class="row">
                                    <div class="col-3">
                                        <label class="col-form-label">Nama BPKB/STNK</label>
                                        <input type="text" name="engine_no" id="engine_no" value="" class="form-control"
                                            placeholder="Engine No">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">No KTP/No TDP</label>
                                        <input type="text" name="acc_no" id="acc_no" value="" class="form-control"
                                            placeholder="Account No">
                                    </div>
                                    <div class="col-6">
                                        <label class="col-form-label">Alamat</label>
                                        <input type="text" name="received_by" id="received_by" value=""
                                            class="form-control" placeholder="Received by">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-3">
                                        <label class="col-form-label">Plat Kendaraan</label>

                                        <div class="radio-buttons">
                                            <label class="radio-button">
                                                <input type="radio" name="plat_kendaraan" checked value="Kuning" />
                                                <span class="radio-tick-kuning"></span>
                                                Kuning
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="plat_kendaraan" value="Hitam" />
                                                <span class="radio-tick"></span>
                                                Hitam
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="plat_kendaraan" value="Merah" />
                                                <span class="radio-tick-merah"></span>Merah
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <label class="col-form-label">Type Body</label>

                                        <div class="radio-buttons">
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="Los Bak" id="radio_t1">
                                                <span class="radio-tick"></span>Los Bak
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="Box" id="radio_t2">
                                                <span class="radio-tick"></span> Box
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="Tangki" id="radio_t3">
                                                <span class="radio-tick"></span> Tangki
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="Dump" id="radio_t4">
                                                <span class="radio-tick"></span> Dump
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="Mixer" id="radio_t5">
                                                <span class="radio-tick"></span> Mixer
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="Tranctor Head" id="radio_t6">
                                                <span class="radio-tick"></span> Tranctor Head
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="Tangki Trailer" id="radio_t7">
                                                <span class="radio-tick"></span> Tangki Trailer
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="Bus" id="radio_t8">
                                                <span class="radio-tick"></span> Bus
                                            </label>
                                            <label class="radio-button">
                                                <input type="radio" name="type_body" value="..." checked  id="radio_t9">
                                                <span class="radio-tick"></span> ........
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="Nama Konsumen" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-4">

                                    </div>
                                </div>
                                <input type="hidden" name="id_estimasi_penawaran" id="id_estimasi_penawaran"
                                    value="<?php echo $kode_po ?>" class="form-control">
                                <input type="hidden" name="kode_ref" id="kode_ref" class="form-control">
                                <input type="hidden" name="user" id="user"
                                    value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                                <div class="modal-footer right-content-between">
                                    <button class="btn btn-primary" id="simpan" type="submit" hidden="hidden"><span
                                            class="fa fa-save"></span>
                                        Simpan Data</button>
                                    <button type="button" class="btn btn-info cetak-po" id="cetak" hidden="hidden"
                                        data-id="" title="Add Data"><i class="fas fa-print"></i> Cetak Part
                                        Order</button>
                                </div>
                            </form>
                            <button type="button" class="btn btn-xl bg-gradient-success" id="tambah-part"
                                title="Add Part" data-toggle="modal" data-target="#modal_form"><i
                                    class="fas fa-plus"></i> Tambah
                                Barang</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="modal-content">
                        <div class="card-header card-dark card-outline">
                            <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i> &nbsp; Keterangan</h3>
                            <div class="text-right">
                                <button type="button" class="btn btn-sm btn-dark" onclick="insertNote()"><i
                                        class="fas fa-plus"></i>
                                    Standart Keterangan</button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#tambah-keterangan" title="Add Data"><i class="fas fa-plus"></i>
                                    Add</button>
                            </div>
                        </div>
                        <div class="col-12">
                            <p></p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover nowrap" id="list-keterangan">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-keterangan">
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="modal-keterangan"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div id="modal-po"></div>
            <div id="data-po"></div>
            <div id="data-po-cache"></div>
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
</section>
<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data PO Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->
<script type="text/javascript">
function fn(o) {
    o.value = o.value.toUpperCase().replace(/([^0-9(),-/])/g, '');
}
$('#tgl_estimasi_penawaran,#tgl_received,#tgl_regis').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
//var Date = 
$('#tgl_deadline').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
})
$('#clear').click(function() {
    $('#tgl_deadline').data("DateTimePicker").clear()
})

$('#tgl_deadline1').datetimepicker({
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
            "url": "<?php echo site_url('EstimasiPenawaran/ajax_list') ?>",
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
    var tgl_estimasi_penawaran = document.formPo.tgl_estimasi_penawaran.value;
    var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;

    $('#table-part tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var id_part = data[0];
        var no_part = data[1];
        var nama_part = data[2];
        var satuan = data[3];
        var stok = data[4];
        var harga_baru = data[5];
        var tgl_estimasi_penawaran = document.formPo.tgl_estimasi_penawaran.value;
        var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;
        $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaran/prosesDetailPo'); ?>',
            data: "tgl_estimasi_penawaran=" + tgl_estimasi_penawaran +
                "&id_estimasi_penawaran=" + id_estimasi_penawaran +
                "&id_part=" + id_part +
                "&no_part=" + no_part +
                "&nama_part=" + nama_part +
                "&satuan=" + satuan +
                "&stok=" + stok +
                "&harga_baru=" + harga_baru
        })
        tampilDetail();
        document.getElementById("simpan").hidden = false;
        $('#modal_form').modal('hide');
        tampilDetail();
        tampilKeterangan();
    });
});
var MyTable = $('#list-po').dataTable({
    "responsive": true,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true
});

var tableKeterangan = $('#list-keterangan').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false,
    "autoWidth": true,
    "pageLength": 5
});

function selectPart(id_part, no_part, nama_part, satuan, stok, harga_baru) {
    var tgl_estimasi_penawaran = document.formPo.tgl_estimasi_penawaran.value;
    var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;

    $.ajax({
        method: 'POST',
        url: '<?php echo base_url('EstimasiPenawaran/prosesDetailPo'); ?>',
        data: "tgl_estimasi_penawaran=" + tgl_estimasi_penawaran +
            "&id_estimasi_penawaran=" + id_estimasi_penawaran +
            "&id_part=" + id_part +
            "&no_part=" + no_part +
            "&nama_part=" + nama_part +
            "&satuan=" + satuan +
            "&stok=" + stok +
            "&harga_baru=" + harga_baru
    })

    tampilDetail(id_estimasi_penawaran);

    $('#modal_form').modal('hide');

}

function next(dataPo, dataRef) {
    document.getElementById('id_estimasi_penawaran').value = dataPo;
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
    //var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value = dataPo;
    var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('EstimasiPenawaran/tampilDetail'); ?>',
        data: 'id_estimasi_penawaran=' + id_estimasi_penawaran,
        success: function(hasil) {
            //MyTable.fnDestroy();
            $('#data-po').html(hasil);
        }
    });
}

function insertNote() {
    var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('EstimasiPenawaran/tambahNote'); ?>',
        data: 'id=' + id_estimasi_penawaran,
        success: function(hasil) {
            tampilKeterangan()
        }
    });
}

function tampilKeterangan() {
    var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('EstimasiPenawaran/tampilKeterangan'); ?>',
        data: 'id_estimasi_penawaran=' + id_estimasi_penawaran,
        success: function(hasil) {
            tableKeterangan.fnDestroy();
            $('#data-keterangan').html(hasil);
        }
    });
}

/** Form Keterangan */

$('#form-keterangan').submit(function(e) {
    var data = $(this).serialize();
    var id = document.getElementById('id_estimasi_penawaran').value;

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('EstimasiPenawaran/tambahKeterangan'); ?>',
            data: data + "&id=" + id
        })
        .done(function(data) {
            tampilKeterangan();
            document.getElementById("form-keterangan").reset();
            $('#tambah-keterangan').modal('hide');
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Mantap',
                showConfirmButton: false,
                timer: 1000
            })
        })

    e.preventDefault();
});

$(document).on("click", ".update-dataType", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Settingwh/updateType'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-type').html(data);
            $('#update-type').modal('show');
        })
})
$(document).on('submit', '#form-update-type', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Settingwh/prosesUtype'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showType();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-update-type").reset();
                $('#update-type').modal('hide');
                $('.msg').html(out.msg);
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })

    e.preventDefault();
});
/** end Keterangan */
function tampilDetailCache(dataPo) {
    //var out = jQuery.parseJSON(data);
    var id_estimasi_penawaran = document.getElementById('id_estimasi_penawaran').value = dataPo;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('EstimasiPenawaran/tampilDetailCache'); ?>?id_estimasi_penawaran=' +
            id_estimasi_penawaran,
        data: 'id_estimasi_penawaran=' + id_estimasi_penawaran,
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
            url: '<?php echo base_url('EstimasiPenawaran/prosesPo'); ?>',
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
                $('#tgl_estimasi_penawaran').attr('readonly', 'readonly');
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
            url: "<?php echo base_url('EstimasiPenawaran/cetak'); ?>",
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
            url: "<?php echo base_url('EstimasiPenawaran/deleteDetail'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);
            if (out.status != 'form') {
                //$('.msg').html(out.msg);
                $('#hapusDetail').modal('hide');
                var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;
                //next(next_proses);
                tampilDetail(id_estimasi_penawaran);
            }
        })
})


$(document).on("click", ".delete-keterangan", function() {
    data_id = $(this).attr("data-id");
})
$(document).on("click", ".delete-keterangan", function() {
    var id = data_id;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('EstimasiPenawaran/deleteKeterangan'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);
            if (out.status != 'form') {
                //$('.msg').html(out.msg);
                $('#hapusKeterangan').modal('hide');
                var id_estimasi_penawaran = document.formPo.id_estimasi_penawaran.value;
                //next(next_proses);
                tampilKeterangan(id_estimasi_penawaran);
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