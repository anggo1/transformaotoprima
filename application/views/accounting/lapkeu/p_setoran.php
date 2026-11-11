<?php defined('BASEPATH') OR exit('No direct script access allowed');
$per = array(); $tot = 0;
foreach ($d['rows'] as $r) { if ( ! isset($per[$r->jenis])) $per[$r->jenis] = 0; $per[$r->jenis] += $r->jumlah; $tot += $r->jumlah; }
?>
<table class="table table-sm">
    <thead><tr><th>Tanggal Setor</th><th>No. Bukti</th><th>Jenis Pajak</th><th>Masa</th><th>Akun Pajak</th><th>Dibayar dari</th><th class="num">Jumlah</th><th>NTPN</th><th>Jurnal</th></tr></thead>
    <tbody>
    <?php foreach ($d['rows'] as $r) { $jp = isset(Akuntansi::$JENIS_PAJAK[$r->jenis]) ? Akuntansi::$JENIS_PAJAK[$r->jenis][0] : $r->jenis; ?>
        <tr><td><?php echo $mode === 'excel' ? $r->tanggal : tgl_singkat($r->tanggal); ?></td><td><?php echo html_escape($r->no_bukti); ?></td><td><?php echo html_escape($jp); ?></td><td><?php echo html_escape($r->masa); ?></td>
            <td><?php echo html_escape($r->kode_akun_pajak); ?></td><td><?php echo html_escape($r->kode_akun_kas); ?></td><td class="num"><?php echo acc_val($r->jumlah, $mode); ?></td>
            <td><?php echo $r->ntpn !== '' ? html_escape($r->ntpn) : ($mode === 'screen' ? '<span class="badge badge-warning">NTPN kosong</span>' : ''); ?></td><td><?php echo html_escape($r->no_ref_jurnal); ?></td></tr>
    <?php } if ( ! $d['rows']) { ?><tr><td colspan="9" class="text-center text-muted">Belum ada setoran pajak pada tahun ini.</td></tr><?php } ?>
    </tbody>
    <tfoot>
    <?php foreach ($per as $j => $v) { ?><tr class="row-total"><td colspan="6">Jumlah <?php echo html_escape(isset(Akuntansi::$JENIS_PAJAK[$j]) ? Akuntansi::$JENIS_PAJAK[$j][0] : $j); ?></td><td class="num"><?php echo acc_val($v, $mode); ?></td><td colspan="2"></td></tr><?php } ?>
        <tr class="row-grand"><td colspan="6">TOTAL SETORAN</td><td class="num"><?php echo acc_val($tot, $mode); ?></td><td colspan="2"></td></tr>
    </tfoot>
</table>
