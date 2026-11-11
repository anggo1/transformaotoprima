<?php defined('BASEPATH') OR exit('No direct script access allowed');
$P = $d['profil']; $H = $d['hitung'];
$row = function ($ket, $v, $cls = '', $note = '') use ($mode) {
    echo '<tr class="' . $cls . '"><td>' . $ket . ($note !== '' && $mode !== 'excel' ? '<div class="small text-muted">' . $note . '</div>' : '') . '</td><td class="num">' . acc_val($v, $mode) . '</td></tr>';
};
?>
<table class="table table-sm" style="max-width:860px">
    <tbody>
    <tr class="row-group"><td colspan="2">I. LABA KOMERSIAL</td></tr>
    <?php $row('Laba (rugi) sebelum pajak menurut Laporan Laba Rugi', $d['laba_komersial'], 'row-sub'); ?>

    <tr class="row-group"><td colspan="2">II. KOREKSI FISKAL POSITIF (menambah laba fiskal)</td></tr>
    <?php foreach ($d['positif'] as $x) $row(html_escape($x->ket), $x->nilai, 'row-sub', html_escape($x->dasar));
    if ( ! $d['positif']) echo '<tr class="row-sub"><td class="text-muted">— tidak ada —</td><td></td></tr>';
    $row('Jumlah koreksi positif', $d['total_positif'], 'row-total'); ?>

    <tr class="row-group"><td colspan="2">III. KOREKSI FISKAL NEGATIF (mengurangi laba fiskal)</td></tr>
    <?php foreach ($d['negatif'] as $x) $row(html_escape($x->ket), $x->nilai, 'row-sub', html_escape($x->dasar));
    if ( ! $d['negatif']) echo '<tr class="row-sub"><td class="text-muted">— tidak ada —</td><td></td></tr>';
    $row('Jumlah koreksi negatif', $d['total_negatif'], 'row-total'); ?>

    <?php $row('<strong>IV. PENGHASILAN NETO FISKAL (I + II − III)</strong>', $d['laba_fiskal'], 'row-grand'); ?>

    <?php if ($d['final']) { ?>
    <tr class="row-group"><td colspan="2">V. PPh FINAL UMKM <?php echo nf($P->tarif_final, 1); ?>% × PEREDARAN BRUTO</td></tr>
    <?php foreach ($d['pph_final_bulanan'] as $b => $x) $row(bulan_panjang($b) . ' — bruto ' . nf($x->bruto), $x->pph, 'row-sub');
    $row('Jumlah PPh Final terutang', $d['pph_final'], 'row-total');
    $row('Sudah disetor (jenis PPh Final)', $d['setor_final'], 'row-sub');
    $row('<strong>Kurang (lebih) setor</strong>', $d['pph_final'] - $d['setor_final'], 'row-grand');
    } else { ?>
    <tr class="row-group"><td colspan="2">V. PERHITUNGAN PPh BADAN TERUTANG</td></tr>
    <?php $row('Peredaran bruto (pendapatan usaha + lain-lain non-final)', $d['bruto'], 'row-sub');
    $row('Penghasilan Kena Pajak (dibulatkan ke bawah ribuan)', $H->pkp, 'row-sub', html_escape($H->fasilitas));
    foreach ($H->rincian as $x) $row(html_escape($x[0]), $x[1], 'row-sub2');
    $row('<strong>PPh terutang</strong>', $d['terutang'], 'row-total'); ?>

    <tr class="row-group"><td colspan="2">VI. KREDIT PAJAK</td></tr>
    <?php foreach ($d['kredit'] as $k) $row(html_escape($k->label) . ($k->kode ? ' <span class="text-muted">(' . html_escape($k->kode) . ')</span>' : ' <span class="text-muted">(akun belum diatur)</span>'), $k->nilai, 'row-sub');
    $row('Jumlah kredit pajak', $d['total_kredit'], 'row-total');
    $row('<strong>VII. ' . ($d['pph29'] >= 0 ? 'PPh PASAL 29 KURANG BAYAR' : 'PPh LEBIH BAYAR') . ' (V − VI)</strong>', abs($d['pph29']), 'row-grand',
        $d['pph29'] >= 0 ? 'Disetor sebelum SPT Tahunan dilaporkan (paling lambat akhir bulan ke-4 setelah tahun pajak berakhir).' : 'Dapat diminta restitusi atau dikompensasikan sesuai ketentuan.');
    $row('Estimasi angsuran PPh 25 per bulan untuk tahun berikutnya', $d['pph25'], 'row-sub', '(PPh terutang − kredit PPh 22 & 23) ÷ 12 — angka final mengikuti SPT yang dilaporkan.'); ?>

    <tr class="row-group"><td colspan="2">VIII. PENCOCOKAN DENGAN PEMBUKUAN</td></tr>
    <?php $row('Beban pajak penghasilan yang sudah tercatat di Laba Rugi', $d['beban_pajak_tercatat'], 'row-sub');
    $row('Selisih dengan PPh terutang', $d['terutang'] - $d['beban_pajak_tercatat'], 'row-sub', $d['jurnal'] ? 'Jurnal PPh Badan: ' . html_escape($d['jurnal']) : 'Jurnal PPh Badan belum dibuat (menu Perpajakan → Fiskal & PPh Badan).');
    } ?>
    </tbody>
</table>
<?php if ($mode !== 'excel') { ?>
<p class="small text-muted mb-0">Perhitungan ini adalah alat bantu penyusunan Lampiran I SPT Tahunan PPh Badan (rekonsiliasi fiskal). Koreksi otomatis berasal dari tanda "Perlakuan Fiskal" di Daftar Akun; koreksi lain diinput di menu Perpajakan. Tarif: Pasal 17 UU PPh (<?php echo nf($P->tarif_badan, 0); ?>%) dengan fasilitas Pasal 31E. Periksa kembali dengan konsultan pajak sebelum melapor.</p>
<?php } ?>
