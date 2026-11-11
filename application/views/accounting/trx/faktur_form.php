<?php defined('BASEPATH') OR exit('No direct script access allowed');
$opsiArAp = $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'hanya' => array($L['kat'], 'LIAB_PENDEK', 'ASET_LANCAR'), 'pilih' => $akunArAp), TRUE);
$opsiLawan = $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'kecuali' => array('KAS_BANK', $L['kat']), 'pilih' => $akunLawan), TRUE);
?>
<form class="acc-ajax" id="form-faktur" action="<?php echo site_url($ctrl . '/simpan'); ?>" method="post" autocomplete="off">
    <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-file-invoice-dollar mr-2 text-primary"></i><?php echo $L['faktur']; ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-row">
            <div class="form-group col-md-4"><label><?php echo $L['kontak']; ?></label>
                <input type="text" name="kontak" class="form-control" list="dl-kontak" maxlength="100" required placeholder="ketik / pilih nama">
                <datalist id="dl-kontak"><?php foreach ($kontak as $k) { ?><option value="<?php echo html_escape($k); ?>"><?php } ?></datalist></div>
            <div class="form-group col-md-2"><label>Tanggal</label><input type="date" name="tanggal" id="ff-tgl" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></div>
            <div class="form-group col-md-2"><label>Termin (hari)</label><input type="number" id="ff-termin" class="form-control" value="30" min="0"></div>
            <div class="form-group col-md-2"><label>Jatuh Tempo</label><input type="date" name="jatuh_tempo" id="ff-jt" class="form-control" value="<?php echo date('Y-m-d', strtotime('+30 day')); ?>" required></div>
            <div class="form-group col-md-2"><label>No. Faktur</label><input type="text" name="no_faktur" class="form-control" maxlength="30" placeholder="otomatis"></div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4"><label>Akun <?php echo strtolower($L['judul']); ?></label><select name="kode_akun_ar_ap" class="form-control select2" required><?php echo $opsiArAp; ?></select></div>
            <div class="form-group col-md-3"><label>No. Referensi</label><input type="text" name="no_referensi" class="form-control" maxlength="50" placeholder="<?php echo $jenis === 'AR' ? 'No. SPK / WO / PO pelanggan' : 'No. faktur pemasok / PO'; ?>"></div>
            <div class="form-group col-md-5"><label>Keterangan</label><input type="text" name="keterangan" class="form-control" maxlength="255"></div>
        </div>
        <label class="mb-1">Rincian (akun <?php echo $jenis === 'AR' ? 'pendapatan' : 'beban / persediaan'; ?>)</label>
        <div class="table-responsive">
        <table class="table table-sm acc-lines mb-0" id="ff-lines" data-template="#tpl-ff-line" data-form="#form-faktur">
            <thead><tr><th style="width:30%">Akun</th><th>Keterangan</th><th style="width:9%" class="num">Qty</th><th style="width:16%" class="num">Harga</th><th style="width:15%" class="num">Jumlah</th><th style="width:36px"></th></tr></thead>
            <tbody></tbody>
            <tfoot>
                <tr><td colspan="4"><a href="#" class="btn btn-xs btn-outline-primary" data-add-line="#ff-lines"><i class="fas fa-plus"></i> Tambah baris</a><span class="float-right text-muted">Subtotal</span></td><td class="num"><strong class="acc-subtotal">0.00</strong></td><td></td></tr>
                <tr><td colspan="4" class="text-right text-muted">Diskon</td><td><input type="text" name="diskon" class="form-control form-control-sm input-angka text-right" value="0" data-acc-recalc></td><td></td></tr>
                <tr><td colspan="4" class="text-right">
                    <div class="d-inline-flex align-items-center">
                        <div class="custom-control custom-checkbox mr-2"><input type="checkbox" class="custom-control-input" id="ff-ppn-on" name="pakai_ppn" value="1"><label class="custom-control-label" for="ff-ppn-on">PPN</label></div>
                        <input type="text" name="ppn_persen" id="ff-ppn" class="form-control form-control-sm text-right" style="width:70px" value="0" data-default="<?php echo html_escape($ppn); ?>" data-acc-recalc readonly> <span class="ml-1">%</span>
                    </div>
                    <input type="text" name="no_faktur_pajak" id="ff-nsfp" class="form-control form-control-sm d-inline-block ml-2" style="width:190px;display:none!important" maxlength="30" placeholder="No. Faktur Pajak (NSFP)">
                    </td><td class="num acc-ppn">0.00</td><td></td></tr>
                <tr class="row-grand"><td colspan="4" class="text-right"><strong>TOTAL</strong></td><td class="num"><strong class="acc-total">0.00</strong></td><td></td></tr>
            </tfoot>
        </table>
        </div>
        <template id="tpl-ff-line">
            <tr>
                <td><select name="baris[__i__][kode_akun]" class="form-control form-control-sm select2"><?php echo $opsiLawan; ?></select></td>
                <td><input type="text" name="baris[__i__][keterangan]" class="form-control form-control-sm" maxlength="255"></td>
                <td><input type="text" name="baris[__i__][qty]" class="form-control form-control-sm text-right acc-qty" value="1" inputmode="decimal"></td>
                <td><input type="text" name="baris[__i__][harga]" class="form-control form-control-sm input-angka text-right acc-harga" inputmode="decimal"></td>
                <td class="num acc-jumlah">0.00</td>
                <td class="text-center"><a href="#" class="text-danger acc-del-line"><i class="fas fa-times"></i></a></td>
            </tr>
        </template>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="ff-cetak" name="cetak" value="1"><label class="custom-control-label" for="ff-cetak">Cetak setelah simpan</label></div>
        <div><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan &amp; Posting</button></div>
    </div>
</form>
<script>
$(function () {
    $('[data-add-line="#ff-lines"]').data('no-focus', 1).trigger('click').removeData('no-focus');
    var jt = function () { var d = new Date($('#ff-tgl').val()); if (isNaN(d)) return; d.setDate(d.getDate() + (parseInt($('#ff-termin').val(), 10) || 0)); $('#ff-jt').val(d.toISOString().slice(0, 10)); };
    $('#ff-tgl, #ff-termin').on('change input', jt);
    $('#ff-ppn-on').on('change', function () { var $p = $('#ff-ppn'); if (this.checked) $p.val($p.data('default')).prop('readonly', false); else $p.val(0).prop('readonly', true); $('#ff-nsfp').attr('style', 'width:190px;' + (this.checked ? '' : 'display:none!important')); $p.trigger('input'); });
});
</script>
