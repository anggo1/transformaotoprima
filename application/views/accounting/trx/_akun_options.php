<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* $akun (array obj), $pilih (kode terpilih), $hanya (array kategori opsional), $kecuali (array kategori opsional) */
$pilih = isset($pilih) ? (string) $pilih : '';
$grup = array();
foreach ($akun as $a) {
    if (isset($a->is_active) && $a->is_active === 'N' && $a->kode_akun !== $pilih) continue;
    if ( ! empty($hanya) && ! in_array($a->kategori, $hanya, true)) continue;
    if ( ! empty($kecuali) && in_array($a->kategori, $kecuali, true)) continue;
    $grup[Akuntansi::label_kategori($a->kategori)][] = $a;
}
?>
<option value="">— pilih akun —</option>
<?php foreach ($grup as $g => $list) { ?>
<optgroup label="<?php echo html_escape($g); ?>">
    <?php foreach ($list as $a) { ?>
    <option value="<?php echo html_escape($a->kode_akun); ?>"<?php echo $a->kode_akun === $pilih ? ' selected' : ''; ?>><?php echo html_escape($a->kode_akun . ' — ' . $a->nama_akun); ?></option>
    <?php } ?>
</optgroup>
<?php } ?>
