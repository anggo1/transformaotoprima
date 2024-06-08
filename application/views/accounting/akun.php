<style>
.table.dataTable {
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
                    <div class="col-lg-12">
                        <div class="card ">
                            <div class="modal-content">
                                <div class="card-header card-blue card-outline">
                                    <h3 class="card-title"><i class="ion-ios-cog ion-lg text-blue"></i> &nbsp; Daftar
                                        Akun</h3>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                            data-target="#tambah-akun" title="Add Data"><i class="fas fa-plus"></i>
                                            Tambah Data</button>

                                    </div>
                                </div>
                                <div class="modal-body form">
                                    <div class="card card-first card-outline">
                                        <div class="card-body">
                                            <div id="data-acc"></div>
                                        </div>
                                            <div id="modal-acc"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
show_my_confirm('hapusAkun', 'hapus-akun', 'Menghapus Data Akun Mungkin akan mempengaruhi beberapa data yang telah di input, anda yakin untuk menghapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data');
?>
            <script type="text/javascript">
            window.onload = function() {
                showAcc();
            }

            function refresh() {
                MyTable = $('#list-laporan,#list-kategori,#list-pk').DataTable();
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
                MyTable = $('#list-akun').dataTable();
            }
            var MyTable = $('#list-akun').dataTable({
                "responsive": false,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 5
            });

            function showAcc() {
                $.get('<?php echo base_url('Account/showAcc'); ?>', function(data) {
                    MyTable.fnDestroy();
                    $('#data-acc').html(data);
                    refresh();
                });
            }


            $('#form-tambah-akun').submit(function(e) {
                var data = $(this).serialize();

                $.ajax({
                        method: 'POST',
                        url: '<?php echo base_url('Account/prosesTacc'); ?>',
                        data: data
                    })
                    .done(function(data) {
                        var out = jQuery.parseJSON(data);
                        showAcc();
                        if (out.status == 'form') {
                            $('.form-msg').html(out.msg);
                            effect_msg_form();
                        } else {
                            document.getElementById("form-tambah-akun").reset();
                            $('#tambah-akun').modal('hide');
                            $('.msg').html(out.msg);
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: out.msg,
                                showConfirmButton: false,
                                timer: 1000
                            })
                        }
                    })

                e.preventDefault();
            });

            $(document).on("click", ".update-akun", function() {
                var id = $(this).attr("data-id");

                $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('Account/updateAkun'); ?>",
                        data: "id=" + id
                    })
                    .done(function(data) {
                        $('#modal-acc').html(data);
                        $('#update-akun').modal('show');
                    })
            })
            $(document).on('submit', '#form-update-akun', function(e) {
                var data = $(this).serialize();

                $.ajax({
                        method: 'POST',
                        url: '<?php echo base_url('Account/prosesUakun'); ?>',
                        data: data
                    })
                    .done(function(data) {
                        var out = jQuery.parseJSON(data);
                        if (out.status == 'form') {
                            $('.form-msg').html(out.msg);
                            effect_msg_form();
                        } else {
                            showAcc();
                            document.getElementById("form-update-akun").reset();
                            $('#update-akun').modal('hide');
                            $('.msg').html(out.msg);
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: out.msg,
                                showConfirmButton: false,
                                timer: 1000
                            })
                        }
                    })

                e.preventDefault();
            });

            $('#tambah-akun').on('hidden.bs.modal', function() {
                $('.form-msg').html('');
            })

            $('#update-akun').on('hidden.bs.modal', function() {
                $('.form-msg').html('');
            })
            $(document).on("click", ".delete-akun", function() {
                id_part = $(this).attr("data-id");
            })
            $(document).on("click", ".hapus-akun", function() {
                var id = id_part;

                $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('Account/deleteAkun'); ?>",
                        data: "id=" + id
                    })

                    .done(function(data) {
                        var out = jQuery.parseJSON(data);
                        $('.msg').html(out.msg);
                        showAcc();
                        $('#hapusAkun').modal('hide');
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