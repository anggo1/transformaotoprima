<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<table class="table table-sm"><thead><tr><th>Tanggal</th><th>NIP</th><th>Nama</th><th>Departemen</th><th>Jabatan</th><th class="num">Usia (tahun ini)</th></tr></thead><tbody>
<?php foreach ($d['rows'] as $r) { $hari = date('Y') . substr($r->tgl_lahir, 4); ?><tr class="<?php echo $hari === date('Y-m-d') ? 'table-success' : ''; ?>"><td><b><?php echo tgl_singkat($hari); ?></b></td><td><?php echo html_escape($r->nip); ?></td><td><?php echo html_escape($r->nama); ?></td><td><?php echo html_escape($r->dept); ?></td><td><?php echo html_escape($r->jab); ?></td><td class="num"><?php echo (int) date('Y') - (int) substr($r->tgl_lahir, 0, 4); ?></td></tr><?php } ?>
<?php if ( ! $d['rows']) { ?><tr><td colspan="6" class="text-center text-muted">Tidak ada pegawai yang berulang tahun di bulan ini (atau tanggal lahir belum diisi).</td></tr><?php } ?>
</tbody></table>
