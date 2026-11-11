<?php defined('BASEPATH') OR exit('No direct script access allowed');
$P = $C['profil'];
$tgl = tgl_id($C['sampai']); $tgl0 = tgl_id(Akuntansi::kemarin($C['dari']));
$metode = array_map(function ($m) { return array('GARIS_LURUS' => 'garis lurus', 'SALDO_MENURUN' => 'saldo menurun ganda', 'TIDAK' => 'tidak disusutkan')[$m] ?? strtolower($m); }, $C['metode_susut']);
?>
<div class="acc-calk">
<p><strong>1. Informasi Umum</strong><br>
<?php echo html_escape($P->nama_wp ?: 'Perusahaan'); ?><?php echo $P->npwp ? ', NPWP ' . html_escape($P->npwp) : ''; ?>, bergerak di bidang karoseri, perbaikan bodi dan perdagangan suku cadang kendaraan.
Status PPN: <?php echo $P->pkp ? 'Pengusaha Kena Pajak (PKP)' : 'bukan PKP'; ?>. Rezim PPh: <?php echo $P->rezim === 'FINAL_UMKM' ? 'PPh Final UMKM ' . nf($P->tarif_final, 1) . '% dari peredaran bruto' : 'tarif umum Pasal 17 UU PPh (' . nf($P->tarif_badan, 0) . '%) dengan fasilitas Pasal 31E'; ?>.</p>

<p><strong>2. Ikhtisar Kebijakan Akuntansi</strong></p>
<ol type="a" class="mb-3">
    <li><em>Dasar penyusunan.</em> Laporan keuangan disusun dengan dasar akrual dan konsep biaya historis, mengacu pada Standar Akuntansi Keuangan Entitas Privat (SAK EP) / SAK EMKM sesuai ukuran entitas. Mata uang penyajian Rupiah.</li>
    <li><em>Kas dan setara kas.</em> Terdiri dari kas di tangan dan rekening bank yang dapat segera digunakan.</li>
    <li><em>Piutang usaha.</em> Dicatat sebesar nilai faktur dikurangi pelunasan dan potongan PPh oleh pelanggan. Umur piutang dipantau untuk menilai kemungkinan tidak tertagih.</li>
    <li><em>Persediaan.</em> Dinyatakan sebesar biaya perolehan; biaya pemakaian diakui sebagai Harga Pokok saat material/suku cadang dikeluarkan ke pekerjaan.</li>
    <li><em>Aset tetap.</em> Dicatat sebesar harga perolehan dikurangi akumulasi penyusutan. Penyusutan dihitung bulanan dengan metode <?php echo $metode ? implode(' / ', $metode) : 'garis lurus'; ?> berdasarkan taksiran masa manfaat.</li>
    <li><em>Pengakuan pendapatan dan beban.</em> Pendapatan diakui saat barang/jasa diserahkan dan faktur diterbitkan; beban diakui saat terjadi (akrual).</li>
    <li><em>Perpajakan.</em> PPN Keluaran dan Masukan dikompensasikan setiap masa; selisih kurang bayar dicatat sebagai Hutang PPN. PPh yang dipotong pihak lain dicatat sebagai pajak dibayar dimuka dan dikreditkan pada akhir tahun. Beban pajak penghasilan dihitung dari laba fiskal setelah koreksi fiskal.</li>
</ol>

<?php foreach ($C['catatan'] as $c) { ?>
<p class="mb-1"><strong><?php echo $c->no . '. ' . html_escape($c->judul); ?></strong></p>
<table class="table table-sm mb-2">
    <thead><tr><th>Akun</th><th class="num"><?php echo $c->lr ? 'Periode ini' : $tgl; ?></th><?php if ( ! $c->lr) { ?><th class="num"><?php echo $tgl0; ?></th><?php } ?></tr></thead>
    <tbody>
    <?php foreach ($c->rows as $r) { ?><tr class="row-sub"><td><?php echo html_escape($r->kode . ' ' . $r->nama); ?></td><td class="num"><?php echo acc_val($r->v1, $mode); ?></td><?php if ( ! $c->lr) { ?><td class="num"><?php echo acc_val($r->v0, $mode); ?></td><?php } ?></tr><?php } ?>
    <tr class="row-total"><td>Jumlah</td><td class="num"><?php echo acc_val($c->t1, $mode); ?></td><?php if ( ! $c->lr) { ?><td class="num"><?php echo acc_val($c->t0, $mode); ?></td><?php } ?></tr>
    </tbody>
</table>
<?php if ($c->tambahan && $c->tambahan['jenis'] === 'umur') { $u = $c->tambahan['umur']; ?>
<div class="small mb-1">Umur <?php echo $c->kat === 'PIUTANG' ? 'piutang' : 'hutang'; ?> dari faktur: belum jatuh tempo <?php echo nf_acc($u['b0']); ?> · 1–30 hari <?php echo nf_acc($u['b1']); ?> · 31–60 hari <?php echo nf_acc($u['b2']); ?> · 61–90 hari <?php echo nf_acc($u['b3']); ?> · &gt;90 hari <?php echo nf_acc($u['b4']); ?>.
Saldo terbesar: <?php echo implode('; ', array_map(function ($k) { return html_escape($k->kontak) . ' ' . nf_acc($k->total); }, $c->tambahan['top'])); ?>.</div>
<?php } elseif ($c->tambahan && $c->tambahan['jenis'] === 'aset') { ?>
<table class="table table-sm mb-2" style="max-width:720px"><thead><tr><th>Kelompok aset</th><th class="num">Jml</th><th class="num">Harga Perolehan</th><th class="num">Akum. Penyusutan</th><th class="num">Nilai Buku</th></tr></thead><tbody>
<?php foreach ($c->tambahan['kelompok'] as $g) { ?><tr><td><?php echo html_escape($g->kelompok); ?></td><td class="num"><?php echo $g->n; ?></td><td class="num"><?php echo acc_val($g->perolehan, $mode); ?></td><td class="num"><?php echo acc_val($g->akumulasi, $mode); ?></td><td class="num"><?php echo acc_val($g->nb, $mode); ?></td></tr><?php } ?>
</tbody></table>
<?php } ?>
<?php } ?>
</div>
