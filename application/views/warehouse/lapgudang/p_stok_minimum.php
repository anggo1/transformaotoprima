<?php defined('BASEPATH') OR exit('No direct script access allowed'); $T = $d['total']; ?>
<?php if ($mode !== 'excel') { ?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Perlu perhatian</div><div class="acc-kpi__value"><?php echo nf($T->n); ?></div><div class="acc-kpi__sub">barang di bawah / sama dengan minimum</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Stok habis</div><div class="acc-kpi__value text-danger"><?php echo nf($T->kosong); ?></div><div class="acc-kpi__sub">stok 0 atau minus</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Perkiraan nilai pesanan</div><div class="acc-kpi__value"><?php echo nf_acc($T->nilai_saran); ?></div><div class="acc-kpi__sub">saran × harga net</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Belum ada stok minimum</div><div class="acc-kpi__value"><?php echo nf($d['tanpa_min']); ?></div><div class="acc-kpi__sub">barang (isi di Material &amp; Sparepart)</div></div>
</div>
<?php } ?>
<table class="table table-sm">
    <thead><tr><th>No. Part</th><th>Nama Barang</th><th>Kategori</th><th>Sat</th><th class="num">Stok Min</th><th class="num">Stok</th><th class="num">Sedang Dipesan</th><th class="num">Kurang</th><th class="num">Saran Pesan</th><th class="num">Harga</th><th class="num">Nilai Saran</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $r) { ?>
        <tr><td><?php echo $mode === 'screen' ? '<a href="' . site_url('LaporanGudang/kartu_stok?part=' . rawurlencode($r->no_part) . '&lok=' . $f['lok']) . '">' . html_escape($r->no_part) . '</a>' : html_escape($r->no_part); ?></td>
            <td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->kategori); ?></td><td><?php echo html_escape($r->satuan); ?></td>
            <td class="num"><?php echo nf_qty($r->min, $mode); ?></td><td class="num"><b><?php echo nf_qty($r->stok, $mode); ?></b></td><td class="num"><?php echo nf_qty($r->dipesan, $mode); ?></td>
            <td class="num"><?php echo nf_qty($r->kurang, $mode); ?></td><td class="num"><b><?php echo nf_qty($r->saran, $mode); ?></b></td><td class="num"><?php echo acc_val($r->harga, $mode); ?></td><td class="num"><?php echo acc_val($r->nilai, $mode); ?></td>
            <td><?php echo $mode === 'excel' ? $r->status : '<span class="acc-status acc-status--' . ($r->status === 'HABIS' ? 'BATAL' : ($r->status === 'AMAN' ? 'LUNAS' : 'SEBAGIAN')) . '">' . $r->status . '</span>'; ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="12" class="text-center text-muted">Semua barang di atas stok minimum.</td></tr><?php } ?>
    </tbody>
</table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">Saran pesan = (2 × stok minimum) − stok − jumlah yang sedang dipesan (sisa Part Order ke pemasok). Sesuaikan dengan lead time pemasok dan pola pemakaian.</p><?php } ?>
