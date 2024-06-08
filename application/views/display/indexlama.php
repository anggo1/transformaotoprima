<?php
$kini = new DateTime("now");

foreach ($bay1 as $by1){}
foreach ($bay2 as $by2){}
foreach ($bay3 as $by3){}
foreach ($bay4 as $by4){}
foreach ($bay5 as $by5){}
foreach ($bay6 as $by6){}
foreach ($bay7 as $by7){}
foreach ($bay8 as $by8){}
foreach ($bay9 as $by9){}
foreach ($bay10 as $by10){}
foreach ($bay11 as $by11){}
foreach ($bay12 as $by12){}
foreach ($bay13 as $by13){}
foreach ($bay14 as $by14){}
foreach ($bay15 as $by15){}
foreach ($bay16 as $by16){}
foreach ($bay17 as $by17){}
foreach ($bay18 as $by18){}
foreach ($bay19 as $by19){}
foreach ($bay20 as $by20){}
foreach ($bay21 as $by21){}
foreach ($bay22 as $by22){}
foreach ($bay23 as $by23){}
foreach ($bay24 as $by24){}
foreach ($bay25 as $by25){}
foreach ($bay26 as $by26){}
foreach ($bay27 as $by27){}
foreach ($bay28 as $by28){}
foreach ($bay29 as $by29){}
foreach ($bay30 as $by30){}
foreach ($bay31 as $by31){}
?>
<script type="text/JavaScript">

var limit="0:30"

if (document.images){
var parselimit=limit.split(":")
parselimit=parselimit[0]*60+parselimit[1]*4
}
function beginrefresh(){
if (!document.images)
return
if (parselimit==1)
window.location.reload()
else{ 
parselimit-=1
curmin=Math.floor(parselimit/60)
cursec=parselimit%60
if (curmin!=0)
curtime=curmin+" minutes and "+cursec+" seconds left until page refresh!"
else
curtime=cursec+" seconds left until page refresh!"
window.status=curtime
setTimeout("beginrefresh()",2000)
}
}

window.onload=beginrefresh
</script><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>MPU DISPLAY</title>
    <link
        href="application/views/display/css/global.css"
        rel="stylesheet"
        type="text/css"/>
    <script src="application/views/display/js/jquery.min.js"></script>
    <link
        rel="stylesheet"
        href="application/views/display/css/style1.css"
        type="text/css">
   <!--  <link href="css/global.css" rel="stylesheet" type="text/css"/> <script
    src="js/jquery.min.js"></script> <link rel="stylesheet" href="css/style1.css"
    type="text/css">-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<div class="header" align="center">
    <strong>DENAH LOKASI BODY REPAIR</strong>
</div>
<div>
    <strong></strong>
    <div class="kategoritengah">
        <div class="jarak_mobil"></div>
        <div class="judul">
            <strong>B O D Y</strong>
        </div>
        <div class="jarak_mobil4"></div>
        <div class="jarak_mobil4"></div>
        <div class="judul">
            <strong>DEMPUL CAT</strong>
        </div>
    </div>

    <div class="denah">
        <div class="kategoritengah">
        <div class="kategoriatas">
          <div class="jarak_kiri"></div>
          <div class="bis2">
              <div class="isikategori2">
                <div class="nomor">1</div>
                <?php if(!empty($by1->id_lapor)){ ?>
                <button <?php if($by1->status=='P'){echo 'class="tombolbus_pause check"';}if($by1->status=='Y'){echo 'class="tombolbus check"';}  ?> data-pk="<?php echo $by1->id_lapor; ?>"
                id-bay="1" id-lapor="<?php echo $by1->id_lapor; ?>">
                  <p><?php echo $by1->no_body; ?></p>
                  PK : <?php echo $by1->jml_pk; ?><br>
                  PK A : <?php echo $by1->aktif; ?><br>
                  PK P: <?php echo $by1->pause; ?><br>
                  PK S: <?php echo $by1->selesai; ?>
                  <p>
                    <?php $tgl1 = new DateTime($by1->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                  </p>
                </button>
                <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
              </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
              <div class="isikategori2">
                <div class="nomor">2</div>
                <?php if(!empty($by2->id_lapor)){ ?>
                <button <?php if($by2->status=='P'){echo 'class="tombolbus_pause check"';}if($by2->status=='Y'){ echo 'class="tombolbus check"';}  ?> id-bay="2" id-lapor="<?php echo $by2->id_lapor; ?>">
                  <p><?php echo $by2->no_body; ?></p>
                  PK : <?php echo $by2->jml_pk; ?><br>
                  PK A : <?php echo $by2->aktif; ?><br>
                  PK P: <?php echo $by2->pause; ?><br>
                  PK S: <?php echo $by2->selesai; ?>
                  <p>
                    <?php $tgl1 = new DateTime($by2->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                  </p>
                </button>
                <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
              </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">3</div>
              <?php if(!empty($by3->id_lapor)){ ?>
              <button <?php if($by3->status=='P'){echo 'class="tombolbus_pause check"';}if($by3->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="3" id-lapor="<?php echo $by3->id_lapor; ?>">
                <p><?php echo $by3->no_body; ?></p>
                PK : <?php echo $by3->jml_pk; ?><br>
                PK A : <?php echo $by3->aktif; ?><br>
                PK P: <?php echo $by3->pause; ?><br>
                PK S: <?php echo $by3->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by3->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">4</div>
              <?php if(!empty($by4->id_lapor)){ ?>
              <button <?php if($by4->status=='P'){echo 'class="tombolbus_pause check"';}if($by4->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="4" id-lapor="<?php echo $by4->id_lapor; ?>">
                <p><?php echo $by4->no_body; ?></p>
                PK : <?php echo $by4->jml_pk; ?><br>
                PK A : <?php echo $by4->aktif; ?><br>
                PK P: <?php echo $by4->pause; ?><br>
                PK S: <?php echo $by4->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by4->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_mobil"></div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">5</div>
              <?php if(!empty($by5->id_lapor)){ ?>
              <button <?php if($by5->status=='P'){echo 'class="tombolbus_pause check"';}if($by5->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="5" id-lapor="<?php echo $by5->id_lapor; ?>">
                <p><?php echo $by5->no_body; ?></p>
                PK : <?php echo $by5->jml_pk; ?><br>
                PK A : <?php echo $by5->aktif; ?><br>
                PK P: <?php echo $by5->pause; ?><br>
                PK S: <?php echo $by5->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by5->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">6</div>
              <?php if(!empty($by6->id_lapor)){ ?>
              <button <?php if($by6->status=='P'){echo 'class="tombolbus_pause check"';}if($by6->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="6" id-lapor="<?php echo $by6->id_lapor; ?>">
                <p><?php echo $by6->no_body; ?></p>
                PK : <?php echo $by6->jml_pk; ?><br>
                PK A : <?php echo $by6->aktif; ?><br>
                PK P: <?php echo $by6->pause; ?><br>
                PK S: <?php echo $by6->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by6->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">7</div>
              <?php if(!empty($by7->id_lapor)){ ?>
              <button <?php if($by7->status=='P'){echo 'class="tombolbus_pause check"';}if($by7->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="7" id-lapor="<?php echo $by7->id_lapor; ?>">
                <p><?php echo $by7->no_body; ?></p>
                PK : <?php echo $by7->jml_pk; ?><br>
                PK A : <?php echo $by7->aktif; ?><br>
                PK P: <?php echo $by7->pause; ?><br>
                PK S: <?php echo $by7->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by7->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">8</div>
              <?php if(!empty($by8->id_lapor)){ ?>
              <button <?php if($by8->status=='P'){echo 'class="tombolbus_pause check"';}if($by8->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="8" id-lapor="<?php echo $by8->id_lapor; ?>">
                <p><?php echo $by8->no_body; ?></p>
                PK : <?php echo $by8->jml_pk; ?><br>
                PK A : <?php echo $by8->aktif; ?><br>
                PK P: <?php echo $by8->pause; ?><br>
                PK S: <?php echo $by8->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by8->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">9</div>
              <?php if(!empty($by9->id_lapor)){ ?>
              <button <?php if($by9->status=='P'){echo 'class="tombolbus_pause check"';}if($by9->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="9" id-lapor="<?php echo $by9->id_lapor; ?>">
                <p><?php echo $by9->no_body; ?></p>
                PK : <?php echo $by9->jml_pk; ?><br>
                PK A : <?php echo $by9->aktif; ?><br>
                PK P: <?php echo $by9->pause; ?><br>
                PK S: <?php echo $by9->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by9->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">10</div>
              <?php if(!empty($by10->id_lapor)){ ?>
              <button <?php if($by10->status=='P'){echo 'class="tombolbus_pause check"';}if($by10->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="10" id-lapor="<?php echo $by10->id_lapor; ?>">
                <p><?php echo $by10->no_body; ?></p>
                PK : <?php echo $by10->jml_pk; ?><br>
                PK A : <?php echo $by10->aktif; ?><br>
                PK P: <?php echo $by10->pause; ?><br>
                PK S: <?php echo $by10->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by10->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
		  </div>			
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">11</div>
              <?php if(!empty($by11->id_lapor)){ ?>
              <button <?php if($by11->status=='P'){echo 'class="tombolbus_pause check"';}if($by11->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="11" id-lapor="<?php echo $by11->id_lapor; ?>">
                <p><?php echo $by11->no_body; ?></p>
                PK : <?php echo $by11->jml_pk; ?><br>
                PK A : <?php echo $by11->aktif; ?><br>
                PK P: <?php echo $by11->pause; ?><br>
                PK S: <?php echo $by11->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by11->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
		  </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">12</div>
              <?php if(!empty($by12->id_lapor)){ ?>
              <button <?php if($by12->status=='P'){echo 'class="tombolbus_pause check"';}if($by12->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="12" id-lapor="<?php echo $by12->id_lapor; ?>">
                <p><?php echo $by12->no_body; ?></p>
                PK : <?php echo $by12->jml_pk; ?><br>
                PK A : <?php echo $by12->aktif; ?><br>
                PK P: <?php echo $by12->pause; ?><br>
                PK S: <?php echo $by12->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by12->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">13</div>
              <?php if(!empty($by13->id_lapor)){ ?>
              <button <?php if($by13->status=='P'){echo 'class="tombolbus_pause check"';}if($by13->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="13" id-lapor="<?php echo $by13->id_lapor; ?>">
                <p><?php echo $by13->no_body; ?></p>
                PK : <?php echo $by13->jml_pk; ?><br>
                PK A : <?php echo $by13->aktif; ?><br>
                PK P: <?php echo $by13->pause; ?><br>
                PK S: <?php echo $by13->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by13->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
            <div class="isikategori2">
              <div class="nomor">14</div>
              <?php if(!empty($by14->id_lapor)){ ?>
              <button <?php if($by14->status=='P'){echo 'class="tombolbus_pause check"';}if($by14->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="14" id-lapor="<?php echo $by14->id_lapor; ?>">
                <p><?php echo $by14->no_body; ?></p>
                PK : <?php echo $by14->jml_pk; ?><br>
                PK A : <?php echo $by14->aktif; ?><br>
                PK P: <?php echo $by14->pause; ?><br>
                PK S: <?php echo $by14->selesai; ?>
                <p>
                  <?php $tgl1 = new DateTime($by14->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?>
                </p>
              </button>
              <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
            </div>
          </div>
			<div class="jarak_bus"></div>
            <div class="bis2">
                    <div class="isikategori2">
                        <div class="nomor">15</div>
                        <?php if(!empty($by15->id_lapor)){ ?>
                        <button <?php if($by15->status=='P'){echo 'class="tombolbus_pause check"';}if($by15->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="15" id-lapor="<?php echo $by15->id_lapor; ?>">
                            <p><?php echo $by15->no_body; ?></p>
                            PK :
                            <?php echo $by15->jml_pk; ?><br>
                            PK A :
                            <?php echo $by15->aktif; ?><br>
                            PK P:
                            <?php echo $by15->pause; ?><br>
                            PK S:
                            <?php echo $by15->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by15->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="bis2">
                    <div class="isikategori2">
                        <div class="nomor">16</div>
                        <?php if(!empty($by16->id_lapor)){ ?>
                        <button <?php if($by16->status=='P'){echo 'class="tombolbus_pause check"';}if($by16->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="16" id-lapor="<?php echo $by16->id_lapor; ?>">
                            <p><?php echo $by16->no_body; ?></p>
                            PK :
                            <?php echo $by16->jml_pk; ?><br>
                            PK A :
                            <?php echo $by16->aktif; ?><br>
                            PK P:
                            <?php echo $by16->pause; ?><br>
                            PK S:
                            <?php echo $by16->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by16->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
          </div>
          </div>
          <div class="jarak_bus"></div>
          <div class="jarak_bus"></div>
          <div class="jarak_bus"></div>
        </div>
        </div>
        <div class="denah">
            <div class="kategoritengah">
                <div class="bis5">
                    <div class="jarak_mobil2"></div>
                    <div class="judul">
                        <strong>T R I M I N G</strong>
                    </div>

                    <div class="jarak_mobil3"></div>
                    <div class="jarak_mobil"></div>
                    <div class="judulAuto">
                        <strong>ELEKTRIK</strong>
                    </div>
                    <div class="jarak_mobil3"></div>
                    <div class="judulAuto">
                        <strong>Q/C</strong>
                    </div>
                    <div class="jarak_mobil3"></div>
                    <div class="judulAuto">
                        <strong>JOK</strong>
                    </div>
                    <div class="jarak_mobil2"></div>
                    <div class="judul">
                        <strong>P.HARIAN</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="denah">
            <div class="kategoriatas">
                <div class="jarak_kiri2"></div>
				<div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">17</div>
                        <?php if(!empty($by17->id_lapor)){ ?>
                        <button <?php if($by17->status=='P'){echo 'class="tombolbus_pause check"';}if($by17->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="17" id-lapor="<?php echo $by17->id_lapor; ?>">
                            <p><?php echo $by17->no_body; ?></p>
                            PK :
                            <?php echo $by17->jml_pk; ?><br>
                            PK A :
                            <?php echo $by17->aktif; ?><br>
                            PK P:
                            <?php echo $by17->pause; ?><br>
                            PK S:
                            <?php echo $by17->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by17->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">18</div>
                        <?php if(!empty($by18->id_lapor)){ ?>
                        <button <?php if($by18->status=='P'){echo 'class="tombolbus_pause check"';}if($by18->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="18" id-lapor="<?php echo $by18->id_lapor; ?>">
                            <p><?php echo $by18->no_body; ?></p>
                            PK :
                            <?php echo $by18->jml_pk; ?><br>
                            PK A :
                            <?php echo $by18->aktif; ?><br>
                            PK P:
                            <?php echo $by18->pause; ?><br>
                            PK S:
                            <?php echo $by18->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by18->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">19</div>
                        <?php if(!empty($by19->id_lapor)){ ?>
                        <button <?php if($by19->status=='P'){echo 'class="tombolbus_pause check"';}if($by19->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="19" id-lapor="<?php echo $by19->id_lapor; ?>">
                            <p><?php echo $by19->no_body; ?></p>
                            PK :
                            <?php echo $by19->jml_pk; ?><br>
                            PK A :
                            <?php echo $by19->aktif; ?><br>
                            PK P:
                            <?php echo $by19->pause; ?><br>
                            PK S:
                            <?php echo $by19->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by19->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">20</div>
                        <?php if(!empty($by20->id_lapor)){ ?>
                        <button <?php if($by20->status=='P'){echo 'class="tombolbus_pause check"';}if($by20->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="20" id-lapor="<?php echo $by20->id_lapor; ?>">
                            <p><?php echo $by20->no_body; ?></p>
                            PK :
                            <?php echo $by20->jml_pk; ?><br>
                            PK A :
                            <?php echo $by20->aktif; ?><br>
                            PK P:
                            <?php echo $by20->pause; ?><br>
                            PK S:
                            <?php echo $by20->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by20->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_mobil"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">21</div>
                        <?php if(!empty($by21->id_lapor)){ ?>
                        <button <?php if($by21->status=='P'){echo 'class="tombolbus_pause check"';}if($by21->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="21" id-lapor="<?php echo $by21->id_lapor; ?>">
                            <p><?php echo $by21->no_body; ?></p>
                            PK :
                            <?php echo $by21->jml_pk; ?><br>
                            PK A :
                            <?php echo $by21->aktif; ?><br>
                            PK P:
                            <?php echo $by21->pause; ?><br>
                            PK S:
                            <?php echo $by21->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by21->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">22</div>
                        <?php if(!empty($by22->id_lapor)){ ?>
                        <button <?php if($by22->status=='P'){echo 'class="tombolbus_pause check"';}if($by22->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="22" id-lapor="<?php echo $by22->id_lapor; ?>">
                            <p><?php echo $by22->no_body; ?></p>
                            PK :
                            <?php echo $by22->jml_pk; ?><br>
                            PK A :
                            <?php echo $by22->aktif; ?><br>
                            PK P:
                            <?php echo $by22->pause; ?><br>
                            PK S:
                            <?php echo $by22->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by22->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">23</div>
                        <?php if(!empty($by23->id_lapor)){ ?>
                        <button <?php if($by23->status=='P'){echo 'class="tombolbus_pause check"';}if($by23->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="23" id-lapor="<?php echo $by23->id_lapor; ?>">
                            <p><?php echo $by23->no_body; ?></p>
                            PK :
                            <?php echo $by23->jml_pk; ?><br>
                            PK A :
                            <?php echo $by23->aktif; ?><br>
                            PK P:
                            <?php echo $by23->pause; ?><br>
                            PK S:
                            <?php echo $by23->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by23->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_mobil"></div>

                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">24</div>
                        <?php if(!empty($by24->id_lapor)){ ?>
                        <button <?php if($by24->status=='P'){echo 'class="tombolbus_pause check"';}if($by24->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="24" id-lapor="<?php echo $by24->id_lapor; ?>">
                            <p><?php echo $by24->no_body; ?></p>
                            PK :
                            <?php echo $by24->jml_pk; ?><br>
                            PK A :
                            <?php echo $by24->aktif; ?><br>
                            PK P:
                            <?php echo $by24->pause; ?><br>
                            PK S:
                            <?php echo $by24->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by24->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">25</div>
                        <?php if(!empty($by25->id_lapor)){ ?>
                        <button <?php if($by25->status=='P'){echo 'class="tombolbus_pause check"';}if($by25->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="25" id-lapor="<?php echo $by25->id_lapor; ?>">
                            <p><?php echo $by25->no_body; ?></p>
                            PK :
                            <?php echo $by25->jml_pk; ?><br>
                            PK A :
                            <?php echo $by25->aktif; ?><br>
                            PK P:
                            <?php echo $by25->pause; ?><br>
                            PK S:
                            <?php echo $by25->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by25->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_mobil"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">26</div>
                        <?php if(!empty($by26->id_lapor)){ ?>
                        <button <?php if($by26->status=='P'){echo 'class="tombolbus_pause check"';}if($by26->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="26" id-lapor="<?php echo $by26->id_lapor; ?>">
                            <p><?php echo $by26->no_body; ?></p>
                            PK :
                            <?php echo $by26->jml_pk; ?><br>
                            PK A :
                            <?php echo $by26->aktif; ?><br>
                            PK P:
                            <?php echo $by26->pause; ?><br>
                            PK S:
                            <?php echo $by26->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by26->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                    <strong></strong>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">27</div>
                        <?php if(!empty($by27->id_lapor)){ ?>
                        <button <?php if($by27->status=='P'){echo 'class="tombolbus_pause check"';}if($by27->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="27" id-lapor="<?php echo $by27->id_lapor; ?>">
                            <p><?php echo $by27->no_body; ?></p>
                            PK :
                            <?php echo $by27->jml_pk; ?><br>
                            PK A :
                            <?php echo $by27->aktif; ?><br>
                            PK P:
                            <?php echo $by27->pause; ?><br>
                            PK S:
                            <?php echo $by27->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by27->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_mobil"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">28</div>
                        <?php if(!empty($by28->id_lapor)){ ?>
                        <button <?php if($by28->status=='P'){echo 'class="tombolbus_pause check"';}if($by28->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="28" id-lapor="<?php echo $by28->id_lapor; ?>">
                            <p><?php echo $by28->no_body; ?></p>
                            PK :
                            <?php echo $by28->jml_pk; ?><br>
                            PK A :
                            <?php echo $by28->aktif; ?><br>
                            PK P:
                            <?php echo $by28->pause; ?><br>
                            PK S:
                            <?php echo $by28->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by28->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">29</div>
                        <?php if(!empty($by29->id_lapor)){ ?>
                        <button <?php if($by29->status=='P'){echo 'class="tombolbus_pause check"';}if($by29->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="29" id-lapor="<?php echo $by29->id_lapor; ?>">
                            <p><?php echo $by29->no_body; ?></p>
                            PK :
                            <?php echo $by29->jml_pk; ?><br>
                            PK A :
                            <?php echo $by29->aktif; ?><br>
                            PK P:
                            <?php echo $by29->pause; ?><br>
                            PK S:
                            <?php echo $by29->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by29->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                <div class="bis2">
                <div class="isikategori2">
                        <div class="nomor">30</div>
                        <?php if(!empty($by30->id_lapor)){ ?>
                        <button <?php if($by30->status=='P'){echo 'class="tombolbus_pause check"';}if($by30->status=='Y'){echo 'class="tombolbus check"';}  ?> id-bay="30" id-lapor="<?php echo $by30->id_lapor; ?>">
                            <p><?php echo $by30->no_body; ?></p>
                            PK :
                            <?php echo $by30->jml_pk; ?><br>
                            PK A :
                            <?php echo $by30->aktif; ?><br>
                            PK P:
                            <?php echo $by30->pause; ?><br>
                            PK S:
                            <?php echo $by30->selesai; ?>
                            <p><?php $tgl1 = new DateTime($by30->tgl_masuk);$jarak = $kini->diff($tgl1);echo 'BM : '. $jarak->d. ' Hari'?></p>
                        </button>
                    <?php } else { echo '<div class="empty">EMPTY</div>';} ?>
                    </div>
                </div>
                <div class="jarak_bus"></div>
                
            
            </div>

        </div>
    </div>
</div>
<div class="bawah_01">
    <table width="50%" align="center" border="0">
        <tbody>
            <tr>
                <th width="5%" bgcolor="#37BF07">&nbsp;</th>
                <th width="23%" style="font-size:12px; text-align:left;">
                    : Bay Kosong</th>
                <th width="5%" bgcolor="#E6F408" style="font-size:12px; text-align:left;">&nbsp;</th>
                <th width="22%" style="font-size:12px; text-align:left;">
                    <span style="font-size:12px;text-align:left;">: Bay Pause</span></th>
                <th width="5%" bgcolor="#FFFFFF" style="font-size:12px; text-align:left;">&nbsp;</th>
                <th width="17%" style="font-size:12px; text-align:left;">
                    <span style="font-size:12px;text-align:left;">: Bay Aktif</span></th>
                <th width="23%" style="font-size:12px; text-align:left;">BM<span style="font-size:12px;text-align:left;">: Bis Masuk</span></th>
            </tr>
        </tbody>
    </table>

</div>

<div class="modal fade" id="data-bay" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Detail</h4>
        </div>
        <div class="modal-body">
			<?php if(!empty($dataPk)){
	foreach ($dataPk as $d){ echo'test';  }
}
			?>
           <p id="modal_body"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<div id="page1">
    <ul id="ticker_02" class="ticker">
        <div align="center">
            <table width="100%">
                <td width="100%" class="keterangan_lanjutan">
                    <strong>DAFTAR ANTRIAN BUS</strong>
                </td>
            </table>
        </div>
        <div align="left">
            <table width="100%">
                <td width="3%" class="keterangan_judul">No.</td>
                <td width="25%%" class="keterangan_judul">TglMasuk</td>
                <td width="20%%" class="keterangan_judul">NoBody</td>
                <td width="20%" class="keterangan_judul">No Pol</td>
                <td width="20%%" class="keterangan_judul">Keterangan</td>
            </table>
        </div>
        <li>
            <?php
            $noUrutberatbanget=0;
            foreach($dataAntri as $antri){
                
            	$noUrutberatbanget++;
				echo "<li>
				<table width=100%>	
				<td width=3% class=isi_slider ><font> $noUrutberatbanget</font></td>	
				<td width=25%% class=isi_slider ><div align=center> ".tglIndoSedang($antri->tgl_bast)."</div> </td>
				<td width=20% class=isi_slider ><div align=center> ".$antri->no_body."</div></td>
				<td width=20% class=isi_slider ><div align=center> ".$antri->no_pol."</div></td>
				<td width=20% class=isi_slider ><div align=center> ".$antri->keterangan."</div></td></font></table></li>";
             }
            ?>
    </ul>
</div>
<div id="page2">
    <ul id="ticker_03" class="ticker">
        <div align="center">
            <table width="100%">
                <td width="100%" class="keterangan_lanjutan">
                    <strong>WIP (WORK IN PROCESS)</strong>
                </td>
            </table>
        </div>
        <div align="left">
            <table width="100%">
                <td width="3%" class="keterangan_judul">No.</td>
                <td width="15%%" class="keterangan_judul">TglMulai</td>
                <td width="20%%" class="keterangan_judul">IdPK</td>
                <td width="20%" class="keterangan_judul">No Body</td>
                <td width="20%%" class="keterangan_judul">PK</td>
                <td width="20%%" class="keterangan_judul">Pemborong</td>
            </table>
        </div>
        <li>
            <?php
              $noUrutberatbanget1=0;
            foreach($listPk as $pk){
                
            	$noUrutberatbanget1++;
				echo "<li>
				<table width=100%>	
				<td width=3% class=isi_slider ><font> $noUrutberatbanget1</font></td>	
				<td width=15%% class=isi_slider ><div align=center> ".tglIndoSedang($pk->tgl_mulai)."</div> </td>
				<td width=20% class=isi_slider ><div align=center> ".$pk->id_pk."</div></td>
				<td width=20% class=isi_slider ><div align=center> ".$pk->no_body."</div></td>
				<td width=20% class=isi_slider ><div align=center> ".$pk->jns_pk."</div></td>
				<td width=20% class=isi_slider ><div align=center> ".$pk->pj_borong."</div></td></font></table></li>";
             }
            ?>
            <?php
						 function selisih($jam_a,$jam_b){
						list ($h,$m,$s)= explode (":",$jam_a);
						$dtAwal= mktime($h,$m,$s,"1","1","1");
						list ($h,$m,$s)= explode (":",$jam_b);
						$dtAkhir= mktime($h,$m,$s,"1","1","1");
						$dtSelisih = $dtAkhir-$dtAwal;
						$totalmenit=$dtSelisih/60;
						$jam=explode(".",$totalmenit/60);
						$sisamenit=($totalmenit/60)-$jam[0];
						$sisamenit2=$sisamenit*60;
						return "$jam[0] ";//jam $sisamenit2 menit
						} ?>
    </ul>
</div>
<div id="page3">
    <ul id="ticker_04" class="ticker">
        <div align="center">
            <table width="100%">
                <td width="100%" class="keterangan_lanjutan">
                    <strong>DAFTAR KENDARAAN SIAP KELUAR</strong>
                </td>
            </table>
        </div>
        <div align="left">
            <table width="100%">
                <td width="3%" class="keterangan_judul">No.</td>
                <td width="15%%" class="keterangan_judul">TglSelesai</td>
                <td width="20%%" class="keterangan_judul">SPK</td>
                <td width="20%" class="keterangan_judul">No Body</td>
                <td width="20%%" class="keterangan_judul">Katergori</td>
            </table>
        </div>
        <li>
            <?php
            date_default_timezone_set('Asia/Jakarta');
            $date = date("Y-m-d");
             $noUrutberatbanget2=0;
            foreach($dataPkselesai as $pk){
              $tgl1 = strtotime($pk->tgl_selesai); 
              $tgl2 = strtotime($date);
              
              $jarak = $tgl2 - $tgl1;
              
              $hari = $jarak / 60 / 60 / 24;
             
              
            	$noUrutberatbanget2++;
				echo "<li>
				<table width=100%>	
				<td width=3% class=isi_slider ><font> $noUrutberatbanget2</font></td>	
				<td width=15%% class=isi_slider ><div align=center> ".tglIndoSedang($pk->tgl_selesai)."</div> </td>
				<td width=20% class=isi_slider ><div align=center> ".$pk->id_lapor."</div></td>
				<td width=20% class=isi_slider ><div align=center> ".$pk->no_body."</div></td>
				<td width=20% class=isi_slider ><div align=center> ".$pk->kategori."</div></td></font></table></li>";
             }
            ?>
    </ul>
</div>
<script type="text/javascript">
  

    function tick() {
        $('#ticker_01 li:first').slideUp(function () {
            $(this)
                .appendTo($('#ticker_01'))
                .slideDown();
        });
    }
    setInterval(function () {
        tick()
    }, 5000);

    function tick2() {
        $('#ticker_02 li:first').slideUp(function () {
            $(this)
                .appendTo($('#ticker_02'))
                .slideDown();
        });
    }
    setInterval(function () {
        tick2()
    }, 2900);

    function tick3() {
        $('#ticker_03 li:first').slideUp(function () {
            $(this)
                .appendTo($('#ticker_03'))
                .slideDown();
        });
    }
    setInterval(function () {
        tick3()
    }, 3000);

    function tick4() {
        $('#ticker_04 li:first').slideUp(function () {
            $(this)
                .appendTo($('#ticker_04'))
                .slideDown();
        });
    }
    setInterval(function () {
        tick4()
    }, 2800);
    function tick5() {
        $('#ticker_05 li:first').slideUp(function () {
            $(this)
                .appendTo($('#ticker_05'))
                .slideDown();
        });
    }
    setInterval(function () {
        tick5()
    }, 3000);
    function tick6() {
        $('#ticker_06 li:first').slideUp(function () {
            $(this)
                .appendTo($('#ticker_06'))
                .slideDown();
        });
    }
    setInterval(function () {
        tick6()
    }, 3000);

    $(document).on("click", ".check1", function () {
        var id = $(this).attr("id-lapor");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Display/detail'); ?>",
                data: "id=" + id
            })
            .done(function (data) {
               // $('#modal-pk').html(data);
                $('#data-bay').modal('show').html(data);
            })
    })
    $(document).on("click", ".check", function () {
        var id = $(this).attr("id-lapor");
        var id1 = $(this).attr("data-pk");
        var id2 = $(this).attr("id-bay");
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Display/detail'); ?>",
                data: "id=" + id
            })
            .done(function (data) {
                $('#data-bay').modal('show');
                $('#modal_body').html(data);
            })
           // var str = "You Have Entered " 
           //     + "id: " + id
           //     + "id1: " + id1 
           //     + "id2: " + id2 ;
           // $("#modal_body").html(str);
    })
</script>