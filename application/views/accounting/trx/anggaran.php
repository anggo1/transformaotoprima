<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <form class="acc-toolbar" method="get">
            <div class="form-group"><label class="mb-1">Tahun anggaran</label><input type="number" name="tahun" class="form-control form-control-sm" value="<?php echo $tahun; ?>" min="2000" max="2100" style="width:110px"></div>
            <div class="form-group"><button class="btn btn-sm btn-outline-primary">Tampilkan</button></div>
        </form>
        <div class="d-flex" style="gap:.5rem">
            <a class="btn btn-sm btn-outline-secondary" href="<?php echo site_url('LaporanKeuangan/anggaran?tahun=' . $tahun . '&bulan=' . ($tahun == date('Y') ? date('n') : 12)); ?>"><i class="fas fa-chart-bar mr-1"></i> Anggaran vs Realisasi</a>
            <?php if ($boleh_edit) { ?><button type="submit" form="form-anggaran" class="btn btn-sm btn-primary"><i class="fas fa-save mr-1"></i> Simpan Anggaran</button><?php } ?>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-2">Isi anggaran per bulan. Ketik nilai di kolom <b>Setahun</b> lalu tekan <kbd>Bagi rata</kbd> untuk membagi otomatis ke 12 bulan.</p>
        <form class="acc-ajax" id="form-anggaran" action="<?php echo site_url('Anggaran/simpan'); ?>" method="post">
            <input type="hidden" name="tahun" value="<?php echo $tahun; ?>">
            <div class="table-responsive" style="max-height:70vh">
            <table class="table table-sm table-bordered" style="min-width:1500px">
                <thead style="position:sticky;top:0;z-index:2"><tr><th style="min-width:230px">Akun</th><?php for ($b = 1; $b <= 12; $b++) { ?><th class="num"><?php echo bulan_singkat($b); ?></th><?php } ?><th class="num">Setahun</th><th></th></tr></thead>
                <tbody>
                <?php $kat = null; foreach ($akun as $a) { if ($kat !== $a->kategori) { $kat = $a->kategori; ?>
                    <tr class="row-group"><td colspan="15" style="background:#f6f8fb;font-weight:700"><?php echo Akuntansi::label_kategori($kat); ?></td></tr>
                <?php } $r = isset($data[$a->kode_akun]) ? $data[$a->kode_akun] : null; $tot = 0; ?>
                    <tr class="ang-row">
                        <td class="small"><?php echo html_escape($a->kode_akun . ' — ' . $a->nama_akun); ?></td>
                        <?php for ($b = 1; $b <= 12; $b++) { $f = sprintf('b%02d', $b); $val = $r ? (float) $r->$f : 0; $tot += $val; ?>
                        <td style="padding:2px"><input type="text" class="form-control form-control-sm text-right input-angka ang-b" name="a[<?php echo html_escape($a->kode_akun); ?>][<?php echo $b; ?>]" value="<?php echo $val ? nf($val) : ''; ?>" <?php echo $boleh_edit ? '' : 'readonly'; ?>></td>
                        <?php } ?>
                        <td style="padding:2px;min-width:120px"><input type="text" class="form-control form-control-sm text-right input-angka ang-tot" value="<?php echo $tot ? nf($tot) : ''; ?>"></td>
                        <td style="padding:2px"><?php if ($boleh_edit) { ?><button type="button" class="btn btn-xs btn-outline-secondary ang-bagi" title="Bagi rata 12 bulan">÷12</button><?php } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
        </form>
    </div>
</div>
<script src="<?php echo base_url('assets/js/top-acc.js?v=2.0.0'); ?>"></script>
<script>
$(document).on('input', '.ang-b', function () {
    var $r = $(this).closest('tr'), t = 0; $r.find('.ang-b').each(function () { t += TOP.parse(this.value); });
    $r.find('.ang-tot').val(t ? TOP.fmt(t) : '');
});
$(document).on('click', '.ang-bagi', function () {
    var $r = $(this).closest('tr'), t = TOP.parse($r.find('.ang-tot').val()), per = Math.floor(t / 12), sisa = t - per * 11;
    $r.find('.ang-b').each(function (i) { this.value = TOP.fmt(i === 11 ? sisa : per); });
});
</script>
