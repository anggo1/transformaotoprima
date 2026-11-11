<?php defined('BASEPATH') OR exit('No direct script access allowed');
$P = $d['profil']; $final = $P->rezim === 'FINAL_UMKM';
$kol = array('bruto' => 'Peredaran Bruto', 'ppn_k' => 'PPN Keluaran', 'ppn_m' => 'PPN Masukan', 'ppn_kb' => 'PPN Kurang Bayar', 'ppn_lb' => 'PPN Lebih Bayar*',
    'pph21' => 'PPh 21', 'pph23' => 'PPh 23 (dipotong)', 'pph42' => 'PPh 4(2)');
if ($final) $kol['pph_final'] = 'PPh Final ' . nf($P->tarif_final, 1) . '%';
$kol['kewajiban'] = 'Total Kewajiban'; $kol['setor'] = 'Disetor';
$tot = array_fill_keys(array_keys($kol), 0.0);
$ppnRows = $d['ppn']['rows'];
?>
<table class="table table-sm">
    <thead><tr><th>Masa</th><?php foreach ($kol as $l) { ?><th class="num"><?php echo $l; ?></th><?php } ?><th>Masa PPN</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $b => $r) { foreach ($kol as $k => $l) if ($k !== 'ppn_lb') $tot[$k] += $r->$k; ?>
        <tr><td><?php echo bulan_panjang($b); ?></td>
            <?php foreach ($kol as $k => $l) { ?><td class="num"><?php echo acc_val($r->$k, $mode); ?></td><?php } ?>
            <td><?php
                $t = $ppnRows[$b]->tutup;
                if ($mode === 'excel') echo $t ?: '';
                elseif ($t) echo '<span class="acc-status acc-status--LUNAS">Ditutup</span>';
                elseif ($r->ppn_k != 0 || $r->ppn_m != 0) echo '<span class="acc-status acc-status--SEBAGIAN">Belum ditutup</span>';
                else echo '<span class="text-muted">-</span>';
            ?></td></tr>
    <?php } $tot['ppn_lb'] = $d['rows'][12]->ppn_lb; ?>
    </tbody>
    <tfoot><tr class="row-grand"><td>TOTAL <?php echo html_escape((string) $f['tahun']); ?></td><?php foreach ($kol as $k => $l) { ?><td class="num"><?php echo acc_val($tot[$k], $mode); ?></td><?php } ?><td></td></tr></tfoot>
</table>
<?php if ($mode !== 'excel') { ?>
<div class="small text-muted mt-2">
    * PPN Lebih Bayar dikompensasikan ke masa berikutnya; kolom total menampilkan sisa lebih bayar pada akhir Desember.<br>
    Total Kewajiban = PPN kurang bayar + PPh 21 + PPh 23 + PPh 4(2)<?php echo $final ? ' + PPh Final' : ''; ?> yang timbul pada masa tersebut (wajib disetor pada bulan berikutnya). Kolom Disetor = seluruh setoran pajak dengan masa tersebut (termasuk PPh 25).
</div>
<?php } ?>
