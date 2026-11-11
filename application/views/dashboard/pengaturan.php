<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('warehouse/_flash');
if ($jenis !== '') { ?>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-2" style="gap:.5rem">
    <a href="<?php echo site_url('PengaturanDashboard'); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    <span class="small text-muted"><?php echo $tersimpan ? 'Pengaturan tersimpan' : 'Belum diatur — susunan di bawah adalah preset bawaan'; ?> · <a target="_blank" href="<?php echo site_url('dashboard?pratinjau=' . $jenis . ':' . $target); ?>">Pratinjau sebagai admin</a></span>
</div>
<?php $this->load->view('dashboard/_editor'); return; }

$st = function ($k) use ($ada) { return isset($ada[$k]) ? '<span class="badge badge-success">Diatur</span><div class="small text-muted">' . html_escape($ada[$k]->updated_by) . ' · ' . date('d/m/y', strtotime($ada[$k]->updated_at)) . ($ada[$k]->boleh_ubah === 'Y' ? ' · user boleh ubah' : '') . '</div>' : '<span class="badge badge-light border">Bawaan</span>'; };
$nmDept = array(); foreach ($dept as $d) $nmDept[$d->id_departement] = $d->departement;
?>
<div class="wh-steps mb-3">
    <div class="wh-steps__item"><b>1</b><span><strong>Level user</strong>Susunan dasar untuk semua user dengan level tersebut</span></div>
    <div class="wh-steps__item"><b>2</b><span><strong>Departemen</strong>Lebih spesifik: berlaku untuk user yang dipetakan ke departemen</span></div>
    <div class="wh-steps__item"><b>3</b><span><strong>User tertentu</strong>Paling spesifik, menimpa level & departemen</span></div>
    <div class="wh-steps__item"><b><i class="fas fa-shield-alt"></i></b><span><strong>Hak akses tetap berlaku</strong>Widget dari menu yang tidak boleh dilihat user otomatis disembunyikan</span></div>
</div>
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-layer-group mr-1"></i> Per level user</h3></div>
            <div class="card-body p-0"><table class="table table-sm mb-0">
                <thead><tr><th>Level</th><th>Status</th><th>Bawaan</th><th></th></tr></thead><tbody>
                <?php foreach ($level as $l) { $k = 'LEVEL:' . $l->id_level; $pk = isset($pl[$l->id_level]) ? $pl[$l->id_level] : 'UMUM'; ?>
                <tr><td><b><?php echo html_escape($l->nama_level); ?></b> <span class="text-muted small">#<?php echo $l->id_level; ?></span></td><td><?php echo $st($k); ?></td><td class="small"><?php echo html_escape($preset[$pk]['nama']); ?></td>
                    <td class="text-right text-nowrap"><a class="btn btn-xs btn-outline-secondary" target="_blank" href="<?php echo site_url('dashboard?pratinjau=' . (isset($ada[$k]) ? $k : 'PRESET:' . $pk)); ?>" title="Pratinjau"><i class="fas fa-eye"></i></a> <a class="btn btn-xs btn-primary" href="<?php echo site_url('PengaturanDashboard?jenis=LEVEL&target=' . $l->id_level); ?>">Atur</a></td></tr>
                <?php } ?>
                </tbody></table></div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-sitemap mr-1"></i> Per departemen</h3></div>
            <div class="card-body p-0"><table class="table table-sm mb-0">
                <thead><tr><th>Departemen</th><th>Status</th><th class="num">User</th><th></th></tr></thead><tbody>
                <?php foreach ($dept as $d) { $k = 'DEPT:' . $d->id_departement; $n = 0; foreach ($ud as $x) if ((int) $x->id_departement === (int) $d->id_departement) $n++; ?>
                <tr><td><b><?php echo html_escape($d->departement); ?></b></td><td><?php echo $st($k); ?></td><td class="num"><?php echo $n; ?></td>
                    <td class="text-right text-nowrap"><?php if (isset($ada[$k])) { ?><a class="btn btn-xs btn-outline-secondary" target="_blank" href="<?php echo site_url('dashboard?pratinjau=' . $k); ?>"><i class="fas fa-eye"></i></a> <?php } ?><a class="btn btn-xs btn-primary" href="<?php echo site_url('PengaturanDashboard?jenis=DEPT&target=' . $d->id_departement); ?>">Atur</a></td></tr>
                <?php } if ( ! $dept) { ?><tr><td colspan="4" class="text-muted text-center py-3">Belum ada data departemen (menu HRMS → Panel Setting HRD).</td></tr><?php } ?>
                </tbody></table></div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-user-cog mr-1"></i> User tertentu</h3></div>
            <form class="card-body d-flex" style="gap:.4rem" method="get">
                <input type="hidden" name="jenis" value="USER">
                <select name="target" class="form-control form-control-sm"><?php foreach ($user as $u) { ?><option value="<?php echo $u->id_user; ?>"><?php echo html_escape($u->full_name . ' (' . $u->username . ')'); ?><?php echo isset($ada['USER:' . $u->id_user]) ? ' ✓' : ''; ?></option><?php } ?></select>
                <button class="btn btn-sm btn-primary">Atur</button>
            </form>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card" id="pengumuman">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-bullhorn mr-1"></i> Pengumuman di dashboard</h3></div>
            <form class="card-body" method="post" action="<?php echo site_url('PengaturanDashboard/pengumuman'); ?>">
                <div class="form-row">
                    <div class="form-group col-md-7"><label>Judul</label><input name="judul" class="form-control form-control-sm" maxlength="120" value="<?php echo html_escape($umum['judul']); ?>" placeholder="mis. Stock opname akhir tahun"></div>
                    <div class="form-group col-md-2"><label>Warna</label><select name="warna" class="form-control form-control-sm"><?php foreach (array('info' => 'Info', 'success' => 'Hijau', 'warning' => 'Kuning', 'danger' => 'Merah') as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $umum['warna'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
                    <div class="form-group col-md-3"><label>Tampil s/d</label><input type="date" name="sampai" class="form-control form-control-sm" value="<?php echo html_escape($umum['sampai']); ?>"></div>
                </div>
                <div class="form-group"><label>Isi</label><textarea name="isi" rows="3" class="form-control form-control-sm" maxlength="2000" placeholder="Kosongkan untuk menyembunyikan pengumuman"><?php echo html_escape($umum['isi']); ?></textarea></div>
                <?php if ($bisa) { ?><button class="btn btn-sm btn-primary">Simpan pengumuman</button><?php } ?>
                <span class="small text-muted ml-2">Tampil untuk semua user yang dashboard-nya memuat widget "Pengumuman".</span>
            </form>
        </div>
        <div class="card" id="departemen">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-link mr-1"></i> Pemetaan user → departemen</h3></div>
            <form method="post" action="<?php echo site_url('PengaturanDashboard/departemen'); ?>">
                <div class="table-responsive" style="max-height:420px"><table class="table table-sm mb-0">
                    <thead class="thead-sticky"><tr><th>User</th><th>Departemen</th><th>NIP pegawai (opsional)</th></tr></thead><tbody>
                    <?php foreach ($user as $u) { $x = isset($ud[$u->id_user]) ? $ud[$u->id_user] : null; ?>
                    <tr><td><b><?php echo html_escape($u->full_name); ?></b><div class="small text-muted"><?php echo html_escape($u->username . ' · ' . $u->lokasi); ?></div></td>
                        <td><select name="u[<?php echo $u->id_user; ?>][dept]" class="form-control form-control-sm"><option value="0">—</option><?php foreach ($dept as $d) { ?><option value="<?php echo $d->id_departement; ?>" <?php echo $x && (int) $x->id_departement === (int) $d->id_departement ? 'selected' : ''; ?>><?php echo html_escape($d->departement); ?></option><?php } ?></select></td>
                        <td><input name="u[<?php echo $u->id_user; ?>][nip]" class="form-control form-control-sm" value="<?php echo html_escape($x ? $x->nip : ''); ?>" maxlength="30"></td></tr>
                    <?php } ?>
                    </tbody></table></div>
                <div class="card-footer d-flex justify-content-between align-items-center"><small class="text-muted">NIP menghubungkan akun dengan data pegawai HRD (untuk widget & laporan pribadi ke depan).</small><?php if ($bisa) { ?><button class="btn btn-sm btn-primary">Simpan pemetaan</button><?php } ?></div>
            </form>
        </div>
    </div>
</div>
