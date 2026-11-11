<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <table class="table table-sm table-hover" id="list-akun" style="width:100%">
        <thead>
            <tr><th>Kode</th><th>Nama Akun</th><th>Tipe Akun</th><th>Kelompok / Type</th><th class="text-center">Normal</th><th class="num">Saldo Hari Ini</th><th>Status</th><th class="text-right">Aksi</th></tr>
        </thead>
        <tbody>
        <?php foreach ($dataPk as $s) {
            $kat = isset($s->kategori) && $s->kategori !== '' ? $s->kategori : '';
            $sal = isset($saldo[(string) $s->kode_akun]) ? $saldo[(string) $s->kode_akun] : 0;
            $aktif = ! isset($s->is_active) || $s->is_active !== 'N';
            $indent = ! empty($s->parent_kode) ? 'padding-left:1.4rem' : ''; ?>
            <tr<?php echo $aktif ? '' : ' class="text-muted"'; ?>>
                <td style="<?php echo $indent; ?>"><?php echo html_escape($s->kode_akun); ?></td>
                <td><?php echo html_escape($s->nama_akun); ?><?php echo ! empty($s->parent_kode) ? '<div class="small text-muted">sub dari ' . html_escape($s->parent_kode) . '</div>' : ''; ?></td>
                <td data-order="<?php echo $kat !== '' && isset(Akuntansi::$KATEGORI[$kat]) ? Akuntansi::$KATEGORI[$kat][3] : 999; ?>"><?php echo $kat !== '' ? html_escape(Akuntansi::label_kategori($kat)) : '<span class="text-warning">belum diatur</span>'; ?></td>
                <td class="small"><?php echo html_escape(trim($s->nama_kelompok . ' / ' . $s->nama_type, ' /')); ?></td>
                <td class="text-center"><?php echo isset($s->saldo_normal) ? $s->saldo_normal : ''; ?></td>
                <td class="num" data-order="<?php echo $sal; ?>"><a href="<?php echo site_url('LaporanKeuangan/buku_besar?akun=' . rawurlencode($s->kode_akun) . '&dari=' . date('Y-01-01') . '&sampai=' . date('Y-m-d')); ?>"><?php echo nf_acc($sal); ?></a></td>
                <td><?php echo $aktif ? '<span class="acc-status acc-status--AKTIF">Aktif</span>' : '<span class="acc-status acc-status--BATAL">Nonaktif</span>'; ?></td>
                <td class="text-right text-nowrap">
                    <?php if ($boleh['edit']) { ?><button class="btn btn-xs btn-outline-primary update-akun" data-id="<?php echo $s->id; ?>" title="Ubah"><i class="fa fa-edit"></i></button><?php } ?>
                    <?php if ($boleh['delete']) { ?><button class="btn btn-xs btn-outline-danger delete-akun" data-id="<?php echo $s->id; ?>" data-kode="<?php echo html_escape($s->kode_akun); ?>" title="Hapus"><i class="fa fa-trash"></i></button><?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
$('#list-akun').DataTable({
    pageLength: 25, order: [[0, 'asc']], autoWidth: false,
    dom: "<'row align-items-center'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>t<'row align-items-center'<'col-sm-6'i><'col-sm-6'p>>",
    buttons: [
        { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', title: 'Daftar Akun', className: 'btn btn-sm btn-outline-success', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] } },
        { extend: 'print', text: '<i class="fas fa-print"></i> Cetak', title: 'Daftar Akun', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] } }
    ]
});
</script>
