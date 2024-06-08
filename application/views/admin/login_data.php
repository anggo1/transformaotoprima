<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $aplikasi->title; ?> | Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome-5.5.0/css/all.min.css">
    <!-- Font Awesome -->
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/adminlte.min.css">
    <!-- Body style -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/styleanggo.css">
    <!-- iCheck -->
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="<?php echo base_url();?>assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition login-page" id="gradien3">
<div class="login-box">
      <div class="login-logo">
        <img src="<?php echo base_url(); ?>assets/dist/img/Logo.png" alt="logo" width="25%" class="shadow-light"><br>
		  <a href="<?php echo base_url();?>">
                    <b><?php 
          echo '<font color=white>'.$aplikasi->nama_aplikasi.'</font>';
        ?></b></a>
      </div>

        <!-- /.login-logo -->
        <div class="card">
            <div class="card-header bg-black">
                <h4><i class="fas fa-sign-in-alt"></i> Start your session</h4>
            </div>
            <div class="card-body login-card-body">

                <form action="" role="form" id="quickForm" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group mb-3 kosong">
                            <input type="text" name="username" class="form-control" placeholder="Username"
                                value="<?php echo set_value('username'); ?>">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label for="username">Password</label>
                    <div class="input-group mb-3 kosong">
                        <input type="password" name="password" class="form-control" placeholder="Password"
                            value="<?php echo set_value('password'); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <!-- /.col -->
                    <div class="center">
                        <button type="submit" id="login" class="btn btn-secondary bg-gradient btn-block btn-radius"><span
                                class="fa fa-sign-in-alt"></span> Sign In</button>
                    </div>
                    <!-- /.col -->
                    <!-- </div> -->
                </form>
            </div>
            <!-- <div class="col-md-12">      
        <?php
        if(!empty($pesan)) {
          echo $pesan;
        }?>
        </div> -->
            <!-- /.login-card-body -->
            <div class="card-footer" style="text-align: center;">
                <b>
                    <?php 
        // foreach ($aplikasi as $apl) {
          echo $aplikasi->copy_right.' '.$aplikasi->tahun.' <br> '.$aplikasi->nama_owner;
        // }
        
        ?>
                </b>
            </div>
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- jquery-validation -->
    <script src="<?php echo base_url();?>assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url();?>assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url();?>assets/dist/js/demo.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="<?php echo base_url();?>assets/plugins/toastr/toastr.min.js"></script>


    <script>
    $(document).on('submit', '#quickForm', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Login/login'); ?>',
                data: data
            })
            .done(function(data) {
                var data = jQuery.parseJSON(data);
                if (data.status) {
                    toastr.success('Login Berhasil!');
                    var url = '<?php echo base_url('dashboard') ?>';
                    window.location = url;
                } else if (data.error) {
                    toastr.error(
                        data.pesan
                    );
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid');
                        $('[name="' + data.inputerror[i] + '"]').closest('.kosong').append(
                            '<span></span>');
                        $('[name="' + data.inputerror[i] + '"]').next().next().text(data
                            .error_string[i]).addClass('invalid-feedback');
                    }
                }
            })

        e.preventDefault();
    });
    </script>
</body>

</html>