<?php defined('BASEPATH') OR exit('No direct script access allowed');
$nm = function ($k) use ($akun) { return isset($akun[$k]) ? $k . ' — ' . $akun[$k]->nama_akun : $k; };
$sisa = $f->total - $f->terbayar;
?>
<div data-modal-size="lg"></div>
<div class="modal-header">
    <h4 class="modal-title"><?php echo $L['faktur']; ?> · <?php echo html_escape($f->no_faktur); ?> <span class="acc-status acc-status--<?php echo $f->status; ?> ml-2"><?php echo $f->status; ?></span></h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row mb-3">
        <div class="col-sm-4"><small class="text-muted d-block"><?php echo $L['kontak']; ?></small><strong><?php echo html_escape($f->kontak); ?></strong></div>
        <div class="col-sm-4"><small class="text-muted d-block">Tanggal / Jatuh tempo</small><?php echo tgl_singkat($f->tanggal); ?> / <?php echo tgl_singkat($f->jatuh_tempo); ?></div>
        <div class="col-sm-4"><small class="text-muted d-block">Akun</small><?php echo html_escape($nm($f->kode_akun_ar_ap)); ?></div>
        <div class="col-sm-4 mt-2"><small class="text-muted d-block">No. referensi</small><?php echo html_escape($f->no_referensi ?: '-'); ?></div>
        <div class="col-sm-4 mt-2"><small class="text-muted d-block">No. Faktur Pajak (NSFP)</small><?php echo html_escape(isset($f->no_faktur_pajak) && $f->no_faktur_pajak !== '' ? $f->no_faktur_pajak : '-'); ?></div>
        <div class="col-sm-4 mt-2"><small class="text-muted d-block">Keterangan</small><?php echo html_escape($f->keterangan ?: '-'); ?></div>
    </div>
    <table class="table table-sm"><thead><tr><th>Akun</th><th>Keterangan</th><th class="num">Qty</th><th class="num">Harga</th><th class="num">Jumlah</th></tr></thead><tbody>
    <?php foreach ($f->detail as $d) { ?><tr><td><?php echo html_escape($nm($d->kode_akun)); ?></td><td><?php echo html_escape($d->keterangan); ?></td><td class="num"><?php echo nf($d->qty, 2); ?></td><td class="num"><?php echo nf($d->harga, 2); ?></td><td class="num"><?php echo nf($d->jumlah, 2); ?></td></tr><?php } ?>
    </tbody><tfoot>
        <tr><td colspan="4" class="text-right">Subtotal</td><td class="num"><?php echo nf($f->subtotal, 2); ?></td></tr>
        <?php if ($f->diskon > 0) { ?><tr><td colspan="4" class="text-right">Diskon</td><td class="num">(<?php echo nf($f->diskon, 2); ?>)</td></tr><?php } ?>
        <?php if ($f->ppn > 0) { ?><tr><td colspan="4" class="text-right">PPN <?php echo nf($f->ppn_persen, 2); ?>%</td><td class="num"><?php echo nf($f->ppn, 2); ?></td></tr><?php } ?>
        <tr class="row-grand"><td colspan="4" class="text-right">TOTAL</td><td class="num"><?php echo nf($f->total, 2); ?></td></tr>
    </tfoot></table>

    <h6 class="mt-3"><?php echo $L['bayar']; ?></h6>
    <table class="table table-sm"><thead><tr><th>Tanggal</th><th>No. Bukti</th><th>Akun Kas/Bank</th><th>Keterangan</th><th class="num">Jumlah</th><th class="num">Potongan PPh</th><th></th></tr></thead><tbody>
    <?php foreach ($f->bayar as $b) { $pot = isset($b->potongan) ? (float) $b->potongan : 0; ?><tr><td><?php echo tgl_singkat($b->tanggal); ?></td><td><?php echo html_escape($b->no_bukti); ?></td><td><?php echo html_escape($nm($b->kode_akun_kas)); ?></td><td><?php echo html_escape($b->keterangan); ?></td><td class="num"><?php echo nf($b->jumlah, 2); ?></td>
        <td class="num"><?php echo $pot > 0 ? nf($pot, 2) . '<div class="small text-muted">' . html_escape($b->jenis_pph . ($b->no_bukti_potong ? ' · ' . $b->no_bukti_potong : '')) . '</div>' : '-'; ?></td>
        <td class="text-right"><?php if ($boleh['delete']) { ?><a href="#" class="text-danger" data-acc-post="<?php echo site_url($ctrl . '/hapus_bayar'); ?>" data-param='{"id":<?php echo (int) $b->id; ?>}' data-konfirmasi="Hapus pembayaran <?php echo html_escape($b->no_bukti); ?>?" data-tombol="Ya, hapus"><i class="fas fa-trash"></i></a><?php } ?></td></tr><?php } ?>
    <?php if ( ! $f->bayar) { ?><tr><td colspan="7" class="text-muted text-center">Belum ada pembayaran.</td></tr><?php } ?>
    </tbody><tfoot><tr><th colspan="5" class="text-right">Sisa tagihan</th><th class="num"><?php echo nf($f->status === 'BATAL' ? 0 : $sisa, 2); ?></th><th></th></tr></tfoot></table>
</div>
<div class="modal-footer">
    <?php if ($boleh['add'] && in_array($f->status, array('BELUM', 'SEBAGIAN'), true)) { ?><button class="btn btn-success" data-acc-modal="<?php echo site_url($ctrl . '/form_bayar/' . $f->id); ?>"><i class="fas fa-hand-holding-usd mr-1"></i> <?php echo $L['bayar']; ?></button><?php } ?>
    <a class="btn btn-outline-secondary" target="_blank" href="<?php echo site_url($ctrl . '/cetak/' . $f->id); ?>"><i class="fas fa-print"></i> Cetak</a>
    <button class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
