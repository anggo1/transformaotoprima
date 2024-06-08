<style>
    .table.DataTable {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
    }

    table.dataTable td {
        padding: 5px;
    }
</style>
<div class="col-12 ">
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-detail">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>No Part</th>
                    <th>Nama Part</th>
                    <th>Std</th>
                    <th>Total</th>
                    <th>Jml</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                                            $no = 1;
                                            foreach ($dataDetail as $s) {
                                                $id_lapor=$_GET['id_lapor'];
                                                $ci = get_instance();
                                                $query = "SELECT SUM(b.jumlah) AS total
                                                            FROM `tbl_wh_part_keluar` AS a
                                                            LEFT JOIN `tbl_wh_detail_part_keluar` AS b ON b.id_keluar=a.id_keluar 
                                                            WHERE a.no_spk='{$id_lapor}' AND  b.no_part='{$s->no_part}'  GROUP BY b.no_part";
                                                $d_data = $ci->db->query($query)->row_array();
                                                if(!empty($d_data)){                                                    
                                                $total_pakai    = $d_data['total'];
                                                }

                                            ?> <tr>

                    <td width="5%"><?php echo $no; ?></td>
                    <td><?php echo $s->no_part; ?></td>
                    <td><?php echo $s->nama_part; ?></td>
                    <td align="center"><?php echo $s->std_pakai; ?></td>
                    <td align="center"><?php if(!empty($total_pakai)) {echo $total_pakai;}else{echo"";} ?></td>
                    <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan">
                    <input type="number" class="form-input col-sm-6" name="jumlah" id="jumlah"
                    value="<?php echo $s->jumlah ?>"
                    onkeypress="saveData(event,'<?php echo $s->id; ?>','<?php echo $s->id_keluar; ?>','<?php echo $s->no_part; ?>','<?php echo $s->status_part; ?>','<?php echo $id_lapor; ?>','<?php echo $s->std_pakai; ?>',$(this).val() )"
                    class="form-control col-sm-10">
                </td>
                    <td><?php echo $s->satuan; ?></td>
                    <td title="Double click Untuk Edit Dan Tekan Enter untuk Simpan"
                        onclick="this.contentEditable=true; this.className='inEdit';"
                        onblur="this.contentEditable=false; this.className='';"
                        onkeypress="saveKet(event,'<?php echo $s->id; ?>','<?php echo $s->id_keluar; ?>',$(this).html() )">
                        <?php echo $s->ket_part; ?></td>
                        <td class="text-center">
                    <button class="btn btn-xs bg-gradient-danger" onclick="delData(event,'<?php echo $s->id; ?>','<?php echo $s->no_part; ?>','<?php echo $s->jumlah; ?>','<?php echo $s->status_part; ?>')"><i class="fa fa-trash"></i></button>
            </td>
                </tr>
                <?php
                                                $no++;
                                            }
                                            ?>
            </tbody>
            <tfoot></tfoot>
        </table>
    </div>
</div>
<script language="javascript">
    
function saveKet(e,id,id_keluar,keterangan) {
          if(e.keyCode === 13 ){
              e.preventDefault();
              $.ajax({
                type: "POST",
                url: "<?php echo base_url('PartPk/updateKet')?>",
                data: {  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'id': id,
                        'keterangan' :keterangan,
                      },
                success: function(response){ 
                
                    showDetail(id_keluar);
                }
              });
        }  
        }
        function saveData(e,id,id_keluar,no_part,status_part,id_spk,std_pakai,jumlah) {
          if(e.keyCode === 13){
              e.preventDefault();
              $.ajax({
                type: "POST",
                url: "<?php echo base_url('PartPk/updateJumlah')?>",
                data: {  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'id': id,
                        'no_part' :no_part,
                        'status_part' :status_part,
                        'id_spk' :id_spk,
                        'std_pakai' :std_pakai,
                        'jumlah' :jumlah,
                      },
                success: function(data){ 
                    
                var out = jQuery.parseJSON(data);
                if (out.msg == 'Error')  {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Penggunaan Barang Melebihi Batas Standart Pakai',
                        text: "Apakah anda ingin melanjutkan?!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Ya!',
                        cancelButtonText: "Tidak!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }).then(function(result) {
                if (result.value) {
                    $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('PartPk/updateJumlahOver')?>",
                    data: {  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                            'id': id,
                            'no_part' :no_part,
                            'status_part' :status_part,
                            'id_spk' :id_spk,
                            'jumlah' :jumlah,
                        },
                    success: function(response){ 
                    
                        showDetail(id_keluar);
                    }
                });
                        //document.getElementById("btnTambah").disabled = false;
                    } })
                } else {
                showDetail(id_keluar);
                }
            }

            });
                
              }
        }  
        

function delData(e, id,no_part,jumlah,status_part) {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('PartPk/deletepartDetail')?>",
        data: {
            'id': id,
            'no_part': no_part,
            'jumlah': jumlah,
            'status_part': status_part,
        },

        success: function(response) {
            showDetail(id_keluar);
        }
    });
}
</script>