<?php echo $this->session->flashdata('msg'); ?>
<div class="row">
    <div class="col-12 ">
        <div class="card">
            <div class="card-body card-outline">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Widget: user widget style 1 -->
                        <div class="card card-widget widget-user">
                            <div class="widget-user-header text-white"
                                style="background: url(<?php echo base_url(); ?>assets/img/logo3.png) gray no-repeat center center">


                            </div>
                            <div class="widget-user-image">
                                <img class="profile-user-img img-fluid img-circle" src="<?php
                if (!empty($this->session->userdata['image']))
                { echo base_url();?>assets/foto/user/<?php echo $this->session->userdata['image'];
                                            }
                else{ echo base_url().'assets/img/se3.png';} ?>" alt="User profile picture">
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <!-- /.col -->
                                    <div class="col-sm-12 border-right">
                                        <h3 class="widget-user-username text-center">
                                            <font color="#000"></font>
                                            <?php echo $this->session->userdata['full_name']; ?>
                                        </h3>
                                        <div class="description-block">
                                            <h5 class="description-header">Username</h5>
                                            <span
                                                class="description-text"><?php echo $this->session->userdata['username']; ?></span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>
                    <div class="col-md-8">
                        <div class="card card-gray card-outline ">
                            <div class="card-body card-outline">

                                <div class="card-header p-0 border-bottom-0 ">
                                    <ul class="nav nav-tabs " id="custom-content-above-tab" role="tablist">
                                        <li class="nav-item">

                                            <a class="nav-link active" id="tab-daftar-tab" data-toggle="pill"
                                                href="#settings" role="tab"> <i class="fas fa-edit"></i> Settings</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab-proses-tab" data-toggle="pill" href="#password"
                                                role="tab"> <i class="fas fa-luggage-cart"></i>Ubah Password</a>
                                        </li>

                                    </ul>
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="settings">
                                            <form action="#" id="form" class="form-horizontal"
                                                enctype="multipart/form-data">
                                                <!-- <?php echo form_open_multipart('', array('class' => 'form-horizontal', 'id' => 'form')) ?> -->
                                                <input type="hidden" name="id_user"
                                                    value="<?php echo $this->session->userdata['id_user']; ?>" />
                                                <div class="card-body">
                                                    <div class="form-group row ">
                                                        <label for="username" class="col-sm-3 col-form-label">User
                                                            Name</label>
                                                        <div class="col-sm-9 kosong">
                                                            <input type="text" class="form-control" name="username"
                                                                id="username" placeholder="Username"
                                                                value="<?php echo $this->session->userdata['username']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row ">
                                                        <label for="full_name" class="col-sm-3 col-form-label">Full
                                                            Name</label>
                                                        <div class="col-sm-9 kosong">
                                                            <input type="text" class="form-control" name="full_name"
                                                                id="full_name" placeholder="Full Name"
                                                                value="<?php echo $this->session->userdata['full_name']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row ">
                                                        <label for="imagefile"
                                                            class="col-sm-3 col-form-label">Foto</label>
                                                        <div class="col-sm-9 kosong ">
                                                            <img id="v_image" width="100px" height="100px">
                                                            <input type="file" class="form-control btn-file"
                                                                onchange="loadFile(event)" name="imagefile"
                                                                id="imagefile" placeholder="Image" value="UPLOAD">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <?php echo form_close(); ?> -->
                                            </form>
                                            <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" id="btnSave" onclick="save()"
                                                    class="btn btn-primary"><span class="fa fa-save"></span>&nbsp;&nbsp; Simpan</button>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="password">
                                            <form action="#" id="formPass" class="form-horizontal"
                                                enctype="multipart/form-data">
                                                <input type="hidden" name="id_user"
                                                    value="<?php echo $this->session->userdata['id_user']; ?>" />
                                                <div class="form-group">
                                                    <label for="passLama" class="col-sm-2 control-label">Password
                                                        Lama</label>
                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control"
                                                            placeholder="Password Lama" name="passLama">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="passBaru" class="col-sm-2 control-label">Password
                                                        Baru</label>
                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control"
                                                            placeholder="Password Baru" name="passBaru">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="passKonf" class="col-sm-2 control-label">Konfirmasi
                                                        Password</label>
                                                    <div class="col-sm-10">
                                                        <input type="password" class="form-control"
                                                            placeholder="Konfirmasi Password" name="passKonf">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" id="btnSavePass" onclick="savePass()"
                                                    class="btn btn-primary"><span class="fa fa-save"></span>&nbsp;&nbsp; Simpan</button>
                                                        
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="msg" style="display:none;">
                        <?php echo $this->session->flashdata('msg'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="javascript">
var loadFile = function(event) {
    var image = document.getElementById('v_image');
    image.src = URL.createObjectURL(event.target.files[0]);
};

function save() {
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled', true); //set button disable 
    var url;
    url = "<?php echo site_url('Profile/update_data')?>";
    var formdata = new FormData($('#form')[0]);
    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {

            if (data.status) //if success close modal and reload ajax table
            {
                //reload_table();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Untuk Perubahan harap Re login',
                    showConfirmButton: true,
                    imageUrl: 'assets/dist/img/thumbs.png'


                });
                //setTimeout(function(){
                //window.location.reload(1);}, 1000);
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid');
                    $('[name="' + data.inputerror[i] + '"]').closest('.kosong').append('<span></span>');
                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]).addClass(
                        'invalid-feedback');
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable 


        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(textStatus);
            // alert('Error adding / update data');
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Gagal',
                showConfirmButton: false,
                timer: 900
            });
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable 

        }
    });
}
function savePass() {
    $('#btnSavePass').text('saving...'); //change button text
    $('#btnSavePass').attr('disabled', true); //set button disable 
    var url;
    url = "<?php echo site_url('Profile/update_pass')?>";
    var formdata = new FormData($('#formPass')[0]);
    $.ajax({
        url: url,
        type: "POST",
        data: formdata,
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
  var out = data;

if (out.status == 'gagal1') {
  $('.msg').html(out.msg);
  Swal.fire({
          position: 'center',
          icon: 'error',
          title: out.msg,
          showConfirmButton: true
      })
  $('#btnSavePass').text('save'); //change button text
  $('#btnSavePass').attr('disabled', false); //set button enable 
  setTimeout(function(){
  window.location.reload(1);}, 1000);
  if (out.status == 'gagal2') {
  } 
    $('.msg').html(out.msg);
    Swal.fire({
          position: 'center',
          icon: 'error',
          title: out.msg,
          showConfirmButton: true
      })
      //toastr.error(out.msg);
  $('#btnSavePass').text('save'); //change button text
  $('#btnSavePass').attr('disabled', false); //set button enable 
  setTimeout(function(){
  window.location.reload(1);}, 1000);
  }
  if (out.status == 'ok') { 
      Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Untuk Perubahan harap Re login',
          showConfirmButton: true,
          imageUrl: 'assets/dist/img/thumbs.png'
      })
  }

  $('#btnSavePass').text('save'); //change button text
  $('#btnSavePass').attr('disabled', false); //set button enable 


},
    });
}
</script>