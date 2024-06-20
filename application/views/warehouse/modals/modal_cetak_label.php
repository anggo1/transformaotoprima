<script>
		window.print();top.close();
	
</script>

    <style>
		 p, td, th {
    font:2 Verdana, Arial, Helvetica, sans-serif;
	
}
.datatable2 {
    border: 0px solid #000;
    border-collapse: collapse;
	text-align:left;
	padding: 1px;
	padding-top: 1px;
	padding-bottom: 1px;
	padding-left: 1px;
}
.datatable2 th, td {
    border: 1px solid #000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
    font-weight: normal;
	text-align:justify;
	padding: 1px;
	padding-top: 1px;
	padding-bottom: 1px;
	padding-left: 1px;

}
</style>  


<?php
    if (!empty($dataPart)) {
      foreach ($dataPart as $part) {

    
   
    ?>

      <!--<?php echo '<img src="'.base_url().'qr.png" width="250"  align="center" />' ?>-->
  <?php }} ?>  
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="datatable2">
                                        <tbody>
                                            <tr>
												
                                                <td width="6%" rowspan="4"><img src="<?= base_url('./assets/img_qr/'.$part->no_part.'.png') ?>" alt="QRcode-part" width="70" height="70"></td>
                                                <td width="94%"><?php if (!empty($part->no_part)) {
                                                          echo $part->no_part; } ?></td>
                                            </tr>
                                            <tr>
                                              <td><?php if (!empty($part->nama_part)) {
                                                          echo $part->nama_part; } ?></td>
                                            </tr>
                                            <tr>
                                              <td><?php if (!empty($part->type_mesin)) {
                                                          echo $part->type_mesin; } ?></td>
                                            </tr>
                                            <tr>
                                              <td height="18"><?php if (!empty($part->lokasi)) {
                                                          echo $part->lokasi; } ?></td>
                                            </tr>
                                        </tbody>
                                    </table><table width="100%" border="0">
  <tbody>
    <tr>
      <td align="right" style="text-align: end;border: 0px solid #000;
	font-size:8px;
    font-weight: bold;"> PT.Transforma Oto Prima</td>
    </tr>
  </tbody>
</table>

                    
<script>

$('body').removeClass('modal-open');
$('.modal-backdrop').remove();

</script>
        </div>