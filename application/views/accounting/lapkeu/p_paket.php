<?php defined('BASEPATH') OR exit('No direct script access allowed');
$sec = 0;
$bagian = function ($judul, $sub) use (&$sec, $mode) {
    $sec++;
    $pb = ($mode === 'print' && $sec > 1) ? ' style="page-break-before:always"' : '';
    echo '<div class="acc-paket__sec"' . $pb . '><h4 style="margin:' . ($sec > 1 ? '2rem' : '0') . ' 0 .2rem;font-size:1.05rem;font-weight:800">' . $judul . '</h4><div class="text-muted small mb-2">' . $sub . '</div>';
};
$tutup = function () { echo '</div>'; };
$C = $d['calk'];
$noCalk = array(); foreach ($C['catatan'] as $c) $noCalk[$c->kat] = $c->no;
?>
<?php if ($mode !== 'excel') { ?>
<div class="small text-muted mb-3">Isi: 1. Laporan Posisi Keuangan (Neraca) · 2. Laporan Laba Rugi · 3. Laporan Perubahan Ekuitas · 4. Laporan Arus Kas · 5. Catatan atas Laporan Keuangan. Kolom pembanding Neraca = sehari sebelum awal periode.</div>
<?php } ?>

<?php $bagian('1. LAPORAN POSISI KEUANGAN (NERACA)', 'Per ' . tgl_id($d['neraca']['tanggal'][0]) . ' dan ' . tgl_id($d['neraca']['tanggal'][1]));
$this->load->view('accounting/lapkeu/p_neraca', array('d' => $d['neraca'], 'f' => $f, 'mode' => $mode, 'kunci' => 'neraca'));
$tutup(); ?>

<?php $bagian('2. LAPORAN LABA RUGI', 'Periode ' . tgl_id($f['dari']) . ' s/d ' . tgl_id($f['sampai']));
$this->load->view('accounting/lapkeu/p_laba_rugi', array('d' => $d['lr'], 'f' => $f, 'mode' => $mode, 'kunci' => 'laba_rugi'));
$tutup(); ?>

<?php $bagian('3. LAPORAN PERUBAHAN EKUITAS', 'Periode ' . tgl_id($f['dari']) . ' s/d ' . tgl_id($f['sampai']));
$this->load->view('accounting/lapkeu/p_perubahan_ekuitas', array('d' => $d['pe'], 'f' => $f, 'mode' => $mode, 'kunci' => 'perubahan_ekuitas'));
$tutup(); ?>

<?php $bagian('4. LAPORAN ARUS KAS', 'Metode langsung, periode ' . tgl_id($f['dari']) . ' s/d ' . tgl_id($f['sampai']));
$this->load->view('accounting/lapkeu/p_arus_kas', array('d' => $d['ak'], 'f' => $f, 'mode' => $mode, 'kunci' => 'arus_kas'));
$tutup(); ?>

<?php $bagian('5. CATATAN ATAS LAPORAN KEUANGAN', 'Penjelasan pos-pos laporan di atas');
$this->load->view('accounting/lapkeu/p_calk', array('C' => $C, 'f' => $f, 'mode' => $mode));
$tutup(); ?>
