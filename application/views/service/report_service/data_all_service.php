<style>
<!-- HTML !-->
<button class="button-88" role="button">Button 88</button>

/* CSS */
.button-88 {
  display: flex;
  align-items: center;
  font-family: inherit;
  font-weight: 500;
  font-size: 16px;
  padding: 0.7em 1.4em 0.7em 1.1em;
  color: white;
  background: #ad5389;
  background: linear-gradient(0deg, rgba(20,167,62,1) 0%, rgba(102,247,113,1) 100%);
  border: none;
  box-shadow: 0 0.7em 1.5em -0.5em #14a73e98;
  letter-spacing: 0.05em;
  border-radius: 20em;
  cursor: pointer;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-88:hover {
  box-shadow: 0 0.5em 1.5em -0.5em #14a73e98;
}

.button-88:active {
  box-shadow: 0 0.3em 1em -0.5em #14a73e98;
}
    </style>
<div class="col-12 ">
  
    <div class="table-responsive">
        <table class="table table-bordered table-hover nowrap" id="list-pk">
            <thead>
                <tr>
                    <th width='5%'>No</th>
                    <th>Wo No</th>
                    <th>SA</th>
                    <th>Date Open</th>
                    <th>VIN</th>
                    <th>Konsumen</th>
                    <th>Complain</th>
                    <th>Engine No</th>
                    <th>Type</th>
                    <th>Last Service</th>
                    <th>Deadline</th>
                    <th>Date Close</th>
                    <th>Status</th>
                    <th>Pembuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($dataPo as $s) {
                ?> <tr>

                        <td><?php echo $no; ?></td>
                        <td><?php echo $s->wo_no; ?></td>
                        <td><?php echo $s->sa_name; ?></td>
                        <td><?php echo tglIndoSedang($s->date_open_wo); ?></td>
                        <td><?php echo $s->vin; ?></td>
                        <td><?php echo $s->customer_name; ?></td>
                        <td><?php echo $s->customer_complain; ?></td>
                        <td><?php echo $s->engine_no; ?></td>
                        <td><?php echo $s->type; ?></td>
                        <td><?php echo tglIndoSedang($s->last_service_date); ?></td>
                        <td><?php echo tglIndoSedang($s->dead_line); ?></td>
                        <td><?php echo tglIndoSedang($s->date_close_wo); ?></td>
                        <td><?php echo ($s->status == 'Y') ? 'Free' : 'Non Free'; ?></td>
                        <td><?php echo $s->pembuat; ?></td>
                        <td>
                        <button class="btn btn-xs bg-gradient-primary cetak-jobtime" id="cetakjobtime" title="Cetak Jobtime"
                            data-id="<?php echo $s->wo_no; ?>"><i class="fa fa-print"></i> Job Time</button>
                        <button class="btn btn-xs bg-gradient-dark cetak-workshop" title="Cetak Workshop"
                            data-id="<?php echo $s->wo_no; ?>"><i class="fa fa-print"></i> Workshop
                        </button></td>

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
                extend: 'copyHtml5',
                text: '<i class="fas fa-copy"></i> Copy',
                titleAttr: 'Copy',
                title: 'Report After Sales',
                className: 'btn btn-sm  btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4,5,6,7,8,9,10,11]
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel',
                title: 'Report After Sales',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4,5,6,7,8,9,10,11]
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'PDF',
                title: 'Report After Sales',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4,5,6,7,8,9,10,11]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Cetak',
                titleAttr: 'Print',
                title: 'Report After Sales',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4,5,6,7,8,9,10,11]
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-eye"></i> Tampilan',
                titleAttr: 'Costum Tampilan',
                className: 'btn btn-outline-secondary',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
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
    $(document).on("click", ".cetak-keluar", function() {
        var id = $(this).attr("data-id");
        //var id = document.getElementById('next_proses').value=datakode;
        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Part_keluar/cetak'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#modal-keluar').html(data);
                $('#cetak-keluar').modal('show');
            })
    })


    function exportExcel() {
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'POST',
		url: '<?php echo base_url('ReportService/export_excel'); ?>?',
		data: 'date1=' +date1+'&date2=' +date2
		});
	}
    function exportTableToExcel() {
    // Select the HTML table
    var el = document.getElementById('myTable');
    
    // Convert table to worksheet
    var wb = XLSX.utils.table_to_book(el, {sheet: "SheetJS"});
    
    // Save/Download the file
    XLSX.writeFile(wb, 'exported_data.xlsx');
}
</script>