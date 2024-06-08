<?php
$server = "localhost";
$username = "root";
$password = "sql2010sj";
$database = "db_body_repair";
date_default_timezone_set('Asia/Jakarta');
  $jam_ini=date('H:i:s');
	$hari_ini = date('Y-m-d');
	
	
 
$kini = new DateTime('now');   

$konek = mysql_connect($server, $username, $password) or die ("Gagal konek ke server MySQL" .mysql_error());
$bukadb = mysql_select_db($database) or die ("Gagal membuka database $database" .mysql_error());
$barangnya=mysql_query("select * from pk_aktif where status='N'");
						while($rowket=mysql_fetch_array($barangnya)){
						$id_detail=$rowket['id_detail'];
						$id_pk=$rowket['id_pk'];
						$id_bay=$rowket['no_bay'];
						}
$warna_hijau = "background: #9dd53a;background: -moz-linear-gradient(top, #9dd53a 0%, #a1d54f 50%, #80c217 51%, #7cbc0a 100%);background: webkit-linear-gradient(top, #9dd53a 0%,#a1d54f 50%,#80c217 51%,#7cbc0a 100%);background: linear-gradient(to bottom, #9dd53a 0%,#a1d54f 50%,#80c217 51%,#7cbc0a 100%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 );";
$warna_kuning="background: #fcf4c0; /* Old browsers */
background: -moz-linear-gradient(top, #fcf4c0 0%, #fdea59 50%, #ffe30b 51%, #fcf09a 100%); 
background: -webkit-linear-gradient(top, #fcf4c0 0%,#fdea59 50%,#ffe30b 51%,#fcf09a 100%);
background: linear-gradient(to bottom, #fcf4c0 0%,#fdea59 50%,#ffe30b 51%,#fcf09a 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcf4c0', endColorstr='#fcf09a',GradientType=0 );";
?>
<script type="text/JavaScript">
<!--
<!--setTimeout("location.href = 'index.php';",1000*120 );
-->
<!--

/*
Auto Refresh Page with Time script
By JavaScript Kit (javascriptkit.com)
Over 200+ free scripts here!
*/

//enter refresh time in "minutes:seconds" Minutes should range from 0 to inifinity. Seconds should range from 0 to 59
var limit="0:30"

if (document.images){
var parselimit=limit.split(":")
parselimit=parselimit[0]*60+parselimit[1]*4
}
function beginrefresh(){
if (!document.images)
return
if (parselimit==1)
window.location.reload()
else{ 
parselimit-=1
curmin=Math.floor(parselimit/60)
cursec=parselimit%60
if (curmin!=0)
curtime=curmin+" minutes and "+cursec+" seconds left until page refresh!"
else
curtime=cursec+" seconds left until page refresh!"
window.status=curtime
setTimeout("beginrefresh()",2000)
}
}

window.onload=beginrefresh
//-->
</script>
<?php 
	$jam_mulai_pt=date('H:i');
  	$tgl_mulai_pt=date('Y-m-d');
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '41' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay41 = $hasil_jatah ['no_bay'];
							 $bay_body41 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk41 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj41 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk41'";
	$hasil_j41 = mysql_query($sql_ptj41);$data_j41  = mysql_fetch_array($hasil_j41);			 
	$tgl41 = new DateTime($data_j41['tgl_masuk']); 						 
	$sql_pk41A = mysql_query("select * from pk_aktif WHERE no_bay = '41' AND status !='S'"); $jumlah_pk41A = mysql_num_rows($sql_pk41A);
	
	$sql_pt41 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk41' AND status ='N'";
	$hasil_41 = mysql_query($sql_pt41);$data_41  = mysql_fetch_array($hasil_41);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '42' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay42 = $hasil_jatah ['no_bay'];
							 $bay_body42 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk42 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj42 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk42'";
	$hasil_j42 = mysql_query($sql_ptj42);$data_j42  = mysql_fetch_array($hasil_j42);			 
	$tgl42 = new DateTime($data_j42['tgl_masuk']); 						 
	$sql_pk42A = mysql_query("select * from pk_aktif WHERE no_bay = '42' AND status !='S'"); $jumlah_pk42A = mysql_num_rows($sql_pk42A);
	
	$sql_pt42 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk42' AND status ='N'";
	$hasil_42 = mysql_query($sql_pt42);$data_42  = mysql_fetch_array($hasil_42);
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '43' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay43 = $hasil_jatah ['no_bay'];
							 $bay_body43 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk43 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj43 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk43'";
	$hasil_j43 = mysql_query($sql_ptj43);$data_j43  = mysql_fetch_array($hasil_j43);			 
	$tgl43 = new DateTime($data_j43['tgl_masuk']); 					 
	$sql_pk43A = mysql_query("select * from pk_aktif WHERE no_bay = '43' AND status !='S'"); $jumlah_pk43A = mysql_num_rows($sql_pk43A);
	
	$sql_pt43 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk43' AND status ='N'";
	$hasil_43 = mysql_query($sql_pt43);$data_43  = mysql_fetch_array($hasil_43);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '44' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay44 = $hasil_jatah ['no_bay'];
							 $bay_body44 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk44 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj44 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk44'";
	$hasil_j44 = mysql_query($sql_ptj44);$data_j44  = mysql_fetch_array($hasil_j44);			 
	$tgl44 = new DateTime($data_j44['tgl_masuk']); 					 
	$sql_pk44A = mysql_query("select * from pk_aktif WHERE no_bay = '44' AND status !='S'"); $jumlah_pk44A = mysql_num_rows($sql_pk44A);
	
	$sql_pt44 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk44' AND status ='N'";
	$hasil_44 = mysql_query($sql_pt44);$data_44  = mysql_fetch_array($hasil_44);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '45' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay45 = $hasil_jatah ['no_bay'];
							 $bay_body45 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk45 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj45 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk45'";
	$hasil_j45 = mysql_query($sql_ptj45);$data_j45  = mysql_fetch_array($hasil_j45);			 
	$tgl45 = new DateTime($data_j45['tgl_masuk']); 						 
	$sql_pk45A = mysql_query("select * from pk_aktif WHERE no_bay = '45' AND status !='S'"); $jumlah_pk45A = mysql_num_rows($sql_pk45A);
	
	$sql_pt45 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk45' AND status ='N'";
	$hasil_45 = mysql_query($sql_pt45);$data_45  = mysql_fetch_array($hasil_45);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '36' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay36 = $hasil_jatah ['no_bay'];
							 $bay_body36 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk36 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj36 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk36'";
	$hasil_j36 = mysql_query($sql_ptj36);$data_j36  = mysql_fetch_array($hasil_j36);			 
	$tgl36 = new DateTime($data_j36['tgl_masuk']); 	
							 					 
	$sql_pk36A = mysql_query("select * from pk_aktif WHERE no_bay = '36' AND status !='S'"); $jumlah_pk36A = mysql_num_rows($sql_pk36A);
	
	$sql_pt36 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk36' AND status ='N'";
	$hasil_36 = mysql_query($sql_pt36);$data_36  = mysql_fetch_array($hasil_36);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '37' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay37 = $hasil_jatah ['no_bay'];
							 $bay_body37 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk37 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj37 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk37'";
	$hasil_j37 = mysql_query($sql_ptj37);$data_j37  = mysql_fetch_array($hasil_j37);			 
	$tgl37 = new DateTime($data_j37['tgl_masuk']); 					 
	$sql_pk37A = mysql_query("select * from pk_aktif WHERE no_bay = '37' AND status !='S'"); $jumlah_pk37A = mysql_num_rows($sql_pk37A);
	
	$sql_pt37 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk37' AND status ='N'";
	$hasil_37 = mysql_query($sql_pt37);$data_37  = mysql_fetch_array($hasil_37);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '38' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay38 = $hasil_jatah ['no_bay'];
							 $bay_body38 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk38 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj38 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk38'";
	$hasil_j38 = mysql_query($sql_ptj38);$data_j38  = mysql_fetch_array($hasil_j38);			 
	$tgl38 = new DateTime($data_j38['tgl_masuk']); 						 
	$sql_pk38A = mysql_query("select * from pk_aktif WHERE no_bay = '38' AND status !='S'"); $jumlah_pk38A = mysql_num_rows($sql_pk38A);
	
	$sql_pt38 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk38' AND status ='N'";
	$hasil_38 = mysql_query($sql_pt38);$data_38  = mysql_fetch_array($hasil_38);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '39' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay39 = $hasil_jatah ['no_bay'];
							 $bay_body39 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk39 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj39 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk39'";
	$hasil_j39 = mysql_query($sql_ptj39);$data_j39  = mysql_fetch_array($hasil_j39);			 
	$tgl39 = new DateTime($data_j39['tgl_masuk']); 						 
	$sql_pk39A = mysql_query("select * from pk_aktif WHERE no_bay = '39' AND status !='S'"); $jumlah_pk39A = mysql_num_rows($sql_pk39A);
	
	$sql_pt39 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk39' AND status ='N'";
	$hasil_39 = mysql_query($sql_pt39);$data_39  = mysql_fetch_array($hasil_39);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '40' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay40 = $hasil_jatah ['no_bay'];
							 $bay_body40 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk40 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj40 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk40'";
	$hasil_j40 = mysql_query($sql_ptj40);$data_j40  = mysql_fetch_array($hasil_j40);			 
	$tgl40 = new DateTime($data_j40['tgl_masuk']); 					 
	$sql_pk40A = mysql_query("select * from pk_aktif WHERE no_bay = '40' AND status !='S'"); $jumlah_pk40A = mysql_num_rows($sql_pk40A);
	
	$sql_pt40 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk40' AND status ='N'";
	$hasil_40 = mysql_query($sql_pt40);$data_40  = mysql_fetch_array($hasil_40);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '35' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay35 = $hasil_jatah ['no_bay'];
							 $bay_body35 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk35 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj35 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk35'";
	$hasil_j35 = mysql_query($sql_ptj35);$data_j35  = mysql_fetch_array($hasil_j35);			 
	$tgl35 = new DateTime($data_j35['tgl_masuk']); 					 
	$sql_pk35A = mysql_query("select * from pk_aktif WHERE no_bay = '35' AND status !='S'"); $jumlah_pk35A = mysql_num_rows($sql_pk35A);
	
	$sql_pt35 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk35' AND status ='N'";
	$hasil_35 = mysql_query($sql_pt35);$data_35  = mysql_fetch_array($hasil_35);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '34' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay34 = $hasil_jatah ['no_bay'];
							 $bay_body34 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk34 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj34 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk34'";
	$hasil_j34 = mysql_query($sql_ptj34);$data_j34  = mysql_fetch_array($hasil_j34);			 
	$tgl34 = new DateTime($data_j34['tgl_masuk']); 					 
	$sql_pk34A = mysql_query("select * from pk_aktif WHERE no_bay = '34' AND status !='S'"); $jumlah_pk34A = mysql_num_rows($sql_pk34A);
	
	$sql_pt34 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk34' AND status ='N'";
	$hasil_34 = mysql_query($sql_pt34);$data_34  = mysql_fetch_array($hasil_34);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '32' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay32 = $hasil_jatah ['no_bay'];
							 $bay_body32 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk32 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj32= "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk30'";
	$hasil_j32= mysql_query($sql_ptj30);$data_j32= mysql_fetch_array($hasil_j30);			 
	$tgl32= new DateTime($data_j32['tgl_masuk']); 						 
	$sql_pk32A = mysql_query("select * from pk_aktif WHERE no_bay = '32' AND status !='S'"); $jumlah_pk32A = mysql_num_rows($sql_pk32A);
	
	$sql_pt32 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk32' AND status ='N'";
	$hasil_32 = mysql_query($sql_pt32);$data_32  = mysql_fetch_array($hasil_32);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '33' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay33 = $hasil_jatah ['no_bay'];
							 $bay_body33 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk33 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj33 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk33'";
	$hasil_j33 = mysql_query($sql_ptj33);$data_j33  = mysql_fetch_array($hasil_j33);			 
	$tgl33 = new DateTime($data_j33['tgl_masuk']); 						 
	$sql_pk33A = mysql_query("select * from pk_aktif WHERE no_bay = '33' AND status !='S'"); $jumlah_pk33A = mysql_num_rows($sql_pk33A);
	
	$sql_pt33 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk33' AND status ='N'";
	$hasil_33 = mysql_query($sql_pt33);$data_33  = mysql_fetch_array($hasil_33);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '31' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay31 = $hasil_jatah ['no_bay'];
							 $bay_body31 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk31 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj31 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk31'";
	$hasil_j31 = mysql_query($sql_ptj31);$data_j31  = mysql_fetch_array($hasil_j31);			 
	$tgl31 = new DateTime($data_j31['tgl_masuk']); 						 
	$sql_pk31A = mysql_query("select * from pk_aktif WHERE no_bay = '31' AND status !='S'"); $jumlah_pk31A = mysql_num_rows($sql_pk31A);	
	$sql_pt31 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk31' AND status ='N'";
	$hasil_31 = mysql_query($sql_pt31);$data_31  = mysql_fetch_array($hasil_31);}
	
							
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '30' AND status !='S' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){ 
							 $bay30 = $hasil_jatah ['no_bay'];
							 $bay_body30 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk30 = $hasil_jatah ['id_pk'];									 							
	$sql_ptj30 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk30'";
	$hasil_j30 = mysql_query($sql_ptj30);$data_j30  = mysql_fetch_array($hasil_j30);			 
	$tgl30 = new DateTime($data_j30['tgl_masuk']); 			
				
	$sql_pk30A = mysql_query("select * from pk_aktif WHERE no_bay = '30' AND status !='S'"); $jumlah_pk30A = mysql_num_rows($sql_pk30A);
	
	$sql_pt30 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk30' AND status ='N'";
	$hasil_30 = mysql_query($sql_pt30);$data_30  = mysql_fetch_array($hasil_30);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '29' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay29 = $hasil_jatah ['no_bay'];
							 $bay_body29 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk29 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj29 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk29'";
	$hasil_j29 = mysql_query($sql_ptj29);$data_j29  = mysql_fetch_array($hasil_j29);			 
	$tgl29 = new DateTime($data_j29['tgl_masuk']); 									 
	$sql_pk29A = mysql_query("select * from pk_aktif WHERE no_bay = '29' AND status !='S'"); $jumlah_pk29A = mysql_num_rows($sql_pk29A);
	$sql_pt29 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk29' AND status ='N'";
	$hasil_29 = mysql_query($sql_pt29);$data_29  = mysql_fetch_array($hasil_29);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '28' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay28 = $hasil_jatah ['no_bay'];
							 $bay_body28 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk28 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj28 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk28'";
	$hasil_j28 = mysql_query($sql_ptj28);$data_j28  = mysql_fetch_array($hasil_j28);			 
	$tgl28 = new DateTime($data_j28['tgl_masuk']); 									 
	$sql_pk28A = mysql_query("select * from pk_aktif WHERE no_bay = '28' AND status !='S'"); $jumlah_pk28A = mysql_num_rows($sql_pk28A);
	$sql_pt28 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk28' AND status ='N'";
	$hasil_28 = mysql_query($sql_pt28);$data_28  = mysql_fetch_array($hasil_28);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '27' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay27 = $hasil_jatah ['no_bay'];
							 $bay_body27 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							$id_pk27 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj27 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk27'";
	$hasil_j27 = mysql_query($sql_ptj27);$data_j27  = mysql_fetch_array($hasil_j27);			 
	$tgl27 = new DateTime($data_j27['tgl_masuk']); 									 
	$sql_pk27A = mysql_query("select * from pk_aktif WHERE no_bay = '27' AND status !='S'"); $jumlah_pk27A = mysql_num_rows($sql_pk27A);
	$sql_pt27 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk27' AND status ='N'";
	$hasil_27 = mysql_query($sql_pt27);$data_27  = mysql_fetch_array($hasil_27);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '26' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay26 = $hasil_jatah ['no_bay'];
							 $bay_body26 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk26 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj26 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk26'";
	$hasil_j26 = mysql_query($sql_ptj26);$data_j26  = mysql_fetch_array($hasil_j26);			 
	$tgl26 = new DateTime($data_j26['tgl_masuk']); 								 
	$sql_pk26A = mysql_query("select * from pk_aktif WHERE no_bay = '26' AND status !='S'"); $jumlah_pk26A = mysql_num_rows($sql_pk26A);
	$sql_pt26 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk26' AND status ='N'";
	$hasil_26 = mysql_query($sql_pt26);$data_26  = mysql_fetch_array($hasil_26);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '25' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay25 = $hasil_jatah ['no_bay'];
							 $bay_body25 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk25 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj25 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk25'";
	$hasil_j25 = mysql_query($sql_ptj25);$data_j25  = mysql_fetch_array($hasil_j25);			 
	$tgl25 = new DateTime($data_j25['tgl_masuk']); 									 
	$sql_pk25A = mysql_query("select * from pk_aktif WHERE no_bay = '25' AND status !='S'"); $jumlah_pk25A = mysql_num_rows($sql_pk25A);
	$sql_pt25 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk25' AND status ='N'";
	$hasil_25 = mysql_query($sql_pt25);$data_25  = mysql_fetch_array($hasil_25);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '24' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay24 = $hasil_jatah ['no_bay'];
							 $bay_body24 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk24 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj24 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk24'";
	$hasil_j24 = mysql_query($sql_ptj24);$data_j24  = mysql_fetch_array($hasil_j24);			 
	$tgl24 = new DateTime($data_j24['tgl_masuk']); 									 
	$sql_pk24A = mysql_query("select * from pk_aktif WHERE no_bay = '24' AND status !='S'"); $jumlah_pk24A = mysql_num_rows($sql_pk24A);
	$sql_pt24 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk24' AND status ='N'";
	$hasil_24 = mysql_query($sql_pt24);$data_24  = mysql_fetch_array($hasil_24);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '23' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay23 = $hasil_jatah ['no_bay'];
							 $bay_body23 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk23 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj23 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk23'";
	$hasil_j23 = mysql_query($sql_ptj23);$data_j23  = mysql_fetch_array($hasil_j23);			 
	$tgl23 = new DateTime($data_j23['tgl_masuk']); 									 
	$sql_pk23A = mysql_query("select * from pk_aktif WHERE no_bay = '23' AND status !='S'"); $jumlah_pk23A = mysql_num_rows($sql_pk23A);
	$sql_pt23 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk23' AND status ='N'";
	$hasil_23 = mysql_query($sql_pt23);$data_23  = mysql_fetch_array($hasil_23);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '22' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay22 = $hasil_jatah ['no_bay'];
							 $bay_body22 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk22 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj22 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk22'";
	$hasil_j22 = mysql_query($sql_ptj22);$data_j22  = mysql_fetch_array($hasil_j22);			 
	$tgl22 = new DateTime($data_j22['tgl_masuk']); 									 
	$sql_pk22A = mysql_query("select * from pk_aktif WHERE no_bay = '22' AND status !='S'"); $jumlah_pk22A = mysql_num_rows($sql_pk22A);
	$sql_pt22 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk22' AND status ='N'";
	$hasil_22 = mysql_query($sql_pt22);$data_22  = mysql_fetch_array($hasil_22);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '21' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay21 = $hasil_jatah ['no_bay'];
							 $bay_body21 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk21 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj21 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk21'";
	$hasil_j21 = mysql_query($sql_ptj21);$data_j21  = mysql_fetch_array($hasil_j21);			 
	$tgl21 = new DateTime($data_j21['tgl_masuk']); 									 
	$sql_pk21A = mysql_query("select * from pk_aktif WHERE no_bay = '21' AND status !='S'"); $jumlah_pk21A = mysql_num_rows($sql_pk21A);
	$sql_pt21 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk21' AND status ='N'";
	$hasil_21 = mysql_query($sql_pt21);$data_21  = mysql_fetch_array($hasil_21); }
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '20' AND status !='S' GROUP BY 'no_body' ");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay20 = $hasil_jatah ['no_bay'];
							 $bay_body20 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk20 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj20 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk20'";
	$hasil_j20 = mysql_query($sql_ptj20);$data_j20  = mysql_fetch_array($hasil_j20);			 
	$tgl20 = new DateTime($data_j20['tgl_masuk']); 								 
	$sql_pk20A = mysql_query("select * from pk_aktif WHERE no_bay = '20' AND status !='S'"); $jumlah_pk20A = mysql_num_rows($sql_pk20A);
	$sql_pt20 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk20' AND status ='N'";
	$hasil_20 = mysql_query($sql_pt20);$data_20  = mysql_fetch_array($hasil_20);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '19' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay19 = $hasil_jatah ['no_bay'];
							 $bay_body19 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk19 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj19 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk19'";
	$hasil_j19 = mysql_query($sql_ptj19);$data_j19  = mysql_fetch_array($hasil_j19);			 
	$tgl19 = new DateTime($data_j19['tgl_masuk']); 									 
	$sql_pk19A = mysql_query("select * from pk_aktif WHERE no_bay = '19' AND status !='S'"); $jumlah_pk19A = mysql_num_rows($sql_pk19A);
	$sql_pt19 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk19' AND status ='N'";
	$hasil_19 = mysql_query($sql_pt19);$data_19  = mysql_fetch_array($hasil_19);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '18' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay18 = $hasil_jatah ['no_bay'];
							 $bay_body18 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							$id_pk18 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj18 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk18'";
	$hasil_j18 = mysql_query($sql_ptj18);$data_j18  = mysql_fetch_array($hasil_j18);			 
	$tgl18 = new DateTime($data_j18['tgl_masuk']); 								 
	$sql_pk18A = mysql_query("select * from pk_aktif WHERE no_bay = '18' AND status !='S'"); $jumlah_pk18A = mysql_num_rows($sql_pk18A);
	$sql_pt18 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk18' AND status ='N'";
	$hasil_18 = mysql_query($sql_pt18);$data_18  = mysql_fetch_array($hasil_18);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '17' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay17 = $hasil_jatah ['no_bay'];
							 $bay_body17 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk17 = $hasil_jatah ['id_pk'];									 							
	$sql_ptj17 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk17'";
	$hasil_j17 = mysql_query($sql_ptj17);$data_j17  = mysql_fetch_array($hasil_j17);			 
	$tgl17 = new DateTime($data_j17['tgl_masuk']); 									 
	$sql_pk17A = mysql_query("select * from pk_aktif WHERE no_bay = '17' AND status !='S'"); $jumlah_pk17A = mysql_num_rows($sql_pk17A);
	$sql_pt17 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk17' AND status ='N'";
	$hasil_17 = mysql_query($sql_pt17);$data_17  = mysql_fetch_array($hasil_17);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '16' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay16 = $hasil_jatah ['no_bay'];
							 $bay_body16 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk16 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj16 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk16'";
	$hasil_j16 = mysql_query($sql_ptj16);$data_j16  = mysql_fetch_array($hasil_j16);			 
	$tgl16 = new DateTime($data_j16['tgl_masuk']); 									 
	$sql_pk16A = mysql_query("select * from pk_aktif WHERE no_bay = '16' AND status !='S'"); $jumlah_pk16A = mysql_num_rows($sql_pk16A);
	$sql_pt16 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk16' AND status ='N'";
	$hasil_16 = mysql_query($sql_pt16);$data_16  = mysql_fetch_array($hasil_16);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '15' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay15 = $hasil_jatah ['no_bay'];
							 $bay_body15 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk15 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj15 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk15'";
	$hasil_j15 = mysql_query($sql_ptj15);$data_j15  = mysql_fetch_array($hasil_j15);			 
	$tgl15 = new DateTime($data_j15['tgl_masuk']); 									 
	$sql_pk15A = mysql_query("select * from pk_aktif WHERE no_bay = '15' AND status !='S'"); $jumlah_pk15A = mysql_num_rows($sql_pk15A);
	$sql_pt15 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk15' AND status ='N'";
	$hasil_15 = mysql_query($sql_pt15);$data_15  = mysql_fetch_array($hasil_15);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '14' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay14 = $hasil_jatah ['no_bay'];
							 $bay_body14 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk14 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj14 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk14'";
	$hasil_j14 = mysql_query($sql_ptj14);$data_j14  = mysql_fetch_array($hasil_j14);			 
	$tgl14 = new DateTime($data_j14['tgl_masuk']); 									 
	$sql_pk14A = mysql_query("select * from pk_aktif WHERE no_bay = '14' AND status !='S'"); $jumlah_pk14A = mysql_num_rows($sql_pk14A);
	$sql_pt14 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk14' AND status ='N'";
	$hasil_14 = mysql_query($sql_pt14);$data_14  = mysql_fetch_array($hasil_14);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '13' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay13 = $hasil_jatah ['no_bay'];
							 $bay_body13 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk13 = $hasil_jatah ['id_pk'];											 							
	$sql_ptj13 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk13'";
	$hasil_j13 = mysql_query($sql_ptj13);$data_j13  = mysql_fetch_array($hasil_j13);			 
	$tgl13 = new DateTime($data_j13['tgl_masuk']); 								 
	$sql_pk13A = mysql_query("select * from pk_aktif WHERE no_bay = '13' AND status !='S'"); $jumlah_pk13A = mysql_num_rows($sql_pk13A);
	$sql_pt13 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk13' AND status ='N'";
	$hasil_13 = mysql_query($sql_pt13);$data_13  = mysql_fetch_array($hasil_13);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '12' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay12 = $hasil_jatah ['no_bay'];
							 $bay_body12 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk12 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj12 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk12'";
	$hasil_j12 = mysql_query($sql_ptj12);$data_j12  = mysql_fetch_array($hasil_j12);			 
	$tgl12 = new DateTime($data_j12['tgl_masuk']); 									 
	$sql_pk12A = mysql_query("select * from pk_aktif WHERE no_bay = '12' AND status !='S'"); $jumlah_pk12A = mysql_num_rows($sql_pk12A);
	$sql_pt12 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk12' AND status ='N'";
	$hasil_12 = mysql_query($sql_pt12);$data_12  = mysql_fetch_array($hasil_12);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '11' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay11 = $hasil_jatah ['no_bay'];
							 $bay_body11 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk11 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj11 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk11'";
	$hasil_j11 = mysql_query($sql_ptj11);$data_j11  = mysql_fetch_array($hasil_j11);			 
	$tgl11 = new DateTime($data_j11['tgl_masuk']); 									 
	$sql_pk11A = mysql_query("select * from pk_aktif WHERE no_bay = '11' AND status !='S'"); $jumlah_pk11A = mysql_num_rows($sql_pk11A);
	$sql_pt11 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk11' AND status ='N'";
	$hasil_11 = mysql_query($sql_pt11);$data_11  = mysql_fetch_array($hasil_11);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '10' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay10 = $hasil_jatah ['no_bay'];
							 $bay_body10 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk10 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj10 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk10'";
	$hasil_j10 = mysql_query($sql_ptj10);$data_j10  = mysql_fetch_array($hasil_j10);			 
	$tgl10 = new DateTime($data_j10['tgl_masuk']); 									 
	$sql_pk10A = mysql_query("select * from pk_aktif WHERE no_bay = '10' AND status !='S'"); $jumlah_pk10A = mysql_num_rows($sql_pk10A);
	$sql_pt10 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk10' AND status ='N'";
	$hasil_10 = mysql_query($sql_pt10);$data_10  = mysql_fetch_array($hasil_10);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '9' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay9 = $hasil_jatah ['no_bay'];
							 $bay_body9 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk9 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj9 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk9'";
	$hasil_j9 = mysql_query($sql_ptj9);$data_j9  = mysql_fetch_array($hasil_j9);			 
	$tgl9 = new DateTime($data_j9['tgl_masuk']); 									 
	$sql_pk9A = mysql_query("select * from pk_aktif WHERE no_bay = '9' AND status !='S'"); $jumlah_pk9A = mysql_num_rows($sql_pk9A);
	$sql_pt9 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk9' AND status ='N'";
	$hasil_9 = mysql_query($sql_pt9);$data_9  = mysql_fetch_array($hasil_9);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '8' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay8 = $hasil_jatah ['no_bay'];
							 $bay_body8 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk8 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj8 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk8'";
	$hasil_j8 = mysql_query($sql_ptj8);$data_j8  = mysql_fetch_array($hasil_j8);			 
	$tgl8 = new DateTime($data_j8['tgl_masuk']); 									 
	$sql_pk8A = mysql_query("select * from pk_aktif WHERE no_bay = '8' AND status !='S'"); $jumlah_pk8A = mysql_num_rows($sql_pk8A);
	$sql_pt8 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk8' AND status ='N'";
	$hasil_8 = mysql_query($sql_pt8);$data_8  = mysql_fetch_array($hasil_8);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '7' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay7 = $hasil_jatah ['no_bay'];
							 $bay_body7 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk7 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj7 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk7'";
	$hasil_j7 = mysql_query($sql_ptj7);$data_j7  = mysql_fetch_array($hasil_j7);			 
	$tgl7 = new DateTime($data_j7['tgl_masuk']); 									 
	$sql_pk7A = mysql_query("select * from pk_aktif WHERE no_bay = '7' AND status !='S'"); $jumlah_pk7A = mysql_num_rows($sql_pk7A);
	$sql_pt7 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk7' AND status ='N'";
	$hasil_7 = mysql_query($sql_pt7);$data_7  = mysql_fetch_array($hasil_7);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '6' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay6 = $hasil_jatah ['no_bay'];
							 $bay_body6 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk6 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj6 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk6'";
	$hasil_j6 = mysql_query($sql_ptj6);$data_j6  = mysql_fetch_array($hasil_j6);			 
	$tgl6 = new DateTime($data_j6['tgl_masuk']); 									 
	$sql_pk6A = mysql_query("select * from pk_aktif WHERE no_bay = '6' AND status !='S'"); $jumlah_pk6A = mysql_num_rows($sql_pk6A);
	$sql_pt6 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk6' AND status ='N'";
	$hasil_6 = mysql_query($sql_pt6);$data_6  = mysql_fetch_array($hasil_6);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '5' AND status !='S' GROUP BY 'no_body'");												
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay5 = $hasil_jatah ['no_bay'];
							 $bay_body5 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk5 = $hasil_jatah ['id_pk'];									 							
	$sql_ptj5 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk5'";
	$hasil_j5 = mysql_query($sql_ptj5);$data_j5  = mysql_fetch_array($hasil_j5);			 
	$tgl5 = new DateTime($data_j5['tgl_masuk']); 										 
	$sql_pk5A = mysql_query("select * from pk_aktif WHERE no_bay = '5' AND status !='S'"); $jumlah_pk5A = mysql_num_rows($sql_pk5A);
	$sql_pt5 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk5' AND status ='N'";
	$hasil_5 = mysql_query($sql_pt5);$data_5  = mysql_fetch_array($hasil_5);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '4' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay4 = $hasil_jatah ['no_bay'];
							 $bay_body4 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk4 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj4 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk4'";
	$hasil_j4 = mysql_query($sql_ptj4);$data_j4  = mysql_fetch_array($hasil_j4);			 
	$tgl4 = new DateTime($data_j4['tgl_masuk']); 									 
	$sql_pk4A = mysql_query("select * from pk_aktif WHERE no_bay = '4' AND status !='S'"); $jumlah_pk4A = mysql_num_rows($sql_pk4A);
	$sql_pt4 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk4' AND status ='N'";
	$hasil_4 = mysql_query($sql_pt4);$data_4  = mysql_fetch_array($hasil_4);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '3' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay3 = $hasil_jatah ['no_bay'];
							 $bay_body3 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk3 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj3 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk3'";
	$hasil_j3 = mysql_query($sql_ptj3);$data_j3  = mysql_fetch_array($hasil_j3);			 
	$tgl3 = new DateTime($data_j3['tgl_masuk']); 									 
	$sql_pk3A = mysql_query("select * from pk_aktif WHERE no_bay = '3' AND status !='S'"); $jumlah_pk3A = mysql_num_rows($sql_pk3A);
	$sql_pt3 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk3' AND status ='N'";
	$hasil_3 = mysql_query($sql_pt3);$data_3  = mysql_fetch_array($hasil_3);}
	
	$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '2' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay2 = $hasil_jatah ['no_bay'];
							 $bay_body2 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk2 = $hasil_jatah ['id_pk'];										 							
	$sql_ptj2 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk2'";
	$hasil_j2 = mysql_query($sql_ptj2);$data_j2  = mysql_fetch_array($hasil_j2);			 
	$tgl2 = new DateTime($data_j2['tgl_masuk']); 									 
	$sql_pk2A = mysql_query("select * from pk_aktif WHERE no_bay = '2' AND status !='S'"); $jumlah_pk2A = mysql_num_rows($sql_pk2A);
	$sql_pt2 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk2' AND status ='N'";
	$hasil_2 = mysql_query($sql_pt2);$data_2  = mysql_fetch_array($hasil_2);}
	
	
							$sql_jatah = mysql_query("select no_bay, no_body,id_pk,id_detail from pk_aktif WHERE no_bay = '1' AND status !='S' GROUP BY 'no_body'");
							 while($hasil_jatah =mysql_fetch_array($sql_jatah)){  
							 $bay1 = $hasil_jatah ['no_bay'];
							 $bay_body1 = $hasil_jatah ['no_body'];
							 $detail1 = $hasil_jatah ['id_detail'];
							 $id_pk1 = $hasil_jatah ['id_pk'];									 							
	$sql_ptj1 = "SELECT jam_masuk,tgl_masuk FROM bus_masuk WHERE id_pk = '$id_pk1'";
	$hasil_j1 = mysql_query($sql_ptj1);$data_j1  = mysql_fetch_array($hasil_j1);			 
	$tgl1 = new DateTime($data_j1['tgl_masuk']); 										 
	$sql_pk1A = mysql_query("select * from pk_aktif WHERE no_bay = '1' AND status !='S'"); $jumlah_pk1A = mysql_num_rows($sql_pk1A);
	$sql_pt1 = "SELECT max(estimasi) AS last FROM list_mekanik WHERE id_pk = '$id_pk1' AND status ='N'";
	$hasil_1 = mysql_query($sql_pt1);$data_1  = mysql_fetch_array($hasil_1);}
	
							 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>KAROSERI SYSTEM</title>
<link href="css/global.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="css/style1.css" type="text/css">
<link href="css/global.css" rel="stylesheet" type="text/css" />
</head>
<div>
  <div class="header" align="center"><strong>DENAH LOKASI PERBAIKAN BODY REPAIR</strong></div> 
  <div class="kategoriatas">
  <div class="jarak_kiri1"></div>
  <div class="bis2">
    <div
        class="isikategori2"
        <?php if($jumlah_pk30A < 1){ echo'style=background-color:#37BF07;';}if($data_30['last'] < 1){ echo'style=background-color:#E6F408;';} ?>> <div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>18</strong></div> <?php echo "<h4>".$bay_body30."</h4>";echo "PK : ".$jumlah_pk30A."<br>"; echo "Time : ".$data_30['last']."<br>"; if(!empty($tgl30)) {$diff = $tgl30->diff($kini); echo "BM : ".$diff->
        d;}="D;}"
        ?="?"d, ></div>
</div>
      <div class="bis2"><div class="isikategori2" <?php if($jumlah_pk29A < 1){ echo'style=background-color:#37BF07;';}if($data_29['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
        <div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>17</strong></div>
							<?php 
 	echo "<h4>".$bay_body29."</h4>";echo "PK  : ".$jumlah_pk29A."<br>"; echo "Time : ".$data_29['last']."<br>"; if(!empty($tgl29)) {$diff = $tgl29->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 ?>
	 </div></div>
      <div class="bis2"><div class="isikategori2" <?php if($jumlah_pk28A < 1){ echo'style=background-color:#37BF07;';}if($data_28['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
        <div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>16</strong></div>
							<?php 
 	echo "<h4>".$bay_body28."</h4>";echo "PK  : ".$jumlah_pk28A."<br>"; echo "Time : ".$data_28['last']."<br>"; if(!empty($tgl28)) {$diff = $tgl28->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div></div>
      <div class="bis2"><div class="isikategori2" <?php if($jumlah_pk27A < 1){ echo'style=background-color:#37BF07;';}if($data_27['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
        <div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>15</strong></div>
							<?php 
 	echo "<h4>".$bay_body27."</h4>";echo "PK  : ".$jumlah_pk27A."<br>"; echo "Time : ".$data_27['last']."<br>"; if(!empty($tgl27)) {$diff = $tgl27->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div></div>
    <div class="bis2"><div class="isikategori2" <?php if($jumlah_pk26A < 1){ echo'style=background-color:#37BF07;';}if($data_26['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>14</strong></div>
							<?php 
 	echo "<h4>".$bay_body26."</h4>";echo "PK  : ".$jumlah_pk26A."<br>"; echo "Time : ".$data_26['last']."<br>"; if(!empty($tgl26)) {$diff = $tgl26->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div></div>
     <div class="bis2"><div class="isikategori2" <?php if($jumlah_pk25A < 1){ echo 'style=background-color:#37BF07;';}if($data_25['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>13</strong></div>
							<?php 
 	echo "<h4>".$bay_body25."</h4>";echo "PK  : ".$jumlah_pk25A."<br>"; echo "Time : ".$data_25['last']."<br>"; if(!empty($tgl25)) {$diff = $tgl25->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk24A < 1){ echo'style=background-color:#37BF07;';}if($data_24['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>12</strong></div>
							<?php 
 	echo "<h4>".$bay_body24."</h4>";echo "PK  : ".$jumlah_pk24A."<br>"; echo "Time : ".$data_24['last']."<br>"; if(!empty($tgl24)) {$diff = $tgl24->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk23A < 1){ echo'style=background-color:#37BF07;';}if($data_23['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>11</strong></div>
							<?php 
 	echo "<h4>".$bay_body23."</h4>";echo "PK  : ".$jumlah_pk23A."<br>"; echo "Time : ".$data_23['last']."<br>"; if(!empty($tgl23)) {$diff = $tgl23->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk22A < 1){ echo'style=background-color:#37BF07;';}if($data_22['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>10</strong></div>
							<?php 
 	echo "<h4>".$bay_body22."</h4>";echo "PK  : ".$jumlah_pk22A."<br>"; echo "Time : ".$data_22['last']."<br>"; if(!empty($tgl22)) {$diff = $tgl22->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
    <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk21A < 1){ echo'style=background-color:#37BF07;';}if($data_21['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>9</strong></div>
							<?php 
 	echo "<h4>".$bay_body21."</h4>";echo "PK  : ".$jumlah_pk21A."<br>"; echo "Time : ".$data_21['last']."<br>"; if(!empty($tgl21)) {$diff = $tgl21->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
    <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk20A < 1){ echo'style=background-color:#37BF07;';}if($data_20['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>8</strong></div>
							<?php 
 	echo "<h4>".$bay_body20."</h4>";echo "PK  : ".$jumlah_pk20A."<br>"; echo "Time : ".$data_20['last']."<br>"; if(!empty($tgl20)) {$diff = $tgl20->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
    </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk19A < 1){ echo'style=background-color:#37BF07;';}if($data_19['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>7</strong></div>
							<?php 
 	echo "<h4>".$bay_body19."</h4>";echo "PK  : ".$jumlah_pk19A."<br>"; echo "Time : ".$data_19['last']."<br>"; if(!empty($tgl19)) {$diff = $tgl19->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk18A < 1){ echo'style=background-color:#37BF07;';}if($data_18['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>6</strong></div>
							<?php 
 	echo "<h4>".$bay_body18."</h4>";echo "PK  : ".$jumlah_pk18A."<br>"; echo "Time : ".$data_18['last']."<br>"; if(!empty($tgl18)) {$diff = $tgl18->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk17A < 1){ echo'style=background-color:#37BF07;';}if($data_17['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>5</strong></div>
							<?php 
 	echo "<h4>".$bay_body17."</h4>";echo "PK  : ".$jumlah_pk17A."<br>"; echo "Time : ".$data_17['last']."<br>"; if(!empty($tgl17)) {$diff = $tgl17->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk16A < 1){ echo'style=background-color:#37BF07;';}if($data_16['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>4</strong></div>
							<?php 
 	echo "<h4>".$bay_body16."</h4>";echo "PK  : ".$jumlah_pk16A."<br>"; echo "Time : ".$data_16['last']."<br>"; if(!empty($tgl16)) {$diff = $tgl16->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk15A < 1){ echo'style=background-color:#37BF07;';}if($data_15['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>3</strong></div>
							<?php 
 	echo "<h4>".$bay_body15."</h4>";echo "PK  : ".$jumlah_pk15A."<br>"; echo "Time : ".$data_15['last']."<br>"; if(!empty($tgl15)) {$diff = $tgl15->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk14A < 1){ echo'style=background-color:#37BF07;';}if($data_14['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>2</strong></div>
							<?php 
 	echo "<h4>".$bay_body14."</h4>";echo "PK  : ".$jumlah_pk14A."<br>"; echo "Time : ".$data_14['last']."<br>"; if(!empty($tgl14)) {$diff = $tgl14->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
    <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk13A < 1){ echo'style=background-color:#37BF07;';}if($data_13['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>1</strong></div>
							<?php 
 	echo "<h4>".$bay_body13."</h4>";echo "PK  : ".$jumlah_pk13A."<br>"; echo "Time : ".$data_13['last']."<br>"; if(!empty($tgl13)) {$diff = $tgl13->diff($kini); echo "BM : ".$diff->d, D;} 							 
							 
							 ?>
	 </div><strong></strong></div>
  </div><div class="kategoritengah"><div class="jarak_mobil2"></div><div class="judul"><strong>DEMPUL & CAT TOTAL BODY</strong></div><div class="jarak_mobil2"></div><div class="jarak_mobil2"></div><div class="judul"><strong>DEMPUL & CAT</strong></div><div class="jarak_mobil2"></div><div class="jarak_mobil"></div>
  <div class="judul"><strong>BODY WELDING</strong></div><div class="jarak_mobil3"></div>
  <div class="judul2"><strong>CHASSIS</strong></div><div class="jarak_mobil"></div><div class="jarak_mobil"></div><div class="jarak_mobil"></div>
  <div class="judul"><strong>BODY MTE</strong></div>
</div>
<div class="kategoritengah">
     
  <div class="jarak_kiri"></div>
  <div class="bis1" <?php if($jumlah_pk36A < 1){ echo'style=background-color:#37BF07;';}if($data_36['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
  <div class="nomor2" style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>1</strong></div>  <?php 
 	echo "<h10>".$bay_body36."</h10>"."<br>";echo "PK  : ".$jumlah_pk36A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_36['last']."<br>"; if(!empty($tgl36)) {$diff = $tgl36->diff($kini); echo "BM : ".$diff->d, D;} ?>
    </div> 
       <div class="jarak_mobil"></div>
     <div class="bis1" <?php if($jumlah_pk37A < 1){ echo'style=background-color:#37BF07;';}if($data_37['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>	<div class="nomor2" style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>2</strong></div>
       <?php 
 	echo "<h10>".$bay_body37."</h10>"."<br>";echo "PK  : ".$jumlah_pk37A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_37['last']."<br>"; if(!empty($tgl37)) {$diff = $tgl37->diff($kini); echo "BM : ".$diff->d, D;} ?>
     </div><div class="jarak_mobil"></div>
     <div class="bis1" <?php if($jumlah_pk38A < 1){ echo'style=background-color:#37BF07;';}if($data_38['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>	<div class="nomor2" style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>3</strong></div>
       <?php 
 	echo "<h10>".$bay_body38."</h10>"."<br>";echo "PK  : ".$jumlah_pk38A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_38['last']."<br>"; if(!empty($tgl38)) {$diff = $tgl38->diff($kini); echo "BM : ".$diff->d, D;} ?>
     </div><div class="jarak_mobil"></div>
  <div class="bis1" <?php if($jumlah_pk39A < 1){ echo'style=background-color:#37BF07;';}if($data_39['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
    <div class="nomor2" style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>4</strong></div>
    <?php 
 	echo "<h10>".$bay_body39."</h10>"."<br>";echo "PK  : ".$jumlah_pk39A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_39['last']."<br>"; if(!empty($tgl39)) {$diff = $tgl39->diff($kini); echo "BM : ".$diff->d, D;} ?>
  </div><div class="jarak_mobil"></div>
     <div class="bis1 bis2 bis5" <?php if($jumlah_pk40A < 1){ echo'style=background-color:#37BF07;';}if($data_40['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
       <div class="nomor2"style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>5</strong></div>		
							<?php 
 	echo "<h10>".$bay_body40."</h10>"."<br>";echo "PK  : ".$jumlah_pk40A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_40['last']."<br>"; if(!empty($tgl40)) {$diff = $tgl40->diff($kini); echo "BM : ".$diff->d, D;} ?>
     </div><div class="jarak_mobil"></div>
     <div class="bis1 bis2 bis5" <?php if($jumlah_pk45A < 1){ echo'style=background-color:#37BF07;';}if($data_45['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
       <div class="nomor2"style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>6</strong></div>		
							<?php 
 	echo "<h10>".$bay_body45."</h10>"."<br>";echo "PK  : ".$jumlah_pk45A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_45['last']."<br>"; if(!empty($tgl45)) {$diff = $tgl45->diff($kini); echo "BM : ".$diff->d, D;} ?>
     </div>
</div><br /><div class="kategoritengah">
     
  <div class="jarak_kiri"></div>
  <div class="bis1" <?php if($jumlah_pk31A < 1){ echo'style=background-color:#37BF07;';}if($data_31['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
    <div class="nomor2"style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>1</strong></div>		
						 <?php 
 	echo "<h10>".$bay_body31."</h10>"."<br>";echo "PK  : ".$jumlah_pk31A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_31['last']."<br>"; if(!empty($tgl31)) {$diff = $tgl31->diff($kini); echo "BM : ".$diff->d, D;} ?>
    </div><div class="jarak_mobil"></div>
  <div class="bis1" <?php if($jumlah_pk32A < 1){ echo'style=background-color:#37BF07;';}if($data_32['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>	<div class="nomor2"style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>2</strong></div>	
							<?php 
 	echo "<h10>".$bay_body31."</h10>"."<br>";echo "PK  : ".$jumlah_pk31A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_32['last']."<br>"; if(!empty($tgl32)) {$diff = $tgl32->diff($kini); echo "BM : ".$diff->d, D;} ?>
  </div><div class="jarak_mobil"></div>
     <div class="bis1" <?php if($jumlah_pk33A < 1){ echo'style=background-color:#37BF07;';}if($data_33['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>	<div class="nomor2"style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>3</strong></div>	
							<?php 
 	echo "<h10>".$bay_body33."</h10>"."<br>";echo "PK  : ".$jumlah_pk33A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_33['last']."<br>"; if(!empty($tgl33)) {$diff = $tgl33->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><div class="jarak_mobil"></div>
  <div class="bis1" <?php if($jumlah_pk34A < 1){ echo'style=background-color:#37BF07;';}if($data_34['last'] < 1){ echo'style=background-color:#E6F408;';} ?> >	<div class="nomor2"style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>4</strong></div>
						 <?php 
 	echo $bay_body34."&nbsp;&nbsp;&nbsp;";echo "PK  : ".$jumlah_pk34A."<br>"; echo "Time : ".$data_34['last']."<br>"; if(!empty($tgl34)) {$diff = $tgl34->diff($kini); echo "BM : ".$diff->d, D;} ?>
  </div><div class="jarak_mobil"></div>
     <div class="bis1" <?php if($jumlah_pk35A < 1){ echo'style=background-color:#37BF07;';}if($data_35['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>	<div class="nomor2"style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>5</strong></div>
						 <?php 
 	echo "<h10>".$bay_body35."</h10>"."<br>";echo "PK  : ".$jumlah_pk35A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_35['last']."<br>"; if(!empty($tgl35)) {$diff = $tgl35->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><div class="jarak_mobil"></div>
  <div class="bis1" <?php if($jumlah_pk46A < 1){ echo'style=background-color:#37BF07;';}if($data_46['last'] < 1){ echo'style=background-color:#E6F408;';} ?>>
    <div class="nomor2"style='border-radius:0 10px 10px 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>6</strong></div>
      <?php 
 	echo "<h10>".$bay_body46."</h10>"."<br>";echo "PK  : ".$jumlah_pk46A."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo "Time : ".$data_46['last']."<br>"; if(!empty($tgl46)) {$diff = $tgl46->diff($kini); echo "BM : ".$diff->d, D;} ?>
</div></div>
     <div class="bis5">
     <br />
  <div class="jarak_kiri"></div>
<div class="kategoriatas" >
   <div class="jarak_kiri1"></div>
   <div class="bis2"><div class="isikategori2" <?php  if($jumlah_pk42A < 1){ echo'style=background-color:#37BF07;';}if($data_42['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>1</strong></div>
							<?php 	echo "<h4>".$bay_body42."</h4>";echo "PK  : ".$jumlah_pk42A."<br>"; echo "Time : ".$data_42['last']."<br>"; if(!empty($tgl42)) {$diff = $tgl42->diff($kini); echo "BM : ".$diff->d, D;} ?>
    </div></div>
     <div class="bis2"><div class="isikategori2" <?php if($jumlah_pk43A < 1){ echo'style=background-color:#37BF07;';}if($data_43['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>2</strong></div>
							<?php echo "<h4>".$bay_body43."</h4>";echo "PK  : ".$jumlah_pk43A."<br>"; echo "Time : ".$data_43['last']."<br>"; if(!empty($tgl43)) {$diff = $tgl43->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div></div><div class="jarak_mobil"></div>
     <div class="bis2"><div class="isikategori2" <?php  if($jumlah_pk41A < 1){ echo'style=background-color:#37BF07;';}if($data_41['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>1</strong></div>
							<?php 	echo "<h4>".$bay_body41."</h4>";echo "PK  : ".$jumlah_pk41A."<br>"; echo "Time : ".$data_41['last']."<br>"; if(!empty($tgl41)) {$diff = $tgl41->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div></div>
   <div class="jarak_kiri1"></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk12A < 1){ echo'style=background-color:#37BF07;';}if($data_12['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>12</strong></div>
							<?php 
 	echo "<h4>".$bay_body12."</h4>";echo "PK  : ".$jumlah_pk12A."<br>"; echo "Time : ".$data_12['last']."<br>"; if(!empty($tgl12)) {$diff = $tgl12->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk11A < 1){ echo'style=background-color:#37BF07;';}if($data_11['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>11</strong></div>
							<?php 
 	echo "<h4>".$bay_body11."</h4>";echo "PK  : ".$jumlah_pk11A."<br>"; echo "Time : ".$data_11['last']."<br>"; if(!empty($tgl11)) {$diff = $tgl11->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk10A < 1){ echo'style=background-color:#37BF07;';}if($data_10['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>10</strong></div>
							<?php 
 	echo "<h4>".$bay_body10."</h4>";echo "PK  : ".$jumlah_pk10A."<br>"; echo "Time : ".$data_10['last']."<br>"; if(!empty($tgl10)) {$diff = $tgl10->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk9A < 1){ echo'style=background-color:#37BF07;';}if($data_9['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>9</strong></div>
							<?php 
 	echo "<h4>".$bay_body9."</h4>";echo "PK  : ".$jumlah_pk9A."<br>"; echo "Time : ".$data_9['last']."<br>"; if(!empty($tgl9)) {$diff = $tgl9->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk8A < 1){ echo'style=background-color:#37BF07;';}if($data_8['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>8</strong></div>
							<?php 
 	echo "<h4>".$bay_body8."</h4>";echo "PK  : ".$jumlah_pk8A."<br>"; echo "Time : ".$data_8['last']."<br>"; if(!empty($tgl8)) {$diff = $tgl8->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk7A < 1){ echo'style=background-color:#37BF07;';}if($data_7['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>7</strong></div>
							<?php 
 	echo "<h4>".$bay_body7."</h4>";echo "PK  : ".$jumlah_pk7A."<br>"; echo "Time : ".$data_7['last']."<br>"; if(!empty($tgl7)) {$diff = $tgl7->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk6A < 1){ echo'style=background-color:#37BF07;';}if($data_6['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>6</strong></div>
							<?php 
 	echo "<h4>".$bay_body6."</h4>";echo "PK  : ".$jumlah_pk6A."<br>"; echo "Time : ".$data_6['last']."<br>"; if(!empty($tgl6)) {$diff = $tgl6->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk5A < 1){ echo'style=background-color:#37BF07;';}if($data_5['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>5</strong></div>
							<?php 
 	echo "<h4>".$bay_body5."</h4>";echo "PK  : ".$jumlah_pk5A."<br>"; echo "Time : ".$data_5['last']."<br>"; if(!empty($tgl5)) {$diff = $tgl5->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk4A < 1){ echo'style=background-color:#37BF07;';}if($data_4['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>4</strong></div>
							<?php 
 	echo "<h4>".$bay_body4."</h4>";echo "PK  : ".$jumlah_pk4A."<br>"; echo "Time : ".$data_4['last']."<br>"; if(!empty($tgl4)) {$diff = $tgl4->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk3A < 1){ echo'style=background-color:#37BF07;';}if($data_3['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>3</strong></div>
							<?php 
 	echo "<h4>".$bay_body3."</h4>";echo "PK  : ".$jumlah_pk3A."<br>"; echo "Time : ".$data_3['last']."<br>"; if(!empty($tgl3)) {$diff = $tgl3->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
     <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk2A < 1){ echo'style=background-color:#37BF07;';}if($data_2['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>2</strong></div>
							<?php 
 	echo "<h4>".$bay_body2."</h4>";echo "PK  : ".$jumlah_pk2A."<br>"; echo "Time : ".$data_2['last']."<br>"; if(!empty($tgl2)) {$diff = $tgl2->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div>
    <div class="bis2">	<div class="isikategori2" <?php if($jumlah_pk1A < 1){ echo'style=background-color:#37BF07;';}if($data_1['last'] < 1){ echo'style=background-color:#E6F408;';} ?>><div class="nomor1"style='background-color: #258DFA; border-radius:10px 10px 0 0 ; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>1</strong></div>
							<?php 
 	echo "<h4>".$bay_body1."</h4>";echo "PK  : ".$jumlah_pk1A."<br>"; echo "Time : ".$data_1['last']."<br>"; if(!empty($tgl1)) {$diff = $tgl1->diff($kini); echo "BM : ".$diff->d, D;} ?>
	 </div><strong></strong></div><div class="jarak_kiri1"></div>
     <div class="keteranganpk">	<div class="isiketeranganpk"><div class="nomor1"style='background-color: #258DFA; color:#F8F8F8; text-shadow:#000000; font-style: !important;'><strong>KETERANGAN</strong></div>
         <table width="100%" border="0">
           <tbody>
             <tr>
               <th width="19" bgcolor="#37BF07">&nbsp;</th>
               <th width="76" style="font-size:12px; text-align:left;"> : Bay Kosong</th>
             </tr>
             <tr>
               <th bgcolor="#E6F408">&nbsp;</th>
               <th width="76" style="font-size:12px;text-align:left;"> : Bay Pause</th>
             </tr>
             <tr>
               <th bgcolor="#FFFFFF">&nbsp;</th>
               <th width="76" style="font-size:12px;text-align:left;"> : Bay Aktif</th>
             </tr>
             <tr>
               <th>BM</th>
               <th style="font-size:12px;text-align:left;">: Bis Masuk</th>
             </tr>
             <tr>
               <th>&nbsp;</th>
               <th>&nbsp;</th>
             </tr>
           </tbody>
         </table>
         <?php } ?>
     </div><strong></strong></div></div></div></div>
     </div><div class="kategoritengah"><div class="jarak_mobil3"></div>
     <div class="judul4"><strong>LUAR</strong></div><div class="jarak_mobil"></div>
     <div class="judul2"><strong>S.BOTH</strong></div><div class="jarak_mobil2"></div><div class="jarak_mobil3"></div><div class="judul"><strong>DEMPUL & CAT</strong></div><div class="jarak_mobil2"></div>
     <div class="judul"><strong>BODY WELDING</strong></div><div class="jarak_mobil3"></div><div class="jarak_mobil"></div>
  <div class="judul">TRIMMING Ext &amp; Int</div>
</div>
 
     <div id="bawah2" >
<ul id="bawah_01" class="bawah"><marquee direction="left" behavior="scroll" scrollamount="5">
	      <?php
		 
             $antrian_pk=mysql_query("SELECT * FROM pk_aktif WHERE status='S' AND tgl_selesai='".date('Y-m-d')."'");
              while($dpk=mysql_fetch_array($antrian_pk)){
				echo "<td width=0 style=text-transform:capitalize;><img src=images/centang.png width=30 height=30>$dpk[no_body]($dpk[jns_pk])&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
              }
            ?>
	      
</marquee></div> <?php
 function DateToIndo($date){
		$BulanIndo = array("01", "02", "03",
						   "04", "05", "06",
						   "07", "08", "09",
						   "10", "11", "12");
	
		$tahun = substr($date, 0, 4);  
        $bulan = substr($date, 5, 2);  
        $tgl   = substr($date, 8, 2);  
          
        $result = $tgl . "/" . $BulanIndo[(int)$bulan-1] . "/". $tahun;       
        return($result);  
	}
?>
<div id="page1">
<ul id="ticker_02" class="ticker">
      <div align="center">
        <table width="100%">
              <td width="100%" class="keterangan_lanjutan"><strong>D A F T A R &nbsp;&nbsp; P E K E R J A A N &nbsp;&nbsp; AKTIF</strong></td>
        </table>
    </div>
    <div align="left">
        <table width="100%">
          <td width="3%"class="keterangan_judul" >No.</td>
            <td width="7%"class="keterangan_judul">Tgl-Masuk</td>
            <td width="10%"class="keterangan_judul">NoBody</td>
            <td width="10%"class="keterangan_judul">Status</td>
            <td width="20%"class="keterangan_judul">Spv</td>
            <td width="20%"class="keterangan_judul">Leader</td>
            <td width="100%"class="keterangan_judul">Keterangan</td>
      </table>
    </div>
		<li>
	      <?php
             $antrian=mysql_query("SELECT * FROM pk_aktif WHERE status ='N' ORDER BY jam_mulai ASC LIMIT 50");
              while($d=mysql_fetch_array($antrian)){
				  $noUrutberatbanget++;
				echo "<li>
				<table width=100%>	
				<td width=3% class=isi_slider ><font> $noUrutberatbanget</font></td>	
				<td width=7% class=isi_slider ><div align=center> ".DateToIndo($d[tgl_mulai])."</div> </td>
				<td width=10% class=isi_slider ><div align=center> $d[no_body]</div></td>
				<td width=10% class=isi_slider ><div align=center> $d[jns_pk]</div></td>
				<td width=20% class=isi_slider ><div align=center> $d[nama_spv]</div></td>
				<td width=20% class=isi_slider ><div align=center> $d[nama_ld]</div></td>
				<td width=30$ class=isi_slider ><font size=1>$d[ket_pk]</td></font></table></li>";
              }
            ?>
  </ul>
</div><div id="page2">
<ul id="ticker_03" class="ticker">
      <div align="center">
        <table width="100%">
              <td width="100%" class="keterangan_lanjutan"><strong>D A F T A R &nbsp;&nbsp; ANTRIAN &nbsp;&nbsp; B O D Y &nbsp;&nbsp; R E P A I R</strong></td>
        </table>
    </div>
    <div align="left">
        <table width="100%">
          <td width="3%"class="keterangan_judul" >No.</td>
            <td width="7%"class="keterangan_judul">Tgl-Masuk</td>
            <td width="10%"class="keterangan_judul">NoBody</td>
            <td width="20%"class="keterangan_judul">Kategori</td>
            <td width="20%"class="keterangan_judul">Pelapor</td>
            <td width="100%"class="keterangan_judul">Keterangan</td>
      </table>
    </div>
		<li>
	      <?php
             $antrian1=mysql_query("select * from bus_masuk where status ='N'");
              while($d1=mysql_fetch_array($antrian1)){
				  $noUrutberatbanget1++;
				echo "<li>
				<table width=100%>	
				<td width=3% class=isi_slider ><font> $noUrutberatbanget1</font></td>	
				<td width=7% class=isi_slider ><div align=center> ".DateToIndo($d1[tgl_masuk])."</div> </td>
				<td width=10% class=isi_slider ><div align=center> $d1[no_body]</div></td>
				<td width=20% class=isi_slider ><div align=center> $d1[kategori_pk]</div></td>
				<td width=20% class=isi_slider ><div align=center> $d1[pelapor]</div></td>
				<td width=30$ class=isi_slider ><font size=2>$d1[keterangan]</td></font></table></li>";
              }
            ?>
                <?php
						 function selisih($jam_a,$jam_b){
						list ($h,$m,$s)= explode (":",$jam_a);
						$dtAwal= mktime($h,$m,$s,"1","1","1");
						list ($h,$m,$s)= explode (":",$jam_b);
						$dtAkhir= mktime($h,$m,$s,"1","1","1");
						$dtSelisih = $dtAkhir-$dtAwal;
						$totalmenit=$dtSelisih/60;
						$jam=explode(".",$totalmenit/60);
						$sisamenit=($totalmenit/60)-$jam[0];
						$sisamenit2=$sisamenit*60;
						return "$jam[0] ";//jam $sisamenit2 menit
						} ?>
  </ul>
</div>
<script>

	function tick(){
		$('#ticker_01 li:first').slideUp( function () { $(this).appendTo($('#ticker_01')).slideDown(); });
	}
	setInterval(function(){ tick () }, 5000);
	


	function tick2(){
		$('#ticker_02 li:first').slideUp( function () { $(this).appendTo($('#ticker_02')).slideDown(); });
	}
	setInterval(function(){ tick2 () }, 3000);


	function tick3(){
		$('#ticker_03 li:first').slideUp( function () { $(this).appendTo($('#ticker_03')).slideDown(); });
	}
	setInterval(function(){ tick3 () }, 3000);

	function tick4(){
		$('#ticker_04 li:first').slideUp( function () { $(this).appendTo($('#ticker_04')).slideDown(); });
	}

	function tick5(){
		$('#ticker_05 li:first').slideUp( function () { $(this).appendTo($('#ticker_05')).slideDown(); });
	}
	setInterval(function(){ tick5 () }, 3000);
	function tick6(){
		$('#ticker_06 li:first').slideUp( function () { $(this).appendTo($('#ticker_06')).slideDown(); });
	}
	setInterval(function(){ tick6 () }, 3000);


	/**
	 * USE TWITTER DATA
	 */

	 $.ajax ({
		 url: 'http://search.twitter.com/search.json',
		 data: 'q=%23javascript',
		 dataType: 'jsonp',
		 timeout: 10000,
		 success: function(data){
		 	if (!data.results){
		 		return false;
		 	}

		 	for( var i in data.results){
		 		var result = data.results[i];
		 		var $res = $("<li />");
		 		$res.append('<img src="' + result.profile_image_url + '" />');
		 		$res.append(result.text);

		 		console.log(data.results[i]);
		 		$res.appendTo($('#ticker_04'));
		 	}
			setInterval(function(){ tick4 () }, 4000);	

			$('#example_4').show();

		 }
	});


  </script>
  
</body>
</html>