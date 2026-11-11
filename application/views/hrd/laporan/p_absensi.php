<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($mode === 'screen') { ?>
<form method="get" class="form-inline small mb-2 no-print" style="gap:.4rem"><input type="hidden" name="tahun" value="<?php echo (int) $f['tahun']; ?>"><input type="hidden" name="bulan" value="<?php echo (int) $f['bulan']; ?>">
<label>Jam masuk</label><input type="time" name="jam" value="<?php echo html_escape($d['jam_masuk']); ?>" class="form-control form-control-sm"><button class="btn btn-sm btn-outline-primary">Terapkan</button></form>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Hari kerja (Sen–Jum)</div><div class="acc-kpi__value"><?php echo nf($d['hari_kerja']); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">PIN tercatat</div><div class="acc-kpi__value"><?php echo nf(count($d['rows'])); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Pegawai aktif tanpa data absen</div><div class="acc-kpi__value <?php echo $d['tanpa_absen'] ? 'text-warning' : ''; ?>"><?php echo nf(max(0, $d['tanpa_absen'])); ?></div><div class="acc-kpi__sub">cocokkan PIN mesin dengan NIP/NIPA</div></div>
</div>
<?php } ?>
<table class="table table-sm"><thead><tr><th>PIN</th><th>Nama</th><th>Departemen</th><th class="num">Hadir</th><th class="num">% hadir</th><th class="num">Terlambat</th><th class="num">Menit terlambat</th><th class="num">Rata jam kerja</th></tr></thead><tbody>
<?php foreach ($d['rows'] as $r) { ?><tr><td><?php echo html_escape($r->pin); ?></td><td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->dept); ?></td><td class="num"><?php echo nf($r->hadir); ?></td><td class="num"><?php echo nf($r->persen, 1); ?></td>
<td class="num <?php echo $r->telat > 3 ? 'text-danger' : ''; ?>"><?php echo nf($r->telat); ?></td><td class="num"><?php echo nf($r->menit_telat); ?></td><td class="num"><?php echo $r->hadir ? nf($r->jam_kerja / $r->hadir, 1) : '-'; ?></td></tr><?php } ?>
<?php if ( ! $d['rows']) { ?><tr><td colspan="8" class="text-center text-muted">Belum ada data absensi bulan ini. Tarik data dari menu HRMS → Mesin Absen.</td></tr><?php } ?>
</tbody></table>
