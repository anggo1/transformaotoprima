<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Panel Setting HRD (master data). Tata letak tab seperti Pengaturan Gudang; id tabel/tbody/modal sama seperti versi lama
   sehingga seluruh proses tambah/ubah/hapus (AJAX) tetap berjalan seperti sebelumnya. */
$jml = function ($t) { return $this->db->table_exists($t) ? $this->db->count_all($t) : 0; };
$peg = $jml('tbl_hrd_pegawai');
$perDep = array(); if ($this->db->table_exists('tbl_hrd_pegawai')) foreach ($this->db->select('departement, COUNT(*) AS n', false)->group_by('departement')->get('tbl_hrd_pegawai')->result() as $r) $perDep[$r->departement] = (int) $r->n;
$perJab = array(); if ($this->db->table_exists('tbl_hrd_pegawai')) foreach ($this->db->select('jabatan, COUNT(*) AS n', false)->group_by('jabatan')->get('tbl_hrd_pegawai')->result() as $r) $perJab[$r->jabatan] = (int) $r->n;
?>
<div class="wh-master">
    <div class="row">
        <div class="col-lg-3 mb-3">
            <div class="card mb-0 h-100">
                <div class="card-body p-2">
                    <div class="text-muted small font-weight-bold text-uppercase px-2 pt-1 pb-2">Master data HRD</div>
                    <div class="nav flex-column nav-pills wh-master__nav" role="tablist">
                        <a class="nav-link active" data-toggle="pill" href="#hr-tab-departement" role="tab"><i class="fas fa-sitemap"></i><span>Departemen</span><b><?php echo nf($jml('tbl_hrd_departement')); ?></b></a>
                        <a class="nav-link" data-toggle="pill" href="#hr-tab-jabatan" role="tab"><i class="fas fa-user-tie"></i><span>Jabatan</span><b><?php echo nf($jml('tbl_hrd_jabatan')); ?></b></a>
                        <a class="nav-link" data-toggle="pill" href="#hr-tab-pendidikan" role="tab"><i class="fas fa-user-graduate"></i><span>Pendidikan</span><b><?php echo nf($jml('tbl_hrd_pendidikan')); ?></b></a>
                        <a class="nav-link" data-toggle="pill" href="#hr-tab-kdcuti" role="tab"><i class="fas fa-plane-departure"></i><span>Kode Cuti</span><b><?php echo nf($jml('tbl_hrd_kodecuti')); ?></b></a>
                        <a class="nav-link" data-toggle="pill" href="#hr-tab-ringkas" role="tab"><i class="fas fa-project-diagram"></i><span>Ringkasan &amp; modul terkait</span></a>
                    </div>
                    <div class="wh-master__note">
                        <i class="fas fa-info-circle"></i> Master HRD dipakai di data pegawai, cuti, dashboard per departemen & laporan HRD. Hindari menghapus data yang sudah dipakai pegawai — ubah namanya saja.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="hr-tab-departement" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-sitemap mr-2 text-primary"></i>Departemen</h3>
                                <div class="small text-muted mt-1">Unit kerja (HRD, Marketing, Teknik, Keuangan, …). Dipakai pada data pegawai, <b>dashboard per departemen</b> dan laporan komposisi pegawai.</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-departement" title="Tambah data"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover nowrap w-100" id="list-departement">
                                    <thead><tr><th style="width:50px">No</th><th>Departemen</th><th class="text-center" style="width:90px">Aksi</th></tr></thead>
                                    <tbody id="data-departement"></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="modal-departement"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="hr-tab-jabatan" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-user-tie mr-2 text-primary"></i>Jabatan</h3>
                                <div class="small text-muted mt-1">Posisi/jabatan pegawai (Manager, Supervisor, Staff, Mekanik, …). Menjadi dasar matriks departemen × jabatan di Laporan HRD.</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-jabatan" title="Tambah data"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover nowrap w-100" id="list-jabatan">
                                    <thead><tr><th style="width:50px">No</th><th>Jabatan</th><th class="text-center" style="width:90px">Aksi</th></tr></thead>
                                    <tbody id="data-jabatan"></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="modal-jabatan"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="hr-tab-pendidikan" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-user-graduate mr-2 text-primary"></i>Pendidikan</h3>
                                <div class="small text-muted mt-1">Jenjang pendidikan terakhir (SD s.d. S3). Dipakai untuk analisis kualifikasi pegawai.</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-pendidikan" title="Tambah data"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover nowrap w-100" id="list-pendidikan">
                                    <thead><tr><th style="width:50px">No</th><th>Pendidikan</th><th class="text-center" style="width:90px">Aksi</th></tr></thead>
                                    <tbody id="data-pendidikan"></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="modal-pendidikan"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="hr-tab-kdcuti" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-plane-departure mr-2 text-primary"></i>Kode Cuti</h3>
                                <div class="small text-muted mt-1">Jenis cuti/izin (cuti tahunan, sakit, melahirkan, izin khusus, …). Pisahkan cuti tahunan dari cuti khusus agar rekap sisa hak cuti akurat.</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-kdcuti" title="Tambah data"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover nowrap w-100" id="list-kdcuti">
                                    <thead><tr><th style="width:50px">No</th><th>Kode</th><th>Nama</th><th class="text-center" style="width:90px">Aksi</th></tr></thead>
                                    <tbody id="data-kdcuti"></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="modal-kdcuti"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="hr-tab-ringkas" role="tabpanel">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-project-diagram mr-2 text-primary"></i>Ringkasan &amp; modul terkait</h3></div>
                        <div class="card-body">
                            <div class="acc-kpi">
                                <div class="acc-kpi__item"><div class="acc-kpi__label">Pegawai</div><div class="acc-kpi__value"><?php echo nf($peg); ?></div><div class="acc-kpi__sub"><a href="<?php echo site_url('Pegawai'); ?>">data pegawai ›</a></div></div>
                                <div class="acc-kpi__item"><div class="acc-kpi__label">Tanpa departemen</div><div class="acc-kpi__value"><?php echo nf(($perDep[''] ?? 0) + ($perDep['0'] ?? 0)); ?></div><div class="acc-kpi__sub">perlu dilengkapi</div></div>
                                <div class="acc-kpi__item"><div class="acc-kpi__label">Mesin absen</div><div class="acc-kpi__value"><?php echo nf($jml('tbl_hrd_mesin')); ?></div><div class="acc-kpi__sub"><a href="<?php echo site_url('Mesin_absen'); ?>">kelola mesin ›</a></div></div>
                            </div>
                            <?php if ($this->db->table_exists('tbl_hrd_departement')) { ?>
                            <h6 class="mt-2">Pegawai per departemen</h6>
                            <table class="table table-sm" style="max-width:520px"><thead><tr><th>Departemen</th><th class="num">Pegawai</th></tr></thead><tbody>
                            <?php foreach ($this->db->order_by('departement')->get('tbl_hrd_departement')->result() as $d) { ?><tr><td><?php echo html_escape($d->departement); ?></td><td class="num"><?php echo nf($perDep[$d->id_departement] ?? 0); ?></td></tr><?php } ?>
                            </tbody></table>
                            <?php } ?>
                            <h6 class="mt-3">Alur HRD</h6>
                            <div class="pd-flow">
                                <div><strong>Panel Setting HRD</strong>departemen, jabatan, pendidikan, kode cuti</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
                                <div><strong><a href="<?php echo site_url('Pegawai'); ?>">Employe</a></strong>data pegawai</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
                                <div><strong><a href="<?php echo site_url('Cuti'); ?>">Cuti</a> &amp; <a href="<?php echo site_url('Mesin_absen'); ?>">Mesin Absen</a></strong>kehadiran</div><div class="pd-arrow"><i class="fas fa-arrow-right"></i></div>
                                <div><strong><a href="<?php echo site_url('LaporanHR'); ?>">Laporan HRD</a></strong>komposisi, cuti, absensi</div>
                            </div>
                            <ul class="small text-muted mb-0 mt-2">
                                <li>Petakan akun user ke departemen di <a href="<?php echo site_url('PengaturanDashboard#departemen'); ?>">Pengaturan Dashboard</a> agar setiap departemen mendapat dashboard sendiri.</li>
                                <li>Isi NIPA pegawai sama dengan PIN di mesin absen agar rekap absensi tampil per nama.</li>
                                <li>Periksa <a href="<?php echo site_url('LaporanHR/kelengkapan'); ?>">Kelengkapan Data Pegawai</a> secara berkala (NPWP, BPJS, tanggal lahir/masuk).</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).on('shown.bs.tab', '.wh-master__nav a', function () {
    if ($.fn.dataTable) $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
</script>

<?php show_my_confirm('hapusPendidikan', 'hapus-pendidikan', 'Hapus Data Pendidikan Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>
<?php show_my_confirm('hapusJabatan', 'hapus-jabatan', 'Hapus Data Jabatan Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>
<?php show_my_confirm('hapusDepartement', 'hapus-departement', 'Hapus Data Departement Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>
<?php show_my_confirm('hapusKdcuti', 'hapus-kdcuti', 'Hapus Data Kode Cuti Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>
<script type="text/javascript">
window.onload = function() {
    showPend();
    showDep();
    showJab();
    showCt();
}

function refresh() {
    MyTable = $('#list-pendidikan,#list-departement,#list-jabatan,#list-kdcuti').dataTable();
}

function effect_msg_form() {
    // $('.form-msg').hide();
    $('.form-msg').show(500);
    setTimeout(function() {
        $('.form-msg').fadeOut(500);
    }, 1000);
}

function effect_msg() {
    // $('.msg').hide();
    $('.msg').show(500);
    setTimeout(function() {
        $('.msg').fadeOut(500);
    }, 1000);
}

var tablePendidikan = $('#list-pendidikan').DataTable();
var tableDepartement = $('#list-departement').DataTable();
var tableJabatan = $('#list-jabatan').DataTable();
var tableCt = $('#list-kdcuti').DataTable();

//ajax Jabatan
function showPend() {
    $.get('<?php echo base_url('SettingHrd/showPend'); ?>', function(data) {
        tablePendidikan.destroy();
        $('#data-pendidikan').html(data);
        refresh();
    });
}

function showDep() {
    $.get('<?php echo base_url('SettingHrd/showDep'); ?>', function(data) {
        tableDepartement.destroy();
        $('#data-departement').html(data);
        refresh();
    });
}

function showJab() {
    $.get('<?php echo base_url('SettingHrd/showJab'); ?>', function(data) {
        tableJabatan.destroy();
        $('#data-jabatan').html(data);
        refresh();
    });
}

function showCt() {
    $.get('<?php echo base_url('SettingHrd/showCt'); ?>', function(data) {
        tableCt.destroy();
        $('#data-kdcuti').html(data);
        refresh();
    });
}
//
$('#form-tambah-jabatan').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('SettingHrd/prosesTjabatan'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showJab();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-tambah-jabatan").reset();
                $('#tambah-jabatan').modal('hide');
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

$(document).on("click", ".update-dataJabatan", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('SettingHrd/updateJabatan'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-jabatan').html(data);
            $('#update-jabatan').modal('show');
        })
})
$(document).on('submit', '#form-update-jabatan', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('SettingHrd/prosesUjabatan'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showJab();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-update-jabatan").reset();
                $('#update-jabatan').modal('hide');
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

$('#tambah-jabatan').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-jabatan').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})
$(document).on("click", ".delete-jabatan", function() {
    id_sat = $(this).attr("data-id");
})
$(document).on("click", ".hapus-jabatan", function() {
    var id = id_sat;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('SettingHrd/deleteJabatan'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            showJab();
            if (out.status != 'form') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
                //$('.msg').html(out.msg);
                $('#hapusJabatan').modal('hide');
            }
        })
})

//*** end Jabatan **//
$('#form-tambah-departement').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('SettingHrd/prosesTdepartement'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showDep();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-tambah-departement").reset();
                $('#tambah-departement').modal('hide');
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

$(document).on("click", ".update-dataDepartement", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('SettingHrd/updateDepartement'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-departement').html(data);
            $('#update-departement').modal('show');
        })
})
$(document).on('submit', '#form-update-departement', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('SettingHrd/prosesUdepartement'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showDep();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-update-departement").reset();
                $('#update-departement').modal('hide');
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

$('#tambah-jabatan').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-jabatan').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})
$(document).on("click", ".delete-departement", function() {
    id_sat = $(this).attr("data-id");
})
$(document).on("click", ".hapus-departement", function() {
    var id = id_sat;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('SettingHrd/deleteDepartement'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            showDep();
            if (out.status != 'form') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
                //$('.msg').html(out.msg);
                $('#hapusDepartement').modal('hide');
            }
        })
})
//*** end Departement **//
$('#form-tambah-pendidikan').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('SettingHrd/prosesTpendidikan'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showPend();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-tambah-pendidikan").reset();
                $('#tambah-pendidikan').modal('hide');
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

$(document).on("click", ".update-dataPendidikan", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('SettingHrd/updatePendidikan'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-pendidikan').html(data);
            $('#update-pendidikan').modal('show');
        })
})
$(document).on('submit', '#form-update-pendidikan', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('SettingHrd/prosesUpendidikan'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showPend();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-update-pendidikan").reset();
                $('#update-pendidikan').modal('hide');
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

$('#tambah-jabatan').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-jabatan').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$(document).on("click", ".delete-pendidikan", function() {
    id_sat = $(this).attr("data-id");
})
$(document).on("click", ".hapus-pendidikan", function() {
    var id = id_sat;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('SettingHrd/deletePendidikan'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            showPend();
            if (out.status != 'form') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
                //$('.msg').html(out.msg);
                $('#hapusPendidikan').modal('hide');
            }
        })
})

//*** end Pendidikan **//
$('#form-tambah-kdcuti').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('SettingHrd/prosesTkdcuti'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showCt();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-tambah-kdcuti").reset();
                $('#tambah-kdcuti').modal('hide');
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

$(document).on("click", ".update-dataKdcuti", function() {
    var id = $(this).attr("data-id");

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('SettingHrd/updateKdcuti'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-kdcuti').html(data);
            $('#update-kdcuti').modal('show');
        })
})
$(document).on('submit', '#form-update-kdcuti', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('SettingHrd/prosesUkdcuti'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            showCt();
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("form-update-kdcuti").reset();
                $('#update-kdcuti').modal('hide');
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

$('#tambah-kdcuti').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$('#update-kdcuti').on('hidden.bs.modal', function() {
    $('.form-msg').html('');
})

$(document).on("click", ".delete-kdcuti", function() {
    id_sat = $(this).attr("data-id");
})
$(document).on("click", ".hapus-kdcuti", function() {
    var id = id_sat;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('SettingHrd/deleteKdcuti'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            showCt();
            if (out.status != 'form') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
                //$('.msg').html(out.msg);
                $('#hapusKdcuti').modal('hide');
            }
        })
})
//*** end KOde Cuti **//
</script>