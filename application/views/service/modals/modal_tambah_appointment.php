<section class="content">
    <div class="row">
        <div class="modal-content">
            <div class="modal-header text-blue">

                <h5 style="display:block; text-align:center;"><span
                        class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Service Appoinment</h5>
                <button type="button" class="btn btn-success" id="tambah" hidden="hidden"
                    onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> PO
                    BARU</button>
            </div>
            <div class="modal-body">
                <form id="form-tambah-appointment" name="form-tambah-appointment" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-4">
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                <input type="text" name="date_open_wo" id="date_open_wo" value=""
                                    class="form-control date_open_wo datetimepicker" data-toggle="datetimepicker"
                                    data-target=".date_open_wo" data-format="yyy-mm-dd" required>

                                <div class="input-group-append" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="col-sm-2 col-form-label">Clock In</label>
                        <div class="col-sm-4">
                            <div class="input-group date" id="timepicker" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" name="clockin" id="clockin"
                                    value="" data-toggle="datetimepicker" data-target="#timepicker"
                                    data-format="HH:mm" />
                            </div>
                        </div>
                    </div>

                    <div class="row form-group row">
                        <label class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-4">
                            <select name="customer" id="customer" class="form-control">
                                <option value="">Customer...
                                </option>
                                <?php
											if (!empty($dataCus)) {
												foreach ($dataCus as $sp) {   ?>
                                <option value="<?php echo $sp->kode_cus . '|' . $sp->nama_cus; ?>">
                                    <?php echo $sp->nama_cus; ?>
                                </option>
                                <?php
												}
											}
											?>
                            </select>
                        </div>
                        <label class="col-sm-2 col-form-label">Complain</label>
                        <div class="col-sm-4">
                            <input type="text" name="customer_complain" id="customer_complain" value=""
                                class="form-control" placeholder="Customer Complain">
                        </div>
                    </div>
                    <div class="row form-group row">
                        <label class="col-sm-2 col-form-label">VIN</label>
                        <div class="col-sm-4">
                            <input type="text" name="vin" id="vin" value="" class="form-control" placeholder="VIN">
                        </div>
                        <label class="col-sm-2 col-form-label">Engine No</label>
                        <div class="col-sm-4">
                            <input type="text" name="engine_no" id="engine_no" value="" class="form-control"
                                placeholder="Engine No">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Last Service Date</label>
                        <div class="col-sm-4">
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                <input type="text" name="last_service_date" id="last_service_date" value=""
                                    class="form-control last_service_date datetimepicker" data-toggle="datetimepicker"
                                    data-target=".last_service_date" data-format="yyy-mm-dd" required>

                                <div class="input-group-append" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="col-sm-2 col-form-label">Dead Line</label>
                        <div class="col-sm-4">
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                <input type="text" name="dead_line" id="dead_line" value=""
                                    class="form-control dead_line datetimepicker" data-toggle="datetimepicker"
                                    data-target=".dead_line" data-format="yyy-mm-dd" required>

                                <div class="input-group-append" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="row form-group row">
                <label class="col-sm-2 col-form-label">Mileage</label>
                <div class="col-sm-4">
                    <input type="text" name="mileage" id="mileage" value="" class="form-control" placeholder="Mileage">
                </div>
                <label class="col-sm-2 col-form-label">Licence Plate</label>
                <div class="col-sm-4">
                    <input type="text" name="licence_plate" id="licence_plate" value="" class="form-control"
                        placeholder="Licence Plate">
                </div>
            </div>
            <div class="row form-group row">
                <label class="col-sm-2 col-form-label">Vehicle Type</label>
                <div class="col-sm-4">
                    <input type="text" name="vehicle_type" id="vehicle_type" value="" class="form-control"
                        placeholder="Vehicle Type">
                </div>
                <label class="col-sm-2 col-form-label">Storing</label>
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="customRadio1" value="Y" name="storing">
                    <label for="customRadio1" class="custom-control-label">Ya</label>
                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="customRadio2" value="N" name="storing" checked>
                    <label for="customRadio2" class="custom-control-label">Tidak</label>
                </div>
            </div>

            <div class="row form-group row">
                <label class="col-sm-2 col-form-label">Remarks</label>
                <div class="col-sm-4">
                    <input type="text" name="remark" id="remark" value="" class="form-control" placeholder="Komentar">
                </div>
                <label class="col-sm-2 col-form-label">Service Advisor</label>
                <div class="col-sm-4">
                    <input type="text" name="sa_name" id="sa_name" value="" class="form-control"
                        placeholder="Service Advisor">
                </div>
            </div>
            <input type="hidden" name="pembuat" id="pembuat"
                value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
            <div class="modal-footer right-content-between">
                <button class="btn btn-primary" id="simpan" type="submit"><span class="fa fa-save"></span>
                    Simpan Data</button>
            </div>
            </form>
        </div>
    </div>

</section><!-- /.modal-content -->
<script type="text/javascript">

</script>