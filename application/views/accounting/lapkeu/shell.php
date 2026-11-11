<?php defined('BASEPATH') OR exit('No direct script access allowed');
$ctrl = isset($ctrl) ? $ctrl : 'LaporanKeuangan';
$pv = strpos($partial, '/') !== false ? $partial : 'accounting/lapkeu/p_' . $partial;
$q = $_GET; unset($q['cetak'], $q['export']);
$qs = http_build_query($q);
$urlCetak = site_url($ctrl . '/' . $kunci) . '?' . $qs . ($qs ? '&' : '') . 'cetak=1';
$urlExcel = site_url($ctrl . '/' . $kunci) . '?' . $qs . ($qs ? '&' : '') . 'export=xlsx';
$jenis = $f['jenis'];
?>
<div class="card no-print">
    <div class="card-body py-3">
        <form method="get" action="<?php echo site_url($ctrl . '/' . $kunci); ?>" class="acc-toolbar" id="form-filter-lap">
            <?php if (strpos($jenis, 'periode') === 0) { ?>
            <div class="form-group">
                <label class="mb-1"><?php echo t('dari'); ?></label>
                <input type="date" name="dari" class="form-control form-control-sm" value="<?php echo $f['dari']; ?>" required>
            </div>
            <div class="form-group">
                <label class="mb-1"><?php echo t('sampai'); ?></label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="<?php echo $f['sampai']; ?>" required>
            </div>
            <div class="form-group">
                <label class="mb-1"><?php echo t('cepat'); ?></label>
                <select class="form-control form-control-sm" id="preset-periode">
                    <option value=""><?php echo t('pilih'); ?></option>
                    <option value="bulan_ini"><?php echo t('bulan_ini'); ?></option>
                    <option value="bulan_lalu"><?php echo t('bulan_lalu'); ?></option>
                    <option value="kuartal_ini"><?php echo t('kuartal_ini'); ?></option>
                    <option value="tahun_ini"><?php echo t('tahun_ini'); ?></option>
                    <option value="tahun_lalu"><?php echo t('tahun_lalu'); ?></option>
                </select>
            </div>
            <?php } ?>
            <?php if (in_array($jenis, array('tanggal', 'tanggal_rinci'), true)) { ?>
            <div class="form-group">
                <label class="mb-1"><?php echo t('per_tanggal'); ?></label>
                <input type="date" name="tgl" class="form-control form-control-sm" value="<?php echo $f['tgl']; ?>" required>
            </div>
            <?php } ?>
            <?php if ($kunci === 'neraca') { ?>
            <div class="form-group">
                <label class="mb-1">Bandingkan dengan (opsional)</label>
                <input type="date" name="banding" class="form-control form-control-sm" value="<?php echo html_escape((string) $f['banding']); ?>">
            </div>
            <?php } ?>
            <?php if ($kunci === 'laba_rugi') { ?>
            <div class="form-group">
                <label class="mb-1">Tampilan</label>
                <select name="mode" class="form-control form-control-sm">
                    <option value="standar" <?php echo $f['mode'] === 'standar' ? 'selected' : ''; ?>>Standar</option>
                    <option value="bulanan" <?php echo $f['mode'] === 'bulanan' ? 'selected' : ''; ?>>Per bulan (multi periode)</option>
                    <option value="banding" <?php echo $f['mode'] === 'banding' ? 'selected' : ''; ?>>Bandingkan periode sebelumnya</option>
                </select>
            </div>
            <?php } ?>
            <?php if ($jenis === 'periode_akun' || $jenis === 'periode_kas') { ?>
            <div class="form-group" style="min-width:260px">
                <label class="mb-1">Akun</label>
                <select name="akun" class="form-control form-control-sm select2-akun">
                    <option value="">— Semua akun —</option>
                    <?php foreach ($akun as $a) { if ($jenis === 'periode_kas' && $a->kategori !== 'KAS_BANK') continue; ?>
                    <option value="<?php echo html_escape($a->kode_akun); ?>" <?php echo $f['akun'] === $a->kode_akun ? 'selected' : ''; ?>><?php echo html_escape($a->kode_akun . ' - ' . $a->nama_akun); ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($jenis === 'periode_sumber') { ?>
            <div class="form-group">
                <label class="mb-1">Sumber</label>
                <select name="sumber" class="form-control form-control-sm">
                    <option value="">— Semua —</option>
                    <?php foreach (Akuntansi::$SUMBER as $k => $v) { ?>
                    <option value="<?php echo $k; ?>" <?php echo $f['sumber'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if (in_array($jenis, array('tahun', 'tahun_rinci', 'tahun_masa'), true)) { ?>
            <div class="form-group">
                <label class="mb-1"><?php echo t('tahun'); ?></label>
                <input type="number" name="tahun" class="form-control form-control-sm" value="<?php echo (int) $f['tahun']; ?>" min="2000" max="2100" style="width:100px">
            </div>
            <?php } ?>
            <?php if ($jenis === 'tahun_masa') { ?>
            <div class="form-group">
                <label class="mb-1">Masa (bulan)</label>
                <select name="bulan" class="form-control form-control-sm">
                    <?php for ($b = 1; $b <= 12; $b++) { ?><option value="<?php echo $b; ?>" <?php echo (int) $f['bulan'] === $b ? 'selected' : ''; ?>><?php echo bulan_panjang($b); ?></option><?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($jenis === 'periode_kontak') { ?>
            <div class="form-group" style="min-width:240px">
                <label class="mb-1"><?php echo $kunci === 'kartu_piutang' ? 'Pelanggan' : 'Pemasok'; ?></label>
                <select name="kontak" class="form-control form-control-sm select2-akun">
                    <option value="">— Semua —</option>
                    <?php foreach ($f['kontak_list'] as $k) { ?><option value="<?php echo html_escape($k); ?>" <?php echo $f['kontak'] === $k ? 'selected' : ''; ?>><?php echo html_escape($k); ?></option><?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($jenis === 'periode_faktur') { ?>
            <div class="form-group">
                <label class="mb-1">Status</label>
                <select name="status" class="form-control form-control-sm">
                    <?php foreach (array('' => '— Semua —', 'BELUM' => 'Belum dibayar', 'SEBAGIAN' => 'Dibayar sebagian', 'LUNAS' => 'Lunas', 'BATAL' => 'Batal') as $k => $v) { ?>
                    <option value="<?php echo $k; ?>" <?php echo $f['status'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($jenis === 'tahun_bulan') { ?>
            <div class="form-group">
                <label class="mb-1"><?php echo t('tahun'); ?></label>
                <input type="number" name="tahun" class="form-control form-control-sm" value="<?php echo (int) $f['tahun']; ?>" min="2000" max="2100" style="width:100px">
            </div>
            <div class="form-group">
                <label class="mb-1">s/d Bulan</label>
                <select name="bulan" class="form-control form-control-sm">
                    <?php for ($b = 1; $b <= 12; $b++) { ?><option value="<?php echo $b; ?>" <?php echo (int) $f['bulan'] === $b ? 'selected' : ''; ?>><?php echo bulan_panjang($b); ?></option><?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($kunci === 'neraca_saldo') { ?>
            <div class="form-group">
                <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" id="cb-nol" name="nol" value="1" <?php echo $f['nol'] ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="cb-nol">Tampilkan akun bersaldo nol</label>
                </div>
            </div>
            <?php } ?>
            <?php if ($kunci === 'laba_rugi') { ?>
            <div class="form-group">
                <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" id="cb-persen" name="persen" value="1" <?php echo ! empty($f['persen']) ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="cb-persen">Kolom % pendapatan</label>
                </div>
            </div>
            <?php } ?>
            <?php if (in_array($jenis, array('tanggal_rinci', 'tahun_rinci', 'periode_faktur'), true)) { ?>
            <div class="form-group">
                <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" id="cb-rinci" name="rinci" value="1" <?php echo $f['rinci'] ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="cb-rinci"><?php echo $jenis === 'tahun_rinci' ? 'Rincian per bulan' : ($jenis === 'periode_faktur' ? 'Rincian baris faktur' : 'Rincian per faktur'); ?></label>
                </div>
            </div>
            <?php } ?>
            <?php if (array_key_exists('lok', $f)) { ?>
            <div class="form-group">
                <label class="mb-1"><?php echo t('lokasi'); ?></label>
                <select name="lok" class="form-control form-control-sm">
                    <?php if (empty($f['lok_wajib'])) { ?><option value=""><?php echo t('semua'); ?></option><?php } ?>
                    <?php foreach (Mod_lapgudang::$LOKASI as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $f['lok'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if (array_key_exists('part', $f)) { ?>
            <div class="form-group" style="min-width:300px">
                <label class="mb-1">No. Part / Nama barang</label>
                <select name="part" class="form-control form-control-sm" id="pilih-part">
                    <?php if ($f['part'] !== '') { ?><option value="<?php echo html_escape($f['part']); ?>" selected><?php echo html_escape($f['part'] . (isset($f['part_nama']) ? ' — ' . $f['part_nama'] : '')); ?></option><?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if (array_key_exists('kategori', $f)) { ?>
            <div class="form-group">
                <label class="mb-1">Kategori</label>
                <select name="kategori" class="form-control form-control-sm">
                    <option value=""><?php echo t('semua'); ?></option>
                    <?php foreach ($f['kategori_list'] as $k) { ?><option value="<?php echo html_escape($k->kode_kategori); ?>" <?php echo $f['kategori'] === (string) $k->kode_kategori ? 'selected' : ''; ?>><?php echo html_escape($k->kategori); ?></option><?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if (array_key_exists('hari', $f)) { ?>
            <div class="form-group">
                <label class="mb-1">Tidak keluar ≥ (hari)</label>
                <input type="number" name="hari" min="7" max="1095" class="form-control form-control-sm" style="width:110px" value="<?php echo (int) $f['hari']; ?>">
            </div>
            <?php } ?>
            <?php if (array_key_exists('opname', $f)) { ?>
            <div class="form-group" style="min-width:260px">
                <label class="mb-1">Dokumen opname</label>
                <select name="opname" class="form-control form-control-sm">
                    <?php foreach ($f['opname_list'] as $o) { ?><option value="<?php echo html_escape($o->id_opname); ?>" <?php echo $f['opname'] === (string) $o->id_opname ? 'selected' : ''; ?>><?php echo html_escape($o->id_opname . ' · ' . $o->tgl . ' · ' . $o->kelompok); ?></option><?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if (array_key_exists('bergerak', $f)) { ?>
            <div class="form-group">
                <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" id="cb-gerak" name="semua" value="1" <?php echo ! $f['bergerak'] ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="cb-gerak"><?php echo $kunci === 'stok_minimum' ? 'Tampilkan juga yang aman' : 'Tampilkan semua barang bersaldo'; ?></label>
                </div>
            </div>
            <?php } ?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-sync-alt mr-1"></i> <?php echo t('tampilkan'); ?></button>
            </div>
            <div class="form-group ml-auto">
                <div class="btn-group">
                    <a href="<?php echo $urlCetak; ?>" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="fas fa-print mr-1"></i> <?php echo t('cetak'); ?></a>
                    <a href="<?php echo $urlExcel; ?>" class="btn btn-outline-success btn-sm"><i class="fas fa-file-excel mr-1"></i> Excel</a>
                    <a href="<?php echo site_url($ctrl); ?>" class="btn btn-outline-secondary btn-sm" title="<?php echo t('semua_laporan'); ?>"><i class="fas fa-th-large"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ( ! empty($penjelasan)) { $this->load->view('accounting/lapkeu/_penjelasan', array('p' => $penjelasan, 'kunci' => $kunci)); } ?>

<?php if ( ! empty($grafik)) { ?>
<div class="top-grafik-grid no-print">
    <?php foreach ($grafik as $g) { ?>
    <div class="card"><div class="card-body"><div class="top-chart" data-chart="<?php echo html_escape(json_encode($g)); ?>"></div></div></div>
    <?php } ?>
</div>
<?php } ?>

<div class="card">
    <div class="card-body acc-report">
        <div class="acc-report__head">
            <div class="text-muted" style="font-weight:600"><?php echo html_escape($perusahaan); ?></div>
            <h3><?php echo html_escape($info[0]); ?></h3>
            <div class="sub"><?php echo html_escape($subjudul); ?></div>
        </div>
        <div class="table-responsive">
            <?php $this->load->view($pv, array('d' => $d, 'f' => $f, 'mode' => 'screen', 'kunci' => $kunci)); ?>
        </div>
    </div>
</div>

<script>
$(function () {
    if ($.fn.select2) $('.select2-akun').select2({ theme: 'bootstrap4', width: '100%' });
    if ($.fn.select2 && $('#pilih-part').length) $('#pilih-part').select2({ theme: 'bootstrap4', width: '100%', placeholder: 'Ketik no. part / nama…', minimumInputLength: 2,
        ajax: { url: '<?php echo site_url($ctrl . '/cari_part'); ?>', dataType: 'json', delay: 250, data: function (p) { return { q: p.term }; }, processResults: function (r) { return { results: r }; } } });
    $('#preset-periode').on('change', function () {
        var t = new Date(), y = t.getFullYear(), m = t.getMonth(), a, b;
        var f = function (d) { return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2); };
        switch (this.value) {
            case 'bulan_ini': a = new Date(y, m, 1); b = new Date(y, m + 1, 0); break;
            case 'bulan_lalu': a = new Date(y, m - 1, 1); b = new Date(y, m, 0); break;
            case 'kuartal_ini': var q = Math.floor(m / 3) * 3; a = new Date(y, q, 1); b = new Date(y, q + 3, 0); break;
            case 'tahun_ini': a = new Date(y, 0, 1); b = new Date(y, 11, 31); break;
            case 'tahun_lalu': a = new Date(y - 1, 0, 1); b = new Date(y - 1, 11, 31); break;
            default: return;
        }
        $('[name=dari]').val(f(a)); $('[name=sampai]').val(f(b));
        $('#form-filter-lap').trigger('submit');
    });
});
</script>
