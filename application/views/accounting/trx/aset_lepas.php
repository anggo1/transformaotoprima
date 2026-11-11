<?php defined('BASEPATH') OR exit('No direct script access allowed');
$opsi = $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'hanya' => array('KAS_BANK', 'PIUTANG', 'ASET_LANCAR')), TRUE);
?>
<form class="acc-ajax" action="<?php echo site_url('AsetTetap/lepas'); ?>" method="post" data-modal-size="lg">
    <input type="hidden" name="id" value="<?php echo (int) $a->id; ?>">
    <div class="modal-header"><h4 class="modal-title"><i class="fas fa-sign-out-alt mr-2 text-warning"></i>Pelepasan Aset · <?php echo html_escape($a->kode_aset); ?></h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
    <div class="modal-body">
        <div class="alert alert-light border small">Nilai buku saat ini: <strong><?php echo nf($a->harga_perolehan - $a->akumulasi, 2); ?></strong> (harga <?php echo nf($a->harga_perolehan, 2); ?> − akumulasi <?php echo nf($a->akumulasi, 2); ?>). Proses penyusutan sampai bulan pelepasan sebelum menyimpan. Selisih nilai jual dengan nilai buku dicatat sebagai laba/rugi pelepasan aset.</div>
        <div class="form-row">
            <div class="form-group col-md-4"><label>Jenis</label><select name="status" class="form-control"><option value="DIJUAL">Dijual</option><option value="DIHAPUS">Dihapus / rusak</option></select></div>
            <div class="form-group col-md-4"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></div>
            <div class="form-group col-md-4"><label>Nilai jual</label><input type="text" name="nilai" class="form-control input-angka text-right" value="0"></div>
            <div class="form-group col-12"><label>Diterima di akun</label><select name="akun_kas" class="form-control select2"><?php echo $opsi; ?></select></div>
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning"><i class="fas fa-check mr-1"></i> Proses Pelepasan</button></div>
</form>
