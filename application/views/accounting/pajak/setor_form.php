<?php defined('BASEPATH') OR exit('No direct script access allowed');
$opsiKas = $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'hanya' => array('KAS_BANK')), TRUE);
?>
<form class="acc-ajax" action="<?php echo site_url('Pajak/simpan_setor'); ?>" method="post" autocomplete="off" data-modal-size="lg">
    <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-money-check-alt mr-2 text-success"></i>Catat Setoran Pajak</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-row">
            <div class="form-group col-md-6"><label>Jenis pajak</label>
                <select name="jenis" id="sp-jenis" class="form-control" required>
                    <option value="">— pilih —</option>
                    <?php foreach (Akuntansi::$JENIS_PAJAK as $k => $x) { ?><option value="<?php echo $k; ?>" <?php echo $jenis === $k ? 'selected' : ''; ?>><?php echo $x[0]; ?></option><?php } ?>
                </select></div>
            <div class="form-group col-md-3"><label>Masa pajak</label><input type="month" name="masa" id="sp-masa" class="form-control" value="<?php echo html_escape($masa); ?>" required>
                <small class="form-text text-muted">PPh 29: isi Desember tahun pajak</small></div>
            <div class="form-group col-md-3"><label>Tanggal setor</label><input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></div>
            <div class="form-group col-md-6"><label>Dibayar dari akun</label><select name="kode_akun_kas" class="form-control select2" required><?php echo $opsiKas; ?></select></div>
            <div class="form-group col-md-6"><label>Jumlah <a href="#" id="sp-saran" class="small ml-2"><i class="fas fa-magic"></i> isi otomatis dari saldo</a></label><input type="text" name="jumlah" id="sp-jml" class="form-control input-angka text-right" value="<?php echo $saran > 0 ? nf($saran, 2) : ''; ?>" required></div>
            <div class="form-group col-md-6"><label>NTPN <small class="text-muted">(16 karakter dari bukti penerimaan negara)</small></label><input type="text" name="ntpn" class="form-control text-uppercase" maxlength="30"></div>
            <div class="form-group col-md-6"><label>Keterangan</label><input type="text" name="keterangan" class="form-control" maxlength="255" placeholder="No. billing / kode MAP-KJS"></div>
        </div>
        <div class="small text-muted"><i class="fas fa-info-circle"></i> Jurnal otomatis: <b>Debit</b> akun pajak sesuai jenis (Hutang PPN/PPh, PPh 25 dibayar dimuka, atau Beban PPh Final), <b>Kredit</b> akun kas/bank. Tombol "isi otomatis" mengambil saldo hutang pajak per akhir masa (untuk PPh Final: 0,5% × omzet bulan itu; PPh 25: hasil rekonsiliasi tahun lalu ÷ 12).</div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan &amp; Posting</button></div>
</form>
<script>
$(function () {
    var isi = function (e) {
        if (e) e.preventDefault();
        var j = $('#sp-jenis').val(), m = $('#sp-masa').val();
        if (!j || !m) return;
        $.getJSON('<?php echo site_url('Pajak/saran'); ?>', { jenis: j, masa: m }, function (r) { $('#sp-jml').val(Number(r.jumlah || 0).toFixed(2)).trigger('blur'); });
    };
    $('#sp-saran').on('click', isi);
    $('#sp-jenis, #sp-masa').on('change', isi);
});
</script>
