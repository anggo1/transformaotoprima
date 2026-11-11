<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Form Service Appointment (tambah & ubah). Nama field sama seperti versi lama. */
$r = isset($r) ? $r : null; $fid = isset($form_id) ? $form_id : 'form-tambah-appointment';
$v = function ($k, $d = '') use ($r) { return $r && isset($r->$k) && $r->$k !== null ? $r->$k : $d; };
$tg = function ($k, $d = '') use ($v) { $x = (string) $v($k, $d); return $x && substr($x, 0, 4) !== '0000' ? substr($x, 0, 10) : $d; };
$jam = function ($k, $d = '') use ($v) { $x = (string) $v($k, $d); return $x !== '' ? substr($x, 0, 5) : $d; };
$cek = function ($k, $val, $d) use ($v) { return (string) $v($k, $d) === $val ? 'checked' : ''; };
?>
<div class="modal-content sa-form">
    <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-calendar-check text-primary mr-1"></i> <?php echo $r ? 'Ubah Service Appointment <small class="text-muted">WO ' . html_escape($r->wo_no) . '</small>' : 'Service Appointment Baru'; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
    </div>
    <form id="<?php echo $fid; ?>" method="POST" autocomplete="off">
        <div class="modal-body">
            <?php if ($r) { ?><input type="hidden" name="id" value="<?php echo (int) $r->id; ?>"><?php } ?>
            <input type="hidden" name="pembuat" value="<?php echo html_escape((string) $this->session->userdata('full_name')); ?>">
            <div class="sa-sec"><i class="fas fa-clock"></i> Jadwal</div>
            <div class="form-row">
                <div class="form-group col-md-3"><label>Tanggal masuk <span class="text-danger">*</span></label><input type="date" name="date_open_wo" class="form-control" value="<?php echo $tg('date_open_wo', date('Y-m-d')); ?>" required></div>
                <div class="form-group col-md-2"><label>Jam masuk</label><input type="time" name="clockin" class="form-control" value="<?php echo $jam('clockin', date('H:i')); ?>"></div>
                <div class="form-group col-md-3"><label>Target selesai (deadline)</label><input type="date" name="dead_line" class="form-control" value="<?php echo $tg('dead_line', date('Y-m-d', strtotime('+1 day'))); ?>"></div>
                <div class="form-group col-md-2"><label>Jam target</label><input type="time" name="dead_time" class="form-control" value="<?php echo $jam('dead_time', '16:00'); ?>"></div>
                <div class="form-group col-md-2"><label>Service advisor</label><input name="sa_name" class="form-control" value="<?php echo html_escape($v('sa_name', (string) $this->session->userdata('full_name'))); ?>"></div>
            </div>
            <div class="sa-sec"><i class="fas fa-user-tie"></i> Pelanggan &amp; kendaraan</div>
            <div class="form-row">
                <div class="form-group col-md-6"><label>Customer <span class="text-danger">*</span></label>
                    <select name="customer" class="form-control sa-customer" required>
                        <option value="">— pilih customer —</option>
                        <?php foreach ((array) $dataCus as $sp) { $val = $sp->kode_cus . '|' . $sp->nama_cus; ?>
                        <option value="<?php echo html_escape($val); ?>" data-details="<?php echo html_escape($sp->kota); ?>" <?php echo $r && $r->customer === $sp->kode_cus ? 'selected' : ''; ?>><?php echo html_escape($sp->nama_cus); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-6"><label>Kendaraan yang pernah diservis</label>
                    <select class="form-control sa-riwayat"><option value="">— pilih customer dahulu —</option></select>
                    <small class="text-muted">Pilih untuk mengisi VIN, no. mesin, no. polisi, tipe & km otomatis.</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3"><label>No. polisi</label><input name="licence_plate" class="form-control text-uppercase" value="<?php echo html_escape($v('no_pol')); ?>" placeholder="B 1234 XYZ"></div>
                <div class="form-group col-md-3"><label>Tipe kendaraan</label><input name="vehicle_type" class="form-control" value="<?php echo html_escape($v('type')); ?>" placeholder="mis. Actros 2644"></div>
                <div class="form-group col-md-3"><label>VIN / no. rangka</label><input name="vin" class="form-control text-uppercase" value="<?php echo html_escape($v('vin')); ?>"></div>
                <div class="form-group col-md-3"><label>No. mesin</label><input name="engine_no" class="form-control text-uppercase" value="<?php echo html_escape($v('engine_no')); ?>"></div>
                <div class="form-group col-md-3"><label>Kilometer (mileage)</label><input name="mileage" class="form-control" inputmode="numeric" value="<?php echo html_escape($v('mileage')); ?>"></div>
                <div class="form-group col-md-3"><label>Service terakhir</label><input type="date" name="last_service_date" class="form-control" value="<?php echo $tg('last_service_date'); ?>"></div>
            </div>
            <div class="sa-sec"><i class="fas fa-comment-medical"></i> Keluhan &amp; jenis layanan</div>
            <div class="form-row">
                <div class="form-group col-md-7"><label>Keluhan pelanggan</label><textarea name="customer_complain" rows="3" class="form-control" placeholder="Uraikan keluhan / permintaan pelanggan"><?php echo html_escape($v('customer_complain')); ?></textarea></div>
                <div class="form-group col-md-5"><label>Catatan (remarks)</label><textarea name="remark" rows="3" class="form-control" placeholder="Catatan internal"><?php echo html_escape($v('remarks')); ?></textarea></div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4"><label class="d-block">Storing (kendaraan derek / mogok)</label>
                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                        <label class="btn btn-outline-secondary btn-sm <?php echo $cek('storing', 'N', 'N') ? 'active' : ''; ?>"><input type="radio" name="storing" value="N" <?php echo $cek('storing', 'N', 'N'); ?>> Tidak</label>
                        <label class="btn btn-outline-danger btn-sm <?php echo $cek('storing', 'Y', 'N') ? 'active' : ''; ?>"><input type="radio" name="storing" value="Y" <?php echo $cek('storing', 'Y', 'N'); ?>> Ya, storing</label>
                    </div></div>
                <div class="form-group col-md-4"><label class="d-block">Status service</label>
                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                        <label class="btn btn-outline-secondary btn-sm <?php echo $cek('free_service', 'N', 'N') ? 'active' : ''; ?>"><input type="radio" name="free_service" value="N" <?php echo $cek('free_service', 'N', 'N'); ?>> Non free</label>
                        <label class="btn btn-outline-success btn-sm <?php echo $cek('free_service', 'Y', 'N') ? 'active' : ''; ?>"><input type="radio" name="free_service" value="Y" <?php echo $cek('free_service', 'Y', 'N'); ?>> Free service</label>
                    </div></div>
                <div class="form-group col-md-4"><label class="d-block">Pembayaran (CIW / GW)</label>
                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                        <?php foreach (array('C' => 'Cash', 'I' => 'Internal', 'W' => 'Warranty', 'GW' => 'Goodwill') as $k => $l) { ?>
                        <label class="btn btn-outline-primary btn-sm <?php echo $cek('status_service', $k, 'C') ? 'active' : ''; ?>"><input type="radio" name="status_service" value="<?php echo $k; ?>" <?php echo $cek('status_service', $k, 'C'); ?>> <?php echo $l; ?></label>
                        <?php } ?>
                    </div></div>
            </div>
        </div>
        <div class="modal-footer">
            <span class="small text-muted mr-auto"><?php echo $r ? 'Nomor WO tidak berubah.' : 'Nomor WO dibuat otomatis saat disimpan.'; ?></span>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button class="btn btn-primary" type="submit"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </form>
</div>
