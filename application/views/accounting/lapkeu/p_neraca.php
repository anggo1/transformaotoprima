<?php defined('BASEPATH') OR exit('No direct script access allowed');
$n = count($d['tanggal']); $G = $d['grup'];
$cell = function ($vals) use ($mode) { $h = ''; foreach ($vals as $v) $h .= '<td class="num">' . acc_val($v, $mode) . '</td>'; return $h; };
$tglAkhir = $d['tanggal'][0];
$blok = function ($g) use ($G, $cell, $mode, $tglAkhir) {
    $x = $G[$g];
    echo '<tr class="row-group"><td colspan="99">' . $x->label . '</td></tr>';
    foreach ($x->sub as $s) {
        echo '<tr class="row-sub"><td colspan="99"><strong>' . html_escape($s->label) . '</strong></td></tr>';
        foreach ($s->rows as $r) {
            $nama = html_escape($r->nama);
            if ($mode === 'screen' && $r->kode !== '') $nama = '<a href="' . site_url('LaporanKeuangan/buku_besar?akun=' . rawurlencode($r->kode) . '&dari=' . substr($tglAkhir, 0, 4) . '-01-01&sampai=' . $tglAkhir) . '">' . $nama . '</a>';
            echo '<tr class="row-sub2"><td>' . ($r->kode !== '' ? html_escape($r->kode) . ' &nbsp; ' : '') . $nama . '</td>' . $cell($r->nilai) . '</tr>';
        }
        echo '<tr class="row-sub"><td><em>Jumlah ' . html_escape($s->label) . '</em></td>' . $cell($s->total) . '</tr>';
    }
    echo '<tr class="row-total"><td>TOTAL ' . $x->label . '</td>' . $cell($x->total) . '</tr>';
};
$liabEk = array(); for ($i = 0; $i < $n; $i++) $liabEk[] = $G['LIABILITAS']->total[$i] + $G['EKUITAS']->total[$i];
?>
<table class="table table-sm">
    <thead>
        <tr><th>Keterangan</th><?php foreach ($d['tanggal'] as $t) { ?><th class="num"><?php echo tgl_singkat($t); ?></th><?php } ?></tr>
    </thead>
    <tbody>
        <?php $blok('ASET'); ?>
        <tr><td colspan="99" style="border:0">&nbsp;</td></tr>
        <?php $blok('LIABILITAS'); $blok('EKUITAS'); ?>
        <tr class="row-grand"><td>TOTAL LIABILITAS &amp; EKUITAS</td><?php echo $cell($liabEk); ?></tr>
    </tbody>
</table>
<?php if ($mode !== 'excel') foreach ($d['selisih'] as $i => $s) { if (abs($s) > 0.5) { ?>
<div class="alert alert-warning py-2 mt-2"><i class="fas fa-exclamation-triangle"></i> Neraca per <?php echo tgl_singkat($d['tanggal'][$i]); ?> tidak seimbang, selisih <?php echo nf($s, 2); ?>. Biasanya karena saldo awal belum seimbang atau ada jurnal tidak seimbang.</div>
<?php } } ?>
