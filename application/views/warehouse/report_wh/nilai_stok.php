<?php defined('BASEPATH') OR exit('No direct script access allowed');
$data = array();
foreach ($rows as $r) {
    $min = is_numeric($r->stok_min) ? (float) $r->stok_min : 0;
    $data[] = array($r->no_part, trim($r->nama_part), $r->kategori, $r->satuan, $r->rak, $min, (float) $r->stok, (float) $r->harga, (float) $r->nilai, ($min > 0 && (float) $r->stok < $min) ? 1 : 0);
}
?>
<div class="card no-print">
    <div class="card-body py-3">
        <form class="acc-toolbar" method="get">
            <div class="form-group"><label class="mb-1">Lokasi gudang</label><select name="lokasi" class="form-control form-control-sm">
                <?php foreach ($lokList as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $f['lokasi'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
            <div class="form-group"><label class="mb-1">Kategori</label><select name="kategori" class="form-control form-control-sm"><option value="">Semua</option>
                <?php foreach ($kats as $k) { ?><option <?php echo $f['kategori'] === $k ? 'selected' : ''; ?>><?php echo html_escape($k); ?></option><?php } ?></select></div>
            <div class="form-group"><label class="mb-1">Tampilkan</label><select name="filter" class="form-control form-control-sm">
                <?php foreach (array('' => 'Semua item', 'ada' => 'Hanya yang ada stok', 'min' => 'Di bawah stok minimum', 'nol' => 'Stok kosong') as $k => $v) { ?><option value="<?php echo $k; ?>" <?php echo $f['filter'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php } ?></select></div>
            <div class="form-group"><button class="btn btn-primary btn-sm"><i class="fas fa-sync-alt mr-1"></i> Tampilkan</button></div>
        </form>
    </div>
</div>

<div class="acc-kpi">
    <div class="acc-kpi__item"><div class="acc-kpi__label">Jumlah item</div><div class="acc-kpi__value"><?php echo nf($tot['item']); ?></div><div class="acc-kpi__sub"><?php echo $lokList[$f['lokasi']]; ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Total kuantitas</div><div class="acc-kpi__value"><?php echo nf($tot['qty']); ?></div></div>
    <div class="acc-kpi__item"><div class="acc-kpi__label">Nilai persediaan</div><div class="acc-kpi__value"><?php echo nf($tot['nilai']); ?></div><div class="acc-kpi__sub">stok &times; harga net</div></div>
</div>

<div class="row">
    <div class="col-xl-3 col-lg-4">
        <div class="card"><div class="card-header"><h3 class="card-title">Per kategori</h3></div>
            <div class="card-body p-0"><table class="table table-sm mb-0"><thead><tr><th>Kategori</th><th class="num">Item</th><th class="num">Nilai</th></tr></thead><tbody>
            <?php foreach ($ringkas as $k => $v) { ?><tr><td><?php echo html_escape($k); ?></td><td class="num"><?php echo nf($v['item']); ?></td><td class="num"><?php echo nf($v['nilai']); ?></td></tr><?php } ?>
            </tbody><tfoot><tr><th>Total</th><th class="num"><?php echo nf($tot['item']); ?></th><th class="num"><?php echo nf($tot['nilai']); ?></th></tr></tfoot></table></div></div>
    </div>
    <div class="col-xl-9 col-lg-8">
        <div class="card"><div class="card-body">
            <table class="table table-sm table-hover" id="tbl-nilai-stok" style="width:100%">
                <thead><tr><th>No. Part</th><th>Nama</th><th>Kategori</th><th>Sat</th><th>Rak</th><th class="num">Min</th><th class="num">Stok</th><th class="num">Harga Net</th><th class="num">Nilai</th></tr></thead>
            </table>
        </div></div>
    </div>
</div>
<script>
$(function () {
    var data = <?php echo json_encode($data); ?>;
    var n = function (d, t) { return t === 'display' ? TOP.fmt(d) : d; };
    $('#tbl-nilai-stok').DataTable({
        data: data, deferRender: true, pageLength: 25, order: [[8, 'desc']], autoWidth: false,
        dom: "<'row align-items-center'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>t<'row align-items-center'<'col-sm-6'i><'col-sm-6'p>>",
        buttons: [
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', title: 'Nilai Persediaan <?php echo $lokList[$f['lokasi']]; ?>', className: 'btn btn-sm btn-outline-success' },
            { extend: 'print', text: '<i class="fas fa-print"></i> Cetak', title: 'Nilai Persediaan <?php echo $lokList[$f['lokasi']]; ?>', className: 'btn btn-sm btn-outline-secondary' }
        ],
        columns: [{}, {}, {}, {}, {}, { className: 'num', render: n }, { className: 'num', render: function (d, t, r) { return t === 'display' && r[9] ? '<span class="text-danger font-weight-bold" title="di bawah stok minimum">' + TOP.fmt(d) + ' <i class="fas fa-arrow-down"></i></span>' : n(d, t); } },
                  { className: 'num', render: n }, { className: 'num', render: n }]
    });
});
</script>
