<?php defined('BASEPATH') OR exit('No direct script access allowed');
$grup = array();
foreach ($laporan as $k => $l) { $grup[$l[3]][$k] = $l; }
?>
<div class="card">
    <div class="card-body">
        <p class="text-muted mb-0">Pilih laporan. Semua laporan dapat dicetak dan diekspor ke Excel, dihitung langsung dari jurnal (Jurnal Umum, Kas &amp; Bank, Piutang, Hutang, Aset Tetap) dan Saldo Awal.</p>
        <?php foreach ($grup as $g => $items) { ?>
        <div class="acc-hub-title"><?php echo html_escape($g); ?></div>
        <div class="acc-hub">
            <?php foreach ($items as $k => $l) { ?>
            <a href="<?php echo site_url('LaporanKeuangan/' . $k); ?>">
                <i class="fas <?php echo $l[1]; ?>"></i>
                <div><strong><?php echo html_escape($l[0]); ?></strong><span><?php echo html_escape($l[2]); ?></span></div>
            </a>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
