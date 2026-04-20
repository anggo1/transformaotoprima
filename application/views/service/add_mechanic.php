<?php

                  foreach ($dataMechanic as $m) {}
                  ?>

<div class="col-lg-12">
            <div class="card">
                    <div class="card-header card-dark card-outline">
                        <h3 class="card-title" id="card-title" title="Operation" text><i class="ion-outlet ion-lg text-blue"></i> &nbsp;
                            Data Mekanik <?php echo "<span style='color:green'>".$m->no_work_order."</span>"  ?></h3>
                    </div>
                    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">NIK</label>
                            <div class="col-sm-12">
                                <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $m->wo_no; ?>"
                                    class="form-control" placeholder="Operation">
                                <input type="hidden" name="no_work_order" id="no_work_order" value="<?php echo $m->no_work_order; ?>"
                                    class="form-control" placeholder="Operation">
                                <input type="text" name="nik" id="nik" value="" class="form-control" placeholder="Nomor Induk Karyawan">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Nama</label>
                            <div class="col-sm-12 input-group">
                                <input type="text" name="nama" id="nama" value="" class="form-control"
                                    placeholder="Nama Karyawan">

                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer right-content-between">
                    <button class="btn btn-info" id="simpan-mechanic" onclick="insertMechanic()" type="button"><span
                            class="fa fa-save"></span>
                        Save</button>
                </div>

                    <div class="col-12">
                <div id="data-daftar-mechanic"></div>
                    </div>
            </div>
        </div>