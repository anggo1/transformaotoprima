<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('warehouse/_flash');
$S = $set; $L = Mod_lapgudang::$LOKASI;
$warna = array('DRAFT' => 'secondary', 'HITUNG' => 'info', 'REVIEW' => 'warning', 'DISETUJUI' => 'success', 'BATAL' => 'dark');
?>
<div class="wh-steps mb-3">
    <div class="wh-steps__item"><b>1</b><span><strong>Buat dokumen</strong>Pilih gudang & cakupan (semua, kategori, rak, kelas A…)</span></div>
    <div class="wh-steps__item"><b>2</b><span><strong>Mulai = kunci snapshot</strong>Stok sistem saat itu disimpan sebagai pembanding</span></div>
    <div class="wh-steps__item"><b>3</b><span><strong>Hitung fisik</strong>Ketik, scan QR label, atau isi lembar Excel lalu impor</span></div>
    <div class="wh-steps__item"><b>4</b><span><strong>Review & hitung ulang</strong>Selisih di atas toleransi dihitung kedua kali</span></div>
    <div class="wh-steps__item"><b>5</b><span><strong>Setujui</strong>Stok disesuaikan sebesar selisih + jurnal persediaan (opsional)</span></div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h3 class="card-title">Dokumen stock opname</h3>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-<?php echo $status === '' ? 'primary' : 'outline-secondary'; ?>" href="<?php echo site_url('OpnameStok'); ?>">Semua</a>
                    <?php foreach (Mod_opname::$STATUS as $k => $v) { ?><a class="btn btn-<?php echo $status === $k ? 'primary' : 'outline-secondary'; ?>" href="<?php echo site_url('OpnameStok?status=' . $k); ?>"><?php echo $v; ?></a><?php } ?>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>No. dokumen</th><th>Tanggal</th><th>Gudang</th><th>Cakupan</th><th>Progres hitung</th><th class="num">Selisih (Rp)</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($rows as $r) { $R = $r->ringkas; ?>
                        <tr>
                            <td><a href="<?php echo site_url('OpnameStok/lihat/' . $r->id); ?>"><b><?php echo html_escape($r->no_so); ?></b></a><div class="small text-muted"><?php echo html_escape($r->dibuat_oleh); ?></div></td>
                            <td><?php echo tgl_singkat($r->tgl); ?></td>
                            <td><?php echo isset($L[$r->lokasi]) ? $L[$r->lokasi] : $r->lokasi; ?></td>
                            <td><?php echo html_escape(Mod_opname::$CAKUPAN[$r->cakupan]); ?><?php if ($r->filter !== '' && $r->cakupan !== 'PILIH') { ?><div class="small text-muted"><?php echo html_escape($r->filter); ?></div><?php } ?></td>
                            <td style="min-width:140px"><div class="wh-bar"><span style="width:<?php echo $R->persen; ?>%"></span><em><?php echo nf($R->dihitung) . ' / ' . nf($R->item); ?></em></div></td>
                            <td class="num"><?php if ($R->selisih) { ?><span class="text-success">+<?php echo nf_acc($R->lebih); ?></span><br><span class="text-danger"><?php echo nf_acc($R->kurang); ?></span><?php } else echo '-'; ?></td>
                            <td><span class="badge badge-<?php echo $warna[$r->status]; ?>"><?php echo Mod_opname::$STATUS[$r->status]; ?></span></td>
                        </tr>
                    <?php } if ( ! $rows) { ?><tr><td colspan="7" class="text-center text-muted py-4">Belum ada dokumen stock opname. Buat dokumen pertama di panel kanan.</td></tr><?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <?php if ($bisa_tambah) { ?>
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-plus-circle mr-1"></i> Opname baru</h3></div>
            <form class="card-body" method="post" action="<?php echo site_url('OpnameStok/buat'); ?>">
                <div class="form-row">
                    <div class="form-group col-6"><label>Tanggal</label><input type="date" name="tgl" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>"></div>
                    <div class="form-group col-6"><label>Gudang</label>
                        <select name="lokasi" class="form-control form-control-sm"><?php foreach ($L as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $lok_user === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
                </div>
                <div class="form-group"><label>Cakupan barang</label>
                    <select name="cakupan" class="form-control form-control-sm" id="so-cakupan"><?php foreach (Mod_opname::$CAKUPAN as $k => $v) { ?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php } ?></select></div>
                <div class="form-group so-f so-f--KATEGORI" hidden><label>Kategori</label>
                    <select class="form-control form-control-sm" data-nama="filter"><?php foreach ($kategori as $k) { ?><option value="<?php echo html_escape($k->kode_kategori); ?>"><?php echo html_escape($k->kode_kategori . ' — ' . $k->kategori); ?></option><?php } ?></select></div>
                <div class="form-group so-f so-f--KELOMPOK" hidden><label>Kelompok</label>
                    <select class="form-control form-control-sm" data-nama="filter"><?php foreach ($kelompok as $k) { ?><option><?php echo html_escape($k->kelompok); ?></option><?php } ?></select></div>
                <div class="form-group so-f so-f--RAK" hidden><label>Awalan kode rak</label><input class="form-control form-control-sm" data-nama="filter" placeholder="mis. A1 atau RAK-B"><small class="text-muted">Cocok untuk opname bergilir (cycle count) per lorong.</small></div>
                <div class="form-group so-f so-f--PILIH" hidden><label>Daftar no. part</label><textarea class="form-control form-control-sm" rows="3" data-nama="filter" placeholder="satu no. part per baris"></textarea></div>
                <div class="form-row">
                    <div class="form-group col-6"><label>Toleransi selisih</label><div class="input-group input-group-sm"><input type="number" step="0.1" min="0" max="100" name="toleransi" class="form-control" value="<?php echo html_escape($S['so_toleransi'] ?? '0'); ?>"><div class="input-group-append"><span class="input-group-text">%</span></div></div></div>
                    <div class="form-group col-6 d-flex align-items-end"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="so-buta" name="buta" value="1" <?php echo ($S['so_buta'] ?? 'Y') === 'Y' ? 'checked' : ''; ?>><label class="custom-control-label" for="so-buta">Hitung buta</label></div></div>
                </div>
                <div class="form-group"><label>Catatan</label><input name="catatan" class="form-control form-control-sm" maxlength="255" placeholder="mis. Opname akhir tahun / cycle count rak A"></div>
                <div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="custom-control-input" id="so-langsung" name="langsung" value="1" checked><label class="custom-control-label" for="so-langsung">Langsung mulai (kunci snapshot stok sekarang)</label></div>
                <button class="btn btn-primary btn-block"><i class="fas fa-clipboard-check mr-1"></i> Buat dokumen opname</button>
                <p class="small text-muted mt-2 mb-0"><b>Hitung buta</b>: stok sistem disembunyikan dari petugas hitung agar hasil tidak "menyesuaikan" angka sistem. <b>Toleransi</b>: selisih di atas persentase ini ditandai wajib hitung ulang.</p>
            </form>
        </div>
        <?php } ?>

        <?php if ($admin) { ?>
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-sliders-h mr-1"></i> Pengaturan & integrasi akuntansi</h3></div>
            <form class="card-body" method="post" action="<?php echo site_url('OpnameStok/pengaturan'); ?>">
                <div class="form-row">
                    <div class="form-group col-6"><label>Toleransi bawaan (%)</label><input type="number" step="0.1" min="0" max="100" name="so_toleransi" class="form-control form-control-sm" value="<?php echo html_escape($S['so_toleransi'] ?? '0'); ?>"></div>
                    <div class="form-group col-6 d-flex align-items-end"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="st-buta" name="so_buta" value="1" <?php echo ($S['so_buta'] ?? 'Y') === 'Y' ? 'checked' : ''; ?>><label class="custom-control-label" for="st-buta">Hitung buta bawaan</label></div></div>
                </div>
                <div class="custom-control custom-switch mb-2"><input type="checkbox" class="custom-control-input" id="st-jurnal" name="so_jurnal" value="1" <?php echo ($S['so_jurnal'] ?? 'N') === 'Y' ? 'checked' : ''; ?>><label class="custom-control-label" for="st-jurnal">Buat jurnal penyesuaian persediaan otomatis</label></div>
                <div class="form-group"><label>Akun persediaan</label>
                    <select name="akun_persediaan" class="form-control form-control-sm"><option value="">— pilih —</option><?php foreach ($akun as $a) { ?><option value="<?php echo html_escape($a->kode_akun); ?>" <?php echo ($S['akun_persediaan'] ?? '') === $a->kode_akun ? 'selected' : ''; ?>><?php echo html_escape($a->kode_akun . ' ' . $a->nama_akun); ?></option><?php } ?></select></div>
                <div class="form-group"><label>Akun selisih persediaan (beban / pendapatan lain)</label>
                    <select name="akun_selisih_persediaan" class="form-control form-control-sm"><option value="">— pilih —</option><?php foreach ($akun as $a) { ?><option value="<?php echo html_escape($a->kode_akun); ?>" <?php echo ($S['akun_selisih_persediaan'] ?? '') === $a->kode_akun ? 'selected' : ''; ?>><?php echo html_escape($a->kode_akun . ' ' . $a->nama_akun); ?></option><?php } ?></select></div>
                <button class="btn btn-outline-primary btn-sm">Simpan pengaturan</button>
                <p class="small text-muted mt-2 mb-0">Selisih kurang: <b>Dr</b> Selisih persediaan / <b>Cr</b> Persediaan. Selisih lebih: kebalikannya. Jurnal bersumber <code>SO</code> tampil di Jurnal Umum & Buku Besar.</p>
            </form>
        </div>
        <?php } ?>
        <a class="btn btn-block btn-outline-secondary mb-3" href="<?php echo site_url('LaporanGudang/selisih_opname'); ?>"><i class="fas fa-chart-bar mr-1"></i> Laporan selisih stock opname</a>
    </div>
</div>
<script>
(function () {
    var s = document.getElementById('so-cakupan'); if (!s) return;
    function ubah() {
        document.querySelectorAll('.so-f').forEach(function (g) {
            var aktif = g.classList.contains('so-f--' + s.value); g.hidden = !aktif;
            g.querySelectorAll('[data-nama]').forEach(function (i) { if (aktif) i.setAttribute('name', 'filter'); else i.removeAttribute('name'); });
        });
    }
    s.addEventListener('change', ubah); ubah();
})();
</script>
