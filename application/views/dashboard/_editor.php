<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Editor susunan widget. Variabel: $widget [[kode,lebar]], $aksi, $preset, $target_label, $boleh_ubah (null = sembunyikan), $hidden (array), $hanya_tersedia */
$grup = array();
foreach (Dashwidget::$W as $k => $w) {
    if ( ! empty($hanya_tersedia) && ! $this->dashwidget->tersedia($k)) continue;
    $grup[$w[1]][$k] = $w;
}
$meta = array(); foreach (Dashwidget::$W as $k => $w) $meta[$k] = array('j' => $w[0], 'g' => $w[1], 'i' => $w[2], 'w' => $w[3], 'd' => $w[5], 'a' => $w[4]);
$presetJs = array(); foreach ($preset as $k => $p) $presetJs[$k] = $p['widget'];
?>
<form method="post" action="<?php echo $aksi; ?>" id="de-form" class="row">
    <?php foreach ((array) ($hidden ?? array()) as $k => $v) { ?><input type="hidden" name="<?php echo $k; ?>" value="<?php echo html_escape($v); ?>"><?php } ?>
    <input type="hidden" name="layout" id="de-layout">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Widget tersedia</h3></div>
            <div class="card-body p-2" style="max-height:70vh;overflow:auto">
                <?php foreach ($grup as $g => $ws) { ?>
                <div class="de-grup"><?php echo html_escape($g); ?></div>
                <?php foreach ($ws as $k => $w) { ?>
                <div class="de-item" data-k="<?php echo $k; ?>">
                    <i class="fas <?php echo $w[2]; ?>"></i>
                    <div><b><?php echo html_escape($w[0]); ?></b><small><?php echo html_escape($w[5]); ?><?php echo $w[4] ? ' · perlu akses <code>' . $w[4] . '</code>' : ''; ?></small></div>
                    <button type="button" class="btn btn-xs btn-outline-primary de-tambah" title="Tambahkan"><i class="fas fa-plus"></i></button>
                </div>
                <?php } } ?>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between" style="gap:.5rem">
                <h3 class="card-title">Susunan: <?php echo html_escape($target_label); ?></h3>
                <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-magic"></i> Muat preset</button>
                    <div class="dropdown-menu dropdown-menu-right"><?php foreach ($preset as $k => $p) { ?><a class="dropdown-item de-preset" href="#" data-p="<?php echo $k; ?>"><i class="fas <?php echo $p['ikon']; ?> mr-2 text-muted"></i><?php echo html_escape($p['nama']); ?></a><?php } ?></div>
                </div>
            </div>
            <div class="card-body p-2">
                <div id="de-list" class="de-list"></div>
                <div class="de-kosong text-center text-muted py-4" id="de-kosong" hidden>Belum ada widget. Tambahkan dari daftar kiri atau muat preset.</div>
                <div class="de-pratinjau mt-2" id="de-pratinjau"></div>
            </div>
            <div class="card-footer d-flex flex-wrap align-items-center justify-content-between" style="gap:.5rem">
                <div>
                    <?php if ($boleh_ubah !== null) { ?>
                    <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="de-boleh" name="boleh_ubah" value="Y" <?php echo $boleh_ubah === 'Y' ? 'checked' : ''; ?>><label class="custom-control-label" for="de-boleh">Izinkan user mengatur dashboard pribadinya</label></div>
                    <?php } ?>
                </div>
                <div class="d-flex" style="gap:.4rem">
                    <button class="btn btn-outline-danger btn-sm" name="reset" value="1" onclick="return confirm('Hapus pengaturan ini dan kembali ke bawaan?')"><i class="fas fa-undo"></i> Kembalikan bawaan</button>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
        <p class="small text-muted">Lebar: 3 = ¼, 4 = ⅓, 6 = ½, 8 = ⅔, 12 = penuh (layar lebar). Di layar kecil widget otomatis menyesuaikan. Widget yang menu sumbernya tidak boleh diakses user otomatis disembunyikan.</p>
    </div>
</form>
<script>
(function () {
    var META = <?php echo json_encode($meta); ?>, PRESET = <?php echo json_encode($presetJs); ?>, data = <?php echo json_encode(array_values(array_filter($widget, function ($w) { return isset(Dashwidget::$W[$w[0]]); }))); ?>;
    var list = document.getElementById('de-list'), out = document.getElementById('de-layout');
    function esc(s) { return String(s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
    function gambar() {
        list.innerHTML = data.map(function (w, i) {
            var m = META[w[0]];
            return '<div class="de-row"><span class="de-no">' + (i + 1) + '</span><i class="fas ' + m.i + '"></i><div class="de-row__t"><b>' + esc(m.j) + '</b><small>' + esc(m.g) + '</small></div>' +
                '<select class="form-control form-control-sm" data-i="' + i + '">' + [3, 4, 6, 8, 12].map(function (x) { return '<option value="' + x + '"' + (x == w[1] ? ' selected' : '') + '>' + x + '/12</option>'; }).join('') + '</select>' +
                '<div class="btn-group btn-group-sm"><button type="button" class="btn btn-outline-secondary" data-a="naik" data-i="' + i + '"><i class="fas fa-arrow-up"></i></button><button type="button" class="btn btn-outline-secondary" data-a="turun" data-i="' + i + '"><i class="fas fa-arrow-down"></i></button><button type="button" class="btn btn-outline-danger" data-a="hapus" data-i="' + i + '"><i class="fas fa-times"></i></button></div></div>';
        }).join('');
        document.getElementById('de-kosong').hidden = data.length > 0;
        document.getElementById('de-pratinjau').innerHTML = '<div class="small text-muted mb-1">Pratinjau tata letak</div><div class="de-mini">' + data.map(function (w) { return '<span style="grid-column:span ' + w[1] + '"><i class="fas ' + META[w[0]].i + '"></i> ' + esc(META[w[0]].j) + '</span>'; }).join('') + '</div>';
        out.value = JSON.stringify(data);
        document.querySelectorAll('.de-item').forEach(function (el) { el.classList.toggle('is-on', data.some(function (w) { return w[0] === el.getAttribute('data-k'); })); });
    }
    list.addEventListener('click', function (e) {
        var b = e.target.closest('button[data-a]'); if (!b) return; var i = +b.getAttribute('data-i'), a = b.getAttribute('data-a');
        if (a === 'hapus') data.splice(i, 1);
        if (a === 'naik' && i > 0) data.splice(i - 1, 0, data.splice(i, 1)[0]);
        if (a === 'turun' && i < data.length - 1) data.splice(i + 1, 0, data.splice(i, 1)[0]);
        gambar();
    });
    list.addEventListener('change', function (e) { var s = e.target.closest('select[data-i]'); if (s) { data[+s.getAttribute('data-i')][1] = +s.value; gambar(); } });
    document.querySelectorAll('.de-tambah').forEach(function (b) { b.addEventListener('click', function () { var k = b.closest('.de-item').getAttribute('data-k'); data.push([k, META[k].w]); gambar(); list.lastElementChild && list.lastElementChild.scrollIntoView({ block: 'nearest' }); }); });
    document.querySelectorAll('.de-preset').forEach(function (a) { a.addEventListener('click', function (e) { e.preventDefault(); if (data.length && !confirm('Ganti susunan dengan preset ini?')) return; data = JSON.parse(JSON.stringify(PRESET[a.getAttribute('data-p')])).filter(function (w) { return META[w[0]]; }); gambar(); }); });
    gambar();
})();
</script>
