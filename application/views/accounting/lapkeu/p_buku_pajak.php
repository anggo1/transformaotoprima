<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<table class="table table-sm">
    <thead><tr><th>Akun</th><th>Fungsi</th><th class="num">Saldo Awal</th><th class="num">Bertambah</th><th class="num">Berkurang</th><th class="num">Saldo Akhir</th><th>Arti saldo akhir</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $r) { $hutang = $r->saldo_normal === 'K'; ?>
        <tr<?php echo $d['rinci'] ? ' class="row-group"' : ''; ?>>
            <td><?php echo $mode === 'screen' ? '<a href="' . site_url('LaporanKeuangan/buku_besar?akun=' . rawurlencode($r->kode) . '&dari=' . $d['tahun'] . '-01-01&sampai=' . $d['tahun'] . '-12-31') . '">' . html_escape($r->kode . ' ' . $r->nama) . '</a>' : html_escape($r->kode . ' ' . $r->nama); ?></td>
            <td><?php echo html_escape($r->label); ?></td>
            <td class="num"><?php echo acc_val($r->awal, $mode); ?></td><td class="num"><?php echo acc_val($r->tambah, $mode); ?></td><td class="num"><?php echo acc_val($r->kurang, $mode); ?></td><td class="num"><?php echo acc_val($r->akhir, $mode); ?></td>
            <td class="small"><?php echo abs($r->akhir) < 0.5 ? 'Nihil' : ($hutang ? ($r->akhir > 0 ? 'Belum disetor ke kas negara' : 'Disetor melebihi kewajiban — periksa') : ($r->akhir > 0 ? 'Kredit pajak / lebih bayar yang belum dikompensasikan' : 'Periksa: saldo berlawanan')); ?></td></tr>
        <?php if ($d['rinci']) { foreach ($r->bulan as $b => $x) { if (abs($x->tambah) < 0.005 && abs($x->kurang) < 0.005) continue; ?>
        <tr class="row-sub"><td><?php echo bulan_panjang($b); ?></td><td></td><td></td><td class="num"><?php echo acc_val($x->tambah, $mode); ?></td><td class="num"><?php echo acc_val($x->kurang, $mode); ?></td><td class="num"><?php echo acc_val($x->saldo, $mode); ?></td><td></td></tr>
        <?php } } ?>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="7" class="text-center text-muted">Akun pajak belum diatur. Buka Perpajakan → Pengaturan.</td></tr><?php } ?>
    </tbody>
</table>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-0">Akun hutang pajak (saldo normal kredit): bertambah saat PPh dipotong / PPN masa ditutup, berkurang saat disetor. Akun dibayar dimuka (saldo normal debit): bertambah saat dipotong pihak lain / angsuran PPh 25 disetor, berkurang saat dikreditkan di jurnal PPh Badan akhir tahun.</p><?php } ?>
