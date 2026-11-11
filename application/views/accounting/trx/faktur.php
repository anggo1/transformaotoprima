<?php defined('BASEPATH') OR exit('No direct script access allowed');
$st = array('TERBUKA' => 'Belum lunas', 'BELUM' => 'Belum dibayar', 'SEBAGIAN' => 'Dibayar sebagian', 'LUNAS' => 'Lunas', 'BATAL' => 'Batal');
$hari = date('Y-m-d');
?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label"><?php echo $L['judul']; ?> terbuka</div><div class="acc-kpi__value"><?php echo nf_acc($ringkas->sisa); ?></div><div class="acc-kpi__sub"><?php echo $ringkas->n; ?> faktur belum lunas</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Lewat jatuh tempo</div><div class="acc-kpi__value text-danger"><?php echo nf_acc($ringkas->sisa_lewat); ?></div><div class="acc-kpi__sub"><?php echo $ringkas->n_lewat; ?> faktur</div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Laporan</div><div class="acc-kpi__value" style="font-size:1rem"><a href="<?php echo site_url('LaporanKeuangan/' . ($jenis === 'AR' ? 'umur_piutang' : 'umur_hutang') . '?rinci=1'); ?>"><i class="fas fa-hourglass-half"></i> Umur <?php echo strtolower($L['judul']); ?></a></div><div class="acc-kpi__sub">aging per <?php echo strtolower($L['kontak']); ?></div></div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <form class="acc-toolbar" method="get">
            <div class="form-group"><label class="mb-1">Dari</label><input type="date" class="form-control form-control-sm" name="dari" value="<?php echo $f['dari']; ?>"></div>
            <div class="form-group"><label class="mb-1">Sampai</label><input type="date" class="form-control form-control-sm" name="sampai" value="<?php echo $f['sampai']; ?>"></div>
            <div class="form-group"><label class="mb-1">Status</label>
                <select name="status" class="form-control form-control-sm"><option value="">Semua</option>
                <?php foreach ($st as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $f['status'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
            <div class="form-group"><label class="mb-1"><?php echo $L['kontak']; ?></label><input type="text" class="form-control form-control-sm" name="kontak" value="<?php echo html_escape($f['kontak']); ?>" placeholder="cari nama"></div>
            <div class="form-group"><button class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i> Filter</button></div>
        </form>
        <?php if ($boleh['add']) { ?>
        <button class="btn btn-primary btn-sm mt-2 mt-md-0" data-acc-modal="<?php echo site_url($ctrl . '/form'); ?>"><i class="fas fa-plus mr-1"></i> <?php echo $L['faktur']; ?> Baru</button>
        <?php } ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-sm table-hover acc-dt">
            <thead><tr><th>Tanggal</th><th>No. Faktur</th><th><?php echo $L['kontak']; ?></th><th>Jatuh Tempo</th><th class="num">Total</th><th class="num">Terbayar</th><th class="num">Sisa</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r) { $sisa = $r->total - $r->terbayar; $lewat = in_array($r->status, array('BELUM', 'SEBAGIAN'), true) && $r->jatuh_tempo < $hari; ?>
                <tr>
                    <td data-order="<?php echo $r->tanggal; ?>"><?php echo tgl_singkat($r->tanggal); ?></td>
                    <td><a href="#" data-acc-modal="<?php echo site_url($ctrl . '/detail/' . $r->id); ?>"><?php echo html_escape($r->no_faktur); ?></a><?php echo $r->no_referensi ? '<div class="small text-muted">' . html_escape($r->no_referensi) . '</div>' : ''; ?></td>
                    <td><?php echo html_escape($r->kontak); ?></td>
                    <td data-order="<?php echo $r->jatuh_tempo; ?>" class="<?php echo $lewat ? 'text-danger font-weight-bold' : ''; ?>"><?php echo tgl_singkat($r->jatuh_tempo); ?><?php echo $lewat ? ' <i class="fas fa-exclamation-circle" title="Lewat jatuh tempo"></i>' : ''; ?></td>
                    <td class="num" data-order="<?php echo (float) $r->total; ?>"><?php echo nf($r->total); ?></td>
                    <td class="num"><?php echo nf($r->terbayar); ?></td>
                    <td class="num" data-order="<?php echo $sisa; ?>"><strong><?php echo $r->status === 'BATAL' ? '-' : nf($sisa); ?></strong></td>
                    <td><span class="acc-status acc-status--<?php echo $r->status; ?>"><?php echo $st[$r->status]; ?></span></td>
                    <td class="text-right text-nowrap">
                        <?php if ($boleh['add'] && in_array($r->status, array('BELUM', 'SEBAGIAN'), true)) { ?><a href="#" class="btn btn-xs btn-outline-success" data-acc-modal="<?php echo site_url($ctrl . '/form_bayar/' . $r->id); ?>" title="<?php echo $L['bayar']; ?>"><i class="fas fa-hand-holding-usd"></i></a><?php } ?>
                        <?php if ($boleh['print']) { ?><a class="btn btn-xs btn-outline-secondary" target="_blank" href="<?php echo site_url($ctrl . '/cetak/' . $r->id); ?>" title="Cetak"><i class="fas fa-print"></i></a><?php } ?>
                        <?php if ($boleh['delete'] && $r->status === 'BELUM') { ?><a href="#" class="btn btn-xs btn-outline-danger" data-acc-post="<?php echo site_url($ctrl . '/batal'); ?>" data-param='{"id":<?php echo (int) $r->id; ?>}' data-konfirmasi="Batalkan faktur <?php echo html_escape($r->no_faktur); ?>?" data-teks="Jurnal faktur akan dihapus." data-tombol="Ya, batalkan" title="Batalkan"><i class="fas fa-ban"></i></a><?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/top-acc.js?v=2.0.0'); ?>"></script>
