<?php defined('BASEPATH') OR exit('No direct script access allowed');
$label = array('MASUK' => 'Penerimaan', 'KELUAR' => 'Pengeluaran', 'TRANSFER' => 'Transfer');
$nm = function ($k) use ($akun) { return isset($akun[$k]) ? $k . ' — ' . $akun[$k]->nama_akun : $k; };
?>
<div data-modal-size="lg"></div>
<div class="modal-header">
    <h4 class="modal-title"><?php echo $label[$h->jenis]; ?> · <?php echo html_escape($h->no_transaksi); ?></h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row mb-3">
        <div class="col-sm-4"><small class="text-muted d-block">Tanggal</small><?php echo tgl_id($h->tanggal); ?></div>
        <div class="col-sm-4"><small class="text-muted d-block">Akun kas/bank</small><?php echo html_escape($nm($h->kode_akun_kas)); ?></div>
        <div class="col-sm-4"><small class="text-muted d-block">Status</small><?php echo $h->status === 'Y' ? 'Diposting' : 'Dibatalkan'; ?></div>
        <div class="col-sm-4 mt-2"><small class="text-muted d-block">Kontak</small><?php echo html_escape($h->kontak ?: '-'); ?></div>
        <div class="col-sm-4 mt-2"><small class="text-muted d-block">No. Bukti</small><?php echo html_escape($h->no_bukti ?: '-'); ?></div>
        <div class="col-sm-4 mt-2"><small class="text-muted d-block">Dibuat oleh</small><?php echo html_escape($h->user); ?></div>
        <div class="col-12 mt-2"><small class="text-muted d-block">Keterangan</small><?php echo html_escape($h->keterangan); ?></div>
    </div>
    <table class="table table-sm"><thead><tr><th>Akun</th><th>Keterangan</th><th class="num">Jumlah</th></tr></thead><tbody>
    <?php foreach ($h->detail as $d) { ?><tr><td><?php echo html_escape($nm($d->kode_akun)); ?></td><td><?php echo html_escape($d->keterangan); ?></td><td class="num"><?php echo nf($d->jumlah, 2); ?></td></tr><?php } ?>
    </tbody><tfoot><tr><th colspan="2">Total</th><th class="num"><?php echo nf($h->total, 2); ?></th></tr></tfoot></table>
    <?php if ($jurnal) { ?>
    <h6 class="mt-3 text-muted">Jurnal <?php echo html_escape($h->no_ref_jurnal); ?></h6>
    <table class="table table-sm"><thead><tr><th>Akun</th><th class="num">Debit</th><th class="num">Kredit</th></tr></thead><tbody>
    <?php foreach ($jurnal as $j) { ?><tr><td<?php echo $j->kredit > 0 ? ' style="padding-left:1.6rem"' : ''; ?>><?php echo html_escape($j->kode_akun . ' — ' . $j->nama_akun); ?></td><td class="num"><?php echo nf_acc($j->debit, 2); ?></td><td class="num"><?php echo nf_acc($j->kredit, 2); ?></td></tr><?php } ?>
    </tbody></table>
    <?php } ?>
</div>
<div class="modal-footer"><a class="btn btn-outline-secondary" target="_blank" href="<?php echo site_url('KasBank/cetak/' . $h->id); ?>"><i class="fas fa-print"></i> Cetak</a><button class="btn btn-primary" data-dismiss="modal">Tutup</button></div>
