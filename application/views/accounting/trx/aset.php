<?php defined('BASEPATH') OR exit('No direct script access allowed');
$tH = 0; $tA = 0; $tB = 0; foreach ($rows as $r) { if ($r->status !== 'AKTIF') continue; $tH += $r->harga_perolehan; $tA += $r->akumulasi; $tB += $r->nilai_buku; }
?>
<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Harga perolehan (aktif)</div><div class="acc-kpi__value"><?php echo nf_acc($tH); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Akumulasi penyusutan</div><div class="acc-kpi__value"><?php echo nf_acc($tA); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Nilai buku</div><div class="acc-kpi__value"><?php echo nf_acc($tB); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Penyusutan terakhir diposting</div><div class="acc-kpi__value" style="font-size:1.1rem"><?php echo $periodeTerakhir ? bulan_panjang((int) substr($periodeTerakhir, 5, 2)) . ' ' . substr($periodeTerakhir, 0, 4) : '—'; ?></div></div>
</div>
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <form class="acc-toolbar" method="get">
            <div class="form-group"><label class="mb-1">Status</label><select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                <option value="">Semua</option><?php foreach (array('AKTIF', 'DIJUAL', 'DIHAPUS') as $s) { ?><option <?php echo $status === $s ? 'selected' : ''; ?>><?php echo $s; ?></option><?php } ?></select></div>
        </form>
        <div class="d-flex flex-wrap" style="gap:.5rem">
            <?php if ($boleh['add']) { ?>
            <form class="d-flex align-items-end" style="gap:.4rem" id="form-susut">
                <div><label class="mb-1 small">Proses penyusutan s/d</label><input type="month" name="periode" class="form-control form-control-sm" value="<?php echo date('Y-m'); ?>"></div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-susut"><i class="fas fa-calculator mr-1"></i> Proses</button>
                <?php if ($boleh['delete'] && $periodeTerakhir) { ?><button type="button" class="btn btn-sm btn-outline-danger" data-acc-post="<?php echo site_url('AsetTetap/batal_susut'); ?>" data-param='{"periode":"<?php echo $periodeTerakhir; ?>"}' data-konfirmasi="Batalkan penyusutan <?php echo $periodeTerakhir; ?>?" data-teks="Jurnal penyusutan periode tersebut akan dihapus." title="Batalkan penyusutan terakhir"><i class="fas fa-undo"></i></button><?php } ?>
            </form>
            <button class="btn btn-primary btn-sm align-self-end" data-acc-modal="<?php echo site_url('AsetTetap/form'); ?>"><i class="fas fa-plus mr-1"></i> Aset Baru</button>
            <?php } ?>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-sm table-hover acc-dt">
            <thead><tr><th>Kode</th><th>Nama Aset</th><th>Kelompok</th><th>Perolehan</th><th>Metode</th><th class="num">Harga</th><th class="num">Akumulasi</th><th class="num">Nilai Buku</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r) { ?>
                <tr>
                    <td><a href="#" data-acc-modal="<?php echo site_url('AsetTetap/detail/' . $r->id); ?>"><?php echo html_escape($r->kode_aset); ?></a></td>
                    <td><?php echo html_escape($r->nama_aset); ?><?php echo $r->lokasi ? '<div class="small text-muted">' . html_escape($r->lokasi) . '</div>' : ''; ?></td>
                    <td><?php echo html_escape($r->kelompok_aset); ?></td>
                    <td data-order="<?php echo $r->tgl_perolehan; ?>"><?php echo tgl_singkat($r->tgl_perolehan); ?></td>
                    <td><?php echo str_replace('_', ' ', ucwords(strtolower($r->metode), '_')); ?><div class="small text-muted"><?php echo (int) $r->umur_bulan; ?> bulan</div></td>
                    <td class="num"><?php echo nf($r->harga_perolehan); ?></td>
                    <td class="num"><?php echo nf($r->akumulasi); ?></td>
                    <td class="num"><strong><?php echo nf($r->nilai_buku); ?></strong></td>
                    <td><span class="acc-status acc-status--<?php echo $r->status; ?>"><?php echo $r->status; ?></span></td>
                    <td class="text-right text-nowrap">
                        <?php if ($boleh['edit'] && $r->status === 'AKTIF') { ?>
                        <a href="#" class="btn btn-xs btn-outline-primary" data-acc-modal="<?php echo site_url('AsetTetap/form/' . $r->id); ?>" title="Ubah"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-xs btn-outline-warning" data-acc-modal="<?php echo site_url('AsetTetap/form_lepas/' . $r->id); ?>" title="Jual / hapus aset"><i class="fas fa-sign-out-alt"></i></a>
                        <?php } ?>
                        <?php if ($boleh['delete'] && ! $r->susut_terakhir && $r->status === 'AKTIF') { ?><a href="#" class="btn btn-xs btn-outline-danger" data-acc-post="<?php echo site_url('AsetTetap/hapus'); ?>" data-param='{"id":<?php echo (int) $r->id; ?>}' data-konfirmasi="Hapus aset <?php echo html_escape($r->kode_aset); ?>?" data-tombol="Ya, hapus" title="Hapus"><i class="fas fa-trash"></i></a><?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/top-acc.js?v=2.0.0'); ?>"></script>
<script>
$('#btn-susut').on('click', function () {
    var per = $('#form-susut [name=periode]').val();
    TOP.konfirmasi('Proses penyusutan s/d ' + per + '?', 'Jurnal penyusutan akan dibuat untuk setiap bulan yang belum diproses.', function () {
        $.post('<?php echo site_url('AsetTetap/proses_susut'); ?>', { periode: per }, null, 'json').done(function (r) {
            if (r.status) { toastr.success(r.pesan); setTimeout(function () { location.reload(); }, 600); } else toastr.error(r.pesan);
        });
    }, 'Ya, proses');
});
</script>
