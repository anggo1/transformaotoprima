<?php defined('BASEPATH') OR exit('No direct script access allowed');
$bln = (int) date('n'); $thnIni = (int) date('Y');
$masaLalu = (int) date('n', strtotime('first day of last month'));
$rowLalu = ($tahun === (int) date('Y', strtotime('first day of last month'))) ? $ppn['rows'][$masaLalu] : null;
$hariIni = date('Y-m-d');
$aksesSetor = $boleh['add'];
$labelRezim = $profil->rezim === 'FINAL_UMKM' ? 'PPh Final UMKM ' . nf($profil->tarif_final, 1) . '%' : 'PPh Badan umum (Ps. 17 + 31E)';
?>
<div class="acc-kpi">
    <div class="acc-kpi__item">
        <div class="acc-kpi__label">PPN masa <?php echo $rowLalu ? bulan_panjang($masaLalu) : '-'; ?></div>
        <div class="acc-kpi__value <?php echo $rowLalu && $rowLalu->kb > 0 ? 'text-danger' : ''; ?>"><?php echo $rowLalu ? ($rowLalu->kb > 0 ? nf_acc($rowLalu->kb) : nf_acc(-$rowLalu->lb)) : '-'; ?></div>
        <div class="acc-kpi__sub"><?php echo $rowLalu ? ($rowLalu->kb > 0 ? 'kurang bayar · setor s/d ' . tgl_singkat($rowLalu->jt_setor) : 'lebih bayar / nihil') : 'pilih tahun berjalan'; ?></div>
    </div>
    <div class="acc-kpi__item">
        <div class="acc-kpi__label">Hutang pajak belum disetor</div>
        <div class="acc-kpi__value <?php echo $hutang > 0 ? 'text-danger' : ''; ?>"><?php echo nf_acc($hutang); ?></div>
        <div class="acc-kpi__sub">saldo akun Hutang PPN/PPh per akhir <?php echo $tahun; ?></div>
    </div>
    <div class="acc-kpi__item">
        <div class="acc-kpi__label">Kredit pajak (dibayar dimuka)</div>
        <div class="acc-kpi__value"><?php echo nf_acc($dimuka); ?></div>
        <div class="acc-kpi__sub">PPh 22/23/25 — mengurangi PPh Badan</div>
    </div>
    <div class="acc-kpi__item">
        <div class="acc-kpi__label"><?php echo $rekon['final'] ? 'PPh Final ' . $tahun : 'Estimasi PPh Badan ' . $tahun; ?></div>
        <div class="acc-kpi__value"><?php echo nf_acc($rekon['final'] ? $rekon['pph_final'] : $rekon['terutang']); ?></div>
        <div class="acc-kpi__sub"><?php echo $rekon['final'] ? 'sudah disetor ' . nf($rekon['setor_final']) : ($rekon['pph29'] >= 0 ? 'PPh 29 kurang bayar ' . nf($rekon['pph29']) : 'lebih bayar ' . nf(-$rekon['pph29'])); ?></div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tp-ringkas"><i class="fas fa-home mr-1"></i>Ringkasan</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tp-ppn"><i class="fas fa-percent mr-1"></i>Masa PPN</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tp-setor"><i class="fas fa-money-check-alt mr-1"></i>Setoran Pajak</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tp-fiskal"><i class="fas fa-calculator mr-1"></i>Fiskal &amp; PPh Badan</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tp-set"><i class="fas fa-cog mr-1"></i>Pengaturan</a></li>
        </ul>
        <form class="acc-toolbar ml-auto" method="get"><div class="form-group mb-0"><label class="mb-1">Tahun pajak</label><input type="number" name="tahun" value="<?php echo $tahun; ?>" class="form-control form-control-sm" style="width:100px" onchange="this.form.submit()"></div></form>
    </div>
    <div class="card-body tab-content">

    <!-- ===================== RINGKASAN ===================== -->
    <div class="tab-pane fade show active" id="tp-ringkas">
        <div class="row">
            <div class="col-lg-5">
                <h6 class="text-uppercase text-muted small font-weight-bold">Profil pajak</h6>
                <table class="table table-sm mb-4">
                    <tr><td class="text-muted" style="width:40%">Wajib Pajak</td><td><strong><?php echo html_escape($profil->nama_wp ?: '— belum diisi —'); ?></strong></td></tr>
                    <tr><td class="text-muted">NPWP</td><td><?php echo html_escape($profil->npwp ?: '— belum diisi —'); ?></td></tr>
                    <tr><td class="text-muted">Status PPN</td><td><?php echo $profil->pkp ? '<span class="acc-status acc-status--AKTIF">PKP</span> tarif efektif ' . nf($profil->ppn_persen, 0) . '%' : '<span class="acc-status acc-status--BATAL">Non-PKP</span>'; ?></td></tr>
                    <tr><td class="text-muted">Rezim PPh</td><td><?php echo $labelRezim; ?></td></tr>
                </table>
                <h6 class="text-uppercase text-muted small font-weight-bold">Kewajiban bulan ini (<?php echo bulan_panjang($bln) . ' ' . $thnIni; ?>)</h6>
                <ul class="list-group mb-4">
                <?php foreach ($kalender as $k) { $lewat = $k[0] < $hariIni; ?>
                    <li class="list-group-item d-flex align-items-start py-2">
                        <span class="badge <?php echo $lewat ? 'badge-secondary' : 'badge-warning'; ?> mr-2" style="min-width:58px"><?php echo tgl_singkat($k[0]); ?></span>
                        <span class="small <?php echo $lewat ? 'text-muted' : ''; ?>"><?php echo html_escape($k[1]); ?></span>
                    </li>
                <?php } ?>
                </ul>
                <div class="small text-muted mb-3"><i class="fas fa-info-circle"></i> Tanggal mengikuti ketentuan umum (PMK 81/2024 / Coretax). Bila jatuh pada hari libur, batas waktu bergeser ke hari kerja berikutnya. Selalu cek pengumuman DJP terbaru.</div>
            </div>
            <div class="col-lg-7">
                <h6 class="text-uppercase text-muted small font-weight-bold">Saldo akun pajak <?php echo $tahun; ?></h6>
                <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead><tr><th>Akun</th><th class="num">Saldo awal</th><th class="num">Bertambah</th><th class="num">Berkurang</th><th class="num">Saldo akhir</th></tr></thead>
                    <tbody>
                    <?php foreach ($buku as $b) { ?>
                        <tr>
                            <td><a href="<?php echo site_url('LaporanKeuangan/buku_besar?akun=' . rawurlencode($b->kode) . '&dari=' . $tahun . '-01-01&sampai=' . $tahun . '-12-31'); ?>"><?php echo html_escape($b->kode . ' ' . $b->nama); ?></a><div class="small text-muted"><?php echo $b->saldo_normal === 'K' ? 'kewajiban (harus disetor)' : 'hak / kredit pajak'; ?></div></td>
                            <td class="num"><?php echo nf($b->awal); ?></td><td class="num"><?php echo nf($b->tambah); ?></td><td class="num"><?php echo nf($b->kurang); ?></td>
                            <td class="num"><strong><?php echo nf_acc($b->akhir); ?></strong></td>
                        </tr>
                    <?php } ?>
                    <?php if ( ! $buku) { ?><tr><td colspan="5" class="text-center text-muted">Akun pajak belum diatur — buka tab Pengaturan.</td></tr><?php } ?>
                    </tbody>
                </table>
                </div>
                <h6 class="text-uppercase text-muted small font-weight-bold mt-3">Laporan pajak</h6>
                <div class="acc-hub">
                    <a href="<?php echo site_url('LaporanKeuangan/pajak_tahunan?tahun=' . $tahun); ?>"><i class="fas fa-receipt"></i><div><strong>Ringkasan Pajak Tahunan</strong><span>Omzet, PPN, PPh per bulan + setoran</span></div></a>
                    <a href="<?php echo site_url('LaporanKeuangan/ppn?tahun=' . $tahun); ?>"><i class="fas fa-percent"></i><div><strong>Rekap PPN & Daftar Faktur</strong><span>Keluaran vs Masukan per masa</span></div></a>
                    <a href="<?php echo site_url('LaporanKeuangan/pph?tahun=' . $tahun); ?>"><i class="fas fa-file-signature"></i><div><strong>Buku Pembantu PPh</strong><span>Mutasi akun pajak & bukti potong</span></div></a>
                    <a href="<?php echo site_url('LaporanKeuangan/rekon_fiskal?tahun=' . $tahun); ?>"><i class="fas fa-calculator"></i><div><strong>Rekonsiliasi Fiskal</strong><span>Laba komersial → PKP → PPh Badan</span></div></a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MASA PPN ===================== -->
    <div class="tab-pane fade" id="tp-ppn">
        <p class="text-muted small mb-2">Angka diambil dari jurnal akun <b>PPN Keluaran</b> (<?php echo html_escape($ppn['akun_k'] ?: '-'); ?>) dan <b>PPN Masukan</b> (<?php echo html_escape($ppn['akun_m'] ?: '-'); ?>). <b>Tutup masa</b> membuat jurnal kompensasi pada akhir bulan: PPN Keluaran didebit, PPN Masukan dikredit, selisihnya menjadi <b>Hutang PPN</b> (kurang bayar) atau tetap di PPN Masukan (lebih bayar, dikompensasikan ke masa berikutnya). Tutup masa harus berurutan.</p>
        <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead><tr><th>Masa</th><th class="num">DPP Keluaran</th><th class="num">PPN Keluaran</th><th class="num">DPP Masukan</th><th class="num">PPN Masukan</th><th class="num">Kompensasi</th><th class="num">Kurang Bayar</th><th class="num">Lebih Bayar</th><th class="num">Disetor</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($ppn['rows'] as $r) { $sudahLewat = $r->periode < date('Y-m'); ?>
                <tr>
                    <td><a href="<?php echo site_url('LaporanKeuangan/ppn?tahun=' . $tahun . '&masa=' . $r->bulan); ?>"><?php echo bulan_singkat($r->bulan); ?></a></td>
                    <td class="num"><?php echo nf($r->dpp_k); ?></td><td class="num"><?php echo nf($r->ppn_k); ?></td>
                    <td class="num"><?php echo nf($r->dpp_m); ?></td><td class="num"><?php echo nf($r->ppn_m); ?></td>
                    <td class="num text-muted"><?php echo nf($r->kompensasi); ?></td>
                    <td class="num <?php echo $r->kb > 0 ? 'text-danger font-weight-bold' : ''; ?>"><?php echo nf($r->kb); ?></td>
                    <td class="num"><?php echo nf($r->lb); ?></td>
                    <td class="num"><?php echo nf($r->setor); ?></td>
                    <td><?php if ($r->tutup) { ?><span class="acc-status acc-status--LUNAS"><i class="fas fa-lock mr-1"></i>Ditutup</span><?php } elseif ($sudahLewat && ($r->ppn_k > 0 || $r->ppn_m > 0)) { ?><span class="acc-status acc-status--SEBAGIAN">Belum ditutup</span><?php } else { ?><span class="text-muted small">—</span><?php } ?></td>
                    <td class="text-right text-nowrap">
                        <?php if ( ! $r->tutup && $boleh['add'] && ($r->ppn_k > 0 || $r->ppn_m > 0)) { ?>
                        <a href="#" class="btn btn-xs btn-outline-primary" data-acc-post="<?php echo site_url('Pajak/tutup_ppn'); ?>" data-param='{"periode":"<?php echo $r->periode; ?>"}' data-konfirmasi="Tutup masa PPN <?php echo bulan_panjang($r->bulan) . ' ' . $tahun; ?>?" data-teks="Jurnal kompensasi PPN Keluaran vs Masukan dibuat per <?php echo tgl_id(date('Y-m-t', strtotime($r->periode . '-01'))); ?>." data-tombol="Ya, tutup masa"><i class="fas fa-lock"></i> Tutup</a>
                        <?php } elseif ($r->tutup && $boleh['delete']) { ?>
                        <a href="#" class="btn btn-xs btn-outline-danger" data-acc-post="<?php echo site_url('Pajak/batal_ppn'); ?>" data-param='{"periode":"<?php echo $r->periode; ?>"}' data-konfirmasi="Batalkan tutup masa PPN <?php echo bulan_panjang($r->bulan); ?>?" data-tombol="Ya, batalkan"><i class="fas fa-undo"></i></a>
                        <?php } ?>
                        <?php if ($r->kb > $r->setor && $aksesSetor) { ?><a href="#" class="btn btn-xs btn-outline-success" data-acc-modal="<?php echo site_url('Pajak/form_setor?jenis=PPN&masa=' . $r->periode); ?>" title="Setor PPN"><i class="fas fa-money-check-alt"></i></a><?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot><tr class="row-total"><td>Total</td><td class="num"><?php echo nf($ppn['total']->dpp_k); ?></td><td class="num"><?php echo nf($ppn['total']->ppn_k); ?></td><td class="num"><?php echo nf($ppn['total']->dpp_m); ?></td><td class="num"><?php echo nf($ppn['total']->ppn_m); ?></td><td></td><td class="num"><?php echo nf($ppn['total']->kb); ?></td><td></td><td class="num"><?php echo nf($ppn['total']->setor); ?></td><td colspan="2"></td></tr></tfoot>
        </table>
        </div>
    </div>

    <!-- ===================== SETORAN ===================== -->
    <div class="tab-pane fade" id="tp-setor">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <p class="text-muted small mb-0">Setiap setoran (billing → bayar → NTPN) dicatat di sini. Jurnal otomatis: <b>Debit</b> akun hutang pajak / PPh dibayar dimuka, <b>Kredit</b> Kas/Bank.</p>
            <?php if ($aksesSetor) { ?><button class="btn btn-primary btn-sm" data-acc-modal="<?php echo site_url('Pajak/form_setor'); ?>"><i class="fas fa-plus mr-1"></i> Catat Setoran</button><?php } ?>
        </div>
        <div class="table-responsive">
        <table class="table table-sm table-hover acc-dt">
            <thead><tr><th>Tanggal</th><th>No. Bukti</th><th>Jenis</th><th>Masa</th><th>NTPN</th><th>Dari akun</th><th class="num">Jumlah</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($setor as $s) { ?>
                <tr>
                    <td data-order="<?php echo $s->tanggal; ?>"><?php echo tgl_singkat($s->tanggal); ?></td>
                    <td><?php echo html_escape($s->no_bukti); ?><div class="small text-muted"><?php echo html_escape($s->no_ref_jurnal); ?></div></td>
                    <td><?php echo html_escape(isset(Akuntansi::$JENIS_PAJAK[$s->jenis]) ? Akuntansi::$JENIS_PAJAK[$s->jenis][0] : $s->jenis); ?></td>
                    <td><?php echo html_escape($s->masa); ?></td>
                    <td><?php echo html_escape($s->ntpn ?: '-'); ?></td>
                    <td class="small"><?php echo html_escape($s->kode_akun_kas . (isset($akun[$s->kode_akun_kas]) ? ' ' . $akun[$s->kode_akun_kas]->nama_akun : '')); ?></td>
                    <td class="num"><?php echo nf($s->jumlah); ?></td>
                    <td class="text-right"><?php if ($boleh['delete']) { ?><a href="#" class="btn btn-xs btn-outline-danger" data-acc-post="<?php echo site_url('Pajak/hapus_setor'); ?>" data-param='{"id":<?php echo (int) $s->id; ?>}' data-konfirmasi="Hapus setoran <?php echo html_escape($s->no_bukti); ?>?" data-tombol="Ya, hapus"><i class="fas fa-trash"></i></a><?php } ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
    </div>

    <!-- ===================== FISKAL ===================== -->
    <div class="tab-pane fade" id="tp-fiskal">
        <div class="row">
            <div class="col-lg-6">
                <h6 class="text-uppercase text-muted small font-weight-bold">Perhitungan PPh Badan <?php echo $tahun; ?> (ringkas)</h6>
                <table class="table table-sm">
                    <tr><td>Laba (rugi) komersial sebelum pajak</td><td class="num"><?php echo nf_acc($rekon['laba_komersial']); ?></td></tr>
                    <tr><td>(+) Koreksi fiskal positif</td><td class="num"><?php echo nf($rekon['total_positif']); ?></td></tr>
                    <tr><td>(−) Koreksi fiskal negatif</td><td class="num">(<?php echo nf($rekon['total_negatif']); ?>)</td></tr>
                    <tr class="row-total"><td>Penghasilan Kena Pajak (dibulatkan ribuan)</td><td class="num"><?php echo nf($rekon['hitung']->pkp); ?></td></tr>
                    <tr><td>Peredaran bruto</td><td class="num"><?php echo nf($rekon['bruto']); ?></td></tr>
                    <?php if ($rekon['final']) { ?>
                    <tr class="row-total"><td>PPh Final UMKM <?php echo nf($profil->tarif_final, 1); ?>% × bruto</td><td class="num"><?php echo nf($rekon['pph_final']); ?></td></tr>
                    <tr><td>Sudah disetor (jenis PPh Final)</td><td class="num"><?php echo nf($rekon['setor_final']); ?></td></tr>
                    <?php } else { ?>
                    <tr class="row-total"><td>PPh terutang <div class="small text-muted"><?php echo html_escape($rekon['hitung']->fasilitas); ?></div></td><td class="num"><?php echo nf($rekon['terutang']); ?></td></tr>
                    <tr><td>(−) Kredit pajak PPh 22/23/25</td><td class="num">(<?php echo nf($rekon['total_kredit']); ?>)</td></tr>
                    <tr class="row-grand"><td><?php echo $rekon['pph29'] >= 0 ? 'PPh Pasal 29 kurang bayar' : 'PPh lebih bayar'; ?></td><td class="num"><?php echo nf(abs($rekon['pph29'])); ?></td></tr>
                    <tr><td>Estimasi angsuran PPh 25 tahun berikutnya / bulan</td><td class="num"><?php echo nf($rekon['pph25']); ?></td></tr>
                    <?php } ?>
                </table>
                <div class="d-flex flex-wrap" style="gap:.4rem">
                    <a href="<?php echo site_url('LaporanKeuangan/rekon_fiskal?tahun=' . $tahun); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-alt mr-1"></i> Laporan rekonsiliasi lengkap</a>
                    <?php if ( ! $rekon['final']) { if ( ! $rekon['jurnal'] && $boleh['add']) { ?>
                    <button class="btn btn-sm btn-primary" data-acc-post="<?php echo site_url('Pajak/jurnal_pph_badan'); ?>" data-param='{"tahun":<?php echo $tahun; ?>}' data-konfirmasi="Buat jurnal PPh Badan <?php echo $tahun; ?>?" data-teks="Per 31 Desember: Debit Beban PPh Badan <?php echo nf($rekon['terutang']); ?>, Kredit PPh dibayar dimuka & Hutang PPh 29. Lakukan SEBELUM jurnal penutup." data-tombol="Ya, buat"><i class="fas fa-file-invoice mr-1"></i> Buat Jurnal PPh Badan</button>
                    <?php } elseif ($rekon['jurnal']) { ?>
                    <span class="acc-status acc-status--LUNAS align-self-center">Jurnal PPh Badan: <?php echo html_escape($rekon['jurnal']); ?></span>
                    <?php if ($boleh['delete']) { ?><button class="btn btn-sm btn-outline-danger" data-acc-post="<?php echo site_url('Pajak/batal_pph_badan'); ?>" data-param='{"tahun":<?php echo $tahun; ?>}' data-konfirmasi="Hapus jurnal PPh Badan <?php echo $tahun; ?>?"><i class="fas fa-undo"></i></button><?php } ?>
                    <?php } } ?>
                </div>

                <h6 class="text-uppercase text-muted small font-weight-bold mt-4">Koreksi fiskal manual <?php echo $tahun; ?></h6>
                <table class="table table-sm">
                    <thead><tr><th>Jenis</th><th>Keterangan</th><th class="num">Nilai</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($koreksi as $k) { ?>
                        <tr><td><span class="acc-status acc-status--<?php echo $k->jenis === 'POSITIF' ? 'SEBAGIAN' : 'AKTIF'; ?>"><?php echo $k->jenis === 'POSITIF' ? '+ Positif' : '− Negatif'; ?></span></td><td><?php echo html_escape($k->keterangan); ?></td><td class="num"><?php echo nf($k->nilai); ?></td>
                            <td class="text-right"><?php if ($boleh['delete']) { ?><a href="#" class="text-danger" data-acc-post="<?php echo site_url('Pajak/hapus_koreksi'); ?>" data-param='{"id":<?php echo (int) $k->id; ?>}' data-konfirmasi="Hapus koreksi ini?"><i class="fas fa-trash"></i></a><?php } ?></td></tr>
                    <?php } if ( ! $koreksi) { ?><tr><td colspan="4" class="text-muted text-center small">Belum ada koreksi manual.</td></tr><?php } ?>
                    </tbody>
                </table>
                <?php if ($boleh['add']) { ?>
                <form class="acc-ajax border rounded p-2 bg-light" action="<?php echo site_url('Pajak/simpan_koreksi'); ?>" method="post">
                    <input type="hidden" name="tahun" value="<?php echo $tahun; ?>">
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2"><select name="jenis" class="form-control form-control-sm"><option value="POSITIF">+ Positif</option><option value="NEGATIF">− Negatif</option></select></div>
                        <div class="form-group col-md-5 mb-2"><input type="text" name="keterangan" class="form-control form-control-sm" placeholder="mis. Selisih penyusutan fiskal" required></div>
                        <div class="form-group col-md-3 mb-2"><input type="text" name="nilai" class="form-control form-control-sm input-angka text-right" placeholder="Nilai" required></div>
                        <div class="form-group col-md-1 mb-2"><button class="btn btn-sm btn-primary btn-block"><i class="fas fa-plus"></i></button></div>
                    </div>
                    <div class="small text-muted">Gunakan untuk koreksi yang tidak bisa ditandai per akun, misalnya selisih penyusutan komersial vs fiskal, sebagian biaya entertainment tanpa daftar nominatif, atau kompensasi kerugian tahun lalu (negatif).</div>
                </form>
                <?php } ?>
            </div>
            <div class="col-lg-6">
                <h6 class="text-uppercase text-muted small font-weight-bold">Perlakuan fiskal per akun laba rugi</h6>
                <form class="acc-ajax" action="<?php echo site_url('Pajak/simpan_fiskal'); ?>" method="post">
                <div class="table-responsive" style="max-height:520px;overflow:auto">
                <table class="table table-sm table-hover">
                    <thead><tr><th>Akun</th><th style="width:55%">Perlakuan fiskal</th></tr></thead>
                    <tbody>
                    <?php foreach ($akun as $a) { if ( ! in_array(Akuntansi::grup_kategori($a->kategori), array('PENDAPATAN', 'BEBAN'), true) || $a->kategori === 'PAJAK_PENGHASILAN') continue; $fk = isset($a->fiskal) ? $a->fiskal : ''; ?>
                        <tr><td class="small"><?php echo html_escape($a->kode_akun . ' ' . $a->nama_akun); ?><div class="text-muted"><?php echo html_escape(Akuntansi::label_kategori($a->kategori)); ?></div></td>
                            <td><select name="fiskal[<?php echo html_escape($a->kode_akun); ?>]" class="form-control form-control-sm">
                                <?php foreach (Akuntansi::$FISKAL as $k => $x) { ?><option value="<?php echo $k; ?>" <?php echo $fk === $k ? 'selected' : ''; ?>><?php echo $k === '' ? 'Normal' : ($k === 'POSITIF' ? 'Tidak boleh dibebankan (+)' : 'Final / bukan objek (−)'); ?></option><?php } ?>
                            </select></td></tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
                <?php if ($boleh['edit']) { ?><button class="btn btn-sm btn-primary mt-2"><i class="fas fa-save mr-1"></i> Simpan perlakuan fiskal</button><?php } ?>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== PENGATURAN ===================== -->
    <div class="tab-pane fade" id="tp-set">
        <form class="acc-ajax" action="<?php echo site_url('Pajak/simpan_setting'); ?>" method="post">
            <div class="form-row">
            <?php foreach ($pref as $k => $x) { $val = isset($setting[$k]) ? $setting[$k] : ''; ?>
                <div class="form-group col-md-6 col-lg-4">
                    <label class="mb-1"><?php echo $x[0]; ?></label>
                    <?php if ($x[1] === 'text' || $x[1] === 'number') { ?>
                        <input type="<?php echo $x[1]; ?>" name="<?php echo $k; ?>" class="form-control form-control-sm" value="<?php echo html_escape($val); ?>" step="any">
                    <?php } elseif ($x[1] === 'yn') { ?>
                        <select name="<?php echo $k; ?>" class="form-control form-control-sm"><option value="Y" <?php echo $val !== 'N' ? 'selected' : ''; ?>>Ya</option><option value="N" <?php echo $val === 'N' ? 'selected' : ''; ?>>Tidak</option></select>
                    <?php } elseif ($x[1] === 'rezim') { ?>
                        <select name="<?php echo $k; ?>" class="form-control form-control-sm"><option value="UMUM" <?php echo $val !== 'FINAL_UMKM' ? 'selected' : ''; ?>>UMUM — PPh Badan Pasal 17 + 31E</option><option value="FINAL_UMKM" <?php echo $val === 'FINAL_UMKM' ? 'selected' : ''; ?>>FINAL UMKM — 0,5% dari omzet</option></select>
                    <?php } else { ?>
                        <select name="<?php echo $k; ?>" class="form-control form-control-sm select2"><?php echo $this->load->view('accounting/trx/_akun_options', array('akun' => $akun, 'pilih' => $val, 'hanya' => array()), TRUE); ?></select>
                    <?php } ?>
                    <?php if ($x[2] !== '') { ?><small class="form-text text-muted"><?php echo html_escape($x[2]); ?></small><?php } ?>
                </div>
            <?php } ?>
            </div>
            <?php if ($boleh['edit']) { ?><button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Pengaturan Pajak</button><?php } ?>
        </form>
    </div>

    </div>
</div>
<script src="<?php echo base_url('assets/js/top-acc.js?v=2.0.0'); ?>"></script>
<script>
$(function () {
    ACC.init($('#tp-set'));
    // ingat tab terakhir
    var key = 'top_pajak_tab';
    try { var t = localStorage.getItem(key); if (t && $('a[href="' + t + '"]').length) $('a[href="' + t + '"]').tab('show'); } catch (e) {}
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) { try { localStorage.setItem(key, $(e.target).attr('href')); } catch (x) {} });
    if (location.hash && $('a[href="' + location.hash + '"]').length) $('a[href="' + location.hash + '"]').tab('show');
});
</script>
