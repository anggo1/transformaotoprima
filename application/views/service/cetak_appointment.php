<?php defined('BASEPATH') OR exit('No direct script access allowed');
$t = function ($x) { return $x && substr((string) $x, 0, 4) !== '0000' ? tgl_id(substr($x, 0, 10)) : '-'; };
$jenis = array('C' => 'Cash', 'I' => 'Internal', 'W' => 'Warranty', 'GW' => 'Goodwill');
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Service Appointment <?php echo html_escape($r->wo_no); ?></title>
<style>
@page { size: A5 landscape; margin: 9mm; }
* { box-sizing: border-box; }
body { font-family: "Segoe UI", Arial, sans-serif; font-size: 11px; color: #111; margin: 0; }
.kop { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid #111; padding-bottom: 6px; margin-bottom: 8px; }
.kop h1 { font-size: 16px; margin: 0; letter-spacing: .02em; } .kop .no { text-align: right; } .kop .no b { font-size: 15px; }
.grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.box { border: 1px solid #999; border-radius: 4px; padding: 6px 8px; }
.box h3 { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; margin: 0 0 4px; color: #444; }
table.kv { width: 100%; border-collapse: collapse; } table.kv td { padding: 1.5px 0; vertical-align: top; } table.kv td:first-child { width: 38%; color: #444; }
.keluhan { min-height: 46px; white-space: pre-wrap; }
.tag { display: inline-block; border: 1px solid #111; border-radius: 3px; padding: 0 5px; margin-right: 4px; font-weight: 600; }
.ttd { display: flex; justify-content: space-between; gap: 20px; margin-top: 12px; } .ttd div { flex: 1; text-align: center; } .ttd span { display: block; margin-top: 40px; border-top: 1px solid #111; padding-top: 2px; }
.ket { font-size: 9.5px; color: #555; margin-top: 6px; }
@media screen { body { max-width: 210mm; margin: 14px auto; padding: 10px; background: #fff; } .bar { position: fixed; right: 14px; bottom: 14px; } .bar button { padding: 8px 14px; border: 0; border-radius: 6px; background: #0e7c86; color: #fff; font-weight: 600; cursor: pointer; } }
@media print { .bar { display: none; } }
</style></head>
<body>
<div class="bar"><button onclick="window.print()">Cetak</button></div>
<div class="kop">
    <div><h1>SERVICE APPOINTMENT</h1><div><?php echo html_escape($apl ? $apl->nama_owner : ''); ?></div></div>
    <div class="no">No. WO <b><?php echo html_escape($r->wo_no); ?></b><br><?php echo $t($r->date_open_wo); ?> · <?php echo html_escape(substr((string) $r->clockin, 0, 5)); ?></div>
</div>
<div class="grid">
    <div class="box"><h3>Pelanggan</h3><table class="kv">
        <tr><td>Nama</td><td><b><?php echo html_escape($r->customer_name); ?></b></td></tr>
        <tr><td>Kode</td><td><?php echo html_escape($r->customer); ?></td></tr>
        <tr><td>Alamat</td><td><?php echo html_escape($cus ? $cus->alamat : ''); ?></td></tr>
        <tr><td>Kontak</td><td><?php echo html_escape($cus ? trim($cus->tlp_person . ' ' . $cus->no_tlp) : ''); ?></td></tr>
    </table></div>
    <div class="box"><h3>Kendaraan</h3><table class="kv">
        <tr><td>No. polisi</td><td><b><?php echo html_escape($r->no_pol); ?></b></td></tr>
        <tr><td>Tipe</td><td><?php echo html_escape($r->type); ?></td></tr>
        <tr><td>VIN / no. mesin</td><td><?php echo html_escape($r->vin); ?> / <?php echo html_escape($r->engine_no); ?></td></tr>
        <tr><td>Kilometer · service terakhir</td><td><?php echo html_escape($r->mileage); ?> km · <?php echo $t($r->last_service_date); ?></td></tr>
    </table></div>
</div>
<div class="box" style="margin-top:8px"><h3>Keluhan / permintaan pelanggan</h3><div class="keluhan"><?php echo html_escape($r->customer_complain); ?></div>
    <?php if (trim((string) $r->remarks) !== '') { ?><div class="ket">Catatan: <?php echo html_escape($r->remarks); ?></div><?php } ?></div>
<div class="grid" style="margin-top:8px">
    <div class="box"><h3>Layanan</h3>
        <span class="tag"><?php echo $r->free_service === 'Y' ? 'FREE SERVICE' : 'NON FREE'; ?></span>
        <span class="tag"><?php echo html_escape($jenis[$r->status_service] ?? $r->status_service); ?></span>
        <?php if ($r->storing === 'Y') { ?><span class="tag">STORING</span><?php } ?>
    </div>
    <div class="box"><h3>Target selesai</h3><b><?php echo $t($r->dead_line); ?></b> <?php echo html_escape(substr((string) $r->dead_time, 0, 5)); ?> · SA: <?php echo html_escape($r->sa_name); ?></div>
</div>
<div class="ttd"><div>Pelanggan<span>( ............................ )</span></div><div>Service Advisor<span><?php echo html_escape($r->sa_name); ?></span></div></div>
<div class="ket">Harap tunjukkan slip ini saat pengambilan kendaraan. Estimasi waktu & biaya dapat berubah setelah pemeriksaan (Pre Order). Dicetak <?php echo date('d/m/Y H:i'); ?> oleh <?php echo html_escape((string) $this->session->userdata('full_name')); ?>.</div>
<script>window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 300); });</script>
</body></html>
