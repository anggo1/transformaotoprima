<?php defined('BASEPATH') OR exit('No direct script access allowed'); $T = $d['total']; ?>
<?php if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Item dihitung</div><div class="acc-kpi__value"><?php echo nf($T->item); ?></div><div class="acc-kpi__sub"><?php echo nf($T->selisih_item); ?> item berselisih (<?php echo $T->item ? nf($T->selisih_item / $T->item * 100, 1) : 0; ?>%)</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Fisik lebih</div><div class="acc-kpi__value"><?php echo nf($T->lebih); ?></div><div class="acc-kpi__sub">nilai <?php echo nf_acc($T->nilai_lebih); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Fisik kurang</div><div class="acc-kpi__value text-danger"><?php echo nf($T->kurang); ?></div><div class="acc-kpi__sub">nilai <?php echo nf_acc($T->nilai_kurang); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Selisih bersih</div><div class="acc-kpi__value"><?php echo nf_acc($T->nilai_lebih + $T->nilai_kurang); ?></div><div class="acc-kpi__sub">dasar jurnal penyesuaian persediaan</div></div>
</div>
<?php } ?>
<table class="table table-sm">
    <thead><tr><th>No. Part</th><th>Nama Barang</th><th>Kelompok</th><th>Rak</th><th>Sat</th><th class="num">Stok Sistem</th><th class="num">Stok Fisik</th><th class="num">Selisih</th><th class="num">Harga</th><th class="num">Nilai Selisih</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $r) { ?>
        <tr><td><?php echo html_escape($r->no_part); ?></td><td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->kelompok); ?></td><td><?php echo html_escape($r->rak); ?></td><td><?php echo html_escape($r->satuan); ?></td>
            <td class="num"><?php echo nf_qty($r->lama, $mode); ?></td><td class="num"><?php echo nf_qty($r->fisik, $mode); ?></td><td class="num"><b><?php echo nf_qty($r->selisih, $mode); ?></b></td>
            <td class="num"><?php echo acc_val($r->harga, $mode); ?></td><td class="num"><?php echo acc_val($r->nilai, $mode); ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="10" class="text-center text-muted">Tidak ada selisih pada dokumen ini.</td></tr><?php } ?>
    </tbody>
</table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">Setelah selisih disetujui, perbarui stok di Material &amp; Sparepart dan minta bagian Akuntansi membuat jurnal penyesuaian: fisik kurang → Dr Beban Selisih Persediaan / Cr Persediaan; fisik lebih → kebalikannya.</p><?php } ?>
