<?php defined('BASEPATH') OR exit('No direct script access allowed'); $t = $d['total']; ?>
<table class="table table-sm table-hover">
    <thead>
        <tr>
            <th rowspan="2">Kode</th><th rowspan="2">Nama Akun</th><th rowspan="2">Tipe</th>
            <th colspan="2" class="text-center">Saldo Awal</th>
            <th colspan="2" class="text-center">Mutasi</th>
            <th colspan="2" class="text-center">Saldo Akhir</th>
        </tr>
        <tr>
            <th class="num">Debit</th><th class="num">Kredit</th>
            <th class="num">Debit</th><th class="num">Kredit</th>
            <th class="num">Debit</th><th class="num">Kredit</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($d['rows'] as $r) { ?>
        <tr>
            <td><?php echo html_escape($r->kode); ?></td>
            <td><?php if ($mode === 'screen') { ?><a href="<?php echo site_url('LaporanKeuangan/buku_besar?akun=' . rawurlencode($r->kode) . '&dari=' . $f['dari'] . '&sampai=' . $f['sampai']); ?>"><?php echo html_escape($r->nama); ?></a><?php } else echo html_escape($r->nama); ?></td>
            <td><?php echo html_escape(Akuntansi::label_kategori($r->kategori)); ?></td>
            <td class="num"><?php echo acc_val($r->awal_d, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->awal_k, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->mut_d, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->mut_k, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->akhir_d, $mode); ?></td>
            <td class="num"><?php echo acc_val($r->akhir_k, $mode); ?></td>
        </tr>
        <?php } if ( ! $d['rows']) { ?>
        <tr><td colspan="9" class="text-center text-muted">Tidak ada data pada periode ini.</td></tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr class="row-grand">
            <td colspan="3">TOTAL</td>
            <td class="num"><?php echo acc_val($t->awal_d, $mode); ?></td>
            <td class="num"><?php echo acc_val($t->awal_k, $mode); ?></td>
            <td class="num"><?php echo acc_val($t->mut_d, $mode); ?></td>
            <td class="num"><?php echo acc_val($t->mut_k, $mode); ?></td>
            <td class="num"><?php echo acc_val($t->akhir_d, $mode); ?></td>
            <td class="num"><?php echo acc_val($t->akhir_k, $mode); ?></td>
        </tr>
        <?php if ($mode !== 'excel' && (abs($t->akhir_d - $t->akhir_k) > 0.5 || abs($t->mut_d - $t->mut_k) > 0.5)) { ?>
        <tr><td colspan="9" class="text-danger"><i class="fas fa-exclamation-triangle"></i> Neraca saldo tidak seimbang (selisih saldo akhir <?php echo nf($t->akhir_d - $t->akhir_k, 2); ?>). Periksa saldo awal atau jurnal yang belum seimbang.</td></tr>
        <?php } ?>
    </tfoot>
</table>
