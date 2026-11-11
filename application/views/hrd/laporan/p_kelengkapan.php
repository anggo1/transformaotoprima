<?php defined('BASEPATH') OR exit('No direct script access allowed'); $lengkap = count(array_filter($d['rows'], function ($x) { return ! $x->kurang; })); ?>
<?php if ($mode !== 'excel') { ?><div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Data lengkap</div><div class="acc-kpi__value"><?php echo nf($lengkap); ?> / <?php echo nf($d['n']); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Tanpa NPWP</div><div class="acc-kpi__value text-warning"><?php echo nf($d['per_kolom']['NPWP']); ?></div><div class="acc-kpi__sub">tarif PPh 21 lebih tinggi 20% bila tanpa NPWP/NIK terpadan</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Tanpa BPJS/Jamsostek</div><div class="acc-kpi__value text-warning"><?php echo nf($d['per_kolom']['BPJS/Jamsostek']); ?></div></div>
</div><?php } ?>
<table class="table table-sm"><thead><tr><th>NIP</th><th>Nama</th><th>Departemen</th><th class="num">Kelengkapan</th><th>Belum diisi</th></tr></thead><tbody>
<?php foreach ($d['rows'] as $x) { $r = $x->r; ?><tr><td><?php echo $mode === 'screen' ? '<a href="' . site_url('Pegawai') . '">' . html_escape($r->nip) . '</a>' : html_escape($r->nip); ?></td><td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->dept); ?></td>
<td class="num"><?php echo $mode === 'excel' ? $x->persen : '<div class="wh-bar" style="min-width:90px"><span style="width:' . $x->persen . '%"></span><em>' . $x->persen . '%</em></div>'; ?></td><td class="small"><?php echo html_escape(implode(', ', $x->kurang)) ?: '<span class="text-success">Lengkap</span>'; ?></td></tr><?php } ?>
</tbody></table>
