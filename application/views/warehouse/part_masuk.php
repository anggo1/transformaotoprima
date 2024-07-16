<style>
.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 12px;
}

table.dataTable td {
    padding-bottom: 5px;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <!-- /.card-header -->
                    <div class="modal-content">
                        <div class="modal-header text-blue">

                            <h5 style="display:block; text-align:center;"><span
                                    class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Penerimaan Barang PO
                            </h5>
                            <button type="button" class="btn btn-success" id="tambah" hidden="hidden"
                                onclick="window.location.reload();" title="Add Data"><i class="fas fa-plus"></i> Data
                                Baru</button>
                        </div>
                        <div class="modal-body">
                            <form id="formpartmasuk" name="formpartmasuk" method="POST">
                                <input type="hidden" name="id_masuk" id="id_masuk" class="form-control" readonly>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tanggal</label>
                                    <div class="col-sm-4">
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                            <input type="text" name="tgl_po" id="tgl_po"
                                                class="form-control tgl_po datetimepicker" data-toggle="datetimepicker"
                                                data-target=".tgl_po" data-format="yyy-mm-dd" required>

                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 col-form-label">No PO</label>
                                    <div class="col-sm-4">
                                        <input type="hidden" id="id_po" name="id_po" class="form-control"></input>
                                        <input type="text" id="no_po" name="no_po" class="form-control"
                                            onclick="showPo()" data-toggle="modal" data-target="#modal_po" placeholder="Klik Untuk Melihat Daftar PO"
                                            required></input>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">No SJ</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="no_sj_sup" id="no_sj_sup" class="form-control" placeholder="Surat Jalan Barang">
                                    </div>
                                    <label class="col-sm-2 col-form-label">No INV</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="no_inv_sup" id="no_inv_sup" class="form-control" placeholder="No Invoice">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Supplier</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="supplier" id="supplier" class="form-control" readonly>
                                        <input type="hidden" name="kode_sup" id="kode_sup" class="form-control">
                                    </div>
                                    <label class="col-sm-2 col-form-label">Keterangan</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan Barang Masuk PO">
                                    </div>
                                </div>
                                
                                <div class="row form-group row">
                                    <label class="col-sm-2 col-form-label">Stok Cabang</label>
                                    <div class="col-sm-4">
                                        <select name="lokasi" id="lokasi" class="form-control" <?php  $lvl = $this->session->userdata['id_level']; 
                                        if ($lvl !='1' && $lvl !='12'){ echo 'disabled';} ?>>
                                            <option value="">Cabang Dealer...
                                            </option>
                                            <?php
                                            $lok = $this->session->userdata['lokasi'];
                                                                    foreach ($dataKota as $kel) { ?>
                                                                <option
                                                                    value="<?php echo $kel->kode_kota.'|'.$kel->nama_kota; ?>"
                                                                    <?php if ($kel->nama_kota == $lok) { echo "selected='selected'"; } ?>>
                                                                    <?php echo $kel->nama_kota; ?>
                                                                </option>
                                                                <?php }  ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="data_po_detail"></div>

                                <input type="hidden" name="status" id="status" class="form-control">
                                <input type="hidden" name="user" id="user"
                                    value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">


                                <div class="modal-footer center-content-between">
                                    <button class="btn btn-primary" id="simpan" name="simpan" type="submit"><span
                                            class="fa fa-save"></span> Simpan</button>
                                    <button type="button" class="btn btn-success cetak-masuk" hidden="hidden" id="cetak"
                                        data-id="" title="Add Data"><i class="fas fa-print"></i> &nbsp;Cetak </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="modal-masuk"></div>
                </div>
            </div>
            <div class="modal fade" id="modal_po" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body form">
                            <div class="card card-first card-outline">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div id="data-po"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</section><!-- /.modal-content -->
<script type="text/javascript">
$('#date1,#tgl_po,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
var MyTable = $('#list-masuk,#list-po,#table-part').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true
});

function refresh() {
    MyTable = $('#list-masuk,#list-po,#table-part').dataTable();
}

function PO() {
    document.getElementById("fndisc").hidden = false;
    document.getElementById("fndisc2").hidden = true;
    $('#no_po').attr('required', 'required');
}

function nonPO() {
    document.getElementById("fndisc").hidden = true;
    document.getElementById("fndisc2").hidden = false;
}

function showPart(id_po, no_po, kode_sup, supplier, status) {
    //var no_po = document.getElementById("no_po").value;
    //var no_po=document.getElementById("showPart");
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('Part_masuk/showPart'); ?>?id_po=' + id_po,
        data: 'id_po=' + id_po,
        success: function(hasil) {
            //MyTable.fnDestroy();//refresh();
            //$('#data_po').html(hasil);
            $('#data_po_detail').html(hasil);
            $('[name = "id_po"]').val(id_po);
            $('[name = "no_po"]').val(no_po);
            $('[name = "status"]').val(status);
            $('[name = "kode_sup"]').val(kode_sup);
            $('[name = "supplier"]').val(supplier);
            $('#modal_po').modal('hide');
        }
    });
}

function showPo1() {
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('Part_masuk/showPo'); ?>',
        //data: 'id_po=' + id_po,
        success: function(hasil) {
            MyTable.fnDestroy(); //refresh();
            //$('#data_po').html(hasil);
            $('#data_po').html(hasil);
            //$('#modal_po').modal('hide');
        }
    });
}

function showPo() {
    var tgl_po = document.getElementById("tgl_po").value;
    $.get('<?php echo base_url('Part_masuk/showPo'); ?>',
        function(data) {
            // success: function (data) {
            MyTable.fnDestroy(); //refresh();
            $('#data-po').html(data);
        })
}

function selectPart(no_part, nama_part, hrg_awal, stok_awal, stok_jkt, stok_cbt, stok_sby, jumlah, supplier) {

    $('[name = "no_part"]').val(no_part);
    $('[name = "nama_part"]').val(nama_part);
    $('[name = "hrg_awal"]').val(hrg_awal);
    $('[name = "jumlah"]').val(jumlah);
    $('[name = "supplier"]').val(supplier);
    $('[name = "stok_awal"]').val(stok_awal);
    $('[name = "stok_jkt"]').val(stok_jkt);
    $('[name = "stok_cbt"]').val(stok_cbt);
    $('[name = "stok_sby"]').val(stok_sby);


    $('#modal_form').modal('hide');
}

function tampilDetail(dataPo) {
    //var out = jQuery.parseJSON(data);
    var id_po = document.getElementById('id_masuk').value = dataPo;
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('Part_masuk/tampilDetail'); ?>?id_po=' + id_po,
        data: 'id_po=' + id_po,
        success: function(hasil) {
            MyTable.fnDestroy();
            $('#data-masuk').html(hasil);
            refresh();
        }
    });
}
$('#formpartmasuk').submit(function(e) {
    var data = $(this).serialize();
    //var data = $('td').find('input[name="qty_masuk[]"]').val();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Part_masuk/prosesPartmasuk'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                //toastr.error(out.msg);
                $('.msg').html(out.msg);
                refresh();
                effect_msg();
            } else {
                $('.msg').html(out.msg);
                $('.dataPo').html(out.dataPo);
                next(out.dataPo);
                document.getElementById("cetak").hidden = false;
                document.getElementById("tambah").hidden = false;
                document.getElementById("simpan").hidden = true;
                document.getElementById("formPo"); //reset()	
                $('#tgl_po').attr('readonly', 'readonly');
                $('#no_po').attr('readonly', 'readonly');
                $('#keterangan').attr('readonly', 'readonly');
                $('#no_sj_sup').attr('readonly', 'readonly');
                $('#no_inv_sup').attr('readonly', 'readonly');
                $('#supplier').attr('readonly', 'readonly');
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

function next(dataPo) {
    document.getElementById('id_masuk').value = dataPo;
    var d = document.getElementById("cetak");
    d.setAttribute('data-id', dataPo);

    //document.getElementById("cetak").hidden = false;
    //document.getElementById("alamat").readonly = true;
}

function cetakPo(datakode) {}


$(document).on("click", ".cetak-masuk", function() {
    var id = $(this).attr("data-id");
    //var id = document.getElementById('next_proses').value=datakode;
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Part_masuk/cetak'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-masuk').html(data);
            $('#cetak-masuk').modal('show');
        })
})
</script>