<?php defined('BASEPATH') OR exit('No direct script access allowed');
$fmt = function ($v, $f) use ($mode) {
    if ($v === null) return '-';
    if ($mode === 'excel') return $f === '%' ? round($v * 100, 2) : round($v, 2);
    switch ($f) {
        case '%': return nf($v * 100, 1) . '%';
        case 'x': return nf($v, 2) . '×';
        case 'hari': return nf($v, 0) . ' hari';
        default: return nf_acc($v);
    }
};
$badge = array('ok' => array('Baik', '#16a34a'), 'warn' => array('Perhatian', '#d97706'), 'bad' => array('Kurang', '#dc2626'), '' => array('Info', '#64748b'));
$A = $d['posisi'];
?>
<?php if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Pendapatan usaha</div><div class="acc-kpi__value"><?php echo nf_acc($d['pend']); ?></div><div class="acc-kpi__sub"><?php echo $d['hari']; ?> hari</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Laba bersih</div><div class="acc-kpi__value <?php echo $d['laba_bersih'] < 0 ? 'text-danger' : ''; ?>"><?php echo nf_acc($d['laba_bersih']); ?></div><div class="acc-kpi__sub">laba kotor <?php echo nf_acc($d['laba_kotor']); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Total aset</div><div class="acc-kpi__value"><?php echo nf_acc($A['_ASET']); ?></div><div class="acc-kpi__sub">aset lancar <?php echo nf_acc($A['_AL']); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Liabilitas / Ekuitas</div><div class="acc-kpi__value"><?php echo nf_acc($A['_LIAB']); ?></div><div class="acc-kpi__sub">ekuitas <?php echo nf_acc($A['_EKUITAS']); ?></div></div>
</div>
<?php } ?>
<table class="table table-sm">
    <thead><tr><th style="width:22%">Rasio</th><th style="width:22%">Rumus</th><th class="num" style="width:10%">Nilai</th><th style="width:8%">Penilaian</th><th>Tafsiran</th></tr></thead>
    <tbody>
    <?php foreach ($d['grup'] as $g => $rows) { ?>
        <tr class="row-group"><td colspan="5"><?php echo html_escape($g); ?></td></tr>
        <?php foreach ($rows as $r) { $b = $badge[$r->baik]; ?>
        <tr class="row-sub"><td><strong><?php echo html_escape($r->nama); ?></strong><?php if ($mode !== 'excel') { ?><div class="small text-muted"><?php echo html_escape($r->arti); ?></div><?php } ?></td>
            <td class="small"><?php echo html_escape($r->rumus); ?></td>
            <td class="num" style="font-weight:700"><?php echo $fmt($r->nilai, $r->fmt); ?></td>
            <td><?php echo $mode === 'excel' ? $b[0] : '<span class="acc-status" style="background:' . $b[1] . '1a;color:' . $b[1] . '">' . $b[0] . '</span>'; ?></td>
            <td class="small"><?php echo html_escape($r->tafsir); ?></td></tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">Batas "Baik/Perhatian/Kurang" adalah patokan umum; bandingkan juga dengan periode sebelumnya dan rata-rata industri karoseri/otomotif. Rasio berbasis laba disetahunkan bila periode kurang dari setahun. Saldo rata-rata = (saldo awal periode + saldo akhir) ÷ 2.</p><?php } ?>
