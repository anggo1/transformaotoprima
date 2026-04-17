<section class="content">
    <div class="row">
        <div class="modal-content">
            <div class="modal-header text-blue">

                <h5 style="display:block; text-align:center;"><span
                        class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Service Appoinment</h5>

            </div>
            <?php
			$kd='PRE-';
			$tgl_keluar = date("y-m-d");
			$date = date("ym");
			$ci_kons = get_instance();
			$query = "SELECT max(no_pre_order) AS maxKode FROM tbl_after_sales_pre_order WHERE no_pre_order LIKE '%$date%'";
			$hasil = $ci_kons->db->query($query)->row_array();
			$noOrder = $hasil['maxKode'];
			$noUrut = (int)substr($noOrder, 4, 5);
			$noUrut++;
			$tahun = substr($date, 0, 2);
			$bulan = substr($date, 2, 2);

			$id_keluar  = $tahun.$bulan.sprintf("%04s", $noUrut);
			$kode_keluar  = $kd.$tahun.$bulan.sprintf("%04s", $noUrut);
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
                <input type="hidden" name="no_pre_order" id="no_pre_order" value="<?php echo $kode_keluar; ?>"
                    class="form-control" placeholder="Operation">

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
                </div>

                <div class="modal-footer right-content-between">
                    <button class="btn btn-info" id="simpan-operation" onclick="insertOperation()" type="button"><span
                            class="fa fa-save"></span>
                        Save</button>
                </div>
            </div>
            <div class="card-body">
                <div id="data-detail-pre"></div>
            </div>

            <form id="form-pre-order" name="form-pre-order" method="POST">
                <div class="card-body">

                    <label class="col-sm-2 col-form-label" require>Vehicle Type</label>
                    <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $s->wo_no; ?>" class="form-control"
                        placeholder="Operation">
                    <input type="hidden" name="no_pre_order" id="no_pre_order" value="<?php echo $kode_keluar; ?>"
                        class="form-control" placeholder="Operation">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="vehicleTypeBus" value="BUS"
                            name="vehicle_type" required>
                        <label for="vehicleTypeBus" class="custom-control-label"><span class="fa fa-bus"></span>
                            BUS</label>
                    </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="vehicleTypeTruck" value="TRUCK"
                            name="vehicle_type">
                        <label for="vehicleTypeTruck" class="custom-control-label"><span class="fa fa-truck"></span>
                            TRUCK</label>
                    </div>
                    <table width="100%" cellpadding="1" cellspacing="0" class="data1"
                        style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
                        <thead>
                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="30" colspan="2"
                                    style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
                                    Standard Checking</td>
                                <td width="20%">&nbsp;</td>
                                <td colspan="2"
                                    style="border-left:0px solid #000; border-bottom:2px solid #000;border-right:0px solid #000;">
                                    Valuable Item Missing</td>
                            </tr>
                            <tr>
                                <td width="30%">Lekage</td>
                                <td width="10%">
                                    <input type="hidden" id="leakage" name="leakage" value="N">
                                    <input type="checkbox" id="leakage" name="leakage" value="Y">
                                </td>
                                <td width="20%">&nbsp;</td>
                                <td width="30%">First Air Kit</td>
                                <td><input type="hidden" id="fak" name="fak" value="N">
                                    <input type="checkbox" id="fak" name="fak" value="Y">
                                </td>
                            </tr>
                            <tr>
                                <td>Abnormal Noise</td>
                                <td>
                                    <input type="hidden" id="abnormal_noise" name="abnormal_noise" value="N">
                                    <input type="checkbox" id="abnormal_noise" name="abnormal_noise" value="Y">
                                </td>
                                <td>&nbsp;</td>
                                <td>Spare Kit</td>
                                <td><input type="hidden" id="spare_kit" name="spare_kit" value="N">
                                    <input type="checkbox" id="spare_kit" name="spare_kit" value="Y">
                                </td>
                            </tr>
                            <tr>
                                <td>Error Code/ Indicator</td>
                                <td><input type="hidden" id="error_code" name="error_code" value="N">
                                    <input type="checkbox" id="error_code" name="error_code" value="Y">
                                </td>
                                <td>&nbsp;</td>
                                <td>STNK</td>
                                <td><input type="hidden" id="stnk" name="stnk" value="N">
                                    <input type="checkbox" id="stnk" name="stnk" value="Y">
                                </td>
                            </tr>
                            <tr>
                                <td>Brake,Clutch &amp; Tire 10 Minutes Cyle Check</td>
                                <td><input type="hidden" id="brake" name="brake" value="N">
                                    <input type="checkbox" id="brake" name="brake" value="Y">
                                </td>
                                <td>&nbsp;</td>
                                <td>Operational Manual</td>
                                <td width="10%"><input type="hidden" id="manual" name="manual" value="N">
                                    <input type="checkbox" id="manual" name="manual" value="Y">
                                </td>
                            </tr>
                            <tr>
                                <td>Vehicle Tool Kit</td>
                                <td><input type="hidden" id="vtk" name="vtk" value="N">
                                    <input type="checkbox" id="vtk" name="vtk" value="Y">
                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="21">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <td height="2">
                                </thead>
                        </tbody>
                    </table>

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