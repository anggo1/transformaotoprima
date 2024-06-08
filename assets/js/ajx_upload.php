<script type="text/javascript">window.onload = function() {
		File();
	}



	function refresh() {
		MyTable = $('#list-data').dataTable();
	}
	function effect_msg_form() {
		// $('.form-msg').hide();
		$('.form-msg').show(500);
		setTimeout(function() { $('.form-msg').fadeOut(500); }, 1000);
	}

	function effect_msg() {
		// $('.msg').hide();
		$('.msg').show(500);
		setTimeout(function() { $('.msg').fadeOut(500); }, 1000);
	}
 var MyTable = $('#list-data').dataTable({
		//"responsive": true,
		"paging": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
        "pageLength": 5   
		});

    function File() {
		$.get('<?php echo base_url('Upload/data_file'); ?>', function(data) {
			MyTable.fnDestroy();
			$('#data-upload').html(data);
			refresh();
		});
	}
</script>