<style>
    .table.DataTable {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12px;
    }

    table.dataTable td {
        padding: 5px;
    }
</style>
<div class="row">
    <div class="col-12 ">
        <div class="content">
                        <div class="card">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Report Seluruh Biaya SPK</h3>
                                </div>
                                
                        
                    <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-1 col-form-label">No SPK</label>
									<div class="col-sm-2">
										
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
											<input type="text" name="no_spk" id="no_spk" placeholder="Surat Perintah Kerja" class="form-control">
										</div>
									</div>
									<div class="col-sm-1">
							<button class="btn bg-gradient-primary col-sm-12" onclick="listPart()" type="submit"><i class="fa fa-search"></i> Cari</button>
									</div>
						</div>
                                <div id="modal-pk"></div>
                          <div id="data-pk">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?php
show_my_confirm('startPk', 'start-pk', 'PK Dimulai', 'Ya, Mulai', 'Batal Mulai');
show_my_confirm('selesaiPk', 'selesai-pk', 'PK Telah Selesai?', 'Ya, Selesai', 'Batal');
?>
<script type="text/javascript">
    $('#tgl_awal,#tgl_akhir').datetimepicker({
		format: 'DD-MM-YYYY',
		date: moment()
	});
    function refresh() {
        MyTable = $('#list-data').dataTable();
    }

    function showPk() {
        $.get('<?php echo base_url('PartPk/showPk'); ?>', function(data) {
            MyTable.fnDestroy();
            $('#data-pk').html(data);
            refresh();
        });
    }

    function listPart() {
		var no_spk = document.getElementById("no_spk").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccSpk/listSPK'); ?>?no_spk=' +no_spk,
		data: 'no_spk=' +no_spk,
			success:
            function(hasil) {
			//MyTable.fnDestroy();
			$('#data-pk').html(hasil);
			//refresh();
			}
		});
	}
	function detailPart() {
		var no_spk = document.getElementById("no_spk").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccSpk/listSPKdetail'); ?>?no_spk=' +no_spk,
		data: 'no_spk=' +no_spk,
			success:
            function(hasil) {
			//MyTable.fnDestroy();
			$('#data-pk').html(hasil);
			//refresh();
			}
		});
	}
	function detailUpah() {
		var no_spk = document.getElementById("no_spk").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccSpk/listSPKdetail_upah'); ?>?no_spk=' +no_spk,
		data: 'no_spk=' +no_spk,
			success:
            function(hasil) {
			//MyTable.fnDestroy();
			$('#data-pk').html(hasil);
			//refresh();
			}
		});
	}

	function cetak_dataSpk() {
		var no_spk = document.getElementById("no_spk").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccSpk/cetak_listSPK'); ?>?no_spk=' +no_spk,
		data: 'no_spk=' +no_spk,
			success:
            function(hasil) {
				$('#modal-pk').html(hasil);
				$('#cetak-detail').modal('show');
			}
		});
	}
	function cetak_dataSpk_detail() {
		var no_spk = document.getElementById("no_spk").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccSpk/cetak_listSPKdetail'); ?>?no_spk=' +no_spk,
		data: 'no_spk=' +no_spk,
			success:
            function(hasil) {
				$('#modal-pk').html(hasil);
				$('#cetak-detail').modal('show');
			}
		});
	}
	function cetak_dataSpk_upah_detail() {
		var no_spk = document.getElementById("no_spk").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('ReportAccSpk/cetak_listSPKdetail_upah'); ?>?no_spk=' +no_spk,
		data: 'no_spk=' +no_spk,
			success:
            function(hasil) {
				$('#modal-pk').html(hasil);
				$('#cetak-detail').modal('show');
			}
		});
	}

</script>