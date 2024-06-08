var xmlhttp;
var pengemudi;
var kernet;
var asal;
var nobody;
var nopol;
var kasus;
var jalur;
var jatah;
var jenis;
var area;
var kpl_barat;
var kpl_timur;
var timer;

/*req_namapengemudi*/
function req_nama_sp1(get_kode,get_area,flag)
{
clearTimeout(timer);
nic_sp=get_kode;
area=get_area;
if(flag=="start")
{
timer=setTimeout("req_nama_sp1(nic_sp,area,'delay')",200);
}
else if(flag=="delay")
{
if(get_kode==document.getElementById("nic_sp1").value)
{
var url="models/nama_sp1.php?rand="+Math.random();
var post="nic_sp="+nic_sp+"&act=req_nama_sp1";
ajax(url,post,area);
}
else
{timer=setTimeout("req_nama_sp1(nic_sp,area,'delay')",200);}
}
}
/* cari Kernet Brow */
function req_nama_kr(get_kode,get_area,flag)
{
clearTimeout(timer);
nic_kr=get_kode;
area=get_area;
if(flag=="start")
{
timer=setTimeout("req_nama_kr(nic_kr,area,'delay')",200);
}
else if(flag=="delay")
{
if(get_kode==document.getElementById("nic_kr").value)
{
var url="models/nama_kr.php?rand="+Math.random();
var post="nic_kr="+nic_kr+"&act=req_nama_kr";
ajax(url,post,area);
}
else
{timer=setTimeout("req_nama_kr(nic_kr,area,'delay')",200);}
}
}
/* cari nomor Kasus */
var xmlhttp = false;

try {
	xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
		xmlhttp = false;
	}
}

if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
	xmlhttp = new XMLHttpRequest();
}


//untuk bukutamu
function surat(key){
	var obj=document.getElementById("pencarian");
	var url='proses_surat.php?key='+key;
	
	xmlhttp.open("GET", url);
	
	xmlhttp.onreadystatechange = function() {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			obj.innerHTML = xmlhttp.responseText;
		} else {
			obj.innerHTML = "<div align ='center'><img src='waiting.gif' alt='Loading' /></div>";
		}
	}
	xmlhttp.send(null);
}
/*ajax*/
function ajax(url,post,area)
{
xmlhttp=GetXmlHttpObject();
xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
{
if(xmlhttp.status==200)
{
area.value=xmlhttp.responseText;
}
else{ajax_fail();}
}
}
xmlhttp.open("POST",url,true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send(post);
}
/*--------*/

/*ajax_fail*/
function ajax_fail()
{
alert("There's a problem with the ajax, please reload the page.");
return false;
}
/*--------*/

/*pilih xmlhttp berdasarkan browser*/
function GetXmlHttpObject()
{
if(window.XMLHttpRequest)
{
return new XMLHttpRequest();
}
if(window.ActiveXObject)
{
return new ActiveXObject("Microsoft.XMLHTTP");
}
else
{alert("Maaf browser anda tidak mendukung ajax.");}
return false;
}

function handleEnter (field, event) {
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
		if (keyCode == 13) {
			var i;
			for (i = 0; i < field.form.elements.length; i++)
				if (field == field.form.elements[i])
					break;
			i = (i + 1) % field.form.elements.length;
			field.form.elements[i].focus();
			return false;
		} 
		else
		return true;
	}   
	
	
	
/*--------*/
//-->
