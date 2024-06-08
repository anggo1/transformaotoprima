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
                        <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Data Buku Besar</h3>
                    </div>


                    <div class="card-body">

                        <div class="form-group row">
                            <label for="No Akun" class="col-sm-1 col-form-label">Kode
                                Acc</label>
                            <div class="col-sm-2">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" name="kode_akun" id="kode_akun" class="form-control" readonly>
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-info" data-toggle="modal"
                                            data-target="#modal_form"><i class="glyphicon glyphicon-plus-sign"><i
                                                    class="fa fa-search"></i></button></i>
                                    </span>
                                </div>
                            </div>
                            <label for="Nama Akun" class="col-sm-1 col-form-label">Nama
                                Acc</label>
                            <div class="col-sm-3">
                                <input type="text" name="nama_akun" id="nama_akun" readonly class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label">Tanggal Awal</label>
                            <div class="col-sm-2">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                    <input type="text" name="tgl_awal" id="tgl_awal"
                                        class="form-control tgl_awal datetimepicker" data-toggle="datetimepicker"
                                        data-target=".tgl_awal" data-format="yyy-mm-dd" required>

                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-sm-1 col-form-label">Tanggal Akhir</label>
                            <div class="col-sm-2">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                    <input type="text" name="tgl_akhir" id="tgl_akhir"
                                        class="form-control tgl_akhir datetimepicker" data-toggle="datetimepicker"
                                        data-target=".tgl_akhir" data-format="yyy-mm-dd" required>

                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn bg-gradient-primary col-sm-12" onclick="listBuku()"
                                    type="submit"><span class="fa fa-search"></span> Cari</button>
                            </div>
                        </div>
                        <div id="modal-cetak"></div>
                        <div id="data-buku">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body form">
                    <div class="card card-first card-outline">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-hover nowrap" id="list-akun">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No Akun</th>
                                            <th>Nama Akun</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    $no=0; foreach ($dataAkun as $a){ $no++; ?>
                                        <tr
                                            onclick="selectAkun('<?php echo $a->kode_akun; ?>','<?php echo $a->nama_akun; ?>')">
                                            <th><?php echo $no ?></th>
                                            <th><?php echo $a->kode_akun ?></th>
                                            <th><?php echo $a->nama_akun ?></th>
                                            </th>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $('#tgl_awal,#tgl_akhir').datetimepicker({
        format: 'DD-MM-YYYY',
        date: moment()
    });

    function refresh() {
        MyTable = $('#list-akun,#list-saldo,#list-saldo2,#list-global').dataTable();
    }
    var MyTable = $('#list-akun,#list-saldo,#list-saldo2,#list-global').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "pageLength": 10
    });

    function selectAkun(kode_akun, nama_akun) {
        $('[name = "kode_akun"]').val(kode_akun);
        $('[name = "nama_akun"]').val(nama_akun);

        //$('#modal-pk').modal('hide');
        $('#modal_form').modal('hide');
    }

    function listBuku() {
        var kode_akun = document.getElementById("kode_akun").value;
        var tgl_awal = document.getElementById("tgl_awal").value;
        var tgl_akhir = document.getElementById("tgl_akhir").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BukuBesar/showBuku'); ?>?kode_akun=' + kode_akun,
            data: 'kode_akun=' + kode_akun+'&tgl_awal=' + tgl_awal+'&tgl_akhir=' + tgl_akhir,
            success: function(hasil) {
                MyTable.fnDestroy();
                $('#data-buku').html(hasil);
                refresh();
            }
        });
    }
    $(document).on("click", ".update-saldo", function() {
        var id = $(this).attr("data-id");

        $.ajax({
                method: "POST",
                url: "<?php echo base_url('Saldo/updateSaldo'); ?>",
                data: "id=" + id
            })
            .done(function(data) {
                $('#tempat-modal').html(data);
                $('#update-saldo').modal('show');
            })
    })
    $(document).on('submit', '#form-update-saldo', function(e) {
        var data = $(this).serialize();

        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('Saldo/prosesUsaldo'); ?>',
                data: data
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                table.ajax.reload();
                if (out.status == 'form') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else {
                    document.getElementById("form-update-saldo").reset();
                    $('#update-saldo').modal('hide');
                    $('.msg').html(out.msg);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: out.msg,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })

        e.preventDefault();
    });
    function cetakBuku() {
		var date1 = document.getElementById("tgl_awal").value;
		var date2 = document.getElementById("tgl_akhir").value;
		$.ajax({
		type: 'GET',
		url: '<?php echo base_url('BukuBesar/cetakBuku'); ?>?date1=' +date1,
		data: 'date1=' +date1 +'&date2=' +date2,
			success:
            function(hasil) {
				$('#modal-cetak').html(hasil);
				$('#cetak-buku').modal('show');
                refresh();
			}
		});
	}
    function cetakBukuAkun() {
        var kode_akun = document.getElementById("kode_akun").value;
        var tgl_awal = document.getElementById("tgl_awal").value;
        var tgl_akhir = document.getElementById("tgl_akhir").value;
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('BukuBesar/cetakBukuAkun'); ?>?kode_akun=' + kode_akun,
            data: 'kode_akun=' + kode_akun+'&tgl_awal=' + tgl_awal+'&tgl_akhir=' + tgl_akhir,
			success:
            function(hasil) {
				$('#modal-cetak').html(hasil);
				$('#cetak-buku-akun').modal('show');
                refresh();
			}
		});
	}
    </script>