<?php defined('BASEPATH') OR exit('No direct script access allowed');
$kol = $d['kolom']; $n = count($kol); $S = $d['seksi']; $R = $d['ringkas'];
$banding = $d['mode'] === 'banding';
// analisis vertikal: % terhadap total pendapatan usaha (hanya mode standar)
$persen = ! empty($f['persen']) && $d['mode'] === 'standar';
$basis = $S['PENDAPATAN']->total;
$cell = function ($vals) use ($mode, $n, $banding, $persen, $basis) {
    $h = '';
    foreach ($vals as $v) $h .= '<td class="num">' . acc_val($v, $mode) . '</td>';
    if ($banding) {
        $sel = $vals[0] - $vals[1];
        $h .= '<td class="num">' . acc_val($sel, $mode) . '</td>';
        $h .= '<td class="num">' . ($vals[1] != 0 ? ($mode === 'excel' ? round($sel / abs($vals[1]) * 100, 2) : nf($sel / abs($vals[1]) * 100, 1) . '%') : '-') . '</td>';
    }
    if ($persen) {
        $h .= '<td class="num text-muted">' . ($basis[0] != 0 ? ($mode === 'excel' ? round($vals[0] / $basis[0] * 100, 2) : nf($vals[0] / $basis[0] * 100, 1) . '%') : '-') . '</td>';
    }
    return $h;
};
$seksiRow = function ($key, $wajib = true) use ($S, $cell, $mode, $f) {
    $s = $S[$key];
    if ( ! $wajib && ! $s->rows) return;
    echo '<tr class="row-group"><td colspan="99">' . html_escape($s->label) . '</td></tr>';
    foreach ($s->rows as $r) {
        $nama = html_escape($r->nama);
        if ($mode === 'screen') $nama = '<a href="' . site_url('LaporanKeuangan/buku_besar?akun=' . rawurlencode($r->kode) . '&dari=' . $f['dari'] . '&sampai=' . $f['sampai']) . '">' . $nama . '</a>';
        echo '<tr class="row-sub"><td>' . html_escape($r->kode) . '</td><td>' . $nama . '</td>' . $cell($r->nilai) . '</tr>';
    }
    if ( ! $s->rows) echo '<tr class="row-sub"><td></td><td class="text-muted">—</td>' . $cell($s->total) . '</tr>';
    echo '<tr class="row-total"><td></td><td>Total ' . html_escape($s->label) . '</td>' . $cell($s->total) . '</tr>';
};
$adaPajak = (bool) $S['PAJAK_PENGHASILAN']->rows;
?>
<table class="table table-sm">
    <thead>
        <tr>
            <th style="width:110px">Kode</th><th>Keterangan</th>
            <?php foreach ($kol as $c) { ?><th class="num"><?php echo html_escape($c['label']); ?></th><?php } ?>
            <?php if ($banding) { ?><th class="num">Selisih</th><th class="num">%</th><?php } ?>
            <?php if ($persen) { ?><th class="num">% Pendapatan</th><?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php $seksiRow('PENDAPATAN'); $seksiRow('HPP'); ?>
        <tr class="row-grand"><td></td><td>LABA KOTOR</td><?php echo $cell($R['laba_kotor']); ?></tr>
        <?php $seksiRow('BEBAN'); ?>
        <tr class="row-grand"><td></td><td>LABA OPERASIONAL</td><?php echo $cell($R['laba_operasi']); ?></tr>
        <?php $seksiRow('PENDAPATAN_LAIN'); $seksiRow('BEBAN_LAIN'); ?>
        <?php if ($adaPajak) { ?>
        <tr class="row-grand"><td></td><td>LABA (RUGI) SEBELUM PAJAK</td><?php echo $cell($R['laba_sebelum_pajak']); ?></tr>
        <?php $seksiRow('PAJAK_PENGHASILAN', false); ?>
        <?php } ?>
        <tr class="row-grand"><td></td><td>LABA (RUGI) BERSIH<?php echo $adaPajak ? ' SETELAH PAJAK' : ''; ?></td><?php echo $cell($R['laba_bersih']); ?></tr>
    </tbody>
</table>
