<?php
if(!empty($dataOpname)){
foreach ($dataOpname as $st) {}}
?>
<div class="col-12 ">
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded" onclick="listOpnameDetail()"><i class="fa fa-indent"></i>  &nbsp;Detail</button>
    <button type="button" class="btn bg-gradient-navy shadow mb-3 rounded"  onclick="cetaklistOpnameDetail()"><i class="fa fa-print"></i>  &nbsp;CETAK DETAIL</button>
    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover nowrap" id="list-pk">
                                            <thead>
                                                <tr>
                                                    <th width='5%'>No</th>
                                                    <th>Kode</th>
                                                    <th>Tgl Opname</th>
                                                    <th>Kelompok</th>
                                                    <th>Keterangan</th>
                                                    <th>Status</th>
                                                    <th>Pembuat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
$no = 1;
foreach ($dataOpname as $s) {
?> <tr>

        <td><?php echo $no; ?></td>
        <td><?php echo $s->id_opname; ?></td>
        <td><?php echo tglIndoSedang($s->tgl_opname); ?></td>
        <td><?php if(empty($s->kelompok)){echo "Uncategorized";}else { echo $s->kelompok;} ?></td>
        <td><?php echo $s->keterangan; ?></td>
        <td><?php if($s->status=='N'){echo '<span class="right badge badge-danger">proses</span>';}else{echo'<span class="right badge badge-success">sukses</span>';} ?></td>
        <td><?php echo $s->pembuat; ?></td>

        <td class="text-left"width=15%>
            <button class="btn btn-xs btn-outline-primary cetak-list" data-id="<?php echo $s->id_opname; ?>"><i class="fa fa-eye"></i> View</button>
    </td>
    </tr>
<?php
    $no++;
}
?>

</tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
<script>
var MyTable = $('#list-pk').DataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [{
                extend: 'print',
                text: '<i class="fas fa-print"></i> Cetak',
                titleAttr: 'Print',
                title: 'Report Data Stok Opname',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
            exportOptions: {
                columns: [0, 1, 2, 3, 4,5,6]
            }
            }

        ],

        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 10
    });
    $(document).on("click", ".cetak-list", function() {
		var id = $(this).attr("data-id");
		//var id = document.getElementById('next_proses').value=datakode;
		$.ajax({
				method: "POST",
				url: "<?php echo base_url('ReportAccOpname/cetakListOpname'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				$('#modal-cetak-detail').html(data);
				$('#cetak-list-detail').modal('show');
			})
	})

    </script>