<?php

if (! function_exists('hitung_umur')) {
	function hitung_umur($tgl)
	{
		$tanggal = new DateTime($tgl);
		$today = new DateTime('today');
		$y = $today->diff($tanggal)->y;
		$m = $today->diff($tanggal)->m;
		$d = $today->diff($tanggal)->d;
		return $y . " Tahun, " . $m . " Bulan, " . $d . " Hari";
		//return $y . " , " . $m;
	}
}

if (! function_exists('anti_injection')) {
	function anti_injection($data)
	{
		$filter = stripslashes(strip_tags(htmlspecialchars((string) $data, ENT_QUOTES)));
		return $filter;
	}
}

/**
 * number_format yang aman untuk PHP 8.x
 * PHP 8 melempar TypeError bila argumen number_format() berupa string kosong
 * / non-numerik (mis. kolom varchar ''), sedangkan PHP 7 hanya warning.
 * Semua pemanggilan number_format() di view/controller diarahkan ke fungsi ini.
 */
if (! function_exists('nf')) {
	function nf($angka, $desimal = 0, $pemisah_desimal = '.', $pemisah_ribuan = ',')
	{
		if ($angka === null || $angka === '') {
			$angka = 0;
		} elseif (! is_int($angka) && ! is_float($angka)) {
			$angka = str_replace(',', '', trim((string) $angka));
			$angka = is_numeric($angka) ? (float) $angka : 0;
		}
		return number_format((float) $angka, (int) $desimal, (string) $pemisah_desimal, (string) $pemisah_ribuan);
	}
}

/** Konversi angka dari input berformat (1,250,000.50) menjadi float */
if (! function_exists('to_number')) {
	function to_number($nilai)
	{
		if (is_int($nilai) || is_float($nilai)) return (float) $nilai;
		$nilai = str_replace(array(',', ' '), '', trim((string) $nilai));
		return is_numeric($nilai) ? (float) $nilai : 0.0;
	}
}

/** Format tanggal dd-mm-yyyy -> yyyy-mm-dd (aman bila format sudah Y-m-d) */
if (! function_exists('tgl_sql')) {
	function tgl_sql($tgl)
	{
		$tgl = trim((string) $tgl);
		if ($tgl === '') return null;
		if (preg_match('/^\d{4}-\d{2}-\d{2}/', $tgl)) return substr($tgl, 0, 10);
		$p = preg_split('/[-\/.]/', $tgl);
		if (count($p) === 3) return sprintf('%04d-%02d-%02d', (int) $p[2], (int) $p[1], (int) $p[0]);
		return null;
	}
}

if (! function_exists('slug')) {
function slug($s)
{
	$c = array(' ');
	$d = array('-', '/', '\\', ',', '.', '#', ':', ';', '\'', '"', '[', ']', '{', '}', ')', '(', '|', '`', '~', '!', '@', '%', '$', '^', '&', '*', '=', '?', '+');

	$s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d

	$s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
	return $s;
}
}


if (! function_exists('rupiah')) {
	function rupiah($nominal)
	{
		return 'Rp ' . nf($nominal, 0, ',', '.');
	}
}

/* ---------- tanggal Indonesia (dipakai modul akuntansi) ---------- */
if (! function_exists('bulan_panjang')) {
	function bulan_panjang($b)
	{
		$n = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
		$b = (int) $b;
		return isset($n[$b]) ? $n[$b] : '';
	}
}
if (! function_exists('bulan_singkat')) {
	function bulan_singkat($b)
	{
		$n = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
		$b = (int) $b;
		return isset($n[$b]) ? $n[$b] : '';
	}
}
/** 2026-11-05 -> 5 November 2026 */
if (! function_exists('tgl_id')) {
	function tgl_id($tgl)
	{
		if (! $tgl || substr((string) $tgl, 0, 4) === '0000') return '-';
		$t = strtotime($tgl);
		return $t ? date('j', $t) . ' ' . bulan_panjang(date('n', $t)) . ' ' . date('Y', $t) : '-';
	}
}
/** 2026-11-05 -> 05 Nov 2026 */
if (! function_exists('tgl_singkat')) {
	function tgl_singkat($tgl)
	{
		if (! $tgl || substr((string) $tgl, 0, 4) === '0000') return '-';
		$t = strtotime($tgl);
		return $t ? date('d', $t) . ' ' . bulan_singkat(date('n', $t)) . ' ' . date('Y', $t) : '-';
	}
}
/** angka akuntansi: negatif dalam kurung */
if (! function_exists('nf_acc')) {
	function nf_acc($n, $dec = 0)
	{
		$n = (float) $n;
		if (abs($n) < 0.005) return '-';
		return $n < 0 ? '(' . nf(-$n, $dec) . ')' : nf($n, $dec);
	}
}

/** nilai sel laporan: angka mentah untuk Excel, format akuntansi untuk layar/cetak */
if (! function_exists('acc_val')) {
	function acc_val($n, $mode = 'screen', $dec = 0)
	{
		if ($mode === 'excel') return round((float) $n, 2);
		$s = nf_acc($n, $dec);
		return ((float) $n < -0.004) ? '<span class="acc-neg">' . $s . '</span>' : $s;
	}
}

/** login codeIgniter menggunakan bycrypt **/

if (!function_exists('get_hash')) {
	function get_hash($PlainPassword)
	{
		$option = [
			'cost' => 5, // proses hash sebanyak: 2^5 = 32x
		];
		return password_hash($PlainPassword, PASSWORD_DEFAULT, $option);
	}
}

if (!function_exists('hash_verified')) {
	function hash_verified($PlainPassword, $HashPassword)
	{
		return password_verify($PlainPassword, $HashPassword) ? true : false;
	}
}

/** login codeIgniter menggunakan bycrypt **/
function show_ok_msg($content = '', $size = '14px')
{
	if ($content != '') {
		return   $content;
	}
}

function show_del_msg($content = '', $size = '14px')
{
	if ($content != '') {
		return   $content;
	}
}
// dipakai di beberapa controller (Aplikasi, Settingwh, SettingHrd, Account) namun
// sebelumnya tidak pernah didefinisikan -> Fatal Error di PHP 8
if (! function_exists('show_succ_msg')) {
	function show_succ_msg($content = '', $size = '14px')
	{
		if ($content != '') {
			return $content;
		}
	}
}
function show_err_msg($content = '', $size = '20px')
{
	if ($content != '') {
		return   $content;
	}
}
function show_err_msg2($content = '', $size = '14px')
{
	if ($content != '') {
		return   '<p class="box-msg">
				      <div class="alert alert-danger alert-dismissible">
					      <div class="info-box-icon">
					      	<i class="fa fa-warning"></i>
					      </div>
					      <div class="info-box-content" style="font-size:' . $size . '">
				        	' . $content
			. '</div>
					  </div>
				    </p>';
	}
}

// MODAL
function show_my_print($content = '', $id = '', $data = '', $size = '')
{
	$_ci = &get_instance();

	if ($content != '') {
		$view_content = seimbangkan_div($_ci->load->view($content, $data, TRUE));

		return '<div class="modal fade" id="' . $id . '" role="dialog">
					  <div class="modal-dialog modal-' . $size . '" role="document">
					    <div class="modal-content" style="border: none;">
					        ' . $view_content . '
					    </div>
					  </div>
					</div>';
	}
}
/**
 * Rapikan tag <div> pada potongan HTML modal: beberapa file modal lama memiliki
 * </div> berlebih yang menutup kerangka halaman sehingga konten "keluar" dari
 * area utama (tampil di bawah sidebar). Kelebihan </div> dibuang dari belakang,
 * kekurangan ditambahkan di akhir.
 */
function seimbangkan_div($html)
{
	$buka = preg_match_all('/<div\b/i', $html);
	$tutup = preg_match_all('/<\/div\s*>/i', $html);
	while ($tutup > $buka) {
		$pos = strripos($html, '</div>');
		if ($pos === false) break;
		$html = substr($html, 0, $pos) . substr($html, $pos + 6);
		$tutup--;
	}
	if ($buka > $tutup) $html .= str_repeat('</div>', $buka - $tutup);
	return $html;
}

function show_my_modal($content = '', $id = '', $data = '', $size = '')
{
	$_ci = &get_instance();

	if ($content != '') {
		$view_content = seimbangkan_div($_ci->load->view($content, $data, TRUE));

		return '<div class="modal fade" id="' . $id . '" role="dialog">
					  <div class="modal-dialog modal-' . $size . '" role="document">
					    <div class="modal-content">
					        ' . $view_content . '
					    </div>
					  </div>
					</div>';
	}
}
function show_my_display($content = '', $id = '', $data = '', $size = '')
{
	$_ci = &get_instance();

	if ($content != '') {
		$view_content = seimbangkan_div($_ci->load->view($content, $data, TRUE));

		return $view_content;
	}
}

function show_my_confirm($id = '', $class = '', $title = 'Konfirmasi', $yes = 'Ya', $no = 'Tidak')
{
	$_ci = &get_instance();

	if ($id != '') {
		echo '
			<div class="modal fade" id="' . $id . '">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header bg-yellow">
						<h4 class="modal-title">PERINGATAN !!</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</div>
								<div class="modal-body">
								<p>' . $title . '</p>
								</div>
								<div class="modal-footer justify-content-between">
								
								<button class="btn btn-sm btn-primary ' . $class . '"> <i class="fas fa-hand-paper"></i> ' . $yes . '</button>
									<button class="btn btn-sm btn-danger float-right" data-dismiss="modal"> <i class="fas fa-times"></i> ' . $no . '</button>
						</div>
					</div>
				</div>
			</div>';
	}
}
function penyebut($nilai)
{
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " " . $huruf[$nilai];
	} else if ($nilai < 20) {
		$temp = penyebut($nilai - 10) . " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
	}
	return $temp;
}

function terbilang($nilai)
{
	if ($nilai < 0) {
		$hasil = "minus " . trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}
	return $hasil;
}
function hitungTotalKerja($jamMasuk, $jamKeluar, $istirahatMenit = 60)
{
	$mulai = new DateTime($jamMasuk);
	$selesai = new DateTime($jamKeluar);

	$interval = $mulai->diff($selesai);

	// Total menit = (jam * 60) + menit
	$totalMenit = ($interval->h * 60) + $interval->i;
	$jamKerjaBersih = $totalMenit - $istirahatMenit;

	// Konversi kembali ke jam dan menit
	$jam = floor($jamKerjaBersih / 60);
	$menit = $jamKerjaBersih % 60;

	return $jam . "." . $menit;
}

/** jumlah barang: tanpa desimal bila bulat; angka mentah untuk Excel */
if (! function_exists('nf_qty')) {
	function nf_qty($n, $mode = 'screen')
	{
		$n = (float) $n;
		if ($mode === 'excel') return round($n, 3);
		if (abs($n) < 0.0005) return '-';
		$s = nf($n, (abs($n - round($n)) < 0.0005) ? 0 : 2);
		return $n < 0 ? '<span class="acc-neg">' . $s . '</span>' : $s;
	}
}
