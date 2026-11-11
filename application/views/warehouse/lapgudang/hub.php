<?php defined('BASEPATH') OR exit('No direct script access allowed');
$D = $dash; $grup = array();
foreach ($laporan as $k => $l) $grup[$l[3]][$k] = $l;
$totNilai = 0; foreach ($D['lokasi'] as $x) $totNilai += $x->nilai;
// grafik 30 hari
$hari = array(); $maks = 1;
for ($i = 29; $i >= 0; $i--) { $t = date('Y-m-d', strtotime('-' . $i . ' day')); $v = isset($D['harian'][$t]) ? $D['harian'][$t] : array(0, 0); $hari[$t] = $v; $maks = max($maks, $v[0], $v[1]); }
?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Nilai persediaan (semua gudang)</div><div class="acc-kpi__value"><?php echo nf_acc($totNilai); ?></div><div class="acc-kpi__sub"><?php echo nf($D['item']); ?> barang terdaftar</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Masuk bulan ini</div><div class="acc-kpi__value"><?php echo nf_qty($D['bulan']['masuk']); ?></div><div class="acc-kpi__sub"><?php echo count($D['bulan']['dok_m']); ?> dokumen · <?php echo nf_acc($D['bulan']['nm']); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Keluar bulan ini</div><div class="acc-kpi__value"><?php echo nf_qty($D['bulan']['keluar']); ?></div><div class="acc-kpi__sub"><?php echo count($D['bulan']['dok_k']); ?> dokumen · <?php echo nf_acc($D['bulan']['nk']); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">PO ke pemasok belum datang</div><div class="acc-kpi__value"><?php echo nf($D['po_ap']); ?> <small style="font-size:12px" class="text-muted">baris</small></div><div class="acc-kpi__sub"><a href="<?php echo site_url('LaporanGudang/outstanding'); ?>">nilai <?php echo nf_acc($D['po_ap_nilai']); ?> · <?php echo nf($D['po_ar']); ?> baris PO customer belum dikirim ›</a></div></div>
</div>

<div class="row">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Stok per gudang</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Gudang</th><th class="num">Barang bersaldo</th><th class="num">Total qty</th><th class="num">Nilai</th><th class="num">≤ Stok min</th><th class="num">Habis</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($D['lokasi'] as $k => $x) { ?>
                        <tr><td><b><?php echo $x->nama; ?></b><div class="wh-bar wh-bar--thin"><span style="width:<?php echo $totNilai > 0 ? round($x->nilai / $totNilai * 100, 1) : 0; ?>%"></span></div></td>
                            <td class="num"><?php echo nf($x->item); ?></td><td class="num"><?php echo nf_qty($x->qty); ?></td><td class="num"><?php echo nf_acc($x->nilai); ?></td>
                            <td class="num"><a href="<?php echo site_url('LaporanGudang/stok_minimum?lok=' . $k); ?>"><?php echo nf($x->min); ?></a></td><td class="num <?php echo $x->habis ? 'text-danger font-weight-bold' : ''; ?>"><?php echo nf($x->habis); ?></td>
                            <td class="text-right"><a class="btn btn-xs btn-outline-secondary" href="<?php echo site_url('ReportWhNilaiStok?lokasi=' . $k); ?>">Detail</a></td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center"><h3 class="card-title">Mutasi 30 hari terakhir</h3><span class="small text-muted"><span class="wh-dot wh-dot--in"></span> masuk &nbsp; <span class="wh-dot wh-dot--out"></span> keluar (qty)</span></div>
            <div class="card-body">
                <div class="wh-chart" role="img" aria-label="Grafik mutasi masuk dan keluar 30 hari">
                    <?php foreach ($hari as $t => $v) { ?>
                    <div class="wh-chart__col" title="<?php echo tgl_singkat($t) . ' — masuk ' . nf($v[0]) . ', keluar ' . nf($v[1]); ?>">
                        <span class="wh-chart__in" style="height:<?php echo round($v[0] / $maks * 100); ?>%"></span><span class="wh-chart__out" style="height:<?php echo round($v[1] / $maks * 100); ?>%"></span>
                    </div>
                    <?php } ?>
                </div>
                <div class="d-flex justify-content-between small text-muted mt-1"><span><?php echo tgl_singkat(date('Y-m-d', strtotime('-29 day'))); ?></span><span><?php echo tgl_singkat(date('Y-m-d')); ?></span></div>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Paling banyak dipakai (90 hari)</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0"><tbody>
                <?php $mx = 1; foreach ($D['top'] as $x) $mx = max($mx, $x['nilai']);
                foreach ($D['top'] as $k => $x) { ?>
                    <tr><td><a href="<?php echo site_url('LaporanGudang/kartu_stok?part=' . rawurlencode($k)); ?>"><?php echo html_escape($k); ?></a><div class="small text-muted text-truncate" style="max-width:260px"><?php echo html_escape($x['nama']); ?></div>
                        <div class="wh-bar wh-bar--thin"><span style="width:<?php echo round($x['nilai'] / $mx * 100); ?>%"></span></div></td>
                        <td class="num"><?php echo nf_qty($x['qty']); ?> <?php echo html_escape($x['satuan']); ?><div class="small text-muted"><?php echo nf_acc($x['nilai']); ?></div></td></tr>
                <?php } if ( ! $D['top']) { ?><tr><td class="text-center text-muted py-3">Belum ada pengeluaran.</td></tr><?php } ?>
                </tbody></table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Pengeluaran per jenis (90 hari)</h3></div>
            <div class="card-body">
                <?php $tj = 0; foreach ($D['jenis'] as $x) $tj += $x['nilai']; foreach ($D['jenis'] as $k => $x) { $p = $tj > 0 ? $x['nilai'] / $tj * 100 : 0; ?>
                <div class="d-flex justify-content-between small"><span><?php echo html_escape($k); ?></span><span><?php echo nf_acc($x['nilai']); ?></span></div>
                <div class="wh-bar mb-2"><span style="width:<?php echo round($p, 1); ?>%"></span><em><?php echo nf($p, 1); ?>%</em></div>
                <?php } if ( ! $D['jenis']) { ?><div class="text-muted small">Belum ada data.</div><?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p class="text-muted mb-0">Laporan gudang lengkap — semua dapat dicetak & diekspor ke Excel, dihitung dari transaksi Part Masuk, Part Keluar (umum/PK, WO, PO customer), Part Order, PO Masuk dan Stock Opname.</p>
        <?php foreach ($grup as $g => $items) { ?>
        <div class="acc-hub-title"><?php echo html_escape($g); ?></div>
        <div class="acc-hub">
            <?php foreach ($items as $k => $l) { ?>
            <a href="<?php echo site_url('LaporanGudang/' . $k); ?>"><i class="fas <?php echo $l[1]; ?>"></i><div><strong><?php echo html_escape($l[0]); ?></strong><span><?php echo html_escape($l[2]); ?></span></div></a>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="acc-hub-title">Laporan gudang lama (tetap tersedia)</div>
        <div class="acc-hub">
            <?php foreach (array('ReportWhMasuk' => array('Penerimaan Barang', 'fa-dolly-flatbed'), 'ReportWhKeluar' => array('Pengeluaran Barang', 'fa-dolly'), 'ReportWhPerbarang' => array('Report Perbarang', 'fa-luggage-cart'),
                'ReportWhPerkategori' => array('Report Per Divisi', 'fa-layer-group'), 'ReportWhMasukPerbarang' => array('Report Barang Masuk', 'fa-chalkboard-teacher'), 'ReportWhReturn' => array('Barang Return', 'fa-exchange-alt'),
                'ReportOpname' => array('Report Stok Opname', 'fa-vote-yea'), 'ReportWhPartpk' => array('Report Part PK', 'fa-tasks'), 'ReportWhPo' => array('List PO', 'fa-tasks'), 'ReportWhPoMasuk' => array('List PO Masuk', 'fa-tasks'),
                'ReportWhPartorder' => array('List Part Order', 'fa-list'), 'ReportWhPenawaran' => array('List Penawaran', 'fa-list')) as $u => $l) { ?>
            <a href="<?php echo site_url($u); ?>"><i class="fas <?php echo $l[1]; ?>"></i><div><strong><?php echo $l[0]; ?></strong><span>Laporan bawaan</span></div></a>
            <?php } ?>
        </div>
    </div>
</div>
