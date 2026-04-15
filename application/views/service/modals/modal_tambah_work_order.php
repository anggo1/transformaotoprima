<section class="content">
    <div class="row">
        <div class="modal-content">
            <div class="modal-header text-blue">

                <h5 style="display:block; text-align:center;"><span
                        class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Work Order</h5>

            </div>
            <?php
			if (!empty($dataCus)) {
			foreach ($dataCus as $c)
                foreach ($dataSa as $s)  {{}}} ?>
            <div class="card-body">
                <table width="100%" cellpadding="1" cellspacing="0" class="data1"
                    style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
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

                <button type="button" class="btn btn-success" id="tambah" onclick="showOperationForm()"
                    title="Add Data"><i class="fas fa-plus"></i> Add data</button>
            </div>
            <div class="card-body" id="operation-body" hidden="true">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Type Of Work</label>
                            <div class="col-sm-12">
                                <input type="text" name="type_of_work" id="type_of_work" value="" class="form-control"
                                    placeholder="Type Of Work">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Hours</label>
                            <div class="col-sm-12">
                                <input type="text" name="hours" id="hours" value="" class="form-control"
                                    placeholder="Hours">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer right-content-between">
                    <button class="btn btn-info" id="simpan-operation" onclick="insertOperation()" type="button"><span
                            class="fa fa-save"></span>
                        Save</button>
                </div>
            </div>
            <div class="card-body">
                <div id="data-detail-wo"></div>
            </div>

            <form id="form-work-order" name="form-work-order" method="POST">
                <div class="card-body">
                <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $s->wo_no; ?>" class="form-control"
                        placeholder="Operation">

                    <input type="hidden" name="pembuat" id="pembuat"
                        value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                    <div class="modal-footer right-content-between">
                        <button class="btn btn-primary" id="simpan" type="submit"><span class="fa fa-save"></span>
                            Save Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</section><!-- /.modal-content -->
<script type="text/javascript">

</script>