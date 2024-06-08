<div class="modal-body">
							<div class="table-responsive">
								<table class="table table-striped  table-bordered table-hover nowrap" id="list-po">
									<thead>
										<tr>
											<th>No</th>
											<th>No PK</th>
											<th>Tgl</th>
											<th>Keterangan</th>
											<th>Jumlah</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
                  <?php
                  $no = 1;
                  foreach ($dataDetail as $s) {
                  ?>
                  <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $s->id_pk; ?></td>
                    <td>
                      
                    <input type="date" id="tgl" name="tgl" data-date="" data-date-format="DD/MM/YYYY" value="<?php echo $s->tgl_bayar; ?>"pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                    onchange="saveTgl('<?php echo $s->id; ?>',$(this).val())"></td>

                    <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan" 
                    onclick="this.contentEditable=true; this.className='inEdit';"
                    onblur="this.contentEditable=false; this.className='';" 
                    onkeypress="saveKet(event,'<?php echo $s->id_pk; ?>','<?php echo $s->id; ?>',$(this).html() )"><?php echo $s->keterangan; ?>
                  </td>
                    <td title="Tekan Enter untuk Simpan">
                    <input type="text" name="jumlah" id="jumlah" 
                    onkeyup="formatNumber(this)" 
                    onclick="formatNumber(this)"
                    onkeypress="saveData(event,'<?php echo $s->id_pk; ?>','<?php echo $s->id; ?>','<?php echo $s->sisa; ?>',$(this).val())"
                    class="form-control-sm col-sm-8"
                    value="<?php if($s->jumlah==0){echo"";}else{ echo number_format($s->jumlah);} ?>">
                  </td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-outline-danger delete-detail ion-android-delete" data-toggle="modal" data-target="#hapusDetail" data-id="<?php echo $s->id; ?>"></button>
                    </td>
                  </tr>
                <?php
                    $no++;
                  }
                  ?>
                  </tbody>
                  <td colspan="4" align="right">Sisa Pembayaran</td>
                  <td colspan="2"><?php if(empty($dataDetail)) {
                    echo'<font color=red size=3><strong>Belum ada pembayaran</strong></font>';
                  }else{  foreach ($dataSisa as $ds) {
                        $hasil_sisa=$ds->sisanye;}
                        if($hasil_sisa == 0){echo '<font color=green size=3><strong>LUNAS</strong></font>';}else{ echo '<font color=red size=3><strong>'.number_format($hasil_sisa).'</strong></font>';} }?>
                        </td>
									<tfoot>
                    
                  </tfoot>
								</table>
  </div>
						</div>

<script language="javascript">
            var MyTable = $('#list-po').DataTable({
              "responsive": false,
              "paging": false,
              "lengthChange": false,
              "searching": false,
              "ordering": false,
              "info": false
            });

  var biaya_borong = document.getElementById('biaya');
					biaya_borong.addEventListener('keyup', function(e)
    {
        biaya_borong.value = formatRupiah(this.value);
    });
function formatRupiah(angka, prefix)
    {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah     = split[0].substr(0, sisa),
            ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
function formatNumber(input)
{
    var num = input.value.replace(/\,/g,'');
    if(!isNaN(num)){
    if(num.indexOf('.') > -1){
    num = num.split('.');
    num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'');
    if(num[1].length > 2){
    alert('You may only enter two decimals!');
    num[1] = num[1].substring(0,num[1].length-1);
    } input.value = num[0]+'.'+num[1];
    } else{ input.value = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'') };
    }
    else{ alert('Anda hanya diperbolehkan memasukkan angka!');
    input.value = input.value.substring(0,input.value.length-1);
    }
}
    function saveTgl(id,tgl_bayar) {
		$.ajax({
			type: 'POST',
			url: "<?php echo base_url('PembayaranPk/updateTglBayar')?>",
			data: {
                        'id': id,
                        'tgl_bayar' :tgl_bayar,
                      },
			success: function(hasil) {
                
                tampilDetail();
			}
		});
	}
          function saveKet(e,idpk,id,keterangan) {
          if(e.keyCode === 13 ){
              e.preventDefault();
              $.ajax({
                type: "POST",
                url: "<?php echo base_url('PembayaranPk/updateKetBayar')?>",
                data: {  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'id': id,
                        'keterangan' :keterangan,
                      },
                success: function(response){ 
                
                  tampilDetail();
                }
              });
        }  
        }
        function saveData(e,idpk,id,sisa,jumlah) {
          if(e.keyCode === 13){
              e.preventDefault();
              $.ajax({
                type: "POST",
                url: "<?php echo base_url('PembayaranPk/updateBayar')?>",
                data: {  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'id': id,
                        'sisa' :sisa,
                        'jumlah' :jumlah,
                      },
                success: function(response){ 
                
                  tampilDetail();
                }
              });
        }  
        }
        </script>
