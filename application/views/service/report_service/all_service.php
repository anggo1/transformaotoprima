<?php defined('BASEPATH') OR exit('No direct script access allowed');
$q = http_build_query($f);
$lab = array(); $nil = array(); foreach ($R->per_tgl as $t => $n) { $lab[] = date('d/m', strtotime($t)); $nil[] = $n; }
$g1 = array('judul' => 'Work order per tanggal masuk', 'tipe' => 'bar', 'labels' => $lab, 'seri' => array(array('nama' => 'WO', 'data' => $nil)), 'format' => 'qty', 'tinggi' => 230);
$g2 = array('judul' => 'WO per jenis (CIW/GW)', 'tipe' => 'doughnut', 'labels' => array_keys($R->per_ciw), 'seri' => array(array('nama' => 'WO', 'data' => array_values($R->per_ciw))), 'format' => 'qty', 'tinggi' => 230);
$sa = array_slice($R->per_sa, 0, 10, true);
$g3 = array('judul' => 'WO per Service Advisor', 'tipe' => 'horizontalBar', 'labels' => array_keys($sa), 'seri' => array(array('nama' => 'WO', 'data' => array_values($sa))), 'format' => 'qty', 'tinggi' => 230);
$st = array('F' => array('Selesai', 'success'), 'P' => array('Proses', 'info'), 'N' => array('Appointment', 'warning'));
$ciw = array('C' => 'secondary', 'I' => 'info', 'W' => 'primary', 'GW' => 'warning');
?>
<div class="card no-print">
    <form class="card-body acc-toolbar d-flex flex-wrap align-items-end" method="get" style="gap:.6rem">
        <div class="form-group mb-0"><label class="mb-1">Dari</label><input type="date" name="dari" class="form-control form-control-sm" value="<?php echo $f['dari']; ?>"></div>
        <div class="form-group mb-0"><label class="mb-1">Sampai</label><input type="date" name="sampai" class="form-control form-control-sm" value="<?php echo $f['sampai']; ?>"></div>
        <div class="form-group mb-0"><label class="mb-1">Status WO</label><select name="status" class="form-control form-control-sm">
            <option value="" <?php echo $f['status'] === '' ? 'selected' : ''; ?>>Semua</option><?php foreach (Mod_lapservice::$STATUS as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $f['status'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
        <div class="form-group mb-0"><label class="mb-1">Jenis</label><select name="ciw" class="form-control form-control-sm"><option value="">Semua</option><?php foreach (Mod_lapservice::$CIW as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $f['ciw'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
        <div class="form-group mb-0"><label class="mb-1">Service advisor</label><select name="sa" class="form-control form-control-sm"><option value="">Semua</option><?php foreach ($sa_list as $s) { ?><option <?php echo $f['sa'] === $s->sa_name ? 'selected' : ''; ?>><?php echo html_escape($s->sa_name); ?></option><?php } ?></select></div>
        <div class="form-group mb-0"><button class="btn btn-primary btn-sm"><i class="fas fa-sync-alt mr-1"></i> Tampilkan</button></div>
        <div class="form-group mb-0 ml-auto btn-group">
            <a class="btn btn-outline-secondary btn-sm" target="_blank" href="<?php echo site_url('ReportService/cetak_daftar?' . $q); ?>"><i class="fas fa-print mr-1"></i> Cetak laporan</a>
            <a class="btn btn-outline-secondary btn-sm" href="<?php echo site_url('ReportService/excel?' . $q); ?>"><i class="fas fa-file-excel mr-1"></i> Excel</a>
        </div>
    </form>
</div>

<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Work order</div><div class="acc-kpi__value"><?php echo nf($R->n); ?></div><div class="acc-kpi__sub">selesai <?php echo nf($R->selesai); ?> · proses <?php echo nf($R->proses); ?> · appointment <?php echo nf($R->baru); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Rata-rata lama pengerjaan</div><div class="acc-kpi__value"><?php echo $R->rata_hari === null ? '-' : nf($R->rata_hari, 1) . ' hari'; ?></div><div class="acc-kpi__sub">dari tanggal masuk s/d tutup WO</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Jam terjual (sold hours)</div><div class="acc-kpi__value"><?php echo nf($R->jam, 1); ?></div><div class="acc-kpi__sub">dari operasi pada work order</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Nilai jasa + part</div><div class="acc-kpi__value"><?php echo nf_acc($R->jasa + $R->part); ?></div><div class="acc-kpi__sub">jasa <?php echo nf_acc($R->jasa); ?> · part (estimasi) <?php echo nf_acc($R->part); ?></div></div>
</div>

<?php if ($rows) { ?>
<div class="top-grafik-grid">
    <div class="card"><div class="card-body"><div class="top-chart" data-chart='<?php echo html_escape(json_encode($g1)); ?>'></div></div></div>
    <div class="card"><div class="card-body"><div class="top-chart" data-chart='<?php echo html_escape(json_encode($g2)); ?>'></div></div></div>
    <div class="card"><div class="card-body"><div class="top-chart" data-chart='<?php echo html_escape(json_encode($g3)); ?>'></div></div></div>
</div>
<?php } ?>

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between" style="gap:.5rem">
        <h3 class="card-title">Daftar work order <small class="text-muted"><?php echo tgl_id($f['dari']) . ' s/d ' . tgl_id($f['sampai']); ?></small></h3>
        <input type="search" class="form-control form-control-sm" id="rs-cari" placeholder="Saring WO, customer, no. polisi, VIN…" style="max-width:280px">
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-sm table-hover mb-0" id="rs-tabel">
            <thead class="thead-sticky"><tr><th>No. WO</th><th>Masuk / selesai</th><th>Customer</th><th>Kendaraan</th><th>Keluhan</th><th>SA / mekanik</th><th>Jenis</th><th class="num">Jam</th><th class="num">Jasa</th><th class="num">Part</th><th>Status</th><th class="text-center">Cetak</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r) { $s = $st[$r->status] ?? array($r->status, 'light'); $w = rawurlencode($r->wo_no); ?>
                <tr data-cari="<?php echo html_escape(strtolower($r->wo_no . ' ' . $r->customer_name . ' ' . $r->no_pol . ' ' . $r->vin . ' ' . $r->type . ' ' . $r->sa_name)); ?>">
                    <td><b><?php echo html_escape($r->wo_no); ?></b></td>
                    <td class="text-nowrap"><?php echo tgl_singkat($r->date_open_wo); ?> <span class="text-muted small"><?php echo html_escape(substr((string) $r->clockin, 0, 5)); ?></span><div class="small text-muted"><?php echo Mod_lapservice::tgl_ok($r->date_close_wo) ? 'selesai ' . tgl_singkat($r->date_close_wo) . ($r->hari !== null ? ' · ' . $r->hari . ' hr' : '') : '—'; ?></div></td>
                    <td><b><?php echo html_escape($r->customer_name); ?></b><div class="small text-muted"><?php echo html_escape($r->customer); ?></div></td>
                    <td><b><?php echo html_escape($r->no_pol); ?></b><div class="small text-muted"><?php echo html_escape($r->type); ?> · <?php echo html_escape($r->mileage); ?> km</div><div class="small text-muted">VIN <?php echo html_escape(trim((string) $r->vin)); ?></div></td>
                    <td style="min-width:180px;white-space:normal"><?php echo html_escape($r->customer_complain); ?></td>
                    <td><?php echo html_escape($r->sa_name); ?><div class="small text-muted"><?php echo html_escape($r->mekanik); ?></div></td>
                    <td><span class="badge badge-<?php echo $ciw[$r->status_service] ?? 'light'; ?>"><?php echo html_escape(Mod_lapservice::$CIW[$r->status_service] ?? $r->status_service); ?></span><?php echo $r->free_service === 'Y' ? '<div><span class="badge badge-success">Free</span></div>' : ''; ?></td>
                    <td class="num"><?php echo nf($r->jam, 1); ?><div class="small text-muted"><?php echo nf($r->n_op); ?> op</div></td>
                    <td class="num"><?php echo nf_acc($r->jasa); ?></td>
                    <td class="num"><?php echo nf_acc($r->part); ?></td>
                    <td><span class="badge badge-<?php echo $s[1]; ?>"><?php echo $s[0]; ?></span></td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-print"></i></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" target="_blank" href="<?php echo site_url('ReportService/cetak/ringkasan/' . $w); ?>"><i class="fas fa-file-alt fa-fw mr-1"></i> Laporan service lengkap</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item <?php echo $r->ada_pre ? '' : 'text-muted'; ?>" target="_blank" href="<?php echo site_url('ReportService/cetak/preorder/' . $w); ?>"><i class="fas fa-clipboard-check fa-fw mr-1"></i> Pre Order</a>
                                <a class="dropdown-item <?php echo $r->ada_est ? '' : 'text-muted'; ?>" target="_blank" href="<?php echo site_url('ReportService/cetak/estimasi/' . $w); ?>"><i class="fas fa-file-invoice-dollar fa-fw mr-1"></i> Estimasi penawaran</a>
                                <a class="dropdown-item" target="_blank" href="<?php echo site_url('ReportService/cetak/wo/' . $w); ?>"><i class="fas fa-file-signature fa-fw mr-1"></i> Work Order</a>
                                <a class="dropdown-item" target="_blank" href="<?php echo site_url('ReportService/cetak/jobtime/' . $w); ?>"><i class="fas fa-user-clock fa-fw mr-1"></i> Job Time Card</a>
                                <a class="dropdown-item" target="_blank" href="<?php echo site_url('ReportService/cetak/workshop/' . $w); ?>"><i class="fas fa-tools fa-fw mr-1"></i> Workshop Report (WOR)</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } if ( ! $rows) { ?><tr><td colspan="12" class="text-center text-muted py-4">Tidak ada work order pada filter ini.</td></tr><?php } ?>
            </tbody>
            <?php if ($rows) { ?><tfoot><tr class="row-grand"><td colspan="7">TOTAL (<?php echo nf($R->n); ?> WO)</td><td class="num"><?php echo nf($R->jam, 1); ?></td><td class="num"><?php echo nf_acc($R->jasa); ?></td><td class="num"><?php echo nf_acc($R->part); ?></td><td colspan="2"></td></tr></tfoot><?php } ?>
        </table>
        </div>
    </div>
</div>
<?php if ($R->per_cus) { ?>
<div class="row">
    <div class="col-lg-6"><div class="card"><div class="card-header"><h3 class="card-title">Pelanggan terbanyak</h3></div><div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>Customer</th><th class="num">WO</th><th class="num">Nilai</th></tr></thead><tbody>
        <?php foreach (array_slice($R->per_cus, 0, 10, true) as $k => $v) { ?><tr><td><?php echo html_escape($k); ?></td><td class="num"><?php echo nf($v[0]); ?></td><td class="num"><?php echo nf_acc($v[1]); ?></td></tr><?php } ?>
    </tbody></table></div></div></div>
    <div class="col-lg-6"><div class="card"><div class="card-header"><h3 class="card-title">Tipe kendaraan</h3></div><div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>Tipe</th><th class="num">WO</th><th class="num">%</th></tr></thead><tbody>
        <?php foreach (array_slice($R->per_type, 0, 10, true) as $k => $v) { ?><tr><td><?php echo html_escape($k); ?></td><td class="num"><?php echo nf($v); ?></td><td class="num"><?php echo $R->n ? nf($v / $R->n * 100, 1) : 0; ?></td></tr><?php } ?>
    </tbody></table></div></div></div>
</div>
<?php } ?>
<script>
(function () {
    var c = document.getElementById('rs-cari'); if (!c) return;
    c.addEventListener('input', function () { var q = this.value.toLowerCase().trim(); document.querySelectorAll('#rs-tabel tbody tr[data-cari]').forEach(function (tr) { tr.hidden = q && tr.getAttribute('data-cari').indexOf(q) < 0; }); });
})();
</script>
