<?php

foreach ($dataMechanic as $m) {
}
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header card-dark card-outline">
            <h3 class="card-title" id="card-title" title="Operation" text><i class="ion-outlet ion-lg text-blue"></i>
                &nbsp;
                Data No : <?php echo "<span style='color:green'>" . $m->spk . "</span>"  ?></h3>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 col-form-label">NIK</label>
                    <div class="col-sm-12">
                        <input type="hidden" name="spk" id="spk" value="<?php echo $m->spk; ?>" class="form-control"
                            placeholder="Operation">
                        <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $m->wo_no; ?>"
                            class="form-control" placeholder="Operation">
                        <input type="text" name="nik" id="nik" value="" data-toggle="modal" data-target="#modal-mekanik"
                            class="form-control" placeholder="Nomor Induk Karyawan">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 col-form-label">Nama</label>
                    <div class="col-sm-12 input-group">
                        <input type="text" name="nama" id="nama" value="" class="form-control"
                            placeholder="Nama Karyawan">

                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer right-content-between">
            <button class="btn btn-info" id="simpan-mechanic" onclick="insertMechanic()" type="button"><span
                    class="fa fa-save"></span>
                Save</button>
        </div>

        <div class="col-12">
            <div id="data-daftar-mechanic"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-mekanik">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-gray-light">
            <div class="modal-header">
                <h4 class="modal-title">Data Mekanik</h4>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- <table class="table table-head-fixed text-nowrap" id="table-kons">-->
                    <table class="table table-bordered table-hover dt-responsive nowrap" id="tabel-mekanik">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
            $no = 1;
            $dataMekanik = $this->db->select('nip, nama_depan, nama_belakang')
                        ->get('tbl_hrd_pegawai')
                        ->result();

foreach ($dataMekanik as $s) {
    $nip  = $s->nip;
    $nama = $s->nama_depan . ' ' . $s->nama_belakang;
            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td
                                    onClick="selectM('<?php echo $s->nip; ?>','<?php echo $s->nama_depan. '&nbsp;' .$s->nama_belakang; ?>')">
                                    <?php echo $s->nip; ?></td>
                                <td
                                    onClick="selectM('<?php echo $s->nip; ?>','<?php echo $s->nama_depan. '&nbsp;' .$s->nama_belakang; ?>')">
                                    <?php echo $s->nama_depan. '&nbsp;' .$s->nama_belakang; ?></td>
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
            <!-- /.modal-content -->
        </div>
    </div>
</div>
<script language="javascript">
var MyTable = $('#tabel-mekanik').dataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": false,
    "info": false
});
function selectM(nip, nama) {

    $('[name = "nik"]').val(nip);
    $('[name = "nama"]').val(nama);


    $('#modal-mekanik').modal('hide');
}

</script>