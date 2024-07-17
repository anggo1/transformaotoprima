<style>
.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 12px;
}

table.dataTable td {
    padding-bottom: 5px;
}

input[type="checkbox"]::before {
    content: "";
    width: 0.65em;
    height: 0.65em;
    clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    transform: scale(0);
    transform-origin: bottom left;
    transition: 120ms transform ease-in-out;
    box-shadow: inset 2em 4em var(--form-control-color);
    /* Windows High Contrast Mode */
    background-color: green;


}

input[type="checkbox"]:checked::before {
    transform: scale(5);
}

input[type="checkbox"]:focus {
    outline: max(3px, 0.15em) solid currentColor;
    outline-offset: max(2px, 0.15em);

}

input-besar,
textarea {
    text-transform: uppercase;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <!-- /.card-header -->
                    <div class="modal-content">
                        <div class="modal-header text-blue">
                            <h5 style="display:block; text-align:center;"><span
                                    class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Penerimaan Barang Non PO
                            </h5>
                            <button type="button" class="btn btn-success" id="tambah" hidden="hidden"
                                onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> Data
                                Baru</button>
                        </div>
                        <div class="modal-body">
                            <?php
								$tgl_masuk = date("y-m-d");
								$date = date("ym");
								$ci_kons = get_instance();
								$query = "SELECT max(kode_masuk) AS maxKode FROM tbl_wh_part_masuk WHERE kode_masuk LIKE '%$date%'";
								$hasil = $ci_kons->db->query($query)->row_array();
								$noOrder = $hasil['maxKode'];
								$noUrut = (int)substr($noOrder, 4, 5);
								$noUrut++;
								$tahun = substr($date, 0, 2);
								$bulan = substr($date, 2, 2);

								$kode_awal  = $tahun.$bulan.sprintf("%04s", $noUrut);
							?>
                            <form id="formpartmasuk" name="formpartmasuk" method="POST">
                                <input type="hidden" name="kode_masuk" id="kode_masuk" value="<?php echo $kode_awal ?>"
                                    class="form-control" readonly>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-4">
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                            <input type="text" name="date1" id="date1" value=""
                                                class="form-control date1 datetimepicker" data-toggle="datetimepicker"
                                                data-target=".date1" data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <label class="col-sm-2 col-form-label">Status</label>

                                    <div class="col-sm-2">
                                        <select name="lokasi" id="lokasi" class="form-control" <?php  $lvl = $this->session->userdata['id_level']; 
                                        if ($lvl !='1' && $lvl !='12'){ echo 'disabled';} ?>>
                                            <option value="">Cabang Dealer...
                                            </option>
                                            <?php
                                            $lok = $this->session->userdata['lokasi'];
                                                                    foreach ($dataKota as $kel) { ?>
                                            <option value="<?php echo $kel->kode_kota.'|'.$kel->nama_kota; ?>"
                                                <?php if ($kel->nama_kota == $lok) { echo "selected='selected'"; } ?>>
                                                <?php echo $kel->nama_kota; ?>
                                            </option>
                                            <?php }  ?>
                                        </select>
                                    </div>
                                    <!--
                                    <div class="col-sm-1">
                                        <select name="status" id="status" class="form-control"
                                            onchange="fungsiKode(event)" required>
                                            <option value="">Pilih Status ...</option>
                                            <option value="PPU">PPU</option>
                                            <option value="MPU">MPU</option>
                                        </select>
                                    </div>
                                                                    -->
                                    <div class="col-sm-2">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">No SJ</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="no_sj_sup" id="no_sj_sup" class="form-control">
                                    </div>
                                    <label class="col-sm-2 col-form-label">No INV</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="no_inv_sup" id="no_inv_sup" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Supplier</label>
                                    <div class="col-sm-4">
                                        <select name="supplier" id="supplier" class="form-control">
                                            <option value="">Supplier...
                                            </option>
                                            <?php
											if (!empty($dataSup)) {
												foreach ($dataSup as $sp) {   ?>
                                            <option value="<?php echo $sp->kode_sup; ?>">
                                                <?php echo $sp->nama_sup; ?>
                                            </option>
                                            <?php
												}
											}
											?>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 col-form-label">Keterangan</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="keterangan" id="keterangan" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Barang Kembali ? </label>
                                    <div class="col-sm-4">
                                        <input type="checkbox" name="return" id="return" value="Y"> Ya
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Barang" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-4">
                                    </div>
                                </div>
                                <div id="data-masuk"></div>
                                <div id="data-masuk-cache"></div>
                                <input type="hidden" name="user" id="user"
                                    value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                                <input type="hidden" name="id_masuk" id="id_masuk" class="form-control" value="<?php echo $kode_awal ?>" required readonly>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-xl btn-outline-primary" id="tambahBarang"
                                        title="Add Part" data-toggle="modal" data-target="#modal_form"><i
                                            class="fas fa-plus"></i> Tambah Barang</button>
                                    <button class="btn btn-primary" id="simpan" type="submit"><span
                                            class="fa fa-save"></span> Simpan</button>
                                    <button type="button" class="btn btn-success cetak-masuk" id="cetak" hidden="hidden"
                                        data-id="" title="Cetak"><i class="fas fa-print"></i> Cetak Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="modal-masuk"></div>
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

        <?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->
<script type="text/javascript">
$('#date1,#tgl_po,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
window.onload = function() {
    //startRefresh();
}
var MyTable = $('#listpomasuk').dataTable({
    "responsive": true,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true
});

function startRefresh() {
    setTimeout(startRefresh, 1000);
    $.get('Part_masuk_npo', function(data) {
        $('#data-masuk').html(data);
    });
}

function fungsiKode(event) {
    var status = event.target;
    var hasil = status.value;
    var kode_masuk = document.getElementById("kode_masuk").value;
    if (hasil == 'PPU') {
        kd = 'PTB-';
        document.getElementById('id_masuk').value = kd + kode_masuk;
    } else if (hasil == 'MPU') {
        kd = 'MTB-';
        document.getElementById('id_masuk').value = kd + kode_masuk;
    }

}

function refresh() {
    MyTable = $('#listpomasuk').dataTable();
}

function tampilDetail(kode_masuk) {
    //var kode_masuk = document.getElementById('id_masuk').value = kode_masuk;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('Part_masuk_npo/tampilDetail'); ?>?kode_masuk=' + kode_masuk,
        data: 'kode_masuk=' + kode_masuk,
        success: function(hasil) {
            MyTable.fnDestroy();
            $('#data-masuk').html(hasil);
            refresh();
        }
    });
}
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
            "url": "<?php echo site_url('Part_masuk_npo/ajax_list') ?>",
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
    var tgl_masuk = document.formpartmasuk.date1.value;
    var kode_masuk = document.formpartmasuk.kode_masuk.value;
    $('#table-part tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var id_part = data[6];
        var no_part = data[1];
        var nama_part = data[2];
        var stok = data[3];
        var satuan = data[5];
        var stok_jkt = data[7];
        var stok_cbt = data[8];
        var stok_sby = data[9];
        var harga_baru = data[10];
        $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Part_masuk_npo/prosesDetailInput'); ?>',
            data: "tgl_masuk=" + tgl_masuk +
                "&kode_masuk=" + kode_masuk +
                "&id_part=" + id_part +
                "&no_part=" + no_part +
                "&nama_part=" + nama_part +
                "&stok=" + stok +
                "&satuan=" + satuan +
                "&stok_jkt=" + stok_jkt +
                "&stok_cbt=" + stok_cbt +
                "&stok_sby=" + stok_sby +
                "&harga_baru=" + harga_baru
        })
        tampilDetail(kode_masuk);
        tampilDetail(kode_masuk);
        $('#modal_form').modal('hide');

    });
});

function tampilDetailCache(kode_masuk) {
    //var out = jQuery.parseJSON(data);
    var kode_masuk = document.getElementById('kode_masuk').value = kode_masuk;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('Part_masuk_npo/tampilDetailCache'); ?>?kode_masuk=' + kode_masuk,
        data: 'kode_masuk=' + kode_masuk,
        success: function(hasil) {
            MyTable.fnDestroy();
            $('#data-masuk-cache').html(hasil);
            refresh();
        }
    });
}
$('#formpartmasuk').submit(function(e) {
    //document.getElementById("detailPart").hidden = false;
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Part_masuk_npo/prosesPartmasuk'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                //toastr.error(out.msg);
                $('.msg').html(out.msg);
                refresh();
                effect_msg();
            } else {
                $('.msg').html(out.msg);
                document.getElementById("formpartmasuk"); //reset()	
                $('#date1').attr('readonly', 'readonly');
                $('#keterangan').attr('readonly', 'readonly');
                $('#status').attr('readonly', 'readonly');
                $('#no_sj_sup').attr('readonly', 'readonly');
                $('#no_inv_sup').attr('readonly', 'readonly');
                $('#supplier').attr('readonly', 'readonly');
                var d = document.getElementById("cetak");
                d.setAttribute('data-id', out.dataPo);
                document.getElementById("cetak").hidden = false;
                document.getElementById("tambah").hidden = false;
                document.getElementById("tambahBarang").hidden = true;
                document.getElementById("simpan").hidden = true;
                document.getElementById("data-masuk").hidden = true;
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


$(document).on("click", ".cetak-masuk", function() {
    var id = $(this).attr("data-id");
    //var id = document.getElementById('next_proses').value=datakode;
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Part_masuk_npo/cetak'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-masuk').html(data);
            $('#cetak-masuknpo').modal('show');
        })
})
var data_id;
$(document).on("click", ".delete-detail", function() {
    data_id = $(this).attr("data-id");
})
$(document).on("click", ".hapus-detail", function() {
    var id = data_id;

    var dataPo = document.getElementById("id_masuk").value;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Part_masuk_npo/deleteDetail'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);
            if (out.status != 'form') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#hapusDetail').modal('hide');
                //var id_po = document.formPo.id_po.value;
                //next(next_proses);
                tampilDetail(dataPo);
            }
        })
})
</script>