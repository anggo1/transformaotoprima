<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Kotak panduan singkat per halaman (isi di application/config/panduan.php -> 'halaman'). */
$seg1 = strtolower((string) $this->uri->segment(1));
$seg2 = strtolower((string) $this->uri->segment(2));
if ($seg1 === '' || ($seg2 !== '' && $seg2 !== 'index')) return;
$this->config->load('panduan', true, true);
$H = (array) $this->config->item('halaman', 'panduan');
if ( ! isset($H[$seg1])) return;
$p = $H[$seg1];
$id = 'pb-' . preg_replace('/[^a-z0-9]/', '', $seg1);
?>
<div class="card acc-explain acc-explain--page no-print" id="<?php echo $id; ?>">
    <div class="acc-explain__head" data-toggle="collapse" data-target="#<?php echo $id; ?>-body" aria-expanded="false">
        <i class="fas fa-question-circle"></i>
        <span><strong><?php echo html_escape(t('panduan_halaman')); ?></strong> — <?php echo $p['ringkas']; ?></span>
        <i class="fas fa-chevron-down acc-explain__chev"></i>
    </div>
    <div id="<?php echo $id; ?>-body" class="collapse">
        <div class="acc-explain__body">
            <div>
                <h6><?php echo html_escape(t('langkah_kerja')); ?></h6>
                <ol><?php foreach ($p['langkah'] as $x) { ?><li><?php echo $x; ?></li><?php } ?></ol>
            </div>
            <div>
                <?php if ($p['catatan'] !== '') { ?><h6><?php echo html_escape(t('catatan')); ?></h6><p><?php echo $p['catatan']; ?></p><?php } ?>
                <a href="<?php echo site_url(isset($p['panduan']) ? $p['panduan'] : 'PanduanAkuntansi'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-book mr-1"></i> <?php echo html_escape(t('buka_panduan')); ?></a>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    var k = 'top.pb.<?php echo $id; ?>', b = document.getElementById('<?php echo $id; ?>-body');
    try { if (localStorage.getItem(k) === '1') b.classList.add('show'); } catch (e) {}
    if (window.jQuery) {
        jQuery(b).on('shown.bs.collapse', function () { try { localStorage.setItem(k, '1'); } catch (e) {} })
                 .on('hidden.bs.collapse', function () { try { localStorage.setItem(k, '0'); } catch (e) {} });
    }
})();
</script>
