<?php defined('BASEPATH') OR exit('No direct script access allowed');
$en = $pref['bahasa'] === 'en';
$L = function ($id, $en_) use ($en) { return $en ? $en_ : $id; };
$radio = function ($nama, $nilai, $label, $isi = '') use ($pref) {
    $id = 'tp-' . $nama . '-' . $nilai;
    return '<input type="radio" name="' . $nama . '" id="' . $id . '" value="' . $nilai . '"' . ((string) $pref[$nama] === (string) $nilai ? ' checked' : '') . '>'
        . '<label for="' . $id . '">' . $isi . '<span>' . $label . '</span></label>';
};
$mini = function ($sb, $bg, $fg) {
    return '<span class="tp-mini" style="background:' . $bg . ';color:' . $fg . '"><span class="tp-mini__sb" style="background:' . $sb . '"></span><span class="tp-mini__main"><i></i><i></i><i></i><i></i></span></span>';
};
?>
<?php if ( ! $tabel_ada) { ?>
<div class="alert alert-warning"><i class="fas fa-info-circle mr-1"></i> <?php echo $L('Tabel preferensi belum ada; akan dibuat otomatis saat Anda menyimpan. Bila gagal, jalankan file migrasi database.', 'Preference table not found; it will be created on first save. If that fails, run the database migration file.'); ?></div>
<?php } ?>
<form id="form-tampilan" class="tp-grid" autocomplete="off">
    <div class="card"><div class="card-body">

        <div class="tp-sec">
            <h6><i class="fas fa-globe-asia mr-1"></i> <?php echo $L('Bahasa', 'Language'); ?></h6>
            <p class="tp-desc"><?php echo $L('Mengubah bahasa menu, navigasi, panduan & label umum. Data transaksi tidak diterjemahkan.', 'Changes the language of menus, navigation, guides & common labels. Transaction data is not translated.'); ?></p>
            <div class="tp-opsi">
                <?php echo $radio('bahasa', 'id', 'Bahasa Indonesia', '<strong style="font-size:18px">ID</strong>'); ?>
                <?php echo $radio('bahasa', 'en', 'English', '<strong style="font-size:18px">EN</strong>'); ?>
            </div>
        </div>

        <div class="tp-sec">
            <h6><i class="fas fa-adjust mr-1"></i> <?php echo $L('Tema', 'Theme'); ?></h6>
            <div class="tp-opsi">
                <?php echo $radio('tema', 'terang', $L('Terang', 'Light'), $mini('#0f1b2d', '#f3f5f9', '#1d2939')); ?>
                <?php echo $radio('tema', 'gelap', $L('Gelap', 'Dark'), $mini('#0a111e', '#0b1220', '#e4e9f1')); ?>
                <?php echo $radio('tema', 'otomatis', $L('Ikuti perangkat', 'Match device'), '<span class="tp-mini" style="background:linear-gradient(135deg,#f3f5f9 50%,#0b1220 50%)"></span>'); ?>
            </div>
        </div>

        <div class="tp-sec">
            <h6><i class="fas fa-palette mr-1"></i> <?php echo $L('Warna aksen', 'Accent color'); ?></h6>
            <p class="tp-desc"><?php echo $L('Dipakai untuk tombol utama, tautan, menu aktif & sorotan.', 'Used for primary buttons, links, active menu & highlights.'); ?></p>
            <div class="tp-opsi tp-warna">
                <?php foreach ($aksen as $k => $a) { echo $radio('aksen', $k, html_escape($a[0]), '<span class="tp-sw" style="background:' . $a[1] . '" data-c="' . $a[1] . '|' . $a[2] . '|' . $a[3] . '|' . $a[4] . '"></span>'); } ?>
            </div>
        </div>

        <div class="tp-sec">
            <h6><i class="fas fa-columns mr-1"></i> <?php echo $L('Gaya sidebar', 'Sidebar style'); ?></h6>
            <div class="tp-opsi">
                <?php echo $radio('sidebar', 'gelap', $L('Gelap elegan', 'Elegant dark'), $mini('#0f1b2d', '#f3f5f9', '#1d2939')); ?>
                <?php echo $radio('sidebar', 'terang', $L('Terang bersih', 'Clean light'), $mini('#ffffff;box-shadow:inset -1px 0 0 #e3e8ef', '#f3f5f9', '#1d2939')); ?>
                <?php echo $radio('sidebar', 'aksen', $L('Warna aksen', 'Accent color'), $mini('var(--top-accent)', '#f3f5f9', '#1d2939')); ?>
            </div>
            <div class="mt-3">
                <div class="custom-control custom-switch mb-2">
                    <input type="checkbox" class="custom-control-input" id="tp-kelompok" name="kelompok_menu" value="Y" <?php echo $pref['kelompok_menu'] === 'Y' ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="tp-kelompok"><?php echo $L('Tampilkan judul kelompok menu (Utama, Operasional, Gudang, Keuangan, Sistem)', 'Show menu group titles (Main, Operations, Warehouse, Finance, System)'); ?></label>
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="tp-mini" name="sidebar_mini" value="Y" <?php echo $pref['sidebar_mini'] === 'Y' ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="tp-mini"><?php echo $L('Sidebar ringkas (hanya ikon) saat membuka aplikasi', 'Compact sidebar (icons only) when opening the app'); ?></label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 tp-sec">
                <h6><i class="fas fa-th-list mr-1"></i> <?php echo $L('Kepadatan', 'Density'); ?></h6>
                <div class="tp-opsi">
                    <?php echo $radio('kepadatan', 'nyaman', $L('Nyaman', 'Comfortable')); ?>
                    <?php echo $radio('kepadatan', 'ringkas', $L('Ringkas', 'Compact')); ?>
                </div>
            </div>
            <div class="col-md-4 tp-sec">
                <h6><i class="fas fa-text-height mr-1"></i> <?php echo $L('Ukuran huruf', 'Font size'); ?></h6>
                <div class="tp-opsi">
                    <?php echo $radio('huruf', 'kecil', 'A<small>-</small>'); ?>
                    <?php echo $radio('huruf', 'sedang', 'A'); ?>
                    <?php echo $radio('huruf', 'besar', 'A<small>+</small>'); ?>
                </div>
            </div>
            <div class="col-md-4 tp-sec">
                <h6><i class="fas fa-font mr-1"></i> Font</h6>
                <div class="tp-opsi">
                    <?php echo $radio('font', 'jakarta', 'Jakarta Sans'); ?>
                    <?php echo $radio('font', 'inter', 'Inter'); ?>
                    <?php echo $radio('font', 'sistem', $L('Sistem', 'System')); ?>
                </div>
            </div>
        </div>

        <div class="tp-sec mb-0">
            <h6><i class="fas fa-star mr-1"></i> <?php echo $L('Menu favorit', 'Favorite menus'); ?></h6>
            <p class="tp-desc"><?php echo $L('Arahkan kursor ke submenu di sidebar lalu klik ☆ untuk menyematkannya. Urutkan dengan tombol panah.', 'Hover a submenu in the sidebar and click ☆ to pin it. Reorder with the arrow buttons.'); ?></p>
            <ul class="tp-fav-list" id="tp-fav">
                <?php foreach ($pref['favorit'] as $l) { if ( ! isset($fav[$l])) continue; ?>
                <li data-link="<?php echo html_escape($l); ?>"><i class="<?php echo html_escape($fav[$l][1] ?: 'far fa-circle'); ?> text-muted"></i><span><?php echo html_escape($fav[$l][0]); ?></span>
                    <button type="button" class="btn btn-xs btn-light tp-up" title="↑"><i class="fas fa-arrow-up"></i></button>
                    <button type="button" class="btn btn-xs btn-light tp-down" title="↓"><i class="fas fa-arrow-down"></i></button>
                    <button type="button" class="btn btn-xs btn-light text-danger tp-del" title="<?php echo html_escape(t('lepas')); ?>"><i class="fas fa-times"></i></button></li>
                <?php } if ( ! $fav) { ?><li class="text-muted"><span><?php echo html_escape(t('favorit_kosong')); ?></span></li><?php } ?>
            </ul>
        </div>
    </div></div>

    <div class="tp-preview">
        <div class="card"><div class="card-body">
            <h6 class="mb-0" style="font-weight:700"><?php echo $L('Pratinjau langsung', 'Live preview'); ?></h6>
            <p class="small text-muted mb-1"><?php echo $L('Perubahan langsung terlihat di halaman ini. Klik Simpan agar berlaku di semua halaman & perangkat.', 'Changes apply instantly on this page. Click Save to keep them on every page & device.'); ?></p>
            <div class="d-flex flex-wrap" style="gap:.4rem"><button type="button" class="btn btn-primary btn-sm"><?php echo $L('Tombol utama', 'Primary'); ?></button><button type="button" class="btn btn-outline-primary btn-sm"><?php echo $L('Sekunder', 'Secondary'); ?></button><span class="badge badge-success align-self-center">LUNAS</span></div>
            <input class="form-control form-control-sm" placeholder="<?php echo $L('Contoh isian', 'Sample input'); ?>">
            <table class="table table-sm table-hover mb-0"><thead><tr><th>Item</th><th class="text-right"><?php echo $L('Jumlah', 'Amount'); ?></th></tr></thead>
                <tbody><tr><td>Spare part A</td><td class="text-right">1,250,000</td></tr><tr><td>Spare part B</td><td class="text-right">780,000</td></tr></tbody></table>
            <hr class="my-1">
            <?php if ($admin) { ?>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="tp-default" name="jadikan_default" value="1">
                <label class="custom-control-label" for="tp-default"><?php echo $L('Jadikan default untuk semua pengguna', 'Make this the default for all users'); ?></label>
            </div>
            <?php } ?>
            <div class="tp-aksi">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> <?php echo html_escape(t('simpan')); ?></button>
                <button type="button" class="btn btn-outline-secondary" id="tp-reset"><i class="fas fa-undo mr-1"></i> <?php echo $L('Kembalikan ke bawaan', 'Reset to default'); ?></button>
            </div>
        </div></div>
    </div>
</form>

<script>
$(function () {
    var html = document.documentElement, $f = $('#form-tampilan');
    var gelapOS = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)').matches : false;
    var terapkan = function () {
        ['tema', 'sidebar', 'kepadatan', 'huruf', 'font', 'aksen'].forEach(function (k) {
            var v = $f.find('[name=' + k + ']:checked').val(); if (v) html.setAttribute('data-' + k, v);
        });
        var t = html.getAttribute('data-tema');
        $(html).toggleClass('top-dark', t === 'gelap' || (t === 'otomatis' && gelapOS));
        var c = $f.find('[name=aksen]:checked + label .tp-sw').data('c');
        if (c) { c = String(c).split('|'); var st = html.style; st.setProperty('--top-accent', c[0]); st.setProperty('--top-accent-600', c[1]); st.setProperty('--top-accent-50', c[2]); st.setProperty('--top-accent-rgb', c[3]); }
        var $sb = $('.main-sidebar'); var sb = html.getAttribute('data-sidebar');
        $sb.toggleClass('sidebar-light-primary', sb === 'terang').toggleClass('sidebar-dark-primary', sb !== 'terang');
        $('.top-sb-section').not('.top-sb-fav-head').toggle($('#tp-kelompok').is(':checked'));
    };
    $f.on('change', 'input', terapkan);
    var kirim = function (url, data) {
        return $.post(url, data).done(function (r) {
            if (typeof r === 'string') { try { r = JSON.parse(r); } catch (e) {} }
            if (r && r.status) { toastr.success(r.pesan); setTimeout(function () { location.reload(); }, 600); }
            else toastr.error((r && r.pesan) || 'Gagal menyimpan.');
        });
    };
    $f.on('submit', function (e) {
        e.preventDefault();
        var d = $f.serializeArray();
        if (!$('#tp-kelompok').is(':checked')) d.push({ name: 'kelompok_menu', value: 'N' });
        if (!$('#tp-mini').is(':checked')) d.push({ name: 'sidebar_mini', value: 'N' });
        $('#tp-fav li[data-link]').each(function () { d.push({ name: 'favorit_urut[]', value: $(this).data('link') }); });
        if (!$('#tp-fav li[data-link]').length) d.push({ name: 'favorit_urut[]', value: '' });
        if ($('#tp-mini').is(':checked') !== (<?php echo json_encode($pref['sidebar_mini'] === 'Y'); ?>)) { try { localStorage.removeItem('top.sidebar'); } catch (er) {} }
        kirim(TOP.siteUrl + 'Tampilan/simpan', $.param(d));
    });
    $('#tp-reset').on('click', function () { try { localStorage.removeItem('top.sidebar'); } catch (er) {} kirim(TOP.siteUrl + 'Tampilan/reset', {}); });
    $('#tp-fav').on('click', '.tp-up', function () { var li = $(this).closest('li'); li.prev().before(li); })
        .on('click', '.tp-down', function () { var li = $(this).closest('li'); li.next().after(li); })
        .on('click', '.tp-del', function () { $(this).closest('li').remove(); });
});
</script>
