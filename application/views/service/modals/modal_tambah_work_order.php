<?php
			if (!empty($dataCus)) {
			foreach ($dataCus as $c)
                foreach ($dataSa as $s)  {{}}} ?>
<div class="card-body">
    <div class="row">
        <div class="col-lg-8">
            <div class="card-header card-dark card-outline">
                <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i> &nbsp;
                    Work Order</h3>
                <div class="text-right">
                    <button type="button" class="btn btn-sm btn-dark" onclick="insertNote()"><i class="fas fa-plus"></i>
                        Standart Keterangan</button>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#tambah-keterangan" title="Add Data"><i class="fas fa-plus"></i>
                        Add</button>
                </div>
            </div>
            <table width="100%" cellpadding="1" cellspacing="0" class="data1" style="border-bottom:1px solid #8d8989;">
                <thead>
                <tbody>
                    <tr>
                        <th width="15%">Customer name</th>
                        <th width="1%">: </th>
                        <th width="20%"><?php echo $c->nama_cus; ?></th>
                        <th width="20%">&nbsp;</th>
                        <th colspan="3">PT Transforma Oto Prima</th>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>&nbsp;</th>
                        <th><?php echo $c->alamat; ?></th>
                        <th>&nbsp;</th>
                        <th colspan="3">Authorized Dealer of Mercedes-Benz Truck &amp; Bus in Indonesia</th>
                    </tr>
                    <tr>
                        <th>City</th>
                        <th>&nbsp;</th>
                        <th><?php echo $c->kota; ?></th>
                        <th>&nbsp;</th>
                        <th colspan="3">&nbsp;</th>
                    </tr>
                    <tr>
                        <th>Telp</th>
                        <th>&nbsp;</th>
                        <th><?php echo $c->no_tlp; ?></th>
                        <th>&nbsp;</th>
                        <th width="20%">Order No</th>
                        <th width="11">:</th>
                        <th width="25%"><?php echo $s->wo_no; ?></th>
                    </tr>
                    <tr>
                        <th>Tax Code</th>
                        <th>:</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>Date</th>
                        <th>:</th>
                        <th><?php echo tglIndoSedang($s->date_open_wo); ?></th>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                </tbody>
            </table>

            <div id="operation-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Operation</label>
                            <div class="col-sm-12">
                                <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $s->wo_no; ?>"
                                    class="form-control" placeholder="Operation">
                                <input type="text" name="operation" id="operation" value="" class="form-control"
                                    placeholder="Operation">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Hours</label>
                            <div class="col-sm-12 input-group">
                                <input type="text" name="hours" id="hours" value="" class="form-control"
                                    placeholder="Hours">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Type Of Work</label>
                            <div class="col-sm-12 input-group">
                                <input type="text" name="type_of_work" id="type_of_work" value="" class="form-control"
                                    placeholder="Type Of Work">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-warning btn-flat" data-toggle="modal"
                                        data-target="#modal-operation">Cari!</button>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer right-content-between">
                    <button class="btn btn-info" id="simpan-operation" onclick="insertOperation()" type="button"><span
                            class="fa fa-save"></span>
                        Save Detail</button>
                </div>
            </div>
            <div id="data-detail-wo"></div>

            <form id="form-work-order" name="form-work-order" method="POST">
                <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $s->wo_no; ?>" class="form-control"
                    placeholder="Operation">

                <input type="hidden" name="pembuat" id="pembuat"
                    value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                <div class="modal-footer right-content-between">
                    <button class="btn btn-primary" id="simpan" type="submit"><span class="fa fa-save"></span>
                        Save All Data</button>
                </div>
            </form>
        </div>


        <div id="data-proses-mechanic"></div>
    </div>
</div>


<div class="modal fade" id="modal-operation">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-gray-light">
            <div class="modal-header">
                <h4 class="modal-title">Xentry Operation Time</h4>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- <table class="table table-head-fixed text-nowrap" id="table-kons">-->
                    <table class="table table-bordered table-hover dt-responsive nowrap" id="tabel-operation">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code</th>
                                <th>Hours</th>
                                <th>Type of Work</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
<script type="text/javascript">
</script>