<?php

function Parse_Data($data,$p1,$p2){
    $data=" ".$data;
    $hasil="";
    $awal=strpos($data,$p1);
    if($awal!=""){
    $akhir=strpos(strstr($data,$p1),$p2);
        if($akhir!=""){
        $hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
        }
    }
    return $hasil; 
}

function to_currency($number)
{
    if($number >= 0)
    {
        return 'Rp. '.number_format($number, 0, ',', '.');
    }
    else
    {
        return 'Rp. '.number_format(abs($number), 0, ',', '.');
    }
}

function bilangan($number)
{
    if($number > 0)
    {
        return number_format($number, 0, ',', '.');
    }
    else
    {
        return '0';
    }
}
//function rupiah($number)
//{
//    if ($number==0) return '-';
//    return to_currency($number);
//}

function to_pdf($html, $filename='', $stream=TRUE) 
{
    require_once(APPPATH."libraries/dompdf/dompdf_config.inc.php");
    
    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf");
    } else {
        return $dompdf->output();
    }
}

function cetak_report($html,$out)
{
	if ($out=='pdf')
		to_pdf($html,'Report'.rand(100,1000));
	else
		echo $html;

}

function nomor_pasien($id)
{
	return sprintf("%05d",$id); 
}

function nama_bulan($i)
{
    $bulan = array(
        'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
    );

    return $bulan[$i-1];
}

function bulan_romawi($i)
{
    $bulan = array(
        'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'
    );

    return $bulan[$i-1];
}

function tanggal($tanggal)
{
return date('d',strtotime($tanggal)).'  '.nama_bulan(date('m',strtotime($tanggal))).' '.date('Y',strtotime($tanggal));
	  			
}

function t($v)
{
    if ((int)$v==0)
        return "-";
    return (int)$v;

}

function tahun_akademik($str){
	$r = substr($str, -1);
	$t = substr($str,0, -1);
	if($r==1)
		return $t.' GANJIL';
	else
		return $t.' GENAP';

}

function beda_tanggal($start,$end = false) {
    if(!$end) { $end = date('Y-m-d'); }
    return round((strtotime($start)-strtotime($end))/86400);
}
