<?php defined('BASEPATH') OR exit('No direct script access allowed');
$judul = array('MASUK' => 'Penerimaan Kas / Bank', 'KELUAR' => 'Pengeluaran Kas / Bank', 'TRANSFER' => 'Transfer Antar Kas / Bank');
$opsiKas = $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'hanya' => array('KAS_BANK')), TRUE);
$opsiLawan = $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'kecuali' => array('KAS_BANK')), TRUE);
?>
<form class="acc-ajax" id="form-kb" action="<?php echo site_url('KasBank/simpan'); ?>" method="post" autocomplete="off" data-modal-size="<?php echo $jenis === 'TRANSFER' ? 'lg' : 'xl'; ?>">
    <input type="hidden" name="jenis" value="<?php echo $jenis; ?>">
    <div class="modal-header">
        <h4 class="modal-title"><i class="fas <?php echo $jenis === 'MASUK' ? 'fa-arrow-down text-success' : ($jenis === 'KELUAR' ? 'fa-arrow-up text-danger' : 'fa-exchange-alt text-info'); ?> mr-2"></i><?php echo $judul[$jenis]; ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-row">
            <div class="form-group col-md-3"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></div>
            <div class="form-group col-md-<?php echo $jenis === 'TRANSFER' ? '4' : '5'; ?>"><label><?php echo $jenis === 'TRANSFER' ? 'Dari Akun' : ($jenis === 'MASUK' ? 'Masuk ke Akun' : 'Dibayar dari Akun'); ?></label>
                <select name="kode_akun_kas" class="form-control select2" required><?php echo $opsiKas; ?></select></div>
            <?php if ($jenis === 'TRANSFER') { ?>
            <div class="form-group col-md-5"><label>Ke Akun</label><select name="kode_akun_tujuan" class="form-control select2" required><?php echo $opsiKas; ?></select></div>
            <?php } else { ?>
            <div class="form-group col-md-4"><label><?php echo $jenis === 'MASUK' ? 'Diterima dari' : 'Dibayar kepada'; ?></label><input type="text" name="kontak" class="form-control" maxlength="100" placeholder="Nama pihak (opsional)"></div>
            <?php } ?>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3"><label>No. Bukti / Cek</label><input type="text" name="no_bukti" class="form-control" maxlength="50" placeholder="opsional"></div>
            <div class="form-group col-md-<?php echo $jenis === 'TRANSFER' ? '5' : '9'; ?>"><label>Keterangan</label><input type="text" name="keterangan" class="form-control" maxlength="255" required></div>
            <?php if ($jenis === 'TRANSFER') { ?>
            <div class="form-group col-md-4"><label>Jumlah</label><input type="text" name="jumlah" class="form-control input-angka text-right" inputmode="decimal" required></div>
            <?php } ?>
        </div>

        <?php if ($jenis !== 'TRANSFER') { ?>
        <label class="mb-1"><?php echo $jenis === 'MASUK' ? 'Rincian sumber penerimaan (akun lawan)' : 'Rincian pengeluaran (akun beban/lawan)'; ?></label>
        <div class="table-responsive">
        <table class="table table-sm acc-lines mb-1" id="kb-lines" data-template="#tpl-kb-line" data-form="#form-kb">
            <thead><tr><th style="width:38%">Akun</th><th>Keterangan</th><th style="width:20%" class="num">Jumlah</th><th style="width:40px"></th></tr></thead>
            <tbody></tbody>
            <tfoot><tr><td colspan="2"><a href="#" class="btn btn-xs btn-outline-primary" data-add-line="#kb-lines"><i class="fas fa-plus"></i> Tambah baris</a></td>
                <td class="num"><strong class="acc-subtotal">0.00</strong></td><td></td></tr></tfoot>
        </table>
        </div>
        <template id="tpl-kb-line">
            <tr>
                <td><select name="baris[__i__][kode_akun]" class="form-control form-control-sm select2"><?php echo $opsiLawan; ?></select></td>
                <td><input type="text" name="baris[__i__][keterangan]" class="form-control form-control-sm" maxlength="255"></td>
                <td><input type="text" name="baris[__i__][jumlah]" class="form-control form-control-sm input-angka text-right acc-jumlah" inputmode="decimal"></td>
                <td class="text-center"><a href="#" class="text-danger acc-del-line" title="Hapus"><i class="fas fa-times"></i></a></td>
            </tr>
        </template>
        <?php } ?>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="kb-cetak" name="cetak" value="1"><label class="custom-control-label" for="kb-cetak">Cetak bukti setelah simpan</label></div>
        <div><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan &amp; Posting</button></div>
    </div>
</form>
<script>$(function () { $('[data-add-line="#kb-lines"]').data('no-focus', 1).trigger('click').trigger('click').removeData('no-focus'); });</script>
