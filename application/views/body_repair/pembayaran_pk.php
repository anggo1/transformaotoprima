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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Daftar
                                        Pembayaran Upah Pekerjaan</h3>
                                </div>
                                <div class="modal-body form">
                                    <div class="card card-first card-outline">
                                        <div class="card-body">
                                        <div class="form-group row">
									<label class="col-sm-2 col-form-label">No PK</label>
									<div class="col-sm-6">
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
											<input type="text" name="no_pk" id="no_pk" placeholder="Nomor Perintah Kerja" class="form-control">
										</div>
									</div>
                                    <div class="col-sm-4">
							<button class="btn bg-gradient-primary col-sm-12" onclick="listPart()" type="submit"><span class="fa fa-search"></span> Cari</button>
								</div>
									</div>
                                            <div class="col-12 ">
                                                        <div id="data-pk">
                                                        </div>
                                          
                                                        <div id="data-pk2">
                                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title">
                                        <i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Jumlah Pembayaran
                                    </h3>
                                </div>
                                <div class="modal-body">
                                    <div class="card card-first card-outline">
                                        <div class="card-body">
                                            <div class="col-12 ">
                                                <table width="100%" border="0" cellpadding="5" cellspacing="0"
                                                    class="datatablex">
                                                    <tbody>
                                                        <tr>
                                                            <td>No Body</td>
                                                            <td id="no_body"></td>
                                                            <td>ID PK</td>
                                                            <td id="id_pk">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Pekerjaan</td>
                                                            <td id="ket_pk">&nbsp;</td>
                                                            <td>Pemborong</td>
                                                            <td id="pj_borong">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Upah Borong</td>
                                                            <td id="biaya">&nbsp;</td>
                                                            <td>Sisa Pembayaran</td>
                                                            <td id="sisanye"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                                <button class="btn btn-sm btn-outline-primary bayar" id="bayar"><i class="fa fa-plus"></i> Tambah Pembayaran</button>
                                        </div><div id="data-bayar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
window.onload = function() {
    showPk();
}

function effect_msg_form() {
    // $('.form-msg').hide();
    $('.form-msg').show(500);
    setTimeout(function() {
        $('.form-msg').fadeOut(500);
    }, 1000);
}

function effect_msg() {
    // $('.msg').hide();
    $('.msg').show(500);
    setTimeout(function() {
        $('.msg').fadeOut(500);
    }, 1000);
}

function refresh() {
    MyTable = $('#list-pk-mulai').dataTable();
}
var MyTable = $('#list-pk-mulai,#table-body').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": true,
    "pageLength": 5
});
function listPart() {
		var no_pk = document.getElementById("no_pk").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('PembayaranPk/showPk'); ?>?no_pk=' +no_pk,
		data: 'no_pk=' +no_pk,
			success:
            function(hasil) {
			MyTable.fnDestroy();
			$('#data-pk').html(hasil);
			refresh();
			}
		});
	}

function showPk() {
    $.get('<?php echo base_url('PembayaranPk/showPk2'); ?>', function(data) {
        MyTable.fnDestroy();
        $('#data-pk2').html(data);
        refresh();
    });
}
function selectPk(no_body, id_pk, ket_pk, pj_borong,biaya,sisanye) {
    
    var input = document.getElementById("bayar");
    $(input).attr(
        {
        "id-pk": id_pk, 
        "no-body": no_body,
        "ket-pk": ket_pk,
        "pj-borong": pj_borong,
        "biaya": biaya,
        "sisanye": sisanye
        });
    document.getElementById('no_body').innerHTML = no_body.bold();
    document.getElementById('id_pk').innerHTML = id_pk.bold();
    document.getElementById('ket_pk').innerHTML = ket_pk.bold();
    document.getElementById('pj_borong').innerHTML = pj_borong.bold();
    document.getElementById('biaya').innerHTML = biaya.bold();
    document.getElementById('sisanye').innerHTML = sisanye.bold();
	tampilDetail();
    }

$(document).on("click", ".bayar", function() {
    var id_pk = $(this).attr("id-pk");
    var no_body = $(this).attr("no-body");
    var ket_pk = $(this).attr("ket-pk");
    var pj_borong = $(this).attr("pj-borong");
    var biaya = $(this).attr("biaya");

    if (id_pk === undefined){
Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'ID PK Belum ditentukan',
                    showConfirmButton: false,
                    timer: 900
                })
} else{
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('PembayaranPk/prosesBayar'); ?>",
            data: "id_pk=" + id_pk +
            "&no_body=" + no_body +
            "&ket_pk=" + ket_pk +
            "&pj_borong=" + pj_borong +
            "&biaya=" + biaya
        })
        .done(function(data) {
			tampilDetail(id_pk);
        })}
})

function tampilDetail() {
    var id_pk = $(".bayar").attr("id-pk");
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url('PembayaranPk/tampilDetail'); ?>?id_pk=' + id_pk,
			data: 'id_pk=' + id_pk,
			success: function(hasil) {
				MyTable.fnDestroy();
				$('#data-bayar').html(hasil);
				refresh();
			}
		});
	}
$(document).on("click", ".cetak-detail", function() {
    var id = $(this).attr("data-id");
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('ProsesPk/cetakDetail'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-pk').html(data);
            $('#cetak-detail').modal('show');
        })
})

$(document).on("click", ".delete-detail", function() {
		data_id = $(this).attr("data-id");
	})
	$(document).on("click", ".delete-detail", function() {
		var id = data_id;

		$.ajax({
				method: "POST",
				url: "<?php echo base_url('PembayaranPk/deleteDetail'); ?>",
				data: "id=" + id
			})
			.done(function(data) {
				var out = jQuery.parseJSON(data);
				if (out.status != 'form') {
					tampilDetail();
				}
			})
	})

function refreshDetail() {
    MyDetail = $('#list-detail').dataTable();
}
var MyDetail = $('#list-detail').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "pageLength": 5
});

function showDetail() {
    var id_pk = document.getElementById('id_pk').value;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('ProsesPk/showDetail'); ?>?id_pk=' + id_pk,
        data: 'id_pk=' + id_pk,
        success: function(hasil) {
            MyDetail.fnDestroy();
            refreshDetail();
            $('#detail-datapk').html(hasil);
        }
    });

}

$(document).on("click", ".detail-pk", function() {
    var id = $(this).attr("data-id");

    $
        .ajax({
            method: "POST",
            url: "<?php echo base_url('ProsesPk/Detail'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            $('#modal-pk').html(data);
            $('#detail-pk').modal('show');
            showDetail();
        })
})
$(document).on('submit', '#detailPkaktif', function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('ProsesPk/detailPk'); ?>',
            data: data
        })
        .done(function(data) {
            showDetail();
            var out = jQuery.parseJSON(data);
            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                document.getElementById("detailPkaktif").reset();
                // $('#detail-pk').modal('hide');

            }
        })

    e.preventDefault();
});
$(document).on("click", ".delete-detail", function() {
    id_lapor = $(this).attr("data-id");
})
$(document).on("click", ".hapus-detail", function() {
    var id = id_lapor;

    $
        .ajax({
            method: "POST",
            url: "<?php echo base_url('ProsesPk/deleteDetail'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);
            table
                .ajax
                .reload();
            $('.msg').html(out.msg);
            $('#hapusDetail').modal('hide');
            if (out.status != 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1200
                })
            }
        })
})

</script>