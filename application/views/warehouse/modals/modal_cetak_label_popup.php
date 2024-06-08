<?php 

?><script>function printThis() {
	document.getElementById("toGetRid").innerHTML = "";
	print();top.close();
	}</script><? if ($_GET["noScript"] != "1") { ?><? if ($export == "") { ?>
	<div id="toGetRid">
	    
        <input type=button value="C E T A K" onClick="javascript:printThis();" /><input type=button value="B A T A L" onClick="javascript: self.close ();"/>   
    </div><? } ?><? } ?>
    <?
?><style>
body{
text-align:center}

@media screen {
    #cetak {
        display:none;
    }
	
}

@media print {
	.isipro{
		display:none;}
	#frm{
		display:none;}	
    #cetak {
        display:block;
    }
	.gantihal{
		page-break-after:always;
	}
}	
select {
  width: 100%;
  padding: 10px 20px;
  border-radius: 3px;
  border: 1px solid #f1f1f1;
  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
          box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
  margin-bottom: 10px;
  font-weight: 300;
  outline: none;
}

<!--untuk tabel-->
p, td, th {
    font:2 Verdana, Arial, Helvetica, sans-serif;
	
}
.datatable {
    border: 0px solid #000;
    border-collapse: collapse;
	text-align:left;
}
.datatable th, td {
    border: 0px dashed #000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:20px;
    font-weight: normal;
	text-align:justify;

}
.datatable1 th, td {
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:8px;
    font-weight: normal;
	text-align:justify;

}
</style>
<body>
	   
         
    </div>
<?php
date_default_timezone_set('Asia/Jakarta');
$today = date('Y-m-d H:i:s');
$time = date('H:i:s');
?>
<?php
 function DateToIndo($date){
		$BulanIndo = array("Januari", "Februari", "Maret",
						   "April", "Mei", "Juni",
						   "Juli", "Agustus", "September",
						   "Oktober", "Nopember", "Desember");
	
		$tahun = substr($date, 0, 4);  
        $bulan = substr($date, 5, 2);  
        $tgl   = substr($date, 8, 2);  
          
        $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;       
        return($result);  
	}
?>   <div style="border:0px solid #000;" class="gantihal">
 
    <table width="100%" border="0" cellpadding="1" cellspacing="0"  class="datatable" >
  <thead>
                    <tr>
                      <th width="219" rowspan="4"><img src="../../../../assets/img_qr/.png" width="150" height="150" alt=""/></th>
                      <th width="1111" height="41">Kode Barang</th>
                    </tr>
                    <tr>
                      <th height="33">Nama Barang</th>
                    </tr>
                    <tr>
                      <th height="33">Jenis</th>
                    </tr>
                    <tr>
                      <th height="34">Lokasi</th>
                    </tr>
      </thead>
                <tbody>
</table>