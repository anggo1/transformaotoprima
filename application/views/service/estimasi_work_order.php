<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card-body card-outline">
                <div class="modal-body">

                    <?php
                                            if (!empty($dataCus)) {
                                                foreach ($dataCus as $a) {
                                                 foreach ($dataCus as $b) {
                                                }}}

						$date = date("y-m");
						$ci_kons = get_instance();
						$query = "SELECT max(id_estimasi_penawaran) AS maxKode FROM tbl_wh_estimasi_penawaran WHERE id_estimasi_penawaran LIKE '%$date%'";
						$hasil = $ci_kons->db->query($query)->row_array();
						$noOrder = $hasil['maxKode'];
						$noUrut = (int)substr($noOrder, 5, 4);
						$noUrut++;
						$tahun = substr($date, 0, 2);
						$bulan = substr($date, 3, 2);
						$kode_po  = $tahun.'-'.$bulan.sprintf("%03s", $noUrut);
						$kode_ref = 'SP/TOP/'.$bulan.'/'.$tahun.'/'.sprintf("%03s", $noUrut);
						?>
                    <form id="formPo" name="formPo" method="POST">
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">No Reff</label>
                                <input type="text" name="no_ref" id="no_ref" value="<?php echo $kode_ref ?>"
                                    class="form-control" placeholder="Nomor Referensi">
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
                                <input type="text" name="kode_cus" id="kode_cus" value="<?php echo $a->kode_cus; ?>"
                                    class="form-control" placeholder="Customer No">
                                <input type="text" name="nama_cus" id="nama_cus" value="<?php echo $a->nama_cus; ?>"
                                    class="form-control" placeholder="Customer Name">

                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Registration No</label>
                                <input type="text" name="no_reg" id="no_reg" value="" class="form-control"
                                    placeholder="No Registration">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">VIN No</label>
                                <input type="text" name="no_vin" id="no_vin" value="" class="form-control"
                                    placeholder="VIN">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Sales designation</label>
                                <input type="text" name="sales_design" id="sales_design" value="" class="form-control"
                                    placeholder="Sales designation">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Date/time received</label>
                                <input type="text" name="date_received" id="date_received" value="" class="form-control"
                                    placeholder="Date Received">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Millage/Km</label>
                                <input type="text" name="millage" id="millage" value="" class="form-control"
                                    placeholder="Millage / Km">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">Engine No</label>
                                <input type="text" name="engine_no" id="engine_no" value="" class="form-control"
                                    placeholder="Engine No">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Account No</label>
                                <input type="text" name="acc_no" id="acc_no" value="" class="form-control"
                                    placeholder="Account No">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Received by</label>
                                <input type="text" name="received_by" id="received_by" value="" class="form-control"
                                    placeholder="Received by">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Routing No</label>
                                <input type="text" name="routing_no" id="routing_no" value="" class="form-control"
                                    placeholder="Routing No">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label">Last Service
                                    date/millage/km</label>
                                <input type="text" name="last_km" id="last_km" value="" class="form-control"
                                    placeholder="Last Service date/millage/km">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Date of 1st
                                    registration</label>
                                <input type="text" name="date_of_regis" id="date_of_regis" value="" class="form-control"
                                    placeholder="Date of 1st registration">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">P P N</label>
                                <input type="text" name="ppn" id="ppn" value="" class="form-control" placeholder="PPN">
                            </div>
                            <div class="col-3">
                                <label class="col-form-label">Bea Kirim</label>
                                <input type="text" name="bea_kirim" id="bea_kirim" value="0"
                                    onkeyup="formatNumber(this)" onchange="formatNumber(this);" class="form-control"
                                    placeholder="Bea Pengiriman Barang" style="text-align:right; color:red;">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="Nama Konsumen" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-4">

                            </div>
                        </div>
                        <input type="hidden" name="id_estimasi_penawaran" id="id_estimasi_penawaran"
                            value="<?php echo $kode_po ?>" class="form-control">
                        <input type="hidden" name="kode_ref" id="kode_ref" class="form-control">
                        <input type="hidden" name="user" id="user"
                            value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                        <div class="modal-footer right-content-between">
                            <button class="btn btn-primary" id="simpan" type="submit" hidden="hidden"><span
                                    class="fa fa-save"></span>
                                Simpan Data</button>
                            <button type="button" class="btn btn-info cetak-po" id="cetak" hidden="hidden" data-id=""
                                title="Add Data"><i class="fas fa-print"></i> Cetak Estimasi
                                Penawaran</button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-xl bg-gradient-success" id="tambah-part" onclick="panggilPart()" title="Add Part"
                        data-toggle="modal" data-target="#modal_part"><i class="fas fa-plus"></i> Tambah
                        Barang</button>
                    <button type="button" class="btn btn-xl bg-gradient-info" id="tambah-jasa" onclick="panggilTabel()"
                        title="Add Part" data-toggle="modal" data-target="#modal_operation"><i class="fas fa-plus"></i>
                        Tambah
                        Jasa</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-body card-outline">
                <div class="card-header card-dark">
                    <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i>
                        &nbsp; Keterangan</h3>
                    <div class="text-right">
                        <button type="button" class="btn btn-sm btn-dark" onclick="insertNote()"><i
                                class="fas fa-plus"></i>
                            Standart Keterangan</button>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#tambah-keterangan" title="Add Data"><i class="fas fa-plus"></i>
                            Add</button>
                    </div>
                </div>
                <div class="col-12">
                    <p></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover nowrap" id="list-keterangan">
                            <thead>
                                <tr>
                                    <th width="10%">No</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="data-keterangan">
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
                <div id="modal-keterangan"></div>
            </div>
        </div>
        <div class="card-body card-outline">
            <div id="modal-po"></div>
            <div id="data-po"></div>
            <div id="data-po-cache"></div>
        </div>
    </div>
</div>
</section>
<?php show_my_confirm('hapusDetail', 'hapus-detail', 'Hapus Data PO Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->