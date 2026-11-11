<?php defined('BASEPATH') OR exit('No direct script access allowed');
$grp = array('AR' => array(), 'AP' => array());
foreach ($d['rows'] as $r) $grp[$r->jenis][] = $r;
$judul = array(
    'AR' => array('A. PPh dipotong oleh pelanggan — KREDIT PAJAK kita', 'Pelanggan (pemotong)', 'Minta bukti potong asli (Coretax) dari pelanggan; tanpa bukti potong, kredit pajak tidak dapat diklaim di SPT Tahunan.'),
    'AP' => array('B. PPh yang kita potong dari pemasok — WAJIB DISETOR', 'Pemasok (dipotong)', 'Buat bukti potong untuk pemasok, setor paling lambat tgl 15 bulan berikutnya, dan laporkan di SPT Masa PPh Unifikasi.'),
);
foreach ($judul as $j => $J) { $t = array(0, 0); ?>
<h6 class="mt-2 mb-1" style="font-weight:700"><?php echo $J[0]; ?></h6>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-2"><?php echo $J[2]; ?></p><?php } ?>
<table class="table table-sm mb-4">
    <thead><tr><th>Tanggal</th><th>No. Pelunasan</th><th>No. Faktur</th><th><?php echo $J[1]; ?></th><th>Jenis</th><th class="num">DPP Faktur</th><th class="num">Tarif Efektif</th><th class="num">PPh Dipotong</th><th>No. Bukti Potong</th></tr></thead>
    <tbody>
    <?php foreach ($grp[$j] as $r) { $t[0] += $r->dpp; $t[1] += $r->potongan; $tarif = $r->dpp > 0 ? $r->potongan / $r->dpp * 100 : 0; ?>
        <tr><td><?php echo $mode === 'excel' ? $r->tanggal : tgl_singkat($r->tanggal); ?></td><td><?php echo html_escape($r->no_bukti); ?></td><td><?php echo html_escape($r->no_faktur); ?></td><td><?php echo html_escape($r->kontak); ?></td>
            <td><?php echo html_escape(str_replace('PPH', 'PPh ', $r->jenis_pph)); ?></td><td class="num"><?php echo acc_val($r->dpp, $mode); ?></td>
            <td class="num"><?php echo $mode === 'excel' ? round($tarif, 2) : nf($tarif, 2) . '%'; ?></td><td class="num"><?php echo acc_val($r->potongan, $mode); ?></td>
            <td><?php echo $r->no_bukti_potong !== '' ? html_escape($r->no_bukti_potong) : ($mode === 'screen' ? '<span class="badge badge-warning">belum ada</span>' : ''); ?></td></tr>
    <?php } if ( ! $grp[$j]) { ?><tr><td colspan="9" class="text-center text-muted">Tidak ada.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-total"><td colspan="5">Jumlah</td><td class="num"><?php echo acc_val($t[0], $mode); ?></td><td></td><td class="num"><?php echo acc_val($t[1], $mode); ?></td><td></td></tr></tfoot>
</table>
<?php } ?>
