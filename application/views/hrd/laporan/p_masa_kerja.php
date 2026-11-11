<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($mode !== 'excel') { ?><div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Rata-rata masa kerja</div><div class="acc-kpi__value"><?php echo nf($d['rata'], 1); ?> th</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Ulang tahun kerja kelipatan 5 (12 bln ke depan)</div><div class="acc-kpi__value"><?php echo nf(count($d['milestone'])); ?></div><div class="acc-kpi__sub">kandidat penghargaan masa kerja</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Tanggal masuk belum diisi</div><div class="acc-kpi__value <?php echo $d['kelompok']['Belum diisi'] ? 'text-warning' : ''; ?>"><?php echo nf($d['kelompok']['Belum diisi']); ?></div></div>
</div><?php } ?>
<?php if ($d['milestone']) { ?>
<h6>Penghargaan masa kerja (12 bulan ke depan)</h6>
<table class="table table-sm"><thead><tr><th>NIP</th><th>Nama</th><th>Departemen</th><th>Tanggal masuk</th><th>Genap</th><th>Pada tanggal</th></tr></thead><tbody>
<?php foreach ($d['milestone'] as $m) if ($m->tgl <= date('Y-m-d', strtotime('+1 year'))) { ?><tr><td><?php echo html_escape($m->r->nip); ?></td><td><?php echo html_escape($m->r->nama); ?></td><td><?php echo html_escape($m->r->dept); ?></td><td><?php echo tgl_singkat($m->r->tgl_masuk); ?></td><td><b><?php echo $m->tahun; ?> tahun</b></td><td><?php echo tgl_singkat($m->tgl); ?></td></tr><?php } ?>
</tbody></table>
<?php } ?>
<h6>Daftar pegawai (masa kerja terlama di atas)</h6>
<table class="table table-sm"><thead><tr><th>NIP</th><th>Nama</th><th>Departemen</th><th>Jabatan</th><th>Tanggal masuk</th><th class="num">Masa kerja</th><th>Status kerja</th></tr></thead><tbody>
<?php foreach ($d['rows'] as $r) { ?><tr><td><?php echo html_escape($r->nip); ?></td><td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->dept); ?></td><td><?php echo html_escape($r->jab); ?></td>
<td><?php echo Mod_laphr::tgl_ok($r->tgl_masuk) ? ($mode === 'excel' ? $r->tgl_masuk : tgl_singkat($r->tgl_masuk)) : '<span class="text-warning">belum diisi</span>'; ?></td>
<td class="num"><?php echo $r->masa === null ? '-' : floor($r->masa) . ' th ' . floor(($r->masa - floor($r->masa)) * 12) . ' bl'; ?></td><td><?php echo html_escape($r->status_kerja); ?></td></tr><?php } ?>
</tbody></table>
