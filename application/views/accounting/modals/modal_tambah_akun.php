<?php defined('BASEPATH') OR exit('No direct script access allowed');
$k = ! empty($dataAkun) ? $dataAkun[0] : null;
$v = function ($f, $d = '') use ($k) { return $k && isset($k->$f) ? $k->$f : $d; };
$edit = $k && ! empty($k->id);
$katNow = $v('kategori', '');
?>
<form id="<?php echo $edit ? 'form-update-akun' : 'form-tambah-akun'; ?>" method="POST" autocomplete="off">
    <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-<?php echo $edit ? 'edit' : 'plus-circle'; ?> mr-2 text-primary"></i><?php echo $edit ? 'Ubah Akun ' . html_escape($k->kode_akun) : 'Tambah Akun'; ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-msg alert alert-danger py-2" style="display:none"></div>
        <input type="hidden" name="id" value="<?php echo (int) $v('id', 0); ?>">
        <div class="form-row">
            <div class="form-group col-md-4"><label>Nomor Akun</label>
                <input type="text" class="form-control" name="kode_akun" value="<?php echo html_escape($v('kode_akun')); ?>" maxlength="25" required <?php echo $edit ? 'readonly' : ''; ?>></div>
            <div class="form-group col-md-8"><label>Nama Akun</label>
                <input type="text" class="form-control" name="nama_akun" value="<?php echo html_escape($v('nama_akun')); ?>" maxlength="100" required></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6"><label>Tipe Akun <small class="text-muted">(menentukan posisi di laporan)</small></label>
                <select name="kategori" class="form-control">
                    <option value="">— otomatis dari nomor akun —</option>
                    <?php $g = null; foreach (Akuntansi::$KATEGORI as $kk => $info) { if ($g !== $info[1]) { if ($g !== null) echo '</optgroup>'; $g = $info[1]; echo '<optgroup label="' . $g . '">'; } ?>
                    <option value="<?php echo $kk; ?>" <?php echo $katNow === $kk ? 'selected' : ''; ?>><?php echo $info[0]; ?> (saldo normal <?php echo $info[2] === 'D' ? 'Debit' : 'Kredit'; ?>)</option>
                    <?php } echo '</optgroup>'; ?>
                </select></div>
            <div class="form-group col-md-6"><label>Sub dari akun <small class="text-muted">(opsional)</small></label>
                <select name="parent_kode" class="form-control">
                    <option value="">— akun induk —</option>
                    <?php foreach ($semuaAkun as $a) { if ($edit && $a->kode_akun === $k->kode_akun) continue; ?>
                    <option value="<?php echo html_escape($a->kode_akun); ?>" <?php echo $v('parent_kode') === $a->kode_akun ? 'selected' : ''; ?>><?php echo html_escape($a->kode_akun . ' — ' . $a->nama_akun); ?></option>
                    <?php } ?>
                </select></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4"><label>Kelompok</label>
                <select name="kelompok" class="form-control"><option value="">—</option>
                    <?php foreach ($dataKl as $kl) { ?><option value="<?php echo $kl->id_kelompok; ?>" <?php echo (string) $v('kelompok') === (string) $kl->id_kelompok ? 'selected' : ''; ?>><?php echo html_escape($kl->kelompok); ?></option><?php } ?>
                </select></div>
            <div class="form-group col-md-4"><label>Type</label>
                <select name="type" class="form-control"><option value="">—</option>
                    <?php foreach ($dataType as $ty) { ?><option value="<?php echo $ty->id_type; ?>" <?php echo (string) $v('type_akun') === (string) $ty->id_type ? 'selected' : ''; ?>><?php echo html_escape($ty->type); ?></option><?php } ?>
                </select></div>
            <div class="form-group col-md-4"><label>Jenis Beban</label>
                <select name="jns_beban" class="form-control"><option value="">—</option>
                    <?php foreach ($dataJn as $jn) { ?><option value="<?php echo $jn->id_jenis; ?>" <?php echo (string) $v('jenis_beban') === (string) $jn->id_jenis ? 'selected' : ''; ?>><?php echo html_escape($jn->jenis_beban); ?></option><?php } ?>
                </select></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12"><label>Perlakuan Pajak (fiskal) <small class="text-muted">— dipakai laporan Rekonsiliasi Fiskal / SPT PPh Badan</small></label>
                <select name="fiskal" class="form-control">
                    <?php foreach (Akuntansi::$FISKAL as $fk => $fi) { ?><option value="<?php echo $fk; ?>" <?php echo (string) $v('fiskal', '') === $fk ? 'selected' : ''; ?>><?php echo $fi[0]; ?></option><?php } ?>
                </select>
                <small class="form-text text-muted">Contoh koreksi positif: sumbangan, denda/sanksi pajak, jamuan tanpa daftar nominatif, biaya pribadi pemilik, beban PPh. Contoh penghasilan final: bunga deposito/jasa giro, sewa tanah &amp; bangunan.</small></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4"><label>Status</label>
                <select name="is_active" class="form-control"><option value="Y">Aktif</option><option value="N" <?php echo $v('is_active', 'Y') === 'N' ? 'selected' : ''; ?>>Nonaktif</option></select></div>
            <div class="form-group col-md-8"><label>Keterangan</label><input type="text" class="form-control" name="keterangan" value="<?php echo html_escape($v('keterangan')); ?>" maxlength="255"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-1"></i> Simpan</button>
    </div>
</form>
