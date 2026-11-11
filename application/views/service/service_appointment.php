<?php defined('BASEPATH') OR exit('No direct script access allowed'); $R = $ringkas; ?>
<div class="wh-steps mb-3">
    <div class="wh-steps__item"><b>1</b><span><strong>Service Appointment</strong>Daftarkan kendaraan, keluhan & jadwal — nomor WO dibuat</span></div>
    <div class="wh-steps__item"><b>2</b><span><strong><a href="<?php echo site_url('PreOrder'); ?>">Pre Order</a></strong>Pemeriksaan awal & kelengkapan kendaraan</span></div>
    <div class="wh-steps__item"><b>3</b><span><strong><a href="<?php echo site_url('WorkOrder'); ?>">Work Order</a></strong>Pekerjaan mekanik, jam kerja & part</span></div>
    <div class="wh-steps__item"><b>4</b><span><strong><a href="<?php echo site_url('ReportService'); ?>">Services Report</a></strong>Laporan & cetak hasil service</span></div>
</div>
<div class="acc-kpi sa-kpi">
    <a class="acc-kpi__item" href="#" data-f="hari"><div class="acc-kpi__label">Appointment hari ini</div><div class="acc-kpi__value"><?php echo nf($R->hari_ini); ?></div><div class="acc-kpi__sub"><?php echo tgl_id(date('Y-m-d')); ?></div></a>
    <a class="acc-kpi__item" href="#" data-f="bulan"><div class="acc-kpi__label">Bulan ini</div><div class="acc-kpi__value"><?php echo nf($R->bulan_ini); ?></div><div class="acc-kpi__sub">belum menjadi WO</div></a>
    <a class="acc-kpi__item" href="#" data-f="menunggu"><div class="acc-kpi__label">Menunggu pre order</div><div class="acc-kpi__value text-warning"><?php echo nf($R->menunggu); ?></div><div class="acc-kpi__sub">klik untuk menyaring</div></a>
    <a class="acc-kpi__item" href="#" data-f="storing"><div class="acc-kpi__label">Storing</div><div class="acc-kpi__value text-danger"><?php echo nf($R->storing); ?></div><div class="acc-kpi__sub">kendaraan derek / mogok</div></a>
    <a class="acc-kpi__item" href="#" data-f="lewat"><div class="acc-kpi__label">Lewat deadline</div><div class="acc-kpi__value <?php echo $R->lewat ? 'text-danger' : ''; ?>"><?php echo nf($R->lewat); ?></div><div class="acc-kpi__sub">target selesai terlewati</div></a>
</div>
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-end justify-content-between" style="gap:.6rem">
        <form class="form-inline" id="sa-filter" style="gap:.4rem">
            <div><label class="small mb-0 d-block">Dari</label><input type="date" name="dari" class="form-control form-control-sm"></div>
            <div><label class="small mb-0 d-block">Sampai</label><input type="date" name="sampai" class="form-control form-control-sm"></div>
            <div><label class="small mb-0 d-block">Tampilkan</label><select name="filter" class="form-control form-control-sm"><option value="">Semua appointment</option><option value="menunggu">Menunggu pre order</option><option value="storing">Storing</option><option value="lewat">Lewat deadline</option></select></div>
            <div class="align-self-end"><button class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i> Terapkan</button> <button type="button" class="btn btn-sm btn-link" id="sa-reset">Reset</button></div>
        </form>
        <?php if ($akses->add_level === 'Y') { ?><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah-appointment"><i class="fas fa-plus mr-1"></i> Appointment baru</button><?php } ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table id="tabel-appointment" class="table table-hover w-100">
            <thead><tr><th>No</th><th>No. WO</th><th>Masuk</th><th>Customer</th><th>Kendaraan</th><th>Keluhan</th><th>SA</th><th>Jenis</th><th>Deadline</th><th>Proses</th><th>Aksi</th></tr></thead>
            <tbody></tbody>
        </table>
        </div>
    </div>
    <div id="tempat-modal"></div>
</div>
<?php show_my_confirm('hapusAppointment', 'hapus-appointment', 'Hapus appointment ini? Nomor WO yang sudah terbentuk ikut terhapus.', 'Ya, hapus', 'Batal'); ?>
<script>
(function () {
    var SITE = '<?php echo rtrim(site_url(), '/'); ?>/', table, hapusId = null;
    function pesan(ok, t) { if (window.Swal) Swal.fire({ position: 'center', icon: ok ? 'success' : 'error', title: t, showConfirmButton: false, timer: 1600 }); else alert(t); }
    function siapkan($m) {
        var $c = $m.find('.sa-customer'), $h = $m.find('.sa-riwayat');
        if ($.fn.select2) $c.select2({ dropdownParent: $m, width: '100%', templateResult: function (d) { if (!d.id) return d.text; var x = $(d.element).data('details') || ''; return $('<div class="d-flex justify-content-between"><b></b><span class="text-muted"></span></div>').find('b').text(d.text).end().find('span').text(x).end(); } });
        function muat() {
            var v = $c.val(); $h.html('<option value="">— memuat… —</option>');
            if (!v) { $h.html('<option value="">— pilih customer dahulu —</option>'); return; }
            $.getJSON(SITE + 'ServiceAppointment/kendaraan', { customer: v }, function (rows) {
                $h.html('<option value="">' + (rows.length ? '— ' + rows.length + ' kendaraan tercatat —' : '— belum ada riwayat —') + '</option>');
                rows.forEach(function (r, i) { $h.append($('<option>').val(i).text((r.no_pol || '-') + ' · ' + (r.type || '') + ' · VIN ' + (r.vin || '-')).data('r', r)); });
            });
        }
        $c.on('change', muat); if ($c.val()) muat();
        $h.on('change', function () { var r = $(this).find(':selected').data('r'); if (!r) return; var f = $m.find('form')[0];
            f.licence_plate.value = r.no_pol || ''; f.vehicle_type.value = r.type || ''; f.vin.value = r.vin || ''; f.engine_no.value = r.engine_no || ''; f.mileage.value = r.mileage || ''; if (r.terakhir) f.last_service_date.value = String(r.terakhir).substr(0, 10); });
    }
    function kirim($form, url, $modal) {
        $.post(url, $form.serialize()).done(function (d) {
            var o; try { o = typeof d === 'object' ? d : JSON.parse(d); } catch (e) { pesan(false, 'Respon tidak valid'); return; }
            if (o.status === 'form' || o.error) { pesan(false, o.msg || o.pesan); return; }
            $modal.modal('hide'); table.ajax.reload(null, false); pesan(true, o.msg);
            if ($form.attr('id') === 'form-tambah-appointment') { $form[0].reset(); $form.find('.sa-customer').val('').trigger('change'); }
        }).fail(function (x) { var o = x.responseJSON || {}; pesan(false, o.pesan || 'Gagal menyimpan'); });
    }
    $(function () {
        table = $('#tabel-appointment').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>><'row'<'col-12'tr>><'row'<'col-sm-6'i><'col-sm-6'p>>",
            buttons: [{ extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', title: 'Service Appointment', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], stripHtml: true } },
                      { extend: 'print', text: '<i class="fas fa-print"></i> Cetak daftar', title: 'Daftar Service Appointment', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] },
                        customize: function (w) { $(w.document.body).css('font-size', '10px').find('table').addClass('compact').css('font-size', 'inherit'); } },
                      { extend: 'colvis', text: '<i class="fas fa-columns"></i> Kolom', className: 'btn btn-sm btn-outline-secondary' }],
            processing: true, serverSide: true, order: [], autoWidth: false, lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
            language: { emptyTable: 'Belum ada service appointment', processing: '<i class="fas fa-spinner fa-spin fa-2x"></i>' },
            ajax: { url: SITE + 'ServiceAppointment/ajax_list', type: 'POST', data: function (d) { $('#sa-filter').serializeArray().forEach(function (x) { d[x.name] = x.value; }); } },
            columnDefs: [{ targets: [0, 10], orderable: false }]
        });
        siapkan($('#tambah-appointment'));
        $('#tambah-appointment').on('submit', '#form-tambah-appointment', function (e) { e.preventDefault(); kirim($(this), SITE + 'ServiceAppointment/prosesTappointment', $('#tambah-appointment')); });
        $(document).on('click', '.update-appointment', function () {
            $.post(SITE + 'ServiceAppointment/updateAppointment', { id: $(this).data('id') }, function (h) {
                $('#tempat-modal').html(h); var $m = $('#update-appointment'); siapkan($m); $m.modal('show');
            });
        });
        $(document).on('submit', '#form-update-appointment', function (e) { e.preventDefault(); kirim($(this), SITE + 'ServiceAppointment/prosesUappointment', $('#update-appointment')); });
        $(document).on('click', '.delete-appointment', function () { hapusId = $(this).data('id'); });
        $(document).on('click', '.hapus-appointment', function () {
            $.post(SITE + 'ServiceAppointment/deleteAppointment', { id: hapusId }).done(function (d) {
                var o = JSON.parse(d); $('#hapusAppointment').modal('hide'); table.ajax.reload(null, false); pesan(o.status !== 'form', o.msg);
            });
        });
        $('#sa-filter').on('submit', function (e) { e.preventDefault(); table.ajax.reload(); });
        $('#sa-reset').on('click', function () { $('#sa-filter')[0].reset(); table.ajax.reload(); });
        $('.sa-kpi [data-f]').on('click', function (e) {
            e.preventDefault(); var f = $(this).data('f'), F = $('#sa-filter')[0], t = new Date().toISOString().slice(0, 10);
            F.reset();
            if (f === 'hari') { F.dari.value = t; F.sampai.value = t; }
            else if (f === 'bulan') { F.dari.value = t.slice(0, 8) + '01'; F.sampai.value = t; }
            else F.filter.value = f;
            table.ajax.reload();
        });
    });
})();
</script>
