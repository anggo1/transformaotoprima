    
    <div class="row">
        <div class="modal-content">
            <div class="modal-header text-blue">

                <h5 style="display:block; text-align:center;"><span
                        class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Part Request</h5>

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
            </div>

            <div id="operation-body" class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">No Part</label>
                            <div class="col-sm-12">
                                <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $s->wo_no; ?>"
                                    class="form-control" placeholder="Operation">
                                <input type="text" name="no_part" id="no_part" value="" class="form-control"
                                    placeholder="No Part">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Part Name</label>
                            <div class="col-sm-12 input-group">
                                <input type="text" name="nama_part" id="nama_part" value="" class="form-control"
                                    placeholder="Part Name">

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="col-sm-6 col-form-label">Quantity</label>
                            <div class="col-sm-12 input-group">
                                <input type="text" name="jumlah" id="jumlah" value="" class="form-control"
                                    placeholder="Quantity">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-4 col-form-label">Remark</label>
                            <div class="col-sm-12 input-group">
                                <input type="text" name="keterangan" id="keterangan" value="" class="form-control"
                                    placeholder="Remark">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-warning btn-flat" onclick="dataPart()"
                                        data-toggle="modal"
                                        data-target="#modal-part">Cari!</button>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer right-content-between">
                    <button class="btn btn-info" id="simpan-request" onclick="insertRequest()" type="button"><span
                            class="fa fa-save"></span>
                        Save List</button>
                </div>
            </div>
            <div class="card-body">
                <div id="data-detail-request"></div>
            </div>

            <form id="form-part-request" name="form-part-request" method="POST">
                <div class="card-body">

                    <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $s->wo_no ?>"
                        class="form-control" placeholder="Operation">
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

<div class="modal fade" id="modal-part">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-gray-light">
            <div class="modal-header">
                <h4 class="modal-title">Spare Part Data</h4>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dt-responsive nowrap" id="tabel-part-request" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Part</th>
                                <th>Part Name</th>
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
<script language="javascript">

            //$('#tabel-part-request').DataTable().fnDestroy();
</script>