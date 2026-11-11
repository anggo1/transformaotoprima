<?php defined('BASEPATH') OR exit('No direct script access allowed');
$sisa = $f->total - $f->terbayar;
$opsiKas = $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'hanya' => array('KAS_BANK')), TRUE);
?>
<form class="acc-ajax" action="<?php echo site_url($ctrl . '/simpan_bayar'); ?>" method="post" autocomplete="off" data-modal-size="lg">
    <input type="hidden" name="id_faktur" value="<?php echo (int) $f->id; ?>">
    <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-hand-holding-usd mr-2 text-success"></i><?php echo $L['bayar']; ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="alert alert-light border mb-3">
            <div class="d-flex justify-content-between flex-wrap">
                <div><small class="text-muted d-block">Faktur</small><strong><?php echo html_escape($f->no_faktur); ?></strong> · <?php echo html_escape($f->kontak); ?></div>
                <div class="text-right"><small class="text-muted d-block">Sisa tagihan</small><strong style="font-size:1.1rem"><?php echo nf($sisa, 2); ?></strong></div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?php echo max(date('Y-m-d'), $f->tanggal); ?>" min="<?php echo $f->tanggal; ?>" required></div>
            <div class="form-group col-md-8"><label><?php echo $f->jenis === 'AR' ? 'Diterima di akun' : 'Dibayar dari akun'; ?></label><select name="kode_akun_kas" class="form-control select2" required><?php echo $opsiKas; ?></select></div>
            <div class="form-group col-md-4"><label>Jumlah</label><input type="text" name="jumlah" class="form-control input-angka text-right" value="<?php echo nf($sisa, 2); ?>" required></div>
            <div class="form-group col-md-8"><label>Keterangan</label><input type="text" name="keterangan" class="form-control" maxlength="255"></div>
        </div>
        <div class="acc-pot border rounded p-2 bg-light">
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="fb-pot-on">
                <label class="custom-control-label" for="fb-pot-on"><?php echo $f->jenis === 'AR'
                    ? 'Pelanggan memotong PPh (ada bukti potong) — jumlah diterima lebih kecil dari tagihan'
                    : 'Kita memotong PPh dari pemasok jasa — dibayar neto, PPh disetor sendiri'; ?></label>
            </div>
            <div class="form-row" id="fb-pot" style="display:none">
                <div class="form-group col-md-4"><label>Jenis PPh</label>
                    <select name="jenis_pph" id="fb-jenis" class="form-control">
                        <?php if ($f->jenis === 'AR') { ?>
                        <option value="PPH23" data-persen="<?php echo html_escape($pph23); ?>">PPh 23 (jasa) <?php echo html_escape($pph23); ?>%</option>
                        <option value="PPH22" data-persen="1.5">PPh 22 (pemungut) 1,5%</option>
                        <?php } else { ?>
                        <option value="PPH23" data-persen="<?php echo html_escape($pph23); ?>">PPh 23 (jasa badan) <?php echo html_escape($pph23); ?>%</option>
                        <option value="PPH21" data-persen="2.5">PPh 21 (jasa orang pribadi) ±2,5%</option>
                        <option value="PPH42" data-persen="10">PPh 4(2) final (sewa bangunan 10%)</option>
                        <?php } ?>
                    </select></div>
                <div class="form-group col-md-4"><label>Potongan PPh <small class="text-muted">(dari DPP <?php echo nf($dpp, 0); ?>)</small></label><input type="text" name="potongan" id="fb-pot-nilai" class="form-control input-angka text-right" value="0" data-dpp="<?php echo (float) $dpp; ?>"></div>
                <div class="form-group col-md-4"><label>No. Bukti Potong</label><input type="text" name="no_bukti_potong" class="form-control" maxlength="50"></div>
                <div class="col-12 small text-muted">Jurnal: <?php echo $f->jenis === 'AR'
                    ? 'Kas (diterima) + PPh dibayar dimuka (potongan) = Piutang berkurang. PPh ini menjadi <b>kredit pajak</b> di SPT Tahunan.'
                    : 'Hutang berkurang = Kas (dibayar) + Hutang PPh (potongan). Hutang PPh wajib disetor paling lambat tgl 15 bulan berikutnya lewat menu Perpajakan.'; ?></div>
            </div>
        </div>
    </div>
    <script>
    $(function () {
        var $j = $('[name=jumlah]'), sisa = <?php echo json_encode(round($sisa, 2)); ?>;
        var hitung = function () {
            var on = $('#fb-pot-on').is(':checked'), $p = $('#fb-pot-nilai');
            var pot = 0;
            if (on) { pot = Math.round(parseFloat($p.data('dpp')) * parseFloat($('#fb-jenis option:selected').data('persen')) ) / 100; $p.val(pot.toFixed(2)); }
            else $p.val('0');
            $j.val((sisa - pot).toFixed(2));
        };
        $('#fb-pot-on').on('change', function () { $('#fb-pot').toggle(this.checked); hitung(); });
        $('#fb-jenis').on('change', hitung);
        $('#fb-pot-nilai').on('input', function () { var v = parseFloat(String(this.value).replace(/,/g, '')) || 0; $j.val((sisa - v).toFixed(2)); });
    });
    </script>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan &amp; Posting</button></div>
</form>
