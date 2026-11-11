<?php defined('BASEPATH') OR exit('No direct script access allowed'); $tot = array_sum($d['bulan']); ?>
<?php if ($mode !== 'excel') { ?><div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Total hari cuti</div><div class="acc-kpi__value"><?php echo nf($tot); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Pegawai mengambil cuti</div><div class="acc-kpi__value"><?php echo nf(count($d['rows'])); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Rata-rata per pegawai</div><div class="acc-kpi__value"><?php echo count($d['rows']) ? nf($tot / count($d['rows']), 1) : 0; ?> hari</div><div class="acc-kpi__sub">hak cuti tahunan minimal 12 hari (UU Ketenagakerjaan)</div></div>
</div><?php } ?>
<table class="table table-sm"><thead><tr><th>NIP</th><th>Nama</th><th>Departemen</th><?php for ($b = 1; $b <= 12; $b++) { ?><th class="num"><?php echo bulan_singkat($b); ?></th><?php } ?><th class="num">Total</th><th class="num">Sisa (12)</th><th>Jenis</th></tr></thead><tbody>
<?php foreach ($d['rows'] as $r) { ?><tr><td><?php echo html_escape($r->nip); ?></td><td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->dept); ?></td>
<?php for ($b = 1; $b <= 12; $b++) { ?><td class="num"><?php echo $r->bulan[$b] ?: ''; ?></td><?php } ?><td class="num"><b><?php echo $r->hari; ?></b></td><td class="num <?php echo $r->hari > 12 ? 'text-danger' : ''; ?>"><?php echo 12 - $r->hari; ?></td>
<td class="small"><?php $x = array(); foreach ($r->jenis as $j => $n) $x[] = $j . ' ' . $n; echo html_escape(implode(', ', $x)); ?></td></tr><?php } ?>
<?php if ( ! $d['rows']) { ?><tr><td colspan="18" class="text-center text-muted">Belum ada cuti tercatat di tahun ini (menu HRMS → Cuti).</td></tr><?php } ?>
</tbody></table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">"Sisa (12)" mengasumsikan hak cuti tahunan 12 hari; sesuaikan dengan peraturan perusahaan. Cuti khusus (sakit dengan surat dokter, melahirkan, dll.) idealnya tidak mengurangi hak cuti tahunan — bedakan dengan kode cuti di Panel Setting HRD.</p><?php } ?>
