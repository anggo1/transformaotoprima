<?php defined('BASEPATH') OR exit('No direct script access allowed');
$label = array('MASUK' => 'Penerimaan', 'KELUAR' => 'Pengeluaran', 'TRANSFER' => 'Transfer');
$totSaldo = 0; foreach ($saldo as $s) $totSaldo += $s->saldo;
$tm = 0; $tk = 0; foreach ($rows as $r) { if ($r->status !== 'Y') continue; if ($r->jenis === 'MASUK') $tm += $r->total; elseif ($r->jenis === 'KELUAR') $tk += $r->total; }
?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Total Kas &amp; Bank</div><div class="acc-kpi__value"><?php echo nf_acc($totSaldo); ?></div><div class="acc-kpi__sub">per hari ini, <?php echo count($saldo); ?> akun</div></div>
    <?php foreach (array_slice($saldo, 0, 3) as $s) { ?>
    <div class="acc-kpi__item"><div class="acc-kpi__label"><?php echo html_escape($s->kode . ' · ' . $s->nama); ?></div><div class="acc-kpi__value"><?php echo nf_acc($s->saldo); ?></div>
        <div class="acc-kpi__sub"><a href="<?php echo site_url('LaporanKeuangan/mutasi_kas?akun=' . rawurlencode($s->kode)); ?>">Lihat mutasi &rsaquo;</a></div></div>
    <?php } ?>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Masuk / Keluar (periode)</div><div class="acc-kpi__value" style="font-size:1.05rem"><span class="text-success"><?php echo nf($tm); ?></span> / <span class="text-danger"><?php echo nf($tk); ?></span></div><div class="acc-kpi__sub">sesuai filter</div></div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <form class="acc-toolbar" method="get">
            <div class="form-group"><label class="mb-1">Dari</label><input type="date" class="form-control form-control-sm" name="dari" value="<?php echo $f['dari']; ?>"></div>
            <div class="form-group"><label class="mb-1">Sampai</label><input type="date" class="form-control form-control-sm" name="sampai" value="<?php echo $f['sampai']; ?>"></div>
            <div class="form-group"><label class="mb-1">Jenis</label>
                <select name="jenis" class="form-control form-control-sm"><option value="">Semua</option>
                    <?php foreach ($label as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $f['jenis'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
            <div class="form-group"><label class="mb-1">Akun</label>
                <select name="akun" class="form-control form-control-sm"><option value="">Semua akun</option>
                    <?php foreach ($akunKas as $a) { ?><option value="<?php echo html_escape($a->kode_akun); ?>" <?php echo $f['akun'] === $a->kode_akun ? 'selected' : ''; ?>><?php echo html_escape($a->kode_akun . ' - ' . $a->nama_akun); ?></option><?php } ?></select></div>
            <div class="form-group"><button class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i> Filter</button></div>
        </form>
        <?php if ($boleh['add']) { ?>
        <div class="btn-group mt-2 mt-md-0">
            <button class="btn btn-primary btn-sm" data-acc-modal="<?php echo site_url('KasBank/form?jenis=MASUK'); ?>"><i class="fas fa-arrow-down mr-1"></i> Penerimaan</button>
            <button class="btn btn-outline-primary btn-sm" data-acc-modal="<?php echo site_url('KasBank/form?jenis=KELUAR'); ?>"><i class="fas fa-arrow-up mr-1"></i> Pengeluaran</button>
            <button class="btn btn-outline-primary btn-sm" data-acc-modal="<?php echo site_url('KasBank/form?jenis=TRANSFER'); ?>"><i class="fas fa-exchange-alt mr-1"></i> Transfer</button>
        </div>
        <?php } ?>
    </div>
    <div class="card-body">
        <?php if ( ! $akunKas) { ?><div class="alert alert-warning">Belum ada akun bertipe <b>Kas &amp; Bank</b>. Atur di menu Daftar Akun.</div><?php } ?>
        <div class="table-responsive">
        <table class="table table-sm table-hover acc-dt">
            <thead><tr><th>Tanggal</th><th>No. Transaksi</th><th>Jenis</th><th>Akun Kas/Bank</th><th>Kontak</th><th>Keterangan</th><th class="num">Jumlah</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r) { $ak = isset($akunKas[$r->kode_akun_kas]) ? $akunKas[$r->kode_akun_kas]->nama_akun : $r->kode_akun_kas; ?>
                <tr<?php echo $r->status !== 'Y' ? ' class="text-muted" style="text-decoration:line-through"' : ''; ?>>
                    <td data-order="<?php echo $r->tanggal; ?>"><?php echo tgl_singkat($r->tanggal); ?></td>
                    <td><a href="#" data-acc-modal="<?php echo site_url('KasBank/detail/' . $r->id); ?>"><?php echo html_escape($r->no_transaksi); ?></a></td>
                    <td><span class="badge badge-<?php echo $r->jenis === 'MASUK' ? 'success' : ($r->jenis === 'KELUAR' ? 'danger' : 'info'); ?>"><?php echo $label[$r->jenis]; ?></span></td>
                    <td><?php echo html_escape($ak); ?><?php if ($r->jenis === 'TRANSFER') { ?> <i class="fas fa-long-arrow-alt-right text-muted"></i> <?php echo html_escape($r->kode_akun_tujuan); ?><?php } ?></td>
                    <td><?php echo html_escape($r->kontak); ?></td>
                    <td><?php echo html_escape($r->keterangan); ?></td>
                    <td class="num" data-order="<?php echo (float) $r->total; ?>"><?php echo nf($r->total); ?></td>
                    <td><?php echo $r->status === 'Y' ? '<span class="acc-status acc-status--LUNAS">Diposting</span>' : '<span class="acc-status acc-status--BATAL">Batal</span>'; ?></td>
                    <td class="text-right text-nowrap">
                        <?php if ($boleh['print']) { ?><a class="btn btn-xs btn-outline-secondary" target="_blank" href="<?php echo site_url('KasBank/cetak/' . $r->id); ?>" title="Cetak bukti"><i class="fas fa-print"></i></a><?php } ?>
                        <?php if ($boleh['delete'] && $r->status === 'Y') { ?><a class="btn btn-xs btn-outline-danger" href="#" data-acc-post="<?php echo site_url('KasBank/batal'); ?>" data-param='{"id":<?php echo (int) $r->id; ?>}' data-konfirmasi="Batalkan transaksi <?php echo html_escape($r->no_transaksi); ?>?" data-teks="Jurnal transaksi ini akan dihapus." data-tombol="Ya, batalkan" title="Batalkan"><i class="fas fa-ban"></i></a><?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/top-acc.js?v=2.0.0'); ?>"></script>
