<?php defined('BASEPATH') OR exit('No direct script access allowed');
$grup = array(); foreach ($laporan as $k => $l) $grup[$l[3]][$k] = $l; $lengkap = count(array_filter($kl['rows'], function ($x) { return ! $x->kurang; }));
?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Pegawai aktif</div><div class="acc-kpi__value"><?php echo nf($kom['total']); ?></div><div class="acc-kpi__sub"><?php echo nf(count($kom['dept'])); ?> departemen · <?php echo nf(count($kom['jab'])); ?> jabatan</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Rata-rata masa kerja</div><div class="acc-kpi__value"><?php echo nf($mk['rata'], 1); ?> th</div><div class="acc-kpi__sub"><?php echo nf(count($mk['milestone'])); ?> ulang tahun kerja kelipatan 5 mendatang</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Kelengkapan data</div><div class="acc-kpi__value"><?php echo $kl['n'] ? round($lengkap / $kl['n'] * 100) : 0; ?>%</div><div class="acc-kpi__sub"><a href="<?php echo site_url('LaporanHR/kelengkapan'); ?>"><?php echo nf($kl['n'] - $lengkap); ?> pegawai belum lengkap ›</a></div></div>
</div>
<div class="row">
    <div class="col-lg-5"><div class="card"><div class="card-header"><h3 class="card-title">Pegawai per departemen</h3></div><div class="card-body"><div class="top-chart" data-tanpa-judul data-chart='<?php echo html_escape(json_encode(array('judul' => 'Pegawai per departemen', 'tipe' => 'doughnut', 'labels' => array_keys($kom['dept']), 'seri' => array(array('nama' => 'Pegawai', 'data' => array_values($kom['dept']))), 'format' => 'qty'))); ?>'></div></div></div></div>
    <div class="col-lg-7"><div class="card"><div class="card-header"><h3 class="card-title">Sebaran masa kerja</h3></div><div class="card-body"><div class="top-chart" data-tanpa-judul data-chart='<?php echo html_escape(json_encode(array('judul' => 'Sebaran masa kerja', 'tipe' => 'bar', 'labels' => array_keys($mk['kelompok']), 'seri' => array(array('nama' => 'Pegawai', 'data' => array_values($mk['kelompok']))), 'format' => 'qty'))); ?>'></div></div></div></div>
</div>
<div class="card"><div class="card-body">
<?php foreach ($grup as $g => $ls) { ?>
<div class="acc-hub-title"><?php echo html_escape($g); ?></div>
<div class="acc-hub">
<?php foreach ($ls as $k => $l) { ?>
    <a href="<?php echo site_url('LaporanHR/' . $k); ?>"><i class="fas <?php echo $l[1]; ?>"></i><div><strong><?php echo $l[0]; ?></strong><span><?php echo $l[2]; ?></span></div></a>
<?php } ?>
</div>
<?php } ?>
</div></div>
<div class="card"><div class="card-body small">
    <b><i class="fas fa-lightbulb text-warning mr-1"></i> Rekomendasi pengembangan HRM</b>
    <ul class="mb-0 mt-1">
        <li>Lengkapi NPWP/NIK, BPJS, tanggal lahir & tanggal masuk semua pegawai — dibutuhkan untuk PPh 21, BPJS, dan perhitungan pesangon/masa kerja.</li>
        <li>Cocokkan PIN mesin absen dengan NIP/NIPA pegawai agar rekap absensi & keterlambatan otomatis tampil per nama.</li>
        <li>Petakan akun user ke departemen & NIP di <a href="<?php echo site_url('PengaturanDashboard#departemen'); ?>">Pengaturan Dashboard</a> agar setiap departemen mendapat dashboard sendiri.</li>
        <li>Tahap berikutnya yang disarankan: penggajian (payroll) terhubung ke jurnal Akuntansi (beban gaji, hutang PPh 21, BPJS), saldo hak cuti per pegawai, dan kontrak kerja (PKWT) dengan pengingat habis kontrak.</li>
    </ul>
</div></div>
