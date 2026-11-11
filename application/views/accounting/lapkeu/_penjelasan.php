<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Kotak "Tentang laporan ini": tujuan, cara membaca, sumber data, tips. Dapat dilipat; status lipat diingat per browser. */
$id = 'pj-' . preg_replace('/[^a-z0-9_]/', '', $kunci);
?>
<div class="card acc-explain no-print" id="<?php echo $id; ?>">
    <div class="acc-explain__head" data-toggle="collapse" data-target="#<?php echo $id; ?>-body" aria-expanded="false">
        <i class="fas fa-lightbulb"></i>
        <span><strong><?php echo html_escape(t('tentang_laporan')); ?></strong> — <?php echo html_escape($p['tujuan']); ?></span>
        <i class="fas fa-chevron-down acc-explain__chev"></i>
    </div>
    <div id="<?php echo $id; ?>-body" class="collapse">
        <div class="acc-explain__body">
            <?php if ( ! empty($p['baca'])) { ?>
            <div>
                <h6><?php echo html_escape(t('cara_membaca')); ?></h6>
                <ul><?php foreach ($p['baca'] as $x) { ?><li><?php echo $x; ?></li><?php } ?></ul>
            </div>
            <?php } ?>
            <div>
                <?php if ( ! empty($p['sumber'])) { ?><h6><?php echo html_escape(t('sumber_data')); ?></h6><p><?php echo $p['sumber']; ?></p><?php } ?>
                <?php if ( ! empty($p['tips'])) { ?><h6><?php echo html_escape(t('tips')); ?></h6><ul><?php foreach ((array) $p['tips'] as $x) { ?><li><?php echo $x; ?></li><?php } ?></ul><?php } ?>
                <?php if ( ! empty($p['dasar'])) { ?><h6><?php echo html_escape(t('dasar')); ?></h6><p class="mb-0"><?php echo $p['dasar']; ?></p><?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    var k = 'top.pj.<?php echo $id; ?>', b = $('#<?php echo $id; ?>-body');
    try { if (localStorage.getItem(k) === '1') b.addClass('show'); } catch (e) {}
    b.on('shown.bs.collapse', function () { try { localStorage.setItem(k, '1'); } catch (e) {} });
    b.on('hidden.bs.collapse', function () { try { localStorage.setItem(k, '0'); } catch (e) {} });
})();
</script>
