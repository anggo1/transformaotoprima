<?php defined('BASEPATH') OR exit('No direct script access allowed');
$R = $d['rekap']; $P = $d['profil'];
$tabel = function ($rows, $judul, $label) use ($mode) {
    $t = array(0, 0, 0);
    echo '<h6 class="mt-3 mb-2" style="font-weight:700">' . $judul . '</h6>';
    echo '<table class="table table-sm"><thead><tr><th>No</th><th>Tanggal</th><th>No. Faktur</th><th>No. Seri Faktur Pajak</th><th>' . $label . '</th><th class="num">DPP (Harga Jual − Diskon)</th><th class="num">DPP Nilai Lain</th><th class="num">Tarif</th><th class="num">PPN</th></tr></thead><tbody>';
    $i = 0;
    foreach ($rows as $x) {
        $i++; $t[0] += $x->dpp; $t[1] += $x->dpp_lain; $t[2] += $x->ppn;
        $nsfp = html_escape($x->no_faktur_pajak);
        if ($nsfp === '' && $mode === 'screen') $nsfp = '<span class="badge badge-warning">belum diisi</span>';
        echo '<tr><td>' . $i . '</td><td>' . ($mode === 'excel' ? $x->tanggal : tgl_singkat($x->tanggal)) . '</td><td>' . html_escape($x->no_faktur) . '</td><td>' . $nsfp . '</td><td>' . html_escape($x->kontak) . '</td>'
            . '<td class="num">' . acc_val($x->dpp, $mode) . '</td><td class="num">' . acc_val($x->dpp_lain, $mode) . '</td><td class="num">' . ($mode === 'excel' ? $x->tarif : nf($x->tarif, 0) . '%') . '</td><td class="num">' . acc_val($x->ppn, $mode) . '</td></tr>';
    }
    if ( ! $rows) echo '<tr><td colspan="9" class="text-center text-muted">Tidak ada faktur ber-PPN.</td></tr>';
    echo '</tbody><tfoot><tr class="row-total"><td colspan="5">Jumlah</td><td class="num">' . acc_val($t[0], $mode) . '</td><td class="num">' . acc_val($t[1], $mode) . '</td><td></td><td class="num">' . acc_val($t[2], $mode) . '</td></tr></tfoot></table>';
    return $t[2];
};
?>
<table class="table table-sm" style="max-width:760px">
    <thead><tr><th colspan="2">Rekapitulasi masa <?php echo bulan_panjang((int) $f['bulan']) . ' ' . $f['tahun']; ?> (angka dari jurnal akun PPN)</th></tr></thead>
    <tbody>
        <tr><td>A. PPN Keluaran (pajak atas penjualan)</td><td class="num"><?php echo acc_val($R->ppn_k, $mode); ?></td></tr>
        <tr><td>B. PPN Masukan yang dapat dikreditkan (pajak atas pembelian)</td><td class="num"><?php echo acc_val($R->ppn_m, $mode); ?></td></tr>
        <tr><td>C. Kompensasi lebih bayar dari masa sebelumnya</td><td class="num"><?php echo acc_val($R->kompensasi, $mode); ?></td></tr>
        <tr class="row-total"><td><?php echo $R->kb > 0 ? 'D. PPN KURANG BAYAR (A − B − C) — wajib disetor' : 'D. PPN LEBIH BAYAR (dikompensasikan ke masa berikutnya)'; ?></td><td class="num"><?php echo acc_val($R->kb > 0 ? $R->kb : $R->lb, $mode); ?></td></tr>
        <tr><td>Batas setor &amp; lapor SPT Masa PPN</td><td class="num"><?php echo $mode === 'excel' ? $R->jt_setor : tgl_singkat($R->jt_setor); ?></td></tr>
        <tr><td>Status tutup masa / jurnal</td><td class="num"><?php echo $R->tutup ?: 'Belum ditutup'; ?></td></tr>
        <tr><td>Sudah disetor (NTPN tercatat)</td><td class="num"><?php echo acc_val($R->setor, $mode); ?></td></tr>
    </tbody>
</table>
<?php
$tk = $tabel($d['rincian']['AR'], 'Daftar Pajak Keluaran (Faktur Penjualan)', 'Pembeli');
$tm = $tabel($d['rincian']['AP'], 'Daftar Pajak Masukan (Faktur Pembelian)', 'Penjual');
if ($mode !== 'excel') {
    $selK = round($R->ppn_k - $tk, 2); $selM = round($R->ppn_m - $tm, 2);
?>
<div class="small text-muted mt-2">
    <?php if (abs($selK) >= 1 || abs($selM) >= 1) { ?>
    <p class="mb-1"><i class="fas fa-info-circle text-warning"></i> PPN di jurnal berbeda dengan PPN di faktur (Keluaran <?php echo nf_acc($selK); ?>, Masukan <?php echo nf_acc($selM); ?>). Biasanya karena ada PPN yang dicatat lewat Kas &amp; Bank atau Jurnal Umum. Rekap di atas mengikuti jurnal.</p>
    <?php } ?>
    <p class="mb-0">DPP Nilai Lain = 11/12 × DPP untuk penyerahan dengan tarif 12% (barang/jasa non-mewah, PMK 131/2024), sehingga PPN efektif 11%. <?php echo $P->dpp_nilai_lain ? '' : '(Pengaturan DPP nilai lain sedang nonaktif.)'; ?> Cocokkan daftar ini dengan e-Faktur / Coretax sebelum melapor.</p>
</div>
<?php } ?>
