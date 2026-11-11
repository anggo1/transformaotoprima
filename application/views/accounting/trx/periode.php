<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-periode">Tutup Buku</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-pref">Preferensi Akun</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane fade show active" id="tab-periode">
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <form class="acc-toolbar" method="get"><div class="form-group"><label class="mb-1">Tahun</label><input type="number" name="tahun" value="<?php echo $tahun; ?>" class="form-control form-control-sm" style="width:110px"></div><div class="form-group"><button class="btn btn-sm btn-outline-primary">Tampilkan</button></div></form>
            <div>
                <?php if ( ! $penutup && $boleh['add']) { ?>
                <button class="btn btn-sm btn-outline-primary" data-acc-post="<?php echo site_url('PeriodeAkuntansi/penutup'); ?>" data-param='{"tahun":<?php echo $tahun; ?>}' data-konfirmasi="Buat jurnal penutup tahun <?php echo $tahun; ?>?" data-teks="Saldo pendapatan & beban tahun <?php echo $tahun; ?> dipindahkan ke akun Laba Ditahan per 31 Desember." data-tombol="Ya, buat"><i class="fas fa-flag-checkered mr-1"></i> Jurnal Penutup <?php echo $tahun; ?></button>
                <?php } elseif ($penutup) { ?>
                <span class="acc-status acc-status--LUNAS mr-2">Jurnal penutup <?php echo $tahun; ?> sudah dibuat</span>
                <?php if ($boleh['delete']) { ?><button class="btn btn-sm btn-outline-danger" data-acc-post="<?php echo site_url('PeriodeAkuntansi/batal_penutup'); ?>" data-param='{"tahun":<?php echo $tahun; ?>}' data-konfirmasi="Hapus jurnal penutup <?php echo $tahun; ?>?"><i class="fas fa-undo"></i></button><?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small">Periode yang <b>ditutup</b> tidak dapat ditambah, diubah, atau dihapus transaksinya (Jurnal Umum, Kas &amp; Bank, Piutang, Hutang, Aset). Buka kembali bila perlu koreksi.</p>
            <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead><tr><th>Periode</th><th class="num">Jumlah Jurnal</th><th class="num">Total Debit</th><th>Status</th><th>Diubah oleh</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($periode as $p) { $b = (int) substr($p->periode, 5, 2); ?>
                <tr>
                    <td><strong><?php echo bulan_panjang($b) . ' ' . substr($p->periode, 0, 4); ?></strong></td>
                    <td class="num"><?php echo nf($p->jurnal); ?></td>
                    <td class="num"><?php echo nf($p->nilai); ?></td>
                    <td><span class="acc-status acc-status--<?php echo $p->status; ?>"><i class="fas <?php echo $p->status === 'TUTUP' ? 'fa-lock' : 'fa-lock-open'; ?> mr-1"></i><?php echo $p->status; ?></span></td>
                    <td class="small text-muted"><?php echo html_escape($p->user); ?><?php echo $p->updated_at ? ' · ' . date('d/m/Y H:i', strtotime($p->updated_at)) : ''; ?></td>
                    <td class="text-right"><?php if ($boleh['edit']) { if ($p->status === 'BUKA') { ?>
                        <a href="#" class="btn btn-xs btn-outline-danger" data-acc-post="<?php echo site_url('PeriodeAkuntansi/set'); ?>" data-param='{"periode":"<?php echo $p->periode; ?>","status":"TUTUP"}' data-konfirmasi="Tutup periode <?php echo bulan_panjang($b) . ' ' . substr($p->periode, 0, 4); ?>?" data-tombol="Ya, tutup"><i class="fas fa-lock"></i> Tutup</a>
                    <?php } else { ?>
                        <a href="#" class="btn btn-xs btn-outline-success" data-acc-post="<?php echo site_url('PeriodeAkuntansi/set'); ?>" data-param='{"periode":"<?php echo $p->periode; ?>","status":"BUKA"}' data-konfirmasi="Buka kembali periode ini?" data-tombol="Ya, buka"><i class="fas fa-lock-open"></i> Buka</a>
                    <?php } } ?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade" id="tab-pref">
    <div class="card"><div class="card-body">
        <form class="acc-ajax" action="<?php echo site_url('PeriodeAkuntansi/simpan_pref'); ?>" method="post">
            <div class="form-row">
            <?php foreach ($pref as $k => $x) { $val = isset($setting[$k]) ? $setting[$k] : ''; ?>
                <div class="form-group col-md-6">
                    <label><?php echo $x[0]; ?></label>
                    <?php if ($x[1] === 'text' || $x[1] === 'number') { ?>
                    <input type="<?php echo $x[1]; ?>" name="<?php echo $k; ?>" class="form-control" value="<?php echo html_escape($val); ?>" step="any">
                    <?php } else { ?>
                    <select name="<?php echo $k; ?>" class="form-control select2">
                        <?php echo $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'pilih' => $val, 'hanya' => $x[1] === '*' ? array() : array($x[1])), TRUE); ?>
                    </select>
                    <?php } ?>
                </div>
            <?php } ?>
            </div>
            <?php if ($boleh['edit']) { ?><button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Preferensi</button><?php } ?>
        </form>
    </div></div>
</div>
</div>
<script src="<?php echo base_url('assets/js/top-acc.js?v=2.0.0'); ?>"></script>
<script>$(function () { ACC.init($('#tab-pref')); });</script>
