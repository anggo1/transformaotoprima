<?php defined('BASEPATH') OR exit('No direct script access allowed');
$sec = array('AP' => array('A. Part Order ke pemasok — barang BELUM DATANG', 'Pemasok', 'Tindak lanjuti ke pemasok; pesanan > 60 hari sebaiknya dikonfirmasi atau ditutup.'),
             'AR' => array('B. PO customer — barang BELUM DIKIRIM', 'Customer', 'Siapkan barang / informasikan estimasi pengiriman ke pelanggan.'));
foreach ($sec as $j => $s) { $tn = 0; ?>
<h6 class="mt-2 mb-1" style="font-weight:700"><?php echo $s[0]; ?></h6>
<?php if ($mode !== 'excel') { ?><p class="small text-muted mb-2"><?php echo $s[2]; ?></p><?php } ?>
<table class="table table-sm mb-4">
    <thead><tr><th>No. Dokumen</th><th>Tanggal</th><th class="num">Umur (hari)</th><th><?php echo $s[1]; ?></th><th>No. Part</th><th>Nama</th><th class="num">Dipesan</th><th class="num">Diterima/Dikirim</th><th class="num">Sisa</th><th class="num">Harga</th><th class="num">Nilai Sisa</th></tr></thead>
    <tbody>
    <?php foreach ($d[$j] as $r) { $tn += $r->nilai; ?>
        <tr><td><?php echo html_escape($r->no); ?></td><td><?php echo $r->tgl && $r->tgl !== '0000-00-00' ? ($mode === 'excel' ? $r->tgl : tgl_singkat($r->tgl)) : '-'; ?></td>
            <td class="num"><?php echo $r->hari === null ? '-' : ($mode !== 'excel' && $r->hari > 60 ? '<span class="text-danger font-weight-bold">' . nf($r->hari) . '</span>' : nf($r->hari)); ?></td>
            <td><?php echo html_escape($r->kontak); ?></td><td><?php echo html_escape($r->no_part); ?></td><td><?php echo html_escape($r->nama); ?></td>
            <td class="num"><?php echo nf_qty($r->jumlah, $mode); ?></td><td class="num"><?php echo nf_qty($r->diterima, $mode); ?></td><td class="num"><b><?php echo nf_qty($r->sisa, $mode); ?></b></td>
            <td class="num"><?php echo acc_val($r->harga, $mode); ?></td><td class="num"><?php echo acc_val($r->nilai, $mode); ?></td></tr>
    <?php } if ( ! $d[$j]) { ?><tr><td colspan="11" class="text-center text-muted">Tidak ada.</td></tr><?php } ?>
    </tbody>
    <tfoot><tr class="row-total"><td colspan="10">Jumlah nilai sisa (<?php echo count($d[$j]); ?> baris)</td><td class="num"><?php echo acc_val($tn, $mode); ?></td></tr></tfoot>
</table>
<?php } ?>
