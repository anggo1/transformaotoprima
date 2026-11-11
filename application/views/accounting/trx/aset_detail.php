<?php defined('BASEPATH') OR exit('No direct script access allowed');
$nm = function ($k) use ($akun) { return isset($akun[$k]) ? $k . ' — ' . $akun[$k]->nama_akun : $k; };
?>
<div data-modal-size="lg"></div>
<div class="modal-header"><h4 class="modal-title"><?php echo html_escape($a->kode_aset . ' · ' . $a->nama_aset); ?> <span class="acc-status acc-status--<?php echo $a->status; ?> ml-2"><?php echo $a->status; ?></span></h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
<div class="modal-body">
    <div class="row mb-3 small">
        <div class="col-sm-3"><span class="text-muted d-block">Perolehan</span><?php echo tgl_singkat($a->tgl_perolehan); ?></div>
        <div class="col-sm-3"><span class="text-muted d-block">Harga</span><?php echo nf($a->harga_perolehan, 2); ?></div>
        <div class="col-sm-3"><span class="text-muted d-block">Residu</span><?php echo nf($a->nilai_residu, 2); ?></div>
        <div class="col-sm-3"><span class="text-muted d-block">Metode / umur</span><?php echo str_replace('_', ' ', $a->metode); ?> · <?php echo (int) $a->umur_bulan; ?> bln</div>
        <div class="col-sm-4 mt-2"><span class="text-muted d-block">Akun aset</span><?php echo html_escape($nm($a->akun_aset)); ?></div>
        <div class="col-sm-4 mt-2"><span class="text-muted d-block">Akun akumulasi</span><?php echo html_escape($nm($a->akun_akumulasi)); ?></div>
        <div class="col-sm-4 mt-2"><span class="text-muted d-block">Akun beban</span><?php echo html_escape($nm($a->akun_beban)); ?></div>
        <?php if ($a->status !== 'AKTIF') { ?><div class="col-12 mt-2"><span class="text-muted">Dilepas</span> <?php echo tgl_singkat($a->tgl_lepas); ?> · nilai <?php echo nf($a->nilai_lepas, 2); ?></div><?php } ?>
    </div>
    <h6>Jadwal Penyusutan</h6>
    <div style="max-height:360px;overflow:auto">
    <table class="table table-sm"><thead><tr><th>Periode</th><th class="num">Penyusutan</th><th class="num">Akumulasi</th><th class="num">Nilai Buku</th><th>Status</th></tr></thead><tbody>
    <?php foreach ($jadwal as $j) { ?><tr<?php echo $j->status === 'Proyeksi' ? ' class="text-muted"' : ''; ?>><td><?php echo bulan_singkat((int) substr($j->periode, 5, 2)) . ' ' . substr($j->periode, 0, 4); ?></td><td class="num"><?php echo nf($j->jumlah, 2); ?></td><td class="num"><?php echo nf($j->akumulasi, 2); ?></td><td class="num"><?php echo nf($j->nilai_buku, 2); ?></td><td><?php echo $j->status; ?></td></tr><?php } ?>
    <?php if ( ! $jadwal) { ?><tr><td colspan="5" class="text-muted text-center">Aset tidak disusutkan.</td></tr><?php } ?>
    </tbody></table>
    </div>
</div>
<div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal">Tutup</button></div>
