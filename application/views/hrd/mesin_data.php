<section class="content">
      <div class="container-fluid">
        <div class="row">
    <div class="col-8">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title">
                                        <i class="fa fa-cog text-blue"></i>
                                        &nbsp; Data Mesin Absensi</h3>
                                    <div class="text-right">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-toggle="modal"
                                            data-target="#tambah-mesin"
                                            title="Add Data">
                                            <i class="fas fa-plus"></i>
                                            Add Mesin</button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-striped  table-bordered table-hover dt-responsive nowrap"
                                                id="list-mesin">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>IP</th>
                                                        <th>Key</th>
                                                        <th>Nama Mesin</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-mesin"></tbody>
                                                <tfoot></tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="modal-mesin"></div>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-lg-6">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title">
                                        <i class="fa fa-cog text-blue"></i>
                                        &nbsp; Upload Nama</h3>
                                    <div class="text-right"></div>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <form id="form-uploadNama" name="form-uploadNama" method="POST">
                                            <div class="form-group row">
                                                <label for="Nama Konsumen" class="col-sm-2 col-form-label">IP Mesin</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                        <input
                                                            type="text"
                                                            name="ip"
                                                            id="ip"
                                                            value=""
                                                            class="form-control"
                                                            placeholder="IP Mesin Absen">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Key</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="pass" id="pass" value="" class="form-control" placeholder="Key">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">NIP</label>
                                                <div class="col-sm-10">
                                                    <input
                                                        type="text"
                                                        name="nip"
                                                        id="nip"
                                                        value=""
                                                        class="form-control"
                                                        placeholder="NIP Pegawai">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Nama</label>
                                                <div class="col-sm-10">
                                                    <input
                                                        type="text"
                                                        name="nama"
                                                        id="nama"
                                                        value=""
                                                        class="form-control"
                                                        placeholder="Nama yang dirubah">
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button class="btn btn-primary " type="submit">
                                                    <span class="fa fa-save"></span>
                                                    Simpan</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title">
                                        <i class="fa fa-cog text-blue"></i>
                                        &nbsp; Clear Log Data Mesin</h3>
                                    <div class="text-right"></div>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <form id="form-uploadNama" name="form-uploadNama" method="POST">
                                            <div class="form-group row">
                                                <label for="Nama Konsumen" class="col-sm-2 col-form-label">IP Mesin</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                        <input
                                                            type="text"
                                                            name="ip"
                                                            id="ip"
                                                            value=""
                                                            class="form-control"
                                                            placeholder="IP Mesin Absen">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Key</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="pass" id="pass" value="" class="form-control" placeholder="Key">
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button class="btn btn-primary " type="submit">
                                                    <span class="fa fa-save"></span>
                                                    Clear</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                        
                    </div>
                        </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    <div class="row">
    <div class="col-12 ">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title">
                                        <i class="fa fa-cog text-blue"></i>
                                        &nbsp; Data Mesin Absensi</h3>
                                    <div class="text-right">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-toggle="modal"
                                            data-target="#tambah-mesin"
                                            title="Add Data">
                                            <i class="fas fa-plus"></i>
                                            Add Mesin</button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-striped  table-bordered table-hover dt-responsive nowrap"
                                                id="list-absensi">
                                                <thead>
                                                    <tr>
                                                        <th>PIN</th>
                                                        <th>DateTime</th>
                                                        <th>Ver</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-absensi"></tbody>
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
    </div>
</section>

<?php show_my_confirm('hapusMesin', 'hapus-mesin', 'Hapus Data Mesin Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

<script type="text/javascript">
window.onload = function () {
showDev();
}

function refresh() {
MyTable = $('#list-mesin,#list-absensi').dataTable();
}

function effect_msg_form() {
// $('.form-msg').hide();
$('.form-msg').show(500);
setTimeout(function () {
$('.form-msg').fadeOut(500);
}, 1000);
}

function effect_msg() {
// $('.msg').hide();
$('.msg').show(500);
setTimeout(function () {
$('.msg').fadeOut(500);
}, 1000);
}

var tableDev = $('#list-mesin').dataTable({"pageLength": 5});
var tableAbsen = $('#list-absensi').dataTable({"pageLength": 5});

//ajax mesin
function showDev() {
$.get('<?php echo base_url('Mesin_absen/showDev'); ?>', function (data) {
tableDev.fnDestroy();
$('#data-mesin').html(data);
refresh();
});
}
function showAbsen() {
$.get('<?php echo base_url('Mesin_absen/downloadMesin'); ?>', function (data) {
tableAbsen.fnDestroy();
$('#data-absensi').html(data);
refresh();
});
}

$('#form-tambah-mesin').submit(function (e) {
var data = $(this).serialize();

$
.ajax({
    method: 'POST',
    url: '<?php echo base_url('Mesin_absen/prosesTmesin '); ?>',
    data: data
})
.done(function (data) {
    var out = jQuery.parseJSON(data);

    showDev();
    if (out.status == 'form') {
        $('.form-msg').html(out.msg);
        effect_msg_form();
    } else {
        document
            .getElementById("form-tambah-mesin")
            .reset();
        $('#tambah-mesin').modal('hide');
        $('.msg').html(out.msg);
        Swal.fire(
            {position: 'top-end', icon: 'success', title: out.msg, showConfirmButton: false, timer: 1500}
        )
    }
})

e.preventDefault();
});
$('#form-uploadNama').submit(function (e) {
var data = $(this).serialize();

$
.ajax({
    method: 'POST',
    url: '<?php echo base_url('Mesin_absen/prosesUnama '); ?>',
    data: data
})
.done(function (data) {
    var out = jQuery.parseJSON(data);

    //showDev();
    if (out.status == 'form') {
        $('.form-msg').html(out.msg);
        effect_msg_form();
    } else {
        document
            .getElementById("form-uploadNama")
            .reset();
        $('.msg').html(out.msg);
        Swal.fire(
            {position: 'top-end', icon: 'success', title: 'Sukses', showConfirmButton: false, timer: 1500}
        )
    }
})

e.preventDefault();
});

$(document).on("click", ".download-dataMesin", function () {
var ip = $(this).attr("data-ip");
var pass = $(this).attr("data-pass");

$
.ajax({
    method: "POST",
    url: "<?php echo base_url('Mesin_absen/downloadMesin'); ?>",
    data: "ip=" + ip + "&pass=" + pass
})
.done(function (data) {
$('#data-absensi').html(data);
refresh();
    //showAbsen();
    Swal.fire(
        {position: 'top-end', icon: 'success', title: 'Data Telah di dowload', showConfirmButton: false, timer: 1500}
    )
    // $('#modal-mesin').html(data); $('#update-mesin').modal('show');
})
})
$(document).on("click", ".update-dataMesin", function () {
var id = $(this).attr("data-id");

$
.ajax({
    method: "POST",
    url: "<?php echo base_url('Mesin_absen/updateMesin'); ?>",
    data: "id=" + id
})
.done(function (data) {
    $('#modal-mesin').html(data);
    $('#update-mesin').modal('show');
})
})

$(document).on('submit', '#form-update-mesin', function (e) {
var data = $(this).serialize();

$
.ajax({
    method: 'POST',
    url: '<?php echo base_url('Mesin_absen/prosesUmesin '); ?>',
    data: data
})
.done(function (data) {
    var out = jQuery.parseJSON(data);

    showDev();
    if (out.status == 'form') {
        $('.form-msg').html(out.msg);
        effect_msg_form();
    } else {
        document
            .getElementById("form-update-mesin")
            .reset();
        $('#update-mesin').modal('hide');
        $('.msg').html(out.msg);
        Swal.fire(
            {position: 'top-end', icon: 'success', title: out.msg, showConfirmButton: false, timer: 1500}
        )
    }
})

e.preventDefault();
});

$('#tambah-mesin').on('hidden.bs.modal', function () {
$('.form-msg').html('');
})

$('#update-mesin').on('hidden.bs.modal', function () {
$('.form-msg').html('');
})
$(document).on("click", ".delete-mesin", function () {
id_sat = $(this).attr("data-id");
})
$(document).on("click", ".hapus-mesin", function () {
var id = id_sat;

$
.ajax({
    method: "POST",
    url: "<?php echo base_url('Mesin_absen/deleteMesin'); ?>",
    data: "id=" + id
})
.done(function (data) {
    var out = jQuery.parseJSON(data);
    showDev();
    if (out.status != 'form') {
        Swal.fire(
            {position: 'top-end', icon: 'error', title: out.msg, showConfirmButton: false, timer: 1500}
        )
        //$('.msg').html(out.msg);
        $('#hapusMesin').modal('hide');
    }
})
})
</script>