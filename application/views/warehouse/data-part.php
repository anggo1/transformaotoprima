
                        <div class="modal-body">

                            <form id="formPo" name="formPo" method="POST">
                                <div class="row">
                                    <div class="col-3">
                                        <label class="col-form-label">No Invoice</label>
                                        <input type="text" name="no_invoice" id="no_invoice" value=""
                                            class="form-control" placeholder="Invoice">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">No D O</label>
                                        <input type="text" name="no_do" id="no_do" value="" class="form-control"
                                            placeholder="Delivery Order">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Date</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                            <input type="text" name="tgl_estimasi_penawaran" id="tgl_estimasi_penawaran"
                                                value="" class="form-control tgl_estimasi_penawaran datetimepicker"
                                                data-toggle="datetimepicker" data-target=".tgl_estimasi_penawaran"
                                                data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Page</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="col-form-label">No Reff</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Date</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                            <input type="text" name="tgl_estimasi_penawaran" id="tgl_estimasi_penawaran"
                                                value="" class="form-control tgl_estimasi_penawaran datetimepicker"
                                                data-toggle="datetimepicker" data-target=".tgl_estimasi_penawaran"
                                                data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Customer No</label>
                                        <select name="supplier" id="supplier" class="form-control">
                                            <option value="">Customer...
                                            </option>
                                            <?php
											if (!empty($dataCustomer)) {
												foreach ($dataCustomer as $sp) {   ?>
                                            <option value="<?php echo $sp->kode_cus; ?>">
                                                <?php echo $sp->nama_cus; ?>
                                            </option>
                                            <?php
												}
											}
											?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Page</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="col-form-label">Registration No</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">VIN No</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Sales designation</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Date/time received</label>
                                        <div class="input-group date" id="datetimereceiuved"
                                            data-target-input="nearest">

                                            <input type="text" name="tgl_received" id="tgl_received" value=""
                                                class="form-control tgl_received datetimepicker"
                                                data-toggle="datetimepicker" data-target=".tgl_received"
                                                data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="col-form-label">Millage/Km</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Engine No</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Account No</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Received by</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="col-form-label">Routing No</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Last Service date/millage/km</label>
                                        <input type="text" name="keterangan" id="keterangan" value=""
                                            class="form-control" placeholder="Keterangan">
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Date of 1st registration</label>
                                        <div class="input-group date" id="tgl_regis" data-target-input="nearest">

                                            <input type="text" name="tgl_regis" id="tgl_regis" value=""
                                                class="form-control tgl_regis datetimepicker"
                                                data-toggle="datetimepicker" data-target=".tgl_regis"
                                                data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label">Deadline</label>
                                        <div class="input-group date" id="tgl_deadline" data-target-input="nearest">

                                            <input type="text" name="tgl_deadline" id="tgl_deadline" value=""
                                                class="form-control tgl_deadline datetimepicker"
                                                data-toggle="datetimepicker" data-target=".tgl_deadline"
                                                data-format="yyy-mm-dd">

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                                <div class="input-group-text" id="clear"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="Nama Konsumen" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-4">

                                    </div>
                                </div>
                                <?php
						$date = date("y-m");
						$ci_kons = get_instance();
						$query = "SELECT max(id_estimasi_penawaran) AS maxKode FROM tbl_wh_estimasi_penawaran WHERE id_estimasi_penawaran LIKE '%$date%'";
						$hasil = $ci_kons->db->query($query)->row_array();
						$noOrder = $hasil['maxKode'];
						$noUrut = (int)substr($noOrder, 5, 4);
						$noUrut++;
						$tahun = substr($date, 0, 2);
						$bulan = substr($date, 3, 2);
						$kode_po  = $tahun.'-'.$bulan.sprintf("%04s", $noUrut);
						?>
                                <input type="hidden" name="id_estimasi_penawaran" id="id_estimasi_penawaran"
                                    value="<?php echo $kode_po ?>" class="form-control">
                                <input type="hidden" name="kode_ref" id="kode_ref" class="form-control">
                                <input type="hidden" name="user" id="user"
                                    value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                                <div class="modal-footer right-content-between">
                                    <button class="btn btn-primary" id="simpan" type="submit"><span
                                            class="fa fa-save"></span>
                                        Simpan Data</button>
                                    <button type="button" class="btn btn-info cetak-po" id="cetak" hidden="hidden"
                                        data-id="" title="Add Data"><i class="fas fa-print"></i> Cetak Part
                                        Order</button>
                                </div>
                            </form>
                            <button type="button" class="btn btn-xl bg-gradient-success" id="tambah-part"
                                title="Add Part" data-toggle="modal" data-target="#modal_form"><i
                                    class="fas fa-plus"></i> Tambah Barang
                                PO</button>
                        </div>