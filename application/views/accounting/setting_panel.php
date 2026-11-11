<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Panel Setting Accounting (master & integrasi). Tata letak tab seperti Pengaturan Gudang; id kontainer/modal sama
   seperti versi lama sehingga proses tambah/ubah/hapus (AJAX) tetap berjalan seperti sebelumnya. */
$jml = function ($t) { return $this->db->table_exists($t) ? $this->db->count_all($t) : 0; };
$S = array(); if ($this->db->table_exists('tbl_acc_setting')) foreach ($this->db->get('tbl_acc_setting')->result() as $r) $S[$r->kunci] = $r->nilai;
$A = array(); if ($this->db->table_exists('tbl_acc_akun')) foreach ($this->db->get('tbl_acc_akun')->result() as $r) $A[$r->kode_akun] = $r;
$supTot = $jml('tbl_wh_supplier'); $supAkun = $supTot ? $this->db->where("kode_akun IS NOT NULL AND kode_akun <> ''", null, false)->count_all_results('tbl_wh_supplier') : 0;
$W = array(); if ($this->db->table_exists('tbl_wh_setting')) foreach ($this->db->get('tbl_wh_setting')->result() as $r) $W[$r->kunci] = $r->nilai;
$petaAkun = array(
    'Umum' => array('akun_laba_ditahan' => 'Laba ditahan', 'akun_laba_berjalan' => 'Laba tahun berjalan', 'akun_pendapatan_default' => 'Pendapatan bawaan', 'akun_beban_default' => 'Beban bawaan'),
    'Piutang & hutang' => array('akun_piutang' => 'Piutang usaha', 'akun_hutang' => 'Hutang usaha'),
    'Aset tetap' => array('akun_akumulasi_default' => 'Akumulasi penyusutan', 'akun_beban_susut_default' => 'Beban penyusutan', 'akun_laba_pelepasan_aset' => 'Laba pelepasan aset', 'akun_rugi_pelepasan_aset' => 'Rugi pelepasan aset'),
    'Pajak' => array('akun_ppn_keluaran' => 'PPN keluaran', 'akun_ppn_masukan' => 'PPN masukan', 'akun_hutang_ppn' => 'Hutang PPN', 'akun_hutang_pph21' => 'Hutang PPh 21', 'akun_hutang_pph23' => 'Hutang PPh 23', 'akun_hutang_pph42' => 'Hutang PPh 4(2)', 'akun_hutang_pph_badan' => 'Hutang PPh badan', 'akun_pph23_dimuka' => 'PPh 23 dibayar dimuka'),
);
$kat = array(); foreach ($A as $a) { $k = isset($a->kategori) && $a->kategori !== '' ? $a->kategori : 'Belum dikategorikan'; $kat[$k] = (isset($kat[$k]) ? $kat[$k] : 0) + 1; } ksort($kat);
$nm = function ($kode) use ($A) { return $kode !== '' && isset($A[$kode]) ? html_escape($kode . ' — ' . $A[$kode]->nama_akun) : ($kode === '' ? '<span class="badge badge-warning">belum diisi</span>' : '<span class="badge badge-danger">' . html_escape($kode) . ' tidak ada di daftar akun</span>'); };
?>
<div class="wh-master">
    <div class="row">
        <div class="col-lg-3 mb-3">
            <div class="card mb-0 h-100">
                <div class="card-body p-2">
                    <div class="text-muted small font-weight-bold text-uppercase px-2 pt-1 pb-2">Pengaturan akuntansi</div>
                    <div class="nav flex-column nav-pills wh-master__nav" role="tablist">
                        <a class="nav-link active" data-toggle="pill" href="#acc-tab-integrasi" role="tab"><i class="fas fa-project-diagram"></i><span>Akun bawaan &amp; integrasi</span></a>
                        <a class="nav-link" data-toggle="pill" href="#acc-tab-supplier" role="tab"><i class="fas fa-truck"></i><span>Akun hutang supplier</span><b><?php echo nf($supAkun) . '/' . nf($supTot); ?></b></a>
                        <a class="nav-link" data-toggle="pill" href="#acc-tab-kelompok" role="tab"><i class="fas fa-layer-group"></i><span>Kelompok Akun</span><b><?php echo nf($jml('tbl_acc_kelompok')); ?></b></a>
                        <a class="nav-link" data-toggle="pill" href="#acc-tab-type" role="tab"><i class="fas fa-tag"></i><span>Type Akun</span><b><?php echo nf($jml('tbl_acc_type')); ?></b></a>
                        <a class="nav-link" data-toggle="pill" href="#acc-tab-jenis" role="tab"><i class="fas fa-receipt"></i><span>Jenis Beban</span><b><?php echo nf($jml('tbl_acc_jenis')); ?></b></a>
                        <a class="nav-link" data-toggle="pill" href="#acc-tab-ref" role="tab"><i class="fas fa-hashtag"></i><span>Jenis Akun (Ref)</span><b><?php echo nf($jml('tbl_acc_ref')); ?></b></a>
                    </div>
                    <div class="wh-master__note">
                        <i class="fas fa-info-circle"></i> Daftar akun ada di menu <a href="<?php echo site_url('Account'); ?>">Daftar Akun</a>; akun bawaan, PPN & periode diatur di <a href="<?php echo site_url('PeriodeAkuntansi'); ?>">Tutup Buku &amp; Preferensi</a>. Panel ini merangkum semuanya.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="acc-tab-integrasi" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-project-diagram mr-2 text-primary"></i>Akun bawaan &amp; integrasi modul</h3>
                                <div class="small text-muted mt-1">Akun yang dipakai otomatis oleh jurnal faktur, pelunasan, penyusutan, pajak, tutup buku dan stock opname.</div>
                            </div>
                            <a class="btn btn-sm btn-primary" href="<?php echo site_url('PeriodeAkuntansi'); ?>"><i class="fas fa-sliders-h mr-1"></i> Ubah preferensi</a>
                        </div>
                        <div class="card-body">
                            <div class="acc-kpi">
                                <div class="acc-kpi__item"><div class="acc-kpi__label">Akun terdaftar</div><div class="acc-kpi__value"><?php echo nf(count($A)); ?></div><div class="acc-kpi__sub"><a href="<?php echo site_url('Account'); ?>">daftar akun ›</a></div></div>
                                <div class="acc-kpi__item"><div class="acc-kpi__label">Tarif PPN</div><div class="acc-kpi__value"><?php echo html_escape($S['ppn_persen'] ?? '-'); ?>%</div><div class="acc-kpi__sub">PKP: <?php echo ($S['pajak_pkp'] ?? 'N') === 'Y' ? 'ya' : 'tidak'; ?> · rezim <?php echo html_escape($S['pajak_rezim'] ?? '-'); ?></div></div>
                                <div class="acc-kpi__item"><div class="acc-kpi__label">Supplier berakun hutang</div><div class="acc-kpi__value <?php echo $supAkun < $supTot ? 'text-warning' : ''; ?>"><?php echo nf($supAkun) . ' / ' . nf($supTot); ?></div><div class="acc-kpi__sub">lihat tab Akun hutang supplier</div></div>
                            </div>
                            <div class="row">
                            <?php foreach ($petaAkun as $g => $ls) { ?>
                                <div class="col-xl-6"><h6 class="mt-2"><?php echo $g; ?></h6>
                                <table class="table table-sm"><tbody>
                                <?php foreach ($ls as $k => $l) { ?><tr><td style="width:45%"><?php echo $l; ?></td><td><?php echo $nm((string) ($S[$k] ?? '')); ?></td></tr><?php } ?>
                                </tbody></table></div>
                            <?php } ?>
                                <div class="col-xl-6"><h6 class="mt-2">Gudang (stock opname)</h6>
                                <table class="table table-sm"><tbody>
                                    <tr><td style="width:45%">Persediaan</td><td><?php echo $nm((string) ($W['akun_persediaan'] ?? '')); ?></td></tr>
                                    <tr><td>Selisih persediaan</td><td><?php echo $nm((string) ($W['akun_selisih_persediaan'] ?? '')); ?></td></tr>
                                    <tr><td>Jurnal opname otomatis</td><td><?php echo ($W['so_jurnal'] ?? 'N') === 'Y' ? 'Aktif' : 'Tidak aktif'; ?> · <a href="<?php echo site_url('OpnameStok'); ?>">atur ›</a></td></tr>
                                </tbody></table></div>
                            </div>
                            <?php if ($kat) { ?>
                            <h6 class="mt-2">Jumlah akun per kategori</h6>
                            <div class="d-flex flex-wrap" style="gap:.4rem"><?php foreach ($kat as $k => $n) { ?><span class="badge badge-light border px-2 py-1"><?php echo html_escape(class_exists('Akuntansi') ? Akuntansi::label_kategori($k) : $k); ?> <b><?php echo $n; ?></b></span><?php } ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="acc-tab-supplier" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-truck mr-2 text-primary"></i>Akun hutang supplier</h3>
                                <div class="small text-muted mt-1">Hubungkan setiap pemasok gudang ke akun hutang & lawan akunnya agar faktur pembelian dan pembayaran hutang terjurnal dengan benar. Klik ikon ubah untuk mengisi.</div>
                            </div>
                            
                        </div>
                        <div class="card-body"><div id="data-supplier"></div></div>
                        <div id="modal-supplier"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="acc-tab-kelompok" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-layer-group mr-2 text-primary"></i>Kelompok Akun</h3>
                                <div class="small text-muted mt-1">Kelompok besar pada daftar akun (Aktiva, Kewajiban, Modal, Pendapatan, Biaya, …).</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-kelompok" title="Tambah data"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div class="card-body"><div id="data-kelompok"></div></div>
                        <div id="modal-kelompok"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="acc-tab-type" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-tag mr-2 text-primary"></i>Type Akun</h3>
                                <div class="small text-muted mt-1">Tipe akun pada daftar akun (mis. Header / Detail, Lancar / Tidak lancar).</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-type" title="Tambah data"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div class="card-body"><div id="data-type"></div></div>
                        <div id="modal-type"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="acc-tab-jenis" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-receipt mr-2 text-primary"></i>Jenis Beban</h3>
                                <div class="small text-muted mt-1">Klasifikasi biaya (langsung, operasional, administrasi & umum, …) untuk laporan laba rugi.</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-jenis" title="Tambah data"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div class="card-body"><div id="data-jenis"></div></div>
                        <div id="modal-jenis"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="acc-tab-ref" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:.5rem">
                            <div>
                                <h3 class="card-title mb-0"><i class="fas fa-hashtag mr-2 text-primary"></i>Jenis Akun (Ref)</h3>
                                <div class="small text-muted mt-1">Kode referensi jurnal beserta keterangannya.</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambah-ref" title="Tambah data"><i class="fas fa-plus mr-1"></i> Tambah</button>
                        </div>
                        <div class="card-body"><div id="data-ref"></div></div>
                        <div id="modal-ref"></div>
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

<?php
show_my_confirm('hapusKelompok', 'hapus-kelompok', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusType', 'hapus-type', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusJenis', 'hapus-jenis', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
show_my_confirm('hapusRef', 'hapus-ref', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>
<script type="text/javascript">
    window.onload = function() {
        showKel();
        showType();
        showJen();
        showRef();
        showSup();
    }
    function showKel() {
        $.get('<?php echo base_url('SettingAcc/showKel'); ?>', function(data) {
            $('#data-kelompok').html(data);
        });
    }

    function showType() {
        $.get('<?php echo base_url('SettingAcc/showType'); ?>', function(data) {
            $('#data-type').html(data);
        });
    }

    function showJen() {
        $.get('<?php echo base_url('SettingAcc/showJenis'); ?>', function(data) {
            $('#data-jenis').html(data);
        });
    }
    function showRef() {
        $.get('<?php echo base_url('SettingAcc/showRef'); ?>', function(data) {
            $('#data-ref').html(data);
        });
    }
    function showSup() {
        $.get('<?php echo base_url('SettingAcc/showSup'); ?>', function(data) {
            $('#data-supplier').html(data);
        });
    }

    $('#form-tambah-kelompok').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTkelompok'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKel();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-kelompok").reset();
                    $('#tambah-kelompok').modal('hide');
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

    $(document).on("click", ".update-dataKelompok", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateKelompok'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-kelompok').html(data);
                $('#update-kelompok').modal('show');
            })
    })
    $(document).on('submit', '#form-update-kelompok', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUkelompok'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showKel();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-kelompok").reset();
                    $('#update-kelompok').modal('hide');
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

    $('#tambah-kelompok').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-kelompok').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-kelompok", function() {
        id_lap = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-kelompok", function() {
        var id = id_lap;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteKelompok'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showKel();
                $('.msg').html(out.msg);
                $('#hapusKelompok').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })


    //*** end Keterangan Laporan **//

    $('#form-tambah-type').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTtype'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showType();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-type").reset();
                    $('#tambah-type').modal('hide');
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

    $(document).on("click", ".update-dataType", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateType'); ?>",
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
                url: '<?php echo base_url('SettingAcc/prosesUtype'); ?>',
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

    $('#tambah-type').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-type').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-type", function() {
        id_kat = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-type", function() {
        var id = id_kat;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteType'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showType();
                $('.msg').html(out.msg);
                $('#hapusType').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })
    //*** end Type **//

    $('#form-tambah-jenis').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTjenis'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showJen();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-jenis").reset();
                    $('#tambah-jenis').modal('hide');
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

    $(document).on("click", ".update-dataJenis", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateJenis'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-jenis').html(data);
                $('#update-jenis').modal('show');
            })
    })
    $(document).on('submit', '#form-update-jenis', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUjenis'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showJen();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-jenis").reset();
                    $('#update-jenis').modal('hide');
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

    $('#tambah-jenis').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-jenis').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-jenis", function() {
        id_j = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-jenis", function() {
        var id = id_j;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteJenis'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showJen();
                $('.msg').html(out.msg);
                $('#hapusJenis').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })
    //** End Jenis akun */

    
    $('#form-tambah-supplier').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTsupplier'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showSup();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-supplier").reset();
                    $('#tambah-supplier').modal('hide');
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

    $(document).on("click", ".update-dataSupplier", function() {
        var id_sup = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateSupplier'); ?>",
                data: "id=" + id_sup
            })
            .done(function(data) {
                $('#modal-supplier').html(data);
                $('#update-supplier').modal('show');
            })
    })
    $(document).on('submit', '#form-update-supplier', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUsupplier'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showSup();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-supplier").reset();
                    $('#update-supplier').modal('hide');
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

    $('#tambah-supplier').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-supplier').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-supplier", function() {
        id_sup = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-supplier", function() {
        var id = id_sup;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteSupplier'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showSup();
                $('.msg').html(out.msg);
                $('#hapusSupplier').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })
    //** end Supplier */
    
    $('#form-tambah-ref').submit(function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesTref'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showRef();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-tambah-ref").reset();
                    $('#tambah-ref').modal('hide');
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

    $(document).on("click", ".update-dataRef", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/updateRef'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-ref').html(data);
                $('#update-ref').modal('show');
            })
    })
    $(document).on('submit', '#form-update-ref', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('SettingAcc/prosesUref'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                showRef();
                if (out.status == 'form') {
                    $('.form-msg').html(out.msg);
                    effect_msg_form();
                } else {
                    document.getElementById("form-update-ref").reset();
                    $('#update-ref').modal('hide');
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

    $('#tambah-ref').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })

    $('#update-ref').on('hidden.bs.modal', function() {
        $('.form-msg').html('');
    })
    $(document).on("click", ".delete-ref", function() {
        id_j = $(this).attr("data-id");
    })
    $(document).on("click", ".hapus-ref", function() {
        var id = id_j;

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('SettingAcc/deleteRef'); ?>",
                data: "id=" + id
            })

            .done(function(data) {
                var out = jQuery.parseJSON(data);
                showRef();
                $('.msg').html(out.msg);
                $('#hapusRef').modal('hide');
                if (out.status != 'form') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    })
</script>