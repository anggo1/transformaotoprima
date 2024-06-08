<div class="box ">
  <table style="width: 100%" > 
  <?php foreach ($data_table as $dataPegawai) {?>
              <div class="row">
    <div class="col-sm-12" data-spy="scroll" data-offset="0">                            
        <div class="panel panel-default">            
            <br />
	 <section class="content">
      <div class="container-fluid">
            <div id="printableArea">
				
        <div class="row">
          <div class="col-md-6">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
					<?php if ($dataPegawai->foto): ?>
                                    <img style="width:150px;height:150px;" class="img-circle width-5" src="<?php echo base_url() . 'assets/foto_pegawai/' . $dataPegawai->foto; ?>" alt="" />
                                <?php else: ?>
                                    <img src="<?php  echo base_url() ?>assets/img/unknown_user.png" class="profile-user-img img-fluid img-circle">
                                <?php endif; ?> 
                </div>

                <h3 class="profile-username text-center"><?php echo "$dataPegawai->nama_depan " . "$dataPegawai->nama_belakang"; ?></h3>

                <p class="text-muted text-center"><?php echo "$dataPegawai->nipa".'.'."$dataPegawai->nip"; ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                      <a class="float-left"><b>Departement</b></a> <a class="float-right"><?php echo "$dataPegawai->departement"; ?></a>
                  </li>
                  <li class="list-group-item">
                      <a class="float-left"><b>Jabatan</b> </a><a class="float-right"><?php echo "$dataPegawai->jabatan"; ?></a>
                  </li>
					
                  <li class="list-group-item">
                      <a class="float-left"><b>Tanggal Bergabung</b></a> <a class="float-right"><?php echo tgl_indo($dataPegawai->tgl_masuk); ?></a>
                  </li>
					
                  <li class="list-group-item">
                      <a class="float-left"><b>Status</b></a> <a class="float-right"><?php echo "$dataPegawai->status"; ?></a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                      <a class="float-left"><b>Pendidikan</b></a> <a class="float-right"><?php echo "$dataPegawai->pendidikan"; ?></a>
                  </li>
                  <li class="list-group-item">
                      <a class="float-left"><b>Alamat</b> </a><a class="float-right"><?php echo "$dataPegawai->alamat"; ?></a>
                  </li>
					
                  <li class="list-group-item">
                      <a class="float-left"><b>Agama</b></a> <a class="float-right"><?php echo $dataPegawai->agama; ?></a>
                  </li>
					
                  <li class="list-group-item">
                      <a class="float-left"><b>Jenis Kelamin</b></a> <a class="float-right"><?php echo "$dataPegawai->kelamin"; ?></a>
                  </li>
                </ul>
                
              </div>
              <!-- /.card-body -->
            </div>
            </div>
          <div class="col-md-6">
			  <div class="card card-primary">
              <!-- /.card-header -->
              <div class="card-body">

                <strong><i class="fas fa-pencil-alt mr-1"></i> Tempat & Tanggal Lahir</strong>

                <p class="text-muted">
                  <span class="tag tag-danger"><?php echo "$dataPegawai->tempat_lahir"; ?>,<?php echo tgl_indo($dataPegawai->tgl_lahir); ?></span>
                </p>

                <hr>


                <strong><i class="far fa-file-alt mr-1"></i> Usia</strong>

                <p class="text-muted"><?php echo hitung_umur($dataPegawai->tgl_lahir); ?></p>
				
				<hr>
                  <strong><i class="far fa-file-alt mr-1"></i> Status Perkawinan</strong>

                <p class="text-muted"><?php echo "$dataPegawai->status_nikah"; ?></p>
				
				<hr>

                
                <strong><i class="far fa-file-alt mr-1"></i> Status Anak</strong>

                <p class="text-muted"><?php echo $dataPegawai->s_kawin; ?></p>
				
				<hr>

                <strong><i class="far fa-file-alt mr-1"></i> Tinggi Badan</strong>

                <p class="text-muted"><?php echo "$dataPegawai->tinggi"; ?></p>
				<hr>

                <strong><i class="far fa-file-alt mr-1"></i> Berat Badan</strong>

                <p class="text-muted"><?php echo "$dataPegawai->berat"; ?></p>
				<hr>

                <strong><i class="far fa-file-alt mr-1"></i> Golongan Darah</strong>

                <p class="text-muted"><?php echo "$dataPegawai->darah"; ?></p>
				 
				<hr>

                <strong><i class="far fa-file-alt mr-1"></i> No KTP</strong>

                <p class="text-muted"><?php echo "$dataPegawai->no_ktp"; ?></p>
				 
				<hr>

                <strong><i class="far fa-file-alt mr-1"></i> No NPWP</strong>

                <p class="text-muted"><?php echo "$dataPegawai->no_ktp"; ?></p>
				 
				<hr>

                <strong><i class="far fa-file-alt mr-1"></i> No Asuransi</strong>

                <p class="text-muted"><?php echo "$dataPegawai->jamsostek"; ?></p>
				  
				<hr>
              </div>
              </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div><?php } ?>
    </section>
			
                                <div class="modal-footer justify-content-between">
                                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Tutup</button>
                                    <button class="btn btn-primary" type="button"  onclick="printDiv('printableArea')"><span class="fa fa-print"></span> Cetak</button>
                                </div>
        </div>
    </div>
</div>
      </div>
  
  </table>
</div>
<script type="text/javascript">
    function printDiv(printableArea) {
        var printContents = document.getElementById(printableArea).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>