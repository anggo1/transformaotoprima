<section class="content">
        <div class="row">
            <div class="col-md-12">
                            <?php
						$date = date("my");
						$ci_kons = get_instance();
						$query = "SELECT max(wo_no) AS maxKode FROM tbl_after_sales WHERE wo_no LIKE '%$date%'";
						$hasil = $ci_kons->db->query($query)->row_array();
						$noOrder = $hasil['maxKode'];
						$noUrut = substr($noOrder, 0, 5);
						$noUrut++;
						$tahun = substr($date, 2, 2);
						$bulan = substr($date, 0, 2);
						$kode_po  = sprintf("%05s", $noUrut).$bulan.$tahun; 
						$kode_po2  = sprintf("%05s", $noUrut);
						?>
                    <div class="modal-content">
                        <div class="modal-header text-blue">

                            <h5 style="display:block; text-align:center;"><span
                                    class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Service Appoinment</h5> No : <?php echo $kode_po ?>
                            <button type="button" class="btn btn-success" id="tambah" hidden="hidden"
                                onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> PO
                                BARU</button>
                        </div>
                        <div class="modal-body">
                            <form id="formPo" name="formPo" method="POST">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-4">
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                            <input type="text" name="tgl_part_order" id="tgl_part_order" value=""
                                                class="form-control tgl_part_order datetimepicker"
                                                data-toggle="datetimepicker" data-target=".tgl_part_order"
                                                data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <label class="col-sm-2 col-form-label">Customer</label>
                                    <div class="col-sm-4">
                                        <select name="customer" id="customer" class="form-control">
                                            <option value="">Customer...
                                            </option>
                                            <?php
											if (!empty($dataCus)) {
												foreach ($dataCus as $sp) {   ?>
                                            <option value="<?php echo $sp->kode_cus; ?>">
                                                <?php echo $sp->nama_cus; ?>
                                            </option>
                                            <?php
												}
											}
											?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group row">
                                    <label class="col-sm-2 col-form-label">Cases Rep</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="cases_rep" id="cases_rep" value=""
                                            class="form-control" placeholder="Cases Rep">
                                    </div>
                                    <label class="col-sm-2 col-form-label">VIN</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="vin" id="vin" value=""
                                            class="form-control" placeholder="VIN">
                                    </div>
                                </div>                                
                                <div class="row form-group row">
                                    <label class="col-sm-2 col-form-label">Licence Plate</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="licence_plate" id="licence_plate" value=""
                                            class="form-control" placeholder="Licence Plate">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Keterangan</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                </div>
                                <div class="row form-group row">
                                    <label class="col-sm-2 col-form-label">Vehicle Type</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="vehicle_type" id="vehicle_type" value=""
                                            class="form-control" placeholder="Vehicle Type">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Storing</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="storing" id="storing" value=""
                                            class="form-control" placeholder="Storing">
                                    </div>
                                </div>
                                
                                <div class="row form-group row">
                                    <label class="col-sm-2 col-form-label">Service Start</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="service_start" id="service_start" value=""
                                            class="form-control" placeholder="Service Start">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Service End</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="service_end" id="service_end" value=""
                                            class="form-control" placeholder="Service End">
                                    </div>
                                </div>
                                
                                <input type="hidden" name="id_part_order" id="id_part_order"
                                    value="<?php echo $kode_po ?>" class="form-control">
                                <input type="hidden" name="kode_ref" id="kode_ref" class="form-control">
                                <input type="hidden" name="user" id="user"
                                    value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                                <div class="modal-footer right-content-between">
                                    <button class="btn btn-primary" id="simpan" type="submit"><span
                                            class="fa fa-save"></span> Simpan Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
</section>
<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data PO Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->
<script type="text/javascript">

</script>