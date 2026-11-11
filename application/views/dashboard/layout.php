<?php defined('BASEPATH') OR exit('No direct script access allowed');
$nama = (string) $this->session->userdata('full_name');
$jam = (int) date('G');
$salam = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 19 ? 'Selamat sore' : 'Selamat malam'));
?>
<div class="dw-hero">
    <div>
        <h2><?php echo $salam; ?>, <?php echo html_escape($nama); ?></h2>
        <p><?php echo tgl_id(date('Y-m-d')); ?> &middot; <?php echo html_escape((string) $this->session->userdata('lokasi')); ?> &middot; <span class="dw-sumber"><i class="fas fa-th-large"></i> <?php echo html_escape($sumber); ?></span></p>
    </div>
    <div class="dw-hero__aksi">
        <button type="button" class="btn btn-sm btn-light" id="dw-muat"><i class="fas fa-sync-alt"></i> Muat ulang</button>
        <?php if ($boleh_atur) { ?><a class="btn btn-sm btn-light" href="<?php echo site_url('Dashboard/atur'); ?>"><i class="fas fa-sliders-h"></i> Atur dashboard saya</a><?php } ?>
        <?php if ($admin) { ?><a class="btn btn-sm btn-light" href="<?php echo site_url('PengaturanDashboard'); ?>"><i class="fas fa-users-cog"></i> Pengaturan per level / departemen</a><?php } ?>
        <a class="btn btn-sm btn-light" href="<?php echo site_url('PanduanSistem'); ?>"><i class="fas fa-project-diagram"></i> Panduan sistem</a>
        <a class="btn btn-sm btn-light" href="<?php echo site_url('Dashboard/klasik'); ?>" title="Tampilan dashboard sebelumnya">Klasik</a>
    </div>
</div>

<div class="row dw-grid">
<?php foreach ($widget as $w) { $i = Dashwidget::$W[$w[0]]; ?>
    <div class="col-12 col-lg-<?php echo $w[1] >= 8 ? 12 : 6; ?> col-xl-<?php echo (int) $w[1]; ?> dw-col" data-w="<?php echo $w[0]; ?>">
        <div class="card dw-card dw-card--<?php echo $w[0]; ?>">
            <div class="dw-skel"><div class="dw-skel__judul"><i class="fas <?php echo $i[2]; ?>"></i> <?php echo html_escape($i[0]); ?></div><div class="dw-skel__bar"></div><div class="dw-skel__bar" style="width:70%"></div></div>
        </div>
    </div>
<?php } ?>
<?php if ( ! $widget) { ?>
    <div class="col-12"><div class="card"><div class="card-body text-center text-muted py-5"><i class="fas fa-th-large fa-2x mb-2 d-block"></i>Belum ada widget yang dapat ditampilkan untuk hak akses Anda.<?php if ($boleh_atur) { ?><br><a href="<?php echo site_url('Dashboard/atur'); ?>">Atur dashboard</a><?php } ?></div></div></div>
<?php } ?>
</div>
<script>
(function () {
    var antre = Array.prototype.slice.call(document.querySelectorAll('.dw-col[data-w]'));
    function muat(col) {
        var k = col.getAttribute('data-w'), card = col.querySelector('.dw-card');
        return fetch('<?php echo site_url('Dashboard/widget'); ?>/' + k, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (h) {
                if (!h.trim()) { col.remove(); return; }
                var t = document.createElement('div'); t.innerHTML = h; var baru = t.firstElementChild;
                if (!baru) { col.remove(); return; }
                card.replaceWith(baru);
                if (window.TOPChart) TOPChart.render(baru);
            }).catch(function () { card.innerHTML = '<div class="card-body small text-muted">Gagal memuat widget.</div>'; });
    }
    // muat 3 sekaligus agar halaman tetap responsif
    function jalan(list) { var i = 0; function next() { if (i >= list.length) return Promise.resolve(); return muat(list[i++]).then(next); } return Promise.all([next(), next(), next()]); }
    jalan(antre);
    document.getElementById('dw-muat').addEventListener('click', function () { location.reload(); });
})();
</script>
