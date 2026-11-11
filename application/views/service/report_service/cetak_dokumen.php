<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Dokumen cetak After Sales (halaman mandiri, A4). jenis: wo | preorder | estimasi | jobtime | workshop | ringkasan */
$e = function ($s) { return html_escape(trim((string) $s)); };
$tg = function ($t) { return Mod_lapservice::tgl_ok($t) ? date('d/m/Y', strtotime($t)) : ''; };
$jm = function ($t) { return $t !== null && trim((string) $t) !== '' && substr((string) $t, 0, 5) !== '00:00' ? substr((string) $t, 0, 5) : ''; };
$rp = function ($n) { return 'Rp ' . number_format((float) $n, 0, ',', '.'); };
$c = $r->cus;
$judul = array('wo' => 'WORK ORDER', 'preorder' => 'PRE ORDER / VEHICLE RECEPTION', 'estimasi' => 'ESTIMASI PENAWARAN SPAREPART', 'jobtime' => 'JOB TIME CARD', 'workshop' => 'WORKSHOP REPORT', 'ringkasan' => 'LAPORAN SERVICE')[$jenis];
$ciw = Mod_lapservice::$CIW[$r->status_service] ?? $r->status_service;
$ops = $r->op_wo ?: $r->op_pre;
$kotak = function ($on) { return '<span class="cb">' . ($on ? '&#10004;' : '') . '</span>'; };
$blank = function ($n, $kol) { $h = ''; for ($i = 0; $i < $n; $i++) $h .= '<tr class="kosong">' . str_repeat('<td>&nbsp;</td>', $kol) . '</tr>'; return $h; };
$base = rtrim(base_url(), '/') . '/';
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title><?php echo $judul . ' ' . $e($r->wo_no); ?></title>
<style>
@page { size: A4 portrait; margin: 10mm 11mm 12mm; }
* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; }
body { font-family: Arial, Helvetica, sans-serif; font-size: 10.5px; color: #000; line-height: 1.3; }
.hal { width: 188mm; margin: 0 auto; }
.kop { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 6px; }
.kop .logo { text-align: center; } .kop .logo img { height: 15mm; }
.kop .pt { text-align: right; font-size: 10px; } .kop .pt b { font-size: 12px; }
.kop .judul b { font-size: 15px; letter-spacing: .03em; display: block; } .kop .judul span { font-size: 10px; }
h4 { margin: 8px 0 3px; font-size: 11px; text-transform: uppercase; letter-spacing: .04em; }
table { width: 100%; border-collapse: collapse; }
.g td, .g th { border: 1px solid #000; padding: 3px 5px; vertical-align: top; }
.g th { background: #e9eef3; font-weight: 700; text-align: left; font-size: 9.5px; }
.lbl { display: block; font-size: 8.5px; color: #333; text-transform: uppercase; letter-spacing: .02em; }
.val { display: block; font-weight: 700; font-size: 11px; min-height: 13px; }
.dua { display: grid; grid-template-columns: 1.25fr 1fr; gap: 6px; align-items: start; }
.kv td { padding: 1.5px 3px; vertical-align: top; } .kv td:first-child { width: 33%; color: #333; }
.ref td { border: 1px solid #000; padding: 3px 5px; text-align: center; }
.c { text-align: center; } .r { text-align: right; white-space: nowrap; } .b { font-weight: 700; }
tr.kosong td { height: 17px; }
.cb { display: inline-block; width: 11px; height: 11px; border: 1px solid #000; text-align: center; line-height: 10px; font-size: 10px; vertical-align: -2px; margin-right: 3px; }
.ket { font-size: 9px; text-align: justify; }
.ttd { display: grid; grid-template-columns: repeat(var(--n, 2), 1fr); gap: 18px; margin-top: 10px; text-align: center; page-break-inside: avoid; }
.ttd span { display: block; margin-top: 42px; border-top: 1px solid #000; padding-top: 2px; }
.foot { margin-top: 8px; } .foot img { width: 100%; display: block; }
.fuel { display: flex; justify-content: space-between; border: 1px solid #000; padding: 3px 6px; font-weight: 700; }
.garis { border-bottom: 1px solid #000; height: 17px; }
.pecah { page-break-before: always; }
.total td { font-weight: 700; background: #f2f4f7; }
@media screen { body { background: #e9edf2; padding: 62px 0 20px; } .hal { background: #fff; padding: 10mm 11mm; box-shadow: 0 2px 12px rgba(0,0,0,.15); width: 210mm; margin-bottom: 14px; }
  .bar { position: fixed; top: 0; left: 0; right: 0; background: #0f172a; color: #fff; padding: 10px 16px; display: flex; gap: 10px; align-items: center; font: 13px "Segoe UI", Arial, sans-serif; z-index: 9; }
  .bar button, .bar a { background: #0e7c86; color: #fff; border: 0; padding: 7px 14px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; font-size: 13px; }
  .bar a { background: #334155; } .bar span { opacity: .8; margin-left: auto; } }
@media print { .bar { display: none; } }
</style></head>
<body>
<div class="bar">
    <button onclick="window.print()">Cetak</button>
    <?php foreach (array('ringkasan' => 'Laporan lengkap', 'preorder' => 'Pre Order', 'estimasi' => 'Estimasi', 'wo' => 'Work Order', 'jobtime' => 'Job Time', 'workshop' => 'WOR') as $k => $l) if ($k !== $jenis) { ?><a href="<?php echo site_url('ReportService/cetak/' . $k . '/' . rawurlencode($r->wo_no)); ?>"><?php echo $l; ?></a><?php } ?>
    <span>WO <?php echo $e($r->wo_no); ?> · A4 · skala 100%</span>
</div>
<?php
$kop = function ($judul2 = null) use ($apl, $base, $judul, $e, $r, $tg) { ?>
<div class="kop">
    <div class="judul"><b><?php echo $judul2 ?: $judul; ?></b><span>No. WO <?php echo $e($r->wo_no); ?> · <?php echo $tg($r->date_open_wo); ?></span></div>
    <div class="logo"><img src="<?php echo $base; ?>assets/dist/img/logo_mercedes.png" alt="" onerror="this.style.display='none'"></div>
    <div class="pt"><b><?php echo $e($apl ? $apl->nama_owner : ''); ?></b><br><?php echo $e($apl ? $apl->status : ''); ?><br><?php echo $e($apl ? trim($apl->alamat . ', ' . $apl->kota) : ''); ?><?php echo $apl && $apl->tlp ? '<br>Telp. ' . $e($apl->tlp) : ''; ?></div>
</div>
<?php };
$footer = function () use ($apl, $base) { if ($apl && trim((string) $apl->footer) !== '') { ?><div class="foot"><img src="<?php echo $base; ?>assets/foto/logo/<?php echo rawurlencode($apl->footer); ?>" alt="" onerror="this.parentNode.style.display='none'"></div><?php } };
$pelanggan = function () use ($c, $r, $e, $tg) { ?>
<div class="dua">
    <table class="kv">
        <tr><td>Customer name</td><td class="b">: <?php echo $e($c ? $c->nama_cus : $r->customer_name); ?></td></tr>
        <tr><td>Address</td><td>: <?php echo $e($c ? $c->alamat : ''); ?></td></tr>
        <tr><td>City</td><td>: <?php echo $e($c ? $c->kota : ''); ?></td></tr>
        <tr><td>Up</td><td>: <?php echo $e($c ? $c->tlp_person : ''); ?></td></tr>
        <tr><td>Telp / mobile</td><td>: <?php echo $e($c ? $c->no_tlp : ''); ?></td></tr>
    </table>
    <table class="ref">
        <tr><td colspan="3" class="b">For reference please quote the following no.</td></tr>
        <tr><td style="width:34%">Order no.</td><td colspan="2" class="b" style="font-size:13px"><?php echo $e($r->wo_no); ?></td></tr>
        <tr><td><span class="lbl">Date</span><?php echo $tg($r->date_open_wo); ?></td><td><span class="lbl">Customer no.</span><?php echo $e($r->customer); ?></td><td><span class="lbl">Jenis</span><?php echo $e(Mod_lapservice::$CIW[$r->status_service] ?? $r->status_service); ?><?php echo $r->free_service === 'Y' ? ' · FREE' : ''; ?></td></tr>
    </table>
</div>
<?php };
$kendaraan = function ($aktif = true) use ($r, $e, $tg, $jm, $kotak) { ?>
<table class="g" style="margin-top:6px">
    <tr><td><span class="lbl">Registration no.</span><span class="val"><?php echo $e($r->no_pol); ?></span></td><td><span class="lbl">VIN no.</span><span class="val"><?php echo $e($r->vin); ?></span></td><td><span class="lbl">Sales designation</span><span class="val"><?php echo $e($r->type); ?></span></td><td><span class="lbl">Reception date / time</span><span class="val"><?php echo $tg($r->date_open_wo) . ' ' . $jm($r->clockin); ?></span></td></tr>
    <tr><td><span class="lbl">Mileage / km</span><span class="val"><?php echo $e($r->mileage); ?></span></td><td><span class="lbl">Engine no.</span><span class="val"><?php echo $e($r->engine_no); ?></span></td><td><span class="lbl">Active reception</span><span class="val"><?php echo $aktif ? $kotak(false) . 'Yes &nbsp; ' . $kotak(false) . 'No' : ''; ?></span></td><td><span class="lbl">Received by</span><span class="val"><?php echo $e($r->sa_name); ?></span></td></tr>
    <tr><td><span class="lbl">Routing no.</span><span class="val">&nbsp;</span></td><td><span class="lbl">Last service date / km</span><span class="val"><?php echo $tg($r->last_service_date); ?></span></td><td><span class="lbl">Date of 1st registration</span><span class="val">&nbsp;</span></td><td><span class="lbl">Deadline / time</span><span class="val"><?php echo $tg($r->dead_line) . ' ' . $jm($r->dead_time); ?></span></td></tr>
</table>
<?php };
$operasi = function ($rows, $kolomJam = 'hours') use ($e, $blank) { ?>
<table class="g" style="margin-top:4px">
    <tr><th class="c" style="width:7%">Item</th><th class="c" style="width:18%">Operation no.</th><th class="c" style="width:9%">Hours</th><th>Type of work</th></tr>
    <?php $i = 0; foreach ($rows as $d) { ?><tr><td class="c"><?php echo ++$i; ?></td><?php if (trim((string) $d->type_of_work) === '' && mb_strlen(trim((string) $d->operation)) > 14) { ?><td colspan="3"><?php echo $e($d->operation); ?></td></tr><?php } else { ?><td class="c"><?php echo $e($d->operation); ?></td><td class="c"><?php echo $e(isset($d->jumlah) && $kolomJam === 'jumlah' ? $d->jumlah : $d->hours); ?></td><td><?php echo $e($d->type_of_work); ?></td></tr><?php } ?><?php } ?>
    <?php echo $blank(max(3, 8 - $i), 4); ?>
</table>
<?php };
?>

<?php if ($jenis === 'wo') { ?>
<div class="hal">
    <?php $kop(); $pelanggan(); $kendaraan(); ?>
    <div style="margin:4px 0">Is this vehicle related to campaign <?php echo $kotak(false); ?>No <?php echo $kotak(false); ?>Yes &nbsp;&nbsp; Mobility service <?php echo $kotak(false); ?>No <?php echo $kotak(false); ?>Yes &nbsp;&nbsp; Storing <?php echo $kotak($r->storing === 'Y'); ?></div>
    <?php if (trim((string) $r->customer_complain) !== '') { ?><table class="g"><tr><th>Customer complaint</th></tr><tr><td><?php echo nl2br($e($r->customer_complain)); ?></td></tr></table><?php } ?>
    <?php $operasi($r->op_wo ?: $r->op_pre, $r->op_wo ? 'jumlah' : 'hours'); ?>
    <table style="margin-top:6px"><tr><td class="ket" style="width:50%;padding-right:8px;vertical-align:top"><b>Ketentuan:</b><br>
        1. Lembar Work Order yang sudah ditandatangani merupakan surat kuasa dari pelanggan kepada dealer untuk melakukan pekerjaan sesuai yang tertera di Work Order, serta memberi wewenang untuk melakukan uji coba kendaraan di luar bengkel.<br>
        2. Kendaraan yang sudah selesai diperbaiki wajib diambil oleh pelanggan dalam waktu ...... × 24 jam dari waktu pemberitahuan. Apabila melebihi waktu tersebut, dealer tidak bertanggung jawab atas kondisi kendaraan dan dikenakan biaya Rp .................... / hari.</td>
        <td class="ket" style="vertical-align:top">3. Dealer tidak bertanggung jawab atas segala kerugian akibat musibah, bencana alam, ataupun kejadian lain yang tidak dapat diprediksi atau di luar tanggung jawab dealer.<br>
        4. Tidak diperkenankan menyimpan barang berharga di dalam kendaraan. Dealer tidak bertanggung jawab apabila terjadi kehilangan atas barang tersebut.<br>
        5. Hal lain di luar ketentuan ini diatur tersendiri oleh masing-masing dealer.</td></tr></table>
    <div class="ttd"><div>Customer's signature &amp; name<span>( ................................ )</span></div><div>Service Adviser's signature &amp; name<span><?php echo $e($r->sa_name ?: $oleh); ?></span></div></div>
    <?php $footer(); ?>
</div>

<?php } elseif ($jenis === 'preorder') { $p = $r->pre; $v = function ($k) use ($p) { return $p && isset($p->$k) && $p->$k === 'Y'; }; ?>
<div class="hal">
    <?php $kop($p ? 'PRE ORDER ' . $e($p->no_pre_order) : null); $pelanggan(); $kendaraan(); ?>
    <?php if (trim((string) $r->customer_complain) !== '') { ?><table class="g" style="margin-top:4px"><tr><th>Customer complaint</th></tr><tr><td><?php echo nl2br($e($r->customer_complain)); ?></td></tr></table><?php } ?>
    <?php $operasi($r->op_pre); ?>
    <div class="dua" style="margin-top:6px;grid-template-columns:1fr 1fr">
        <table class="g">
            <tr><th colspan="2">Standard checking</th></tr>
            <?php foreach (array('leakage' => 'Leakage', 'abnormal_noise' => 'Abnormal noise', 'error_code' => 'Error code / indicator', 'brake' => 'Brake, clutch & tire 10 minutes cycle check', 'vtk' => 'Vehicle tool kit') as $k => $l) { ?><tr><td><?php echo $l; ?></td><td class="c" style="width:16%"><?php echo $kotak($v($k)); ?></td></tr><?php } ?>
        </table>
        <table class="g">
            <tr><th colspan="2">Valuable item missing / kelengkapan</th></tr>
            <?php foreach (array('fak' => 'First aid kit', 'spare_kit' => 'Spare kit', 'stnk' => 'STNK', 'manual' => 'Operational manual') as $k => $l) { ?><tr><td><?php echo $l; ?></td><td class="c" style="width:16%"><?php echo $kotak($v($k)); ?></td></tr><?php } ?>
            <tr><td colspan="2"><span class="lbl">Fuel level</span><div class="fuel"><span>E</span><span>¼</span><span>½</span><span>¾</span><span>F</span></div></td></tr>
        </table>
    </div>
    <table style="margin-top:6px"><tr>
        <td style="width:28%;vertical-align:top;padding-right:8px"><img src="<?php echo $base; ?>assets/foto/logo/<?php echo $p && $p->vehicle_type === 'BUS' ? 'bustop.png' : 'trucktop.png'; ?>" alt="" style="width:100%" onerror="this.style.display='none'">
            <div class="ket">X : stone chipping &nbsp; O : dent &nbsp; ∧ : scratches</div></td>
        <td style="vertical-align:top"><b>Others, please specify:</b><div class="garis"></div><div class="garis"></div><div class="garis"></div><div class="garis"></div></td>
    </tr></table>
    <div class="ttd"><div>This order is placed in accordance with our general terms of business<br>Customer's signature &amp; name<span>( ................................ )</span></div><div><br>Service Adviser's signature &amp; name<span><?php echo $e($r->sa_name ?: $oleh); ?></span></div></div>
    <?php $footer(); ?>
</div>

<?php } elseif ($jenis === 'estimasi') { $k = $r->est; ?>
<div class="hal">
    <?php $kop($k ? 'ESTIMASI PENAWARAN ' . $e($k->kode_estimasi_penawaran) : null); $pelanggan(); ?>
    <?php if ($k) { ?>
    <table class="g" style="margin-top:6px">
        <tr><td><span class="lbl">Registration no.</span><span class="val"><?php echo $e($k->no_reg ?: $r->no_pol); ?></span></td><td><span class="lbl">VIN no.</span><span class="val"><?php echo $e($k->no_vin ?: $r->vin); ?></span></td><td><span class="lbl">Sales designation</span><span class="val"><?php echo $e($k->sales_design ?: $r->type); ?></span></td><td><span class="lbl">Date / time received</span><span class="val"><?php echo $e($k->date_received); ?></span></td></tr>
        <tr><td><span class="lbl">Mileage / km</span><span class="val"><?php echo $e($k->millage ?: $r->mileage); ?></span></td><td><span class="lbl">Engine no.</span><span class="val"><?php echo $e($k->engine_no ?: $r->engine_no); ?></span></td><td><span class="lbl">Account no.</span><span class="val"><?php echo $e($k->acc_no); ?></span></td><td><span class="lbl">Received by</span><span class="val"><?php echo $e($k->received_by ?: $r->sa_name); ?></span></td></tr>
        <tr><td><span class="lbl">Routing no.</span><span class="val"><?php echo $e($k->routing_no); ?></span></td><td><span class="lbl">Last service / km</span><span class="val"><?php echo $e($k->last_km); ?></span></td><td><span class="lbl">Date of 1st registration</span><span class="val"><?php echo $e($k->date_of_regis); ?></span></td><td><span class="lbl">Tanggal estimasi</span><span class="val"><?php echo $tg($k->tgl_estimasi_penawaran); ?></span></td></tr>
    </table>
    <?php } else $kendaraan(false); ?>
    <table class="g" style="margin-top:6px">
        <tr><th class="c" style="width:5%">No</th><th style="width:16%">Part number</th><th>Description</th><th class="r" style="width:12%">Price</th><th class="c" style="width:7%">Qty</th><th class="r" style="width:13%">Amount</th><th style="width:13%">Remarks</th></tr>
        <?php $i = 0; $sub = 0; foreach ($r->est_det as $d) { $amt = (float) $d->harga_net * (float) $d->jumlah; $sub += $amt; ?>
        <tr><td class="c"><?php echo ++$i; ?></td><td><?php echo $e($d->no_part); ?></td><td><?php echo $e($d->nama_part); ?></td><td class="r"><?php echo $rp($d->harga_net); ?></td><td class="c"><?php echo $e($d->jumlah); ?></td><td class="r"><?php echo $rp($amt); ?></td><td><?php echo $e($d->remark); ?></td></tr>
        <?php } echo $blank(max(2, 6 - $i), 7); foreach ($r->est_note as $n) { ?><tr><td></td><td></td><td colspan="5" style="font-size:9px">** <?php echo $e($n->remark); ?></td></tr><?php }
        $ppn = $k ? (float) $k->ppn : 0; ?>
        <tr class="total"><td colspan="5" class="r">Subtotal</td><td class="r"><?php echo $rp($sub); ?></td><td></td></tr>
        <tr class="total"><td colspan="5" class="r">PPN <?php echo $ppn ? rtrim(rtrim(number_format($ppn, 2, ',', '.'), '0'), ',') . '%' : ''; ?></td><td class="r"><?php echo $rp($sub * $ppn / 100); ?></td><td></td></tr>
        <tr class="total"><td colspan="5" class="r">TOTAL</td><td class="r"><?php echo $rp($sub * (1 + $ppn / 100)); ?></td><td></td></tr>
    </table>
    <?php if ( ! $r->est_det) { ?><p class="ket">Belum ada data estimasi penawaran untuk WO ini (menu Estimasi Penawaran Service).</p><?php } ?>
    <p class="ket">Harga dapat berubah sewaktu-waktu. Estimasi berlaku 14 hari sejak tanggal diterbitkan, kecuali disepakati lain.</p>
    <div class="ttd"><div>Disetujui pelanggan<span>( ................................ )</span></div><div>Hormat kami,<span><?php echo $e(($k ? $k->user : '') ?: $oleh); ?></span></div></div>
    <?php $footer(); ?>
</div>

<?php } elseif ($jenis === 'jobtime') {
    // satu kartu per mekanik: operasi dengan SPK yang sama, atau semua operasi bila tidak ada pemetaan
    $kartu = array();
    foreach ($r->labor as $l) { $k = trim($l->nik . '|' . $l->nama); if ( ! isset($kartu[$k])) $kartu[$k] = array('l' => $l, 'spk' => array()); if ($l->spk) $kartu[$k]['spk'][$l->spk] = true; }
    if ( ! $kartu) $kartu[''] = array('l' => (object) array('nik' => '', 'nama' => ''), 'spk' => array());
    $n = 0;
    foreach ($kartu as $kk) { $l = $kk['l']; $mine = array();
        foreach ($r->op_wo as $o) if ( ! $kk['spk'] || isset($kk['spk'][$o->spk])) $mine[] = $o;
        if ( ! $mine) $mine = $r->op_wo; $tot = array('C' => 0, 'I' => 0, 'W' => 0, 'GW' => 0); $all = 0; ?>
<div class="hal <?php echo $n++ ? 'pecah' : ''; ?>">
    <?php $kop('JOB TIME CARD'); ?>
    <table class="g"><tr><td style="width:50%"><span class="lbl">Mechanic name</span><span class="val"><?php echo $e(trim($l->nik . '  ' . $l->nama)) ?: '&nbsp;'; ?></span></td><td><span class="lbl">WIP number</span><span class="val"><?php echo $e($r->wo_no); ?></span></td><td><span class="lbl">Reg. no / type</span><span class="val"><?php echo $e($r->no_pol . ' · ' . $r->type); ?></span></td></tr></table>
    <p style="margin:4px 0"><i>This card must be punched together with the workshop order.</i></p>
    <table class="g">
        <tr><th class="c" style="width:9%">CIW/GW</th><th>Operational code / description</th><th class="c" style="width:4%"></th><th class="c" style="width:13%">Time recording</th><th class="c" style="width:13%">Date</th><th class="r" style="width:11%">Total time</th></tr>
        <?php foreach ($mine as $o) { $t = (float) str_replace(',', '.', $o->total_time); $tot[isset($tot[$r->status_service]) ? $r->status_service : 'C'] += $t; $all += $t; ?>
        <tr><td class="c b"><?php echo $e($r->status_service); ?></td><td><b><?php echo $e($o->operation); ?></b><br><?php echo $e($o->type_of_work); ?></td><td class="c">S<br>E</td>
            <td class="c"><?php echo Mod_lapservice::tgl_ok($o->start_date) ? date('H:i', strtotime($o->start_date)) : ''; ?><br><?php echo Mod_lapservice::tgl_ok($o->end_date) ? date('H:i', strtotime($o->end_date)) : ''; ?></td>
            <td class="c"><?php echo $tg($o->start_date); ?><br><?php echo $tg($o->end_date); ?></td><td class="r"><?php echo number_format($t, 2, ',', '.'); ?></td></tr>
        <?php } echo $blank(max(2, 6 - count($mine)), 6); ?>
    </table>
    <table class="g" style="margin-top:6px">
        <tr><th>Hours</th><th class="c">C</th><th class="c">I</th><th class="c">W/G</th><th class="c">W1</th><th class="c">W2</th><th class="c">W3</th><th class="c">W4</th><th class="c">W5</th><th class="c">W6</th><th class="r">Total</th></tr>
        <tr><th>Total</th><td class="c"><?php echo $tot['C'] ? number_format($tot['C'], 2, ',', '.') : ''; ?></td><td class="c"><?php echo $tot['I'] ? number_format($tot['I'], 2, ',', '.') : ''; ?></td><td class="c"><?php echo $tot['W'] + $tot['GW'] ? number_format($tot['W'] + $tot['GW'], 2, ',', '.') : ''; ?></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="r b"><?php echo number_format($all, 2, ',', '.'); ?></td></tr>
    </table>
    <div class="ttd" style="--n:3"><div>Mekanik<span><?php echo $e($l->nama) ?: '( ........................ )'; ?></span></div><div>Foreman<span>( ........................ )</span></div><div>Service Advisor<span><?php echo $e($r->sa_name); ?></span></div></div>
    <?php $footer(); ?>
</div>
<?php } ?>

<?php } elseif ($jenis === 'workshop') { $mek = array(); foreach ($r->labor as $l) $mek[trim((string) $l->nama)] = true; ?>
<div class="hal">
    <?php $kop('WORKSHOP REPORT'); ?>
    <table class="g">
        <tr><td><span class="lbl">Date</span><span class="val"><?php echo $tg($r->date_open_wo); ?></span></td><td><span class="lbl">WIP number</span><span class="val"><?php echo $e($r->wo_no); ?></span></td><td colspan="2"><span class="lbl">Customer name</span><span class="val"><?php echo $e($r->customer_name); ?></span></td></tr>
        <tr><td><span class="lbl">Chassis number</span><span class="val"><?php echo $e($r->vin); ?></span></td><td><span class="lbl">Engine number</span><span class="val"><?php echo $e($r->engine_no); ?></span></td><td><span class="lbl">Reg. number</span><span class="val"><?php echo $e($r->no_pol); ?></span></td><td><span class="lbl">Type / mileage</span><span class="val"><?php echo $e($r->type . ' · ' . $r->mileage . ' km'); ?></span></td></tr>
    </table>
    <table class="g" style="margin-top:6px"><tr><th>Customer complaint</th></tr><tr><td style="height:52px">
        <?php echo nl2br($e($r->customer_complain)); ?>
        <?php if ($r->op_pre || $r->op_wo) { ?><ul style="margin:3px 0 0 16px;padding:0"><?php foreach (($r->op_pre ?: $r->op_wo) as $o) { ?><li><?php echo $e($o->operation . ' — ' . $o->type_of_work); ?></li><?php } ?></ul><?php } ?>
    </td></tr></table>
    <table class="g" style="margin-top:6px"><tr><th colspan="2">Supporting document</th></tr>
        <tr><td style="width:28%">TIPS document number</td><td>&nbsp;</td></tr><tr><td>WIS document number</td><td>&nbsp;</td></tr><tr><td>SD media</td><td>&nbsp;</td></tr>
        <tr><td>Spare parts number</td><td><?php echo $e(implode(', ', array_map(function ($d) { return trim($d->no_part); }, array_slice($r->est_det, 0, 12)))); ?></td></tr></table>
    <table class="g" style="margin-top:6px"><tr><th colspan="4">Measurement type / guidance test</th></tr>
        <tr><td style="width:28%"></td><td class="c" style="width:18%">Spesifikasi</td><td class="c" style="width:18%">Aktual</td><td rowspan="4"></td></tr>
        <tr><td style="height:24px">Pengukuran mekanis</td><td></td><td></td></tr><tr><td style="height:24px">Pengukuran elektrik</td><td></td><td></td></tr><tr><td style="height:24px">Print out DAS / Xentry</td><td></td><td></td></tr>
        <tr><td colspan="4" style="height:60px"></td></tr></table>
    <table class="g" style="margin-top:6px"><tr><th colspan="4">Workshop findings</th></tr>
        <tr><td style="width:28%"></td><td class="c" style="width:18%">Ada</td><td class="c" style="width:18%">Tidak ada</td><td rowspan="3"></td></tr>
        <tr><td style="height:22px">Photo (# lembar)</td><td></td><td></td></tr><tr><td style="height:22px">Video (nama file)</td><td></td><td></td></tr>
        <tr><td colspan="4" style="height:60px;vertical-align:top">Note:</td></tr></table>
    <table class="g" style="margin-top:6px"><tr><th>Rectification</th></tr><tr><td style="height:70px;vertical-align:top">
        <?php foreach ($r->op_wo as $o) echo $e($o->operation . ' — ' . $o->type_of_work) . '<br>'; ?></td></tr></table>
    <div class="ttd"><div>Supervisor / Foreman<span>( ................................ )</span></div><div>Mekanik<span><?php echo $e(implode(', ', array_filter(array_keys($mek)))) ?: '( ................................ )'; ?></span></div></div>
    <?php $footer(); ?>
</div>

<?php } else { // ringkasan
    $jasa = 0; $jam = 0; foreach ($r->op_wo as $o) { $jasa += (float) $o->total_harga; $jam += (float) $o->jumlah; }
    $part = 0; foreach ($r->est_det as $d) $part += (float) $d->harga_net * (float) $d->jumlah;
    $st = Mod_lapservice::$STATUS[$r->status] ?? $r->status; ?>
<div class="hal">
    <?php $kop('LAPORAN SERVICE'); $pelanggan(); $kendaraan(false); ?>
    <h4>Riwayat proses</h4>
    <table class="g">
        <tr><th>Appointment</th><th>Pre Order</th><th>Estimasi</th><th>Work Order</th><th>Selesai</th><th>Status</th></tr>
        <tr><td><?php echo $tg($r->date_open_wo) . ' ' . $jm($r->clockin); ?><br><small>SA <?php echo $e($r->sa_name); ?></small></td>
            <td><?php echo $r->pre ? $e($r->pre->no_pre_order) : '-'; ?></td>
            <td><?php echo $r->est ? $e($r->est->kode_estimasi_penawaran) . '<br><small>' . $tg($r->est->tgl_estimasi_penawaran) . '</small>' : '-'; ?></td>
            <td><?php echo $r->wo_doc ? $e($r->wo_doc->no_work_order) . '<br><small>' . $tg($r->wo_doc->wo_date) . '</small>' : '-'; ?></td>
            <td><?php echo $tg($r->date_close_wo) . ' ' . $jm($r->clockout); ?></td><td class="b"><?php echo $e($st); ?> · <?php echo $e($ciw); ?><?php echo $r->free_service === 'Y' ? ' · FREE' : ''; ?></td></tr>
    </table>
    <h4>Keluhan pelanggan</h4>
    <table class="g"><tr><td><?php echo nl2br($e($r->customer_complain)) ?: '-'; ?><?php echo trim((string) $r->remarks) !== '' ? '<br><small>Catatan: ' . $e($r->remarks) . '</small>' : ''; ?></td></tr></table>
    <h4>Pekerjaan (operasi)</h4>
    <table class="g">
        <tr><th style="width:5%">No</th><th style="width:15%">Operation</th><th>Type of work</th><th class="c" style="width:8%">Jam</th><th class="c" style="width:14%">Mulai – selesai</th><th class="r" style="width:13%">Nilai jasa</th></tr>
        <?php $i = 0; foreach ($r->op_wo as $o) { ?><tr><td class="c"><?php echo ++$i; ?></td><td><?php echo $e($o->operation); ?></td><td><?php echo $e($o->type_of_work); ?></td><td class="c"><?php echo $e($o->jumlah); ?></td><td class="c" style="font-size:9px"><?php echo $tg($o->start_date) . ' – ' . $tg($o->end_date); ?></td><td class="r"><?php echo $rp($o->total_harga); ?></td></tr><?php } ?>
        <?php if ( ! $r->op_wo) { ?><tr><td colspan="6" class="c">Belum ada operasi pada work order.</td></tr><?php } ?>
        <tr class="total"><td colspan="3" class="r">Total</td><td class="c"><?php echo number_format($jam, 2, ',', '.'); ?></td><td></td><td class="r"><?php echo $rp($jasa); ?></td></tr>
    </table>
    <h4>Mekanik</h4>
    <table class="g"><tr><th>NIK</th><th>Nama</th><th>SPK</th><th>Keterangan</th></tr>
        <?php $grp = array(); foreach ($r->labor as $l) { $k = trim($l->nik . '|' . $l->nama); if ( ! isset($grp[$k])) $grp[$k] = array($l, array(), array()); if ($l->spk) $grp[$k][1][$l->spk] = 1; if (trim((string) $l->keterangan) !== '') $grp[$k][2][trim($l->keterangan)] = 1; }
        foreach ($grp as $g) { ?><tr><td><?php echo $e($g[0]->nik); ?></td><td><?php echo $e($g[0]->nama); ?></td><td style="font-size:9px"><?php echo $e(implode(', ', array_keys($g[1]))); ?></td><td><?php echo $e(implode('; ', array_keys($g[2]))); ?></td></tr><?php } if ( ! $r->labor) { ?><tr><td colspan="4" class="c">-</td></tr><?php } ?></table>
    <h4>Spare part</h4>
    <table class="g">
        <tr><th style="width:5%">No</th><th style="width:17%">Part number</th><th>Description</th><th class="c" style="width:8%">Qty</th><th class="c" style="width:9%">Keluar</th><th class="r" style="width:13%">Harga</th><th class="r" style="width:13%">Jumlah</th></tr>
        <?php $kel = array(); foreach ($r->part_keluar as $pk) $kel[trim($pk->no_part)] = ($kel[trim($pk->no_part)] ?? 0) + (float) ($pk->jml_keluar ?: $pk->jumlah);
        $i = 0; foreach ($r->est_det as $d) { ?><tr><td class="c"><?php echo ++$i; ?></td><td><?php echo $e($d->no_part); ?></td><td><?php echo $e($d->nama_part); ?></td><td class="c"><?php echo $e($d->jumlah); ?></td><td class="c"><?php echo isset($kel[trim($d->no_part)]) ? $kel[trim($d->no_part)] : '-'; ?></td><td class="r"><?php echo $rp($d->harga_net); ?></td><td class="r"><?php echo $rp((float) $d->harga_net * (float) $d->jumlah); ?></td></tr><?php } ?>
        <?php if ( ! $r->est_det) { ?><tr><td colspan="7" class="c">Tidak ada estimasi part.</td></tr><?php } ?>
        <tr class="total"><td colspan="6" class="r">Total part (estimasi)</td><td class="r"><?php echo $rp($part); ?></td></tr>
    </table>
    <table class="g" style="margin-top:6px;width:55%;margin-left:auto">
        <tr><td>Nilai jasa</td><td class="r"><?php echo $rp($jasa); ?></td></tr><tr><td>Nilai part</td><td class="r"><?php echo $rp($part); ?></td></tr>
        <tr class="total"><td>Total (sebelum pajak)</td><td class="r"><?php echo $rp($jasa + $part); ?></td></tr>
    </table>
    <div class="ttd" style="--n:3"><div>Service Advisor<span><?php echo $e($r->sa_name); ?></span></div><div>Foreman<span>( ........................ )</span></div><div>Pelanggan<span>( ........................ )</span></div></div>
    <p class="ket">Dicetak <?php echo date('d/m/Y H:i'); ?> oleh <?php echo $e($oleh); ?>.</p>
    <?php $footer(); ?>
</div>
<?php } ?>
<script>
(function () {
    var imgs = Array.prototype.slice.call(document.images);
    Promise.all(imgs.map(function (i) { return i.complete ? 0 : new Promise(function (ok) { i.addEventListener('load', ok); i.addEventListener('error', ok); }); })).then(function () { setTimeout(function () { window.print(); }, 250); });
})();
</script>
</body></html>
