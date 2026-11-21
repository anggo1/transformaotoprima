<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Modal cetak dokumen After Sales: memuat halaman cetak Services Report (ReportService/cetak) di dalam iframe. */
$wo  = trim((string) $wo);
$src = site_url('ReportService/cetak/' . $jenis . '/' . rawurlencode($wo));
$fid = 'frame-cetak-' . preg_replace('/[^a-z0-9]/i', '', $jenis . $wo);
?>
<div class="modal-header py-2 bg-light">
    <h5 class="modal-title"><i class="fas fa-print mr-2 text-info"></i>Cetak <?php echo html_escape($judul); ?> <small class="text-muted">· WO <?php echo html_escape($wo); ?></small></h5>
    <div class="ml-auto">
        <button type="button" class="btn btn-sm btn-info" onclick="var f=document.getElementById('<?php echo $fid; ?>');f.contentWindow.focus();f.contentWindow.print();"><i class="fas fa-print mr-1"></i> Cetak</button>
        <a class="btn btn-sm btn-outline-secondary" target="_blank" href="<?php echo $src; ?>"><i class="fas fa-external-link-alt mr-1"></i> Tab baru</a>
        <button type="button" class="btn btn-sm btn-outline-dark" data-dismiss="modal"><i class="fas fa-times"></i></button>
    </div>
</div>
<div class="modal-body p-0" style="background:#e9edf2">
    <iframe id="<?php echo $fid; ?>" src="<?php echo $src; ?>?modal=1" style="width:100%;height:78vh;border:0;display:block" title="Cetak <?php echo html_escape($judul); ?>"></iframe>
</div>
<div class="modal-footer py-1 small text-muted justify-content-start">Atur printer: kertas A4, skala 100%, margin bawaan.</div>
