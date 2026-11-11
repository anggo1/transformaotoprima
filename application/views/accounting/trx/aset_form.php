<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($k, $d = '') use ($a) { return $a && isset($a->$k) ? $a->$k : $d; };
$sudahSusut = $a && ! empty($a->susut);
$o = function ($hanya, $pilih) use ($akun) { return $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'hanya' => $hanya, 'pilih' => $pilih), TRUE); };
?>
<form class="acc-ajax" action="<?php echo site_url('AsetTetap/simpan'); ?>" method="post" autocomplete="off" data-modal-size="lg">
    <input type="hidden" name="id" value="<?php echo (int) $v('id', 0); ?>">
    <div class="modal-header"><h4 class="modal-title"><i class="fas fa-building mr-2 text-primary"></i><?php echo $a ? 'Ubah Aset ' . html_escape($a->kode_aset) : 'Aset Tetap Baru'; ?></h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
    <div class="modal-body">
        <?php if ($sudahSusut) { ?><div class="alert alert-info py-2 small">Aset sudah disusutkan: harga, metode &amp; tanggal mulai susut dikunci.</div><?php } ?>
        <div class="form-row">
            <div class="form-group col-md-3"><label>Kode Aset</label><input type="text" name="kode_aset" class="form-control" value="<?php echo html_escape($v('kode_aset')); ?>" placeholder="otomatis" <?php echo $a ? 'readonly' : ''; ?>></div>
            <div class="form-group col-md-5"><label>Nama Aset</label><input type="text" name="nama_aset" class="form-control" value="<?php echo html_escape($v('nama_aset')); ?>" required maxlength="150"></div>
            <div class="form-group col-md-4"><label>Kelompok</label><input type="text" name="kelompok_aset" class="form-control" list="dl-kel" value="<?php echo html_escape($v('kelompok_aset')); ?>" placeholder="Kendaraan, Peralatan, ...">
                <datalist id="dl-kel"><?php foreach (array('Bangunan', 'Kendaraan', 'Peralatan Bengkel', 'Peralatan Kantor', 'Inventaris') as $k) { ?><option value="<?php echo $k; ?>"><?php } foreach ($kelompok as $k) { ?><option value="<?php echo html_escape($k->kelompok_aset); ?>"><?php } ?></datalist></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3"><label>Tgl Perolehan</label><input type="date" name="tgl_perolehan" class="form-control" value="<?php echo $v('tgl_perolehan', date('Y-m-d')); ?>" required></div>
            <div class="form-group col-md-3"><label>Mulai Disusutkan</label><input type="date" name="tgl_mulai_susut" class="form-control" value="<?php echo $v('tgl_mulai_susut', date('Y-m-01')); ?>" <?php echo $sudahSusut ? 'readonly' : ''; ?>></div>
            <div class="form-group col-md-3"><label>Harga Perolehan</label><input type="text" name="harga_perolehan" class="form-control input-angka text-right" value="<?php echo $a ? nf($a->harga_perolehan, 2) : ''; ?>" required <?php echo $sudahSusut ? 'readonly' : ''; ?>></div>
            <div class="form-group col-md-3"><label>Nilai Residu</label><input type="text" name="nilai_residu" class="form-control input-angka text-right" value="<?php echo nf($v('nilai_residu', 0), 2); ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4"><label>Metode Penyusutan</label><select name="metode" class="form-control" <?php echo $sudahSusut ? 'disabled' : ''; ?>>
                <?php foreach (array('GARIS_LURUS' => 'Garis lurus', 'SALDO_MENURUN' => 'Saldo menurun ganda', 'TIDAK' => 'Tidak disusutkan (tanah)') as $k => $l) { ?><option value="<?php echo $k; ?>" <?php echo $v('metode', 'GARIS_LURUS') === $k ? 'selected' : ''; ?>><?php echo $l; ?></option><?php } ?></select>
                <?php if ($sudahSusut) { ?><input type="hidden" name="metode" value="<?php echo $a->metode; ?>"><?php } ?></div>
            <div class="form-group col-md-2"><label>Umur (bulan)</label><input type="number" name="umur_bulan" class="form-control" min="0" value="<?php echo (int) $v('umur_bulan', 48); ?>"></div>
            <div class="form-group col-md-6"><label>Lokasi</label><input type="text" name="lokasi" class="form-control" value="<?php echo html_escape($v('lokasi', (string) $this->session->userdata('lokasi'))); ?>" maxlength="50"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4"><label>Akun Aset</label><select name="akun_aset" class="form-control select2" required><?php echo $o(array('ASET_TETAP', 'ASET_LAIN'), $v('akun_aset')); ?></select></div>
            <div class="form-group col-md-4"><label>Akun Akumulasi Penyusutan</label><select name="akun_akumulasi" class="form-control select2" required><?php echo $o(array('AKUM_SUSUT', 'ASET_TETAP'), $v('akun_akumulasi', $def['akum'])); ?></select></div>
            <div class="form-group col-md-4"><label>Akun Beban Penyusutan</label><select name="akun_beban" class="form-control select2" required><?php echo $o(array('BEBAN', 'HPP', 'BEBAN_LAIN'), $v('akun_beban', $def['beban'])); ?></select></div>
        </div>
        <?php if ( ! $a) { ?>
        <div class="form-row align-items-end">
            <div class="form-group col-md-5"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="cb-jp" name="jurnal_perolehan" value="1"><label class="custom-control-label" for="cb-jp">Buat jurnal perolehan aset</label></div>
                <small class="text-muted">Kosongkan bila pembelian aset sudah dicatat lewat Kas &amp; Bank / Hutang.</small></div>
            <div class="form-group col-md-7"><label>Dibayar dari / lawan</label><select name="akun_lawan_perolehan" class="form-control select2"><?php echo $o(array('KAS_BANK', 'HUTANG', 'LIAB_PENDEK', 'LIAB_PANJANG', 'EKUITAS'), ''); ?></select></div>
        </div>
        <?php } ?>
        <div class="form-group"><label>Keterangan</label><input type="text" name="keterangan" class="form-control" value="<?php echo html_escape($v('keterangan')); ?>" maxlength="255"></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button></div>
</form>
