<?php defined('BASEPATH') OR exit('No direct script access allowed');
$nama = (string) $this->session->userdata('full_name');
$jam = (int) date('G');
$salam = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 19 ? 'Selamat sore' : 'Selamat malam'));
$lokNama = array('cbt' => 'Cibitung', 'jkt' => 'Jakarta', 'sby' => 'Surabaya');
?>
<style>
.dash-hero{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;background:linear-gradient(120deg,#0f1b2d 0%,#16304a 60%,#0e7c86 140%);color:#fff;border-radius:16px;padding:1.4rem 1.6rem;margin-bottom:1.1rem;position:relative;overflow:hidden}
.dash-hero h2{color:#fff;font-size:1.35rem;margin:0}
.dash-hero p{margin:.25rem 0 0;color:#b8c7d9}
.dash-hero .chips a{display:inline-flex;align-items:center;gap:.4rem;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);color:#fff;border-radius:999px;padding:.4rem .8rem;margin:.2rem;font-size:12px;font-weight:600}
.dash-hero .chips a:hover{background:rgba(255,255,255,.16);text-decoration:none}
.dash-sec{font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:var(--top-muted);font-weight:700;margin:1.2rem 0 .6rem}
.kpi-ico{float:right;width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:var(--top-accent-50);color:var(--top-accent)}
.kpi-ico.warn{background:#fdf3e1;color:#b7791f}.kpi-ico.red{background:#fbeaea;color:#c0392b}.kpi-ico.blue{background:#e8f0fb;color:#2563a8}
.chart-box{position:relative;height:280px}
</style>

<div class="dash-hero">
    <div>
        <h2><?php echo $salam; ?>, <?php echo html_escape($nama); ?></h2>
        <p><?php echo tgl_id(date('Y-m-d')); ?> &middot; <?php echo html_escape((string) $this->session->userdata('lokasi')); ?></p>
    </div>
    <div class="chips">
        <?php if ( ! empty($tampil_gudang)) { ?>
        <a href="<?php echo site_url('Sparepart'); ?>"><i class="fas fa-wrench"></i> Material &amp; Sparepart</a>
        <a href="<?php echo site_url('Part_keluar'); ?>"><i class="fas fa-dolly"></i> Part Keluar</a>
        <a href="<?php echo site_url('ReportWhNilaiStok'); ?>"><i class="fas fa-boxes"></i> Nilai Persediaan</a>
        <?php } ?>
        <?php if ( ! empty($tampil_keuangan)) { ?>
        <a href="<?php echo site_url('KasBank'); ?>"><i class="fas fa-wallet"></i> Kas &amp; Bank</a>
        <a href="<?php echo site_url('LaporanKeuangan'); ?>"><i class="fas fa-chart-pie"></i> Laporan Keuangan</a>
        <?php } ?>
    </div>
</div>

<?php if ( ! empty($tampil_keuangan) && ! empty($keuangan)) { $k = $keuangan; ?>
<div class="dash-sec">Keuangan</div>
<div class="acc-kpi">
    <div class="acc-kpi__item"><span class="kpi-ico"><i class="fas fa-wallet"></i></span><div class="acc-kpi__label">Kas &amp; Bank</div><div class="acc-kpi__value"><?php echo nf_acc($k->kas); ?></div><div class="acc-kpi__sub"><a href="<?php echo site_url('LaporanKeuangan/mutasi_kas'); ?>">mutasi &rsaquo;</a></div></div>
    <div class="acc-kpi__item"><span class="kpi-ico blue"><i class="fas fa-file-invoice-dollar"></i></span><div class="acc-kpi__label">Piutang terbuka</div><div class="acc-kpi__value"><?php echo nf_acc($k->piutang->sisa); ?></div><div class="acc-kpi__sub"><span class="text-danger"><?php echo nf($k->piutang->sisa_lewat); ?></span> lewat jatuh tempo</div></div>
    <div class="acc-kpi__item"><span class="kpi-ico warn"><i class="fas fa-file-invoice"></i></span><div class="acc-kpi__label">Hutang terbuka</div><div class="acc-kpi__value"><?php echo nf_acc($k->hutang->sisa); ?></div><div class="acc-kpi__sub"><span class="text-danger"><?php echo nf($k->hutang->sisa_lewat); ?></span> lewat jatuh tempo</div></div>
    <div class="acc-kpi__item"><span class="kpi-ico <?php echo $k->laba_bulan_ini < 0 ? 'red' : ''; ?>"><i class="fas fa-chart-line"></i></span><div class="acc-kpi__label">Laba (rugi) bulan ini</div><div class="acc-kpi__value"><?php echo acc_val($k->laba_bulan_ini); ?></div><div class="acc-kpi__sub"><a href="<?php echo site_url('LaporanKeuangan/laba_rugi'); ?>">laba rugi &rsaquo;</a></div></div>
</div>
<div class="card"><div class="card-header"><h3 class="card-title">Pendapatan vs Beban — 6 bulan terakhir</h3></div>
    <div class="card-body"><div class="chart-box"><canvas id="chart-keuangan"></canvas></div></div></div>
<?php } ?>

<?php if ( ! empty($tampil_gudang) && ! empty($gudang)) { $g = $gudang; ?>
<div class="dash-sec">Gudang &middot; <?php echo $lokNama[$lokasi_stok]; ?></div>
<div class="acc-kpi">
    <div class="acc-kpi__item"><span class="kpi-ico"><i class="fas fa-cubes"></i></span><div class="acc-kpi__label">Material &amp; Sparepart</div><div class="acc-kpi__value"><?php echo nf($g->item); ?></div><div class="acc-kpi__sub"><?php echo nf($g->ada); ?> item bersaldo</div></div>
    <div class="acc-kpi__item"><span class="kpi-ico blue"><i class="fas fa-coins"></i></span><div class="acc-kpi__label">Nilai persediaan</div><div class="acc-kpi__value"><?php echo nf($g->nilai); ?></div><div class="acc-kpi__sub">stok &times; harga net</div></div>
    <div class="acc-kpi__item"><span class="kpi-ico red"><i class="fas fa-exclamation-triangle"></i></span><div class="acc-kpi__label">Di bawah stok minimum</div><div class="acc-kpi__value"><?php echo nf($g->dibawah_min); ?></div><div class="acc-kpi__sub"><a href="<?php echo site_url('ReportWhNilaiStok?filter=min'); ?>">lihat daftar &rsaquo;</a></div></div>
    <div class="acc-kpi__item"><span class="kpi-ico warn"><i class="fas fa-file-signature"></i></span><div class="acc-kpi__label">PO belum diproses</div><div class="acc-kpi__value"><?php echo nf($g->po_terbuka); ?></div><div class="acc-kpi__sub"><a href="<?php echo site_url('PurchaseOrder'); ?>">purchase order &rsaquo;</a></div></div>
    <div class="acc-kpi__item"><span class="kpi-ico"><i class="fas fa-dolly"></i></span><div class="acc-kpi__label">Barang keluar bulan ini</div><div class="acc-kpi__value"><?php echo nf($g->keluar_nilai); ?></div><div class="acc-kpi__sub"><?php echo nf($g->keluar_dok); ?> dokumen</div></div>
</div>
<div class="row">
    <div class="col-lg-6"><div class="card"><div class="card-header"><h3 class="card-title">Barang keluar per divisi</h3><div class="card-tools"><a href="<?php echo site_url('ReportWhPerkategori'); ?>" class="small">Laporan &rsaquo;</a></div></div>
        <div class="card-body"><div class="chart-box"><canvas id="chart-divisi"></canvas></div></div></div></div>
    <div class="col-lg-6"><div class="card"><div class="card-header"><h3 class="card-title">Tren nilai barang keluar</h3><div class="card-tools"><a href="<?php echo site_url('ReportWhKeluar'); ?>" class="small">Laporan &rsaquo;</a></div></div>
        <div class="card-body"><div class="chart-box"><canvas id="chart-tren"></canvas></div></div></div></div>
</div>
<?php } ?>

<?php if (empty($tampil_gudang) && empty($tampil_keuangan)) { ?>
<div class="card"><div class="card-body text-muted">Gunakan menu di sebelah kiri untuk mulai bekerja. Tekan <kbd>/</kbd> untuk mencari menu.</div></div>
<?php } ?>

<script>
$(function () {
    if (typeof Chart === 'undefined') return;
    Chart.defaults.global.defaultFontFamily = getComputedStyle(document.body).fontFamily;
    Chart.defaults.global.defaultFontColor = '#667085';
    var fmt = function (v) { return TOP.fmt(v); };
    var singkat = function (v) { var a = Math.abs(v); return a >= 1e9 ? (v / 1e9).toFixed(1) + ' M' : a >= 1e6 ? (v / 1e6).toFixed(1) + ' jt' : a >= 1e3 ? (v / 1e3).toFixed(0) + ' rb' : v; };
    var opsi = function (stacked) {
        return { maintainAspectRatio: false, legend: { position: 'bottom', labels: { boxWidth: 12 } },
            tooltips: { callbacks: { label: function (t, d) { return d.datasets[t.datasetIndex].label + ': ' + fmt(t.yLabel); } } },
            scales: { xAxes: [{ gridLines: { display: false } }], yAxes: [{ ticks: { callback: singkat, beginAtZero: true }, gridLines: { color: '#eef1f6', zeroLineColor: '#d5dbe4' } }] } };
    };
<?php if ( ! empty($keuangan)) {
    $lbl = array(); $p = array(); $b = array(); $l = array();
    foreach ($keuangan->tren as $per => $v) { $lbl[] = bulan_singkat((int) substr($per, 5, 2)) . ' ' . substr($per, 2, 2); $p[] = round($v['pendapatan']); $b[] = round($v['beban']); $l[] = round($v['laba']); } ?>
    if (document.getElementById('chart-keuangan')) new Chart(document.getElementById('chart-keuangan'), { type: 'bar',
        data: { labels: <?php echo json_encode($lbl); ?>, datasets: [
            { label: 'Pendapatan', data: <?php echo json_encode($p); ?>, backgroundColor: '#0e7c86' },
            { label: 'Beban', data: <?php echo json_encode($b); ?>, backgroundColor: '#c8a25a' },
            { label: 'Laba', type: 'line', data: <?php echo json_encode($l); ?>, borderColor: '#0f1b2d', backgroundColor: 'transparent', pointRadius: 3, lineTension: .3 }
        ] }, options: opsi() });
<?php } ?>
<?php if ( ! empty($keluar_divisi)) {
    $dl = array(); $di = array(); $dla = array();
    foreach ($keluar_divisi as $r) { $dl[] = $r->divisi; $di[] = round($r->ini); $dla[] = round($r->lalu); } ?>
    if (document.getElementById('chart-divisi')) new Chart(document.getElementById('chart-divisi'), { type: 'bar',
        data: { labels: <?php echo json_encode($dl); ?>, datasets: [
            { label: 'Bulan lalu', data: <?php echo json_encode($dla); ?>, backgroundColor: '#c9d3df' },
            { label: 'Bulan ini', data: <?php echo json_encode($di); ?>, backgroundColor: '#0e7c86' }
        ] }, options: opsi() });
<?php } ?>
<?php if ( ! empty($tren_keluar)) {
    $tl = array(); $tv = array();
    foreach ($tren_keluar as $per => $v) { $tl[] = bulan_singkat((int) substr($per, 5, 2)) . ' ' . substr($per, 2, 2); $tv[] = round($v); } ?>
    if (document.getElementById('chart-tren')) new Chart(document.getElementById('chart-tren'), { type: 'line',
        data: { labels: <?php echo json_encode($tl); ?>, datasets: [{ label: 'Nilai keluar', data: <?php echo json_encode($tv); ?>, borderColor: '#0e7c86', backgroundColor: 'rgba(14,124,134,.10)', pointRadius: 3, lineTension: .3 }] },
        options: opsi() });
<?php } ?>
});
</script>
