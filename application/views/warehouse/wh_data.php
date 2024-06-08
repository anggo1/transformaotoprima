
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-light">
            <h3 class="card-title"><i class="fa fa-list text-blue"></i> Data Kepegawaian</h3>
            <div class="text-right">
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="add_pegawai()" title="Add Data"><i class="fas fa-plus"></i> Add</button>
              <a  href="<?php echo base_url('pegawai/download'); ?>" type="button" class="btn btn-sm btn-outline-info" id="dwn_pegawai" title="Download"><i class="fas fa-download"></i> Download</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tabel1" class="table table-bordered table-striped table-hover">
              <thead>
                <tr class="bg-info">
                  <th>No</th>
                  <th>NIP</th>
                  <th>Nama</th>
                  <th>Tgl Lahir</th>
                  <th>Pendidikan</th>
                  <th>Jabatan</th>
                  <th>Departement</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>

<!-- Modal Hapus-->
<div class="modal fade" id="myModal" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Konfirmasi</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idhapus" id="idhapus">
        <p>Apakah anda yakin ingin menghapus pegawai <strong class="text-konfirmasi"> </strong> ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-xs" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger btn-xs" id="konfirmasi">Hapus</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="modal-default">
  <div class="modal-dialog modal-xl">
    <div class="modal-content" >
      <div class="modal-header">
        <h4 class="modal-title ">View pegawai</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center" id="md_def">

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
</div>
<!-- /.modal -->  


<script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table =$("#tabel1").DataTable({
      "responsive": true,
      "autoWidth": false,
      "language": {
        "sEmptyTable": "Data pegawai Belum Ada"
      },
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('Pegawai/ajax_list')?>",
          "type": "POST"
        },
         //Set column definition initialisation properties.
         "columnDefs": [
         { 
            "targets": [ -1 ], //last column
            "render": function ( data, type, row ) {

             
              if (row[10] && row[11]=="Y") { 
                return "<a class=\"btn btn-xs btn-outline-info\" href=\"javascript:void(0)\" title=\"View\" onclick=\"vpegawai("+row[7]+")\"><i class=\"fas fa-eye\"></i></a> <a class=\"btn btn-xs btn-outline-primary\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit_pegawai("+row[7]+")\"><i class=\"fas fa-edit\"></i></a><a class=\"btn btn-xs btn-outline-danger\" href=\"javascript:void(0)\" title=\"Delete\" nama="+row[0]+"  onclick=\"delpegawai("+row[7]+")\"><i class=\"fas fa-trash\"></i></a>"
              }else{
               return "<a class=\"btn btn-xs btn-outline-info\" href=\"javascript:void(0)\" title=\"View\" onclick=\"vpegawai("+row[7]+")\"><i class=\"fas fa-eye\"></i></a> <a class=\"btn btn-xs btn-outline-primary\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit_pegawai("+row[7]+")\"><i class=\"fas fa-edit\"></i></a>";
             }

           },
            "orderable": false, //set not orderable
          },
          ],
        });

 //set input/textarea/select event when change value, remove class error and remove text help block 
 $("input").change(function(){
  $(this).parent().parent().removeClass('has-error');
  $(this).next().empty();
  $(this).removeClass('is-invalid');
});
 $("textarea").change(function(){
  $(this).parent().parent().removeClass('has-error');
  $(this).next().empty();
  $(this).removeClass('is-invalid');
});
 $("select").change(function(){
  $(this).parent().parent().removeClass('has-error');
  $(this).next().empty();
  $(this).removeClass('is-invalid');
});

});

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
  }

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });


//view
// $(".v_pegawai").click(function(){
  function vpegawai(id){
    $('.modal-title').text('View pegawai');
    $("#modal-default").modal();
    $.ajax({
      url : '<?php echo base_url('Pegawai/viewpegawai'); ?>',
      type : 'post',
      data : 'table=tbl_pegawai&id='+id,
      success : function(respon){

        $("#md_def").html(respon);
      }
    })


  }


  function delpegawai(id){

    Swal.fire({
      title: 'Anda Yakin?',
      text: "Anda Akan Menghapus seluruh data pegawai tersebut!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url:"<?php echo site_url('Pegawai/delete');?>",
          type:"POST",
          data:"nip="+id,
          cache:false,
          dataType: 'json',
          success:function(respone){
            if (respone.status == true) {
              reload_table();
              Swal.fire(
                'Deleted!',
                'File anda telah dihapus.',
                'success'
                );
            }else{
              Toast.fire({
                icon: 'error',
                title: 'Delete Error!!.'
              });
            }
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal(
          'Cancelled',
          'Your imaginary file is safe :)',
          'error'
          )
      }
    })
  }



  function add_pegawai()
  {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Penambahan Data Pegawai'); // Set Title to Bootstrap modal title
  }

  function edit_pegawai(nip){
   save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
      url : "<?php echo site_url('Pegawai/editpegawai')?>/" + nip,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {

        $('[name="nip"]').val(data.nip);
        $('[name="nama_depan"]').val(data.nama_depan);
        $('[name="nama_belakang"]').val(data.nama_belakang);
        $('[name="departement"]').val(data.departement);
        $('[name="jabatan"]').val(data.jabatan);
        $('[name="status_kerja"]').val(data.status_kerja);
        $('[name="tgl_masuk"]').val(data.tgl_masuk);
        $('[name="tempat_lahir"]').val(data.tempat_lahir);
        $('[name="tgl_lahir"]').val(data.tgl_lahir);
        $('[name="agama"]').val(data.agama);
        $('[name="status_nikah"]').val(data.status_nikah);
        $('[name="pendidikan"]').val(data.pendidikan);
        $('[name="alamat"]').val(data.alamat);
        $('[name="kodepos"]').val(data.kodepos);
        $('[name="no_hp"]').val(data.no_hp);
        $('[name="status_nikah"]').val(data.status_nikah);
        $('[name="jamsostek"]').val(data.jamsostek);
        $('[name="tinggi"]').val(data.tinggi);
        $('[name="berat"]').val(data.berat);
        $('[name="darah"]').val(data.darah);
        $('[name="s_kawin"]').val(data.s_kawin);
        $('[name="no_ktp"]').val(data.no_ktp);
        $('[name="npwp"]').val(data.npwp);
        $('[name="catatan1"]').val(data.catatan1);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit pegawai'); // Set title to Bootstrap modal title

          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');
          }
        });
  }

  function save()
  {
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
      url = "<?php echo site_url('pegawai/insert')?>";
    } else {
      url = "<?php echo site_url('pegawai/update')?>";
    }

    // ajax adding data to database
    $.ajax({
      url : url,
      type: "POST",
      data: $('#form').serialize(),
      dataType: "JSON",
      success: function(data)
      {

            if(data.status) //if success close modal and reload ajax table
            {
              $('#modal_form').modal('hide');
              reload_table();
              Toast.fire({
                icon: 'success',
                title: 'Success!!.'
              });
            }
            else
            {
              for (var i = 0; i < data.inputerror.length; i++) 
              {
                $('[name="'+data.inputerror[i]+'"]').addClass('is-invalid');
                $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]).addClass('invalid-feedback');
              }
            }
            $('#btnSave').text('Simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error adding / update data');
            $('#btnSave').text('Simpan'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

          }
        });
  }
    var loadFile = function(event) {
    var image = document.getElementById('v_image');
    image.src = URL.createObjectURL(event.target.files[0]);
  };

  function batal() {
    $('#form')[0].reset();
    reload_table();
    var image = document.getElementById('v_image');
    image.src ="";
  }
</script>

</script>



<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title">Person Form</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="nip"/> 
          <div class="row">
    <div class="col-sm-12" data-spy="scroll" data-offset="0">
        <div class="panel panel-default">
            <section class="content">
                <div class="container-fluid">
                    <?php if (!empty($dataPegawai)) {
                        foreach ($dataPegawai as $dataPegawai) {
                        }
                    } ?>
                    <div class="row">
                        <div class="col-12 ">                            
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="card card-danger card-outline">
                                            <div class="card-body">
                                                <h4 class="panel-title">Status Kepegawaian</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <label class="control-label">NIP <span class="required"> *</span></label>
                                                    <input type="text" required <?php if (!empty($dataPegawai->nip)) {
                                                                                    echo 'readonly';
                                                                                } ?> name="nip" value="<?php
                                                                if (!empty($dataPegawai->nip)) {
                                                echo $dataPegawai->nip;
                                                                        }
                                            ?>" class="form-control">
                                                </div>
                                                <div class="">
                                                    <label class="control-label">Departement <span class="required"> *</span></label>
                                                    <select name="departement" required class="form-control">
                                                        <option value="">Pilih Departement.....</option>
                                                        <?php
                                                        if (empty($dataPegawai->departement)) {
                                                            foreach ($databagian as $dep) {
                                                        ?>
                                                                <option <?php echo $dep == $dep->id_departement ? 'selected="selected"' : '' ?> value="<?php echo $dep->id_departement ?>"><?php echo $dep->departement ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                        } else {
                                                            foreach ($databagian as $de) {          ?>
                                                                <option value="<?php echo $de->id_departement; ?>" <?php if ($de->id_departement == $dataPegawai->departement) {
                                                                                                                        echo "selected='selected'";
                                                                                                                    } ?>><?php echo $de->departement; ?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="">
                                                    <label class="control-label">Posisi / Jabatan <span class="required"> *</span></label>
                                                    <select name="jabatan" required class="form-control">
                                                        <option value="">Pilih posisi/jabatan.....</option>
                                                        <?php
                                                        if (empty($dataPegawai->jabatan)) {
                                                            foreach ($dataposisi as $pos) {
                                                        ?>
                                                                <option <?php echo $pos == $pos->id_jabatan ? 'selected="selected"' : '' ?> value="<?php echo $pos->id_jabatan ?>"><?php echo $pos->jabatan  ?><?php } ?> </option>
                                                                <?php
                                                            } else {
                                                                foreach ($dataposisi as $posisi) {          ?>
                                                                    <option value="<?php echo $posisi->id_jabatan; ?>" <?php if ($posisi->id_jabatan == $dataPegawai->jabatan) {
                                                                                                                            echo "selected='selected'";
                                                                                                                        } ?>><?php echo $posisi->jabatan; ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="field-1" class="control-label">Tanggal Masuk </label>
                                                    <div class="input-group date" id="tgl1" data-target-input="nearest">

                                                        <input type="text" name="tgl_masuk" id="tgl1" value="<?php
                                                                                                                if (!empty($dataPegawai->tgl_masuk)) {
                                                                                                                    $tgl_masuk = $dataPegawai->tgl_masuk;
                                                                                                                    $tgl1 = explode('-', $tgl_masuk);
                                                                                                                    $tgl_masuknya = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
                                                                                                                    echo $tgl_masuknya;
                                                                                                                }
                                                                                                                ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="tgl1"/>

                                                        <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <label class="control-label">Status Kerja</label>
                                                        <input type="text" name="status_kerja" value="<?php
                                                                                                        if (!empty($dataPegawai->status_kerja)) {
                                                                                                            echo $dataPegawai->status_kerja;
                                                                                                        }
                                                                                                        ?>" class="form-control">
                                                    </div>
                                                    <div class="">
                                                        <label class="control-label">Keterangan</label>
                                                        <input type="text" name="catatan1" required value="<?php
                                                                                                            if (!empty($dataPegawai->catatan1)) {
                                                                                                                echo $dataPegawai->catatan1;
                                                                                                            }
                                                                                                            ?>" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card card-info card-outline">
                                            <div class="card-body">
                                                <h4 class="panel-title">Data Lengkap Pegawai</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <label class="control-label">Nama Depan <span class="required"> *</span></label>
                                                    <input type="text" name="nama_depan" value="<?php
                                                                                                if (!empty($dataPegawai->nama_depan)) {
                                                                                                    echo $dataPegawai->nama_depan;
                                                                                                }
                                                                                                ?>" class="form-control">
                                                </div>
                                                <div class="">
                                                    <label class="control-label">Nama belakang </label>
                                                    <input type="text" name="nama_belakang" value="<?php
                                                                                                    if (!empty($dataPegawai->nama_belakang)) {
                                                                                                        echo $dataPegawai->nama_belakang;
                                                                                                    }
                                                                                                    ?>" class="form-control">
                                                </div>

                                                <div class="">
                                                    <label class="control-label">Tempat Lahir </label>
                                                    <input type="text" name="tempat_lahir" value="<?php
                                                                                                    if (!empty($dataPegawai->tempat_lahir)) {
                                                                                                        echo $dataPegawai->tempat_lahir;
                                                                                                    }
                                                                                                    ?>" class="form-control">
                                                </div>
                                                <div class="">
                                                    <label class="control-label">Tanggal Lahir</label>
                                                    <div class="input-group date" id="tgl2" data-target-input="nearest">

                                                        <input type="text" name="tgl_lahir" id="tgl2" required value="<?php
                                                                                                                        if (!empty($dataPegawai->tgl_lahir)) {
                                                                                                                            $tgl_lahir = $dataPegawai->tgl_lahir;
                                                                                                                            $tgl1 = explode('-', $tgl_lahir);
                                                                                                                            $tgl_lahirnya = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";
                                                                                                                            echo $tgl_lahirnya;
                                                                                                                        }
                                                                                                                        ?>" class="form-control tgl2 datetimepicker" data-toggle="datepicker" data-target=".tgl2" data-format="yyy-mm-dd">

                                                        <div class="input-group-append" data-target="#tgl2" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <label class="control-label">Jenis Kelamin</label>
                                                    <select name="kelamin" class="form-control">
                                                        <option value="">Pilih Jenis ...</option>
                                                        <option value="Pria" <?php
                                                                                if (!empty($dataPegawai->kelamin)) {
                                                                                    echo $dataPegawai->kelamin == 'Pria' ? 'selected' : '';
                                                                                }
                                                                                ?>>Pria</option>
                                                        <option value="Wanita" <?php
                                                                                if (!empty($dataPegawai->kelamin)) {
                                                                                    echo $dataPegawai->kelamin == 'Wanita' ? 'selected' : '';
                                                                                }
                                                                                ?>>Wanita</option>
                                                    </select>
                                                </div>
                                                <div class="">
                                                    <label class="control-label">Agama <span class="required"> *</span></label>
                                                    <select name="agama" class="form-control">
                                                        <option value="">Pilih Agama ...</option>
                                                        <option value="ISLAM" <?php
                                                                                if (!empty($dataPegawai->agama)) {
                                                                                    echo $dataPegawai->agama == 'ISLAM' ? 'selected' : '';
                                                                                }
                                                                                ?>>ISLAM</option>
                                                        <option value="KRISTEN" <?php
                                                                                if (!empty($dataPegawai->agama)) {
                                                                                    echo $dataPegawai->agama == 'KRISTEN' ? 'selected' : '';
                                                                                }
                                                                                ?>>KRISTEN</option>
                                                        <option value="KATOLIK" <?php
                                                                                if (!empty($dataPegawai->agama)) {
                                                                                    echo $dataPegawai->agama == 'KATOLIK' ? 'selected' : '';
                                                                                }
                                                                                ?>>KATOLIK</option>
                                                        <option value="BUDHA" <?php
                                                                                if (!empty($dataPegawai->agama)) {
                                                                                    echo $dataPegawai->agama == 'BUDHA' ? 'selected' : '';
                                                                                }
                                                                                ?>>BUDHA</option>
                                                        <option value="HINDU" <?php
                                                                                if (!empty($dataPegawai->agama)) {
                                                                                    echo $dataPegawai->agama == 'HINDU' ? 'selected' : '';
                                                                                }
                                                                                ?>>HINDU</option>
                                                        <option value="KONGHUCU" <?php
                                                                                    if (!empty($dataPegawai->agama)) {
                                                                                        echo $dataPegawai->agama == 'KONGHUCU' ? 'selected' : '';
                                                                                    }
                                                                                    ?>>KONGHUCU</option>
                                                    </select>
                                                </div>
                                                <div class="">
                                                    <label class="control-label">Status Perkawinan </label>
                                                    <select name="status_nikah" class="form-control">
                                                        <option value="">Pilih Status ...</option>
                                                        <option value="Nikah" <?php
                                                                                if (!empty($dataPegawai->status_nikah)) {
                                                                                    echo $dataPegawai->status_nikah == 'Nikah' ? 'selected' : '';
                                                                                }
                                                                                ?>>Nikah</option>
                                                        <option value="Lajang" <?php
                                                                                if (!empty($dataPegawai->status_nikah)) {
                                                                                    echo $dataPegawai->status_nikah == 'Lajang' ? 'selected' : '';
                                                                                }
                                                                                ?>>Lajang</option>
                                                        <option value="Duda" <?php
                                                                                if (!empty($dataPegawai->status_nikah)) {
                                                                                    echo $dataPegawai->status_nikah == 'Duda' ? 'selected' : '';
                                                                                }
                                                                                ?>>Duda</option>
                                                        <option value="Janda" <?php
                                                                                if (!empty($dataPegawai->status_nikah)) {
                                                                                    echo $dataPegawai->status_nikah == 'Janda' ? 'selected' : '';
                                                                                }
                                                                                ?>>Janda</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="">
                                                    <label class="control-label">Pendidikan </label>
                                                    <select name="pendidikan" class="form-control">
                                                        <option value="">Pilih Pendidikan...</option>
                                                        <?php
                                                        if (empty($dataPegawai->pendidikan)) {
                                                            foreach ($datapendidikan as $pend) {
                                                        ?>
                                                                <option <?php echo $pend == $pend->id_pendidikan ? 'selected="selected"' : '' ?> value="<?php echo $pend->id_pendidikan ?>"><?php echo $pend->pendidikan ?></option>
                                                            <?php
                                                            }
                                                        } else {
                                                            foreach ($datapendidikan as $pe) {          ?>
                                                                <option value="<?php echo $pe->id_pendidikan; ?>" <?php if ($pe->id_pendidikan == $dataPegawai->pendidikan) {
                                                                                                                        echo "selected='selected'";
                                                                                                                    } ?>><?php echo $pe->pendidikan; ?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card card-blue card-outline">
                                            <div class="card-body">
                                                <h4 class="panel-title">Informasi Pegawai</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <label class="control-label">Alamat</label>
                                                    <textarea id="present" name="alamat" class="form-control"><?php
                                                                                                                if (!empty($dataPegawai->alamat)) {
                                                                                                                    echo $dataPegawai->alamat;
                                                                                                                }
                                                                                                                ?></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                      <!-- text input -->
                                                      <div class="form-group">
                                                        <label>Kode Pos</label>
                                                        <input type="text" class="form-control" name="kodepos" value="<?php
                                                                                                if (!empty($dataPegawai->kodepos)) {
                                                                                                    echo $dataPegawai->kodepos;
                                                                                                }
                                                                                                ?>" placeholder="Kodepos ...">
                                                      </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                      <div class="form-group">
                                                        <label>No Telp</label>
                                                        <input type="text" class="form-control" name="no_hp" value="<?php
                                                                                            if (!empty($dataPegawai->no_hp)) {
                                                                                                echo $dataPegawai->no_hp;
                                                                                            }
                                                                                            ?>" placeholder="No Telp ...">
                                                      </div>
                                                    </div>   
                                                  </div>      
                                                <div class="">
                                                    <label class="control-label">No KTP </label>
                                                    <input type="text" name="no_ktp" value="<?php
                                                                                            if (!empty($dataPegawai->no_ktp)) {
                                                                                                echo $dataPegawai->no_ktp;
                                                                                            }
                                                                                            ?>" class="form-control">
                                                </div>
                                                <div class="">
                                                    <label class="control-label">NPWP</label>
                                                    <input type="text" name="npwp" value="<?php
                                                                                            if (!empty($dataPegawai->npwp)) {
                                                                                                echo $dataPegawai->npwp;
                                                                                            }
                                                                                            ?>" class="form-control">
                                                </div>
                                                <div class="">
                                                    <label class="control-label">BPJS</label>
                                                    <input type="text" name="jamsostek" value="<?php
                                                                                                if (!empty($dataPegawai->jamsostek)) {
                                                                                                    echo $dataPegawai->jamsostek;
                                                                                                }
                                                                                                ?>" class="form-control">
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                      <!-- text input -->
                                                      <div class="form-group">
                                                        <label>Tinggi</label>
                                                        <input type="text" class="form-control" name="tinggi" placeholder="Tinggi ...">
                                                      </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                      <div class="form-group">
                                                        <label>Berat</label>
                                                        <input type="text" class="form-control" placeholder="Berat ...">
                                                      </div>
                                                    </div>                                                    
                                                    <div class="col-sm-4">
                                                      <div class="form-group">
                                                        <label>Gol Darah</label>
                                                        <input type="text" class="form-control" placeholder="Gol Darah ...">
                                                      </div>
                                                    </div>
                                                  </div>       
                                                <div class="">
                                                    <label class="control-label">Status kawin</label>
                                                    <select name="s_kawin" class="form-control">
                                                      <option value="">Pilih Status ...</option>
                                                      <option value="TK/0" <?php
                                                                                if (!empty($dataPegawai->s_kawin)) {
                                                                                    echo $dataPegawai->s_kawin == 'TK/0' ? 'selected' : '';
                                                                                }
                                                                                ?>>TK/0</option>
                                                      <option value="K/0" <?php
                                                                                if (!empty($dataPegawai->s_kawin)) {
                                                                                    echo $dataPegawai->s_kawin == 'K/0' ? 'selected' : '';
                                                                                }
                                                                                ?>>K/0</option>
                                                      <option value="K/1" <?php
                                                                                if (!empty($dataPegawai->s_kawin)) {
                                                                                    echo $dataPegawai->s_kawin == 'K/1' ? 'selected' : '';
                                                                                }
                                                                                ?>>K/1</option>
                                                      <option value="K/2" <?php
                                                                                if (!empty($dataPegawai->s_kawin)) {
                                                                                    echo $dataPegawai->s_kawin == 'K/2' ? 'selected' : '';
                                                                                }
                                                                                ?>>K/2</option>
														<option value="K/3" <?php
                                                                                if (!empty($dataPegawai->s_kawin)) {
                                                                                    echo $dataPegawai->s_kawin == 'K/3' ? 'selected' : '';
                                                                                }
                                                                                ?>>K/3</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button class="btn btn-danger" data-dismiss="modal" onclick="batal()" aria-hidden="true">Tutup</button>
                                    <button class="btn btn-primary"  id="btnSave" onclick="save()"><span class="fa fa-save"></span> Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
        </div>
    </div>
</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->