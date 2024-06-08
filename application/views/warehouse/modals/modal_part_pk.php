  <style>
    .table.DataTable {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12px;
    }

    table.dataTable td {
        padding: 5px;
    }
</style>
  <div class="col-12 col-md-12 col-lg-12">
      <div class="modal-header">

          <?php
    if (!empty($dataPk)) {
      foreach ($dataPk as $dataPk) {
      }
    }
    
    ?>
          <p>
          <h4 style="display:block; text-align:center;">Part Keluar Dengan PK</h4>
          </p>
      </div>
  <table width="100%" border="0">
        <tbody>
          <tr>
            <td>No SPK</td>
            <td>: <?php echo $dataPk->id_lapor; ?></td>
            <td>No PK</td>
            <td>: <?php echo $dataPk->id_pk; ?></td>
          </tr>
          <tr>
            <td>Tgl Mulai</td>
            <td>: <?php echo tglIndoSedang($dataPk->tgl_mulai) ?></td>
            <td>Pekerjaan</td>
            <td>: <?php echo $dataPk->jns_pk; ?></td>
          </tr>
          <tr>
            <td>No Body </td>
            <td>: <?php echo $dataPk->no_body ?></td>
            <td>Kategori</td>
            <td>: <?php echo $dataPk->nama_kategori ?></td>
          </tr>
          <tr>
            <td>Type</td>
            <td>: <?php echo $dataPk->type ?></td>
            <td>Keterangan</td>
            <td>: <?php echo $dataPk->ket_pk ?></td>
          </tr>
        </tbody>
      </table>
      <?php 
      $date = date("ym");
      $ci_kons = get_instance();
      $query = "SELECT max(kode_keluar) AS maxKode FROM tbl_wh_part_keluar WHERE kode_keluar LIKE '%$date%'";
      $hasil = $ci_kons->db->query($query)->row_array();
      $noOrder = $hasil['maxKode'];
      $noUrut = (int)substr($noOrder, 4, 5);
      $noUrut++;
      $tahun = substr($date, 0, 2);
      $bulan = substr($date, 2, 2);
      $kd='PPB-';

      $kode_awal  = $tahun.$bulan.sprintf("%04s", $noUrut);
      $kode_keluar  = $kd.$kode_awal;
      ?>
      <form id="formKeluar" name="formKeluar" method="POST">
          <div class="modal-body">
              <div class="form-group row">
                  <label for="No Part" class="col-sm-2 col-form-label">No Part</label>
                  <div class="col-sm-4">
                      <div class="input-group date" id="reservationdate" data-target-input="nearest">
                          <input type="text" name="no_part" id="no_part" class="form-control">
                          <span class="input-group-append">
                              <button type="button" class="btn btn-info" data-toggle="modal"
                                  data-target="#modal_form"><i class="glyphicon glyphicon-plus-sign"><i
                                          class="fa fa-search"></i></button></i>
                          </span>
                      </div>
                  </div>
                  <label for="Nama Part" class="col-sm-2 col-form-label">Nama Part</label>
                  <div class="col-sm-4">
                      <input type="text" name="nama_part" id="nama_part" readonly class="form-control">
                  </div>
              </div>


              <input type="hidden" name="kode_awal" id="kode_awal" class="form-control" value="<?php echo $kode_awal ?>" readonly>
              <input type="hidden" name="kode_keluar" id="kode_keluar" class="form-control" value="<?php echo $kode_keluar ?>" readonly>
              <input type="hidden" name="stok_awal" id="stok_awal" class="form-control" readonly>
              <input type="hidden" name="stok_a" id="stok_a" class="form-control" readonly>
              <input type="hidden" name="stok_p" id="stok_p" class="form-control" readonly>
              <input type="hidden" name="keterangan" id="keterangan" value="">
              <input type="hidden" name="status_part" value="<?php echo $dataPk->status_body; ?>" id="status_part">
              <input type="hidden" name="jumlah" id="jumlah" value="0">
              <input type="hidden" name="id_keluar" id="id_keluar" value="<?php echo $kode_awal ?>" class="form-control">
              <input type="hidden" name="hrg_awal" id="hrg_awal" class="form-control">
              <input type="hidden" name="no_body" id="no_body" value="<?php echo $dataPk->no_body; ?>"
                  class="form-control">
              <input type="hidden" name="jns_pk" id="jns_pk" value="<?php echo $dataPk->jns_pk; ?>"
                  class="form-control">
              <input type="hidden" name="ket_pk" id="ket_pk" value="<?php echo $dataPk->ket_pk; ?>"
                  class="form-control">
              <input type="hidden" name="id_lapor" id="id_lapor" value="<?php echo $dataPk->id_lapor; ?>"
                  class="form-control">
              <input type="hidden" name="id_pk" id="id_pk" value="<?php echo $dataPk->id_pk; ?>" class="form-control">
              <input type="hidden" name="user" id="user" value="<?php echo $this->session->userdata['full_name']; ?>"
                  class="form-control">

                  <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-success cetak-bon" id="btnCetak" hidden=""><span
                          class=" fa fa-print"></span> Cetak</button>
        <button type="submit" id="btnSimpan" class="btn btn-primary" hidden=""><i class="fa fa-save"></i> Simpan</button>
              </div>
              

      </form>
  </div>
  <div id="detail-partpk" style="overflow-y: scroll; height:300px;">
  </div>
  </div>
  <div class="modal fade" id="modal_form" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-body form">
                  <div class="card card-first card-outline">
                      <div class="card-body">
                          <div class="table-responsive">
                              <table width="100%" class="table table-hover nowrap" id="table-part">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>No Part</th>
                                          <th>Nama Part</th>
                                          <th>Stok</th>
                                          <th>Harga</th>
                                          <th>Satuan</th>
                                      </tr>
                                  </thead>
                                  <tbody>
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
  <script language="javascript">
function startCek() {
    var jml = document.formKeluar.jumlah.value;
    var stok = document.formKeluar.stok_awal.value;
    if (stok - jml < 0) {
        document.getElementById("btnSimpan").disabled = true;
    } else {

        document.getElementById("btnSimpan").disabled = false;
    }
}
$(document).ready(function() {
    var id_keluar2 = document.formKeluar.id_keluar.value;
    table = $('#table-part').dataTable({
        "responsive": true,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 10, // Defaults number of rows to display in table
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('PartPk/ajax_list') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false,
        }, ]
    });
});
$(document).ready(function() {
    var table = $('#table-part').DataTable();
    //var id_keluar = document.getElementById('id_keluar').value;
    var kode_keluar = document.formKeluar.kode_keluar.value;
    var no_body = document.formKeluar.no_body.value;
    var id_pk = document.formKeluar.id_pk.value;
    var id_lapor = document.formKeluar.id_lapor.value;
    var status_part = document.formKeluar.status_part.value;
    var user = document.formKeluar.user.value;
    var keterangan = document.getElementById('keterangan').value;

    $('#table-part tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var no_part = data[1];
        var nama_part = data[2];
        var stok = data[3];
        var satuan = data[5];
        var stok_a = data[6];
        var stok_p = data[7];
        var hrg_awal = data[9];
        var std_pakai = data[10];
        $.ajax({
                method: 'POST',
                url: '<?php echo base_url('PartPk/prosesKeluarDetail'); ?>',
                data: "&kode_keluar=" + kode_keluar +
                    "&id_pk=" + id_pk +
                    "&id_lapor=" + id_lapor +
                    "&no_body=" + no_body +
                    "&satuan=" + satuan +
                    "&no_part=" + no_part +
                    "&nama_part=" + nama_part +
                    "&status_part=" + status_part +
                    "&stok=" + stok +
                    "&stok_a=" + stok_a +
                    "&stok_p=" + stok_p +
                    "&hrg_awal=" + hrg_awal +
                    "&user=" + user +
                    "&keterangan=" + keterangan +
                    "&std_pakai=" + std_pakai
            })
            .done(function(data) {
                var out = jQuery.parseJSON(data);

                if (out.status == 'form') {
                    //toastr.error(out.msg);
                    $('.msg').html(out.msg);
                } else {
                    $('.msg').html(out.msg);
                    $('.dataKeluar').html(out.dataKeluar);
                    //document.getElementById("formKeluar").reset();
                    //document.getElementById('id_keluar').value = dataKeluar;
                    next(out.dataKeluar);
                    showDetail(out.dataKeluar);
                    document.getElementById("btnSimpan").disabled = false;
                    document.getElementById("btnSimpan").hidden = false;
                    $('#modal_form').modal('hide');
                }
            })

        //e.preventDefault();
        //showDetail(id_pk);
        //showDetail(id_pk);
    });
});

function next(dataKeluar) {
    document.getElementById('id_keluar').value = dataKeluar;
    //var d = document.getElementById("cetak");
    //d.setAttribute('data-id', dataKeluar);

    //document.getElementById("cetak").hidden = false;
    //document.getElementById("alamat").readonly = true;
}

function selectPart3(id_barang, no_part, nama_part, stok, stok_a, stok_p, hrg_awal) {
    var no_body = document.formKeluar.no_body.value;
    var id_pk = document.formKeluar.id_pk.value;
    var id_lapor = document.formKeluar.id_lapor.value;
    var status_part = document.formKeluar.status_part.value;
    var jumlah = document.formKeluar.jumlah.value;
    var user = document.formKeluar.user.value;
    $.ajax({
        method: 'POST',
        url: '<?php echo base_url('PartPk/prosesKeluar'); ?>',
        data: "&id_pk=" + id_pk +
            "&id_lapor=" + id_lapor +
            "&no_body=" + no_body +
            "&id_barang=" + id_barang +
            "&no_part=" + no_part +
            "&nama_part=" + nama_part +
            "&status_part=" + status_part +
            "&stok=" + stok +
            "&stok_a=" + stok_a +
            "&stok_p=" + stok_p +
            "&hrg_awal=" + hrg_awal +
            "&jumlah=" + jumlah +
            "&user=" + user
    })
    showDetail(id_pk);
    $('#modal_form').modal('hide');
}
  </script>