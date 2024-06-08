<script>
        document.getElementById("btnPrint").onclick = function () {
    printElement(document.getElementById("printThis"));
}

function printElement(elem) {
    var domClone = elem.cloneNode(true);
    
    var $printSection = document.getElementById("printSection");
    
    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }
    
    $printSection.innerHTML = "";
    $printSection.appendChild(domClone);
    window.print();
	//window.location.assign("<?php echo base_url(); ?>/PartPk");
}
    </script>
     <style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible;
  }
  #printSection {
    position: absolute;
    left:0;
    top:0;
    width: 100%;
  }
}


p, td, th {
    font:2 Verdana, Arial, Helvetica, sans-serif;
	
}
.datatable {
    border-collapse: collapse;
    font: bold;
}
.datatable td {
    padding: 0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
    font: bold;
}
.datatable th {
    border: 2px solid #000;
    font: bold;
    font-weight: normal;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
}
.under { text-decoration: underline; }
#A4 {background-color:#FFFFFF;
left:1px;
right:1px;
height:5.51in ; /*Ukuran Panjang Kertas */
width: 8.50in; /*Ukuran Lebar Kertas */
margin:1px solid #FFFFFF;
 
font-family:Georgia, "Times New Roman", Times, serif;
}
    </style>
<div id="printThis">
<div class="alert bg-white"><?php foreach ($dataPk as $k){}?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" style="border-collapse: collapse; position: relative; background-color:#000 border: 2px solid #000; border:double list-style-position: outside;	background-attachment: scroll;	background-repeat: repeat-x; font-family: arial; font-size: 13px;" >
  <thead>
                    <tr>
                      <th colspan="4"><div align="left">
                        <table width="100%">
                          <tbody>
                            <tr>
                              <td colspan="5"><font size="+2"> BON BARANG</font> </td>
                            </tr>
                            <tr>
                              <td>No BON</td>
                              <td>: <?php echo $k->kode_keluar ?></td>
                              <td>Tujuan</td>
                              <td>: <?php echo $k->tujuan ?></td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td width="15%">Tgl</td>
                              <td width="49%">: <?php echo tglIndoSedang($k->tgl_keluar) ?></td>
                              <td width="15%">Keterangan</td>
                              <td width="21%">: <?php echo $k->keterangan ?></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </tr>
   </thead>
                <tbody>
               
			
                </tbody>
</table>
                    <div class="table-responsive">
<table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#000000" class="datatable"  id="table-1" >
   <thead>
     <tr>
       <th width="4%">No</th>
       <th>No Part</th>
       <th>Nama Part</th>
       <th>Jumlah</th>
       <th>Keterangan</th>
       </tr>
     <?php
       $no=0;
       foreach ($dataDetail as $x): 
								  
	     $no++;
						?>
      <tr>
        <th><?php echo $no ?></th>
        <th><?php echo $x->no_part ?></th>
        <th><?php echo $x->nama_part ?></th>
        <th><?php echo $x->jumlah ?></th>
        <th><?php echo $x->ket_part ?></th>
      </tr><?php  endforeach ?>
      </tr>
     
   </thead>
   <tbody>
</table>
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable3">
        <thead>
          <tr>
            <th width="20%" style="text-align: center; align-content: center;">&nbsp;</th>
            <th width="20%" style="text-align: center; align-content: center;">&nbsp;</th>
            <th width="20%" style="text-align: center; align-content: center;">&nbsp;</th>
            <th width="25%">Dibuat Oleh</th>
          </tr>
          <tr>
            <th style="text-align: center; align-content: center;">&nbsp;</th>
            <th style="text-align: center; align-content: center;">&nbsp;</th>
            <th height="60" style="text-align: center; align-content: center;">
              <p>&nbsp;</p>
            </th>
            <th> <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p><?php echo $this->session->userdata['full_name']; ?></p>
            </th>
          </tr>
        </thead>

      </table>
	</div>

</div>
</div>
        <div class="card-footer">
        <button type="button" id="btnPrint" class="btn btn-success" ><span class="fa fa-print"></span>&nbsp;&nbsp;  C E T A K </button>
      <button class="btn btn-danger" id="tutup" data-dismiss="modal"><span class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>