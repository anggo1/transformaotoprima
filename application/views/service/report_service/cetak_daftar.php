<?php defined('BASEPATH') OR exit('No direct script access allowed');
$e = function ($s) { return html_escape(trim((string) $s)); };
$tg = function ($t) { return Mod_lapservice::tgl_ok($t) ? date('d/m/y', strtotime($t)) : '-'; };
$lab = array(); $nil = array(); foreach ($R->per_tgl as $t => $n) { $lab[] = date('d/m', strtotime($t)); $nil[] = $n; }
$sa = array_slice($R->per_sa, 0, 8, true);
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Services Report <?php echo $f['dari'] . ' - ' . $f['sampai']; ?></title>
<style>
@page { size: A4 landscape; margin: 9mm 9mm 11mm; }
* { box-sizing: border-box; }
body { font-family: Arial, Helvetica, sans-serif; font-size: 9.5px; color: #000; margin: 0; }
.kop { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid #000; padding-bottom: 4px; margin-bottom: 6px; }
.kop h2 { margin: 0; font-size: 15px; } .kop .r { text-align: right; }
.ring { display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; margin-bottom: 6px; }
.ring div { border: 1px solid #999; border-radius: 4px; padding: 4px 6px; } .ring b { display: block; font-size: 13px; }
.graf { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 6px; margin-bottom: 6px; }
.graf > div { border: 1px solid #ccc; border-radius: 4px; padding: 4px; }
table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #777; padding: 2.5px 4px; vertical-align: top; }
th { background: #e9eef3; font-size: 8.5px; text-transform: uppercase; } thead { display: table-header-group; } tr { page-break-inside: avoid; }
.num { text-align: right; white-space: nowrap; } .small { color: #444; font-size: 8.5px; }
tfoot td { font-weight: 700; background: #f2f4f7; }
.ttd { display: flex; justify-content: flex-end; gap: 40px; margin-top: 14px; page-break-inside: avoid; } .ttd div { width: 55mm; text-align: center; } .ttd span { display: block; margin-top: 36px; border-top: 1px solid #000; }
@media screen { body { padding: 54px 14px 14px; background: #e9edf2; } .lembar { background: #fff; padding: 9mm; max-width: 297mm; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,.12); }
  .bar { position: fixed; top: 0; left: 0; right: 0; background: #0f172a; color: #fff; padding: 9px 14px; font: 13px "Segoe UI", Arial; z-index: 9; } .bar button { background: #0e7c86; color: #fff; border: 0; padding: 6px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; } }
@media print { .bar { display: none; } }
</style></head>
<body>
<div class="bar"><button onclick="window.print()">Cetak</button> &nbsp; A4 landscape · grafik ikut tercetak</div>
<div class="lembar">
<div class="kop">
    <div><h2>SERVICES REPORT</h2><?php echo $e($apl ? $apl->nama_owner . ' — ' . $apl->lokasi : ''); ?></div>
    <div class="r">Periode <?php echo tgl_id($f['dari']) . ' s/d ' . tgl_id($f['sampai']); ?><br>Status: <?php echo $f['status'] === '' ? 'semua' : Mod_lapservice::$STATUS[$f['status']]; ?><?php echo $f['ciw'] ? ' · ' . Mod_lapservice::$CIW[$f['ciw']] : ''; ?><?php echo $f['sa'] ? ' · SA ' . $e($f['sa']) : ''; ?></div>
</div>
<div class="ring">
    <div>Work order<b><?php echo nf($R->n); ?></b>selesai <?php echo nf($R->selesai); ?> · proses <?php echo nf($R->proses); ?></div>
    <div>Rata-rata lama<b><?php echo $R->rata_hari === null ? '-' : nf($R->rata_hari, 1) . ' hari'; ?></b>masuk s/d tutup WO</div>
    <div>Jam terjual<b><?php echo nf($R->jam, 1); ?></b>sold hours</div>
    <div>Nilai jasa<b><?php echo nf($R->jasa); ?></b>labor</div>
    <div>Nilai part (estimasi)<b><?php echo nf($R->part); ?></b>total <?php echo nf($R->jasa + $R->part); ?></div>
</div>
<?php if ($rows) { ?>
<div class="graf">
    <div><div class="top-chart" data-cetak data-chart='<?php echo html_escape(json_encode(array('judul' => 'WO per tanggal masuk', 'tipe' => 'bar', 'labels' => $lab, 'seri' => array(array('nama' => 'WO', 'data' => $nil)), 'format' => 'qty', 'tinggi' => 150))); ?>'></div></div>
    <div><div class="top-chart" data-cetak data-chart='<?php echo html_escape(json_encode(array('judul' => 'Per jenis', 'tipe' => 'doughnut', 'labels' => array_keys($R->per_ciw), 'seri' => array(array('nama' => 'WO', 'data' => array_values($R->per_ciw))), 'format' => 'qty', 'tinggi' => 150))); ?>'></div></div>
    <div><div class="top-chart" data-cetak data-chart='<?php echo html_escape(json_encode(array('judul' => 'Per SA', 'tipe' => 'horizontalBar', 'labels' => array_keys($sa), 'seri' => array(array('nama' => 'WO', 'data' => array_values($sa))), 'format' => 'qty', 'tinggi' => 150))); ?>'></div></div>
</div>
<?php } ?>
<table>
    <thead><tr><th>No</th><th>No. WO</th><th>Masuk</th><th>Selesai</th><th>Customer</th><th>No. polisi / tipe</th><th>VIN</th><th>Keluhan</th><th>SA</th><th>Mekanik</th><th>Jenis</th><th class="num">Jam</th><th class="num">Jasa</th><th class="num">Part</th></tr></thead>
    <tbody>
    <?php $i = 0; foreach ($rows as $r) { ?>
        <tr><td><?php echo ++$i; ?></td><td><b><?php echo $e($r->wo_no); ?></b></td><td><?php echo $tg($r->date_open_wo); ?></td><td><?php echo $tg($r->date_close_wo); ?><?php echo $r->hari !== null ? '<div class="small">' . $r->hari . ' hr</div>' : ''; ?></td>
            <td><?php echo $e($r->customer_name); ?></td><td><b><?php echo $e($r->no_pol); ?></b><div class="small"><?php echo $e($r->type); ?></div></td><td class="small"><?php echo $e($r->vin); ?></td>
            <td><?php echo $e(mb_strimwidth((string) $r->customer_complain, 0, 90, '…')); ?></td><td><?php echo $e($r->sa_name); ?></td><td class="small"><?php echo $e($r->mekanik); ?></td>
            <td><?php echo $e(Mod_lapservice::$CIW[$r->status_service] ?? $r->status_service); ?><?php echo $r->free_service === 'Y' ? ' · Free' : ''; ?></td>
            <td class="num"><?php echo nf($r->jam, 1); ?></td><td class="num"><?php echo nf($r->jasa); ?></td><td class="num"><?php echo nf($r->part); ?></td></tr>
    <?php } if ( ! $rows) { ?><tr><td colspan="14" style="text-align:center">Tidak ada data.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr><td colspan="11">TOTAL</td><td class="num"><?php echo nf($R->jam, 1); ?></td><td class="num"><?php echo nf($R->jasa); ?></td><td class="num"><?php echo nf($R->part); ?></td></tr></tfoot>
</table>
<div class="ttd"><div>Dibuat oleh<span><?php echo $e((string) $this->session->userdata('full_name')); ?></span></div><div>Mengetahui<span>( ........................ )</span></div></div>
<div class="small" style="margin-top:6px">Dicetak <?php echo date('d/m/Y H:i'); ?>. Nilai jasa dari operasi work order; nilai part dari estimasi penawaran service.</div>
</div>
<script>window.TOP = { baseUrl: '<?php echo base_url(); ?>' };</script>
<script src="<?php echo base_url('assets/plugins/chart.js/Chart.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/top-chart.js?v=3.0.0'); ?>"></script>
<script>window.addEventListener('load', function () { (window.TOPChart ? TOPChart.render() : Promise.resolve()).then(function () { setTimeout(function () { window.print(); }, 300); }); });</script>
</body></html>
